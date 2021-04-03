@extends('frontend.layouts._main')
@section('content')
<div class="container mt-5 mb-5">
	<form class="row" method="POST">
		<div class="col-md-6 mb-5 mb-md-0">
			<h2 class="h3 mb-3 text-black font-heading-serif">{{$LANGUAGE==='EN'?'Billing Details':'帐单资讯'}}</h2>
			<div class="p-3 p-lg-5 border">
				<div class="form-group">
					<label for="c_country" class="text-black">{{$LANGUAGE==='EN'?'Country':'国家'}}<span class="text-danger">*</span></label>     @if($errors->has('BillingAddr.Country'))
					<p class="mb-0 mt-0 text-danger">{{$errors->first('BillingAddr.Country')}}</p>
					@endif
					<select id="c_country" class="form-control" name="BillingAddr[Country]">
						<option value="">{{$LANGUAGE==='EN'?'Select a country':'请选择国家'}}</option>                        @foreach(\Config::get('app.countries') as $shortCountry=>$country)
							@if($defaultBilling)
						<option value="{{$shortCountry}}" {{old('BillingAddr[Country]',$defaultBilling->Country)==$shortCountry?'selected=true':''}}>{{$country}}</option>
							@else
						<option value="{{$shortCountry}}">{{$country}}</option>
							@endif
						@endforeach
					</select>
				</div>
				<div class="form-group row">
					<div class="col-md-6">
						<label for="c_fname" class="text-black">{{$LANGUAGE==='EN'?'First Name':'姓名'}} <span class="text-danger">*</span></label>
						@if($errors->has('BillingAddr.FirstName'))
						<p class="mb-0 mt-0 text-danger">{{$errors->first('BillingAddr.FirstName')}}</p>
						@endif
						@if(old('BillingAddr.FirstName'))
						<input type="text" class="form-control" id="c_fname" name="BillingAddr[FirstName]" value="{{old('BillingAddr.FirstName')}}">
						@else
						<input type="text" class="form-control" id="c_fname" name="BillingAddr[FirstName]" value='@if($defaultBilling){{$defaultBilling->Contact_first}}@endif'>
						@endif
					</div>
					<div class='col-md-6'>
						<label for="c_fmiddlename" class="text-black">{{$LANGUAGE==='EN'?'Middle Name':'中间名'}}</label>                        @if($errors->has('BillingAddr.MiddleName'))
						<p class="mb-0 mt-0 text-danger">{{$errors->first('BillingAddr.MiddleName')}}</p>
						@endif
						@if(old('BillingAddr.MiddleName'))
						<input type="text" class="form-control" id="c_fmiddlename" name="BillingAddr[MiddleName]" value='{{old('BillingAddr.MiddleName')}}'>
						@else
						<input type="text" class="form-control" id="c_fmiddlename" name="BillingAddr[MiddleName]" value='@if($defaultBilling){{$defaultBilling->Contact_middle}}@endif'>
						@endif
					</div>
				</div>
				<div class='form-group row'>
					<div class="col-12">
						<label for="c_lname" class="text-black">{{$LANGUAGE==='EN'?'Last Name':'姓'}} <span class="text-danger">*</span></label>
						@if($errors->has('BillingAddr.LastName'))
						<p class="mb-0 mt-0 text-danger">{{$errors->first('BillingAddr.LastName')}}</p>
						@endif
						@if(old('BillingAddr.LastName'))
						<input type="text" class="form-control" id="c_lname" name="BillingAddr[LastName]" value="{{old('BillingAddr.LastName')}}">
						@else
						<input type="text" class="form-control" id="c_lname" name="BillingAddr[LastName]" value='@if($defaultBilling){{$defaultBilling->Contact_last}}@endif'>
						@endif
					</div>
				</div>
				<div class="form-group row">
					<div class="col-md-12">
						<label for="c_companyname" class="text-black">{{$LANGUAGE==='EN'?'Company':'公司名称'}}</label>
						@if($errors->has('BillingAddr.Company'))
						<p class="mb-0 mt-0 text-danger">{{$errors->first('BillingAddr.Company')}}</p>
						@endif
						@if(old('BillingAddr.Company'))
						<input type="text" class="form-control" id="c_companyname" name="BillingAddr[Company]" value="{{old('BillingAddr.Company')}}">
						@else
						<input type="text" class="form-control" id="c_companyname" name="BillingAddr[Company]" value='@if($defaultBilling){{$defaultBilling->Company}}@endif'>
					@endif
					</div>
				</div>
				<div class="form-group row">
					<div class="col-12">
						<label for="c_address" class="text-black">{{$LANGUAGE==='EN'?'Address':'地址'}} <span class="text-danger">*</span></label>
					@if($errors->has('BillingAddr.Address'))
						<p class="mb-0 mt-0 text-danger">{{$errors->first('BillingAddr.Address')}}</p>
					@endif
					@if(old('BillingAddr.Address'))
						<input type="text" class="form-control" id="c_address" name="BillingAddr[Address]" placeholder="{{$LANGUAGE==='EN'?'Street address':'地址'}}" value="{{old('BillingAddr.Address')}}">
					@else
						<input type="text" class="form-control" id="c_address" name="BillingAddr[Address]" placeholder="{{$LANGUAGE==='EN'?'Street address':'地址'}}" value='@if($defaultBilling){{$defaultBilling->StreetAddr}}@endif'>
					@endif
					</div>
					@if($defaultBilling&&$defaultBilling->StreetAddr2)
					<div class="col-12 mt-2">
						<label for="c_address2" class="text-black">Address2</label>
						@if($errors->has('BillingAddr.Address2'))
						<p class="mb-0 mt-0 text-danger">{{$errors->first('BillingAddr.Address2')}}</p>
						@endif
						@if(old('BillingAddr.Address2'))
						<input type="text" class="form-control" id="c_address2" name="BillingAddr[Address2]" placeholder="{{$LANGUAGE==='EN'?'Street address':'地址'}}" value="{{old('BillingAddr.Address2')}}">
						@else
						<input type="text" class="form-control" id="c_address2" name="BillingAddr[Address2]" placeholder="{{$LANGUAGE==='EN'?'Street address':'地址'}}" value='@if($defaultBilling){{$defaultBilling->StreetAddr2}}@endif'>
						@endif
					</div>
					@endif
				</div>
				<div class="form-group row">
					<div class="col-12">
					@if($errors->has('BillingAddr.City'))
						<p class="mb-0 mt-0 text-danger">{{$errors->first('BillingAddr.City')}}</p>
					@endif
					@if($errors->has('BillingAddr.Region'))
						<p class="mb-0 mt-0 text-danger">{{$errors->first('BillingAddr.Region')}}</p>
					@endif
					</div>
					<div class="col-md-6">
						<label for="c_city" class="text-black">{{$LANGUAGE==='EN'?'City':'县市'}} <span class="text-danger">*</span></label>
					@if(old('BillingAddr.City'))
						<input type="text" class="form-control" id="c_city" name="BillingAddr[City]" value="{{old('BillingAddr.City')}}">
					@else
						<input type="text" class="form-control" id="c_city" name="BillingAddr[City]" value='@if($defaultBilling){{$defaultBilling->City}}@endif'>
					@endif
					</div>
					<div class="col-md-6">
						<label for="c_region" class="text-black">{{$LANGUAGE==='EN'?'Region':'区域'}} <span class="text-danger">*</span></label>
					@if(old('Billing.Region'))
						<input type="text" class="form-control" id="c_region" name="BillingAddr[Region]" value="{{old('Billing.Region')}}">
					@else
						<input type="text" class="form-control" id="c_region" name="BillingAddr[Region]" value='@if($defaultBilling){{$defaultBilling->Region}}@endif'>
					@endif
					</div>
					<div class="col-md-6">
						<label for="c_postal_zip" class="text-black">{{$LANGUAGE==='EN'?'Post Code':'邮递区号'}} <span class="text-danger">*</span></label>
					@if($errors->has('BillingAddr.PostCode'))
						<p class="mb-0 mt-0 text-danger">{{$errors->first('BillingAddr.PostCode')}}</p>
					@endif
					@if(old('BillingAddr.PostCode'))
						<input type="text" class="form-control" id="c_postal_zip" name="BillingAddr[PostCode]" value={{old('BillingAddr.PostCode')}} >
					@else
						<input type="text" class="form-control" id="c_postal_zip" name="BillingAddr[PostCode]" value='@if($defaultBilling){{$defaultBilling->PostCode}}@endif'>
					@endif
					</div>
				</div>
				<div class="form-group row">
					<div class="col-12">
					@if($errors->has('BillingAddr.Email'))
						<p class="mb-0 mt-0 text-danger">{{$errors->first('BillingAddr.Email')}}</p>
					@endif
					@if($errors->has('BillingAddr.Telephone'))
						<p class="mb-0 mt-0 text-danger">{{$errors->first('BillingAddr.Telephone')}}</p>
					@endif
					</div>
					<div class="col-md-6">
						<label for="c_email_address" class="text-black">Email <span class="text-danger">*</span></label>
					@if(old('BillingAddr.Email'))
						<input type="email" class="form-control" id="c_email_address" name="BillingAddr[Email]" value="{{old('BillingAddr.Email')}}">
					@else
						<input type="email" class="form-control" id="c_email_address" name="BillingAddr[Email]" value='@if($currentMemInfo){{$currentMemInfo->Email}}@endif'>
					@endif
					</div>
					<div class="col-md-6">
						<label for="c_phone" class="text-black">{{$LANGUAGE==='EN'?'Phone':'联络电话'}} <span class="text-danger">*</span></label>
					@if(old('BillingAddr.Telephone'))
						<input type="text" class="form-control" id="c_phone" name="BillingAddr[Telephone]" placeholder="{{$LANGUAGE==='EN'?'Phone Number':'联络电话'}}" value="{{old('BillingAddr.Telephone')}}">
					@else
						<input type="text" class="form-control" id="c_phone" name="BillingAddr[Telephone]" placeholder="{{$LANGUAGE==='EN'?'Phone Number':'联络电话'}}" value='@if($defaultBilling){{$defaultBilling->Telephone}}@endif'>
					@endif
					</div>
				</div>
					@if($shippingBook->count()>0&&$deliveryMethod=='deliveryHome')
				<div class='form-group'>
					<label for="shippingAddrSel" class="text-black">{{$LANGUAGE==='EN'?'Shipping Address':'配送地址'}} <span class="text-danger">*</span></label>
						@if($errors->has('ShippingBookId'))
					<p class="mb-0 mt-0 text-danger">{{$errors->first('ShippingBookId')}}</p>
						@endif
					<select id="shippingAddrSel" class="form-control" name="ShippingBookId">
						@foreach($shippingBook as $shippingAddr)
							@if(old('ShippingBookId',$shippingAddr->DefaultChoose))
						<option value="{{$shippingAddr->AddrId}}" selected='true'>{{$shippingAddr->Contact_first}} {{$shippingAddr->Contact_middle}} {{$shippingAddr->Contact_last}}, {{$shippingAddr->StreetAddr}} {{$shippingAddr->StreetAddr2}} {{$shippingAddr->City}} {{$shippingAddr->Region}} {{$shippingAddr->PostCode}} {{\Config::get('app.countries')[$shippingAddr->Country]}}</option>
							@else
						<option value="{{$shippingAddr->AddrId}}">{{$shippingAddr->Contact_first}} {{$shippingAddr->Contact_middle}} {{$shippingAddr->Contact_last}}, {{$shippingAddr->StreetAddr}} {{$shippingAddr->StreetAddr2}} {{$shippingAddr->City}} {{$shippingAddr->Region}} {{$shippingAddr->PostCode}} {{\Config::get('app.countries')[$shippingAddr->Country]}}</option>
							@endif
						@endforeach
					</select>
				</div>
					@endif
					@if($deliveryMethod=='deliveryHome')
				<div class="form-group">
					<label for="c_ship_different_address" class="text-black" data-toggle="collapse" href="#ship_different_address" role="button" aria-expanded="false" aria-controls="ship_different_address"><input type="checkbox" value="1" name="ShippingOption" id="c_ship_different_address" {{old('ShippingOption')==1?'checked=true':''}}> {{$LANGUAGE==='EN'?'Ship To other Address?':'配送至其他地址?'}}</label>
					<div @if(old('ShippingOption')!=1) class="collapse" @endif id="ship_different_address">
						<div class="py-2">
							<div class="form-group">
								<label for="c_shipping_country" class="text-black">{{$LANGUAGE==='EN'?'Country':'国家'}} <span class="text-danger">*</span></label>
								@if($errors->has('ShippingAddr.Country'))
								<p class="mb-0 mt-0 text-danger">{{$errors->first('ShippingAddr.Country')}}</p>
								@endif
								<select id="c_shipping_country" class="form-control" name="ShippingAddr[Country]">
									<option value="">{{$LANGUAGE==='EN'?'Select a country':'请选择国家'}}</option>
									@foreach(\Config::get('app.countries') as $shortCountry=>$country)
										@if($defaultShipping)
									<option value="{{$shortCountry}}" {{old('ShippingAddr.Country',$defaultShipping->Country)==$shortCountry?'selected=true':''}}>{{$country}}</option>
										@else
									<option value="{{$shortCountry}}" {{old('ShippingAddr.Country')==$shortCountry?'selected=true':''}}>{{$country}}</option>
										@endif
									@endforeach
								</select>
							</div>
							<div class="form-group row">
								<div class="col-md-6">
									<label for="c_diff_fname" class="text-black">{{$LANGUAGE==='EN'?'First Name':'姓名'}} <span class="text-danger">*</span></label>
									@if($errors->has('ShippingAddr.FirstName'))
									<p class="mb-0 mt-0 text-danger">{{$errors->first('ShippingAddr.FirstName')}}</p>
									@endif
									@if($defaultShipping)
									<input type="text" class="form-control" id="c_diff_fname" name="ShippingAddr[FirstName]" value="{{old('ShippingAddr.FirstName',$defaultShipping->Contact_first)}}">
									@else
									<input type="text" class="form-control" id="c_diff_fname" name="ShippingAddr[FirstName]" value="{{old('ShippingAddr.FirstName')}}">
									@endif
								</div>
								<div class='col-md-6'>
									<label for="c_diff_fmiddleame" class="text-black">{{$LANGUAGE==='EN'?'Middle Name':'中间名'}}</label>
									@if($errors->has('ShippingAddr.MiddleName'))
									<p class="mb-0 mt-0 text-danger">{{$errors->first('ShippingAddr.MiddleName')}}</p>
									@endif
									@if($defaultShipping)
									<input type="text" class="form-control" id="c_diff_fmiddleame" name="ShippingAddr[MiddleName]" value="{{old('ShippingAddr.MiddleName',$defaultShipping->Contact_middle)}}">
									@else
									<input type="text" class="form-control" id="c_diff_fmiddleame" name="ShippingAddr[MiddleName]" value="{{old('ShippingAddr.MiddleName')}}">
									@endif
								</div>
							</div>
							<div class='form-group row'>
								<div class="col-12">
									<label for="c_diff_lname" class="text-black">{{$LANGUAGE==='EN'?'Last Name':'姓'}} <span class="text-danger">*</span></label>
								@if($errors->has('ShippingAddr.LastName'))
									<p class="mb-0 mt-0 text-danger">{{$errors->first('ShippingAddr.LastName')}}</p>
								@endif
								@if($defaultShipping)
									<input type="text" class="form-control" id="c_diff_lname" name="ShippingAddr[LastName]" value="{{old('ShippingAddr.LastName',$defaultShipping->Contact_last)}}">
								@else
									<input type="text" class="form-control" id="c_diff_lname" name="ShippingAddr[LastName]" value="{{old('ShippingAddr.LastName')}}">
								@endif
								</div>
							</div>
							<div class="form-group row">
								<div class="col-md-12">
									<label for="c_diff_companyname" class="text-black">{{$LANGUAGE==='EN'?'Company':'公司名称'}}</label>
								@if($errors->has('ShippingAddr.Company'))
									<p class="mb-0 mt-0 text-danger">{{$errors->first('ShippingAddr.Company')}}</p>
								@endif
								@if($defaultShipping)
									<input type="text" class="form-control" id="c_diff_companyname" name="ShippingAddr[Company]" value="{{old('ShippingAddr.Company',$defaultShipping->Company)}}">
								@else
									<input type="text" class="form-control" id="c_diff_companyname" name="ShippingAddr[Company]" value="{{old('ShippingAddr.Company')}}">
								@endif
								</div>
							</div>
							<div class="form-group row">
								<div class="col-12">
									<label for="c_diff_address" class="text-black">{{$LANGUAGE==='EN'?'Address':'地址'}} <span class="text-danger">*</span></label>
								@if($errors->has('ShippingAddr.Address'))
									<p class="mb-0 mt-0 text-danger">{{$errors->first('ShippingAddr.Address')}}</p>
								@endif
								@if($defaultShipping)
									<input type="text" class="form-control" id="c_diff_address" name="ShippingAddr[Address]" placeholder="{{$LANGUAGE==='EN'?'Street address':'地址'}}" value="{{old('ShippingAddr.Address',$defaultShipping->StreetAddr)}}">
								@else
									<input type="text" class="form-control" id="c_diff_address" name="ShippingAddr[Address2]" placeholder="{{$LANGUAGE==='EN'?'Street address':'地址'}}" value="{{old('ShippingAddr.Address2')}}">
								@endif
								</div>
								@if($defaultShipping&&$defaultShipping->StreetAddr2)
								<div class="col-12">
									<label for="c_diff_address2" class="text-black">{{$LANGUAGE==='EN'?'Address2':'第二地址'}}</label>
									@if($errors->has('ShippingAddr.Address2'))
									<p class="mb-0 mt-0 text-danger">{{$errors->first('ShippingAddr.Address2')}}</p>
									@endif
									@if($defaultShipping)
									<input type="text" class="form-control" id="c_diff_address2" name="ShippingAddr[Address2]" placeholder="{{$LANGUAGE==='EN'?'Street address':'地址'}}" value='{{old('ShippingAddr.Address2',$defaultShipping->StreetAddr2)}}'>
									@else
									<input type="text" class="form-control" id="c_diff_address2" name="ShippingAddr[Address2]" placeholder="{{$LANGUAGE==='EN'?'Street address':'地址'}}" value='{{old('ShippingAddr.Address2')}}'>
									@endif
								</div>
								@endif
							</div>
							<div class="form-group row">
								<div class="col-md-6">
									<label for="c_diff_city" class="text-black">{{$LANGUAGE==='EN'?'City':'县市'}} <span class="text-danger">*</span></label>
									@if($errors->has('ShippingAddr.City'))
									<p class="mb-0 mt-0 text-danger">{{$errors->first('ShippingAddr.City')}}</p>
									@endif
									@if($defaultShipping)
									<input type="text" class="form-control" id="c_diff_city" name="ShippingAddr[City]" value='{{old('ShippingAddr.City',$defaultShipping->City)}}'>
									@else
									<input type="text" class="form-control" id="c_diff_city" name="ShippingAddr[City]" value='{{old('ShippingAddr.City')}}'>
									@endif
								</div>
								<div class="col-md-6">
									<label for="c_diff_region" class="text-black">{{$LANGUAGE==='EN'?'Region':'区域'}} <span class="text-danger">*</span></label>
									@if($errors->has('ShippingAddr.Region'))
									<p class="mb-0 mt-0 text-danger">{{$errors->first('ShippingAddr.Region')}}</p>
									@endif
									@if($defaultShipping)
									<input type="text" class="form-control" id="c_diff_region" name="ShippingAddr[Region]" value='{{old('ShippingAddr.Region',$defaultShipping->Region)}}'>
									@else
									<input type="text" class="form-control" id="c_diff_region" name="ShippingAddr[Region]" value="{{old('ShippingAddr.Region')}}">
									@endif
								</div>
								<div class="col-md-6">
									<label for="c_diff_postal_zip" class="text-black">{{$LANGUAGE==='EN'?'Post Code':'邮递区号'}} <span class="text-danger">*</span></label>
									@if($errors->has('ShippingAddr.PostCode'))
									<p class="mb-0 mt-0 text-danger">{{$errors->first('ShippingAddr.PostCode')}}</p>
									@endif
									@if($defaultShipping)
									<input type="text" class="form-control" id="c_diff_postal_zip" name="ShippingAddr[PostCode]" value="{{old('ShippingAddr.PostCode',$defaultShipping->PostCode)}}">
									@else
									<input type="text" class="form-control" id="c_diff_postal_zip" name="ShippingAddr[PostCode]" value="{{old('ShippingAddr.PostCode')}}">
									@endif
								</div>
							</div>
							<div class="form-group row mb-5">
								<div class="col-md-6">
									<label for="c_diff_email_address" class="text-black">Email <span class="text-danger">*</span></label>
									@if($errors->has('ShippingAddr.Email'))
									<p class="mb-0 mt-0 text-danger">{{$errors->first('ShippingAddr.Email')}}</p>
									@endif
									@if($currentMemInfo)
									<input type="text" class="form-control" id="c_diff_email_address" name="ShippingAddr[Email]" value="{{old('ShippingAddr.Email',$currentMemInfo->Email)}}">
									@else
									<input type="text" class="form-control" id="c_diff_email_address" name="ShippingAddr[Email]" value="{{old('ShippingAddr.Email')}}">
									@endif
								</div>
								<div class="col-md-6">
									<label for="c_diff_phone" class="text-black">{{$LANGUAGE==='EN'?'Phone':'联络电话'}} <span class="text-danger">*</span></label>
									@if($errors->has('ShippingAddr.Telephone'))
									<p class="mb-0 mt-0 text-danger">{{$errors->first('ShippingAddr.Telephone')}}</p>
									@endif
									@if($defaultShipping)
									<input type="text" class="form-control" id="c_diff_phone" name="ShippingAddr[Telephone]" placeholder="Phone Number" value="{{old('ShippingAddr.Telephone',$defaultShipping->Telephone)}}">
									@else
									<input type="text" class="form-control" id="c_diff_phone" name="ShippingAddr[Telephone]" placeholder="Phone Number" value="{{old('ShippingAddr.Telephone')}}">
									@endif
								</div>
							</div>
						</div>
					</div>
				</div>
					@else
				<div class="form-group">
					<label for="c_shipping_to_store" class="text-black">{{$LANGUAGE==='EN'?'Ship to Store':'配送至店家'}}</label>
					<input type="text" readonly="true" class="form-control" value="{{$shippingStore[2]}}, {{$shippingStore[0]}}, {{$shipping_country->Country}}" />
				</div>
					@endif
					@if(!\Session::has('MemberId'))
				<div class="form-group">
					<label for="c_create_account" class="text-black" data-toggle="collapse" href="#create_an_account" role="button" aria-expanded="false" aria-controls="create_an_account"><input type="checkbox" value="1" id="c_create_account" name="CreateAccount" {{old('CreateAccount')==1?'checked=true':''}}> {{$LANGUAGE==='EN'?'Create an account?':'成为会员?'}}</label>
					<div @if(old('CreateAccount')!=1) class="collapse" @endif id="create_an_account">
						<div class="py-2">
							@if($LANGUAGE==='EN')
							<p class="mb-3">Create an account by entering the information below.</p>
							@else
							<p class="mb-3">完成下列资讯栏位即可成为会员。</p>
							@endif
							<div class="form-group">
								<label for="c_account_password" class="text-black">Account Password</label>                                @if($errors->has('MemberPassword'))
								<p class="mb-0 mt-0 text-danger">{{$errors->first('MemberPassword')}}</p>
								@endif
								<input type="password" class="form-control" id="c_account_password" name="MemberPassword" />
							</div>
						</div>
					</div>
				</div>
					@endif
				<div class="form-group">
					<label for="c_order_notes" class="text-black">{{$LANGUAGE==='EN'?'Order Notes':'订单备注'}}</label>
					<textarea name="Notes" id="c_order_notes" cols="30" rows="5" class="form-control" placeholder="{{$LANGUAGE==='EN'?'Write your notes here...':'请留下您的备注...'}}">{{ old('Notes') }}</textarea>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<!--
			<div class="row mb-5">
				<div class="col-md-12">
					<h2 class="h3 mb-3 text-black font-heading-serif">Coupon Code</h2>
					<div class="p-3 p-lg-5 border">
						<label for="c_code" class="text-black mb-3">Enter your coupon code if you have one</label>
						<div class="input-group w-75">
							<input type="text" class="form-control" id="c_code" placeholder="Coupon Code" aria-label="Coupon Code" aria-describedby="button-addon2">
							<div class="input-group-append">
								<button class="btn btn-primary btn-sm rounded px-4" type="button" id="button-addon2">Apply</button>
							</div>
						</div>
					</div>
				</div>
			</div> -->
			<div class="row mb-4">
				<div class="col-12">
					<h2 class="h3 mb-3 text-black font-heading-serif">{{$LANGUAGE==='EN'?'Your Order':'您的订单内容'}}</h2>
					<div class="p-3 p-lg-5 border">
						<table class="table site-block-order-table mb-5">
							<thead>
								<th>{{$LANGUAGE==='EN'?'Product':'商品'}}</th>
								<th class="text-right">{{$LANGUAGE==='EN'?'Total':'金额'}}</th>
							</thead>
							<tbody>
								@foreach($orderSection as $product)
								<tr>
									<td>{{$product['name']}} <strong class="mx-2">x</strong> {{$product['quantity']}}</td>                                    <td class="text-right">${{$product['subtotal']}}</td>
								</tr>
								@endforeach
								<tr>
									<td class="text-black font-weight-bold"><strong>{{$LANGUAGE==='EN'?'Cart Subtotal':'小计'}}</strong></td>
									<td class="text-black text-right">${{$prices['beforeTotal']}}</td>
								</tr>
								<tr>
									<td class="text-black font-weight-bold"><strong>{{$LANGUAGE==='EN'?'GST':'商品与服务税'}}</strong></td>
									<td class="text-black text-right">${{$prices['GST_Price']}}</td>
								</tr>
								<tr>
									<td class="text-black font-weight-bold"><strong>{{$LANGUAGE==='EN'?'Delivery Cost':'运费'}}</strong></td>
									<td class="text-black text-right">${{$prices['DeliveryCost']}}</td>
								</tr>
								<tr>
									<td class="text-black font-weight-bold"><strong>{{$LANGUAGE==='EN'?'Order Total':'订单金额'}}</strong></td>
									<td class="text-black font-weight-bold text-right"><strong>${!! $prices['afterTotal'] !!}</strong></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="row mb-4">
				<div class="col-12">
					<h2 class="h3 mb-3 text-black font-heading-serif">{{$LANGUAGE==='EN'?'Payment':'付费方式'}}</h2>
					<div class="p-3 p-lg-5 border">
						<select class="form-control" name="Paymethod">
							<option value="Card" {{trim(old('Paymethod'))===''||old('Paymethod')==='Card'?'selected=true':''}}>{{$LANGUAGE==='EN'?'Card':'刷卡'}}</option>
							<option value="QRCode_Alipay" {{old('Paymethod')==='QRCode_Alipay'?'selected=true':''}}>QRCode (Alipay)</option>
							<option value="QRCode_WechatPay" {{old('Paymethod')==='QRCode_WechatPay'?'selected=true':''}}>QRCode (WeChatPay)</option>
						</select>
					</div>
				</div>				
			</div>
			<input type="hidden" name="_token" value="{{csrf_token()}}" />
			@if($LANGUAGE==='EN')
			<input type="submit" onclick="return confirm('Sure to Checkout?')" value='Checkout' class='btn btn-primary btn-lg btn-block' />
			@else
			<input type="submit" onclick="return confirm('确定下单?')" value='结帐' class='btn btn-primary btn-lg btn-block' />
			@endif
		</div>
	</form>
</div>
@endsection
