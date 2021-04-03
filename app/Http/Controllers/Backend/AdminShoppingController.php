<?php
namespace App\Http\Controllers\Backend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\System\Freight_GSTData;
use App\Shopping\OrdersData;
use App\Shopping\OrderDetailData;
use App\Shopping\BillingData;
use App\Shopping\ShippingData;
use App\Products\CasesData;
use App\Products\SalesMixData;
use App\Products\WinesData;
use App\GiftsData;

use Validator;
use Session;
use Widget_Helper;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
class AdminShoppingController extends Controller
{
    public function __construct(){

    }

    public function parameters(Request $request){
        $data['currentPage']='shopping';
        $data['header_title']='Parameters in shopping';
        return view('admin.shopping.parameters',$data);
    }

    public function edit_parameter(Request $request){
        $key=trim(request('key'));
        $data['currentPage']='shopping';
        if($key==='freight'){
            $data['header_title']='Freight & GST';
            $countries=DB::table('countries')->orderBy('CountryId','ASC')->get();
            $data['countries']=$countries;
            return view('admin.shopping.freights',$data);
        }
        elseif($key==='discount'){
            if($request->isMethod('post')){
                $validator_rule_array=array(
                    'MemberDiscount'=>'required|between:0,999999.999999'
                );
                $build_validator=Validator::make($request->all(),$validator_rule_array);
                if($build_validator->fails()){
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }                
                $MemberDiscount = floatval(request('MemberDiscount'));
                Widget_Helper::setEnvironmentValue(array('MemberDiscount'=>$MemberDiscount));
            }
            else{
                $data['header_title']='Discount with member';
                $data['MemberDiscount']=env('MemberDiscount',90);
                return view('admin.shopping.edit_member_discount',$data);
            }
        }
        return redirect('/admin/shopping/parameters');
    }

    public function edit_freight(Request $request){
        $country=trim(request('country'));
        if($country!==""){
            if($request->isMethod('post')){
                // Array ( [GST] => 10 [Freight_home] => 60 [Store] => Array ( [0] => 台灣 ) [StoreMap] => Array ( [0] => 新北市 ) [StoreFreight] => Array ( [0] => 30 ) [_token] => kHhvf8cHm9Kew2S6EXhB1aCHGhFcHMg9TLHQ7hVu )
                $validator_rule_array=array(
                    'GST'=>'required|between:0,999999.999999',
                    'Freight_home'=>'required|between:0,999999.999999',
                    'Region'=>'nullable|array',
                    'Store'=>'nullable|array',
                    'StoreMap'=>'nullable|array',
                    'StoreFreight'=>'nullable|array',
                    'StoreFreight.*'=>'nullable|between:0,999999.999999'
                );
                $build_validator=Validator::make($request->all(),$validator_rule_array);
                if($build_validator->fails()){
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }

                $GST=request('GST');
                $Freight_home=request('Freight_home');
                $Freight_store=array();
                $Regions=request('Region',array());
                $Store=request('Store',array());
                $StoreMap=request('StoreMap',array());
                $StoreFreight=request('StoreFreight',array());
                $MapURL=request('MapURL',array());
                foreach($Regions as $idx=>$Region){
                    if(isset($Store[$idx])&&isset($StoreMap[$idx])&&isset($StoreFreight[$idx])&&isset($MapURL[$idx]))
                        array_push($Freight_store,array($Region,$Store[$idx],$StoreMap[$idx],$StoreFreight[$idx],$MapURL[$idx]));
                }

                Freight_GSTData::updateOrCreate(
                    array('CountryId'=>$country),
                    array(
                        'GST'=>$GST,
                        'Freight_home'=>$Freight_home,
                        'Freight_store'=>json_encode($Freight_store),
                        'updated_at'=>date('Y-m-d H:i:s')
                    )
                );
            }
            else{
                $GST=0;
                $Freight_home=(double)0;
                $Freight_store=array();
                $saveData=Freight_GSTData::where('CountryId',$country)->take(1)->first();
                if($saveData){
                    $GST=$saveData->GST;
                    $Freight_home=(double)$saveData->Freight_home;
                    $Freight_store=array();
                    if(trim($saveData->Freight_store)!=="")
                        $Freight_store=json_decode($saveData->Freight_store,true);
                }

                $data['GST']=$GST;
                $data['Freight_home']=$Freight_home;
                $data['Freight_store']=$Freight_store;
                $data['currentPage']='shopping';
                $data['header_title']='Edit freight & GST';
                return view('admin.shopping.edit_freight',$data);
            }
        }
        return redirect('/admin/shopping/parameter/edit?key=freight');
    }

    public function orders(Request $request){
        $whereConditions=array();
        $searchId = "";
        if($request->has('order-id')){
            if(trim(request('order-id'))!==''){
                $searchId = request('order-id');
                array_push($whereConditions,array('orders.OrderId','=',request('order-id')));
            }
        }
        $orders = OrdersData::join('order_shipping','orders.OrderId','=','order_shipping.OrderId')->where(function($query) use ($whereConditions){
            if(count($whereConditions)>0){
                foreach($whereConditions as $condition){
                    $query->where($condition[0],$condition[1],$condition[2]);
                }
            }
        })->orderBy('OrderId','DESC')->orderBy('PayDate','DESC')->orderBy('Status','ASC')->select(array(
            'orders.*',
            'order_shipping.FirstName',
            'order_shipping.MiddleName',
            'order_shipping.LastName',
        ))->paginate(10);
        $data['searchId'] = $searchId;
        $data['orders'] = $orders;
        $data['currentPage']='shopping';
        $data['header_title']='Orders';

        $page = 1;
        if(request('page')){
            $page = intval(request('page'));
        }
        $data['page'] = $page;
        return view('admin.shopping.orders',$data);
    }

    public function maintain_order(Request $request){
        $id = request('id');
        $orderInfo = OrdersData::join('order_shipping as os','orders.OrderId','=','os.OrderId')->join('order_billing as ob','orders.OrderId','=','ob.OrderId')->leftJoin('members as m','orders.MemberId','=','m.MemberId')->where('orders.OrderId','=',$id)->select(
            array(
                'orders.*',
                'os.Type','os.Country','os.FirstName as osFirst','os.MiddleName as osMiddle','os.LastName as osLast','os.Company as osCompany','os.Address as osAddress','os.Address2 as osAddress2','os.City as osCity','os.Region as osRegion','os.PostCode as osPostCode','os.Email as osEmail','os.Telephone as osTelephone','os.Country as osCountry',
                'ob.Country','ob.FirstName as obFirst','ob.MiddleName as obMiddle','ob.LastName as obLast','ob.Company as obCompany','ob.Address as obAddress','ob.Address2 as obAddress2','ob.City as obCity','ob.Region as obRegion','ob.PostCode as obPostCode','ob.Email as obEmail','ob.Telephone as obTelephone','ob.Country as obCountry',
                'm.*'
            )
        )->take(1)->first();
        if($orderInfo){
            $data['orderInfo'] = $orderInfo;

            $orderDetails = array();
            $orderDetailRows = OrderDetailData::where('OrderId','=',$id)->get();
            foreach($orderDetailRows as $row){
                if($row->ProductType==='wine'){
                    if($wine = WinesData::where('WineId',$row->Product)->take(1)->first()){
                        array_push($orderDetails,array(
                            'Product'=>$wine->Name,
                            'Type'=>'wine',
                            'Price'=>$row->Price,
                            'Quantity'=>$row->Quantity,
                            'Subtotal'=>$row->Subtotal
                        ));
                    }
                }
                elseif($row->ProductType==='case'){
                    if($case = SalesMixData::where('MixId',$row->Product)->take(1)->first()){
                        array_push($orderDetails,array(
                            'Product'=>$case->MixName,
                            'Type'=>'case',
                            'Price'=>$row->Price,
                            'Quantity'=>$row->Quantity,
                            'Subtotal'=>$row->Subtotal                      
                        ));
                    }
                }
                elseif($row->ProductType==='gift'){
                    if($gift = GiftsData::where(array(
                        array('GiftId','=',$row->Product),
                    ))->take(1)->first()){
                        array_push($orderDetails,array(
                            'Product'=>$gift->Name,
                            'Type'=>'gift',
                            'Price'=>$row->Price,
                            'Quantity'=>$row->Quantity,
                            'Subtotal'=>$row->Subtotal
                        ));
                    }
                }
            }

            $data['orderDetails'] = $orderDetails;

            if($request->isMethod('post')){
                $Status = intval(request('Status'));
                OrdersData::where('OrderId',$id)->update(array(
                    'Status'=>$Status,
                    'updated_at'=>date('Y-m-d H:i:s')
                ));

                if($Status===1){
                    // Send shipping invoice
                    $shippingHtml = '<!DOCTYPE html>
                    <html>
                    <head>
                        <title></title>
                    </head>
                    <body>
                    <p style="font-weight:bold;font-size:20px;color:rgb(118,114,52)">INVOICE (#'.$id.')</p>';

                    $shippingHtml .= '<div style="position:relative">
                        <img src="https://www.shopwinecave.nz/images/logo.png" style="position:absolute;width:300px;right:100px;float:right" />
                        <h1 style="color:rgb(178,86,0);font-size:40px">The Wine Cave NZ</h1>
                        <p style="color:rgb(118,114,52);font-size:20px;margin:0 0 10px;">104 Carlton Gore Road, Newmarket</p>
                        <p style="color:rgb(118,114,52);font-size:20px;margin:0;">Auckland, 1023</p>';
                    $shippingHtml .= '<p style="color:rgb(118,114,52);font-weight:bold;font-size:20px">DATE: (';
                    $shippingHtml .= date('d/m/y');
                    $shippingHtml .= ')</p>';
                    $shippingHtml .= '<h2 style="color:rgb(178,86,0);font-size:30px;margin-bottom:0">BILL TO</h2><p style="margin:0;color:rgb(118,114,52)">(';
                    $shippingHtml .= $orderInfo->osFirst;
                    if(trim($orderInfo->osMiddle)!==""){
                        $shippingHtml .= " ";
                        $shippingHtml .= $orderInfo->osMiddle;
                    }
                    $shippingHtml .= " ";
                    $shippingHtml .= $orderInfo->osLast;
                    if(trim($orderInfo->osCompany)!==""){
                        $shippingHtml .= " / ";
                        $shippingHtml .= $orderInfo->osCompany;
                    }
                    $shippingHtml .= ')</p>';

                    $shippingHtml .= '<p style="margin-top:0;color:rgb(118,114,52)">(';

                    $shippingHtml .= $orderInfo->osAddress;
                    $shippingHtml .= "<br />";
                    $shippingHtml .= $orderInfo->osCity;
                    $shippingHtml .= ", ";
                    $shippingHtml .= $orderInfo->osRegion;
                    $shippingHtml .= $orderInfo->PostCode;
                    $shippingHtml .= ")</p>";

                    $shippingHtml .= '</div>';
                    $shippingHtml .= '<div style="margin-top:30px">
                        <table style="border-collapse:collapse;width:100%">
                            <tr>
                                <th style="text-align:left;color:rgb(118,114,52);font-size:20px">Details</th>
                                <th style="text-align:right;color:rgb(118,114,52);font-size:20px">AMOUNT</th>
                            </tr>';

                    $Subtotal = 0;
                    foreach($orderDetails as $product){
                        $Subtotal += $product['Subtotal'];

                        $shippingHtml .= '<tr>';

                        $shippingHtml .= '<td style="text-align:left;color:rgb(118,114,52);font-size:17px;border-bottom:2px solid rgb(196,194,82);padding:15px 0 0">';
                        $shippingHtml .= $product['Product'];
                        $shippingHtml .= '</td>';

                        $shippingHtml .= '<td style="text-align:right;color:rgb(118,114,52);font-size:17px;border-bottom:2px solid rgb(196,194,82);padding:15px 0 0">';
                        $shippingHtml .= $product['Subtotal'];
                        $shippingHtml .= '</td>';

                        $shippingHtml .= '</tr>';
                    }

                    $shippingHtml .= '<tr><td style="text-align:right;color:rgb(118,114,52);font-size:17px;padding:15px 10px 0 0">SUBTOTAL</td><td style="text-align:right;color:rgb(118,114,52);font-size:17px;border-bottom:2px solid rgb(196,194,82);padding:15px 0 0">';
                    $shippingHtml .= number_format($Subtotal,2);
                    $shippingHtml .= '</td></tr>';

                    $shippingHtml .= '<tr><td style="text-align:right;color:rgb(118,114,52);font-size:17px;padding:15px 10px 0 0">GST</td><td style="text-align:right;color:rgb(118,114,52);font-size:17px;border-bottom:2px solid rgb(196,194,82);padding:15px 0 0">';
                    $shippingHtml .= number_format($orderInfo->GST,2);
                    $shippingHtml .= '</td></tr>';

                    $shippingHtml .= '<tr><td style="text-align:right;color:rgb(118,114,52);font-size:17px;padding:15px 10px 0 0">DELIVERY</td><td style="text-align:right;color:rgb(118,114,52);font-size:17px;border-bottom:2px solid rgb(196,194,82);padding:15px 0 0">';
                    $shippingHtml .= number_format($orderInfo->DeliveryCost,2);
                    $shippingHtml .= '</td></tr>';

                    $shippingHtml .= '<tr><td style="text-align:right;color:rgb(118,114,52);font-size:17px;padding:15px 10px 0 0">SUBTOTAL</td><td style="text-align:right;color:rgb(118,114,52);font-size:17px;border-bottom:2px solid rgb(196,194,82);padding:15px 0 0">';
                    $shippingHtml .= number_format($orderInfo->Total,2);
                    $shippingHtml .= '</td></tr>';

                    $shippingHtml .= '
                        </table>
                    </div>
                    <div style="margin-top:10px">
                        <p style="color:rgb(118,114,52);font-size:17px">YOUR ORDER HAS BEEN DISPATCHED.</p>
                        <p style="color:rgb(118,114,52);font-size:17px">If you have any questions concerning this invoice, use the following contact information:</p>
                        <p style="color:rgb(118,114,52);font-size:17px">Email: <a style="color:rgb(118,114,52);text-decoration:none" href="mailto:accounts@winecave.nz">accounts@winecave.nz</a></p>
                        <p style="color:rgb(118,114,52);font-size:17px;font-weight:bold">THANK YOU FOR YOUR BUSINESS!</p>
                    </div>';
                    $shippingHtml .= '</body></html>';
                    
                    // include(public_path("/plugins/TCPDF/tcpdf.php"));
                    // create new PDF document
                    // $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                    // set document information
                    // $pdf->SetCreator("Wine Cave from New Zealand");
                    // $pdf->SetAuthor("Wine Cave from New Zealand");
                    // $pdf->SetSubject('Invoice of Order#'.$id);
                    // $pdf->SetSubject('Invoice of Order#'.$id);
                    // $pdf->SetKeywords('Invoice');
          
                    // set default header data
                    // $pdf->setPrintHeader(false);

                    // set default monospaced font
                    // $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
          
                    // set margins
                    // $pdf->SetMargins(10, 10, 10);
                    // $pdf->SetHeaderMargin(0);
                    // $pdf->SetFooterMargin(10);
                    // // set auto page breaks
                    // $pdf->SetAutoPageBreak(TRUE, 10);

                    // // add a page
                    // $pdf->AddPage();
                    // // output the HTML content
                    // $pdf->writeHTML($shippingHtml, true, false, true, false, '');
                    // // reset pointer to the last page
                    // $pdf->lastPage();
                    // // ---------------------------------------------------------
                    // //Close and output PDF document
                    // $pdf->Output(public_path("/shipping_invoice/".$id.".pdf"),'F');
                    // exit();

                    //Server settings
                    $mail = new PHPMailer(true);
                    $mail->SMTPDebug = 0;
                    $mail->isSMTP();
                    $mail->Host='mail.shopwinecave.nz';
                    $mail->SMTPAuth=true;
                    $mail->Username='service@shopwinecave.nz';
                    $mail->Password='A1qazxsw2!';
                    $mail->SMTPSecure='ssl';
                    $mail->Port=465;
                    $mail->CharSet="UTF-8"; 
                    //Recipients
                    $mail->setFrom('service@shopwinecave.nz','Wine Cave');
                    $mail->addAddress($orderInfo->obEmail,$orderInfo->obFirst.' '.$orderInfo->obLast);

                    $mail->SMTPOptions = array(
                        'ssl' => array(
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                            'allow_self_signed' => true
                        )
                    );

                    // Content
                    $mail->isHTML(true); // Set email format to HTML
                    $mail->Subject = 'Your invoice of shipping from Wine Cave(Order#'.$id.')';
                    $mail->Body    = $shippingHtml;
                    $mail->Send();
                }

                return redirect('/admin/shopping/orders');
            }
            else{
                $data['currentPage']='shopping';
                $data['header_title']='Detail of order ('.$id.')';
                return view('admin.shopping.order_detail',$data);                
            }
        }        
    }

    public function delete_order(){
        $id = request('id');
        OrdersData::where('OrderId',$id)->delete();
        return redirect()->back();
    }

    public function quick_shipping(Request $request){
        $id = request('id');
        $orderInfo = OrdersData::join('order_shipping as os','orders.OrderId','=','os.OrderId')->join('order_billing as ob','orders.OrderId','=','ob.OrderId')->leftJoin('members as m','orders.MemberId','=','m.MemberId')->where('orders.OrderId','=',$id)->where('orders.Status','=',0)->select(
            array(
                'orders.*',
                'os.Type','os.Country','os.FirstName as osFirst','os.MiddleName as osMiddle','os.LastName as osLast','os.Company as osCompany','os.Address as osAddress','os.Address2 as osAddress2','os.City as osCity','os.Region as osRegion','os.PostCode as osPostCode','os.Email as osEmail','os.Telephone as osTelephone','os.Country as osCountry',
                'ob.Country','ob.FirstName as obFirst','ob.MiddleName as obMiddle','ob.LastName as obLast','ob.Company as obCompany','ob.Address as obAddress','ob.Address2 as obAddress2','ob.City as obCity','ob.Region as obRegion','ob.PostCode as obPostCode','ob.Email as obEmail','ob.Telephone as obTelephone','ob.Country as obCountry',
                'm.*'
            )
        )->take(1)->first();

        if($orderInfo){
            $orderDetails = array();
            $orderDetailRows = OrderDetailData::where('OrderId','=',$id)->get();
            foreach($orderDetailRows as $row){
                if($row->ProductType==='wine'){
                    if($wine = WinesData::where('WineId',$row->Product)->take(1)->first()){
                        array_push($orderDetails,array(
                            'Product'=>$wine->Name,
                            'Type'=>'wine',
                            'Price'=>$row->Price,
                            'Quantity'=>$row->Quantity,
                            'Subtotal'=>$row->Subtotal
                        ));
                    }
                }
                elseif($row->ProductType==='case'){
                    if($case = SalesMixData::where('MixId',$row->Product)->take(1)->first()){
                        array_push($orderDetails,array(
                            'Product'=>$case->MixName,
                            'Type'=>'case',
                            'Price'=>$row->Price,
                            'Quantity'=>$row->Quantity,
                            'Subtotal'=>$row->Subtotal                      
                        ));
                    }
                }
                elseif($row->ProductType==='gift'){
                    if($gift = GiftsData::where(array(
                        array('GiftId','=',$row->Product),
                    ))->take(1)->first()){
                        array_push($orderDetails,array(
                            'Product'=>$gift->Name,
                            'Type'=>'gift',
                            'Price'=>$row->Price,
                            'Quantity'=>$row->Quantity,
                            'Subtotal'=>$row->Subtotal
                        ));
                    }
                }
            }

            // if(!file_exists(public_path("/shipping_invoice/".$id.".pdf"))){
                $shippingHtml = '<!DOCTYPE html>
                <html>
                <head>
                    <title></title>
                </head>
                <body>
                <p style="font-weight:bold;font-size:20px;color:rgb(118,114,52)">INVOICE (#'.$id.')</p>';

                $shippingHtml .= '<div style="position:relative">
                    <img src="https://www.shopwinecave.nz/images/logo.png" style="position:absolute;width:300px;right:100px;float:right" />
                    <h1 style="color:rgb(178,86,0);font-size:40px">The Wine Cave NZ</h1>
                    <p style="color:rgb(118,114,52);font-size:20px;margin:0 0 10px;">104 Carlton Gore Road, Newmarket</p>
                    <p style="color:rgb(118,114,52);font-size:20px;margin:0;">Auckland, 1023</p>';
                $shippingHtml .= '<p style="color:rgb(118,114,52);font-weight:bold;font-size:20px">DATE: (';
                $shippingHtml .= date('d/m/y');
                $shippingHtml .= ')</p>';
                $shippingHtml .= '<h2 style="color:rgb(178,86,0);font-size:30px;margin-bottom:0">BILL TO</h2><p style="margin:0;color:rgb(118,114,52)">(';
                $shippingHtml .= $orderInfo->osFirst;
                if(trim($orderInfo->osMiddle)!==""){
                    $shippingHtml .= " ";
                    $shippingHtml .= $orderInfo->osMiddle;
                }
                $shippingHtml .= " ";
                $shippingHtml .= $orderInfo->osLast;
                if(trim($orderInfo->osCompany)!==""){
                    $shippingHtml .= " / ";
                    $shippingHtml .= $orderInfo->osCompany;
                }
                $shippingHtml .= ')</p>';

                $shippingHtml .= '<p style="margin-top:0;color:rgb(118,114,52)">(';

                $shippingHtml .= $orderInfo->osAddress;
                $shippingHtml .= "<br />";
                $shippingHtml .= $orderInfo->osCity;
                $shippingHtml .= ", ";
                $shippingHtml .= $orderInfo->osRegion;
                $shippingHtml .= $orderInfo->PostCode;
                $shippingHtml .= ")</p>";

                $shippingHtml .= '</div>';
                $shippingHtml .= '<div style="margin-top:30px">
                    <table style="border-collapse:collapse;width:100%">
                        <tr>
                            <th style="text-align:left;color:rgb(118,114,52);font-size:20px">Details</th>
                            <th style="text-align:right;color:rgb(118,114,52);font-size:20px">AMOUNT</th>
                        </tr>';

                $Subtotal = 0;
                foreach($orderDetails as $product){
                    $Subtotal += $product['Subtotal'];

                    $shippingHtml .= '<tr>';

                    $shippingHtml .= '<td style="text-align:left;color:rgb(118,114,52);font-size:17px;border-bottom:2px solid rgb(196,194,82);padding:15px 0 0">';
                    $shippingHtml .= $product['Product'];
                    $shippingHtml .= '</td>';

                    $shippingHtml .= '<td style="text-align:right;color:rgb(118,114,52);font-size:17px;border-bottom:2px solid rgb(196,194,82);padding:15px 0 0">';
                    $shippingHtml .= $product['Subtotal'];
                    $shippingHtml .= '</td>';

                    $shippingHtml .= '</tr>';
                }

                $shippingHtml .= '<tr><td style="text-align:right;color:rgb(118,114,52);font-size:17px;padding:15px 10px 0 0">SUBTOTAL</td><td style="text-align:right;color:rgb(118,114,52);font-size:17px;border-bottom:2px solid rgb(196,194,82);padding:15px 0 0">';
                $shippingHtml .= number_format($Subtotal,2);
                $shippingHtml .= '</td></tr>';

                $shippingHtml .= '<tr><td style="text-align:right;color:rgb(118,114,52);font-size:17px;padding:15px 10px 0 0">GST</td><td style="text-align:right;color:rgb(118,114,52);font-size:17px;border-bottom:2px solid rgb(196,194,82);padding:15px 0 0">';
                $shippingHtml .= number_format($orderInfo->GST,2);
                $shippingHtml .= '</td></tr>';

                $shippingHtml .= '<tr><td style="text-align:right;color:rgb(118,114,52);font-size:17px;padding:15px 10px 0 0">DELIVERY</td><td style="text-align:right;color:rgb(118,114,52);font-size:17px;border-bottom:2px solid rgb(196,194,82);padding:15px 0 0">';
                $shippingHtml .= number_format($orderInfo->DeliveryCost,2);
                $shippingHtml .= '</td></tr>';

                $shippingHtml .= '<tr><td style="text-align:right;color:rgb(118,114,52);font-size:17px;padding:15px 10px 0 0">SUBTOTAL</td><td style="text-align:right;color:rgb(118,114,52);font-size:17px;border-bottom:2px solid rgb(196,194,82);padding:15px 0 0">';
                $shippingHtml .= number_format($orderInfo->Total,2);
                $shippingHtml .= '</td></tr>';

                $shippingHtml .= '
                    </table>
                </div>
                <div style="margin-top:10px">
                    <p style="color:rgb(118,114,52);font-size:17px">YOUR ORDER HAS BEEN DISPATCHED.</p>
                    <p style="color:rgb(118,114,52);font-size:17px">If you have any questions concerning this invoice, use the following contact information:</p>
                    <p style="color:rgb(118,114,52);font-size:17px">Email: <a style="color:rgb(118,114,52);text-decoration:none" href="mailto:accounts@winecave.nz">accounts@winecave.nz</a></p>
                    <p style="color:rgb(118,114,52);font-size:17px;font-weight:bold">THANK YOU FOR YOUR BUSINESS!</p>
                </div>';
                $shippingHtml .= '</body></html>';
                
                // include(public_path("/plugins/TCPDF/tcpdf.php"));
                // create new PDF document
                // $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                // set document information
                // $pdf->SetCreator("Wine Cave from New Zealand");
                // $pdf->SetAuthor("Wine Cave from New Zealand");
                // $pdf->SetSubject('Invoice of Order#'.$id);
                // $pdf->SetSubject('Invoice of Order#'.$id);
                // $pdf->SetKeywords('Invoice');
      
                // set default header data
                // $pdf->setPrintHeader(false);

                // set default monospaced font
                // $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
      
                // set margins
                // $pdf->SetMargins(10, 10, 10);
                // $pdf->SetHeaderMargin(0);
                // $pdf->SetFooterMargin(10);
                // // set auto page breaks
                // $pdf->SetAutoPageBreak(TRUE, 10);

                // // add a page
                // $pdf->AddPage();
                // // output the HTML content
                // $pdf->writeHTML($shippingHtml, true, false, true, false, '');
                // // reset pointer to the last page
                // $pdf->lastPage();
                // // ---------------------------------------------------------
                // //Close and output PDF document
                // $pdf->Output(public_path("/shipping_invoice/".$id.".pdf"),'F');
                // exit();

                //Server settings
                $mail = new PHPMailer(true);
                $mail->SMTPDebug = 0;
                $mail->isSMTP();
                $mail->Host='mail.shopwinecave.nz';
                $mail->SMTPAuth=true;
                $mail->Username='service@shopwinecave.nz';
                $mail->Password='A1qazxsw2!';
                $mail->SMTPSecure='ssl';
                $mail->Port=465;
                $mail->CharSet="UTF-8"; 
                //Recipients
                $mail->setFrom('service@shopwinecave.nz','Wine Cave');
                $mail->addAddress($orderInfo->obEmail,$orderInfo->obFirst.' '.$orderInfo->obLast);

                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );

                // Content
                $mail->isHTML(true); // Set email format to HTML
                $mail->Subject = 'Your invoice of shipping from Wine Cave(Order#'.$id.')';
                $mail->Body    = $shippingHtml;
                if($mail->send()){
                    OrdersData::where('OrderId',$id)->update(array(
                        'Status'=>1,
                        'updated_at'=>date('Y-m-d H:i:s')
                    ));
                }
            // }
        }
        return redirect('/admin/shopping/orders?page='.intval(request('page')));
    }
}