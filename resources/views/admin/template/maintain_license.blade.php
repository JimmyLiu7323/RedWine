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
                <div class="card-header">
                    <div class="card-title">Footer license</div>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="col-md-2 control-label">Display</label>
                        <div class="col-md-10">
                            @if($errors->has('display_footer_license'))
                            <small class="small_form_error">{{$errors->first('display_footer_license')}}</small>
                            @endif
                            <div class="row">
                                <div class="col-xs-3">
                                    <div class="radio radio-inline">
                                        <input type="radio" id="Display" name="display_footer_license" value=1 {{intval(old('display_footer_license',$display_footer_license))===1?'checked=true':''}}>
                                        <label for="Display">Display</label>
                                    </div>
                                </div>
                                <div class="col-xs-3">
                                    <div class="radio radio-inline">
                                        <input type="radio" id="Hide" name="display_footer_license" value=0 {{intval(old('display_footer_license',$display_footer_license))===0?'checked=true':''}}>
                                        <label for="Hide">Hide</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">License File</label>
                        <div class="col-md-10">
                            @if($errors->has('footer_license'))
                            <small class="small_form_error">{{$errors->first('footer_license')}}</small>
                            @endif                                    
                            <div class="row">
                                <div class="col-xs-8">
                                    <input type="text" name="footer_license" class="form-control" readonly="true" id="footer_license" value="{{old('footer_license',$footer_license)}}" />
                                </div>
                                <div class="col-xs-4">
                                    <button class="popup_selector btn btn-info" data-inputid="footer_license" style="width:100%">Select License</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Display license number</label>
                        <div class="col-md-10">
                            @if($errors->has('display_footer_license_number'))
                            <small class="small_form_error">{{$errors->first('display_footer_license_number')}}</small>
                            @endif
                            <div class="row">
                                <div class="col-xs-3">
                                    <div class="radio radio-inline">
                                        <input type="radio" id="Display_footer_license_number" name="display_footer_license_number" value=1 {{intval(old('display_footer_license_number',$display_footer_license_number))===1?'checked=true':''}}>
                                        <label for="Display_footer_license_number">Display</label>
                                    </div>
                                </div>
                                <div class="col-xs-3">
                                    <div class="radio radio-inline">
                                        <input type="radio" id="Hide_footer_license_number" name="display_footer_license_number" value=0 {{intval(old('display_footer_license_number',$display_footer_license_number))===0?'checked=true':''}}>
                                        <label for="Hide_footer_license_number">Hide</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">License number</label>
                        <div class="col-md-10">
                            @if($errors->has('footer_license_number'))
                            <small class="small_form_error">{{$errors->first('footer_license_number')}}</small>
                            @endif
                            <input type="text" name="footer_license_number" class="form-control" value="{{old('footer_license_number',$footer_license_number)}}" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Display expires date</label>
                        <div class="col-md-10">
                            @if($errors->has('display_footer_license_expires'))
                            <small class="small_form_error">{{$errors->first('display_footer_license_expires')}}</small>
                            @endif
                            <div class="row">
                                <div class="col-xs-3">
                                    <div class="radio radio-inline">
                                        <input type="radio" id="Display_footer_license_expires" name="display_footer_license_expires" value=1 {{intval(old('display_footer_license_expires',$display_footer_license_expires))===1?'checked=true':''}}>
                                        <label for="Display_footer_license_expires">Display</label>
                                    </div>
                                </div>
                                <div class="col-xs-3">
                                    <div class="radio radio-inline">
                                        <input type="radio" id="Hide_footer_license_expires" name="display_footer_license_expires" value=0 {{intval(old('display_footer_license_expires',$display_footer_license_expires))===0?'checked=true':''}}>
                                        <label for="Hide_footer_license_expires">Hide</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Expires (d/m/y)</label>
                        <div class="col-md-10">
                            @if($errors->has('footer_license_expires'))
                            <small class="small_form_error">{{$errors->first('footer_license_expires')}}</small>
                            @endif
                            <input type="text" name="footer_license_expires" class="form-control" value="{{old('footer_license_expires',$footer_license_expires)}}" />
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