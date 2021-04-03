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
                                <label class="col-md-2 control-label">Impact image</label>
                                <div class="col-md-10">
                                    @if($errors->has('corporate_image'))
                                    <small class="small_form_error">{{$errors->first('corporate_image')}}</small>
                                    @endif                                    
                                    <div class="row">
                                        <div class="col-xs-8">
                                            <input type="text" name="corporate_image" class="form-control" readonly="true" id="corporate_image" value="{{old('corporate_image',$corporate_image)}}" />
                                        </div>
                                        <div class="col-xs-4">
                                            <button class="popup_selector btn btn-info" data-inputid="corporate_image" style="width:100%">Select Img</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Introduction</label>
                                <div class="col-md-10">
                                    @if($errors->has('corporate_introduction'))
                                    <small class="small_form_error">{{$errors->first('corporate_introduction')}}</small>
                                    @endif
                                    <textarea name="corporate_introduction" id="corporate_introduction">{!! old('corporate_introduction',$corporate_introduction) !!}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Introduction(En)</label>
                                <div class="col-md-10">
                                    @if($errors->has('corporate_introduction_en'))
                                    <small class="small_form_error">{{$errors->first('corporate_introduction_en')}}</small>
                                    @endif
                                    <textarea name="corporate_introduction_en" id="corporate_introduction_en">{!! old('corporate_introduction_en',$corporate_introduction_en) !!}</textarea>
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
infoConfig['selector']='#corporate_introduction';
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
infoConfigEn['selector']='#corporate_introduction_en';
tinymce.init(infoConfigEn);
</script>
@endsection