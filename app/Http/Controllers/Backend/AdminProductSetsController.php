<?php
namespace App\Http\Controllers\Backend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Products\SalesMixData;
use App\Products\CateringsData;

use Widget_Helper;
use Validator;
use Session;

class AdminProductSetsController extends Controller
{
    private $_variety_rule_array;
    private $_variety_rule_messages;
    private $_colour_rule_array;
    private $_colour_rule_messages;  
    private $_region_rule_array;
    private $_region_rule_messages;
    private $_closure_rule_array;
    private $_closure_rule_messages;
    private $_catering_rule_array;
    private $_catering_rule_messages;
    private $_style_rule_array;
    private $_style_rule_messages;
    public function __construct(){
        $this->_variety_rule_array=array(
            'Variety'=>'required|max:40',
            'VarietyEn'=>'nullable|max:40',
            'VarietyDesc'=>'nullable|max:65535',
            'VarietyDescEn'=>'nullable|max:65535',
            'VarietyMainImage'=>'nullable|max:100',
            'OrderNumber'=>'required|integer',
            'Status'=>'required|integer|in:0,1'
        );
        $this->_variety_rule_messages=array(
            'Variety.required'=>'请输入品种',
            'Variety.max'=>'品种最多输入40个字元',
            'VarietyEn.max'=>'品种(英)最多输入40个字元',
            'VarietyDesc.max'=>'品种介绍内容请勿超过65535个字元',
            'VarietyDescEn.max'=>'品种介绍内容(英)请勿超过65535个字元',
            'VarietyMainImage.max'=>'品种介绍主图内容请勿超过100个字元',
            'OrderNumber.required'=>'请输入排序号码',
            'OrderNumber.integer'=>'排序号码仅能输入整数',
            'Status.required'=>'请选择是否启用',
            'Status.integer'=>'请选择是否启用',
            'Status.in'=>'请选择是否启用'
        );
        $this->_colour_rule_array=array(
            'Colour'=>'required|max:40',
            'ColourEn'=>'nullable|max:40',
            'OrderNumber'=>'required|integer',
            'Status'=>'required|integer|in:0,1'
        );
        $this->_colour_rule_messages=array(
            'Colour.required'=>'请输入色泽',
            'Colour.max'=>'色泽最多输入40个字元',
            'ColourEn.max'=>'色泽(英)最多输入40个字元',
            'OrderNumber.required'=>'请输入排序号码',
            'OrderNumber.integer'=>'排序号码仅能输入整数',
            'Status.required'=>'请选择是否启用',
            'Status.integer'=>'请选择是否启用',
            'Status.in'=>'请选择是否启用'
        );
        $this->_region_rule_array=array(
            'Region'=>'required|max:40',
            'RegionEn'=>'nullable|max:40',
            'OrderNumber'=>'required|integer',
            'Status'=>'required|integer|in:0,1',
            'RegionDesc'=>'nullable|max:65535',
            'RegionDescEn'=>'nullable|max:65535',
            'RegionMainImage'=>'nullable|max:100',
        );
        $this->_region_rule_messages=array(
            'Region.required'=>'请输入产区',
            'Region.max'=>'产区最多输入40个字元',
            'RegionEn.max'=>'产区(英)最多输入40个字元',
            'OrderNumber.required'=>'请输入排序号码',
            'OrderNumber.integer'=>'排序号码仅能输入整数',
            'RegionDesc.max'=>'产区介绍内容请勿超过65535个字元',
            'RegionDescEn.max'=>'产区介绍内容(英)请勿超过65535个字元',
            'RegionMainImage.max'=>'产区介绍主图内容请勿超过100个字元',            
            'Status.required'=>'请选择是否启用',
            'Status.integer'=>'请选择是否启用',
            'Status.in'=>'请选择是否启用'
        );
        $this->_catering_rule_array=array(
            'Catering'=>'required|max:40',
            'CateringEn'=>'nullable|max:40',
            'CateringPic'=>'required',
            'Status'=>'required|integer|in:0,1',
            'Memo'=>'nullable|max:100'
        );
        $this->_catering_rule_messages=array(
            'Catering.required'=>'请输入配餐名称',
            'Catering.max'=>'配餐名称最多输入40个字元',
            'CateringEn.max'=>'配餐名称(英)最多输入40个字元',
            'CateringPic.required'=>'请选择配餐图片',
            'Status.required'=>'请选择是否启用',
            'Status.integer'=>'请选择是否启用',
            'Status.in'=>'请选择是否启用',
            'Memo.max'=>'最多输入100个字元'
        ); 
        $this->_closure_rule_array=array(
            'Closure'=>'required|max:40',
            'ClosureEn'=>'nullable|max:40',
            'OrderNumber'=>'required|integer',
            'Status'=>'required|integer|in:0,1'
        );
        $this->_closure_rule_messages=array(
            'Closure.required'=>'请输入包装方式',
            'Closure.max'=>'包装方式最多输入40个字元',
            'ClosureEn.max'=>'包装方式(英)最多输入40个字元',
            'OrderNumber.required'=>'请输入排序号码',
            'OrderNumber.integer'=>'排序号码仅能输入整数',
            'Status.required'=>'请选择是否启用',
            'Status.integer'=>'请选择是否启用',
            'Status.in'=>'请选择是否启用'
        ); 
        $this->_style_rule_array=array(
            'Style'=>'required|max:40',
            'StyleEn'=>'nullable|max:40',
            'StyleDesc'=>'nullable|max:65535',
            'StyleDescEn'=>'nullable|max:65535',
            'StyleMainImage'=>'nullable|max:100',
            'OrderNumber'=>'required|integer',
            'Status'=>'required|integer|in:0,1'
        );
        $this->_style_rule_messages=array(
            'Style.required'=>'请输入风格名称',
            'Style.max'=>'风格名称最多输入40个字元',
            'StyleEn.max'=>'风格名称(英)最多输入40个字元',
            'OrderNumber.required'=>'请输入排序号码',
            'OrderNumber.integer'=>'排序号码仅能输入整数',
            'StyleDesc.max'=>'风格介绍内容请勿超过65535个字元',
            'StyleDescEn.max'=>'风格介绍内容(英)请勿超过65535个字元',
            'StyleMainImage.max'=>'风格介绍主图内容请勿超过100个字元',            
            'Status.required'=>'请选择是否启用',
            'Status.integer'=>'请选择是否启用',
            'Status.in'=>'请选择是否启用'
        );                
    }

    public function varieties(){
        $varieties=DB::table('wine_varieties')->orderBy('OrderNumber','ASC')->orderBy('Status','DESC')->get();
        $data['varieties']=$varieties;
        $data['currentPage']='products';
        $data['header_title']='Variety List';
        return view('admin.products.list_varieties',$data);        
    }

    public function add_variety(Request $request){
        if($request->isMethod('post')){
            $build_validator=Validator::make($request->all(),$this->_variety_rule_array,$this->_variety_rule_messages);
            if($build_validator->fails()){
                return redirect()->back()->withInput()->withErrors($build_validator->errors());
            }

            $VarietyMainImage=trim(request('VarietyMainImage'));
            $Variety=request('Variety');
            $VarietyEn=request('VarietyEn');
            $VarietyDesc=trim(request('VarietyDesc'));
            $VarietyDescEn=trim(request('VarietyDescEn'));      
            $OrderNumber=intval(request('OrderNumber'));
            $Status=intval(request('Status'));
            $Maintainer=Session::get('AdminId');

            // check same category name
            $exist=DB::table('wine_varieties')->where('Variety',$Variety)->take(1)->first();
            if($exist){
                Session::flash('maintain_message',true);
                Session::flash('maintain_message_warning','已有相同名称的品种存在。');
                return redirect()->back()->withInput()->withErrors($build_validator->errors());
            }

            if(DB::table('wine_varieties')->insert(array(
                'VarietyMainImage'=>$VarietyMainImage,
                'Variety'=>$Variety,
                'VarietyEn'=>$VarietyEn,
                'VarietyDesc'=>$VarietyDesc,
                'VarietyDescEn'=>$VarietyDescEn,
                'Status'=>$Status,
                'OrderNumber'=>$OrderNumber,
                'Maintainer'=>$Maintainer,
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s')
            ))){
                return redirect('/admin/products/varieties');
            }
            else{
                Session::flash('maintain_message',true);
                Session::flash('maintain_message_fail','新增品种失败。');
                return redirect()->back()->withInput()->withErrors($build_validator->errors());
            }
        }
        $data['currentPage']='products';
        $data['header_title']='Add Variety';        
        return view('admin.products.add_variety',$data);
    }

    public function edit_variety($id,Request $request){
        $id=intval($id);
        $varietyInfo=DB::table("wine_varieties")->where('VarietyId',$id)->take(1)->first();
        if($varietyInfo){
            if($request->isMethod('post')){
                $build_validator=Validator::make($request->all(),$this->_variety_rule_array,$this->_variety_rule_messages);
                if($build_validator->fails()){
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }

                $VarietyMainImage=trim(request('VarietyMainImage'));
                $Variety=request('Variety');
                $VarietyEn=request('VarietyEn');
                $VarietyDesc=trim(request('VarietyDesc'));
                $VarietyDescEn=trim(request('VarietyDescEn'));
                $OrderNumber=intval(request('OrderNumber'));
                $Status=intval(request('Status'));
                $Maintainer=Session::get('AdminId');

                // check same category name
                $exist=DB::table('wine_varieties')->where('Variety',$Variety)->where('VarietyId','!=',$id)->take(1)->first();
                if($exist){
                    Session::flash('maintain_message',true);
                    Session::flash('maintain_message_warning','已有相同名称的品种存在。');
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());                
                }

                if(DB::table('wine_varieties')->where('VarietyId',$id)->update(array(
                    'VarietyMainImage'=>$VarietyMainImage,
                    'Variety'=>$Variety,
                    'VarietyEn'=>$VarietyEn,
                    'VarietyDesc'=>$VarietyDesc,
                    'VarietyDescEn'=>$VarietyDescEn,
                    'Status'=>$Status,
                    'OrderNumber'=>$OrderNumber,
                    'Maintainer'=>$Maintainer,
                    'updated_at'=>date('Y-m-d H:i:s')
                ))){
                    return redirect('/admin/products/varieties');
                }
                else{
                    Session::flash('maintain_message',true);
                    Session::flash('maintain_message_fail','更新酒类品种失败。');
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }
            }
            else{
                $data['varietyInfo']=$varietyInfo;
                $data['currentPage']='products';
                $data['header_title']='Edit Variety';
                return view('admin.products.edit_variety',$data);
            }
        }
        return redirect('/admin/products/varieties');
    }

    public function delete_variety($id){
        DB::table('wine_varieties')->where('VarietyId',$id)->delete();
        return redirect('/admin/products/varieties');
    }

    public function colours(){
        $colours=DB::table('wine_colours')->orderBy('OrderNumber','ASC')->orderBy('Status','DESC')->get();
        $data['colours']=$colours;
        $data['currentPage']='products';
        $data['header_title']='Color List';
        return view('admin.products.list_colours',$data); 
    }

    public function add_colour(Request $request){
        if($request->isMethod('post')){
            $build_validator=Validator::make($request->all(),$this->_colour_rule_array,$this->_colour_rule_messages);
            if($build_validator->fails()){
                return redirect()->back()->withInput()->withErrors($build_validator->errors());
            }

            $Colour=request('Colour');
            $ColourEn=request('ColourEn');
            $OrderNumber=intval(request('OrderNumber'));
            $Status=intval(request('Status'));
            $Maintainer=Session::get('AdminId');

            // check same category name
            $exist=DB::table('wine_colours')->where('Colour',$Colour)->take(1)->first();
            if($exist){
                Session::flash('maintain_message',true);
                Session::flash('maintain_message_warning','已有相同名称的色泽存在。');
                return redirect()->back()->withInput()->withErrors($build_validator->errors());                
            }

            if(DB::table('wine_colours')->insert(array(
                'Colour'=>$Colour,
                'ColourEn'=>$ColourEn,
                'Status'=>$Status,
                'OrderNumber'=>$OrderNumber,
                'Maintainer'=>$Maintainer,
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s')
            ))){
                return redirect('/admin/products/colours');
            }
            else{
                Session::flash('maintain_message',true);
                Session::flash('maintain_message_fail','新增色泽失败。');
                return redirect()->back()->withInput()->withErrors($build_validator->errors());
            }
        }
        $data['currentPage']='products';
        $data['header_title']='Add Color';        
        return view('admin.products.add_colour',$data);
    }

    public function edit_colour($id,Request $request){
        $id=intval($id);
        $colourInfo=DB::table("wine_colours")->where('ColourId',$id)->take(1)->first();
        if($colourInfo){
            if($request->isMethod('post')){
                $build_validator=Validator::make($request->all(),$this->_colour_rule_array,$this->_colour_rule_messages);
                if($build_validator->fails()){
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }

                $Colour=request('Colour');
                $ColourEn=request('ColourEn');
                $OrderNumber=intval(request('OrderNumber'));
                $Status=intval(request('Status'));
                $Maintainer=Session::get('AdminId');

                // check same category name
                $exist=DB::table('wine_colours')->where('Colour',$Colour)->where('ColourId','!=',$id)->take(1)->first();
                if($exist){
                    Session::flash('maintain_message',true);
                    Session::flash('maintain_message_warning','已有相同名称的色泽存在。');
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());                
                }

                if(DB::table('wine_colours')->where('ColourId',$id)->update(array(
                    'Colour'=>$Colour,
                    'ColourEn'=>$ColourEn,
                    'Status'=>$Status,
                    'OrderNumber'=>$OrderNumber,
                    'Maintainer'=>$Maintainer,
                    'updated_at'=>date('Y-m-d H:i:s')
                ))){
                    return redirect('/admin/products/colours');
                }
                else{
                    Session::flash('maintain_message',true);
                    Session::flash('maintain_message_fail','更新酒类色泽失败。');
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }
            }
            else{
                $data['colourInfo']=$colourInfo;
                $data['currentPage']='products';
                $data['header_title']='Edit Color';
                return view('admin.products.edit_colour',$data);
            }
        }
        return redirect('/admin/products/colours');
    }

    public function delete_colour($id){
        DB::table('wine_colours')->where('ColourId',$id)->delete();
        return redirect('/admin/products/colours');
    }

    public function regions(){
        $regions=DB::table('wine_regions')->orderBy('OrderNumber','ASC')->orderBy('Status','DESC')->get();
        $data['regions']=$regions;
        $data['currentPage']='products';
        $data['header_title']='Region List';
        return view('admin.products.list_regions',$data);
    }

    public function add_region(Request $request){
        if($request->isMethod('post')){
            $build_validator=Validator::make($request->all(),$this->_region_rule_array,$this->_region_rule_messages);
            if($build_validator->fails()){
                return redirect()->back()->withInput()->withErrors($build_validator->errors());
            }

            $Region=request('Region');
            $RegionEn=request('RegionEn');
            $RegionDesc=request('RegionDesc');
            $RegionDescEn=request('RegionDescEn');
            $RegionMainImage=request('RegionMainImage');
            $OrderNumber=intval(request('OrderNumber'));
            $Status=intval(request('Status'));
            $Maintainer=Session::get('AdminId');

            // check same category name
            $exist=DB::table('wine_regions')->where('Region',$Region)->take(1)->first();
            if($exist){
                Session::flash('maintain_message',true);
                Session::flash('maintain_message_warning','已有相同名称的产区存在。');
                return redirect()->back()->withInput()->withErrors($build_validator->errors());                
            }

            if(DB::table('wine_regions')->insert(array(
                'Region'=>$Region,
                'RegionEn'=>$RegionEn,
                'RegionDesc'=>$RegionDesc,
                'RegionDescEn'=>$RegionDescEn,
                'RegionMainImage'=>$RegionMainImage,
                'Status'=>$Status,
                'OrderNumber'=>$OrderNumber,
                'Maintainer'=>$Maintainer,
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s')
            ))){
                return redirect('/admin/products/regions');
            }
            else{
                Session::flash('maintain_message',true);
                Session::flash('maintain_message_fail','新增产区失败。');
                return redirect()->back()->withInput()->withErrors($build_validator->errors());
            }
        }
        $data['currentPage']='products';
        $data['header_title']='Add Region';        
        return view('admin.products.add_region',$data);
    }

    public function edit_region($id,Request $request){
        $id=intval($id);
        $regionInfo=DB::table("wine_regions")->where('RegionId',$id)->take(1)->first();
        if($regionInfo){
            if($request->isMethod('post')){
                $build_validator=Validator::make($request->all(),$this->_region_rule_array,$this->_region_rule_messages);
                if($build_validator->fails()){
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }

                $Region=request('Region');
                $RegionEn=request('RegionEn');
                $RegionDesc=request('RegionDesc');
                $RegionDescEn=request('RegionDescEn');
                $RegionMainImage=request('RegionMainImage');                
                $OrderNumber=intval(request('OrderNumber'));
                $Status=intval(request('Status'));
                $Maintainer=Session::get('AdminId');

                // check same category name
                $exist=DB::table('wine_regions')->where('Region',$Region)->where('RegionId','!=',$id)->take(1)->first();
                if($exist){
                    Session::flash('maintain_message',true);
                    Session::flash('maintain_message_warning','已有相同名称的产区存在。');
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());                
                }

                if(DB::table('wine_regions')->where('RegionId',$id)->update(array(
                    'Region'=>$Region,
                    'RegionEn'=>$RegionEn,
                    'RegionDesc'=>$RegionDesc,
                    'RegionDescEn'=>$RegionDescEn,
                    'RegionMainImage'=>$RegionMainImage,                    
                    'Status'=>$Status,
                    'OrderNumber'=>$OrderNumber,
                    'Maintainer'=>$Maintainer,
                    'updated_at'=>date('Y-m-d H:i:s')
                ))){
                    return redirect('/admin/products/regions');
                }
                else{
                    Session::flash('maintain_message',true);
                    Session::flash('maintain_message_fail','更新产区失败。');
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }
            }
            else{
                $data['regionInfo']=$regionInfo;
                $data['currentPage']='products';
                $data['header_title']='Edit Region';
                return view('admin.products.edit_region',$data);
            }
        }
        return redirect('/admin/products/regions');
    }

    public function delete_region($id){
        DB::table('wine_regions')->where('RegionId',$id)->delete();
        return redirect('/admin/products/regions');
    }

    public function caterings(){
        $caterings=CateringsData::orderBy('Status','DESC')->orderBy('updated_at','DESC')->get();
        $data['caterings']=$caterings;
        $data['currentPage']='products';
        $data['header_title']='Catering List';
        return view('admin.products.list_caterings',$data);
    }

    public function add_catering(Request $request){
        if($request->isMethod('post')){
            $build_validator=Validator::make($request->all(),$this->_catering_rule_array,$this->_catering_rule_messages);
            if($build_validator->fails()){
                return redirect()->back()->withInput()->withErrors($build_validator->errors());
            }
            $Catering=trim(request('Catering'));
            $CateringEn=trim(request('CateringEn'));
            $CateringPic=trim(request('CateringPic'));
            $Status=intval(request('Status'));
            $Memo=trim(request('Memo'));

            // check same category name
            $exist=CateringsData::where('Catering',$Catering)->take(1)->first();
            if($exist){
                Session::flash('maintain_message',true);
                Session::flash('maintain_message_warning','已有相同名称的配餐存在。');
                return redirect()->back()->withInput()->withErrors($build_validator->errors());                
            }

            $newCatering=new CateringsData;
            $newCatering->Catering=$Catering;
            $newCatering->CateringEn=$CateringEn;
            $newCatering->CateringPic=$CateringPic;
            $newCatering->Status=$Status;
            $newCatering->Memo=$Memo;
            $newCatering->Maintainer=Session::get('AdminId');
            $newCatering->created_at=date('Y-m-d H:i:s');
            $newCatering->updated_at=date('Y-m-d H:i:s');
            if($newCatering->save()){
                return redirect('/admin/products/caterings');
            }
            else{
                Session::flash('maintain_message',true);
                Session::flash('maintain_message_fail','新增配餐失败。');
                return redirect()->back()->withInput()->withErrors($build_validator->errors());                
            }
        }
        $data['currentPage']='products';
        $data['header_title']='Add Catering';
        return view('admin.products.add_catering',$data);
    }

    public function edit_catering($id,Request $request){
        $id=intval($id);
        $cateringInfo=CateringsData::where('CateringId',$id)->take(1)->first();
        if($cateringInfo){
            if($request->isMethod('post')){
                $build_validator=Validator::make($request->all(),$this->_catering_rule_array,$this->_catering_rule_messages);
                if($build_validator->fails()){
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }
                $Catering=trim(request('Catering'));
                $CateringEn=trim(request('CateringEn'));
                $CateringPic=trim(request('CateringPic'));
                $Status=intval(request('Status'));
                $Memo=trim(request('Memo'));

                // check same category name
                $exist=CateringsData::where('Catering',$Catering)->where('CateringId','!=',$id)->take(1)->first();
                if($exist){
                    Session::flash('maintain_message',true);
                    Session::flash('maintain_message_warning','已有相同名称的配餐存在。');
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());                
                }

                if(CateringsData::where('CateringId',$id)->update(array(
                    'Catering'=>$Catering,
                    'CateringEn'=>$CateringEn,
                    'CateringPic'=>$CateringPic,
                    'Status'=>$Status,
                    'Memo'=>$Memo,
                    'updated_at'=>date('Y-m-d H:i:s')
                ))){
                    return redirect('/admin/products/caterings');
                }
                else{
                    Session::flash('maintain_message',true);
                    Session::flash('maintain_message_fail','更新配餐失败。');
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());                
                }
            }
            $data['currentPage']='products';
            $data['header_title']='Edit Catering';
            $data['cateringInfo']=$cateringInfo;
            return view('admin.products.edit_catering',$data);
        }
        return reidrect('/admin/products/caterings');
    }

    public function delete_catering($id){
        CateringsData::where('CateringId',$id)->delete();
        return redirect('/admin/products/caterings');
    }

    public function closures(){
        $closures=DB::table('wine_closures')->orderBy('OrderNumber','ASC')->orderBy('Status','DESC')->get();
        $data['closures']=$closures;
        $data['currentPage']='products';
        $data['header_title']='Package Method';
        return view('admin.products.list_closures',$data);         
    }

    public function add_closure(Request $request){
        if($request->isMethod('post')){
            $build_validator=Validator::make($request->all(),$this->_closure_rule_array,$this->_closure_rule_messages);
            if($build_validator->fails()){
                return redirect()->back()->withInput()->withErrors($build_validator->errors());
            }

            $Closure=request('Closure');
            $ClosureEn=request('ClosureEn');
            $OrderNumber=intval(request('OrderNumber'));
            $Status=intval(request('Status'));
            $Maintainer=Session::get('AdminId');

            // check same category name
            $exist=DB::table('wine_closures')->where('Closure',$Closure)->take(1)->first();
            if($exist){
                Session::flash('maintain_message',true);
                Session::flash('maintain_message_warning','已有相同名称的包装方式存在。');
                return redirect()->back()->withInput()->withErrors($build_validator->errors());                
            }

            if(DB::table('wine_closures')->insert(array(
                'Closure'=>$Closure,
                'ClosureEn'=>$ClosureEn,
                'Status'=>$Status,
                'OrderNumber'=>$OrderNumber,
                'Maintainer'=>$Maintainer,
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s')
            ))){
                return redirect('/admin/products/closures');
            }
            else{
                Session::flash('maintain_message',true);
                Session::flash('maintain_message_fail','新增包装方式失败。');
                return redirect()->back()->withInput()->withErrors($build_validator->errors());
            }            
        }
        $data['currentPage']='products';
        $data['header_title']='Add Package';
        return view('admin.products.add_closure',$data);
    }

    public function edit_closure($id,Request $request){
        $id=intval($id);
        $closureInfo=DB::table("wine_closures")->where('ClosureId',$id)->take(1)->first();
        if($closureInfo){
            if($request->isMethod('post')){
                $build_validator=Validator::make($request->all(),$this->_closure_rule_array,$this->_closure_rule_messages);
                if($build_validator->fails()){
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }

                $Closure=request('Closure');
                $ClosureEn=request('ClosureEn');
                $OrderNumber=intval(request('OrderNumber'));
                $Status=intval(request('Status'));
                $Maintainer=Session::get('AdminId');

                // check same category name
                $exist=DB::table('wine_closures')->where('Closure',$Closure)->where('ClosureId','!=',$id)->take(1)->first();
                if($exist){
                    Session::flash('maintain_message',true);
                    Session::flash('maintain_message_warning','已有相同名称的包装方式存在。');
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());                
                }

                if(DB::table('wine_closures')->where('ClosureId',$id)->update(array(
                    'Closure'=>$Closure,
                    'ClosureEn'=>$ClosureEn,
                    'Status'=>$Status,
                    'OrderNumber'=>$OrderNumber,
                    'Maintainer'=>$Maintainer,
                    'updated_at'=>date('Y-m-d H:i:s')
                ))){
                    return redirect('/admin/products/closures');
                }
                else{
                    Session::flash('maintain_message',true);
                    Session::flash('maintain_message_fail','更新包装方式失败。');
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }
            }
            else{
                $data['closureInfo']=$closureInfo;
                $data['currentPage']='products';
                $data['header_title']='Edit Package';
                return view('admin.products.edit_closure',$data);
            }
        }
        return redirect('/admin/products/closures');        
    }

    public function delete_closure($id){
        DB::table('wine_closures')->where('ClosureId',$id)->delete();
        return redirect('/admin/products/closures');        
    }

    public function styles(){
        $styles=DB::table('wine_styles')->orderBy('OrderNumber','ASC')->orderBy('Status','DESC')->get();
        $data['styles']=$styles;
        $data['currentPage']='products';
        $data['header_title']='Style List';
        return view('admin.products.list_styles',$data);  
    }

    public function add_style(Request $request){
        if($request->isMethod('post')){
            $build_validator=Validator::make($request->all(),$this->_style_rule_array,$this->_style_rule_messages);
            if($build_validator->fails()){
                return redirect()->back()->withInput()->withErrors($build_validator->errors());
            }

            $Style=request('Style');
            $StyleEn=request('StyleEn');
            $StyleDesc=trim(request('StyleDesc'));
            $StyleDescEn=trim(request('StyleDescEn'));
            $StyleMainImage=trim(request('StyleMainImage'));            
            $OrderNumber=intval(request('OrderNumber'));
            $Status=intval(request('Status'));
            $Maintainer=Session::get('AdminId');

            // check same category name
            $exist=DB::table('wine_styles')->where('Style',$Style)->take(1)->first();
            if($exist){
                Session::flash('maintain_message',true);
                Session::flash('maintain_message_warning','已有相同名称的风格存在。');
                return redirect()->back()->withInput()->withErrors($build_validator->errors());                
            }

            if(DB::table('wine_styles')->insert(array(
                'Style'=>$Style,
                'StyleEn'=>$StyleEn,
                'Status'=>$Status,
                'StyleDesc'=>$StyleDesc,
                'StyleDescEn'=>$StyleDescEn,
                'StyleMainImage'=>$StyleMainImage,                
                'OrderNumber'=>$OrderNumber,
                'Maintainer'=>$Maintainer,
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s')
            ))){
                return redirect('/admin/products/styles');
            }
            else{
                Session::flash('maintain_message',true);
                Session::flash('maintain_message_fail','新增风格失败。');
                return redirect()->back()->withInput()->withErrors($build_validator->errors());
            }
        }
        $data['currentPage']='products';
        $data['header_title']='Add Style';
        return view('admin.products.add_style',$data);
    }

    public function edit_style($id,Request $request){
        $id=intval($id);
        $styleInfo=DB::table("wine_styles")->where('StyleId',$id)->take(1)->first();
        if($styleInfo){
            if($request->isMethod('post')){
                $build_validator=Validator::make($request->all(),$this->_style_rule_array,$this->_style_rule_messages);
                if($build_validator->fails()){
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }

                $Style=request('Style');
                $StyleEn=request('StyleEn');
                $StyleDesc=trim(request('StyleDesc'));
                $StyleDescEn=trim(request('StyleDescEn'));
                $StyleMainImage=trim(request('StyleMainImage'));
                $OrderNumber=intval(request('OrderNumber'));
                $Status=intval(request('Status'));
                $Maintainer=Session::get('AdminId');

                // check same category name
                $exist=DB::table('wine_styles')->where('Style',$Style)->where('StyleId','!=',$id)->take(1)->first();
                if($exist){
                    Session::flash('maintain_message',true);
                    Session::flash('maintain_message_warning','已有相同名称的风格存在。');
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());                
                }

                if(DB::table('wine_styles')->where('StyleId',$id)->update(array(
                    'Style'=>$Style,
                    'StyleEn'=>$StyleEn,
                    'StyleDesc'=>$StyleDesc,
                    'StyleDescEn'=>$StyleDescEn,
                    'StyleMainImage'=>$StyleMainImage,
                    'Status'=>$Status,
                    'OrderNumber'=>$OrderNumber,
                    'Maintainer'=>$Maintainer,
                    'updated_at'=>date('Y-m-d H:i:s')
                ))){
                    return redirect('/admin/products/styles');
                }
                else{
                    Session::flash('maintain_message',true);
                    Session::flash('maintain_message_fail','更新风格失败。');
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }
            }
            else{
                $data['styleInfo']=$styleInfo;
                $data['currentPage']='products';
                $data['header_title']='Edit Style';
                return view('admin.products.edit_style',$data);
            }
        }
        return redirect('/admin/products/styles');
    }

    public function delete_style($id){
        DB::table('wine_styles')->where('StyleId',$id)->delete();
        return redirect('/admin/products/styles');
    }
}