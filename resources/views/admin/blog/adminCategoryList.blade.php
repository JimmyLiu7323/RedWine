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
                <div class="card-title">Category</div>
                <ul class="card-action">
                    <li>
                        <a href="/admin/blog/category/maintain" class="btn btn-primary">Add</a>
                    </li>
                </ul>
            </div>  
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th width="30%">Category</th>
                                    <th width="30%">Order</th>
                                    <th width="40%">Functions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $category)
                                <tr>
                                    <td>{{$category->Category}}</td>
                                    <td>{{$category->OrderNumber}}</td>
                                    <td>
                                        <a href="/admin/blog/category/maintain?id={{$category->CategoryId}}" class="btn-sm btn-primary">Edit</a>
                                        <a href="/admin/blog/category/delete?id={{$category->CategoryId}}" class="btn-sm btn-danger" onclick="return confirm('Sure about delete?')">Delete</a>
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