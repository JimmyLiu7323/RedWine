@extends('frontend.layouts._main')
@section('content')
<div class="site-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <span class="icon-check_circle display-3 text-success"></span>
                <h2 class="display-3 text-black font-heading-serif">{{$LANGUAGE==='EN'?'Finished':'完成购买'}}</h2>
                @if($LANGUAGE==='EN')
                <p class="lead mb-5">Payment completed</p>
                <p><a href="/wines" class="btn btn-md height-auto btn-primary">Back to store</a></p>
                @else
                <p class="lead mb-0">付款完成</p>
                @if($warning_msg!=='')
                <p class="lead text-warning mb-0">{{$warning_msg}}</p>
                @endif
                <p class="mt-5"><a href="/wines" class="btn btn-md height-auto btn-primary">回到商店</a></p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection