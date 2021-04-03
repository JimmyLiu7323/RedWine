<?php
$AdminId=Session::get('AdminId');
$AdminAuthority=DB::table('admins')->where('AdminId',$AdminId)->select('AdminAuthority')->take(1)->first();
$AdminAuthority=$AdminAuthority->AdminAuthority;
$GroupInfo=DB::table('groups')->where('GroupId',$AdminAuthority)->take(1)->first();
if($GroupInfo){
	$Funcs=json_decode($GroupInfo->Funcs);
	$ChildFuncs=json_decode($GroupInfo->ChildFuncs);
?>
<div class="sidebar-menu">
    <ul class="sidebar-nav">
    	@if(in_array('led',$Funcs))
        <li {{($currentPage=='dashboard')?'class=active':''}}>
            <a href="/admin">
                <div class="icon">
                    <i class="fa fa-lightbulb-o" aria-hidden="true"></i>
                </div>
                <div class="title">LED圖檔合成</div>
            </a>
        </li>
        @endif
        @if(in_array('layers',$Funcs))
        <li class="dropdown {{($currentPage=='layers')?'active':''}}">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <div class="icon">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                </div>
                <div class="title">層級維護</div>
            </a>
            <div class="dropdown-menu">
                <ul>
                	@if(in_array('main',$ChildFuncs->layers))
                    <li>
                        <a href="/admin/layers/main">主層級維護</a>
                    </li>
                    @endif
                    @if(in_array('submenus',$ChildFuncs->layers))
                    <li>
                        <a href="/admin/layers/submenus">子選單列表</a>
                    </li>
                    @endif
                    @if(in_array('primary_catgs',$ChildFuncs->layers))
                    <li>
                        <a href="/admin/layers/primary_catgs">大分類列表</a>
                    </li> 
                    @endif                   
                </ul>
            </div>            
        </li>
        @endif
        @if(in_array('index_management',$Funcs))
        <li class="dropdown {{($currentPage=='banners'||$currentPage=='index_management')?'active':''}}">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <div class="icon">
                    <i class="fa fa-home" aria-hidden="true"></i>
                </div>
                <div class="title">首頁管理</div>
            </a>
            <div class="dropdown-menu">
                <ul>
                	@if(in_array('banners',$ChildFuncs->index_management))
                    <li>
                        <a href="/admin/banners">Banner管理</a>
                    </li>
                    @endif
                    @if(in_array('popular_science',$ChildFuncs->index_management))
                    <li>
                        <a href="/admin/index_management/popular_science">科普新知</a>
                    </li>
                    @endif
                    @if(in_array('popular_video',$ChildFuncs->index_management))
                    <li>
                        <a href="/admin/index_management/popular_video">熱門影音</a>
                    </li>
                    @endif                
                </ul>
            </div>          
        </li>
        @endif
        @if(in_array('digital_show',$Funcs))
        <li {{($currentPage=='digital_show')?'class=active':''}}>
            <a href="/admin/digital_show">
                <div class="icon">
                    <i class="fa fa-globe" aria-hidden="true"></i>
                </div>
                <div class="title">數位展場管理</div>
            </a>            
        </li>
        @endif
        @if(in_array('exhibition_voice',$Funcs))
        <li {{($currentPage=='exhibition_voice')?'class=active':''}}>
            <a href="/admin/exhibition_voice">
                <div class="icon">
                    <i class="fa fa-microphone" aria-hidden="true"></i>
                </div>
                <div class="title">展場語音導覽</div>
            </a>            
        </li>
        @endif
        @if(in_array('weather_tips',$Funcs))
        <li {{($currentPage=='weather_tips')?'class=active':''}}>
            <a href="/admin/weather_tips">
                <div class="icon">
                    <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                </div>
                <div class="title">氣象小叮嚀</div>
            </a>            
        </li>
        @endif
        @if(in_array('volunteers',$Funcs))
        <li class="dropdown {{($currentPage=='volunteers')?'active':''}}">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <div class="icon">
                    <i class="fa fa-hand-paper-o" aria-hidden="true"></i>
                </div>
                <div class="title">志工管理</div>
            </a>
            <div class="dropdown-menu">
                <ul>
                	@if(in_array('mg_volunteer_manage',$ChildFuncs->volunteers))
                    <li>
                        <a href="/admin/volunteers/mg_volunteer_manage">志工資料管理</a>
                    </li>
                    @endif
                    @if(in_array('mg_volunteer_scheduled',$ChildFuncs->volunteers))
                    <li>
                        <a href="/admin/volunteers/mg_volunteer_scheduled">預約團體志工登錄</a>
                    </li>
                    @endif
                    @if(in_array('mg_volunteer_timing',$ChildFuncs->volunteers))
                    <li>
                        <a href="/admin/volunteers/mg_volunteer_timing">定時解說志工登錄</a>
                    </li>
                    @endif
                    @if(in_array('mg_volunteer_teaching',$ChildFuncs->volunteers))
                    <li>
                        <a href="/admin/volunteers/mg_volunteer_teaching">環境教育志工登錄</a>
                    </li>
                    @endif
                    @if(in_array('mg_volunteer_log_hours',$ChildFuncs->volunteers))
                    <li>
                        <a href="/admin/volunteers/mg_volunteer_log_hours">執勤時數登錄</a>
                    </li>
                    @endif
                    @if(in_array('mg_volunteer_action_hours',$ChildFuncs->volunteers))
                    <li>
                        <a href="/admin/volunteers/mg_volunteer_action_hours">活動支援時數登錄</a>
                    </li>
                    @endif
                    @if(in_array('mg_volunteer_hours_sum_person',$ChildFuncs->volunteers))
                    <li>
                        <a href="/admin/volunteers/mg_volunteer_hours_sum_person">志工服務時數統計</a>
                    </li>
                    @endif
                </ul>
            </div>          
        </li>
        @endif
        @if(in_array('events',$Funcs))
        <li class="dropdown {{($currentPage=='events')?'active':''}}">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <div class="icon">
                    <i class="fa fa-calendar" aria-hidden="true"></i>
                </div>
                <div class="title">活動專區</div>
            </a>
            <div class="dropdown-menu">
                <ul>
                	@if(in_array('category',$ChildFuncs->events))
                    <li>
                        <a href="/admin/events/category">活動專區類別管理</a>
                    </li>
                    @endif
                    @if(in_array('events',$ChildFuncs->events))
                    <li>
                        <a href="/admin/events">活動專區</a>
                    </li>
                    @endif
                </ul>
            </div>          
        </li>
        @endif
        @if(in_array('fileApply',$Funcs))
        <li {{($currentPage=='fileApply')?'class=active':''}}>
            <a href="/admin/fileApply">
                <div class="icon">
                    <i class="fa fa-file" aria-hidden="true"></i>
                </div>
                <div class="title">檔案庫</div>
            </a>            
        </li>
        @endif
        @if(in_array('authorities_accounts_departments_groups',$Funcs))
        <li class="dropdown {{($currentPage=='authorities'||$currentPage=='sys_setting')?'active':''}}">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <div class="icon">
                    <i class="fa fa-cogs" aria-hidden="true"></i>
                </div>
                <div class="title">系統管理</div>
            </a>
            <div class="dropdown-menu">
                <ul>
                	@if(in_array('self',$ChildFuncs->authorities))
                    <li>
                        <a href="/admin/authorities/self">個人資料管理</a>
                    </li>
                    @endif
                    @if(in_array('accounts',$ChildFuncs->authorities))
                    <li>
                        <a href="/admin/accounts">使用者管理</a>
                    </li>
                    @endif
                    @if(in_array('groups',$ChildFuncs->authorities))
                    <li>
                        <a href="/admin/groups">群組管理</a>
                    </li>
                    @endif
                    @if(in_array('departments',$ChildFuncs->authorities))
                    <li>
                        <a href="/admin/departments">單位管理</a>
                    </li>
                    @endif
                </ul>
            </div>          
        </li>
        @endif
        <li {{($currentPage=='materials')?'class=active':''}}>
            <a href="javascript:void(0)" class="popup_selector">
                <div class="icon">
                    <i class="fa fa-image" aria-hidden="true"></i>
                </div>
                <div class="title">素材管理</div>
            </a>            
        </li>
    </ul>
</div>
<?php
}
?>