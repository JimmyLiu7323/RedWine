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
                                <label class="col-md-2 control-label">Display</label>
                                <div class="col-md-10">
                                    @if($errors->has('display_index_featured_video'))
                                    <small class="small_form_error">{{$errors->first('display_index_featured_video')}}</small>
                                    @endif
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <div class="radio radio-inline">
                                                <input type="radio" id="Display" name="display_index_featured_video" value=1 {{intval(old('display_index_featured_video',$display_index_featured_video))===1?'checked=true':''}}>
                                                <label for="Display">Display</label>
                                            </div>
                                        </div>
                                        <div class="col-xs-3">
                                            <div class="radio radio-inline">
                                                <input type="radio" id="Hide" name="display_index_featured_video" value=0 {{intval(old('display_index_featured_video',$display_index_featured_video))===0?'checked=true':''}}>
                                                <label for="Hide">Hide</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Video source</label>
                                <div class="col-md-10">
                                    @if($errors->has('index_featured_video_source'))
                                    <small class="small_form_error">{{$errors->first('index_featured_video_source')}}</small>
                                    @endif
                                    <select name='index_featured_video_source' class='form-control'>
                                        <option value="Youtube" {{old('index_featured_video_source',$index_featured_video_source)=='Youtube'?'selected=true':''}}>Youtube</option>
                                        <!-- <option value="Self" {{old('index_featured_video_source',$index_featured_video_source)=='Self'?'selected=true':''}}>Self</option> -->
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Video url</label>
                                <div class="col-md-10">
                                    @if($errors->has('index_featured_video_url'))
                                    <small class="small_form_error">{{$errors->first('index_featured_video_url')}}</small>
                                    @endif                                    
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <input type="text" name="index_featured_video_url" class="form-control" id="index_featured_video_url" value="{{old('index_featured_video_url',$index_featured_video_url)}}" placeholder="Select the video file or paste youtube embed code" />
                                        </div>
                                        <!-- <div class="col-xs-4">
                                            <button class="popup_selector btn btn-info" data-inputid="index_featured_video_url" style="width:100%">Select video file</button>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Video description</label>
                                <div class="col-md-10">
                                @if($errors->has('index_featured_video_description'))
                                    <small class='small_form_error'>{{$errors->first('index_featured_video_description')}}</small>
                                @endif
                                    <textarea name='index_featured_video_description' class="form-control" maxlength="500" rows="4" style="resize:vertical">{!! old('index_featured_video_description',$index_featured_video_description) !!}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Video description(En)</label>
                                <div class="col-md-10">
                                @if($errors->has('index_featured_video_description_en'))
                                    <small class='small_form_error'>{{$errors->first('index_featured_video_description_en')}}</small>
                                @endif
                                    <textarea name='index_featured_video_description_en' class="form-control" maxlength="500" rows="4" style="resize:vertical">{!! old('index_featured_video_description_en',$index_featured_video_description_en) !!}</textarea>
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