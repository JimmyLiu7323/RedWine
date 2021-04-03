<!DOCTYPE html>
<html lang="{{$LANGUAGE==='EN'?'en':'zh'}}">
    <head>
        <title>{{$htmlTitle}} &mdash; {{$LANGUAGE==='EN'?$COMPANY['companyName_En']:$COMPANY['companyName_Zh']}}</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- METAS -->
        <meta name="robots" content="allow"/>
        <meta name="copyright" content="{{$DCs['DC_Rights']}}">
        <meta name="author" content="The Wine Cave"/>
        <meta http-equiv="Content-Language" content="{{$DCs['DC_Language']}}">
        <meta name="Creation-Date" content="2020-02-06">
        <meta http-equiv="revised" content="2020-02-06 00:00:00">
        @foreach($DCs as $DCKey=>$DCValue)
            @if(trim($DCValue)!=='')      
                @if(trim($DCKey)==='DC_Coverage')
        <meta name="DC.coverage.t.min" content="{{$DCValue}}"/>
                @elseif(trim($DCKey)==='DC_Coverage_t_max')
        <meta name="DC.coverage.t.max" content="{{$DCValue}}"/>
                @else
                    @php $cutMetaKey=str_replace("_",".",$DCKey); @endphp
        <meta name="{{$cutMetaKey}}" content="{{$DCValue}}" />
                @endif            
            @endif
        @endforeach

        @yield('metas')
        <meta property="og:locale" content="{{trim($LANGUAGE)==='EN'?'EN':'ZH'}}">
        <meta property="og:url" content="{{\Request::url()}}" />
        <meta property="og:type" content="website" />
        @yield('facebook_metas')
        <meta name="csrf_token" content="{{ csrf_token() }}" />
        <link rel="stylesheet" href="{!! asset('fonts/icomoon/style.css') !!}">
        <link rel="stylesheet" href="{!! asset('css/front/bootstrap.min.css') !!}">
        <link rel="stylesheet" href="{!! asset('css/front/jquery-ui.css') !!}">
        <link rel="stylesheet" href="{!! asset('css/front/owl.carousel.min.css') !!}">
        <link rel="stylesheet" href="{!! asset('css/front/owl.theme.default.min.css') !!}">
        <link rel="stylesheet" href="{!! asset('css/front/owl.theme.default.min.css') !!}">
        <link rel="stylesheet" href="{!! asset('css/front/jquery.fancybox.min.css') !!}">
        <link rel="stylesheet" href="{!! asset('css/front/bootstrap-datepicker.css') !!}">
        <link rel="stylesheet" href="{!! asset('fonts/flaticon/font/flaticon.css') !!}">
        <link rel="stylesheet" href="{!! asset('css/front/aos.css') !!}">
        <link href="{!! asset('css/front/jquery.mb.YTPlayer.min.css') !!}" media="all" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="{!! asset('css/front/style.css') !!}">
        <link rel="stylesheet" href="{!! asset('css/front/custom-style.css') !!}">
        <link rel="stylesheet" type="text/css" href="{!! asset('resources/noUiSlider/nouislider.css') !!}">
        <link rel="stylesheet" type="text/css" href="{!!asset('css/sweetalert2.min.css')!!}">
        <link rel="stylesheet" type="text/css" href="{!! asset('resources/swiper/package/css/swiper.min.css') !!}">
        <link rel="stylesheet" href="{!! asset('css/front/all.min.css') !!}">
        <!-- favicon -->
<!--         <link rel="icon" type="image/png" href="http://www.example.com/image.png">
        <link rel="icon" type="image/gif" href="http://www.example.com/image.gif">
        <link rel="icon" type="image/jpeg" href="http://www.example.com/image.jpg">
        <link rel="icon" type="image/vnd.microsoft.icon" href="http://www.example.com/image.ico">`</pre>
        <link rel="shortcut icon" href="http://www.example.com/image.ico" />
        <link rel="address bar icon" href="http://www.example.com/image.ico">
        <link rel="icon" type="image/png" href="http://www.example.com/image.png"> -->
        <!--[if IE]><link rel="shortcut icon" href="http://www.example.com/alternateimage.ico"/><![endif]-->
    </head>
    <body data-spy="scroll" data-target=".site-navbar-target" data-offset="300">
        <div class="global-notice-wrapper" @if(!$display_global_notice) style='padding:5px' @endif>
            @if($display_global_notice)
            <p class="global-notice-banner mt-0 mb-0">{{$LANGUAGE==='EN'&&trim($global_notice_en)!==''?trim($global_notice_en):$global_notice}}</p>
            @endif
        </div>        
        <div id="loading-gif">
            <img src="{!! asset('images/loading.gif') !!}" alt="loading" title="loading" />
        </div>
        <div class="site-wrap">
            <div class="header-top">
                <div class="container">
                    <div class="header-container row mb-3">
                        <div class="header-sub-logo">
                            <img src="{!! asset('/images/sub_logo.jpg') !!}" alt="Wine of New Zealand" />
                        </div>
                        <div class="logo-container col-12 text-center">
                            <h1 class="mb-0">
                                <a href="/" class="site-logo">
                                    <img src="{!! asset('images/logo.png') !!}" alt="LOGO" class="img-fluid">
                                </a>
                            </h1>
                        </div>
                        <div class="account-funcs-container col-sm-4 ml-auto">
                            <div class="account-funcs-top">
                                <p id='header-phone'>{{$COMPANY['phone']}}</p>
                                <ul class="account-funcs">
                                    <li id="cart-link">
                                        @php
                                        $sessionCart=Session::get('product',array());
                                        @endphp

                                        @if(count($sessionCart)>0)
                                        <div id="cart-counts">{{$shareCartInfo['itemCounts']}}</div>
                                        <img id="hover-cart-icon" src="{!! asset('images/icons/item_cart.png') !!}" />
                                        @else
                                        <div id="cart-counts" style="display:none">0</div>
                                        <img id="hover-cart-icon" src="{!! asset('images/icons/cart.png') !!}" />
                                        @endif

                                        <div class="abs-cart-list">
                                            <div id="cart-loading-gif">
                                                <img src="{!! asset('images/loading.gif') !!}" alt="loading" title="loading" />
                                            </div>
                                            <p class="mb-0 mt-0 cart-title text-left">{{$LANGUAGE==='EN'?'your cart':'您的购物车'}}</p>
                                            @if(!empty($sessionCart))
                                            <div class='items'>
                                                @foreach($shareCartInfo['items'] as $item)
                                                <div class='item'>
                                                    <div class="fixed-delete" onclick="delCartItem('{{$item['id']}}')">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </div>
                                                    <div class='leftItemImg'>
                                                        <img src='{{$item['image']}}' title="cart_{{$item['id']}}" alt="cart_{{$item['id']}}" />
                                                    </div>
                                                    <div class="rightItemTool">
                                                        <p class="mt-0 mb-0 itemTitle text-left">{{$item['name']}}</p>
                                                        <div class="priceLine mt-1">
                                                            <label for="cartItemPrice" class="text-left">{{$LANGUAGE==='EN'?'Price':'金额'}}</label>
                                                            <div class="cartItemPrice-container text-left" id="cartItemPrice">{{$item['price']}}</div>
                                                        </div>
                                                        <div class="qtyLine mt-1">
                                                            <label for="itemQty_{{$item['id']}}" class="text-left">{{$LANGUAGE==='EN'?'Qty':'数量'}}</label>
                                                            <div class="cartItemQty-container row">
                                                                <div class="col-8">
                                                                    <input type="number" step="1" min="1" id="itemQty_{{$item['id']}}" class="form-control" value="{{$item['quantity']}}" />
                                                                </div>
                                                                <div class="col-4">
                                                                    <button type="button" class="btn btn-sm btn-primary" onclick="updateItemQty(this,'{{$item['id']}}')">OK</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                            <div class="abs-cart-subtotal mt-3 mb-2 text-center pt-2 pb-2">{{$LANGUAGE==='EN'?'CART SUBTOTAL':'小计'}}: ${{$shareCartInfo['subtotal']}}</div>
                                            <a href="/shopping/cart" class="cart-link mt-2 mb-2 text-center pt-2 pb-2">{{$LANGUAGE==='EN'?'VIEW CART':'浏览购物车'}}</a>
                                            <a href="/shopping/checkout" class="cart-link mt-2 mb-2 text-center pt-2 pb-2">{{$LANGUAGE==='EN'?'CHECKOUT':'结帐'}}</a>
                                            <p class="mb-0 mt-0 text-left" id="noCartItem" style="display:none">{{$LANGUAGE==='EN'?'YOU HAVE NO ITEMS IN YOUR SHOPPING CART.':'购物车内无商品。'}}</p>
                                            @else
                                            <p class="mb-0 mt-0 text-left" id="noCartItem">{{$LANGUAGE==='EN'?'YOU HAVE NO ITEMS IN YOUR SHOPPING CART.':'购物车内无商品。'}}</p>
                                            @endif
                                        </div>
                                    </li>                                    
                                    <li id="account-link">
                                        <img src="{!! asset('images/icons/account.png') !!}" />
                                        <div class="abs-account-list">
                                            <ul>
                                                <li>
                                                    <a href="/account">
                                                        <span class="icon-person"> {{$LANGUAGE==='EN'?'Account':'帐号总览'}}</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="/wishlist">
                                                        <span class="icon-heart"> {{$LANGUAGE==='EN'?'Wishlist':'愿望清单'}}</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="/shopping/cart">
                                                        <span class="icon-shopping-cart"> {{$LANGUAGE==='EN'?'Cart':'购物车'}}</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="/shopping/checkout">
                                                        <span class="icon-money"> {{$LANGUAGE==='EN'?'Checkout':'结帐'}}</span>
                                                    </a>
                                                </li>
                                                @if(!\Session::get('MemberId'))
                                                <li>
                                                    <a href="/login">
                                                        <span class="icon-loyalty"> {{$LANGUAGE==='EN'?'Log in':'登入'}}</span>
                                                    </a>
                                                </li>
                                                @else
                                                <li>
                                                    <a href="/logout">
                                                        <span class="icon-loyalty"> {{$LANGUAGE==='EN'?'Log out':'登出'}}</span>
                                                    </a>
                                                </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="account-funcs-bottom">
                                <form action="/search" method="get">
                                    <div class="input-box">
                                        <input id="search" type="text" class="form-control" name="q" value="{{isset($searchKeyword)?$searchKeyword:''}}" placeholder="Search for wine.." />
                                        <button type="submit" class="button search-button"><span><span>Search</span></span></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <nav class="pc-menu">
                        <ol class="nav-primary">
                            <li class="pc-menu-item">
                                <div class="nav-title">{{$LANGUAGE==='EN'?'latest deals':'近期销售'}}</div>
                                <div class="menu-dropdown-container ld-sub">
                                    <div class="pc-sub-menu-item">
                                        <h3>{{$LANGUAGE==='EN'?'best sell':'销售排行'}}</h3>
                                        <ul>
                                            <li>
                                                <a href="/best-sell/year/{{date('Y')-1}}">@if($LANGUAGE==='EN')Sell of {{date('Y')-1}}@else{{date('Y')-1}}年销售排行@endif</a>
                                            </li>
                                            <li>
                                                <a href="/best-sell/month">@if($LANGUAGE==='EN'){{'Sell of Month'}}@else{{'当月销售排行'}}@endif</a>
                                            </li>
                                            <li>
                                                <a href="/best-sell/week">@if($LANGUAGE==='EN'){{'Sell of Week'}}@else{{'当周销售排行'}}@endif</a>
                                            </li>                                        
                                        </ul>
                                    </div>
                                    <div class="pc-sub-menu-item">
                                        <h3>{{$LANGUAGE==='EN'?'by price':'金额筛选'}}</h3>
                                        <ul>
                                            <li>
                                                <a href="/best-sell/price/20">{{$LANGUAGE==='EN'?'Best Wines Under $20':'$20元以下最佳销售商品'}}</a>
                                            </li>
                                            <li>
                                                <a href="/best-sell/price/30">{{$LANGUAGE==='EN'?'Best Wines Under $30':'$30元以下最佳销售商品'}}</a>
                                            </li>
                                            <li>
                                                <a href="/best-sell/price/50">{{$LANGUAGE==='EN'?'Best Wines Under $50':'$50元以下最佳销售商品'}}</a>
                                            </li>                                        
                                        </ul>
                                    </div>
                                    @if(intval($display_menu_featured_image)===1)
                                    <div class="menu-lower-banner">
                                        <div class="wine-club-desk">
                                            @if(trim($menu_featured_image_link)!=='')
                                            <a href="{{$menu_featured_image_link}}">
                                                <img src="{!! asset($menu_featured_image) !!}" alt="Featured image in menu">
                                            </a>
                                            @else
                                            <img src="{!! asset($menu_featured_image) !!}" alt="Featured image in menu">
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </li>
                            <li class="pc-menu-item">
                                <a class="nav-title" href="/cases">{{$LANGUAGE==='EN'?'case deals':'销售组合'}}</a>
                                <div class="menu-dropdown-container">
                                    @foreach($CaseDeals as $CaseDealId=>$CaseDeal)
                                    <div class="pc-sub-menu-item">
                                        <h3>
                                            <a href="/cases/{{$CaseDealId}}">{{$CaseDeal['name']}}</a>
                                        </h3>
                                        <ul>
                                            @foreach($CaseDeal['topics'] as $tpItem)
                                            <li>
                                                <a href="/case/{{$tpItem->MixId}}">{{$LANGUAGE==='EN'&&trim($tpItem->MixNameEn)!==''?$tpItem->MixNameEn:$tpItem->MixName}}</a>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endforeach
                                    @if(intval($display_menu_featured_image)===1)
                                    <div class="menu-lower-banner">
                                        <div class="wine-club-desk">
                                            @if(trim($menu_featured_image_link)!=='')
                                            <a href="{{$menu_featured_image_link}}">
                                                <img src="{!! asset($menu_featured_image) !!}" alt="Featured image in menu">
                                            </a>
                                            @else
                                            <img src="{!! asset($menu_featured_image) !!}" alt="Featured image in menu">
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </li>
                            <li class="pc-menu-item">
                                <a class="nav-title" href="/wines">{{$LANGUAGE==='EN'?'browse wines':'浏览商品'}}</a>
                                <div class="menu-dropdown-container">
                                    <div class="pc-sub-menu-item">
                                        <h3>
                                            <a href="/wines/whites">{{$LANGUAGE==='EN'?'WHITES':'白酒'}}</a>
                                        </h3>
                                        <ul>
                                            @foreach($global_winesFilter['WHITES'] as $variety)
                                            <li>
                                                @if($LANGUAGE==='EN'&&trim($variety->VarietyEn)!=='')
                                                <a href="/wines/whites?variety={{$variety->VarietyId}}">{{$variety->VarietyEn}}</a>
                                                @else
                                                <a href="/wines/whites?variety={{$variety->VarietyId}}">{{$variety->Variety}}</a>
                                                @endif
                                            </li>
                                            @endforeach
                                            <li>
                                                <a href="/wines/whites" class='view-all-link'>{{$LANGUAGE==='EN'?'View All':'浏览全部'}}</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="pc-sub-menu-item">
                                        <h3>
                                            <a href="/wines/reds">{{$LANGUAGE==='EN'?'REDS':'红酒'}}</a>
                                        </h3>
                                        <ul>
                                            @foreach($global_winesFilter['REDS'] as $variety)
                                            <li>
                                                @if($LANGUAGE==='EN'&&trim($variety->VarietyEn)!=='')
                                                <a href="/wines/reds?variety={{$variety->VarietyId}}">{{$variety->VarietyEn}}</a>
                                                @else
                                                <a href="/wines/reds?variety={{$variety->VarietyId}}">{{$variety->Variety}}</a>
                                                @endif
                                            </li>
                                            @endforeach
                                            <li>
                                                <a href="/wines/reds" class='view-all-link'>{{$LANGUAGE==='EN'?'View All':'浏览全部'}}</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="pc-sub-menu-item">
                                        <h3>
                                            <a href="/by-style">{{$LANGUAGE==='EN'?'BY STYLE':'风格筛选'}}</a>
                                        </h3>
                                        <ul>
                                            @foreach($global_winesFilter['styles'] as $style)
                                            <li>
                                                @if($LANGUAGE==='EN'&&trim($style->StyleEn)!=='')
                                                <a href="/by-style/{{$style->StyleId}}">{{$style->StyleEn}}</a>
                                                @else
                                                <a href="/by-style/{{$style->StyleId}}">{{$style->Style}}</a>
                                                @endif
                                            </li>
                                            @endforeach
                                            <li>
                                                <a href="/by-style/whites" class='view-all-link'>{{$LANGUAGE==='EN'?'View All':'浏览全部'}}</a>
                                            </li>
                                        </ul>
                                        <h3 style="margin-top:20px">
                                            <a href="/wines/sparklings">{{$LANGUAGE==='EN'?'SPARKLINGS':'气泡酒'}}</a>
                                        </h3>
                                        <ul>
                                            @foreach($global_winesFilter['SPARKLINGS'] as $variety)
                                            <li>
                                                @if($LANGUAGE==='EN'&&trim($variety->VarietyEn)!=='')
                                                <a href="/wines/sparklings?variety={{$variety->VarietyId}}">{{$variety->VarietyEn}}</a>
                                                @else
                                                <a href="/wines/sparklings?variety={{$variety->VarietyId}}">{{$variety->Variety}}</a>
                                                @endif
                                            </li>
                                            @endforeach
                                            <li>
                                                <a href="/wines/sparklings" class='view-all-link'>{{$LANGUAGE==='EN'?'View All':'浏览全部'}}</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="pc-sub-menu-item">
                                        <h3>
                                            <a href="/by-country">{{$LANGUAGE==='EN'?'BY COUNTRY':'国家筛选'}}</a>
                                        </h3>
                                        <ul>
                                            @php $printCount=0; @endphp
                                            @foreach($global_winesFilter['countries'] as $cKey=>$country)
                                                @if($printCount<10)
                                            <li>
                                                <a href="/by-country/{{$cKey}}">{{$country}}</a>
                                            </li>
                                                    @php $printCount++; @endphp
                                                @endif
                                            @endforeach
                                            <li>
                                                <a href="/by-country" class='view-all-link'>{{$LANGUAGE==='EN'?'View All':'浏览全部'}}</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="pc-sub-menu-item">
                                        <h3>
                                            <a href="/by-region">{{$LANGUAGE==='EN'?'POPULAR REGIONS':'产区'}}</a>
                                        </h3>
                                        <ul>
                                            @foreach($global_winesFilter['regions'] as $region)
                                            <li>
                                                @if($LANGUAGE==='EN'&&trim($region->RegionEn)!=='')
                                                <a href="/by-region/{{$region->RegionId}}">{{$region->RegionEn}}</a>
                                                @else
                                                <a href="/by-region/{{$region->RegionId}}">{{$region->Region}}</a>
                                                @endif
                                            </li>
                                            @endforeach
                                            <li>
                                                <a href="/by-region" class='view-all-link'>{{$LANGUAGE==='EN'?'View All':'浏览全部'}}</a>
                                            </li>
                                        </ul>
                                    </div>
                                    @if(intval($display_menu_featured_image)===1)
                                    <div class="menu-lower-banner">
                                        <div class="wine-club-desk">
                                            @if(trim($menu_featured_image_link)!=='')
                                            <a href="{{$menu_featured_image_link}}">
                                                <img src="{!! asset($menu_featured_image) !!}" alt="Featured image in menu">
                                            </a>
                                            @else
                                            <img src="{!! asset($menu_featured_image) !!}" alt="Featured image in menu">
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </li>
                            <li class="pc-menu-item">
                                <a class="nav-title" href="/exhibitions">{{$LANGUAGE==='EN'?'exhibition booking':'预约聚会'}}</a>
                                <div class="menu-dropdown-container">
                                    <div class="pc-sub-menu-item">
                                        <h3>
                                            <a href="/exhibitions">{{$LANGUAGE==='EN'?'Events':'活动报名'}}</a>
                                        </h3>
                                        @if($EXHIBITION_events->count()>0)
                                        <ul>
                                            @foreach($EXHIBITION_events as $event)
                                            <li>
                                                <a href="/exhibition/{{$event->EventId}}">{{$LANGUAGE==='EN'&&trim($event->EventNameEn)!==''?$event->EventNameEn:$event->EventName}}</a>
                                            </li>
                                            @endforeach
                                        </ul>
                                        @endif
                                    </div>
                                    <div class="pc-sub-menu-item">
                                        <h3>
                                            <a href="/exhibitions/visit">{{$LANGUAGE==='EN'?'Visits':'参观Wine Cave'}}</a>
                                        </h3>
                                        @if($EXHIBITION_visits->count()>0)
                                        <ul>
                                            @foreach($EXHIBITION_visits as $visit)
                                            <li>
                                                <a href="/exhibition/{{$visit->EventId}}">{{$LANGUAGE==='EN'&&trim($visit->EventNameEn)!==''?$visit->EventNameEn:$visit->EventName}}</a>
                                            </li>
                                            @endforeach
                                        </ul>
                                        @endif
                                    </div>
                                    @if(intval($display_menu_featured_image)===1)
                                    <div class="menu-lower-banner">
                                        <div class="wine-club-desk">
                                            @if(trim($menu_featured_image_link)!=='')
                                            <a href="{{$menu_featured_image_link}}">
                                                <img src="{!! asset($menu_featured_image) !!}" alt="Featured image in menu">
                                            </a>
                                            @else
                                            <img src="{!! asset($menu_featured_image) !!}" alt="Featured image in menu">
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                </div>                                
                            </li>
                            <li class="pc-menu-item">
                                <a class="nav-title" href="/gifts">{{$LANGUAGE==='EN'?'gifts':'礼品'}}</a>
                            </li>
                            <li class="pc-menu-item">
                                <a class="nav-title" href="/blog">{{$LANGUAGE==='EN'?'blog':'部落格'}}</a>
                            </li>
                            <li class="pc-menu-item">
                                <a class="nav-title" href="/about-history">{{$LANGUAGE==='EN'?'about us':'关于我们'}}</a>
                            </li>
                        </ol>
                    </nav>
                    <div class="mobile-menu row">
                        <ul>
                            <a href="javascript:void(0)" onclick="enableMobileMenu('menu')" id="mobile-menu-btn">
                                <li class="skip-nav">{{$LANGUAGE==='EN'?'Menu':'选单'}}</li>
                            </a>
                            <!-- <a href="javascript:void(0)" onclick="enableMobileMenu('search')" id="mobile-search-btn">
                                <li>{{$LANGUAGE==='EN'?'Search':'搜寻'}}</li>
                            </a> -->
                            <a href="/account">
                                <li>{{$LANGUAGE==='EN'?'Account':'帐号总览'}}</li>
                            </a>
                            <a href="/shopping/cart">
                                <li style="position:relative;@if(count($sessionCart)>0){{'color:#9c8c3a'}}@endif">
                                    <span class="icon-shopping-cart"></span>
                                    @if(count($sessionCart)>0)
                                    <div id="mobile-cart-counts">{{$shareCartInfo['itemCounts']}}</div>
                                    @else
                                    <div id="mobile-cart-counts">0</div>
                                    @endif
                                </li>
                            </a>
                        </ul>
                    </div>
                    <div id="mobile-menu">
                        <ol class="mobile-menu-list">
                            <li onclick="enableLv1(this)">
                                <span class="icon-play_arrow mobile-sub-toggle"></span>
                                <span>{{$LANGUAGE==='EN'?'LATEST DEALS':'近期销售'}}</span>
                            </li>
                            <div class="mobile-menu-dropdown-container">
                                <div class="mobile-sub-menu-item">
                                    <ul class="level1">
                                        <li onclick="enableLv2(this)">
                                            <div class="lv1-title">
                                                <span class="icon-play_arrow mobile-sub-toggle"></span> {{$LANGUAGE==='EN'?'best sell':'销售排行'}}
                                            </div>
                                            <ul class="level2">
                                                <a href="/best-sell/year/{{date('Y')-1}}">
                                                    <li>@if($LANGUAGE==='EN')Sell of {{date('Y')-1}}@else{{date('Y')-1}}年销售排行@endif</li>
                                                </a>
                                                <a href="/best-sell/month">
                                                    <li>@if($LANGUAGE==='EN'){{'Sell of Month'}}@else{{'当月销售排行'}}@endif</li>
                                                </a>
                                                <a href="/best-sell/week">
                                                    <li>@if($LANGUAGE==='EN'){{'Sell of Week'}}@else{{'当周销售排行'}}@endif</li>
                                                </a>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                                <div class="mobile-sub-menu-item">
                                    <ul class="level1">
                                        <li onclick="enableLv2(this)">
                                            <div class="lv1-title">
                                                <span class="icon-play_arrow mobile-sub-toggle"></span> {{$LANGUAGE==='EN'?'by price':'金额筛选'}}
                                            </div>
                                            <ul class="level2">
                                                <a href="/best-sell/price/20">
                                                    <li>{{$LANGUAGE==='EN'?'Best Wines Under $20':'$20元以下最佳销售商品'}}</li>
                                                </a>
                                                <a href="/best-sell/price/30">
                                                    <li>{{$LANGUAGE==='EN'?'Best Wines Under $30':'$30元以下最佳销售商品'}}</li>
                                                </a>
                                                <a href="/best-sell/price/50">
                                                    <li>{{$LANGUAGE==='EN'?'Best Wines Under $50':'$50元以下最佳销售商品'}}</li>
                                                </a>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <li onclick="enableLv1(this)">
                                <span class="icon-play_arrow mobile-sub-toggle"></span>
                                <a href="/cases">{{$LANGUAGE==='EN'?'CASE DEALS':'销售组合'}}</a>
                            </li>
                            <div class="mobile-menu-dropdown-container">
                                @foreach($CaseDeals as $CaseDealId=>$CaseDeal)
                                <div class="mobile-sub-menu-item">
                                    <ul class="level1">
                                        <a href="/cases/{{$CaseDealId}}">
                                            <li>
                                                <div class="lv1-title">{{$CaseDeal['name']}}</div>
                                            </li>
                                        </a>
                                    </ul>
                                </div>
                                @endforeach
                            </div>
                            <li onclick="enableLv1(this)">
                                <span class="icon-play_arrow mobile-sub-toggle"></span>
                                <a href="/wines">{{$LANGUAGE==='EN'?'BROWSE WINES':'浏览商品'}}</a>
                            </li>
                            <div class="mobile-menu-dropdown-container">
                                <div class="mobile-sub-menu-item">
                                    <ul class="level1">
                                        <li onclick="enableLv2(this)">
                                            <div class="lv1-title">
                                                <span class="icon-play_arrow mobile-sub-toggle"></span>
                                                <a href="/wines/whites">{{$LANGUAGE==='EN'?'WHITES':'白酒'}}</a>
                                            </div>
                                            <ul class="level2">
                                                @foreach($global_winesFilter['WHITES'] as $variety)
                                                <li>
                                                    @if($LANGUAGE==='EN'&&trim($variety->VarietyEn)!=='')
                                                    <a href="/wines/whites?variety={{$variety->VarietyId}}">{{$variety->VarietyEn}}</a>
                                                    @else
                                                    <a href="/wines/whites?variety={{$variety->VarietyId}}">{{$variety->Variety}}</a>
                                                    @endif
                                                </li>
                                                @endforeach
                                                <li>
                                                    <a href="/wines/whites" class='view-all-link'>{{$LANGUAGE==='EN'?'View All':'浏览全部'}}</a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                                <div class="mobile-sub-menu-item">
                                    <ul class="level1">
                                        <li onclick="enableLv2(this)">
                                            <div class="lv1-title">
                                                <span class="icon-play_arrow mobile-sub-toggle"></span>
                                                <a href="/wines/reds">{{$LANGUAGE==='EN'?'REDS':'红酒'}}</a>
                                            </div>
                                            <ul class="level2">
                                                @foreach($global_winesFilter['REDS'] as $variety)
                                                <li>
                                                    @if($LANGUAGE==='EN'&&trim($variety->VarietyEn)!=='')
                                                    <a href="/wines/reds?variety={{$variety->VarietyId}}">{{$variety->VarietyEn}}</a>
                                                    @else
                                                    <a href="/wines/reds?variety={{$variety->VarietyId}}">{{$variety->Variety}}</a>
                                                    @endif
                                                </li>
                                                @endforeach
                                                <li>
                                                    <a href="/wines/reds" class='view-all-link'>{{$LANGUAGE==='EN'?'View All':'浏览全部'}}</a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                                <div class="mobile-sub-menu-item">
                                    <ul class="level1">
                                        <li onclick="enableLv2(this)">
                                            <div class="lv1-title">
                                                <span class="icon-play_arrow mobile-sub-toggle"></span>
                                                <a href="/wines/sparklings">{{$LANGUAGE==='EN'?'SPARKLINGS':'气泡酒'}}</a>
                                            </div>
                                            <ul class="level2">
                                                @foreach($global_winesFilter['SPARKLINGS'] as $variety)
                                                <li>
                                                    @if($LANGUAGE==='EN'&&trim($variety->VarietyEn)!=='')
                                                    <a href="/wines/sparklings?variety={{$variety->VarietyId}}">{{$variety->VarietyEn}}</a>
                                                    @else
                                                    <a href="/wines/sparklings?variety={{$variety->VarietyId}}">{{$variety->Variety}}</a>
                                                    @endif                                                    
                                                </li>
                                                @endforeach
                                                <li>
                                                    <a href="/wines/sparklings" class='view-all-link'>{{$LANGUAGE==='EN'?'View All':'浏览全部'}}</a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                                <div class="mobile-sub-menu-item">
                                    <ul class="level1">
                                        <li onclick="enableLv2(this)">
                                            <div class="lv1-title">
                                                <span class="icon-play_arrow mobile-sub-toggle"></span>
                                                <a href="/by-style">{{$LANGUAGE==='EN'?'BY STYLE':'风格筛选'}}</a>
                                            </div>
                                            <ul class="level2">
                                                @foreach($global_winesFilter['styles'] as $style)
                                                <li>
                                                    @if($LANGUAGE==='EN'&&trim($style->StyleEn)!=='')
                                                    <a href="/by-style/{{$style->StyleId}}">{{$style->StyleEn}}</a>
                                                    @else
                                                    <a href="/by-style/{{$style->StyleId}}">{{$style->Style}}</a>
                                                    @endif
                                                </li>
                                                @endforeach
                                                <li>
                                                    <a href="/by-style" class='view-all-link'>{{$LANGUAGE==='EN'?'View All':'浏览全部'}}</a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                                <div class="mobile-sub-menu-item">
                                    <ul class="level1">
                                        <li onclick="enableLv2(this)">
                                            <div class="lv1-title">
                                                <span class="icon-play_arrow mobile-sub-toggle"></span>
                                                <a href="/by-country">{{$LANGUAGE==='EN'?'BY COUNTRY':'国家筛选'}}</a>
                                            </div>
                                            <ul class="level2">
                                                @php $printCount=0; @endphp
                                                @foreach($global_winesFilter['countries'] as $country)
                                                    @if($printCount<10)
                                                <li>
                                                    <a href="/by-country/{{$cKey}}">{{$country}}</a>
                                                </li>
                                                        @php $printCount++; @endphp
                                                    @endif
                                                @endforeach
                                                <li>
                                                    <a href="/by-country" class='view-all-link'>{{$LANGUAGE==='EN'?'View All':'浏览全部'}}</a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                                <div class="mobile-sub-menu-item">
                                    <ul class="level1">
                                        <li onclick="enableLv2(this)">
                                            <div class="lv1-title">
                                                <span class="icon-play_arrow mobile-sub-toggle"></span>
                                                <a href="/by-region">{{$LANGUAGE==='EN'?'BY REGION':'产区'}}</a>
                                            </div>
                                            <ul class="level2">
                                                @foreach($global_winesFilter['regions'] as $region)
                                                <li>
                                                    @if($LANGUAGE==='EN'&&trim($region->RegionEn)!=='')
                                                    <a href="/by-region/{{$region->RegionId}}">{{$region->RegionEn}}</a>
                                                    @else
                                                    <a href="/by-region/{{$region->RegionId}}">{{$region->Region}}</a>
                                                    @endif
                                                </li>
                                                @endforeach
                                                <li>
                                                    <a href="/by-region" class='view-all-link'>{{$LANGUAGE==='EN'?'View All':'浏览全部'}}</a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </ol>
                        <ol class="mobile-menu-list">
                            <li onclick="enableLv1(this)">
                                <span class="icon-play_arrow mobile-sub-toggle"></span>
                                <span><a href="/exhibitions">{{$LANGUAGE==='EN'?'EXHIBITION BOOKING':'预约聚会'}}</a></span>
                            </li>
                            <div class="mobile-menu-dropdown-container">
                                <div class="mobile-sub-menu-item">
                                    <div class="mobile-sub-menu-item">
                                        <ul class="level1">
                                            <li onclick="enableLv2(this)">
                                                <div class="lv1-title">
                                                    <span class="icon-play_arrow mobile-sub-toggle"></span>
                                                    <a href="/exhibitions">{{$LANGUAGE==='EN'?'Events':'活动报名'}}</a>
                                                </div>
                                                <ul class="level2">
                                                    @foreach($EXHIBITION_events as $event)
                                                    <li>
                                                        <a href="/exhibition/{{$event->EventId}}">{{$LANGUAGE==='EN'&&trim($event->EventNameEn)!==''?$event->EventNameEn:$event->EventName}}</a>
                                                    </li>
                                                    @endforeach
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="mobile-sub-menu-item">
                                    <div class="mobile-sub-menu-item">
                                        <ul class="level1">
                                            <li onclick="enableLv2(this)">
                                                <div class="lv1-title">
                                                    <span class="icon-play_arrow mobile-sub-toggle"></span>
                                                    <a href="/exhibitions/visit">{{$LANGUAGE==='EN'?'Visits':'参观Wine Cave'}}</a>
                                                </div>
                                                <ul class="level2">
                                                    @foreach($EXHIBITION_visits as $visit)
                                                    <li>
                                                        <a href="/exhibition/{{$visit->EventId}}">{{$LANGUAGE==='EN'&&trim($visit->EventNameEn)!==''?$visit->EventNameEn:$visit->EventName}}</a>
                                                    </li>
                                                    @endforeach
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </ol>
                        <ol class="mobile-menu-list">
                            <li>
                                <span class="icon-play_arrow mobile-sub-toggle"></span>
                                <span><a href="/gifts">{{$LANGUAGE==='EN'?'GIFTS':'礼品'}}</a></span>
                            </li>
                        </ol>
                        <ol class="mobile-menu-list">
                            <li>
                                <span class="icon-play_arrow mobile-sub-toggle"></span>
                                <span><a href="/blog">{{$LANGUAGE==='EN'?'BLOG':'部落格'}}</a></span>
                            </li>
                        </ol>
                        <ol class="mobile-menu-list">
                            <li>
                                <span class="icon-play_arrow mobile-sub-toggle"></span>
                                <span><a class="nav-title" href="/about-history">{{$LANGUAGE==='EN'?'ABOUT US':'关于我们'}}</a></span>
                            </li>
                        </ol>
                    </div>
                    <div id="mobile-search">
                        <form id="search_mini_form" action="/search" method="get">
                            <div class="input-box col-12">
                                <input id="search" type="text" class="form-control" name="q" value="{{isset($searchKeyword)?$searchKeyword:''}}" placeholder="Search for wine.." />
                                <button type="submit" class="button search-button"><span><span>Search</span></span></button>
                            </div>
                        </form>                        
                    </div>
                </div>
            </div>
            @if(!$hasW18ConfirmationCookie)
            <div id="alert18">
                <div id="aw_popup_wraper"></div>
                <div id="aw_popup_window" style="width:350px;margin-left:-175px;margin-top:-200px;top:50%;left:50%">
                    <div id="aw_popup_content">
                        <div id="btnPanel">
                            <img src="{!! asset('images/logo.png') !!}" alt="LOGO" />
                            <p>{{$LANGUAGE==='EN'?'You need to be aged 18 or older':'您的年龄需大于18岁'}}</p>
                            <input class="btn yes" title="I am over 18" onclick="confirmation18()" type="button" value="{{$LANGUAGE==='EN'?'I am over 18':'已年满18岁'}}" />
                            <input class="btn no" title="I am under 18" onclick="window.location='http://www.alcohol.org.nz/';return false;" type="button" value="{{$LANGUAGE==='EN'?'I am under 18':'尚未年满18岁'}}">
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @yield('content')
            <div class="footer footer-bg-light">
                <div class="container">
                    <div class="footer-first-layer row">
                        <div class="col-12">
                            <div class="footer-first-layer-item">
                                <div class="item-header mb-0 mt-0">
                                    <p class="mb-0 mt-0">{{$LANGUAGE==='EN'?'join the conversation':'关注我们'}}</p>
                                    <i class="fas fa-chevron-right rightArrow"></i>
                                    <i class="fas fa-chevron-down downArrow"></i>
                                </div>
                                <ul>
                                    @if($COMPANY['fb_link']!=="")
                                    <li><a href="{{$COMPANY['fb_link']}}">{{$LANGUAGE==='EN'?'Follow Us On Facebook':'追随Facebook'}}</a></li>
                                    @endif
                                    @if($COMPANY['ig_link']!=="")
                                    <li><a href="{{$COMPANY['ig_link']}}">{{$LANGUAGE==='EN'?'Catch Us On Instagram':'追随Instagram'}}</a></li>
                                    @endif
                                    @if($COMPANY['twitter_link']!=="")
                                    <li><a href="{{$COMPANY['twitter_link']}}">{{$LANGUAGE==='EN'?'Join Us On Twitter':'追随Twitter'}}</a></li>
                                    @endif
                                    @if($COMPANY['yt_link']!=="")
                                    <li><a href="{{$COMPANY['yt_link']}}">{{$LANGUAGE==='EN'?'Watch Us On Youtube':'追随Youtube'}}</a></li>
                                    @endif
                                    <li><a href="/blog">{{$LANGUAGE==='EN'?'Keep Up With Our Blog':'我们的部落格'}}</a></li>
                                </ul>
                            </div>
                            <div class="footer-first-layer-item">
                                <div class="item-header mb-0 mt-0">
                                    <p class="mb-0 mt-0">{{$LANGUAGE==='EN'?'online ordering':'线上购物'}}</p>
                                    <i class="fas fa-chevron-right rightArrow"></i>
                                    <i class="fas fa-chevron-down downArrow"></i>
                                </div>
                                <ul>
                                    <li><a href="/orders-and-payment">{{$LANGUAGE==='EN'?'Orders And Payment':'订购与付款'}}</a></li>
                                    <li><a href="/delivery-page">{{$LANGUAGE==='EN'?'Delivery Information':'运送资讯'}}</a></li>
                                </ul>
                            </div>
                            <div class="footer-first-layer-item">
                                <div class="item-header mb-0 mt-0">
                                    <p class="mt-0 mb-0">{{$LANGUAGE==='EN'?'customer service':'顾客服务'}}</p>
                                    <i class="fas fa-chevron-right rightArrow"></i>
                                    <i class="fas fa-chevron-down downArrow"></i>
                                </div>
                                <ul>
                                    <li><a href="/guarantee">{{$LANGUAGE==='EN'?'Our Guarantee':'商家保证'}}</a></li>
                                    <li><a href="/privacy-policy">{{$LANGUAGE==='EN'?'Privacy Policy':'隐私政策'}}</a></li>
                                    <li><a href="/corporate">{{$LANGUAGE==='EN'?'Corporate Wine Service':'合作促销'}}</a></li>
                                </ul>
                            </div>
                            <div class="footer-first-layer-item">
                                <div class="item-header mb-0 mt-0">
                                    <p class="mt-0 mb-0">{{$LANGUAGE==='EN'?'about us':'关于我们'}}</p>
                                    <i class="fas fa-chevron-right rightArrow"></i>
                                    <i class="fas fa-chevron-down downArrow"></i>
                                </div>
                                <ul>
                                    <li><a href="/contacts#what_are_your_opening_hours">{{$LANGUAGE==='EN'?'Location & Opening Hours':'地址与营业时间'}}</a></li>
                                    <li><a href="/contacts">{{$LANGUAGE==='EN'?'Contact Us':'联络我们'}}</a></li>
                                    <li><a href="/about-history">{{$LANGUAGE==='EN'?'Our History':'历史沿革'}}</a></li>
                                    <li><a href="/awards">{{$LANGUAGE==='EN'?'Awards':'历年奖项'}}</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>                    
                </div>
            </div>
            <div class="footer footer-bg-light2">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="row">
                                <div class="col-md-5 col-12">
                                    <div class="footer-logo">
                                        <a href="/">
                                            <img src="{!! asset('images/wine_logo_small.png') !!}" alt="LOGO" class="img-fluid">
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-7 col-12">
                                    <ul class="footer-company-info">
                                        <li>{{$LANGUAGE==='EN'?'Address':'地址'}}: {{$COMPANY['address']}}</li>
                                        <li>Email: <a href="mailto:{{$COMPANY['email']}}">{{$COMPANY['email']}}</a></li>
                                        <li>{{$LANGUAGE==='EN'?'Phone':'电话'}}: {{$COMPANY['phone']}}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-12 right-part">
                            <div id="footer-sm-links">
                                @if($COMPANY['fb_link']!=="")
                                <a href="{{$COMPANY['fb_link']}}" title="Facebook" class="facebook"><i class="fab fa-facebook-f"></i></a>
                                @endif
                                @if($COMPANY['twitter_link']!=="")
                                <a href="{{$COMPANY['twitter_link']}}" title="Twitter" class="twitter"><i class="fab fa-twitter"></i></a>
                                @endif
                                @if($COMPANY['yt_link']!=="")
                                <a href="{{$COMPANY['yt_link']}}" class="youtube"><i class="fab fa-youtube"></i></a>
                                @endif
                                @if($COMPANY['ig_link']!=="")
                                <a href="{{$COMPANY['ig_link']}}" class="instagram"><i class="fab fa-instagram"></i></a>
                                @endif
                            </div>
                            <div id="footer-payment-method">
                                <span class="icon-visa"></span>
                                <span class="icon-master"></span>
                                <span class="icon-alipay"></span>
                                <span class="icon-wechatpay"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-copyright">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <address class="copyright">Copyright &copy;{{date('Y')}} All rights reserved
                                @if(intval($display_footer_license)===1&&$footer_license)
                                    @if($LANGUAGE==='EN')
                                        @if(intval($display_footer_license_number)===1&&trim($footer_license_number)!=='')
                                        <a href="{!! asset($footer_license) !!}" title="Liquor licence" rel="nofollow" target="_blank">Liquor license number: {{$footer_license_number}}</a>@endif
                                        @if(intval($display_footer_license_expires)===1&&trim($footer_license_expires)!=='')Expires: {{$footer_license_expires}}@endif
                                    @else
                                        @if(intval($display_footer_license_number)===1&&trim($footer_license_number)!=='')
                                        <a href="{!! asset($footer_license) !!}" title="Liquor licence" rel="nofollow" target="_blank">执照编号: {{$footer_license_number}}</a>@endif
                                        @if(intval($display_footer_license_expires)===1&&trim($footer_license_expires)!=='')执照过期日: {{$footer_license_expires}}@endif                                    
                                    @endif
                                @endif
                            </address>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Language selector -->
        <span class="language--picker">
            <span class="language--text">🌐 {{$LANGUAGE==='EN'?'Pick Language':'选择语言'}}</span>
        </span>
        <div class="language--container">
            <ul>
                @if($LANGUAGE==='EN')
                <li><a href="/lang/zh">Chinese</a></li>
                <li class="language--active"><a href="/lang/en">English</a></li>
                @else
                <li class="language--active"><a href="/lang/zh">中文</a></li>
                <li><a href="/lang/en">英文</a></li>                
                @endif
            </ul>
            <span class="language--close">X</span>
        </div>        

        <!-- loader -->
        <div id="loader" class="show fullscreen">
            <svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#ff5e15"/></svg>
        </div>
        <script src="{!! asset('js/front/jquery-3.3.1.min.js') !!}"></script>
        <script src="{!! asset('js/front/jquery-migrate-3.0.1.min.js') !!}"></script>
        <script src="{!! asset('js/front/jquery-ui.js') !!}"></script>
        <script src="{!! asset('js/front/popper.min.js') !!}"></script>
        <script src="{!! asset('js/front/bootstrap.min.js') !!}"></script>
        <script src="{!! asset('js/front/owl.carousel.min.js') !!}"></script>
        <script src="{!! asset('js/front/jquery.stellar.min.js') !!}"></script>
        <script src="{!! asset('js/front/jquery.countdown.min.js') !!}"></script>
        <script src="{!! asset('js/front/bootstrap-datepicker.min.js') !!}"></script>
        <script src="{!! asset('js/front/jquery.easing.1.3.js') !!}"></script>
        <script src="{!! asset('js/front/aos.js') !!}"></script>
        <script src="{!! asset('js/front/jquery.fancybox.min.js') !!}"></script>
        <script src="{!! asset('js/front/jquery.sticky.js') !!}"></script>
        <script src="{!! asset('js/front/jquery.mb.YTPlayer.min.js') !!}"></script>
        <script src="{!! asset('js/front/main.js') !!}"></script>
        <script src="{!! asset('resources/noUiSlider/nouislider.js') !!}"></script>
        <script type="text/javascript" src="{!!asset('js/sweetalert2.min.js')!!}"></script>
        <script type="text/javascript" src="{!! asset('resources/swiper/package/js/swiper.min.js') !!}"></script>
        <script type="text/javascript">
        function confirmation18(){
            $.ajax({
                'url':'/confirmation18',
                'data':{}
            })
            $('#alert18').hide();
        }

        function updatePriceValue(values, handle, unencoded, tap, positions){
            console.log(values);
            console.log(handle);
            console.log(unencoded);
            console.log(tap);
            console.log(positions);
        }

        $(document).ready(function(){
            if($('.filter-box').length>0){
                $('.filter-box').children('.filter-title').click(function(){
                    $(this).next('.filter-scroll').toggle();
                })

                $('.filter-box').children('.filter-title').click(function(){
                    $(this).next('.filter-slider').toggle();
                })
            }

            let startSlider=document.getElementById('slider-start');
            if(startSlider){
                noUiSlider.create(startSlider,{
                    start:[filter_price_start,filter_price_end],
                    connect:true,
                    range:{
                        'min':filter_price_min,
                        'max':filter_price_max
                    },
                    step:10
                });

                startSlider.noUiSlider.on('slide',function (values, handle){
                    $('#price-set-start').html(parseInt(values[0]));
                    $('#price-set-end').html(parseInt(values[1]));
                });

                startSlider.noUiSlider.on('change',function(){
                    console.log('end ajax');
                });
            }

            $('.content-title').click(function(){
                let contentBox=$(this).next('.content-box');
                let titleIcon=$(this).children('.span-icon');
                contentBox.slideToggle('fast',function(){
                    if(contentBox.is(':visible')){
                        titleIcon.addClass('icon-arrow-circle-down');
                        titleIcon.removeClass('icon-arrow-circle-right');
                    }
                    else{
                        titleIcon.addClass('icon-arrow-circle-right');
                        titleIcon.removeClass('icon-arrow-circle-down');

                    }
                });
            })

            $('#change_pwd').click(function(){
                if($("#change_pwd").is(":checked")){
                    $('#change_pwd_section').show();
                }
                else{
                    $('#change_pwd_section').hide();
                }
            })

            if($('#shipping_country').length>0){
                $('#shipping_country').val('NZ').change();
                initEstimate('auto');
            }

            $('.footer-first-layer-item').children('.item-header').click(function(){
                $(this).toggleClass('active');
            })

            $( "#cart-link" ).on( "mouseenter", function() {
                $('.abs-cart-list').show();
            }).on( "mouseleave", function() {
                setTimeout(function(){
                    $('.abs-cart-list').hide();
                },700);
            });

            $( ".abs-cart-list" ).on( "mouseenter", function() {
                $('.abs-cart-list').show();
            }).on( "mouseleave", function() {
                setTimeout(function(){
                    $('.abs-cart-list').hide();
                },700);
            });
        })

        $('.pc-menu-item').hover(function(){
            if($(this).children('.menu-dropdown-container').length>0){
                $(this).parent('ol').addClass('over');
                $(this).addClass('over');
                $(this).children('.nav-title').addClass('over');
                $(this).children('.menu-dropdown-container').css('display','block');  
            }
        },function(){
            $(this).parent('ol').removeClass('over');
            $(this).removeClass('over');
            $(this).children('.nav-title').removeClass('over');
            $(this).children('.menu-dropdown-container').css('display','none');
        })

        const swiper=new Swiper('.swiper-container',{
            pagination:{
                el:'.swiper-pagination',
                clickable: true,
            },
            autoplay: {
                delay: 5000
            },
            speed: 500,
            slidesPerView: 1,
            // centeredSlides: true,
            spaceBetween: 15,
        });

        function enableMobileMenu(flag){
            if(flag=='menu'){
                $('#mobile-menu').toggleClass('active');
                $('#mobile-menu-btn').toggleClass('skip-active');
                $('#mobile-search-btn').removeClass('skip-active');
                $('#mobile-search').removeClass('active');
            }
            else if(flag=='search'){
                $('#mobile-search').toggleClass('active');
                $('#mobile-search-btn').toggleClass('skip-active');
                $('#mobile-menu-btn').removeClass('skip-active');
                $('#mobile-menu').removeClass('active');
            }
        }

        function toggleShrink(elem){
            $(elem).toggleClass('icon-arrow-circle-down');
            $(elem).parent().next('.shrink-list').slideToggle('fast');
        }

        function enableLv1(elem){
            $(elem).next('.mobile-menu-dropdown-container').slideToggle('fast');
        }

        function enableLv2(elem){
            $(elem).children('.level2').slideToggle('fast');
        }

        $(window).resize(function(){
            if($(window).width()>=768){
                $('#mobile-menu').removeClass('active');
                $('.mobile-menu').find('a').removeClass('skip-active');

                $('.shrink-list').show();
            }
        })

        function addItem(itemType,itemId){
            let addQty=0;
            if(itemType=='mix'||itemType=='wine'||itemType=='gift'){
                if($('#itemQuantity').length>0)
                    addQty=parseInt($('#itemQuantity').val());

                if(addQty>0){
                    $.ajax({
                        "type": "POST",
                        "headers": {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                        "url": "/shopping/addItem",
                        "data":{
                            itemType:itemType,
                            itemId:itemId,
                            itemQuantity:addQty                   
                        },
                        success:function(msg){
                            $('html').addClass('current-loading');
                            $('#loading-gif').show();
                            try{
                                $('.abs-cart-list').find('.items').remove();
                                var addItemRes=JSON.parse(msg);
                                var newItemHtml="";
                                if(addItemRes['status']=='success'){
                                    for(var i=0;i<addItemRes['cart']['items']['length'];i++){
                                        var iterateId=addItemRes['cart']['items'][i]['id'];
                                        var iterateImg=addItemRes['cart']['items'][i]['image'];
                                        var iterateQty=addItemRes['cart']['items'][i]['quantity'];
                                        var iteratePrice=addItemRes['cart']['items'][i]['price'];
                                        var iterateName=addItemRes['cart']['items'][i]['name'];

                                        newItemHtml+="<div class='item'>";

                                        newItemHtml+="<div class='fixed-delete' onclick='delCartItem('"+iterateId+"')'><i class='fas fa-trash-alt'></i></div>";

                                        newItemHtml+="<div class='leftItemImg'>";
                                        newItemHtml+="<img src='"+iterateImg+"' title='cart_"+iterateId+"' alt='cart_"+iterateId+"' />";
                                        newItemHtml+="</div>";

                                        newItemHtml+="<div class='rightItemTool'>";

                                        newItemHtml+="<p class='mt-0 mb-0 itemTitle text-left'>"+iterateName+"</p>";
                                        newItemHtml+="<div class='priceLine mt-1'>";
                                        @if($LANGUAGE==='EN')
                                        newItemHtml+="<label for='cartItemPrice' class='text-left'>Price</label>";
                                        @else
                                        newItemHtml+="<label for='cartItemPrice' class='text-left'>金额</label>";
                                        @endif
                                        newItemHtml+="<div class='cartItemPrice-container text-left' id='cartItemPrice'>"+iteratePrice+"</div>";
                                        newItemHtml+="</div>";

                                        newItemHtml+="<div class='qtyLine mt-1'>";
                                        @if($LANGUAGE==='EN')
                                        newItemHtml+="<label for='itemQty_"+iterateId+"' class='text-left'>Qty</label>";
                                        @else
                                        newItemHtml+="<label for='itemQty_"+iterateId+"' class='text-left'>数量</label>";
                                        @endif
                                        newItemHtml+="<div class='cartItemQty-container row'>";
                                        newItemHtml+="<div class='col-8'>";
                                        newItemHtml+="<input type='number' step=1 min=1 id='itemQty_"+iterateId+"' class='form-control' value='"+iterateQty+"' />";
                                        newItemHtml+="</div>";
                                        newItemHtml+="<div class='col-4'>";
                                        newItemHtml+="<button type='button' class='btn btn-sm btn-primary' onclick=updateItemQty(this,'"+iterateId+"')>OK</button>";
                                        newItemHtml+="</div>";
                                        newItemHtml+="</div>";
                                        newItemHtml+="</div>";

                                        newItemHtml+="</div>";

                                        newItemHtml+="</div>";
                                    }

                                    if($('#noCartItem').css('display')=='block'){
                                        $('#noCartItem').hide();
                                        newItemHtml="<div class='items'>"+newItemHtml+"</div>";
                                    }
                                    else{
                                        $('a.cart-link').remove();
                                        $('.abs-cart-subtotal').remove();
                                        $('.abs-cart-list').find('.item').remove();
                                    }

                                    @if($LANGUAGE==='EN')
                                    newItemHtml+="<div class='abs-cart-subtotal mt-3 mb-2 text-center pt-2 pb-2'>CART SUBTOTAL: $"+addItemRes['cart']['subtotal']+"</div>";
                                    newItemHtml+="<a href='/shopping/cart' class='cart-link mt-2 mb-2 text-center pt-2 pb-2'>VIEW CART</a>";
                                    newItemHtml+="<a href='/shopping/checkout' class='cart-link mt-2 mb-2 text-center pt-2 pb-2'>CHECKOUT</a>";
                                    @else
                                    newItemHtml+="<div class='abs-cart-subtotal mt-3 mb-2 text-center pt-2 pb-2'>小计: $"+addItemRes['cart']['subtotal']+"</div>";
                                    newItemHtml+="<a href='/shopping/cart' class='cart-link mt-2 mb-2 text-center pt-2 pb-2'>浏览购物车</a>";
                                    newItemHtml+="<a href='/shopping/checkout' class='cart-link mt-2 mb-2 text-center pt-2 pb-2'>结帐</a>";
                                    @endif
                                    

                                    $('.abs-cart-list').append(newItemHtml);

                                    $('#cart-counts').text(addItemRes['cart']['itemCounts']);
                                    $('#mobile-cart-counts').text(addItemRes['cart']['itemCounts']);
                                    $('#hover-cart-icon').attr('src','/images/icons/item_cart.png');
                                    $('#cart-counts').show();

                                    $("html, body").animate({ scrollTop: 0 },"slow");
                                }
                                else if(addItemRes['status']=='warning'){
                                    alert(addItemRes['msg']);
                                }
                            }
                            catch(err){
                                alert("Something failed, try again later.");    
                            }

                            setTimeout(function(){
                                $('html').removeClass('current-loading');
                                $('#loading-gif').hide();
                            },1500);
                        },
                        error:function(err){
                            alert("Something failed, try again later.");
                        }
                    })
                }
                else{
                    alert("Please enter quantity!");
                }
            }
        }

        function quick_addItem(itemType,itemId){
            if(itemType=='mix'||itemType=='wine'||itemType=='gift'){
                let addQty=parseInt($('#qty_'+itemType+'_'+itemId).val());
                if(addQty>0){
                    $.ajax({
                        "type": "POST",
                        "headers": {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                        "url": "/shopping/addItem",
                        "data":{
                            itemType:itemType,
                            itemId:itemId,
                            itemQuantity:addQty                   
                        },
                        success:function(msg){
                            $('html').addClass('current-loading');
                            $('#loading-gif').show();
                            try{
                                $('.abs-cart-list').find('.items').remove();
                                var addItemRes=JSON.parse(msg);
                                var newItemHtml="";
                                if(addItemRes['status']=='success'){
                                    for(var i=0;i<addItemRes['cart']['items']['length'];i++){
                                        var iterateId=addItemRes['cart']['items'][i]['id'];
                                        var iterateImg=addItemRes['cart']['items'][i]['image'];
                                        var iterateQty=addItemRes['cart']['items'][i]['quantity'];
                                        var iteratePrice=addItemRes['cart']['items'][i]['price'];
                                        var iterateName=addItemRes['cart']['items'][i]['name'];

                                        newItemHtml+="<div class='item'>";

                                        newItemHtml+="<div class='fixed-delete' onclick=delCartItem('"+iterateId+"')><i class='fas fa-trash-alt'></i></div>";

                                        newItemHtml+="<div class='leftItemImg'>";
                                        newItemHtml+="<img src='"+iterateImg+"' title='cart_"+iterateId+"' alt='cart_"+iterateId+"' />";
                                        newItemHtml+="</div>";

                                        newItemHtml+="<div class='rightItemTool'>";

                                        newItemHtml+="<p class='mt-0 mb-0 itemTitle text-left'>"+iterateName+"</p>";
                                        newItemHtml+="<div class='priceLine mt-1'>";
                                        newItemHtml+="<label for='cartItemPrice' class='text-left'>Price</label>";
                                        newItemHtml+="<div class='cartItemPrice-container text-left' id='cartItemPrice'>"+iteratePrice+"</div>";
                                        newItemHtml+="</div>";

                                        newItemHtml+="<div class='qtyLine mt-1'>";
                                        @if($LANGUAGE==='EN')
                                        newItemHtml+="<label for='itemQty_"+iterateId+"' class='text-left'>Qty</label>";
                                        @else
                                        newItemHtml+="<label for='itemQty_"+iterateId+"' class='text-left'>数量</label>";
                                        @endif
                                        newItemHtml+="<div class='cartItemQty-container row'>";
                                        newItemHtml+="<div class='col-8'>";
                                        newItemHtml+="<input type='number' step=1 min=1 id='itemQty_"+iterateId+"' class='form-control' value='"+iterateQty+"' />";
                                        newItemHtml+="</div>";
                                        newItemHtml+="<div class='col-4'>";
                                        newItemHtml+="<button type='button' class='btn btn-sm btn-primary' onclick=updateItemQty(this,'"+iterateId+"')>OK</button>";
                                        newItemHtml+="</div>";
                                        newItemHtml+="</div>";
                                        newItemHtml+="</div>";

                                        newItemHtml+="</div>";

                                        newItemHtml+="</div>";
                                    }

                                    if($('#noCartItem').css('display')=='block'){
                                        $('#noCartItem').hide();
                                        newItemHtml="<div class='items'>"+newItemHtml+"</div>";
                                    }
                                    else{
                                        $('a.cart-link').remove();
                                        $('.abs-cart-subtotal').remove();
                                        $('.abs-cart-list').find('.items').remove();
                                    }

                                    @if($LANGUAGE==='EN')
                                    newItemHtml+="<div class='abs-cart-subtotal mt-3 mb-2 text-center pt-2 pb-2'>CART SUBTOTAL: $"+addItemRes['cart']['subtotal']+"</div>";
                                    newItemHtml+="<a href='/shopping/cart' class='cart-link mt-2 mb-2 text-center pt-2 pb-2'>VIEW CART</a>";
                                    newItemHtml+="<a href='/shopping/checkout' class='cart-link mt-2 mb-2 text-center pt-2 pb-2'>CHECKOUT</a>";
                                    @else
                                    newItemHtml+="<div class='abs-cart-subtotal mt-3 mb-2 text-center pt-2 pb-2'>小计: $"+addItemRes['cart']['subtotal']+"</div>";
                                    newItemHtml+="<a href='/shopping/cart' class='cart-link mt-2 mb-2 text-center pt-2 pb-2'>浏览购物车</a>";
                                    newItemHtml+="<a href='/shopping/checkout' class='cart-link mt-2 mb-2 text-center pt-2 pb-2'>结帐</a>";
                                    @endif

                                    $('.abs-cart-list').append(newItemHtml);

                                    $('#cart-counts').text(addItemRes['cart']['itemCounts']);
                                    $('#cart-counts').text(addItemRes['cart']['itemCounts']);
                                    $('#mobile-cart-counts').text(addItemRes['cart']['itemCounts']);
                                    $('#hover-cart-icon').attr('src','/images/icons/item_cart.png');
                                    $('#cart-counts').show();

                                    $("html, body").animate({ scrollTop: 0 },"slow");
                                }
                                else if(addItemRes['status']=='warning'){
                                    alert(addItemRes['msg']);
                                }
                            }
                            catch(err){
                                alert("Something failed, try again later.");    
                            }

                            setTimeout(function(){
                                $('html').removeClass('current-loading');
                                $('#loading-gif').hide();
                            },1500);
                        },
                        error:function(err){
                            alert("Something failed, try again later.");
                        }
                    })
                }
                else{
                    @if($LANGUAGE==='EN')
                    alert("Should put one bottle to cart at least!");
                    @else
                    alert("至少需加入一瓶酒至购物车!")
                    @endif
                }
            }
        }

        function quick_addWeeklyItem(itemType,elem,itemId){
            if(itemType=='mix'||itemType=='wine'){
                var addQty = parseInt($(elem).prev('div').children('.weeklyItemQty').val());
                if(addQty>0){
                    $.ajax({
                        "type": "POST",
                        "headers": {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                        "url": "/shopping/addItem",
                        "data":{
                            itemType:itemType,
                            itemId:itemId,
                            itemQuantity:addQty                   
                        },
                        success:function(msg){
                            $('html').addClass('current-loading');
                            $('#loading-gif').show();
                            try{
                                $('.abs-cart-list').find('.items').remove();
                                var addItemRes=JSON.parse(msg);
                                var newItemHtml="";
                                if(addItemRes['status']=='success'){
                                    for(var i=0;i<addItemRes['cart']['items']['length'];i++){
                                        var iterateId=addItemRes['cart']['items'][i]['id'];
                                        var iterateImg=addItemRes['cart']['items'][i]['image'];
                                        var iterateQty=addItemRes['cart']['items'][i]['quantity'];
                                        var iteratePrice=addItemRes['cart']['items'][i]['price'];
                                        var iterateName=addItemRes['cart']['items'][i]['name'];

                                        newItemHtml+="<div class='item'>";

                                        newItemHtml+="<div class='fixed-delete' onclick=delCartItem('"+iterateId+"')><i class='fas fa-trash-alt'></i></div>";

                                        newItemHtml+="<div class='leftItemImg'>";
                                        newItemHtml+="<img src='"+iterateImg+"' title='cart_"+iterateId+"' alt='cart_"+iterateId+"' />";
                                        newItemHtml+="</div>";

                                        newItemHtml+="<div class='rightItemTool'>";

                                        newItemHtml+="<p class='mt-0 mb-0 itemTitle text-left'>"+iterateName+"</p>";
                                        newItemHtml+="<div class='priceLine mt-1'>";
                                        newItemHtml+="<label for='cartItemPrice' class='text-left'>Price</label>";
                                        newItemHtml+="<div class='cartItemPrice-container text-left' id='cartItemPrice'>"+iteratePrice+"</div>";
                                        newItemHtml+="</div>";

                                        newItemHtml+="<div class='qtyLine mt-1'>";
                                        @if($LANGUAGE==='EN')
                                        newItemHtml+="<label for='cartItemPrice' class='text-left'>Price</label>";
                                        @else
                                        newItemHtml+="<label for='cartItemPrice' class='text-left'>金额</label>";
                                        @endif
                                        newItemHtml+="<div class='cartItemPrice-container text-left' id='cartItemPrice'>"+iteratePrice+"</div>";
                                        newItemHtml+="</div>";

                                        newItemHtml+="<div class='qtyLine mt-1'>";
                                        @if($LANGUAGE==='EN')
                                        newItemHtml+="<label for='itemQty_"+iterateId+"' class='text-left'>Qty</label>";
                                        @else
                                        newItemHtml+="<label for='itemQty_"+iterateId+"' class='text-left'>数量</label>";
                                        @endif
                                        newItemHtml+="<div class='cartItemQty-container row'>";
                                        newItemHtml+="<div class='col-8'>";
                                        newItemHtml+="<input type='number' step=1 min=1 id='itemQty_"+iterateId+"' class='form-control' value='"+iterateQty+"' />";
                                        newItemHtml+="</div>";
                                        newItemHtml+="<div class='col-4'>";
                                        newItemHtml+="<button type='button' class='btn btn-sm btn-primary' onclick=updateItemQty(this,'"+iterateId+"')>OK</button>";
                                        newItemHtml+="</div>";
                                        newItemHtml+="</div>";
                                        newItemHtml+="</div>";

                                        newItemHtml+="</div>";

                                        newItemHtml+="</div>";
                                    }

                                    if($('#noCartItem').css('display')=='block'){
                                        $('#noCartItem').hide();
                                        newItemHtml="<div class='items'>"+newItemHtml+"</div>";
                                    }
                                    else{
                                        $('a.cart-link').remove();
                                        $('.abs-cart-subtotal').remove();
                                        $('.abs-cart-list').find('.items').remove();
                                    }

                                    @if($LANGUAGE==='EN')
                                    newItemHtml+="<div class='abs-cart-subtotal mt-3 mb-2 text-center pt-2 pb-2'>CART SUBTOTAL: $"+addItemRes['cart']['subtotal']+"</div>";
                                    newItemHtml+="<a href='/shopping/cart' class='cart-link mt-2 mb-2 text-center pt-2 pb-2'>VIEW CART</a>";
                                    newItemHtml+="<a href='/shopping/checkout' class='cart-link mt-2 mb-2 text-center pt-2 pb-2'>CHECKOUT</a>";
                                    @else
                                    newItemHtml+="<div class='abs-cart-subtotal mt-3 mb-2 text-center pt-2 pb-2'>小计: $"+addItemRes['cart']['subtotal']+"</div>";
                                    newItemHtml+="<a href='/shopping/cart' class='cart-link mt-2 mb-2 text-center pt-2 pb-2'>浏览购物车</a>";
                                    newItemHtml+="<a href='/shopping/checkout' class='cart-link mt-2 mb-2 text-center pt-2 pb-2'>结帐</a>";
                                    @endif

                                    $('.abs-cart-list').append(newItemHtml);

                                    $('#cart-counts').text(addItemRes['cart']['itemCounts']);
                                    $('#cart-counts').text(addItemRes['cart']['itemCounts']);
                                    $('#hover-cart-icon').attr('src','/images/icons/item_cart.png');
                                    $('#mobile-cart-counts').text(addItemRes['cart']['itemCounts']);
                                    $('#cart-counts').show();

                                    $("html, body").animate({ scrollTop: 0 },"slow");
                                }
                                else if(addItemRes['status']=='warning'){
                                    alert(addItemRes['msg']);
                                }
                            }
                            catch(err){
                                alert("Something failed, try again later.");    
                            }

                            setTimeout(function(){
                                $('html').removeClass('current-loading');
                                $('#loading-gif').hide();
                            },1500);
                        },
                        error:function(err){
                            alert("Something failed, try again later.");
                        }
                    })
                }
                else{
                    @if($LANGUAGE==='EN')
                    alert("Should put one bottle to cart at least!");
                    @else
                    alert("至少需加入一瓶酒至购物车!")
                    @endif
                }
            }
        }

        function updateItemQty(elem,itemId){
            if(itemId!=""){
                if($('input#itemQty_'+itemId).length>0){
                    var getNewQty=$('input#itemQty_'+itemId).val();
                    if(getNewQty>0){
                        $.ajax({
                            "type":"POST",
                            "url":"/shopping/updateItem",
                            "headers": {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                            "data":{
                                itemId:itemId,
                                itemQuantity:getNewQty
                            },
                            success:function(msg){
                                $('html').addClass('current-loading');
                                $('#loading-gif').show();
                                try{
                                    $('.abs-cart-list').find('.items').remove();
                                    var addItemRes=JSON.parse(msg);
                                    var newItemHtml="";
                                    if(addItemRes['status']=='success'){
                                        for(var i=0;i<addItemRes['cart']['items']['length'];i++){
                                            var iterateId=addItemRes['cart']['items'][i]['id'];
                                            var iterateImg=addItemRes['cart']['items'][i]['image'];
                                            var iterateQty=addItemRes['cart']['items'][i]['quantity'];
                                            var iteratePrice=addItemRes['cart']['items'][i]['price'];
                                            var iterateName=addItemRes['cart']['items'][i]['name'];

                                            newItemHtml+="<div class='item'>";

                                            newItemHtml+="<div class='fixed-delete' onclick=delCartItem('"+iterateId+"')><i class='fas fa-trash-alt'></i></div>";

                                            newItemHtml+="<div class='leftItemImg'>";
                                            newItemHtml+="<img src='"+iterateImg+"' title='cart_"+iterateId+"' alt='cart_"+iterateId+"' />";
                                            newItemHtml+="</div>";

                                            newItemHtml+="<div class='rightItemTool'>";

                                            newItemHtml+="<p class='mt-0 mb-0 itemTitle text-left'>"+iterateName+"</p>";
                                            newItemHtml+="<div class='priceLine mt-1'>";
                                            @if($LANGUAGE==='EN')
                                            newItemHtml+="<label for='cartItemPrice' class='text-left'>Price</label>";
                                            @else
                                            newItemHtml+="<label for='cartItemPrice' class='text-left'>金额</label>";
                                            @endif
                                            newItemHtml+="<div class='cartItemPrice-container text-left' id='cartItemPrice'>"+iteratePrice+"</div>";
                                            newItemHtml+="</div>";

                                            newItemHtml+="<div class='qtyLine mt-1'>";
                                            @if($LANGUAGE==='EN')
                                            newItemHtml+="<label for='itemQty_"+iterateId+"' class='text-left'>Qty</label>";
                                            @else
                                            newItemHtml+="<label for='itemQty_"+iterateId+"' class='text-left'>数量</label>";
                                            @endif
                                            newItemHtml+="<div class='cartItemQty-container row'>";
                                            newItemHtml+="<div class='col-8'>";
                                            newItemHtml+="<input type='number' step=1 min=1 id='itemQty_"+iterateId+"' class='form-control' value='"+iterateQty+"' />";
                                            newItemHtml+="</div>";
                                            newItemHtml+="<div class='col-4'>";
                                            newItemHtml+="<button type='button' class='btn btn-sm btn-primary' onclick=updateItemQty(this,'"+iterateId+"')>OK</button>";
                                            newItemHtml+="</div>";
                                            newItemHtml+="</div>";
                                            newItemHtml+="</div>";

                                            newItemHtml+="</div>";

                                            newItemHtml+="</div>";
                                        }

                                        if($('#noCartItem').css('display')=='block'){
                                            $('#noCartItem').hide();
                                            newItemHtml="<div class='items'>"+newItemHtml+"</div>";
                                        }
                                        else{
                                            $('.abs-cart-subtotal').next('a.cart-link').next('a.cart-link').remove();
                                            $('.abs-cart-subtotal').next('a.cart-link').remove();
                                            $('.abs-cart-subtotal').remove();
                                            $('.abs-cart-list').find('.item').remove();
                                        }

                                        @if($LANGUAGE==='EN')
                                        newItemHtml+="<div class='abs-cart-subtotal mt-3 mb-2 text-center pt-2 pb-2'>CART SUBTOTAL: $"+addItemRes['cart']['subtotal']+"</div>";
                                        newItemHtml+="<a href='/shopping/cart' class='cart-link mt-2 mb-2 text-center pt-2 pb-2'>VIEW CART</a>";
                                        newItemHtml+="<a href='/shopping/checkout' class='cart-link mt-2 mb-2 text-center pt-2 pb-2'>CHECKOUT</a>";
                                        @else
                                        newItemHtml+="<div class='abs-cart-subtotal mt-3 mb-2 text-center pt-2 pb-2'>小计: $"+addItemRes['cart']['subtotal']+"</div>";
                                        newItemHtml+="<a href='/shopping/cart' class='cart-link mt-2 mb-2 text-center pt-2 pb-2'>浏览购物车</a>";
                                        newItemHtml+="<a href='/shopping/checkout' class='cart-link mt-2 mb-2 text-center pt-2 pb-2'>结帐</a>";
                                        @endif
                                        

                                        $('.abs-cart-list').append(newItemHtml);

                                        $('#cart-counts').text(addItemRes['cart']['itemCounts']);
                                        $('#mobile-cart-counts').text(addItemRes['cart']['itemCounts']);
                                        $('#hover-cart-icon').attr('src','/images/icons/item_cart.png');
                                        $('#cart-counts').show();

                                        $("html, body").animate({ scrollTop: 0 },"slow");
                                    }
                                    else if(addItemRes['status']=='warning'){
                                        alert(addItemRes['msg']);
                                    }
                                }
                                catch(err){
                                    alert("Something failed, try again later.");    
                                }

                                setTimeout(function(){
                                    $('html').removeClass('current-loading');
                                    $('#loading-gif').hide();
                                },1500);
                            },
                            error:function(err){
                                alert("Something failed, try again later.");
                            }
                        })
                    }
                    else{
                        alert("Quantity should be bigger than 0.");
                    }
                }
                else{
                    alert("Please check your cart.");
                }
            }
        }

        function addAllWItemsToCart(){
            $('html').addClass('current-loading');
            $('#loading-gif').show();
            $.ajax({
                "method":"GET",
                "url":"/wishlist/add_all_to_order",
                success:function(rtnMsg){
                    window.location="/wishlist";
                },
                error:function(err){
                    if(err.status==401){
                        @if($LANGUAGE==='EN')
                        alert("Please login first!")
                        @else
                        alert("请先登入!")
                        @endif
                        window.location="/login";
                    }
                    else{
                        @if($LANGUAGE==='EN')
                        alert("Oops...Something error!")
                        @else
                        alert("不知名错误，请重试。")
                        @endif   

                        setTimeout(function(){
                            $('html').removeClass('current-loading');
                            $('#loading-gif').hide();
                        },1500);
                    }
                }
            })
        }

        function delCartItem(PId){
            var tempConfirm = "您确定要删除此产品吗?";
            @if($LANGUAGE==='EN')
            tempConfirm = "Are you sure you would like to remove this item?";
            @endif

            if(confirm(tempConfirm)){
                $('#cart-loading-gif').show();
                $.ajax({
                    "method":"GET",
                    "url":"/shopping/ajx_deleteItem/"+PId,
                    success:function(rtnMsg){
                        rtnMsg = JSON.parse(rtnMsg);
                        $('.abs-cart-list').find('.item').remove();
                        $('a.cart-link').remove();
                        $('.abs-cart-subtotal').remove();
                        var newItemHtml="";
                        if(rtnMsg['status']=='success'){
                            for(var i=0;i<rtnMsg['cart']['items']['length'];i++){
                                var iterateId=rtnMsg['cart']['items'][i]['id'];
                                var iterateImg=rtnMsg['cart']['items'][i]['image'];
                                var iterateQty=rtnMsg['cart']['items'][i]['quantity'];
                                var iteratePrice=rtnMsg['cart']['items'][i]['price'];
                                var iterateName=rtnMsg['cart']['items'][i]['name'];

                                newItemHtml+="<div class='item'>";

                                newItemHtml+="<div class='fixed-delete' onclick=delCartItem('"+iterateId+"')><i class='fas fa-trash-alt'></i></div>";

                                newItemHtml+="<div class='leftItemImg'>";
                                newItemHtml+="<img src='"+iterateImg+"' title='cart_"+iterateId+"' alt='cart_"+iterateId+"' />";
                                newItemHtml+="</div>";

                                newItemHtml+="<div class='rightItemTool'>";

                                newItemHtml+="<p class='mt-0 mb-0 itemTitle text-left'>"+iterateName+"</p>";
                                newItemHtml+="<div class='priceLine mt-1'>";
                                @if($LANGUAGE==='EN')
                                newItemHtml+="<label for='cartItemPrice' class='text-left'>Price</label>";
                                @else
                                newItemHtml+="<label for='cartItemPrice' class='text-left'>金额</label>";
                                @endif
                                newItemHtml+="<div class='cartItemPrice-container text-left' id='cartItemPrice'>"+iteratePrice+"</div>";
                                newItemHtml+="</div>";

                                newItemHtml+="<div class='qtyLine mt-1'>";
                                @if($LANGUAGE==='EN')
                                newItemHtml+="<label for='itemQty_"+iterateId+"' class='text-left'>Qty</label>";
                                @else
                                newItemHtml+="<label for='itemQty_"+iterateId+"' class='text-left'>数量</label>";
                                @endif
                                newItemHtml+="<div class='cartItemQty-container row'>";
                                newItemHtml+="<div class='col-8'>";
                                newItemHtml+="<input type='number' step=1 min=1 id='itemQty_"+iterateId+"' class='form-control' value='"+iterateQty+"' />";
                                newItemHtml+="</div>";
                                newItemHtml+="<div class='col-4'>";
                                newItemHtml+="<button type='button' class='btn btn-sm btn-primary' onclick=updateItemQty(this,'"+iterateId+"')>OK</button>";
                                newItemHtml+="</div>";
                                newItemHtml+="</div>";
                                newItemHtml+="</div>";

                                newItemHtml+="</div>";

                                newItemHtml+="</div>";
                            }

                            if(rtnMsg['cart']['items']['length']>0){
                                @if($LANGUAGE==='EN')
                                newItemHtml+="<div class='abs-cart-subtotal mt-3 mb-2 text-center pt-2 pb-2'>CART SUBTOTAL: $"+rtnMsg['cart']['subtotal']+"</div>";
                                newItemHtml+="<a href='/shopping/cart' class='cart-link mt-2 mb-2 text-center pt-2 pb-2'>VIEW CART</a>";
                                newItemHtml+="<a href='/shopping/checkout' class='cart-link mt-2 mb-2 text-center pt-2 pb-2'>CHECKOUT</a>";
                                @else
                                newItemHtml+="<div class='abs-cart-subtotal mt-3 mb-2 text-center pt-2 pb-2'>小计: $"+rtnMsg['cart']['subtotal']+"</div>";
                                newItemHtml+="<a href='/shopping/cart' class='cart-link mt-2 mb-2 text-center pt-2 pb-2'>浏览购物车</a>";
                                newItemHtml+="<a href='/shopping/checkout' class='cart-link mt-2 mb-2 text-center pt-2 pb-2'>结帐</a>";
                                @endif
                                $('#cart-counts').text(rtnMsg['cart']['itemCounts']);

                                newItemHtml="<div class='items'>"+newItemHtml+"</div>";
                                $('.abs-cart-list').append(newItemHtml);
                            }
                            else{
                                $('#noCartItem').show();
                                $('#hover-cart-icon').attr('src','/images/icons/cart.png');
                                $('#cart-counts').hide();
                                $('.abs-cart-list').find('.items').remove();
                            }
                            $('#mobile-cart-counts').text(rtnMsg['cart']['itemCounts']);
                        }
                        else if(rtnMsg['status']=='warning'){
                            alert(rtnMsg['msg']);
                        }
                        $('#cart-loading-gif').hide();
                    },
                    error:function(err){
                        @if($LANGUAGE==='EN')
                        alert("Oops...Something error!")
                        @else
                        alert("不知名错误，请重试。")
                        @endif
                        setTimeout(function(){
                            $('#cart-loading-gif').hide();
                        },1500);
                    }
                });
            }
        }

        if($('.product-carousel').length>0){
            var ProductOwl = $(".product-carousel");
            ProductOwl.on('initialize.owl.carousel', function(){
                // $(this).parent().css({'overflow':'visible'});
                // $(this).removeClass('owl-before')
            });

            $(".product-carousel").owlCarousel(
                {
                    "loop":true,
                    "nav":true,
                    "navText": [
                        "<i class='fas fa-caret-left'></i>","<i class='fas fa-caret-right'></i>"
                    ],
                    "items":3,
                    "slideBy":3,
                    "margin":15,
                    "dots":false,
                    "autoplay":false,                    
                    "responsive":
                    {
                        "0":{"items":1},
                        "951":{"items":2,"slideBy":2},
                        "1301":{"items":3,"slideBy":3}

                    }
                }
            );
        }

        document.addEventListener("touchstart", function(){}, true);
        // language picker. Used jQuery as we're alreay using it.
        var pickerBtn = $(".language--picker");
        var closeBtn = $(".language--close");
        var languageContainer = $(".language--container");
        pickerBtn.addClass('shown');
        pickerBtn.click(function(){
            languageContainer.addClass('shown');
        });

        closeBtn.click(function(){
            languageContainer.removeClass('shown');
        });        
        </script>
        @if(\Session::has('system_message_flag'))
        <script>
            @if(\Session::has('system_message'))
        let swalObj={"text":"{!!\Session::get('system_message')!!}","customClass":"swal-wide"};
            @endif
            @if(\Session::get('system_message_status')=="success")
            swalObj['type']='success';
            swalObj['title']='Finished';
            @elseif(\Session::get('system_message_status')=="warning")
            swalObj['type']='warning';
            swalObj['title']='Warning';        
            @else
            swalObj['type']='error';
            swalObj['title']='Oops...';
            @endif
        Swal.fire(swalObj);
        </script>
        @endif
    </body>
</html>