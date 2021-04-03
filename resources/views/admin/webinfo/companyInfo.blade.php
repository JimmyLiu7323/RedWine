@extends('admin.layouts.main')
@section('content')
@if(\Session::has('maintain_message'))
<script>
    @if(\Session::has('maintain_message_fail'))
Swal.fire({
    type:'error',
    title:'Oops...',
    customClass:'swal-wide',
    text:"{!! \Session::get('maintain_message_fail') !!}",
})
    @elseif(\Session::has('maintain_message_success'))
Swal.fire({
    type:'success',
    title:'Success',
    customClass:'swal-wide',
    text:"{!! \Session::get('maintain_message_success') !!}",
})        
    @endif
</script>
@endif
<div class="row">
    <div class="col-xs-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">公司資訊</div>
            </div>
            <div class="card-body">
                <form class="form form-horizontal" id='product_form' method="POST" enctype="multipart/form-data">
                    <div class="section">
                        <div class="section-body">
                            @foreach($companyInfo as $item)
                            <div class="form-group">
                                <label class="col-md-3 control-label">{{$item->InfoKey_Zh}}</label>
                                <div class="col-md-9">
                                    @if($errors->has($item->InfoKey))
                                    <small class="small_form_error">{{$errors->first($item->InfoKey)}}</small>
                                    @endif
                                    <input type="text" class="form-control" value="{!!old($item->InfoKey,$item->InfoValue)!!}" name="{{$item->InfoKey}}"/>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <input type="hidden" name="_token" id="csrf-token" value="{{csrf_token()}}" />
                    <div class="form-footer">
                        <div class="form-group">
                            <div class="col-md-12">
                                <button type='submit' class='btn btn-primary'>儲存</button>                                            
                            </div>
                        </div>
                    </div>
                </form>                            
            </div>
        </div>
    </div>
</div>
@endsection