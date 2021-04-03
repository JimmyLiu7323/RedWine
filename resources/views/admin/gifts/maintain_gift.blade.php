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
<script src="{!! asset('resources/Pikaday/pikaday.js') !!}"></script>
<link rel="stylesheet" type="text/css" href="{!! asset('resources/Pikaday/css/pikaday.css') !!}">
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
                                <label class="col-sm-2 control-label">Product Img</label>
                                <div class="col-sm-10">
                                    @if($errors->has('Image'))
                                    <small class="small_form_error">{{$errors->first('Image')}}</small>
                                    @endif
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <input type="text" name="Image" value="{{old('Image',$Image)}}" class="form-control" id="Image" readonly="true" />
                                        </div>
                                        <div class="col-xs-6">
                                            <button type="button" class="popup_selector btn btn-info" data-inputid="Image" style="width:100%">Select Img</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Name</label>
                                <div class="col-sm-10">
                                    @if($errors->has('Name'))
                                    <small class="small_form_error">{{$errors->first('Name')}}</small>
                                    @endif
                                    <input type="text" name="Name" value="{{old('Name',$Name)}}" class="form-control" maxlength="255" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Name(En)</label>
                                <div class="col-sm-10">
                                    @if($errors->has('NameEn'))
                                    <small class="small_form_error">{{$errors->first('NameEn')}}</small>
                                    @endif
                                    <input type="text" name="NameEn" value="{{old('NameEn',$NameEn)}}" class="form-control" maxlength="255" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Price</label>
                                <div class="col-sm-10">
                                    @if($errors->has('Price'))
                                    <small class="small_form_error">{{$errors->first('Price')}}</small>
                                    @endif
                                    <input type="number" name="Price" class="form-control" min=0 step=0.01 value="{{old('Price',$Price)}}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">On-Sale Price</label>
                                <div class="col-sm-10">
                                    @if($errors->has('S_price'))
                                    <small class="small_form_error">{{$errors->first('S_price')}}</small>
                                    @endif                                    
                                    <small class="small_form_error">* put null as no on sale</small>
                                    <input type="number" name="S_price" class="form-control" min=0 step=0.01 value="{{old('S_price',$S_price)}}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Gift Category</label>
                                <div class="col-sm-10">
                                    @if($errors->has('WineCatg'))
                                    <small class="small_form_error">{{$errors->first('WineCatg')}}</small>
                                    @endif
                                    <select name="Category" class="form-control">
                                        @foreach($GiftCategories as $GiftCategory)
                                        <option {{intval(old('Category',$Category))===$GiftCategory->CategoryId?'selected=true':''}} value="{{$GiftCategory->CategoryId}}">{{$GiftCategory->Category}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Volume(ml)</label>
                                <div class="col-sm-10">
                                    @if($errors->has('Volume'))
                                    <small class="small_form_error">{{$errors->first('Volume')}}</small>
                                    @endif
                                    <input type="number" class="form-control" name="Volume" value="{{old('Volume',$Volume)}}" step="1" min="1" />
                                </div>
                            </div> 
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Alcohol</label>
                                <div class="col-sm-10">
                                    @if($errors->has('Alcohol'))
                                    <small class="small_form_error">{{$errors->first('Alcohol')}}</small>
                                    @endif
                                    <input type="number" class="form-control" name="Alcohol" value="{{old('Alcohol',$Alcohol)}}" step="0.01" min="0" />
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-2 control-label">Stock</label>
                                <div class="col-sm-10">
                                    @if($errors->has('Stocks'))
                                    <small class="small_form_error">{{$errors->first('Stocks')}}</small>
                                    @endif                                    
                                    <input type="number" name="Stocks" class="form-control" value="{{old('Stocks',$Stocks)}}" min="1" step="1" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Sale Date</label>
                                <div class="col-sm-10">
                                    @if($errors->has('ActDate'))
                                    <small class="small_form_error">{{$errors->first('ActDate')}}</small>
                                    @endif
                                    @if($errors->has('EndDate'))
                                    <small class="small_form_error">{{$errors->first('EndDate')}}</small>
                                    @endif
                                    <input type="text" name="ActDate" id="ActDate" class="form-control" readonly="true" value="{{old('ActDate',$ActDate)}}" />
                                    <input type="text" name="EndDate" id="EndDate" class="form-control" readonly="true" placeholder="End Date" value="{{old('EndDate',$EndDate)}}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">BriefDesc</label>
                                <div class="col-sm-10">
                                    @if($errors->has('BriefDesc'))
                                    <small class="small_form_error">{{$errors->first('BriefDesc')}}</small>
                                    @endif
                                    <textarea name="BriefDesc" class="form-control" rows="5" maxlength="500" style="resize:vertical">{{old('BriefDesc',$BriefDesc)}}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">BriefDesc(En)</label>
                                <div class="col-sm-10">
                                    @if($errors->has('BriefDescEn'))
                                    <small class="small_form_error">{{$errors->first('BriefDescEn')}}</small>
                                    @endif
                                    <textarea name="BriefDescEn" class="form-control" rows="5" maxlength="500" style="resize:vertical">{{old('BriefDescEn',$BriefDescEn)}}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Description</label>
                                <div class="col-sm-10">
                                    @if($errors->has('Description'))
                                    <small class="small_form_error">{{$errors->first('Description')}}</small>
                                    @endif
                                    <textarea name="Description" class="form-control" id="Description">{!! old('Description',$Description) !!}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Description(En)</label>
                                <div class="col-sm-10">
                                    @if($errors->has('DescriptionEn'))
                                    <small class="small_form_error">{{$errors->first('DescriptionEn')}}</small>
                                    @endif
                                    <textarea name="DescriptionEn" class="form-control" id="DescriptionEn">{!! old('DescriptionEn',$DescriptionEn) !!}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Delivery & Returns</label>
                                <div class="col-sm-10">
                                    @if($errors->has('Delivery_Returns'))
                                    <small class="small_form_error">{{$errors->first('Delivery_Returns')}}</small>
                                    @endif
                                    <textarea name="Delivery_Returns" class="form-control" id="Delivery_Returns">{!! old('Delivery_Returns',$Delivery_Returns) !!}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Delivery & Returns(En)</label>
                                <div class="col-sm-10">
                                    @if($errors->has('Delivery_ReturnsEn'))
                                    <small class="small_form_error">{{$errors->first('Delivery_ReturnsEn')}}</small>
                                    @endif
                                    <textarea name="Delivery_ReturnsEn" class="form-control" id="Delivery_ReturnsEn">{!! old('Delivery_ReturnsEn',$Delivery_ReturnsEn) !!}</textarea>
                                </div>
                            </div>                                                        
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Order</label>
                                <div class="col-sm-10">
                                    @if($errors->has('OrderNumber'))
                                    <small class="small_form_error">{{$errors->first('OrderNumber')}}</small>
                                    @endif
                                    <input type="number" name="OrderNumber" value="{{old('OrderNumber',$OrderNumber)}}" class="form-control" min=1 step=1 />
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
                        </div>
                    </div>
                    <input type="hidden" name="_token" id="csrf-token" value="{{csrf_token()}}" />
                    <div class="form-footer">
                        <div class="form-group">
                            <div class="col-md-12">
                                <button type='submit' class='btn btn-primary'>Save</button>
                                <a href="/admin/gifts/list" class="btn btn-default">Cancel</a>
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

let delivery_returns_config={
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
delivery_returns_config['selector']='#Delivery_Returns';
tinymce.init(delivery_returns_config);

let delivery_returnsEn_config={
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
delivery_returnsEn_config['selector']='#Delivery_ReturnsEn';
tinymce.init(delivery_returnsEn_config);

$(document).ready(function(){
    const t_option={
        search:true,
        searchText:'輸入過濾。',
        csvDispCount:4,
        placeholder:'點這裡選取',
        captionFormat:'已選擇 {0} 項'
    };
    $('#WineCaterings').SumoSelect(t_option);
});

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
</script>
@endsection