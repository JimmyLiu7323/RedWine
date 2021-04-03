@extends('frontend.layouts._main')
@section('content')
<div class="mt-3 mb-3">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-12">
                <div class="pb-4 pt-4 custom-sidebar">
                    <h3 class="mt-0 mb-4 little-sidebar-title">
                        <span class="sidebar-arrow icon-arrow-circle-right" onclick="toggleShrink(this)"></span> {{$LANGUAGE==='EN'?'my account':'帐号总览'}}
                    </h3>
                    <ul class="shrink-list">
                        <li class="active">{{$LANGUAGE==='EN'?'account dashboard':'帐号大纲'}}</li>
                        <li>
                            <a href="/account/edit">{{$LANGUAGE==='EN'?'maintain account':'维护我的帐号'}}</a>
                        </li>
                        <li>
                            <a href="/address">{{$LANGUAGE==='EN'?'address book':'地址库'}}</a>
                        </li>
                        <!-- <li> -->
                            <!-- <a href="/sales/orders">{{$LANGUAGE==='EN'?'my orders':'我的订单'}}</a> -->
                        <!-- </li> -->
                        <li>
                            <a href="/wishlist/">{{$LANGUAGE==='EN'?'my wishlist':'愿望清单'}}</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-9 col-sm-12">
                <div class="row">
                    <div class="col-sm-12">
                        <h2 class="mb-1 page-title">{{$LANGUAGE==='EN'?'newsletter subscription':'订阅最新消息'}}</h2>
                    </div>
                </div>
                <form method="POST" style="width:100%" class="mt-3">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="FollowingNews" class="text-black">
                                <input type="checkbox" id="FollowingNews" name="FollowingNews" value=1 {{old('FollowingNews',$Subscribed)==1?'checked=true':''}}> {{$LANGUAGE==='EN'?'Latest newsletter':'最新消息'}}
                            </label>
                        </div>
                    </div>
                    <hr>
                    <input type="hidden" value="{{csrf_token()}}" name="_token" />
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <a href="/account" class="btn btn-default"><span class="icon-arrow-left"></span> {{$LANGUAGE==='EN'?'Back':'返回'}}</a>
                        </div>
                        <div class="col-sm-6 text-right">
                            <button type="submit" class="btn btn-primary">{{$LANGUAGE==='EN'?'Save':'储存'}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection