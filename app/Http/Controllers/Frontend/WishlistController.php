<?php
namespace App\Http\Controllers\Frontend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\GiftsData;
use App\Products\SalesMixData;
use App\Products\WinesData;
use App\Products\ProductOptionData;
use App\Products\ProductOptionSetData;
use Session;

class WishlistController extends Controller
{                              
    public function __construct(){
        parent::__construct();
    }

    public function index(){
        $MemberId = Session::get('MemberId');
        // get item information of wishlist
        $wishlist = DB::table('wishlist')->where('MemberId',$MemberId)->get();
        foreach($wishlist as $itemIdx=>$item){
            $itemInfo = false;
            $wishlist[$itemIdx]->Option = false;
            if(trim($item->ProductType)==="Wine"){
                $itemInfo = WinesData::
                    leftJoin('wine_varieties','wine_varieties.VarietyId','=','wines.WineVariety')
                    ->leftJoin('wine_colours','wine_colours.ColourId','=','wines.WineColour')
                    ->leftJoin('wine_styles','wine_styles.StyleId','=','wines.WineStyle')
                    ->leftJoin('wine_regions','wine_regions.RegionId','=','wines.WineRegion')
                    ->leftJoin('wine_closures','wine_closures.ClosureId','=','wines.WineClosure')
                    ->where(array(
                        array('WineId','=',$item->ProductId),
                        array('wines.ActDate','<=',date('Y-m-d')),
                        array('wines.EndDate','>',date('Y-m-d')),
                        array('wines.Status','=',1),
                        array('wine_varieties.Status','=',1),
                        array('wine_colours.Status','=',1),
                        array('wine_styles.Status','=',1),
                        array('wine_regions.Status','=',1),
                        array('wine_closures.Status','=',1)  
                    ))->orWhere(array(
                        array('WineId','=',$item->ProductId),
                        array('wines.ActDate','<=',date('Y-m-d')),
                        array('wines.NoOffShelf','=',1),
                        array('wines.Status','=',1),
                        array('wine_varieties.Status','=',1),
                        array('wine_colours.Status','=',1),
                        array('wine_styles.Status','=',1),
                        array('wine_regions.Status','=',1),
                        array('wine_closures.Status','=',1)
                    ))->take(1)->first();
                if(!$itemInfo){
                    DB::table('wishlist')->where('MemberId',$MemberId)->where('ProductId',$item->ProductId)->delete();
                    unset($wishlist[$itemIdx]);
                }
                else{
                    $wishlist[$itemIdx]->ProductInfo = $itemInfo;
                }
            }
            elseif(trim($item->ProductType)==="Mix"){
                $itemInfo=SalesMixData::join('cases','cases.CaseId','=','sales_mix.ParentCase')->where(array(
                    array('MixId','=',$item->ProductId),
                    array('cases.Status',1),
                    array('sales_mix.Status',1),
                    array('ActDate','<=',date('Y-m-d')),
                    array('EndDate','>=',date('Y-m-d'))
                ))->orWhere(array(
                    array('MixId','=',$item->ProductId),
                    array('cases.Status',1),
                    array('sales_mix.Status',1),
                    array('ActDate','<=',date('Y-m-d')),
                    array('NoOffShelf','=',1)
                ))->take(1)->first();
                if(!$itemInfo){
                    DB::table('wishlist')->where('MemberId',$MemberId)->where('ProductId',$item->ProductId)->delete();
                    unset($wishlist[$itemIdx]);
                }
                else{
                    $wishlist[$itemIdx]->ProductInfo = $itemInfo;
                }
            }
            elseif(trim($item->ProductType)==="Gift"){
                $itemInfo = GiftsData::where(array(
                    array('GiftId','=',$item->ProductId),
                    array('ActDate','<=',date('Y-m-d')),
                    array('EndDate','>',date('Y-m-d')),
                    array('Status','=',1)
                ))->take(1)->first();
                if(!$itemInfo){
                    DB::table('wishlist')->where('MemberId',$MemberId)->where('ProductId',$item->ProductId)->delete();
                    unset($wishlist[$itemIdx]);
                }
                else{
                    $wishlist[$itemIdx]->ProductInfo = $itemInfo;
                }
            }

            if($itemInfo && $optionExist = ProductOptionSetData::where('ProductType',strtolower($item->ProductType))->where('ProductId',$item->ProductId)->take(1)->first()){
                if($optionDetail=ProductOptionData::where('OptionId',$optionExist->OptionId)->take(1)->first()){
                    $wishlist[$itemIdx]->Option = $optionDetail;
                }
            }
        }

        $data['wishlist'] = $wishlist;
        $data['htmlTitle']=Session::get('Language')==='EN'?'Wishlist':'愿望清单';
        return view('frontend.wishlist.index',$data);
    }

    public function addToWish($Ptype,$Pid){
        $MemberId = Session::get('MemberId');
        $wishRecord = DB::table('wishlist')->where(array(
            array("MemberId","=",$MemberId),
            array("ProductId","=",$Pid),
            array("ProductType","=",$Ptype)
        ))->take(1)->first();
        if($wishRecord){
            DB::table('wishlist')->where(array(
                array("MemberId","=",$MemberId),
                array("ProductId","=",$Pid),
                array("ProductType","=",$Ptype)
            ))->update(array(
                'Qty'=>DB::raw('Qty + 1'),
                'updated_at'=>date('Y-m-d H:i:s')
            ));
        }
        else{
            DB::table('wishlist')->insert(array(
                "MemberId"=>$MemberId,
                "ProductId"=>$Pid,
                "ProductType"=>$Ptype,
                "Qty"=>1,
                "Memo"=>"",
                "created_at"=>date('Y-m-d H:i:s'),
                "updated_at"=>date('Y-m-d H:i:s'),
            ));
        }
        // back to index page of wishlist
        return redirect('/wishlist');
    }

    public function updateWishlist(Request $request){
        // Array ( [_token] => 18DZsfTFxXq888uvRJj2DcJb94oA95JOwJOsl0QM [description] => Array ( [vDjN1582730668yzBn] => my new comment ) [do] => [qty] => Array ( [vDjN1582730668yzBn] => 5 ) )
        $descriptions = request('description');
        $qties = request('qty');
        $MemberId = Session::get('MemberId');
        foreach($descriptions as $Pid=>$description){
            $qty = 1;
            // print_R($qties);exit();
            if(isset($qties[$Pid])){
                $qty = intval($qties[$Pid]);
            }
            if($qty<=0)
                $qty = 1;

            DB::table('wishlist')->where(array(
                'MemberId'=>$MemberId,
                'ProductId'=>$Pid
            ))->update(array(
                'Memo'=>$description,
                'Qty'=>$qty,
                'updated_at'=>date('Y-m-d H:i:s')
            ));
        }

        return redirect('/wishlist');
    }

    public function addOneProduct($Pid){
        $ProductName = "";

        $MemberId = Session::get('MemberId');
        $wishlistExist = DB::table('wishlist')->where(array(
            array('MemberId','=',$MemberId),
            array('ProductId','=',$Pid)
        ))->take(1)->first();
        if($wishlistExist){
            if(strtolower(trim($wishlistExist->ProductType))==='wine'){
                $itemInfo = WinesData::
                    leftJoin('wine_varieties','wine_varieties.VarietyId','=','wines.WineVariety')
                    ->leftJoin('wine_colours','wine_colours.ColourId','=','wines.WineColour')
                    ->leftJoin('wine_styles','wine_styles.StyleId','=','wines.WineStyle')
                    ->leftJoin('wine_regions','wine_regions.RegionId','=','wines.WineRegion')
                    ->leftJoin('wine_closures','wine_closures.ClosureId','=','wines.WineClosure')
                    ->where(array(
                        array('WineId','=',$wishlistExist->ProductId),
                        array('wines.ActDate','<=',date('Y-m-d')),
                        array('wines.EndDate','>',date('Y-m-d')),
                        array('wines.Status','=',1),
                        array('wine_varieties.Status','=',1),
                        array('wine_colours.Status','=',1),
                        array('wine_styles.Status','=',1),
                        array('wine_regions.Status','=',1),
                        array('wine_closures.Status','=',1)  
                    ))->orWhere(array(
                        array('WineId','=',$wishlistExist->ProductId),
                        array('wines.ActDate','<=',date('Y-m-d')),
                        array('wines.NoOffShelf','=',1),
                        array('wines.Status','=',1),
                        array('wine_varieties.Status','=',1),
                        array('wine_colours.Status','=',1),
                        array('wine_styles.Status','=',1),
                        array('wine_regions.Status','=',1),
                        array('wine_closures.Status','=',1)
                    ))->take(1)->first();
                if($itemInfo){
                    $ProductName = $itemInfo->Name;
                    if(Session::get('Language')==='EN' && trim($itemInfo->NameEn)!==""){
                        $ProductName = $itemInfo->NameEn;
                    }
                }
            }
            elseif(strtolower(trim($wishlistExist->ProductType))==='mix'){
                $itemInfo=SalesMixData::join('cases','cases.CaseId','=','sales_mix.ParentCase')->where(array(
                    array('MixId','=',$wishlistExist->ProductId),
                    array('cases.Status',1),
                    array('sales_mix.Status',1),
                    array('ActDate','<=',date('Y-m-d')),
                    array('EndDate','>=',date('Y-m-d'))
                ))->orWhere(array(
                    array('MixId','=',$wishlistExist->ProductId),
                    array('cases.Status',1),
                    array('sales_mix.Status',1),
                    array('ActDate','<=',date('Y-m-d')),
                    array('NoOffShelf','=',1)
                ))->take(1)->first();
                if($itemInfo){
                    $ProductName = $itemInfo->MixName;
                    if(Session::get('Language')==='EN' && trim($itemInfo->MixNameEn)!==""){
                        $ProductName = $itemInfo->MixNameEn;
                    }
                }
            }
            elseif(strtolower(trim($wishlistExist->ProductType))==='gift'){
                $itemInfo = GiftsData::where(array(
                    array('GiftId','=',$item->ProductId),
                    array('ActDate','<=',date('Y-m-d')),
                    array('EndDate','>',date('Y-m-d')),
                    array('Status','=',1)
                ))->take(1)->first();

                if($itemInfo){
                    $ProductName = $itemInfo->Name;
                    if(Session::get('Language')==='EN' && trim($itemInfo->NameEn)!==""){
                        $ProductName = $itemInfo->NameEn;
                    }
                }
            }

            if($itemInfo){
                if(Session::get('product.'.$wishlistExist->ProductId)){
                    $oldQuantity=Session::get('product.'.$wishlistExist->ProductId.'.quantity');
                    Session::put("product.".$wishlistExist->ProductId.'.quantity',$oldQuantity+=$wishlistExist->Qty);
                }
                else{
                    Session::put("product.".$wishlistExist->ProductId,array(
                        'type'=>strtolower($wishlistExist->ProductType),
                        'id'=>$wishlistExist->ProductId,
                        'quantity'=>$wishlistExist->Qty
                    ));
                }
            }

            $wishlistExist = DB::table('wishlist')->where(array(
                array('MemberId','=',$MemberId),
                array('ProductId','=',$Pid)
            ))->delete();

            Session::flash('wishlist_add_flag',true);
            if(Session::get('Language')==='EN' && $ProductName!==""){
                Session::flash('wishlist_add_message',$ProductName." was added to your shopping cart.");
            }
            elseif($ProductName!==""){
                Session::flash('wishlist_add_message',$ProductName." 成功加进您的购物车内。");
            }
        }

        return redirect('/wishlist');
    }

    public function addAllWishProducts(){
        $MemberId = Session::get('MemberId');
        $wishlist = DB::table('wishlist')->where(array(
            array('MemberId','=',$MemberId),
        ))->get();
        foreach($wishlist as $item){
            if(strtolower(trim($item->ProductType))==="wine"){
                $itemInfo = WinesData::
                    leftJoin('wine_varieties','wine_varieties.VarietyId','=','wines.WineVariety')
                    ->leftJoin('wine_colours','wine_colours.ColourId','=','wines.WineColour')
                    ->leftJoin('wine_styles','wine_styles.StyleId','=','wines.WineStyle')
                    ->leftJoin('wine_regions','wine_regions.RegionId','=','wines.WineRegion')
                    ->leftJoin('wine_closures','wine_closures.ClosureId','=','wines.WineClosure')
                    ->where(array(
                        array('WineId','=',$item->ProductId),
                        array('wines.ActDate','<=',date('Y-m-d')),
                        array('wines.EndDate','>',date('Y-m-d')),
                        array('wines.Status','=',1),
                        array('wine_varieties.Status','=',1),
                        array('wine_colours.Status','=',1),
                        array('wine_styles.Status','=',1),
                        array('wine_regions.Status','=',1),
                        array('wine_closures.Status','=',1)  
                    ))->orWhere(array(
                        array('WineId','=',$item->ProductId),
                        array('wines.ActDate','<=',date('Y-m-d')),
                        array('wines.NoOffShelf','=',1),
                        array('wines.Status','=',1),
                        array('wine_varieties.Status','=',1),
                        array('wine_colours.Status','=',1),
                        array('wine_styles.Status','=',1),
                        array('wine_regions.Status','=',1),
                        array('wine_closures.Status','=',1)
                    ))->take(1)->first();               
            }
            elseif(strtolower(trim($item->ProductType))==="mix"){
                $itemInfo=SalesMixData::join('cases','cases.CaseId','=','sales_mix.ParentCase')->where(array(
                    array('MixId','=',$item->ProductId),
                    array('cases.Status',1),
                    array('sales_mix.Status',1),
                    array('ActDate','<=',date('Y-m-d')),
                    array('EndDate','>=',date('Y-m-d'))
                ))->orWhere(array(
                    array('MixId','=',$item->ProductId),
                    array('cases.Status',1),
                    array('sales_mix.Status',1),
                    array('ActDate','<=',date('Y-m-d')),
                    array('NoOffShelf','=',1)
                ))->take(1)->first();
            }
            elseif(strtolower(trim($item->ProductType))==='gift'){
                $itemInfo = GiftsData::where(array(
                    array('GiftId','=',$item->ProductId),
                    array('ActDate','<=',date('Y-m-d')),
                    array('EndDate','>',date('Y-m-d')),
                    array('Status','=',1)
                ))->take(1)->first();
            }

            if($itemInfo){
                if(Session::get('product.'.$item->ProductId)){
                    $oldQuantity=Session::get('product.'.$item->ProductId.'.quantity');
                    Session::put("product.".$item->ProductId.'.quantity',$oldQuantity+=$item->Qty);
                }
                else{
                    Session::put("product.".$item->ProductId,array(
                        'type'=>strtolower($item->ProductType),
                        'id'=>$item->ProductId,
                        'quantity'=>$item->Qty
                    ));
                }

                DB::table('wishlist')->where('MemberId',$MemberId)->where('ProductId',$item->ProductId)->delete();
            }            
        }
    }
}