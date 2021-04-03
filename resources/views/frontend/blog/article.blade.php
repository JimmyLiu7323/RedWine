@extends('frontend.layouts._main')

@section('metas')
@if($article->BriefDesc!='')
<meta name="description" content="{{$article->BriefDesc}}"/>
<meta property="og:description" content="{{$article->BriefDesc}}" />
@elseif($METAs['META_DESCRIPTION']!=='')
<meta name="description" content="{{$METAs['META_DESCRIPTION']}}"/>
<meta property="og:description" content="{{$METAs['META_DESCRIPTION']}}" />
@endif
@if($METAs['META_KEYWORDS']!=='')
<meta name="keywords" content="{{$METAs['META_KEYWORDS']}}"/>
@endif
@endsection

@section('facebook_metas')
<meta property="og:site_name" content="{{$DCs['DC_Title']}}" />
<meta property="og:title" content="{{$DCs['DC_Title']}} - {{$article->Title}}" />
@php
$impactImageExist=false;
if($article->Image){
    $impactImageExist=file_exists(public_path($article->Image));
}
@endphp

@if($impactImageExist)
<meta property="og:image" content="{!! asset($article->Image) !!}" />
@else
<meta property="og:image" content="{!! asset('images/default_articleImage.jpg') !!}" />
@endif
@endsection

@section('content')
<div class="site-section" style="padding-bottom:0">
    <div class="container" style="min-height:calc(100vh - 543.5px - 5em)">
        <div class="row">
            <div class="col-lg-8 single-content">                
                <h2 class="mb-4">{{$article->Title}}</h2>
                <div class="post-meta d-flex mb-5">
                    <span class="date-read">{{$article->OnDate}}</span>
                </div>
                {!! $article->Content !!}
                <div class="pt-5">
                    <p>Categories:  <a href="/blog/category/{{$article->CategoryId}}" style="color:#9c8c3a">{{$article->Category}}</a>  Tags:
                    @php
                    $cutTags = explode(",",$article->Tags);
                    @endphp
                    @foreach($cutTags as $tagIdx=>$tag)
                    <a href="/blog/tags/{{$tag}}" style="color:#9c8c3a">#{{$tag}}</a>
                        @if($tagIdx!=count($cutTags)-1)
                        ,
                        @endif
                    @endforeach
                    </p>
                </div>
            </div>

        <div class="col-lg-3 ml-auto">
            <div class="section-title">
                <h2>{{$LANGUAGE==='EN'?'Popular Posts':'热门文章'}}</h2>
            </div>
            @foreach($popular_posts as $popular_postIdx=>$popular_post)
            <div class="trend-entry d-flex">
                <div class="number align-self-start">0{{$popular_postIdx+1}}</div>
                <div class="trend-contents">
                    <h2><a href="/article/{{$popular_post->ArticleId}}">{{$LANGUAGE==='EN' && trim($popular_post->TitleEn)!=='' ? $popular_post->TitleEn : $popular_post->Title}}</a></h2>
                    <div class="post-meta">
                        <span class="d-block">
                            @if($LANGUAGE==='EN'&&trim($popular_post->CategoryEn)!=='')
                            <a href="/blog/category/{{$popular_post->CategoryId}}">{{$popular_post->CategoryEn}}</a>
                            @else
                            <a href="/blog/category/{{$popular_post->CategoryId}}">{{$popular_post->Category}}</a>
                            @endif
                        </span>
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
@endsection