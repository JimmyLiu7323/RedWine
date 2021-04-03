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
                <div class="card-title">Catering List</div>
                <ul class="card-action">
                    <li>
                        <a href="/admin/products/caterings/add" class="btn btn-primary">Add Catering</a>
                    </li>
                </ul>
            </div>  
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th headers="cateringHeader" id="name">Catering</th>
                                    <th headers="cateringHeader" id="status">Status</th>
                                    <th headers="cateringHeader" id="memo">Note</th>
                                    <th headers="cateringHeader" id="func">Func</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($caterings as $catering)
                                <tr>
                                    <td>{{$catering->Catering}}</td>
                                    <td>
                                        @if(intval($catering->Status===0))Close
                                        @else Enable
                                        @endif
                                    </td>
                                    <td>{{$catering->Memo}}</td>
                                    <td>
                                        <p>
                                            <a href="/admin/products/caterings/mod/{{$catering->CateringId}}" class="btn-sm btn-primary">Edit</a>
                                        </p>
                                        <p>
                                            <a href="/admin/products/caterings/delete/{{$catering->CateringId}}" class="btn-sm btn-danger" onclick="return confirm('Are you sure to delete?')">Delete</a>
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