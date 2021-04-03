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
.table tr th:nth-of-type(1){width:20%}
.table tr th:nth-of-type(2){width:40%}
.table tr th:nth-of-type(3){width:40%}
</style>
<div class="row">
    <div class="col-xs-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Countries</div>
                <ul class="card-action">
                    <li>
                        <a href="/admin/system/countries/maintain" class="btn btn-primary">Add country</a>
                    </li>
                </ul>
            </div>  
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th headers="countryHeader" id="countryShortId">CountryId</th>
                                    <th headers="countryHeader" id="countryName">Country</th>
                                    <th headers="countryHeader" id="functions"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($countries as $country)
                                <tr>
                                    <td>{{$country->CountryId}}</td>
                                    <td>{{$country->Country}}</td>
                                    <td>
                                        <a href="/admin/system/countries/maintain/{{$country->CountryId}}" class="btn-sm btn-primary"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>
                                        <a href="/admin/system/countries/delete/{{$country->CountryId}}" class="btn-sm btn-danger" onclick="return confirm('Sure about deleteing this item?')"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>
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