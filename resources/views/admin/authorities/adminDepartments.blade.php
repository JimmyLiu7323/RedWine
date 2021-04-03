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
                <div class="card-title">單位列表</div>
                <ul class="card-action">
                    <li>
                        <a href="/admin/departments/add" class="btn btn-primary">新增單位</a>
                    </li>
                </ul>
            </div>  
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th headers="departmentHeader" id="department">單位</th>
                                    <th headers="departmentHeader" id="order">排序</th>
                                    <th headers="departmentHeader" id="function">功能</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($departments as $dIdx=>$department)
                                <tr>
                                    <td>{{$department->Department}}</td>
                                    <td>
                                        {{$department->OrderNumber}}
                                        @if($dIdx==0)
                                        <a class="btn-sm btn-primary" href="/admin/departments/setOrder?way=down&id={{$department->DepartmentId}}"><i class="fa fa-arrow-down" aria-hidden="true"></i></a>
                                        @elseif($dIdx==$departments->count()-1)
                                        <a class="btn-sm btn-info" href="/admin/departments/setOrder?way=up&id={{$department->DepartmentId}}"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>
                                        @else
                                        <a class="btn-sm btn-primary" href="/admin/departments/setOrder?way=down&id={{$department->DepartmentId}}"><i class="fa fa-arrow-down" aria-hidden="true"></i></a>
                                        <a class="btn-sm btn-info" href="/admin/departments/setOrder?way=up&id={{$department->DepartmentId}}"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>
                                        @endif
                                    </td>
                                    <td>
                                        <p>
                                            <a href="/admin/departments/del/{{$department->DepartmentId}}" class="btn-sm btn-danger" onclick="return confirm('確定要刪除該部門?')">
                                                <i class="fa fa-trash" aria-hidden="true"></i> 刪除
                                            </a>
                                        </p>
                                        <p>
                                            <a href="/admin/departments/mod/{{$department->DepartmentId}}" class="btn-sm btn-primary">
                                                <i class="fa fa-pencil" aria-hidden="true"></i> 修改
                                            </a>
                                        </p>
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