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
                                    @if($errors->has('VarietyMainImage'))
                                    <small class="small_form_error">{{$errors->first('VarietyMainImage')}}</small>
                                    @endif
                                    <div class="row">
                                        <div class="col-xs-8">
                                            <input type="text" name="VarietyMainImage" class="form-control" readonly="true" id="VarietyMainImage" value="{{old('VarietyMainImage',$varietyInfo->VarietyMainImage)}}" />
                                        </div>
                                        <div class="col-xs-4">
                                            <button class="popup_selector btn btn-info" type="button" data-inputid="VarietyMainImage" style="width:100%">Select Img</button>
                                        </div>
                                    </div>
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label class="col-md-2 control-label">Variety</label>
                                <div class="col-md-10">
                                    @if($errors->has('Variety'))
                                    <small class="small_form_error">{{$errors->first('Variety')}}</small>
                                    @endif                                    
                                    <input type="text" name="Variety" value="{{old('Variety',$varietyInfo->Variety)}}" class="form-control" maxlength="40" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Variety(En)</label>
                                <div class="col-md-10">
                                    @if($errors->has('VarietyEn'))
                                    <small class="small_form_error">{{$errors->first('VarietyEn')}}</small>
                                    @endif                                    
                                    <input type="text" name="VarietyEn" value="{{old('VarietyEn',$varietyInfo->VarietyEn)}}" class="form-control" maxlength="40" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Description</label>
                                <div class="col-md-10">
                                    @if($errors->has('VarietyDesc'))
                                    <small class="small_form_error">{{$errors->first('VarietyDesc')}}</small>
                                    @endif
                                    <textarea class="form-control" name="VarietyDesc" rows="3" style="resize:none">{!! old('VarietyDesc',$varietyInfo->VarietyDesc) !!}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Description(En)</label>
                                <div class="col-md-10">
                                    @if($errors->has('VarietyDescEn'))
                                    <small class="small_form_error">{{$errors->first('VarietyDescEn')}}</small>
                                    @endif
                                    <textarea class="form-control" name="VarietyDescEn" rows="3" style="resize:none">{!! old('VarietyDescEn',$varietyInfo->VarietyDescEn) !!}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Order</label>
                                <div class="col-md-10">
                                    @if($errors->has('OrderNumber'))
                                    <small class="small_form_error">{{$errors->first('OrderNumber')}}</small>
                                    @endif                                    
                                    <input type="number" name="OrderNumber" value="{{old('OrderNumber',$varietyInfo->OrderNumber)}}" class="form-control" step="1" min="1" />
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
                                                <input type="radio" id="enabled" name="Status" value=1 {{intval(old('Status',$varietyInfo->Status))===1?'checked=true':''}}>
                                                <label for="enabled">Yes</label>
                                            </div>
                                        </div>
                                        <div class="col-xs-3">
                                            <div class="radio radio-inline">
                                                <input type="radio" id="disabled" name="Status" value=0 {{intval(old('Status',$varietyInfo->Status))===0?'checked=true':''}}>
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
                                <a href="/admin/products/varieties" class="btn btn-default">Cancel</a>
                            </div>
                        </div>
                    </div>              
                </form>
            </div>
        </div>
    </div>
</div>
@endsection