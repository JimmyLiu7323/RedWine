<?php
namespace App\Http\Controllers\Frontend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\ExhibitionData;
use App\ExhibitionAttendeeData;;
use App\Members\MembersData;
use Session;
use Widget_Helper;
use Redirect;

class ExhibitionController extends Controller
{                              
    public function __construct(){
        parent::__construct();
    }

    public function exhibitions(Request $request){
        $data['impactImage'] = '/images/DefaultImages/DefaultEvent.jpg';
        $impactImage = DB::table('template_static_data')->where('DataKey','events_image')->take(1)->first();
        if($impactImage){
            $impactImage->Data = str_replace(array("\\","/"),DIRECTORY_SEPARATOR,$impactImage->Data);
            if(file_exists(public_path($impactImage->Data))){
                $data['impactImage'] = $impactImage->Data;
            }
        }
        $data['impactImage'] = str_replace(array("\\","/"),DIRECTORY_SEPARATOR,$data['impactImage']);

        $exhibitions = ExhibitionData::where(array(
            array('EventType','=',1),
            array('Status','=',1),
            array('EventOnDateTime_Start','<=',date('Y-m-d H:i:s')),
            array('EventOnDateTime_End','>=',date('Y-m-d H:i:s'))
        ))->orderBy('EventDateTime_Start','ASC')->orderBy('EventDateTime_End','ASC')->paginate(10);
        $data['exhibitions'] = $exhibitions;

        $data['htmlTitle']=Session::get('Language')==='EN'?'Events':'活动报名';

        $breadcrumbs=array(
            array('url'=>'/exhibitions','name'=>Session::get('Language')==='EN'?'Events':'活动报名'),
        );
        $data['breadcrumbs'] = $breadcrumbs;
        return view('frontend.exhibitions.list',$data);
    }

    public function visits(){
        $data['impactImage'] = '/images/DefaultImages/DefaultVisit.jpg';
        $impactImage = DB::table('template_static_data')->where('DataKey','visits_image')->take(1)->first();
        if($impactImage){
            $impactImage->Data = str_replace(array("\\","/"),DIRECTORY_SEPARATOR,$impactImage->Data);
            if(file_exists(public_path($impactImage->Data))){
                $data['impactImage'] = $impactImage->Data;
            }
        }        
        $data['impactImage'] = str_replace(array("\\","/"),DIRECTORY_SEPARATOR,$data['impactImage']);

        $exhibitions = ExhibitionData::where(array(
            array('EventType','=',2),
            array('Status','=',1),
            array('EventOnDateTime_Start','<=',date('Y-m-d H:i:s')),
            array('EventOnDateTime_End','>=',date('Y-m-d H:i:s'))
        ))->orderBy('EventDateTime_Start','ASC')->orderBy('EventDateTime_End','ASC')->paginate(10);
        $data['exhibitions'] = $exhibitions;

        $data['htmlTitle']=Session::get('Language')==='EN'?'Visits':'参观Wine Cave';

        $breadcrumbs=array(
            array('url'=>'/exhibitions/visits','name'=>Session::get('Language')==='EN'?'Visits':'参观Wine Cave'),
        );
        $data['breadcrumbs'] = $breadcrumbs;
        return view('frontend.exhibitions.list',$data);
    }

    public function detail($EventId){
        $detailInfo = ExhibitionData::where(array(
            array('EventId','=',$EventId),
            array('Status','=',1),
            array('EventOnDateTime_Start','<=',date('Y-m-d H:i:s')),
            array('EventOnDateTime_End','>=',date('Y-m-d H:i:s'))
        ))->take(1)->first();
        if($detailInfo){
            $data['htmlTitle']=Session::get('Language')==='EN'&&trim($detailInfo->EventNameEn)!==''?$detailInfo->EventNameEn:$detailInfo->EventName;

            if(Session::get('Language')==='EN'&&trim($detailInfo->EventNameEn)!==''){
                $detailInfo->EventName = $detailInfo->EventNameEn;
            }
            if(Session::get('Language')==='EN'&&trim($detailInfo->BriefDescEn)!==''){
                $detailInfo->BriefDesc = $detailInfo->BriefDescEn;
            }
            if(Session::get('Language')==='EN'&&trim($detailInfo->DescriptionEn)!==''){
                $detailInfo->Description = $detailInfo->DescriptionEn;
            }
            $detailInfo->ImpactImage = str_replace(array("\\","/"),DIRECTORY_SEPARATOR,$detailInfo->ImpactImage);
            $data['detailInfo'] = $detailInfo;

            if(intval($detailInfo->EventType)===1){
                $breadcrumbs=array(
                    array('url'=>'/exhibitions','name'=>Session::get('Language')==='EN'?'Events':'活动报名')
                );
            }
            else{
                $breadcrumbs=array(
                    array('url'=>'/exhibitions/visits','name'=>Session::get('Language')==='EN'?'Visits':'参观Wine Cave')
                );
            }
            array_push($breadcrumbs,array('url'=>'/exhibition/'.$EventId,'name'=>$detailInfo->EventName));
            $data['breadcrumbs'] = $breadcrumbs;
            return view('frontend.exhibitions.detail',$data);     
        }
        return redirect('/exhibitions');
    }

    public function booking($EventId,Request $request){
        // Array ( [Qty] => 1 [_token] => E30Mm8jr9HfO8b7daHF7iNCkWH6WW9wrDyqTET3c [Paymethod] => Card )
        $MemberId = Session::get('MemberId');
        $MemberInfo = MembersData::where('MemberId',$MemberId)->take(1)->first();
        $Qty = intval(request('Qty'));
        $Paymethod = request('Paymethod');

        if($MemberId && $MemberInfo && $Qty>0 && in_array($Paymethod,array('Card','QRCode_Alipay','QRCode_WechatPay'))){
            $EventInfo = ExhibitionData::where(array(
                array('EventId','=',$EventId),
                array('Status','=',1),
                array('EventOnDateTime_Start','<=',date('Y-m-d H:i:s')),
                array('EventOnDateTime_End','>=',date('Y-m-d H:i:s'))
            ))->take(1)->first();
            if($EventInfo){
                $AttendeesCount = ExhibitionAttendeeData::where('EventId',$EventId)->count();
                if(intval($EventInfo->AllCapacity) - intval($AttendeesCount) > 0 && intval($EventInfo->AllCapacity) - intval($AttendeesCount) > $Qty){

                }
                else{
                    Session::flash('system_message_flag',true);
                    Session::flash('system_message_status','warning');
                    if(Session::get('Language')==='EN'){
                        Session::flash('system_message','Sorry, this event cannot afford too much people.');
                    }
                    else{
                        Session::flash('system_message','很抱歉，此活动人数已满。');
                    }
                    return redirect('/exhibition/'.$EventId);
                }
            }
            else{
                Session::flash('system_message_flag',true);
                Session::flash('system_message_status','warning');
                if(Session::get('Language')==='EN'){
                    Session::flash('system_message','Sorry, cannot find this event.');
                }
                else{
                    Session::flash('system_message','很抱歉，查无该活动。');
                }
                return redirect('/exhibitions');
            }

            try{
                $AttendeeId = Widget_Helper::createID();
                DB::connection()->getPdo()->beginTransaction();

                // Payment API
                if(!$EventInfo->Free){
                    // INSERT into attendee data
                    ExhibitionAttendeeData::insert(array(
                        'AttendeeId'=>$AttendeeId,
                        'MemberId'=>Session::get('MemberId'),
                        'EventId'=>$EventId,
                        'Quantity'=>$Qty,
                        'Paymethod'=>$Paymethod,
                        'Status'=>-1,
                        'created_at'=>date('Y-m-d H:i:s'),
                        'updated_at'=>date('Y-m-d H:i:s'),
                    ));                    
                    if(trim(request('Paymethod')==='Card')){
                        $GenerateRequestXML="
                            <GenerateRequest>
                                <PxPayUserId>".env('ePayments_UserID','WineCaveNZ')."</PxPayUserId>
                                <PxPayKey>".env('ePayments_MerchantKEY','7c7c573f202de105f9928d18541fcb7aa0464a1852445e615353c6672d0e1e1f')."</PxPayKey>
                                <TxnType>Purchase</TxnType>
                                <AmountInput>".number_format((float)$EventInfo->Price, 2, '.', '')."</AmountInput>
                                <CurrencyInput>NZD</CurrencyInput>
                                <MerchantReference>Payment about Wine Cave</MerchantReference>
                                <TxnData1>".$MemberInfo->FirstName." ".$MemberInfo->LastName."</TxnData1>
                                <TxnData2></TxnData2>
                                <TxnData3>".$MemberInfo->Email."</TxnData3>
                                <ForcePaymentMethod>".trim(request('Paymethod'))."</ForcePaymentMethod>
                                <EmailAddress>".$MemberInfo->Email."</EmailAddress>
                                <TxnId>".$AttendeeId."</TxnId>
                                <UrlSuccess>".url('/')."/exhibition/payment/success</UrlSuccess>
                                <UrlFail>".url('/')."/exhibition/payment/fail</UrlFail>
                                <UrlCallback>".url('/')."/exhibition/payment/notify_url</UrlCallback>
                            </GenerateRequest>                        
                        ";

                        $URL="https://sec.windcave.com/pxaccess/pxpay.aspx";
                        $ch=curl_init($URL);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
                        curl_setopt($ch, CURLOPT_POSTFIELDS, "$GenerateRequestXML");
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        $output = curl_exec($ch);
                        curl_close($ch);

                        $xml=simplexml_load_string($output);
                        if($xml===false){
                            DB::connection()->getPdo()->rollBack();
                            Session::flash('system_message_flag',true);
                            Session::flash('system_message_status','warning');
                            Session::flash('system_message','Oops...Try again.');
                            return redirect('/exhibition/'.$EventId);
                        }
                        else{
                            if(isset($xml->URI)){
                                DB::connection()->getPdo()->commit();
                                return Redirect::to($xml->URI);
                            }
                            else{
                                DB::connection()->getPdo()->rollBack();
                                Session::flash('system_message_flag',true);
                                Session::flash('system_message_status','warning');
                                if(isset($xml->ResponseText)&&trim($xml->ResponseText)==='TxnId/TxnRef duplicate'){
                                    if(Session::get('Language')==='EN'){
                                        Session::flash('system_message','Payment serial number duplicates');
                                    }
                                    else{
                                        Session::flash('system_message','付款序号已重複');
                                    }
                                }
                                else{                            
                                    Session::flash('system_message','Oops...Try again.');
                                }
                                return redirect('/exhibition/'.$EventId);
                            }
                        }
                    }
                    elseif(trim(request('Paymethod')==='QRCode_Alipay') || trim(request('Paymethod'))==='QRCode_WechatPay'){
                        $store_id = env('Attractpay_store',216);
                        $payment_channel = str_replace("QRCode_","",trim(request('Paymethod')));
                        $merchant_trade_no = $AttendeeId;
                        $total_amount = (String)number_format((float)$EventInfo->Price, 2, '.', '');
                        $create_time = date('Y-m-d G:i:s');
                        $currency = "NZD";
                        $notify_url = url('/')."/exhibition/payment/notify_url";
                        $extra_param = "Payment in ShopWineCave";

                        $presign_String = "currency={$currency}&extra_param={$extra_param}&merchant_trade_no={$merchant_trade_no}&notify_url={$notify_url}&payment_channel={$payment_channel}&store_id={$store_id}&total_amount={$total_amount}&authentication_code=";
                        $presign_String.=env("Attractpay_auth_code","e053c97184183c66e74dc0e2f640bec9");
                    
                        $sign = md5($presign_String);

                        $url = "http://pay.attractpay.co.nz/online/payment";
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(
                            array(
                                "store_id"=>$store_id,
                                "payment_channel"=>$payment_channel,
                                "merchant_trade_no"=>$merchant_trade_no,
                                "total_amount"=>$total_amount,
                                // "create_time"=>$create_time,
                                "currency"=>$currency,
                                "notify_url"=>$notify_url,
                                "extra_param"=>$extra_param,
                                "sign"=>$sign
                            )
                        ));
                        $output = curl_exec($ch); 
                        curl_close($ch);
                        $output = json_decode($output,true);
                        if(isset($output['code'])){
                            if(intval($output['code'])===206){
                                ExhibitionAttendeeData::where('AttendeeId',$AttendeeId)->update(array(
                                    'QRCode'=>$output['dataObject']['qr_code'],
                                    'QRCodePic'=>$output['dataObject']['pic'],
                                    'PaymentTransNo'=>$output['dataObject']['trans_no']
                                ));
                                DB::connection()->getPdo()->commit();
                                $data['QRCode']=$output['dataObject']['qr_code'];
                                $data['QRCodePic']=$output['dataObject']['pic'];
                                Session::put('attractpay_QRCode',$output['dataObject']['qr_code']);
                                Session::put('attractpay_QRCodePic',$output['dataObject']['pic']);
                                return redirect('/pay/QRCode_display');
                            }
                            else{
                                DB::connection()->getPdo()->rollBack();
                                Session::flash('system_message_flag',true);
                                Session::flash('system_message_status','error');
                                if(Session::get('Language')==='EN'){
                                    Session::flash('system_message','Something failed in payment...');
                                }
                                else{
                                    Session::flash('system_message','处理付款时发生错误');
                                }
                                return redirect('/exhibition/'.$EventId);
                            }
                        }
                        else{
                            DB::connection()->getPdo()->rollBack();
                            Session::flash('system_message_flag',true);
                            Session::flash('system_message_status','error');
                            if(Session::get('Language')==='EN'){
                                Session::flash('system_message','Something failed in payment...');
                            }
                            else{
                                Session::flash('system_message','处理付款时发生错误');
                            }
                            return redirect('/exhibition/'.$EventId);
                        }
                    }
                }
                else{
                    $AttendeeId = Widget_Helper::createID();
                    ExhibitionAttendeeData::insert(array(
                        'AttendeeId'=>$AttendeeId,
                        'MemberId'=>Session::get('MemberId'),
                        'EventId'=>$EventId,
                        'Quantity'=>$Qty,
                        'Paymethod'=>$Paymethod,
                        'Status'=>0,
                        'created_at'=>date('Y-m-d H:i:s'),
                        'updated_at'=>date('Y-m-d H:i:s'),
                    ));
                    // 免费活动，报名完成直接寄信        
                }
            }
            catch (\PDOException $e) {
                DB::connection()->getPdo()->rollBack();
                // print_R($e->getMessage());exit();
                Session::flash('system_message_flag',true);
                Session::flash('system_message_status','warning');
                Session::flash('system_message','Oops...Try again.');
                return redirect('/exhibition/'.$EventId);
            }            
        }
    }

    public function payment_succcess(Request $request){
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

                    $updateOrder=ExhibitionAttendeeData::where('AttendeeId',$xml->TxnId)->update(array(
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
}