@extends('frontend.layouts._main')
@section('content')
<div class="site-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <span class="icon-check_circle display-3 text-success"></span>
                <h2 class="display-3 text-black font-heading-serif">{{$LANGUAGE==='EN'?'Finished':'完成'}}</h2>
                @if($LANGUAGE==='EN')
                <p class="lead mb-3">Scan QR Code below for paying.</p>
                @else
                <p class="lead mb-3">请扫描以下二维条码进行缴款。</p>
                @endif
                <p class="lead mt-0 mb-3">{{$LANGUAGE==='EN'?'This is a one-time page, please save it.':'此为一次性页面，请自行保存。'}}</p>
                <p>
                    <img src="{{$QRCodePic}}" class="img-fluid" alt="QRCode Payment" title="QRCode Payment" />
                </p>
                <p><a href="/" class="btn btn-md height-auto btn-primary">{{$LANGUAGE==='EN'?'Back to home':'回到首页'}}</a></p>
            </div>
        </div>
    </div>
</div>
@endsection