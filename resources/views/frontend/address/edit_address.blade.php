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
                        <li>
                            <a href="/account">{{$LANGUAGE==='EN'?'account dashboard':'帐号大纲'}}</a>
                        </li>
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
                        <h2 class="mb-1 page-title">{{$LANGUAGE==='EN'?'edit address':'编辑地址'}}</h2>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        <h4 class="box-head">{{$LANGUAGE==='EN'?'contact information':'联络资讯'}}</h4>
                    </div>
                    <form method="POST" class="col-12">
                        <div class="row">
                            <div class="col-12">
                                <div class="bg-default rounded mt-3">
                                    <p class="mb-0 text-danger">* {{$LANGUAGE==='EN'?'Required Fields':'必填栏位'}}</p>
                                </div>
                                <div class="p-3 p-lg-5 border">
                                    <div class="form-group">
                                        <label for="Contact_first" class="text-black">{{$LANGUAGE==='EN'?'First Name':'姓名'}} <span class="text-danger">*</span></label>
                                        @if($errors->has('Contact_first'))
                                        <p class="mb-0 mt-0 text-danger">{{$errors->first('Contact_first')}}</p>
                                        @endif
                                        <input type="text" id="Contact_first" name="Contact_first" class="form-control" value="{{old('Contact_first',$addressData->Contact_first)}}" />
                                    </div>
                                    <div class="form-group">
                                        <label for="Contact_middle" class="text-black">{{$LANGUAGE==='EN'?'Middle Name/Initial':'中间名'}}</label>
                                        @if($errors->has('Contact_middle'))
                                        <p class="mb-0 mt-0 text-danger">{{$errors->first('Contact_middle')}}</p>
                                        @endif
                                        <input type="text" class="form-control" id="Contact_middle" name="Contact_middle" maxlength="255" value="{{old('Contact_middle',$addressData->Contact_middle)}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="c_lname" class="text-black">{{$LANGUAGE==='EN'?'Last Name':'姓'}} <span class="text-danger">*</span></label>
                                        @if($errors->has('Contact_last'))
                                        <p class="mb-0 mt-0 text-danger">{{$errors->first('Contact_last')}}</p>
                                        @endif
                                        <input type="text" class="form-control" id="c_lname" name="Contact_last" maxlength="255" value="{{old('Contact_last',$addressData->Contact_last)}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="Company" class="text-black">{{$LANGUAGE==='EN'?'Company':'公司名称'}}</label>
                                        @if($errors->has('Company'))
                                        <p class="mb-0 mt-0 text-danger">{{$errors->first('Company')}}</p>
                                        @endif
                                        <input type="text" class="form-control" id="Company" name="Company" maxlength="255" value="{{old('Company',$addressData->Company)}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="Telephone" class="text-black">{{$LANGUAGE==='EN'?'Telephone':'连络电话'}} <span class="text-danger">*</span></label>
                                        @if($errors->has('Telephone'))
                                        <p class="mb-0 mt-0 text-danger">{{$errors->first('Telephone')}}</p>
                                        @endif
                                        <input type="text" class="form-control" id="Telephone" name="Telephone" maxlength="255" value="{{old('Telephone',$addressData->Telephone)}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="Fax" class="text-black">{{$LANGUAGE==='EN'?'Fax':'传真号码'}}</label>
                                        @if($errors->has('Fax'))
                                        <p class="mb-0 mt-0 text-danger">{{$errors->first('Fax')}}</p>
                                        @endif
                                        <input type="text" class="form-control" id="Fax" name="Fax" maxlength="255" value="{{old('Fax',$addressData->Fax)}}">
                                    </div>                            
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12">
                                <h4 class="box-head">{{$LANGUAGE==='EN'?'address':'您的地址'}}</h4>
                            </div>                            
                            <div class="col-12">
                                <div class="bg-default rounded mt-3">
                                    <p class="mb-0 text-danger">* {{$LANGUAGE==='EN'?'Required Fields':'必填栏位'}}</p>
                                </div>
                                <div class="p-3 p-lg-5 border">
                                    <div class="form-group">
                                        <label for="StreetAddr" class="text-black">{{$LANGUAGE==='EN'?'Street Address':'地址'}} <span class="text-danger">*</span></label>
                                        @if($errors->has('StreetAddr'))
                                        <p class="mb-0 mt-0 text-danger">{{$errors->first('StreetAddr')}}</p>
                                        @endif
                                        <input type="text" id="StreetAddr" name="StreetAddr" class="form-control" value="{{old('StreetAddr',$addressData->StreetAddr)}}" />
                                    </div>
                                    <div class="form-group">
                                        <label for="StreetAddr2" class="text-black">{{$LANGUAGE==='EN'?'Street Address 2':'第二地址'}}</label>
                                        @if($errors->has('StreetAddr2'))
                                        <p class="mb-0 mt-0 text-danger">{{$errors->first('StreetAddr2')}}</p>
                                        @endif
                                        <input type="text" id="StreetAddr2" name="StreetAddr2" class="form-control" value="{{old('StreetAddr2',$addressData->StreetAddr2)}}" />
                                    </div>
                                    <div class="form-group">
                                        <label for="City" class="text-black">{{$LANGUAGE==='EN'?'City':'县市'}} <span class="text-danger">*</span></label>
                                        @if($errors->has('City'))
                                        <p class="mb-0 mt-0 text-danger">{{$errors->first('City')}}</p>
                                        @endif
                                        <input type="text" id="City" name="City" class="form-control" value="{{old('City',$addressData->City)}}" />
                                    </div>
                                    <div class="form-group">
                                        <label for="Region" class="text-black">{{$LANGUAGE==='EN'?'Region':'地区'}} <span class="text-danger">*</span></label>
                                        @if($errors->has('Region'))
                                        <p class="mb-0 mt-0 text-danger">{{$errors->first('Region')}}</p>
                                        @endif
                                        <input type="text" id="Region" name="Region" class="form-control" value="{{old('Region',$addressData->Region)}}" />
                                    </div>
                                    <div class="form-group">
                                        <label for="Region" class="text-black">{{$LANGUAGE==='EN'?'Post Code':'邮递区号'}} <span class="text-danger">*</span></label>
                                        @if($errors->has('PostCode'))
                                        <p class="mb-0 mt-0 text-danger">{{$errors->first('PostCode')}}</p>
                                        @endif
                                        <input type="text" id="PostCode" name="PostCode" class="form-control" value="{{old('PostCode',$addressData->PostCode)}}" />
                                    </div>
                                    <div class="form-group">
                                        <label for="Country" class="text-black">{{$LANGUAGE==='EN'?'Country':'国家'}} <span class="text-danger">*</span></label>
                                        @if($errors->has('Country'))
                                        <p class="mb-0 mt-0 text-danger">{{$errors->first('Country')}}</p>
                                        @endif
                                        <select name="Country" class="form-control">
                                            @foreach($countries as $shortCountry=>$country)
                                            <option value="{{$shortCountry}}" {{old('Country',$addressData->Country)==$shortCountry?'selected=true':''}}>{{$country}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" value="{{csrf_token()}}" name="_token" />
                        <div class="row mt-2">
                            <div class="col-sm-6">
                                <a href="/address" class="btn btn-default"><span class="icon-arrow-left"></span> {{$LANGUAGE==='EN'?'Back':'返回'}}</a>
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
</div>
@endsection