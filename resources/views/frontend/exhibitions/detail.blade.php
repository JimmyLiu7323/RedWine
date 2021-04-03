@extends('frontend.layouts._main')

@section('metas')
@if($detailInfo->BriefDesc!='')
<meta name="description" content="{{$detailInfo->BriefDesc}}"/>
<meta property="og:description" content="{{$detailInfo->BriefDesc}}" />
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
<meta property="og:title" content="{{$DCs['DC_Title']}} - {{$detailInfo->EventName}}" />
@php
$impactImageExist=false;
if($detailInfo->ImpactImage){
	$setImpactImage=public_path($detailInfo->ImpactImage);
	$impactImageExist=file_exists($setImpactImage);
}
@endphp

@if($impactImageExist)
<meta property="og:image" content="{!! asset($detailInfo->ImpactImage) !!}" />
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
			@if($impactImageExist)
			<div class="col-sm-3 col-12 exhibition-detail-image">
				<img src={!! asset($detailInfo->ImpactImage) !!} alt="Impact image of {{$detailInfo->EventName}}" title="Impact image of {{$detailInfo->EventName}}" class="img-fluid" />
			</div>
			@endif

			@if($impactImageExist)
			<div class="col-sm-9 col-12">
			@else
			<div class="col-12">
			@endif
				<h2 class="exhibition-title">{{$detailInfo->EventName}}</h2>
				<div class="exhibition-info-container">
					<div class="exhibition-description">{!! $detailInfo->Description !!}</div>
					<ul class="info-list mt-2 mb-2">
						<li class="mb-2">{{$LANGUAGE==='EN'?'Price':'所需金額'}}: {{intval($detailInfo->Free)===1?'Free':'$'.number_format($detailInfo->Price,2)}}</li>
						<li class="mb-2">{{$LANGUAGE==='EN'?'All Capacity':'可容納人數'}}: {{$detailInfo->AllCapacity}}
							<ul>
								<li>{{$LANGUAGE==='EN'?'Standing Capacity':'可站立空間'}}: {{$detailInfo->StandingCapacity}}</li>
								<li>{{$LANGUAGE==='EN'?'Seatings Capacity':'座位數'}}: {{$detailInfo->SeatingCapacity}}</li>
							</ul>
						</li>
						<li class="mb-2">{{$LANGUAGE==='EN'?'Start from':'開始時間'}}: {{date('m.d.Y',strtotime($detailInfo->EventDateTime_Start))}} {{date('H:i',strtotime($detailInfo->EventDateTime_Start))}}</li>
						<li class="mb-2">{{$LANGUAGE==='EN'?'End at':'結束時間'}}: {{date('m.d.Y',strtotime($detailInfo->EventDateTime_End))}} {{date('H:i',strtotime($detailInfo->EventDateTime_End))}}</li>
					</ul>
					@if(\Session::has('MemberId'))
					<form method="POST" action="/exhibition/{{$detailInfo->EventId}}/Booking">
						<div class="row">
							@if(!$detailInfo->Free)
							<h5 class="col-12">{{$LANGUAGE==='EN'?'You can booking a reservation here, system will redirect to payment page.':'完成預約後會為您導向付款頁面'}}</h5>
							@else
							<h5 class="col-12">{{$LANGUAGE==='EN'?'You can booking a reservation here.':'您可于此处进行预约'}}</h5>
							@endif
							<div class="col-sm-6 col-12">
								<div class="row">
									<div class="col-4">
										<label for="bookingQty" style="display:none">Qty</label>
										<h6 class="h6 control-label">{{$LANGUAGE==='EN'?'People':'預約幾位'}}</h6>
									</div>
									<div class="col-8">
										<input type="number" name="Qty" id="bookingQty" class="form-control" maxlength="12" value=1 title="Qty" min=1 step=1 />
									</div>
								</div>
							</div>
							<input type="hidden" value="{{csrf_token()}}" name="_token" />
							@if(!$detailInfo->Free)
							<div class="col-sm-6 col-12 mt-sm-0 mt-3">
								<div class="row">
									<div class="col-4">
										<h6 class="h6 control-label">{{$LANGUAGE==='EN'?'Payment':'付费方式'}}</h6>
									</div>
									<div class="col-8">
										<select class="form-control" name="Paymethod">
											<option value="Card">{{$LANGUAGE==='EN'?'Card':'刷卡'}}</option>
											<option value="QRCode_Alipay">QRCode (Alipay)</option>
											<option value="QRCode_WechatPay">QRCode (WeChatPay)</option>
										</select>
									</div>
								</div>
							</div>
							@endif
							<div class="col-12 mt-3">							
								<button type="submit" class="btn-primary btn btn-block">{{$LANGUAGE==='EN'?'Booking':'預約'}}</button>
							</div>
						</div>
					</form>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
@endsection