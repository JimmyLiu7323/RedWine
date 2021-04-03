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
                                <label class="col-md-2 control-label">Display guarantee</label>
                                <div class="col-md-10">
                                    @if($errors->has('display_index_guarantee_image'))
                                    <small class="small_form_error">{{$errors->first('display_index_guarantee_image')}}</small>
                                    @endif
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <div class="radio radio-inline">
                                                <input type="radio" id="DisplayGuarantee" name="display_index_guarantee_image" value=1 {{intval(old('display_index_guarantee_image',$display_index_guarantee_image))===1?'checked=true':''}}>
                                                <label for="DisplayGuarantee">Display</label>
                                            </div>
                                        </div>
                                        <div class="col-xs-3">
                                            <div class="radio radio-inline">
                                                <input type="radio" id="HideGuarantee" name="display_index_guarantee_image" value=0 {{intval(old('display_index_guarantee_image',$display_index_guarantee_image))===0?'checked=true':''}}>
                                                <label for="HideGuarantee">Hide</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Display promotion</label>
                                <div class="col-md-10">
                                    @if($errors->has('display_index_promotion_image'))
                                    <small class="small_form_error">{{$errors->first('display_index_promotion_image')}}</small>
                                    @endif
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <div class="radio radio-inline">
                                                <input type="radio" id="DisplayPromotion" name="display_index_promotion_image" value=1 {{intval(old('display_index_promotion_image',$display_index_promotion_image))===1?'checked=true':''}}>
                                                <label for="DisplayPromotion">Display</label>
                                            </div>
                                        </div>
                                        <div class="col-xs-3">
                                            <div class="radio radio-inline">
                                                <input type="radio" id="HidePromotion" name="display_index_promotion_image" value=0 {{intval(old('display_index_promotion_image',$display_index_promotion_image))===0?'checked=true':''}}>
                                                <label for="HidePromotion">Hide</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Guarantee image</label>
                                <div class="col-md-10">
                                    @if($errors->has('index_guarantee_image'))
                                    <small class="small_form_error">{{$errors->first('index_guarantee_image')}}</small>
                                    @endif                                    
                                    <div class="row">
                                        <div class="col-xs-8">
                                            <input type="text" name="index_guarantee_image" class="form-control" readonly="true" id="IndexGuaranteeImage" value="{{old('index_guarantee_image',$index_guarantee_image)}}" />
                                        </div>
                                        <div class="col-xs-4">
                                            <button class="popup_selector btn btn-info" data-inputid="IndexGuaranteeImage" style="width:100%">Select Img</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Promotion image</label>
                                <div class="col-md-10">
                                    @if($errors->has('index_promotion_image'))
                                    <small class="small_form_error">{{$errors->first('index_promotion_image')}}</small>
                                    @endif                                    
                                    <div class="row">
                                        <div class="col-xs-8">
                                            <input type="text" name="index_promotion_image" class="form-control" readonly="true" id="IndexPromotionImage" value="{{old('index_promotion_image',$index_promotion_image)}}" />
                                        </div>
                                        <div class="col-xs-4">
                                            <button class="popup_selector btn btn-info" data-inputid="IndexPromotionImage" style="width:100%">Select Img</button>
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