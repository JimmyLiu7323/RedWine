@extends('frontend.layouts._main')
@section('content')
<link rel="stylesheet" type="text/css" href="{!! asset('/css/front/wishlist.css') !!}">
<div class="mt-3 mb-3">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-12">
                <div class="pb-4 pt-4 custom-sidebar">
                    <h3 class="mt-0 mb-4 little-sidebar-title">
                        <span class="sidebar-arrow icon-arrow-circle-right" onclick="toggleShrink(this)"></span> {{$LANGUAGE==='EN'?'my wishlist':'愿望清单'}}
                    </h3>
                    <ul class="shrink-list">
                        <li>
                            <a href="/account">{{$LANGUAGE==='EN'?'account dashboard':'帐号大纲'}}</a>
                        </li>
                        <li>
                            <a href="/account/edit">{{$LANGUAGE==='EN'?'maintain account':'维护我的帐号'}}</a>
                        </li>
                        <li>
                            <a href="/address">{{$LANGUAGE==='EN'?'address book':'地址库'}}</a>
                        </li>
                        <!-- <li> -->
                            <!-- <a href="/sales/orders">{{$LANGUAGE==='EN'?'my orders':'我的订单'}}</a> -->
                        <!-- </li> -->
                        <li class="active">{{$LANGUAGE==='EN'?'my wishlist':'愿望清单'}}</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-9 col-sm-12">
                <div class="row">
                    <div class="col-sm-12">
                        <h2 class="mb-1 page-title">{{$LANGUAGE==='EN'?'my wishlist':'愿望清单'}}</h2>
                    </div>
                </div>
                <div class="row mt-2 mb-2">
                    @if(\Session::has('wishlist_add_flag')&&\Session::has('wishlist_add_message'))
                        @if(\Session::get('wishlist_add_flag'))
                        <div class="col-12">
                            <ul class="messages">
                                <li class="success-msg">
                                    <ul>
                                        <li>
                                            <span>{{Session::get('wishlist_add_message')}}</span>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        @endif
                    @endif

                    @if($wishlist->count()>0)
                    <form id="wishlist-view-form" action="/wishlist/update" method="post">
                        <div class="fieldset">
                            <input name="_token" type="hidden" value="{{csrf_token()}}">
                            <table class="table clean-table linearize-table" id="wishlist-table">
                                <thead>
                                    <tr class="first last">
                                        <th class="customer-wishlist-item-image"></th>
                                        <th class="customer-wishlist-item-info"></th>
                                        <th class="customer-wishlist-item-quantity">{{$LANGUAGE==='EN'?'Qty':'数量'}}</th>
                                        <th class="customer-wishlist-item-price">{{$LANGUAGE==='EN'?'Price':'价格'}}</th>
                                        <th class="customer-wishlist-item-cart"></th>
                                        <th class="customer-wishlist-item-remove"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($wishlist as $item)
                                    <tr id="item_{{$item->ProductType}}_{{$item->ProductId}}">
                                        <td class="wishlist-cell0 customer-wishlist-item-image">
                                            @if(strtolower(trim($item->ProductType))==='wine')
                                                @php
                                                $winePicExist=false;
                                                if($item->ProductInfo->Image){
                                                    $setWinePic=public_path($item->ProductInfo->Image);
                                                    $winePicExist=file_exists($setWinePic);
                                                }
                                                @endphp
                                            <a class="product-image" href="/wine/{{$item->ProductId}}" title="{{$LANGUAGE==='EN'&&trim($item->ProductInfo->NameEn)!==''?$item->ProductInfo->NameEn:$item->ProductInfo->Name}}">
                                                @if($winePicExist)
                                                <img src="{!! asset($item->ProductInfo->Image) !!}" width="113" height="113" alt="{{$LANGUAGE==='EN'&&trim($item->ProductInfo->NameEn)!==''?$item->ProductInfo->NameEn:$item->ProductInfo->Name}}">
                                                @else
                                                <img src="{!! asset('images/DefaultImages/DefaultWine.png') !!}" width="113" height="113" alt="{{$LANGUAGE==='EN'&&trim($item->ProductInfo->NameEn)!==''?$item->ProductInfo->NameEn:$item->ProductInfo->Name}}">
                                                @endif
                                            </a>
                                            @elseif(strtolower(trim($item->ProductType))==='mix')
                                                @php
                                                $winePicExist=false;
                                                if($item->ProductInfo->Image){
                                                    $setWinePic=public_path($item->ProductInfo->Image);
                                                    $winePicExist=file_exists($setWinePic);
                                                }
                                                @endphp
                                            <a class="product-image" href="/case/{{$item->ProductId}}" title="{{$LANGUAGE==='EN'&&trim($item->ProductInfo->MixNameEn)!==''?$item->ProductInfo->MixNameEn:$item->ProductInfo->MixName}}">
                                                @if($winePicExist)
                                                <img src="{!! asset($item->ProductInfo->Image) !!}" width="113" height="113" alt="{{$LANGUAGE==='EN'&&trim($item->ProductInfo->MixNameEn)!==''?$item->ProductInfo->MixNameEn:$item->ProductInfo->MixName}}">
                                                @else
                                                <img src="{!! asset('images/DefaultImages/DefaultCase.png') !!}" width="113" height="113" alt="{{$LANGUAGE==='EN'&&trim($item->ProductInfo->MixNameEn)!==''?$item->ProductInfo->MixNameEn:$item->ProductInfo->MixName}}">
                                                @endif
                                            </a>
                                            @elseif(strtolower(trim($item->ProductType))==='gift')
                                                @php
                                                $winePicExist=false;
                                                if($item->ProductInfo->Image){
                                                    $setWinePic=public_path($item->ProductInfo->Image);
                                                    $winePicExist=file_exists($setWinePic);
                                                }
                                                @endphp
                                            <a class="product-image" href="/gift/{{$item->ProductId}}" title="{{$LANGUAGE==='EN'&&trim($item->ProductInfo->NameEn)!==''?$item->ProductInfo->NameEn:$item->ProductInfo->Name}}">
                                                @if($winePicExist)
                                                <img src="{!! asset($item->ProductInfo->Image) !!}" width="113" height="113" alt="{{$LANGUAGE==='EN'&&trim($item->ProductInfo->NameEn)!==''?$item->ProductInfo->NameEn:$item->ProductInfo->Name}}">
                                                @else
                                                <img src="{!! asset('images/DefaultImages/DefaultWine.png') !!}" width="113" height="113" alt="{{$LANGUAGE==='EN'&&trim($item->ProductInfo->NameEn)!==''?$item->ProductInfo->NameEn:$item->ProductInfo->Name}}">
                                                @endif
                                            </a>
                                            @endif
                                        </td>
                                        <td class="wishlist-cell1 customer-wishlist-item-info">
                                            <h4 class="product-name">
                                                @if(strtolower(trim($item->ProductType))==='wine')
                                                <a href="/wine/{{$item->ProductId}}" title="{{$LANGUAGE==='EN'&&trim($item->ProductInfo->NameEn)!==''?$item->ProductInfo->NameEn:$item->ProductInfo->Name}}">{{$LANGUAGE==='EN'&&trim($item->ProductInfo->NameEn)!==''?$item->ProductInfo->NameEn:$item->ProductInfo->Name}}</a>
                                                @elseif(strtolower(trim($item->ProductType))==='mix')
                                                <a href="/case/{{$item->ProductId}}" title="{{$LANGUAGE==='EN'&&trim($item->ProductInfo->MixNameEn)!==''?$item->ProductInfo->MixNameEn:$item->ProductInfo->MixName}}">{{$LANGUAGE==='EN'&&trim($item->ProductInfo->MixNameEn)!==''?$item->ProductInfo->MixNameEn:$item->ProductInfo->MixName}}</a>
                                                @elseif(strtolower(trim($item->ProductType))==='gift')
                                                <a href="/gift/{{$item->ProductId}}" title="{{$LANGUAGE==='EN'&&trim($item->ProductInfo->NameEn)!==''?$item->ProductInfo->NameEn:$item->ProductInfo->Name}}">{{$LANGUAGE==='EN'&&trim($item->ProductInfo->NameEn)!==''?$item->ProductInfo->NameEn:$item->ProductInfo->Name}}</a>
                                                @endif
                                            </h4>
                                            <textarea name="description[{{$item->ProductId}}]" rows="3" cols="5" title="Comment" placeholder="{{$LANGUAGE==='EN'?'Please, enter your comments...':'请留下该商品的註解'}}">{{$item->Memo}}</textarea>
                                            <div class="item-manage">
                                                <button type="submit" name="do" title="{{$LANGUAGE==='EN'?'Update Wishlist':'更新愿望清单'}}" class="btn button btn-update button-secondary"><span><span>{{$LANGUAGE==='EN'?'Update Wishlist':'更新愿望清单'}}</span></span></button>
                                            </div>
                                        </td>
                                        <td class="wishlist-cell2 customer-wishlist-item-quantity" data-rwd-label="Qty">
                                            <div class="cart-cell">
                                                <div class="add-to-cart-alt">
                                                    <input type="number" class="qty" min="1" step="1" name="qty[{{$item->ProductId}}]" value="{{$item->Qty}}">
                                                </div>
                                            </div>
                                        </td>
                                        <td class="wishlist-cell3 customer-wishlist-item-price" data-rwd-label="Price">
                                            <div class="cart-cell">
                                                <div class="price-box">
                                                    @if($item->Option)
                                                    <span class="regular-price">
                                                        <span class="rrpsave"></span>
                                                        <span class="mixed-price">
                                                            <span class="price">${{$item->Option->Price}}</span>
                                                            <span class="mixed-label">{{$LANGUAGE==='EN'?'in mixed '.$item->Option->PurchaseAmount.'+':'当次购买'.$item->Option->PurchaseAmount.'瓶以上'}}</span>
                                                        </span>
                                                        <span class="main-product-price has-case-price">{{$LANGUAGE==='EN'?'or':'或'}}
                                                            <span class="price">@if($item->ProductInfo->S_price){{'$'.number_format($item->ProductInfo->S_price,2)}}@else{{'$'.number_format($item->ProductInfo->Price,2)}}@endif</span>
                                                            <span class="item-price-label">{{$LANGUAGE==='EN'?'per bottle':'每瓶'}}</span>
                                                        </span>
                                                    </span>
                                                    @else
                                                    <span class="regular-price">
                                                        <span class="rrpsave"></span>
                                                        <span class="mixed-price">
                                                            <span class="price">@if($item->ProductInfo->S_price){{'$'.number_format($item->ProductInfo->S_price,2)}}@else{{'$'.number_format($item->ProductInfo->Price,2)}}@endif</span>
                                                            <span class="mixed-label">{{$LANGUAGE==='EN'?'per bottle':'每瓶'}}</span>
                                                        </span>
                                                    </span>                                         
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="wishlist-cell4 customer-wishlist-item-cart">
                                            <div class="cart-cell">
                                                <a href="/wishlist/add_to_order/{{$item->ProductId}}" title="Add to Order" class="button btn btn-primary">
                                                    <span><span>{{$LANGUAGE==='EN'?'Add to Order':'加入购物车'}}</span></span>
                                                </a>
                                            </div>
                                            <p>
                                                @if(strtolower($item->ProductType)==='wine')
                                                <a class="link-edit button btn button-secondary" href="/wine/{{$item->ProductId}}">
                                                @elseif(strtolower($item->ProductType)==='mix')
                                                <a class="link-edit button btn button-secondary" href="/case/{{$item->ProductId}}">
                                                @elseif(strtolower($item->ProductType)==='gift')
                                                <a class="link-edit button btn button-secondary" href="/gift/{{$item->ProductId}}">
                                                @endif
                                                {{$LANGUAGE==='EN'?'Edit':'编辑'}}</a>
                                            </p>
                                        </td>
                                        <td class="wishlist-cell5 customer-wishlist-item-remove last">
                                            <a href="/wishlist/remove/{{$item->ProductId}}" class="button btn-remove btn-remove2 btn btn-danger">{{$LANGUAGE==='EN'?'Remove item':'移除商品'}}</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="buttons-set buttons-set2">
                                <button type="button" title="Add All to Cart" onclick="addAllWItemsToCart()" class="button btn btn-add btn-primary">
                                    <span><span>{{$LANGUAGE==='EN'?'Add All to Cart':'加入所有商品至购物车'}}</span></span>
                                </button>
                                <button type="submit" name="do" title="Update Wishlist" class="button btn button btn-update button-secondary"><span><span>{{$LANGUAGE==='EN'?'Update Wishlist':'更新愿望清单'}}</span></span></button>
                            </div>
                        </div>
                    </form>
                    @else
                    <div class="col-12">
                        <div class="fieldset">
                            <p class="wishlist-empty">{{$LANGUAGE==='EN'?'You have no items in your wishlist.':'您的愿望清单内尚无商品。'}}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
function toggleShrink(elem){
    $(elem).toggleClass('icon-arrow-circle-down');
    $(elem).parent().next('.shrink-list').slideToggle('fast');
}
</script>
@endsection