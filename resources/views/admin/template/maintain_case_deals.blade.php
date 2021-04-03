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
                                    @if($errors->has('case_deals_impact_image'))
                                    <small class="small_form_error">{{$errors->first('case_deals_impact_image')}}</small>
                                    @endif                                    
                                    <div class="row">
                                        <div class="col-xs-8">
                                            <input type="text" name="case_deals_impact_image" class="form-control" readonly="true" id="case_deals_impact_image" value="{{old('case_deals_impact_image',$case_deals_impact_image)}}" />
                                        </div>
                                        <div class="col-xs-4">
                                            <button class="popup_selector btn btn-info" data-inputid="case_deals_impact_image" style="width:100%">Select Img</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Subtitle</label>
                                <div class="col-md-10">
                                    @if($errors->has('case_deals_subtitle'))
                                    <small class="small_form_error">{{$errors->first('case_deals_subtitle')}}</small>
                                    @endif
                                    <input type="text" name="case_deals_subtitle" class="form-control" value="{{old('case_deals_subtitle',$case_deals_subtitle)}}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Subtitle(En)</label>
                                <div class="col-md-10">
                                    @if($errors->has('case_deals_subtitle_en'))
                                    <small class="small_form_error">{{$errors->first('case_deals_subtitle_en')}}</small>
                                    @endif
                                    <input type="text" name="case_deals_subtitle_en" class="form-control" value="{{old('case_deals_subtitle_en',$case_deals_subtitle_en)}}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Description</label>
                                <div class="col-md-10">
                                    @if($errors->has('case_deals_description'))
                                    <small class="small_form_error">{{$errors->first('case_deals_description')}}</small>
                                    @endif
                                    <textarea name="case_deals_description" class="form-control" maxlength="500" rows="3" style="resize:vertical">{!! old('case_deals_description',$case_deals_description) !!}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Description(En)</label>
                                <div class="col-md-10">
                                    @if($errors->has('case_deals_description_en'))
                                    <small class="small_form_error">{{$errors->first('case_deals_description_en')}}</small>
                                    @endif
                                    <textarea name="case_deals_description_en" class="form-control" maxlength="500" rows="3" style="resize:vertical">{!! old('case_deals_description_en',$case_deals_description_en) !!}</textarea>
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