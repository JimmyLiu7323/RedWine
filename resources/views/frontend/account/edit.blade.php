@extends('frontend.layouts._main')
@section('content')
<div class="mt-5 mb-5">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-12">
                <div class="pb-4 pt-4 custom-sidebar">
                    <h3 class="mt-0 mb-4 little-sidebar-title">
                        <span class="sidebar-arrow icon-arrow-circle-right" onclick="toggleShrink(this)"></span> {{$LANGUAGE==='EN'?'my account':'帐号总览'}}
                    </h3>
                    <ul class="shrink-list">
                        <li>
                            <a href="/account">{{$LANGUAGE==='EN'?'account dashboard':'帐号大纲'}}</a>
                        </li>
                        <li class="active">{{$LANGUAGE==='EN'?'maintain account':'维护我的帐号'}}</li>
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
                        <h2 class="mb-1 page-title">{{$LANGUAGE==='EN'?'edit account information':'维护我的帐号'}}</h2>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-sm-12">
                        <h4 class="box-head">{{$LANGUAGE==='EN'?'account information':'帐号资讯'}}</h4>
                    </div>
                    <form method="POST" style="width:100%">
                        <div class="col-sm-12">
                            <div class="bg-default rounded mt-3">
                                <p class="mb-0 text-danger">* {{$LANGUAGE==='EN'?'Required Fields':'必填栏位'}}</p>
                            </div>
                            <div class="p-3 p-lg-5 border">
                                <div class="form-group">
                                    <label for="c_country" class="text-black">Country <span class="text-danger">*</span></label>
                                    @if($errors->has('Country'))
                                    <p class="mb-0 mt-0 text-danger">{{$errors->first('Country')}}</p>
                                    @endif
                                    <select id="c_country" name="Country" class="form-control">
                                        <option value="CN" {{old('Country',$currentMemInfo->Country)=='CN'?'selected=true':''}}>{{$LANGUAGE==='EN'?'China':'中國'}}</option>
                                        <option value="NZ" {{old('Country',$currentMemInfo->Country)=='NZ'?'selected=true':''}}>{{$LANGUAGE==='EN'?'New Zealand':'紐西蘭'}}</option>
                                        <option value="US" {{old('Country',$currentMemInfo->Country)=='US'?'selected=true':''}}>{{$LANGUAGE==='EN'?'USA':'美國'}}</option>
                                    </select>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label for="c_fname" class="text-black">{{$LANGUAGE==='EN'?'First Name':'姓名'}} <span class="text-danger">*</span></label>
                                        @if($errors->has('FirstName'))
                                        <p class="mb-0 mt-0 text-danger">{{$errors->first('FirstName')}}</p>
                                        @endif
                                        <input type="text" class="form-control" id="c_fname" name="FirstName" maxlength="255" value="{{old('FirstName',$currentMemInfo->FirstName)}}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label for="c_mname" class="text-black">{{$LANGUAGE==='EN'?'Middle Name/Initial':'中间名'}}</label>
                                        @if($errors->has('MiddleName'))
                                        <p class="mb-0 mt-0 text-danger">{{$errors->first('MiddleName')}}</p>
                                        @endif
                                        <input type="text" class="form-control" id="c_mname" name="MiddleName" maxlength="255" value="{{old('MiddleName',$currentMemInfo->MiddleName)}}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label for="c_lname" class="text-black">{{$LANGUAGE==='EN'?'Last Name':'姓'}} <span class="text-danger">*</span></label>
                                        @if($errors->has('LastName'))
                                        <p class="mb-0 mt-0 text-danger">{{$errors->first('LastName')}}</p>
                                        @endif
                                        <input type="text" class="form-control" id="c_lname" name="LastName" maxlength="255" value="{{old('LastName',$currentMemInfo->LastName)}}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label for="c_email" class="text-black">Email <span class="text-danger">*</span></label>
                                        @if($errors->has('Email'))
                                        <p class="mb-0 mt-0 text-danger">{{$errors->first('Email')}}</p>
                                        @endif
                                        <input type="email" class="form-control" id="c_email" name="Email" maxlength="255" value="{{old('Email',$currentMemInfo->Email)}}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label for="c_verify_password" class="text-black">{{$LANGUAGE==='EN'?'Current Password to verify':'輸入目前密碼以驗證'}} <span class="text-danger">*</span></label>
                                        @if($errors->has('Password_verify'))
                                        <p class="mb-0 mt-0 text-danger">{{$errors->first('Password_verify')}}</p>
                                        @endif
                                        <input type="password" class="form-control" id="c_verify_password" name="Password_verify">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label for="change_pwd" class="text-black">
                                            <input type="checkbox" id="change_pwd" name="change_pwd" value=1 {{old('change_pwd')==1?'checked=true':''}}> {{$LANGUAGE==='EN'?'Change Password':'變更密碼'}}
                                        </label>
                                    </div>
                                </div>
                                <hr>
                                <div id="change_pwd_section">
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label for="c_password" class="text-black">{{$LANGUAGE==='EN'?'Password':'密碼'}} <span class="text-danger">*</span></label>
                                            @if($errors->has('Password'))
                                            <p class="mb-0 mt-0 text-danger">{{$errors->first('Password')}}</p>
                                            @endif
                                            <input type="password" class="form-control" id="c_password" name="Password">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label for="c_confirm_password" class="text-black">{{$LANGUAGE==='EN'?'Confirm Password':'確認密碼'}} <span class="text-danger">*</span></label>
                                            @if($errors->has('Password_confirmation'))
                                            <p class="mb-0 mt-0 text-danger">{{$errors->first('Password_confirmation')}}</p>
                                            @endif
                                            <input type="password" class="form-control" id="c_confirm_password" name="Password_confirmation">
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                                <input type="hidden" value="{{csrf_token()}}" name="_token" />
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <a href="/account" class="btn btn-default"><span class="icon-arrow-left"></span> {{$LANGUAGE==='EN'?'Back':'返回'}}</a>
                                    </div>
                                    <div class="col-sm-6 text-right">
                                        <button type="submit" class="btn btn-primary">{{$LANGUAGE==='EN'?'Save':'儲存'}}</button>
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
@endsection