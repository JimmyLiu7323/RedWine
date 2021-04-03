@extends('frontend.layouts._main')

@section('metas')
@if($MixInfo->BriefDesc!='')
<meta name="description" content="{{$MixInfo->BriefDesc}}"/>
<meta property="og:description" content="{{$MixInfo->BriefDesc}}" />
@elseif($METAs['META_DESCRIPTION']!=='')
<meta name="description" content="{{$METAs['META_DESCRIPTION']}}"/>
<meta property="og:description" content="{{$METAs['META_DESCRIPTION']}}" />
@endif
@if($METAs['META_KEYWORDS']!=='')
<meta name="keywords" content="{{$METAs['META_KEYWORDS']}}"/>
@endif
@endsection

@section('facebook_metas')
<meta property="og:site_name" content="{{$DCs['DC_Title']}}" />
<meta property="og:title" content="{{$DCs['DC_Title']}} - {{$MixInfo->MixName}}" />
@php
$winePicExist=false;
if($MixInfo->Image){
	$setWinePic=public_path($MixInfo->Image);
	$winePicExist=file_exists($setWinePic);
}
$shareImage = '';
@endphp

@if($winePicExist)
	@php
	$shareImage = "{!! asset($MixInfo->Image) !!}";
	@endphp
<meta property="og:image" content="{!! asset($MixInfo->Image) !!}" />
@else
	@php
	$shareImage = "{!! asset('/images/DefaultImages/DefaultWine.png') !!}";
	@endphp
<meta property="og:image" content="{!! asset('/images/DefaultImages/DefaultCase.png') !!}" />
@endif
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
			<div class="col-md-4 col-12 mb-4 text-center">
				<div class="owl-carousel hero-slide owl-style">
					@php
					$winePicExist=false;
					if($MixInfo->Image){
						$setWinePic=public_path($MixInfo->Image);
						$winePicExist=file_exists($setWinePic);
					}
					$winePic=asset($MixInfo->Image);
					@endphp

					@if($winePicExist)
					<img src="{!! asset($MixInfo->Image) !!}" alt="{{$MixInfo->MixName}} Preview" class="img-fluid">
					@else
					<img src="{!! asset('images/DefaultImages/DefaultCase.png') !!}" alt="Image" class="img-fluid">
					@endif
				</div>
				<div class="share-wish-functions mt-4">
					<div class="row">
						<div class="col-lg-6 col-12">
							<select class="form-control" onchange="shareTo(this)">
								<option value="">{{$LANGUAGE==='EN'?'Share this wine...':'分享这瓶酒'}}
								<option value="facebook">Facebook</option>
								<option value="twitter">Twitter</option>
							</select>
						</div>
						<div class="col-lg-6 col-12 mt-lg-0 mt-2">
							<a href="/wishlist/add/Mix/{{$MixInfo->MixId}}" class="btn btn-block btn-primary">{{$LANGUAGE==='EN'?'Add to Wishlist':'加进愿望清单'}}</a>
						</div>
					</div>
				</div>
			</div>
			<div class="ml-auto col-md-7 col-12">
				<div class="product-shop">
					<div class="product-heading-container">
						<div class="product-name">
							<h2 class="mt-0">{{$MixInfo->MixName}}</h2>
						</div>
					</div>
				@if($MixInfo->Stocks>0)
					<span class="stocks-status enableStocks"><span class="icon-check"></span><span class="stocks-text">{{$LANGUAGE==='EN'?'In Stock':'尚有库存'}}</span></span>
				@else
					<span class="stocks-status disableStocks"><span class="icon-close"></span><span class="stocks-text">{{$LANGAUGE==='EN'?'Out of Stock':'已无库存'}}</span></span>
				@endif
					<div class="short-description">{{$MixInfo->BriefDesc}}</div>
				</div>
				<div class="add-to-cart-wrapper">
					<div class="add-to-box">
						@if($MixInfo->Stocks>0)
						<p class="availability in-stock"><span class="icon-check"> {{$LANGUAGE==='EN'?'In Stock':'尚有库存'}}</span></p>
						@else
						<p class="availability out-stock"><span class="icon-close"> {{$LANGUAGE==='EN'?'Out of Stock':'已无库存'}}</span></p>
						@endif
						<div class="price-info">
							<div class="info-price-box">
								<span class="regular-price">
									@if($MixInfo->Option)
									<span class="mixed-price">
										<span class="price">{{'$'.number_format($MixInfo->Option->Price,2)}}</span>
										@if($LANGUAGE==='EN')
										<span class="mixed-label">in mixed {{$MixInfo->Option->PurchaseAmount}}+</span>
										@else
										<span class="mixed-label">當次购买{{$MixInfo->Option->PurchaseAmount}}瓶以上</span>
										@endif
									</span>
									<span class="main-product-price has-case-price">{{$LANGUAGE==='EN'?'or':'或'}}
										@if($MixInfo->S_price)
										<span class="price">{{'$'.number_format($MixInfo->S_price,2)}}</span>
										@else
										<span class="price">{{'$'.number_format($MixInfo->Price,2)}}</span>
										@endif
										<span class="item-price-label">{{$LANGUAGE==='EN'?'per case':'每組合'}}</span>
									</span>
									@else
									<span class="main-product-price">
										@if($MixInfo->S_price)
										<span class="price">{{'$'.number_format($MixInfo->S_price,2)}}</span>
										@else
										<span class="price">{{'$'.number_format($MixInfo->Price,2)}}</span>
										@endif
										<span class="item-price-label">{{$LANGUAGE==='EN'?'per case':'每組合'}}</span>
									</span>
									@endif
								</span>
							</div>
						</div>
						@if($MixInfo->Stocks>0)
						<div class="add-to-cart">
							<div class="qty-wrapper">
								<label for="itemQuantity">Qty</label>
								<input type="number" id="itemQuantity" class="qty" maxlength="12" value=1 title="Qty" min=1 step=1 />
							</div>
							<div class="add-to-cart-buttons">
								<button type="button" class="buy-now btn btn-sm height-auto btn-primary btn-cart" onclick="addItem('mix','{{$MixInfo->MixId}}')">{{$LANGUAGE==='EN'?'Add To Cart':'加进购物车'}}</button>
							</div>
						</div>
						@endif
						<ul class="add-to-links">
							<li>
								<a class="link-wishlist" href="/wishlist/add/mix/{{$MixInfo->MixId}}">
									<span class="icon-heart"> {{$LANGUAGE==='EN'?'Add to Wishlist':'加进愿望清单'}}</span>
								</a>
							</li>
						</ul>						
					</div>
				</div>
			</div>
		</div>
		@if(count($contentWines)>0)
		<div class="inside">
			<h4 class="inside-title">{{$LANGUAGE==='EN'?'WHAT\'S INSIDE':'组合内容物'}}</h4>
			<div class="inside-box">
				@foreach($contentWines as $wine)
				<div class="inside-wine-container">
					<div class="inside-wine">
						<a href="/wine/{{$wine['info']->MixId}}">
							<div class="left-wine">
								<img src="{!! asset($wine['info']->Image) !!}" alt="{{$wine['info']->Name}} image" class="img-fluid">
							</div>
						</a>
						<div class="right-wine-info">
							<h5 class="wine-title"><a href="/wine/{{$wine['info']->MixId}}">{{$wine['info']->Name}}</a></h5>
							@if(mb_strlen($wine['info']->BriefDesc)>200)
							<p class="mt-3">{{mb_substr($wine['info']->BriefDesc,0,200)}}...</p>
							@else
							<p class="mt-3">{{$wine['info']->BriefDesc}}</p>
							@endif
							<div class="quantity">
								<label>x {{$wine['quantity']}}</label>
							</div>
						</div>
					</div>
				</div>
				@endforeach
			</div>
		</div>
		@endif
	</div>
</div>
<script type="text/javascript">
function shareTo(shareBtn){
	shareLink = '';
	if($(shareBtn).val().trim()==='twitter'){
		var shareLink = "https://twitter.com/share?text={{$LANGUAGE==='EN'&&trim($MixInfo->MixNameEn)!==''?$MixInfo->MixNameEn:$MixInfo->MixName}}&url={{Request::url()}}";
		
	}
	else if($(shareBtn).val().trim()==='facebook'){
		var shareLink = "https://www.facebook.com/sharer.php?s=100&p[url]={{Request::url()}}&p[images][0]={{$shareImage}}&p[title]={$LANGUAGE==='EN'&&trim($MixInfo->MixNameEn)!==''?$MixInfo->MixNameEn:$MixInfo->MixName}}";
	}

	if(shareLink!==''){
		window.open(shareLink, '_blank');
	}
}
</script>
@endsection