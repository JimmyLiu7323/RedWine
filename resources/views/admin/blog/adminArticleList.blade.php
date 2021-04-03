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
#list-table tr td,#list-table tr th{padding:10px}
</style>
<div class="row">
    <div class="col-xs-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Articles</div>
                <ul class="card-action">
                    <li>
                        <a href="/admin/blog/articles/maintain" class="btn btn-primary">Add</a>
                    </li>
                </ul>
            </div>  
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-striped table-bordered table-hover" id="list-table">
                            <thead>
                                <tr>
                                    <th width="25%">Title</th>
                                    <th width="20%">Category</th>
                                    <th width="25%">Enable Date</th>
                                    <th width="20%">Status</th>
                                    <th width="10%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $currentDate=date('Y-m-d');
                                @endphp
                                @foreach($articles as $article)
                                    @php
                                    $timeCheck=false;
                                    if($article->OnDate <= $currentDate && $article->OffDate > $currentDate){
                                        $timeCheck=true;
                                    }
                                    @endphp
                                <tr>
                                    <td>{{$article->Title}}</td>
                                    <td>{{$article->Category?$article->Category:'Not Specify'}}</td>
                                    <td>{{$article->OnDate}}~{{$article->OffDate}}</td>
                                    <td>{{$article->Status && $timeCheck ? 'Enable':'Disable'}}</td>
                                    <td>
                                        <p>
                                            <a href="/admin/blog/articles/maintain?id={{$article->ArticleId}}" class="btn-primary btn-sm">Edit</a>
                                        </p>
                                        <p>
                                            <a href="/admin/blog/articles/delete?id={{$article->ArticleId}}" class="btn-sm btn-danger" onclick="return confirm('Sure about delete?')">Delete</a>
                                        </p>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if(count($articles)>0)
                        {{$articles->appends(request()->query())->links()}}
                        @endif                        
                    </div>                                
                </div>
            </div>
        </div>
    </div>
</div>
@endsection