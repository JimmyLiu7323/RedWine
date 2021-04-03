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
use App\GiftsData;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

use Session;
use Validator;
use Redirect;
use Widget_Helper;
use Config;

class ShoppingController extends Controller
{                              
    public function __construct(){
        parent::__construct();
    }

    public function addItem(Request $request){
        // Array ( [itemType] => mix [itemId] => Szoi1578900276OWXo [itemQuantity] => 3 )
        // Array ( [itemType] => wine [itemId] => AjAH1578900276OEYj [itemQuantity] => 1 )
        $itemInfo=false;
        $itemType=trim(request('itemType'));
        $itemId=trim(request('itemId'));
        $itemQuantity=intval(request('itemQuantity'));

        if($itemQuantity>0){
            if($itemType==='mix'){
                $itemInfo=SalesMixData::join('cases','cases.CaseId','=','sales_mix.ParentCase')->where(array(
                    array('MixId','=',$itemId),
                    array('cases.Status','=',1),
                    array('sales_mix.Status','=',1),
                    array('sales_mix.Stocks','>',0),
                    array('ActDate','<=',date('Y-m-d')),
                    array('EndDate','>=',date('Y-m-d'))
                ))->orWhere(array(
                    array('MixId','=',$itemId),
                    array('cases.Status','=',1),
                    array('sales_mix.Status','=',1),
                    array('sales_mix.Stocks','>',0),
                    array('ActDate','<=',date('Y-m-d')),
                    array('NoOffShelf','=',1)
                ))->orderBy('cases.OrderNumber','ASC')->orderBy('sales_mix.OrderNumber','ASC')->take(1)->first();
            }
            elseif($itemType==='wine'){
                $itemInfo=WinesData::where(array(
                    array('WineId','=',$itemId),
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
                    array('WineId','=',$itemId),
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
                  ->leftJoin('wine_closures','wine_closures.ClosureId','=','wines.WineClosure')->take(1)->first();
            }
            elseif($itemType==='gift'){
                $itemInfo = GiftsData::where(array(
                    array('GiftId','=',$itemId),
                    array('ActDate','<=',date('Y-m-d')),
                    array('EndDate','>',date('Y-m-d')),
                    array('Status','=',1)     
                ))->take(1)->first();
            }

            $cart=array(
                'items'=>array(),
                'itemCounts'=>0,
                'subtotal'=>0
            );
            if($itemInfo){
                if(Session::get('product.'.$itemId)){
                    $oldQuantity=Session::get('product.'.$itemId.'.quantity');
                    Session::put("product.".$itemId.'.quantity',$oldQuantity+=$itemQuantity);
                }
                else{
                    Session::put("product.".$itemId,array(
                        'type'=>$itemType,
                        'id'=>$itemId,
                        'quantity'=>$itemQuantity
                    ));
                }

                foreach(Session::get('product') as $item){
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
                                'price'=>$query->S_price?$query->S_price:$query->Price,
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
                                'price'=>$query->S_price?$query->S_price:$query->Price,
                                'name'=>(Session::get('Language')==='EN' && trim($query->NameEn)!=='')?$query->NameEn:$query->Name,
                                'option'=>$option
                            );
                        }
                    }
                    elseif($item['type']==='gift'){
                        $query = GiftsData::where(array(
                            array('GiftId','=',$itemId),
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
                        array_push($cart['items'],$cartItem);
                        $cart['subtotal']+=$cartItem['price']*$item['quantity'];
                        $cart['itemCounts']+=$item['quantity'];
                    }
                }

                $cart['subtotal'] = 0;
                foreach($cart['items'] as $itemIdx=>$item){
                    if(count($item['option'])>0){
                        if($cart['itemCounts']>intval($item['option']['PurchaseAmount'])){
                            $cart['items'][$itemIdx]['price'] = $item['option']['Price'];
                            $cart['subtotal']+=$cart['items'][$itemIdx]['price']*$item['quantity'];
                        }
                        else{
                            $cart['subtotal']+=$item['price']*$item['quantity'];    
                        }
                    }
                    else{
                        $cart['subtotal']+=$item['price']*$item['quantity'];
                    }
                }
                $cart['subtotal']=number_format($cart['subtotal'],2);

                return json_encode(array(
                    'status'=>'success',
                    'cart'=>$cart
                ));
            }
            else{
                return json_encode(array(
                    'status'=>'warning',
                    'msg'=>Session::get('Language')==='EN'?'Cannot find product information':'查无商品资料'
                ));
            }
        }
        else{
            return json_encode(array(
                'status'=>'warning',
                'msg'=>Session::get('Language')==='EN'?'Quantity should be bigger than 0':'商品数量需大于零'
            ));
        }
    }

    public function updateItem(Request $request){
        // Array ( [itemId] => Szoi1578900276OWXo [itemQuantity] => 28 )
        $itemId=request('itemId');
        $itemQuantity=intval(request('itemQuantity'));
        if($itemQuantity>0){
            // find product in session
            $query=false;
            $hasSessionProduct=Session::get('product.'.$itemId);
            if($hasSessionProduct){
                $itemType=Session::get("product.$itemId.type");
                if($itemType=='mix'){
                    $query=SalesMixData::join('cases','cases.CaseId','=','sales_mix.ParentCase')->where(array(
                        array('MixId','=',$itemId),
                        array('cases.Status','=',1),
                        array('sales_mix.Status','=',1),
                        array('sales_mix.Stocks','>',0),
                        array('ActDate','<=',date('Y-m-d')),
                        array('EndDate','>=',date('Y-m-d'))
                    ))->orWhere(array(
                        array('MixId','=',$itemId),
                        array('cases.Status','=',1),
                        array('sales_mix.Status','=',1),
                        array('sales_mix.Stocks','>',0),
                        array('ActDate','<=',date('Y-m-d')),
                        array('NoOffShelf','=',1)
                    ))->orderBy('cases.OrderNumber','ASC')->orderBy('sales_mix.OrderNumber','ASC')->take(1)->first();

                    if($query){
                        Session::put("product.$itemId.quantity",$itemQuantity);
                    }
                }
                else{
                    $query=WinesData::where(array(
                        array('WineId','=',$itemId),
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
                        array('WineId','=',$itemId),
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
                      ->leftJoin('wine_closures','wine_closures.ClosureId','=','wines.WineClosure')->take(1)->first();

                    if($query){
                        Session::put("product.$itemId.quantity",$itemQuantity);
                    }
                }

                // get subtotal
                $cart=array(
                    'items'=>array(),
                    'itemCounts'=>0,
                    'subtotal'=>0
                );

                foreach(Session::get("product",array()) as $product){
                    $query=false;
                    if($product['type']=='mix'){
                        $query=SalesMixData::join('cases','cases.CaseId','=','sales_mix.ParentCase')->where(array(
                            array('MixId','=',$product['id']),
                            array('cases.Status','=',1),
                            array('sales_mix.Status','=',1),
                            array('ActDate','<=',date('Y-m-d')),
                            array('EndDate','>=',date('Y-m-d'))
                        ))->orWhere(array(
                            array('MixId','=',$product['id']),
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
                            if($optionExist=ProductOptionSetData::where('ProductType','mix')->where('ProductId',$product['id'])->take(1)->first()){
                                if($optionDetail=ProductOptionData::where('OptionId',$optionExist->OptionId)->take(1)->first()){
                                    $option=array(
                                        'Price'=>$optionDetail->Price,
                                        'PurchaseAmount'=>$optionDetail->PurchaseAmount
                                    );
                                }
                            }

                            $cartItem=array(
                                'id'=>$product['id'],
                                'image'=>$image,
                                'quantity'=>$product['quantity'],
                                'price'=>$query->S_price?$query->S_price:$query->Price,
                                'name'=>(Session::get('Language')==='EN' && trim($query->MixNameEn)!=='')?$query->MixNameEn:$query->MixName,
                                'option'=>$option
                            );
                        }
                    }
                    else{
                        $query=WinesData::where(array(
                            array('WineId','=',$product['id']),
                            array('ActDate','<=',date('Y-m-d')),
                            array('EndDate','>',date('Y-m-d')),
                            array('wines.Status','=',1),
                            array('wine_varieties.Status','=',1),
                            array('wine_colours.Status','=',1),
                            array('wine_styles.Status','=',1),
                            array('wine_regions.Status','=',1),
                            array('wine_closures.Status','=',1)            
                        ))->orWhere(array(
                            array('WineId','=',$product['id']),
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
                            if($optionExist=ProductOptionSetData::where('ProductType','wine')->where('ProductId',$product['id'])->take(1)->first()){
                                if($optionDetail=ProductOptionData::where('OptionId',$optionExist->OptionId)->take(1)->first()){
                                    $option=array(
                                        'Price'=>$optionDetail->Price,
                                        'PurchaseAmount'=>$optionDetail->PurchaseAmount
                                    );
                                }
                            }

                            $cartItem=array(
                                'id'=>$product['id'],
                                'image'=>$image,
                                'quantity'=>$product['quantity'],
                                'price'=>$query->S_price?$query->S_price:$query->Price,
                                'name'=>(Session::get('Language')==='EN' && trim($query->NameEn)!=='')?$query->NameEn:$query->Name,
                                'option'=>$option
                            );                           
                        }
                    }

                    if($query){
                        array_push($cart['items'],$cartItem);
                        $cart['subtotal']+=$cartItem['price']*$product['quantity'];
                        $cart['itemCounts']+=$product['quantity'];
                    }                    
                }

                $cart['subtotal'] = 0;
                foreach($cart['items'] as $itemIdx=>$item){
                    if(count($item['option'])>0){
                        if($cart['itemCounts']>intval($item['option']['PurchaseAmount'])){
                            $cart['items'][$itemIdx]['price'] = $item['option']['Price'];
                            $cart['subtotal']+=$cart['items'][$itemIdx]['price']*$item['quantity'];
                        }
                        else{
                            $cart['subtotal']+=$item['price']*$item['quantity'];    
                        }
                    }
                    else{
                        $cart['subtotal']+=$item['price']*$item['quantity'];
                    }
                }

                $cart['subtotal']=number_format($cart['subtotal'],2);
                return json_encode(array(
                    'status'=>'success',
                    'cart'=>$cart
                ));
            }
            else{
                return json_encode(array(
                    'status'=>'warning',
                    'msg'=>'You haven\'t had this product'
                ));
            }
        }
        else{
            return json_encode(array(
                'status'=>'warning',
                'msg'=>'Quantity should be bigger than 0'
            ));
        }
    }

    public function cart(){
        $total=0;
        $cartItems=array();
        $data['htmlTitle']=Session::get('Language')==='EN'?"Your Cart":'您的购物车';
        $cartItemCounts = 0;
        foreach(Session::get('product',array()) as $item){
            $cartItem = array();
            $query = false;
            $quantity = intval($item['quantity']);
            if(trim($item['type'])==='mix'){
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
                    $cartItemCounts+=$quantity;
                    $cartItem=array(
                        'type'=>'mix',
                        'id'=>$query->MixId,
                        'image'=>$query->Image,
                        'name'=>(Session::get('Language')==='EN' && trim($query->MixNameEn)!=='' ) ? $query->MixNameEn : $query->MixName,
                        'price'=>$query->S_price?$query->S_price:$query->Price,
                        'qty'=>$quantity,
                        'contents'=>array(),
                        'option'=>array(),
                        'option_notice'=>''
                    );

                    // find option
                    if($optionExist=ProductOptionSetData::where('ProductType','mix')->where('ProductId',$query->MixId)->take(1)->first()){
                        if($optionDetail=ProductOptionData::where('OptionId',$optionExist->OptionId)->take(1)->first()){
                            $cartItem['option']=array(
                                'Price'=>$optionDetail->Price,
                                'PurchaseAmount'=>$optionDetail->PurchaseAmount
                            );
                        }
                    }                  

                    $cartItem['subtotal']=$cartItem['price']*$quantity;
                    $total+=$cartItem['subtotal'];
                    $mixContents=DB::table('sales_mix_content')->where('MixId',$query->MixId)->select('Content')->take(1)->first();
                    if($mixContents){
                        $mixContents=json_decode($mixContents->Content);
                        foreach($mixContents as $wineId=>$wineQuantity){
                            $wineInfo=WinesData::where(array(
                                array('WineId','=',$wineId),
                            ))->orWhere(array(
                                array('WineId','=',$wineId),
                            ))->take(1)->first();
                            if($wineInfo){
                                array_push($cartItem['contents'],array(
                                    'info'=>$wineInfo,
                                    'quantity'=>$wineQuantity
                                ));
                            }
                        }
                    }
                }
            }
            elseif(trim($item['type'])==='wine'){
                $query=WinesData::where(array(
                    array('WineId','=',$item['id']),
                    array('ActDate','<=',date('Y-m-d')),
                    array('EndDate','>',date('Y-m-d')),
                    array('wines.Status','=',1),         
                ))->orWhere(array(
                    array('WineId','=',$item['id']),
                    array('wines.ActDate','<=',date('Y-m-d')),
                    array('wines.NoOffShelf','=',1),
                    array('wines.Status','=',1),
                ))->take(1)->first();

                if($query){
                    $cartItemCounts+=$quantity;
                    $cartItem=array(
                        'type'=>'wine',
                        'id'=>$query->WineId,
                        'image'=>$query->Image,
                        'name'=>Session::get('Language')==='EN'&&trim($query->NameEn)!==''?$query->NameEn:$query->Name,
                        'price'=>$query->S_price?$query->S_price:$query->Price,
                        'qty'=>$quantity,
                        'option'=>array()
                    );

                    // find option
                    if($optionExist=ProductOptionSetData::where('ProductType','wine')->where('ProductId',$query->WineId)->take(1)->first()){
                        if($optionDetail=ProductOptionData::where('OptionId',$optionExist->OptionId)->take(1)->first()){
                            $cartItem['option']=array(
                                'Price'=>$optionDetail->Price,
                                'PurchaseAmount'=>$optionDetail->PurchaseAmount
                            );
                        }
                    }

                    $cartItem['subtotal']=$cartItem['price']*$quantity;
                    $total+=$cartItem['subtotal'];
                }
            }
            elseif(trim($item['type'])==='gift'){
                $query = GiftsData::where(array(
                    array('GiftId','=',$item['id']),
                    array('ActDate','<=',date('Y-m-d')),
                    array('EndDate','>',date('Y-m-d')),
                    array('Status','=',1)     
                ))->take(1)->first();

                if($query){
                    $cartItemCounts+=$quantity;
                    $cartItem=array(
                        'type'=>'gift',
                        'id'=>$query->GiftId,
                        'image'=>$query->Image,
                        'name'=>Session::get('Language')==='EN'&&trim($query->NameEn)!==''?$query->NameEn:$query->Name,
                        'price'=>$query->S_price?$query->S_price:$query->Price,
                        'qty'=>$quantity,
                        'option'=>array()
                    );

                    $cartItem['subtotal']=$cartItem['price']*$quantity;
                    $total+=$cartItem['subtotal'];
                }                
            }

            if($query){
                array_push($cartItems,$cartItem);
            }
        } 

        if(!empty($cartItems)){
            // update by option condition
            $total = 0;
            $canSave = false;
            $savePrice = 0;
            $needBottles = 0;
            foreach($cartItems as $itemIdx=>$item){
                if(count($item['option'])>0){
                    if($cartItemCounts>intval($item['option']['PurchaseAmount'])){
                        $cartItems[$itemIdx]['price'] = $item['option']['Price'];
                        $cartItems[$itemIdx]['subtotal'] = $cartItems[$itemIdx]['price']*$item['qty'];
                    }
                    else{
                        $cartItems[$itemIdx]['subtotal'] = $item['price']*$item['qty'];
                        $canSave = true;
                        $savePrice += ($cartItems[$itemIdx]['price'] - $item['option']['Price']) * $item['qty'];
                        $needBottles += ($item['option']['PurchaseAmount'] - $cartItemCounts);
                    }
                }
                else{
                    $cartItems[$itemIdx]['subtotal'] = $item['price']*$item['qty'];
                }
                $total += $cartItems[$itemIdx]['subtotal'];

                $cartItems[$itemIdx]['subtotal'] = number_format($cartItems[$itemIdx]['subtotal'],2);
            }
            $savePrice = number_format($savePrice,2);
            $data['canSave'] = $canSave;
            $data['savePrice'] = $savePrice;
            $data['needBottles'] = $needBottles;

            $shipping_countries=DB::table('countries')->orderBy('CountryId')->get();
            $shipping_regions=array();
            foreach($shipping_countries as $shipping_country){
                if($freightQuery=Freight_GSTData::where('CountryId',$shipping_country->CountryId)->take(1)->first()){
                    $freightStores=json_decode($freightQuery->Freight_store,true);
                    foreach($freightStores as $freightStore){
                        $currentRegion=$freightStore[0];
                        if(!isset($shipping_regions[$shipping_country->CountryId]))
                            $shipping_regions[$shipping_country->CountryId]=array();

                        if(!in_array($currentRegion,$shipping_regions[$shipping_country->CountryId]))
                            array_push($shipping_regions[$shipping_country->CountryId],$currentRegion);
                    }
                }
            }

            $data['shipping_countries']=$shipping_countries;
            $data['shipping_regions']=json_encode($shipping_regions);
        }

        $data['total']=number_format($total,2);
        $data['cartItems']=$cartItems;
        return view('frontend.cart',$data);
    }

    public function deleteItem($itemId){
        if(Session::get('product.'.$itemId)){
            Session::forget('product.'.$itemId);
        }
        return redirect('/shopping/cart');
    }

    public function ajx_deleteItem($itemId){
        if(Session::get('product.'.$itemId)){
            Session::forget('product.'.$itemId);
        }

        $cart=array(
            'items'=>array(),
            'itemCounts'=>0,
            'subtotal'=>0
        );
        foreach(Session::get('product') as $item){
            if($item['id']!==$itemId){
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
                            'price'=>$query->S_price?$query->S_price:$query->Price,
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
                            'price'=>$query->S_price?$query->S_price:$query->Price,
                            'name'=>(Session::get('Language')==='EN' && trim($query->NameEn)!=='')?$query->NameEn:$query->Name,
                            'option'=>$option
                        );
                    }
                }
                elseif($item['type']==='gift'){
                    $query = GiftsData::where(array(
                        array('GiftId','=',$itemId),
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
                    array_push($cart['items'],$cartItem);
                    $cart['subtotal']+=$cartItem['price']*$item['quantity'];
                    $cart['itemCounts']+=$item['quantity'];
                }
            }
        }

        $cart['subtotal'] = 0;
        foreach($cart['items'] as $itemIdx=>$item){
            if(count($item['option'])>0){
                if($cart['itemCounts']>intval($item['option']['PurchaseAmount'])){
                    $cart['items'][$itemIdx]['price'] = $item['option']['Price'];
                    $cart['subtotal']+=$cart['items'][$itemIdx]['price']*$item['quantity'];
                }
                else{
                    $cart['subtotal']+=$item['price']*$item['quantity'];    
                }
            }
            else{
                $cart['subtotal']+=$item['price']*$item['quantity'];
            }
        }
        $cart['subtotal']=number_format($cart['subtotal'],2);

        return json_encode(array(
            'status'=>'success',
            'cart'=>$cart
        ));
    }

    public function updateCart(Request $request){
        $itemQty=request('itemQty',array());
        foreach($itemQty as $itemId=>$qty){
            if(Session::get('product.'.$itemId)){
                if(intval($qty)>0){
                    $productType=trim(Session::get('product.'.$itemId.'.type'));
                    if($productType==='mix'){
                        $query=SalesMixData::where(array(
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
                        ))->take(1)->first();

                        if($query){
                            Session::put("product.".$itemId.'.quantity',$qty);
                        }
                    }
                    elseif($productType==='wine'){
                        $query=WinesData::where(array(
                            array('WineId','=',$itemId),
                            array('ActDate','<=',date('Y-m-d')),
                            array('EndDate','>',date('Y-m-d')),
                            array('wines.Status','=',1),         
                        ))->orWhere(array(
                            array('WineId','=',$itemId),
                            array('wines.ActDate','<=',date('Y-m-d')),
                            array('wines.NoOffShelf','=',1),
                            array('wines.Status','=',1),
                        ))->take(1)->first();

                        if($query){
                            Session::put("product.".$itemId.'.quantity',$qty);
                        }
                    }
                    elseif($productType==='gift'){
                        $query = GiftsData::where(array(
                            array('GiftId','=',$itemId),
                            array('ActDate','<=',date('Y-m-d')),
                            array('EndDate','>',date('Y-m-d')),
                            array('Status','=',1)     
                        ))->take(1)->first();

                        if($query){
                            Session::put("product.".$itemId.'.quantity',$qty);
                        }
                    }
                }
            }
        }
        return redirect('/shopping/cart');
    }

    public function estimateUpdatePost(Request $request){
        // Array ( [country] => NZ [region] => )
        $shipping_country=trim(request('country'));
        $shipping_region=trim(request('region'));

        $Freight=array(
            'store'=>array(),
            'home'=>100
        );
        $freight_gstSet=Freight_GSTData::where('CountryId',$shipping_country)->take(1)->first();
        if($freight_gstSet){
            $Freight['home']=$freight_gstSet->Freight_home;
            $Freight_store=json_decode($freight_gstSet->Freight_store,true);
            foreach($Freight_store as $store){
                if($shipping_region!==""){
                    if($store[0]===$shipping_region){
                        array_push($Freight['store'],array(
                            'address'=>$store[2],
                            'cost'=>$store[3],
                            'mapurl'=>$store[4]
                        ));                        
                    }
                }
                else{
                    array_push($Freight['store'],array(
                        'address'=>$store[2],
                        'cost'=>$store[3],
                        'mapurl'=>$store[4]
                    ));
                }
            }
        }

        $cartItemCounts = 0;
        foreach(Session::get('product',array()) as $item){
            $cartItemCounts += $item['quantity'];
        }

        $beforeTotal=0;
        foreach(Session::get('product',array()) as $item){
            $query=false;
            $quantity=intval($item['quantity']);

            if(trim($item['type'])==='mix'){
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
                ))->take(1)->first();

                if($query){
                    $basePrice=$query->S_price?$query->S_price:$query->Price;
                    // find option
                    if($optionExist=ProductOptionSetData::where('ProductType','mix')->where('ProductId',$item['id'])->take(1)->first()){
                        if($optionDetail=ProductOptionData::where('OptionId',$optionExist->OptionId)->take(1)->first()){
                            if($cartItemCounts>intval($optionDetail->PurchaseAmount)){
                                $basePrice = $optionDetail->Price;
                            }
                        }
                    }
                    $beforeTotal+=$basePrice*$quantity;
                }
            }
            elseif(trim($item['type'])==='wine'){
                $query=WinesData::where(array(
                    array('WineId','=',$item['id']),
                    array('ActDate','<=',date('Y-m-d')),
                    array('EndDate','>',date('Y-m-d')),
                    array('wines.Status','=',1),         
                ))->orWhere(array(
                    array('WineId','=',$item['id']),
                    array('wines.ActDate','<=',date('Y-m-d')),
                    array('wines.NoOffShelf','=',1),
                    array('wines.Status','=',1),
                ))->take(1)->first();

                if($query){
                    $basePrice=$query->S_price?$query->S_price:$query->Price;
                    // find option
                    if($optionExist=ProductOptionSetData::where('ProductType','wine')->where('ProductId',$item['id'])->take(1)->first()){
                        if($optionDetail=ProductOptionData::where('OptionId',$optionExist->OptionId)->take(1)->first()){
                            if($cartItemCounts>intval($optionDetail->PurchaseAmount)){
                                $basePrice = $optionDetail->Price;
                            }
                        }
                    }
                    $beforeTotal+=$basePrice*$quantity;                    
                }
            }
            elseif(trim($item['type'])==='gift'){
                $query = GiftsData::where(array(
                    array('GiftId','=',$item['id']),
                    array('ActDate','<=',date('Y-m-d')),
                    array('EndDate','>',date('Y-m-d')),
                    array('Status','=',1)     
                ))->take(1)->first();

                if($query){
                    $basePrice=$query->S_price?$query->S_price:$query->Price;
                    $beforeTotal+=$basePrice*$quantity;                    
                }   
            }
        }

        $Freight_price=round($Freight['home'],2);

        $afterTotal = $beforeTotal + $Freight_price;
        $GST_price = round($afterTotal * 3 / 23,2);
        //removed by Jimmy 2020/5/20
        //$afterTotal += $GST_price;

        if(Session::get('MemberId')){
            $MemberDiscount = env('MemberDiscount',90);   
            $afterTotal = $afterTotal*($MemberDiscount/100);
        }
        $afterTotal = number_format($afterTotal,2);
        if(Session::get('Language')==='EN' && Session::get('MemberId')){
            $afterTotal.=" <text style='color:#FCAB64'>(Discount)</text>";
        }
        elseif(Session::get('Language')!=='EN' && Session::get('MemberId')){
            $afterTotal.=" <text style='color:#FCAB64'>(会员折扣)</text>";
        }

        Session::put('shipping_country',$shipping_country);
        Session::put('shipping_region',$shipping_region);
        Session::put('deliveryMethod','deliveryHome');
        return json_encode(array(
            'GST_price'=>$GST_price,
            'Freight_price'=>$Freight_price,
            'stores'=>$Freight['store'],
            'home'=>$Freight['home'],
            'beforeTotal'=>number_format($beforeTotal,2),
            'afterTotal'=>$afterTotal
        ));
    }

    public function chooseDeliveryMethod(Request $request){
        // Array ( [country] => TW [region] => 新北市 [deliveryMethod] => store_0 )
        $shipping_country=trim(request('country'));
        $shipping_region=trim(request('region'));
        $deliveryMethod=trim(request('deliveryMethod'));

        Session::put('shipping_country',$shipping_country);
        Session::put('shipping_region',$shipping_region);
        Session::put('deliveryMethod',$deliveryMethod);

        if($deliveryMethod!=="deliveryHome")
            $deliveryMethod=str_replace("store_","",$deliveryMethod);

        $Freight=array(
            'store'=>100,
            'home'=>100
        );
        $freight_gstSet=Freight_GSTData::where('CountryId',$shipping_country)->take(1)->first();
        if($freight_gstSet){
            $Freight['home']=$freight_gstSet->Freight_home;
            $Freight_store=json_decode($freight_gstSet->Freight_store,true);
            if($shipping_region!==""){
                if(isset($Freight_store[$deliveryMethod])){
                    $Freight['store']=$Freight_store[$deliveryMethod][3];
                }
            }
        }

        $cartItemCounts = 0;
        foreach(Session::get('product',array()) as $item){
            $cartItemCounts += $item['quantity'];
        }

        $beforeTotal=0;
        foreach(Session::get('product',array()) as $item){
            $query=false;
            $quantity=intval($item['quantity']);

            if(trim($item['type'])==='mix'){
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
                ))->take(1)->first();

                if($query){
                    $basePrice=$query->S_price?$query->S_price:$query->Price;
                    // find option
                    if($optionExist=ProductOptionSetData::where('ProductType','mix')->where('ProductId',$item['id'])->take(1)->first()){
                        if($optionDetail=ProductOptionData::where('OptionId',$optionExist->OptionId)->take(1)->first()){
                            if($cartItemCounts>intval($optionDetail->PurchaseAmount)){
                                $basePrice = $optionDetail->Price;
                            }
                        }
                    }
                    $beforeTotal+=$basePrice*$quantity;
                }
            }
            elseif(trim($item['type'])==='wine'){
                $query=WinesData::where(array(
                    array('WineId','=',$item['id']),
                    array('ActDate','<=',date('Y-m-d')),
                    array('EndDate','>',date('Y-m-d')),
                    array('wines.Status','=',1),         
                ))->orWhere(array(
                    array('WineId','=',$item['id']),
                    array('wines.ActDate','<=',date('Y-m-d')),
                    array('wines.NoOffShelf','=',1),
                    array('wines.Status','=',1),
                ))->take(1)->first();

                if($query){
                    $basePrice=$query->S_price?$query->S_price:$query->Price;
                    // find option
                    if($optionExist=ProductOptionSetData::where('ProductType','wine')->where('ProductId',$item['id'])->take(1)->first()){
                        if($optionDetail=ProductOptionData::where('OptionId',$optionExist->OptionId)->take(1)->first()){
                            if($cartItemCounts>intval($optionDetail->PurchaseAmount)){
                                $basePrice = $optionDetail->Price;
                            }
                        }
                    }
                    $beforeTotal+=$basePrice*$quantity;
                }
            }
            elseif(trim($item['type'])==='gift'){
                $query = GiftsData::where(array(
                    array('GiftId','=',$item['id']),
                    array('ActDate','<=',date('Y-m-d')),
                    array('EndDate','>',date('Y-m-d')),
                    array('Status','=',1)     
                ))->take(1)->first();
                
                if($query){
                    $basePrice=$query->S_price?$query->S_price:$query->Price;
                    $beforeTotal+=$basePrice*$quantity;                    
                }   
            }
        }

        if($deliveryMethod==="deliveryHome")
            $Freight_price=round($Freight['home'],2);
        else
            $Freight_price=round($Freight['store'],2);

        $GST_price=round(($beforeTotal + $Freight_price) * 3 / 23,2);
        //$afterTotal=$beforeTotal+$GST_price+$Freight_price;            
        // removed $GST_Price by Jimmy 2020/5/20
        $afterTotal=$beforeTotal+$Freight_price;  
        
        if(Session::get('MemberId')){
            $MemberDiscount = env('MemberDiscount',90);   
            $afterTotal = $afterTotal*($MemberDiscount/100);
        }
        $afterTotal = number_format($afterTotal,2);
        if(Session::get('Language')==='EN'&&Session::get('MemberId')){
            $afterTotal.=" <text style='color:#FCAB64'>(Discount)</text>";
        }
        elseif(Session::get('Language')!=='EN'&&Session::get('MemberId')){
            $afterTotal.=" <text style='color:#FCAB64'>(会员折扣)</text>";
        }

        return json_encode(array(
            'GST_price'=>$GST_price,
            'Freight_price'=>$Freight_price,
            'stores'=>$Freight['store'],
            'home'=>$Freight['home'],
            'beforeTotal'=>number_format($beforeTotal,2),
            'afterTotal'=>$afterTotal
        ));
    }

    public function checkout(Request $request){
        $hasCart=Session::get('product');
        if($hasCart){
            $shipping_country=Session::get('shipping_country','NZ');
            $shipping_region=Session::get('shipping_region');
            $deliveryMethod=Session::get('deliveryMethod','deliveryHome');
            $cartProducts=Session::get('product');

            $orderSection=array();
            $beforeTotal=0;

            $cartItemCounts = 0;
            foreach($cartProducts as $item){
                $cartItemCounts += $item['quantity'];
            }

            foreach($cartProducts as $product){
                $quantity=intval($product['quantity']);
                if(trim($product['type'])==='mix'){
                    $query=SalesMixData::join('cases','cases.CaseId','=','sales_mix.ParentCase')->where(array(
                        array('MixId','=',$product['id']),
                    ))->take(1)->first();

                    if($query){
                        $basePrice=$query->S_price?$query->S_price:$query->Price;
                        // find option
                        if($optionExist=ProductOptionSetData::where('ProductType','mix')->where('ProductId',$product['id'])->take(1)->first()){
                            if($optionDetail=ProductOptionData::where('OptionId',$optionExist->OptionId)->take(1)->first()){
                                if($cartItemCounts>intval($optionDetail->PurchaseAmount)){
                                    $basePrice = $optionDetail->Price;
                                }
                            }
                        }
                        $beforeTotal+=$basePrice*$quantity;

                        array_push($orderSection,array(
                            'name'=>Session::get('Language')==='EN'&&trim($query->MixNameEn)!==''?$query->MixNameEn:$query->MixName,
                            'quantity'=>$product['quantity'],
                            'subtotal'=>number_format($basePrice*$product['quantity'],2)
                        ));
                    }
                }
                elseif(trim($product['type'])==='wine'){
                    $query=WinesData::where(array(
                        array('WineId','=',$product['id']),
                    ))->take(1)->first();

                    if($query){
                        $basePrice=$query->S_price?$query->S_price:$query->Price;
                        // find option
                        if($optionExist=ProductOptionSetData::where('ProductType','wine')->where('ProductId',$product['id'])->take(1)->first()){
                            if($optionDetail=ProductOptionData::where('OptionId',$optionExist->OptionId)->take(1)->first()){
                                if($cartItemCounts>intval($optionDetail->PurchaseAmount)){
                                    $basePrice = $optionDetail->Price;
                                }
                            }
                        }
                        $beforeTotal+=$basePrice*$quantity;

                        array_push($orderSection,array(
                            'name'=>Session::get('Language')==='EN'&&trim($query->NameEn)!==''?$query->NameEn:$query->Name,
                            'quantity'=>$product['quantity'],
                            'subtotal'=>number_format($basePrice*$product['quantity'],2)
                        ));
                    }
                }
                elseif(trim($item['type'])==='gift'){
                    $query = GiftsData::where(array(
                        array('GiftId','=',$product['id']),
                        array('ActDate','<=',date('Y-m-d')),
                        array('EndDate','>',date('Y-m-d')),
                        array('Status','=',1)     
                    ))->take(1)->first();
                    
                    if($query){
                        $basePrice=$query->S_price?$query->S_price:$query->Price;
                        $beforeTotal+=$basePrice*$quantity;
                        array_push($orderSection,array(
                            'name'=>Session::get('Language')==='EN'&&trim($query->NameEn)!==''?$query->NameEn:$query->Name,
                            'quantity'=>$product['quantity'],
                            'subtotal'=>number_format($basePrice*$product['quantity'],2)
                        ));              
                    }   
                }                
            }

            $GST_Price=0;
            $DeliveryCost=100;
            $freight_gstSet=Freight_GSTData::where('CountryId',$shipping_country)->take(1)->first();
            $shippingStore=array();
            if($freight_gstSet){
                if($deliveryMethod=='deliveryHome'){
                    $DeliveryCost=$freight_gstSet->Freight_home;
                }
                else{
                    if($shipping_region!=""&&$freight_gstSet->Freight_store){
                        $storeDeliveryCost=json_decode($freight_gstSet->Freight_store,true);
                        if(isset($storeDeliveryCost[str_replace("store_","",$deliveryMethod)])){
                            $shippingStore=$storeDeliveryCost[str_replace("store_","",$deliveryMethod)];
                            $DeliveryCost=$storeDeliveryCost[str_replace("store_","",$deliveryMethod)][3];
                        }
                    }
                }
            }

            $GST_Price = round( ($beforeTotal + $DeliveryCost) * 3 / 23,2);
            //$afterTotal = $beforeTotal+$GST_Price+$DeliveryCost;
            // removed $GST_Price by Jimmy 2020/5/20
            $afterTotal = $beforeTotal+$DeliveryCost;

            if($request->isMethod('post')){
                $rule_array=array(
                    'BillingAddr'=>'required|array',
                    'BillingAddr.Country'=>'required',
                    'BillingAddr.FirstName'=>'required|max:255',
                    'BillingAddr.MiddleName'=>'nullable|max:255',
                    'BillingAddr.LastName'=>'required|max:255',
                    'BillingAddr.Company'=>'nullable|max:255',
                    'BillingAddr.Address'=>'required|max:255',
                    'BillingAddr.Address2'=>'nullable|max:255',
                    'BillingAddr.City'=>'required|max:255',
                    'BillingAddr.Region'=>'required|max:255',
                    'BillingAddr.PostCode'=>'required|max:30',
                    'BillingAddr.Email'=>'required|email',
                    'BillingAddr.Telephone'=>'required|max:255',

                    'ShippingBookId'=>'nullable|integer',
                    'ShippingOption'=>'nullable|integer',
                    'ShippingAddr'=>'required_with:ShippingOption|array',
                    'ShippingAddr.Country'=>'required_with:ShippingOption',
                    'ShippingAddr.FirstName'=>'required_with:ShippingOption|max:255',
                    'ShippingAddr.MiddleName'=>'nullable|max:255',
                    'ShippingAddr.LastName'=>'required_with:ShippingOption|max:255',
                    'ShippingAddr.Company'=>'nullable|max:255',
                    'ShippingAddr.Address'=>'required_with:ShippingOption|max:255',
                    'ShippingAddr.Address2'=>'nullable|max:255',
                    'ShippingAddr.City'=>'required_with:ShippingOption|max:255',
                    'ShippingAddr.Region'=>'required_with:ShippingOption|max:255',
                    'ShippingAddr.PostCode'=>'required_with:ShippingOption|max:30',
                    'ShippingAddr.Email'=>'required_with:ShippingOption|email|nullable',
                    'ShippingAddr.Telephone'=>'required_with:ShippingOption|max:255',

                    'CreateAccount'=>'nullable|integer|in:1',
                    'MemberPassword'=>'required_with:CreateAccount',
                    'Paymethod'=>'required|in:Card,QRCode_Alipay,QRCode_WechatPay'
                );

                $rule_message=array(
                    'BillingAddr.required'=>Session::get('Language')==='EN'?'Fill the fields of billing address.':'请填写帐单地址',
                    'BillingAddr.Country.required'=>Session::get('Language')==='EN'?'Choose the country.':'请选择国家',
                    'BillingAddr.FirstName.required'=>Session::get('Language')==='EN'?'Fill the first name.':'请填写您的姓名',
                    'BillingAddr.FirstName.max'=>Session::get('Language')==='EN'?'The first name should under 255 characters.':'您的姓名不可超过255个字符',
                    'BillinaAddr.MiddleName.max'=>Session::get('Language')==='EN'?'The middle name should under 255 characters.':'您的中间名不可超过255个字符',
                    'BillingAddr.LastName.required'=>Session::get('Language')==='EN'?'Fill the last name.':'请填写您的姓',
                    'BillinaAddr.LastName.max'=>Session::get('Language')==='EN'?'The last name should under 255 characters.':'您的姓不可超过255个字符',
                    'BillingAddr.Company.max'=>Session::get('Language')==='EN'?'The company should under 255 characters.':'公司名称不可超过255个字符',
                    'BillingAddr.Address.required'=>Session::get('Language')==='EN'?'Fill the street address.':'请填写地址',
                    'BillingAddr.Address.max'=>Session::get('Language')==='EN'?'The street address should under 255 characters.':'地址不可超过255个字符',
                    'BillingAddr.Address2'=>Session::get('Language')==='EN'?'The street address should under 255 characters.':'地址不可超过255个字符',
                    'BillingAddr.City.required'=>Session::get('Language')==='EN'?'Fill the city.':'请填写县市',
                    'BillingAddr.City.max'=>Session::get('Language')==='EN'?'The city should under 255 characters.':'县市内容不可超过255个字符',
                    'BillingAddr.Region.required'=>Session::get('Language')==='EN'?'Fill the region.':'请填写区域',
                    'BillingAddr.Region.max'=>Session::get('Language')==='EN'?'The region should under 255 characters.':'区域内容不可超过255个字符',
                    'BillingAddr.PostCode.required'=>Session::get('Language')==='EN'?'Fill the post code.':'请填写邮递区号',
                    'BillingAddr.PostCode.max'=>Session::get('Language')==='EN'?'The post code should under 30 characters.':'邮递区号不可超过30个字符',
                    'BillingAddr.Email.required'=>Session::get('Language')==='EN'?'Fill the email.':'请填写Email',
                    'BillingAddr.Email.email'=>Session::get('Language')==='EN'?'The format of email is not correct.':'Email格式有误',
                    'BillingAddr.Telephone.required'=>Session::get('Language')==='EN'?'Fill the telephone.':'请填写联络电话',
                    'BillingAddr.Telephone.max'=>Session::get('Language')==='EN'?'The telephone should under 255 characters.':'联络电话内容不可超过255个字符',

                    'ShippingAddr.required'=>Session::get('Language')==='EN'?'Fill the fields of shipping address.':'请填写配送地址',
                    'ShippingAddr.Country.required_with'=>Session::get('Language')==='EN'?'Choose the country.':'请选择国家',
                    'ShippingAddr.FirstName.required_with'=>Session::get('Language')==='EN'?'Fill the first name.':'请填写您的姓名',
                    'ShippingAddr.FirstName.max'=>Session::get('Language')==='EN'?'The first name should under 255 characters.':'您的姓名不可超过255个字符',
                    'BillinaAddr.MiddleName.max'=>Session::get('Language')==='EN'?'The middle name should under 255 characters.':'您的中间名不可超过255个字符',
                    'ShippingAddr.LastName.required_with'=>Session::get('Language')==='EN'?'Fill the last name.':'请填写您的姓',
                    'BillinaAddr.LastName.max'=>Session::get('Language')==='EN'?'The last name should under 255 characters.':'您的姓不可超过255个字符',
                    'ShippingAddr.Company.max'=>Session::get('Language')==='EN'?'The company should under 255 characters.':'您的公司名不可超过255个字符',
                    'ShippingAddr.Address.required_with'=>Session::get('Language')==='EN'?'Fill the street address.':'请填写配送地址',
                    'ShippingAddr.Address.max'=>Session::get('Language')==='EN'?'The street address should under 255 characters.':'地址不可超过255个字符',
                    'ShippingAddr.Address2'=>Session::get('Language')==='EN'?'The street address should under 255 characters.':'地址不可超过255个字符',
                    'ShippingAddr.City.required_with'=>Session::get('Language')==='EN'?'Fill the city.':'请填写配送县市',
                    'ShippingAddr.City.max'=>Session::get('Language')==='EN'?'The city should under 255 characters.':'配送县市内容不可超过255个字符',
                    'ShippingAddr.Region.required_with'=>Session::get('Language')==='EN'?'Fill the region.':'请填写配送区域',
                    'ShippingAddr.Region.max'=>Session::get('Language')==='EN'?'The region should under 255 characters.':'配送区域内容不可超过255个字符',
                    'ShippingAddr.PostCode.required_with'=>Session::get('Language')==='EN'?'Fill the post code.':'请填写配送地址邮递区号',
                    'ShippingAddr.PostCode.max'=>Session::get('Language')==='EN'?'The post code should under 30 characters.':'邮递区号内容不可超过255个字符',
                    'ShippingAddr.Email.required_with'=>Session::get('Language')==='EN'?'Fill the email.':'请填写Email',
                    'ShippingAddr.Email.email'=>Session::get('Language')==='EN'?'The format of email is not correct.':'Email格式錯誤',
                    'ShippingAddr.Telephone.required_with'=>Session::get('Language')==='EN'?'Fill the telephone.':'请填写联络电话',
                    'ShippingAddr.Telephone.max'=>Session::get('Language')==='EN'?'The telephone should under 255 characters.':'联络电话内容不可超过255个字符', 

                    'MemberPassword.required_with'=>Session::get('Language')==='EN'?'Please fill the password to become member.':'请填写密码以成为会员'
                );
                $build_validator=Validator::make($request->all(),$rule_array,$rule_message);
                if($build_validator->fails()){
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }                

                $BillingAddr=request('BillingAddr');
                $B_Country=trim($BillingAddr['Country']);
                $B_FirstName=trim($BillingAddr['FirstName']);
                $B_MiddleName=trim($BillingAddr['MiddleName']);
                $B_LastName=trim($BillingAddr['LastName']);
                $B_Company=trim($BillingAddr['Company']);
                $B_Address=trim($BillingAddr['Address']);
                $B_Address2="";
                if(isset($BillingAddr['Address2']))
                    $B_Address2=trim($BillingAddr['Address2']);
                $B_City=trim($BillingAddr['City']);
                $B_Region=trim($BillingAddr['Region']);
                $B_PostCode=trim($BillingAddr['PostCode']);
                $B_Email=trim($BillingAddr['Email']);
                $B_Telephone=trim($BillingAddr['Telephone']);

                $ShippingBookId=intval(request('ShippingBookId'));
                $ShippingOption=intval(request('ShippingOption'));

                $ShippingAddr=request('ShippingAddr');
                $S_Country=$B_Country;
                $S_FirstName=$B_FirstName;
                $S_MiddleName=$B_MiddleName;
                $S_LastName=$B_LastName;
                $S_Company=$B_Company;
                $S_Address=$B_Address;
                $S_Address2=$B_Address2;
                $S_City=$B_City;
                $S_Region=$B_Region;
                $S_PostCode=$B_PostCode;
                $S_Email=$B_Email;
                $S_Telephone=$B_Telephone;

                $shippginToWhere="HOME";
                if($ShippingOption===1){
                    // get shipping address from input
                    $S_Country=trim($ShippingAddr['Country']);
                    $S_FirstName=trim($ShippingAddr['FirstName']);
                    $S_MiddleName=trim($ShippingAddr['MiddleName']);
                    $S_LastName=trim($ShippingAddr['LastName']);
                    $S_Company=trim($ShippingAddr['Company']);
                    $S_Address=trim($ShippingAddr['Address']);
                    $S_Address2="";
                    if(isset($ShippingAddr['Address2']))
                        $S_Address2=trim($ShippingAddr['Address2']);
                    $S_City=trim($ShippingAddr['City']);
                    $S_Region=trim($ShippingAddr['Region']);
                    $S_PostCode=trim($ShippingAddr['PostCode']);
                    $S_Email=trim($ShippingAddr['Email']);
                    $S_Telephone=trim($ShippingAddr['Telephone']);        
                }
                else{
                    if(trim($deliveryMethod)!=='deliveryHome'&&count($shippingStore)>0){
                        $S_City=$shippingStore[0];
                        $S_Region="";
                        $S_Company=$shippingStore[1];
                        $S_Address=$shippingStore[2];
                        $S_Address2="";
                        $S_PostCode="";
                        $S_Country=$shipping_country;
                        $shippginToWhere="STORE";
                    }
                    else{
                        if($ShippingBookId!==0){
                            // get shipping address from book
                            $addressInfo=AddressBookData::where('MemberId',Session::get('MemberId'))->where('AddressId',$ShippingBookId)->where('AddrType',2)->take(1)->first();
                            if($addressInfo){
                                $S_FirstName=$addressInfo->Contact_first;
                                $S_MiddleName=$addressInfo->Contact_middle;
                                $S_LastName=$addressInfo->Contact_last;
                                $S_Company=$addressInfo->Company;
                                $S_Telephone=$addressInfo->Telephone;
                                $S_Address=$addressInfo->StreetAddr;
                                $S_Address2=$addressInfo->StreetAddr2;
                                $S_City=$addressInfo->City;
                                $S_Region=$addressInfo->Region;
                                $S_PostCode=$addressInfo->PostCode;
                                $S_Country=$addressInfo->Country;
                            }
                        }
                    }
                }
                $Notes=request('Notes');

                try{
                    DB::connection()->getPdo()->beginTransaction();
                    $prefixOrderId=date('Ymd');
                    $newOrderId=0;
                    $last_existOrder=OrdersData::where('OrderId','like',"$prefixOrderId%")->orderBy('created_at','DESC')->take(1)->first();
                    if($last_existOrder){
                        $appendId=substr($last_existOrder->OrderId,8);
                        $newAppendId=intval($appendId);
                        $newAppendId++;
                        $newAppendId=sprintf("%04d",$newAppendId);
                        $newOrderId=$prefixOrderId.$newAppendId;
                    }
                    else{
                        $newOrderId=$prefixOrderId."0001";
                    }

                    if(intval($newOrderId)===0){
                        DB::connection()->getPdo()->rollBack();
                        Session::flash('system_message_flag',true);
                        Session::flash('system_message_status','warning');
                        Session::flash('system_message','Oops...Try again.');
                        return redirect()->back()->withInput()->withErrors($build_validator->errors());                        
                    }

                    $MemberDiscount = 0;
                    if(Session::get('MemberId')){
                        $MemberDiscount = env('MemberDiscount',90);   
                        $afterTotal = $afterTotal*($MemberDiscount/100);
                    }

                    // INSERT into orders data
                    OrdersData::insert(array(
                        'OrderId'=>$newOrderId,
                        'MemberId'=>Session::get('MemberId')?Session::get('MemberId'):'',
                        'Subtotal'=>$beforeTotal,
                        'GST'=>$GST_Price,
                        'DeliveryCost'=>$DeliveryCost,
                        'Total'=>(float)$afterTotal,
                        'MemberDiscount'=>$MemberDiscount,
                        'Notes'=>$Notes,
                        'Paymethod'=>trim(request('Paymethod')),
                        'Status'=>-1,
                        'created_at'=>date('Y-m-d H:i:s'),
                        'updated_at'=>date('Y-m-d H:i:s')
                    ));

                    // INSERT into order detail data
                    $apiPaymentDesc="";
                    foreach($cartProducts as $product){
                        $quantity=intval($product['quantity']);
                        if(trim($product['type'])==='mix'){
                            $query=SalesMixData::join('cases','cases.CaseId','=','sales_mix.ParentCase')->where(array(
                                array('MixId','=',$product['id']),
                            ))->take(1)->first();

                            if($query){
                                $apiPaymentDesc.=$query->MixName;
                                $apiPaymentDesc.="(x".$quantity.") | ";
                                $insertPrice=$query->S_price?$query->S_price:$query->Price;

                                // find option
                                if($optionExist=ProductOptionSetData::where('ProductType','mix')->where('ProductId',$product['id'])->take(1)->first()){
                                    if($optionDetail=ProductOptionData::where('OptionId',$optionExist->OptionId)->take(1)->first()){
                                        if($cartItemCounts>intval($optionDetail->PurchaseAmount)){
                                            $insertPrice = $optionDetail->Price;
                                        }
                                    }
                                }

                                OrderDetailData::insert(array(
                                    'OrderId'=>$newOrderId,
                                    'ProductType'=>'mix',
                                    'Product'=>$product['id'],
                                    'Price'=>$insertPrice,
                                    'Quantity'=>$quantity,
                                    'Subtotal'=>number_format($insertPrice*$quantity,2)
                                ));
                            }
                        }
                        elseif(trim($product['type'])==='wine'){
                            $query=WinesData::where(array(
                                array('WineId','=',$product['id']),
                            ))->take(1)->first();

                            if($query){
                                $apiPaymentDesc.=$query->Name;
                                $apiPaymentDesc.="(x".$quantity.") | ";                               
                                $insertPrice=$query->S_price?$query->S_price:$query->Price;

                                // find option
                                if($optionExist=ProductOptionSetData::where('ProductType','wine')->where('ProductId',$product['id'])->take(1)->first()){
                                    if($optionDetail=ProductOptionData::where('OptionId',$optionExist->OptionId)->take(1)->first()){
                                        if($cartItemCounts>intval($optionDetail->PurchaseAmount)){
                                            $insertPrice = $optionDetail->Price;
                                        }
                                    }
                                }

                                OrderDetailData::insert(array(
                                    'OrderId'=>$newOrderId,
                                    'ProductType'=>'wine',
                                    'Product'=>$product['id'],
                                    'Price'=>$insertPrice,
                                    'Quantity'=>$quantity,
                                    'Subtotal'=>number_format($insertPrice*$quantity,2)
                                ));
                            }
                        }
                        elseif(trim($product['type'])==='gift'){
                            $query = GiftsData::where(array(
                                array('GiftId','=',$product['id']),
                                array('ActDate','<=',date('Y-m-d')),
                                array('EndDate','>',date('Y-m-d')),
                                array('Status','=',1)     
                            ))->take(1)->first();
                            
                            if($query){
                                $apiPaymentDesc.=$query->Name;
                                $apiPaymentDesc.="(x".$quantity.") | ";                               
                                $insertPrice=$query->S_price?$query->S_price:$query->Price;                                

                                OrderDetailData::insert(array(
                                    'OrderId'=>$newOrderId,
                                    'ProductType'=>'gift',
                                    'Product'=>$product['id'],
                                    'Price'=>$insertPrice,
                                    'Quantity'=>$quantity,
                                    'Subtotal'=>number_format($insertPrice*$quantity,2)
                                ));              
                            }   
                        }
                    }

                    if(trim($apiPaymentDesc)!==""){
                        $apiPaymentDesc=mb_substr($apiPaymentDesc,0,mb_strlen($apiPaymentDesc)-2);
                    }
                    if(mb_strlen($apiPaymentDesc)>=256){
                        $apiPaymentDesc=mb_substr($apiPaymentDesc,0,mb_strlen($apiPaymentDesc)-3);
                        $apiPaymentDesc.="...";
                    }

                    // INSERT into billing data
                    BillingData::insert(array(
                        'OrderId'=>$newOrderId,
                        'Country'=>$B_Country,
                        'FirstName'=>$B_FirstName,
                        'MiddleName'=>$B_MiddleName,
                        'LastName'=>$B_LastName,
                        'Company'=>$B_Company,
                        'Address'=>$B_Address,
                        'Address2'=>$B_Address2,
                        'City'=>$B_City,
                        'Region'=>$B_Region,
                        'PostCode'=>$B_PostCode,
                        'Email'=>$B_Email,
                        'Telephone'=>$B_Telephone,
                        'created_at'=>date('Y-m-d H:i:s'),
                        'updated_at'=>date('Y-m-d H:i:s')
                    ));

                    // INSERT into shipping data
                    ShippingData::insert(array(
                        'OrderId'=>$newOrderId,
                        'Type'=>$shippginToWhere,
                        'Country'=>$S_Country,
                        'FirstName'=>$S_FirstName,
                        'MiddleName'=>$S_MiddleName,
                        'LastName'=>$S_LastName,
                        'Company'=>$S_Company,
                        'Address'=>$S_Address,
                        'Address2'=>$S_Address2,
                        'City'=>$S_City,
                        'Region'=>$S_Region,
                        'PostCode'=>$S_PostCode,
                        'Email'=>$S_Email,
                        'Telephone'=>$S_Telephone,
                        'created_at'=>date('Y-m-d H:i:s'),
                        'updated_at'=>date('Y-m-d H:i:s')
                    ));
                    
                    // Payment API
                    if(trim(request('Paymethod')==='Card')){
                        $GenerateRequestXML="
                            <GenerateRequest>
                                <PxPayUserId>".env('ePayments_UserID','WineCaveNZ')."</PxPayUserId>
                                <PxPayKey>".env('ePayments_MerchantKEY','7c7c573f202de105f9928d18541fcb7aa0464a1852445e615353c6672d0e1e1f')."</PxPayKey>
                                <TxnType>Purchase</TxnType>
                                <AmountInput>".number_format((float)$afterTotal, 2, '.', '')."</AmountInput>
                                <CurrencyInput>NZD</CurrencyInput>
                                <MerchantReference>Payment about Wine Cave</MerchantReference>
                                <TxnData1>".$B_FirstName." ".$B_LastName."</TxnData1>
                                <TxnData2>".$B_Telephone."</TxnData2>
                                <TxnData3>".$B_Email."</TxnData3>
                                <ForcePaymentMethod>".trim(request('Paymethod'))."</ForcePaymentMethod>
                                <EmailAddress>".$B_Email."</EmailAddress>
                                <TxnId>".$newOrderId."</TxnId>
                                <UrlSuccess>".url('/')."/pay/success</UrlSuccess>
                                <UrlFail>".url('/')."/pay/fail</UrlFail>
                                <UrlCallback>".url('/')."/pay/notify_url</UrlCallback>
                            </GenerateRequest>                        
                        ";

                        $URL="https://sec.windcave.com/pxaccess/pxpay.aspx";
                        $ch=curl_init($URL);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
                        curl_setopt($ch, CURLOPT_POSTFIELDS, "$GenerateRequestXML");
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        $output = curl_exec($ch);
                        curl_close($ch);

                        $xml=simplexml_load_string($output);
                        if($xml===false){
                            DB::connection()->getPdo()->rollBack();
                            Session::flash('system_message_flag',true);
                            Session::flash('system_message_status','warning');
                            Session::flash('system_message','Oops...Try again.');
                            return redirect()->back()->withInput()->withErrors($build_validator->errors());
                        }
                        else{
                            if(isset($xml->URI)){
                                DB::connection()->getPdo()->commit();
                                $this->SendMail($newOrderId);

                                Session::forget('product');
                                Session::forget('shipping_country');
                                Session::forget('shipping_region');
                                Session::forget('deliveryMethod');

                                return Redirect::to($xml->URI);
                            }
                            else{
                                DB::connection()->getPdo()->rollBack();
                                Session::flash('system_message_flag',true);
                                Session::flash('system_message_status','warning');
                                if(isset($xml->ResponseText)&&trim($xml->ResponseText)==='TxnId/TxnRef duplicate'){
                                    if(Session::get('Language')==='EN'){
                                        Session::flash('system_message','Order number duplicates');
                                    }
                                    else{
                                        Session::flash('system_message','訂單編號已重複');
                                    }
                                }
                                else{                            
                                    Session::flash('system_message','Oops...Try again.');
                                }
                                return redirect()->back()->withInput()->withErrors($build_validator->errors());
                            }
                        }
                    }
                    elseif(trim(request('Paymethod')==='QRCode_Alipay') || trim(request('Paymethod'))==='QRCode_WechatPay'){
                        $store_id = env('Attractpay_store',216);
                        $payment_channel = str_replace("QRCode_","",trim(request('Paymethod')));
                        $merchant_trade_no = $newOrderId;
                        $total_amount = (String)number_format((float)$afterTotal, 2, '.', '');
                        $create_time = date('Y-m-d G:i:s');
                        $currency = "NZD";
                        $notify_url = url('/')."/pay/notify_url";
                        $extra_param = "Payment in ShopWineCave";

                        $presign_String = "currency={$currency}&extra_param={$extra_param}&merchant_trade_no={$merchant_trade_no}&notify_url={$notify_url}&payment_channel={$payment_channel}&store_id={$store_id}&total_amount={$total_amount}&authentication_code=";
                        $presign_String.=env("Attractpay_auth_code","e053c97184183c66e74dc0e2f640bec9");
                    
                        $sign = md5($presign_String);

                        $url = "http://pay.attractpay.co.nz/online/payment";
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(
                            array(
                                "store_id"=>$store_id,
                                "payment_channel"=>$payment_channel,
                                "merchant_trade_no"=>$merchant_trade_no,
                                "total_amount"=>$total_amount,
                                // "create_time"=>$create_time,
                                "currency"=>$currency,
                                "notify_url"=>$notify_url,
                                "extra_param"=>$extra_param,
                                "sign"=>$sign
                            )
                        ));
                        $output = curl_exec($ch); 
                        curl_close($ch);
                        $output = json_decode($output,true);
                        if(isset($output['code'])){
                            if(intval($output['code'])===206){
                                OrdersData::where('OrderId',$newOrderId)->update(array(
                                    'QRCode'=>$output['dataObject']['qr_code'],
                                    'QRCodePic'=>$output['dataObject']['pic'],
                                    'PaymentTransNo'=>$output['dataObject']['trans_no']
                                ));
                                DB::connection()->getPdo()->commit();
                                $this->SendMail($newOrderId);
                                $data['QRCode']=$output['dataObject']['qr_code'];
                                $data['QRCodePic']=$output['dataObject']['pic'];
                                Session::put('attractpay_QRCode',$output['dataObject']['qr_code']);
                                Session::put('attractpay_QRCodePic',$output['dataObject']['pic']);

                                Session::forget('product');
                                Session::forget('shipping_country');
                                Session::forget('shipping_region');
                                Session::forget('deliveryMethod');
                                return redirect('/pay/QRCode_display');
                            }
                            else{
                                DB::connection()->getPdo()->rollBack();
                                Session::flash('system_message_flag',true);
                                Session::flash('system_message_status','error');
                                if(Session::get('Language')==='EN'){
                                    Session::flash('system_message','Something failed in payment...');
                                }
                                else{
                                    Session::flash('system_message','处理付款时发生错误');
                                }
                                return redirect()->back()->withInput()->withErrors($build_validator->errors()); 
                            }
                        }
                        else{
                            DB::connection()->getPdo()->rollBack();
                            Session::flash('system_message_flag',true);
                            Session::flash('system_message_status','error');
                            if(Session::get('Language')==='EN'){
                                Session::flash('system_message','Something failed in payment...');
                            }
                            else{
                                Session::flash('system_message','处理付款时发生错误');
                            }
                            return redirect()->back()->withInput()->withErrors($build_validator->errors());                             
                        }
                    }
                }
                catch (\PDOException $e) {
                    DB::connection()->getPdo()->rollBack();
                    // print_R($e->getMessage());exit();
                    Session::flash('system_message_flag',true);
                    Session::flash('system_message_status','warning');
                    Session::flash('system_message','Oops...Try again.');
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }
            }
            else{
                if(Session::get('MemberId')){
                    $MemberDiscount = env('MemberDiscount',90);   
                    $afterTotal = $afterTotal*($MemberDiscount/100);
                }
                $afterTotal = number_format((float)$afterTotal,2, '.','');
                if(Session::get('MemberId')){
                    if(Session::get('Language')==='EN'){
                        $afterTotal.=" <text style='color:#FCAB64'>(Discount)</text>";
                    }
                    else{
                        $afterTotal.=" <text style='color:#FCAB64'>(会员折扣)</text>";
                    }
                }

                $data['orderSection']=$orderSection;
                $data['prices']=array(
                    'beforeTotal'=>number_format($beforeTotal),
                    'GST_Price'=>$GST_Price,
                    'DeliveryCost'=>$DeliveryCost,
                    'afterTotal'=>$afterTotal
                );

                $data['shipping_country']=DB::table('countries')->where('CountryId',$shipping_country)->select('Country')->take(1)->first();
                $data['shippingStore']=$shippingStore;
                $data['deliveryMethod']=$deliveryMethod;
                $data['htmlTitle']=Session::get('Language')==='EN'?'Checkout':'结帐';

                $defaultBilling=AddressBookData::where('MemberId',Session::get('MemberId'))->where('AddrType',1)->where('DefaultChoose',1)->take(1)->first();
                $data['defaultBilling']=$defaultBilling;

                $shippingBook=AddressBookData::where('MemberId',Session::get('MemberId'))->where('AddrType',2)->orderBy('DefaultChoose','DESC')->get();
                $data['shippingBook']=$shippingBook;

                $defaultShipping=AddressBookData::where('MemberId',Session::get('MemberId'))->where('AddrType',2)->where('DefaultChoose',1)->take(1)->first();
                $data['defaultShipping']=$defaultShipping;

                $currentMemInfo=MembersData::where('MemberId',Session::get('MemberId'))->take(1)->first();
                $data['currentMemInfo']=$currentMemInfo;
                // echo '<pre>';
                // print_R($data);
                // echo '</pre>';
                return view('frontend.checkout',$data);
            }
        }
        return redirect('/');
    }

    private function SendMail($OrderId){
        $orderInfo = OrdersData::join('order_shipping as os','orders.OrderId','=','os.OrderId')->join('order_billing as ob','orders.OrderId','=','ob.OrderId')->leftJoin('members as m','orders.MemberId','=','m.MemberId')->where('orders.OrderId','=',$OrderId)->select(
            array(
                'orders.*',
                'os.Type','os.Country','os.FirstName as osFirst','os.MiddleName as osMiddle','os.LastName as osLast','os.Company as osCompany','os.Address as osAddress','os.Address2 as osAddress2','os.City as osCity','os.Region as osRegion','os.PostCode as osPostCode','os.Email as osEmail','os.Telephone as osTelephone','os.Country as osCountry',
                'ob.Country','ob.FirstName as obFirst','ob.MiddleName as obMiddle','ob.LastName as obLast','ob.Company as obCompany','ob.Address as obAddress','ob.Address2 as obAddress2','ob.City as obCity','ob.Region as obRegion','ob.PostCode as obPostCode','ob.Email as obEmail','ob.Telephone as obTelephone','ob.Country as obCountry',
                'm.*'
            )
        )->take(1)->first();

        if($orderInfo){
            $orderDetails = array();
            $orderDetailRows = OrderDetailData::where('OrderId','=',$OrderId)->get();
            foreach($orderDetailRows as $row){
                if($row->ProductType==='wine'){
                    if($wine = WinesData::where('WineId',$row->Product)->take(1)->first()){
                        array_push($orderDetails,array(
                            'Product'=>$wine->Name,
                            'Type'=>'wine',
                            'Price'=>$row->Price,
                            'Quantity'=>$row->Quantity,
                            'Subtotal'=>$row->Subtotal
                        ));
                    }
                }
                elseif($row->ProductType==='case'){
                    if($case = SalesMixData::where('MixId',$row->Product)->take(1)->first()){
                        array_push($orderDetails,array(
                            'Product'=>$case->MixName,
                            'Type'=>'case',
                            'Price'=>$row->Price,
                            'Quantity'=>$row->Quantity,
                            'Subtotal'=>$row->Subtotal                      
                        ));
                    }
                }
                elseif($row->ProductType==='gift'){
                    if($gift = GiftsData::where(array(
                        array('GiftId','=',$row->Product),
                    ))->take(1)->first()){
                        array_push($orderDetails,array(
                            'Product'=>$gift->Name,
                            'Type'=>'gift',
                            'Price'=>$row->Price,
                            'Quantity'=>$row->Quantity,
                            'Subtotal'=>$row->Subtotal
                        ));
                    }
                }
            }

            // send email
            // Instantiation and passing `true` enables exceptions
            $mail_instance=new PHPMailer(true);

            try{       
                //Server settings
                $mail_instance->SMTPDebug=0;
                $mail_instance->isSMTP();
                $mail_instance->Host='mail.shopwinecave.nz';
                $mail_instance->SMTPAuth=true;
                $mail_instance->Username='service@shopwinecave.nz';
                $mail_instance->Password='A1qazxsw2!';
                $mail_instance->SMTPSecure='ssl';
                $mail_instance->Port=465;

                // Content
                $mail_instance->isHTML(true);
                $mail_instance->Subject='Order Confirmed: #'.$OrderId;
                $htmlBody = '<p style="font-family:Arial">Thank you for your order at The Wine Cave NZ. Once your package ships we will send you a notification email. Your order confirmation is below.</p>';
                $htmlBody .= '<br />';
                $htmlBody .= "<p style='font-family:Arial'>Order Number: #{$OrderId} (";
                $htmlBody .= $orderInfo->created_at;
                $htmlBody .= ")";

                $htmlBody .= '<table style="width:100%">';
                $htmlBody .= '<tr>';
                $htmlBody .= '<th style="font-family:Arial;padding:5px 0" width="50%" align="left">BILLED TO:</th>';
                $htmlBody .= '<th style="font-family:Arial;padding:5px 0" width="50%" align="left">SHIPPING TO:</th>';
                $htmlBody .= '</tr>';
                $htmlBody .= '<tr>';
                $htmlBody .= "<td style='font-family:Arial;padding:5px 0'>{$orderInfo->obFirst} {$orderInfo->obMiddle} {$orderInfo->obLast}</td>";
                $htmlBody .= "<td style='font-family:Arial;padding:5px 0'>{$orderInfo->osFirst} {$orderInfo->osMiddle} {$orderInfo->osLast}</td>";
                $htmlBody .= "</tr>";
                $htmlBody .= "<tr>";
                $htmlBody .= "<td style='font-family:Arial;padding:5px 0'>{$orderInfo->obAddress} {$orderInfo->obAddress2}</td>";
                $htmlBody .= "<td style='font-family:Arial;padding:5px 0'>{$orderInfo->osAddress} {$orderInfo->osAddress2}</td>";
                $htmlBody .= "</tr>";
                $htmlBody .= "<tr>";
                if($orderInfo->obCompany){
                    $htmlBody .= "<td style='font-family:Arial;padding:5px 0'>{$orderInfo->obCompany}</td>";
                }
                else{
                    $htmlBody .= "<td></td>";
                }
                if($orderInfo->osCompany){
                    $htmlBody .= "<td style='font-family:Arial;padding:5px 0'>{$orderInfo->osCompany}</td>";
                }
                else{
                    $htmlBody .= "<td></td>";
                }
                $htmlBody .= "</tr>";

                $htmlBody .= "<tr>";
                $htmlBody .= "<td style='font-family:Arial;padding:5px 0'>{$orderInfo->obRegion}, {$orderInfo->obCity}, {$orderInfo->obPostCode}</td>";
                $htmlBody .= "<td style='font-family:Arial;padding:5px 0'>{$orderInfo->osRegion}, {$orderInfo->osCity}, {$orderInfo->osPostCode}</td>";
                $htmlBody .= "</tr>";

                $countries = Config::get('app.countries');
                if( isset($countries[$orderInfo->obCountry]) || isset($countries[$orderInfo->osCountry])){
                    $htmlBody .= "<tr>";
                    if(isset($countries[$orderInfo->obCountry])){
                        $htmlBody .= "<td style='font-family:Arial;padding:5px 0'>";
                        $htmlBody .= $countries[$orderInfo->obCountry];
                        $htmlBody .= "</td>";
                    }
                    else{
                        $htmlBody .= "<td></td>";
                    }
                    if(isset($countries[$orderInfo->osCountry])){
                        $htmlBody .= "<td style='font-family:Arial;padding:5px 0'>";
                        $htmlBody .= $countries[$orderInfo->osCountry];
                        $htmlBody .= "</td>";
                    }
                    else{
                        $htmlBody .= "<td></td>";
                    }
                    $htmlBody .= "</tr>";
                }

                $htmlBody .= "<tr>";
                $htmlBody .= "<td style='font-family:Arial;padding:5px 0'>{$orderInfo->obEmail}</td>";
                $htmlBody .= "<td style='font-family:Arial;padding:5px 0'>{$orderInfo->osEmail}</td>";
                $htmlBody .= "</tr>";
                $htmlBody .= "</table>";

                $htmlBody .= '<h2 style="font-family:Arial;margin-top:30px;margin-bottom:30px">Order Sumamry</h2>';
                $htmlBody .= '<table style="width:100%">';
                $htmlBody .= '<tr>';
                $htmlBody .= '<th style="font-family:Arial;padding:5px 0" width="25%" align="left">ITEM</th>';
                $htmlBody .= '<th style="font-family:Arial;padding:5px 0" width="25%" align="left">QTY</th>';
                $htmlBody .= '<th style="font-family:Arial;padding:5px 0" width="25%" align="left">UNIT PRICE</th>';
                $htmlBody .= '<th style="font-family:Arial;padding:5px 0" width="25%" align="left">SUBTOTAL</th>';                
                $htmlBody .= '</tr>';

                foreach($orderDetails as $product){
                    $htmlBody .= "<tr>";

                    $htmlBody .= '<td style="font-family:Arial;padding:5px 0">';
                    $htmlBody .= $product['Product'];
                    $htmlBody .= '</td>';

                    $htmlBody .= '<td style="font-family:Arial;padding:5px 0">';
                    $htmlBody .= $product['Quantity'];
                    $htmlBody .= '</td>';

                    $htmlBody .= '<td style="font-family:Arial;padding:5px 0">';
                    $htmlBody .= $product['Price'];
                    $htmlBody .= '</td>';

                    $htmlBody .= '<td style="font-family:Arial;padding:5px 0">';
                    $htmlBody .= $product['Subtotal'];
                    $htmlBody .= '</td>';
                    $htmlBody .= '</tr>';
                }
                $htmlBody .= "</table>";
                $htmlBody .= "<hr />";
                
                $htmlBody .= '<div style="overflow:hidden">';

                $htmlBody .= '<div style="width:50%;float:left;height:1px"></div>';
                $htmlBody .= '<div style="width:50%;float:left">';

                $htmlBody .= '<div style="overflow:hidden">';
                $htmlBody .= '<p style="width:50%;float:left;font-family:Arial">Item Subtotal</p>';
                $htmlBody .= '<p style="width:50%;float:left;font-family:Arial">';
                $htmlBody .= $orderInfo->Subtotal;
                $htmlBody .= '</p>';
                $htmlBody .= "</div>";

                $htmlBody .= '<div style="overflow:hidden">';
                $htmlBody .= '<p style="width:50%;float:left;font-family:Arial">Shipping & Handling</p>';
                $htmlBody .= '<p style="width:50%;float:left;font-family:Arial">';
                $htmlBody .= $orderInfo->DeliveryCost;
                $htmlBody .= '</p>';
                $htmlBody .= "</div>";

                $htmlBody .= '<div style="overflow:hidden">';
                $htmlBody .= '<p style="width:50%;float:left;font-family:Arial">Sales Tax</p>';
                $htmlBody .= '<p style="width:50%;float:left;font-family:Arial">';
                $htmlBody .= $orderInfo->GST;
                $htmlBody .= '</p>';
                $htmlBody .= "</div>";

                $htmlBody .= '<div style="overflow:hidden">';
                $htmlBody .= '<p style="width:50%;float:left;font-family:Arial;font-weight:bold">TOTAL</p>';
                $htmlBody .= '<p style="width:50%;float:left;font-family:Arial;font-weight:bold">';
                $htmlBody .= $orderInfo->Total;
                $htmlBody .= '</p>';
                $htmlBody .= "</div>";

                $htmlBody .= '</div>';
                $htmlBody .= '</div>';

                $htmlBody .= '<a href="https://www.winecave.nz" style="font-family:Airal;color:black;text-decoration:none">https://www.winecave.nz</a>';
                $htmlBody .= '<p style="font-family:Arial">The Wine Cave NZ ● 121269252 ● 104 Carlton Gore Road, Newmarket, Aucklang 1023, New Zealand</p>';


                $mail_instance->Body=$htmlBody;
                $mail_instance->CharSet='UTF-8';

                $mail_instance->setFrom('service@shopwinecave.nz','Wine Cave');
                $mail_instance->addAddress('accounts@winecave.nz','Mail about Order #'.$OrderId);
                $mail_instance->addAddress('admin@winecave.nz','Mail about Order #'.$OrderId);
                $mail_instance->addAddress('marshall775042@gmail.com','Mail about Order #'.$OrderId);
                $mail_instance->addAddress('wishs900g3@gmail.com','Mail about Order #'.$OrderId);
                $mail_instance->addAddress($orderInfo->obEmail,'Mail about Order #'.$OrderId);
                $mail_instance->send();
            }
            catch (\Exception $e){
                echo 'error: ';
                print_R($e->getMessage());exit();
            }
        }
    }
}