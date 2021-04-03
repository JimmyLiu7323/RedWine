@extends('frontend.layouts._main')
@section('content')
<div class="site-section">
    <div class="container">
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="bg-light rounded p-3">
                    <p class="mb-0">{{$LANGUAGE==='EN'?'Already have an account?':'已经拥有帐号?'}} <a href="/login" style="color:#9c8c3a" class="d-inline-block">{{$LANGUAGE==='EN'?'Click here':'点击此处'}}</a> {{$LANGUAGE==='EN'?'to login':'登入'}}</p>
                </div>
            </div>
        </div>
        <form method="POST">
            <div class="row">
                <div class="col-sm-12">
                    <h2 class="h3 text-black font-heading-serif">{{$LANGUAGE==='EN'?'Become new Member':'注册成为会员'}}</h2>
                    <div class="bg-default rounded">
                        <p class="mb-0 text-danger">* {{$LANGUAGE==='EN'?'Required Fields':'必填栏位'}}</p>
                    </div>
                    <div class="p-3 p-lg-5 border">
                        <div class="form-group">
                            <label for="c_country" class="text-black">{{$LANGUAGE==='EN'?'Country':'国家'}} <span class="text-danger">*</span></label>
                            @if($errors->has('Country'))
                            <p class="mb-0 mt-0 text-danger">{{$errors->first('Country')}}</p>
                            @endif
                            <select id="c_country" name="Country" class="form-control">
                                <option value="CN" {{old('Country')=='CN'?'selected=true':''}}>{{$LANGUAGE==='EN'?'China':'中国'}}</option>
                                <option value="NZ" {{old('Country')=='NZ'?'selected=true':''}}>{{$LANGUAGE==='EN'?'New Zealand':'纽西兰'}}</option>
                                <option value="US" {{old('Country')=='US'?'selected=true':''}}>{{$LANGUAGE==='EN'?'USA':'美国'}}</option>
                            </select>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="c_fname" class="text-black">{{$LANGUAGE==='EN'?'First Name':'姓名'}} <span class="text-danger">*</span></label>
                                @if($errors->has('FirstName'))
                                <p class="mb-0 mt-0 text-danger">{{$errors->first('FirstName')}}</p>
                                @endif
                                <input type="text" class="form-control" id="c_fname" name="FirstName" maxlength="255" value="{{old('FirstName')}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="c_mname" class="text-black">{{$LANGUAGE==='EN'?'Middle Name/Initial':'中间名'}}</label>
                                @if($errors->has('MiddleName'))
                                <p class="mb-0 mt-0 text-danger">{{$errors->first('MiddleName')}}</p>
                                @endif
                                <input type="text" class="form-control" id="c_mname" name="MiddleName" maxlength="255" value="{{old('MiddleName')}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="c_lname" class="text-black">{{$LANGUAGE==='EN'?'Last Name':'姓'}} <span class="text-danger">*</span></label>
                                @if($errors->has('LastName'))
                                <p class="mb-0 mt-0 text-danger">{{$errors->first('LastName')}}</p>
                                @endif
                                <input type="text" class="form-control" id="c_lname" name="LastName" maxlength="255" value="{{old('LastName')}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="c_email" class="text-black">{{$LANGUAGE==='EN'?'Email Address':'Email'}} <span class="text-danger">*</span></label>
                                @if($errors->has('Email'))
                                <p class="mb-0 mt-0 text-danger">{{$errors->first('Email')}}</p>
                                @endif
                                <input type="email" class="form-control" id="c_email" name="Email" maxlength="255" value="{{old('Email')}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="c_password" class="text-black">{{$LANGUAGE==='EN'?'Password':'密码'}} <span class="text-danger">*</span></label>
                                @if($errors->has('Password'))
                                <p class="mb-0 mt-0 text-danger">{{$errors->first('Password')}}</p>
                                @endif
                                <input type="password" class="form-control" id="c_password" name="Password">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="c_confirm_password" class="text-black">{{$LANGUAGE==='EN'?'Confirm Password':'再次输入密码'}} <span class="text-danger">*</span></label>
                                @if($errors->has('Password_confirmation'))
                                <p class="mb-0 mt-0 text-danger">{{$errors->first('Password_confirmation')}}</p>
                                @endif
                                <input type="password" class="form-control" id="c_confirm_password" name="Password_confirmation">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="sign_up_news" class="text-black">
                                    <input type="checkbox" id="sign_up_news" name="sign_up_news" value=1 {{old('sign_up_news')==1?'checked=true':''}}> {{$LANGUAGE==='EN'?'Sign Up for Newsletter':'希望收到最新消息'}}
                                </label>
                            </div>
                        </div>
                        <input type="hidden" value="{{csrf_token()}}" name="_token" />
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary btn-block">{{$LANGUAGE==='EN'?'Become Member':'注册'}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection