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

use Session;
use Validator;
use Redirect;
use Widget_Helper;

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
            else{
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

                            $cartItem=array(
                                'id'=>$item['id'],
                                'image'=>$image,
                                'quantity'=>$item['quantity'],
                                'price'=>$query->S_price?$query->S_price:$query->Price,
                                'name'=>$query->MixName
                            );
                        }
                    }
                    else{
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
                                    $image=asset("images/wine_1.png");
                                }
                            }
                            else{
                                $image=asset("images/wine_1.png");
                            }

                            $cartItem=array(
                                'id'=>$item['id'],
                                'image'=>$image,
                                'quantity'=>$item['quantity'],
                                'price'=>$query->S_price?$query->S_price:$query->Price,
                                'name'=>$query->Name
                            );
                        }
                    }

                    if($query){
                        array_push($cart['items'],$cartItem);
                        $cart['subtotal']+=$cartItem['price']*$item['quantity'];
                        $cart['itemCounts']+=$item['quantity'];
                    }
                }

                $cart['subtotal']=number_format($cart['subtotal']);
                return json_encode(array(
                    'status'=>'success',
                    'cart'=>$cart
                ));
            }
            else{
                return json_encode(array(
                    'status'=>'warning',
                    'msg'=>'Cannot find product information'
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
                $subtotal=0;
                $cartItemsCount=0;
                foreach(Session::get("product",array()) as $product){
                    $cartItemsCount+=$product['quantity'];
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
                            $basePrice=$query->S_price?$query->S_price:$query->Price;
                            $subtotal+=$basePrice*$product['quantity'];
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
                            $basePrice=$query->S_price?$query->S_price:$query->Price;
                            $subtotal+=$basePrice*$product['quantity'];
                        }
                    }
                }

                return json_encode(array(
                    'status'=>'success',
                    'subtotal'=>number_format($subtotal),
                    'cartItemsCount'=>$cartItemsCount
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
        foreach(Session::get('product',array()) as $item){
            $cartItem=array();
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
                ))->orderBy('cases.OrderNumber','ASC')->orderBy('sales_mix.OrderNumber','ASC')->take(1)->first();

                if($query){
                    $cartItem=array(
                        'type'=>'mix',
                        'id'=>$query->MixId,
                        'image'=>$query->Image,
                        'name'=>$query->MixName,
                        'price'=>$query->S_price?$query->S_price:$query->Price,
                        'qty'=>$quantity,
                        'contents'=>array()
                    );

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
                    $cartItem=array(
                        'type'=>'wine',
                        'id'=>$query->WineId,
                        'image'=>$query->Image,
                        'name'=>$query->Name,
                        'price'=>$query->S_price?$query->S_price:$query->Price,
                        'qty'=>$quantity
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
        $data['total']=$total;
        $data['cartItems']=$cartItems;
        return view('frontend.cart',$data);
    }

    public function deleteItem($itemId){
        if(Session::get('product.'.$itemId)){
            Session::forget('product.'.$itemId);
        }
        return redirect('/shopping/cart');
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
                }
            }
        }
        return redirect('/shopping/cart');
    }

    public function estimateUpdatePost(Request $request){
        // Array ( [country] => NZ [region] => )
        $shipping_country=trim(request('country'));
        $shipping_region=trim(request('region'));

        $GST=10;
        $Freight=array(
            'store'=>array(),
            'home'=>100
        );
        $freight_gstSet=Freight_GSTData::where('CountryId',$shipping_country)->take(1)->first();
        if($freight_gstSet){
            $GST=$freight_gstSet->GST;
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
                    $beforeTotal+=$basePrice*$quantity;
                }
            }
        }

        $GST_price=round($beforeTotal*($GST/100),2);
        $Freight_price=round($Freight['home'],2);
        $afterTotal=$beforeTotal+$GST_price+$Freight_price;

        Session::put('shipping_country',$shipping_country);
        Session::put('shipping_region',$shipping_region);
        Session::put('deliveryMethod','deliveryHome');
        return json_encode(array(
            'GST_percent'=>round($GST,2),
            'GST_price'=>$GST_price,
            'Freight_price'=>$Freight_price,
            'stores'=>$Freight['store'],
            'home'=>$Freight['home'],
            'beforeTotal'=>number_format($beforeTotal),
            'afterTotal'=>number_format($afterTotal)
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

        $GST=10;
        $Freight=array(
            'store'=>100,
            'home'=>100
        );
        $freight_gstSet=Freight_GSTData::where('CountryId',$shipping_country)->take(1)->first();
        if($freight_gstSet){
            $GST=$freight_gstSet->GST;
            $Freight['home']=$freight_gstSet->Freight_home;
            $Freight_store=json_decode($freight_gstSet->Freight_store,true);
            if($shipping_region!==""){
                if(isset($Freight_store[$deliveryMethod])){
                    $Freight['store']=$Freight_store[$deliveryMethod][3];
                }
            }
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
                    $beforeTotal+=$basePrice*$quantity;
                }
            }
        }

        $GST_price=round($beforeTotal*($GST/100),2);
        if($deliveryMethod==="deliveryHome")
            $Freight_price=round($Freight['home'],2);
        else
            $Freight_price=round($Freight['store'],2);
        $afterTotal=$beforeTotal+$GST_price+$Freight_price;
        return json_encode(array(
            'GST_percent'=>round($GST,2),
            'GST_price'=>$GST_price,
            'Freight_price'=>$Freight_price,
            'stores'=>$Freight['store'],
            'home'=>$Freight['home'],
            'beforeTotal'=>$beforeTotal,
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
            foreach($cartProducts as $product){
                $quantity=intval($product['quantity']);
                if(trim($product['type'])==='mix'){
                    $query=SalesMixData::join('cases','cases.CaseId','=','sales_mix.ParentCase')->where(array(
                        array('MixId','=',$product['id']),
                    ))->take(1)->first();

                    if($query){
                        $basePrice=$query->S_price?$query->S_price:$query->Price;
                        $beforeTotal+=$basePrice*$quantity;

                        array_push($orderSection,array(
                            'name'=>$query->MixName,
                            'quantity'=>$product['quantity'],
                            'subtotal'=>round($basePrice*$product['quantity'],2)
                        ));
                    }
                }
                elseif(trim($product['type'])==='wine'){
                    $query=WinesData::where(array(
                        array('WineId','=',$product['id']),
                    ))->take(1)->first();

                    if($query){
                        $basePrice=$query->S_price?$query->S_price:$query->Price;
                        $beforeTotal+=$basePrice*$quantity;

                        array_push($orderSection,array(
                            'name'=>$query->Name,
                            'quantity'=>$product['quantity'],
                            'subtotal'=>round($basePrice*$product['quantity'],2)
                        ));
                    }
                }
            }

            $GST=10;
            $GST_Price=0;
            $DeliveryCost=100;
            $freight_gstSet=Freight_GSTData::where('CountryId',$shipping_country)->take(1)->first();
            $shippingStore=array();
            if($freight_gstSet){
                $GST=$freight_gstSet->GST;
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
            $GST_Price=round($beforeTotal*($GST/100),2);
            $afterTotal=$beforeTotal+$GST_Price+$DeliveryCost;

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
                    'Paymethod'=>'required|in:ALIPAY,JDPAY,BESTPAY,WECHAT'       
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

                    'MemberPassword.required_with'=>Session::get('Language')==='EN'?'Please fill the password to become member.':'请填写密码以成为会员',
                    'Paymethod.required'=>Session::get('Language')==='EN'?'Please choose your pay method.':'请选择付款方式',
                    'Paymethod.in'=>Session::get('Language')==='EN'?'Please choose your pay method.':'请选择付款方式'
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
                $Paymethod=request('Paymethod');
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

                    // INSERT into orders data
                    OrdersData::insert(array(
                        'OrderId'=>$newOrderId,
                        'MemberId'=>Session::get('MemberId')?Session::get('MemberId'):'',
                        'Paymethod'=>$Paymethod,
                        'Subtotal'=>$beforeTotal,
                        'GST'=>$GST_Price,
                        'DeliveryCost'=>$DeliveryCost,
                        'Total'=>$afterTotal,
                        'Notes'=>$Notes,
                        'PayExpire'=>date("Y-m-d H:i:s", strtotime("+1 hours")),
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
                                OrderDetailData::insert(array(
                                    'OrderId'=>$newOrderId,
                                    'ProductType'=>'mix',
                                    'Product'=>$product['id'],
                                    'Price'=>$insertPrice,
                                    'Quantity'=>$quantity,
                                    'Subtotal'=>round($insertPrice*$quantity,2)
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
                                OrderDetailData::insert(array(
                                    'OrderId'=>$newOrderId,
                                    'ProductType'=>'wine',
                                    'Product'=>$product['id'],
                                    'Price'=>$insertPrice,
                                    'Quantity'=>$quantity,
                                    'Subtotal'=>round($insertPrice*$quantity,2)
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

                    // Session::forget('product');
                    // Session::forget('shipping_country');
                    // Session::forget('shipping_region');
                    // Session::forget('deliveryMethod');
                    DB::connection()->getPdo()->commit();
                    // Payment API
                    $apiParameters=array(
                        'merchant_id'=>env('ePayments_MerchantID','23300445'),
                        'increment_id'=>$newOrderId,
                        'grandtotal'=>$afterTotal,
                        'currency'=>'NZD',
                        'return_url'=>url('/')."/pay/return_url",
                        'notify_url'=>url('/')."/pay/notify_url",
                        'payment_channels'=>$Paymethod,
                        'subject'=>'Payment about Wine Cave',
                        'describe'=>$apiPaymentDesc,
                        'sign_type'=>'MD5',
                        'service'=>'create_smart_pay',
                        'nonce_str'=>Widget_Helper::createID()
                    );
                    ksort($apiParameters);
                    $signString=http_build_query($apiParameters);
                    // Plus token (signString+key)
                    $signStringKey=$signString.env('ePayments_MerchantKEY','7c7c573f202de105f9928d18541fcb7aa0464a1852445e615353c6672d0e1e1f');
                    // MD5
                    $signature=md5($signStringKey);
                    $apiParameters['signature']=$signature;
                    $smartpayURL="https://api.wetopay.com/api/v1/info/smartpay?".http_build_query($apiParameters);
                    return Redirect::to($smartpayURL);                    
                }
                catch (\PDOException $e) {
                    DB::connection()->getPdo()->rollBack();
                    Session::flash('system_message_flag',true);
                    Session::flash('system_message_status','warning');
                    Session::flash('system_message','Oops...Try again.');
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }
            }
            else{
                $data['orderSection']=$orderSection;
                $data['prices']=array(
                    'beforeTotal'=>number_format($beforeTotal),
                    'GST_Price'=>$GST_Price,
                    'DeliveryCost'=>$DeliveryCost,
                    'afterTotal'=>number_format($afterTotal)
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
}