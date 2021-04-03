@extends('admin.layouts.main')
@section('content')
<style type="text/css">
#list-table{table-layout:fixed}
#list-table tr th,#list-table tr td{padding:10px}
#mainTH th:nth-of-type(1){width:10%}
#mainTH th:nth-of-type(2){width:20%}
#mainTH th:nth-of-type(3){width:10%}
#mainTH th:nth-of-type(4){width:20%}
#mainTH th:nth-of-type(5){width:10%}
#mainTH th:nth-of-type(6){width:20%}
#mainTH th:nth-of-type(7){width:10%}
</style>
<div class="row">
    <div class="col-xs-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Orders</div>
            </div>  
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <form class="form form-horizontal" method="GET">
                            <div class="row">
                                <div class="col-xs-9">
                                    <input type="text" name="order-id" class="form-control" placeholder="Enter the id of order" value="{{$searchId}}" />
                                </div>
                                <div class="col-xs-3"><button type="submit" class="btn btn-block btn-md btn-primary">Search</button></div>
                            </div>
                        </form>
                        <table class="table table-striped table-bordered table-hover" width="100%" id="list-table">
                            <thead>
                                <tr id="mainTH">
                                    <th>Order ID</th>
                                    <th>Pick way</th>
                                    <th>Receiver</th>
                                    <th>Pay method</th>
                                    <th>Total Amount</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr>
                                    <td>{{$order->OrderId}}</td>
                                    <td>{{$order->Type==='HOME'?'Send to home':'Pick at store'}}</td>
                                    <td>{{$order->FirstName}} {{$order->MiddleName}} {{$order->LastName}}</td>
                                    <td>
                                        @if($order->Paymethod==='Card'){{'Credit card'}}
                                        @endif
                                    </td>
                                    <td>$ {{number_format($order->Total,2)}}</td>
                                    <td>
                                        @if(intval($order->Status)===-1){{'Unpaid'}}
                                        @elseif(intval($order->Status)===0){{'Paid'}}
                                        @elseif(intval($order->Status)===1){{'Shipped'}}
                                        @endif
                                    </td>
                                    <td>
                                        <p>
                                            <a href="/admin/shopping/orders/edit?id={{$order->OrderId}}" class="btn-sm btn-primary">Edit</a>
                                        </p>
                                        <p>
                                            <a href="/admin/shopping/orders/delete?id={{$order->OrderId}}" class="btn-sm btn-danger" onclick="return confirm('Sure to delete this order?')">Delete</a>
                                        </p>
                                        @if(intval($order->Status===0))
                                        <p>
                                            <a href="/admin/shopping/orders/shipping?id={{$order->OrderId}}&page={{$page}}" class="btn-sm btn-info">Ship</a>
                                        </p>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if(count($orders)>0)
                        {{$orders->appends(request()->query())->links()}}
                        @endif
                    </div>                                
                </div>
            </div>
        </div>
    </div>
</div>
@endsection