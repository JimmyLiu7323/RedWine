<?php
namespace App\Http\Controllers\Backend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use Validator;
class AdminFooterInfoController extends Controller
{
    public function __construct(){

    }

    public function guarantee(Request $request){
        $data['guarantee_text'] = '';
        $data['guarantee_text_en'] = '';

        $footer_guarantee_combos=DB::table('template_static_data')->whereIn('DataKey',array(
            'guarantee_text',
            'guarantee_text_en'
        ))->get();
        foreach($footer_guarantee_combos as $combo){
            $data[$combo->DataKey]=$combo->Data;
        }

        if($request->isMethod('post')){
            $validator_rule_array=array(
                'guarantee_text'=>'required|max:800',
                'guarantee_text_en'=>'required|max:800'
            );
            $validator_message=array(
                'guarantee_text.max'=>'The guarantee introduction should not over 800 characters',                
                'guarantee_text.required'=>'Guarantee introduction should not be empty',
                'guarantee_text_en.max'=>'The guarantee introduction in English should not over 800 characters',                
                'guarantee_text_en.required'=>'Guarantee introduction in English should not be empty',
            );
            $build_validator=Validator::make($request->all(),$validator_rule_array,$validator_message);
            if($build_validator->fails()){
                return redirect()->back()->withInput()->withErrors($build_validator->errors());
            }

            $guarantee_text=trim(request('guarantee_text'));
            $guarantee_text_en=trim(request('guarantee_text_en'));
            $footer_guarantee_update = array('guarantee_text','guarantee_text_en');
            foreach($footer_guarantee_update as $updateKey){
                DB::table('template_static_data')->where('DataKey',$updateKey)->update(array(
                    'Data'=>${$updateKey},
                    'updated_at'=>date('Y-m-d H:i:s')
                ));
            }
            return redirect('/admin/footer_info/guarantee');
        }
        $data['currentPage']='footer_info';
        $data['header_title']='Our Guarantee';
        return view('admin.footer_info.guarantee',$data);
    }

    public function privacy_policy(Request $request){
        $data['privacy_policy'] = '';
        $data['privacy_policy_en'] = '';

        $footer_page_combos=DB::table('footer_page_static_data')->whereIn('DataKey',array(
            'privacy_policy',
            'privacy_policy_en'
        ))->get();
        foreach($footer_page_combos as $combo){
            $data[$combo->DataKey]=$combo->Data;
        }

        if($request->isMethod('post')){
            $validator_rule_array=array(
                'privacy_policy'=>'required|max:65535',
                'privacy_policy_en'=>'required|max:65535'
            );
            $validator_message=array(
                'privacy_policy.max'=>'The guarantee introduction should not over 800 characters',                
                'privacy_policy_en.max'=>'The guarantee introduction in English should not over 800 characters',                
            );
            $build_validator=Validator::make($request->all(),$validator_rule_array,$validator_message);
            if($build_validator->fails()){
                return redirect()->back()->withInput()->withErrors($build_validator->errors());
            }

            $privacy_policy=trim(request('privacy_policy'));
            $privacy_policy_en=trim(request('privacy_policy_en'));
            $footer_page_update = array('privacy_policy','privacy_policy_en');
            foreach($footer_page_update as $updateKey){
                DB::table('footer_page_static_data')->where('DataKey',$updateKey)->update(array(
                    'Data'=>${$updateKey},
                    'updated_at'=>date('Y-m-d H:i:s')
                ));
            }
            return redirect('/admin/footer_info/privacy_policy');
        }
        $data['currentPage']='footer_info';
        $data['header_title']='Privacy Policy';
        return view('admin.footer_info.privacy_policy',$data);        
    }

    public function corporate_services(Request $request){
        $data['corporate_image'] = '';
        $data['corporate_introduction'] = '';
        $data['corporate_introduction_en'] = '';

        $footer_page_combos=DB::table('footer_page_static_data')->whereIn('DataKey',array(
            'corporate_image',
            'corporate_introduction',
            'corporate_introduction_en'
        ))->get();
        foreach($footer_page_combos as $combo){
            $data[$combo->DataKey]=$combo->Data;
        }

        if($request->isMethod('post')){
            $validator_rule_array=array(
                'corporate_image'=>'required|max:100',
                'corporate_introduction'=>'required|max:65535',
                'corporate_introduction_en'=>'nullable|max:65535'
            );
            $validator_message=array(
                'corporate_image.required'=>'Should choose impact image of coporate',
                'corporate_image.max'=>'File name of impact image of corporate should not above 100 characters',
                'corporate_introduction.required'=>'Should input the introduction of corporate',
                'corporate_introduction.max'=>'The introduction of corporate should not above 65535 characters',
                'corporate_introduction_en.max'=>'The introduction of corporate in English should not above 65535 characters',
            );
            $build_validator=Validator::make($request->all(),$validator_rule_array,$validator_message);
            if($build_validator->fails()){
                return redirect()->back()->withInput()->withErrors($build_validator->errors());
            }

            $corporate_image = trim(request('corporate_image'));
            $corporate_introduction = trim(request('corporate_introduction'));
            $corporate_introduction_en = trim(request('corporate_introduction_en'));
            $footer_page_update = array('corporate_image','corporate_introduction','corporate_introduction_en');
            foreach($footer_page_update as $updateKey){
                DB::table('footer_page_static_data')->where('DataKey',$updateKey)->update(array(
                    'Data'=>${$updateKey},
                    'updated_at'=>date('Y-m-d H:i:s')
                ));
            }
            return redirect('/admin/footer_info/corporate_services');
        }
        $data['currentPage']='footer_info';
        $data['header_title']='Corporate Services';
        return view('admin.footer_info.corporate_services',$data);          
    }

    public function awards(Request $request){
        $data['award_image'] = '';
        $data['award_introduction'] = '';
        $data['award_introduction_en'] = '';

        $footer_page_combos=DB::table('footer_page_static_data')->whereIn('DataKey',array(
            'award_image',
            'award_introduction',
            'award_introduction_en'
        ))->get();
        foreach($footer_page_combos as $combo){
            $data[$combo->DataKey]=$combo->Data;
        }

        if($request->isMethod('post')){
            $validator_rule_array=array(
                'award_image'=>'required|max:100',
                'award_introduction'=>'required|max:65535',
                'award_introduction_en'=>'nullable|max:65535'
            );
            $validator_message=array(
                'award_image.required'=>'Should choose impact image of award',
                'award_image.max'=>'File name of impact image of award should not above 100 characters',
                'award_introduction.required'=>'Should input the introduction of award',
                'award_introduction.max'=>'The introduction of award should not above 65535 characters',
                'award_introduction_en.max'=>'The introduction of award in English should not above 65535 characters',
            );
            $build_validator=Validator::make($request->all(),$validator_rule_array,$validator_message);
            if($build_validator->fails()){
                return redirect()->back()->withInput()->withErrors($build_validator->errors());
            }

            $award_image = trim(request('award_image'));
            $award_introduction = trim(request('award_introduction'));
            $award_introduction_en = trim(request('award_introduction_en'));
            $footer_page_update = array('award_image','award_introduction','award_introduction_en');
            foreach($footer_page_update as $updateKey){
                DB::table('footer_page_static_data')->where('DataKey',$updateKey)->update(array(
                    'Data'=>${$updateKey},
                    'updated_at'=>date('Y-m-d H:i:s')
                ));
            }
            return redirect('/admin/footer_info/awards');
        }
        $data['currentPage']='footer_info';
        $data['header_title']='Awards';
        return view('admin.footer_info.awards',$data);   
    }
}