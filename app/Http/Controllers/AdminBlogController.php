<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\AdminData;
use App\BlogCategoryData;
use App\BlogData;
use Validator;
use Session;
use Widget_Helper;

class AdminBlogController extends Controller
{                              
    public function __construct(){
        
    }

    public function category(Request $request){
        $categories=DB::table('blog_category')->orderBy('OrderNumber','ASC')->get();
        $data['categories']=$categories;

        $data['currentPage']='blog';
        $data['header_title']='Categories of Blog';
        return view('admin.blog.adminCategoryList',$data);
    }

    public function maintain_category(Request $request){
        $data=array(
            'currentPage'=>'blog',
            'header_title'=>'Maintain category',
            'action'=>'add',
            'Category'=>'',
            'CategoryEn'=>'',
            'OrderNumber'=>1
        );

        $validator_rule_array=array(
            'Category'=>'required|max:100',
            'CategoryEn'=>'nullable|max:100',
            'OrderNumber'=>'required|integer',
        );

        $categoryId=intval(request('id'));
        if($categoryId===0){
            if($request->isMethod('post')){
                $build_validator=Validator::make($request->all(),$validator_rule_array);
                if($build_validator->fails()){
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }

                $Category=trim(request('Category'));
                $CategoryEn=trim(request('CategoryEn'));
                $OrderNumber=intval(request('OrderNumber'));

                if(
                    DB::table('blog_category')->insert(array(
                        'Category'=>$Category,
                        'CategoryEn'=>$CategoryEn,
                        'OrderNumber'=>$OrderNumber,
                        'created_at'=>date('Y-m-d H:i:s'),
                        'updated_at'=>date('Y-m-d H:i:s'),
                    ))
                ){
                    return redirect('/admin/blog/category');
                }
                else{
                    Session::flash('maintain_message',true);
                    Session::flash('maintain_message_fail','Failed to add category');
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }
            }
            return view('admin.blog.add_mod_category',$data);
        }
        else{
            $data['action']='mod';
            $categoryInfo=DB::table('blog_category')->where('CategoryId',$categoryId)->take(1)->first();
            if($categoryInfo){
                if($request->isMethod('post')){
                    $build_validator=Validator::make($request->all(),$validator_rule_array);
                    if($build_validator->fails()){
                        return redirect()->back()->withInput()->withErrors($build_validator->errors());
                    }
                    $Category=trim(request('Category'));
                    $CategoryEn=trim(request('CategoryEn'));
                    $OrderNumber=intval(request('OrderNumber'));
                    $updateRes=DB::table('blog_category')->where('CategoryId',$categoryId)->update(array(
                        'Category'=>$Category,
                        'CategoryEn'=>$CategoryEn,
                        'OrderNumber'=>$OrderNumber,
                        'updated_at'=>date('Y-m-d H:i:s')                        
                    ));
                    if($updateRes){
                        return redirect('/admin/blog/category');
                    }
                    else{
                        Session::flash('maintain_message',true);
                        Session::flash('maintain_message_fail','Fail to update category');
                        return redirect()->back()->withInput()->withErrors($build_validator->errors());                        
                    }
                }
                foreach($categoryInfo as $key=>$val){
                    $data[$key]=$val;
                }
                return view('admin.blog.add_mod_category',$data);
            }
            return redirect('admin/blog/category');
        }
    }

    public function delete_category(Request $request){
        $categoryId=intval(request('id'));
        if(!DB::table('blog_category')->where('CategoryId',$categoryId)->delete()){
            Session::flash('maintain_message',true);
            Session::flash('maintain_message_fail','Fail to delete category');
        }
        return redirect('/admin/blog/category');
    }

    // articles
    public function articles(Request $request){
        $articles=BlogData::leftJoin('blog_category','blogs.Category','=','blog_category.CategoryId')->orderBy('Status','DESC')->orderBy('OnTop','DESC')->orderBy('blogs.OrderNumber','ASC')->paginate(10);
        $data['articles']=$articles;
        $data['currentPage']='blog';
        $data['header_title']='Articles of Blog';
        return view('admin.blog.adminArticleList',$data);        
    }

    public function maintain_articles(Request $request){
        $data=array(
            'currentPage'=>'blog',
            'header_title'=>'Maintain article',
            'action'=>'add',
            'Image'=>'',
            'Title'=>'',
            'TitleEn'=>'',
            'BriefDesc'=>'',
            'BriefDescEn'=>'',
            'Category'=>'',
            'Tags'=>'',
            'TagsEn'=>'',
            'Content'=>'',
            'ContentEn'=>'',
            'OnDate'=>date('Y-m-d'),
            'OffDate'=>date('Y-m-d',strtotime("+1 day")),
            'Status'=>0,
            'OnTop'=>0,
            'OrderNumber'=>1,
            'Flagship'=>0
        );

        $validator_rule_array=array(
            'Title'=>'required|max:255',
            'TitleEn'=>'required|max:255',
            'BriefDesc'=>'required|max:500',
            'BriefDescEn'=>'nullable|max:500',
            'Category'=>'nullable|integer',
            'Content'=>'required',
            'Tags'=>'nullable|max:100',
            'TagsEn'=>'nullable|max:200',
            'OnDate'=>'nullable|date',
            'OffDate'=>'nullable|date',
            'Status'=>'required|integer|in:0,1',
            'OnTop'=>'required|integer|in:0,1',
            'OrderNumber'=>'required|integer',
            'Flagship'=>'nullable|integer|in:0,1'
        );

        $CategoryOptions=DB::table('blog_category')->orderBy('OrderNumber','ASC')->select('CategoryId','Category')->get();
        if($CategoryOptions->count()>0){
            $validator_rule_array['Category'].="|in:";
            foreach($CategoryOptions as $CategoryOption){
                $validator_rule_array['Category'].=$CategoryOption->CategoryId.",";
            }
            $validator_rule_array['Category'] = substr($validator_rule_array['Category'],0,strlen($validator_rule_array['Category'])-1);
        }
        $data['CategoryOptions']=$CategoryOptions;

        $articleId=trim(request('id'));
        if($articleId===''){
            if($request->isMethod('post')){
                $build_validator=Validator::make($request->all(),$validator_rule_array);
                if($build_validator->fails()){
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }

                $ArticleId=Widget_Helper::createID();
                $Title=trim(request('Title'));
                $Image=trim(request('Image'));
                $TitleEn=trim(request('TitleEn'));
                $BriefDesc=trim(request('BriefDesc'));
                $BriefDescEn=trim(request('BriefDescEn'));
                $Category=intval(request('Category'));
                $Tags=trim(request('Tags'));
                $TagsEn=trim(request('TagsEn'));
                $OnDate=trim(request('OnDate'));
                $OffDate=trim(request('OffDate'));
                $Content=trim(request('Content'));
                $ContentEn=trim(request('ContentEn'));
                $OrderNumber=intval(request('OrderNumber'));
                $Status=intval(request('Status'));
                $OnTop=intval(request('OnTop'));
                $Flagship=intval(request('Flagship'));

                $articleArray=array(
                    'ArticleId'=>$ArticleId,
                    'Image'=>$Image,
                    'Title'=>$Title,
                    'TitleEn'=>$TitleEn,
                    'BriefDesc'=>$BriefDesc,
                    'BriefDescEn'=>$BriefDescEn,
                    'Category'=>$Category,
                    'Tags'=>$Tags,
                    'TagsEn'=>$TagsEn,
                    'OnDate'=>$OnDate,
                    'OffDate'=>$OffDate,
                    'Content'=>$Content,
                    'ContentEn'=>$ContentEn,
                    'OrderNumber'=>$OrderNumber,
                    'Status'=>$Status,
                    'OnTop'=>$OnTop,
                    'Flagship'=>$Flagship,
                    'Author'=>Session::get('AdminId'),
                    'created_at'=>date('Y-m-d H:i:s'),
                    'updated_at'=>date('Y-m-d H:i:s')
                );

                if(BlogData::create($articleArray)){
                    return redirect('/admin/blog/articles');
                }
                else{
                    Session::flash('maintain_message',true);
                    Session::flash('maintain_message_fail','Failed to add article');
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }
            }
            return view('admin.blog.add_mod_article',$data);            
        }
        else{
            $data['action']='mod';
            $articleInfo=BlogData::leftJoin('blog_category','blogs.Category','=','blog_category.CategoryId')->where('ArticleId',$articleId)->select('blogs.*')->take(1)->first()->toArray();
            if($articleInfo){
                if($request->isMethod('post')){
                    $build_validator=Validator::make($request->all(),$validator_rule_array);
                    if($build_validator->fails()){
                        return redirect()->back()->withInput()->withErrors($build_validator->errors());
                    }

                    $Image=trim(request('Image'));
                    $Title=trim(request('Title'));
                    $TitleEn=trim(request('TitleEn'));
                    $BriefDesc=trim(request('BriefDesc'));
                    $BriefDescEn=trim(request('BriefDescEn'));
                    $Category=intval(request('Category'));
                    $Tags=trim(request('Tags'));
                    $TagsEn=trim(request('TagsEn'));
                    $OnDate=trim(request('OnDate'));
                    $OffDate=trim(request('OffDate'));
                    $Content=trim(request('Content'));
                    $ContentEn=trim(request('ContentEn'));
                    $OrderNumber=intval(request('OrderNumber'));
                    $Status=intval(request('Status'));
                    $OnTop=intval(request('OnTop'));
                    $Flagship=intval(request('Flagship'));

                    $articleArray=array(
                        'Image'=>$Image,
                        'Title'=>$Title,
                        'TitleEn'=>$TitleEn,
                        'BriefDesc'=>$BriefDesc,
                        'BriefDescEn'=>$BriefDescEn,
                        'Category'=>$Category,
                        'Tags'=>$Tags,
                        'TagsEn'=>$TagsEn,
                        'OnDate'=>$OnDate,
                        'OffDate'=>$OffDate,
                        'Content'=>$Content,
                        'ContentEn'=>$ContentEn,
                        'OrderNumber'=>$OrderNumber,
                        'Status'=>$Status,
                        'OnTop'=>$OnTop,
                        'Flagship'=>$Flagship,
                        'updated_at'=>date('Y-m-d H:i:s')
                    );

                    $updateRes=BlogData::where('ArticleId',$articleId)->update($articleArray);

                    if($updateRes){
                        return redirect('/admin/blog/articles');
                    }
                    else{
                        Session::flash('maintain_message',true);
                        Session::flash('maintain_message_fail','Fail to update article');
                        return redirect()->back()->withInput()->withErrors($build_validator->errors());                        
                    }
                }
                
                foreach($articleInfo as $key=>$val){
                    $data[$key]=$val;
                }
                return view('admin.blog.add_mod_article',$data);
            }
            return redirect('admin/blog/articles');
        }
    }

    public function delete_article(Request $request){
        $ArticleId=trim(request('id'));
        if(!BlogData::where('ArticleId',$ArticleId)->delete()){
            Session::flash('maintain_message',true);
            Session::flash('maintain_message_fail','Fail to delete article');
        }
        return redirect('/admin/blog/articles');        
    }
}