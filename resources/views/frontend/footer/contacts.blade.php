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
<meta property="og:title" content="{{$DCs['DC_Title']}} - {{$LANGUAGE==='EN'?'Contact Us':'联络我们'}}" />
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
            <div class="col-md-6 col-12 contacts-col-main">
                <div class="footer-page-title">
                    <h2>{{$LANGUAGE==='EN'?'Contact Us':'联络我们'}}</h2>
                </div>
                <form action="/send-contacts" id="contactForm" method="post">
                    <div class="fieldset">
                        <p class="required">* {{$LANGUAGE==='EN'?'Required Fields':'必填栏位'}}</p>
                        <ul class="form-list">
                            <li class="fields">
                                <div class="field">
                                    <label for="name" class="required mb-0">{{$LANGUAGE==='EN'?'Name':'姓名'}}</label>
                                    <div class="input-box">
                                        <input name="name" id="name" title="Name" value="" class="form-control required-entry" type="text">
                                    </div>
                                </div>
                                <div class="field">
                                    <label for="email" class="required mb-0">Email</label>
                                    <div class="input-box">
                                        <input name="email" id="email" title="Email" value="" class="form-control required-entry" type="email" autocapitalize="off" autocorrect="off" spellcheck="false">
                                    </div>
                                </div>
                            </li>
                            <li>
                                <label for="telephone">{{$LANGUAGE==='EN'?'Telephone':'联络电话'}}</label>
                                <div class="input-box">
                                    <input name="telephone" id="telephone" title="Telephone" value="" class="form-control" type="tel">
                                </div>
                            </li>
                            <li class="wide">
                                <label for="comment" class="required mb-0">{{$LANGUAGE==='EN'?'Comment':'您的意见'}}</label>
                                <div class="input-box">
                                    <textarea name="comment" id="comment" title="Comment" class="form-control required-entry" cols="5" rows="3"></textarea>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="buttons-set mt-3">
                        <input type="hidden" value="{{csrf_token()}}" name="_token" />
                        <button type="submit" title="Submit" class="btn btn-block btn-primary"><span><span>{{$LANGUAGE==='EN'?'Submit':'送出信件'}}</span></span></button>
                    </div>
                </form>                        
                <div class="opening-hours-wrapper mt-4">
                    <h4>
                        <strong>
                            <a name="what_are_your_opening_hours"></a>{{$LANGUAGE==='EN'?'Opening Hours':'营业时间'}}*
                        </strong>
                    </h4>
                    <p class="store">Our Wine Cave store at 104 Carlton Gore Road, Newmarket:</p>
                    <p>Monday: 10.30am – 7.00pm<br> Tuesday: 10.30am – 7.00pm<br> Wednesday: 10.30am – 7.00pm<br> Thursday: 10.30am – 7.00pm<br> Friday: 10.30am – 7.00pm<br> Saturday: 10.30am – 7.00pm(Appointment Required)<br> Sunday: Appointment Required<br>The Wine Cave is complied with New Zealand law that we will be closed on Christmas Day, Anzac Day, Easter Friday and Easter Sunday.</p>
                </div>
            </div>
            <div class="col-md-3 col-12">
                <div class="sidebar contacts-sidebar">
                    <div class="widget widget-static-block">
                        <h4>{{$LANGUAGE==='EN'?'Contact Information':'联络资讯'}}</h4>
                        <p class="contact-number">
                            <em class="fas fa-phone-alt"></em> {{$COMPANY['phone']}}
                        </p>
                        <p class="contact-number">
                            <em class="fa fa-envelope"></em> Email <a style="text-transform: none;" href="mailto:{{$COMPANY['email']}}">{{$COMPANY['email']}}</a>
                        </p>
                    </div>
                    <div class="map-locator mt-5">
                        <h4>{{$LANGUAGE==='EN'?'Our Wine Cave Store Location':'店铺地址'}}</h4>
                        <iframe style="border: 0;" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3192.054703539815!2d174.7760178450673!3d-36.86510941502127!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6d0d48744bbcd0a3%3A0xfd24acf47529a9c6!2zMTA0IENhcmx0b24gR29yZSBSb2FkLCBOZXdtYXJrZXQsIEF1Y2tsYW5kIDEwMjPntJDopb_omK0!5e0!3m2!1szh-TW!2stw!4v1587774747546!5m2!1szh-TW!2stw" frameborder="0" width="100%" height="250"></iframe>
                        <p>
                            <a class="map-link" href="https://goo.gl/maps/cSPjakyH6reBe8qt6" target="_blank"><em class="fas fa-search-plus"></em>&nbsp;{{$LANGUAGE==='EN'?'View Larger Map':'浏览详细地图'}}</a>
                        </p>
                    </div>
                    <div class="contact-info-left addresses mt-5">
                        <h4 class="contact-address-title">{{$LANGUAGE==='EN'?'Street Address':'详细地址'}}</h4>
                        104 Carlton Gore Road<br>
                        Newmarket<br>
                        Auckland, 1023<br>
                        New Zealand
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection