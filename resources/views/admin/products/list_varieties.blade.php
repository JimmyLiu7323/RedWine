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
#list-table tr th:nth-of-type(1){width:35%}
#list-table tr th:nth-of-type(2){width:25%}
#list-table tr th:nth-of-type(3){width:25%}
#list-table tr th:nth-of-type(4){width:15%}
</style>
<div class="row">
    <div class="col-xs-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Product Variety List</div>
                <ul class="card-action">
                    <li>
                        <a href="/admin/products/varieties/add" class="btn btn-primary">Add Varity</a>
                    </li>
                </ul>
            </div>  
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table" id="list-table">
                            <thead>
                                <tr>
                                    <th headers="varietyHeader" id="name">Variety</th>
                                    <th headers="varietyHeader" id="orderNumber">Order(Ascending)</th>
                                    <th headers="varietyHeader" id="status">Status</th>
                                    <th headers="varietyHeader" id="func">Func</th>
                                </tr>
                            </thead>
                            <tbody>
                               @foreach($varieties as $variety)
                               <tr>
                                   <td>{{$variety->Variety}}</td>
                                   <td>{{$variety->OrderNumber}}</td>
                                   <td>
                                       @if(intval($variety->Status)===0){{'Disable'}}
                                       @else{{'Enable'}}
                                       @endif
                                   </td>
                                   <td>
                                        <a href="/admin/products/varieties/mod/{{$variety->VarietyId}}" class="btn-sm btn-primary">Edit</a>
                                        <a href="/admin/products/varieties/delete/{{$variety->VarietyId}}" class="btn-sm btn-danger" onclick="return confirm('Are you sure to delete?')">Delete</a>
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