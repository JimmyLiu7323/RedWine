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
                                <label class="col-md-2 control-label">Display</label>
                                <div class="col-md-10">
                                    @if($errors->has('display_global_notice'))
                                    <small class="small_form_error">{{$errors->first('display_global_notice')}}</small>
                                    @endif
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <div class="radio radio-inline">
                                                <input type="radio" id="Display" name="display_global_notice" value=1 {{intval(old('display_global_notice',$display_global_notice))===1?'checked=true':''}}>
                                                <label for="Display">Display</label>
                                            </div>
                                        </div>
                                        <div class="col-xs-3">
                                            <div class="radio radio-inline">
                                                <input type="radio" id="Hide" name="display_global_notice" value=0 {{intval(old('display_global_notice',$display_global_notice))===0?'checked=true':''}}>
                                                <label for="Hide">Hide</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Content</label>
                                <div class="col-md-10">
                                    @if($errors->has('global_notice'))
                                    <small class="small_form_error">{{$errors->first('global_notice')}}</small>
                                    @endif
                                    <input type="text" value="{{old('global_notice',$global_notice)}}" name="global_notice" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Content(En)</label>
                                <div class="col-md-10">
                                    @if($errors->has('global_notice_en'))
                                    <small class="small_form_error">{{$errors->first('global_notice_en')}}</small>
                                    @endif
                                    <input type="text" value="{{old('global_notice_en',$global_notice_en)}}" name="global_notice_en" class="form-control" />
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