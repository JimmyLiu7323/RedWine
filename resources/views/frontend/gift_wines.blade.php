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
		<div class="row">
			<div class="col-12">
				<div class="row category-intro-container">
					<div class="category-intro-desc col-sm-9 col-12">
						<div class="page-title category-title">
                        	<h2>{{$LANGUAGE==='EN'?'Gifts':'礼品'}}</h2>
                        </div>
                    </div>
                </div>                
				<div class="row">
					<div class="col-12">				
						<div class="condition-line">
							<div class="row">
								<div class="col-12">
									<form id="whereForm1" style="width:100%">
										<div class="row">
											<div class="col-md-6 col-12">
												<div class="row">
													<div class="col-6">
														<div class="orderBySelector text-left row">
															<div class="col-md-3 col-4 text-left">{{$LANGUAGE==='EN'?'SORT':'排序'}}</div>
															<div class="col-md-9 col-8">
																<select id="orderBy" class="form-control" name="order" onchange='changeCondition(1)'>
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
															@if(is_numeric($allWines)&&is_numeric($takeItems))
																@if($allWines>0)
																	@if($allWines<$takeItems)
															<p class="mt-0 mb-0">{{1+($page-1)*$takeItems}}-{{$allWines}} OF {{$allWines}}</p>
																	@else
															<p class="mt-0 mb-0">{{1+($page-1)*$takeItems}}-{{($page-1)*$takeItems+$takeItems}} OF {{$allWines}}</p>
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
																<select id="showItems" class="form-control" name="limit" onchange='changeCondition(1)'>
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
				<div class="row category-products">
					<ol class="products-list col-12" id="products-list">
					@foreach($wines as $wineIdx=>$wine)
						@php
						$winePicExist=false;
						if($wine->Image){
							$setWinePic=public_path($wine->Image);
							$winePicExist=file_exists($setWinePic);
						}
						@endphp
						<li class="item">
                        	<a href="/gift/{{$wine->GiftId}}" title="{{$LANGUAGE==='EN'&&trim($wine->NameEn)!==''?$wine->NameEn:$wine->Name}}" class="product-image">
                        		<div class="crop">
									@if($winePicExist)
									<img src="{!! asset("$wine->Image") !!}" alt="{{$LANGUAGE==='EN'&&trim($wine->NameEn)!==''?$wine->NameEn:$wine->Name}}">
									@else
									<img src="{!! asset('images/wine_1.png') !!}" alt="{{$LANGUAGE==='EN'&&trim($wine->NameEn)!==''?$wine->NameEn:$wine->Name}}">
									@endif
                        		</div>
                        	</a>
                        	<div class="product-shop">
                        		<div class="f-fix">
                        			<div class="product-primary">
                        				<h2 class="product-name">
                        					<a href="/gift/{{$wine->GiftId}}" title="{{$LANGUAGE==='EN'&&trim($wine->NameEn)!==''?$wine->NameEn:$wine->Name}}">{{$LANGUAGE==='EN'&&trim($wine->NameEn)!==''?$wine->NameEn:$wine->Name}}</a>
                        				</h2>
                        				<div class="desc std">
                                			@if($LANGUAGE==='EN'&&trim($wine->BriefDescEn)!=='')
                        					<div class="product-short-description">{{mb_substr($wine->BriefDescEn,0,150)}} ... </div>
                        					@elseif(trim($wine->BriefDesc)!=='')
                        					<div class="product-short-description">{{mb_substr($wine->BriefDesc,0,150)}} ... </div>
                        					@endif
                        					<div class="mobile-readmore-link">
                        						<a href="/gift/{{$wine->GiftId}}" title="{{$LANGUAGE==='EN'&&trim($wine->NameEn)!==''?$wine->NameEn:$wine->Name}}" class="link-learn">Read More</a>
                        					</div>
                        				</div>
                        			</div>
                        			<div class="product-secondary">
                        				<div class="price-box">
                        					<span class="regular-price">
                        						<span class="rrpsave"></span>
                        						<span class="mixed-price">
                        							<span class="price">@if($wine->S_price){{'$'.number_format($wine->S_price,2)}}@else{{'$'.number_format($wine->Price,2)}}@endif</span>
                        							<span class="mixed-label">{{$LANGUAGE==='EN'?'per bottle':'每瓶'}}</span>
                        						</span>
                        					</span>
                        				</div>
                        				<div class="action">
                        					<div class="qty-box">
                        						<label for="qty_gift_{{$wine->GiftId}}">{{$LANGUAGE==='EN'?'Qty':'数量'}}</label>
                        						<input type="number" name="qty" id="qty_gift_{{$wine->GiftId}}" maxlength="12" value="1" min="0" step="1" title="Qty" class="input-text qty">
                        					</div>
                        					<button type="button" title="Add to Order" class="button btn-cart" onclick="quick_addItem('gift','{{$wine->GiftId}}')">
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
				@if($takeItems!=='all' && $wines->count()>0)
				{{ $wines->appends(request()->query())->links() }}
				@endif
				<div class="row">
					<div class="col-12">				
						<div class="condition-line mt-3">
							<div class="row">
								<div class="col-12">
									<form id="whereForm2" style="width:100%">
										<div class="row">
											<div class="col-md-6 col-12">
												<div class="row">
													<div class="col-6">
														<div class="orderBySelector text-left row">
															<div class="col-md-3 col-4 text-left">{{$LANGUAGE==='EN'?'SORT':'排序'}}</div>
															<div class="col-md-9 col-8">
																<select id="orderBy" class="form-control" name="order" onchange='changeCondition(2)'>
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
															@if(is_numeric($allWines)&&is_numeric($takeItems))
																@if($allWines>0)
																	@if($allWines<$takeItems)
															<p class="mt-0 mb-0">{{1+($page-1)*$takeItems}}-{{$allWines}} OF {{$allWines}}</p>
																	@else
															<p class="mt-0 mb-0">{{1+($page-1)*$takeItems}}-{{($page-1)*$takeItems+$takeItems}} OF {{$allWines}}</p>
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
																<select id="showItems" class="form-control" name="limit" onchange='changeCondition(2)'>
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
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
function changeCondition(formSN){
	var whereForm=document.getElementById('whereForm'+formSN);
	whereForm.submit();
}
</script>
@endsection