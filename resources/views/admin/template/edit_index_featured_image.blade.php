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
@endif
<style type="text/css">.form-group{margin-top:10px;margin-bottom:10px}</style>
<div class="row">
    <div class="col-xs-12">
        <div class="card">
            <div class="card-body">
                <form class="form form-horizontal" id='product_form' method="POST" enctype="multipart/form-data">
                    <div class="section">
                        <div class="section-body">
                            <div class="form-group">
                                <label class="col-md-2 control-label">Image</label>
                                <div class="col-md-10">
                                    @if($errors->has('Image'))
                                    <small class="small_form_error">{{$errors->first('Image')}}</small>
                                    @endif                                    
                                    <div class="row">
                                        <div class="col-xs-8">
                                            <input type="text" name="Image" class="form-control" readonly="true" id="FeaturedImage" value="{{old('Image',$imageInfo->Image)}}" />
                                        </div>
                                        <div class="col-xs-4">
                                            <button class="popup_selector btn btn-info" data-inputid="FeaturedImage" style="width:100%">Select Img</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Image(En)</label>
                                <div class="col-md-10">
                                    @if($errors->has('ImageEn'))
                                    <small class="small_form_error">{{$errors->first('ImageEn')}}</small>
                                    @endif                                    
                                    <div class="row">
                                        <div class="col-xs-8">
                                            <input type="text" name="ImageEn" class="form-control" readonly="true" id="FeaturedImageEn" value="{{old('ImageEn',$imageInfo->ImageEn)}}" />
                                        </div>
                                        <div class="col-xs-4">
                                            <button class="popup_selector btn btn-info" data-inputid="FeaturedImageEn" style="width:100%">Select Img</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Link file</label>
                                <div class="col-md-10">
                                    @if($errors->has('LinkFile'))
                                    <small class="small_form_error">{{$errors->first('LinkFile')}}</small>
                                    @endif                                    
                                    <div class="row">
                                        <div class="col-xs-8">
                                            <input type="text" name="LinkFile" class="form-control" readonly="true" id="LinkFile" value="{{old('LinkFile',$imageInfo->LinkFile)}}" />
                                        </div>
                                        <div class="col-xs-4">
                                            <button class="popup_selector btn btn-warning" data-inputid="LinkFile" style="width:100%">Select File</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Link file(En)</label>
                                <div class="col-md-10">
                                    @if($errors->has('LinkFileEn'))
                                    <small class="small_form_error">{{$errors->first('LinkFileEn')}}</small>
                                    @endif                                    
                                    <div class="row">
                                        <div class="col-xs-8">
                                            <input type="text" name="LinkFileEn" class="form-control" readonly="true" id="LinkFileEn" value="{{old('LinkFileEn',$imageInfo->LinkFileEn)}}" />
                                        </div>
                                        <div class="col-xs-4">
                                            <button class="popup_selector btn btn-warning" data-inputid="LinkFileEn" style="width:100%">Select File</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Title</label>
                                <div class="col-md-10">
                                    @if($errors->has('BigTitle'))
                                    <small class="small_form_error">{{$errors->first('BigTitle')}}</small>
                                    @endif
                                    <input type="text" name="BigTitle" value="{{old('BigTitle',$imageInfo->BigTitle)}}" class="form-control" maxlength="20" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Title(En)</label>
                                <div class="col-md-10">
                                    @if($errors->has('BigTitleEn'))
                                    <small class="small_form_error">{{$errors->first('BigTitleEn')}}</small>
                                    @endif
                                    <input type="text" name="BigTitleEn" value="{{old('BigTitleEn',$imageInfo->BigTitleEn)}}" class="form-control" maxlength="20" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Description</label>
                                <div class="col-md-10">
                                    @if($errors->has('Description'))
                                    <small class="small_form_error">{{$errors->first('Description')}}</small>
                                    @endif
                                    <textarea class="form-control" rows="2" style="resize:none" name="Description" maxlength="100">{!! old('Description',$imageInfo->Description) !!}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Description(En)</label>
                                <div class="col-md-10">
                                    @if($errors->has('DescriptionEn'))
                                    <small class="small_form_error">{{$errors->first('DescriptionEn')}}</small>
                                    @endif
                                    <textarea class="form-control" rows="2" style="resize:none" name="DescriptionEn" maxlength="100">{!! old('DescriptionEn',$imageInfo->DescriptionEn) !!}</textarea>
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
                                                <input type="radio" id="enabled" name="Status" value=1 {{old('Status',$imageInfo->Status)==1?'checked=true':''}}>
                                                <label for="enabled">Yes</label>
                                            </div>
                                        </div>
                                        <div class="col-xs-3">
                                            <div class="radio radio-inline">
                                                <input type="radio" id="disabled" name="Status" value=0 {{old('Status',$imageInfo->Status)==0?'checked=true':''}}>
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
                                <a href="/admin/template/index_featured_image" class="btn btn-default">Cancel</a>
                            </div>
                        </div>
                    </div>              
                </form>
            </div>
        </div>
    </div>
</div>
@endsection