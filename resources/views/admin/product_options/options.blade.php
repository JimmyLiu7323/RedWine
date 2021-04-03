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
#list-table tr th:nth-of-type(1){width:40%}
#list-table tr th:nth-of-type(2){width:30%}
#list-table tr th:nth-of-type(3){width:20%}
#list-table tr th:nth-of-type(4){width:10%}
#list-table tr th,#list-table tr td{padding:10px 0}
</style>
<div class="row">
    <div class="col-xs-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Options</div>
                <ul class="card-action">
                    <li>
                        <a href="/admin/products/options/add" class="btn btn-primary">Add Option</a>
                    </li>
                </ul>
            </div>  
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table" id="list-table">
                            <thead>
                                <tr>
                                    <th headers="optionHeader" id="optionRule">Option Rule</th>
                                    <th headers="optionHeader" id="optionPrice">Price</th>
                                    <th headers="optionHeader" id="optionPurchaseAmount">Purchase amount</th>
                                    <th headers="optionHeader" id="funcs">Func</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($options as $option)
                                <tr>
                                    <td>
                                        @if(trim($option->OptionRule) === 'discount'){{'Discount with purchase of a target amount'}}
                                        @elseif(trim($option->OptionRule) === 'combineSelling'){{'Combine selling'}}
                                        @endif
                                    </td>
                                    <td>$ {{number_format($option->Price,2)}}</td>
                                    <td>@if(trim($option->OptionRule=='discount')){{$option->PurchaseAmount}}@endif</td>
                                    <td>
                                        <p>
                                            <a href="/admin/products/options/edit/{{$option->OptionId}}" class="btn-sm btn-primary">Edit</a>
                                        </p>
                                        <p>
                                            <a href="/admin/products/options/delete/{{$option->OptionId}}" class="btn-sm btn-danger" onclick="return confirm('Sure about delete?')">Delete</a>
                                        </p>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if(count($options)>0)
                        {{$options->appends(request()->query())->links()}}
                        @endif
                    </div>                                
                </div>
            </div>
        </div>
    </div>
</div>
@endsection