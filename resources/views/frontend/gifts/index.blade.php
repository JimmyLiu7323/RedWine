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
<meta property="og:title" content="{{$DCs['DC_Title']}}" />
@if($METAs['META_OG_IMAGE']!=='')
<meta property="og:image" content="{!! asset('uploads/'.$METAs['META_OG_IMAGE']) !!}" />
@endif
@endsection

@section('content')
<div class="mt-3 mb-5">
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
		<div class="case-deals-category-intro-container">
			<div class="category-intro-desc">
				<div class="page-title category-title">
					<h2>{{$LANGUAGE==='EN'?'Gifts':'礼品'}}</h2>
				</div>
			</div>
		</div>

		@if($banners->count()>0)
		<div class="row mb-3">
			<div class="swiper-container container gift-index-banner">
			    <div class="swiper-wrapper">
		    @foreach($banners as $banner)
		        @if($banner->BannerImage)
		        	@php
		        	$banner->BannerImage = str_replace(array("/","\\"),"/",$banner->BannerImage);
		        	$bgImage = asset($banner->BannerImage);
		        	@endphp
		        	<div class="swiper-slide" {{'style=background-image:url("'.$bgImage.'")'}}></div>
		        @endif
		    @endforeach
		    	</div>
		    	<div class="swiper-pagination"></div>
		    </div>
		</div>
		@endif

		<div class="row gift-categories-row">
			<div class="col-12">
				<div class="feature-blocks">
					<div class="row">
				@foreach($categories as $category)
						<a class="col-sm-4 col-12 mt-3" href="/gifts/{{$category->CategoryEn}}">
							<code>
								<div class="feature-block-overlay">
									<h4>{{$LANGUAGE==='EN'?$category->CategoryEn:$category->Category}}</h4>
									<span>+</span>
								</div>
								<p>
									<img src="{!! asset($category->Image) !!}" alt="{{$LANGUAGE==='EN'?$category->CategoryEn:$category->Category}}" />
								</p>
							</code>
						</a>
				@endforeach
					</div>
				</div>
			</div>
			@if(trim($gift_business_image)!=='')
			<div class="col-12 mt-4">
				<img src="{!! asset($gift_business_image) !!}" alt="Gifts for business" style="width:100%">
			</div>
			@endif			
		</div>		
	</div>
</div>
@endsection