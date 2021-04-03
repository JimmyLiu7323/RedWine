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
<meta property="og:image" content="{!! asset($impactImage) !!}" />
@endsection

@section('content')
<div class="mb-5">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="exhibition-impact-image-container">
					<img src="{!! asset($impactImage) !!}" alt="Impact Image" title="Impact Image" />
				</div>
			</div>
		</div>
		@if(isset($breadcrumbs))
		<div class="row mt-3 mb-3">
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
		<hr />
		@endif
		<div class="row">
		@foreach($exhibitions as $exhibition)
			<a href="/exhibition/{{$exhibition->EventId}}" class="col-12 exhibition-link mb-4">
				<div class="exhibition-container">
					<div class="row">
						<div class="col-md-3 col-12 mb-md-0 mb-4">
							<div class="exhibition-img-container">
								<img src="{!! asset($exhibition->ImpactImage) !!}" alt="{{$LANGUAGE==='EN' && trim($exhibition->EventNameEn)!=='' ? $exhibition->EventNameEn:$exhibition->EventName}}" />
							</div>
						</div>
						<div class="col-md-9 col-12">
							<h4 class="mt-0 mb-0 exhibition-title">{{$LANGUAGE==='EN' && trim($exhibition->EventNameEn)!=='' ? $exhibition->EventNameEn:$exhibition->EventName}}</h4>
							@php
							$showBrief = $exhibition->BriefDesc;
							if($LANGUAGE==='EN' && trim($exhibition->BriefDescEn)!==''){
								$showBrief = $exhibition->BriefDescEn;
							}

							if(mb_strlen($showBrief)>150){
								$showBrief = mb_substr($showBrief,0,150)."...";
							}
							@endphp
							<p class="mt-2 mb-3 exhibition-brief-desc">{!! $showBrief !!}</p>
							<div class="exhibition-info">
								<div class="row">
									<div class="col-4">
										<p class="mt-0 mb-0">
											<i class="far fa-calendar-alt"></i>&nbsp;&nbsp;{{date('m.d.Y',strtotime($exhibition->EventDateTime_Start))}}
										</p>
										<p class="mt-2 mb-0">
											<i class="far fa-clock"></i>&nbsp;&nbsp;{{date('H:i',strtotime($exhibition->EventDateTime_Start))}}
										</p>
									</div>
									<div class="col-8">
										<p class="mt-0 mb-0">{{$LANGUAGE==='EN'?'Standing Capacity':'可站立空间'}} {{$exhibition->StandingCapacity}}</p>
										<p class="mt-2 mb-0">{{$LANGUAGE==='EN'?'Seatings Capacity':'座位数'}} {{$exhibition->SeatingCapacity}}</p>
									</div>
								</div>
								<div class="row mt-3">
									<div class="col-sm-6 col-12">
										<div class="exhibition-price-box">
											<div class="row">
												<div class="col-6">
													<p class="mt-0 mb-0 exhibition-info-label">{{$LANGUAGE==='EN'?'Price':'所需金额'}}</p>
													<p class="mt-2 mb-0 exhibition-info-content">{{intval($exhibition->Free)===1?'Free':'$'.number_format($exhibition->Price,2)}}</p>
												</div>
												<div class="col-6">
													<p class="mt-0 mb-0 exhibition-info-label">{{$LANGUAGE==='EN'?'All Capacity':'可容纳人数'}}</p>
													<p class="mt-2 mb-0 exhibition-info-content">{{$exhibition->AllCapacity	}}</p>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</a>
		@endforeach
		</div>
		@if($exhibitions->count()>0)
		<div class="row">
			<div class="col-12">{{ $exhibitions->appends(request()->query())->links() }}</div>
		</div>
		@endif
	</div>
</div>
@endsection