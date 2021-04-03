@extends('admin.layouts.main')
@section('content')
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
                                <label class="col-md-2 control-label">Display</label>
                                <div class="col-md-10">
                                    @if($errors->has('display_menu_featured_image'))
                                    <small class="small_form_error">{{$errors->first('display_menu_featured_image')}}</small>
                                    @endif
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <div class="radio radio-inline">
                                                <input type="radio" id="Display" name="display_menu_featured_image" value=1 {{intval(old('display_menu_featured_image',$display_menu_featured_image))===1?'checked=true':''}}>
                                                <label for="Display">Display</label>
                                            </div>
                                        </div>
                                        <div class="col-xs-3">
                                            <div class="radio radio-inline">
                                                <input type="radio" id="Hide" name="display_menu_featured_image" value=0 {{intval(old('display_menu_featured_image',$display_menu_featured_image))===0?'checked=true':''}}>
                                                <label for="Hide">Hide</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Featured image</label>
                                <div class="col-md-10">
                                    @if($errors->has('menu_featured_image'))
                                    <small class="small_form_error">{{$errors->first('menu_featured_image')}}</small>
                                    @endif                                    
                                    <div class="row">
                                        <div class="col-xs-8">
                                            <input type="text" name="menu_featured_image" class="form-control" readonly="true" id="menu_featured_image" value="{{old('menu_featured_image',$menu_featured_image)}}" />
                                        </div>
                                        <div class="col-xs-4">
                                            <button class="popup_selector btn btn-info" data-inputid="menu_featured_image" style="width:100%">Select Image</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Link of image</label>
                                <div class="col-md-10">
                                    @if($errors->has('menu_featured_image_link'))
                                    <small class="small_form_error">{{$errors->first('menu_featured_image_link')}}</small>
                                    @endif                                    
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <input type="text" name="menu_featured_image_link" class="form-control" id="menu_featured_image_link" value="{{old('menu_featured_image_link',$menu_featured_image_link)}}" placeholder="Type correct url here" />
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