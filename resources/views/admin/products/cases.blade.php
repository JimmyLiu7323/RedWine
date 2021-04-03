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
<style type="text/css">
#list-table tr th,#list-table tr td{padding:10px 0}
#list-table tr th:nth-of-type(1){width:30%}
#list-table tr th:nth-of-type(2){width:30%}
#list-table tr th:nth-of-type(3){width:20%}
#list-table tr th:nth-of-type(4){width:20%}
</style>
<div class="row">
    <div class="col-xs-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Cases</div>
                <ul class="card-action">
                    <li>
                        <a href="/admin/products/cases/add" class="btn btn-primary">Add Case</a>
                    </li>
                </ul>
            </div>  
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table" id="list-table">
                            <thead>
                                <tr>
                                    <th headers="mixHeader" id="nameZh">Name</th>
                                    <th headers="mixHeader" id="nameEn">Name(En)</th>
                                    <th headers="mixHeader" id="status">Status</th>
                                    <th headers="mixHeader" id="funcs">Func</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cases as $case)
                                <tr>
                                    <td>{{$case->CaseName}}</td>
                                    <td>{{$case->CaseNameEn}}</td>
                                    <td>{{intval($case->Status)===0?'Off':'On'}}</td>
                                    <td>
                                        <p>
                                            <a href="/admin/products/cases/mod?id={{$case->CaseId}}" class="btn-sm btn-primary">Edit</a>
                                        </p>
                                        <p>
                                            <a href="/admin/products/cases/delete?id={{$case->CaseId}}" class="btn-sm btn-danger" onclick="return confirm('Are you sure to delete?')">Delete</a>
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