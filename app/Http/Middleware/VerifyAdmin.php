<?php

namespace App\Http\Middleware;

use Closure;
use App\AdminData;
use App\GroupData;

class VerifyAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //需驗證登入否(session)
        $AdminId=$request->session()->get('AdminId');
        if(!$AdminId){
            if($request->ajax()){
                return Response::make("",401);
            }
            else{
                return redirect('admin/login');
            }
        }
        else{
            $AdminAuthority=AdminData::where('AdminId',$AdminId)->where('Status',1)->select('AdminAuthority')->take(1)->first();
            if($AdminAuthority){
                $canAccess=true;
                if(intval($AdminAuthority->AdminAuthority)!=99999){
                    $GroupInfo=GroupData::where('GroupId',$AdminAuthority->AdminAuthority)->take(1)->first();
                    if($GroupInfo){
                        $Funcs=json_decode($GroupInfo->Funcs);
                        $ChildFuncs=json_decode($GroupInfo->ChildFuncs);
                        $mainFunc=trim(\Request::segment(2));
                        $detailFunc=trim(\Request::segment(3));
                        if($mainFunc!==''){
                            if(in_array($mainFunc,$Funcs)){
                                if($detailFunc!==''){
                                    if(isset($ChildFuncs->{$mainFunc})){
                                        if(!in_array($detailFunc,$ChildFuncs->{$mainFunc})){
                                            $canAccess=false;
                                        }
                                    }
                                }
                            }
                            else{
                                $canAccess=false;
                            }
                        }
                    }
                }
            }
            else{
                $canAccess=false;
                echo '查無您的帳號資料，是否未啟用？';
                die();
            }

            if($canAccess){
                $currentAction=\Route::currentRouteAction();
                list($controller,$method)=explode('@',$currentAction);
                if($method=='loginIndex'){
                    // get first view in backend
                    if(intval($AdminAuthority->AdminAuthority)!==99999){
                        if($GroupInfo){
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
                return $next($request);
            }
            else{
                echo '權限不符。';
                die();
            }
        }
    }
}
