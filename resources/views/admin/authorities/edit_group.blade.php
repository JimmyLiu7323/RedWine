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
@endif
<style type="text/css">
.form-group{margin-top:10px;margin-bottom:10px}
.checkbox h4{margin:0}
</style>
<div class="row">
    <div class="col-xs-12">
        <div class="card">
            <div class="card-body">
                <form class="form form-horizontal" id='product_form' method="POST" enctype="multipart/form-data">
                    <div class="section">
                        <div class="section-body">
                            <div class="form-group">
                                <label class="col-md-2 control-label">名稱</label>
                                <div class="col-md-10">
                                    @if($errors->has('Group'))
                                    <small class="small_form_error">{{$errors->first('Group')}}</small>
                                    @endif
                                    <input type="text" name="Group" class="form-control" value="{{old('Group',$groupInfo->GroupName)}}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">說明</label>
                                <div class="col-md-10">
                                    <textarea class="form-control" rows="2" maxlength="40" name="GroupDesc" style="resize:none">{!! old('GroupDesc',$groupInfo->GroupDesc) !!}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">可使用功能</label>
                                <div class="col-md-10">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="function-section form-group" style="margin-top:0">
                                                <div class="col-xs-12">
                                                    <?php
                                                    $funcs=array();
                                                    $smallFuncs=array(
                                                        'layers'=>array(),
                                                        'index_management'=>array(),
                                                        'volunteers'=>array(),
                                                        'events'=>array(),
                                                        'authorities'=>array()
                                                    );
                                                    if(old('funcs',json_decode($groupInfo->Funcs)))
                                                       $funcs=old('funcs',json_decode($groupInfo->Funcs));
                                                    if(old('smallFuncs',json_decode($groupInfo->ChildFuncs))){
                                                        foreach(old('smallFuncs',json_decode($groupInfo->ChildFuncs)) as $key=>$val){
                                                            $smallFuncs[$key]=$val;
                                                        }
                                                    }
                                                    ?>
                                                    <div class="checkbox">
                                                        <input type="checkbox" id="led-func" name="funcs[]" value="led" {{in_array('led',$funcs)?'checked=true':''}}>
                                                        <label for="led-func"><h4>LED圖檔合成</h4></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="function-section form-group">
                                                <div class="col-xs-12">
                                                    <div class="checkbox">
                                                        <input type="checkbox" id="layer" name="funcs[]" value="layers" {{in_array('layers',$funcs)?'checked=true':''}}>
                                                        <label for="layer"><h4>層級維護</h4></label>
                                                    </div>
                                                    <div class="small-function-section" style="overflow:hidden">
                                                        <div class="col-sm-4 checkbox">
                                                            <input type="checkbox" id="layer-func1" name="smallFuncs[layers][]" value="main" {{in_array('main',$smallFuncs['layers'])?'checked=true':''}}>
                                                            <label for="layer-func1">主層級維護</label>
                                                        </div>
                                                        <div class="col-sm-4 checkbox">
                                                            <input type="checkbox" id="layer-func2" name="smallFuncs[layers][]" value="submenus" {{in_array('submenus',$smallFuncs['layers'])?'checked=true':''}}>
                                                            <label for="layer-func2">子選單維護</label>
                                                        </div>
                                                        <div class="col-sm-4 checkbox">
                                                            <input type="checkbox" id="layer-func3" name="smallFuncs[layers][]" value="primary_catgs" {{in_array('primary_catgs',$smallFuncs['layers'])?'checked=true':''}}>
                                                            <label for="layer-func3">大分類維護</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="function-section form-group">
                                                <div class="col-xs-12">
                                                    <div class="checkbox">
                                                        <input type="checkbox" id="index-management" name="funcs[]" value="index_management" {{in_array('index_management',$funcs)?'checked=true':''}}>
                                                        <label for="index-management"><h4>首頁管理</h4></label>
                                                    </div>
                                                    <div class="small-function-section" style="overflow:hidden">
                                                        <div class="col-sm-4 checkbox">
                                                            <input type="checkbox" id="index-management-func1" name="smallFuncs[index_management][]" value="banners" {{in_array('banners',$smallFuncs['index_management'])?'checked=true':''}}>
                                                            <label for="index-management-func1">Banner管理</label>
                                                        </div>
                                                        <div class="col-sm-4 checkbox">
                                                            <input type="checkbox" id="index-management-func2" name="smallFuncs[index_management][]" value="popular_science" {{in_array('popular_science',$smallFuncs['index_management'])?'checked=true':''}}>
                                                            <label for="index-management-func2">科普新知</label>
                                                        </div>
                                                        <div class="col-sm-4 checkbox">
                                                            <input type="checkbox" id="index-management-func3" name="smallFuncs[index_management][]" value="popular_video" {{in_array('popular_video',$smallFuncs['index_management'])?'checked=true':''}}>
                                                            <label for="index-management-func3">熱門影音</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="function-section form-group">
                                                <div class="col-xs-12">
                                                    <div class="checkbox">
                                                        <input type="checkbox" id="digital-show" name="funcs[]" value="digital_show" {{in_array('digital_show',$funcs)?'checked=true':''}}>
                                                        <label for="digital-show"><h4>數位展場管理</h4></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="function-section form-group">
                                                <div class="col-xs-12">
                                                    <div class="checkbox">
                                                        <input type="checkbox" id="exhibition-voice" name="funcs[]" value="exhibition_voice" {{in_array('exhibition_voice',$funcs)?'checked=true':''}}>
                                                        <label for="exhibition-voice"><h4>展場語音導覽</h4></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="function-section form-group">
                                                <div class="col-xs-12">
                                                    <div class="checkbox">
                                                        <input type="checkbox" id="weather_tips" name="funcs[]" value="weather_tips" {{in_array('weather_tips',$funcs)?'checked=true':''}}>
                                                        <label for="weather_tips"><h4>氣象小叮嚀</h4></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="function-section form-group">
                                                <div class="col-xs-12">
                                                    <div class="checkbox">
                                                        <input type="checkbox" id="volunteers" name="funcs[]" value="volunteers" {{in_array('volunteers',$funcs)?'checked=true':''}}>
                                                        <label for="volunteers"><h4>志工管理</h4></label>
                                                    </div>
                                                    <div class="small-function-section" style="overflow:hidden">
                                                        <div class="col-sm-4 checkbox">
                                                            <input type="checkbox" id="volunteer_func1" name="smallFuncs[volunteers][]" value="mg_volunteer_manage" {{in_array('mg_volunteer_manage',$smallFuncs['volunteers'])?'checked=true':''}}>
                                                            <label for="volunteer_func1">志工資料管理</label>
                                                        </div>
                                                        <div class="col-sm-4 checkbox">
                                                            <input type="checkbox" id="volunteer_func2" name="smallFuncs[volunteers][]" value="mg_volunteer_scheduled" {{in_array('mg_volunteer_scheduled',$smallFuncs['volunteers'])?'checked=true':''}}>
                                                            <label for="volunteer_func2">預約團體志工登錄</label>
                                                        </div>
                                                        <div class="col-sm-4 checkbox">
                                                            <input type="checkbox" id="volunteer_func3" name="smallFuncs[volunteers][]" value="mg_volunteer_timing" {{in_array('mg_volunteer_timing',$smallFuncs['volunteers'])?'checked=true':''}}>
                                                            <label for="volunteer_func3">定時解說志工登錄</label>
                                                        </div>
                                                        <div class="col-sm-4 checkbox">
                                                            <input type="checkbox" id="volunteer_func4" name="smallFuncs[volunteers][]" value="mg_volunteer_timing" {{in_array('mg_volunteer_timing',$smallFuncs['volunteers'])?'checked=true':''}}>
                                                            <label for="volunteer_func4">環境教育志工登錄</label>
                                                        </div>
                                                        <div class="col-sm-4 checkbox">
                                                            <input type="checkbox" id="volunteer_func5" name="smallFuncs[volunteers][]" value="mg_volunteer_log_hours" {{in_array('mg_volunteer_log_hours',$smallFuncs['volunteers'])?'checked=true':''}}>
                                                            <label for="volunteer_func5">執勤時數登錄</label>
                                                        </div>
                                                        <div class="col-sm-4 checkbox">
                                                            <input type="checkbox" id="volunteer_func6" name="smallFuncs[volunteers][]" value="mg_volunteer_action_hours" {{in_array('mg_volunteer_action_hours',$smallFuncs['volunteers'])?'checked=true':''}}>
                                                            <label for="volunteer_func6">活動支援時數登錄</label>
                                                        </div>
                                                        <div class="col-sm-4 checkbox">
                                                            <input type="checkbox" id="volunteer_func7" name="smallFuncs[volunteers][]" value="mg_volunteer_hours_sum_person" {{in_array('mg_volunteer_hours_sum_person',$smallFuncs['volunteers'])?'checked=true':''}}>
                                                            <label for="volunteer_func7">志工服務時數統計表</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="function-section form-group">
                                                <div class="col-xs-12">
                                                    <div class="checkbox">
                                                        <input type="checkbox" id="events" name="funcs[]" value="events" {{in_array('events',$funcs)?'checked=true':''}}>
                                                        <label for="events"><h4>活動專區</h4></label>
                                                    </div>
                                                    <div class="small-function-section" style="overflow:hidden">
                                                        <div class="col-sm-4 checkbox">
                                                            <input type="checkbox" id="events_func1" name="smallFuncs[events][]" value="category" {{in_array('category',$smallFuncs['events'])?'checked=true':''}}>
                                                            <label for="events_func1">活動專區類別管理</label>
                                                        </div>
                                                        <div class="col-sm-4 checkbox">
                                                            <input type="checkbox" id="events_func2" name="smallFuncs[events][]" value="events" {{in_array('events',$smallFuncs['events'])?'checked=true':''}}>
                                                            <label for="events_func2">活動專區類別管理</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="function-section form-group">
                                                <div class="col-xs-12">
                                                    <div class="checkbox">
                                                        <input type="checkbox" id="fileApply" name="funcs[]" value="fileApply" {{in_array('fileApply',$funcs)?'checked=true':''}}>
                                                        <label for="fileApply"><h4>檔案庫</h4></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="function-section form-group">
                                                <div class="col-xs-12">
                                                    <div class="checkbox">
                                                        <input type="checkbox" id="sys_setting" name="funcs[]" value="authorities_accounts_departments_groups" {{in_array('authorities_accounts_departments_groups',$funcs)?'checked=true':''}}>
                                                        <label for="sys_setting"><h4>系統管理</h4></label>
                                                    </div>
                                                    <div class="small-function-section" style="overflow:hidden">
                                                        <div class="col-sm-4 checkbox">
                                                            <input type="checkbox" id="authorities_func1" name="smallFuncs[authorities][]" value="self" {{in_array('self',$smallFuncs['authorities'])?'checked=true':''}}>
                                                            <label for="authorities_func1">個人資料管理</label>
                                                        </div>
                                                        <div class="col-sm-4 checkbox">
                                                            <input type="checkbox" id="authorities_func2" name="smallFuncs[authorities][]" value="accounts" {{in_array('accounts',$smallFuncs['authorities'])?'checked=true':''}}>
                                                            <label for="authorities_func2">使用者管理</label>
                                                        </div>
                                                        <div class="col-sm-4 checkbox">
                                                            <input type="checkbox" id="authorities_func3" name="smallFuncs[authorities][]" value="groups" {{in_array('groups',$smallFuncs['authorities'])?'checked=true':''}}>
                                                            <label for="authorities_func3">群組管理</label>
                                                        </div>
                                                        <div class="col-sm-4 checkbox">
                                                            <input type="checkbox" id="authorities_func4" name="smallFuncs[authorities][]" value="departments" {{in_array('departments',$smallFuncs['authorities'])?'checked=true':''}}>
                                                            <label for="authorities_func4">單位管理</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>                                             
                                        </div>
                                    </div>                                   
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="_token" id="csrf-token" value="{{csrf_token()}}" />
                    <div class="form-footer">
                        <div class="form-group">
                            <div class="col-md-12">
                                <button type='submit' class='btn btn-primary'>儲存</button>
                                <a href="/admin/groups" class="btn btn-default">取消</a>
                            </div>
                        </div>
                    </div>              
                </form>
            </div>
        </div>
    </div>
</div>
@endsection