@extends('frontend.layouts._main')
@section('content')
<div class="mt-3 mb-3">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-12">
                <div class="pb-4 pt-4 custom-sidebar">
                    <h3 class="mt-0 mb-4 little-sidebar-title">
                        <span class="sidebar-arrow icon-arrow-circle-right" onclick="toggleShrink(this)"></span> {{$LANGUAGE==='EN'?'my account':'帐号总览'}}
                    </h3>
                    <ul class="shrink-list">
                        <li class="active">{{$LANGUAGE==='EN'?'account dashboard':'帐号大纲'}}</li>
                        <li>
                            <a href="/account/edit">{{$LANGUAGE==='EN'?'maintain account':'维护我的帐号'}}</a>
                        </li>
                        <li>
                            <a href="/address">{{$LANGUAGE==='EN'?'address book':'地址库'}}</a>
                        </li>
                        <!-- <li> -->
                            <!-- <a href="/sales/orders">{{$LANGUAGE==='EN'?'my orders':'我的订单'}}</a> -->
                        <!-- </li> -->
                        <li>
                            <a href="/wishlist/">{{$LANGUAGE==='EN'?'my wishlist':'愿望清单'}}</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-9 col-sm-12">
                <div class="row">
                    <div class="col-sm-12">
                        <h2 class="mb-1 page-title">{{$LANGUAGE==='EN'?'my dashboard':'帐号大纲'}}</h2>
                        <p class="mt-2 mb-2 font-weight-bold">{{$LANGUAGE==='EN'?'Hi':'嗨'}}, {{\Session::get('FirstName')}} {{\Session::get('LastName')}}</p>
                        @if($LANGUAGE==='EN')
                        <p class="mt-0 mb-0">Here is snapshot of your recent account information. Select the page link you want to view or maintain.</p>
                        @else
                        <p class="mt-0 mb-0">可以在这里看到您帐号的最新资讯，请选择欲浏览或维护的页面连结。</p>
                        @endif                        
                    </div>
                </div>
                <div class="row mt-4 mb-4">
                    <div class="col-sm-12">
                        <h4 class="box-head">{{$LANGUAGE==='EN'?'account information':'帐号资讯'}}</h4>
                    </div>
                    <div class="col-md-6 col-sm-12 box">
                        <div class="box-title">
                            <h5>{{$LANGUAGE==='EN'?'contact information':'联络资讯'}}</h5>
                            <a href="/account/edit">{{$LANGUAGE==='EN'?'Edit':'编辑'}}</a>
                        </div>
                        <div class="box-content">
                            <p>
                                {{\Session::get('FirstName')}} {{\Session::get('LastName')}}<br />
                                {{$currentMemInfo->Email}}
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 box">
                        <div class="box-title">
                            <h5>{{$LANGUAGE==='EN'?'news':'接收最新消息'}}</h5>
                            <a href="/newsletter/manage/">{{$LANGUAGE==='EN'?'Edit':'编辑'}}</a>
                        </div>
                        <div class="box-content">
                            <p>
                                @if(\Session::get('Language')==='EN')
                                    @if(!$currentMemInfo->FollowingNews){{'You are currently not subscribed to any news.'}}
                                    @else{{'You have already subscribed latest newsletter'}}
                                    @endif
                                @else
                                    @if(!$currentMemInfo->FollowingNews){{'您已訂閱最新消息。'}}
                                    @else{{'您尚未訂閱最新消息。'}}
                                    @endif                                
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row mt-5 mb-4">
                    <div class="col-sm-12">
                        <h4 class="box-head">{{$LANGUAGE==='EN'?'address book':'地址库'}}</h4>
                    </div>
                    <div class="col-md-6 col-sm-12 box">
                        <div class="box-title">
                            <h5>{{$LANGUAGE==='EN'?'default billing address':'帐单预设地址'}}</h5>
                            <a href="/address/billing/edit">{{$LANGUAGE==='EN'?'Edit':'编辑'}}</a>
                        </div>
                        <div class="box-content">
                        @if(!$defaultBilling)
                        <p>{{$LANGUAGE==='EN'?'Add your default billing address first':'请先新增您的帐单预设地址'}}</p>
                        @else
                        <p class="mt-1 mb-1">{{$defaultBilling->Contact_first}} {{$defaultBilling->Contact_middle}} {{$defaultBilling->Contact_last}}</p>
                        <p class="mt-1 mb-1">{{$defaultBilling->StreetAddr}}</p>
                            @if($defaultBilling->StreetAddr2)
                        <p class="mt-1 mb-1">{{$defaultBilling->StreetAddr2}}</p>
                            @endif
                        <p class="mt-1 mb-1">{{$defaultBilling->City}}, {{$defaultBilling->Region}}, {{$defaultBilling->PostCode}}</p>
                        <p class="mt-1 mb-1">{{\Config::get('app.countries')[$defaultBilling->Country]}}</p>
                        <p class="mt-1 mb-1">{{$LANGUAGE==='EN'?'Phone':'电话'}}: {{$defaultBilling->Telephone}}</p>
                        @endif
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 box">
                        <div class="box-title">
                            <h5>{{$LANGUAGE==='EN'?'default shipping address':'配送预设地址'}}</h5>
                            <a href="/address/shipping/edit">{{$LANGUAGE==='EN'?'Edit':'编辑'}}</a>
                        </div>
                        <div class="box-content">
                        @if(!$defaultShipping)
                        <p>{{$LANGUAGE==='EN'?'Add your default shipping address first':'请先新增您的配送预设地址'}}</p>
                        @else
                        <p class="mt-1 mb-1">{{$defaultShipping->Contact_first}} {{$defaultShipping->Contact_middle}} {{$defaultShipping->Contact_last}}</p>
                        <p class="mt-1 mb-1">{{$defaultShipping->StreetAddr}}</p>
                            @if($defaultShipping->StreetAddr2)
                        <p class="mt-1 mb-1">{{$defaultShipping->StreetAddr2}}</p>
                            @endif
                        <p class="mt-1 mb-1">{{$defaultShipping->City}}, {{$defaultShipping->Region}}, {{$defaultShipping->PostCode}}</p>
                        <p class="mt-1 mb-1">{{\Config::get('app.countries')[$defaultShipping->Country]}}</p>
                        <p class="mt-1 mb-1">{{$LANGUAGE==='EN'?'Phone':'电话'}}: {{$defaultShipping->Telephone}}</p>
                        @endif
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