@extends('frontend.layouts._main')

@section('metas')
@if($METAs['META_DESCRIPTION']!=='')
<meta name="description" content="{{$METAs['META_DESCRIPTION']}}"/>
<meta property="og:description" content="{{$METAs['META_DESCRIPTION']}}" />
@endif
@if($METAs['META_KEYWORDS']!=='')
<meta name="keywords" content="{{$METAs['META_KEYWORDS']}}"/>
@endif
@endsection

@section('facebook_metas')
<meta property="og:site_name" content="{{$DCs['DC_Title']}}" />
<meta property="og:title" content="{{$DCs['DC_Title']}}" />
@if($METAs['META_OG_IMAGE']!=='')
<meta property="og:image" content="{!! asset('uploads/'.$METAs['META_OG_IMAGE']) !!}" />
@endif
@endsection

@section('content')
<style type="text/css">
.owl-carousel .owl-stage-outer{padding-left:10px}
</style>
@if(count($banners)>0)
<div class="swiper-container container intro-section">
    <div class="swiper-wrapper">
    @foreach($banners as $banner)
        @if($banner->BannerImage)
            @php $bgImage=asset($banner->BannerImage); @endphp        
        <div class="swiper-slide index-banner" {{'style=background-image:url("'.$bgImage.'")'}}>
            <div class="row justify-content-center text-left">
            @if($banner->BannerSubtitle || $banner->BannerTitle)
                <div class="col-md-8" style="padding-top:150px">
                    @if($banner->BannerSubtitle)
                    <span class="sub-title">{{$banner->BannerSubtitle}}</span>
                    @endif
                    @if($banner->BannerTitle)
                    <p>{{$banner->BannerTitle}}</p>
                    @endif
                </div>
            @endif
            </div>
        </div>
        @endif
    @endforeach
    </div>
    <div class="swiper-pagination"></div>
</div>
@endif

@if(!empty($weekly_recommendations['wines']) || !empty($weekly_recommendations['topics']))
<div class="mt-5 mb-3">
    <div class="container">
        <div class="row mb-3">
            <div class="col-12 text-center">
                <h2 class="weeklyRecosTitle">{{$LANGUAGE==='EN'?'WEEKLY RECOMMEND':'当周推荐'}}</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-11 col-10 product-carousel product-featured-carousel owl-carousel owl-theme ml-auto mr-auto">
            @foreach($weekly_recommendations['topics'] as $wine)
                @php
                $winePicExist=false;
                if($wine->Image){
                    $setWinePic=public_path($wine->Image);
                    $winePicExist=file_exists($setWinePic);
                }
                $winePic=asset($wine->Image);
                @endphp
                <div>
                    <div class="top-info">
                        <a href="/case/{{$wine->CaseId}}" class="product-image">
                            <div class="crop">
                                @if($winePicExist)
                                <img src="{{$winePic}}" alt="Image" class="img-fluid">
                                @else
                                <img src="{!! asset('images/default_casePic.jpeg') !!}" alt="Image" class="img-fluid">
                                @endif
                            </div>
                        </a>
                        <div class="wine-info product-desc-box" style="height:273px">
                            @if($LANGUAGE==='EN'&&trim($wine->MixNameEn)!=='')
                            <h3 class="heading mb-1"><a target="_blank" href="/case/{{$wine->CaseId}}">{{$wine->MixNameEn}}</a></h3>
                            @else
                            <h3 class="heading mb-1"><a target="_blank" href="/case/{{$wine->CaseId}}">{{$wine->MixName}}</a></h3>
                            @endif
                            <!-- <div class="critic-results">
                                <span class="critic-score">
                                    <img src="https://www.caros.co.nz/media/option_image/points-icons-92.png" alt="92 Points">
                                </span>
                                <span class="critic-name">John Caro</span>
                            </div> -->
                            <div class="product-short-description">
                                @if($LANGUAGE==='EN'&&trim($wine->BriefDescEn)!=='')
                                <span>{{mb_substr($wine->BriefDescEn,0,90)}} ... </span>
                                @elseif(trim($wine->BriefDesc)!=='')
                                <span>{{mb_substr($wine->BriefDesc,0,90)}} ... </span>
                                @endif
                            </div>
                            <div class="mobile-readmore-link">
                                <a href="/case/{{$wine->CaseId}}" title="Read more about this wine" class="link-learn">Read More</a>
                            </div>
                        </div>
                    </div>
                    <div class="weekly-price-box">
                        <span class="regular-price">
                            @if(isset($wine->Option))
                                @if($LANGUAGE==='EN')
                            <span class="mixed-price"><span class="price">${{$wine->Option->Price}}</span> <span class="mixed-label">in mixed {{$wine->Option->PurchaseAmount}}+</span></span>
                                @else
                            <span class="mixed-price"><span class="price">${{$wine->Option->Price}}</span> <span class="mixed-label">當次购买{{$wine->Option->PurchaseAmount}}瓶以上</span></span>                                
                                @endif
                            <span class="main-product-price has-case-price">{{$LANGUAGE==='EN'?'or':'或'}} <span class="price">@if($wine->S_price){{'$'.number_format($wine->S_price,2)}}@else{{'$'.number_format($wine->Price,2)}}@endif</span><span class="item-price-label"> {{$LANGUAGE==='EN'?'per bottle':'每瓶'}}</span></span>
                            @else
                            <span class="mixed-price"><span class="price">@if($wine->S_price){{'$'.number_format($wine->S_price,2)}}@else{{'$'.number_format($wine->Price,2)}}@endif</span>  <span class="mixed-label">{{$LANGUAGE==='EN'?'per bottle':'每瓶'}}</span></span>
                            @endif
                        </span>
                    </div>
                    <div class="actions">
                        <div>
                            <input type="number" class="disable-owl-swipe weeklyItemQty" maxlength="12" value="1" title="Qty">
                        </div>
                        <button type="button" title="Add to Order" class="button btn-cart" onclick="quick_addWeeklyItem('mix',this,'{{$wine->MixId}}')" />
                            <span><span>{{$LANGUAGE==='EN'?'Buy Now':'现在购买'}}</span></span>
                        </button>
                    </div>                    
                </div>
            @endforeach

            @foreach($weekly_recommendations['wines'] as $wine)
                @php
                $winePicExist=false;
                if($wine->Image){
                    $setWinePic=public_path($wine->Image);
                    $winePicExist=file_exists($setWinePic);
                }
                $winePic=asset($wine->Image);
                @endphp
                <div>
                    <div class="top-info">
                        <a href="/wine/{{$wine->WineId}}" class="product-image">
                            <div class="crop">
                                @if($winePicExist)
                                <img src="{{$winePic}}" alt="Image" class="img-fluid">
                                @else
                                <img src="{!! asset('images/wine_1.png') !!}" alt="Image" class="img-fluid">
                                @endif
                            </div>
                        </a>
                        <div class="wine-info product-desc-box" style="height:273px">
                            @if($LANGUAGE==='EN'&&trim($wine->NameEn)!=='')
                            <h3 class="heading mb-1"><a target="_blank" href="/wine/{{$wine->WineId}}">{{$wine->NameEn}}</a></h3>
                            @else
                            <h3 class="heading mb-1"><a target="_blank" href="/wine/{{$wine->WineId}}">{{$wine->Name}}</a></h3>
                            @endif
                            <!-- <div class="critic-results">
                                <span class="critic-score">
                                    <img src="https://www.caros.co.nz/media/option_image/points-icons-92.png" alt="92 Points">
                                </span>
                                <span class="critic-name">John Caro</span>
                            </div> -->
                            <div class="product-short-description">
                                @if($LANGUAGE==='EN'&&trim($wine->BriefDescEn)!=='')
                                <span>{{mb_substr($wine->BriefDescEn,0,90)}} ... </span>
                                @elseif(trim($wine->BriefDesc)!=='')
                                <span>{{mb_substr($wine->BriefDesc,0,90)}} ... </span>
                                @endif
                            </div>
                            <div class="mobile-readmore-link">
                                <a href="/wine/{{$wine->WineId}}" title="Read more about this wine" class="link-learn">Read More</a>
                            </div>
                        </div>
                    </div>
                    <div class="weekly-price-box">
                        <span class="regular-price">
                            @if(isset($wine->Option))
                                @if($LANGUAGE==='EN')
                            <span class="mixed-price"><span class="price">${{$wine->Option->Price}}</span> <span class="mixed-label">in mixed {{$wine->Option->PurchaseAmount}}+</span></span>
                                @else
                            <span class="mixed-price"><span class="price">${{$wine->Option->Price}}</span> <span class="mixed-label">當次购买{{$wine->Option->PurchaseAmount}}瓶以上</span></span>                                
                                @endif
                            <span class="main-product-price has-case-price">{{$LANGUAGE==='EN'?'or':'或'}} <span class="price">@if($wine->S_price){{'$'.number_format($wine->S_price,2)}}@else{{'$'.number_format($wine->Price,2)}}@endif</span><span class="item-price-label"> {{$LANGUAGE==='EN'?'per bottle':'每瓶'}}</span></span>
                            @else
                            <span class="mixed-price"><span class="price">@if($wine->S_price){{'$'.number_format($wine->S_price,2)}}@else{{'$'.number_format($wine->Price,2)}}@endif</span>  <span class="mixed-label">{{$LANGUAGE==='EN'?'per bottle':'每瓶'}}</span></span>
                            @endif
                        </span>
                    </div>
                    <div class="actions">
                        <div>
                            <input type="number" class="disable-owl-swipe weeklyItemQty" maxlength="12" value="1" title="Qty">
                        </div>
                        <button type="button" title="Add to Order" class="button btn-cart" onclick="quick_addWeeklyItem('wine',this,'{{$wine->WineId}}')" />
                            <span><span>{{$LANGUAGE==='EN'?'Buy Now':'现在购买'}}</span></span>
                        </button>
                    </div>                    
                </div>
            @endforeach
            </div>         
        </div>
        <div class="row mt-4 mb-4">
            <div class="col-12 week-view-all">
                <a href="/wines"><span class="icon-play_arrow"></span> {{$LANGUAGE==='EN'?'View All of Products':'浏览所有商品'}}</a>
            </div>
        </div>
        @if($index_featured_images->count()>0)
            @php
            $featureLgSize = 12 / $index_featured_images->count();
            $featureSmSize = 12;
            if( intval($index_featured_images->count()) === 3)
                $featureSmSize = 6;
            elseif(intval($index_featured_images->count()) === 2)
                $featureSmSize = 12;
            @endphp
        <div class="row widget widget-static-block mt-2">
            <div class="col-12">
                <div class="feature-blocks">
                    <div class="row">
            @foreach($index_featured_images as $index_featured_image)
                @if(intval($index_featured_image->SN)===3)
                        <div class="col-lg-{{$featureLgSize}} col-12 feature-block mb-2" @if(intval($index_featured_images->count()) === 1) style="max-height:290px" @endif>
                @else
                        <div class="col-lg-{{$featureLgSize}} col-sm-{{$featureSmSize}} col-12 feature-block mb-2" @if(intval($index_featured_images->count()) === 1) style="max-height:290px" @endif>
                @endif

                @if( $LANGUAGE==='EN' && trim($index_featured_image->LinkFileEn)!=='' )
                            <a href="{!! asset($index_featured_image->LinkFileEn) !!}">
                @else
                            <a href="{!! asset($index_featured_image->LinkFile) !!}">
                @endif
                                <code>
                                    <div class="feature-block-overlay">
                                        <h4>{{$LANGUAGE==='EN'&&trim($index_featured_image->BigTitleEn)!==''?$index_featured_image->BigTitleEn:$index_featured_image->BigTitle}}</h4>
                                        <p>{{$LANGUAGE==='EN'&&trim($index_featured_image->DescriptionEn)!==''?$index_featured_image->DescriptionEn:$index_featured_image->Description}}</p>
                                        <span>+</span>
                                    </div>
                                    <p>
                                        @if($LANGUAGE==='EN'&&trim($index_featured_image->ImageEn)!=='')
                                        <img class="img-fluid" alt="{{$LANGUAGE==='EN' && trim($index_featured_image->BigTitleEn)!==''?$index_featured_image->BigTitleEn:$index_featured_image->BigTitle}}" src="{!! asset($index_featured_image->ImageEn) !!}">
                                        @else
                                        <img class="img-fluid" alt="{{$LANGUAGE==='EN'&&trim($index_featured_image->BigTitleEn)!==''?$index_featured_image->BigTitleEn:$index_featured_image->BigTitle}}" src="{!! asset($index_featured_image->Image) !!}">
                                        @endif
                                    </p>
                                </code>
                            </a>
                        </div>
            @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if(intval($display_index_slogan)===1)
        <div class="row">
            <div class="col-12">
                <div class="index-page-small-slogan">
                    <h5 class="mt-0 mb-0 text-center">{{$LANGUAGE==='EN'&&trim($index_slogan_en)!==''?$index_slogan_en:$index_slogan}}</h5>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endif

<div class="site-section">
    <div class="container">
        @if(intval($display_index_featured_video)===0)
        <div class="row">
            <div class="col-12">
                <h2 class="blog-section-title">{{$LANGUAGE==='EN'?'BLOG WINES':'推荐文章'}}</h2>
                <p class="blog-video-view-all"><a href="/blog/">{{$LANGUAGE==='EN'?'View All':'浏览全部'}}</a></p>
            </div>
        </div>        
        <div class="row">        
            @foreach($articles as $article)
                @php
                $impactImageExist=false;
                if($article->Image){
                    $impactImageExist=file_exists(public_path($article->Image));
                }
                @endphp
            <div class="col-12">
                <article class='blog-article-item'>
                    <div class="latest-blog-img">
                        <a href="/article/{{$article->ArticleId}}">
                            @if($impactImageExist)
                            <img src="{!! asset($article->Image) !!}" alt="{{$LANGUAGE==='EN' && trim($article->TitleEn)!=='' ? $article->TitleEn.'Impact Image' : $article->Title.'形象照片'}}" title="{{$LANGUAGE==='EN' && trim($article->TitleEn)!=='' ? $article->TitleEn.'Impact Image' : $article->Title.'形象照片'}}">
                            @else
                            <img src="{!! asset('images/default_articleImage.jpg') !!}" alt="{{$LANGUAGE==='EN' && trim($article->TitleEn)!=='' ? $article->TitleEn.'Impact Image' : $article->Title.'形象照片'}}" title="{{$LANGUAGE==='EN' && trim($article->TitleEn)!=='' ? $article->TitleEn.'Impact Image' : $article->Title.'形象照片'}}">
                            @endif
                        </a>
                    </div>
                    <div class="latest-blog-text">
                        <h3><a href="/article/{{$article->ArticleId}}">{{$LANGUAGE==='EN' && trim($article->TitleEn)!=='' ? $article->TitleEn:$article->Title}}</a></h3>
                        @php
                        $articleBrief=$article->BriefDesc;
                        if($LANGUAGE==='EN' && trim($article->BriefDescEn)!=='' ){
                            $articleBrief=$article->BriefDescEn;
                        }
                        if(mb_strlen($articleBrief)>300){
                            $articleBrief = mb_substr($articleBrief,0,300);
                            $articleBrief.="...";
                        }
                        @endphp
                        <div><p>{{$articleBrief}}</p></div>
                        <div class="latest-blog-read-more"><a href="/article/{{$article->ArticleId}}" title="Read the rest">Read More</a></div>
                    </div>
                </article>
            </div>
            @endforeach
        </div>            
        @else
        <div class="row">
            <div class="col-lg-8 col-12" style="position:relative">
                <div class="row">
                    <div class="col-12">
                        <h2 class="blog-section-title">{{$LANGUAGE==='EN'?'BLOG WINES':'推荐文章'}}</h2>
                        <p class="blog-video-view-all"><a href="/blog/">{{$LANGUAGE==='EN'?'View All':'浏览全部'}}</a></p>
                    </div>
                </div>
                <div class="row">
                @foreach($articles as $article)
                    @php
                    $impactImageExist=false;
                    if($article->Image){
                        $impactImageExist=file_exists(public_path($article->Image));
                    }
                    @endphp
                    <div class="col-12">
                        <article class='blog-article-item'>
                            <div class="latest-blog-img">
                                <a href="/article/{{$article->ArticleId}}">
                                    @if($impactImageExist)
                                    <img src="{!! asset($article->Image) !!}" alt="{{$LANGUAGE==='EN' && trim($article->TitleEn)!=='' ? $article->TitleEn.'Impact Image' : $article->Title.'形象照片'}}" title="{{$LANGUAGE==='EN' && trim($article->TitleEn)!=='' ? $article->TitleEn.'Impact Image' : $article->Title.'形象照片'}}">
                                    @else
                                    <img src="{!! asset('images/default_articleImage.jpg') !!}" alt="{{$LANGUAGE==='EN' && trim($article->TitleEn)!=='' ? $article->TitleEn.'Impact Image' : $article->Title.'形象照片'}}" title="{{$LANGUAGE==='EN' && trim($article->TitleEn)!=='' ? $article->TitleEn.'Impact Image' : $article->Title.'形象照片'}}">
                                    @endif
                                </a>
                            </div>
                            <div class="latest-blog-text">
                                <h3><a href="/article/{{$article->ArticleId}}">{{$LANGUAGE==='EN' && trim($article->TitleEn)!=='' ? $article->TitleEn:$article->Title}}</a></h3>
                                @php
                                $articleBrief=$article->BriefDesc;
                                if($LANGUAGE==='EN' && trim($article->BriefDescEn)!=='' ){
                                    $articleBrief=$article->BriefDescEn;
                                }
                                if(mb_strlen($articleBrief)>150){
                                    $articleBrief = mb_substr($articleBrief,0,150);
                                    $articleBrief.="...";
                                }
                                @endphp
                                <div><p>{{$articleBrief}}</p></div>
                                <div class="latest-blog-read-more"><a href="/article/{{$article->ArticleId}}" title="Read the rest">Read More</a></div>
                            </div>
                        </article>
                    </div>
                @endforeach
                </div>
            </div>
            <div class="col-lg-4 col-12 featured-video">
                <h2>{{$LANGUAGE==='EN'?'Featured Video':'精选影片'}}</h2>
                <p class="video-info">{{$LANGUAGE==='EN' && trim($index_featured_video_description_en)!=='' ? trim($index_featured_video_description_en) : $index_featured_video_description}}</p>
                @if(trim($index_featured_video_source==='Youtube'))
                <div class="video-container">{!! $index_featured_video_url !!}</div>
                @else
                @endif
            </div>
        </div>
        @endif

        @if( intval($display_index_guarantee_image)===1 || intval($display_index_promotion_image)===1 )
        <div class="row">
            <div class="col-12">
                <div class="meta-blocks">
                    <div class="row">
                        @if(intval($display_index_guarantee_image)===1)
                        <div class="col-6">
                            <code>
                                <p>
                                    <img src="{!! asset($index_guarantee_image) !!}" alt="Wine Cave's Guarantee">
                                </p>
                            </code>
                        </div>
                        @endif
                        @if(intval($display_index_promotion_image)===1)
                        <div class="col-6">
                            <code>
                                <img src="{!! asset($index_promotion_image) !!}" alt="Wine Cave's Promotion">
                            </code>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection