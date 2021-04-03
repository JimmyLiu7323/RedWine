<?php
namespace App\Http\Controllers\Frontend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Session;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
class FooterPageController extends Controller
{                              
    public function __construct(){
        parent::__construct();
    }

    public function guarantee(){
        $data['display_index_guarantee_image'] = 0;
        $data['index_guarantee_image'] = '';
        $data['guarantee_text'] = '';

        $index_guarantee_images_condition=array('display_index_guarantee_image','index_guarantee_image');
        $index_guarantee_images_query=DB::table('template_static_data')->whereIn('DataKey',$index_guarantee_images_condition)->get();
        foreach($index_guarantee_images_query as $setting){
            $data[$setting->DataKey]=$setting->Data;
        }

        $currentLang = Session::get('Language');
        $getKey = "guarantee_text";
        if($currentLang==='EN')
            $getKey.="_en";
        $guaranteeIntroduction = DB::table('template_static_data')->where('DataKey','=',$getKey)->take(1)->first();
        if($guaranteeIntroduction){
            $data['guarantee_text'] = $guaranteeIntroduction->Data;
        }

        $data['htmlTitle']=Session::get('Language')==='EN'?'Our Guarantee':'商家保证';
        // breadcrumbs
        $breadcrumbs = array(
            Session::get('Language')==='EN' ? array('url'=>'/guarantee','name'=>'Our Guarantee') : array('url'=>'/guarantee','name'=>'商家保证')
        );

        $data['breadcrumbs'] = $breadcrumbs;
        return view('frontend.footer.guarantee',$data);
    }

    public function privacyPolicy(){
        $data['privacy_policy_text'] = '';
        $currentLang = Session::get('Language');
        $getKey = "privacy_policy";
        if($currentLang==='EN')
            $getKey.="_en";
        $privacyPolicyQuery = DB::table('footer_page_static_data')->where('DataKey','=',$getKey)->take(1)->first();
        if($privacyPolicyQuery){
            $data['privacy_policy_text'] = $privacyPolicyQuery->Data;
        }

        $data['htmlTitle']=Session::get('Language')==='EN'?'Privacy Policy':'隐私政策';
        // breadcrumbs
        $breadcrumbs = array(
            Session::get('Language')==='EN' ? array('url'=>'/privacy-policy','name'=>'Privacy Policy') : array('url'=>'/privacy-policy','name'=>'隐私政策')
        );

        $data['breadcrumbs'] = $breadcrumbs;
        return view('frontend.footer.privacy-policy',$data);        
    }

    public function corporate(){
        $data['corporate_image'] = '';
        $data['corporate_introduction'] = '';
        $data['corporate_introduction_en'] = '';

        $corporate_condition=array('corporate_image','corporate_introduction','corporate_introduction_en');
        $corporate_service_query=DB::table('footer_page_static_data')->whereIn('DataKey',$corporate_condition)->get();
        foreach($corporate_service_query as $setting){
            $data[$setting->DataKey]=$setting->Data;
        }

        $data['htmlTitle']=Session::get('Language')==='EN'?'Corporate Wine Service':'合作促销';
        // breadcrumbs
        $breadcrumbs = array(
            Session::get('Language')==='EN' ? array('url'=>'/corporate','name'=>'Corporate Wine Service') : array('url'=>'/corporate','name'=>'合作促销')
        );

        $data['breadcrumbs'] = $breadcrumbs;
        return view('frontend.footer.corporate',$data);        
    }

    public function awards(){
        $data['award_image'] = '';
        $data['award_introduction'] = '';
        $data['award_introduction_en'] = '';

        $award_condition=array('award_image','award_introduction','award_introduction_en');
        $award_service_query=DB::table('footer_page_static_data')->whereIn('DataKey',$award_condition)->get();
        foreach($award_service_query as $setting){
            $data[$setting->DataKey]=$setting->Data;
        }

        $data['htmlTitle']=Session::get('Language')==='EN'?'Awards':'历年奖项';
        // breadcrumbs
        $breadcrumbs = array(
            Session::get('Language')==='EN' ? array('url'=>'/awards','name'=>'Awards') : array('url'=>'/awards','name'=>'历年奖项')
        );

        $data['breadcrumbs'] = $breadcrumbs;
        return view('frontend.footer.awards',$data);        
    }

    public function orders_payment(){
        $data['htmlTitle']=Session::get('Language')==='EN'?'Orders and Payment':'订购与付款';
        // breadcrumbs
        $breadcrumbs = array(
            Session::get('Language')==='EN' ? array('url'=>'/orders-and-payment','name'=>'Orders and Payment') : array('url'=>'/orders-and-payment','name'=>'订购与付款')
        );

        $data['breadcrumbs'] = $breadcrumbs;
        return view('frontend.footer.orders-and-payment',$data);
    }

    public function deliveryPage(){
        $data['htmlTitle']=Session::get('Language')==='EN'?'Delivery Information':'运送资讯';
        // breadcrumbs
        $breadcrumbs = array(
            Session::get('Language')==='EN' ? array('url'=>'/delivery-page','name'=>'Delivery Information') : array('url'=>'/delivery-page','name'=>'运送资讯')
        );

        $data['breadcrumbs'] = $breadcrumbs;
        return view('frontend.footer.delivery-page',$data);        
    }

    public function contacts(){
        $data['htmlTitle']=Session::get('Language')==='EN'?'Contact Us':'联络我们';
        // breadcrumbs
        $breadcrumbs = array(
            Session::get('Language')==='EN' ? array('url'=>'/contacts','name'=>'Contact Us') : array('url'=>'/contacts','name'=>'联络我们')
        );

        $data['breadcrumbs'] = $breadcrumbs;
        return view('frontend.footer.contacts',$data);
    }

    public function sendContacts(){
        // Array ( [name] => Gai Chen [email] => hahaca820407@gmail.com [telephone] => 022879990 [comment] => test [_token] => yvxDsZXZjul9hcDcLO5qilD9RzTH6wRZLm4bUFR8 )
        $name = trim(request('name'));
        $email = trim(request('email'));
        $telephone = trim(request('telephone'));
        $comment = trim(request('comment'));

        $mail = new PHPMailer(true);
        if($name!=='' && $email!=='' && $comment!==''){
            try {
                //Server settings
                $mail->SMTPDebug = 0;
                $mail->isSMTP();
                $mail->Host='mail.shopwinecave.nz';
                $mail->SMTPAuth=true;
                $mail->Username='service@shopwinecave.nz';
                $mail->Password='A1qazxsw2!';
                $mail->SMTPSecure='ssl';
                $mail->Port=465;

                //Recipients
                $mail->setFrom('service@shopwinecave.nz','Wine Cave');
                $mail->addAddress('accounts@winecave.nz','Administrator of Wine Cave');
                $mail->addAddress('admin@winecave.nz','Administrator of Wine Cave');
                $mail->addAddress('marshall775042@gmail.com','Administrator of Wine Cave');
                $mail->addAddress('wishs900g3@gmail.com','Administrator of Wine Cave');

                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );

                // Content
                $mail->isHTML(true); // Set email format to HTML
                $mail->Subject = 'User message from website Wine Cave';
                $bodyHtml = "<p style='margin:0'>Name: ".$name."</p>";
                $bodyHtml .= "<p style='margin:0'>Email: ".$email."</p>";
                if($telephone!==''){
                    $bodyHtml .= "<p style='margin:0'>Telephone: ".$telephone."</p>";
                }
                $bodyHtml .= "<p style='margin:0'>Comment from user: ".$comment."</p>";
                $mail->Body    = $bodyHtml;
                $mail->send();
            } catch (\Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }
        return redirect('/contacts');
    }

    public function aboutHistory(){
        $data['htmlTitle']=Session::get('Language')==='EN'?'Our History':'历史沿革';
        // breadcrumbs
        $breadcrumbs = array(
            Session::get('Language')==='EN' ? array('url'=>'/about-history','name'=>'Our History') : array('url'=>'/about-history','name'=>'历史沿革')
        );

        $data['breadcrumbs'] = $breadcrumbs;
        return view('frontend.footer.about-history',$data);        
    }    
}