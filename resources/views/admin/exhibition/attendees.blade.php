@extends('admin.layouts.main')
@section('content')
<div class="row">
    <div class="col-xs-12">
    	<form class="form form-horizontal" method="POST">
	        <div class="card">
	            <div class="card-body">
	                <div class="section">
	                    <div class="section-title">Exhibition information</div>
	                    <div class="section-body">
	                        <div class="form-group">
	                            <label class="col-md-3 control-label">ID</label>
	                            <div class="col-md-9">
	                                <input type="text" class="form-control" readonly="true" value="{{$EventInfo->EventId}}">
	                            </div>
	                        </div>
	                        <div class="form-group">
	                            <label class="col-md-3 control-label">Event Name</label>
	                            <div class="col-md-9">
	                                <input type="text" class="form-control" readonly="true" value="{{$EventInfo->EventName}}">
	                            </div>
	                        </div>
	                        <div class="form-group">
	                            <label class="col-md-3 control-label">Price</label>
	                            <div class="col-md-9">
	                                <input type="text" class="form-control" readonly="true" value="{{intval($EventInfo->Free)===0?number_format($EventInfo->Price,2):'Free'}}">
	                            </div>
	                        </div>
	                        <div class="form-group">
	                            <label class="col-md-3 control-label">Remain Capacity</label>
	                            <div class="col-md-9">
	                                <input type="text" class="form-control" readonly="true" value="{{$EventInfo->AllCapacity - $AttendeesCount}}">
	                            </div>
	                        </div>
	                        <div class="form-group">
	                            <label class="col-md-3 control-label">Date time of Event</label>
	                            <div class="col-md-9">
	                                <input type="text" class="form-control" readonly="true" value="{{$EventInfo->EventDateTime_Start}} ~ {{$EventInfo->EventDateTime_End}}">
	                            </div>
	                        </div>
	                    </div>
	                </div>	                
	            </div>
				<div class="card-body">
	                <div class="section">
	                    <div class="section-title">Attendees</div>
	                    <div class="section-body">
	                        <table class="table table-striped table-bordered table-hover" width="100%" id="list-table">
	                            <thead>
	                                <tr>
	                                    <th>ID</th>
	                                    <th>Name</th>
	                                    <th>Email</th>
	                                    <th>Reservation for</th>
	                                    <th>Paid</th>
	                                </tr>
	                            </thead>
	                            <tbody>
	                            	@foreach($Attendees as $Attendee)
	                            	<tr>
	                            		<td>{{$Attendee->MemberId}}</td>
	                            		<td>{{$Attendee->FirstName}} {{$Attendee->LastName}}</td>
	                            		<td>{{$Attendee->Email}}</td>
	                            		<td>{{$Attendee->Quantity}}</td>
	                            		<td>{{$Attendee->Status==-1?'Unpaid':'Paid'}}</td>
	                            	</tr>
	                            	@endforeach
	                            </tbody>
	                        </table>
	                    </div>
	                </div>
	                <div class="form-footer">
			        	<a href="/admin/exhibition/{{$findEventType}}" class="btn btn-default">Back</a>
			        </div>	                
	            </div>	            
	        </div>
	    </form>
    </div>
</div>
@endsection