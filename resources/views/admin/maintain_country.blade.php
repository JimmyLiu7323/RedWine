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
                                    @if($errors->has('CountryMainImage'))
                                    <small class="small_form_error">{{$errors->first('CountryMainImage')}}</small>
                                    @endif
                                    <div class="row">
                                        <div class="col-xs-8">
                                            <input type="text" name="CountryMainImage" class="form-control" readonly="true" id="CountryMainImage" value="{{old('CountryMainImage',$countryInfo['CountryMainImage'])}}" />
                                        </div>
                                        <div class="col-xs-4">
                                            <button class="popup_selector btn btn-info" type="button" data-inputid="CountryMainImage" style="width:100%">Select Img</button>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                            <div class="form-group">
                                <label class="col-md-2 control-label">Country code</label>
                                <div class="col-md-10">
                                    @if($errors->has('CountryId'))
                                    <small class="small_form_error">{{$errors->first('CountryId')}}</small>
                                    @endif
                                    <input type="text" name="CountryId" class="form-control" value="{{old('CountryId',$countryInfo['CountryId'])}}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Country</label>
                                <div class="col-md-10">
                                    @if($errors->has('Country'))
                                    <small class="small_form_error">{{$errors->first('Country')}}</small>
                                    @endif
                                    <input type="text" name="Country" class="form-control" value="{{old('Country',$countryInfo['Country'])}}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Country(En)</label>
                                <div class="col-md-10">
                                    @if($errors->has('CountryEn'))
                                    <small class="small_form_error">{{$errors->first('CountryEn')}}</small>
                                    @endif
                                    <input type="text" name="CountryEn" class="form-control" value="{{old('CountryEn',$countryInfo['CountryEn'])}}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Description</label>
                                <div class="col-md-10">
                                    @if($errors->has('CountryDesc'))
                                    <small class="small_form_error">{{$errors->first('CountryDesc')}}</small>
                                    @endif
                                    <textarea class="form-control" name="CountryDesc" rows="3" style="resize:none">{!! old('CountryDesc',$countryInfo['CountryDesc']) !!}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Description(En)</label>
                                <div class="col-md-10">
                                    @if($errors->has('CountryDescEn'))
                                    <small class="small_form_error">{{$errors->first('CountryDescEn')}}</small>
                                    @endif
                                    <textarea class="form-control" name="CountryDescEn" rows="3" style="resize:none">{!! old('CountryDescEn',$countryInfo['CountryDescEn']) !!}</textarea>
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
                                <a href="/admin/system/countries" class="btn btn-default">Cancel</a>
                            </div>
                        </div>
                    </div>              
                </form>
            </div>
        </div>
    </div>
</div>
@endsection