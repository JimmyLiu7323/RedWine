@extends('frontend.layouts._main')
@section('content')
<div class="site-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <span class="icon-error_outline display-3 text-danger"></span>
                <h2 class="display-3 text-black font-heading-serif">Oops...</h2>
                @if($LANGUAGE==='EN')
                <p class="lead mb-0">The flow of payment failed...</p>
                <p class="lead mb-5">Try again please.</p>
                <p><a href="/wines" class="btn btn-md height-auto btn-primary">Back to store</a></p>
                @else
                <p class="lead mb-0">付款流程失败...</p>
                <p class="lead mb-5">请重试。</p>
                <p><a href="/wines" class="btn btn-md height-auto btn-primary">回到商店</a></p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection