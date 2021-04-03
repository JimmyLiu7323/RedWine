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
                                <label class="col-md-2 control-label"><span style="color:red">*</span> Order</label>
                                <div class="col-md-10">
                                    @if($errors->has('BannerImage'))
                                    <small class="small_form_error">{{$errors->first('BannerImage')}} (Image)</small>
                                    @endif
                                    @if($errors->has('BannerOrder'))
                                    <small class="small_form_error">{{$errors->first('BannerOrder')}}</small>
                                    @endif
                                    <div class="row">
                                        <div class="col-md-2">
                                            <input type="number" class="form-control" step="1" min="1" max="99" name="BannerOrder" value="{{old('BannerOrder',$BannerOrder)}}" />
                                        </div>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control" readonly="true" placeholder="Banner image" name="BannerImage" id="BannerImage" value="{{old('BannerImage',$BannerImage)}}" />
                                        </div>
                                        <div class="col-md-3">
                                            <button style="width:100%" type="button" data-inputid="BannerImage" class="btn btn-info popup_selector">Explore</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Link</label>
                                <div class="col-md-10">
                                    @if($errors->has('BannerLink'))
                                    <small class="small_form_error">{{$errors->first('BannerLink')}}</small>
                                    @endif
                                    <input type="text" name="BannerLink" class="form-control" value="{{old('BannerLink',$BannerLink)}}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label"><span style="color:red">*</span>上架</label>
                                <div class="col-md-10">
                                    @if($errors->has('BannerOnStatus'))
                                    <small class="small_form_error">{{$errors->first('BannerOnStatus')}} (Status)</small>
                                    @endif
                                    @if($errors->has('BannerOnDate'))
                                    <small class="small_form_error">{{$errors->first('BannerOnDate')}} (Date)</small>
                                    @endif
                                    <div class="row">
                                        <div class="col-md-6">
                                            <select name="BannerOnStatus" class="form-control" style="height:58px">
                                                <option value="1" {{old('BannerOnStatus',$BannerOnStatus)==1?'selected=true':''}}>Yes</option>
                                                <option value="0" {{old('BannerOnStatus',$BannerOnStatus)==0?'selected=true':''}}>No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="date" class="form-control" name="BannerOnDate" value="{{old('BannerOnDate',$BannerOnDate)}}" />
                                        </div>
                                    </div>
                                </div>                                
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">下架</label>
                                <div class="col-md-10">
                                    @if($errors->has('BannerOffDate'))
                                    <small class="small_form_error">{{$errors->first('BannerOffDate')}}</small>
                                    @endif
                                    <input type="date" class="form-control" name="BannerOffDate" value="{{old('BannerOffDate',$BannerOffDate)}}" />
                                </div>                                
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="_token" id="csrf-token" value="{{csrf_token()}}" />
                    <input type="hidden" name="action" value="{{$action}}" />
                    <div class="form-footer">
                        <div class="form-group">
                            <div class="col-md-12">
                                <button type='submit' class='btn btn-primary'>Save</button>
                                <a href="/admin/gifts/banners" class="btn btn-default">Cancel</a>
                            </div>
                        </div>
                    </div>              
                </form>
            </div>
        </div>
    </div>
</div>
@endsection