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
<script src="{!! asset('resources/Pikaday/pikaday.js') !!}"></script>
<link rel="stylesheet" type="text/css" href="{!! asset('resources/Pikaday/css/pikaday.css') !!}">
<style type="text/css">
.form-group{margin-top:10px;margin-bottom:10px}
.SumoSelect{width:100%}
</style>
<div class="row">
    <div class="col-xs-12">
        <div class="card">
            <div class="card-body">
                <form class="form form-horizontal" id='product_form' method="POST" enctype="multipart/form-data">
                    <div class="section">
                        <div class="section-body">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Case</label>
                                <div class="col-sm-10">
                                    @if($errors->has('ParentCase'))
                                    <small class="small_form_error">{{$errors->first('ParentCase')}}</small>
                                    @endif
                                    <select class="form-control" name="ParentCase">
                                        @foreach($cases as $case)
                                        <option value="{{$case->CaseId}}" {{old('ParentCase')==$case->CaseId?'selected=true':''}}>{{$case->CaseName}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Product Img</label>
                                <div class="col-sm-10">
                                    @if($errors->has('Image'))
                                    <small class="small_form_error">{{$errors->first('Image')}}</small>
                                    @endif
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <input type="text" name="Image" value="{{old('Image')}}" class="form-control" id="Image" readonly="true" />
                                        </div>
                                        <div class="col-xs-6">
                                            <button type="button" class="popup_selector btn btn-info" data-inputid="Image" style="width:100%">Select Img</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Topic Name</label>
                                <div class="col-sm-10">
                                    @if($errors->has('MixName'))
                                    <small class="small_form_error">{{$errors->first('MixName')}}</small>
                                    @endif
                                    <input type="text" name="MixName" value="{{old('MixName')}}" class="form-control" maxlength="100" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Topic Name(En)</label>
                                <div class="col-sm-10">
                                    @if($errors->has('MixNameEn'))
                                    <small class="small_form_error">{{$errors->first('MixNameEn')}}</small>
                                    @endif
                                    <input type="text" name="MixNameEn" value="{{old('MixNameEn')}}" class="form-control" maxlength="100" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Page Description</label>
                                <div class="col-sm-10">
                                    @if($errors->has('MetaDesc'))
                                    <small class="small_form_error">{{$errors->first('MetaDesc')}}</small>
                                    @endif
                                    <textarea rows="4" style="resize:vertical" name="MetaDesc" class="form-control">{{old('MetaDesc')}}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Page Description(En)</label>
                                <div class="col-sm-10">
                                    @if($errors->has('MetaDescEn'))
                                    <small class="small_form_error">{{$errors->first('MetaDescEn')}}</small>
                                    @endif
                                    <textarea rows="4" style="resize:vertical" name="MetaDescEn" class="form-control">{{old('MetaDescEn')}}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Page Keywords</label>
                                <div class="col-sm-10">
                                    @if($errors->has('MetaKeywordsEn'))
                                    <small class="small_form_error">{{$errors->first('MetaKeywords')}}</small>
                                    @endif
                                    <input type="text" name="MetaKeywords" class="form-control" value="{{old('MetaKeywords')}}" style="margin-bottom:0" />
                                    <span class="text-danger">(Use comma for split)</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Page Keywords(En)</label>
                                <div class="col-sm-10">
                                    @if($errors->has('MetaKeywordsEn'))
                                    <small class="small_form_error">{{$errors->first('MetaKeywordsEn')}}</small>
                                    @endif
                                    <input type="text" name="MetaKeywordsEn" class="form-control" value="{{old('MetaKeywordsEn')}}" style="margin-bottom:0" />
                                    <span class="text-danger">(Use comma for split)</span>
                                </div>
                            </div>                                                                                 
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Price</label>
                                <div class="col-sm-10">
                                    @if($errors->has('Price'))
                                    <small class="small_form_error">{{$errors->first('Price')}}</small>
                                    @endif
                                    <input type="number" name="Price" class="form-control" min=0 step=0.01 value="{{old('Price',1)}}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">On-Sale Price</label>
                                <div class="col-sm-10">
                                    @if($errors->has('S_price'))
                                    <small class="small_form_error">{{$errors->first('S_price')}}</small>
                                    @endif                                    
                                    <small class="small_form_error">* put null as no on sale</small>
                                    <input type="number" name="S_price" class="form-control" min=0 step=0.01 value="{{old('S_price')}}" />
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-2 control-label">Stock</label>
                                <div class="col-sm-10">
                                    @if($errors->has('Stocks'))
                                    <small class="small_form_error">{{$errors->first('Stocks')}}</small>
                                    @endif                                    
                                    <input type="number" name="Stocks" class="form-control" value="{{old('Stocks',1)}}" min="1" step="1" />
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-2 control-label">Contents</label>
                                <div class="col-sm-10">
                                    @if($errors->has('WineId'))
                                    <small class="small_form_error">{{$errors->first('WineId')}}</small>
                                    @endif
                                    @if($errors->has('WineNumber'))
                                    <small class="small_form_error">{{$errors->first('WineNumber')}}</small>
                                    @endif
                                    <button type="button" class="btn btn-info btn-md" data-toggle="modal" data-target="#WinesModal">Maintain Contents</button>
                                    <div class="row" id="Wines_Content">
                                        @if(old('WineId')!='')
                                        <?php
                                        $WineIds=old('WineId');
                                        $WineNums=old('WineNumber');
                                        $WineNames=old('WineName');
                                        ?>
                                            @foreach($WineIds as $k=>$v)
                                        <div id='list_wine_{{$WineIds[$k]}}'>
                                            <input type='hidden' name='WineId[]' value='{{$WineIds[$k]}}' />
                                            <div class='col-xs-6'>
                                                <input class='form-control' type='text' readonly='true' value='{{$WineNames[$k]}}' name='WineName[]' />
                                            </div>
                                            <div class='col-xs-6'>
                                                <input class='form-control' type='number' min='1' step='1' name='WineNumber[]' value={{$WineNums[$k]}} />
                                            </div>
                                        </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Sale Date</label>
                                <div class="col-sm-10">
                                    @if($errors->has('noOffShelf'))
                                    <small class="small_form_error">{{$errors->first('noOffShelf')}}</small>
                                    @endif                                    
                                    @if($errors->has('ActDate'))
                                    <small class="small_form_error">{{$errors->first('ActDate')}}</small>
                                    @endif
                                    @if($errors->has('EndDate'))
                                    <small class="small_form_error">{{$errors->first('EndDate')}}</small>
                                    @endif
                                    <div class="checkbox">
                                        <input type="checkbox" id="forever-onShelf" name="noOffShelf" {{old('noOffShelf')!=''&&old('noOffShelf')==='on'?'checked=true':''}}>
                                        <label for="forever-onShelf">Never Off</label>
                                    </div>
                                    <input type="text" name="ActDate" id="ActDate" class="form-control" readonly="true" value="{{old('ActDate',date('Y-m-d'))}}" />
                                    <input type="text" name="EndDate" id="EndDate" class="form-control" readonly="true" placeholder="End Date" value="{{old('EndDate')}}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">BriefDesc</label>
                                <div class="col-sm-10">
                                    @if($errors->has('BriefDesc'))
                                    <small class="small_form_error">{{$errors->first('BriefDesc')}}</small>
                                    @endif
                                    <textarea name="BriefDesc" class="form-control" rows="5" maxlength="500" style="resize:vertical">{{old('BriefDesc')}}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">BriefDesc(En)</label>
                                <div class="col-sm-10">
                                    @if($errors->has('BriefDescEn'))
                                    <small class="small_form_error">{{$errors->first('BriefDescEn')}}</small>
                                    @endif
                                    <textarea name="BriefDescEn" class="form-control" rows="5" maxlength="500" style="resize:vertical">{{old('BriefDescEn')}}</textarea>
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Description</label>
                                <div class="col-sm-10">
                                    @if($errors->has('Description'))
                                    <small class="small_form_error">{{$errors->first('Description')}}
                                    @endif
                                    <textarea name="Description" id="Description" class="form-control">{!! old('Description') !!}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Description(En)</label>
                                <div class="col-sm-10">
                                    @if($errors->has('DescriptionEn'))
                                    <small class="small_form_error">{{$errors->first('DescriptionEn')}}
                                    @endif
                                    <textarea name="DescriptionEn" id="DescriptionEn" class="form-control">{!! old('DescriptionEn') !!}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Delivery & Returns</label>
                                <div class="col-sm-10">
                                    @if($errors->has('Delivery_Returns'))
                                    <small class="small_form_error">{{$errors->first('Delivery_Returns')}}</small>
                                    @endif
                                    <textarea name="Delivery_Returns" class="form-control" id="Delivery_Returns">{!! old('Delivery_Returns') !!}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Delivery & Returns(En)</label>
                                <div class="col-sm-10">
                                    @if($errors->has('Delivery_ReturnsEn'))
                                    <small class="small_form_error">{{$errors->first('Delivery_ReturnsEn')}}</small>
                                    @endif
                                    <textarea name="Delivery_ReturnsEn" class="form-control" id="Delivery_ReturnsEn">{!! old('Delivery_ReturnsEn') !!}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Order</label>
                                <div class="col-sm-10">
                                    @if($errors->has('OrderNumber'))
                                    <small class="small_form_error">{{$errors->first('OrderNumber')}}</small>
                                    @endif
                                    <input type="number" name="OrderNumber" value="{{old('OrderNumber',1)}}" class="form-control" min=1 step=1 />
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
                                                <input type="radio" id="enabled" name="Status" value=1 {{old('Status')!=''&&old('Status')==1?'checked=true':''}}>
                                                <label for="enabled">Yes</label>
                                            </div>
                                        </div>
                                        <div class="col-xs-3">
                                            <div class="radio radio-inline">
                                                <input type="radio" id="disabled" name="Status" value=0 {{old('Status')!=''&&old('Status')==0?'checked=true':''}}>
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
                                <a href="/admin/products/mix" class="btn btn-default">Cancel</a>
                            </div>
                        </div>
                    </div>              
                </form>
            </div>
        </div>
    </div>
</div>

<div id="WinesModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Maintain Contents</h4>
            </div>
            <div class="modal-body">
                @foreach($wines as $wine)
                <div class="checkbox">
                    <input type="checkbox" id="wine_{{$wine->WineId}}" class="modal-wine">
                    <label for="wine_{{$wine->WineId}}">{{$wine->Name}}</label>
                </div>
                @endforeach
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="renewContents()">OK</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src='{!! asset("resources/tinymce47/js/tinymce/tinymce.min.js") !!}'></script>
<script type="text/javascript">
let infoConfig={
    width:'100%',
    height:200,
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
                failure('Upload Falied!');
                return;
            }
            json=JSON.parse(xhr.responseText);
            if(!json||typeof json.location!='string'||json.location=='fail'){
                failure('Upload Falied!');
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
infoConfig['selector']='#Delivery_Returns';
tinymce.init(infoConfig);

let infoEnConfig={
    width:'100%',
    height:200,
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
                failure('Upload Falied!');
                return;
            }
            json=JSON.parse(xhr.responseText);
            if(!json||typeof json.location!='string'||json.location=='fail'){
                failure('Upload Falied!');
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
infoEnConfig['selector']='#Delivery_ReturnsEn';
tinymce.init(infoEnConfig);

let descriptionConfig={
    width:'100%',
    height:200,
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
                failure('Upload Failed!');
                return;
            }
            json=JSON.parse(xhr.responseText);
            if(!json||typeof json.location!='string'||json.location=='fail'){
                failure('Upload Failed!');
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
descriptionConfig['selector']='#Description';
tinymce.init(descriptionConfig);

let descriptionEnConfig={
    width:'100%',
    height:200,
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
                failure('Upload Failed!');
                return;
            }
            json=JSON.parse(xhr.responseText);
            if(!json||typeof json.location!='string'||json.location=='fail'){
                failure('Upload Failed!');
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
descriptionEnConfig['selector']='#DescriptionEn';
tinymce.init(descriptionEnConfig);

const date_picker=new Pikaday({
    field:document.getElementById('ActDate'),
    format:'YYYY-MM-DD',
    toString(date,format){
        const day=("0"+date.getDate()).substr(-2);
        const month=("0"+(date.getMonth()+1)).substr(-2);
        const year=date.getFullYear();
        return `${year}-${month}-${day}`;
    },
    i18n:{
        previousMonth:'上個月',
        nextMonth:'下個月',
        months:['一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月'],
        weekdays:['日','一','二','三','四','五','六'],
        weekdaysShort:['日','一','二','三','四','五','六']
    }
});

const EndDate_picker=new Pikaday({
    field:document.getElementById('EndDate'),
    format:'YYYY-MM-DD',
    toString(date,format){
        const day=("0"+date.getDate()).substr(-2);
        const month=("0"+(date.getMonth()+1)).substr(-2);
        const year=date.getFullYear();
        return `${year}-${month}-${day}`;
    },
    i18n:{
        previousMonth:'上個月',
        nextMonth:'下個月',
        months:['一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月'],
        weekdays:['日','一','二','三','四','五','六'],
        weekdaysShort:['日','一','二','三','四','五','六']
    }
});

function renewContents(){
    let newWine='';
    $.each($('.modal-wine'),function(k,v){
        newWine='';

        if($(this).is(":checked")){
            let WineId=$(this).attr('id');
            WineId=WineId.replace('wine_','');
            if($('#list_wine_'+WineId).length==0){
                newWine+="<div id='list_wine_"+WineId+"'>";
                newWine+="<input type='hidden' name='WineId[]' value='"+WineId+"' />";
                newWine+="<div class='col-xs-6'><input class='form-control' type='text' readonly='true' value='"+$(this).next('label').text()+"' name='WineName[]' /></div>";
                newWine+="<div class='col-xs-6'><input class='form-control' type='number' min='1' step='1' name='WineNumber[]' value=1 /></div>";
                newWine+="</div>";
                $('#Wines_Content').append(newWine);
            }
        }
        else{
            let WineId=$(this).attr('id');
            WineId=WineId.replace('wine_','');
            if($('#list_wine_'+WineId).length>0)
                $('#list_wine_'+WineId).remove();
        }
    })
}
</script>
@endsection