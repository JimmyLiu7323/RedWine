@extends('frontend.layouts._main')

@section('metas')
@if($WineInfo->BriefDesc!='')
<meta name="description" content="{{$WineInfo->BriefDesc}}"/>
<meta property="og:description" content="{{$WineInfo->BriefDesc}}" />
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
<meta property="og:title" content="{{$DCs['DC_Title']}} - {{$WineInfo->Name}}" />
@php
$winePicExist=false;
if($WineInfo->Image){
	$setWinePic=public_path($WineInfo->Image);
	$winePicExist=file_exists($setWinePic);
}
$shareImage = '';
@endphp

@if($winePicExist)
	@php
	$shareImage = "{!! asset($WineInfo->Image) !!}";
	@endphp
<meta property="og:image" content="{!! asset($WineInfo->Image) !!}" />
@else
	@php
	$shareImage = "{!! asset('/images/DefaultImages/DefaultWine.png') !!}";
	@endphp
<meta property="og:image" content="{!! asset('/images/DefaultImages/DefaultWine.png') !!}" />
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
			<div class="col-md-4 col-12 mb-4">
				<div class="owl-carousel hero-slide owl-style">
					@if($winePicExist)
						<img src="{!! asset($WineInfo->Image) !!}" alt="{{$WineInfo->Name}} image" class="img-fluid">
						@if($WineInfo->Image2)
						<img src="{!! asset($WineInfo->Image2) !!}" alt="{{$WineInfo->Name}} image" class="img-fluid">
						@endif
						@if($WineInfo->Image3)
						<img src="{!! asset($WineInfo->Image3) !!}" alt="{{$WineInfo->Name}} image" class="img-fluid">
						@endif
					@else
					<img src="{!! asset('images/DefaultImages/DefaultWine.png') !!}" alt="Image" class="img-fluid">
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
							<a href="/wishlist/add/Wine/{{$WineInfo->WineId}}" class="btn btn-block btn-primary">{{$LANGUAGE==='EN'?'Add to Wishlist':'加进愿望清单'}}</a>
						</div>
					</div>
					@if($WineInfo->IntroductionPDF)
						@php
						$PDFExist = false;
						if(file_exists(public_path($WineInfo->IntroductionPDF))){
							$PDFExist = true;
						}
						@endphp
						@if($PDFExist)
					<div class="row mt-2">
						<div class="col-12">
							<a target="_blank" href="{!! asset($WineInfo->IntroductionPDF) !!}" class="btn-default btn btn-block download-pdf-link" alt="Open in new tab" title="Open in new tab">
								<span class="icon">
									<img src="{!! asset('/images/icons/pdf.png') !!}" />
								</span>
								<span class="text text-left">{{$LANGUAGE==='EN'?'Download Information':'下载PDF'}}</span>
							</a>
						</div>
					</div>
						@endif
					@endif
				</div>
			</div>
			<div class="ml-auto col-md-7 col-12">
				<div class="product-shop">
					<div class="product-heading-container">
						<div class="product-name">
							<h2 class="mt-0">{{$WineInfo->Name}}</h2>
						</div>
					</div>
				@if($WineInfo->Stocks>0)
					<span class="stocks-status enableStocks"><span class="icon-check"></span><span class="stocks-text">{{$LANGUAGE==='EN'?'In Stock':'尚有库存'}}</span></span>
				@else
					<span class="stocks-status disableStocks"><span class="icon-close"></span><span class="stocks-text">{{$LANGAUGE==='EN'?'Out of Stock':'已无库存'}}</span></span>
				@endif
					<div class="short-description">{{$WineInfo->BriefDesc}}</div>
				</div>
				<div class="add-to-cart-wrapper">
					<div class="add-to-box">
						@if($WineInfo->Stocks>0)
						<p class="availability in-stock"><span class="icon-check"> {{$LANGUAGE==='EN'?'In Stock':'尚有库存'}}</span></p>
						@else
						<p class="availability out-stock"><span class="icon-close"> {{$LANGUAGE==='EN'?'Out of Stock':'已无库存'}}</span></p>
						@endif
						<div class="price-info">
							<div class="info-price-box">
								<span class="regular-price">
									@if($WineInfo->Option)
									<span class="mixed-price">
										<span class="price">{{'$'.number_format($WineInfo->Option->Price,2)}}</span>
										@if($LANGUAGE==='EN')
										<span class="mixed-label">in mixed {{$WineInfo->Option->PurchaseAmount}}+</span>
										@else
										<span class="mixed-label">當次购买{{$WineInfo->Option->PurchaseAmount}}瓶以上</span>
										@endif
									</span>
									<span class="main-product-price has-case-price">{{$LANGUAGE==='EN'?'or':'或'}}
										@if($WineInfo->S_price)
										<span class="price">{{'$'.number_format($WineInfo->S_price,2)}}</span>
										@else
										<span class="price">{{'$'.number_format($WineInfo->Price,2)}}</span>
										@endif
										<span class="item-price-label">{{$LANGUAGE==='EN'?'per bottle':'每瓶'}}</span>
									</span>
									@else
									<span class="main-product-price">
										@if($WineInfo->S_price)
										<span class="price">{{'$'.number_format($WineInfo->S_price,2)}}</span>
										@else
										<span class="price">{{'$'.number_format($WineInfo->Price,2)}}</span>
										@endif
										<span class="item-price-label">{{$LANGUAGE==='EN'?'per bottle':'每瓶'}}</span>
									</span>
									@endif
								</span>
							</div>
						</div>
						@if($WineInfo->Stocks>0)
						<div class="add-to-cart">
							<div class="qty-wrapper">
								<label for="itemQuantity">Qty</label>
								<input type="number" id="itemQuantity" class="qty" maxlength="12" value=1 title="Qty" min=1 step=1 />
							</div>
							<div class="add-to-cart-buttons">
								<button type="button" class="buy-now btn btn-sm height-auto btn-primary btn-cart" onclick="addItem('wine','{{$WineInfo->WineId}}')">{{$LANGUAGE==='EN'?'Add To Cart':'加进购物车'}}</button>
							</div>
						</div>
						@endif
						<ul class="add-to-links">
							<li>
								<a class="link-wishlist" href="/wishlist/add/wine/{{$WineInfo->WineId}}">
									<span class="icon-heart"> {{$LANGUAGE==='EN'?'Add to Wishlist':'加进愿望清单'}}</span>
								</a>
							</li>
						</ul>						
					</div>
				</div>
				<div id="product-accordion">
					<h4 class="content-title"><span class="span-icon icon-arrow-circle-down"></span> {{$LANGUAGE==='EN'?'Overview':'商品概况'}}</h4>
					<div class="content-box">
						<p class="content-text">{!! $WineInfo->Description !!}</p>
					</div>
					<h4 class="content-title"><span class="span-icon icon-arrow-circle-down"></span> {{$LANGUAGE==='EN'?'Ingredients & Caterings':'原料与配菜'}}</h4>
					<div class="content-box">
						<ul class="ingredients-list">
							<li>Variety: {{$WineInfo->Variety}}</li>
							<li>Colour: {{$WineInfo->Colour}}</li>
							<li>Region: {{$WineInfo->Region}}</li>
							<li>Closure: {{$WineInfo->Closure}}</li>
							<li>Volume: {{$WineInfo->Volume}}ml</li>
							<li>Alcohol: {{$WineInfo->Alcohol}}%</li>
						</ul>
						@if(count($Caterings)>0)
						<p class="content-sub-title">{{$LANGUAGE==='EN'?'Caterings':'配菜'}}</p>
						<div class="caterings">
							<ul>
							@foreach($Caterings as $Catering)
								<li><span class="icon-stop2"></span> {{$Catering['name']}}</li>
							@endforeach
							</ul>
						</div>
						@endif
					</div>
					<h4 class="content-title"><span class="span-icon icon-arrow-circle-down"></span> {{$LANGUAGE==='EN'?'Delivery & Returns':'配送与退货'}}</h4>
					<div class="content-box">
						<p class="content-text">{!! $WineInfo->Delivery_Returns !!}</p>
					</div>
				</div>
			</div>
		</div>
		@if($randomWines)
		<div class="row">
			<div class="col-12 box-collateral box-up-sell">
			    <h5>{{$LANGUAGE==='EN'?'YOU MAY ALSO LIKE THESE WINES':'您可能还会喜欢'}}</h5>
			    <ul class="products-grid products-grid" id="upsell-product-table">
			    	@foreach($randomWines as $randomWine)
			    		@php
			    		$randomWine_PicExist = false;
			    		if($randomWine->Image){
			    			$randomWine_PicExist = file_exists(public_path($randomWine->Image));
			    		}
			    		@endphp
			        <li>
			            <a href="/wine/{{$randomWine->WineId}}" class="product-image">
			                <div class="crop">
			            @if($randomWine_PicExist)
			                    <img src="{!! asset($randomWine->Image) !!}" alt="{{$LANGUAGE==='EN'&&trim($randomWine->NameEn)!==''?$randomWine->NameEn:$randomWine->Name}}">
			            @else
			            		<img src="{!! asset('/images/DefaultImages/DefaultWine.png') !!}" alt="{{$LANGUAGE==='EN'&&trim($randomWine->NameEn)!==''?$randomWine->NameEn:$randomWine->Name}}">
			            @endif
			                </div>
			            </a>
			            <h6 class="product-name">
			                <a href="/wine/{{$randomWine->WineId}}" title="{{$LANGUAGE==='EN'&&trim($randomWine->NameEn)!==''?$randomWine->NameEn:$randomWine->Name}}">{{$LANGUAGE==='EN'&&trim($randomWine->NameEn)!==''?$randomWine->NameEn:$randomWine->Name}}</a>
			            </h6>
			            <div class="random-wine-price-box">
			            	<span class="regular-price">
			            		@if($randomWine->Option)
								<span class="mixed-price">
									<span class="price">{{'$'.number_format($randomWine->Option->Price,2)}}</span>
									@if($LANGUAGE==='EN')
									<span class="mixed-label">in mixed {{$randomWine->Option->PurchaseAmount}}+</span>
									@else
									<span class="mixed-label">當次购买{{$randomWine->Option->PurchaseAmount}}瓶以上</span>
									@endif
								</span>
								<span class="main-product-price has-case-price">{{$LANGUAGE==='EN'?'or':'或'}}
									@if($randomWine->S_price)
									<span class="price">{{'$'.number_format($randomWine->S_price,2)}}</span>
									@else
									<span class="price">{{'$'.number_format($randomWine->Price,2)}}</span>
									@endif
									<span class="item-price-label">{{$LANGUAGE==='EN'?'per bottle':'每瓶'}}</span>
								</span>
								@else
								<span class="main-product-price">
									@if($randomWine->S_price)
									<span class="price">{{'$'.number_format($randomWine->S_price,2)}}</span>
									@else
									<span class="price">{{'$'.number_format($randomWine->Price,2)}}</span>
									@endif
									<span class="item-price-label">{{$LANGUAGE==='EN'?'per bottle':'每瓶'}}</span>
								</span>
								@endif
			                </span>
			            </div>
						<div class="actions">
							<div class="qty-box">
								<label for="qty_wine_{{$randomWine->WineId}}">Qty</label>
								<input type="number" name="qty" id="qty_wine_{{$randomWine->WineId}}" maxlength="12" value=1 step="1" min="1" title="Qty" class="input-text qty">
							</div>
							<button type="button" id="btn_{{$randomWine->WineId}}" title="Add to Order" class="buy-now btn btn-sm height-auto btn-cart" onclick="quick_addItem('wine','{{$randomWine->WineId}}')">{{$LANGUAGE==='EN'?'Add To Cart':'加进购物车'}}</button>
						</div>
					</li>
					@endforeach
			    </ul>
			</div>
		</div>
		@endif
	</div>
</div>
<script type="text/javascript">
function shareTo(shareBtn){
	shareLink = '';
	if($(shareBtn).val().trim()==='twitter'){
		var shareLink = "https://twitter.com/share?text={{$LANGUAGE==='EN'&&trim($WineInfo->NameEn)!==''?$WineInfo->NameEn:$WineInfo->Name}}&url={{Request::url()}}";
		
	}
	else if($(shareBtn).val().trim()==='facebook'){
		var shareLink = "https://www.facebook.com/sharer.php?s=100&p[url]={{Request::url()}}&p[images][0]={{$shareImage}}&p[title]={$LANGUAGE==='EN'&&trim($WineInfo->NameEn)!==''?$WineInfo->NameEn:$WineInfo->Name}}";
	}

	if(shareLink!==''){
		window.open(shareLink, '_blank');
	}
}
</script>
@endsection