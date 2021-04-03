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
#list-table tr th,#list-table tr td{padding:10px;text-align:center}
#mainTH th:nth-of-type(1){width:10%}
#mainTH th:nth-of-type(2){width:30%}
#mainTH th:nth-of-type(3){width:10%}
#mainTH th:nth-of-type(4){width:20%}
#mainTH th:nth-of-type(5){width:10%}
#mainTH th:nth-of-type(6){width:20%}
</style>
<div class="row">
    <div class="col-xs-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Gift List</div>
                <ul class="card-action">
                    <li>
                        <a href="/admin/gifts/add" class="btn btn-primary">Add Gift</a>
                    </li>
                </ul>
            </div>  
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-striped table-bordered table-hover" width="100%" id="list-table">
                            <thead>
                                <tr id="mainTH">
                                    <th align="center" style="text-align:center" headers="wineHeader" id="WineCatg">Category</th>
                                    <th align="center" style="text-align:center" headers="wineHeader" id="Name">Name</th>
                                    <th align="center" style="text-align:center" headers="wineHeader" id="Price">Price</th>
                                    <th align="center" style="text-align:center" headers="wineHeader" id="SalesSection">Shelve Time</th>
                                    <th align="center" style="text-align:center" headers="wineHeader" id="Status">Status</th>
                                    <th align="center" style="text-align:center" headers="wineHeader" id="Func">Func</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($gifts as $gift)
                                <tr>
                                    <td>{{$gift->Category}}</td>
                                    <td>{{$gift->Name}}</td>
                                    <td>{{$gift->Price}}</td>
                                    <td>{{$gift->ActDate}} ~ {{$gift->EndDate}}</td>
                                    <td>
                                        @if( $gift->ActDate<=date('Y-m-d') && $gift->EndDate>date('Y-m-d') && intval($gift->Status)===1 )
                                        Enable
                                        @else
                                        Disable
                                        @endif
                                    </td>
                                    <td style="text-align:left">
                                        <p>
                                            <a href="/admin/gifts/edit?id={{$gift->GiftId}}" class="btn-sm btn-primary">Edit</a>
                                        </p>
                                        <p>
                                            <a href="/admin/gifts/delete?id={{$gift->GiftId}}" class="btn-sm btn-danger" onclick="return confirm('Sure to delete this gift product?')">Delete</a>
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