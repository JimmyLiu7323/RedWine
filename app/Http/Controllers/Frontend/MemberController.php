<?php
namespace App\Http\Controllers\Frontend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Members\MembersData;
use App\Members\AddressBookData;

use Config;
use Cookie;
use Validator;
use Widget_Helper;
use Session;

class MemberController extends Controller
{                              
    public function __construct(){
        parent::__construct();
    }

    public function account(){
        $data['htmlTitle']=Session::get('Language')==='EN'?"Account Overview":"帐号大纲";
        $MemberId=Session::get('MemberId');
        $currentMemInfo=MembersData::where('MemberId',$MemberId)->take(1)->first();
        $data['currentMemInfo']=$currentMemInfo;
        if($currentMemInfo){
            $defaultBilling=AddressBookData::where('DefaultChoose',1)->where('MemberId',Session::get('MemberId'))->where('AddrType',1)->orderBy('updated_at','DESC')->take(1)->first();
            $defaultShipping=AddressBookData::where('DefaultChoose',1)->where('MemberId',Session::get('MemberId'))->where('AddrType',2)->orderBy('updated_at','DESC')->take(1)->first();
            $data['defaultBilling']=$defaultBilling;
            $data['defaultShipping']=$defaultShipping;

            return view('frontend.account.overview',$data);
        }
        return redirect('/');
    }

    public function login(Request $request){
        if(Session::has('MemberId')){
            return redirect('/account');
        }        
        if($request->isMethod('post')){
            $email=trim(request('email'));
            $password=trim(request('password'));
            $cookie_remember=intval(request('cookie_remember'));
            $row=MembersData::where('Email',$email)->take(1)->first();
            if($row){
                $password_hash=$row->Password;
                if(password_verify($password,$password_hash)){
                    Session::put('MemberId',$row->MemberId);
                    Session::put('FirstName',$row->FirstName);
                    Session::put('LastName',$row->LastName);

                    if($cookie_remember){
                        Cookie::queue('cookie_email',$email,43200);
                    }
                    return redirect('/account');
                }
                else{
                    Session::flash('system_message_flag',true);
                    Session::flash('system_message_status','warning');
                    Session::flash('system_message',Session::get('Language')==='EN'?'Email or password is not correct':'信箱或密码错误');
                    return redirect('/login');
                }
            }
            Session::flash('system_message_flag',true);
            Session::flash('system_message_status','warning');
            Session::flash('system_message',Session::get('Language')==='EN'?'Can\'t find member data':'查无会员资料');
            return redirect('/login');
        }
        $data['htmlTitle']=Session::get('Language')==='EN'?"Customer Login":"用户登入";
        return view('frontend.account.login',$data);
    }

    public function register(Request $request){
        if(Session::has('MemberId')){
            return redirect('/account');
        }
        if($request->isMethod('post')){
            $rule_array=array(
                'Country'=>'required|in:CN,NZ,US',
                'FirstName'=>'required|max:255',
                'MiddleName'=>'nullable|max:255',
                'LastName'=>'required|max:255',
                'Email'=>'required|email|max:255',
                'Password'=>'required|confirmed',
                'Password_confirmation'=>'required',
                'sign_up_news'=>'nullable|in:1'
            );

            if(Session::get('Language')==='EN'){
                $rule_message=array(
                    'Country.required'=>'Choose one country',
                    'Country.in'=>'Choose one country',
                    'FirstName.required'=>'Fill your first name',
                    'FirstName.max'=>'Your first name cannot above 255 characters',
                    'MiddleName.max'=>'Your middle name cannot above 255 characters',
                    'LastName.required'=>'Fill your last name',
                    'Email.required'=>'Fill your email for being login account',
                    'Email.email'=>'Format of this field is not correct',
                    'Email.max'=>'Your email cannot above 255 characters',
                    'Password.required'=>'Fill your login password',
                    'Password.confirmed'=>'Please confirm your password again',
                    'Password_confirmation'=>'Fill your login password again'
                );
            }
            else{
                $rule_message=array(
                    'Country.required'=>'请选择您的国家',
                    'Country.in'=>'请选择您的国家',
                    'FirstName.required'=>'请填入您的大名',
                    'FirstName.max'=>'您的姓名不可超过255个字符',
                    'MiddleName.max'=>'您的中间名不可超过255个字符',
                    'LastName.required'=>'请填入您的姓',
                    'Email.required'=>'请填入您的Email',
                    'Email.email'=>'您的Email格式有误',
                    'Email.max'=>'您的Email不可超过255个字符',
                    'Password.required'=>'请填入您的登入密码',
                    'Password.confirmed'=>'请再次填入您的密码',
                    'Password_confirmation'=>'请输入相同密码'
                );                
            }
            $build_validator=Validator::make($request->all(),$rule_array,$rule_message);
            if($build_validator->fails()){
                return redirect()->back()->withInput()->withErrors($build_validator->errors());
            }
            $Country=request('Country');
            $FirstName=request('FirstName');
            $MiddleName=request('MiddleName');
            $LastName=request('LastName');
            $Email=request('Email');
            $Password=request('Password');
            $sign_up_news=intval(request('sign_up_news'));

            $Password=password_hash($Password,PASSWORD_DEFAULT);

            // verify account 
            $exists=MembersData::where('Email',$Email)->take(1)->first();
            if(!$exists){
                $newMemberId=Widget_Helper::createID();
                if(MembersData::insert(array(
                    'MemberId'=>$newMemberId,
                    'Country'=>$Country,
                    'FirstName'=>$FirstName,
                    'MiddleName'=>$MiddleName,
                    'LastName'=>$LastName,
                    'Email'=>$Email,
                    'Password'=>$Password,
                    'FollowingNews'=>$sign_up_news,
                    'created_at'=>date('Y-m-d H:i:s'),
                    'updated_at'=>date('Y-m-d H:i:s')
                ))){
                    Session::put('MemberId',$newMemberId);
                    Session::put('FirstName',$FirstName);
                    Session::put('LastName',$LastName);
                    Session::flash('system_message_flag',true);
                    Session::flash('system_message_status','success');
                    if(Session::get('Language')==='EN')
                        Session::flash('system_message','Success to become our member!');
                    else
                        Session::flash('system_message','恭喜您已成为我们的会员!');
                    return redirect('/account');
                }
                else{
                    Session::flash('system_message_flag',true);
                    Session::flash('system_message_status','error');
                    if(Session::get('Language')==='EN')
                        Session::flash('system_message','Fail to add new member...');
                    else
                        Session::flash('system_message','无法注册，请重试...');
                }
            }
            else{
                Session::flash('system_message_flag',true);
                Session::flash('system_message_status','warning');
                if(Session::get('Language')==='EN')
                    Session::flash('system_message','Same email has exist');
                else
                    Session::flash('system_message','相同Email帐号已经存在');
                return redirect()->back()->withInput()->withErrors($build_validator->errors());
            }
        }
        $data['htmlTitle']=Session::get('Language')==='EN'?"Become New Member":"会员注册";
        return view('frontend.account.register',$data);        
    }

    public function edit(Request $request){
        $MemberId=Session::get('MemberId');
        $currentMemInfo=MembersData::where('MemberId',$MemberId)->take(1)->first();
        if($currentMemInfo){
            if($request->isMethod('post')){
                $rule_array=array(
                    'Country'=>'required|in:CN,NZ,US',
                    'FirstName'=>'required|max:255',
                    'MiddleName'=>'nullable|max:255',
                    'LastName'=>'required|max:255',
                    'Email'=>'required|email|max:255',
                    'Password_verify'=>'required',
                    'Password'=>'nullable|confirmed',
                    'Password_confirmation'=>'nullable',
                    'change_pwd'=>'nullable|integer|in:1'
                );
                $rule_message=array(
                    'Country.required'=>(Session::get('Language')==='EN')?'Choose one country':'请选择您的国家',
                    'Country.in'=>(Session::get('Language')==='EN')?'Choose one country':'请选择您的国家',
                    'FirstName.required'=>(Session::get('Language')==='EN')?'Fill your first name':'请输入您的姓名',
                    'FirstName.max'=>(Session::get('Language')==='EN')?'Your first name cannot above 255 characters':'您的姓名不可超过255个字符',
                    'MiddleName.max'=>(Session::get('Language')==='EN')?'Your middle name cannot above 255 characters':'您的中间名不可超过255个字符',
                    'LastName.required'=>(Session::get('Language')==='EN')?'Fill your last name':'请输入您的姓',
                    'Email.required'=>(Session::get('Language')==='EN')?'Fill your email for being login account':'请输入您的登入帐号',
                    'Email.email'=>(Session::get('Language')==='EN')?'Format of this field is not correct':'您的Email格式有误',
                    'Email.max'=>(Session::get('Language')==='EN')?'Your email cannot above 255 characters':'您的Email不可超过255个字符',
                    'Password.required'=>(Session::get('Language')==='EN')?'Fill your login password':'请输入登入密码',
                    'Password.confirmed'=>(Session::get('Language')==='EN')?'Please confirm your password again':'请输入相同密码',
                    'Password_confirmation'=>(Session::get('Language')==='EN')?'Fill your login password again:':'请输入相同密码'
                );
                $build_validator=Validator::make($request->all(),$rule_array,$rule_message);
                if($build_validator->fails()){
                    if($build_validator->errors()->has('Password_verify')){
                        Session::flash('system_message_flag',true);
                        Session::flash('system_message_status','warning');
                        if(Session::get('Language')==='EN'){
                            Session::flash('system_message','Please fill your password');
                        }
                        else{
                            Session::flash('system_message','请输入您的密码');
                        }
                    }
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }

                $Country=trim(request('Country'));
                $FirstName=trim(request('FirstName'));
                $MiddleName=trim(request('MiddleName'));
                $LastName=trim(request('LastName'));
                $Email=trim(request('Email'));
                $Password_verify=trim(request('Password_verify'));
                $Password=trim(request('Password'));
                $change_pwd=intval(request('change_pwd'));
                if(password_verify($Password_verify,$currentMemInfo->Password)){
                    $updateArr=array(
                        'Country'=>$Country,
                        'FirstName'=>$FirstName,
                        'MiddleName'=>$MiddleName,
                        'LastName'=>$LastName,
                        'Email'=>$Email,
                        'updated_at'=>date('Y-m-d H:i:s')
                    );
                    if($change_pwd){
                        $updateArr['Password']=password_hash($Password,PASSWORD_DEFAULT);
                    }
                    
                    if(MembersData::where('MemberId',$MemberId)->update($updateArr)){
                        Session::put('FirstName',$FirstName);
                        Session::put('LastName',$LastName);
                        Session::flash('system_message_flag',true);
                        Session::flash('system_message_status','success');
                        if(Session::get('Language')==='EN'){
                            Session::flash('system_message','Updated information');
                        }
                        else{
                            Session::flash('system_message','更新资讯成功');
                        }
                        return redirect('/account');
                    }
                    else{
                        Session::flash('system_message_flag',true);
                        Session::flash('system_message_status','fail');
                        if(Session::get('Language')==='EN'){
                            Session::flash('system_message','Updated fail...');
                        }
                        else{
                            Session::flash('system_message','更新失败...');
                        }
                    }
                }
                else{
                    Session::flash('system_message_flag',true);
                    Session::flash('system_message_status','warning');
                    if(Session::get('Language')==='EN'){
                        Session::flash('system_message','Please fill your password');
                    }
                    else{
                        Session::flash('system_message','请输入您的密码');
                    }
                }
            }
            $data['currentMemInfo']=$currentMemInfo;
            $data['htmlTitle']=Session::get('Language')==='EN'?"Account Information":'维护我的帐号';
            return view('frontend.account.edit',$data);
        }
        return redirect('/');
    }

    public function maintain_newsletter(Request $request){
        $currentMemInfo=MembersData::where('MemberId',Session::get('MemberId'))->take(1)->first();
        if($currentMemInfo){
            if($request->isMethod('post')){
                $FollowingNews=intval(request('FollowingNews'));
                MembersData::where('MemberId',Session::get('MemberId'))->update(array(
                    'FollowingNews'=>$FollowingNews,
                    'updated_at'=>date('Y-m-d H:i:s')
                ));
                return redirect('/account');
            }
            $data['Subscribed']=$currentMemInfo->FollowingNews;
            $data['htmlTitle']=Session::get('Language')==='EN'?"News subscription":"订阅最新消息";
            return view('frontend.account.maintain_newsletter',$data);
        }
        return redirect('/');
    }

    public function addressBook(){
        $defaultBilling=AddressBookData::where('AddrType',1)->where('DefaultChoose',1)->take(1)->first();
        $defaultShipping=AddressBookData::where('AddrType',2)->where('DefaultChoose',1)->take(1)->first();
        $data['defaultBilling']=$defaultBilling;
        $data['defaultShipping']=$defaultShipping;
        $data['htmlTitle']=Session::get('Language')==='EN'?"Address Book":'地址库';
        return view('frontend.address.book',$data);
    }

    public function new_address(Request $request){
        $countries=Config::get('app.countries');
        $data['countries']=$countries;
        if($request->isMethod('post')){
            $rule_array=array(
                'Contact_first'=>'required|max:255',
                'Contact_middle'=>'nullable|max:255',
                'Contact_last'=>'required|max:255',
                'Company'=>'nullable|max:255',
                'Telephone'=>'required|max:255',
                'Fax'=>'nullable|max:255',
                'StreetAddr'=>'required|max:16777215',
                'StreetAddr2'=>'nullable|max:16777215',
                'City'=>'required|max:255',
                'Region'=>'required|max:255',
                'PostCode'=>'required|max:255',
                'Country'=>'required|max:2',
            );
            $rule_message=array(
                'Contact_first.required'=>(Session::get('Language')==='EN')?'Fill your first name':'请输入您的姓名',
                'Contact_first.max'=>(Session::get('Language')==='EN')?'Should not over 255 characters':'您的姓名不可超过255个字符',
                'Contact_middle.max'=>(Session::get('Language')==='EN')?'Should not over 255 characters':'您的中间名不可超过255个字符',
                'Contact_last.required'=>(Session::get('Language')==='EN')?'Fill your last name':'请输入您的姓',
                'Contact_last.max'=>(Session::get('Language')==='EN')?'Should not over 255 characters':'您的姓不可超过255个字符',
                'Company.max'=>(Session::get('Language')==='EN')?'Should not over 255 characters':'您的公司名称不可超过255个字符',
                'Telephone.required'=>(Session::get('Language')==='EN')?'Fill your telephone':'请输入您的电话',
                'Telephone.max'=>(Session::get('Language')==='EN')?'Should not over 255 characters':'您的电话不可超过255个字符',
                'Fax.max'=>(Session::get('Language')==='EN')?'Should not over 255 characters':'您的传真号码不可超过255个字符',
                'StreetAddr.required'=>(Session::get('Language')==='EN')?'Fill your address':'请输入您的地址',
                'StreetAddr.max'=>(Session::get('Language')==='EN')?'Should not over 16777215 characters':'您的地址不可超过16777215个字符',
                'StreetAddr2.max'=>(Session::get('Language')==='EN')?'Should not over 16777215 characters':'您的地址不可超过16777215个字符',
                'City.required'=>(Session::get('Language')==='EN')?'Fill the city of address':'请输入县市',
                'City.max'=>(Session::get('Language')==='EN')?'Should not over 255 characters':'县市内容不可超过255个字符',
                'Region.required'=>(Session::get('Language')==='EN')?'Fill the region of address':'请输入区域',
                'Region.max'=>(Session::get('Language')==='EN')?'Should not over 255 characters':'区域内容不可超过255个字符',
                'PostCode.required'=>(Session::get('Language')==='EN')?'Fill the post code of address':'请输入邮递区号',
                'PostCode.max'=>(Session::get('Language')==='EN')?'Should not over 255 characters':'邮递区号不可超过255个字符',
                'Country.required'=>(Session::get('Language')==='EN')?'Choose the country of address':'请选择您的国家',
                'Country.max'=>(Session::get('Language')==='EN')?'Choose the country in list':'请选择您的国家'
            );
            $build_validator=Validator::make($request->all(),$rule_array,$rule_message);
            if($build_validator->fails()){
                return redirect()->back()->withInput()->withErrors($build_validator->errors());
            }

            $Contact_first=trim(request('Contact_first'));
            $Contact_middle=trim(request('Contact_middle'));
            $Contact_last=trim(request('Contact_last'));
            $Company=trim(request('Company'));
            $Telephone=trim(request('Telephone'));
            $Fax=trim(request('Fax'));
            $StreetAddr=trim(request('StreetAddr'));
            $StreetAddr2=trim(request('StreetAddr2'));
            $City=trim(request('City'));
            $Region=trim(request('Region'));
            $PostCode=trim(request('PostCode'));
            $Country=trim(request('Country'));
            $DefaultBilling=intval(request('DefaultBilling'));
            $DefaultShipping=intval(request('DefaultShipping'));

            $newBillingAddress=new AddressBookData;
            $newBillingAddress->MemberId=Session::get('MemberId');
            $newBillingAddress->Contact_first=$Contact_first;
            $newBillingAddress->Contact_middle=$Contact_middle;
            $newBillingAddress->Contact_last=$Contact_last;
            $newBillingAddress->Company=$Company;
            $newBillingAddress->Telephone=$Telephone;
            $newBillingAddress->Fax=$Fax;
            $newBillingAddress->StreetAddr=$StreetAddr;
            $newBillingAddress->StreetAddr2=$StreetAddr2;
            $newBillingAddress->City=$City;
            $newBillingAddress->Region=$Region;
            $newBillingAddress->PostCode=$PostCode;
            $newBillingAddress->Country=$Country;
            $newBillingAddress->AddrType=1;
            if($DefaultBilling){
                $newBillingAddress->DefaultChoose=1;
                AddressBookData::where('MemberId',Session::get('MemberId'))->where('AddrType',1)->update(array('DefaultChoose'=>0,'updated_at'=>date('Y-m-d H:i:s')));
            }
            $newBillingAddress->created_at=date('Y-m-d H:i:s');
            $newBillingAddress->updated_at=date('Y-m-d H:i:s');
            $newBillingAddress->save();

            $newShippingAddress=new AddressBookData;
            $newShippingAddress->MemberId=Session::get('MemberId');
            $newShippingAddress->Contact_first=$Contact_first;
            $newShippingAddress->Contact_middle=$Contact_middle;
            $newShippingAddress->Contact_last=$Contact_last;
            $newShippingAddress->Company=$Company;
            $newShippingAddress->Telephone=$Telephone;
            $newShippingAddress->Fax=$Fax;
            $newShippingAddress->StreetAddr=$StreetAddr;
            $newShippingAddress->StreetAddr2=$StreetAddr2;
            $newShippingAddress->City=$City;
            $newShippingAddress->Region=$Region;
            $newShippingAddress->PostCode=$PostCode;
            $newShippingAddress->Country=$Country;
            $newShippingAddress->AddrType=2;
            if($DefaultShipping){
                $newShippingAddress->DefaultChoose=1;
                AddressBookData::where('MemberId',Session::get('MemberId'))->where('AddrType',2)->update(array('DefaultChoose'=>0,'updated_at'=>date('Y-m-d H:i:s')));
            }
            $newShippingAddress->created_at=date('Y-m-d H:i:s');
            $newShippingAddress->updated_at=date('Y-m-d H:i:s');
            $newShippingAddress->save();

            Session::flash('badge_flag',true);
            Session::flash('badge_flag_type','success');
            if(Session::get('Language')==='EN'){
                Session::flash('badge_flag_message','You just added a new address!');
            }
            else{
                Session::flash('badge_flag_message','您刚刚新增了一个地址!');
            }
        }
        else{
            $currentMemInfo=MembersData::where('MemberId',Session::get('MemberId'))->take(1)->first();
            if($currentMemInfo){
                $data['currentMemInfo']=$currentMemInfo;
                $data['htmlTitle']=Session::get('Language')==='EN'?'Add New Address':'新增地址';
                return view('frontend.address.new_address',$data);
            }
        }
        return redirect('/address');
    }

    public function billing_address_edit(Request $request){
        $addressData=AddressBookData::where('DefaultChoose',1)->where('AddrType',1)->where('MemberId',Session::get('MemberId'))->orderBy('updated_at','DESC')->take(1)->first();
        if($addressData){
            if($request->isMethod('post')){
                $rule_array=array(
                    'Contact_first'=>'required|max:255',
                    'Contact_middle'=>'nullable|max:255',
                    'Contact_last'=>'required|max:255',
                    'Company'=>'nullable|max:255',
                    'Telephone'=>'required|max:255',
                    'Fax'=>'nullable|max:255',
                    'StreetAddr'=>'required|max:16777215',
                    'StreetAddr2'=>'nullable|max:16777215',
                    'City'=>'required|max:255',
                    'Region'=>'required|max:255',
                    'PostCode'=>'required|max:255',
                    'Country'=>'required|max:2',
                );
                $rule_message=array(
                    'Contact_first.required'=>(Session::get('Language')==='EN')?'Fill your first name':'请输入您的姓名',
                    'Contact_first.max'=>(Session::get('Language')==='EN')?'Should not over 255 characters':'您的姓名不可超过255个字符',
                    'Contact_middle.max'=>(Session::get('Language')==='EN')?'Should not over 255 characters':'您的中间名不可超过255个字符',
                    'Contact_last.required'=>(Session::get('Language')==='EN')?'Fill your last name':'请输入您的姓',
                    'Contact_last.max'=>(Session::get('Language')==='EN')?'Should not over 255 characters':'请输入您的姓',
                    'Company.max'=>(Session::get('Language')==='EN')?'SHould not over 255 characters':'您的公司名称不可超过255个字符',
                    'Telephone.required'=>(Session::get('Language')==='EN')?'Fill your telephone':'请输入您的电话',
                    'Telephone.max'=>(Session::get('Language')==='EN')?'Should not over 255 characters':'您的电话可超过255个字符',
                    'Fax.max'=>(Session::get('Language')==='EN')?'Should not over 255 characters':'请输入您的传真电话',
                    'StreetAddr.required'=>(Session::get('Language')==='EN')?'Fill your address':'请输入您的地址',
                    'StreetAddr.max'=>(Session::get('Language')==='EN')?'Should not over 16777215 characters':'您的地址不可超过16777215个字符',
                    'StreetAddr2.max'=>(Session::get('Language')==='EN')?'Should not over 16777215 characters':'您的地址不可超过16777215个字符',
                    'City.required'=>(Session::get('Language')==='EN')?'Fill the city of address':'请输入您的城市',
                    'City.max'=>(Session::get('Language')==='EN')?'Should not over 255 characters':'您的城市内容不可超过255个字元',
                    'Region.required'=>(Session::get('Language')==='EN')?'Fill the region of address':'请输入您的区域',
                    'Region.max'=>(Session::get('Language')==='EN')?'Should not over 255 characters':'您的区域内容不可超过255个字元',
                    'PostCode.required'=>(Session::get('Language')==='EN')?'Fill the post code of address':'请输入您的邮递区号',
                    'PostCode.max'=>(Session::get('Language')==='EN')?'Should not over 255 characters':'您的邮递区号不可超过255个字元',
                    'Country.required'=>(Session::get('Language')==='EN')?'Choose the country of address':'请选择您的国家',
                    'Country.max'=>(Session::get('Language')==='EN')?'Choose the country in list':'请选择您的国家'
                );
                $build_validator=Validator::make($request->all(),$rule_array,$rule_message);
                if($build_validator->fails()){
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }

                $Contact_first=trim(request('Contact_first'));
                $Contact_middle=trim(request('Contact_middle'));
                $Contact_last=trim(request('Contact_last'));
                $Company=trim(request('Company'));
                $Telephone=trim(request('Telephone'));
                $Fax=trim(request('Fax'));
                $StreetAddr=trim(request('StreetAddr'));
                $StreetAddr2=trim(request('StreetAddr2'));
                $City=trim(request('City'));
                $Region=trim(request('Region'));
                $PostCode=trim(request('PostCode'));
                $Country=trim(request('Country'));

                AddressBookData::where('MemberId',Session::get('MemberId'))->where('DefaultChoose',1)->where('AddrType',1)->update(array(
                    'DefaultChoose'=>0,
                    'updated_at'=>date('Y-m-d H:i:s')
                ));
                $updateRes=AddressBookData::where('AddressId',$addressData->AddressId)->where('MemberId',Session::get('MemberId'))->where('AddrType',1)->update(array(
                    'Contact_first'=>$Contact_first,
                    'Contact_middle'=>$Contact_middle,
                    'Contact_last'=>$Contact_last,
                    'Company'=>$Company,
                    'Telephone'=>$Telephone,
                    'Fax'=>$Fax,
                    'StreetAddr'=>$StreetAddr,
                    'StreetAddr2'=>$StreetAddr2,
                    'City'=>$City,
                    'Region'=>$Region,
                    'PostCode'=>$PostCode,
                    'Country'=>$Country,
                    'DefaultChoose'=>1,
                    'updated_at'=>date('Y-m-d H:i:s')
                ));

                if($updateRes){
                    Session::flash('badge_flag',true);
                    Session::flash('badge_flag_type','success');
                    if(Session::get('Language')==='EN'){
                        Session::flash('badge_flag_message','Updated success!');
                    }
                    else{
                        Session::flash('badge_flag_message','更新资讯成功!');
                    }
                }
                else{
                    Session::flash('badge_flag',true);
                    Session::flash('badge_flag_type','error');
                    if(Session::get('Language')==='EN'){
                        Session::flash('badge_flag_message','Updated fail!');
                    }
                    else{
                        Session::flash('badge_flag_message','更新失败!');
                    }
                }
            }
            else{
                $data['countries']=Config::get('app.countries');
                $data['addressData']=$addressData;
                $data['htmlTitle']=Session::get('Language')==='EN'?'Edit Address':'编辑帐单地址';
                return view('frontend.address.edit_address',$data);
            }
        }
        return redirect('/address');
    }

    public function shipping_address_edit(Request $request){
        $addressData=AddressBookData::where('DefaultChoose',1)->where('AddrType',2)->where('MemberId',Session::get('MemberId'))->orderBy('updated_at','DESC')->take(1)->first();
        if($addressData){
            if($request->isMethod('post')){
                $rule_array=array(
                    'Contact_first'=>'required|max:255',
                    'Contact_middle'=>'nullable|max:255',
                    'Contact_last'=>'required|max:255',
                    'Company'=>'nullable|max:255',
                    'Telephone'=>'required|max:255',
                    'Fax'=>'nullable|max:255',
                    'StreetAddr'=>'required|max:16777215',
                    'StreetAddr2'=>'nullable|max:16777215',
                    'City'=>'required|max:255',
                    'Region'=>'required|max:255',
                    'PostCode'=>'required|max:255',
                    'Country'=>'required|max:2',
                );
                $rule_message=array(
                    'Contact_first.required'=>(Session::get('Language')==='EN')?'Fill your first name':'请输入您的姓名',
                    'Contact_first.max'=>(Session::get('Language')==='EN')?'Should not over 255 characters':'您的姓名不可超过255个字符',
                    'Contact_middle.max'=>(Session::get('Language')==='EN')?'Should not over 255 characters':'您的中间名不可超过255个字符',
                    'Contact_last.required'=>(Session::get('Language')==='EN')?'Fill your last name':'请输入您的姓',
                    'Contact_last.max'=>(Session::get('Language')==='EN')?'Should not over 255 characters':'请输入您的姓',
                    'Company.max'=>(Session::get('Language')==='EN')?'SHould not over 255 characters':'您的公司名称不可超过255个字符',
                    'Telephone.required'=>(Session::get('Language')==='EN')?'Fill your telephone':'请输入您的电话',
                    'Telephone.max'=>(Session::get('Language')==='EN')?'Should not over 255 characters':'您的电话可超过255个字符',
                    'Fax.max'=>(Session::get('Language')==='EN')?'Should not over 255 characters':'请输入您的传真电话',
                    'StreetAddr.required'=>(Session::get('Language')==='EN')?'Fill your address':'请输入您的地址',
                    'StreetAddr.max'=>(Session::get('Language')==='EN')?'Should not over 16777215 characters':'您的地址不可超过16777215个字符',
                    'StreetAddr2.max'=>(Session::get('Language')==='EN')?'Should not over 16777215 characters':'您的地址不可超过16777215个字符',
                    'City.required'=>(Session::get('Language')==='EN')?'Fill the city of address':'请输入您的城市',
                    'City.max'=>(Session::get('Language')==='EN')?'Should not over 255 characters':'您的城市内容不可超过255个字元',
                    'Region.required'=>(Session::get('Language')==='EN')?'Fill the region of address':'请输入您的区域',
                    'Region.max'=>(Session::get('Language')==='EN')?'Should not over 255 characters':'您的区域内容不可超过255个字元',
                    'PostCode.required'=>(Session::get('Language')==='EN')?'Fill the post code of address':'请输入您的邮递区号',
                    'PostCode.max'=>(Session::get('Language')==='EN')?'Should not over 255 characters':'您的邮递区号不可超过255个字元',
                    'Country.required'=>(Session::get('Language')==='EN')?'Choose the country of address':'请选择您的国家',
                    'Country.max'=>(Session::get('Language')==='EN')?'Choose the country in list':'请选择您的国家'                    
                );
                $build_validator=Validator::make($request->all(),$rule_array,$rule_message);
                if($build_validator->fails()){
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }

                $Contact_first=trim(request('Contact_first'));
                $Contact_middle=trim(request('Contact_middle'));
                $Contact_last=trim(request('Contact_last'));
                $Company=trim(request('Company'));
                $Telephone=trim(request('Telephone'));
                $Fax=trim(request('Fax'));
                $StreetAddr=trim(request('StreetAddr'));
                $StreetAddr2=trim(request('StreetAddr2'));
                $City=trim(request('City'));
                $Region=trim(request('Region'));
                $PostCode=trim(request('PostCode'));
                $Country=trim(request('Country'));

                AddressBookData::where('MemberId',Session::get('MemberId'))->where('DefaultChoose',1)->where('AddrType',2)->update(array(
                    'DefaultChoose'=>0,
                    'updated_at'=>date('Y-m-d H:i:s')
                ));
                $updateRes=AddressBookData::where('AddressId',$addressData->AddressId)->where('MemberId',Session::get('MemberId'))->where('AddrType',2)->update(array(
                    'Contact_first'=>$Contact_first,
                    'Contact_middle'=>$Contact_middle,
                    'Contact_last'=>$Contact_last,
                    'Company'=>$Company,
                    'Telephone'=>$Telephone,
                    'Fax'=>$Fax,
                    'StreetAddr'=>$StreetAddr,
                    'StreetAddr2'=>$StreetAddr2,
                    'City'=>$City,
                    'Region'=>$Region,
                    'PostCode'=>$PostCode,
                    'Country'=>$Country,
                    'DefaultChoose'=>1,
                    'updated_at'=>date('Y-m-d H:i:s')
                ));

                if($updateRes){
                    Session::flash('badge_flag',true);
                    Session::flash('badge_flag_type','success');
                    Session::flash('badge_flag_message','Updated success!');
                }
                else{
                    Session::flash('badge_flag',true);
                    Session::flash('badge_flag_type','error');
                    Session::flash('badge_flag_message','Updated fail!');      
                }
            }
            else{
                $data['countries']=Config::get('app.countries');
                $data['addressData']=$addressData;
                $data['htmlTitle']=Session::get('Language')==='EN'?'Edit Address':'编辑配送地址';
                return view('frontend.address.edit_address',$data);
            }
        }
        return redirect('/address');        
    }

    public function logout(){
        Session::forget('MemberId');
        Session::forget('FirstName');
        Session::forget('LastName');
        return redirect('/');
    }
}