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
    title:'成功',
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
                <div class="card-title">帳號列表</div>
                <ul class="card-action">
                    <li>
                        <a href="/admin/accounts/add" class="btn btn-primary">新增帳號</a>
                    </li>
                </ul>
            </div>  
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th headers="accountHeader" id="name">姓名</th>
                                    <th headers="accountHeader" id="account">帳號</th>
                                    <th headers="accountHeader" id="accountDepartment">單位</th>
                                    <th headers="accountHeader" id="accountStatus">狀態</th>
                                    <th headers="accountHeader" id="accountFunc">功能</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($accounts as $account)
                                <tr>
                                    <td>{{$account->AdminName}}</td>
                                    <td>{{$account->AdminAccount}}</td>
                                    <td>{{$account->Department===''?'未指定':$account->Department}}</td>
                                    <td>{{$account->Status==0?'未啟用':'已啟用'}}</td>
                                    <td>
                                        <a href="/admin/accounts/mod/{{$account->AdminId}}" class="btn-sm btn-primary">編輯</a>
                                        <a href="/admin/accounts/delete/{{$account->AdminId}}" class="btn-sm btn-danger" onclick="return confirm('確定要刪除該帳號?')">刪除</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if(count($accounts)>0)
                        {{$accounts->appends(request()->query())->links()}}
                        @endif
                    </div>                                
                </div>
            </div>
        </div>
    </div>
</div>
@endsection