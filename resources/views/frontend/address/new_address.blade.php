@extends('frontend.layouts._main')
@section('content')
<div class="mt-3 mb-3">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-12">
                <div class="pb-4 pt-4 custom-sidebar">
                    <h3 class="mt-0 mb-4 little-sidebar-title">
                        <span class="sidebar-arrow icon-arrow-circle-right" onclick="toggleShrink(this)"></span> my account
                    </h3>
                    <ul class="shrink-list">
                        <li>
                            <a href="/account">account dashboard</a>
                        </li>
                        <li>
                            <a href="/account/edit">account information</a>
                        </li>
                        <li class="active">
                            <a href="/address">address book</a>
                        </li>
                        <!-- <li> -->
                            <!-- <a href="/sales/orders">my orders</a> -->
                        <!-- </li> -->
                        <li>
                            <a href="/wishlist/">my wishlist</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-9 col-sm-12">
                <div class="row">
                    <div class="col-sm-12">
                        <h2 class="mb-1 page-title">add new address</h2>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        <h4 class="box-head">contact information</h4>
                    </div>
                    <form method="POST" class="col-12">
                        <div class="row">
                            <div class="col-12">
                                <div class="bg-default rounded mt-3">
                                    <p class="mb-0 text-danger">* Required Fields</p>
                                </div>
                                <div class="p-3 p-lg-5 border">
                                    <div class="form-group">
                                        <label for="Contact_first" class="text-black">First Name <span class="text-danger">*</span></label>
                                        @if($errors->has('Contact_first'))
                                        <p class="mb-0 mt-0 text-danger">{{$errors->first('Contact_first')}}</p>
                                        @endif
                                        <input type="text" id="Contact_first" name="Contact_first" class="form-control" value="{{old('Contact_first',$currentMemInfo->FirstName)}}" />
                                    </div>
                                    <div class="form-group">
                                        <label for="Contact_middle" class="text-black">Middle Name/Initial</label>
                                        @if($errors->has('Contact_middle'))
                                        <p class="mb-0 mt-0 text-danger">{{$errors->first('Contact_middle')}}</p>
                                        @endif
                                        <input type="text" class="form-control" id="Contact_middle" name="Contact_middle" maxlength="255" value="{{old('Contact_middle',$currentMemInfo->MiddleName)}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="c_lname" class="text-black">Last Name <span class="text-danger">*</span></label>
                                        @if($errors->has('Contact_last'))
                                        <p class="mb-0 mt-0 text-danger">{{$errors->first('Contact_last')}}</p>
                                        @endif
                                        <input type="text" class="form-control" id="c_lname" name="Contact_last" maxlength="255" value="{{old('Contact_last',$currentMemInfo->LastName)}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="Company" class="text-black">Company</label>
                                        @if($errors->has('Company'))
                                        <p class="mb-0 mt-0 text-danger">{{$errors->first('Company')}}</p>
                                        @endif
                                        <input type="text" class="form-control" id="Company" name="Company" maxlength="255" value="{{old('Company')}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="Telephone" class="text-black">Telephone <span class="text-danger">*</span></label>
                                        @if($errors->has('Telephone'))
                                        <p class="mb-0 mt-0 text-danger">{{$errors->first('Telephone')}}</p>
                                        @endif
                                        <input type="text" class="form-control" id="Telephone" name="Telephone" maxlength="255" value="{{old('Telephone')}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="Fax" class="text-black">Fax</label>
                                        @if($errors->has('Fax'))
                                        <p class="mb-0 mt-0 text-danger">{{$errors->first('Fax')}}</p>
                                        @endif
                                        <input type="text" class="form-control" id="Fax" name="Fax" maxlength="255" value="{{old('Fax')}}">
                                    </div>                            
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12">
                                <h4 class="box-head">address</h4>
                            </div>                            
                            <div class="col-12">
                                <div class="bg-default rounded mt-3">
                                    <p class="mb-0 text-danger">* Required Fields</p>
                                </div>
                                <div class="p-3 p-lg-5 border">
                                    <div class="form-group">
                                        <label for="StreetAddr" class="text-black">Street Address <span class="text-danger">*</span></label>
                                        @if($errors->has('StreetAddr'))
                                        <p class="mb-0 mt-0 text-danger">{{$errors->first('StreetAddr')}}</p>
                                        @endif
                                        <input type="text" id="StreetAddr" name="StreetAddr" class="form-control" value="{{old('StreetAddr')}}" />
                                    </div>
                                    <div class="form-group">
                                        <label for="StreetAddr2" class="text-black">Street Address 2</label>
                                        @if($errors->has('StreetAddr2'))
                                        <p class="mb-0 mt-0 text-danger">{{$errors->first('StreetAddr2')}}</p>
                                        @endif
                                        <input type="text" id="StreetAddr2" name="StreetAddr2" class="form-control" value="{{old('StreetAddr2')}}" />
                                    </div>
                                    <div class="form-group">
                                        <label for="City" class="text-black">City <span class="text-danger">*</span></label>
                                        @if($errors->has('City'))
                                        <p class="mb-0 mt-0 text-danger">{{$errors->first('City')}}</p>
                                        @endif
                                        <input type="text" id="City" name="City" class="form-control" value="{{old('City')}}" />
                                    </div>
                                    <div class="form-group">
                                        <label for="Region" class="text-black">Region <span class="text-danger">*</span></label>
                                        @if($errors->has('Region'))
                                        <p class="mb-0 mt-0 text-danger">{{$errors->first('Region')}}</p>
                                        @endif
                                        <input type="text" id="Region" name="Region" class="form-control" value="{{old('Region')}}" />
                                    </div>
                                    <div class="form-group">
                                        <label for="Region" class="text-black">Post Code <span class="text-danger">*</span></label>
                                        @if($errors->has('PostCode'))
                                        <p class="mb-0 mt-0 text-danger">{{$errors->first('PostCode')}}</p>
                                        @endif
                                        <input type="text" id="PostCode" name="PostCode" class="form-control" value="{{old('PostCode')}}" />
                                    </div>
                                    <div class="form-group">
                                        <label for="Country" class="text-black">Country <span class="text-danger">*</span></label>
                                        @if($errors->has('Country'))
                                        <p class="mb-0 mt-0 text-danger">{{$errors->first('Country')}}</p>
                                        @endif
                                        <select name="Country" class="form-control">
                                            @foreach($countries as $shortCountry=>$country)
                                            <option value="{{$shortCountry}}" {{old('Country','NZ')==$shortCountry?'selected=true':''}}>{{$country}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="1" name="DefaultBilling" style="margin-right:10px">Use my default billing address</label>
                                    </div>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="1" name="DefaultShipping" style="margin-right:10px">Use my default shipping address</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" value="{{csrf_token()}}" name="_token" />
                        <div class="row mt-2">
                            <div class="col-sm-6">
                                <a href="/address" class="btn btn-default"><span class="icon-arrow-left"></span> Back</a>
                            </div>
                            <div class="col-sm-6 text-right">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection