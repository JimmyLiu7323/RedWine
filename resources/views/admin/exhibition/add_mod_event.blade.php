@extends('admin.layouts.main')
@section('content')
<style type="text/css">
.form-group{margin-top:10px;margin-bottom:10px}
</style>
<div class="row">
    <div class="col-xs-12">
        <form class="form form-horizontal" id='product_form' method="POST" enctype="multipart/form-data">
            <div class="card">
                <div class="card-body">
                    <div class="section">
                        <div class="section-body">
                            <div class="form-group">
                                <label class="col-md-2 control-label">Impact image</label>
                                <div class="col-md-10">
                                    @if($errors->has('ImpactImage'))
                                    <small class="small_form_error">{{$errors->first('ImpactImage')}}</small>
                                    @endif                                    
                                    <div class="row">
                                        <div class="col-xs-8">
                                            <input type="text" name="ImpactImage" class="form-control" readonly="true" id="ImpactImage" value="{{old('ImpactImage',$ImpactImage)}}" />
                                        </div>
                                        <div class="col-xs-4">
                                            <button class="popup_selector btn btn-info" type="button" data-inputid="ImpactImage" style="width:100%">Select Img</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Name</label>
                                <div class="col-md-10">
                                    @if($errors->has('EventName'))
                                    <small class="small_form_error">{{$errors->first('EventName')}}</small>
                                    @endif                                                                        
                                    <input type="text" name="EventName" class="form-control" value="{{old('EventName',$EventName)}}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Name(En)</label>
                                <div class="col-md-10">
                                    @if($errors->has('EventNameEn'))
                                    <small class="small_form_error">{{$errors->first('EventNameEn')}}</small>
                                    @endif                                                                        
                                    <input type="text" name="EventNameEn" class="form-control" value="{{old('EventNameEn',$EventNameEn)}}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Brief Description</label>
                                <div class="col-md-10">
                                    @if($errors->has('BriefDesc'))
                                    <small class="small_form_error">{{$errors->first('BriefDesc')}}</small>
                                    @endif                                                                        
                                    <textarea name="BriefDesc" class="form-control" maxlength="500" style="resize:vertical" rows="4">{!! old('BriefDesc',$BriefDesc) !!}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Brief Description(En)</label>
                                <div class="col-md-10">
                                    @if($errors->has('BriefDescEn'))
                                    <small class="small_form_error">{{$errors->first('BriefDescEn')}}</small>
                                    @endif                                                                        
                                    <textarea name="BriefDescEn" class="form-control" maxlength="500" style="resize:vertical" rows="4">{!! old('BriefDescEn',$BriefDescEn) !!}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Description</label>
                                <div class="col-md-10">
                                    @if($errors->has('Description'))
                                    <small class="small_form_error">{{$errors->first('Description')}}</small>
                                    @endif
                                    <textarea name="Description" id="Description">{!! old('Description',$Description) !!}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Description(En)</label>
                                <div class="col-md-10">
                                    @if($errors->has('DescriptionEn'))
                                    <small class="small_form_error">{{$errors->first('DescriptionEn')}}</small>
                                    @endif
                                    <textarea name="DescriptionEn" id="DescriptionEn">{!! old('DescriptionEn',$DescriptionEn) !!}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Date of event(Start)</label>
                                <div class="col-md-10">
                                    @if($errors->has('EventDateStart'))
                                    <small class="small_form_error">{{$errors->first('EventDateStart')}}</small>
                                    @endif
                                    <input type="date" name="EventDateStart" class="form-control" value="{{old('EventDateStart',$EventDateStart)}}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Time of event(Start)</label>
                                <div class="col-md-10">
                                    @if($errors->has('EventTimeStart'))
                                    <small class="small_form_error">{{$errors->first('EventTimeStart')}}</small>
                                    @endif
                                    <input type="text" name="EventTimeStart" id="EventTimeStart" class="form-control" value="{{old('EventTimeStart',$EventTimeStart)}}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Date of event(End)</label>
                                <div class="col-md-10">
                                    @if($errors->has('EventDateEnd'))
                                    <small class="small_form_error">{{$errors->first('EventDateEnd')}}</small>
                                    @endif
                                    <input type="date" name="EventDateEnd" class="form-control" value="{{old('EventDateEnd',$EventDateEnd)}}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Time of event(End)</label>
                                <div class="col-md-10">
                                    @if($errors->has('EventTimeEnd'))
                                    <small class="small_form_error">{{$errors->first('EventTimeEnd')}}</small>
                                    @endif
                                    <input type="text" name="EventTimeEnd" id="EventTimeEnd" class="form-control" value="{{old('EventTimeEnd',$EventTimeEnd)}}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Capacity</label>
                                <div class="col-md-10">
                                    @if($errors->has('AllCapacity'))
                                    <small class="small_form_error">{{$errors->first('AllCapacity')}}</small>
                                    @endif
                                    <input type="number" step="1" min="0" name="AllCapacity" class="form-control" value="{{old('AllCapacity',$AllCapacity)}}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Standing Capacity</label>
                                <div class="col-md-10">
                                    @if($errors->has('StandingCapacity'))
                                    <small class="small_form_error">{{$errors->first('StandingCapacity')}}</small>
                                    @endif
                                    <input type="number" step="1" min="0" name="StandingCapacity" class="form-control" value="{{old('StandingCapacity',$StandingCapacity)}}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Seating Capacity</label>
                                <div class="col-md-10">
                                    @if($errors->has('SeatingCapacity'))
                                    <small class="small_form_error">{{$errors->first('SeatingCapacity')}}</small>
                                    @endif
                                    <input type="number" step="1" min="0" name="SeatingCapacity" class="form-control" value="{{old('SeatingCapacity',$SeatingCapacity)}}" />
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
                                                <input type="radio" id="StatusY" name="Status" value="1" {{intval(old('Status',$Status))===1?'checked=true':''}} />
                                                <label for="StatusY">Enabled</label>
                                            </div>
                                        </div>
                                        <div class="col-xs-3">
                                            <div class="radio radio-inline">
                                                <input type="radio" id="StatusN" name="Status" value="0" {{intval(old('Status',$Status))===0?'checked=true':''}}>
                                                <label for="freeN">Disabled</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Enable from(Start date)</label>
                                <div class="col-md-10">
                                    @if($errors->has('EventOnDate'))
                                    <small class="small_form_error">{{$errors->first('EventOnDate')}}</small>
                                    @endif
                                    <input type="date" name="EventOnDate" class="form-control" value="{{old('EventOnDate',$EventOnDate)}}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Enable from(Start time)</label>
                                <div class="col-md-10">
                                    @if($errors->has('EventOnTime'))
                                    <small class="small_form_error">{{$errors->first('EventOnTime')}}</small>
                                    @endif
                                    <input type="text" name="EventOnTime" id="EventOnTime" class="form-control" value="{{old('EventOnTime',$EventOnTime)}}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Enable until(End date)</label>
                                <div class="col-md-10">
                                    @if($errors->has('EventOffDate'))
                                    <small class="small_form_error">{{$errors->first('EventOffDate')}}</small>
                                    @endif
                                    <input type="date" name="EventOffDate" class="form-control" value="{{old('EventOffDate',$EventOffDate)}}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Enable until(End time)</label>
                                <div class="col-md-10">
                                    @if($errors->has('EventOffTime'))
                                    <small class="small_form_error">{{$errors->first('EventOffTime')}}</small>
                                    @endif
                                    <input type="text" name="EventOffTime" id="EventOffTime" class="form-control" value="{{old('EventOffTime',$EventOffTime)}}" />
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label class="col-md-2 control-label">Free event</label>
                                <div class="col-md-10">
                                    @if($errors->has('Free'))
                                    <small class="small_form_error">{{$errors->first('Free')}}</small>
                                    @endif
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <div class="radio radio-inline">
                                                <input type="radio" id="freeY" name="Free" value="1" {{intval(old('Free',$Free))===1?'checked=true':''}} />
                                                <label for="freeY">Yes</label>
                                            </div>
                                        </div>
                                        <div class="col-xs-3">
                                            <div class="radio radio-inline">
                                                <input type="radio" id="freeN" name="Free" value="0" {{intval(old('Free',$Free))===0?'checked=true':''}}>
                                                <label for="freeN">No</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Price</label>
                                <div class="col-md-10">
                                    @if($errors->has('Price'))
                                    <small class="small_form_error">{{$errors->first('Price')}}</small>
                                    @endif
                                    <div class="input-group">
                                        <span class="input-group-addon">$</span>
                                        <input type="text" class="form-control" name="Price" value="{{old('Price',$Price)}}" min="0" step="0.01" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Ticket image</label>
                                <div class="col-md-10">
                                    @if($errors->has('TicketImage'))
                                    <small class="small_form_error">{{$errors->first('TicketImage')}}</small>
                                    @endif                                    
                                    <div class="row">
                                        <div class="col-xs-8">
                                            <input type="text" name="TicketImage" class="form-control" readonly="true" id="TicketImage" value="{{old('TicketImage',$TicketImage)}}" />
                                        </div>
                                        <div class="col-xs-4">
                                            <button class="popup_selector btn btn-info" type="button" data-inputid="TicketImage" style="width:100%">Select Img</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="_token" id="csrf-token" value="{{csrf_token()}}" />
                    <div class="form-footer">
                        <button type='submit' class='btn btn-primary'>Save</button>   
                        <a href="/admin/exhibition/{{\Request::segment(3)}}" class="btn btn-default">Cancel</a>         
                    </div>                    
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript" src='{!! asset("resources/tinymce47/js/tinymce/tinymce.min.js") !!}'></script>
<script type="text/javascript">
$('#EventTimeStart').timepicker();
$('#EventTimeEnd').timepicker();
$('#EventOnTime').timepicker();
$('#EventOffTime').timepicker();

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
infoConfig['selector']='#Description';
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
infoConfigEn['selector']='#DescriptionEn';
tinymce.init(infoConfigEn);
</script>
@endsection