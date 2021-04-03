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
                                <label class="col-md-2 control-label">單位</label>
                                <div class="col-md-10">
                                    @if($errors->has('Department'))
                                    <small class="small_form_error">{{$errors->first('Department')}}</small>
                                    @endif
                                    <select name="Department" class="form-control">
                                        <option value="0" {{old('Department')==''?'selected=true':''}}>無</option>
                                        @foreach($departments as $department)
                                        <option value="{{$department->DepartmentId}}" {{old('Department')==$department->DepartmentId?'selected=true':''}}>{{$department->Department}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">姓名</label>
                                <div class="col-md-10">
                                    @if($errors->has('AdminName'))
                                    <small class="small_form_error">{{$errors->first('AdminName')}}</small>
                                    @endif                                    
                                    <input type="text" class="form-control" name="AdminName" value="{{old('AdminName')}}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">帳號</label>
                                <div class="col-md-10">
                                    @if($errors->has('AdminAccount'))
                                    <small class="small_form_error">{{$errors->first('AdminAccount')}}</small>
                                    @endif                                    
                                    <input type="text" class="form-control" name="AdminAccount" value="{{old('AdminAccount')}}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">密碼</label>
                                <div class="col-md-10">
                                    @if($errors->has('AdminPassword'))
                                    <small class="small_form_error">{{$errors->first('AdminPassword')}}</small>
                                    @endif                                    
                                    <input type="password" class="form-control" name="AdminPassword" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Mail</label>
                                <div class="col-md-10">
                                    @if($errors->has('email'))
                                    <small class="small_form_error">{{$errors->first('email')}}</small>
                                    @endif                                    
                                    <input type="text" class="form-control" name="email" value="{{old('email')}}"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">電話</label>
                                <div class="col-md-10">
                                    @if($errors->has('phone'))
                                    <small class="small_form_error">{{$errors->first('phone')}}</small>
                                    @endif                                    
                                    <input type="text" class="form-control" name="phone" value="{{old('phone')}}" />
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label class="col-md-2 control-label">分機</label>
                                <div class="col-md-10">
                                    @if($errors->has('phone-ext'))
                                    <small class="small_form_error">{{$errors->first('phone-ext')}}</small>
                                    @endif                                    
                                    <input type="number" class="form-control" name="phone-ext" value={{old('phone-ext')}} />
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label class="col-md-2 control-label">啟用</label>
                                <div class="col-md-10">
                                    @if($errors->has('status'))
                                    <small class="small_form_error">{{$errors->first('status')}}</small>
                                    @endif
                                    <select name="status" class="form-control">
                                        <option value="0" {{intval(old('status'))===0?'selected=true':''}}>否</option>
                                        <option value="1" {{intval(old('status'))===1?'selected=true':''}}>是</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="_token" id="csrf-token" value="{{csrf_token()}}" />
                    <div class="form-footer">
                        <div class="form-group">
                            <div class="col-md-12">
                                <button type='submit' class='btn btn-primary'>儲存</button>
                                <a href="/admin/accounts" class="btn btn-default">取消</a>
                            </div>
                        </div>
                    </div>              
                </form>
            </div>
        </div>
    </div>
</div>
@endsection