<?php
namespace App\Http\Controllers\Frontend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Shopping\OrdersData;
use App\Shopping\OrderDetailData;
use App\Shopping\BillingData;
use App\Shopping\ShippingData;
use Session;

use Illuminate\Support\Facades\Storage;
class PaymentController extends Controller
{                              
    public function __construct(){
        parent::__construct();
    }

    public function fail(){
        $data['htmlTitle']=Session::get('Language')==='EN'?'Payment failed...':'付款失败...';
        return view('frontend.payment_fail',$data);
    }

    public function succcess(Request $request){
        // print_R($request->all());exit();
        Storage::disk('local')->append('payment.txt',"\n".serialize($request->all()));
        $payResult=trim(request('result'));
        if($payResult!==''){
            $xml="
                <ProcessResponse>
                    <PxPayUserId>".env('ePayments_UserID','WineCaveNZ')."</PxPayUserId>
                    <PxPayKey>".env('ePayments_MerchantKEY','7c7c573f202de105f9928d18541fcb7aa0464a1852445e615353c6672d0e1e1f')."</PxPayKey>
                    <Response>".request('result')."</Response>
                </ProcessResponse>                        
            ";

            $URL="https://sec.windcave.com/pxaccess/pxpay.aspx";
            $ch=curl_init($URL);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
            curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch);
            curl_close($ch);
            $xml=simplexml_load_string($output);
            // print_R($xml);exit();
            if(isset($xml->TxnId)){
                $logExist=DB::table('payment_log')->where('OrderId',$xml->TxnId)->take(1)->first();
                if(!$logExist){
                    DB::connection()->getPdo()->beginTransaction();
                    $logInsert=false;
                    $updateOrder=false;

                    $logInsert=DB::table('payment_log')->insert(array(
                        'PaymentType'=>'Card',
                        'CardNumber'=>$xml->CardNumber,
                        'CardName'=>$xml->CardName,
                        'CardHolder'=>$xml->CardHolderName,
                        'ClientIP'=>$xml->ClientInfo,
                        'OrderId'=>$xml->TxnId,
                        'CreateTime'=>date('Y-m-d H:i:s')
                    ));

                    $updateOrder=OrdersData::where('OrderId',$xml->TxnId)->update(array(
                        'Status'=>0,
                        'PayDate'=>date('Y-m-d'),
                        'CardHolder'=>$xml->CardHolderName,
                        'CardName'=>$xml->CardName,
                        'CardNumber'=>$xml->CardNumber,
                        'updated_at'=>date('Y-m-d H:i:s')
                    ));

                    if($logInsert&&$updateOrder){
                        DB::connection()->getPdo()->commit();
                        $data['htmlTitle']=Session::get('Language')==='EN'?'Payment completed!':'付款完成';
                        $data['warning_msg']='';
                    }
                    else{
                        DB::connection()->getPdo()->rollBack();
                        $data['htmlTitle']=Session::get('Language')==='EN'?'Payment completed!':'付款完成';
                        $data['warning_msg']=Session::get('Language')==='EN'?'Something failed, reload page please':'发生未知错误，请重新整理网页';
                    }
                    return view('frontend.payment_success',$data);
                }
            }
        }
        return redirect('/');
    }

    public function notify_url(Request $request){
        Storage::disk('local')->append('payment.txt',"\n".serialize($request->all()));
        // a:11:{s:8:"currency";s:3:"NZD";s:18:"extra_common_param";s:9:"WECHATPAY";s:11:"merchant_id";s:7:"1232838";s:17:"merchant_trade_no";s:12:"202005110002";s:9:"notify_id";s:5:"51427";s:12:"payment_time";s:19:"2020-05-11 12:15:06";s:6:"status";s:7:"SUCCESS";s:12:"total_amount";s:4:"6.22";s:15:"weixin_trade_no";s:28:"4200000564202005118260930305";s:9:"sign_type";s:3:"MD5";s:4:"sign";s:32:"58182342b030f8bc9cd7ce2e8add7846";}
        $notification_payload = $request->all();
        $notification_payload = json_decode($notification_payload,true);
        $currency = $notification_payload['currency'];
        $extra_common_param = trim($notification_payload['extra_common_param']);
        $merchant_id = $notification_payload['merchant_id'];
        $merchant_trade_no = $notification_payload['merchant_trade_no'];
        $notify_id = $notification_payload['notify_id'];
        $payment_time = $notification_payload['payment_time'];
        $status = $notification_payload['status'];
        $total_amount = $notification_payload['total_amount'];
        $third_trade_no = '';
        if($paymethod==='WECHATPAY'){
            $third_trade_no = $notification_payload['weixin_trade_no'];
        }
        elseif($paymethod==='ALIPAY'){
            $third_trade_no = $notification_payload['alipay_trade_no'];
        }
        $sign_type = $notification_payload['sign_type'];
        $sign = $notification_payload['sign'];

        $OrdersInfo = OrdersData::where('OrderId',$merchant_trade_no)->take(1)->first();
        if($OrdersInfo){
            // alipay_trade_no=20178888&currency=NZD&extra_common_param= ALIPAY&merchant_id=10088&merchant_trade_no=88888888&notify_id=1000000088&payment_time=2017-11-01 18:08:50&status=SUCCESS&total_amount=15
            $presign_String = "currency={$currency}&extra_common_param={$extra_common_param}&merchant_id={$merchant_id}&merchant_trade_no={$merchant_trade_no}&notify_id={$notify_id}&payment_time={$payment_time}&status={$status}&total_amount={$total_amount}";
            if($paymethod==='WECHATPAY'){
                $presign_String = $presign_String."&weixin_trade_no=";
                $presign_String .= $third_trade_no;
            }
            elseif($paymethod==='ALIPAY'){
                $presign_String = "alipay_trade_no=".$third_trade_no.$presign_String;
            }
            $verify_sign = md5($presign_String);
            if($sign===$verify_sign && $status==='SUCCESS'){
                // confirm the source
                $existLog = DB::table('payment_log2')->where('OrderId',$merchant_trade_no)->take(1)->first();
                if(!$existLog){
                    OrdersData::where('OrderId',$merchant_trade_no)->update(array('Status'=>0,'updated_at'=>date('Y-m-d H:i:s')));
                    DB::table('payment_log2')->insert(array(
                        'Paymethod'=>$paymethod,
                        'OrderId'=>$merchant_trade_no,
                        'NotifyId'=>$notify_id,
                        'PaymentTime'=>$payment_time,
                        'ThirdTradeNo'=>$third_trade_no
                    ));
                }
            }
        }
    }

    public function QRCode_display(){
        $attractpay_QRCode = Session::get('attractpay_QRCode','');        
        $attractpay_QRCodePic = Session::get('attractpay_QRCodePic','');

        if($attractpay_QRCode!=='' && $attractpay_QRCodePic!==''){
            $data['QRCodePic'] = $attractpay_QRCodePic;
            $data['htmlTitle']=Session::get('Language')==='EN'?'Your QRCode':'您的付款码';
            // 清除Session
            Session::forget('attractpay_QRCode');
            Session::forget('attractpay_QRCodePic');
            return view('frontend.attractpay.qrcode_display',$data);
        }
        return redirect('/');
    }
}