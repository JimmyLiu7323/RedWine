@extends('admin.layouts.main')
@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Banners list</div>
                <ul class="card-action">
                    <li>
                        <a href="/admin/gifts/banners/add" class="btn btn-primary">Add</a>
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
                                    <th width="50%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($banners as $banner)
                                <tr>
                                    <td>
                                        <img src="{!! asset($banner->BannerImage) !!}" width="200px" />
                                    </td>
                                    <td>{{$banner->BannerOrder}}</td>
                                    <td>
                                        @if(intval($banner->BannerOnStatus)===1 && date('Y-m-d')>=$banner->BannerOnDate && date('Y-m-d')<$banner->BannerOffDate)
                                        Enable
                                        @else
                                        Disable
                                        @endif
                                    </td>
                                    <td>
                                        <a href="/admin/gifts/banners/edit?id={{$banner->BannerId}}" class="btn-sm btn-primary">Edit</a>
                                        <a href="/admin/gifts/banners/delete?id={{$banner->BannerId}}" onclick="return confirm('Sure to delete this banner?')" class="btn-sm btn-danger">Delete</a>
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