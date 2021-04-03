@extends('admin.layouts.main')
@section('content')
@if(\Session::has('maintain_message_warning'))
<script>
Swal.fire({
    type:'warning',
    title:'Warning',
    customClass:'swal-wide',
    text:"{!! \Session::get('maintain_message_warning') !!}",
})
</script>
@elseif(\Session::has('maintain_message_error'))
<script>
Swal.fire({
    type:'error',
    title:'Oops...',
    customClass:'swal-wide',
    text:"{!! \Session::get('maintain_message_error') !!}",
})
</script>
@endif
<style type="text/css">
.form-group{margin-top:10px;margin-bottom:10px}
</style>
<div class="row">
    <div class="col-xs-12">
        <div class="card">
            <div class="card-body">
                <form class="form form-horizontal" id='product_form' method="POST" enctype="multipart/form-data">
                    <div class="section">
                        <div class="section-body">
                            <div class="row">
                                <div class="col-xs-3">
                                    <button type="button" onclick="addWine()" class="btn btn-sm btn-block btn-warning">Add Wine</button>
                                </div>
                                <div class="col-xs-3">
                                    <button type="button" onclick="addTopic()" class="btn btn-block btn-sm btn-danger">Add Topic</button>
                                </div>
                            </div>
                            <div id="productsInOption">
                                <div class="row" style="margin-top:10px">
                                    <div class="col-xs-12"><h4>Products</h4></div>
                                </div>
                                @if($errors->has('Wines'))
                                <small class="small_form_error">{{$errors->first('Wines')}}</small>
                                @endif
                                @if($errors->has('Topics'))
                                <small class="small_form_error">{{$errors->first('Topics')}}</small>
                                @endif

                                @php
                                $oldWines=old('Wines',$option_sets_Wine);
                                $oldTopics=old('Topics',$option_sets_Topic);
                                @endphp
                                @foreach($oldWines as $wine)
                                <div class='form-group'>
                                    <div class='col-xs-2'>
                                        <label class='control-label'>Wine: </label>
                                    </div>
                                    <div class='col-xs-10'>
                                        <select name='Wines[]' class='form-control'>
                                            @foreach($wines as $wineChoice)
                                            <option value="{{$wineChoice['WineId']}}" {{$wineChoice['WineId']==$wine?'selected=true':''}}>{{$wineChoice['Name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @endforeach
                                @foreach($oldTopics as $topic)
                                <div class='form-group'>
                                    <div class='col-xs-2'>
                                        <label class='control-label'>Topic: </label>
                                    </div>
                                    <div class='col-xs-10'>
                                        <select name='Topics[]' class='form-control'>
                                            @foreach($topics as $topicChoice)
                                            <option value="{{$topicChoice['TopicId']}}" {{$topic['MixId']==$topic?'selected=true':''}}>{{$topicChoice['MixName']}}</option>
                                            @endforeach
                                        </select>
                                    </div>                                        
                                </div>                                
                                @endforeach
                            </div>
                            <div class="form-group">
                                <label class="col-xs-2 control-label">Option</label>
                                <div class="col-xs-10">
                                    @if($errors->has('Option'))
                                    <small class="small_form_error">{{$errors->first('Option')}}</small>
                                    @endif                                    
                                    <select name="Option" class="form-control" onchange="changeOption(this)">
                                        <!-- <option value=""></option> -->
                                        <!-- <option value="combineSelling">Combine selling</option> -->
                                        <option value="discount" {{old('Option',$basicOption->OptionRule)=='discount'?'selected=true':''}}>Discount with purchase of a target amount</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group" id="purchaseAmount">
                                <label class="col-xs-2 control-label">Purchase Amount</label>
                                <div class="col-xs-10">
                                    @if($errors->has('PurchaseAmount'))
                                    <small class="small_form_error">{{$errors->first('PurchaseAmount')}}</small>
                                    @endif
                                    <input type="number" name="PurchaseAmount" class="form-control" value="{{old('PurchaseAmount',$basicOption->PurchaseAmount)}}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-xs-2 control-label">Price</label>
                                <div class="col-xs-10">
                                    @if($errors->has('Price'))
                                    <small class="small_form_error">{{$errors->first('Price')}}</small>
                                    @endif
                                    <div class="input-group">
                                        <span class="input-group-addon">$</span>
                                        <input type="number" class="form-control" name="Price" value="{{old('Price',$basicOption->Price)}}" min="0.1" step="0.1"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="_token" id="csrf-token" value="{{csrf_token()}}" />
                    <div class="form-footer">
                        <div class="form-group">
                            <div class="col-md-12">
                                <button type='submit' class='btn btn-primary'>Save</button>
                                <a href="/admin/products/options" class="btn btn-default">Cancel</a>
                            </div>
                        </div>
                    </div>              
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
function addWine(){
    let newProduct='';
    $.ajax({
        'url':'/admin/products/options/ajaxProduct',
        'method':'GET',
        'data':{},
        success:function(wineRes){
            if(wineRes.length>0){
                newProduct="<div class='form-group'><div class='col-xs-2'><label class='control-label'>Wine: </label></div><div class='col-xs-10'><select name='Wines[]' class='form-control'>";
                for(var i=0;i<wineRes.length;i++){
                    newProduct+="<option value='"+wineRes[i]['WineId']+"'>"+wineRes[i]['Name']+"</option>";
                }
                newProduct+="</select></div></div>";
                $('#productsInOption').append(newProduct);
            }
            else{
                alert('The system did not have any enable wines.');
            }
        },
        error:function(err){
            if(parseInt((err.status)===401)){
                alert('Login first please');
                window.location='/admin';
            }
            else{
                alert('Oops...Something error');
            }
        }
    })
}

function addTopic(){
    let newTopic='';
    $.ajax({
        'url':'/admin/products/options/ajaxTopic',
        'method':'GET',
        'data':{},
        success:function(topicRes){
            if(topicRes.length>0){
                newTopic="<div class='form-group'><div class='col-xs-2'><label class='control-label'>Topic: </label></div><div class='col-xs-10'><select name='Topics[]' class='form-control'>";
                for(var i=0;i<topicRes.length;i++){
                    newTopic+="<option value='"+topicRes[i]['WineId']+"'>"+topicRes[i]['Name']+"</option>";
                }
                newTopic+="</select></div></div>";
                $('#productsInOption').append(newTopic);
            }
            else{
                alert('The system did not have any enable topics.');
            }
        },
        error:function(err){
            if(parseInt((err.status)===401)){
                alert('Login first please');
                window.location='/admin';
            }
            else{
                alert('Oops...Something error');
            }
        }
    })
}

function changeOption(elem){
    if($(elem).val()=='discount'){
        $('#purchaseAmount').show();
    }
    else{
        $('#purchaseAmount').hide();
    }
}
</script>
@endsection