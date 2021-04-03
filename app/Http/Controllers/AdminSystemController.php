<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Session;
use Validator;

class AdminSystemController extends Controller
{                              
    public function __construct(){
        
    }

    public function countries(Request $request){
        $countries=DB::table('countries')->orderBy('CountryId','ASC')->get();
        $data['countries']=$countries;

        $data['currentPage']='sys_setting';
        $data['header_title']='Countries';
        return view('admin.adminCountries',$data);
    }

    public function maintain_country($id=false,Request $request){
    	$action="edit";
    	$countryInfo=array(
    		'CountryId'=>'',
    		'Country'=>'',
            'CountryEn'=>'',
            'CountryDesc'=>'',
            'CountryDescEn'=>'',
            'CountryMainImage'=>'',
    	);
    	if((bool)$id===false){
    		$action="add";
    	}
    	else{
    		$countryQuery=DB::table('countries')->where('CountryId',$id)->take(1)->first();
    		if($countryQuery){
    			$countryInfo['CountryId']=$countryQuery->CountryId;
    			$countryInfo['Country']=$countryQuery->Country;
                $countryInfo['CountryEn']=$countryQuery->CountryEn;
                $countryInfo['CountryDesc']=$countryQuery->CountryDesc;
                $countryInfo['CountryDescEn']=$countryQuery->CountryDescEn;
                $countryInfo['CountryMainImage']=$countryQuery->CountryMainImage;
    		}
    	}

    	if($request->isMethod('post')){
	        $validator_rule_array=array(
	        	'CountryId'=>'required|max:5',
	        	'Country'=>'required|max:20',
                'CountryEn'=>'nullable|max:20',
                'CountryDesc'=>'nullable|max:65535',
                'CountryDescEn'=>'nullable|max:65535',
                'CountryMainImage'=>'nullable|max:100'
	        );
            $build_validator=Validator::make($request->all(),$validator_rule_array);
            if($build_validator->fails()){
                return redirect()->back()->withInput()->withErrors($build_validator->errors());
            }

    		$CountryId=request('CountryId');
    		$Country=request('Country');
            $CountryEn=request('CountryEn');
            $CountryDesc=trim(request('CountryDesc'));
            $CountryDescEn=trim(request('CountryDescEn'));
            $CountryMainImage=trim(request('CountryMainImage'));

    		$originalData=DB::table('countries')->where('CountryId',$CountryId)->take(1)->first();
    		if($originalData)
    			$action="edit";
    		else
    			$action="add";

    		if($action=='add'){
    			DB::table('countries')->insert(array(
    				'CountryId'=>$CountryId,
    				'Country'=>$Country,
                    'CountryEn'=>$CountryEn,
                    'CountryDesc'=>$CountryDesc,
                    'CountryDescEn'=>$CountryDescEn,
                    'CountryMainImage'=>$CountryMainImage,
    				'created_at'=>date('Y-m-d H:i:s'),
    				'updated_at'=>date('Y-m-d H:i:s')
    			));
    		}
    		else{
    			DB::table('countries')->where('CountryId',$CountryId)->update(array(
    				'Country'=>$Country,
                    'CountryEn'=>$CountryEn,
                    'CountryDesc'=>$CountryDesc,
                    'CountryDescEn'=>$CountryDescEn,
                    'CountryMainImage'=>$CountryMainImage,                    
    				'updated_at'=>date('Y-m-d H:i:s')
    			));    			
    		}

    		return redirect('/admin/system/countries');
    	}
    	$data['action']=$action;
    	$data['countryInfo']=$countryInfo;
    	$data['currentPage']='sys_setting';
    	$data['header_title']='Maintain country';
    	return view('admin.maintain_country',$data);
    }

    public function delete_country($CountryId){
    	DB::table('countries')->where('CountryId',$CountryId)->delete();
    	return redirect('/admin/system/countries');
    }
}