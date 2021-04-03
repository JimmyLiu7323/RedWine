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
<script src="{!! asset('resources/sumoselect/jquery.sumoselect.min.js') !!}"></script>
<link href="{!! asset('resources/sumoselect/sumoselect.min.css') !!}" rel="stylesheet" />
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
                                <label class="col-sm-2 control-label">Product Img</label>
                                <div class="col-sm-10">
                                    @if($errors->has('Image'))
                                    <small class="small_form_error">{{$errors->first('Image')}}</small>
                                    @endif
                                    @if($errors->has('Image2'))
                                    <small class="small_form_error">{{$errors->first('Image2')}}</small>
                                    @endif
                                    @if($errors->has('Image3'))
                                    <small class="small_form_error">{{$errors->first('Image3')}}</small>
                                    @endif
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <input type="text" name="Image" value="{{old('Image',$wineInfo->Image)}}" class="form-control" id="Image" readonly="true" />
                                        </div>
                                        <div class="col-xs-6">
                                            <button type="button" class="popup_selector btn btn-info" data-inputid="Image" style="width:100%">Select Img</button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <input type="text" name="Image2" value="{{old('Image',$wineInfo->Image2)}}" class="form-control" id="Image2" readonly="true" />
                                        </div>
                                        <div class="col-xs-4">
                                            <button type="button" class="popup_selector btn btn-info" data-inputid="Image2" style="width:100%">Select Img</button>
                                        </div>
                                        <div class="col-xs-4">
                                            <button type="button" class="btn btn-danger" style="width:100%" onclick="$('#Image2').val('')">Clear</button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <input type="text" name="Image3" value="{{old('Image',$wineInfo->Image3)}}" class="form-control" id="Image" readonly="true" />
                                        </div>
                                        <div class="col-xs-4">
                                            <button type="button" class="popup_selector btn btn-info" data-inputid="Image3" style="width:100%">Select Img</button>
                                        </div>
                                        <div class="col-xs-4">
                                            <button type="button" class="btn btn-danger" style="width:100%" onclick="$('#Image3').val('')">Clear</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Introduction PDF</label>
                                <div class="col-sm-10">
                                    @if($errors->has('IntroductionPDF'))
                                    <small class="small_form_error">{{$errors->first('IntroductionPDF')}}</small>
                                    @endif
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <input type="text" name="IntroductionPDF" value="{{old('IntroductionPDF',$wineInfo->IntroductionPDF)}}" class="form-control" id="IntroductionPDF" readonly="true" />
                                        </div>
                                        <div class="col-xs-6">
                                            <button type="button" class="popup_selector btn btn-info" data-inputid="IntroductionPDF" style="width:100%">Select PDF</button>
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
                                    <input type="text" name="Name" value="{{old('Name',$wineInfo->Name)}}" class="form-control" maxlength="255" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Name(En)</label>
                                <div class="col-sm-10">
                                    @if($errors->has('NameEn'))
                                    <small class="small_form_error">{{$errors->first('NameEn')}}</small>
                                    @endif
                                    <input type="text" name="NameEn" value="{{old('NameEn',$wineInfo->NameEn)}}" class="form-control" maxlength="255" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Price</label>
                                <div class="col-sm-10">
                                    @if($errors->has('Price'))
                                    <small class="small_form_error">{{$errors->first('Price')}}</small>
                                    @endif
                                    <input type="number" name="Price" class="form-control" min=0 step=0.01 value="{{old('Price',$wineInfo->Price)}}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">On-Sale Price</label>
                                <div class="col-sm-10">
                                    @if($errors->has('S_price'))
                                    <small class="small_form_error">{{$errors->first('S_price')}}</small>
                                    @endif                                    
                                    <small class="small_form_error">* put null as no on sale</small>
                                    <input type="number" name="S_price" class="form-control" min=0 step=0.01 value="{{old('S_price',$wineInfo->S_price)}}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Wine Category</label>
                                <div class="col-sm-10">
                                    @if($errors->has('WineCatg'))
                                    <small class="small_form_error">{{$errors->first('WineCatg')}}</small>
                                    @endif
                                    <select name="WineCatg" class="form-control">
                                        <option {{old('WineCatg',$wineInfo->WineCatg)==1?'selected=true':''}} value="1">REDS</option>
                                        <option {{old('WineCatg',$wineInfo->WineCatg)==2?'selected=true':''}} value="2">WHITES</option>
                                        <option {{old('WineCatg',$wineInfo->WineCatg)==3?'selected=true':''}} value="3">SPARKLING</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Style</label>
                                <div class="col-sm-10">
                                    @if($errors->has('WineStyle'))
                                    <small class="small_form_error">{{$errors->first('WineStyle')}}</small>
                                    @endif                                    
                                    <select name="WineStyle" class="form-control">
                                        <option value="">Select</option>
                                        @foreach($styles as $style)
                                        <option value="{{$style->StyleId}}" {{old('WineStyle',$wineInfo->WineStyle)==$style->StyleId?'selected=true':''}}>{{$style->Style}}</option>
                                        @endforeach
                                    </select>
                                </div>                                
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Variety</label>
                                <div class="col-sm-10">
                                    @if($errors->has('WineVariety'))
                                    <small class="small_form_error">{{$errors->first('WineVariety')}}</small>
                                    @endif                                    
                                    <select name="WineVariety" class="form-control">
                                        <option value="">Select</option>
                                        @foreach($varieties as $variety)
                                        <option value="{{$variety->VarietyId}}" {{old('WineVariety',$wineInfo->WineVariety)==$variety->VarietyId?'selected=true':''}}>{{$variety->Variety}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Color</label>
                                <div class="col-sm-10">
                                    @if($errors->has('WineColour'))
                                    <small class="small_form_error">{{$errors->first('WineColour')}}</small>
                                    @endif                                    
                                    <select name="WineColour" class="form-control">
                                        <option value="">Select</option>
                                        @foreach($colours as $colour)
                                        <option value="{{$colour->ColourId}}" {{old('WineColour',$wineInfo->WineColour)==$colour->ColourId?'selected=true':''}}>{{$colour->Colour}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Country</label>
                                <div class="col-sm-10">
                                    @if($errors->has('WineCountry'))
                                    <small class="small_form_error">{{$errors->first('WineCountry')}}</small>
                                    @endif
                                    <select name="WineCountry" class="form-control">
                                        <option value="">Select</option>
                                        @foreach($countries as $countryKey=>$country)
                                        <option value="{{$countryKey}}" {{old('WineCountry',$wineInfo->WineCountry)==$countryKey?'selected=true':''}}>{{$country}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Region</label>
                                <div class="col-sm-10">
                                    @if($errors->has('WineRegion'))
                                    <small class="small_form_error">{{$errors->first('WineRegion')}}</small>
                                    @endif                                    
                                    <select name="WineRegion" class="form-control">
                                        <option value="">Select</option>
                                        @foreach($regions as $region)
                                        <option value="{{$region->RegionId}}" {{old('WineRegion',$wineInfo->WineRegion)==$region->RegionId?'selected=true':''}}>{{$region->Region}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Packing Method</label>
                                <div class="col-sm-10">
                                    @if($errors->has('WineClosure'))
                                    <small class="small_form_error">{{$errors->first('WineClosure')}}</small>
                                    @endif                                     
                                    <select name="WineClosure" class="form-control">
                                        <option value="">Select</option>
                                        @foreach($closures as $closure)
                                        <option value="{{$closure->ClosureId}}" {{old('WineClosure',$wineInfo->WineClosure)==$closure->ClosureId?'selected=true':''}}>{{$closure->Closure}}</option>
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
                                    <input type="number" class="form-control" value="{{old('Volume',$wineInfo->Volume)}}" step="1" min="1" name="Volume" />
                                </div>
                            </div> 
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Alcohol</label>
                                <div class="col-sm-10">
                                    @if($errors->has('Alcohol'))
                                    <small class="small_form_error">{{$errors->first('Alcohol')}}</small>
                                    @endif
                                    <input type="number" class="form-control" value="{{old('Alcohol',$wineInfo->Alcohol)}}" step="0.01" min="0" name="Alcohol" />
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-2 control-label">Catering</label>
                                <div class="col-sm-10">
                                    @if($errors->has('WineCaterings'))
                                    <small class="small_form_error">{{$errors->first('WineCaterings')}}</small>
                                    @endif
                                    <select name="WineCaterings[]" id="WineCaterings" class="form-control" multiple="multiple" maxselected=5>
                                        @php $catering_arr = array(); @endphp
                                        @if(old('WineCaterings',null)===NULL)
                                            @if(trim($wineInfo->WineCaterings)!=='' && !is_null($wineInfo->WineCaterings) && $wineInfo->WineCaterings!=='null')
                                                @php $catering_arr = json_decode($wineInfo->WineCaterings,true); @endphp
                                            @endif
                                        @else
                                            @php $catering_arr = old('WineCaterings',array()); @endphp
                                        @endif
                                        @foreach($caterings as $catering)
                                            <option value='{{$catering->CateringId}}' {{in_array($catering->CateringId,$catering_arr)?'selected=true':''}}>{{$catering->Catering}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-2 control-label">Stock</label>
                                <div class="col-sm-10">
                                    @if($errors->has('Stocks'))
                                    <small class="small_form_error">{{$errors->first('Stocks')}}</small>
                                    @endif                                    
                                    <input type="number" name="Stocks" class="form-control" value="{{old('Stocks',$wineInfo->Stocks)}}" min="1" step="1" />
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
                                        @if(trim(old('noOffShelf'))!=='')
                                        <input type="checkbox" id="forever-onShelf" name="noOffShelf" {{old('noOffShelf')==='on'?'checked=true':''}}>
                                        @else
                                        <input type="checkbox" id="forever-onShelf" name="noOffShelf" {{intval($wineInfo->NoOffShelf)===1?'checked=true':''}}>
                                        @endif
                                        <label for="forever-onShelf">Never off</label>
                                    </div>
                                    <input type="text" name="ActDate" id="ActDate" class="form-control" readonly="true" value="{{old('ActDate',$wineInfo->ActDate)}}" />
                                    <input type="text" name="EndDate" id="EndDate" class="form-control" readonly="true" placeholder="End Date" value="{{old('EndDate',$wineInfo->EndDate)}}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">BriefDesc</label>
                                <div class="col-sm-10">
                                    @if($errors->has('BriefDesc'))
                                    <small class="small_form_error">{{$errors->first('BriefDesc')}}</small>
                                    @endif
                                    <textarea name="BriefDesc" class="form-control" rows="5" maxlength="500" style="resize:vertical">{{old('BriefDesc',$wineInfo->BriefDesc)}}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">BriefDesc(En)</label>
                                <div class="col-sm-10">
                                    @if($errors->has('BriefDescEn'))
                                    <small class="small_form_error">{{$errors->first('BriefDescEn')}}</small>
                                    @endif
                                    <textarea name="BriefDescEn" class="form-control" rows="5" maxlength="500" style="resize:vertical">{{old('BriefDescEn',$wineInfo->BriefDescEn)}}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Description</label>
                                <div class="col-sm-10">
                                    @if($errors->has('Description'))
                                    <small class="small_form_error">{{$errors->first('Description')}}</small>
                                    @endif
                                    <textarea name="Description" class="form-control" id="Description">{!! old('Description',$wineInfo->Description) !!}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Description(En)</label>
                                <div class="col-sm-10">
                                    @if($errors->has('DescriptionEn'))
                                    <small class="small_form_error">{{$errors->first('DescriptionEn')}}</small>
                                    @endif
                                    <textarea name="DescriptionEn" class="form-control" id="DescriptionEn">{!! old('DescriptionEn',$wineInfo->DescriptionEn) !!}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Delivery & Returns</label>
                                <div class="col-sm-10">
                                    @if($errors->has('Delivery_Returns'))
                                    <small class="small_form_error">{{$errors->first('Delivery_Returns')}}</small>
                                    @endif
                                    <textarea name="Delivery_Returns" class="form-control" id="Delivery_Returns">{!! old('Delivery_Returns',$wineInfo->Delivery_Returns) !!}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Delivery & Returns(En)</label>
                                <div class="col-sm-10">
                                    @if($errors->has('Delivery_ReturnsEn'))
                                    <small class="small_form_error">{{$errors->first('Delivery_ReturnsEn')}}</small>
                                    @endif
                                    <textarea name="Delivery_ReturnsEn" class="form-control" id="Delivery_ReturnsEn">{!! old('Delivery_ReturnsEn',$wineInfo->Delivery_ReturnsEn) !!}</textarea>
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Order</label>
                                <div class="col-sm-10">
                                    @if($errors->has('OrderNumber'))
                                    <small class="small_form_error">{{$errors->first('OrderNumber')}}</small>
                                    @endif
                                    <input type="number" name="OrderNumber" value="{{old('OrderNumber',$wineInfo->OrderNumber)}}" class="form-control" min=1 step=1 />
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
                                                <input type="radio" id="enabled" name="Status" value=1 {{intval($wineInfo->Status)==1?'checked=true':''}}>
                                                <label for="enabled">Yes</label>
                                            </div>
                                        </div>
                                        <div class="col-xs-3">
                                            <div class="radio radio-inline">
                                                <input type="radio" id="disabled" name="Status" value=0 {{intval($wineInfo->Status)==0?'checked=true':''}}>
                                                <label for="disabled">No</label>
                                            </div>
                                        </div>
                                        @endif
                                    </div>  
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Weekly Reco.</label>
                                <div class="col-md-10">
                                    @if($errors->has('WeeklyReco'))
                                    <small class="small_form_error">{{$errors->first('WeeklyReco')}}</small>
                                    @endif
                                    <div class="row">
                                        @if(trim(old('WeeklyReco'))!=='')
                                        <div class="col-xs-3">
                                            <div class="radio radio-inline">
                                                <input type="radio" id="WeeklyReco_Y" name="WeeklyReco" value=1 {{old('WeeklyReco')==1?'checked=true':''}}>
                                                <label for="WeeklyReco_Y">Yes</label>
                                            </div>
                                        </div>
                                        <div class="col-xs-3">
                                            <div class="radio radio-inline">
                                                <input type="radio" id="WeeklyReco_N" name="WeeklyReco" value=0 {{old('WeeklyReco')==0?'checked=true':''}}>
                                                <label for="WeeklyReco_N">No</label>
                                            </div>
                                        </div>
                                        @else
                                        <div class="col-xs-3">
                                            <div class="radio radio-inline">
                                                <input type="radio" id="WeeklyReco_Y" name="WeeklyReco" value=1 {{intval($wineInfo->WeeklyReco)==1?'checked=true':''}}>
                                                <label for="WeeklyReco_Y">Yes</label>
                                            </div>
                                        </div>
                                        <div class="col-xs-3">
                                            <div class="radio radio-inline">
                                                <input type="radio" id="WeeklyReco_N" name="WeeklyReco" value=0 {{intval($wineInfo->WeeklyReco)==0?'checked=true':''}}>
                                                <label for="WeeklyReco_N">No</label>
                                            </div>
                                        </div>
                                        @endif
                                    </div>  
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Flagship</label>
                                <div class="col-md-10">
                                    @if($errors->has('Flagship'))
                                    <small class="small_form_error">{{$errors->first('Flagship')}}</small>
                                    @endif
                                    <div class="row">
                                        @if(trim(old('Flagship'))!=='')
                                        <div class="col-xs-3">
                                            <div class="radio radio-inline">
                                                <input type="radio" id="Flagship_Y" name="Flagship" value=1 {{old('Flagship')==1?'checked=true':''}}>
                                                <label for="Flagship_Y">Yes</label>
                                            </div>
                                        </div>
                                        <div class="col-xs-3">
                                            <div class="radio radio-inline">
                                                <input type="radio" id="Flagship_N" name="Flagship" value=0 {{old('Flagship')==0?'checked=true':''}}>
                                                <label for="Flagship_N">No</label>
                                            </div>
                                        </div>
                                        @else
                                        <div class="col-xs-3">
                                            <div class="radio radio-inline">
                                                <input type="radio" id="Flagship_Y" name="Flagship" value=1 {{intval($wineInfo->Flagship)==1?'checked=true':''}}>
                                                <label for="Flagship_Y">Yes</label>
                                            </div>
                                        </div>
                                        <div class="col-xs-3">
                                            <div class="radio radio-inline">
                                                <input type="radio" id="Flagship_N" name="Flagship" value=0 {{intval($wineInfo->Flagship)==0?'checked=true':''}}>
                                                <label for="Flagship_N">No</label>
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
                                <a href="/admin/products" class="btn btn-default">Cancel</a>
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