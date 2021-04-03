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
    <div class="col-xs-12">
        <div class="card">
            <div class="card-body">
                <form class="form form-horizontal" id='product_form' method="POST" enctype="multipart/form-data">
                    <div class="section">
                        <div class="section-body">
                            <div class="form-group">
                                <label class="col-md-2 control-label">Catering Name</label>
                                <div class="col-md-10">
                                    @if($errors->has('Catering'))
                                    <small class="small_form_error">{{$errors->first('Catering')}}</small>
                                    @endif
                                    <input type="text" name="Catering" value="{{old('Catering')}}" class="form-control" maxlength="40" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Catering Name(En)</label>
                                <div class="col-md-10">
                                    @if($errors->has('CateringEn'))
                                    <small class="small_form_error">{{$errors->first('CateringEn')}}</small>
                                    @endif
                                    <input type="text" name="CateringEn" value="{{old('CateringEn')}}" class="form-control" maxlength="40" />
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label class="col-md-2 control-label">Catering Img</label>
                                <div class="col-md-10">
                                    @if($errors->has('CateringPic'))
                                    <small class="small_form_error">{{$errors->first('CateringPic')}}</small>
                                    @endif                                    
                                    <div class="row">
                                        <div class="col-xs-8">
                                            <input type="text" name="CateringPic" class="form-control" readonly="true" id="CateringPic" value="{{old('CateringPic')}}" />
                                        </div>
                                        <div class="col-xs-4">
                                            <button class="popup_selector btn btn-info" data-inputid="CateringPic" style="width:100%">Select Img</button>
                                        </div>
                                    </div>
                                </div>
                            </div>                        
                            <div class="form-group">
                                <label class="col-md-2 control-label">Enable</label>
                                <div class="col-md-10">
                                    @if($errors->has('Status'))
                                    <small class="small_form_error">{{$errors->first('Status')}}</small>
                                    @endif
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <div class="radio radio-inline">
                                                <input type="radio" id="enabled" name="Status" value=1 {{old('Status')!=''&&old('Status')==1?'checked=true':''}}>
                                                <label for="enabled">Yes</label>
                                            </div>
                                        </div>
                                        <div class="col-xs-3">
                                            <div class="radio radio-inline">
                                                <input type="radio" id="disabled" name="Status" value=0 {{old('Status')!=''&&old('Status')==0?'checked=true':''}}>
                                                <label for="disabled">No</label>
                                            </div>
                                        </div>
                                    </div>  
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Note</label>
                                <div class="col-md-10">
                                    @if($errors->has('Memo'))
                                    <small class="small_form_error">{{$errors->first('Memo')}}</small>
                                    @endif                                    
                                    <textarea name="Memo" class="form-control" rows="4" style="resize:vertical" maxlength="100">{!! old('Memo') !!}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="_token" id="csrf-token" value="{{csrf_token()}}" />
                    <div class="form-footer">
                        <div class="form-group">
                            <div class="col-md-12">
                                <button type='submit' class='btn btn-primary'>Save</button>
                                <a href="/admin/products/caterings" class="btn btn-default">Cancel</a>
                            </div>
                        </div>
                    </div>              
                </form>
            </div>
        </div>
    </div>
</div>
@endsection