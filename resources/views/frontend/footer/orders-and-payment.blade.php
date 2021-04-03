@extends('frontend.layouts._main')

@section('metas')
@if($METAs['META_DESCRIPTION']!=='')
<meta name="description" content="{{$METAs['META_DESCRIPTION']}}"/>
<meta property="og:description" content="{{$METAs['META_DESCRIPTION']}}" />
@endif
@if($METAs['META_KEYWORDS']!=='')
<meta name="keywords" content="{{$METAs['META_KEYWORDS']}}"/>
@endif
@endsection

@section('facebook_metas')
<meta property="og:site_name" content="{{$DCs['DC_Title']}}" />
<meta property="og:title" content="{{$DCs['DC_Title']}} - {{$LANGUAGE==='EN'?'Orders and Payment':'订购与付款'}}" />
<meta property="og:image" content="{!! asset('images/logo.png') !!}" />
@endsection

@section('content')
<div class="site-section pt-2">
    <div class="container">
        @if(isset($breadcrumbs))
        <div class="row mb-3">
            <div class="col-12">
                <div class="breadcrumbs">
                    <ul>
                        <li class="home">
                            <a href="/" title="Go to Home Page">{{$LANGUAGE==='EN'?'Home':'首页'}}</a>
                            <span></span>
                        </li>
                        @foreach($breadcrumbs as $breadcrumbIdx=>$breadcrumb)
                        <li>
                            @if( isset($breadcrumb['url']) && intval($breadcrumbIdx)!==count($breadcrumbs)-1 )
                            <a href="{{$breadcrumb['url']}}" title="{{$breadcrumb['name']}}"> {{$breadcrumb['name']}} </a>
                            @else
                            <strong>{{$breadcrumb['name']}} </strong>
                            @endif
                            <span></span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif
        <div class="row">
            <div class="col-md-3 col-12">@include('frontend.layouts.footer-page-menu')</div>
            <div class="col-md-9 col-12">
                <div class="footer-page-title">
                    <h2>{{$LANGUAGE==='EN'?'Orders and Payment':'订购与付款'}}</h2>
                    <div class="std">
                        @if($LANGUAGE==='EN')
                        <p>In order to purchase alcohol you must be over 18 years old and legally entitled to do so.</p>
                        <p>Once your order is processed you will receive an order confirmation with a receipt.</p>
                        <p>All goods are subject to availability. We cannot guarantee the availability of specific vintages.</p>
                        <p>Payment should be made at the time of ordering by Visa or Mastercard, even Wechat and Alipay through our secure server. Cancellation If for some reason you need to cancel an order, please call us immediately on 099308115, or send your request to <a href="mailto:info@winecave.nz" style="color:#9c8c3a">info@winecave.nz</a> . Order cannot be cancelled once parcel has been dispatched.
                        </p>
                        @else
                        <p>提醒您，需年满18岁或法定年龄才可透过我们的网站购买含酒精饮料。</p>
                        <p>在您完成订单之后将会收到包含收件资讯及订单内容的信件。</p>
                        <p>所有商品都经我们严格审核并保管，惟我们无法保证商品送出过程中可能产生的任何损伤。</p>
                        <p>请在订购时通过Visa或Mastercard付款，甚至通过我们的系统以最安全的方式通过微信和支付宝付款。若由于某些原因需要取消订单，请立即致电099308115，或将您的退货原因及订单序号發送到<a href="mailto:info@winecave.nz" style="color:#9c8c3a"> info@winecave.nz </a>。 请注意若包裹寄出后无法取消订单。</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection