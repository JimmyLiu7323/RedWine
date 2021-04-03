<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\AdminData;
use App\BannersData;
use App\GroupData;
use Widget_Helper;
use Validator;
use Session;

class AdminBannersController extends Controller
{                              
    public function __construct(){
        
    }

    public function banners(Request $request){
    	$banners=BannersData::orderby('BannerOrder','ASC')->orderBy('updated_at','DESC')->get();
        foreach($banners as $banner){
            $banner->OnText="未上架";
            $banner->OffText="";
            if(intval($banner->BannerOnStatus===1)){
                if($banner->BannerDate>date('Y-m-d H:i:s',strtotime($banner->BannerOnDate." ".$banner->BannerOnTime))){
                    $banner->OnText="上架中";
                }
            }

            if(intval($banner->BannerOffStatus)===0){
                $banner->OnText = "";
                $banner->OffText="永不下架";
            }
            else{
                if(date('Y-m-d H:i:s')>date('Y-m-d H:i:s',strtotime($banner->BannerOffDate." ".$banner->BannerOffTime))){
                    $banner->OffText="已下架";
                }
            }
        }
    	$data['banners']=$banners;
        $data['currentPage']='banners';
        $data['header_title']='Banners';
        return view('admin.banners.adminBannerList',$data);
    }

    public function add_mod_banner(Request $request){
        $data=array(
            'currentPage'=>'banners',
            'header_title'=>'Banner maintain',
            'action'=>'add',
            'BannerImage'=>'',
            'BannerLink'=>'',
            'BannerTitle'=>'',
            'BannerSubtitle'=>'',
            'BannerOnStatus'=>'',
            'BannerOnDate'=>'',
            'BannerOnTime'=>'',
            'BannerOffStatus'=>'',
            'BannerOffDate'=>'',
            'BannerOffTime'=>'',
            'BannerOrder'=>1
        );

        $validator_rule_array=array(
            'BannerOrder'=>'required|integer',
            'BannerImage'=>'required|max:255',
            'BannerTitle'=>'nullable|max:20',
            'BannerSubtitle'=>'nullable|max:20',
            'BannerLink'=>'nullable|max:255',
            'BannerOnStatus'=>'required|integer',
            'BannerOnDate'=>'required|date',
            'BannerOnTime'=>'required|date_format:H:i',
            'BannerOffStatus'=>'required|integer',
            'BannerOffDate'=>'nullable|date',
            'BannerOffTime'=>'nullable|date_format:H:i'
        );

        $bannerId=intval(request('id'));
        if($bannerId===0){
            if($request->isMethod('post')){                
                $build_validator=Validator::make($request->all(),$validator_rule_array);
                if($build_validator->fails()){
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }
                $BannerOrder=intval(request('BannerOrder'));
                $BannerImage=trim(request('BannerImage'));
                $BannerTitle=trim(request('BannerTitle'));
                $BannerSubtitle=trim(request('BannerSubtitle'));
                $BannerLink=trim(request('BannerLink'));
                $BannerOnStatus=intval(request('BannerOnStatus'));
                $BannerOnDate=trim(request('BannerOnDate'));
                $BannerOnTime=trim(request('BannerOnTime'));
                $BannerOffStatus=intval(request('BannerOffStatus'));
                $BannerOffDate=trim(request('BannerOffDate'));
                $BannerOffTime=trim(request('BannerOffTime'));

                if(BannersData::insert(array(
                    'BannerOrder'=>$BannerOrder,
                    'BannerImage'=>$BannerImage,
                    'BannerTitle'=>$BannerTitle,
                    'BannerSubtitle'=>$BannerSubtitle,
                    'BannerLink'=>$BannerLink,
                    'BannerOnStatus'=>$BannerOnStatus,
                    'BannerOnDate'=>$BannerOnDate,
                    'BannerOnTime'=>$BannerOnTime,
                    'BannerOffStatus'=>$BannerOffStatus,
                    'BannerOffDate'=>$BannerOffDate,
                    'BannerOffTime'=>$BannerOffTime,
                    'created_at'=>date('Y-m-d H:i:s'),
                    'updated_at'=>date('Y-m-d H:i:s')
                ))){
                    return redirect('/admin/banners');
                }
                else{
                    Session::flash('maintain_message',true);
                    Session::flash('maintain_message_fail','Failed to add banner');
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }
            }
            return view('admin.banners.add_mod',$data);
        }
        else{
            $data['action']='mod';
            $bannerInfo=BannersData::where('BannerId',$bannerId)->take(1)->first()->toArray();
            if($bannerInfo){
                if($request->isMethod('post')){
                    $build_validator=Validator::make($request->all(),$validator_rule_array);
                    if($build_validator->fails()){
                        return redirect()->back()->withInput()->withErrors($build_validator->errors());
                    }
                    $BannerOrder=intval(request('BannerOrder'));
                    $BannerImage=trim(request('BannerImage'));
                    $BannerTitle=trim(request('BannerTitle'));
                    $BannerSubtitle=trim(request('BannerSubtitle'));
                    $BannerLink=trim(request('BannerLink'));
                    $BannerOnStatus=intval(request('BannerOnStatus'));
                    $BannerOnDate=trim(request('BannerOnDate'));
                    $BannerOnTime=trim(request('BannerOnTime'));
                    $BannerOffStatus=intval(request('BannerOffStatus'));
                    $BannerOffDate=trim(request('BannerOffDate'));
                    $BannerOffTime=trim(request('BannerOffTime'));
                    $updateRes=BannersData::where('BannerId',$bannerId)->update(array(
                        'BannerOrder'=>$BannerOrder,
                        'BannerImage'=>$BannerImage,
                        'BannerTitle'=>$BannerTitle,
                        'BannerSubtitle'=>$BannerSubtitle,
                        'BannerLink'=>$BannerLink,
                        'BannerOnStatus'=>$BannerOnStatus,
                        'BannerOnDate'=>$BannerOnDate,
                        'BannerOnTime'=>$BannerOnTime,
                        'BannerOffStatus'=>$BannerOffStatus,
                        'BannerOffDate'=>$BannerOffDate,
                        'BannerOffTime'=>$BannerOffTime,
                        'updated_at'=>date('Y-m-d H:i:s')                        
                    ));
                    if($updateRes){
                        return redirect('/admin/banners');
                    }
                    else{
                        Session::flash('maintain_message',true);
                        Session::flash('maintain_message_fail','Fail to update banner');
                        return redirect()->back()->withInput()->withErrors($build_validator->errors());                        
                    }
                }
                foreach($bannerInfo as $key=>$val){
                    $data[$key]=$val;
                }
                return view('admin.banners.add_mod',$data);
            }
            return redirect('admin/banners');
        }
    }

    public function delete_banner(Request $request){
        $bannerId=intval(request('id'));
    	if(!BannersData::where('BannerId',$bannerId)->delete()){
    		Session::flash('maintain_message',true);
    		Session::flash('maintain_message_fail','刪除Banner失敗。');
    	}
    	return redirect('/admin/banners');
    }
}