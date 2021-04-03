<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\AdminData;
use App\CategoriesData;
use App\GroupData;
use App\Products\WinesData;
use Widget_Helper;
use Session;

class AdminPageEnterController extends Controller
{
	public function loginIndex(Request $request){
        $AdminId=$request->session()->get('AdminId');
        if(!$AdminId){
		    return view('admin.adminLogin');
        }
        else{
            return redirect('admin/dashboard');
        }
	}

    public function verifyLogin(Request $request){
        $AdminId=$request->session()->get('AdminId');
        if(!$AdminId){
            if($request->isMethod('post')){
                $account=trim(request('account'));
                $password=trim(request('password'));
                if($account!=''&&$password!=''&&!Widget_Helper::inject_check($account)&&!Widget_Helper::inject_check($password)&&!Widget_Helper::xss_check($account)&&!Widget_Helper::xss_check($password)){
                    // 未出現危險字元 執行登入
                    $adminData=AdminData::login($account,$password);
                    if($adminData){
                        $AdminId=$adminData->AdminId;
                        $AdminAccount=$adminData->AdminAccount;
                        $AdminName=$adminData->AdminName;
                        $request->session()->put('AdminId',$AdminId);
                        $request->session()->put('AdminAccount',$AdminAccount);
                        $request->session()->put('AdminName',$AdminName);

                        // get first view in backend
                        $AdminAuthority=$adminData->AdminAuthority;
                        $GroupInfo=GroupData::where('GroupId',$AdminAuthority)->take(1)->first();
                        if(intval($AdminAuthority)!==99999){
                            if($GroupInfo){
                                $Funcs=json_decode($GroupInfo->Funcs);
                                $ChildFuncs=json_decode($GroupInfo->ChildFuncs);
                                if(count($Funcs)>0){
                                    $mainFunc=$Funcs[0];
                                    $detailFunc='';
                                    if(isset($ChildFuncs->$mainFunc)&&count($ChildFuncs->$mainFunc)>0){
                                        $tempArr=$ChildFuncs->$mainFunc;
                                        $detailFunc=$tempArr[0];
                                    }
                                }
                                return redirect("/admin/$mainFunc/$detailFunc");
                            }
                        }
                        else{
                            return redirect('admin/dashboard');
                        }
                    }
                    $request->session()->flash('message','無法登入，請確認您的帳號或密碼!');
                    return redirect('admin/login');
                }
                else{
                    return redirect('admin/login');
                }
            }
            else{
                return redirect('admin/login');
            }
        }
        else{
            return redirect('admin/login');
        }
    }

    public function adminDashboard(Request $request){
        $onShelfWines=WinesData::where(array(
            array('Status','=',1),
            array('NoOffShelf','!=',1),
            array('ActDate','<=',date('Y-m-d')),
            array('EndDate','>',date('Y-m-d'))
        ))->orWhere(array(
            array('Status','=',1),
            array('NoOffShelf','=',1),
        ))->count();

        $onShelfSalexMix=DB::table('sales_mix')->where(array(
            array('Status','=',1),
            array('NoOffShelf','!=',1),
            array('ActDate','<=',date('Y-m-d')),
            array('EndDate','>',date('Y-m-d'))
        ))->orWhere(array(
            array('Status','=',1),
            array('NoOffShelf','=',1),
        ))->count();

        $data['onShelfWines']=$onShelfWines;
        $data['onShelfSalexMix']=$onShelfSalexMix;
        $data['currentPage']='dashboard';
        $adminID=$request->session()->get('AdminId');
        $account=$request->session()->get('AdminAccount');
        $data['header_title']='總覽';
        return view('admin.adminDashboard',$data);
    }

    public function searchCategory(Request $request){
        $searchKey=Widget_Helper::mysql_escape(request('searchKey'));
        $prev_url=request('prev_url');
        if($prev_url!==''){
            $Sort=intval(request('Sort'));
            $searchResults=CategoriesData::where('Sort',$Sort)->where('Cate','like','%'.$searchKey.'%')->get();
            $data['searchKey']=$searchKey;
            $data['searchResults']=$searchResults;
            $data['currentPage']=$prev_url;
            $data['Sort']=$Sort;
            $data['header_title']='類別搜尋';
            return view('admin.searchCatg',$data);
        }
        return redirect('/admin');
    }

    public function logout(){
        Session::flush();
        return redirect('/admin');
    }
}
