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
                                    @if($errors->has('Image'))
                                    <small class="small_form_error">{{$errors->first('Image')}}</small>
                                    @endif                                    
                                    <div class="row">
                                        <div class="col-xs-8">
                                            <input type="text" name="Image" class="form-control" readonly="true" id="Image" value="{{old('Image',$Image)}}" />
                                        </div>
                                        <div class="col-xs-4">
                                            <button class="popup_selector btn btn-info" data-inputid="Image" style="width:100%">Select Img</button>
                                        </div>
                                    </div>
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label class="col-md-2 control-label">Title</label>
                                <div class="col-md-10">
                                    @if($errors->has('Title'))
                                    <small class="small_form_error">{{$errors->first('Title')}}</small>
                                    @endif
                                    <input type="text" name="Title" value="{{old('Title',$Title)}}" class="form-control" maxlength="255" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Title(En)</label>
                                <div class="col-md-10">
                                    @if($errors->has('TitleEn'))
                                    <small class="small_form_error">{{$errors->first('TitleEn')}}</small>
                                    @endif
                                    <input type="text" name="TitleEn" value="{{old('TitleEn',$TitleEn)}}" class="form-control" maxlength="255" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Brief Description</label>
                                <div class="col-md-10">
                                    @if($errors->has('BriefDesc'))
                                    <small class="small_form_error">{{$errors->first('BriefDesc')}}</small>
                                    @endif
                                    <textarea maxlength="500" name="BriefDesc" class="form-control" rows=3 style="riesize:vertical">{{old('BriefDesc',$BriefDesc)}}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Brief Description(En)</label>
                                <div class="col-md-10">
                                    @if($errors->has('BriefDescEn'))
                                    <small class="small_form_error">{{$errors->first('BriefDescEn')}}</small>
                                    @endif
                                    <textarea maxlength="500" name="BriefDescEn" class="form-control" rows=3 style="riesize:vertical">{{old('BriefDescEn',$BriefDescEn)}}</textarea>
                                </div>
                            </div>                                                        
                            <div class="form-group">
                                <label class="col-md-2 control-label">Category</label>
                                <div class="col-md-10">
                                    @if($errors->has('Category'))
                                    <small class="small_form_error">{{$errors->first('Category')}}</small>
                                    @endif
                                    <select name="Category" class="form-control">
                                        <option value=0 {{intval(old('Category',$Category))===0?'selected=true':''}}>Not Specify</option>
                                        @foreach($CategoryOptions as $CategoryOption)
                                        <option value="{{$CategoryOption->CategoryId}}" {{intval(old('Category',$Category))===intval($CategoryOption->CategoryId)?'selected=true':''}}>{{$CategoryOption->Category}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Tags</label>
                                <div class="col-md-10">
                                    @if($errors->has('Tags'))
                                    <small class="small_form_error">{{$erors->first('Tags')}}</small>
                                    @endif
                                    <input type="text" class="form-control" name="Tags" maxlength="100" value="{{old('Tags',$Tags)}}" style="margin-bottom:0">
                                    <span class="small_form_error">(Use comma for split)</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Tags(En)</label>
                                <div class="col-md-10">
                                    @if($errors->has('TagsEn'))
                                    <small class="small_form_error">{{$erors->first('TagsEn')}}</small>
                                    @endif
                                    <input type="text" class="form-control" name="TagsEn" maxlength="200" value="{{old('TagsEn',$TagsEn)}}" style="margin-bottom:0">
                                    <span class="small_form_error">(Use comma for split)</span>
                                </div>                                
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Enable Date</label>
                                <div class="col-md-10">
                                    @if($errors->has('OnDate'))
                                    <small class="small_form_error">{{$errors->first('OnDate')}}</small>
                                    @endif
                                    <input type="date" class="form-control" name="OnDate" value="{{old('OnDate',$OnDate)}}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Disable Date</label>
                                <div class="col-md-10">
                                    @if($errors->has('OffDate'))
                                    <small class="small_form_error">{{$errors->first('OffDate')}}</small>
                                    @endif
                                    <input type="date" class="form-control" name="OffDate" value="{{old('OffDate',$OffDate)}}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Content</label>
                                <div class="col-md-10">
                                    @if($errors->has('Content'))
                                    <small class="small_form_error">{{$errors->first('Content')}}</small>
                                    @endif
                                    <textarea name="Content" class="form-control" id="Content">{!! old('Content',$Content) !!}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Content(En)</label>
                                <div class="col-md-10">
                                    @if($errors->has('ContentEn'))
                                    <small class="small_form_error">{{$errors->first('ContentEn')}}</small>
                                    @endif
                                    <textarea name="ContentEn" class="form-control" id="ContentEn">{!! old('ContentEn',$ContentEn) !!}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Order</label>
                                <div class="col-md-10">
                                    @if($errors->has('OrderNumber'))
                                    <small class="small_form_error">{{$errors->first('OrderNumber')}}</small>
                                    @endif                                    
                                    <input type="number" name="OrderNumber" value="{{old('OrderNumber',$OrderNumber)}}" class="form-control" step="1" min="1" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Status</label>
                                <div class="col-md-10">
                                    @if($errors->has('Status'))
                                    <small class="small_form_error">{{$errors->first('Status')}}</small>
                                    @endif
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <div class="radio radio-inline">
                                                <input type="radio" id="enabled" name="Status" value=1 {{intval(old('Status',$Status))===1?'checked=true':''}}>
                                                <label for="enabled">Yes</label>
                                            </div>
                                        </div>
                                        <div class="col-xs-3">
                                            <div class="radio radio-inline">
                                                <input type="radio" id="disabled" name="Status" value=0 {{intval(old('Status',$Status))===0?'checked=true':''}}>
                                                <label for="disabled">No</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Sticky</label>
                                <div class="col-md-10">
                                    @if($errors->has('OnTop'))
                                    <small class="small_form_error">{{$errors->first('OnTop')}}</small>
                                    @endif
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <div class="radio radio-inline">
                                                <input type="radio" id="onTop_Y" name="OnTop" value=1 {{intval(old('OnTop',$OnTop))===1?'checked=true':''}}>
                                                <label for="onTop_Y">Yes</label>
                                            </div>
                                        </div>
                                        <div class="col-xs-3">
                                            <div class="radio radio-inline">
                                                <input type="radio" id="onTop_N" name="OnTop" value=0 {{intval(old('OnTop',$OnTop))===0?'checked=true':''}}>
                                                <label for="onTop_N">No</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Show at Home Page</label>
                                <div class="col-md-10">
                                    @if($errors->has('Flagship'))
                                    <small class="small_form_error">{{$errors->first('Flagship')}}</small>
                                    @endif
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <div class="radio radio-inline">
                                                <input type="radio" id="Flagship_Y" name="Flagship" value=1 {{intval(old('Flagship',$Flagship))===1?'checked=true':''}}>
                                                <label for="Flagship_Y">Yes</label>
                                            </div>
                                        </div>
                                        <div class="col-xs-3">
                                            <div class="radio radio-inline">
                                                <input type="radio" id="Flagship_N" name="Flagship" value=0 {{intval(old('Flagship',$Flagship))===0?'checked=true':''}}>
                                                <label for="Flagship_N">No</label>
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
                                <a href="/admin/blog/articles" class="btn btn-default">Cancel</a>
                            </div>
                        </div>
                    </div>              
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src='{!! asset("resources/tinymce47/js/tinymce/tinymce.min.js") !!}'></script>
<script type="text/javascript">
let infoConfig={
    width:'100%',
    height:400,
    type:1,
    theme:'modern',
    language:'zh_TW',
    file_browser_callback:elFinderBrowser,
    plugins:'advlist autolink link image lists charmap print preview textcolor anchor emoticons table code contextmenu media lineheight',
    toolbar:['undo redo | cut copy paste | bold italic underline strikethrough | fontsizeselect fontselect styleselect | lineheightselect | forecolor backcolor | link unlink image anchor | charmap emoticons',
        'bullist numlist | alignleft aligncenter alignright alignjustify | outdent indent | table | code media'
    ],
    lineheight_formats: "3px 8px 9px 10px 11px 12px 14px 16px 18px 20px 22px 24px 26px 36px",
    image_advtab:true,
    image_caption: true,
    contextmenu:'cut copy paste | image | align | cell row column deletetable',
    a_plugin_option:true,
    a_configuration_option:400,
    subfolder:'', 
    relative_urls:false,
    remove_script_host:false,
    images_upload_url:'/admin/tinymce/upload_image',
    images_upload_handler:function(blobInfo,success,failure){
        var xhr,formData;
        xhr=new XMLHttpRequest();
        xhr.withCredentials=false;
        xhr.open('POST','/admin/tinymce/upload_image');
        xhr.setRequestHeader('X-CSRF-TOKEN',"{{csrf_token()}}");
        xhr.onload=function(){
            var json;
            if(xhr.status!=200){
                failure('上傳失敗!');
                return;
            }
            json=JSON.parse(xhr.responseText);
            if(!json||typeof json.location!='string'||json.location=='fail'){
                failure('上傳失敗!');
                return;
            }
            success(json.location);
        };

        formData=new FormData();
        formData.append('file',blobInfo.blob(),blobInfo.filename());
        xhr.send(formData);
    },
    automatic_uploads:false            
};
infoConfig['selector']='#Content';
tinymce.init(infoConfig);

let infoConfigEn={
    width:'100%',
    height:400,
    type:1,
    theme:'modern',
    language:'zh_TW',
    file_browser_callback:elFinderBrowser,
    plugins:'advlist autolink link image lists charmap print preview textcolor anchor emoticons table code contextmenu media lineheight',
    toolbar:['undo redo | cut copy paste | bold italic underline strikethrough | fontsizeselect fontselect styleselect | lineheightselect | forecolor backcolor | link unlink image anchor | charmap emoticons',
        'bullist numlist | alignleft aligncenter alignright alignjustify | outdent indent | table | code media'
    ],
    lineheight_formats: "3px 8px 9px 10px 11px 12px 14px 16px 18px 20px 22px 24px 26px 36px",
    image_advtab:true,
    image_caption: true,
    contextmenu:'cut copy paste | image | align | cell row column deletetable',
    a_plugin_option:true,
    a_configuration_option:400,
    subfolder:'', 
    relative_urls:false,
    remove_script_host:false,
    images_upload_url:'/admin/tinymce/upload_image',
    images_upload_handler:function(blobInfo,success,failure){
        var xhr,formData;
        xhr=new XMLHttpRequest();
        xhr.withCredentials=false;
        xhr.open('POST','/admin/tinymce/upload_image');
        xhr.setRequestHeader('X-CSRF-TOKEN',"{{csrf_token()}}");
        xhr.onload=function(){
            var json;
            if(xhr.status!=200){
                failure('上傳失敗!');
                return;
            }
            json=JSON.parse(xhr.responseText);
            if(!json||typeof json.location!='string'||json.location=='fail'){
                failure('上傳失敗!');
                return;
            }
            success(json.location);
        };

        formData=new FormData();
        formData.append('file',blobInfo.blob(),blobInfo.filename());
        xhr.send(formData);
    },
    automatic_uploads:false            
};
infoConfigEn['selector']='#ContentEn';
tinymce.init(infoConfigEn);
</script>
@endsection