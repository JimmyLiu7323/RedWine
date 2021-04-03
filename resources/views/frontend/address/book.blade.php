@extends('frontend.layouts._main')
@section('content')
<div class="mt-3 mb-3">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-12">
                <div class="pb-4 pt-4 custom-sidebar">
                    <h3 class="mt-0 mb-4 little-sidebar-title">
                        <span class="sidebar-arrow icon-arrow-circle-right" onclick="toggleShrink(this)"></span> {{$LANGUAGE==='EN'?'my account':'帐号总览'}}
                    </h3>
                    <ul class="shrink-list">
                        <li>
                            <a href="/account">{{$LANGUAGE==='EN'?'account dashboard':'帐号大纲'}}</a>
                        </li>
                        <li>
                            <a href="/account/edit">{{$LANGUAGE==='EN'?'maintain account':'维护我的帐号'}}</a>
                        </li>
                        <li class="active">{{$LANGUAGE==='EN'?'address book':'地址库'}}</li>
                        <!-- <li> -->
                            <!-- <a href="/sales/orders">{{$LANGUAGE==='EN'?'my orders':'我的订单'}}</a> -->
                        <!-- </li> -->
                        <li>
                            <a href="/wishlist/">{{$LANGUAGE==='EN'?'my wishlist':'愿望清单'}}</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-9 col-12">
                @if(\Session::has('badge_flag'))
                <div class="mb-2 badge_flag @if(\Session::has('badge_flag_type')=='warning') @elseif(\Session::has('badge_flag_type')=='error') error_badge @endif">
                    <div class="badge_triangle"></div>
                    <div class="badge_flag_msg">{{\Session::get('badge_flag_message')}}</div>
                </div>
                @endif
                <div class="row">
                    <div class="col-6">
                        <h2 class="mb-0 mt-0 page-title">{{$LANGUAGE==='EN'?'address book':'地址库'}}</h2>
                    </div>
                    <div class="col-6 text-right">
                        <a href="/address/new/" class="btn btn-md btn-primary text-uppercase">{{$LANGUAGE==='EN'?'new address':'新增地址'}}</a>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-sm-12">
                        <h4 class="box-head h4">{{$LANGUAGE==='EN'?'Default Addresses':'预设地址'}}</h4>
                    </div>
                    <div class="col-sm-12 mt-4">
                        <h5 class="border-bottom mb-4">{{$LANGUAGE==='EN'?'Default Billing Address':'预设帐单地址'}}</h5>
                        @if(!$defaultBilling)
                        <p>{{$LANGUAGE==='EN'?'Add your default billing address first':'请先新增您的帐单地址'}}</p>
                        @else
                        <p class="mt-1 mb-1">{{$defaultBilling->Contact_first}} {{$defaultBilling->Contact_middle}} {{$defaultBilling->Contact_last}}</p>
                        <p class="mt-1 mb-1">{{$defaultBilling->StreetAddr}}</p>
                            @if($defaultBilling->StreetAddr2)
                        <p class="mt-1 mb-1">{{$defaultBilling->StreetAddr2}}</p>
                            @endif
                        <p class="mt-1 mb-1">{{$defaultBilling->City}}, {{$defaultBilling->Region}}, {{$defaultBilling->PostCode}}</p>
                        <p class="mt-1 mb-1">{{\Config::get('app.countries')[$defaultBilling->Country]}}</p>
                        <p class="mt-1 mb-1">{{$LANGUAGE==='EN'?'Phone':'连络电话'}}: {{$defaultBilling->Telephone}}</p>
                        @endif
                        <div class="mt-4">
                            <a class="btn btn-sm btn-primary" href="/address/billing/edit">{{$LANGUAGE==='EN'?'Edit Billing Address':'编辑帐单地址'}}</a>
                        </div>
                    </div>
                    <div class="col-sm-12 mt-4">
                        <h5 class="border-bottom mb-4">{{$LANGUAGE==='EN'?'Default Shipping Address':'预设配送地址'}}</h5>
                        @if(!$defaultShipping)
                        <p>{{$LANGUAGE==='EN'?'Add your default shipping address first':'请先新增您的配送地址'}}</p>
                        @else
                        <p class="mt-1 mb-1">{{$defaultShipping->Contact_first}} {{$defaultShipping->Contact_middle}} {{$defaultShipping->Contact_last}}</p>
                        <p class="mt-1 mb-1">{{$defaultShipping->StreetAddr}}</p>
                            @if($defaultShipping->StreetAddr2)
                        <p class="mt-1 mb-1">{{$defaultShipping->StreetAddr2}}</p>
                            @endif
                        <p class="mt-1 mb-1">{{$defaultShipping->City}}, {{$defaultShipping->Region}}, {{$defaultShipping->PostCode}}</p>
                        <p class="mt-1 mb-1">{{\Config::get('app.countries')[$defaultShipping->Country]}}</p>
                        <p class="mt-1 mb-1">{{$LANGUAGE==='EN'?'Phone':'连络电话'}}: {{$defaultShipping->Telephone}}</p>
                        @endif
                        <div class="mt-4">
                            <a class="btn btn-sm btn-primary" href="/address/shipping/edit">{{$LANGUAGE==='EN'?'Edit Shipping Address':'编辑配送地址'}}</a>
                        </div>                        
                    </div>
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