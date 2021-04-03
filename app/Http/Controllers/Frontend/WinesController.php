<?php
namespace App\Http\Controllers\Frontend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Products\WinesData;
use App\Products\ProductOptionData;
use App\Products\ProductOptionSetData;

use Config;
use Session;

class WinesController extends Controller
{                              
    public function __construct(){
        parent::__construct();
    }

    public function wines($basic_catg=false,Request $request){
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

        // set filter and condition
        $set_filter=array();
        $whereIns=array();
        $multipleWhere=array(
            "basic_catg"=>false,
        );

        $multipleCountWinesWhere=array(
            "basic_catg"=>false,
            "country"=>false,
            "style"=>false,
            "region"=>false
        );

        $country=trim(request('country'));
        if($country!==""){
            $set_filter['country']=$country;
        }

        $style=trim(request('style'));
        if($style!==""){
            $set_filter['style']=$style;
        }

        $region=trim(request('region'));
        if($region!==""){
            $set_filter['region']=$region;
        }
        // breadcrumbs
        $breadcrumbs = array(
            Session::get('Language')==='EN' ? array('url'=>'/wines','name'=>'Browse Wines') : array('url'=>'/wines','name'=>'浏览商品')
        );

        $basicCatgInfo = array(
            'Catg'=>'',
            'Description'=>'',
            'MainImage'=>''
        );
        $data['showImpact'] = '';
        if($basic_catg){
            if(strtolower($basic_catg)==='reds'){
                $multipleWhere['basic_catg']=array('wines.WineCatg','=',1);
                $multipleCountWinesWhere['basic_catg']=array('count_wines_table.WineCatg','=',1);
            }
            elseif(strtolower($basic_catg)==='whites'){
                $multipleWhere['basic_catg']=array('wines.WineCatg','=',2);
                $multipleCountWinesWhere['basic_catg']=array('count_wines_table.WineCatg','=',2);
            }
            else{
                $multipleWhere['basic_catg']=array('wines.WineCatg','=',3);
                $multipleCountWinesWhere['basic_catg']=array('count_wines_table.WineCatg','=',3);
            }

            $convertCatg = array('whites'=>'白酒','reds'=>'红酒','sparklings'=>'气泡酒');
            if(Session::get('Language')==='EN'){
                $basicCatgInfo['Catg'] = ucfirst($basic_catg);
                array_push($breadcrumbs,array('url'=>'/'.$basic_catg,'name'=>ucfirst($basic_catg)));
            }
            else{
                $basicCatgInfo['Catg'] = $convertCatg[$basic_catg];
                array_push($breadcrumbs,array('url'=>'/'.$basic_catg,'name'=>$convertCatg[$basic_catg]));
            }

            if($queryBasicCatg = DB::table('wine_basic_category')->where('Category',strtolower($basic_catg))->take(1)->first()){
                if(Session::get('Language')==='EN'&&trim($queryBasicCatg->DescriptionEn)!==''){
                    $basicCatgInfo['Description'] = $queryBasicCatg->DescriptionEn;
                }
                else{
                    if(trim($queryVariety->Description)!==''){
                        $basicCatgInfo['Description'] = $queryBasicCatg->Description;
                    }
                }

                if($queryBasicCatg->Image){
                    $findFile = str_replace(array("\\","/"),DIRECTORY_SEPARATOR,$queryBasicCatg->Image);
                    if(file_exists(public_path($findFile))){
                        $basicCatgInfo['MainImage'] = $findFile;
                    }
                    else{
                        $basicCatgInfo['MainImage'] = "images".DIRECTORY_SEPARATOR."DefaultImages".DIRECTORY_SEPARATOR."DefaultWine.png";
                    }
                }
            }
            $data['showImpact'] = 'basic_catg';
        }
        $data['basic_catg'] = $basic_catg;
        $data['basicCatgInfo'] = $basicCatgInfo;

        if($style){
            $cut_style=explode(",",$style);
            array_push($whereIns,array('wines.WineStyle',$cut_style));
            $multipleCountWinesWhere["style"]=$cut_style;
        }

        if($country!=""){
            $cut_country=explode(",",$country);
            array_push($whereIns,array('wines.WineCountry',$cut_country));
            $multipleCountWinesWhere["country"]=$cut_country;
        }

        if($region!=""){
            $cut_region=explode(",",$region);
            array_push($whereIns,array('wines.WineRegion',$cut_region));
            $multipleCountWinesWhere["region"]=$cut_region;
        }
        // end set filter and condition

        // read filter from text file
        $variety_links=array();
        if(file_exists(public_path('/datas/filter.txt'))){
            $fh_variety=fopen(public_path('/datas/filter.txt'),'r');
            while($line=fgets($fh_variety)){
                $line = json_decode($line,true);

                if($basic_catg){
                    if(strtolower($basic_catg)==='reds'){
                        $line[1] = array();
                        $line[2] = array();
                    }
                    elseif(strtolower($basic_catg)==='whites'){
                        $line[0] = array();
                        $line[2] = array();
                    }
                    else{
                        $line[0] = array();
                        $line[1] = array();
                    }
                }
                $variety_links=$line;
            }
        }
        $data['variety_links']=$variety_links;

        $style_links=array();
        $countryQuery=DB::table('countries')->orderBy('CountryId','ASC')->get();
        $country_links=array();
        foreach($countryQuery as $country){
            if(Session::get('Language')==='EN'&&trim($country->CountryEn)!==''){
                $country_links[$country->CountryId]=$country->CountryEn;
            }
            else{
                $country_links[$country->CountryId]=$country->Country;
            }
        }
        $data['country_links']=$country_links;

        $filter_style=DB::table('wine_styles')->select(
            'wine_styles.Style','wine_styles.StyleEn','wine_styles.StyleId','count_wines_table.count_wines'
        )->join(
            DB::raw('( SELECT COUNT(`WineId`) AS `count_wines`,`WineStyle`,`WineCatg`,`WineCountry`,`WineRegion` FROM `wines` GROUP BY `WineStyle` )  count_wines_table'),function($join) use ($multipleCountWinesWhere){
                $join->on('count_wines_table.WineStyle','=','wine_styles.StyleId');
                $join->where('wine_styles.Status','=',1);
                if($multipleCountWinesWhere['basic_catg'])
                    $join->where(array($multipleCountWinesWhere['basic_catg']));
                if($multipleCountWinesWhere['country'])
                    $join->whereIn('count_wines_table.WineCountry',$multipleCountWinesWhere['country']);
                if($multipleCountWinesWhere['style'])
                    $join->whereIn('count_wines_table.WineStyle',$multipleCountWinesWhere['style']);
                if($multipleCountWinesWhere['region'])
                    $join->whereIn('count_wines_table.WineRegion',$multipleCountWinesWhere['region']);
            }
        )->orderBy('wine_styles.OrderNumber','ASC')->orderBy('wine_styles.updated_at','DESC')->get();
        $data['filter_style']=$filter_style;

        if($filter_style->count()>0){
            $random=array();
            $runTimes=0;
            while($runTimes<3){
                $randomIdx=rand(0,$filter_style->count()-1);
                if(!in_array($randomIdx,$random))
                    array_push($random,$randomIdx);
                $runTimes++;
            }
            foreach($random as $idx){
                array_push($style_links,array(
                    'id'=>$filter_style[$idx]->StyleId,
                    'name'=>(Session::get('Language')==='EN'&&trim($filter_style[$idx]->StyleEn)!=='')?$filter_style[$idx]->StyleEn:$filter_style[$idx]->Style
                ));
            }
        }

        $data['style_links']=$style_links;

        $filter_variety=DB::table('wine_varieties')->select(
            'wine_varieties.Variety','wine_varieties.VarietyEn','wine_varieties.VarietyId','count_wines_table.count_wines'
        )->join(
            DB::raw('( SELECT COUNT(`WineId`) AS `count_wines`,`WineVariety`,`WineCatg`,`WineCountry`,`WineStyle`,`WineRegion` FROM `wines` GROUP BY `WineVariety` )  count_wines_table'),function($join) use ($multipleCountWinesWhere){
                $join->on('count_wines_table.WineVariety','=','wine_varieties.VarietyId');
                $join->where('wine_varieties.Status',1);
                if($multipleCountWinesWhere['basic_catg'])
                    $join->where(array($multipleCountWinesWhere['basic_catg']));
                if($multipleCountWinesWhere['country'])
                    $join->whereIn('count_wines_table.WineCountry',$multipleCountWinesWhere['country']);
                if($multipleCountWinesWhere['style'])
                    $join->whereIn('count_wines_table.WineStyle',$multipleCountWinesWhere['style']);
                if($multipleCountWinesWhere['region'])
                    $join->whereIn('count_wines_table.WineRegion',$multipleCountWinesWhere['region']);
            }
        )->orderBy('wine_varieties.OrderNumber','ASC')->orderBy('wine_varieties.updated_at','DESC')->get();
        $data['filter_variety']=$filter_variety;

        $filter_countries=array();
        foreach($country_links as $key=>$val){
            $filter_countries[$key]=array('id'=>$key,'name'=>$val,'count_wines'=>WinesData::where('WineCountry',$key)->count());
        }
        $data['filter_countries']=$filter_countries;


        $filter_regions=DB::table('wine_regions')->select(
            'wine_regions.Region','wine_regions.RegionEn','wine_regions.RegionId','count_wines_table.count_wines'
        )->join(
            DB::raw('( SELECT COUNT(`WineId`) AS `count_wines`,`WineRegion`,`WineCatg`,`WineCountry`,`WineStyle` FROM `wines` GROUP BY `WineRegion` )  count_wines_table'),function($join) use ($multipleCountWinesWhere){
                $join->on('count_wines_table.WineRegion','=','wine_regions.RegionId');
                $join->where('wine_regions.Status',1);
                if($multipleCountWinesWhere['basic_catg'])
                    $join->where(array($multipleCountWinesWhere['basic_catg']));
                if($multipleCountWinesWhere['country'])
                    $join->whereIn('count_wines_table.WineCountry',$multipleCountWinesWhere['country']);
                if($multipleCountWinesWhere['style'])
                    $join->whereIn('count_wines_table.WineStyle',$multipleCountWinesWhere['style']);
                if($multipleCountWinesWhere['region'])
                    $join->whereIn('count_wines_table.WineRegion',$multipleCountWinesWhere['region']);
            }
        )->orderBy('wine_regions.OrderNumber','ASC')->orderBy('wine_regions.updated_at','DESC')->get();
        $data['filter_regions']=$filter_regions;

        $wine_max_price=WinesData::where('Status',1)->max('Price');
        $wine_min_price=WinesData::where('Status',1)->min('Price');
        $wine_max_sprice=WinesData::where('Status',1)->max('S_price');
        $wine_min_sprice=WinesData::where('Status',1)->min('S_price');
        $price_range=array('min'=>floor(min($wine_min_price,$wine_min_sprice)),'max'=>ceil(max($wine_max_price,$wine_max_sprice)));
        if(trim($price_range['min'])===""){
            $price_range['min']=0;
        }

        $data['price_range']=$price_range;
        $data['htmlTitle']=Session::get('Language')==='EN'?"Wines":"浏览商品";

        $where=array(
            array('wines.ActDate','<=',date('Y-m-d')),
            array('wines.EndDate','>',date('Y-m-d')),
            array('wines.Status','=',1),
            array('wine_varieties.Status','=',1),
            array('wine_colours.Status','=',1),
            array('wine_styles.Status','=',1),
            array('wine_regions.Status','=',1),
            array('wine_closures.Status','=',1)            
        ); 
        $orWhere=array(
            array('wines.ActDate','<=',date('Y-m-d')),
            array('wines.NoOffShelf','=',1),
            array('wines.Status','=',1),
            array('wine_varieties.Status','=',1),
            array('wine_colours.Status','=',1),
            array('wine_styles.Status','=',1),
            array('wine_regions.Status','=',1),
            array('wine_closures.Status','=',1)
        );
        if($multipleWhere['basic_catg']){
            array_push($where,$multipleWhere['basic_catg']);
            array_push($orWhere,$multipleWhere['basic_catg']);
        }

        $findVariety=trim(request('variety'));
        $varietyInfo = array(
            'Variety'=>'',
            'Description'=>'',
            'MainImage'=>''
        );
        if($findVariety!==''){
            if($queryVariety=DB::table('wine_varieties')->where('VarietyId',intval($findVariety))->select('VarietyDesc','VarietyDescEn','VarietyMainImage','VarietyEn','Variety')->take(1)->first()){
                if(Session::get('Language')==='EN'&&trim($queryVariety->VarietyDescEn)!==''){
                    $varietyInfo['Description'] = $queryVariety->VarietyDescEn;
                }
                else{
                    if(trim($queryVariety->VarietyDesc)!==''){
                        $varietyInfo['Description'] = $queryVariety->VarietyDesc;
                    }
                }

                if($queryVariety->VarietyMainImage){
                    $findFile = str_replace(array("\\","/"),DIRECTORY_SEPARATOR,$queryVariety->VarietyMainImage);
                    if(file_exists(public_path($findFile))){
                        $varietyInfo['MainImage'] = $findFile;
                    }
                    else{
                        $varietyInfo['MainImage'] = "images".DIRECTORY_SEPARATOR."DefaultImages".DIRECTORY_SEPARATOR."DefaultWine.png";
                    }
                }

                $varietyInfo['Variety'] = Session::get('Language')==='EN' && trim($queryVariety->VarietyEn)!=='' ? $queryVariety->VarietyEn : $queryVariety->Variety;

                if(Session::get('Language')==='EN' && trim($queryVariety->VarietyEn)!=='' ){
                    array_push($breadcrumbs,array('name'=>ucfirst($queryVariety->VarietyEn)));
                }
                else{
                    array_push($breadcrumbs,array('name'=>$queryVariety->Variety));
                }                
            }

            array_push($where,array('wine_varieties.VarietyId','=',$findVariety));
            array_push($orWhere,array('wine_varieties.VarietyId','=',$findVariety));
            $data['showImpact'] = 'variety';
        }
        $data['findVariety'] = $findVariety;
        $data['varietyInfo'] = $varietyInfo;

        $query=WinesData::leftJoin('wine_varieties','wine_varieties.VarietyId','=','wines.WineVariety')
                        ->leftJoin('wine_colours','wine_colours.ColourId','=','wines.WineColour')
                        ->leftJoin('wine_styles','wine_styles.StyleId','=','wines.WineStyle')
                        ->leftJoin('wine_regions','wine_regions.RegionId','=','wines.WineRegion')
                        ->leftJoin('wine_closures','wine_closures.ClosureId','=','wines.WineClosure')
                        ->where(function($query) use ($where,$whereIns){
                            $query->where($where);
                            foreach($whereIns as $whereIn){
                                $query->whereIn($whereIn[0],$whereIn[1]);
                            }
                        })->orWhere(function($query) use($orWhere,$whereIns){
                            $query->orWhere($orWhere);
                            foreach($whereIns as $whereIn){
                                $query->whereIn($whereIn[0],$whereIn[1]);
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

        $data['set_filter']=$set_filter;
        // echo '<pre>';
        // print_R($set_filter);
        // echo '</pre>';

        // echo '<pre>';
        // print_R($data);
        // echo '</pre>';
        $data['breadcrumbs'] = $breadcrumbs;
        $data['wines']=$wines;
        return view('frontend.wines',$data);
    }

    public function wine($wineId){
        $WineInfo=WinesData::where(array(
            array('WineId','=',$wineId),
            array('ActDate','<=',date('Y-m-d')),
            array('EndDate','>',date('Y-m-d')),
            array('wines.Status','=',1),
            array('wine_varieties.Status','=',1),
            array('wine_colours.Status','=',1),
            array('wine_styles.Status','=',1),
            array('wine_regions.Status','=',1),
            array('wine_closures.Status','=',1)            
        ))->orWhere(array(
            array('WineId','=',$wineId),
            array('wines.ActDate','<=',date('Y-m-d')),
            array('wines.NoOffShelf','=',1),
            array('wines.Status','=',1),
            array('wine_varieties.Status','=',1),
            array('wine_colours.Status','=',1),
            array('wine_styles.Status','=',1),
            array('wine_regions.Status','=',1),
            array('wine_closures.Status','=',1)
        ))->leftJoin('wine_varieties','wine_varieties.VarietyId','=','wines.WineVariety')
          ->leftJoin('wine_colours','wine_colours.ColourId','=','wines.WineColour')
          ->leftJoin('wine_styles','wine_styles.StyleId','=','wines.WineStyle')
          ->leftJoin('wine_regions','wine_regions.RegionId','=','wines.WineRegion')
          ->leftJoin('wine_closures','wine_closures.ClosureId','=','wines.WineClosure')->take(1)->first();
        if($WineInfo){
            if(Session::get('Language')==='EN'){
                if(trim($WineInfo->NameEn)!==''){
                    $WineInfo->Name=$WineInfo->NameEn;
                }
                if(trim($WineInfo->BriefDescEn)!==''){
                    $WineInfo->BriefDesc=$WineInfo->BriefDescEn;
                }
                if(trim($WineInfo->DescriptionEn)!==''){
                    $WineInfo->Description=$WineInfo->DescriptionEn;
                }
                if(trim($WineInfo->Delivery_ReturnsEn)!==''){
                    $WineInfo->Delivery_Returns=$WineInfo->Delivery_ReturnsEn;
                }
                if(trim($WineInfo->VarietyEn)!==''){
                    $WineInfo->Variety=$WineInfo->VarietyEn;
                }
                if(trim($WineInfo->ColourEn)!==''){
                    $WineInfo->Colour=$WineInfo->ColourEn;
                }
                if(trim($WineInfo->StyleEn)!==''){
                    $WineInfo->Style=$WineInfo->StyleEn;
                }
                if(trim($WineInfo->RegionEn)!==''){
                    $WineInfo->Region=$WineInfo->RegionEn;
                }
                if(trim($WineInfo->ClosureEn)!==''){
                    $WineInfo->Closure=$WineInfo->ClosureEn;
                }
            }

            // find option
            $WineInfo->Option = false;
            if($optionExist=ProductOptionSetData::where('ProductType','wine')->where('ProductId',$WineInfo->WineId)->take(1)->first()){
                if($optionDetail=ProductOptionData::where('OptionId',$optionExist->OptionId)->take(1)->first()){
                    $WineInfo->Option = $optionDetail;
                }
            }

            $randomWines = false;
            if($WineInfo->VarietyId){
                $randomWines = WinesData::where(array(
                    array('WineId','!=',$WineInfo->WineId),
                    array('ActDate','<=',date('Y-m-d')),
                    array('EndDate','>',date('Y-m-d')),
                    array('wines.Status','=',1),
                    array('wine_varieties.Status','=',1)
                ))->orWhere(array(
                    array('WineId','!=',$WineInfo->WineId),
                    array('wines.ActDate','<=',date('Y-m-d')),
                    array('wines.NoOffShelf','=',1),
                    array('wines.Status','=',1),
                    array('wine_varieties.Status','=',1),
                ))->join('wine_varieties','wine_varieties.VarietyId','=','wines.WineVariety')->inRandomOrder()->take(3)->get();
            }

            if($randomWines){
                foreach($randomWines as $randomIdx=>$randomWine){
                    // find option
                    $randomWines[$randomIdx]->Option = false;
                    if($optionExist=ProductOptionSetData::where('ProductType','wine')->where('ProductId',$randomWine->WineId)->take(1)->first()){
                        if($optionDetail=ProductOptionData::where('OptionId',$optionExist->OptionId)->take(1)->first()){
                            $randomWines[$randomIdx]->Option = $optionDetail;
                        }
                    }
                }
            }
            $data['randomWines'] = $randomWines;

            $data['htmlTitle']=$WineInfo->Name;
            $data['WineInfo']=$WineInfo;
            $data['Caterings']=array();
            if($WineInfo->WineCaterings && $WineInfo->WineCaterings===NULL){
            	$cateringArr = json_decode($WineInfo->WineCaterings,true);
            }
           	else{
           		$cateringArr = array();
           	}
            foreach($cateringArr as $cateringId){
                if($row=DB::table('wine_caterings')->where('CateringId',$cateringId)->where('Status',1)->take(1)->first()){
                    array_push($data['Caterings'],array(
                        'name'=>(Session::get('Language')==='EN'&&trim($row->CateringEn)!=='')?$row->CateringEn:$row->Catering,
                        'pic'=>$row->CateringPic
                    ));
                }
            }

            $data['breadcrumbs']=array(
                array('url'=>'/wines','name'=>'Browse Wines'),
                array('url'=>'/wine/'.$WineInfo->WineId,'name'=>$WineInfo->Name)
            );
            return view('frontend.wine',$data);
        }
        return redirect('/wines');
    }

    public function wine_byStyle($styleId=false){
        $styleId=intval($styleId);
        $data['styleId'] = $styleId;
        $styleBasicInfo = false;

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
        $data['showImpact'] = '';

        $where=array(
            array('wines.ActDate','<=',date('Y-m-d')),
            array('wines.EndDate','>',date('Y-m-d')),
            array('wines.Status','=',1),
            array('wine_varieties.Status','=',1),
            array('wine_colours.Status','=',1),
            array('wine_styles.Status','=',1),
            array('wine_regions.Status','=',1),
            array('wine_closures.Status','=',1)
        );

        $orWhere=array(
            array('wines.ActDate','<=',date('Y-m-d')),
            array('wines.NoOffShelf','=',1),
            array('wines.Status','=',1),
            array('wine_varieties.Status','=',1),
            array('wine_colours.Status','=',1),
            array('wine_styles.Status','=',1),
            array('wine_regions.Status','=',1),
            array('wine_closures.Status','=',1)
        );

        if($styleId!==0){
            array_push($where,array('wine_styles.StyleId','=',$styleId));
            array_push($orWhere,array('wine_styles.StyleId','=',$styleId));
            $styleBasicInfo=DB::table('wine_styles')->where('StyleId',$styleId)->where('Status',1)->take(1)->first();
            if($styleBasicInfo){
                $data['showImpact'] = 'style';
                $data['styleInfo'] = $styleBasicInfo;
                if(Session::get('Language')==='EN'&&trim($styleBasicInfo->StyleEn)!==''){
                    $styleBasicInfo->Style=$styleBasicInfo->StyleEn;
                }
            }            
        }

        $set_filter=array();
        $multipleWhere=array(
            "style"=>false,
        );

        $multipleCountWinesWhere=array(
            "country"=>false,
            "style"=>false,
            "region"=>false
        );

        $country=trim(request('country'));
        if($country!==""){
            $set_filter['country']=$country;
        }

        $style=trim(request('style'));
        if($style!==""){
            $set_filter['style']=$style;
        }

        $region=trim(request('region'));
        if($region!==""){
            $set_filter['region']=$region;
        }

        $query=WinesData::leftJoin('wine_varieties','wine_varieties.VarietyId','=','wines.WineVariety')
                        ->leftJoin('wine_colours','wine_colours.ColourId','=','wines.WineColour')
                        ->leftJoin('wine_styles','wine_styles.StyleId','=','wines.WineStyle')
                        ->leftJoin('wine_regions','wine_regions.RegionId','=','wines.WineRegion')
                        ->leftJoin('wine_closures','wine_closures.ClosureId','=','wines.WineClosure')
                        ->where(function($query) use ($where){
                            $query->where($where);
                        })->orWhere(function($query) use($orWhere){
                            $query->orWhere($orWhere);
                        })->orderBy("wines.".$convertOrder[$order],strtoupper($dir))->orderBy('wines.OrderNumber','ASC')->orderBy('wines.updated_at','DESC');

        if($takeItems=='all'){
            $wines=$query->get();
        }
        else{
            $wines=$query->paginate($takeItems);
        }

        foreach($wines as $wineIdx=>$wine){
            if($optionExist=ProductOptionSetData::where('ProductType','wine')->where('ProductId',$wine->WineId)->take(1)->first()){
                if($optionDetail=ProductOptionData::where('OptionId',$optionExist->OptionId)->take(1)->first()){
                    $wine->Option = $optionDetail;
                }
            }
        }              
        $data['wines']=$wines;

        if($wines->count()>0&&$takeItems!=='all'){
            $data['allWines']=$wines->total();
        }
        elseif($wines->count()>0&&$takeItems==='all'){
            $data['allWines']=$wines->count();
        }
        else{
            $data['allWines']=(Integer)0;
        }

        // read filter from text file
        $style_links=array();
        $countryQuery=DB::table('countries')->orderBy('CountryId','ASC')->get();
        $country_links=array();
        foreach($countryQuery as $country){
            if(Session::get('Language')==='EN'&&trim($country->CountryEn)!==''){
                $country_links[$country->CountryId]=$country->CountryEn;
            }
            else{
                $country_links[$country->CountryId]=$country->Country;
            }
        }
        $data['country_links']=$country_links;

        $filter_style=DB::table('wine_styles')->select(
            'wine_styles.Style','wine_styles.StyleEn','wine_styles.StyleId','count_wines_table.count_wines'
        )->join(
            DB::raw('( SELECT COUNT(`WineId`) AS `count_wines`,`WineStyle`,`WineCatg`,`WineCountry`,`WineRegion` FROM `wines` GROUP BY `WineStyle` )  count_wines_table'),function($join) use ($multipleCountWinesWhere){
                $join->on('count_wines_table.WineStyle','=','wine_styles.StyleId');
                $join->where('wine_styles.Status','=',1);
                if($multipleCountWinesWhere['country'])
                    $join->whereIn('count_wines_table.WineCountry',$multipleCountWinesWhere['country']);
                if($multipleCountWinesWhere['style'])
                    $join->whereIn('count_wines_table.WineStyle',$multipleCountWinesWhere['style']);
                if($multipleCountWinesWhere['region'])
                    $join->whereIn('count_wines_table.WineRegion',$multipleCountWinesWhere['region']);
            }
        )->orderBy('wine_styles.OrderNumber','ASC')->orderBy('wine_styles.updated_at','DESC')->get();
        $data['filter_style']=$filter_style;
        if($filter_style->count()>0){
            $style_links = $filter_style;
        }
        $data['style_links']=$style_links;

        $filter_variety=DB::table('wine_varieties')->select(
            'wine_varieties.Variety','wine_varieties.VarietyEn','wine_varieties.VarietyId','count_wines_table.count_wines'
        )->join(
            DB::raw('( SELECT COUNT(`WineId`) AS `count_wines`,`WineVariety`,`WineCatg`,`WineCountry`,`WineStyle`,`WineRegion` FROM `wines` GROUP BY `WineVariety` )  count_wines_table'),function($join) use ($multipleCountWinesWhere){
                $join->on('count_wines_table.WineVariety','=','wine_varieties.VarietyId');
                $join->where('wine_varieties.Status',1);
                if($multipleCountWinesWhere['country'])
                    $join->whereIn('count_wines_table.WineCountry',$multipleCountWinesWhere['country']);
                if($multipleCountWinesWhere['style'])
                    $join->whereIn('count_wines_table.WineStyle',$multipleCountWinesWhere['style']);
                if($multipleCountWinesWhere['region'])
                    $join->whereIn('count_wines_table.WineRegion',$multipleCountWinesWhere['region']);
            }
        )->orderBy('wine_varieties.OrderNumber','ASC')->orderBy('wine_varieties.updated_at','DESC')->get();
        $data['filter_variety']=$filter_variety;

        $filter_countries=array();
        foreach($country_links as $key=>$val){
            $filter_countries[$key]=array('id'=>$key,'name'=>$val,'count_wines'=>WinesData::where('WineCountry',$key)->count());
        }
        $data['filter_countries']=$filter_countries;


        $filter_regions=DB::table('wine_regions')->select(
            'wine_regions.Region','wine_regions.RegionEn','wine_regions.RegionId','count_wines_table.count_wines'
        )->join(
            DB::raw('( SELECT COUNT(`WineId`) AS `count_wines`,`WineRegion`,`WineCatg`,`WineCountry`,`WineStyle` FROM `wines` GROUP BY `WineRegion` )  count_wines_table'),function($join) use ($multipleCountWinesWhere){
                $join->on('count_wines_table.WineRegion','=','wine_regions.RegionId');
                $join->where('wine_regions.Status',1);
                if($multipleCountWinesWhere['country'])
                    $join->whereIn('count_wines_table.WineCountry',$multipleCountWinesWhere['country']);
                if($multipleCountWinesWhere['style'])
                    $join->whereIn('count_wines_table.WineStyle',$multipleCountWinesWhere['style']);
                if($multipleCountWinesWhere['region'])
                    $join->whereIn('count_wines_table.WineRegion',$multipleCountWinesWhere['region']);
            }
        )->orderBy('wine_regions.OrderNumber','ASC')->orderBy('wine_regions.updated_at','DESC')->get();
        $data['filter_regions']=$filter_regions;

        $wine_max_price=WinesData::where('Status',1)->max('Price');
        $wine_min_price=WinesData::where('Status',1)->min('Price');
        $wine_max_sprice=WinesData::where('Status',1)->max('S_price');
        $wine_min_sprice=WinesData::where('Status',1)->min('S_price');
        $price_range=array('min'=>floor(min($wine_min_price,$wine_min_sprice)),'max'=>ceil(max($wine_max_price,$wine_max_sprice)));
        if(trim($price_range['min'])===""){
            $price_range['min']=0;
        }

        $data['price_range']=$price_range;

        if(Session::get('Language')==='EN'){
            if($styleId!==0&&$styleBasicInfo){
                $data['htmlTitle']="Wines of ".$styleBasicInfo->Style;
                $data['breadcrumbs']=array(
                    array('url'=>'/wines','name'=>'Browse Wines'),
                    array('url'=>'/by-style/','name'=>"By Style"),
                    array('url'=>'/by-style/'.$styleId,'name'=>"Wines of ".$styleBasicInfo->Style)
                );
            }
            else{
                $data['htmlTitle']="By Style - Browse Wines";
                $data['breadcrumbs']=array(
                    array('url'=>'/wines','name'=>'Browse Wines'),
                    array('url'=>'/by-style/','name'=>"By Style")
                );
            }
        }
        else{
            if($styleId!==0&&$styleBasicInfo){
                $data['htmlTitle']=$styleBasicInfo->Style."风格的酒";
                $data['breadcrumbs']=array(
                    array('url'=>'/wines','name'=>'浏览商品'),
                    array('url'=>'/by-style/','name'=>"不同风格的酒"),
                    array('url'=>'/by-style/'.$styleId,'name'=>$styleBasicInfo->Style."风格的酒")
                );
            }
            else{
                $data['htmlTitle']="不同风格的酒";
                $data['breadcrumbs']=array(
                    array('url'=>'/wines','name'=>'浏览商品'),
                    array('url'=>'/by-style/'.$styleId,'name'=>"不同风格的酒")
                );
            }
        }
        $data['set_filter']=$set_filter;
        return view('frontend.wines_byStyle',$data);
    }

    public function wine_byCountry($countryShort=false){
        $countryShort=trim($countryShort);
        $data['countryShort'] = $countryShort;
        $countryInfo = false;

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
        $data['showImpact'] = '';

        $where=array(
            array('wines.ActDate','<=',date('Y-m-d')),
            array('wines.EndDate','>',date('Y-m-d')),
            array('wines.Status','=',1),
            array('wine_varieties.Status','=',1),
            array('wine_colours.Status','=',1),
            array('wine_styles.Status','=',1),
            array('wine_regions.Status','=',1),
            array('wine_closures.Status','=',1)
        ); 
        $orWhere=array(
            array('wines.ActDate','<=',date('Y-m-d')),
            array('wines.NoOffShelf','=',1),
            array('wines.Status','=',1),
            array('wine_varieties.Status','=',1),
            array('wine_colours.Status','=',1),
            array('wine_styles.Status','=',1),
            array('wine_regions.Status','=',1),
            array('wine_closures.Status','=',1)
        );

        if($countryShort!==''){
            array_push($where,array('wines.WineCountry','=',$countryShort));
            array_push($orWhere,array('wines.WineCountry','=',$countryShort));
            $countryInfo=DB::table('countries')->where('CountryId',$countryShort)->take(1)->first();
            if($countryInfo){
                $data['showImpact'] = 'country';
                $data['countryInfo'] = $countryInfo;
                if(Session::get('Language')==='EN'&&trim($countryInfo->CountryEn)!==''){
                    $countryInfo->Country=$countryInfo->CountryEn;
                }                
            }
        }

        $set_filter=array();
        $multipleWhere=array(
            "country"=>false,
        );

        $multipleCountWinesWhere=array(
            "country"=>false,
            "style"=>false,
            "region"=>false
        );

        $country=trim(request('country'));
        if($country!==""){
            $set_filter['country']=$country;
        }

        $style=trim(request('style'));
        if($style!==""){
            $set_filter['style']=$style;
        }

        $region=trim(request('region'));
        if($region!==""){
            $set_filter['region']=$region;
        }

        $query=WinesData::leftJoin('wine_varieties','wine_varieties.VarietyId','=','wines.WineVariety')
                        ->leftJoin('wine_colours','wine_colours.ColourId','=','wines.WineColour')
                        ->leftJoin('wine_styles','wine_styles.StyleId','=','wines.WineStyle')
                        ->leftJoin('wine_regions','wine_regions.RegionId','=','wines.WineRegion')
                        ->leftJoin('wine_closures','wine_closures.ClosureId','=','wines.WineClosure')
                        ->where(function($query) use ($where){
                            $query->where($where);
                        })->orWhere(function($query) use($orWhere){
                            $query->orWhere($orWhere);
                        })->orderBy("wines.".$convertOrder[$order],strtoupper($dir))->orderBy('wines.OrderNumber','ASC')->orderBy('wines.updated_at','DESC');

        if($takeItems=='all'){
            $wines=$query->get();
        }
        else{
            $wines=$query->paginate($takeItems);
        }        
        $data['wines']=$wines;

        if($wines->count()>0&&$takeItems!=='all'){
            $data['allWines']=$wines->total();
        }
        elseif($wines->count()>0&&$takeItems==='all'){
            $data['allWines']=$wines->count();
        }
        else{
            $data['allWines']=(Integer)0;
        }

        // read filter from text file
        $country_links=array();
        $countryQuery=DB::table('countries')->orderBy('CountryId','ASC')->get();
        $country_links=array();
        foreach($countryQuery as $country){
            if(Session::get('Language')==='EN'&&trim($country->CountryEn)!==''){
                $country_links[$country->CountryId]=$country->CountryEn;
            }
            else{
                $country_links[$country->CountryId]=$country->Country;
            }
        }
        $data['country_links']=$country_links;

        $filter_style=DB::table('wine_styles')->select(
            'wine_styles.Style','wine_styles.StyleEn','wine_styles.StyleId','count_wines_table.count_wines'
        )->join(
            DB::raw('( SELECT COUNT(`WineId`) AS `count_wines`,`WineStyle`,`WineCatg`,`WineCountry`,`WineRegion` FROM `wines` GROUP BY `WineStyle` )  count_wines_table'),function($join) use ($multipleCountWinesWhere){
                $join->on('count_wines_table.WineStyle','=','wine_styles.StyleId');
                $join->where('wine_styles.Status','=',1);
                if($multipleCountWinesWhere['country'])
                    $join->whereIn('count_wines_table.WineCountry',$multipleCountWinesWhere['country']);
                if($multipleCountWinesWhere['style'])
                    $join->whereIn('count_wines_table.WineStyle',$multipleCountWinesWhere['style']);
                if($multipleCountWinesWhere['region'])
                    $join->whereIn('count_wines_table.WineRegion',$multipleCountWinesWhere['region']);
            }
        )->orderBy('wine_styles.OrderNumber','ASC')->orderBy('wine_styles.updated_at','DESC')->get();
        $data['filter_style']=$filter_style;
        if($filter_style->count()>0){
            $style_links = $filter_style;
        }
        $data['style_links']=$style_links;

        $filter_variety=DB::table('wine_varieties')->select(
            'wine_varieties.Variety','wine_varieties.VarietyEn','wine_varieties.VarietyId','count_wines_table.count_wines'
        )->join(
            DB::raw('( SELECT COUNT(`WineId`) AS `count_wines`,`WineVariety`,`WineCatg`,`WineCountry`,`WineStyle`,`WineRegion` FROM `wines` GROUP BY `WineVariety` )  count_wines_table'),function($join) use ($multipleCountWinesWhere){
                $join->on('count_wines_table.WineVariety','=','wine_varieties.VarietyId');
                $join->where('wine_varieties.Status',1);
                if($multipleCountWinesWhere['country'])
                    $join->whereIn('count_wines_table.WineCountry',$multipleCountWinesWhere['country']);
                if($multipleCountWinesWhere['style'])
                    $join->whereIn('count_wines_table.WineStyle',$multipleCountWinesWhere['style']);
                if($multipleCountWinesWhere['region'])
                    $join->whereIn('count_wines_table.WineRegion',$multipleCountWinesWhere['region']);
            }
        )->orderBy('wine_varieties.OrderNumber','ASC')->orderBy('wine_varieties.updated_at','DESC')->get();
        $data['filter_variety']=$filter_variety;

        $filter_countries=array();
        foreach($country_links as $key=>$val){
            $filter_countries[$key]=array('id'=>$key,'name'=>$val,'count_wines'=>WinesData::where('WineCountry',$key)->count());
        }
        $data['filter_countries']=$filter_countries;


        $filter_regions=DB::table('wine_regions')->select(
            'wine_regions.Region','wine_regions.RegionEn','wine_regions.RegionId','count_wines_table.count_wines'
        )->join(
            DB::raw('( SELECT COUNT(`WineId`) AS `count_wines`,`WineRegion`,`WineCatg`,`WineCountry`,`WineStyle` FROM `wines` GROUP BY `WineRegion` )  count_wines_table'),function($join) use ($multipleCountWinesWhere){
                $join->on('count_wines_table.WineRegion','=','wine_regions.RegionId');
                $join->where('wine_regions.Status',1);
                if($multipleCountWinesWhere['country'])
                    $join->whereIn('count_wines_table.WineCountry',$multipleCountWinesWhere['country']);
                if($multipleCountWinesWhere['style'])
                    $join->whereIn('count_wines_table.WineStyle',$multipleCountWinesWhere['style']);
                if($multipleCountWinesWhere['region'])
                    $join->whereIn('count_wines_table.WineRegion',$multipleCountWinesWhere['region']);
            }
        )->orderBy('wine_regions.OrderNumber','ASC')->orderBy('wine_regions.updated_at','DESC')->get();
        $data['filter_regions']=$filter_regions;

        $wine_max_price=WinesData::where('Status',1)->max('Price');
        $wine_min_price=WinesData::where('Status',1)->min('Price');
        $wine_max_sprice=WinesData::where('Status',1)->max('S_price');
        $wine_min_sprice=WinesData::where('Status',1)->min('S_price');
        $price_range=array('min'=>floor(min($wine_min_price,$wine_min_sprice)),'max'=>ceil(max($wine_max_price,$wine_max_sprice)));
        if(trim($price_range['min'])===""){
            $price_range['min']=0;
        }

        $data['price_range']=$price_range;

        if(Session::get('Language')==='EN'){
            if($countryShort!==''&&$countryInfo){
                $data['htmlTitle']="Wines of ".$countryInfo->Country;
                $data['breadcrumbs']=array(
                    array('url'=>'/wines','name'=>'Browse Wines'),
                    array('url'=>'/by-country/','name'=>"By Country"),
                    array('url'=>'/by-country/'.$countryShort,'name'=>"Wines of ".$countryInfo->Country)
                );
            }
            else{
                $data['htmlTitle']="By Country - Browse Wines";
                $data['breadcrumbs']=array(
                    array('url'=>'/wines','name'=>'Browse Wines'),
                    array('url'=>'/by-country','name'=>"By Country")
                );
            }
        }
        else{
            if($countryShort!==''&&$countryInfo){
                $data['htmlTitle']=$countryInfo->Country."的酒";
                $data['breadcrumbs']=array(
                    array('url'=>'/wines','name'=>'浏览商品'),
                    array('url'=>'/by-country/','name'=>"不同国家的酒"),
                    array('url'=>'/by-country/'.$countryShort,'name'=>$countryInfo->Country."的酒")
                );
            }
            else{
                $data['htmlTitle']="不同国家的酒";
                $data['breadcrumbs']=array(
                    array('url'=>'/wines','name'=>'浏览商品'),
                    array('url'=>'/by-country','name'=>"不同国家的酒")
                );
            }
        }        

        $data['set_filter'] = $set_filter;
        return view('frontend.wines_byCountry',$data);
    }

    public function wine_byRegion($regionId=false){
        $regionId=intval($regionId);
        $data['regionId'] = $regionId;
        $regionBasicInfo = false;

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
        $data['showImpact'] = '';

        $where=array(
            array('wines.ActDate','<=',date('Y-m-d')),
            array('wines.EndDate','>',date('Y-m-d')),
            array('wines.Status','=',1),
            array('wine_varieties.Status','=',1),
            array('wine_colours.Status','=',1),
            array('wine_styles.Status','=',1),
            array('wine_regions.Status','=',1),
            array('wine_closures.Status','=',1)
        );

        $orWhere=array(
            array('wines.ActDate','<=',date('Y-m-d')),
            array('wines.NoOffShelf','=',1),
            array('wines.Status','=',1),
            array('wine_varieties.Status','=',1),
            array('wine_colours.Status','=',1),
            array('wine_styles.Status','=',1),
            array('wine_regions.Status','=',1),
            array('wine_closures.Status','=',1)
        );

        if($regionId!==0){
            array_push($where,array('wine_regions.RegionId','=',$regionId));
            array_push($orWhere,array('wine_regions.RegionId','=',$regionId));
            $regionBasicInfo=DB::table('wine_regions')->where('RegionId',$regionId)->where('Status',1)->take(1)->first();
            if($regionBasicInfo){
                $data['showImpact'] = 'region';
                $data['regionInfo'] = $regionBasicInfo;
                if(Session::get('Language')==='EN'&&trim($regionBasicInfo->RegionEn)!==''){
                    $regionBasicInfo->Region=$regionBasicInfo->RegionEn;
                }
            }            
        }

        $set_filter=array();
        $multipleWhere=array(
            "region"=>false,
        );

        $multipleCountWinesWhere=array(
            "country"=>false,
            "style"=>false,
            "region"=>false
        );

        $country=trim(request('country'));
        if($country!==""){
            $set_filter['country']=$country;
        }

        $style=trim(request('style'));
        if($style!==""){
            $set_filter['style']=$style;
        }

        $region=trim(request('region'));
        if($region!==""){
            $set_filter['region']=$region;
        }

        $query=WinesData::leftJoin('wine_varieties','wine_varieties.VarietyId','=','wines.WineVariety')
                        ->leftJoin('wine_colours','wine_colours.ColourId','=','wines.WineColour')
                        ->leftJoin('wine_styles','wine_styles.StyleId','=','wines.WineStyle')
                        ->join('wine_regions','wine_regions.RegionId','=','wines.WineRegion')
                        ->leftJoin('wine_closures','wine_closures.ClosureId','=','wines.WineClosure')
                        ->where(function($query) use ($where){
                            $query->where($where);
                        })->orWhere(function($query) use($orWhere){
                            $query->orWhere($orWhere);
                        })->orderBy("wines.".$convertOrder[$order],strtoupper($dir))->orderBy('wines.OrderNumber','ASC')->orderBy('wines.updated_at','DESC');

        if($takeItems=='all'){
            $wines=$query->get();
        }
        else{
            $wines=$query->paginate($takeItems);
        }

        foreach($wines as $wineIdx=>$wine){
            if($optionExist=ProductOptionSetData::where('ProductType','wine')->where('ProductId',$wine->WineId)->take(1)->first()){
                if($optionDetail=ProductOptionData::where('OptionId',$optionExist->OptionId)->take(1)->first()){
                    $wine->Option = $optionDetail;
                }
            }
        }              
        $data['wines']=$wines;

        if($wines->count()>0&&$takeItems!=='all'){
            $data['allWines']=$wines->total();
        }
        elseif($wines->count()>0&&$takeItems==='all'){
            $data['allWines']=$wines->count();
        }
        else{
            $data['allWines']=(Integer)0;
        }

        // read filter from text file
        $style_links=array();
        $region_links=array();
        $countryQuery=DB::table('countries')->orderBy('CountryId','ASC')->get();
        $country_links=array();
        foreach($countryQuery as $country){
            if(Session::get('Language')==='EN'&&trim($country->CountryEn)!==''){
                $country_links[$country->CountryId]=$country->CountryEn;
            }
            else{
                $country_links[$country->CountryId]=$country->Country;
            }
        }
        $data['country_links']=$country_links;

        $filter_style=DB::table('wine_styles')->select(
            'wine_styles.Style','wine_styles.StyleEn','wine_styles.StyleId','count_wines_table.count_wines'
        )->join(
            DB::raw('( SELECT COUNT(`WineId`) AS `count_wines`,`WineStyle`,`WineCatg`,`WineCountry`,`WineRegion` FROM `wines` GROUP BY `WineStyle` )  count_wines_table'),function($join) use ($multipleCountWinesWhere){
                $join->on('count_wines_table.WineStyle','=','wine_styles.StyleId');
                $join->where('wine_styles.Status','=',1);
                if($multipleCountWinesWhere['country'])
                    $join->whereIn('count_wines_table.WineCountry',$multipleCountWinesWhere['country']);
                if($multipleCountWinesWhere['style'])
                    $join->whereIn('count_wines_table.WineStyle',$multipleCountWinesWhere['style']);
                if($multipleCountWinesWhere['region'])
                    $join->whereIn('count_wines_table.WineRegion',$multipleCountWinesWhere['region']);
            }
        )->orderBy('wine_styles.OrderNumber','ASC')->orderBy('wine_styles.updated_at','DESC')->get();
        $data['filter_style']=$filter_style;
        if($filter_style->count()>0){
            $random=array();
            $runTimes=0;
            while($runTimes<3){
                $randomIdx=rand(0,$filter_style->count()-1);
                if(!in_array($randomIdx,$random))
                    array_push($random,$randomIdx);
                $runTimes++;
            }
            foreach($random as $idx){
                array_push($style_links,array(
                    'id'=>$filter_style[$idx]->StyleId,
                    'name'=>(Session::get('Language')==='EN'&&trim($filter_style[$idx]->StyleEn)!=='')?$filter_style[$idx]->StyleEn:$filter_style[$idx]->Style
                ));
            }
        }
        $data['style_links']=$style_links;

        $filter_variety=DB::table('wine_varieties')->select(
            'wine_varieties.Variety','wine_varieties.VarietyEn','wine_varieties.VarietyId','count_wines_table.count_wines'
        )->join(
            DB::raw('( SELECT COUNT(`WineId`) AS `count_wines`,`WineVariety`,`WineCatg`,`WineCountry`,`WineStyle`,`WineRegion` FROM `wines` GROUP BY `WineVariety` )  count_wines_table'),function($join) use ($multipleCountWinesWhere){
                $join->on('count_wines_table.WineVariety','=','wine_varieties.VarietyId');
                $join->where('wine_varieties.Status',1);
                if($multipleCountWinesWhere['country'])
                    $join->whereIn('count_wines_table.WineCountry',$multipleCountWinesWhere['country']);
                if($multipleCountWinesWhere['style'])
                    $join->whereIn('count_wines_table.WineStyle',$multipleCountWinesWhere['style']);
                if($multipleCountWinesWhere['region'])
                    $join->whereIn('count_wines_table.WineRegion',$multipleCountWinesWhere['region']);
            }
        )->orderBy('wine_varieties.OrderNumber','ASC')->orderBy('wine_varieties.updated_at','DESC')->get();
        $data['filter_variety']=$filter_variety;

        $filter_countries=array();
        foreach($country_links as $key=>$val){
            $filter_countries[$key]=array('id'=>$key,'name'=>$val,'count_wines'=>WinesData::where('WineCountry',$key)->count());
        }
        $data['filter_countries']=$filter_countries;


        $filter_regions=DB::table('wine_regions')->select(
            'wine_regions.Region','wine_regions.RegionEn','wine_regions.RegionId','count_wines_table.count_wines'
        )->join(
            DB::raw('( SELECT COUNT(`WineId`) AS `count_wines`,`WineRegion`,`WineCatg`,`WineCountry`,`WineStyle` FROM `wines` GROUP BY `WineRegion` )  count_wines_table'),function($join) use ($multipleCountWinesWhere){
                $join->on('count_wines_table.WineRegion','=','wine_regions.RegionId');
                $join->where('wine_regions.Status',1);
                if($multipleCountWinesWhere['country'])
                    $join->whereIn('count_wines_table.WineCountry',$multipleCountWinesWhere['country']);
                if($multipleCountWinesWhere['style'])
                    $join->whereIn('count_wines_table.WineStyle',$multipleCountWinesWhere['style']);
                if($multipleCountWinesWhere['region'])
                    $join->whereIn('count_wines_table.WineRegion',$multipleCountWinesWhere['region']);
            }
        )->orderBy('wine_regions.OrderNumber','ASC')->orderBy('wine_regions.updated_at','DESC')->get();
        $data['filter_regions']=$filter_regions;
        $region_links = $filter_regions;
        $data['region_links'] = $region_links;

        $wine_max_price=WinesData::where('Status',1)->max('Price');
        $wine_min_price=WinesData::where('Status',1)->min('Price');
        $wine_max_sprice=WinesData::where('Status',1)->max('S_price');
        $wine_min_sprice=WinesData::where('Status',1)->min('S_price');
        $price_range=array('min'=>floor(min($wine_min_price,$wine_min_sprice)),'max'=>ceil(max($wine_max_price,$wine_max_sprice)));
        if(trim($price_range['min'])===""){
            $price_range['min']=0;
        }

        $data['price_range']=$price_range;

        if(Session::get('Language')==='EN'){
            if($regionId!==0&&$regionBasicInfo){
                $data['htmlTitle']="Wines of ".$regionBasicInfo->Region;
                $data['breadcrumbs']=array(
                    array('url'=>'/wines','name'=>'Browse Wines'),
                    array('url'=>'/by-region/','name'=>"By Region"),
                    array('url'=>'/by-region/'.$regionId,'name'=>"Wines of ".$regionBasicInfo->Region)
                );
            }
            else{
                $data['htmlTitle']="By Regionn - Browse Wines";
                $data['breadcrumbs']=array(
                    array('url'=>'/wines','name'=>'Browse Wines'),
                    array('url'=>'/by-region/','name'=>"By Region")
                );
            }
        }
        else{
            if($regionId!==0&&$regionBasicInfo){
                $data['htmlTitle']=$regionBasicInfo->Region."产区的酒";
                $data['breadcrumbs']=array(
                    array('url'=>'/wines','name'=>'浏览商品'),
                    array('url'=>'/by-region/','name'=>"不同产区的酒"),
                    array('url'=>'/by-region/'.$regionId,'name'=>$regionBasicInfo->Region."产区的酒")
                );
            }
            else{
                $data['htmlTitle']="不同产区的酒";
                $data['breadcrumbs']=array(
                    array('url'=>'/wines','name'=>'浏览商品'),
                    array('url'=>'/by-region/'.$region,'name'=>"不同产区的酒")
                );
            }
        }

        $data['set_filter']=$set_filter;
        return view('frontend.wines_byRegion',$data);
    }
}