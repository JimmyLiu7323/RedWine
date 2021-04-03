<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\System\CompanyData;

use Validator;
use Session;

class AdminWebInfoController extends Controller
{
    public function __construct(){

    }

    public function company(Request $request){
        if($request->isMethod('post')){
            $validator_rule_messages=[
                'email.email'=>'公司Email不符合格式',
                'fb_link.url'=>'公司Facebook網址不符合格式',
                'ig_link.url'=>'公司Instagram網址不符合格式',
                'twitter_link.url'=>'公司Twitter網址不符合格式',
                'yt_link.url'=>'公司Youtube網址不符合格式',
            ];
            $validator_rule_array=array(
                'email'=>'nullable|email',
                'fb_link'=>'nullable|url',
                'ig_link'=>'nullable|url',
                'twitter_link'=>'nullable|url',
                'yt_link'=>'nullable|url'
            );

            $build_validator=Validator::make($request->all(),$validator_rule_array,$validator_rule_messages);
            if($build_validator->fails()){
                return redirect()->back()->withInput()->withErrors($build_validator->errors());
            }

            $updateRes=array();
            foreach($request->all() as $key=>$value){
                if($key!='_token'){
                    if(trim($value)!=''){
                        $newValue=trim($value);
                        $updated_at_time=date('Y-m-d H:i:s');
                        $update_array=array(
                            'InfoValue'=>$newValue,
                            'updated_at'=>$updated_at_time
                        );
                        $pushUpdateRes=CompanyData::where('InfoKey',$key)->update($update_array);
                        array_push($updateRes,$pushUpdateRes);
                    }
                }
            }
            if(!in_array(false,$updateRes)){
                Session::flash('maintain_message',true);
                Session::flash('maintain_message_success','Updated successfully');
                return redirect('/admin/webinfo/company');
            }
            else{
                Session::flash('maintain_message',true);
                Session::flash('maintain_message_fail','Some data updated fail...');
                return redirect()->back()->withInput()->withErrors($build_validator->errors());
            }
        }
        else{
            $data['currentPage']='webinfo';
            $data['header_title']='Company';
            $companyInfo=CompanyData::get();
            $data['companyInfo']=$companyInfo;
            return view('admin.webinfo.companyInfo',$data);
        }
    }

    public function basic_seo(Request $req){
        $basic_seo=array(
            'DC_Title'=>'text',
            'DC_Creator'=>'text',
            'DC_Subject'=>'text',
            'DC_Description'=>'textarea',
            'DC_Contributor'=>'text',
            'DC_Type'=>'text',
            'DC_Format'=>'text',
            'DC_Source'=>'text',
            'DC_Language'=>'text',
            'DC_Coverage'=>'date',
            'DC_Coverage_t_max'=>'date',
            'DC_Publisher'=>'text',
            'DC_Date'=>'text_readonly_(Today)',
            'DC_Rights'=>'text',
            'META_KEYWORDS'=>'text',
            'META_DESCRIPTION'=>'textarea',
            'META_OG_IMAGE'=>'file',
        );
        if($req->input('lang')=='zh')
            $basic_seo['DC_Date']='text_readonly_(今天)';

        $seo_Title=array(
            'DC_Title'=>'DC.Title',
            'DC_Creator'=>'DC.Creator',
            'DC_Subject'=>'DC.Subject',
            'DC_Description'=>'DC.Description',
            'DC_Contributor'=>'DC.Contributor',
            'DC_Type'=>'DC.Type',
            'DC_Format'=>'DC.Format',
            'DC_Source'=>'DC.Source',
            'DC_Language'=>'DC.Language',
            'DC_Coverage'=>'DC.Coverage(Start)',
            'DC_Coverage_t_max'=>'DC.Coverage(End)',
            'DC_Publisher'=>'DC.Publisher',
            'DC_Date'=>'DC.Date',
            'DC_Rights'=>'DC.Rights',
            'META_KEYWORDS'=>'Keywords',
            'META_DESCRIPTION'=>'Description',
            'META_OG_IMAGE'=>'Open graph Preview'
        );
        if($req->input('lang')=='zh'){
            $seo_Title=array(
                'DC_Title'=>'標題',
                'DC_Creator'=>'創作者',
                'DC_Subject'=>'主旨',
                'DC_Description'=>'內容描述',
                'DC_Contributor'=>'貢獻者',
                'DC_Type'=>'資源類型',
                'DC_Format'=>'資料格式',
                'DC_Source'=>'來源',
                'DC_Language'=>'語言',
                'DC_Coverage'=>'涵蓋範圍起',
                'DC_Coverage_t_max'=>'涵蓋範圍訖',
                'DC_Publisher'=>'出版者',
                'DC_Date'=>'製作日期',
                'DC_Rights'=>'版權說明',
                'META_KEYWORDS'=>'網站關鍵字',
                'META_DESCRIPTION'=>'網站描述',
                'META_OG_IMAGE'=>'Open graph網站縮圖'
            );
        }

        $img_preview=array();
        if($req->isMethod('post')){
            foreach($basic_seo as $seo_key=>$seo_dataType){
                if($seo_dataType=="file"){
                    if($req->file($seo_key)&&$req->file($seo_key)->isValid()){
                        if($uploadRes=Storage::disk('public_uploads')->put(".",$req->file($seo_key))){
                            $seo_key_image=substr($uploadRes,2);
                            if(env($seo_key)!=''){
                                //remove previous image
                                Storage::disk('public_uploads')->delete(env($seo_key));
                            }

                            $this->setEnv($seo_key,$seo_key_image,$req->input('lang')!='zh'?'En':'');
                        }
                    }
                }
                else{
                    $this->setEnv($seo_key,trim(request($seo_key)),$req->input('lang')!='zh'?'En':'');
                }
            }
            return redirect('/admin/webinfo/basic_seo?lang='.$req->input('lang'));
        }
        $seoData=array();
        foreach($basic_seo as $seo_key=>$seo_type){
            $seoData[$seo_key]=array($seo_type,$req->input('lang')!='zh'?env($seo_key."En"):env($seo_key));
            if($seo_type=="file"){
                array_push($img_preview,$seo_key);
            }
        }
        $data['img_preview']=json_encode($img_preview);
        $data['seo_Title']=$seo_Title;
        $data['seoData']=$seoData;
        $data['currentPage']='webinfo';
        $data['header_title']='SEO Structure';
        return view('admin.webinfo.basic_seo',$data);        
    }

    private function setEnv($name,$value,$lang="")
    {
        $path=base_path('.env');
        $getEnvKey=$name;
        if(trim($lang)!=="")
            $getEnvKey=$name."En";
        if(file_exists($path)){
            if(env($getEnvKey)===false||env($getEnvKey)===true){
                if(env($getEnvKey)==false)
                    $before_env=$getEnvKey."=false";
                else
                    $before_env=$getEnvKey."=true";
            }
            else{
                $before_env=$getEnvKey."=\"".env($getEnvKey)."\"";
            }

            $after_env=$getEnvKey."=\"".$value."\"";
            if($value===true||$value===false){
                if($value==false)
                    $after_env=$getEnvKey."=false";
                else
                    $after_env=$getEnvKey."=true";
            }

            file_put_contents($path,str_replace(
                $before_env,
                $after_env,
                file_get_contents($path)
            ));
        }
    }
}
