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
                <div class="card-title">群組列表</div>
                <ul class="card-action">
                    <li>
                        <a href="/admin/groups/add" class="btn btn-primary">新增群組</a>
                    </li>
                </ul>
            </div>  
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th headers="groupHeader" id="groupName">群組名稱</th>
                                    <th headers="groupHeader" id="function">功能</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($groups as $group)
                                <tr>
                                    <td>{{$group->GroupName}}</td>
                                    <td>
                                        <a href="/admin/groups/mod/{{$group->GroupId}}" class="btn-sm btn-primary">修改</a>
                                        <a href="/admin/groups/people/{{$group->GroupId}}" class="btn-sm btn-info">群組人員</a>
                                        <a href="/admin/groups/delete/{{$group->GroupId}}" class="btn-sm btn-danger" onclick="return confirm('確定要刪除此群組?')">刪除</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>                                
                </div>
            </div>
        </div>
    </div>
</div>
@endsection