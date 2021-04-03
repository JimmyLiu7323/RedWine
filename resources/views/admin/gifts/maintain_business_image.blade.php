@extends('admin.layouts.main')
@section('content')
<style type="text/css">
.form-group{margin-top:10px;margin-bottom:10px}
</style>
<div clasas="row">
    <form class="form form-horizontal" id='product_form' method="POST" enctype="multipart/form-data">
        <div class="col-xs-12">
            <div class="card">
                <div class="card-body">
                    <div class="section">
                        <div class="section-body">
                            <div class="form-group">
                                <label class="col-md-2 control-label">Impact image</label>
                                <div class="col-md-10">
                                    @if($errors->has('gift_business_image'))
                                    <small class="small_form_error">{{$errors->first('gift_business_image')}}</small>
                                    @endif                                    
                                    <div class="row">
                                        <div class="col-xs-8">
                                            <input type="text" name="gift_business_image" class="form-control" readonly="true" id="gift_business_image" value="{{old('gift_business_image',$gift_business_image)}}" />
                                        </div>
                                        <div class="col-xs-4">
                                            <button class="popup_selector btn btn-info" data-inputid="gift_business_image" style="width:100%">Select Img</button>
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