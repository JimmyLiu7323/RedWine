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
@endsection
@section('content')
<style type="text/css">
.post-entry-2 .contents h2{font-size:20px}
.post-entry-2 .thumbnail{min-height:200px}
</style>
<div class="site-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-9">
                <div class="section-title">
                    <span class="caption d-block small">Tag</span>
                    <h2>{{$tagText}}</h2>
                </div>

                @foreach($articles as $article)
                <div class="post-entry-2 d-flex">
                    <div class="contents order-md-1 pl-0">
                        <h2><a href="/article/{{$article->ArticleId}}">{{$LANGUAGE==='EN' && trim($article->TitleEn)!=='' ? $article->TitleEn : $article->Title}}</a></h2>
                        <p class="mb-3">{{$LANGUAGE==='EN' && trim($article->BriefDescEn)!=='' ? mb_substr($article->BriefDescEn,0,150) : mb_substr($article->BriefDesc,0,150)}}</p>
                        <div class="post-meta">
                            <span class="date-read">{{$article->OnDate}}</span>
                        </div>
                    </div>
                </div>
                @endforeach
                @if($articles->count()>0)
                {{ $articles->appends(request()->query())->links() }}
                @endif
            </div>
            <div class="col-lg-3">
                <div class="section-title">
                    <h2>{{$LANGUAGE==='EN'?'Popular Posts':'热门文章'}}</h2>
                </div>
                @foreach($popular_posts as $popular_postIdx=>$popular_post)
                <div class="trend-entry d-flex">
                    <div class="number align-self-start">0{{$popular_postIdx+1}}</div>
                    <div class="trend-contents">
                        <h2><a href="/article/{{$popular_post->ArticleId}}">{{$LANGUAGE==='EN' && trim($popular_post->TitleEn)!=='' ? $popular_post->TitleEn : $popular_post->Title}}</a></h2>
                        <div class="post-meta">
                            <span class="date-read">{{$popular_post->OnDate}}</span>
                        </div>
                    </div>
                </div>
                @endforeach
                <p>
                    <a href="#" class="more" style="color:#9c8c3a">{{$LANGUAGE==='EN'?'See All Popular':'浏览所有热门文章'}} <span class="icon-keyboard_arrow_right"></span></a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection