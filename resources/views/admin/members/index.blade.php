@extends('admin.layouts.main')
@section('content')
<style type="text/css">
#list-table tr td{word-break:break-all}
</style>
<div class="row">
    <div class="col-xs-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Members</div>
            </div>  
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <form class="form form-horizontal" method="GET">
                            <div class="row">
                                <div class="col-xs-6">
                                    <input type="text" name="q" class="form-control" placeholder="Enter the id or name of member" value="{{$searchQ}}" />
                                </div>
                                <div class="col-xs-3">
                                    <select name="type" class="form-control" style="height:42px">
                                        <option value="id" {{$searchType==='id'?'selected=true':''}}>Member ID</option>
                                        <option value="name" {{$searchType==='name'?'selected=true':''}}>Name</option>
                                    </select>
                                </div>
                                <div class="col-xs-3"><button type="submit" class="btn btn-block btn-md btn-primary">Search</button></div>
                            </div>
                        </form>
                        <table class="table table-striped table-bordered table-hover" width="100%" id="list-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Country</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($members as $member)
                                <tr>
                                    <td>{{$member->MemberId}}</td>
                                    <td>@if(isset(\Config::get('app.countries')[$member->Country])){{\Config::get('app.countries')[$member->Country]}}@endif</td>
                                    <td>{{$member->FirstName}} {{$member->MiddleName}} {{$member->LastName}} </td>
                                    <td>{{$member->Email}}</td>
                                    <td>
                                        <a href="/admin/members/delete?id={{$member->MemberId}}" onclick="return confirm('Sure to delete the member?')" class="btn-sm btn-danger">Delete</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if(count($members)>0)
                        {{$members->appends(request()->query())->links()}}
                        @endif                        
                    </div>                                
                </div>
            </div>
        </div>
    </div>
</div>
@endsection