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
#list-table{table-layout:fixed}
#list-table tr td{padding:10px}
#mainTH th:nth-of-type(1){width:20%}
#mainTH th:nth-of-type(2){width:60%}
#mainTH th:nth-of-type(3){width:20%}
</style>
<div class="row">
    <div class="col-xs-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Basic category</div>
            </div>  
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-hover" width="100%" id="list-table">
                            <thead>
                                <tr id="mainTH">
                                    <th headers="wineHeader" id="WineCatg">Category</th>
                                    <th headers="wineHeader" id="WineDescription">Description</th>
                                    <th headers="wineHeader" id="Func">Func</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($basic_category as $category)
                                <tr>
                                    <td>{{ucfirst($category->Category)}}</td>
                                    <td>{{$category->Description}}</td>
                                    <td>
                                        <a href="/admin/products/basic_category/edit?id={{$category->Category}}" class="btn-primary btn-sm">Edit</a>
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