<?php
namespace App\Http\Controllers\Frontend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\BlogData;
use Session;

class BlogController extends Controller
{                              
    public function __construct(){
        parent::__construct();
    }

    public function blog($category=false,Request $request){
        $originalWhere=array(
            array('OnDate','<=',date('Y-m-d')),
            array('OffDate','>',date('Y-m-d')),
            array('Status','=',1)
        );

        $queryCatg=false;
        if((bool)$category!==false){
            $category=intval($category);
            array_push($originalWhere,array('blogs.Category','=',$category));

            $queryCatg=DB::table('blog_category')->where('CategoryId',$category)->take(1)->first();
        }
        $articles=BlogData::leftJoin('blog_category','blogs.Category','=','blog_category.CategoryId')->where($originalWhere)->orderBy('Flagship','DESC')->orderBy('OnDate','DESC')->orderBy('OffDate','DESC')->paginate(10);

        $data['category']=$queryCatg;
        $data['articles']=$articles;

        // Popular posts
        $popular_posts=BlogData::leftJoin('blog_category','blogs.Category','=','blog_category.CategoryId')->where(array(
            array('Status','=',1),
            array('OnDate','<=',date('Y-m-d')),
            array('OffDate','>',date('Y-m-d'))
        ))->orderBy('ClickCounts','DESC')->take(5)->get();
        $data['popular_posts']=$popular_posts;

        $data['htmlTitle']=Session::get('Language')==='EN'?'Blog':'部落格';
        return view('frontend.blog.articles',$data);
    }

    public function article($articleId,Request $request){
        // add click counts
        $accessIP=$request->ip();
        if(!$existRow=DB::table('blogreadlog')->where('ArticleId',$articleId)->where('AccessIP',$accessIP)->where('created_at','>',date('Y-m-d 00:00:00'))->where('created_at','<=',date('Y-m-d 23:59:59'))->take(1)->first()){
            DB::table('blogreadlog')->insert(array(
                'ArticleId'=>$articleId,
                'AccessIP'=>$accessIP,
                'created_at'=>date('Y-m-d H:i:s')
            ));
            BlogData::where('ArticleId',$articleId)->update(array(
                'ClickCounts'=>DB::raw('ClickCounts+1')
            ));
        }

        $article=BlogData::leftJoin('blog_category','blogs.Category','=','blog_category.CategoryId')->where(array(
            array('ArticleId','=',$articleId),
            array('Status','=',1),
            array('OnDate','<=',date('Y-m-d')),
            array('OffDate','>',date('Y-m-d'))
        ))->take(1)->first();
        if($article){
            if(Session::get('Language')==='EN'){
                if(trim($article->TitleEn)!==''){
                    $article->Title = $article->TitleEn;
                }
                if(trim($article->BriefDescEn)!==''){
                    $article->BriefDesc = $article->BriefDescEn;;
                }
                if(trim($article->TagsEn)!==''){
                    $article->Tags = $article->TagsEn;
                }
                if(trim($article->ContentEn)!==''){
                    $article->Content = $article->ContentEn;
                }

                if(trim($article->CategoryEn)!==''){
                    $article->Category = $article->CategoryEn;
                }
                else{
                    if(!$article->Category){
                        $article->Category = 'Not Specify';
                    }
                }
            }

            $data['article']=$article;
            $data['htmlTitle']=Session::get('Language')==='EN' && trim($article->TitleEn)!=='' ? $article->TitleEn : $article->Title;

            // Popular posts
            $popular_posts=BlogData::leftJoin('blog_category','blogs.Category','=','blog_category.CategoryId')->where(array(
                array('Status','=',1),
                array('OnDate','<=',date('Y-m-d')),
                array('OffDate','>',date('Y-m-d'))
            ))->orderBy('ClickCounts','DESC')->take(5)->get();
            $data['popular_posts']=$popular_posts;

            return view('frontend.blog.article',$data);
        }
        return redirect('/blog');
    }

    public function tagSearch($tagText){
        $tagText=trim($tagText);
        if($tagText!==''){
            $articles=BlogData::leftJoin('blog_category','blogs.Category','=','blog_category.CategoryId')->where(function($query) use ($tagText){
                if(Session::get('Language')==='EN'){
                    return $query->where('TagsEn','LIKE',$tagText)->orWhere('TagsEn','LIKE',$tagText.",%")->orWhere('TagsEn','LIKE',"%,".$tagText.",%")->orWhere('TagsEn','LIKE','%,'.$tagText);
                }
                else{
                    return $query->where('Tags','LIKE',$tagText)->orWhere('Tags','LIKE',$tagText.",%")->orWhere('Tags','LIKE',"%,".$tagText.",%")->orWhere('Tags','LIKE','%,'.$tagText);                
                }
            })->where(array(
                array('OnDate','<=',date('Y-m-d')),
                array('OffDate','>',date('Y-m-d')),
                array('Status','=',1)            
            ))->orderBy('Flagship','DESC')->orderBy('OnDate','DESC')->orderBy('OffDate','DESC')->paginate(10);

            $data['htmlTitle']=Session::get('Language')==='EN'?'Blog':'部落格';
            $data['articles']=$articles;

            // Popular posts
            $popular_posts=BlogData::leftJoin('blog_category','blogs.Category','=','blog_category.CategoryId')->where(function($query) use ($tagText){
                if(Session::get('Language')==='EN'){
                    return $query->where('TagsEn','LIKE',$tagText)->orWhere('TagsEn','LIKE',$tagText.",%")->orWhere('TagsEn','LIKE',"%,".$tagText.",%")->orWhere('TagsEn','LIKE','%,'.$tagText);
                }
                else{
                    return $query->where('Tags','LIKE',$tagText)->orWhere('Tags','LIKE',$tagText.",%")->orWhere('Tags','LIKE',"%,".$tagText.",%")->orWhere('Tags','LIKE','%,'.$tagText);                
                }            
            })->where(array(
                array('Status','=',1),
                array('OnDate','<=',date('Y-m-d')),
                array('OffDate','>',date('Y-m-d'))
            ))->orderBy('ClickCounts','DESC')->take(5)->get();
            $data['popular_posts']=$popular_posts;

            $data['tagText']=$tagText;
            return view('frontend.blog.tag_articles',$data);
        }
        return redirect('/blog');
    }
}