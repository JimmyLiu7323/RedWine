<!DOCTYPE html>
<html lang="zh">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Adminitration Back-End</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="{!! asset('backend/assets/css/vendor.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('backend/assets/css/flat-admin.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('backend/assets/css/theme/blue-sky.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('backend/assets/css/theme/blue.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('backend/assets/css/theme/red.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('backend/assets/css/theme/yellow.css') !!}">
</head>
<body>
    @if(Session::has('message'))
    <script type="text/javascript">
    alert("{{Session::get('message')}}");
    </script>
    @endif    
    <div class="app app-default">
        <div class="app-container app-login" style="width:100%;position:relative;top:0">
            <div class="flex-center">
                <div class="app-header"></div>
                <div class="app-body">
                    <div class="app-block">
                        <div class="app-form">
                            <div class="form-header">
                                <h1 class="app-brand"><span class="highlight">Admin</span> Back-End</h1>
                            </div>
                            <form action="/admin/verifyLogin" method="POST">
                                <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon1">
                                        <i class="fa fa-user" aria-hidden="true"></i>
                                    </span>
                                    <label for="account" style="display:none">Username</label>
                                    <input type="text" name="account" id="account" class="form-control" placeholder="Username" aria-describedby="basic-addon1">
                                </div>
                                <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon2">
                                        <i class="fa fa-key" aria-hidden="true"></i>
                                    </span>
                                    <label for="password" style="display:none">Password</label>
                                    <input type="password" id="password" name="password" class="form-control" placeholder="Password" aria-describedby="basic-addon2">
                                </div>
                                <div class="text-center">
                                    <input type="submit" class="btn btn-success btn-submit" value="Login">
                                </div>
                                <input type="hidden" name="_token" id="csrf-token" value="{{csrf_token()}}" />
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="{!! asset('backend/assets/js/vendor.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('backend/assets/js/app.js') !!}"></script>
</body>
</html>