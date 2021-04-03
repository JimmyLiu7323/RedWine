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
                <div class="card-header">
                    <div class="card-title">Our Guarantee</div>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="col-md-2 control-label">Guarantee introduction</label>
                        <div class="col-md-10">
                            @if($errors->has('guarantee_text'))
                            <small class="small_form_error">{{$errors->first('guarantee_text')}}</small>
                            @endif
                            <textarea name="guarantee_text" maxlength="800" class="form-control" rows="5" style="resize:none">{!! old('guarantee_text',$guarantee_text) !!}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Guarantee introduction(En)</label>
                        <div class="col-md-10">
                            @if($errors->has('guarantee_text_en'))
                            <small class="small_form_error">{{$errors->first('guarantee_text_en')}}</small>
                            @endif
                            <textarea name="guarantee_text_en" maxlength="800" class="form-control" rows="5" style="resize:none">{!! old('guarantee_text_en',$guarantee_text_en) !!}</textarea>
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