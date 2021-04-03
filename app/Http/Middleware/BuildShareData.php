<?php
namespace App\Http\Middleware;
use Closure;
use App\ExhibitionData;
use App\Products\CasesData;
use App\Products\SalesMixData;
use App\Products\WinesData;
use App\System\CompanyData;
use App\Products\ProductOptionData;
use App\Products\ProductOptionSetData;
use App\GiftsData;

use Config;
use DB;
use View;
use Session;
use Cookie;

class BuildShareData
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
        $CaseDeals=array();
        $Cases=CasesData::where('Status',1)->orderBy('OrderNumber','ASC')->orderBy('updated_at','DESC')->get();
        foreach($Cases as $Case){
            $CaseDeals[$Case->CaseId]=array(
                'name'=>(Session::get('Language')==='EN' && trim($Case->CaseNameEn)!=='')?$Case->CaseNameEn:$Case->CaseName,
                'topics'=>SalesMixData::where(array(
                    array("ParentCase","=",$Case->CaseId),
                    array("Status","=",1),
                    array("NoOffShelf","=",1)
                ))->orWhere(array(
                    array("ParentCase","=",$Case->CaseId),
                    array("Status","=",1),
                    array("NoOffShelf","!=",1),
                    array('ActDate','<=',date('Y-m-d')),
                    array('EndDate','>',date('Y-m-d'))
                ))->get()
            );
        }
        View::share("CaseDeals",$CaseDeals);

        $global_winesFilter=array();

        $countryQuery=DB::table('countries')->orderBy('CountryId','ASC')->get();
        $country_links=array();
        foreach($countryQuery as $country){
            if(Session::get('Language')==='EN'&&$country->CountryEn)
                $country_links[$country->CountryId]=$country->CountryEn;
            else
                $country_links[$country->CountryId]=$country->Country;
        }
        $global_winesFilter['countries']=$country_links;

        $filter_style=DB::table('wine_styles')->select(
            'wine_styles.Style','wine_styles.StyleEn','wine_styles.StyleId','count_wines_table.count_wines'
        )->join(
            DB::raw('( SELECT COUNT(`WineId`) AS `count_wines`,`WineStyle`,`WineCatg`,`WineCountry`,`WineRegion` FROM `wines` GROUP BY `WineStyle` )  count_wines_table'),function($join){
                $join->on('count_wines_table.WineStyle','=','wine_styles.StyleId');
                $join->where('wine_styles.Status','=',1);
            }
        )->orderBy('wine_styles.OrderNumber','ASC')->orderBy('wine_styles.updated_at','DESC')->take(10)->get();
        $global_winesFilter['styles']=$filter_style;

        $filter_regions=DB::table('wine_regions')->select(
            'wine_regions.Region','wine_regions.RegionEn','wine_regions.RegionId','count_wines_table.count_wines'
        )->join(
            DB::raw('( SELECT COUNT(`WineId`) AS `count_wines`,`WineRegion`,`WineCatg`,`WineCountry`,`WineStyle` FROM `wines` GROUP BY `WineRegion` )  count_wines_table'),function($join){
                $join->on('count_wines_table.WineRegion','=','wine_regions.RegionId');
                $join->where('wine_regions.Status',1);
            }
        )->orderBy('wine_regions.OrderNumber','ASC')->orderBy('wine_regions.updated_at','DESC')->take(10)->get();
        $global_winesFilter['regions']=$filter_regions;

        // REDS variety
        $global_winesFilter['REDS']=WinesData::join('wine_varieties','wines.WineVariety','=','wine_varieties.VarietyId')->where('WineCatg',1)->where('wine_varieties.Status',1)->orderBy('wine_varieties.OrderNumber','ASC')->take(10)->get();
        // WHITES variety
        $global_winesFilter['WHITES']=WinesData::join('wine_varieties','wines.WineVariety','=','wine_varieties.VarietyId')->where('WineCatg',2)->where('wine_varieties.Status',1)->orderBy('wine_varieties.OrderNumber','ASC')->take(10)->get();
        // SPARKLINGS variety
        $global_winesFilter['SPARKLINGS']=WinesData::join('wine_varieties','wines.WineVariety','=','wine_varieties.VarietyId')->where('WineCatg',3)->where('wine_varieties.Status',1)->orderBy('wine_varieties.OrderNumber','ASC')->take(5)->get();                
        View::share('global_winesFilter',$global_winesFilter);

        $sessionCart=Session::get('product',array());
        $shareCartInfo=array(
            'items'=>array(),
            'itemCounts'=>0,
            'subtotal'=>0
        );

        foreach($sessionCart as $item){
            $query=false;
            $cartItem=array();
            if($item['type']=='mix'){
                $query=SalesMixData::join('cases','cases.CaseId','=','sales_mix.ParentCase')->where(array(
                    array('MixId','=',$item['id']),
                    array('cases.Status','=',1),
                    array('sales_mix.Status','=',1),
                    array('ActDate','<=',date('Y-m-d')),
                    array('EndDate','>=',date('Y-m-d'))
                ))->orWhere(array(
                    array('MixId','=',$item['id']),
                    array('cases.Status','=',1),
                    array('sales_mix.Status','=',1),
                    array('ActDate','<=',date('Y-m-d')),
                    array('NoOffShelf','=',1)
                ))->orderBy('cases.OrderNumber','ASC')->orderBy('sales_mix.OrderNumber','ASC')->take(1)->first();

                if($query){
                    $image=asset($query->Image);
                    if(!file_exists(public_path($query->Image))){
                        $image=asset("images/default_casePic.jpeg");
                    }

                    // find option
                    $option = array();
                    if($optionExist=ProductOptionSetData::where('ProductType','mix')->where('ProductId',$item['id'])->take(1)->first()){
                        if($optionDetail=ProductOptionData::where('OptionId',$optionExist->OptionId)->take(1)->first()){
                            $option=array(
                                'Price'=>$optionDetail->Price,
                                'PurchaseAmount'=>$optionDetail->PurchaseAmount
                            );
                        }
                    }

                    $cartItem=array(
                        'id'=>$item['id'],
                        'image'=>$image,
                        'quantity'=>$item['quantity'],
                        'price'=>number_format($query->S_price?$query->S_price:$query->Price),
                        'name'=>(Session::get('Language')==='EN' && trim($query->MixNameEn)!=='')?$query->MixNameEn:$query->MixName,
                        'option'=>$option
                    );
                }
            }
            elseif($item['type']==='wine'){
                $query=WinesData::where(array(
                    array('WineId','=',$item['id']),
                    array('ActDate','<=',date('Y-m-d')),
                    array('EndDate','>',date('Y-m-d')),
                    array('wines.Status','=',1),
                    array('wine_varieties.Status','=',1),
                    array('wine_colours.Status','=',1),
                    array('wine_styles.Status','=',1),
                    array('wine_regions.Status','=',1),
                    array('wine_closures.Status','=',1)            
                ))->orWhere(array(
                    array('WineId','=',$item['id']),
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

                if($query){
                    if($query->Image){
                        $image=asset($query->Image);
                        if(!file_exists(public_path($query->Image))){
                            $image=asset("images/DefaultImages/DefaultWine.png");
                        }
                    }
                    else{
                        $image=asset("images/DefaultImages/DefaultWine.png");
                    }

                    // find option
                    $option = array();
                    if($optionExist=ProductOptionSetData::where('ProductType','wine')->where('ProductId',$item['id'])->take(1)->first()){
                        if($optionDetail=ProductOptionData::where('OptionId',$optionExist->OptionId)->take(1)->first()){
                            $option=array(
                                'Price'=>$optionDetail->Price,
                                'PurchaseAmount'=>$optionDetail->PurchaseAmount
                            );
                        }
                    }

                    $cartItem=array(
                        'id'=>$item['id'],
                        'image'=>$image,
                        'quantity'=>$item['quantity'],
                        'price'=>number_format($query->S_price?$query->S_price:$query->Price),
                        'name'=>(Session::get('Language')==='EN' && trim($query->NameEn)!=='')?$query->NameEn:$query->Name,
                        'option'=>$option
                    );
                }
            }
            elseif($item['type']==='gift'){
                $query = GiftsData::where(array(
                    array('GiftId','=',$item['id']),
                    array('ActDate','<=',date('Y-m-d')),
                    array('EndDate','>',date('Y-m-d')),
                    array('Status','=',1)     
                ))->take(1)->first();

                if($query){
                    if($query->Image){
                        $image=asset($query->Image);
                        if(!file_exists(public_path($query->Image))){
                            $image=asset("images/DefaultImages/DefaultWine.png");
                        }
                    }
                    else{
                        $image=asset("images/DefaultImages/DefaultWine.png");
                    }

                    $cartItem=array(
                        'id'=>$item['id'],
                        'image'=>$image,
                        'quantity'=>$item['quantity'],
                        'price'=>$query->S_price?$query->S_price:$query->Price,
                        'name'=>(Session::get('Language')==='EN' && trim($query->NameEn)!=='')?$query->NameEn:$query->Name,
                        'option'=>array()
                    );
                }                
            }


            if($query){
                array_push($shareCartInfo['items'],$cartItem);
                $shareCartInfo['subtotal']+=$cartItem['price']*$item['quantity'];
                $shareCartInfo['itemCounts']+=$item['quantity'];
            }
        }

        $shareCartInfo['subtotal'] = 0;
        foreach($shareCartInfo['items'] as $itemIdx=>$item){
            if(count($item['option'])>0){
                if($shareCartInfo['itemCounts']>intval($item['option']['PurchaseAmount'])){
                    $shareCartInfo['items'][$itemIdx]['price'] = $item['option']['Price'];
                    $shareCartInfo['subtotal']+=$shareCartInfo['items'][$itemIdx]['price']*$item['quantity'];
                }
                else{
                    $shareCartInfo['subtotal']+=$item['price']*$item['quantity'];    
                }
            }
            else{
                $shareCartInfo['subtotal']+=$item['price']*$item['quantity'];
            }
        }

        $shareCartInfo['subtotal']=number_format($shareCartInfo['subtotal'],2);
        View::share('shareCartInfo',$shareCartInfo);

        // Company Data
        $COMPANY=array(
            'companyName_En'=>'',
            'companyName_Zh'=>'',
            'address'=>'',
            'email'=>'',
            'phone'=>'',
            'fb_link'=>'',
            'ig_link'=>'',
            'twitter_link'=>'',
            'yt_link'=>''
        );
        $COMPANY_QUERIES=CompanyData::get();
        foreach($COMPANY_QUERIES as $COMPANY_QUERY){
            if(isset($COMPANY[$COMPANY_QUERY->InfoKey])){
                $COMPANY[$COMPANY_QUERY->InfoKey]=trim($COMPANY_QUERY->InfoValue);
            }
        }
        View::share('COMPANY',$COMPANY);

        // GET META & DC data
        $appendEnvKey="";
        if(Session::get("Language")==="EN"){
            $appendEnvKey="En";
        }
        
        $DCs=array(
            'DC_Title'=>env("DC_Title$appendEnvKey"),
            'DC_Creator'=>env("DC_Creator$appendEnvKey"),
            'DC_Subject'=>env("DC_Subject$appendEnvKey"),
            'DC_Description'=>env("DC_Description$appendEnvKey"),
            'DC_Contributor'=>env("DC_Contributor$appendEnvKey"),
            'DC_Type'=>env("DC_Type$appendEnvKey"),
            'DC_Format'=>env("DC_Format$appendEnvKey"),
            'DC_Source'=>env("DC_Source$appendEnvKey"),
            'DC_Language'=>env("DC_Language$appendEnvKey"),
            'DC_Coverage'=>env("DC_Coverage$appendEnvKey"),
            'DC_Coverage_t_max'=>env("DC_Coverage_t_max$appendEnvKey"),
            'DC_Publisher'=>env("DC_Publisher$appendEnvKey"),
            'DC_Date'=>env("DC_Date$appendEnvKey"),
            'DC_Rights'=>env("DC_Rights$appendEnvKey")
        );
        View::share('DCs',$DCs);

        $METAs=array(
            'META_KEYWORDS'=>env("META_KEYWORDS$appendEnvKey",env("META_KEYWORDS")),
            'META_DESCRIPTION'=>env("META_DESCRIPTION$appendEnvKey",env("META_DESCRIPTION")),
            'META_OG_IMAGE'=>env("META_OG_IMAGE$appendEnvKey",env("META_OG_IMAGE"))
        );
        foreach($METAs as $metaKey=>$meta){
            if(trim($meta)===''){
                $METAs[$metaKey] = env($metaKey);
            }
        }
        View::share('METAs',$METAs);

        $LANGUAGE='EN';

        if(trim(Session::get('Language'))===''){
            Session::put('Language','EN');
        }
        else{
            if(Session::get('Language')==='ZH')
                $LANGUAGE='ZH';
        }
        
        View::share('LANGUAGE',$LANGUAGE);

        $hasW18ConfirmationCookie = false;
        if(trim(\Request::segment(1)) !== 'admin' ){
            if( Cookie::get('wine18Confirmation') !== null){
                $hasW18ConfirmationCookie = true;
            }
        }
        View::share('hasW18ConfirmationCookie',$hasW18ConfirmationCookie);

        $footer_license_settting=array(
            'display_footer_license',
            'display_footer_license_expires',
            'display_footer_license_number',
            'footer_license',
            'footer_license_expires',
            'footer_license_number'
        );
        
        $footer_license_setting_query=DB::table('template_static_data')->whereIn('DataKey',$footer_license_settting)->select('Data','DataKey')->get();
        foreach($footer_license_setting_query as $setValue){
            if((String)$setValue->DataKey==='footer_license'){
                $setValue->Data = str_replace(array("\\","/"),DIRECTORY_SEPARATOR,$setValue->Data);
                if(file_exists(public_path($setValue->Data))){
                    View::share($setValue->DataKey,$setValue->Data);
                }
                else{
                    View::share($setValue->DataKey,false);
                }
            }
            else{
                View::share($setValue->DataKey,$setValue->Data);
            }
        }

        $global_notice_setting=array(
            'display_global_notice',
            'global_notice',
            'global_notice_en'
        );

        $global_notice_setting_query=DB::table('template_static_data')->whereIn('DataKey',$global_notice_setting)->select('Data','DataKey')->get();
        foreach($global_notice_setting_query as $setValue){
            View::share($setValue->DataKey,$setValue->Data);
        }

        // exhibition events
        $events = ExhibitionData::where(array(
            array('EventType','=',1),
            array('Status','=',1),
            array('EventOnDateTime_Start','<=',date('Y-m-d H:i:s')),
            array('EventOnDateTime_End','>=',date('Y-m-d H:i:s'))
        ))->orderBy('EventDateTime_Start','ASC')->orderBy('EventDateTime_End','ASC')->take(5)->get();
        $visits = ExhibitionData::where(array(
            array('EventType','=',2),
            array('Status','=',1),
            array('EventOnDateTime_Start','<=',date('Y-m-d H:i:s')),
            array('EventOnDateTime_End','>=',date('Y-m-d H:i:s'))
        ))->orderBy('EventDateTime_Start','ASC')->orderBy('EventDateTime_End','ASC')->take(5)->get();
        View::share('EXHIBITION_events',$events);
        View::share('EXHIBITION_visits',$visits);

        $menu_image_setting = array(
            'display_menu_featured_image',
            'menu_featured_image',
            'menu_featured_image_link'
        );

        $global_notice_setting_query=DB::table('template_static_data')->whereIn('DataKey',$menu_image_setting)->select('Data','DataKey')->get();
        foreach($global_notice_setting_query as $setValue){
            View::share($setValue->DataKey,$setValue->Data);
        }

        return $next($request);
    }
}