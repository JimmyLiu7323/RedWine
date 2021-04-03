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
		<div class="row">
			<div class="col-md-3">
				<div class="side-filter-bar">
					<h3 class="filter-link-title">{{$LANGUAGE==='EN'?'Countries':'国家'}}</h3>
					<ul class="filter-link-list">
					@foreach($country_links as $shortKey=>$countryName)
						<li>
							<a href="/by-country/{{$shortKey}}">{{$countryName}}</a>
						</li>
					@endforeach
					</ul>
					<h3 class="filter-link-title">
						<span>{{$LANGUAGE==='EN'?'filter':'筛选器'}}</span>
						@if(count($set_filter)>0)
						<p id="clear-filter"><a href="{{\Request::path()}}" class="btn-sm btn-danger">CLEAR</a></p>
						@endif
					</h3>
					@if($filter_style->count()>0)
					<div class="filter-box">
						<p class="filter-title icon-arrow_drop_down">{{$LANGUAGE==='EN'?'style':'风格筛选'}}</p>
						<div class="filter-scroll">
							<ul>
							@php
							$style_set_filter=array();
							$init_link="/by-country";
							if($countryShort!==''){
								$init_link.="/$countryShort";
							}
							$tempFilter=$set_filter;
							unset($tempFilter['style']);
							if(count($tempFilter)>0)
								$init_link.="?";
							@endphp

							@foreach($filter_style as $style)
								@php
								$link=$init_link;
								if(count($set_filter)>0){
									foreach($set_filter as $key=>$val){
										if($key!="style"){
											$link.="$key=$val&";
										}
									}
									$link=substr($link,0,strlen($link)-1);							
									if(isset($set_filter['style'])){
										$style_set_filter=explode(",",$set_filter['style']);
										$tempFilterStyle=str_replace(
											array(
												  $style->StyleId.",",
												  ",".$style->StyleId,
												  $style->StyleId
											),
											array("","",""),
											$set_filter['style']
										);
										if(trim($tempFilterStyle)!=""){
											if($link=="/by-country")
												$link.="?style=";
											else
												$link.="&style=";
											$link.=$tempFilterStyle;
											if(strpos((string)$set_filter['style'],(string)$style->StyleId)===false){
												$link.=",".$style->StyleId;
											}
										}
									}
								}
								else{
									$link.="?style=".$style->StyleId;
								}
								@endphp
								<li>
									<a data-param="{{$link}}" href="{{$link}}" @if(in_array($style->StyleId,$style_set_filter)) class='active' @endif>
										@if($LANGUAGE==='EN'&&trim($style->StyleEn)!==''){{$style->StyleEn}}@else{{$style->Style}}@endif <span class="count">({{$style->count_wines}})</span>
									</a>
								</li>								
							@endforeach
							</ul>
						</div>
					</div>
					@endif
					@if($filter_variety->count()>0)
					<div class="filter-box">
						<p class="filter-title icon-arrow_drop_down">{{$LANGUAGE==='EN'?'variety':'品种筛选'}}</p>
						<div class="filter-scroll">
							<ul>
							@foreach($filter_variety as $variety)
								<li>
									<a data-param="{{$init_link}}?variety={{$variety->VarietyId}}" href="{{$init_link}}?variety={{$variety->VarietyId}}">
										@if($LANGUAGE==='EN'&&trim($variety->VarietyEn)!==''){{$variety->VarietyEn}}@else{{$variety->Variety}}@endif <span class="count">({{$variety->count_wines}})</span>
									</a>
								</li>
							@endforeach
							</ul>
						</div>
					</div>
					@endif
					@if(count($filter_countries)>0)
					<div class="filter-box">
						<p class="filter-title icon-arrow_drop_down">{{$LANGUAGE==='EN'?'country':'国家筛选'}}</p>
						<div class="filter-scroll">
							<ul>
							@php
							$country_set_filter=array();
							$init_link="/by-country";
							if($countryShort!==''){
								$init_link.="/$countryShort";
							}
							$tempFilter=$set_filter;
							unset($tempFilter['country']);
							if(count($tempFilter)>0)
								$init_link.="?";
							@endphp
							@foreach($filter_countries as $country)
								@php
								$link=$init_link;
								if(count($set_filter)>0){
									foreach($set_filter as $key=>$val){
										if($key!="country"){
											$link.="$key=$val&";
										}
									}
									$link=substr($link,0,strlen($link)-1);
									if(isset($set_filter['country'])){
										$country_set_filter=explode(",",$set_filter['country']);
										$tempFilterCountry=str_replace(
											array(
												  $country['id'].",",
												  ",".$country['id'],
												  $country['id']
											),
											array("","",""),
											$set_filter['country']
										);
										if(trim($tempFilterCountry)!=""){
											if($link=="/by-country")
												$link.="?country=";
											else
												$link.="&country=";
											$link.=$tempFilterCountry;
											if(strpos($set_filter['country'],$country['id'])===false)
												$link.=",".$country['id'];
										}
									}
									else{
										$link.="&country=".$country['id'];
									}
								}
								else{
									$link.="?country=".$country['id'];
								}
								@endphp
								<li>
									<a data-param="{{$link}}" href="{{$link}}" @if(in_array($country['id'],$country_set_filter)) class='active' @endif>
										{{$country['name']}} <span class="count">({{$country['count_wines']}})</span>
									</a>
								</li>
							@endforeach
							</ul>
						</div>
					</div>
					@endif
					@if($filter_regions->count()>0)
					<div class="filter-box">
						<p class="filter-title icon-arrow_drop_down">{{$LANGUAGE==='EN'?'region':'地区筛选'}}</p>
						<div class="filter-scroll">
							<ul>
							@php
							$region_set_filter=array();
							$init_link="/by-country";
							if($countryShort!==''){
								$init_link.="/$countryShort";
							}
							$tempFilter=$set_filter;
							unset($tempFilter['region']);
							if(count($tempFilter)>0)
								$init_link.="?";
							@endphp
							@foreach($filter_regions as $region)
								@php
								$link=$init_link;
								if(count($set_filter)>0){
									foreach($set_filter as $key=>$val){
										if($key!="region"){
											$link.="$key=$val&";
										}
									}
									$link=substr($link,0,strlen($link)-1);
									if(isset($set_filter['region'])){
										$region_set_filter=explode(",",$set_filter['region']);
										$tempFilterRegion=str_replace(
											array(
												  $region->RegionId.",",
												  ",".$region->RegionId,
												  $region->RegionId
											),
											array("","",""),
											$set_filter['region']
										);
										if(trim($tempFilterRegion)!=""){
											if($link=="/wines")
												$link.="?region=";
											else
												$link.="&region=";
											$link.=$tempFilterRegion;
											if(strpos((string)$set_filter['region'],(string)$region->RegionId)===false)
												$link.=",".$region->RegionId;
										}
									}
									else{
										$link.="&region=".$region->RegionId;
									}
								}
								else{
									$link.="?region=".$region->RegionId;
								}
								@endphp
								<li>
									<a data-param="{{$link}}" href="{{$link}}" @if(in_array($region->RegionId,$region_set_filter)) class='active' @endif>
										@if($LANGUAGE==='EN'&&trim($region->RegionEn)!==''){{$region->RegionEn}}@else{{$region->Region}}@endif <span class="count">({{$region->count_wines}})</span>
									</a>
								</li>
							@endforeach
							</ul>
						</div>
					</div>
					@endif

					<!-- <div class="filter-box">
						<p class="filter-title icon-arrow_drop_down">{{$LANGUAGE==='EN'?'price':'价钱'}}</p>
						<div class="filter-slider">
							<div id="slider-start"></div>
	                        <script type="text/javascript">
	                        let filter_price_min={{$price_range['min']}};
	                        let filter_price_max={{$price_range['max']}};
	                        let filter_price_start={{$price_range['min']}};
	                        let filter_price_end={{$price_range['max']}};
	                        </script>

	                        <div class="price-sets">
	                        	<span id="price-set-start">{{$price_range['min']}}</span>
	                        	<span id="price-set-end">{{$price_range['max']}}</span>
	                        </div>
	                    </div>
					</div> -->
					@if($filter_regions->count()>0)
					<div class="filter-box">
						<p class="filter-title icon-arrow_drop_down">{{$LANGUAGE==='EN'?'region':'地区筛选'}}</p>
						<div class="filter-scroll">
							<ul>
							@php
							$region_set_filter=array();
							$init_link="/by-country";
							if($countryShort!==0){
								$init_link.="/$countryShort";
							}
							$tempFilter=$set_filter;
							unset($tempFilter['region']);
							if(count($tempFilter)>0)
								$init_link.="?";
							@endphp
							@foreach($filter_regions as $region)
								@php
								$link=$init_link;
								if(count($set_filter)>0){
									foreach($set_filter as $key=>$val){
										if($key!="region"){
											$link.="$key=$val&";
										}
									}
									$link=substr($link,0,strlen($link)-1);
									if(isset($set_filter['region'])){
										$region_set_filter=explode(",",$set_filter['region']);
										$tempFilterRegion=str_replace(
											array(
												  $region->RegionId.",",
												  ",".$region->RegionId,
												  $region->RegionId
											),
											array("","",""),
											$set_filter['region']
										);
										if(trim($tempFilterRegion)!=""){
											if($link=="/by-country")
												$link.="?region=";
											else
												$link.="&region=";
											$link.=$tempFilterRegion;
											if(strpos((string)$set_filter['region'],(string)$region->RegionId)===false)
												$link.=",".$region->RegionId;
										}
									}
									else{
										$link.="&region=".$region->RegionId;
									}
								}
								else{
									$link.="?region=".$region->RegionId;
								}
								@endphp
								<li>
									<a data-param="{{$link}}" href="{{$link}}" @if(in_array($region->RegionId,$region_set_filter)) class='active' @endif>
										@if($LANGUAGE==='EN'&&trim($region->RegionEn)!==''){{$region->RegionEn}}@else{{$region->Region}}@endif <span class="count">({{$region->count_wines}})</span>
									</a>
								</li>
							@endforeach
							</ul>
						</div>
					</div>
					@endif

					<!-- <div class="filter-box">
						<p class="filter-title icon-arrow_drop_down">{{$LANGUAGE==='EN'?'price':'价钱'}}</p>
						<div class="filter-slider">
							<div id="slider-start"></div>
	                        <script type="text/javascript">
	                        let filter_price_min={{$price_range['min']}};
	                        let filter_price_max={{$price_range['max']}};
	                        let filter_price_start={{$price_range['min']}};
	                        let filter_price_end={{$price_range['max']}};
	                        </script>

	                        <div class="price-sets">
	                        	<span id="price-set-start">{{$price_range['min']}}</span>
	                        	<span id="price-set-end">{{$price_range['max']}}</span>
	                        </div>
	                    </div>
					</div> -->
				</div>				
			</div>
			<div class="col-md-9 col-sm-12">
				@if($showImpact!=='')
					@if(isset($countryInfo) && trim($countryInfo->CountryDesc)!=='' && trim($countryInfo->CountryMainImage)!=='' )
						@php
						$StlyMainImage = '';
						if($countryInfo->CountryMainImage){
		                    $findFile = str_replace(array("\\","/"),DIRECTORY_SEPARATOR,$countryInfo->CountryMainImage);
		                    if(file_exists(public_path($findFile))){
		                        $CountryMainImage = $findFile;
		                    }
		                }
						@endphp
						@if($CountryMainImage!=='')
				<div class="row category-intro-container">
					<div class="category-intro-desc col-md-9 col-12">
						<div class="page-title category-title">
                        	<h2>{{$LANGUAGE==='EN'&&trim($countryInfo->CountryEn)!=='' ? $countryInfo->CountryEn : $countryInfo->Country}}</h2>
                    	</div>
                    	<div class="category-description std d-none d-sm-block">
                			<p class="p1">
                				<span class="s1">{!! $LANGUAGE==='EN' && trim($countryInfo->CountryDescEn)!=='' ? $countryInfo->CountryDescEn : $countryInfo->CountryDesc !!}</span>
                			</p>
                		</div>
                	</div>
                	<div class="category-intro-img col-3 d-none d-sm-block">
                		<p class="category-image">
                			<img src="{!! asset($countryInfo->CountryMainImage) !!}" alt="{{$LANGUAGE==='EN'&&trim($countryInfo->CountryEn)!=='' ? $countryInfo->CountryEn : $countryInfo->Country}}" title="{{$LANGUAGE==='EN'&&trim($countryInfo->CountryEn)!=='' ? $countryInfo->CountryEn : $countryInfo->Country}}">
                		</p>
                	</div>
                </div>                	
                		@else
					<div class="row category-intro-container">
						<div class="category-intro-desc col-sm-9 col-12">
							<div class="page-title category-title">
	                        	<h2>{{$LANGUAGE==='EN'&&trim($countryInfo->CountryEn)!=='' ? $countryInfo->CountryEn : $countryInfo->Country}}</h2>
	                        </div>
	                    </div>
	                </div>                 		
                		@endif
                	@else
					<div class="row category-intro-container">
						<div class="category-intro-desc col-sm-9 col-12">
							<div class="page-title category-title">
	                        	<h2>{{$LANGUAGE==='EN'&&trim($countryInfo->CountryEn)!=='' ? $countryInfo->CountryEn : $countryInfo->Country}}</h2>
	                        </div>
	                    </div>
	                </div>                 	
                	@endif
                @else
				<div class="row category-intro-container">
					<div class="category-intro-desc col-sm-9 col-12">
						<div class="page-title category-title">
                        	<h2>{{$LANGUAGE==='EN'?'By country':'浏览不同国家的酒'}}</h2>
                        </div>
                    </div>
                </div>                
				@endif
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
                        	<a href="/wine/{{$wine->WineId}}" title="{{$LANGUAGE==='EN'&&trim($wine->NameEn)!==''?$wine->NameEn:$wine->Name}}" class="product-image">
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
                        					<a href="/wine/{{$wine->WineId}}" title="{{$LANGUAGE==='EN'&&trim($wine->NameEn)!==''?$wine->NameEn:$wine->Name}}">{{$LANGUAGE==='EN'&&trim($wine->NameEn)!==''?$wine->NameEn:$wine->Name}}</a>
                        				</h2>
                        				<div class="desc std">
                                			@if($LANGUAGE==='EN'&&trim($wine->BriefDescEn)!=='')
                        					<div class="product-short-description">{{mb_substr($wine->BriefDescEn,0,150)}} ... </div>
                        					@elseif(trim($wine->BriefDesc)!=='')
                        					<div class="product-short-description">{{mb_substr($wine->BriefDesc,0,150)}} ... </div>
                        					@endif
                        					<div class="mobile-readmore-link">
                        						<a href="/wine/{{$wine->WineId}}" title="{{$LANGUAGE==='EN'&&trim($wine->NameEn)!==''?$wine->NameEn:$wine->Name}}" class="link-learn">Read More</a>
                        					</div>
                        				</div>
                        			</div>
                        			<div class="product-secondary">
                        				<div class="price-box">
                        					@if(isset($wine->Option))
                        					<span class="regular-price">
                        						<span class="rrpsave"></span>
                        						<span class="mixed-price">
                        							<span class="price">${{$wine->Option->Price}}</span>
                        							<span class="mixed-label">{{$LANGUAGE==='EN'?'in mixed '.$wine->Option->PurchaseAmount.'+':'當次购买'.$wine->Option->PurchaseAmount.'瓶以上'}}</span>
                        						</span>
                        						<span class="main-product-price has-case-price">{{$LANGUAGE==='EN'?'or':'或'}}
                        							<span class="price">@if($wine->S_price){{'$'.number_format($wine->S_price,2)}}@else{{'$'.number_format($wine->Price,2)}}@endif</span>
                        							<span class="item-price-label">{{$LANGUAGE==='EN'?'per bottle':'每瓶'}}</span>
                        						</span>
                        					</span>
                        					@else
                        					<span class="regular-price">
                        						<span class="rrpsave"></span>
                        						<span class="mixed-price">
                        							<span class="price">@if($wine->S_price){{'$'.number_format($wine->S_price,2)}}@else{{'$'.number_format($wine->Price,2)}}@endif</span>
                        							<span class="mixed-label">{{$LANGUAGE==='EN'?'per bottle':'每瓶'}}</span>
                        						</span>
                        					</span>                        					
                        					@endif
                        				</div>
                        				<div class="action">
                        					<div class="qty-box">
                        						<label for="qty_{{$wine->WineId}}">{{$LANGUAGE==='EN'?'Qty':'数量'}}</label>
                        						<input type="number" name="qty" id="qty_wine_{{$wine->WineId}}" maxlength="12" value="1" min="0" step="1" title="Qty" class="input-text qty">
                        					</div>
                        					<button type="button" title="Add to Order" class="button btn-cart" onclick="quick_addItem('wine','{{$wine->WineId}}')">
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