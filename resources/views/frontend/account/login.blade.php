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
<div class="site-section">
    <div class="container">
        <div class="row">
            <div class="col-md-6 mb-5 mb-md-0">
                <h2 class="h3 text-black font-heading-serif">{{$LANGUAGE==='EN'?'Didn\'t have an account?':'尚未拥有帐号'}}</h2>
                <div class="bg-light rounded p-2 mb-2">
                    <p class="mb-0">{{$LANGUAGE==='EN'?'Registration is easy and quick!':'注册就是这么简单!'}}</p>
                </div>
                <div class="p-3 p-lg-5 border">
                    <ul class="account-notices-list">
                        <li>
                            <span class="icon-star"></span>
                            <span>{{$LANGUAGE==='EN'?'Faster checkout':'结帐更快速'}}</span>
                        </li>
                        <li>
                            <span class="icon-star"></span>
                            <span>{{$LANGUAGE==='EN'?'Save multiple shipping address':'个人专属地址库'}}</span>
                        </li>
                        <li>
                            <span class="icon-star"></span>
                            <span>{{$LANGUAGE==='EN'?'Get free coupons':'免费折扣券'}}</span>
                        </li>
                    </ul>
                    <a href="/account/register" class="btn btn-primary btn-block">{{$LANGUAGE==='EN'?'BECOME A MEMBER':'注册'}}</a>
                </div>
            </div>
            <div class="col-md-6">
                <h2 class="h3 text-black font-heading-serif">{{$LANGUAGE==='EN'?'Already have an account?':'已经拥有帐号?'}}</h2>
                <div class="bg-light rounded p-2 mb-2">
                    <p class="mb-0">{{$LANGUAGE==='EN'?'You can login here!':'在这里可以登入!'}}</p>
                </div>
                <form method="POST">
                    <div class="p-3 p-lg-5 border">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <input type="email" class="form-control" name="email" placeholder="Email" value="{{Cookie::get('cookie_email')}}"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <input type="password" class="form-control" name="password" placeholder="{{$LANGUAGE==='EN'?'Password':'请输入密码'}}" />
                            </div>                        
                        </div>
                        <div class="row mt-2">
                            <div class="col-sm-6">
                                <label for="c_remember_me" class="text-black" data-toggle="collapse" href="#remember-notice" role="button" aria-expanded="false" aria-controls="remember-notice">
                                    <input type="checkbox" value="1" id="c_remember_me" name="cookie_remember" {{Cookie::get('cookie_email')!=''?'checked=true':''}}> {{$LANGUAGE==='EN'?'Remember Me':'记住我'}}
                                </label>
                            </div>
                            <div class="col-sm-6 text-right">
                                <a href="/account/findpassword">{{$LANGUAGE==='EN'?'Find Password?':'忘记密码?'}}</a>
                            </div>
                            <div class="col-sm-12">
                                <div class="collapse {{Cookie::get('cookie_email')!=''?'show':''}}" id="remember-notice">
                                    <div class="py-2">
                                        <p class="mb-3">{{$LANGUAGE==='EN'?'Remember your login information for using our site next time.':'记住您的登入资讯以供未来使用'}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" value="{{csrf_token()}}" name="_token" />                   
                        <button type="submit" class="btn-block btn-primary btn">{{$LANGUAGE==='EN'?'Login':'登入'}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection