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
@elseif(\Session::has('maintain_message_success'))
<script>
Swal.fire({
    type:'success',
    title:'成功',
    customClass:'swal-wide',
    text:"{!! \Session::get('maintain_message_success') !!}",
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
                                <label class="col-xs-2 control-label">群組</label>
                                <div class="col-xs-10">
                                    @if(intval($self_data->AdminAuthority===0))
                                    <input type="text" readonly="true" class="form-control" value="最高權限管理者" />
                                    @else
                                    <input type="text" readonly="true" class="form-control" value="最高權限管理者" />
                                    @endif
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label class="col-xs-2 control-label">帳號</label>
                                <div class="col-xs-10">
                                    <input type="text" name="AdminAccount" class="form-control" value="{{old('AdminAccount',$self_data->AdminAccount)}}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-xs-2 control-label">密碼</label>
                                <div class="col-xs-10">
                                    <input type="password" name="AdminPassword" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-xs-2 control-label">名稱</label>
                                <div class="col-xs-10">
                                    <input type="text" name="AdminName" class="form-control" value="{{old('AdminName',$self_data->AdminName)}}">
                                </div>
                            </div>                            
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