@extends('admin.layouts.main')
@section('content')
<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
        <a class="card card-banner card-green-light">
            <div class="card-body">
                <i class="icon fa fa-glass fa-4x"></i>
                <div class="content">
                    <div class="title">On Shelves</div>
                    <div class="value"><span class="sign"></span>{{$onShelfWines}}</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
        <a class="card card-banner card-yellow-light">
            <div class="card-body">
                <i class="icon fa fa-object-group fa-4x"></i>
                <div class="content">
                    <div class="title">Sale Topic</div>
                    <div class="value"><span class="sign"></span>{{$onShelfSalexMix}}</div>
                </div>
            </div>
        </a>
    </div>    
</div>
@endsection