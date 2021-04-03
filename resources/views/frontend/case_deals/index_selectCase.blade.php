@extends('frontend.layouts._main')

@section('metas')
@if($cases[0]->MetaDesc)
<meta name="description" content="{{$cases[0]->MetaDesc}}"/>
<meta property="og:description" content="{{$cases[0]->MetaDesc}}" />
@elseif($METAs['META_DESCRIPTION']!=='')
<meta name="description" content="{{$METAs['META_DESCRIPTION']}}"/>
<meta property="og:description" content="{{$METAs['META_DESCRIPTION']}}" />
@endif
@if($cases[0]->MetaKeywords!=='')
<meta name="keywords" content="{{$cases[0]->MetaKeywords}}"/>
@elseif($METAs['META_KEYWORDS']!=='')
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
		<div class="row">
			<div class="col-12">				
				<div class="condition-line mt-3">
					<div class="row">
						<div class="col-12">
							<form id="whereForm" style="width:100%">
								<div class="row">
									<div class="col-md-6 col-12">
										<div class="row">
											<div class="col-6">
												<div class="orderBySelector text-left row">
													<div class="col-md-3 col-4 text-left">{{$LANGUAGE==='EN'?'SORT':'排序'}}</div>
													<div class="col-md-9 col-8">
														<select id="orderBy" class="form-control" name="order" onchange='changeCondition()'>
															<option value="price" {{$order=='price'?'selected=true':''}}>{{$LANGUAGE==='EN'?'Price':'金额'}}</option>
															<option value="name" {{$order=='name'?'selected=true':''}}>{{$LANGUAGE==='EN'?'Name':'名称'}}</option>
															<option value="stocks" {{$order=='stocks'?'selected=true':''}}>{{$LANGUAGE==='EN'?'Stocks':'库存'}}</option>
															<option value="created_at" {{$order=='created_at'?'selected=true':''}}>{{$LANGUAGE==='EN'?'Latest':'上架时间'}}</option>
														</select>
													</div>
												</div>
											</div>
											<div class="col-6">
												@if($dir=='asc')
												<a class="dirLink" href="?order={{$order}}&limit={{$takeItems}}&dir=desc">
													<span class="icon-arrow-up"></span>
												</a>
												@else
												<a class="dirLink" href="?order={{$order}}&limit={{$takeItems}}&dir=asc">
													<span class="icon-arrow-down"></span>
												</a>												
												@endif
												<input type="hidden" name="dir" value="{{$dir}}" />
											</div>
										</div>
									</div>
									<div class="col-md-6 col-12 rightTools">
										<div class="row">
											<div class="col-6">
												<div class="currentItems text-right">
													@if(is_numeric($allTopics)&&is_numeric($takeItems))
														@if($allTopics>0)
															@if($allTopics<$takeItems)
													<p class="mt-0 mb-0">{{1+($page-1)*$takeItems}}-{{$allTopics}} OF {{$allTopics}}</p>
															@else
													<p class="mt-0 mb-0">{{1+($page-1)*$takeItems}}-{{($page-1)*$takeItems+$takeItems}} OF {{$allTopics}}</p>
															@endif
														@else
													<p class="mt-0 mb-0">0-0 OF 0</p>														
														@endif
													@endif
												</div>
											</div>
											<div class="col-6">
												<div class="showItemsSelector text-right row">
													<div class="col-md-3 col-4 text-left">{{$LANGUAGE==='EN'?'SHOW':'显示'}}</div>
													<div class="col-md-9 col-8">
														<select id="showItems" class="form-control" name="limit" onchange='changeCondition()'>
															<option value="5" {{$takeItems==5?'selected=true':''}}>5 {{$LANGUAGE==='EN'?'items':'笔'}}</option>
															<option value="10" {{$takeItems==10?'selected=true':''}}>10 {{$LANGUAGE==='EN'?'items':'笔'}}</option>
															<option value="15" {{$takeItems==15?'selected=true':''}}>15 {{$LANGUAGE==='EN'?'items':'笔'}}</option>
															<option value="20" {{$takeItems==20?'selected=true':''}}>20 {{$LANGUAGE==='EN'?'items':'笔'}}</option>
															<option value="25" {{$takeItems==25?'selected=true':''}}>25 {{$LANGUAGE==='EN'?'items':'笔'}}</option>
															<option value="all" {{$takeItems=='all'?'selected=true':''}}>{{$LANGUAGE==='EN'?'All':'全部'}}</option>
														</select>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
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
				@if($takeItems!=='all'&&$topicsQuery->count()>0)
				{{ $topicsQuery->appends(request()->query())->links() }}
				@endif				
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
function changeCondition(){
	var whereForm=document.getElementById('whereForm');
	whereForm.submit();
}
</script>
@endsection