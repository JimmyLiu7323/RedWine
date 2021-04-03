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
<style type="text/css">.form-group{margin-top:10px;margin-bottom:10px}</style>
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
                                    @if($errors->has('CasePic'))
                                    <small class="small_form_error">{{$errors->first('CasePic')}}</small>
                                    @endif
                                    <div class="row">
                                        <div class="col-xs-8">
                                            <input type="text" name="CasePic" class="form-control" readonly="true" id="CasePic" value="{{old('CasePic')}}" />
                                        </div>
                                        <div class="col-xs-4">
                                            <button class="popup_selector btn btn-info" data-inputid="CasePic" style="width:100%">Select Img</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Case Name</label>
                                <div class="col-sm-10">
                                    @if($errors->has('CaseName'))
                                    <small class="small_form_error">{{$errors->first('CaseName')}}</small>
                                    @endif
                                    <input type="text" name="CaseName" value="{{old('CaseName')}}" class="form-control" maxlength="255" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Case Name(En)</label>
                                <div class="col-sm-10">
                                    @if($errors->has('CaseNameEn'))
                                    <small class="small_form_error">{{$errors->first('CaseNameEn')}}</small>
                                    @endif
                                    <input type="text" name="CaseNameEn" value="{{old('CaseNameEn')}}" class="form-control" maxlength="255" />
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
                                <a href="/admin/products/cases" class="btn btn-default">Cancel</a>
                            </div>
                        </div>
                    </div>              
                </form>
            </div>
        </div>
    </div>
</div>
@endsection