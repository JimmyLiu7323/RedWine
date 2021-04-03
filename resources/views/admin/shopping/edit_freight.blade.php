@extends('admin.layouts.main')
@section('content')
@if(\Session::has('maintain_message_fail'))
<script>
Swal.fire({
    type:'error',
    title:'Oops...',
    customClass:'swal-wide',
    text:"{!! \Session::get('maintain_message_fail') !!}",
})
</script>
@elseif(\Session::has('maintain_message_warning'))
<script>
Swal.fire({
    type:'warning',
    title:'警告',
    customClass:'swal-wide',
    text:"{!! \Session::get('maintain_message_warning') !!}",
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
                            <div class="form-group">
                                <label class="col-md-2 control-label">GST (in %)</label>
                                <div class="col-md-10">
                                    @if($errors->has('GST'))
                                    <small class="small_form_error">{{$errors->first('GST')}}</small>
                                    @endif
                                    <input type="text" name="GST" value="{{old('GST',$GST)}}" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Freight (deliver to home)</label>
                                <div class="col-md-10">
                                    @if($errors->has('Freight_home'))
                                    <small class="small_form_error">{{$errors->first('Freight_home')}}</small>
                                    @endif
                                    <input type="text" name="Freight_home" value="{{old('Freight_home',$Freight_home)}}" class="form-control" />    
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Freight (deliver to store)</label>
                                <div class="col-md-10" id="storeLine">
                                    @if($errors->has('Freight_store'))
                                    <small class="small_form_error">{{$errors->first('Freight_store')}}</small>
                                    @endif
                                    <button type="button" class="btn-md btn btn-info" onclick="addStore()">Add store</button>
                                    @if(old('Store')!="")
                                        @php
                                        $oldRegion=old('Region');
                                        $oldStoreAddr=old('StoreMap');
                                        $oldStoreFreight=old('StoreFreight');
                                        $oldStoreMapURL=old('MapURL');
                                        @endphp
                                        @foreach(old('Store') as $idx=>$store)
                                    <div class="row">
                                        <div class="col-md-3 col-xs-12">
                                            <input type='text' name='Region[]' class="form-control" placeholder="Enter city of store" value="{{$oldRegion[$idx]}}" />
                                        </div>
                                        <div class="col-md-3 col-xs-12">
                                            <input type='text' name='Store[]' class='form-control' placeholder='Enter store name' value="{{$store}}" />
                                        </div>
                                        <div class="col-md-3 col-xs-12">
                                            <input type='text' name='StoreMap[]' class='form-control' placeholder='Enter store address' value='{{$oldStoreAddr[$idx]}}' />
                                        </div>
                                        <div class="col-md-3 col-xs-12">
                                            <div class='input-group'>
                                                <span class='input-group-addon'>
                                                    <i class='fa fa-money' aria-hidden='true'></i>
                                                </span>
                                                <input type='text' class='form-control' placeholder='Enter freight' name='StoreFreight[]' value='{{$oldStoreFreight[$idx]}}' />
                                            </div>
                                        </div>
                                        <div class='col-xs-12' style='border-bottom:1px solid #333;margin-bottom:10px'>
                                            <input type='text' class='form-control' placeholder="Enter Google Map's url" name='MapURL[]' value='{{$oldStoreMapURL[$idx]}}' />
                                        </div>
                                    </div>                                        
                                        @endforeach
                                    @else
                                        @foreach($Freight_store as $idx=>$storeSet)
                                    <div class="row">
                                        <div class='col-md-3 col-xs-12'>
                                            <input type='text' name='Region[]' class="form-control" placeholder="Enter city of store" value="{{$storeSet[0]}}">
                                        </div>
                                        <div class="col-md-3 col-xs-12">
                                            <input type='text' name='Store[]' class='form-control' placeholder='Enter store name' value="{{$storeSet[1]}}" />
                                        </div>
                                        <div class="col-md-3 col-xs-12">
                                            <input type='text' name='StoreMap[]' class='form-control' placeholder='Enter store address' value='{{$storeSet[2]}}' />
                                        </div>
                                        <div class="col-md-3 col-xs-12">
                                            <div class='input-group'>
                                                <span class='input-group-addon'>
                                                    <i class='fa fa-money' aria-hidden='true'></i>
                                                </span>
                                                <input type='text' class='form-control' placeholder='Enter freight' name='StoreFreight[]' value='{{$storeSet[3]}}' />
                                            </div>
                                        </div>
                                        <div class='col-xs-12' style='border-bottom:1px solid #333;margin-bottom:10px'>
                                            <input type='text' class='form-control' placeholder="Enter Google Map's url" name='MapURL[]' value='{{$storeSet[4]}}' />
                                        </div>                                        
                                    </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="_token" id="csrf-token" value="{{csrf_token()}}" />
                    <div class="form-footer">
                        <div class="form-group">
                            <div class="col-md-12">
                                <button type='submit' class='btn btn-primary'>Save</button>
                                <a href="/admin/shopping/parameter/edit?key=freight" class="btn btn-default">Cancel</a>
                            </div>
                        </div>
                    </div>              
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
function addStore(){
    var newStore="";
    newStore+="<div class='row'>";

    newStore+="<div class='col-md-3 col-xs-12'>";
    newStore+="<input type='text' name='Region[]' class='form-control' placeholder='Enter city of store' />"
    newStore+="</div>";

    newStore+="<div class='col-md-3 col-xs-12'>";
    newStore+="<input type='text' name='Store[]' class='form-control' placeholder='Enter store name' />";
    newStore+="</div>";

    newStore+="<div class='col-md-3 col-xs-12'>";
    newStore+="<input type='text' name='StoreMap[]' class='form-control' placeholder='Enter store address' />";
    newStore+="</div>";

    newStore+="<div class='col-md-3 col-xs-12'>";
    newStore+="<div class='input-group'>";
    newStore+="<span class='input-group-addon'>";
    newStore+="<i class='fa fa-money' aria-hidden='true'></i>";
    newStore+="</span>";
    newStore+="<input type='text' class='form-control' placeholder='Enter freight' name='StoreFreight[]' />";
    newStore+="</div>";
    newStore+="</div>";

    newStore+="<div class='col-xs-12' style='border-bottom:1px solid #333;margin-bottom:10px'>";
    newStore+="<input type='text' class='form-control' placeholder='Enter Google Map\'s url' name='MapURL[]' />";
    newStore+="</div>";

    newStore+="</div>";
    $('#storeLine').append(newStore);
}
</script>
@endsection