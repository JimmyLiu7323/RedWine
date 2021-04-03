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
<div class="row">
    <div class="col-xs-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Banners list</div>
                <ul class="card-action">
                    <li>
                        <a href="/admin/banners/add" class="btn btn-primary">Add</a>
                    </li>
                </ul>
            </div>  
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th width="20%">Image</th>
                                    <th width="10%">Order</th>
                                    <th width="20%">Status</th>
                                    <th width="50%">Functions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($banners as $banner)
                                <tr>
                                    <td>
                                        <img src="{!! asset($banner->BannerImage) !!}" width=200 />
                                    </td>
                                    <td>{{$banner->BannerOrder}}</td>
                                    @if(trim($banner->OnText)!=="")
                                    <td>{{$banner->OnText}} / {{$banner->OffText}}</td>
                                    @else
                                    <td>{{$banner->OffText}}</td>
                                    @endif
                                    <td>
                                        <a href="/admin/banners/mod?id={{$banner->BannerId}}" class="btn-sm btn-primary">Edit</a>
                                        <a href="/admin/banners/delete?id={{$banner->BannerId}}" class="btn-sm btn-danger" onclick="return confirm('Sure to delete this banner?')">Delete</a>
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