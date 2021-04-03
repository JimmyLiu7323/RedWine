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
#list-table tr th{padding:10px}
#mainTH th:nth-of-type(1){width:10%}
#mainTH th:nth-of-type(2){width:40%}
#mainTH th:nth-of-type(3){width:30%}
#mainTH th:nth-of-type(4){width:20%}
</style>
<div class="row">
    <div class="col-xs-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Featured image(Index Page)</div>
            </div>  
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-hover" width="100%" id="list-table">
                            <thead>
                                <tr id="mainTH">
                                    <th>Name</th>
                                    <th>Link file</th>
                                    <th>Status</th>
                                    <th>Func</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($images as $image)
                                <tr>
                                    <td>Featured image {{$image->SN}}</td>
                                    <td>{{$image->LinkFile}}</td>
                                    <td>{{intval($image->Status) === 0 ? 'Disable' : 'Enable'}}</td>
                                    <td>
                                        <a href="/admin/template/index_featured_image/edit/{{$image->SN}}" class="btn-sm btn-primary">Edit</a>
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