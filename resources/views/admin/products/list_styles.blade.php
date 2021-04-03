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
                <div class="card-title">Style List</div>
                <ul class="card-action">
                    <li>
                        <a href="/admin/products/styles/add" class="btn btn-primary">Add Style</a>
                    </li>
                </ul>
            </div>  
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table" id='list-table'>
                            <thead>
                                <tr>
                                    <th headers="styleHeader" id="name">Style Name</th>
                                    <th headers="styleHeader" id="orderNumber">Order</th>
                                    <th headers="styleHeader" id="status">Status</th>
                                    <th headers="styleHeader" id="func">Func</th>
                                </tr>
                            </thead>
                            <tbody>
                               @foreach($styles as $style)
                               <tr>
                                   <td>{{$style->Style}}</td>
                                   <td>{{$style->OrderNumber}}</td>
                                   <td>
                                       @if(intval($style->Status)===0)Close
                                       @else enable
                                       @endif
                                   </td>
                                   <td>
                                        <a href="/admin/products/styles/mod/{{$style->StyleId}}" class="btn-sm btn-primary">Edit</a>
                                        <a href="/admin/products/styles/delete/{{$style->StyleId}}" class="btn-sm btn-danger" onclick="return confirm('Are you sure to delete?')">Delete</a>                                        
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