@extends('frontend.layouts._main')
@section('content')
<style type="text/css">
.price-item-list{font-size:1.5em}
</style>
<div class="container mt-5 mb-5">
    <div class="row mb-2 justify-content-center">
        <div class="col-12 section-title text-left mb-0">
            @if(!empty($cartItems))
            <h2 class="d-block">{{$LANGUAGE==='EN'?'Your Cart':'您的購物車'}}</h2>
            @else
            <h2 class="d-block">{{$LANGUAGE==='EN'?'Your Cart is Empty':'您的購物車沒有商品'}}</h2>
            @endif
        </div>
    </div>
    @if(!empty($cartItems))
    <form method="POST" action="/shopping/updateCart" class="mb-3">
        <div class="row mb-5">
            <div class="col-12">
                <div class="site-blocks-table">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th class="product-preview">{{$LANGUAGE==='EN'?'Product':'商品'}}</th>
                                <th class="product-contents"></th>
                                <th class="product-price text-center">{{$LANGUAGE==='EN'?'Price':'價格'}}</th>
                                <th class="product-quantity text-center">{{$LANGUAGE==='EN'?'Quantity':'購買數量'}}</th>
                                <th class="product-total text-center">{{$LANGUAGE==='EN'?'Subtotal':'小計'}}</th>
                                <th class="product-remove"></th>
                            </tr>
                        </thead>
                        @if($canSave)
                        <tfoot>
                            <tr class="first last">
                                <td colspan="50" class="a-right cart-footer-actions last">
                                    <div class="case-price-cart-message">
                            @if($LANGUAGE==='EN')
                                        <label>Add any <b>{{$needBottles}}</b> bottles to get discount and <b>save ${{$savePrice}}</b> on your current shopping.</label>
                            @else
                                        <label>新增任意 <b>{{$needBottles}}</b> 瓶以取得折扣并为此次购买<b>省下 ${{$savePrice}}</b></label>
                            @endif                                        
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                            @endif
                        <tbody>
                            @foreach($cartItems as $item)
                                @php
                                $itemPic=$item['image'];
                                $testPath=public_path($itemPic);
                                $itemShowPic=asset($item['image']);
                                @endphp
                            <tr>
                                <td class="product-thumbnail">
                                    @if($item['type']=='mix')
                                    <a href="/case/{{$item['id']}}">
                                    @elseif($item['type']=='wine')
                                    <a href="/wine/{{$item['id']}}">
                                    @elseif($item['type']=='gift')
                                    <a href="/gift/{{$item['id']}}">
                                    @endif

                                    @if(file_exists($testPath)&&$item['image'])                                        
                                        <img src="{!! $itemShowPic !!}" alt="{{$item['name']}} Preview" class="img-fluid" />
                                    @else
                                        <img src="{!! asset('/images/wine_1.png') !!}" alt="{{$item['name']}} Previw" class="img-fluid">
                                    @endif
                                    </a>
                                </td>
                                <td class="product-name">
                                    <h3 class="h5 cart-product-title text-black">
                                        @if($item['type']=='mix')
                                        <a href="/case/{{$item['id']}}">{{$item['name']}}</a>
                                        @elseif($item['type']=='wine')
                                        <a href="/wine/{{$item['id']}}">{{$item['name']}}</a>
                                        @elseif($item['type']=='gift')
                                        <a href="/gift/{{$item['id']}}">{{$item['name']}}</a>
                                        @endif
                                    </h3>
                                    @if($item['type']=='mix')
                                    <dl class="item-options">
                                        @foreach($item['contents'] as $itemIdx=>$itemOption)
                                        <dt>Wine {{$itemIdx+1}}</dt>
                                        @if($LANGUAGE==='EN'&&trim($itemOption['info']->NameEn)!=='')
                                        <dd>{{$itemOption['quantity']}} x {{$itemOption['info']->NameEn}}</dd>
                                        @else
                                        <dd>{{$itemOption['quantity']}} x {{$itemOption['info']->Name}}</dd>
                                        @endif
                                        @endforeach
                                    </dl>
                                    @endif
                                </td>
                                <td class="product-price text-center">${{number_format($item['price'],2)}}</td>
                                <td class="product-qty">
                                    <input type="number" step="1" min="1" class="form-control text-center border" value="{{$item['qty']}}" placeholder="1" name="itemQty[{{$item['id']}}]" />
                                </td>
                                <td class="product-subtotal text-center">${{$item['subtotal']}}</td>
                                <td class="product-remove text-right">
                                    <a href="/shopping/deleteItem/{{$item['id']}}" class="height-auto" onclick="return confirm('Sure about delete this product?')">
                                        <span class="icon-remove_shopping_cart"></span>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-12 mb-3">
                <input type="hidden" name="_token" value="{{csrf_token()}}" />
                <button type="submit" class="btn btn-primary btn-md btn-block">{{$LANGUAGE==='EN'?'Update Cart':'更新購物車'}}</button>
            </div>
            <div class="col-md-6 col-12">
                <a href="/wines" class="btn btn-outline-primary btn-md btn-block">{{$LANGUAGE==='EN'?'Continue Shopping':'繼續購物'}}</a>
            </div>
        </div>
    </form>

    <div class="row">
        <div class="col-md-6 col-12 mb-3">
            <div class="bg-light pt-4 pb-4 pl-4 pr-4">
                <div class="row">
<!--                     <div class="col-md-6 col-12 mb-2">
                        <div>
                            <label class="text-black h4" for="coupon">Coupon</label>
                            <p>Enter your coupon code if you have one.</p>
                        </div>
                        <div class="mb-3">
                            <input type="text" class="form-control py-3" id="coupon" placeholder="Coupon Code">
                        </div>
                        <div>
                            <button class="btn btn-primary btn-md px-4">Apply Coupon</button>
                        </div>
                    </div>
                    <div class="col-md-6 col-12"> -->
                    <div class="col-12">
                        <div>
                            <label class="text-black h4" for="coupon">{{$LANGUAGE==='EN'?'Delivery Cost':'配送費用'}}</label>
                            <p>{{$LANGUAGE==='EN'?'Choose destination to get an estimate':'選擇目的地以估算金額'}}</p>
                        </div>
                        <div class="mb-3">
                            <label class="text-black" for="shipping_country">{{$LANGUAGE==='EN'?'Country':'國家'}} <span class="text-danger">*</span></label>
                            <select id="shipping_country" class="form-control" name="shipping_country" onchange="getRegion(this)">
                                <option value=""></option>
                                @foreach($shipping_countries as $shipping_country)
                                <option value="{{$shipping_country->CountryId}}">{{$LANGUAGE==='EN'&&trim($shipping_country->CountryEn)!==''?$shipping_country->CountryEn:$shipping_country->Country}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3" id="shipping_regions_container" style="display:none">
                            <label class="text-black" for="shipping_region">{{$LANGUAGE==='EN'?'Region':'地區'}} <span class="text-danger">*</span></label>
                            <select id="shipping_region" class="form-control" name="shipping_region"></select>                            
                        </div>
                        <div class="mt-2 mb-3" id="shipping_method_container">
                            <p class="mb-0 mt-0 h6 font-weight-bold">{{$LANGUAGE==='EN'?'Pickup at Store (during store hours)':'到店取貨(營業時間內)'}}</p>
                            <div id="in-store-list" class="mb-4"></div>
                            <p class="mb-0 mt-0 h6 font-weight-bold">{{$LANGUAGE==='EN'?'Deliver to Home':'送貨到府'}}</p>
                            <div id="deliver-home-list"></div>
                        </div>
                        <button type="submit" class="btn btn-md btn-block btn-default" onclick="initEstimate(false)">{{$LANGUAGE==='EN'?'Estimate Delivery Costs':'估算配送費用'}}</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="bg-light pt-4 pb-4 pl-4 pr-4">
                <div class="row">
                    <div class="col-12">
                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="border-bottom">
                                    <div class="row">
                                        <div class="col-6">
                                            <span class="text-black price-item-list">{{$LANGUAGE==='EN'?'SUBTOTAL':'小計'}}</span>
                                        </div>
                                        <div class="col-6 text-right">
                                            <strong class="text-black price-item-list" id="beforeTotal">${{$total}}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="border-bottom">
                                    <div class="row">
                                        <div class="col-6">
                                            <span class="text-black price-item-list">{{$LANGUAGE==='EN'?'GST':'商品和服務稅'}}</span>
                                        </div>
                                        <div class="col-6 text-right">
                                            <strong class="text-black price-item-list" id="GST">$0.00</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="border-bottom">
                                    <div class="row">
                                        <div class="col-6">
                                            <span class="text-black price-item-list">{{$LANGUAGE==='EN'?'DELIVERY':'運費'}}</span>
                                        </div>
                                        <div class="col-6 text-right">
                                            <strong class="text-black price-item-list" id="Freight_price">$0.00</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3" style="font-size:2em">
                            <div class="col-12">
                                <div class="border-bottom">
                                    <div class="row">
                                        <div class="col-6">
                                            <span class="text-black">{{$LANGUAGE=='EN'?'TOTAL':'訂單金額'}}</span>
                                        </div>
                                        <div class="col-6 text-right">
                                            <strong class="text-black" id="afterTotal">$0.00</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <a class="btn btn-primary btn-lg btn-block" href="/shopping/checkout">{{$LANGUAGE==='EN'?'Proceed To Checkout':'下一步'}}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
    const shipping_regions=JSON.parse('{!! $shipping_regions !!}');
    function getRegion(elem){
        $('#shipping_region').empty();
        if(typeof(shipping_regions[$(elem).val()])!="undefined"&&shipping_regions[$(elem).val()].length>0){
            $('#shipping_region').append("<option value=''>Please select region</option>");
            for(var i=0;i<shipping_regions[$(elem).val()].length;i++){
                $('#shipping_region').append("<option value='"+shipping_regions[$(elem).val()][i]+"'>"+shipping_regions[$(elem).val()][i]+"</option>");
            }
            $('#shipping_regions_container').show();
        }
        else{
            $('#shipping_regions_container').hide();
            $('#shipping_method_container').hide();
        }
    }

    function initEstimate(flag){
        var can_estimate=true;

        var selected_shipping_country=($('#shipping_country').val()).trim();
        var selected_shipping_region="";

        if($('#shipping_region').val())
            selected_shipping_region=($('#shipping_region').val()).trim();
        if(selected_shipping_country!==""){
            if(typeof(shipping_regions[selected_shipping_country])!="undefined"&&shipping_regions[selected_shipping_country].length>0){
                if(flag=='auto'){
                    $('select#shipping_region option:eq(1)').attr('selected','selected');
                }
                else if(selected_shipping_region===""){
                    can_estimate=false;
                }                
            }

            if(can_estimate){
                $('#in-store-list').empty();
                $('#deliver-home-list').empty();
                $.ajax({
                    "type": "POST",
                    "headers": {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                    "url": "/shopping/estimateUpdatePost",
                    "data":{
                        country:selected_shipping_country,
                        region:selected_shipping_region
                    },
                    success:function(estimateRes){
                        try{
                            estimateRes=JSON.parse(estimateRes);
                            $('#beforeTotal').text('$'+estimateRes['beforeTotal']);
                            $('#GST').text('$'+estimateRes['GST_price']);
                            $('#Freight_price').text('$'+estimateRes['Freight_price']);
                            $('#afterTotal').html('$'+estimateRes['afterTotal']);

                            if(estimateRes['stores'].length>0){
                                let deliveryHome="<ul>";
                                deliveryHome+="<li>";
                                @if($LANGUAGE==='EN')
                                deliveryHome+="<label><input type='radio' checked='true' onclick='chooseDeliveryMethod(this)' name='pickupMethod' value='deliveryHome' id='deliveryHome_radio' /> Courier Service - $"+estimateRes['home']+"</label>";
                                @else               
                                deliveryHome+="<label><input type='radio' checked='true' onclick='chooseDeliveryMethod(this)' name='pickupMethod' value='deliveryHome' id='deliveryHome_radio' /> 宅配服務 - $"+estimateRes['home']+"</label>";
                                @endif
                                deliveryHome+="</li>";
                                deliveryHome+="</ul>";
                                $('#deliver-home-list').append(deliveryHome);

                                let deliveryStore="<ul>";
                                for(var j=0;j<estimateRes['stores'].length;j++){
                                    deliveryStore+="<li>";
                                    @if($LANGUAGE==='EN')
                                    deliveryStore+="<label><input type='radio' name='pickupMethod' onclick='chooseDeliveryMethod(this)' value='store_"+j+"' />"+estimateRes['stores'][j]['address']+" | <a target='_blank' href='"+estimateRes['stores'][j]['mapurl']+"'>View Map</a> - $"+estimateRes['stores'][j]['cost']+"</label>";
                                    @else
                                    deliveryStore+="<label><input type='radio' name='pickupMethod' onclick='chooseDeliveryMethod(this)' value='store_"+j+"' />"+estimateRes['stores'][j]['address']+" | <a target='_blank' href='"+estimateRes['stores'][j]['mapurl']+"'>瀏覽地圖</a> - $"+estimateRes['stores'][j]['cost']+"</label>";
                                    @endif
                                    deliveryStore+="</li>";
                                }
                                $('#in-store-list').append(deliveryStore);
                                $('#shipping_method_container').show();
                            }
                        }
                        catch(unexpextedErr){
                            @if($LANGUAGE==='EN')
                            alert("Something failed, try again...");
                            @else
                            alert("不知名錯誤，請重試...");
                            @endif
                        }
                    },
                    error:function(err){
                        @if($LANGUAGE==='EN')
                        alert("Something failed, try again...");
                        @else
                        alert("不知名錯誤，請重試...");
                        @endif
                    }
                })
            }
            else{
                @if($LANGUAGE==='EN')
                alert('Sorry, you should choose country and region.');
                @else
                alert('很抱歉，您需先選擇國家與區域。');
                @endif
            }
        }
        else{
            @if($LANGUAGE==='EN')
            alert('Choose the country at least.');
            @else
            alert('至少要選擇國家。');
            @endif
        }
    }

    function chooseDeliveryMethod(elem){
        const deliveryMethod=$(elem).val();
        let can_estimate=true;
        var selected_shipping_country=($('#shipping_country').val()).trim();
        var selected_shipping_region="";

        if($('#shipping_region').val())
            selected_shipping_region=($('#shipping_region').val()).trim();
        if(selected_shipping_country!==""){
            if(typeof(shipping_regions[selected_shipping_country])!="undefined"&&shipping_regions[selected_shipping_country].length>0){
                if(selected_shipping_region===""){
                    can_estimate=false;
                }
            }

            if(can_estimate){    
                $.ajax({
                    "type": "POST",
                    "headers": {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                    "url": "/shopping/chooseDeliveryMethod",
                    "data":{
                        country:selected_shipping_country,
                        region:selected_shipping_region,
                        deliveryMethod:deliveryMethod
                    },
                    success:function(newCost){
                        try{
                            newCost=JSON.parse(newCost);
                            $('#beforeTotal').text('$'+newCost['beforeTotal']);
                            $('#GST').text('$'+newCost['GST_price']);
                            $('#Freight_price').text('$'+newCost['Freight_price']);
                            $('#afterTotal').html('$'+newCost['afterTotal']);
                        }
                        catch(unexpextedErr){
                            @if($LANGUAGE==='EN')
                            alert("Something failed, try again...");
                            @else
                            alert("不知名錯誤，請重試...");
                            @endif
                        }
                    },
                    error:function(err){
                        @if($LANGUAGE==='EN')
                        alert("Something failed, try again...");
                        @else
                        alert("不知名錯誤，請重試...");
                        @endif
                    }
                })
            }
        }
        else{
            @if($LANGUAGE==='EN')
            alert('Choose the country at least.');
            @else
            alert('至少要選擇國家。');
            @endif
        }
    }
    </script>
    @else
        @if($LANGUAGE==='EN')
    <p>You have no items in your shopping cart.</p>
    <p>Click <a href="/wines" style="color:#9c8c3a">Here</a> to continue shopping.</p>
        @else
    <p>您尚未加入商品到購物車中。</p>
    <p>點擊 <a href="/wines" style="color:#9c8c3a">此處</a> 繼續購物。</p>        
        @endif
    @endif
</div>
@endsection