<?php
namespace App\Http\Controllers\Frontend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\GiftsData;
use App\GiftBannersData;

use App\Members\MembersData;
use Session;
use Widget_Helper;

class GiftsController extends Controller
{                              
    public function __construct(){
        parent::__construct();
    }

    public function index(Request $request){
        $data['htmlTitle']=Session::get('Language')==='EN'?'Gifts':'礼品';

        $breadcrumbs=array(
            array('url'=>'/gifts','name'=>Session::get('Language')==='EN'?'Gifts':'礼品'),
        );
        $data['breadcrumbs'] = $breadcrumbs;

        $banners = GiftBannersData::where(array(
            array('BannerOnStatus','=',1),
            array('BannerOnDate','<=',date('Y-m-d')),
            array('BannerOffDate','>',date('Y-m-d'))
        ))->orderBy('BannerOrder','ASC')->orderBy('updated_at','DESC')->get();
        $data['banners'] = $banners;

        $categories = DB::table('gift_categories')->orderBy('CategoryOrder','ASC')->get();
        $data['categories'] = $categories;

        $data['gift_business_image'] = '';
        if( $gift_business_image_query = DB::table('template_static_data')->where('DataKey','gift_business_image')->take(1)->first() ){
            $data['gift_business_image'] = $gift_business_image_query->Data;
        }
        return view('frontend.gifts.index',$data);
    }

    public function category($categoryName){
        $findCategory = DB::table('gift_categories')->where('CategoryEn',$categoryName)->take(1)->first();
        if($findCategory){
            // stdClass Object ( [CategoryId] => 1 [Category] => 木製禮品盒 [CategoryEn] => Wooden Gift Boxes [Image] => file-manager\Gifts\GiftBoxes-335x301.jpg [CategoryOrder] => 1 [created_at] => 0000-00-00 00:00:00 [updated_at] => 2020-05-11 07:58:55 )
            $data['category'] = Session::get('Language')==='EN' && trim($findCategory->CategoryEn)!=='' ? $findCategory->CategoryEn:$findCategory->Category;
            
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

            $query=GiftsData::where(array(
                array('Category','=',$findCategory->CategoryId),
                array('ActDate','<=',date('Y-m-d')),
                array('EndDate','>',date('Y-m-d')),
                array('Status','=',1)
            ))->orderBy($convertOrder[$order],strtoupper($dir))->orderBy('OrderNumber','ASC')->orderBy('updated_at','DESC');

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

            // breadcrumbs
            $breadcrumbs = array(
                Session::get('Language')==='EN' ? array('url'=>'/gifts','name'=>'Gifts') : array('url'=>'/gifts','name'=>'礼品'),
                array('name'=>Session::get('Language')==='EN'&&trim($findCategory->CategoryEn)!==''?$findCategory->CategoryEn:$findCategory->Category)
            );
            $data['breadcrumbs'] = $breadcrumbs;
            $data['wines']=$wines;
            $data['htmlTitle']=Session::get('Language')==='EN'?"Gifts":"礼品";     
            return view('frontend.gift_wines',$data);
        }
        return redirect('/gifts');
    }

    public function giftDetail($GiftId){
        $wine = GiftsData::where(array(
            array('GiftId','=',$GiftId),
            array('ActDate','<=',date('Y-m-d')),
            array('EndDate','>',date('Y-m-d')),
            array('Status','=',1)            
        ))->take(1)->first();
        if($wine){
            $categoryInfo = DB::table('gift_categories')->where('CategoryId',$wine->Category)->take(1)->first();            
            // breadcrumbs
            $breadcrumbs = array(
                Session::get('Language')==='EN' ? array('url'=>'/gifts','name'=>'Gifts') : array('url'=>'/gifts','name'=>'礼品'),
            );

            if($categoryInfo){
                array_push($breadcrumbs,array('url'=>'/gifts/'.$categoryInfo->CategoryEn,'name'=>Session::get('Language')==='EN'&&trim($categoryInfo->CategoryEn)!==''?$categoryInfo->CategoryEn:$categoryInfo->Category));
            }
            array_push($breadcrumbs,array('name'=>Session::get('Language')==='EN'&&trim($wine->NameEn)!==''?$wine->NameEn:$wine->Name));
            $data['breadcrumbs'] = $breadcrumbs;
            $data['WineInfo'] = $wine;
            $data['htmlTitle']=Session::get('Language')==='EN'&&trim($wine->NameEn)!==''?$wine->NameEn:$wine->Name;

            return view('frontend.gift_wine',$data);
        }
        return redirect('/gifts');
    }
}