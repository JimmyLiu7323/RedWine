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
<meta property="og:title" content="{{$DCs['DC_Title']}} - {{$LANGUAGE==='EN'?'Our Guarantee':'商家保证'}}" />
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
                    <h2>{{$LANGUAGE==='EN'?'Our Guarantee':'商家保证'}}</h2>
                    <div class="std">
                        @if( intval($display_index_guarantee_image)===1 )
                        <p>
                            <img src="{!! asset($index_guarantee_image) !!}" alt="Wine Cave's Guarantee">
                        </p>
                        @endif
                        <p>{!! $guarantee_text !!}</p>
                    </div>
                </div>
            </div>
		</div>
	</div>
</div>
@endsection