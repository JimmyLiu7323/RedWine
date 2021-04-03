@extends('admin.layouts.main')
@section('content')
<style type="text/css">
#list-table{table-layout:fixed}
#list-table tr th,#list-table tr td{padding:10px}
#mainTH th:nth-of-type(1){width:50%}
#mainTH th:nth-of-type(2){width:50%}
</style>
<div class="row">
    <div class="col-xs-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Categories of Gifts</div>
            </div>  
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-hover" width="100%" id="list-table">
                            <thead>
                                <tr id="mainTH">
                                    <th headers="wineHeader" id="WineCatg">Category</th>
                                    <th headers="wineHeader" id="Func">Func</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $category)
                                <tr>
                                    <td>{{ucfirst($category->Category)}}</td>
                                    <td>
                                        <a href="/admin/gifts/categories/edit?id={{$category->CategoryId}}" class="btn-primary btn-sm">Edit</a>
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