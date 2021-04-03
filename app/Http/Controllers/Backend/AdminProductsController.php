<?php
namespace App\Http\Controllers\Backend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Products\CasesData;
use App\Products\SalesMixData;
use App\Products\WinesData;

use Widget_Helper;
use Validator;
use Session;
use Config;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class AdminProductsController extends Controller
{
    private $_product_rule_array;
    private $_product_rule_messages;          
    public function __construct(){
        $this->_product_rule_array=array(
            'Image'=>'required',
            'IntroductionPDF'=>'nullable|max:100',
            'Name'=>'required|max:255',
            'NameEn'=>'nullable|max:255',
            'Price'=>'required|numeric|between:0,99999.99',
            'S_price'=>'nullable|numeric|between:0,99999.99',
            'WineCatg'=>'required|integer|in:1,2,3',
            'WineStyle'=>'nullable|integer',
            'WineVariety'=>'nullable|integer',
            'WineColour'=>'nullable|integer',
            'WineRegion'=>'nullable|integer',
            'WineClosure'=>'nullable|integer',
            'Stocks'=>'required|integer',
            'ActDate'=>'required|date',
            'EndDate'=>'nullable|date|required_if:noOffShelf,""',
            'OrderNumber'=>'required|integer|min:1',
            'Status'=>'required|integer|in:0,1',
            'WeeklyReco'=>'nullable|integer|in:0,1',
            'Flagship'=>'nullable|integer|in:0,1',
            'Volume'=>'required|integer',
            'Alcohol'=>'required|between:0,99999.99'
        );
        $this->_product_rule_messages=array(
            'Image.required'=>'请选择商品图片',
            'IntroductionPDF.max'=>'PDF路径与档名最多支援至100个字元',
            'Name.required'=>'请输入商品名称',
            'Name.max'=>'商品名称最多输入255个字元',
            'NameEn.max'=>'商品名称(英)最多输入255个字元',
            'Price.required'=>'请输入商品金额',
            'Price.numeric'=>'商品金额仅能输入数字',
            'Price.between'=>'输入的商品金额超过范围',
            'S_price.required'=>'请输入商品金额',
            'S_price.numeric'=>'商品金额仅能输入数字',
            'S_price.between'=>'输入的商品金额超过范围',
            'WineCatg.required'=>'请选择酒类',
            'WineCatg.integer'=>'请选择酒类',
            'WineCatg.in'=>'请选择酒类',
            'WineVariety.integer'=>'请选择正确的品种',
            'WineColour.integer'=>'请选择正确的酒类色泽',
            'WineCountry.in'=>'请选择正确的国家',
            'WineRegion.integer'=>'请选择正确的产区',
            'WineStyle.integer'=>'请选择正确的风格',
            'WineClosure.integer'=>'请选择正确的包装方式',
            'Stocks.required'=>'请输入库存数量',
            'Stocks.integer'=>'库存数量仅能输入整数',
            'ActDate.required'=>'请输入销售日期(起)',
            'ActDate.date'=>'销售日期(起)格式有误',
            'EndDate.date'=>'销售日期(讫)格式有误',
            'EndDate.required_if'=>'请输入销售日期(讫)或选择永不下架',
            'OrderNumber.required'=>'请输入排序号码',
            'OrderNumber.integer'=>'排序号码仅能输入整数',
            'OrderNumber.min'=>'排序号码最小须为1',
            'Status.required'=>'请选择是否启用',
            'Status.integer'=>'请选择是否启用',
            'Status.in'=>'请选择是否启用',
            'WeeklyReco.integer'=>'请选择是否为当周推荐',
            'WeeklyReco.in'=>'请选择是否为当周推荐',
            'Flagship.integer'=>'请选择是否为首页主打商品',
            'Flagship.in'=>'请选择是否为首页主打商品',
            'Volume.required'=>'请输入商品容量',
            'Volume.integer'=>'请输入商品容量',
            'Alcohol.required'=>'请输入酒精浓度',
            'Alcohol.between'=>'酒精浓度的格式有误'
        );
    }

    public function products(Request $request){
        $whereConditions=array();
        $searchQ = "";
        $searchType = "";
        if($request->has('type') && $request->has('q')){
            if(trim(request('type'))!=='' && trim(request('q'))!==''){
                $searchType = request('type');
                $searchQ = request('q');
                if($searchType==='name'){
                    array_push($whereConditions,array('Name','LIKE','%'.$searchQ.'%'));
                }
            }
        }

        $wines=WinesData::where(function($query) use ($whereConditions){
            if(count($whereConditions)>0){
                foreach($whereConditions as $condition){
                    $query->orWhere($condition[0],$condition[1],$condition[2]);
                }
            }
        })->orderBy('Status','DESC')->orderBy('OrderNumber','ASC')->paginate(10);

        $data['searchType'] = $searchType;
        $data['searchQ'] = $searchQ;
        $data['wines']=$wines;
        $data['currentPage']='products';
        $data['header_title']='Product List';
        return view('admin.products.list',$data);
    }

    public function add_product(Request $request){
        if($request->isMethod('post')){
            $build_validator=Validator::make($request->all(),$this->_product_rule_array,$this->_product_rule_messages);
            if($build_validator->fails()){
                return redirect()->back()->withInput()->withErrors($build_validator->errors());
            }

            $Image=trim(request('Image'));
            $Image2=trim(request('Image2'));
            $Image3=trim(request('Image3'));
            $Name=trim(request('Name'));
            $NameEn=trim(request('NameEn'));
            $Price=trim(request('Price'));
            $S_price=trim(request('S_price'));
            $Volume=intval(request('Volume'));
            $Alcohol=trim(request('Alcohol'));
            $WineCatg=intval(request('WineCatg'));
            $WineStyle=intval(request('WineStyle'));
            $WineVariety=intval(request('WineVariety'));
            $WineColour=intval(request('WineColour'));
            $WineCountry=trim(request('WineCountry'));
            $WineRegion=intval(request('WineRegion'));
            $WineClosure=intval(request('WineClosure'));
            $WineCaterings=json_encode(request('WineCaterings'));
            $Stocks=intval(request('Stocks'));
            $ActDate=request('ActDate');
            $EndDate=request('EndDate');
            $noOffShelf=trim(request('noOffShelf'));
            $BriefDesc=request('BriefDesc');
            $Description=request('Description');
            $Delivery_Returns=request('Delivery_Returns');
            $BriefDescEn=request('BriefDescEn');
            $DescriptionEn=request('DescriptionEn');
            $Delivery_ReturnsEn=request('Delivery_ReturnsEn');            
            $OrderNumber=intval(request('OrderNumber'));
            $Status=intval(request('Status'));
            $WeeklyReco=intval(request('WeeklyReco'));
            $Flagship=intval(request('Flagship'));
            $IntroductionPDF=trim(request('IntroductionPDF'));
            if($Flagship===1){
                WinesData::where('Flagship',1)->update(array('Flagship'=>0));
            }

            if($noOffShelf==='on'){
                $NoOffShelf=1;
            }
            else{
                $NoOffShelf=0;
                if($EndDate===''){
                    $EndDate=date('Y-m-d 23:59:59',strtotime('+30 days',strtotime(date('Y-m-d'))));
                }
            }

            $newProduct=new WinesData;
            $newProduct->WineId=Widget_Helper::createID();
            $newProduct->Image=$Image;
            $newProduct->Image2=$Image2;
            $newProduct->Image3=$Image3;
            $newProduct->IntroductionPDF=$IntroductionPDF;
            $newProduct->Name=$Name;
            $newProduct->NameEn=$NameEn;
            $newProduct->Price=$Price;
            $newProduct->S_price=$S_price;
            $newProduct->Volume=$Volume;
            $newProduct->Alcohol=$Alcohol;
            $newProduct->WineCatg=$WineCatg;
            $newProduct->WineStyle=$WineStyle;
            $newProduct->WineVariety=$WineVariety;
            $newProduct->WineColour=$WineColour;
            $newProduct->WineCountry=$WineCountry;
            $newProduct->WineRegion=$WineRegion;
            $newProduct->WineClosure=$WineClosure;
            $newProduct->WineCaterings=$WineCaterings;
            $newProduct->Stocks=$Stocks;
            $newProduct->ActDate=$ActDate;
            $newProduct->EndDate=$EndDate;
            $newProduct->NoOffShelf=$NoOffShelf;
            $newProduct->BriefDesc=$BriefDesc;
            $newProduct->Description=$Description;
            $newProduct->Delivery_Returns=$Delivery_Returns;
            $newProduct->BriefDescEn=$BriefDescEn;
            $newProduct->DescriptionEn=$DescriptionEn;
            $newProduct->Delivery_ReturnsEn=$Delivery_ReturnsEn;            
            $newProduct->OrderNumber=$OrderNumber;
            $newProduct->Status=$Status;
            $newProduct->WeeklyReco=$WeeklyReco;
            $newProduct->Flagship=$Flagship;
            $newProduct->Maintainer=Session::get('AdminId');
            $newProduct->created_at=date('Y-m-d H:i:s');
            $newProduct->updated_at=date('Y-m-d H:i:s');
            if($newProduct->save()){
                return redirect('/admin/products');
            }
            else{
                Session::flash('maintain_message',true);
                Session::flash('maintain_message_fail','新增商品失败。');
                return redirect()->back()->withInput()->withErrors($build_validator->errors());
            }
        }
        $varieties=DB::table('wine_varieties')->orderBy('OrderNumber','ASC')->orderBy('Status','DESC')->get();
        $colours=DB::table('wine_colours')->orderBy('OrderNumber','ASC')->orderBy('Status','DESC')->get();
        $regions=DB::table('wine_regions')->orderBy('OrderNumber','ASC')->orderBy('Status','DESC')->get();
        $caterings=DB::table('wine_caterings')->orderBy('Status','DESC')->get();
        $closures=DB::table('wine_closures')->orderBy('OrderNumber','ASC')->orderBy('Status','DESC')->get();
        $styles=DB::table('wine_styles')->orderBy('OrderNumber','ASC')->orderBy('Status','DESC')->get();

        $countryQuery=DB::table('countries')->orderBy('CountryId','ASC')->get();
        $countries=array();
        foreach($countryQuery as $country){
            $countries[$country->CountryId]=$country->Country;
        }

        $data['varieties']=$varieties;
        $data['colours']=$colours;
        $data['regions']=$regions;
        $data['closures']=$closures;
        $data['styles']=$styles;
        $data['caterings']=$caterings;
        $data['countries']=$countries;
        $data['currentPage']='products';
        $data['header_title']='Add Product';
        return view('admin.products.add_product',$data);
    }

    public function edit_product($id,Request $request){
        $wineInfo=WinesData::where('WineId',$id)->take(1)->first();
        if($wineInfo){
            if($request->isMethod('post')){
                $build_validator=Validator::make($request->all(),$this->_product_rule_array,$this->_product_rule_messages);
                if($build_validator->fails()){
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }

                $Image=trim(request('Image'));
                $Image2=trim(request('Image2'));
                $Image3=trim(request('Image3'));
                $IntroductionPDF=trim(request('IntroductionPDF'));
                $Name=trim(request('Name'));
                $NameEn=trim(request('NameEn'));
                $Price=trim(request('Price'));
                $S_price=trim(request('S_price'));
                $Volume=intval(request('Volume'));
                $Alcohol=trim(request('Alcohol'));
                $WineCatg=intval(request('WineCatg'));
                $WineStyle=intval(request('WineStyle'));
                $WineVariety=intval(request('WineVariety'));
                $WineColour=intval(request('WineColour'));
                $WineCountry=trim(request('WineCountry'));
                $WineRegion=intval(request('WineRegion'));
                $WineClosure=intval(request('WineClosure'));
                $WineCaterings=json_encode(request('WineCaterings'));
                $Stocks=intval(request('Stocks'));
                $ActDate=request('ActDate');
                $EndDate=request('EndDate');
                $noOffShelf=trim(request('noOffShelf'));
                $BriefDesc=request('BriefDesc');
                $Description=request('Description');
                $Delivery_Returns=request('Delivery_Returns');

                $BriefDescEn=request('BriefDescEn');
                $DescriptionEn=request('DescriptionEn');
                $Delivery_ReturnsEn=request('Delivery_ReturnsEn');

                $OrderNumber=intval(request('OrderNumber'));
                $Status=intval(request('Status'));
                $WeeklyReco=intval(request('WeeklyReco'));
                $Flagship=intval(request('Flagship'));

                if($noOffShelf==='on'){
                    $NoOffShelf=1;
                }
                else{
                    $NoOffShelf=0;
                    if($EndDate===''){
                        $EndDate=date('Y-m-d 23:59:59',strtotime('+30 days',strtotime(date('Y-m-d'))));
                    }
                }

                $updateWine=array(
                    'Image'=>$Image,
                    'Image2'=>$Image2,
                    'Image3'=>$Image3,
                    'IntroductionPDF'=>$IntroductionPDF,
                    'Name'=>$Name,
                    'NameEn'=>$NameEn,
                    'Price'=>$Price,
                    'S_price'=>$S_price,
                    'Volume'=>$Volume,
                    'Alcohol'=>$Alcohol,
                    'WineCatg'=>$WineCatg,
                    'WineStyle'=>$WineStyle,
                    'WineVariety'=>$WineVariety,
                    'WineColour'=>$WineColour,
                    'WineCountry'=>$WineCountry,
                    'WineRegion'=>$WineRegion,
                    'WineClosure'=>$WineClosure,
                    'WineCaterings'=>$WineCaterings,
                    'Stocks'=>$Stocks,
                    'ActDate'=>$ActDate,
                    'NoOffShelf'=>$NoOffShelf,
                    'EndDate'=>$EndDate,
                    'BriefDesc'=>$BriefDesc,
                    'Description'=>$Description,
                    'Delivery_Returns'=>$Delivery_Returns,
                    'BriefDescEn'=>$BriefDescEn,
                    'DescriptionEn'=>$DescriptionEn,
                    'Delivery_ReturnsEn'=>$Delivery_ReturnsEn,
                    'OrderNumber'=>$OrderNumber,
                    'Status'=>$Status,
                    'WeeklyReco'=>$WeeklyReco,
                    'Flagship'=>$Flagship,
                    'Maintainer'=>Session::get('AdminId'),
                    'updated_at'=>date('Y-m-d H:i:s')
                );

                if($Flagship===1){
                    WinesData::where('Flagship',1)->update(array('Flagship'=>0));
                }
                
                if(WinesData::where('WineId',$id)->update($updateWine)){
                    return redirect('/admin/products');
                }
                else{
                    Session::flash('maintain_message',true);
                    Session::flash('maintain_message_fail','更新商品失败。');
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }
            }
            $varieties=DB::table('wine_varieties')->orderBy('OrderNumber','ASC')->orderBy('Status','DESC')->get();
            $colours=DB::table('wine_colours')->orderBy('OrderNumber','ASC')->orderBy('Status','DESC')->get();
            $regions=DB::table('wine_regions')->orderBy('OrderNumber','ASC')->orderBy('Status','DESC')->get();
            $caterings=DB::table('wine_caterings')->orderBy('Status','DESC')->get();
            $closures=DB::table('wine_closures')->orderBy('OrderNumber','ASC')->orderBy('Status','DESC')->get();
            $styles=DB::table('wine_styles')->orderBy('OrderNumber','ASC')->orderBy('Status','DESC')->get();
            $countryQuery=DB::table('countries')->orderBy('CountryId','ASC')->get();
            $countries=array();
            foreach($countryQuery as $country){
                $countries[$country->CountryId]=$country->Country;
            }

            $data['wineInfo']=$wineInfo;
            $data['varieties']=$varieties;
            $data['colours']=$colours;
            $data['regions']=$regions;
            $data['closures']=$closures;
            $data['styles']=$styles;
            $data['caterings']=$caterings;
            $data['countries']=$countries;
            $data['currentPage']='products';
            $data['header_title']='Edit Product';
            return view('admin.products.edit_product',$data);
        }
        return redirect('/admin/products');
    }

    public function delete_product($id){
        WinesData::where('WineId',$id)->delete();
        return redirect('/admin/products');
    }

    public function basic_category(){
        $basic_category = DB::table('wine_basic_category')->get();
        $data['basic_category'] = $basic_category;
        $data['header_title'] = 'Basic category';
        $data['currentPage'] = 'products';
        return view('admin.products.basic_category',$data);
    }

    public function edit_category(Request $request){
        $editId = trim(request('id'));
        $categoryInfo = DB::table('wine_basic_category')->where('Category',$editId)->take(1)->first();
        if($categoryInfo){
            if($request->isMethod('post')){
                $validator_rule_messages=[
                    'Image.required'=>'请选择主图',
                    'Image.max'=>'主图内容请勿超过100个字元',
                    'Description.required'=>'请输入类别描述',
                    'Description.max'=>'类别描述内容请勿超过65535个字元',
                    'DescriptionEn.max'=>'类别描述内容(英)请勿超过65535个字元'
                ];
                $validator_rule_array=array(
                    'Image'=>'required|max:100',
                    'Description'=>'required|max:65535',
                    'DescriptionEn'=>'nullable|max:65535'
                );
                $build_validator=Validator::make($request->all(),$validator_rule_array,$validator_rule_messages);
                if($build_validator->fails()){
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }                

                $Image = trim(request('Image'));
                $Description = trim(request('Description'));
                $DescriptionEn = trim(request('DescriptionEn'));
                DB::table('wine_basic_category')->where('Category',$editId)->update(array(
                    'Description'=>$Description,
                    'DescriptionEn'=>$DescriptionEn,
                    'Image'=>$Image,
                    'updated_at'=>date('Y-m-d H:i:s')
                ));
                return redirect('/admin/products/basic_category');
            }
            $data['categoryInfo'] = $categoryInfo;
            $data['header_title'] = 'Maintain basic category';
            $data['currentPage'] = 'products';
            return view('admin.products.edit_basic_category',$data);
        }
        return redirect('/admin/products/basic_category');
    }

    // 销售组合
    public function sales_mix(){
        $mix=SalesMixData::orderBy('Status','DESC')->orderBy('OrderNumber','ASC')->orderBy('updated_at','DESC')->paginate(10);
        foreach($mix as $k=>$val){
            $mix[$k]->WineNumbers=0;
            $tempQ=DB::table('sales_mix_content')->where('MixId',$val->MixId)->select('Content')->take(1)->first();
            if($tempQ){
                $mix[$k]->WineNumbers=count((array)json_decode($tempQ->Content));
            }
        }
        $data['mix']=$mix;
        $data['currentPage']='products';
        $data['header_title']='Topics';
        return view('admin.products.sales_mix',$data);  
    }

    public function add_salesMix(Request $request){
        if($request->isMethod('post')){
            $validator_rule_messages=[
                'Parent.required'=>'请选择销售大主题',
                'Parent.integer'=>'请选择正确的销售大主题',
                'MixName.required'=>'请输入组合名称',
                'MixName.max'=>'组合名称最多输入100个字元',
                'MixNameEn.max'=>'组合名称(英)最多输入100个字元',
                'Price.required'=>'请输入金额',
                'Price.numeric'=>'商品金额仅能输入数字',
                'Price.between'=>'商品金额仅能输入数字',
                'S_price.numeric'=>'特价金额仅能输入数字',
                'S_price.between'=>'特价金额仅能输入数字',
                'Stocks.required'=>'请输入库存数量',
                'Stocks.integer'=>'库存数量仅能输入数字',
                'WineId.array'=>'商品内容格式有误',
                'WineNumber.array'=>'商品内容格式有误',
                'WineNumber.*.integer'=>'酒类数量仅能输入整数',
                'WineNumber.*.min'=>'酒类数量最小值须为1',
                'noOffShelf.in'=>'永不下架的资料格式有误',
                'ActDate.required'=>'请选择开始销售日期',
                'ActDate.date'=>'开始销售日期的格式有误',
                'EndDate.date'=>'结束销售日期的格式有误',
                'OrderNumber.required'=>'请输入排序号码',
                'OrderNumber.integer'=>'排序号码仅能输入整数',
                'OrderNumber.min'=>'排序号码最小须为1',
                'Status.required'=>'请选择是否启用',
                'Status.integer'=>'请选择是否启用',
                'Status.in'=>'请选择是否启用',
                'WeeklyReco.integer'=>'请选择是否为当周推荐',
                'WeeklyReco.in'=>'请选择是否为当周推荐'
            ];
            $validator_rule_array=array(
                'ParentCase'=>'required|integer',
                'MixName'=>'required|max:100',
                'MixNameEn'=>'nullable|max:100',
                'Price'=>'required|numeric|between:0,99999.99',
                'S_price'=>'nullable|numeric|between:0,99999.99',
                'Stocks'=>'required|integer',
                'WineId'=>'nullable|array',
                'WineNumber'=>'nullable|array',
                'WineNumber.*'=>'nullable|integer|min:1',
                'noOffShelf'=>'nullable|in:on',
                'ActDate'=>'required|date',
                'EndDate'=>'nullable|date',
                'OrderNumber'=>'required|integer|min:1',
                'Status'=>'required|integer|in:0,1',
                'WeeklyReco'=>'nullable|integer|in:0,1'
            );
            $build_validator=Validator::make($request->all(),$validator_rule_array,$validator_rule_messages);
            if($build_validator->fails()){
                return redirect()->back()->withInput()->withErrors($build_validator->errors());
            }

            $ParentCase=intval(request('ParentCase'));
            $Image=trim(request('Image'));
            $MixName=trim(request('MixName'));
            $MixNameEn=trim(request('MixNameEn'));
            $Price=request('Price');
            $S_price=request('S_price');
            $Stocks=intval(request('Stocks'));
            $BriefDesc=trim(request('BriefDesc'));
            $Description=trim(request('Description'));
            $Delivery_Returns=trim(request('Delivery_Returns'));
            $BriefDescEn=trim(request('BriefDescEn'));
            $DescriptionEn=trim(request('DescriptionEn'));
            $Delivery_ReturnsEn=trim(request('Delivery_ReturnsEn'));            
            $noOffShelf=trim(request('noOffShelf'));
            $ActDate=trim(request('ActDate'));
            $EndDate=trim(request('EndDate'));
            $OrderNumber=intval(request('OrderNumber'));
            $Status=intval(request('Status'));
            $WeeklyReco=intval(request('WeeklyReco'));

            $MetaDesc=trim(request('MetaDesc'));
            $MetaDescEn=trim(request('MetaDescEn'));
            $MetaKeywords=trim(request('MetaKeywords'));
            $MetaKeywordsEn=trim(request('MetaKeywordsEn'));

            $Maintainer=Session::get('AdminId');
            $WineIds=request('WineId');
            $WineNumbers=request('WineNumber');

            if($noOffShelf==='on'){
                $NoOffShelf=1;
            }
            else{
                $NoOffShelf=0;
                if($EndDate===''){
                    $EndDate=date('Y-m-d 23:59:59',strtotime('+30 days',strtotime(date('Y-m-d'))));
                }
            }

            // check same mix name
            $exist=SalesMixData::where('MixName',$MixName)->take(1)->first();
            if($exist){
                Session::flash('maintain_message',true);
                Session::flash('maintain_message_warning','已有相同名称的销售组合存在。');
                return redirect()->back()->withInput()->withErrors($build_validator->errors());                
            }

            $newWineId=Widget_Helper::createID();
            $newSalesMix=new SalesMixData;
            $newSalesMix->ParentCase=$ParentCase;
            $newSalesMix->MixId=$newWineId;
            $newSalesMix->Price=$Price;
            $newSalesMix->S_price=$S_price;
            $newSalesMix->Stocks=$Stocks;
            $newSalesMix->BriefDesc=$BriefDesc;
            $newSalesMix->Description=$Description;
            $newSalesMix->Delivery_Returns=$Delivery_Returns;
            $newSalesMix->BriefDescEn=$BriefDescEn;
            $newSalesMix->DescriptionEn=$DescriptionEn;
            $newSalesMix->Delivery_ReturnsEn=$Delivery_ReturnsEn;

            $newSalesMix->MixName=$MixName;
            $newSalesMix->MixNameEn=$MixNameEn;
            $newSalesMix->OrderNumber=$OrderNumber;
            $newSalesMix->Status=$Status;
            $newSalesMix->WeeklyReco=$WeeklyReco;
            $newSalesMix->ActDate=$ActDate;
            $newSalesMix->EndDate=$EndDate;
            $newSalesMix->NoOffShelf=$NoOffShelf;

            $newSalesMix->MetaDesc=$MetaDesc;
            $newSalesMix->MetaDescEn=$MetaDescEn;
            $newSalesMix->MetaKeywords=$MetaKeywords;
            $newSalesMix->MetaKeywordsEn=$MetaKeywordsEn;

            $newSalesMix->Maintainer=$Maintainer;
            $newSalesMix->created_at=date('Y-m-d H:i:s');
            $newSalesMix->updated_at=date('Y-m-d H:i:s');
            if($newSalesMix->save()){
                $Content=array();
                foreach($WineIds as $index=>$WineId){
                    $Content[$WineId]=$WineNumbers[$index];
                }
                DB::table('sales_mix_content')->insert(array(
                    'MixId'=>$newWineId,
                    'Content'=>json_encode($Content),
                    'created_at'=>date('Y-m-d H:i:s'),
                    'updated_at'=>date('Y-m-d H:i:s')
                ));
                return redirect('/admin/products/mix');
            }
            else{
                Session::flash('maintain_message',true);
                Session::flash('maintain_message_fail','新增销售组合失败。');
                return redirect()->back()->withInput()->withErrors($build_validator->errors());
            }
        }
        $cases=CasesData::where('Status',1)->orderBy('OrderNumber','ASC')->orderBy('updated_at','DESC')->get();
        $data['cases']=$cases;

        $wines=WinesData::where('Status',1)->orderBy('OrderNumber','ASC')->get();
        $data['wines']=$wines;
        $data['currentPage']='products';
        $data['header_title']='Add Topic';        
        return view('admin.products.add_salesMix',$data);        
    }

    public function edit_salesMix(Request $request){
        $mixid=$request->input('mixid');
        $mixInfo=SalesMixData::where('MixId',$mixid)->take(1)->first();
        if($mixInfo){
            if($request->isMethod('post')){
                $validator_rule_messages=[
                    'ParentCase.required'=>'请选择销售大主题',
                    'ParentCase.integer'=>'请选择正确的销售大主题',
                    'MixName.required'=>'请输入组合名称',
                    'MixName.max'=>'组合名称最多输入100个字元',
                    'Price.required'=>'请输入金额',
                    'Price.numeric'=>'商品金额仅能输入数字',
                    'Price.between'=>'商品金额仅能输入数字',
                    'S_price.numeric'=>'特价金额仅能输入数字',
                    'S_price.between'=>'特价金额仅能输入数字',
                    'Stocks.required'=>'请输入库存数量',
                    'Stocks.integer'=>'库存数量仅能输入数字',
                    'WineId.array'=>'商品内容格式有误',
                    'WineNumber.array'=>'商品内容格式有误',
                    'WineNumber.*.integer'=>'酒类数量仅能输入整数',
                    'WineNumber.*.min'=>'酒类数量最小值须为1',
                    'noOffShelf.in'=>'永不下架的资料格式有误',
                    'ActDate.required'=>'请选择开始销售日期',
                    'ActDate.date'=>'开始销售日期的格式有误',
                    'OrderNumber.required'=>'请输入排序号码',
                    'OrderNumber.integer'=>'排序号码仅能输入整数',
                    'OrderNumber.min'=>'排序号码最小须为1',
                    'Status.required'=>'请选择是否启用',
                    'Status.integer'=>'请选择是否启用',
                    'Status.in'=>'请选择是否启用',
                    'WeeklyReco.integer'=>'请选择是否为当周推荐',
                    'WeeklyReco.in'=>'请选择是否为当周推荐'
                ];
                $validator_rule_array=array(
                    'ParentCase'=>'required|integer',
                    'MixName'=>'required|max:100',
                    'Price'=>'required|numeric|between:0,99999.99',
                    'S_price'=>'nullable|numeric|between:0,99999.99',
                    'Stocks'=>'required|integer',
                    'WineId'=>'nullable|array',
                    'WineNumber'=>'nullable|array',
                    'WineNumber.*'=>'nullable|integer|min:1',
                    'noOffShelf'=>'nullable|in:on',
                    'ActDate'=>'required|date',
                    'OrderNumber'=>'required|integer|min:1',
                    'Status'=>'required|integer|in:0,1',
                    'WeeklyReco'=>'nullable|integer|in:0,1',
                );
                $build_validator=Validator::make($request->all(),$validator_rule_array,$validator_rule_messages);
                if($build_validator->fails()){
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }

                $ParentCase=intval(request("ParentCase"));
                $Image=trim(request('Image'));
                $MixName=trim(request('MixName'));
                $MixNameEn=trim(request('MixNameEn'));
                $Price=trim(request('Price'));
                $S_price=trim(request('S_price'));
                $Stocks=intval(request('Stocks'));
                $noOffShelf=trim(request('noOffShelf'));
                $ActDate=trim(request('ActDate'));
                $EndDate=trim(request('EndDate'));
                $BriefDesc=trim(request('BriefDesc'));
                $Description=trim(request('Description'));
                $Delivery_Returns=trim(request('Delivery_Returns'));

                $BriefDescEn=trim(request('BriefDescEn'));
                $DescriptionEn=trim(request('DescriptionEn'));
                $Delivery_ReturnsEn=trim(request('Delivery_ReturnsEn'));

                $MetaDesc=trim(request('MetaDesc'));
                $MetaDescEn=trim(request('MetaDescEn'));
                $MetaKeywords=trim(request('MetaKeywords'));
                $MetaKeywordsEn=trim(request('MetaKeywordsEn'));

                $OrderNumber=intval(request('OrderNumber'));
                $Status=intval(request('Status'));
                $WeeklyReco=intval(request('WeeklyReco'));
                $Maintainer=Session::get('AdminId');
                $WineIds=request('WineId');
                $WineNumbers=request('WineNumber');

                if($noOffShelf==='on'){
                    $NoOffShelf=1;
                }
                else{
                    $NoOffShelf=0;
                    if($EndDate===''){
                        $EndDate=date('Y-m-d 23:59:59',strtotime('+30 days',strtotime(date('Y-m-d'))));
                    }
                }

                // check same mix name
                $exist=SalesMixData::where('MixName',$MixName)->where('MixId','!=',$mixid)->take(1)->first();
                if($exist){
                    Session::flash('maintain_message',true);
                    Session::flash('maintain_message_warning','已有相同名称的销售组合存在。');
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());                
                }

                if(SalesMixData::where('MixId',$mixid)->update(array(
                    'ParentCase'=>$ParentCase,
                    'Image'=>$Image,
                    'Price'=>$Price,
                    'S_price'=>$S_price,
                    'Stocks'=>$Stocks,
                    'MixName'=>$MixName,
                    'MixNameEn'=>$MixNameEn,
                    'OrderNumber'=>$OrderNumber,
                    'Status'=>$Status,
                    'WeeklyReco'=>$WeeklyReco,
                    'BriefDesc'=>$BriefDesc,
                    'Description'=>$Description,
                    'Delivery_Returns'=>$Delivery_Returns,
                    'BriefDescEn'=>$BriefDescEn,
                    'DescriptionEn'=>$DescriptionEn,
                    'Delivery_ReturnsEn'=>$Delivery_ReturnsEn,

                    'MetaDesc'=>$MetaDesc,
                    'MetaDescEn'=>$MetaDescEn,
                    'MetaKeywords'=>$MetaKeywords,
                    'MetaKeywordsEn'=>$MetaKeywordsEn,

                    'ActDate'=>$ActDate,
                    'EndDate'=>$EndDate,
                    'NoOffShelf'=>$NoOffShelf,
                    'Maintainer'=>$Maintainer,
                    'updated_at'=>date('Y-m-d H:i:s')
                ))){
                    DB::table('sales_mix_content')->where('MixId',$mixid)->delete();
                    $Content=array();
                    if(is_array($WineIds)){
                        foreach($WineIds as $index=>$WineId){
                            if(isset($WineNumbers[$index]))
                                $Content[$WineId]=$WineNumbers[$index];
                        }
                    }
                    DB::table('sales_mix_content')->insert(array(
                        'MixId'=>$mixid,
                        'Content'=>json_encode($Content),
                        'created_at'=>date('Y-m-d H:i:s'),
                        'updated_at'=>date('Y-m-d H:i:s')
                    ));
                    return redirect('/admin/products/mix');
                }
                else{
                    Session::flash('maintain_message',true);
                    Session::flash('maintain_message_fail','更新销售组合失败。');
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }
            }
            else{
                $cases=CasesData::where('Status',1)->orderBy('OrderNumber','ASC')->orderBy('updated_at','DESC')->get();
                $data['cases']=$cases;

                $wines=WinesData::where('Status',1)->orderBy('OrderNumber','ASC')->get();
                $data['wines']=$wines;
                $data['currentPage']='products';
                $data['header_title']='Edit Topic';
                $data['mixInfo']=$mixInfo;
                $mixContants_arr=array();
                $mixContents=DB::table('sales_mix_content')->where('MixId',$mixid)->select('Content')->take(1)->first();
                if($mixContents){
                    $mixContents=json_decode($mixContents->Content);
                    foreach($mixContents as $wineId=>$wineNum){
                        $wineInfo=WinesData::where('WineId',$wineId)->take(1)->first();
                        if($wineInfo){
                            $mixContants_arr[$wineId]=array(
                                'Name'=>$wineInfo->Name,
                                'Nums'=>$wineNum
                            );
                        }
                    }
                }
                $data['mixContants_arr']=$mixContants_arr;
                return view('admin.products.edit_salesMix',$data);
            }
        }
        return redirect('/admin/products/mix');
    }

    public function change_salesMix_status($id){
        $Maintainer=Session::get('AdminId');
        $oldStatus=SalesMixData::where('MixId',$id)->take(1)->first();
        if($oldStatus){
            if(intval($oldStatus->Status)===0){
                SalesMixData::where('MixId',$id)->update(array(
                    'Status'=>1,
                    'Maintainer'=>$Maintainer,
                    'updated_at'=>date('Y-m-d H:i:s')
                ));
            }
            else{
                SalesMixData::where('MixId',$id)->update(array(
                    'Status'=>0,
                    'Maintainer'=>$Maintainer,
                    'updated_at'=>date('Y-m-d H:i:s')
                ));   
            }
        }
        return redirect('/admin/products/mix');
    }

    public function delete_salesMix($id){
        SalesMixData::where('MixId',$id)->delete();
        DB::table('sales_mix_content')->where('MixId',$id)->delete();
        return redirect('/admin/products/mix');
    }

    public function import_product(Request $request){ 
        if($request->hasFile('ImportFile')){
            ini_set('memory_limit','10000M');
            set_time_limit(0);
            $extension=trim($request->file('ImportFile')->extension());
            if('csv'===$extension){
                $reader=new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            }
            else{
                $reader=new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }

            $spreadsheet=$reader->load(request('ImportFile'));
            $sheetData=$spreadsheet->getActiveSheet()->toArray();
            unset($sheetData[0]);
            $sheetData=array_values($sheetData);
            foreach($sheetData as $rowIdx=>$row){
                $newWine=array(
                    'Image'=>'',
                    'Name'=>'',
                    'Price'=>0,
                    'S_price'=>'',
                    'Volume'=>0,
                    'Alcohol'=>0,
                    'WineCatg'=>'',
                    'WineStyle'=>'',
                    'WineVariety'=>'',
                    'WineColour'=>'',
                    'WineCountry'=>'',
                    'WineRegion'=>'',
                    'WineClosure'=>'',
                    'WineCaterings'=>'[]',
                    'Stocks'=>0,
                    'ActDate'=>date('Y-m-d'),
                    'EndDate'=>'',
                    'NoOffShelf'=>1,
                    'OrderNumber'=>1,
                    'Status'=>1,
                    'BriefDesc'=>'',
                    'Description'=>'',
                    'Delivery_Returns'=>'',
                    'Maintainer'=>Session::get('AdminId'),
                    'updated_at'=>date('Y-m-d H:i:s')
                );


                $Topic=trim($row[0]);
                $TopicId='';
                $SalesMix=false;
                if($Topic!==''){
                    $SalesMix=SalesMixData::where('MixName',$Topic)->take(1)->first();
                    if($SalesMix){
                        $TopicId=$SalesMix->MixId;
                    }
                    else{
                        $tempId=Widget_Helper::createID();
                        $newSalesMix=new SalesMixData;
                        $newSalesMix->MixId=$tempId;
                        $newSalesMix->Image='';
                        $newSalesMix->Price=0;
                        $newSalesMix->S_price='';
                        $newSalesMix->Stocks=0;
                        $newSalesMix->MixName=$Topic;
                        $newSalesMix->OrderNumber=1;
                        $newSalesMix->Status=0;
                        $newSalesMix->BriefDesc='';
                        $newSalesMix->Delivery_Returns='';
                        $newSalesMix->BriefDesc='';
                        $newSalesMix->ActDate=date('Y-m-d');
                        $newSalesMix->EndDate='';
                        $newSalesMix->NoOffShelf=1;
                        $newSalesMix->Maintainer=Session::get('AdminId');
                        $newSalesMix->created_at=date('Y-m-d H:i:s');
                        $newSalesMix->updated_at=date('Y-m-d H:i:s');
                        if($newSalesMix->save()){
                            $TopicId=$tempId;
                        }
                    }
                }

                $Image=$row[1];
                $newWine['Image']=$Image;
                $Name=trim($row[2]);
                $newWine['Name']=$Name;
                $WineExist=WinesData::where('Name',$Name)->take(1)->first();
                $Price=trim($row[3]);
                $newWine['Price']=$Price;
                $S_price=trim($row[4]);
                $newWine['S_price']=$S_price;
                $Volume=intval($row[5]);
                $newWine['Volume']=$Volume;
                $Alcohol=trim($row[6]);
                $newWine['Alcohol']=$Alcohol;
                $WineCategory=intval($row[7]);
                if(in_array($WineCategory,array(1,2,3))){
                    $newWine['WineCatg']=$WineCategory;

                    $Style=trim($row[8]);
                    if($Style!==''){
                        $WineStyle=DB::table('wine_styles')->where('Style',$Style)->take(1)->first();
                        if($WineStyle){
                            $WineStyle=$WineStyle->StyleId;
                        }
                        else{
                            if(DB::table('wine_styles')->insert(array(
                                'Style'=>$Style,
                                'Status'=>1,
                                'OrderNumber'=>1,
                                'Maintainer'=>Session::get('AdminId'),
                                'created_at'=>date('Y-m-d H:i:s'),
                                'updated_at'=>date('Y-m-d H:i:s')
                            )))
                                $WineStyle=DB::getPdo()->lastInsertId();
                            else
                                $WineStyle='';
                        }
                        $newWine['WineStyle']=$WineStyle;
                    }

                    $Variety=trim($row[9]);
                    if($Variety!==''){
                        $WineVariety=DB::table('wine_varieties')->where('Variety',$Variety)->take(1)->first();
                        if($WineVariety){
                            $WineVariety=$WineVariety->VarietyId;
                        }
                        else{
                            if(DB::table('wine_varieties')->insert(array(
                                'Variety'=>$Variety,
                                'Status'=>1,
                                'OrderNumber'=>1,
                                'Maintainer'=>Session::get('AdminId'),
                                'created_at'=>date('Y-m-d H:i:s'),
                                'updated_at'=>date('Y-m-d H:i:s')
                            )))
                                $WineVariety=DB::getPdo()->lastInsertId();
                            else
                                $WineVariety='';
                        }
                        $newWine['WineVariety']=$WineVariety;
                    }

                    $Colour=trim($row[10]);
                    if($Colour!==''){
                        $WineColour=DB::table('wine_colours')->where('Colour',$Colour)->take(1)->first();
                        if($WineColour){
                            $WineColour=$WineColour->ColourId;
                        }
                        else{
                            if(DB::table('wine_colours')->insert(array(
                                'Colour'=>$Colour,
                                'Status'=>1,
                                'OrderNumber'=>1,
                                'Maintainer'=>Session::get('AdminId'),
                                'created_at'=>date('Y-m-d H:i:s'),
                                'updated_at'=>date('Y-m-d H:i:s')
                            )))
                                $WineColour=DB::getPdo()->lastInsertId();
                            else
                                $WineColour='';
                        }
                        $newWine['WineColour']=$WineColour;
                    }

                    $Country=trim($row[11]);
                    $newWine['WineCountry']=$Country;
                    $Region=trim($row[12]);
                    if($Region!==''){
                        $WineRegion=DB::table('wine_regions')->where('Region',$Region)->take(1)->first();
                        if($WineRegion){
                            $WineRegion=$WineRegion->RegionId;
                        }
                        else{
                            if(DB::table('wine_regions')->insert(array(
                                'Region'=>$Region,
                                'Status'=>1,
                                'OrderNumber'=>1,
                                'Maintainer'=>Session::get('AdminId'),
                                'created_at'=>date('Y-m-d H:i:s'),
                                'updated_at'=>date('Y-m-d H:i:s')
                            )))
                                $WineRegion=DB::getPdo()->lastInsertId();
                            else
                                $WineRegion='';
                        }
                        $newWine['WineRegion']=$WineRegion;
                    }

                    $Closure=trim($row[13]);
                    if($Closure!==''){
                        $WineClosure=DB::table('wine_closures')->where('Closure',$Closure)->take(1)->first();
                        if($WineClosure){
                            $WineClosure=$WineClosure->ClosureId;
                        }
                        else{
                            if(DB::table('wine_closures')->insert(array(
                                'Closure'=>$Closure,
                                'Status'=>1,
                                'OrderNumber'=>1,
                                'Maintainer'=>Session::get('AdminId'),
                                'created_at'=>date('Y-m-d H:i:s'),
                                'updated_at'=>date('Y-m-d H:i:s')
                            )))
                                $WineClosure=DB::getPdo()->lastInsertId();
                            else
                                $WineClosure='';
                        }
                        $newWine['WineClosure']=$WineClosure;
                    }

                    $Caterings=trim($row[14]);
                    if($Caterings!==''){
                        $cut_Caterings=explode(',',$Caterings);
                        $WineCaterings=array();
                        foreach($cut_Caterings as $catering){
                            if(trim($catering)!==''){
                                $findCateringIdQuery=DB::table('wine_caterings')->where('Catering',trim($catering))->take(1)->first();
                                if($findCateringIdQuery){
                                    array_push($WineCaterings,$findCateringIdQuery->CateringId);
                                }
                                else{
                                    if(DB::table('wine_caterings')->insert(array(
                                        'Catering'=>trim($catering),
                                        'CateringPic'=>'',
                                        'Status'=>'',
                                        'Memo'=>'',
                                        'Maintainer'=>Session::get('AdminId'),
                                        'created_at'=>date('Y-m-d H:i:s'),
                                        'updated_at'=>date('Y-m-d H:i:s')
                                    )))
                                        array_push($WineCaterings,DB::getPdo()->lastInsertId());
                                }
                            }
                        }
                        $WineCaterings=json_encode($WineCaterings);
                        $newWine['WineCaterings']=$WineCaterings;
                    }
                    
                    $Stocks=intval($row[15]);
                    $ActDate=trim($row[16]);
                    if(trim($ActDate)==="")
                        $ActDate=date('Y-m-d');
                    $ActDate=date('Y-m-d',strtotime($ActDate));
                    $EndDate=trim($row[17]);
                    if(trim($EndDate)==="")
                        $EndDate=date('Y-m-d',strtotime('+1 year'));
                    $EndDate=date('Y-m-d',strtotime($EndDate));
                    $NoOffShelf=0;
                    $OrderNumber=$rowIdx+1;
                    $Status=1;

                    $BriefDesc=trim($row[21]);
                    $Description=trim($row[22]);
                    $Delivery_Returns=trim($row[23]);
                    $IntroductionPDF=trim($row[24]);

                    $newWine['Stocks']=$Stocks;
                    $newWine['ActDate']=$ActDate;
                    $newWine['EndDate']=$EndDate;
                    $newWine['NoOffShelf']=$NoOffShelf;
                    $newWine['OrderNumber']=$OrderNumber;
                    $newWine['Status']=$Status;
                    $newWine['BriefDesc']=$BriefDesc;
                    $newWine['Description']=$Description;
                    $newWine['Delivery_Returns']=$Delivery_Returns;
                    $newWine['IntroductionPDF']=$IntroductionPDF;
                }
                else{
                    continue;
                }

                $thisRowWineId='';
                if($WineExist){
                    // update wine information
                    WinesData::where('WineId',$WineExist->WineId)->update($newWine);
                    $thisRowWineId=$WineExist->WineId;
                }
                else{
                    $newWineId=Widget_Helper::createID();
                    $newWine['WineId']=$newWineId;
                    $newWine['created_at']=date('Y-m-d H:i:s');
                    if(WinesData::insert($newWine))
                        $thisRowWineId=$newWineId;
                }

                if($TopicId!==''&&trim($thisRowWineId)!==''){
                    $MixContent=DB::table('sales_mix_content')->where('MixId',$TopicId)->select('Content')->take(1)->first();
                    if($MixContent){
                        $MixContent=(array)json_decode($MixContent->Content);
                        if(!isset($MixContent[$thisRowWineId])){
                            $MixContent[$thisRowWineId]=1;
                        }
                        DB::table('sales_mix_content')->where('MixId',$TopicId)->update(array(
                            'Content'=>json_encode($MixContent),
                            'updated_at'=>date('Y-m-d H:i:s')
                        ));
                    }
                    else{
                        $newMixContent=array(
                            'MixId'=>$TopicId,
                            'Content'=>json_encode(array($thisRowWineId=>1)),
                            'created_at'=>date('Y-m-d H:i:s'),
                            'updated_at'=>date('Y-m-d H:i:s')
                        );
                        DB::table('sales_mix_content')->insert($newMixContent);
                    }
                }

                // Array ( [0] => file-manager/酒类/babich-cowslip.jpg [1] => 2017 COOPERS CREEK SVALBARINO [2] => 19.99 [3] => [4] => 750 [5] => 13.5 [6] => 1 [7] => Reds [8] => Merlot [9] => Red [10] => ES [11] => Gisborne [12] => Cap [13] => 开胃菜,鱼类 [14] => 2019 [15] => 2020 [16] => 150 [17] => 11/16/2019 [18] => 11/30/2019 [19] => 0 [20] => 1 [21] => 1 [22] => Brief Description [23] => Description [24] => Delivery and returns )
            }
        }
        return redirect('/admin/products');
    }

    public function cases(){
        $cases=CasesData::orderBy('OrderNumber','ASC')->orderBy('updated_at','DESC')->get();
        $data['cases']=$cases;
        $data['currentPage']='products';
        $data['header_title']='Cases';  
        return view('admin.products.cases',$data);        
    }

    public function add_case(Request $request){
        if($request->isMethod('post')){
            $rule_array=array(
                'CasePic'=>'required|max:255',
                'CaseName'=>'required|max:255',
                'CaseNameEn'=>'nullable|max:255',
                'MetaDesc'=>'nullable|max:255',
                'MetaDescEn'=>'nullable|max:16777215',
                'MetaKeywords'=>'nullable|max:255',
                'MetaKeywordsEn'=>'nullable|max:255',
                'OrderNumber'=>'required|min:1|integer',
                'Status'=>'required|in:0,1|integer'
            );
            $rule_messages=array(
                'CasePic.required'=>'请选择销售大主题图片',
                'CasePic.max'=>'请选择销售大主题图片',
                'CaseName.required'=>'请输入销售大主题',
                'CaseName.max'=>'最多输入至255个字元',
                'CaseNameEn.max'=>'最多输入至255个字元',
                'MetaDesc.max'=>'最多输入至255个字元',
                'MetaDescEn.max'=>'最多输入至16777215个字元',
                'MetaKeywords.max'=>'最多输入至255个字元',
                'MetaKeywordsEn.max'=>'最多输入至255个字元',
                'OrderNumber.required'=>'请输入排序号码',
                'OrderNumber.integer'=>'排序号码仅能输入整数',
                'OrderNumber.min'=>'排序号码最小须为1',
                'Status.required'=>'请选择是否启用',
                'Status.integer'=>'请选择是否启用',
                'Status.in'=>'请选择是否启用',
            );
            $build_validator=Validator::make($request->all(),$rule_array,$rule_messages);
            if($build_validator->fails()){
                return redirect()->back()->withInput()->withErrors($build_validator->errors());
            }

            $CasePic=trim(request('CasePic'));
            $CaseName=trim(request('CaseName'));
            $CaseNameEn=trim(request('CaseNameEn'));
            $MetaDesc=trim(request('MetaDesc'));
            $MetaDescEn=trim(request('MetaDescEn'));
            $MetaKeywords=trim(request('MetaKeywords'));
            $MetaKeywordsEn=trim(request('MetaKeywordsEn'));
            $OrderNumber=intval(request('OrderNumber'));
            $Status=intval(request('Status'));
            $newCase=new CasesData;
            $newCase->CasePic=$CasePic;
            $newCase->CaseName=$CaseName;
            $newCase->CaseNameEn=$CaseNameEn;
            $newCase->MetaDesc=$MetaDesc;
            $newCase->MetaDescEn=$MetaDescEn;            
            $newCase->MetaKeywords=$MetaKeywords;
            $newCase->MetaKeywordsEn=$MetaKeywordsEn;
            $newCase->OrderNumber=$OrderNumber;
            $newCase->Status=$Status;
            $newCase->created_at=date('Y-m-d H:i:s');
            $newCase->updated_at=date('Y-m-d H:i:s');
            if($newCase->save()){
                return redirect('/admin/products/cases');
            }
            else{
                Session::flash('maintain_message',true);
                Session::flash('maintain_message_fail','新增销售大主题失败。');
                return redirect()->back()->withInput()->withErrors($build_validator->errors());                
            }
        }
        $data['currentPage']='products';
        $data['header_title']='Add Case';
        return view('admin.products.add_case',$data);
    }

    public function edit_case(Request $request){
        $id=intval(request('id'));
        $caseInfo=CasesData::where('CaseId',$id)->take(1)->first();
        if($caseInfo){
            if($request->isMethod('post')){
                $rule_array=array(
                    'CasePic'=>'required|max:255',
                    'CaseName'=>'required|max:255',
                    'CaseNameEn'=>'nullable|max:255',
                    'MetaDesc'=>'nullable|max:255',
                    'MetaDescEn'=>'nullable|max:16777215',
                    'MetaKeywords'=>'nullable|max:255',
                    'MetaKeywordsEn'=>'nullable|max:255',                    
                    'OrderNumber'=>'required|min:1|integer',
                    'Status'=>'required|in:0,1|integer'
                );
                $rule_messages=array(
                    'CasePic.required'=>'请选择销售大主题图片',
                    'CaseName.required'=>'请输入销售大主题',
                    'CaseName.max'=>'最多输入至255个字元',
                    'CaseNameEn.max'=>'最多输入至255个字元',
                    'MetaDesc.max'=>'最多输入至255个字元',
                    'MetaDescEn.max'=>'最多输入至16777215个字元',
                    'MetaKeywords.max'=>'最多输入至255个字元',
                    'MetaKeywordsEn.max'=>'最多输入至255个字元',                    
                    'OrderNumber.required'=>'请输入排序号码',
                    'OrderNumber.integer'=>'排序号码仅能输入整数',
                    'OrderNumber.min'=>'排序号码最小须为1',
                    'Status.required'=>'请选择是否启用',
                    'Status.integer'=>'请选择是否启用',
                    'Status.in'=>'请选择是否启用',
                );
                $build_validator=Validator::make($request->all(),$rule_array,$rule_messages);
                if($build_validator->fails()){
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }

                $CasePic=trim(request('CasePic'));
                $CaseName=trim(request('CaseName'));
                $CaseNameEn=trim(request('CaseNameEn'));
                $MetaDesc=trim(request('MetaDesc'));
                $MetaDescEn=trim(request('MetaDescEn'));
                $MetaKeywords=trim(request('MetaKeywords'));
                $MetaKeywordsEn=trim(request('MetaKeywordsEn'));
                $OrderNumber=intval(request('OrderNumber'));
                $Status=intval(request('Status'));
                if(CasesData::where('CaseId',$id)->update(array(
                    'CasePic'=>$CasePic,
                    'CaseName'=>$CaseName,
                    'CaseNameEn'=>$CaseNameEn,
                    'MetaDesc'=>$MetaDesc,
                    'MetaDescEn'=>$MetaDescEn,
                    'MetaKeywords'=>$MetaKeywords,
                    'MetaKeywordsEn'=>$MetaKeywordsEn,
                    'OrderNumber'=>$OrderNumber,
                    'Status'=>$Status,
                    'updated_at'=>date('Y-m-d H:i:s')
                ))){
                    return redirect('/admin/products/cases');
                }
                else{
                    Session::flash('maintain_message',true);
                    Session::flash('maintain_message_fail','更新销售大主题失败。');
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }
            }
            $data['caseInfo']=$caseInfo;
            $data['currentPage']='products';
            $data['header_title']='Modify Case';
            return view('admin.products.edit_case',$data);
        }
        return redirect('/admin/products/cases');
    }

    public function delete_case(Request $request){
        $id=intval(request('id'));
        CasesData::where('CaseId',$id)->delete();
        return redirect('/admin/products/cases');
    }

    public function build_filter(){
        $ALLWines=WinesData::join('wine_varieties','wines.WineVariety','=','wine_varieties.VarietyId')->orderBy('wine_varieties.OrderNumber','ASC')->where('wine_varieties.Status','=',1)->get();

        $filter=array(
            array(),
            array(),
            array()
        );

        foreach($ALLWines as $wine){
            $variety=DB::table('wine_varieties')->where('VarietyId',$wine->WineVariety)->take(1)->first();
            if($variety){
                if(!isset($filter[$wine->WineCatg-1]['variety_'.$wine->VarietyId])){
                    $filter[$wine->WineCatg-1]['variety_'.$wine->VarietyId]=array(
                        'id'=>$wine->VarietyId,
                        'name'=>$wine->Variety,
                        'nameEn'=>$wine->VarietyEn!==''?$wine->VarietyEn:$wine->Variety
                    );
                }
            }
        }

        $filterFile_handle=fopen(public_path("/datas/filter.txt"),"wa+");
        fwrite($filterFile_handle,json_encode($filter));
        fclose($filterFile_handle);
        return redirect('/admin');
    }
}