<div class="sidebar-menu">
    <ul class="sidebar-nav">
        <li {{($currentPage=='dashboard')?'class=active':''}}>
            <a href="/admin">
                <div class="icon">
                    <i class="fa fa-tasks" aria-hidden="true"></i>
                </div>
                <div class="title">Overview</div>
            </a>
        </li>
        <li {{($currentPage=='banners')?'class=active':''}}>
            <a href="/admin/banners">
                <div class="icon">
                    <i class="fa fa-flag" aria-hidden="true"></i>
                </div>
                <div class="title">Banners</div>
            </a>
        </li>
        <li class="dropdown {{($currentPage==='products')?'active':''}}">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <div class="icon">
                    <i class="fa fa-glass" aria-hidden="true"></i>
                </div>
                <div class="title">Product Manage</div>
            </a>
            <div class="dropdown-menu">
                <ul>
                    <li>
                        <a href="/admin/products/basic_category">Basic category</a>
                    </li>                    
                    <li>
                        <a href="/admin/products/cases">Cases</a>
                    </li>
                    <li>
                        <a href="/admin/products/mix">Sale Topic</a>
                    </li>                    
                    <li>
                        <a href="/admin/products/varieties">Variety List</a>
                    </li>
                    <li>
                        <a href="/admin/products/colours">Color List</a>
                    </li>
                    <li>
                        <a href="/admin/products/regions">Region List</a>
                    </li>
                    <li>
                        <a href="/admin/products/styles">Style List</a>
                    </li>
                    <li>
                        <a href="/admin/products/closures">Packing Method</a>
                    </li>
                    <li>
                        <a href="/admin/products/caterings">Catering List</a>
                    </li>
                    <li>
                        <a href="/admin/products">Product Manage</a>
                    </li>
                    <li>
                        <a href="/admin/products/options">Product Options</a>
                    </li>
                </ul>
            </div>          
        </li>
        <li class="dropdown {{($currentPage==='gifts')?'active':''}}">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <div class="icon">
                    <i class="fa fa-gift" aria-hidden="true"></i>
                </div>
                <div class="title">Gift</div>
            </a>
            <div class="dropdown-menu">
                <ul>
                    <li>
                        <a href="/admin/gifts/banners">Banners (Index in gifts)</a>
                    </li>
                    <li>
                        <a href="/admin/gifts/business-image">Business image (Index in gifts)</a>
                    </li>
                    <li>
                        <a href="/admin/gifts/categories">Categories</a>
                    </li>
                    <li>
                        <a href="/admin/gifts/list">Gifts</a>
                    </li>
                </ul>
            </div>          
        </li>
        <li class="dropdown {{($currentPage==='shopping')?'active':''}}">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <div class="icon">
                    <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                </div>
                <div class="title">Shop</div>
            </a>
            <div class="dropdown-menu">
                <ul>
                    <li>
                        <a href="/admin/shopping/orders">Orders</a>
                    </li>
                    <li>
                        <a href="/admin/shopping/parameters">Parameters</a>
                    </li>
                </ul>
            </div>          
        </li>
        <li @if($currentPage==='members')class='active'@endif>
            <a href="/admin/members">
                <div class="icon">
                    <i class="fa fa-users" aria-hidden="true"></i>
                </div>
                <div class="title">Members</div>
            </a>
        </li>
        <li class="dropdown {{($currentPage==='blog')?'active':''}}">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <div class="icon">
                    <i class="fa fa-rss" aria-hidden="true"></i>
                </div>
                <div class="title">Blog</div>
            </a>
            <div class="dropdown-menu">
                <ul>
                    <li>
                        <a href="/admin/blog/category">Category</a>
                    </li>
                    <li>
                        <a href="/admin/blog/articles">Articles</a>
                    </li>
                </ul>
            </div>          
        </li>
        <li class="dropdown {{($currentPage==='exhibition')?'active':''}}">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <div class="icon">
                    <i class="fa fa-calendar" aria-hidden="true"></i>
                </div>
                <div class="title">Exhibition</div>
            </a>
            <div class="dropdown-menu">
                <ul>
                    <li>
                        <a href="/admin/exhibition/image">Impact Image</a>
                    </li>
                    <li>
                        <a href="/admin/exhibition/events">Events</a>
                    </li>
                    <li>
                        <a href="/admin/exhibition/visits">Visits</a>
                    </li>
                </ul>
            </div>          
        </li>        
        <li class="dropdown {{($currentPage==='template')?'active':''}}">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <div class="icon">
                    <i class="fa fa-file-text-o" aria-hidden="true"></i>
                </div>
                <div class="title">Template</div>
            </a>
            <div class="dropdown-menu">
                <ul>
                    <li>
                        <a href="/admin/template/global_notice">Global notice</a>
                    </li>
                    <li>
                        <a href="/admin/template/m_feature">Featured image(Menu)</a>
                    </li>
                    <li>
                        <a href="/admin/template/index_featured_image">Featured image(Index Page)</a>
                    </li>
                    <li>
                        <a href="/admin/template/index_featured_video">Featured video(Index Page)</a>
                    </li>
                    <li>
                        <a href="/admin/template/index_slogan">Small slogan(Index Page)</a>
                    </li>
                    <li>
                        <a href="/admin/template/index_guarantee">Guarantees(Index Page)</a>
                    </li>
                    <li>
                        <a href="/admin/template/license">License</a>
                    </li>
                    <li>
                        <a href="/admin/template/case-deals">Case Deals Introduction</a>
                    </li>
                </ul>
            </div>          
        </li>        
        <li class="dropdown {{$currentPage==='sys_setting'?'active':''}}">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <div class="icon">
                    <i class="fa fa-cogs" aria-hidden="true"></i>
                </div>
                <div class="title">System Manage</div>
            </a>
            <div class="dropdown-menu">
                <ul>
                    <li>
                        <a href="/admin/system/self">Admin-Info Manage</a>
                    </li>
                    <li>
                        <a href="/admin/system/countries">Countries</a>
                    </li>
                </ul>
            </div>          
        </li>
        <li class="dropdown {{$currentPage==='footer_info'?'active':''}}">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <div class="icon">
                    <i class="fa fa-info" aria-hidden="true"></i>
                </div>
                <div class="title">Footer info</div>
            </a>
            <div class="dropdown-menu">
                <ul>
                    <li>
                        <a href="/admin/footer_info/guarantee">Our Guarantee</a>
                    </li>
                    <li>
                        <a href="/admin/footer_info/privacy_policy">Privacy Policy</a>
                    </li>
                    <li>
                        <a href="/admin/footer_info/corporate_services">Coporate Wine Services</a>
                    </li>
                    <li>
                        <a href="/admin/footer_info/awards">Awards</a>
                    </li>
                </ul>
            </div>            
        </li>
        <li class="dropdown {{$currentPage==='webinfo'?'active':''}}">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <div class="icon">
                    <i class="fa fa-product-hunt" aria-hidden="true"></i>
                </div>
                <div class="title">Website Info</div>
            </a>
            <div class="dropdown-menu">
                <ul>
                    <li>
                        <a href="/admin/webinfo/basic_seo?lang=zh">SEO Structure(Chinese)</a>
                    </li>
                    <li>
                        <a href="/admin/webinfo/basic_seo?lang=en">SEO Structure(English)</a>
                    </li>
                    <li>
                        <a href="/admin/webinfo/company">Company</a>
                    </li>
                </ul>
            </div>            
        </li>
        <li>
            <a href="javascript:void(0)" class="popup_selector">
                <div class="icon">
                    <i class="fa fa-image" aria-hidden="true"></i>
                </div>
                <div class="title">Img Library</div>
            </a>
        </li>
    </ul>
</div>