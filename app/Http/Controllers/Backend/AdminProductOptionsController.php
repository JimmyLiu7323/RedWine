<?php
namespace App\Http\Controllers\Backend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Products\WinesData;
use App\Products\SalesMixData;
use App\Products\ProductOptionData;
use App\Products\ProductOptionSetData;

use Widget_Helper;
use Validator;
use Session;

class AdminProductOptionsController extends Controller
{
    public function __construct(){

    }

    public function options(){
        $options = ProductOptionData::orderBy('updated_at','DESC')->paginate(10);
        $data['options'] = $options;

        $data['currentPage']='products';
        $data['header_title']='Product options';
        return view('admin.product_options.options',$data);
    }

    public function add_option(Request $request){
        if($request->isMethod('post')){
            $validator_rule_messages=[
                'Wines.required_without'=>'Please add wine or topic for setting option',
                'Wines.array'=>'Please choose one wine at least',
                'Topics.required_without'=>'Please add wine or topic for setting option',
                'Topics.array'=>'Please choose one topic at least',
                'Option.required'=>'Please set the option rule',
                'Option.in'=>'Please set the option rule',
                'PurchaseAmount.integer'=>'Please enter the purchase amount',
                'Price.required'=>'Please enter the price',
                'Price.between'=>'Please enter the price',
            ];
            $validator_rule_array=array(
                'Wines'=>'nullable|required_without:Topics|array',
                'Topics'=>'nullable|required_without:Wines|array',
                'Option'=>'required|in:combineSelling,discount',
                'PurchaseAmount'=>'nullable|integer',
                'Price'=>'required|between:0,99999.99'
            );
            $build_validator=Validator::make($request->all(),$validator_rule_array,$validator_rule_messages);
            if($build_validator->fails()){
                return redirect()->back()->withInput()->withErrors($build_validator->errors());
            }
            
            $Wines = request('Wines',array());
            $Topics = request('Topics',array());
            $Option = trim(request('Option'));
            $PurchaseAmount = intval(request('PurchaseAmount'));
            $Price = floatval(request('Price'));

            if($Option === 'discount' && $PurchaseAmount === 0){
                Session::flash('maintain_message',true);
                Session::flash('maintain_message_warning','Should enter the purchase amount');
                return redirect()->back()->withInput()->withErrors($build_validator->errors());
            }

            foreach($Wines as $findWine){
                if(ProductOptionSetData::where('ProductType','wine')->where('ProductId',$findWine)->take(1)->first()){
                    $wineInfo=WinesData::where('WineId',$findWine)->select('Name')->take(1)->first();

                    Session::flash('maintain_message',true);
                    Session::flash('maintain_message_warning','This wine('.$wineInfo->Name.') has exist in another option');
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }
            }

            foreach($Topics as $findTopic){
                if(ProductOptionSetData::where('ProductType','topic')->where('ProductId',$findTopic)->take(1)->first()){
                    $topicInfo=SalesMixData::where('MixId',$findTopic)->select('MixName')->take(1)->first();

                    Session::flash('maintain_message',true);
                    Session::flash('maintain_message_warning','This topic('.$topicInfo->MixName.') has exist in another option');
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }
            }

            DB::connection()->getPdo()->beginTransaction();
            $basicOptionAdd = ProductOptionData::create(array(
                'OptionRule'=>$Option,
                'Price'=>$Price,
                'PurchaseAmount'=>$PurchaseAmount,
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s')
            ));

            if($basicOptionAdd){
                foreach($Wines as $optionWine){
                    if(!ProductOptionSetData::insert(array(
                        'OptionId'=>$basicOptionAdd->OptionId,
                        'ProductType'=>'wine',
                        'ProductId'=>$optionWine
                    ))){
                        DB::connection()->getPdo()->rollBack();
                        Session::flash('maintain_message',true);
                        Session::flash('maintain_message_warning','Database error...');
                        return redirect()->back()->withInput()->withErrors($build_validator->errors());                        
                    }
                }
                foreach($Topics as $optionTopic){
                    if(!ProductOptionSetData::insert(array(
                        'OptionId'=>$basicOptionAdd->OptionId,
                        'ProductType'=>'topic',
                        'ProductId'=>$optionTopic
                    ))){
                        DB::connection()->getPdo()->rollBack();
                        Session::flash('maintain_message',true);
                        Session::flash('maintain_message_warning','Database error...');
                        return redirect()->back()->withInput()->withErrors($build_validator->errors());                        
                    }
                }

                DB::connection()->getPdo()->commit();
                return redirect('/admin/products/options');
            }
            else{
                DB::connection()->getPdo()->rollBack();
                Session::flash('maintain_message',true);
                Session::flash('maintain_message_warning','Database error...');
                return redirect()->back()->withInput()->withErrors($build_validator->errors());
            }
        }

        $data['currentPage']='products';
        $data['header_title']='Add option';

        $data['wines']=WinesData::where(function($query){
            $query->where('ActDate','<=',date('Y-m-d'));
            $query->where('EndDate','>',date('Y-m-d'));
        })->orWhere(function($query){
            $query->where('NoOffShelf',1);
        })->where('Status',1)->orderBy('OrderNumber','ASC')->orderBy('updated_at','DESC')->select('WineId','Name')->get()->toArray();

        $data['topics']=SalesMixData::where(function($query){
            $query->where('ActDate','<=',date('Y-m-d'));
            $query->where('EndDate','>',date('Y-m-d'));
        })->orWhere(function($query){
            $query->where('NoOffShelf',1);
        })->where('Status',1)->orderBy('OrderNumber','ASC')->orderBy('updated_at','DESC')->select('MixId','MixName')->get()->toArray();
        
        return view('admin.product_options.add_option',$data);
    }

    public function edit_option($optionId,Request $request){
        $basicOption = ProductOptionData::where('OptionId',$optionId)->take(1)->first();
        if($basicOption){
            if($request->isMethod('post')){
                $validator_rule_messages=[
                    'Wines.required_without'=>'Please add wine or topic for setting option',
                    'Wines.array'=>'Please choose one wine at least',
                    'Topics.required_without'=>'Please add wine or topic for setting option',
                    'Topics.array'=>'Please choose one topic at least',
                    'Option.required'=>'Please set the option rule',
                    'Option.in'=>'Please set the option rule',
                    'PurchaseAmount.integer'=>'Please enter the purchase amount',
                    'Price.required'=>'Please enter the price',
                    'Price.between'=>'Please enter the price',
                ];
                $validator_rule_array=array(
                    'Wines'=>'nullable|required_without:Topics|array',
                    'Topics'=>'nullable|required_without:Wines|array',
                    'Option'=>'required|in:combineSelling,discount',
                    'PurchaseAmount'=>'nullable|integer',
                    'Price'=>'required|between:0,99999.99'
                );
                $build_validator=Validator::make($request->all(),$validator_rule_array,$validator_rule_messages);
                if($build_validator->fails()){
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }
                
                $Wines = request('Wines',array());
                $Topics = request('Topics',array());
                $Option = trim(request('Option'));
                $PurchaseAmount = intval(request('PurchaseAmount'));
                $Price = floatval(request('Price'));

                if($Option === 'discount' && $PurchaseAmount === 0){
                    Session::flash('maintain_message',true);
                    Session::flash('maintain_message_warning','Should enter the purchase amount');
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }

                foreach($Wines as $findWine){
                    if(ProductOptionSetData::where(array(
                        array('ProductType','=','wine'),
                        array('ProductId','=',$findWine),
                        array('OptionId','!=',$optionId)
                    ))->take(1)->first()){
                        $wineInfo=WinesData::where('WineId',$findWine)->select('Name')->take(1)->first();

                        Session::flash('maintain_message',true);
                        Session::flash('maintain_message_warning','This wine('.$wineInfo->Name.') has exist in another option');
                        return redirect()->back()->withInput()->withErrors($build_validator->errors());
                    }
                }

                foreach($Topics as $findTopic){
                    if(ProductOptionSetData::where(array(
                        array('ProductType','=','topic'),
                        array('ProductId','=',$findTopic),
                        array('OptionId','!=',$optionId)
                    ))->take(1)->first()){
                        $topicInfo=SalesMixData::where('MixId',$findTopic)->select('MixName')->take(1)->first();

                        Session::flash('maintain_message',true);
                        Session::flash('maintain_message_warning','This topic('.$topicInfo->MixName.') has exist in another option');
                        return redirect()->back()->withInput()->withErrors($build_validator->errors());
                    }
                }

                DB::connection()->getPdo()->beginTransaction();
                $basicOptionEdit = ProductOptionData::where('OptionId',$optionId)->update(array(
                    'OptionRule'=>$Option,
                    'Price'=>$Price,
                    'PurchaseAmount'=>$PurchaseAmount,
                    'updated_at'=>date('Y-m-d H:i:s')
                ));

                if($basicOptionEdit){
                    ProductOptionSetData::where('OptionId',$optionId)->delete();

                    foreach($Wines as $optionWine){
                        if(!ProductOptionSetData::insert(array(
                            'OptionId'=>$optionId,
                            'ProductType'=>'wine',
                            'ProductId'=>$optionWine
                        ))){
                            DB::connection()->getPdo()->rollBack();
                            Session::flash('maintain_message',true);
                            Session::flash('maintain_message_warning','Database error...');
                            return redirect()->back()->withInput()->withErrors($build_validator->errors());                        
                        }
                    }
                    foreach($Topics as $optionTopic){
                        if(!ProductOptionSetData::insert(array(
                            'OptionId'=>$optionId,
                            'ProductType'=>'topic',
                            'ProductId'=>$optionTopic
                        ))){
                            DB::connection()->getPdo()->rollBack();
                            Session::flash('maintain_message',true);
                            Session::flash('maintain_message_warning','Database error...');
                            return redirect()->back()->withInput()->withErrors($build_validator->errors());                        
                        }
                    }

                    DB::connection()->getPdo()->commit();
                    return redirect('/admin/products/options');
                }
                else{
                    DB::connection()->getPdo()->rollBack();
                    Session::flash('maintain_message',true);
                    Session::flash('maintain_message_warning','Database error...');
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }
            }
            else{
                $option_sets_WineQuery = ProductOptionSetData::where('OptionId',$optionId)->where('ProductType','wine')->select('ProductId')->get()->toArray();
                $option_sets_Wine = array();
                foreach($option_sets_WineQuery as $wineQuery){
                    array_push($option_sets_Wine,$wineQuery['ProductId']);
                }
                $option_sets_TopicQuery = ProductOptionSetData::where('OptionId',$optionId)->where('ProductType','topic')->select('ProductId')->get()->toArray();
                $option_sets_Topic = array();
                foreach($option_sets_TopicQuery as $topicQuery){
                    array_push($option_sets_Topic,$topicQuery['ProductId']);
                }        

                $data['wines']=WinesData::where(function($query){
                    // $query->where('ActDate','<=',date('Y-m-d'));
                    // $query->where('EndDate','>',date('Y-m-d'));
                })->orWhere(function($query){
                    // $query->where('NoOffShelf',1);
                })->where('Status',1)->orderBy('OrderNumber','ASC')->orderBy('updated_at','DESC')->select('WineId','Name')->get()->toArray();

                $data['topics']=SalesMixData::where(function($query){
                    // $query->where('ActDate','<=',date('Y-m-d'));
                    // $query->where('EndDate','>',date('Y-m-d'));
                })->orWhere(function($query){
                    // $query->where('NoOffShelf',1);
                })->where('Status',1)->orderBy('OrderNumber','ASC')->orderBy('updated_at','DESC')->select('MixId','MixName')->get()->toArray();        

                $data['currentPage']='products';
                $data['header_title']='Edit option';

                $data['basicOption']=$basicOption;
                $data['option_sets_Wine']=$option_sets_Wine;
                $data['option_sets_Topic']=$option_sets_Topic;
                return view('/admin.product_options.edit_option',$data);
            }
        }
        return redirect('/admin/products/options');
    }

    public function delete_option($optionId){
        $optionId = intval($optionId);
        ProductOptionData::where('OptionId',$optionId)->delete();
        ProductOptionSetData::where('OptionId',$optionId)->delete();
        return redirect('/admin/products/options');
    }

    public function ajaxProduct(){
        $wines=WinesData::where(function($query){
            $query->where('ActDate','<=',date('Y-m-d'));
            $query->where('EndDate','>',date('Y-m-d'));
        })->orWhere(function($query){
            $query->where('NoOffShelf',1);
        })->where('Status',1)->orderBy('OrderNumber','ASC')->orderBy('updated_at','DESC')->select('WineId','Name')->get()->toArray();

        return response()->json($wines);
    }

    public function ajaxTopic(){
        $salesMixes=SalesMixData::where(function($query){
            $query->where('ActDate','<=',date('Y-m-d'));
            $query->where('EndDate','>',date('Y-m-d'));
        })->orWhere(function($query){
            $query->where('NoOffShelf',1);
        })->where('Status',1)->orderBy('OrderNumber','ASC')->orderBy('updated_at','DESC')->select('MixId','MixName')->get()->toArray();

        return response()->json($salesMixes);
    }
}