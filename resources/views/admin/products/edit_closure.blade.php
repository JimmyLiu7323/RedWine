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
                                <label class="col-md-2 control-label">Packing Method</label>
                                <div class="col-md-10">
                                    @if($errors->has('Closure'))
                                    <small class="small_form_error">{{$errors->first('Closure')}}</small>
                                    @endif
                                    <input type="text" name="Closure" value="{{old('Closure',$closureInfo->Closure)}}" class="form-control" maxlength="40" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Packing Method(En)</label>
                                <div class="col-md-10">
                                    @if($errors->has('ClosureEn'))
                                    <small class="small_form_error">{{$errors->first('ClosureEn')}}</small>
                                    @endif
                                    <input type="text" name="ClosureEn" value="{{old('ClosureEn',$closureInfo->ClosureEn)}}" class="form-control" maxlength="40" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Order</label>
                                <div class="col-md-10">
                                    @if($errors->has('OrderNumber'))
                                    <small class="small_form_error">{{$errors->first('OrderNumber')}}</small>
                                    @endif                                    
                                    <input type="number" name="OrderNumber" value="{{old('OrderNumber',$closureInfo->OrderNumber)}}" class="form-control" step="1" min="1" />
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label class="col-md-2 control-label">Enable</label>
                                <div class="col-md-10">
                                    @if($errors->has('Status'))
                                    <small class="small_form_error">{{$errors->first('Status')}}</small>
                                    @endif
                                    <div class="row">
                                        @if(trim(old('Status'))!=='')
                                        <div class="col-xs-3">
                                            <div class="radio radio-inline">
                                                <input type="radio" id="enabled" name="Status" value=1 {{old('Status')==1?'checked=true':''}}>
                                                <label for="enabled">Yes</label>
                                            </div>
                                        </div>
                                        <div class="col-xs-3">
                                            <div class="radio radio-inline">
                                                <input type="radio" id="disabled" name="Status" value=0 {{old('Status')==0?'checked=true':''}}>
                                                <label for="disabled">No</label>
                                            </div>
                                        </div>
                                        @else
                                        <div class="col-xs-3">
                                            <div class="radio radio-inline">
                                                <input type="radio" id="enabled" name="Status" value=1 {{$closureInfo->Status==1?'checked=true':''}}>
                                                <label for="enabled">Yes</label>
                                            </div>
                                        </div>
                                        <div class="col-xs-3">
                                            <div class="radio radio-inline">
                                                <input type="radio" id="disabled" name="Status" value=0 {{$closureInfo->Status==0?'checked=true':''}}>
                                                <label for="disabled">No</label>
                                            </div>
                                        </div>                                        
                                        @endif
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
                                <a href="/admin/products/closures" class="btn btn-default">Cancel</a>
                            </div>
                        </div>
                    </div>              
                </form>
            </div>
        </div>
    </div>
</div>
@endsection