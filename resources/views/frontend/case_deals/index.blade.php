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
<style type="text/css">
#slider-start{margin-top:20px}
.noUi-connect{background:#118AB2}
.noUi-horizontal{height:5px}
.noUi-handle:before, .noUi-handle:after{display:none}
.noUi-horizontal .noUi-handle{width:15px;height:15px;right:-7px;top:-6px;border-radius:50%}
</style>
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
					<h2>{{$LANGUAGE==='EN'?'Case Deals':'销售组合'}}</h2>
				</div>
				<div class="category-description">
					<h4>{{$LANGUAGE==='EN' && trim($case_deals_subtitle_en)!=='' ? $case_deals_subtitle_en : $case_deals_subtitle}}</h4>
					<p class="mt-0 mb-0">{{$LANGUAGE==='EN' && trim($case_deals_description_en)!=='' ? $case_deals_description_en : $case_deals_description}}</p>
				</div>
			</div>
			<div class="category-intro-img">
				<p class="category-image">
					<img src="{!! asset($case_deals_impact_image) !!}" alt="Case Deals" title="Case Deals">
				</p>
			</div>
		</div>

		<div class="row cases-category-cta">
			@foreach($cases as $case)
				@php
				$casePicExist=false;
				if($case->CasePic){
					$case->CasePic=str_replace(array("/","\\"),"/",$case->CasePic);
					$setCasePic=public_path($case->CasePic);
					$casePicExist=file_exists($setCasePic);
				}
				@endphp
			<div class="col-sm-4 col-12 cat-link-container">
				<a class="cat-link" href="#deal-{{$case->CaseId}}">
					<code>
						@if($casePicExist)
							@php
							$case->CasePic=str_replace(array("/","\\"),"/",$case->CasePic);
							$showPic=asset("$case->CasePic");
							@endphp					
						<img src="{{$showPic}}" alt="{{$case->CaseName}}" />
						@else
						<img src="{!! asset('images/default_casePic.jpeg') !!}" alt="{{$case->CaseName}}" />
						@endif
					</code>
				</a>
			</div>
			@endforeach
		</div>

		<div class="row mt-4">
			<div class="col-12 category-case-deals">
				@foreach($topics as $caseId=>$caseContent)
				<div id="deal-{{$caseId}}" class="mt-3">
					<div class="category-title">
						<h2>{{$caseContent['title']}}</h2>
			    	</div>
			    </div>
			    <div class="category-products row">
			    	<ol class="products-list col-12" id="products-list">
			    	@foreach($caseContent['contents'] as $topic)
						@php
						$winePicExist=false;
						if($topic['image']){
							$setWinePic=public_path($topic['image']);
							$winePicExist=file_exists($setWinePic);
						}
						$winePic=asset(rawurlencode($topic['image']));
						@endphp
						<li class="item">
							<a href="/case/{{$topic['id']}}" title="{{$topic['name']}}" class="product-image">
                        		<div class="crop">
									@if($winePicExist)
									<img src={{$winePic}} alt="{{$topic['name']}}" class="img-fluid">
									@else
									<img src="{!! asset('images/DefaultImages/DefaultCase.png') !!}" alt="{{$topic['name']}}" class="img-fluid">
									@endif									
                        		</div>
                        	</a>
                        	<div class="product-shop">
                        		<div class="f-fix">
                        			<div class="product-primary">
                        				<h2 class="product-name">
                        					<a href="/case/{{$topic['id']}}" title="{{$topic['name']}}">{{$topic['name']}}</a>
                        				</h2>
                        				<div class="desc std">
                        					<div class="product-short-description">{{mb_substr($topic['briefdesc'],0,150)}} ... </div>
                        					<div class="mobile-readmore-link">
                        						<a href="/case/{{$topic['id']}}" title="{{$topic['name']}}" class="link-learn">Read More</a>
                        					</div>
                        				</div>
                        			</div>
                        			<div class="product-secondary">
                        				<div class="price-box">
                        					@if($topic['option']))
                        					<span class="regular-price">
                        						<span class="rrpsave"></span>
                        						<span class="mixed-price">
                        							<span class="price">${{$topic['option']->Price}}</span>
                        							<span class="mixed-label">{{$LANGUAGE==='EN'?'in mixed '.$topic['option']->PurchaseAmount.'+':'當次购买'.$topic['option']->PurchaseAmount.'瓶以上'}}</span>
                        						</span>
                        						<span class="main-product-price has-case-price">{{$LANGUAGE==='EN'?'or':'或'}}
                        							<span class="price">@if($topic['s_price']){{'$'.number_format($topic['s_price'],2)}}@else{{'$'.number_format($topic['price'],2)}}@endif</span>
                        							<span class="item-price-label">{{$LANGUAGE==='EN'?'per pack':'每组'}}</span>
                        						</span>
                        					</span>
                        					@else
                        					<span class="regular-price">
                        						<span class="rrpsave"></span>
                        						<span class="mixed-price">
                        							<span class="price">@if($topic['s_price']){{'$'.number_format($topic['s_price'],2)}}@else{{'$'.number_format($topic['price'],2)}}@endif</span>
                        							<span class="mixed-label">{{$LANGUAGE==='EN'?'per pack':'每组'}}</span>
                        						</span>
                        					</span>                        					
                        					@endif
                        				</div>
                        				<div class="action">
                        					<div class="qty-box">
                        						<label for="qty_mix_{{$topic['id']}}">{{$LANGUAGE==='EN'?'Qty':'数量'}}</label>
                        						<input type="number" name="qty" id="qty_mix_{{$topic['id']}}" maxlength="12" value="1" min="0" step="1" title="Qty" class="input-text qty">
                        					</div>
                        					<button type="button" title="Add to Order" class="button btn-cart" onclick="quick_addItem('mix','{{$topic['id']}}')">
                        						<span>
                        							<span>{{$LANGUAGE==='EN'?'Add to Cart':'加入购物车'}}</span>
                        						</span>
                        					</button>
                        				</div>
                        			</div>
                        		</div>
                        	</div>
	                    </li>
					@endforeach
					</ol>
				</div>
				@endforeach
			</div>
		</div>
	</div>
</div>
@endsection