<?php
namespace App\Http\Controllers\Frontend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\BannersData;
use App\Products\WinesData;
use App\Products\SalesMixData;
use App\Products\ProductOptionData;
use App\Products\ProductOptionSetData;
use App\BlogData;
use App\IndexFeaturedImagesData;
use Session;
use Cookie;

class IndexController extends Controller
{                              
    public function __construct(){
        parent::__construct();
    }

    public function index(Request $request){
        $banners=array();
        $bannersQuery=BannersData::where(array(
            array('BannerOnStatus','=',1),
            array('BannerOffStatus','=',0)
        ))->orWhere(array(
            array('BannerOnStatus','=',1),
            array('BannerOffStatus','=',0)
        ))->orderBy('BannerOrder','ASC')->orderBy('updated_at','DESC')->get();
        foreach($bannersQuery as $banner){
            $banner->BannerImage=str_replace("\\","/",$banner->BannerImage);
            if($banner->BannerOffStatus==0){
                if(date('Y-m-d H:i:s') > $banner->BannerOnDate." ".$banner->BannerOnTime){
                    array_push($banners,$banner);
                }
            }
            else{
                if(date('Y-m-d H:i:s') > $banner->BannerOnDate." ".$banner->BannerOnTime && date('Y-m-d H:i:s') < $banner->BannerOffDate." ".$banner->BannerOffTime){
                    array_push($banners,$banner);
                }               
            }
        }

        // random weekly recommendation
        $weekly_recommendations=array(
            'topics'=>array(),
            'wines'=>array()
        );

        $where=array(
            array('wines.ActDate','<=',date('Y-m-d')),
            array('wines.EndDate','>',date('Y-m-d')),
            array('wines.Status','=',1),
            array('wine_varieties.Status','=',1),
            array('wine_colours.Status','=',1),
            array('wine_styles.Status','=',1),
            array('wine_regions.Status','=',1),
            array('wine_closures.Status','=',1),
            array('wines.WeeklyReco','=',1)
        ); 
        $orWhere=array(
            array('wines.ActDate','<=',date('Y-m-d')),
            array('wines.NoOffShelf','=',1),
            array('wines.Status','=',1),
            array('wine_varieties.Status','=',1),
            array('wine_colours.Status','=',1),
            array('wine_styles.Status','=',1),
            array('wine_regions.Status','=',1),
            array('wine_closures.Status','=',1),
            array('wines.WeeklyReco','=',1)
        );
        
        $weekly_recommendations['wines']=WinesData::leftJoin('wine_varieties','wine_varieties.VarietyId','=','wines.WineVariety')
                        ->leftJoin('wine_colours','wine_colours.ColourId','=','wines.WineColour')
                        ->leftJoin('wine_styles','wine_styles.StyleId','=','wines.WineStyle')
                        ->leftJoin('wine_regions','wine_regions.RegionId','=','wines.WineRegion')
                        ->leftJoin('wine_closures','wine_closures.ClosureId','=','wines.WineClosure')
                        ->where(function($query) use ($where){
                            $query->where($where);
                        })->orWhere(function($query) use($orWhere){
                            $query->orWhere($orWhere);
                        })->orderBy('wines.OrderNumber','ASC')->orderBy('wines.updated_at','DESC')->get();

        $weekly_recommendations['topics']=SalesMixData::join('cases','cases.CaseId','=','sales_mix.ParentCase')->where(array(
                array('cases.Status',1),
                array('sales_mix.Status',1),
                array('ActDate','<=',date('Y-m-d')),
                array('EndDate','>=',date('Y-m-d')),
                array('sales_mix.WeeklyReco','=',1)
            ))->orWhere(array(
                array('cases.Status',1),
                array('sales_mix.Status',1),
                array('ActDate','<=',date('Y-m-d')),
                array('NoOffShelf','=',1),
                array('sales_mix.WeeklyReco','=',1)
            ))->orderBy("sales_mix.OrderNumber","ASC")->get();

        foreach($weekly_recommendations['wines'] as $findWineOption){
            if($optionExist=ProductOptionSetData::where('ProductType','wine')->where('ProductId',$findWineOption->WineId)->take(1)->first()){
                if($optionDetail=ProductOptionData::where('OptionId',$optionExist->OptionId)->take(1)->first()){
                    $findWineOption->Option = $optionDetail;
                }
            }
        }
        foreach($weekly_recommendations['topics'] as $findTopicOption){
            if($optionExist=ProductOptionSetData::where('ProductType','topic')->where('ProductId',$findTopicOption->MixId)->take(1)->first()){
                if($optionDetail=ProductOptionData::where('OptionId',$optionExist->OptionId)->take(1)->first()){
                    $findTopicOption->Option = $optionDetail;
                }
            }
        }        
        $data['weekly_recommendations']=$weekly_recommendations;

        unset($where[count($where)-1]);
        unset($orWhere[count($orWhere)-1]);
        array_push($where,array('wines.Flagship','=',1));
        array_push($orWhere,array('wines.Flagship','=',1));
        $index_Flagship=WinesData::leftJoin('wine_varieties','wine_varieties.VarietyId','=','wines.WineVariety')
                        ->leftJoin('wine_colours','wine_colours.ColourId','=','wines.WineColour')
                        ->leftJoin('wine_styles','wine_styles.StyleId','=','wines.WineStyle')
                        ->leftJoin('wine_regions','wine_regions.RegionId','=','wines.WineRegion')
                        ->leftJoin('wine_closures','wine_closures.ClosureId','=','wines.WineClosure')
                        ->where(function($query) use ($where){
                            $query->where($where);
                        })->orWhere(function($query) use($orWhere){
                            $query->orWhere($orWhere);
                        })->take(1)->first();
        $data['index_Flagship']=$index_Flagship;

        // Blog flagship
        $articlesFlagship=BlogData::where(array(
            array('Flagship','=',1),
            array('Status','=',1),
            array('OnDate','<=',date('Y-m-d')),
            array('OffDate','>',date('Y-m-d'))
        ))->orderBy('OnTop','DESC')->orderBy('OrderNumber','ASC')->orderBy('updated_at','DESC')->take(3)->get();
        $data['articles']=$articlesFlagship;

        $index_featured_video_condition=array('display_index_featured_video','index_featured_video_description','index_featured_video_description_en','index_featured_video_source','index_featured_video_url');
        $index_featured_video_query=DB::table('template_static_data')->whereIn('DataKey',$index_featured_video_condition)->get();
        foreach($index_featured_video_query as $setting){
            $data[$setting->DataKey]=$setting->Data;
        }
        $data['index_featured_images'] = IndexFeaturedImagesData::where('Status',1)->orderBy('SN','ASC')->get();

        $index_small_slogan_condition=array('display_index_slogan','index_slogan','index_slogan_en');
        $index_small_slogan_query=DB::table('template_static_data')->whereIn('DataKey',$index_small_slogan_condition)->get();
        foreach($index_small_slogan_query as $setting){
            $data[$setting->DataKey]=$setting->Data;
        }

        $index_guarantee_images_condition=array('display_index_guarantee_image','display_index_promotion_image','index_guarantee_image','index_promotion_image');
        $index_guarantee_images_query=DB::table('template_static_data')->whereIn('DataKey',$index_guarantee_images_condition)->get();
        foreach($index_guarantee_images_query as $setting){
            $data[$setting->DataKey]=$setting->Data;
        }

        $data['htmlTitle']=Session::get('Language')==='EN'?"Home":"首页";
        $data['banners']=$banners;
        return view('frontend.index',$data);
    }

    public function changeLanguage($language='zh'){
        if($language==='zh'){
            Session::put('Language','ZH');
        }
        else{
            Session::put('Language','EN');
        }
        return redirect()->back();
    }

    public function confirmation18(){
        Cookie::queue('wine18Confirmation',true,43200);
        return redirect('/');
    }

    public function search(Request $request){
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
            "name"=>"Name",
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

        $searchKeyword = request('q');        

        $wheres=array(
            array('wines.ActDate','<=',date('Y-m-d')),
            array('wines.EndDate','>',date('Y-m-d')),
            array('wines.Status','=',1),
            array('wine_varieties.Status','=',1),
            array('wine_colours.Status','=',1),
            array('wine_styles.Status','=',1),
            array('wine_regions.Status','=',1),
            array('wine_closures.Status','=',1)            
        ); 
        $orWheres=array(
            array('wines.ActDate','<=',date('Y-m-d')),
            array('wines.NoOffShelf','=',1),
            array('wines.Status','=',1),
            array('wine_varieties.Status','=',1),
            array('wine_colours.Status','=',1),
            array('wine_styles.Status','=',1),
            array('wine_regions.Status','=',1),
            array('wine_closures.Status','=',1)
        );

        if(Session::get("Language")==="EN"){
            array_push($wheres,array('wines.NameEn','LIKE','%'.$searchKeyword.'%'));            
            array_push($orWheres,array('wines.NameEn','LIKE','%'.$searchKeyword.'%'));            
        }
        else{
            array_push($wheres,array('wines.Name','LIKE','%'.$searchKeyword.'%'));
            array_push($orWheres,array('wines.Name','LIKE','%'.$searchKeyword.'%'));
        }

        $query=WinesData::leftJoin('wine_varieties','wine_varieties.VarietyId','=','wines.WineVariety')
                        ->leftJoin('wine_colours','wine_colours.ColourId','=','wines.WineColour')
                        ->leftJoin('wine_styles','wine_styles.StyleId','=','wines.WineStyle')
                        ->leftJoin('wine_regions','wine_regions.RegionId','=','wines.WineRegion')
                        ->leftJoin('wine_closures','wine_closures.ClosureId','=','wines.WineClosure')
                        ->where(function($query) use ($wheres){
                            foreach($wheres as $where){
                                $query->where($where[0],$where[1],$where[2]);
                            }
                        })->orWhere(function($query) use($orWheres){
                            foreach($orWheres as $orWhere){
                                $query->where($orWhere[0],$orWhere[1],$orWhere[2]);
                            }
                        })->orderBy("wines.".$convertOrder[$order],strtoupper($dir))->orderBy('wines.OrderNumber','ASC')->orderBy('wines.updated_at','DESC');

        if($takeItems=='all'){
            $wines=$query->get();
        }
        else{
            $wines=$query->paginate($takeItems);
        }

        if($wines->count()>0&&$takeItems!=='all'){
            $data['allWines']=$wines->total();
        }
        elseif($wines->count()>0&&$takeItems==='all'){
            $data['allWines']=$wines->count();
        }
        else{
            $data['allWines']=(Integer)0;
        }

        foreach($wines as $wineIdx=>$wine){
            if($optionExist=ProductOptionSetData::where('ProductType','wine')->where('ProductId',$wine->WineId)->take(1)->first()){
                if($optionDetail=ProductOptionData::where('OptionId',$optionExist->OptionId)->take(1)->first()){
                    $wine->Option = $optionDetail;
                }
            }
        }

        $data['breadcrumbs']=array(
            array('url'=>'/search','name'=>Session::get('Language')==='EN'?'Search WINES':'搜寻')
        );
        $data['htmlTitle']=Session::get('Language')==='EN'?'Search WINES':'搜寻';
        $data['wines'] = $wines;
        $data['searchKeyword'] = $searchKeyword;
        return view('frontend.search_wines',$data);        
    }
}