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
                <div class="card-title">Product List</div>
                <ul class="card-action">
                    <li>
                        <a href="{!! asset('datas/example-excel.xlsx') !!}" class="btn btn-warning">Sample Excel</a>
                        <a javascript:voide(0) data-toggle="modal" data-target="#ImportModal" class="btn btn-info">Import Product</a>
                        <a href="/admin/products/add" class="btn btn-primary">Add Product</a>
                    </li>
                </ul>
            </div>  
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <form class="form form-horizontal" method="GET">
                            <div class="row">
                                <div class="col-xs-6">
                                    <input type="text" name="q" class="form-control" placeholder="Enter the name of product" value="{{$searchQ}}" />
                                </div>
                                <div class="col-xs-3">
                                    <select name="type" class="form-control" style="height:42px">
                                        <option value="name" {{$searchType==='name'?'selected=true':''}}>Name</option>
                                    </select>
                                </div>
                                <div class="col-xs-3"><button type="submit" class="btn btn-block btn-md btn-primary">Search</button></div>
                            </div>
                        </form>
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
                                @php
                                $currentDateTime = date('Y-m-d');
                                @endphp
                                @foreach($wines as $wine)
                                    @php
                                    $timeCheck = false;
                                    if($wine->NoOffShelf){
                                        $timeCheck = true;
                                    }
                                    else{
                                        if($wine->ActDate <= $currentDateTime && $wine->EndDate > $currentDateTime){
                                            $timeCheck = true;
                                        }
                                    }
                                    @endphp
                                <tr>
                                    <td align="center">
                                        @if(intval($wine->WineCatg)===1)Red
                                        @elseif(intval($wine->WineCatg)===2)White
                                        @elseif(intval($wine->WineCatg)===3)Sparkling
                                        @endif
                                    </td>
                                    <td align="center">{{$wine->Name}}</td>
                                    <td align="center">
                                        @if($wine->S_price){{$wine->S_price}}
                                        @else{{$wine->Price}}
                                        @endif
                                    </td>
                                    <td align="center">
                                        @if(intval($wine->NoOffShelf)===1)Never Off
                                        @else{{$wine->ActDate}}~{{$wine->EndDate}}
                                        @endif
                                    </td>
                                    <td align="center">{{intval($wine->Status)===0 || !$timeCheck ?'Disable':'Enable'}}</td>
                                    <td>
                                        <a href="/admin/products/mod/{{$wine->WineId}}" class="btn-sm btn-primary">Edit</a>
                                         <a href="/admin/products/delete/{{$wine->WineId}}" class="btn-sm btn-danger" onclick="return confirm('Are you sure to delete?')">Delete</a> 
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if(count($wines)>0)
                        {{$wines->appends(request()->query())->links()}}
                        @endif
                    </div>                                
                </div>
            </div>
        </div>
    </div>
</div>

<div id="ImportModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="/admin/products/import" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">批次匯入商品</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-sm-2 control-label">檔案</label>
                        <div class="col-sm-10">
                            <input type="file" name="ImportFile" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">關閉</button>
                    <button type="submit" class="btn btn-primary">匯入</button>
                </div>
                <input type="hidden" value="{{csrf_token()}}" name="_token" />
            </div>
        </form>
    </div>
</div>
@endsection