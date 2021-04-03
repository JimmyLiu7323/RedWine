@extends('admin.layouts.main')
@section('content')
@if(\Session::has('maintain_message_fail'))
<script>
Swal.fire({
    type:'error',
    title:'Oops...',
    customClass:'swal-wide',
    text:"{!! \Session::get('maintain_message_fail') !!}",
})
</script>
@elseif(\Session::has('maintain_message_warning'))
<script>
Swal.fire({
    type:'warning',
    title:'警告',
    customClass:'swal-wide',
    text:"{!! \Session::get('maintain_message_warning') !!}",
})
</script>
@endif
<style type="text/css">
.form-group{margin-top:10px;margin-bottom:10px}
</style>
<div class="row">
    <form class="form form-horizontal" id='product_form' method="POST" enctype="multipart/form-data">
        <div class="col-xs-12">
            <div class="card">
                <div class="card-body">
                    <div class="section">
                        <div class="section-body">
                            <div class="form-group">
                                <label class="col-md-2 control-label">Impact image(Events)</label>
                                <div class="col-md-10">
                                    @if($errors->has('events_image'))
                                    <small class="small_form_error">{{$errors->first('events_image')}}</small>
                                    @endif                                    
                                    <div class="row">
                                        <div class="col-xs-8">
                                            <input type="text" name="events_image" class="form-control" readonly="true" id="events_image" value="{{old('events_image',$events_image)}}" />
                                        </div>
                                        <div class="col-xs-4">
                                            <button class="popup_selector btn btn-info" data-inputid="events_image" style="width:100%">Select Img</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Impact image(Visits)</label>
                                <div class="col-md-10">
                                    @if($errors->has('visits_image'))
                                    <small class="small_form_error">{{$errors->first('visits_image')}}</small>
                                    @endif                                    
                                    <div class="row">
                                        <div class="col-xs-8">
                                            <input type="text" name="visits_image" class="form-control" readonly="true" id="visits_image" value="{{old('visits_image',$visits_image)}}" />
                                        </div>
                                        <div class="col-xs-4">
                                            <button class="popup_selector btn btn-info" data-inputid="visits_image" style="width:100%">Select Img</button>
                                        </div>
                                    </div>
                                </div>
                            </div>                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="_token" id="csrf-token" value="{{csrf_token()}}" />
        <div class="col-xs-12" style="margin-top:20px">
            <button type='submit' class='btn btn-primary'>Save</button>            
        </div>
    </form>
</div>
@endsection