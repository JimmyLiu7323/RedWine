<?php
namespace App\Http\Controllers\Frontend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Members\AddressBookData;
use App\Members\MembersData;
use App\Products\CasesData;
use App\Products\SalesMixData;
use App\Products\WinesData;
use App\System\Freight_GSTData;
use App\Shopping\OrdersData;
use App\Shopping\OrderDetailData;
use App\Shopping\BillingData;
use App\Shopping\ShippingData;
use App\Products\ProductOptionData;
use App\Products\ProductOptionSetData;

use Session;
use Validator;
use Redirect;
use Widget_Helper;

class LatestDealsController extends Controller
{                              
    public function __construct(){
        parent::__construct();
    }

    public function yearSelling($year=2020,Request $request){
        $wines = array();
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
            "name"=>"NameEn",
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

        $yearBestSellingWines = OrderDetailData::select(array(
            DB::raw('SUM(`Quantity`) as `SellingQty`'),
            'order_detail.ProductType',
            'wines.Price',
            'wines.S_price',

            'wines.WineId as Pid',
            'wines.Name as Pname',
            'wines.NameEn as NameEn',
            'wines.BriefDesc as PbriefDesc',
            'wines.BriefDescEn as PbriefDescEn',
            'wines.Stocks as Stocks',
            'wines.created_at as created_at',
            'wines.updated_at as updated_at',
            'wines.OrderNumber as OrderNumber',
            'Image',
        ))->join('wines','wines.WineId','=','order_detail.Product')->where(array(
            array('OrderId','LIKE',$year.'%'),
            array('ActDate','<=',date('Y-m-d')),
            array('EndDate','>',date('Y-m-d')),
            array('wines.Status','=',1),
            array('wines.Stocks','>',0),
            array('wine_varieties.Status','=',1),
            array('wine_colours.Status','=',1),
            array('wine_styles.Status','=',1),
            array('wine_regions.Status','=',1),
            array('wine_closures.Status','=',1)
        ))->orWhere(array(
            array('OrderId','LIKE',$year.'%'),
            array('wines.ActDate','<=',date('Y-m-d')),
            array('wines.NoOffShelf','=',1),
            array('wines.Status','=',1),
            array('wines.Stocks','>',0),
            array('wine_varieties.Status','=',1),
            array('wine_colours.Status','=',1),
            array('wine_styles.Status','=',1),
            array('wine_regions.Status','=',1),
            array('wine_closures.Status','=',1)
        ))->leftJoin('wine_varieties','wine_varieties.VarietyId','=','wines.WineVariety')
                  ->leftJoin('wine_colours','wine_colours.ColourId','=','wines.WineColour')
                  ->leftJoin('wine_styles','wine_styles.StyleId','=','wines.WineStyle')
                  ->leftJoin('wine_regions','wine_regions.RegionId','=','wines.WineRegion')
                  ->leftJoin('wine_closures','wine_closures.ClosureId','=','wines.WineClosure')->groupby('Product')->orderBy('SellingQty','DESC');

        $unionWines = OrderDetailData::select(array(
            DB::raw('SUM(`Quantity`) as `SellingQty`'),
            'order_detail.ProductType',
            'sales_mix.Price',
            'sales_mix.S_price',

            'sales_mix.MixId as Pid',
            'sales_mix.MixName as Pname',
            'sales_mix.MixNameEn as NameEn',
            'sales_mix.BriefDesc as PbriefDesc',
            'sales_mix.BriefDescEn as PbriefDescEn',
            'sales_mix.Stocks as Stocks',
            'sales_mix.created_at as created_at',
            'sales_mix.updated_at as updated_at',
            'sales_mix.OrderNumber as OrderNumber',
            'Image',
        ))->join('sales_mix','sales_mix.MixId','=','order_detail.Product')->join('cases','cases.CaseId','=','sales_mix.ParentCase')->where(array(
            array('OrderId','LIKE',$year.'%'),
            array('ProductType','=','mix'),

            array('cases.Status','=',1),
            array('sales_mix.Status','=',1),
            array('sales_mix.Stocks','>',0),
            array('ActDate','<=',date('Y-m-d')),
            array('EndDate','>=',date('Y-m-d'))
        ))->orWhere(array(
            array('OrderId','LIKE',$year.'%'),
            array('ProductType','=','mix'),
            
            array('cases.Status','=',1),
            array('sales_mix.Status','=',1),
            array('sales_mix.Stocks','>',0),
            array('ActDate','<=',date('Y-m-d')),
            array('NoOffShelf','=',1)
        ))->groupby('Product')->orderBy('SellingQty','DESC')->union($yearBestSellingWines)
        ->orderBy($convertOrder[$order],strtoupper($dir))->orderBy('OrderNumber','ASC')->orderBy('updated_at','DESC');

        if($takeItems=='all'){
            $unionWines=$unionWines->get();
        }
        else{
            $unionWines=$unionWines->paginate($takeItems);
        }

        if($unionWines->count()>0&&$takeItems!=='all'){
            $data['allWines']=$unionWines->total();
        }
        elseif($unionWines->count()>0&&$takeItems==='all'){
            $data['allWines']=$unionWines->count();
        }
        else{
            $data['allWines']=(Integer)0;
        }

        foreach($unionWines as $itemIdx=>$item){
            $unionWines[$itemIdx]->option = array();
            if($item->ProductType==='wine'){
                $image=$item->Image;
                if(!file_exists(public_path($item->Image))||trim($item->Image)===''){
                    $image="/images/DefaultImages/DefaultWine.png";
                }
                $unionWines[$itemIdx]->Image = $image;

                // find option
                if($optionExist=ProductOptionSetData::where('ProductType','wine')->where('ProductId',$item->Pid)->take(1)->first()){
                    if($optionDetail=ProductOptionData::where('OptionId',$optionExist->OptionId)->take(1)->first()){
                        $unionWines[$itemIdx]->option = array(
                            'Price'=>$optionDetail->Price,
                            'PurchaseAmount'=>$optionDetail->PurchaseAmount
                        );
                    }
                }
            }
            elseif($item->ProductType==='mix'){
                $image=asset($itemInfo->Image);
                if(!file_exists(public_path($item->Image))||trim($item->Image)===''){
                    $image=asset("/images/DefaultImages/DefaultCase.png");
                }
                $unionWines[$itemIdx]->Image = $image;

                // find option
                if($optionExist=ProductOptionSetData::where('ProductType','mix')->where('ProductId',$item->Product)->take(1)->first()){
                    if($optionDetail=ProductOptionData::where('OptionId',$optionExist->OptionId)->take(1)->first()){
                        $unionWines[$itemIdx]->option = array(
                            'Price'=>$optionDetail->Price,
                            'PurchaseAmount'=>$optionDetail->PurchaseAmount
                        );
                    }
                }
            }
        }
        // echo'<pre>';
        // print_R($unionWines);exit();
        $data['wines'] = $unionWines;
        $data['htmlTitle']=Session::get('Language')==='EN'?"BEST SELL OF ".$year:$year.'年度最佳销售';
        $breadcrumbs=array(
            array('url'=>'/best-sell/year'.$year,'name'=>Session::get('Language')==='EN'?"BEST SELL OF ".$year:$year.'年度最佳销售')
        );
        $data['breadcrumbs'] = $breadcrumbs;
        return view('frontend.latest-deals.best-sell-list',$data);
    }

    public function monthSelling(Request $request){
        $wines = array();
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
            "name"=>"NameEn",
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

        $monthRange = date('Ym',strtotime("-1 month"));

        $yearBestSellingWines = OrderDetailData::select(array(
            DB::raw('SUM(`Quantity`) as `SellingQty`'),
            'order_detail.ProductType',
            'wines.Price',
            'wines.S_price',

            'wines.WineId as Pid',
            'wines.Name as Pname',
            'wines.NameEn as NameEn',
            'wines.BriefDesc as PbriefDesc',
            'wines.BriefDescEn as PbriefDescEn',
            'wines.Stocks as Stocks',
            'wines.created_at as created_at',
            'wines.updated_at as updated_at',
            'wines.OrderNumber as OrderNumber',
            'Image',
        ))->join('wines','wines.WineId','=','order_detail.Product')->where(array(
            array('OrderId','LIKE',$monthRange.'%'),
            array('ActDate','<=',date('Y-m-d')),
            array('EndDate','>',date('Y-m-d')),
            array('wines.Status','=',1),
            array('wines.Stocks','>',0),
            array('wine_varieties.Status','=',1),
            array('wine_colours.Status','=',1),
            array('wine_styles.Status','=',1),
            array('wine_regions.Status','=',1),
            array('wine_closures.Status','=',1)
        ))->orWhere(array(
            array('OrderId','LIKE',$monthRange.'%'),
            array('wines.ActDate','<=',date('Y-m-d')),
            array('wines.NoOffShelf','=',1),
            array('wines.Status','=',1),
            array('wines.Stocks','>',0),
            array('wine_varieties.Status','=',1),
            array('wine_colours.Status','=',1),
            array('wine_styles.Status','=',1),
            array('wine_regions.Status','=',1),
            array('wine_closures.Status','=',1)
        ))->leftJoin('wine_varieties','wine_varieties.VarietyId','=','wines.WineVariety')
                  ->leftJoin('wine_colours','wine_colours.ColourId','=','wines.WineColour')
                  ->leftJoin('wine_styles','wine_styles.StyleId','=','wines.WineStyle')
                  ->leftJoin('wine_regions','wine_regions.RegionId','=','wines.WineRegion')
                  ->leftJoin('wine_closures','wine_closures.ClosureId','=','wines.WineClosure')->groupby('Product')->orderBy('SellingQty','DESC');

        $unionWines = OrderDetailData::select(array(
            DB::raw('SUM(`Quantity`) as `SellingQty`'),
            'order_detail.ProductType',
            'sales_mix.Price',
            'sales_mix.S_price',

            'sales_mix.MixId as Pid',
            'sales_mix.MixName as Pname',
            'sales_mix.MixNameEn as NameEn',
            'sales_mix.BriefDesc as PbriefDesc',
            'sales_mix.BriefDescEn as PbriefDescEn',
            'sales_mix.Stocks as Stocks',
            'sales_mix.created_at as created_at',
            'sales_mix.updated_at as updated_at',
            'sales_mix.OrderNumber as OrderNumber',
            'Image',
        ))->join('sales_mix','sales_mix.MixId','=','order_detail.Product')->join('cases','cases.CaseId','=','sales_mix.ParentCase')->where(array(
            array('OrderId','LIKE',$monthRange.'%'),
            array('ProductType','=','mix'),

            array('cases.Status','=',1),
            array('sales_mix.Status','=',1),
            array('sales_mix.Stocks','>',0),
            array('ActDate','<=',date('Y-m-d')),
            array('EndDate','>=',date('Y-m-d'))
        ))->orWhere(array(
            array('OrderId','LIKE',$monthRange.'%'),
            array('ProductType','=','mix'),
            
            array('cases.Status','=',1),
            array('sales_mix.Status','=',1),
            array('sales_mix.Stocks','>',0),
            array('ActDate','<=',date('Y-m-d')),
            array('NoOffShelf','=',1)
        ))->groupby('Product')->orderBy('SellingQty','DESC')->union($yearBestSellingWines)
        ->orderBy($convertOrder[$order],strtoupper($dir))->orderBy('OrderNumber','ASC')->orderBy('updated_at','DESC');

        if($takeItems=='all'){
            $unionWines=$unionWines->get();
        }
        else{
            $unionWines=$unionWines->paginate($takeItems);
        }

        if($unionWines->count()>0&&$takeItems!=='all'){
            $data['allWines']=$unionWines->total();
        }
        elseif($unionWines->count()>0&&$takeItems==='all'){
            $data['allWines']=$unionWines->count();
        }
        else{
            $data['allWines']=(Integer)0;
        }

        foreach($unionWines as $itemIdx=>$item){
            $unionWines[$itemIdx]->option = array();
            if($item->ProductType==='wine'){
                $image=$item->Image;
                if(!file_exists(public_path($item->Image))||trim($item->Image)===''){
                    $image="/images/DefaultImages/DefaultWine.png";
                }
                $unionWines[$itemIdx]->Image = $image;

                // find option
                if($optionExist=ProductOptionSetData::where('ProductType','wine')->where('ProductId',$item->Pid)->take(1)->first()){
                    if($optionDetail=ProductOptionData::where('OptionId',$optionExist->OptionId)->take(1)->first()){
                        $unionWines[$itemIdx]->option = array(
                            'Price'=>$optionDetail->Price,
                            'PurchaseAmount'=>$optionDetail->PurchaseAmount
                        );
                    }
                }
            }
            elseif($item->ProductType==='mix'){
                $image=asset($itemInfo->Image);
                if(!file_exists(public_path($item->Image))||trim($item->Image)===''){
                    $image=asset("/images/DefaultImages/DefaultCase.png");
                }
                $unionWines[$itemIdx]->Image = $image;

                // find option
                if($optionExist=ProductOptionSetData::where('ProductType','mix')->where('ProductId',$item->Product)->take(1)->first()){
                    if($optionDetail=ProductOptionData::where('OptionId',$optionExist->OptionId)->take(1)->first()){
                        $unionWines[$itemIdx]->option = array(
                            'Price'=>$optionDetail->Price,
                            'PurchaseAmount'=>$optionDetail->PurchaseAmount
                        );
                    }
                }
            }
        }
        // echo'<pre>';
        // print_R($unionWines);exit();
        $data['wines'] = $unionWines;
        $data['htmlTitle']=Session::get('Language')==='EN'?"BEST SELL OF MONTH":'前一月份最佳销售';
        $breadcrumbs=array(
            array('url'=>'/best-sell/month','name'=>Session::get('Language')==='EN'?"BEST SELL OF MONTH":'前一月份最佳销售')
        );
        $data['breadcrumbs'] = $breadcrumbs;
        return view('frontend.latest-deals.best-sell-list',$data);
    }

    public function weekSelling(){
        $wines = array();
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
            "name"=>"NameEn",
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

        $firstDayOfLastWeek = mktime(0,0,0,date("m"),date("d")-date("w")-6);
        $lastDayOfLastWeek = mktime(0,0,0,date("m"),date("d")-date("w"));
        $firstDayOfLastWeek = date("Ymd",$firstDayOfLastWeek);
        $lastDayOfLastWeek = date("Ymd",$lastDayOfLastWeek);

        $yearBestSellingWines = OrderDetailData::select(array(
            DB::raw('SUM(`Quantity`) as `SellingQty`'),
            'order_detail.ProductType',
            'wines.Price',
            'wines.S_price',

            'wines.WineId as Pid',
            'wines.Name as Pname',
            'wines.NameEn as NameEn',
            'wines.BriefDesc as PbriefDesc',
            'wines.BriefDescEn as PbriefDescEn',
            'wines.Stocks as Stocks',
            'wines.created_at as created_at',
            'wines.updated_at as updated_at',
            'wines.OrderNumber as OrderNumber',
            'Image',
        ))->join('wines','wines.WineId','=','order_detail.Product')->where(array(
            array('OrderId','>=',$firstDayOfLastWeek.'0001'),
            array('OrderId','<=',$lastDayOfLastWeek.'9999'),
            array('ActDate','<=',date('Y-m-d')),
            array('EndDate','>',date('Y-m-d')),
            array('wines.Status','=',1),
            array('wines.Stocks','>',0),
            array('wine_varieties.Status','=',1),
            array('wine_colours.Status','=',1),
            array('wine_styles.Status','=',1),
            array('wine_regions.Status','=',1),
            array('wine_closures.Status','=',1)
        ))->orWhere(array(
            array('OrderId','>=',$firstDayOfLastWeek.'0001'),
            array('OrderId','<=',$lastDayOfLastWeek.'9999'),
            array('wines.ActDate','<=',date('Y-m-d')),
            array('wines.NoOffShelf','=',1),
            array('wines.Status','=',1),
            array('wines.Stocks','>',0),
            array('wine_varieties.Status','=',1),
            array('wine_colours.Status','=',1),
            array('wine_styles.Status','=',1),
            array('wine_regions.Status','=',1),
            array('wine_closures.Status','=',1)
        ))->leftJoin('wine_varieties','wine_varieties.VarietyId','=','wines.WineVariety')
                  ->leftJoin('wine_colours','wine_colours.ColourId','=','wines.WineColour')
                  ->leftJoin('wine_styles','wine_styles.StyleId','=','wines.WineStyle')
                  ->leftJoin('wine_regions','wine_regions.RegionId','=','wines.WineRegion')
                  ->leftJoin('wine_closures','wine_closures.ClosureId','=','wines.WineClosure')->groupby('Product')->orderBy('SellingQty','DESC');

        $unionWines = OrderDetailData::select(array(
            DB::raw('SUM(`Quantity`) as `SellingQty`'),
            'order_detail.ProductType',
            'sales_mix.Price',
            'sales_mix.S_price',

            'sales_mix.MixId as Pid',
            'sales_mix.MixName as Pname',
            'sales_mix.MixNameEn as NameEn',
            'sales_mix.BriefDesc as PbriefDesc',
            'sales_mix.BriefDescEn as PbriefDescEn',
            'sales_mix.Stocks as Stocks',
            'sales_mix.created_at as created_at',
            'sales_mix.updated_at as updated_at',
            'sales_mix.OrderNumber as OrderNumber',
            'Image',
        ))->join('sales_mix','sales_mix.MixId','=','order_detail.Product')->join('cases','cases.CaseId','=','sales_mix.ParentCase')->where(array(
            array('OrderId','>=',$firstDayOfLastWeek.'0001'),
            array('OrderId','<=',$lastDayOfLastWeek.'9999'),
            array('ProductType','=','mix'),

            array('cases.Status','=',1),
            array('sales_mix.Status','=',1),
            array('sales_mix.Stocks','>',0),
            array('ActDate','<=',date('Y-m-d')),
            array('EndDate','>=',date('Y-m-d'))
        ))->orWhere(array(
            array('OrderId','>=',$firstDayOfLastWeek.'0001'),
            array('OrderId','<=',$lastDayOfLastWeek.'9999'),
            array('ProductType','=','mix'),
            
            array('cases.Status','=',1),
            array('sales_mix.Status','=',1),
            array('sales_mix.Stocks','>',0),
            array('ActDate','<=',date('Y-m-d')),
            array('NoOffShelf','=',1)
        ))->groupby('Product')->orderBy('SellingQty','DESC')->union($yearBestSellingWines)
        ->orderBy($convertOrder[$order],strtoupper($dir))->orderBy('OrderNumber','ASC')->orderBy('updated_at','DESC');

        if($takeItems=='all'){
            $unionWines=$unionWines->get();
        }
        else{
            $unionWines=$unionWines->paginate($takeItems);
        }

        if($unionWines->count()>0&&$takeItems!=='all'){
            $data['allWines']=$unionWines->total();
        }
        elseif($unionWines->count()>0&&$takeItems==='all'){
            $data['allWines']=$unionWines->count();
        }
        else{
            $data['allWines']=(Integer)0;
        }

        foreach($unionWines as $itemIdx=>$item){
            $unionWines[$itemIdx]->option = array();
            if($item->ProductType==='wine'){
                $image=$item->Image;
                if(!file_exists(public_path($item->Image))||trim($item->Image)===''){
                    $image="/images/DefaultImages/DefaultWine.png";
                }
                $unionWines[$itemIdx]->Image = $image;

                // find option
                if($optionExist=ProductOptionSetData::where('ProductType','wine')->where('ProductId',$item->Pid)->take(1)->first()){
                    if($optionDetail=ProductOptionData::where('OptionId',$optionExist->OptionId)->take(1)->first()){
                        $unionWines[$itemIdx]->option = array(
                            'Price'=>$optionDetail->Price,
                            'PurchaseAmount'=>$optionDetail->PurchaseAmount
                        );
                    }
                }
            }
            elseif($item->ProductType==='mix'){
                $image=asset($itemInfo->Image);
                if(!file_exists(public_path($item->Image))||trim($item->Image)===''){
                    $image=asset("/images/DefaultImages/DefaultCase.png");
                }
                $unionWines[$itemIdx]->Image = $image;

                // find option
                if($optionExist=ProductOptionSetData::where('ProductType','mix')->where('ProductId',$item->Product)->take(1)->first()){
                    if($optionDetail=ProductOptionData::where('OptionId',$optionExist->OptionId)->take(1)->first()){
                        $unionWines[$itemIdx]->option = array(
                            'Price'=>$optionDetail->Price,
                            'PurchaseAmount'=>$optionDetail->PurchaseAmount
                        );
                    }
                }
            }
        }
        // echo'<pre>';
        // print_R($unionWines);exit();
        $data['wines'] = $unionWines;
        $data['htmlTitle']=Session::get('Language')==='EN'?"BEST SELL OF WEEK":'前一週最佳销售';
        $breadcrumbs=array(
            array('url'=>'/best-sell/week','name'=>Session::get('Language')==='EN'?"BEST SELL OF WEEK":'前一週最佳销售')
        );
        $data['breadcrumbs'] = $breadcrumbs;
        return view('frontend.latest-deals.best-sell-list',$data);        
    }

    public function priceLevelSelling($priceLevel,Request $request){
        $wines = array();
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
            "name"=>"NameEn",
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

        $monthRange = date('Ym',strtotime("-1 month"));

        $yearBestSellingWines = OrderDetailData::select(array(
            DB::raw('SUM(`Quantity`) as `SellingQty`'),
            'order_detail.ProductType',
            'wines.Price',
            'wines.S_price',

            'wines.WineId as Pid',
            'wines.Name as Pname',
            'wines.NameEn as NameEn',
            'wines.BriefDesc as PbriefDesc',
            'wines.BriefDescEn as PbriefDescEn',
            'wines.Stocks as Stocks',
            'wines.created_at as created_at',
            'wines.updated_at as updated_at',
            'wines.OrderNumber as OrderNumber',
            'Image',
        ))->join('wines','wines.WineId','=','order_detail.Product')->where(array(
            array('OrderId','LIKE',$monthRange.'%'),
            array('ActDate','<=',date('Y-m-d')),
            array('EndDate','>',date('Y-m-d')),
            array('wines.Status','=',1),
            array('wines.Stocks','>',0),
            array('wine_varieties.Status','=',1),
            array('wine_colours.Status','=',1),
            array('wine_styles.Status','=',1),
            array('wine_regions.Status','=',1),
            array('wine_closures.Status','=',1),
            array('wines.Price','<=',$priceLevel)
        ))->orWhere(array(
            array('OrderId','LIKE',$monthRange.'%'),
            array('wines.ActDate','<=',date('Y-m-d')),
            array('wines.NoOffShelf','=',1),
            array('wines.Status','=',1),
            array('wines.Stocks','>',0),
            array('wine_varieties.Status','=',1),
            array('wine_colours.Status','=',1),
            array('wine_styles.Status','=',1),
            array('wine_regions.Status','=',1),
            array('wine_closures.Status','=',1),
            array('wines.Price','<=',$priceLevel)
        ))->leftJoin('wine_varieties','wine_varieties.VarietyId','=','wines.WineVariety')
                  ->leftJoin('wine_colours','wine_colours.ColourId','=','wines.WineColour')
                  ->leftJoin('wine_styles','wine_styles.StyleId','=','wines.WineStyle')
                  ->leftJoin('wine_regions','wine_regions.RegionId','=','wines.WineRegion')
                  ->leftJoin('wine_closures','wine_closures.ClosureId','=','wines.WineClosure')->groupby('Product')->orderBy('SellingQty','DESC');

        $unionWines = OrderDetailData::select(array(
            DB::raw('SUM(`Quantity`) as `SellingQty`'),
            'order_detail.ProductType',
            'sales_mix.Price',
            'sales_mix.S_price',

            'sales_mix.MixId as Pid',
            'sales_mix.MixName as Pname',
            'sales_mix.MixNameEn as NameEn',
            'sales_mix.BriefDesc as PbriefDesc',
            'sales_mix.BriefDescEn as PbriefDescEn',
            'sales_mix.Stocks as Stocks',
            'sales_mix.created_at as created_at',
            'sales_mix.updated_at as updated_at',
            'sales_mix.OrderNumber as OrderNumber',
            'Image',
        ))->join('sales_mix','sales_mix.MixId','=','order_detail.Product')->join('cases','cases.CaseId','=','sales_mix.ParentCase')->where(array(
            array('OrderId','LIKE',$monthRange.'%'),
            array('ProductType','=','mix'),

            array('cases.Status','=',1),
            array('sales_mix.Status','=',1),
            array('sales_mix.Stocks','>',0),
            array('ActDate','<=',date('Y-m-d')),
            array('EndDate','>=',date('Y-m-d')),
            array('sales_mix.Price','<=',$priceLevel)
        ))->orWhere(array(
            array('OrderId','LIKE',$monthRange.'%'),
            array('ProductType','=','mix'),
            
            array('cases.Status','=',1),
            array('sales_mix.Status','=',1),
            array('sales_mix.Stocks','>',0),
            array('ActDate','<=',date('Y-m-d')),
            array('NoOffShelf','=',1),
            array('sales_mix.Price','<=',$priceLevel)
        ))->groupby('Product')->orderBy('SellingQty','DESC')->union($yearBestSellingWines)
        ->orderBy($convertOrder[$order],strtoupper($dir))->orderBy('OrderNumber','ASC')->orderBy('updated_at','DESC');

        if($takeItems=='all'){
            $unionWines=$unionWines->get();
        }
        else{
            $unionWines=$unionWines->paginate($takeItems);
        }

        if($unionWines->count()>0&&$takeItems!=='all'){
            $data['allWines']=$unionWines->total();
        }
        elseif($unionWines->count()>0&&$takeItems==='all'){
            $data['allWines']=$unionWines->count();
        }
        else{
            $data['allWines']=(Integer)0;
        }

        foreach($unionWines as $itemIdx=>$item){
            $unionWines[$itemIdx]->option = array();
            if($item->ProductType==='wine'){
                $image=$item->Image;
                if(!file_exists(public_path($item->Image))||trim($item->Image)===''){
                    $image="/images/DefaultImages/DefaultWine.png";
                }
                $unionWines[$itemIdx]->Image = $image;

                // find option
                if($optionExist=ProductOptionSetData::where('ProductType','wine')->where('ProductId',$item->Pid)->take(1)->first()){
                    if($optionDetail=ProductOptionData::where('OptionId',$optionExist->OptionId)->take(1)->first()){
                        $unionWines[$itemIdx]->option = array(
                            'Price'=>$optionDetail->Price,
                            'PurchaseAmount'=>$optionDetail->PurchaseAmount
                        );
                    }
                }
            }
            elseif($item->ProductType==='mix'){
                $image=asset($itemInfo->Image);
                if(!file_exists(public_path($item->Image))||trim($item->Image)===''){
                    $image=asset("/images/DefaultImages/DefaultCase.png");
                }
                $unionWines[$itemIdx]->Image = $image;

                // find option
                if($optionExist=ProductOptionSetData::where('ProductType','mix')->where('ProductId',$item->Product)->take(1)->first()){
                    if($optionDetail=ProductOptionData::where('OptionId',$optionExist->OptionId)->take(1)->first()){
                        $unionWines[$itemIdx]->option = array(
                            'Price'=>$optionDetail->Price,
                            'PurchaseAmount'=>$optionDetail->PurchaseAmount
                        );
                    }
                }
            }
        }
        // echo'<pre>';
        // print_R($unionWines);exit();
        $data['wines'] = $unionWines;
        $data['htmlTitle']=Session::get('Language')==='EN'?"BEST WINES UNDER $".$priceLevel:'$'.$priceLevel.'金额以下的最佳销售';
        $breadcrumbs=array(
            array('url'=>'/best-sell/price/'.$priceLevel,'name'=>Session::get('Language')==='EN'?"BEST WINES UNDER $".$priceLevel:'$'.$priceLevel.'金额以下的最佳销售')
        );
        $data['breadcrumbs'] = $breadcrumbs;
        return view('frontend.latest-deals.best-sell-list',$data);        
    }
}