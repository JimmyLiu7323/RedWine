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
#list-table tr th,#list-table tr td{padding:10px 3px}
#list-table tr th:nth-of-type(1){width:25%}
#list-table tr th:nth-of-type(2){width:25%}
#list-table tr th:nth-of-type(3){width:25%}
#list-table tr th:nth-of-type(4){width:25%}
</style>
<div class="row">
    <div class="col-xs-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Color List</div>
                <ul class="card-action">
                    <li>
                        <a href="/admin/products/colours/add" class="btn btn-primary">Add Color</a>
                    </li>
                </ul>
            </div>  
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table" id="list-table">
                            <thead>
                                <tr>
                                    <th headers="colourHeader" id="name">Color</th>
                                    <th headers="colourHeader" id="orderNumber">Order</th>
                                    <th headers="colourHeader" id="status">Status</th>
                                    <th headers="colourHeader" id="func">Func</th>
                                </tr>
                            </thead>
                            <tbody>
                               @foreach($colours as $colour)
                               <tr>
                                   <td>{{$colour->Colour}}</td>
                                   <td>{{$colour->OrderNumber}}</td>
                                   <td>
                                       @if(intval($colour->Status)===0){{'Close'}}
                                       @else{{'Enable'}}
                                       @endif
                                   </td>
                                   <td>
                                       <a href="/admin/products/colours/mod/{{$colour->ColourId}}" class="btn-sm btn-primary">Edit</a>
                                        <a href="/admin/products/colours/delete/{{$colour->ColourId}}" class="btn-sm btn-danger" onclick="return confirm('Are you sure to delete?')">Delete</a>
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