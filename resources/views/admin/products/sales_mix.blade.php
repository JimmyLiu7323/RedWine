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
</style>
<div class="row">
    <div class="col-xs-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Sale Topic</div>
                <ul class="card-action">
                    <li>
                        <a href="/admin/products/mix/add" class="btn btn-primary">Add Topic</a>
                    </li>
                </ul>
            </div>  
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table" id="list-table">
                            <thead>
                                <tr>
                                    <th headers="mixHeader" id="name">Name</th>
                                    <th headers="mixHeader" id="wineCounts">Amount</th>
                                    <th headers="mixHeader" id="status">Status</th>
                                    <th headers="mixHeader" id="salesTime">Sale Date</th>
                                    <th headers="mixHeader" id="funcs">Func</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($mix as $item)
                                <tr>
                                    <td>{{$item->MixName}}</td>
                                    <td>{{$item->WineNumbers}}</td>
                                    <td>{{intval($item->Status)===0?'Off':'On'}}</td>
                                    <td>
                                        @if(intval($item->Status)===1)
                                            @if(intval($item->NoOffShelf)===1)Never Off
                                            @else
                                                {{$item->ActDate}}~{{$item->EndDate}}
                                            @endif
                                        @else Off
                                        @endif
                                    </td>
                                    <td>
                                        <p>
                                            <a href="/admin/products/mix/mod?mixid={{$item->MixId}}" class="btn-sm btn-primary">Edit</a>
                                        </p>
                                        <p>
                                            <a href="/admin/products/mix/status/{{$item->MixId}}" class="btn-sm btn-info">On/Off</a>
                                        </p>
                                        <p>
                                            <a href="/admin/products/mix/delete/{{$item->MixId}}" class="btn-sm btn-danger" onclick="return confirm('Are you sure to delete?')">Delete</a>
                                        </p>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if(count($mix)>0)
                        {{$mix->appends(request()->query())->links()}}
                        @endif                        
                    </div>                                
                </div>
            </div>
        </div>
    </div>
</div>
@endsection