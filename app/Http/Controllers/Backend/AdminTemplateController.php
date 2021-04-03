<?php
namespace App\Http\Controllers\Backend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\IndexFeaturedImagesData;

use Validator;
use Session;

class AdminTemplateController extends Controller
{
    public function __construct(){

    }

    public function maintain_license(Request $request){
        $data['footer_license']='';
        $data['footer_license_number']='';
        $data['footer_license_expires']='';
        $data['display_footer_license']=0;
        $data['display_footer_license_number']=0;
        $data['display_footer_license_expires']=0;

        $footer_license_combos=DB::table('template_static_data')->whereIn('DataKey',array(
            'footer_license',
            'footer_license_number',
            'footer_license_expires',
            'display_footer_license',
            'display_footer_license_number',
            'display_footer_license_expires'
        ))->get();
        foreach($footer_license_combos as $combo){
            $data[$combo->DataKey]=$combo->Data;
        }

        if($request->isMethod('post')){
            $validator_rule_array=array(
                'footer_license'=>'nullable|max:500',
                'footer_license_number'=>'nullable|max:500',
                'footer_license_expires'=>'nullable|date_format:d/m/Y|max:500',
                'display_footer_license'=>'required|integer',
                'display_footer_license_number'=>'required|integer',
                'display_footer_license_expires'=>'required|integer'
            );
            $validator_message=array(
                'footer_license.max'=>'The file name should not over 500 characters',                
                'footer_license_number.max'=>'The license number should not over 500 characters',
                'footer_license_expires.max'=>'The license expires date should not over 500 characters',
                'footer_license_expires.date_format'=>'The date format should be d/m/Y',

                'display_footer_license.required'=>'Should set the display status',
                'display_footer_license.integer'=>'Should set the display status',
                'display_footer_license_number.required'=>'Should set the display status',
                'display_footer_license_number.integer'=>'Should set the display status',
                'display_footer_license_expires.required'=>'Should set the display status',
                'display_footer_license_expires.integer'=>'Should set the display status',                
            );
            $build_validator=Validator::make($request->all(),$validator_rule_array,$validator_message);
            if($build_validator->fails()){
                return redirect()->back()->withInput()->withErrors($build_validator->errors());
            }

            $footer_license=trim(request('footer_license'));
            $footer_license_number=trim(request('footer_license_number'));
            $footer_license_expires=trim(request('footer_license_expires'));
            $display_footer_license=intval(request('display_footer_license'));
            $display_footer_license_number=intval(request('display_footer_license_number'));
            $display_footer_license_expires=intval(request('display_footer_license_expires'));

            $footer_license_update = array('footer_license','footer_license_number','footer_license_expires','display_footer_license','display_footer_license_number','display_footer_license_expires');
            foreach($footer_license_update as $footerKey){
                DB::table('template_static_data')->where('DataKey',$footerKey)->update(array(
                    'Data'=>${$footerKey},
                    'updated_at'=>date('Y-m-d H:i:s')
                ));
            }
            return redirect('/admin/template/license');
        }
        $data['currentPage']='template';
        $data['header_title']='Maintain license';
        return view('admin.template.maintain_license',$data);
    }

    public function global_notice(Request $request){
        $data['global_notice']='';
        $data['global_notice_en']='';
        $data['display_global_notice']=0;

        $global_notice_combos=DB::table('template_static_data')->whereIn('DataKey',array(
            'global_notice','global_notice_en','display_global_notice'
        ))->get();
        foreach($global_notice_combos as $combo){
            $data[$combo->DataKey]=$combo->Data;
        }

        if($request->isMethod('post')){
            $validator_rule_array=array(
                'global_notice'=>'nullable|max:500',
                'global_notice_en'=>'nullable|max:500',
                'display_global_notice'=>'required|integer'
            );
            $validator_message=array(
                'global_notice.max'=>'The content should not over 500 characters',                
                'global_notice_en.max'=>'The contentt should not over 500 characters',
                'display_global_notice.required'=>'Should set the display status',
                'display_global_notice.integer'=>'Should set the display status',
            );
            $build_validator=Validator::make($request->all(),$validator_rule_array,$validator_message);
            if($build_validator->fails()){
                return redirect()->back()->withInput()->withErrors($build_validator->errors());
            }

            $global_notice=trim(request('global_notice'));
            $global_notice_en=trim(request('global_notice_en'));
            $display_global_notice=intval(request('display_global_notice'));

            $global_notice_update = array('global_notice','global_notice_en','display_global_notice');
            foreach($global_notice_update as $key){
                DB::table('template_static_data')->where('DataKey',$key)->update(array(
                    'Data'=>${$key},
                    'updated_at'=>date('Y-m-d H:i:s')
                ));
            }
            return redirect('/admin/template/global_notice');
        }
        $data['currentPage']='template';
        $data['header_title']='Global notice';
        return view('admin.template.maintain_global_notice',$data);
    }

    public function index_featured_video(Request $request){
        $data['index_featured_video_source']='Youtube';
        $data['index_featured_video_url']='';
        $data['index_featured_video_description']='';
        $data['index_featured_video_description_en']='';
        $data['display_index_featured_video']=0;

        $index_featured_video_combos=DB::table('template_static_data')->whereIn('DataKey',array(
            'index_featured_video_source','index_featured_video_url','index_featured_video_description','display_index_featured_video','index_featured_video_description_en'
        ))->get();
        foreach($index_featured_video_combos as $combo){
            $data[$combo->DataKey]=$combo->Data;
        }

        if($request->isMethod('post')){
            $validator_rule_array=array(
                'display_index_featured_video'=>'required|integer',
                'index_featured_video_description'=>'required|max:500',
                'index_featured_video_description_en'=>'nullable|max:500',
                'index_featured_video_source'=>'required|in:Youtube,Self',
                'index_featured_video_url'=>'required|max:500'
            );
            $validator_message=array(
                'display_index_featured_video.required'=>'Should set the display status',
                'display_index_featured_video.integer'=>'Should set the display status',
                'index_featured_video_description.required'=>'Please fill the description of video',
                'index_featured_video_description.max'=>'The description should not over 500 characters',
                'index_featured_video_description_en.max'=>'The description should not over 500 characters',
                'index_featured_video_source.required'=>'Please choose the video resource',
                'index_featured_video_source.in'=>'Please choose the video resource',
                'index_featured_video_url.max'=>'Please set the video url source',
                'index_featured_video_url.max'=>'The url source should not over 500 characters'
            );
            $build_validator=Validator::make($request->all(),$validator_rule_array,$validator_message);
            if($build_validator->fails()){
                return redirect()->back()->withInput()->withErrors($build_validator->errors());
            }

            $display_index_featured_video=intval(request('display_index_featured_video'));
            $index_featured_video_source=trim(request('index_featured_video_source'));
            $index_featured_video_url=trim(request('index_featured_video_url'));
            $index_featured_video_description=trim(request('index_featured_video_description'));
            $index_featured_video_description_en=trim(request('index_featured_video_description_en'));

            $index_featured_video_set = array('display_index_featured_video','index_featured_video_source','index_featured_video_url','index_featured_video_description','index_featured_video_description_en');
            foreach($index_featured_video_set as $key){
                DB::table('template_static_data')->where('DataKey',$key)->update(array(
                    'Data'=>${$key},
                    'updated_at'=>date('Y-m-d H:i:s')
                ));
            }
            return redirect('/admin/template/index_featured_video');
        }
        $data['currentPage']='template';
        $data['header_title']='Featured video(Index Page)';
        return view('admin.template.maintain_index_featured_video',$data);
    }

    public function index_featured_image(){
        $images = IndexFeaturedImagesData::orderBy('SN','ASC')->get();

        $data['images'] = $images;
        $data['currentPage']='template';
        $data['header_title']='Featured image(Index Page)';
        return view('admin.template.index_featured_image',$data);        
    }

    public function m_feature(Request $request){
        $data['menu_featured_image'] = '';
        $data['display_menu_featured_image'] = 0;
        $data['menu_featured_image_link'] = '';

        $menu_featured_image_combos = DB::table('template_static_data')->whereIn('DataKey',array(
            'menu_featured_image','display_menu_featured_image','menu_featured_image_link'
        ))->get();
        foreach($menu_featured_image_combos as $combo){
            $data[$combo->DataKey] = $combo->Data;
        }

        if($request->isMethod('post')){
            $validator_rule_array = array(
                'menu_featured_image'=>'required|max:100',
                'display_menu_featured_image'=>'required|integer|in:0,1',
                'menu_featured_image_link'=>'nullable|max:500|url'
            );
            $validator_message=array(
                'display_menu_featured_image.required'=>'Should set the display status',
                'display_menu_featured_image.integer'=>'Should set the display status',
                'menu_featured_image.required'=>'Please choose the featured image of menu',
                'menu_featured_image.max'=>'The image name should not over 100 characters',
                'menu_featured_image_link.max'=>'The link of featured image in menu should not over 500 characters',
                'menu_featured_image_link.url'=>'The format of link is not correct'
            );
            $build_validator = Validator::make($request->all(),$validator_rule_array,$validator_message);
            if($build_validator->fails()){
                return redirect()->back()->withInput()->withErrors($build_validator->errors());
            }

            $display_menu_featured_image=intval(request('display_menu_featured_image'));
            $menu_featured_image=trim(request('menu_featured_image'));
            $menu_featured_image_link=trim(request('menu_featured_image_link'));

            $index_featured_video_set = array('display_menu_featured_image','menu_featured_image','menu_featured_image_link');
            foreach($index_featured_video_set as $key){
                DB::table('template_static_data')->where('DataKey',$key)->update(array(
                    'Data'=>${$key},
                    'updated_at'=>date('Y-m-d H:i:s')
                ));
            }
            return redirect('/admin/template/m_feature');
        }
        $data['currentPage']='template';
        $data['header_title']='Featured image(Menu)';
        return view('admin.template.maintain_menu_featured_image',$data);
    }

    public function edit_index_featured_image($SN,Request $request){
        $SN = intval($SN);
        $featuredImageInfo = IndexFeaturedImagesData::where('SN',$SN)->take(1)->first();
        if($featuredImageInfo){
            if($request->isMethod('post')){
                $validator_rule_array=array(
                    'Image'=>'required|max:300',
                    'ImageEn'=>'nullable|max:300',
                    'LinkFile'=>'required|max:300',
                    'LinkFileEn'=>'nullable|max:300',
                    'BigTitle'=>'required|max:20',
                    'BigTitleEn'=>'nullable|max:20',
                    'Description'=>'required|max:100',
                    'DescriptionEn'=>'nullable|max:100',
                    'Status'=>'required|integer|in:0,1'
                );
                $validator_message=array(
                    'Image.required'=>'Should choose image',
                    'Image.max'=>'Image path should not above 300 characters',
                    'ImageEn.required'=>'Should choose image',
                    'ImageEn.max'=>'Image path should not above 300 characters',
                    'LinkFile.required'=>'Should choose file',
                    'LinkFile.max'=>'File path should not above 300 characters',
                    'LinkFileEn.required'=>'Should choose file',
                    'LinkFileEn.max'=>'File path should not above 300 characters',
                    'BigTitle.required'=>'Should enter content of title',
                    'BigTitle.max'=>'Content of title should not above 20 characters',
                    'BigTitleEn.required'=>'Should enter content of title',
                    'BigTitleEn.max'=>'Content of title should not above 20 characters',
                    'Description.required'=>'Should enter content of description',
                    'Description.max'=>'Content of description should not above 100 characters',
                    'DescriptionEn.required'=>'Should enter content of description',
                    'DescriptionEn.max'=>'Content of description should not above 100 characters',
                    'Status.required'=>'Should choose image display or not',
                    'Status.integer'=>'Should choose image display or not',
                    'Status.in'=>'Should choose image display or not',
                );
                $build_validator=Validator::make($request->all(),$validator_rule_array,$validator_message);
                if($build_validator->fails()){
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }

                $Image = trim(request('Image'));
                $ImageEn = trim(request('ImageEn'));
                $LinkFile = trim(request('LinkFile'));
                $LinkFileEn = trim(request('LinkFileEn'));
                $BigTitle = trim(request('BigTitle'));
                $BigTitleEn = trim(request('BigTitleEn'));
                $Description = trim(request('Description'));
                $DescriptionEn = trim(request('DescriptionEn'));
                $Status = intval(request('Status'));

                IndexFeaturedImagesData::where('SN',$SN)->update(array(
                    'Image'=>$Image,
                    'ImageEn'=>$ImageEn,
                    'LinkFile'=>$LinkFile,
                    'LinkFileEn'=>$LinkFileEn,
                    'BigTitle'=>$BigTitle,
                    'BigTitleEn'=>$BigTitleEn,
                    'Description'=>$Description,
                    'DescriptionEn'=>$DescriptionEn,
                    'Status'=>$Status,
                    'updated_at'=>date('Y-m-d H:i:s')
                ));
                return redirect('/admin/template/index_featured_image');
            }
            $data['imageInfo'] = $featuredImageInfo;
            $data['currentPage'] = 'template';
            $data['header_title'] = 'Edit Featured image(Index Page)';
            return view('admin.template.edit_index_featured_image',$data);
        }
        return redirect('/admin/template/index_featured_image');
    }

    public function index_slogan(Request $request){
        $data['index_slogan']='';
        $data['index_slogan_en']='';
        $data['display_index_slogan']=0;

        $index_slogan_combos=DB::table('template_static_data')->whereIn('DataKey',array(
            'index_slogan','index_slogan_en','display_index_slogan'))->get();
        foreach($index_slogan_combos as $combo){
            $data[$combo->DataKey]=$combo->Data;
        }

        if($request->isMethod('post')){
            $validator_rule_array=array(
                'display_index_slogan'=>'required|integer',
                'index_slogan'=>'required|max:500',
                'index_slogan_en'=>'nullable|max:500'
            );
            $validator_message=array(
                'display_index_slogan.required'=>'Should set the display status',
                'display_index_slogan.integer'=>'Should set the display status',
                'index_slogan.required'=>'Please fill the small slogan',
                'index_slogan.max'=>'The small slogan should not over 500 characters',
                'index_slogan_en.max'=>'The small slogan in English should not over 500 characters',
            );
            $build_validator=Validator::make($request->all(),$validator_rule_array,$validator_message);
            if($build_validator->fails()){
                return redirect()->back()->withInput()->withErrors($build_validator->errors());
            }

            $display_index_slogan=intval(request('display_index_slogan'));
            $index_slogan=trim(request('index_slogan'));
            $index_slogan_en=trim(request('index_slogan_en'));            

            $index_slogan_set = array('display_index_slogan','index_slogan','index_slogan_en');
            foreach($index_slogan_set as $key){
                DB::table('template_static_data')->where('DataKey',$key)->update(array(
                    'Data'=>${$key},
                    'updated_at'=>date('Y-m-d H:i:s')
                ));
            }
            return redirect('/admin/template/index_slogan');
        }
        $data['currentPage']='template';
        $data['header_title']='Small slogan(Index Page)';
        return view('admin.template.maintain_index_slogan',$data);        
    }

    public function index_guarantee(Request $request){
        $data['index_guarantee_image']='';
        $data['index_promotion_image']='';
        $data['display_index_guarantee_image']=0;
        $data['display_index_promotion_image']=0;

        $index_guarantee_combos=DB::table('template_static_data')->whereIn('DataKey',array(
            'index_guarantee_image','index_promotion_image','display_index_guarantee_image','display_index_promotion_image'))->get();
        foreach($index_guarantee_combos as $combo){
            $data[$combo->DataKey]=$combo->Data;
        }

        if($request->isMethod('post')){
            $validator_rule_array=array(
                'index_guarantee_image'=>'required|max:500',
                'index_promotion_image'=>'required|max:500',
                'display_index_guarantee_image'=>'required|integer|in:0,1',
                'display_index_promotion_image'=>'required|integer|in:0,1'
            );
            $validator_message=array(
                'index_guarantee_image.required'=>'Should choose the image of guarantee',
                'index_guarantee_image.max'=>'The file name of guarantee should not above 500 characters',
                'index_promotion_image.required'=>'Should choose the image of promotion',
                'index_promotion_image.max'=>'The file name of promotion should not above 500 characters',
                'display_index_guarantee_image.required'=>'Should choose the display status of guarantee',
                'display_index_guarantee_image.integer'=>'Should choose the display status of guarantee',
                'display_index_guarantee_image.in'=>'Should choose the display status of guarantee',
                'display_index_promotion_image.required'=>'Should choose the display status of promotion',
                'display_index_promotion_image.integer'=>'Should choose the display status of promotion',
                'display_index_promotion_image.in'=>'Should choose the display status of promotion',                
            );
            $build_validator=Validator::make($request->all(),$validator_rule_array,$validator_message);
            if($build_validator->fails()){
                return redirect()->back()->withInput()->withErrors($build_validator->errors());
            }

            $index_guarantee_image = trim(request('index_guarantee_image'));
            $index_promotion_image = trim(request('index_promotion_image'));
            $display_index_guarantee_image = intval(request('display_index_guarantee_image'));
            $display_index_promotion_image = intval(request('display_index_promotion_image'));          

            $index_guarantee_set = array('index_guarantee_image','index_promotion_image','display_index_guarantee_image','display_index_promotion_image');
            foreach($index_guarantee_set as $key){
                DB::table('template_static_data')->where('DataKey',$key)->update(array(
                    'Data'=>${$key},
                    'updated_at'=>date('Y-m-d H:i:s')
                ));
            }
            return redirect('/admin/template/index_guarantee');
        }
        $data['currentPage']='template';
        $data['header_title']='Guarantees(Index Page)';
        return view('admin.template.maintain_index_guarantee',$data);        
    }

    public function case_deals(Request $request){
        $data['case_deals_description']='';
        $data['case_deals_description_en']='';
        $data['case_deals_impact_image']='';
        $data['case_deals_subtitle']='';
        $data['case_deals_subtitle_en']='';

        $case_deals_combos=DB::table('template_static_data')->whereIn('DataKey',array('case_deals_description','case_deals_description_en','case_deals_impact_image','case_deals_subtitle','case_deals_subtitle_en'))->get();
        foreach($case_deals_combos as $combo){
            $data[$combo->DataKey]=$combo->Data;
        }

        if($request->isMethod('post')){
            $validator_rule_array=array(
                'case_deals_description'=>'required|max:500',
                'case_deals_description_en'=>'nullable|max:500',
                'case_deals_subtitle'=>'required|max:500',
                'case_deals_subtitle_en'=>'nullable|max:500',
                'case_deals_impact_image'=>'required|max:500',
            );
            $validator_message=array(
                'case_deals_impact_image.required'=>'Should choose the image of case deals',
                'case_deals_impact_image.max'=>'Impact image of case deals should not above 500 characters',
                'case_deals_description.required'=>'Please fill the description of case deals',
                'case_deals_description.max'=>'The description of case deals should not above 500 characters',
                'case_deals_description_en.max'=>'The description of case deals in English should not above 500 characters',
                'case_deals_subtitle.required'=>'Please fill the subtitle of case deals',
                'case_deals_subtitle.max'=>'The subtitle of case deals should not above 500 characters',
                'case_deals_subtitle_en.max'=>'The subtitle of case deals in English should not above 500 characters',                
            );
            $build_validator=Validator::make($request->all(),$validator_rule_array,$validator_message);
            if($build_validator->fails()){
                return redirect()->back()->withInput()->withErrors($build_validator->errors());
            }

            $case_deals_description = trim(request('case_deals_description'));
            $case_deals_description_en = trim(request('case_deals_description_en'));
            $case_deals_impact_image = trim(request('case_deals_impact_image'));
            $case_deals_subtitle = trim(request('case_deals_subtitle'));
            $case_deals_subtitle_en = trim(request('case_deals_subtitle_en'));

            $case_deals_set = array('case_deals_impact_image','case_deals_description','case_deals_description_en','case_deals_subtitle','case_deals_subtitle_en');
            foreach($case_deals_set as $key){
                DB::table('template_static_data')->where('DataKey',$key)->update(array(
                    'Data'=>${$key},
                    'updated_at'=>date('Y-m-d H:i:s')
                ));
            }
            return redirect('/admin/template/case-deals');
        }
        $data['currentPage']='template';
        $data['header_title']='Case Deals Introduction';
        return view('admin.template.maintain_case_deals',$data);      
    }
}