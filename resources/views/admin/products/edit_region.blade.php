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
                                <label class="col-md-2 control-label">Impact Image</label>
                                <div class="col-md-10">
                                    @if($errors->has('RegionMainImage'))
                                    <small class="small_form_error">{{$errors->first('RegionMainImage')}}</small>
                                    @endif
                                    <div class="row">
                                        <div class="col-xs-8">
                                            <input type="text" name="RegionMainImage" class="form-control" readonly="true" id="RegionMainImage" value="{{old('RegionMainImage',$regionInfo->RegionMainImage)}}" />
                                        </div>
                                        <div class="col-xs-4">
                                            <button class="popup_selector btn btn-info" type="button" data-inputid="RegionMainImage" style="width:100%">Select Img</button>
                                        </div>
                                    </div>
                                </div>
                            </div>                             
                            <div class="form-group">
                                <label class="col-md-2 control-label">Region</label>
                                <div class="col-md-10">
                                    @if($errors->has('Region'))
                                    <small class="small_form_error">{{$errors->first('Region')}}</small>
                                    @endif                                    
                                    <input type="text" name="Region" value="{{old('Region',$regionInfo->Region)}}" class="form-control" maxlength="40" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Region(En)</label>
                                <div class="col-md-10">
                                    @if($errors->has('RegionEn'))
                                    <small class="small_form_error">{{$errors->first('RegionEn')}}</small>
                                    @endif                                    
                                    <input type="text" name="RegionEn" value="{{old('RegionEn',$regionInfo->RegionEn)}}" class="form-control" maxlength="40" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Description</label>
                                <div class="col-md-10">
                                    @if($errors->has('RegionDesc'))
                                    <small class="small_form_error">{{$errors->first('RegionDesc')}}</small>
                                    @endif
                                    <textarea class="form-control" name="RegionDesc" rows="3" style="resize:none">{!! old('RegionDesc',$regionInfo->RegionDesc) !!}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Description(En)</label>
                                <div class="col-md-10">
                                    @if($errors->has('RegionDescEn'))
                                    <small class="small_form_error">{{$errors->first('RegionDescEn')}}</small>
                                    @endif
                                    <textarea class="form-control" name="RegionDescEn" rows="3" style="resize:none">{!! old('RegionDescEn',$regionInfo->RegionDescEn) !!}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Order</label>
                                <div class="col-md-10">
                                    @if($errors->has('OrderNumber'))
                                    <small class="small_form_error">{{$errors->first('OrderNumber')}}</small>
                                    @endif                                    
                                    <input type="number" name="OrderNumber" value="{{old('OrderNumber',$regionInfo->OrderNumber)}}" class="form-control" step="1" min="1" />
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
                                                <input type="radio" id="enabled" name="Status" value=1 {{intval(old('Status',$regionInfo->Status))===1?'checked=true':''}}>
                                                <label for="enabled">Yes</label>
                                            </div>
                                        </div>
                                        <div class="col-xs-3">
                                            <div class="radio radio-inline">
                                                <input type="radio" id="disabled" name="Status" value=0 {{intval(old('Status',$regionInfo->Status))===0?'checked=true':''}}>
                                                <label for="disabled">No</label>
                                            </div>
                                        </div>
                                    </div>  
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="_token" id="csrf-token" value="{{csrf_token()}}" />
                    <div class="form-footer">
                        <div class="form-group">
                            <div class="col-md-12">
                                <button type='submit' class='btn btn-primary'>Save</button>
                                <a href="/admin/products/regions" class="btn btn-default">Cancel</a>
                            </div>
                        </div>
                    </div>              
                </form>
            </div>
        </div>
    </div>
</div>
@endsection