@extends('admin.layouts.main')
@section('content')
<link href='{!! asset('resources/fullcalendar/packages/core/main.css') !!}' rel='stylesheet' />
<link href='{!! asset('resources/fullcalendar/packages/daygrid/main.css') !!}' rel='stylesheet' />

<script src='{!! asset('resources/fullcalendar/packages/core/main.js') !!}'></script>
<script src='{!! asset('resources/fullcalendar/packages/daygrid/main.js') !!}'></script>
<script src='{!! asset('resources/fullcalendar/packages/interaction/main.js') !!}'></script>

<div class="row">
    <div class="col-xs-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">{{$header_title}}</div>            
            </div>  
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                    	<div id='calendar'></div>
                    </div>                                
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
	var calendarEl = document.getElementById('calendar');
	var calendar = new FullCalendar.Calendar(calendarEl, {
		plugins: [ 'dayGrid','interaction' ],
		events: {!! $events !!},
		eventClick: function(info) {
			window.open('/admin/exhibition/visits/edit?id='+info.event.id, '_blank');
		},
		dateClick: function(info) {
			window.open('/admin/exhibition/visits/add?date='+info.dateStr,'_blank')
		}
	});
	calendar.render();
});
</script>
@endsection