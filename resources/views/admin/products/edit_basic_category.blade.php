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
                        		<label class="col-md-2 control-label">Maintain</label>
                        		<div class="col-md-10">
                        			<input type="text" readonly="true" value="{{ucfirst($categoryInfo->Category)}}" class="form-control" />
                        		</div>
                        	</div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Impact Image</label>
                                <div class="col-md-10">
                                    @if($errors->has('Image'))
                                    <small class="small_form_error">{{$errors->first('Image')}}</small>
                                    @endif
                                    <div class="row">
                                        <div class="col-xs-8">
                                            <input type="text" name="Image" class="form-control" readonly="true" id="Image" value="{{old('Image',$categoryInfo->Image)}}" />
                                        </div>
                                        <div class="col-xs-4">
                                            <button class="popup_selector btn btn-info" type="button" data-inputid="Image" style="width:100%">Select Img</button>
                                        </div>
                                    </div>
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label class="col-md-2 control-label">Description</label>
                                <div class="col-md-10">
                                    @if($errors->has('Description'))
                                    <small class="small_form_error">{{$errors->first('Description')}}</small>
                                    @endif                                    
                                    <textarea name="Description" class="form-control" rows="4" style="resize:vertical">{!! old('Description',$categoryInfo->Description) !!}</textarea> 
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Description(En)</label>
                                <div class="col-md-10">
                                    @if($errors->has('DescriptionEn'))
                                    <small class="small_form_error">{{$errors->first('DescriptionEn')}}</small>
                                    @endif                                    
                                    <textarea name="DescriptionEn" class="form-control" rows="4" style="resize:vertical">{!! old('DescriptionEn',$categoryInfo->DescriptionEn) !!}</textarea> 
                                </div>
                            </div>                            
                        </div>
                    </div>
                    <input type="hidden" name="_token" id="csrf-token" value="{{csrf_token()}}" />
                    <div class="form-footer">
                        <div class="form-group">
                            <div class="col-md-12">
                                <button type='submit' class='btn btn-primary'>Save</button>
                                <a href="/admin/products/basic_category" class="btn btn-default">Cancel</a>
                            </div>
                        </div>
                    </div>              
                </form>
            </div>
        </div>
    </div>
</div>
@endsection