<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\AdminData;
use Widget_Helper;
use Validator;
use Session;

class AdminAuthoritiesController extends Controller
{                              
    public function __construct(){

    }

    public function accounts(Request $request){
        $uid=Session::get('AdminId');
        $authority=AdminData::where('AdminId',$uid)->select('AdminAuthority')->take(1)->first();
    	$accounts=AdminData::leftJoin('departments','admins.Department','=','departments.DepartmentId')->where('AdminAuthority','!=',1)->orderBy('AdminId','ASC')->orderBy('AdminAuthority','ASC')->orderBy('admins.updated_at','DESC')->paginate(10);
    	$data['accounts']=$accounts;
        $data['currentPage']='authorities';
        $data['header_title']='帳號列表';
        return view('admin.authorities.adminAccounts',$data);
    }

    public function self_data(Request $request){
        $uid=Session::get('AdminId');
        $self_data=AdminData::where('AdminId',$uid)->take(1)->first();
        if($self_data){
            if($request->isMethod('post')){
                $validator_rule_messages=[
                    'AdminAccount.required'=>'請輸入帳號',
                    'AdminAccount.max'=>'帳號不可超過100個字元',
                    'AdminPassword.min'=>'密碼最少需輸入6個字元',
                    'AdminPassword.max'=>'密碼不可超過100個字元',
                    'AdminName.required'=>'請輸入名稱',
                    'AdminName.max'=>'名稱不可超過255個字元'
                ];
                $validator_rule_array=array(
                    'AdminAccount'=>'required|max:100',
                    'AdminPassword'=>'nullable|min:6|max:100',
                    'AdminName'=>'required|max:255',
                );
                $build_validator=Validator::make($request->all(),$validator_rule_array,$validator_rule_messages);
                if($build_validator->fails()){
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }
                
                $AdminAccount=trim(request('AdminAccount'));
                $AdminAuthority=$self_data->AdminAuthority;
                $AdminPassword=trim(request('AdminPassword'));
                $AdminName=trim(request('AdminName'));

                $accountExist=AdminData::where('AdminAccount',$AdminAccount)->where('AdminId','!=',$uid)->take(1)->first();
                if($accountExist){
                    Session::flash('maintain_message',true);
                    Session::flash('maintain_message_warning','該帳號已經存在，請重新輸入。');
                	return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }
                else{
                    $updateArr=array(
                        'AdminAccount'=>$AdminAccount,
                        'AdminName'=>$AdminName,
                        'updated_at'=>date('Y-m-d H:i:s')
                    );
                    if($AdminPassword!==''){
                        $storePwd=password_hash($AdminPassword,PASSWORD_DEFAULT);
                        $updateArr['AdminPassword']=$storePwd;
                    }

                    $updateRes=AdminData::where('AdminId',$uid)->update($updateArr);
                    if($updateRes){
                        Session::put('AdminAccount',$AdminAccount);
                        Session::put('AdminName',$AdminName);
                        Session::flash('maintain_message',true);
                        Session::flash('maintain_message_success','更新完成。');
                        return redirect('/admin/authorities/self');
                    }
                    else{
                        Session::flash('maintain_message',true);
                        Session::flash('maintain_message_fail','更新失敗。');
                    }
                }
            }
            $self_data=AdminData::where('AdminId',$uid)->take(1)->first();
            $data['self_data']=$self_data;
            $data['currentPage']='authorities';
            $data['header_title']='修改個人資料';
            return view('admin.authorities.self_data',$data);
        }
        return redirect('/admin');
    }

    public function departments(){
        $departments=DB::table('departments')->orderBy('OrderNumber','ASC')->get();
        $data['departments']=$departments;
        $data['currentPage']='authorities';
        $data['header_title']='單位列表';
        return view('admin.authorities.adminDepartments',$data);        
    }

    public function add_department(Request $request){
        if($request->isMethod('post')){
            $validator_rule_messages=[
                'Department.required'=>'請輸入單位名稱',
            ];
            $validator_rule_array=array(
                'Department'=>'required'
            );
            $build_validator=Validator::make($request->all(),$validator_rule_array,$validator_rule_messages);
            if($build_validator->fails()){
                return redirect()->back()->withInput()->withErrors($build_validator->errors());
            }

            $Department=trim(request('Department'));
            $OrderNumber=Widget_Helper::tablemaxindexadd('Departments','OrderNumber','');
            if(DB::table('departments')->insert(array(
                'Department'=>$Department,
                'OrderNumber'=>$OrderNumber,
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s')
            ))){
                return redirect('/admin/departments');
            }
            else{
                Session::flash('maintain_message',true);
                Session::flash('maintain_message_fail','新增單位失敗。');
                return redirect()->back()->withInput()->withErrors($build_validator->errors());
            }
        }
        $data['currentPage']='authorities';
        $data['header_title']='新增單位(部門)';        
        return view('admin.authorities.add_department',$data);
    }

    public function edit_department($id,Request $request){
        $id=intval($id);
        $departmentInfo=DB::table("departments")->where('DepartmentId',$id)->take(1)->first();
        if($departmentInfo){
            if($request->isMethod('post')){
                $validator_rule_messages=[
                    'Department.required'=>'請輸入單位名稱',
                    'OrderNumber.integer'=>'排序僅能輸入整數',
                    'OrderNumber.min'=>'排序最小值需大於零'
                ];
                $validator_rule_array=array(
                    'Department'=>'required',
                    'OrderNumber'=>'nullable|integer|min:1'
                );
                $build_validator=Validator::make($request->all(),$validator_rule_array,$validator_rule_messages);
                if($build_validator->fails()){
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }

                $Department=request('Department');
                $OrderNumber=intval(request('OrderNumber'));
                $updateRes=DB::table('departments')->where('DepartmentId',$id)->update(array(
                    'Department'=>$Department,
                    'OrderNumber'=>$OrderNumber,
                    'updated_at'=>date('Y-m-d H:i:s')
                ));
                if(!$updateRes){
                    Session::flash('maintain_message',true);
                    Session::flash('maintain_message_fail','更新單位失敗。');
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }
            }
            else{
                $data['departmentInfo']=$departmentInfo;
                $data['currentPage']='authorities';
                $data['header_title']='編輯單位(部門)';
                return view('admin.authorities.edit_department',$data);
            }
        }
        return redirect('/admin/departments');
    }

    public function setDepartmentOrder(Request $request){
        $way=trim($request->input('way'));
        $id=intval($request->input('id'));
        $oldDepartment=DB::table('departments')->where('DepartmentId',$id)->take(1)->first();
        if($way==='down'){
            $interactDepartment=DB::table('departments')->where('OrderNumber','>',$oldDepartment->OrderNumber)->orderBy('OrderNumber','ASC')->orderBy('updated_at','DESC')->take(1)->first();
        }
        elseif($way==='up'){
            $interactDepartment=DB::table('departments')->where('OrderNumber','<',$oldDepartment->OrderNumber)->orderBy('OrderNumber','ASC')->orderBy('updated_at','DESC')->take(1)->first();
        }

        if($interactDepartment){
            DB::table('departments')->where('DepartmentId',$id)->update(array(
                'OrderNumber'=>$interactDepartment->OrderNumber,
                'updated_at'=>date('Y-m-d H:i:s')
            ));
            DB::table('departments')->where('DepartmentId',$interactDepartment->DepartmentId)->update(array(
                'OrderNumber'=>$oldDepartment->OrderNumber,
                'updated_at'=>date('Y-m-d H:i:s')
            ));
        }
        return redirect('/admin/departments');
    }

    public function delete_department($id){
        DB::table('departments')->where('DepartmentId',$id)->delete();
        return redirect('/admin/departments');
    }

    public function add_account(Request $request){
        if($request->isMethod('post')){
            $validator_rule_messages=[
                'Department.required'=>'請選擇單位',
                'OrderNumber.integer'=>'請選擇正確的單位',
                'AdminName.required'=>'請輸入姓名',
                'AdminName.max'=>'姓名最多輸入255個字元',
                'AdminAccount.required'=>'請輸入帳號',
                'AdminAccount.max'=>'帳號最多輸入100個字元',
                'AdminPassword.required'=>'請輸入密碼',
                'AdminPassword.min'=>'密碼最少需輸入6個字元',
                'AdminPassword.max'=>'密碼不可超過100個字元',
                'email.email'=>'Email格式有誤',
                'phone-ext.integer'=>'分機僅能輸入整數',
                'status.required'=>'請選擇是否啟用',
                'status.integer'=>'請輸入正確的啟用狀態'
            ];
            $validator_rule_array=array(
                'Department'=>'required|integer',
                'AdminName'=>'required|max:255',
                'AdminAccount'=>'required|max:100',
                'AdminPassword'=>'required|min:6|max:100',
                'email'=>'nullable|email',
                'phone-ext'=>'nullable|integer',
                'status'=>'required|integer'
            );
            $build_validator=Validator::make($request->all(),$validator_rule_array,$validator_rule_messages);
            if($build_validator->fails()){
                return redirect()->back()->withInput()->withErrors($build_validator->errors());
            }

            $Department=intval(request('Department'));
            $AdminName=request('AdminName');
            $AdminAccount=request('AdminAccount');
            $AdminPassword=request('AdminPassword');
            $email=request('email');
            $phone=request('phone');
            $phone_ext=intval(request('phone-ext'));
            $status=intval(request('status'));
            $accountExist=AdminData::where('AdminAccount',$AdminAccount)->take(1)->first();
            if($accountExist){
                Session::flash('maintain_message',true);
                Session::flash('maintain_message_warning','該帳號已存在，請重新輸入。');
                return redirect()->back()->withInput()->withErrors($build_validator->errors());
            }
            else{
                if(strlen($phone_ext)>4){
                    $build_validator->errors()->add('phone-ext','分機最多輸入4位數字');
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }
                $newAdmin=new AdminData;
                $newAdmin->AdminAuthority=0;
                $newAdmin->Department=$Department;
                $newAdmin->AdminAccount=$AdminAccount;
                $newAdmin->AdminPassword=password_hash($AdminPassword,PASSWORD_DEFAULT);
                $newAdmin->AdminName=$AdminName;
                $newAdmin->Email=$email;
                $newAdmin->Phone=$phone;
                $newAdmin->PhoneExt=$phone_ext;
                $newAdmin->Status=$status;
                $newAdmin->created_at=date('Y-m-d H:i:s');
                $newAdmin->updated_at=date('Y-m-d H:i:s');
                if($newAdmin->save()){
                    return redirect('/admin/accounts');
                }
                else{
                    Session::flash('maintain_message',true);
                    Session::flash('maintain_message_fail','資料庫操作失敗。');
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }
            }
        }
        $departments=DB::table('departments')->orderBy('OrderNumber','ASC')->orderBy('updated_at','DESC')->get();
        $data['departments']=$departments;
        $data['currentPage']='authorities';
        $data['header_title']='新增帳號';
        return view('admin.authorities.add_account',$data);
    }

    public function mod_account($id,Request $request){
        $id=intval($id);
        $accountInfo=AdminData::where('AdminId',$id)->take(1)->first();
        if($accountInfo){
            if($request->isMethod('post')){
                $validator_rule_messages=[
                    'Department.required'=>'請選擇單位',
                    'OrderNumber.integer'=>'請選擇正確的單位',
                    'AdminName.required'=>'請輸入姓名',
                    'AdminName.max'=>'姓名最多輸入255個字元',
                    'AdminAccount.required'=>'請輸入帳號',
                    'AdminAccount.max'=>'帳號最多輸入100個字元',
                    'AdminPassword.min'=>'密碼最少需輸入6個字元',
                    'AdminPassword.max'=>'密碼不可超過100個字元',
                    'email.email'=>'Email格式有誤',
                    'phone-ext.integer'=>'分機僅能輸入整數',
                    'status.required'=>'請選擇是否啟用',
                    'status.integer'=>'請輸入正確的啟用狀態'
                ];
                $validator_rule_array=array(
                    'Department'=>'required|integer',
                    'AdminName'=>'required|max:255',
                    'AdminAccount'=>'required|max:100',
                    'AdminPassword'=>'nullable|min:6|max:100',
                    'email'=>'nullable|email',
                    'phone-ext'=>'nullable|integer',
                    'status'=>'required|integer'
                );
                $build_validator=Validator::make($request->all(),$validator_rule_array,$validator_rule_messages);
                if($build_validator->fails()){
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }

                $Department=intval(request('Department'));
                $AdminName=trim(request('AdminName'));
                $AdminAccount=trim(request('AdminAccount'));
                $AdminPassword=trim(request('AdminPassword'));
                $email=trim(request('email'));
                $phone=trim(request('phone'));
                $phone_ext=intval(request('phone-ext'));
                $status=intval(request('status'));
                $accountExist=AdminData::where('AdminAccount',$AdminAccount)->where('AdminId','!=',$id)->take(1)->first();
                if($accountExist){
                    Session::flash('maintain_message',true);
                    Session::flash('maintain_message_warning','該帳號已存在，請重新輸入。');
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }
                else{
                    if(strlen($phone_ext)>4){
                        $build_validator->errors()->add('phone-ext','分機最多輸入4位數字');
                        return redirect()->back()->withInput()->withErrors($build_validator->errors());
                    }
                    $updateData=array(
                        'AdminAccount'=>$AdminAccount,
                        'AdminName'=>$AdminName,
                        'Department'=>$Department,
                        'Email'=>$email,
                        'Phone'=>$phone,
                        'PhoneExt'=>$phone_ext,
                        'Status'=>$status,
                        'updated_at'=>date('Y-m-d H:i:s')
                    );
                    if($AdminPassword!==''){
                        $updateData['AdminPassword']=password_hash($AdminPassword,PASSWORD_DEFAULT);
                    }
                    if(AdminData::where('AdminId',$id)->update($updateData)){
                        return redirect('/admin/accounts');
                    }
                    else{
                        Session::flash('maintain_message',true);
                        Session::flash('maintain_message_fail','資料庫操作失敗。');
                        return redirect()->back()->withInput()->withErrors($build_validator->errors());                        
                    }
                }
            }
            else{
                $departments=DB::table('departments')->orderBy('OrderNumber','ASC')->orderBy('updated_at','DESC')->get();
                $data['departments']=$departments;
                $data['accountInfo']=$accountInfo;
                $data['currentPage']='authorities';
                $data['header_title']='編輯帳號';
                return view('admin.authorities.edit_account',$data);                
            }
        }
        return redirect('/admin/accounts');
    }

    public function delete_account($id,Request $request){
        $id=intval($id);
        AdminData::where('AdminId',$id)->where('AdminAuthority','!=',1)->delete();
        return redirect('/admin/accounts');
    }
}