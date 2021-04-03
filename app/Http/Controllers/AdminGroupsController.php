<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\AdminData;
use App\GroupData;
use Validator;
use Session;

class AdminGroupsController extends Controller
{                              
    public function __construct(){

    }

    public function group_list(Request $request){
        $groups=GroupData::paginate(10);
        $data['groups']=$groups;
        $data['currentPage']='sys_setting';
        $data['header_title']='群組列表';
        return view('admin.authorities.adminGroups',$data);
    }

    public function add_group(Request $request){
        if($request->isMethod('post')){
            $validator_rule_messages=[
                'Group.required'=>'請輸入群組名稱',
                'Group.max'=>'群組名稱最多能輸入100個字元',
                'GroupDesc.max'=>'群組說明最多能輸入40個字元',
                'funcs.array'=>'大項功能僅能為陣列',
                'smallFuncs.array'=>'細項功能僅能為陣列',
                'smallFuncs.*.array'=>'細項功能僅能為陣列'
            ];
            $validator_rule_array=array(
                'Group'=>'required|max:100',
                'GroupDesc'=>'nullable|max:40',
                'funcs'=>'nullable|array',
                'smallFuncs'=>'nullable|array',
                'smallFuncs.*'=>'nullable|array'
            );
            $build_validator=Validator::make($request->all(),$validator_rule_array,$validator_rule_messages);
            if($build_validator->fails()){
                return redirect()->back()->withInput()->withErrors($build_validator->errors());
            }

            $Group=trim(request('Group'));
            $GroupDesc=trim(request('GroupDesc'));
            $funcs=request('funcs');
            $smallFuncs=request('smallFuncs');
            $newGroup=new GroupData;
            $newGroup->GroupName=$Group;
            $newGroup->GroupDesc=$GroupDesc;
            $newGroup->Funcs=json_encode($funcs);
            $newGroup->ChildFuncs=json_encode($smallFuncs);
            $newGroup->created_at=date('Y-m-d H:i:s');
            $newGroup->updated_at=date('Y-m-d H:i:s');
            if($newGroup->save()){
                return redirect('/admin/groups');
            }
            else{
                Session::flash('maintain_message',true);
                Session::flash('maintain_message_fail','新增群組失敗。');
                return redirect()->back()->withInput()->withErrors($build_validator->errors());
            }
        }
        $data['currentPage']='sys_setting';
        $data['header_title']='新增群組';
        return view('admin.authorities.add_group',$data);
    }

    public function edit_group($groupId,Request $request){
        $groupId=intval($groupId);
        $groupInfo=GroupData::where('GroupId',$groupId)->take(1)->first();
        if($groupInfo){
            if($request->isMethod('post')){
                $validator_rule_messages=[
                    'Group.required'=>'請輸入群組名稱',
                    'Group.max'=>'群組名稱最多能輸入100個字元',
                    'GroupDesc.max'=>'群組說明最多能輸入40個字元',
                    'funcs.array'=>'大項功能僅能為陣列',
                    'smallFuncs.array'=>'細項功能僅能為陣列',
                    'smallFuncs.*.array'=>'細項功能僅能為陣列'
                ];
                $validator_rule_array=array(
                    'Group'=>'required|max:100',
                    'GroupDesc'=>'nullable|max:40',
                    'funcs'=>'nullable|array',
                    'smallFuncs'=>'nullable|array',
                    'smallFuncs.*'=>'nullable|array'
                );
                $build_validator=Validator::make($request->all(),$validator_rule_array,$validator_rule_messages);
                if($build_validator->fails()){
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }

                $Group=trim(request('Group'));
                $GroupDesc=trim(request('GroupDesc'));
                $funcs=request('funcs');
                $smallFuncs=request('smallFuncs');
                $updateRes=GroupData::where('GroupId',$groupId)->update(array(
                    'GroupName'=>$Group,
                    'GroupDesc'=>$GroupDesc,
                    'Funcs'=>json_encode($funcs),
                    'ChildFuncs'=>json_encode($smallFuncs),
                    'updated_at'=>date('Y-m-d H:i:s')
                ));
                if($updateRes){
                    return redirect('/admin/groups');
                }
                else{
                    Session::flash('maintain_message',true);
                    Session::flash('maintain_message_fail','更新群組失敗。');
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }                
            }
            $data['groupInfo']=$groupInfo;
            $data['currentPage']='sys_setting';
            $data['header_title']='編輯群組';
            return view('admin.authorities.edit_group',$data);
        }
        return redirect('/admin/groups');
    }

    public function delete_group($groupId){
        GroupData::where('GroupId',$groupId)->delete();
        return redirect('/admin/groups');
    }

    public function group_members($groupId,Request $request){
        $group=GroupData::where('GroupId',$groupId)->take(1)->first();
        if($group){
            if($request->isMethod('post')){
                $groupMember=request('groupMember');
                if(!is_array($groupMember))
                    $groupMember=array();
                foreach($groupMember as $user){
                    AdminData::where('AdminId',$user)->update(array(
                        'AdminAuthority'=>$groupId,
                        'updated_at'=>date('Y-m-d H:i:s')
                    ));
                }
            }
            else{
                $data['group']=$group;
                $accounts=AdminData::where('Status',1)->where('AdminAuthority','!=',99999)->orderBy('AdminId','ASC')->get();
                $data['currentPage']='sys_setting';
                $data['header_title']='群組人員維護';
                $data['accounts']=$accounts;
                return view('admin.authorities.edit_group_member',$data);
            }
        }
        return redirect('/admin/groups');
    }
}