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
<meta property="og:title" content="{{$DCs['DC_Title']}} - {{$LANGUAGE==='EN'?'Delivery Information':'运送资讯'}}" />
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
                    <h2>{{$LANGUAGE==='EN'?'Delivery Information':'运送资讯'}}</h2>
                    <div class="std">
                        <ul>
                            <li><a style="color:#9c8c3a" href="#nz_nationwide_delivery">{{$LANGUAGE==='EN'?'New Zealand Nationwide Delivery':'纽西兰国内运送'}}</a></li>
                            <li><a style="color:#9c8c3a" href="#worldwide_delivery">{{$LANGUAGE==='EN'?'Worldwide Wine Delivery':'全球运送'}}</a></li>
                        </ul>
                        <h4 style='margin:10px 0;line-height:20px;color:#555;font-family:"Open Sans","Helvetica Neue",Verdana,Arial,sans-serif;font-size:18px;font-weight:normal'>
                            <br>
                            <strong>
                                <a name="nz_nationwide_delivery"></a>{{$LANGUAGE==='EN'?'New Zealand Nationwide Delivery':'纽西兰国内运送'}}
                            </strong>
                        </h4>
                        @if($LANGUAGE==='EN')
                        <p>All of our orders are sent via our own or the third party courier. An email with tracking number will be sent automaticly once the order leaves our warehouse. Shipping fees are as below:</p>
                        <ul>
                            <li>Auckland: $5</li>
                            <li>North Island: $10</li>
                            <li>South Island: $15</li>
                        </ul>
                        @else
                        <p>所有订单将会从与我们合作的第三方物流公司进行配送。 当包裹开始配送时将会寄發一封包含可以追踪包裹的物流编号给您。 运费则如下:</p>
                        <ul>
                            <li>Auckland: $5</li>
                            <li>North Island: $10</li>
                            <li>South Island: $15</li>
                        </ul>
                        @endif
                        <p style="text-align: right;"><span style="font-size: x-small;"><a href="#top" style="color:#9c8c3a">Back to Top</a></span></p>
                        <h4 style='margin:10px 0;line-height:20px;color:#555;font-family:"Open Sans","Helvetica Neue",Verdana,Arial,sans-serif;font-size:18px;font-weight:normal'>
                            <strong>
                                <a name="worldwide_delivery"></a>{{$LANGUAGE==='EN'?'Worldwide Wine Delivery':'全球运送'}}
                            </strong>
                        </h4>
                        @if($LANGUAGE==='EN')
                        <p>We offer an easy hassle free worldwide delivery service that ensures your wine reaches you in a perfect condition.</p>
                        <p>We sell and deliver the wines to over 30 countries worldwide. Shipping fee is defferent each coutry.</p>
                        <p>We will calculate the shipping fees automaticly once you have fill the address column when placing an order.</p>
                        @else
                        <p>我们提供轻鬆无忧的全球送货服务，确保您的葡萄酒以完美的状态到达您的手中。</p>
                        <p>我们向全球30多个国家销售和运送葡萄酒。运费因每个国家而异。</p>
                        <p>请于订购流程中填写地址栏，我们将自动计算运费。</p>                        
                        @endif
                        <p style="text-align: right;"><span style="font-size: x-small;"><a href="#top" style="color:#9c8c3a">Back to Top</a></span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection