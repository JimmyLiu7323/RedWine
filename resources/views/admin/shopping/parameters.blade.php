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
#parameter-table tr th:nth-of-type(1){width:70%}
#parameter-table tr th:nth-of-type(2){width:30%}
</style>
<div class="row">
    <div class="col-xs-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Parameters</div>
            </div>  
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table" id="parameter-table">
                            <thead>
                                <tr>
                                    <th headers="parameterHeader" id="parameter_name">Name</th>
                                    <th headers="parameterHeader" id="funcs"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Freight & GST</td>
                                    <td>
                                        <a href="/admin/shopping/parameter/edit?key=freight" class="btn-sm btn-primary">Edit</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Discount with member</td>
                                    <td>
                                        <a href="/admin/shopping/parameter/edit?key=discount" class="btn-sm btn-primary">Edit</a>
                                    </td>
                                </tr>                                
                            </tbody>
                        </table>
                    </div>                                
                </div>
            </div>
        </div>
    </div>
</div>
@endsection