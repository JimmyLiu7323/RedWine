<?php
namespace App\Http\Controllers\Frontend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Products\CasesData;
use App\Products\SalesMixData;
use App\Products\WinesData;
use App\Products\ProductOptionData;
use App\Products\ProductOptionSetData;

use Session;

class CaseDealsController extends Controller
{                              
    public function __construct(){
        parent::__construct();
    }

    public function index($caseId=false){
        $page=1;
        if(request('page')!='')
            $page=intval(request('page'));
        $data['page']=$page;

        $takeItems=10;
        $order="created_at";
        $allowOrder=array("stocks","price","name","created_at");
        $convertOrder=array(
            "stocks"=>"Stocks",
            "price"=>"Price",
            "name"=>"MixName",
            "created_at"=>"created_at"
        );
        $dir='asc';

        if(request('limit')!=""){
            $takeItems=intval(request('limit'));
        }
        if(request('limit')=='all'){
            $takeItems='all';
        }
        $data['takeItems']=$takeItems;

        if(in_array(request('order'),$allowOrder)){
            $order=request('order');
        }
        $data['order']=$order;        

        $allowDir=array("asc","desc");
        if(in_array(request('dir'),$allowDir)){
            $dir=request('dir');
        }
        $data['dir']=$dir;

        $data['case_deals_subtitle'] = '';
        $data['case_deals_subtitle_en'] = '';
        $data['case_deals_description'] = '';
        $data['case_deals_description_en'] = '';
        $data['case_deals_impact_image'] = '';

        $selectedCase=false;
        $whereCondition=array();
        if($caseId!==false){
            $caseId=intval($caseId);
            $selectedCase=$caseId;
            array_push($whereCondition,array('CaseId','=',$caseId));
        }
        else{
            $case_deals_setting=array(
                'case_deals_description',
                'case_deals_description_en',
                'case_deals_impact_image',
                'case_deals_subtitle',
                'case_deals_subtitle_en'
            );
            
            $case_deals_setting_query=DB::table('template_static_data')->whereIn('DataKey',$case_deals_setting)->select('Data','DataKey')->get();
            foreach($case_deals_setting_query as $setValue){
                $data[$setValue->DataKey] = $setValue->Data;
            }            
        }

        $cases=CasesData::where('Status',1)->orderBy('OrderNumber','ASC');
        if(!empty($whereCondition)){
            $cases->where($whereCondition);
        }
        $cases=$cases->get();
        if($cases->count()>0){
            foreach($cases as $case){
                if(Session::get('Language')==='EN'&&trim($case->CaseNameEn)!==''){
                    $case->CaseName=$case->CaseNameEn;
                }
                if(Session::get('Language')==='EN'&&trim($case->MetaDescEn)!==''){
                    $case->MetaDesc=$case->MetaDescEn;
                }
                if(Session::get('Language')==='EN'&&trim($case->MetaKeywordsEn)!==''){
                    $case->MetaKeywords=$case->MetaKeywordsEn;
                }                    
            }
            $data['cases']=$cases;

            $topicsQuery=SalesMixData::join('cases','cases.CaseId','=','sales_mix.ParentCase')->where(array(
                array('cases.Status',1),
                array('sales_mix.Status',1),
                array('ActDate','<=',date('Y-m-d')),
                array('EndDate','>=',date('Y-m-d'))
            ))->orWhere(array(
                array('cases.Status',1),
                array('sales_mix.Status',1),
                array('ActDate','<=',date('Y-m-d')),
                array('NoOffShelf','=',1)
            ))->orderBy("sales_mix.".$convertOrder[$order],strtoupper($dir))->orderBy('cases.OrderNumber','ASC')->orderBy('sales_mix.OrderNumber','ASC');
            if(!empty($whereCondition))
                $topicsQuery->where($whereCondition);

            if($takeItems=='all'){
                $topicsQuery=$topicsQuery->get();
            }
            else{
                $topicsQuery=$topicsQuery->paginate($takeItems);
            }
            $data['topicsQuery'] = $topicsQuery;

            $topics=array();
            foreach($topicsQuery as $topic){
                if(!isset($topics[$topic->ParentCase])){
                    $topics[$topic->ParentCase]=array(
                        'title'=>(Session::get('Language')==='EN'&&trim($topic->CaseNameEn)!=='')?$topic->CaseNameEn:$topic->CaseName,
                        'contents'=>array(),
                        'contentQuery'=>$topicsQuery
                    );
                }

                if($hasOption = ProductOptionSetData::where('ProductType','topic')->where('ProductId',$topic->MixId)->take(1)->first()){
                    if($optionDetail = ProductOptionData::where('OptionId',$hasOption->OptionId)->take(1)->first()){
                        $topic->Option = $optionDetail;
                    }
                }

                array_push($topics[$topic->ParentCase]['contents'],array(
                    'id'=>$topic->MixId,
                    'name'=>(Session::get('Language')==='EN'&&trim($topic->MixNameEn)!=='')?$topic->MixNameEn:$topic->MixName,
                    'image'=>$topic->Image,
                    'briefdesc'=>(Session::get('Language')==='EN'&&trim($topic->BriefDescEn)!=='')?$topic->BriefDescEn:$topic->BriefDesc,
                    'price'=>$topic->Price,
                    's_price'=>$topic->S_price,
                    'option'=>isset($topic->Option)?$topic->Option:false
                ));
            }

            if($topicsQuery->count()>0&&$takeItems!=='all'){
                $data['allTopics']=$topicsQuery->total();
            }
            elseif($topicsQuery->count()>0&&$takeItems==='all'){
                $data['allTopics']=$topicsQuery->count();
            }
            else{
                $data['allTopics']=(Integer)0;
            }

            $data['topics']=$topics;
            $data['selectedCase']=$caseId;
            if($caseId===false){
                $data['htmlTitle']=Session::get('Language')==='EN'?"Case Deals":"销售组合";
                $data['breadcrumbs']=array(
                    array('url'=>'/cases','name'=>Session::get('Language')==='EN'?'Case Deals':'销售组合')
                );
                return view('frontend.case_deals.index',$data);
            }
            else{
                $data['htmlTitle']=strtoupper($cases[0]->CaseName);
                if(Session::get('Language')==='EN'){
                    $data['htmlTitle'].=' - Case Deals';
                }
                else{
                    $data['htmlTitle'].=' - 销售组合';
                }
                $data['breadcrumbs']=array(
                    array('url'=>'/cases','name'=>Session::get('Language')==='EN'?'Case Deals':'销售组合'),
                    array('url'=>'/cases/'.$caseId,'name'=>$cases[0]->CaseName)
                );
                return view('frontend.case_deals.index_selectCase',$data);
            }
        }
        return redirect('/');
    }

    public function saleMixDetail($mixId){
        $MixInfo=SalesMixData::join('cases','cases.CaseId','=','sales_mix.ParentCase')->where(array(
            array('MixId','=',$mixId),
            array('cases.Status',1),
            array('sales_mix.Status',1),
            array('ActDate','<=',date('Y-m-d')),
            array('EndDate','>=',date('Y-m-d'))
        ))->orWhere(array(
            array('MixId','=',$mixId),
            array('cases.Status',1),
            array('sales_mix.Status',1),
            array('ActDate','<=',date('Y-m-d')),
            array('NoOffShelf','=',1)
        ))->orderBy('cases.OrderNumber','ASC')->orderBy('sales_mix.OrderNumber','ASC')->take(1)->first();
        if($MixInfo){
            if(Session::get('Language')==='EN'&&trim($MixInfo->CaseNameEn)!=='')
                $MixInfo->CaseName=$MixInfo->CaseNameEn;            
            if(Session::get('Language')==='EN'&&trim($MixInfo->MixNameEn)!=='')
                $MixInfo->MixName=$MixInfo->MixNameEn;
            if(Session::get('Language')==='EN'&&trim($MixInfo->BriefDescEn)!=='')
                $MixInfo->BriefDesc=$MixInfo->BriefDescEn;
            if(Session::get('Language')==='EN'&&trim($MixInfo->DescriptionEn)!=='')
                $MixInfo->Description=$MixInfo->DescriptionEn;
            if(Session::get('Language')==='EN'&&trim($MixInfo->Delivery_ReturnsEn)!=='')
                $MixInfo->Delivery_Returns=$MixInfo->Delivery_ReturnsEn;
            if(Session::get('Language')==='EN'&&trim($MixInfo->MetaDescEn)!=='')
                $MixInfo->MetaDesc=$MixInfo->MetaDescEn;
            if(Session::get('Language')==='EN'&&trim($MixInfo->MetaKeywordsEn)!=='')
                $MixInfo->MetaKeywords=$MixInfo->MetaKeywordsEn;                            

            $contentWines=array();
            $mixContents=DB::table('sales_mix_content')->where('MixId',$mixId)->select('Content')->take(1)->first();
            if($mixContents){
                $mixContents=json_decode($mixContents->Content);
                foreach($mixContents as $wineId=>$wineQuantity){
                    $wineInfo=WinesData::where(array(
                        array('WineId','=',$wineId),
                        array('Status','=',1),
                        array('ActDate','<=',date('Y-m-d')),
                        array('EndDate','>=',date('Y-m-d'))
                    ))->orWhere(array(
                        array('WineId','=',$wineId),
                        array('Status','=',1),
                        array('ActDate','<=',date('Y-m-d')),
                        array('NoOffShelf','=',1)
                    ))->take(1)->first();
                    if($wineInfo){
                        if(Session::get('Language')==='EN'&&trim($wineInfo->NameEn)!==''){
                            $wineInfo->Name=$wineInfo->NameEn;
                        }
                        if(Session::get('Language')==='EN'&&trim($wineInfo->BriefDescEn)!==''){
                            $wineInfo->BriefDesc=$wineInfo->BriefDescEn;
                        }
                        
                        array_push($contentWines,array(
                            'info'=>$wineInfo,
                            'quantity'=>$wineQuantity
                        ));
                    }
                }
            }

            $breadcrumbs=array(
                array('url'=>'/cases','name'=>Session::get('Language')==='EN'?'case deals':'销售组合'),
                array('url'=>'/cases/'.$MixInfo->CaseId,'name'=>$MixInfo->CaseName),
                array('url'=>'/case/'.$MixInfo->MixId,'name'=>$MixInfo->MixName)
            );

            // find option
            $MixInfo->Option = false;
            if($optionExist=ProductOptionSetData::where('ProductType','mix')->where('ProductId',$MixInfo->MixId)->take(1)->first()){
                if($optionDetail=ProductOptionData::where('OptionId',$optionExist->OptionId)->take(1)->first()){
                    $MixInfo->Option = $optionDetail;
                }
            }            

            $data['breadcrumbs']=$breadcrumbs;
            $data['contentWines']=$contentWines;
            $data['MixInfo']=$MixInfo;
            $data['htmlTitle']=$MixInfo->MixName;
            return view('frontend.case_deals.detail',$data);
        }
        return redirect('/cases');
    }
}