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
<style type="text/css">
.form-group{margin-top:10px;margin-bottom:10px}
.checkbox h4{margin:0}
</style>
<div class="row">
    <div class="col-xs-12">
        <div class="card">
            <div class="card-body">
                <form class="form form-horizontal" id='product_form' method="POST" enctype="multipart/form-data">
                    <div class="section">
                        <div class="section-body">
                            <div class="form-group">
                                <label class="col-md-2 control-label">編輯中群組</label>
                                <div class="col-md-10">
                                    <input type="text" readonly="true" class="form-control" value="{{$group->GroupName}}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">人員選擇</label>
                                <div class="col-md-10">
                                    @if($accounts->count()>0)
                                    <div class="row">
                                        @foreach($accounts as $accountIdx=>$account)
                                        <div class="col-xs-4">
                                            <div class="checkbox">
                                                <input type="checkbox" id="account{{$accountIdx}}" name="groupMember[]" value="{{$account->AdminId}}" {{$account->AdminAuthority==$group->GroupId?'checked=true':''}}>
                                                <label for="account{{$accountIdx}}">{{$account->AdminName}}</label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    @else
                                    <label class="control-label">尚未新增人員帳號</label>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="_token" id="csrf-token" value="{{csrf_token()}}" />
                    <div class="form-footer">
                        <div class="form-group">
                            <div class="col-md-12">
                                <button type='submit' class='btn btn-primary'>儲存</button>
                                <a href="/admin/groups" class="btn btn-default">取消</a>
                            </div>
                        </div>
                    </div>              
                </form>
            </div>
        </div>
    </div>
</div>
@endsection