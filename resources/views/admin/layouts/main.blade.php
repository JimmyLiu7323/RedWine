<!DOCTYPE html>
<html lang="zh">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>{{$header_title}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="{!! asset('backend/assets/css/vendor.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('backend/assets/css/flat-admin.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('backend/assets/css/theme/blue-sky.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('backend/assets/css/theme/blue.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('backend/assets/css/theme/red.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('backend/assets/css/theme/yellow.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('css/sweetalert2.min.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('resources/jquery-ui/jquery-ui.min.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('css/jquery-ui-timepicker-addon.css') !!}">
    <script type="text/javascript" src="{!! asset('backend/assets/js/vendor.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('backend/assets/js/app.js') !!}"></script>    
    <script type="text/javascript" src="{!! asset('js/sweetalert2.min.js') !!}"></script>    
    <script type="text/javascript" src="{!! asset('resources/jquery-ui/jquery-ui.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('js/jquery-ui-timepicker-addon.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('packages/barryvdh/elfinder/js/standalonepopup.min.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('backend/assets/js/jquery.colorbox-min.js') !!}"></script>
</head>
    <script type="text/javascript">
    function elFinderBrowser(field_name,url,type,win){
        tinymce.activeEditor.windowManager.open({
            file:"{{route('elfinder.tinymce4')}}",
            title:'素材庫',
            width:900,
            height:450,
            resizable:'yes'
        },
        {
            setUrl:function(url){
                win.document.getElementById(field_name).value=url;
            }
        });
        return false;
    }
    </script>
    <div class="app app-default">
        <aside class="app-sidebar" id="sidebar">
            <div class="sidebar-header">
                <a class="sidebar-brand" href="/admin"><span class="highlight">Wine Cave</span> </a>
                <button type="button" class="sidebar-toggle">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <?php
            $AdminId=Session::get('AdminId');
            $AdminAuthority=DB::table('admins')->where('AdminId',$AdminId)->select('AdminAuthority')->take(1)->first();
            if($AdminAuthority){
            ?>
            @if(intval($AdminAuthority->AdminAuthority)!==99999)
                @include('admin.layouts._authorized_menu');
            @else
                @include('admin.layouts._menu')
            @endif
            <?php 
            }
            ?>
        </aside>
        <script type="text/ng-template" id="sidebar-dropdown.tpl.html">
            <div class="dropdown-background">
                <div class="bg"></div>
            </div>
            <div class="dropdown-container"></div>
        </script>
        <div class="app-container">
            <nav class="navbar navbar-default" id="navbar">
                <div class="container-fluid">
                    <div class="navbar-collapse collapse in">
                        <ul class="nav navbar-nav navbar-mobile">
                            <li>
                                <button type="button" class="sidebar-toggle">
                                    <i class="fa fa-bars"></i>
                                </button>
                            </li>
                            <li class="logo">
                                <a class="navbar-brand" href="/admin"><span class="highlight">Wine Cave</span> Back-End</a>
                            </li>
                            <li>
                                <button type="button" class="navbar-toggle">
                                    <img class="profile-img" src="{!! asset('backend/assets/images/profile.png') !!}">
                                </button>
                            </li>
                        </ul>
                        <ul class="nav navbar-nav navbar-left">
                            <li class="navbar-title">
                                <h1>{{$header_title}}</h1>
                            </li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <li>
                                <a href="/admin/build_filter" class="btn btn-warning">Build wine filter</a>
                            </li>
                            <li class="dropdown profile">
                                <a href="/html/pages/profile.html" class="dropdown-toggle"  data-toggle="dropdown">
                                    <img class="profile-img" src="{!! asset('backend/assets/images/profile.png') !!}">
                                    <div class="title">Admin Info</div>
                                </a>
                                <div class="dropdown-menu">
                                    <div class="profile-info">
                                        <h4 class="username">{{session('AdminName')}}</h4>
                                    </div>
                                    <ul class="action">
                                        <li><a href="/admin/logout">LogOut</a></li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>        
@yield('content')
            <footer class="app-footer">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="footer-copyright">Copyright © {{date('Y')}} Wine Cave Back-end Co,Ltd.</div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script type="text/javascript">
    $(document).bind('cbox_open',function(){
        $('html').css({
            "overflow-y":'hidden'
        });
    }).bind('cbox_closed',function(){
        $('html').css({
            "overflow-y":'auto'
        });
    })
    </script>    
    </body>
</html>