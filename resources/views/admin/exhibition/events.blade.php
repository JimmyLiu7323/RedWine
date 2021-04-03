@extends('admin.layouts.main')
@section('content')
<style type="text/css">
#list-table tr td{word-break:break-all}
#list-table tr th,#list-table tr td{padding:10px}
#list-table tr th:nth-of-type(1){width:35%}
#list-table tr th:nth-of-type(2){width:25%}
#list-table tr th:nth-of-type(3){width:20%}
#list-table tr th:nth-of-type(4){width:5%}
#list-table tr th:nth-of-type(5){width:5%}
#list-table tr th:nth-of-type(6){width:10%}
</style>
<div class="row">
    <div class="col-xs-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">{{$header_title}}</div>
                <ul class="card-action">
                    <li>
                        <a href="/admin/exhibition/{{\Request::segment(3)}}/add" class="btn btn-primary">New Exhibition</a>
                    </li>
                </ul>                
            </div>  
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-striped table-bordered table-hover" width="100%" id="list-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Date</th>
                                    <th>Capacity</th>
                                    <th>Free</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($events as $event)
                                <tr>
                                    <td>{{$event->EventName}}</td>
                                    <td>{{$event->EventDateTime_Start}}~{{$event->EventDateTime_End}}</td>
                                    <td>{{$event->AllCapacity}}</td>
                                    <td>{{intval($event->Free)===0?'No':'Yes'}}</td>
                                    <td>{{intval($event->Status)===0?'Disabled':'Enabled'}}</td>
                                    <td>
                                        <p>
                                            <a href="/admin/exhibition/{{\Request::segment(3)}}/edit?id={{$event->EventId}}" class="btn-sm btn-info">Edit</a>
                                        </p>
                                        <p>
                                            <a href="/admin/exhibition/{{\Request::segment(3)}}/attendees?id={{$event->EventId}}" class="btn-sm btn-warning">Attendees</a>
                                        </p>
                                        <p>
                                            <a href="/admin/exhibition/{{\Request::segment(3)}}/delete?id={{$event->EventId}}" class="btn-sm btn-danger" onclick="return confirm('Sure to delete this event (including records of attendees?)')">Delete</a>
                                        </p>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if(count($events)>0)
                        {{$events->appends(request()->query())->links()}}
                        @endif
                    </div>                                
                </div>
            </div>
        </div>
    </div>
</div>
@endsection