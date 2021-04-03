@extends('admin.layouts.main')
@section('content')
<div class="row">
    <div class="col-xs-12">
        <form class="form form-horizontal" method="POST">
            <div class="card">
                <div class="card-body">
                    <div class="section">
                        <div class="section-title">Basic information</div>
                        <div class="section-body">
                            <div class="form-group">
                                <label class="col-md-3 control-label">ID</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" readonly="true" value="{{$orderInfo->OrderId}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Pay method</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" readonly="true" value="@if($orderInfo->Paymethod==='Card'){{'Credit card'}}@endif">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Pick method</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" readonly="true" value="@if($orderInfo->osType==='HOME'){{'Send to home'}}@else{{'Pick at store'}}@endif">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Subtotal</label>                                
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <span class="input-group-addon">$</span>
                                        <input type="text" class="form-control" readonly="true" value="{{number_format($orderInfo->Subtotal,2)}}" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">GST</label>
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <span class="input-group-addon">$</span>
                                        <input type="text" class="form-control" readonly="true" value="{{number_format($orderInfo->GST,2)}}" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Delivery fee</label>
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <span class="input-group-addon">$</span>
                                        <input type="text" class="form-control" readonly="true" value="{{number_format($orderInfo->DeliveryCost,2)}}" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">
                                    @if($orderInfo->MemberId){{'Total'}}<p class="control-label-help">( Has member discount - {{(100 - floatval($orderInfo->MemberDiscount)).'% off'}} )</p>
                                    @else{{'Total'}}
                                    @endif
                                </label>
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <span class="input-group-addon">$</span>
                                        <input type="text" class="form-control" readonly="true" value="{{number_format($orderInfo->Total,2)}}" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Notes</label>
                                <div class="col-md-9">
                                    <textarea class="form-control" readonly="true">{!! $orderInfo->Notes !!}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Status</label>
                                <div class="col-md-9">
                                    <div>
                                      <div class="radio radio-inline">
                                          <input type="radio" name="Status" id="radio1" value=-1 {{intval($orderInfo->Status)===-1?'checked=true':''}} />
                                          <label for="radio1">Unpaid</label>
                                      </div>
                                      <div class="radio radio-inline">
                                          <input type="radio" name="Status" id="radio2" value=0 {{intval($orderInfo->Status)===0?'checked=true':''}} />
                                          <label for="radio2">Paid</label>
                                      </div>
                                      <div class="radio radio-inline">
                                          <input type="radio" name="Status" id="radio3" value=1 {{intval($orderInfo->Status)===2?'checked=true':''}} />
                                          <label for="radio3">Shipped</label>
                                      </div>                                      
                                    </div>                                    
                                </div>
                            </div>
                        </div>
                        @if($orderInfo->Paymethod==='Card'&&intval($orderInfo->Status)===0)
                        <div class="section-title">Information of credit card</div>
                        <div class="section-body">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Card holder</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" readonly="true" value="{{$orderInfo->CardHolder}}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Card name</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" readonly="true" value="{{$orderInfo->CardName}}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Card number</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" readonly="true" value="{{$orderInfo->CardNumber}}" />
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    @if($orderInfo->MemberId)
                    <div class="section">
                        <div class="section-title">Member information</div>
                        <div class="section-body">
                            @if(isset(\Config::get('app.countries')[$orderInfo->Country]))
                            <div class="form-group">
                                <label class="col-md-3 control-label">From</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" readonly="true" value="{{\Config::get('app.countries')[$orderInfo->Country]}}" />
                                </div>
                            </div>
                            @endif
                            <div class="form-group">
                                <label class="col-md-3 control-label">First name</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" readonly="true" value="{{$orderInfo->FirstName}}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Middle name</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" readonly="true" value="{{$orderInfo->MiddleName}}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Last name</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" readonly="true" value="{{$orderInfo->LastName}}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Email</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" readonly="true" value="{{$orderInfo->Email}}" />
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="card" style="margin-top:20px">
                <div class="card-body">
                    <div class="section">
                        <div class="section-title">Billing information</div>
                        <div class="section-body">
                            <div class="form-group">
                                <label class="col-md-3 control-label">First name</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" readonly="true" value="{{$orderInfo->obFirst}}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Middle name</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" readonly="true" value="{{$orderInfo->obMiddle}}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Last name</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" readonly="true" value="{{$orderInfo->obLast}}" />
                                </div>
                            </div>
                            @if($orderInfo->obCompany)
                            <div class="form-group">
                                <label class="col-md-3 control-label">Company</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" readonly="true" value="{{$orderInfo->obCompany}}" />
                                </div>
                            </div>
                            @endif
                            @if(isset(\Config::get('app.countries')[$orderInfo->obCountry]))
                            <div class="form-group">
                                <label class="col-md-3 control-label">Country</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" readonly="true" value="{{\Config::get('app.countries')[$orderInfo->obCountry]}}" />
                                </div>
                            </div>
                            @endif                            
                            <div class="form-group">
                                <label class="col-md-3 control-label">City</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" readonly="true" value="{{$orderInfo->obCity}}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Region</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" readonly="true" value="{{$orderInfo->obRegion}}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Address</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" readonly="true" value="{{$orderInfo->obAddress}}" />
                                </div>
                            </div>
                            @if($orderInfo->obAddress2)
                            <div class="form-group">
                                <label class="col-md-3 control-label">Address2</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" readonly="true" value="{{$orderInfo->obAddress2}}" />
                                </div>
                            </div>
                            @endif
                            <div class="form-group">
                                <label class="col-md-3 control-label">Postal code</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" readonly="true" value="{{$orderInfo->obPostCode}}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Email</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" readonly="true" value="{{$orderInfo->obEmail}}" />
                                </div>
                            </div> 
                            <div class="form-group">
                                <label class="col-md-3 control-label">Telephone</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" readonly="true" value="{{$orderInfo->obTelephone}}" />
                                </div>
                            </div>                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="card" style="margin-top:20px">
                <div class="card-body">
                    <div class="section">
                        <div class="section-title">Shipping information</div>
                        <div class="section-body">
                            <div class="form-group">
                                <label class="col-md-3 control-label">First name</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" readonly="true" value="{{$orderInfo->osFirst}}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Middle name</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" readonly="true" value="{{$orderInfo->osMiddle}}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Last name</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" readonly="true" value="{{$orderInfo->osLast}}" />
                                </div>
                            </div>
                            @if($orderInfo->osCompany)
                            <div class="form-group">
                                <label class="col-md-3 control-label">Company</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" readonly="true" value="{{$orderInfo->osCompany}}" />
                                </div>
                            </div>
                            @endif
                            @if(isset(\Config::get('app.countries')[$orderInfo->osCountry]))
                            <div class="form-group">
                                <label class="col-md-3 control-label">Country</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" readonly="true" value="{{\Config::get('app.countries')[$orderInfo->osCountry]}}" />
                                </div>
                            </div>
                            @endif                            
                            <div class="form-group">
                                <label class="col-md-3 control-label">City</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" readonly="true" value="{{$orderInfo->osCity}}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Region</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" readonly="true" value="{{$orderInfo->osRegion}}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Address</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" readonly="true" value="{{$orderInfo->osAddress}}" />
                                </div>
                            </div>
                            @if($orderInfo->osAddress2)
                            <div class="form-group">
                                <label class="col-md-3 control-label">Address2</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" readonly="true" value="{{$orderInfo->osAddress2}}" />
                                </div>
                            </div>
                            @endif
                            <div class="form-group">
                                <label class="col-md-3 control-label">Postal code</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" readonly="true" value="{{$orderInfo->osPostCode}}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Email</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" readonly="true" value="{{$orderInfo->osEmail}}" />
                                </div>
                            </div> 
                            <div class="form-group">
                                <label class="col-md-3 control-label">Telephone</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" readonly="true" value="{{$orderInfo->osTelephone}}" />
                                </div>
                            </div>                            
                        </div>                      
                    </div>
                </div>
            </div>
            <div class="card" style="margin-top:20px">
                <div class="card-body">
                    <div class="section">
                        <div class="section-title">Contents</div>
                        <div class="section-body">
                            @if(count($orderDetails)>0)
                            <table class="table" width="100%" id="list-table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Type</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orderDetails as $content)
                                    <tr>
                                        <td>{{$content['Product']}}</td>
                                        <td>{{strtoupper($content['Type'])}}</td>
                                        <td>{{$content['Price']}}</td>
                                        <td>x{{$content['Quantity']}}</td>
                                        <td>${{number_format($content['Subtotal'],2)}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @endif
                        </div>
                    </div>
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a class="btn btn-default" href="/admin/shopping/orders">Cancel</a>
                    </div>                    
                </div>  
            </div>
        </form>
    </div>
</div>
@endsection