<?php
namespace App\Http\Controllers\Backend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\GiftsData;
use App\GiftBannersData;

use Validator;
use Widget_Helper;
class AdminGiftsController extends Controller
{
    public function __construct(){

    }

    public function banners(Request $request){
        $data['currentPage']='gifts';
        $data['header_title']='Banners in gift page';

        $banners = GiftBannersData::get();
        $data['banners'] = $banners;
        return view('admin.gifts.banners',$data);
    }

    public function add_mod_banner(Request $request){
        $data['BannerId'] = 0;
        $data['BannerImage'] = '';
        $data['BannerLink'] = '';
        $data['BannerOnStatus'] = 0;
        $data['BannerOnDate'] = date('Y-m-d');
        $data['BannerOffDate'] = date('Y-m-d',strtotime("+1 day"));
        $data['BannerOrder'] = 1;
        $data['action'] = 'add';

        $findId = intval(request('id'));
        if($findId!==0){
            $bannerInfo = GiftBannersData::where('BannerId',$findId)->take(1)->first();
            if($bannerInfo){
                $bannerInfo = $bannerInfo->toArray();
                foreach($bannerInfo as $key=>$value){
                    $data[$key] = $value;
                }
                $data['action'] = 'update';
            }
        }

        if($request->isMethod('post')){
            $validator_rule_array=array(
                'BannerOrder'=>'required|integer',
                'BannerImage'=>'required|max:100',
                'BannerLink'=>'nullable|url|max:65535',
                'BannerOnStatus'=>'required|integer|in:0,1',
                'BannerOnDate'=>'required|date',
                'BannerOffDate'=>'required|date'
            );

            $niceNames = array(
                'BannerOrder'=>'Order of banner',
                'BannerImage'=>'Image',
                'BannerLink'=>'Link of banner',
                'BannerOnStatus'=>'Status of Enable\Disable',
                'BannerOnDate'=>'Enable date(Start)',
                'BannerOffDate'=>'Enable date(End)'
            );

            $build_validator=Validator::make($request->all(),$validator_rule_array);
            $build_validator->setAttributeNames($niceNames);
            if($build_validator->fails()){
                return redirect()->back()->withInput()->withErrors($build_validator->errors());
            }

            $BannerOrder = intval(request('BannerOrder'));
            $BannerImage = trim(request('BannerImage'));
            $BannerLink = trim(request('BannerLink'));
            $BannerOnStatus = intval(request('BannerOnStatus'));
            $BannerOnDate = trim(request('BannerOnDate'));
            $BannerOffDate = trim(request('BannerOffDate'));            
            if($data['action']==='add'){
                // Array ( [BannerOrder] => 1 [BannerImage] => file-manager\Gifts\2_599ece6c8cbb46.05232707.jpg [BannerLink] => [BannerOnStatus] => 1 [BannerOnDate] => 2020-05-10 [BannerOffDate] => 2020-05-13 [_token] => vL47q98yUZJ7jK0Rv8AEY5gIHFUQnCdN6MrhyZVj [action] => add )
                GiftBannersData::insert(array(
                    'BannerImage'=>$BannerImage,
                    'BannerLink'=>$BannerLink,
                    'BannerOnStatus'=>$BannerOnStatus,
                    'BannerOnDate'=>$BannerOnDate,
                    'BannerOffDate'=>$BannerOffDate,
                    'BannerOrder'=>$BannerOrder,
                    'created_at'=>date('Y-m-d H:i:s'),
                    'updated_at'=>date('Y-m-d H:i:s')
                ));
            }
            else{
                GiftBannersData::where('BannerId',$data['BannerId'])->update(array(
                    'BannerImage'=>$BannerImage,
                    'BannerLink'=>$BannerLink,
                    'BannerOnStatus'=>$BannerOnStatus,
                    'BannerOnDate'=>$BannerOnDate,
                    'BannerOffDate'=>$BannerOffDate,
                    'BannerOrder'=>$BannerOrder,
                    'updated_at'=>date('Y-m-d H:i:s')
                ));
            }
            return redirect('/admin/gifts/banners');
        }
        $data['currentPage'] = 'gifts';
        $data['header_title'] = 'Maintain banner in gift page';
        return view('admin.gifts.add_mod_banner',$data);
    }

    public function delete_banner(Request $request){
        $id = intval(request('id'));
        GiftBannersData::where('BannerId',$id)->delete();
        return redirect('/admin/gifts/banners');
    }

    public function business_image(Request $request){
        $data['gift_business_image']='';

        $gift_combos=DB::table('template_static_data')->whereIn('DataKey',array('gift_business_image'))->get();
        foreach($gift_combos as $combo){
            $data[$combo->DataKey]=$combo->Data;
        }

        if($request->isMethod('post')){
            $validator_rule_array=array(
                'gift_business_image'=>'required|max:500'
            );
            $validator_message=array(
                'gift_business_image.required'=>'Should choose the business image of gift',
                'gift_business_image.max'=>'Business image of gift should not above 500 characters'
            );
            $build_validator=Validator::make($request->all(),$validator_rule_array,$validator_message);
            if($build_validator->fails()){
                return redirect()->back()->withInput()->withErrors($build_validator->errors());
            }

            $gift_business_image = trim(request('gift_business_image'));
            $gift_set = array('gift_business_image');
            foreach($gift_set as $key){
                DB::table('template_static_data')->where('DataKey',$key)->update(array(
                    'Data'=>${$key},
                    'updated_at'=>date('Y-m-d H:i:s')
                ));
            }
            return redirect('/admin/gifts/business-image');
        }
        $data['currentPage']='gifts';
        $data['header_title']='Business image (Index in gifts)';
        return view('admin.gifts.maintain_business_image',$data);
    }

    public function categories(){
        $categories = DB::table('gift_categories')->orderBy('CategoryOrder','ASC')->get();

        $data['categories'] = $categories;
        $data['currentPage'] = 'gifts';
        $data['header_title'] = 'Categories';
        return view('admin.gifts.categories',$data);
    }

    public function edit_category(Request $request){
        $CategoryId = intval(request('id'));
        $categoryInfo = DB::table('gift_categories')->where('CategoryId',$CategoryId)->take(1)->first();
        if($categoryInfo){
            if($request->isMethod('post')){
                $validator_rule_array=array(
                    'Category'=>'required|max:200',
                    'CategoryEn'=>'required|max:200',
                    'Image'=>'required|max:100',
                    'CategoryOrder'=>'required|integer'
                );
                $niceNames = array(
                    'CategoryEn'=>'Category in English',
                    'Image'=>'Impact image of category',
                    'CategoryOrder'=>'Order of category'
                );

                $build_validator=Validator::make($request->all(),$validator_rule_array);
                $build_validator->setAttributeNames($niceNames);
                if($build_validator->fails()){
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }
                // Array ( [Category] => Wooden Gift Boxes [Image] => file-manager\Gifts\GiftBoxes-335x301.jpg [_token] => vL47q98yUZJ7jK0Rv8AEY5gIHFUQnCdN6MrhyZVj )

                $Category = trim(request('Category'));
                $CategoryEn = trim(request('CategoryEn'));
                // verify same Category exist or not
                if($exist = DB::table('gift_categories')->where(array(
                        array('Category','=',$Category),
                        array('CategoryId','!=',$CategoryId)
                    ))->orWhere(array(
                        array('CategoryEn','=',$CategoryEn),
                        array('CategoryId','!=',$CategoryId)
                    ))->take(1)->first()){
                    Session::flash('maintain_message',true);
                    Session::flash('maintain_message_warning','Another category has same name...');                    
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());                  
                }

                $Image = trim(request('Image'));
                DB::table('gift_categories')->where('CategoryId',$CategoryId)->update(array(
                    'Category'=>$Category,
                    'CategoryEn'=>$CategoryEn,
                    'Image'=>$Image,
                    'updated_at'=>date('Y-m-d H:i:s')
                ));
            }
            else{
                foreach($categoryInfo as $key=>$value){
                    $data[$key] = $value;
                }
                $data['header_title'] = 'Maintain category of gifts';
                $data['currentPage'] = 'gifts';
                return view('admin.gifts.edit_category',$data);
            }
        }
        return redirect('/admin/gifts/categories');
    }

    public function gifts(){
        $gifts = GiftsData::join('gift_categories','gifts.Category','=','gift_categories.CategoryId')->orderBy('Status','DESC')->orderBy('ActDate','DESC')->orderBy('EndDate','ASC')->paginate(10);
        $data['gifts'] = $gifts;
        $data['header_title'] = 'Gifts';
        $data['currentPage'] = 'gifts';
        return view('admin.gifts.list',$data);
    }

    public function add_gift(Request $request){
        if($request->isMethod('post')){
            //             Array ( [Image] => file-manager/Gifts/caros-single-wooden-box_1.jpg [Name] => 1瓶木制羊毛酒盒 [NameEn] => 1 BOTTLE WOODEN WINE GIFT BOX WITH WOOD WOOL [Price] => 8 [S_price] => 0 [Category] => 1 [Volume] => 50 [Alcohol] => 5 [Stocks] => 10 [ActDate] => 2020-05-11 [EndDate] => 2020-05-18 [BriefDesc] => 赠送那些特殊葡萄酒的好方法。这个单瓶装的盒子里装满了木棉，以保护瓶子，直到他们到达幸运的主人之前……这才是公平的游戏！ [BriefDescEn] => A great way to gift those special wines. This single bottle box is packed with wood wool to protect the bottles until they get to their lucky owner... then they're fair game! [Description] =>
            // test

            // [DescriptionEn] =>
            // test123

            // [Delivery_Returns] =>
            // We deliver throughout New Zealand and to some locations overseas. Our deliveries are made by trusted third party carriers. For further details on prices and delivery options please go to DELIVERY INFORMATION section of our site.

            // If for any reason you are not entirely happy with your order or you suspect that they may be affected by a quality issue, please do not hesitate to contact us and we will remedy the problem straight away. Contact us on 0800 422 767.

            // [Delivery_ReturnsEn] =>
            // We deliver throughout New Zealand and to some locations overseas. Our deliveries are made by trusted third party carriers. For further details on prices and delivery options please go to DELIVERY INFORMATION section of our site.

            // If for any reason you are not entirely happy with your order or you suspect that they may be affected by a quality issue, please do not hesitate to contact us and we will remedy the problem straight away. Contact us on 0800 422 767.

            // [OrderNumber] => 1 [Status] => 1 [_token] => PbRMcb9QzbEOmAcsib2w3y7s73r2KB1BgEzcEhxW )
            $validator_rule_array=array(
                'Image'=>'required|max:255',
                'Name'=>'required|max:255',
                'NameEn'=>'nullable|max:255',
                'Price'=>'required|between:0.01,999999.99',
                'S_price'=>'nullable|between:0.01,999999.99',
                'Volume'=>'required|between:0.01,999999.99',
                'Alcohol'=>'required|between:0.01,100.00',
                'Stocks'=>'required|integer',
                'ActDate'=>'required|date',
                'EndDate'=>'required|date',
                'OrderNumber'=>'required|integer',
                'Status'=>'required|integer|in:0,1',
                'BriefDesc'=>'required|max:500',
                'Description'=>'required|max:4294967295',
                'Delivery_Returns'=>'required|max:4294967295',
                'BriefDescEn'=>'nullable|max:500',
                'DescriptionEn'=>'nullable|max:4294967295',
                'Delivery_ReturnsEn'=>'nullable|max:4294967295'
            );

            $niceNames = array(
                'Image'=>'Image',
                'NameEn'=>'Gift name in English',
                'S_price'=>'Special price on sale',
                'ActDate'=>'Enable date(Start)',
                'EndDate'=>'Enable date(End)',
                'OrderNumber'=>'Order of gift',
                'BriefDesc'=>'Brief description',
                'Delivery_Returns'=>'Content of delivery and returns',
                'BriefDescEn'=>'Brief description in English',
                'DescriptionEn'=>'Description in English',
                'Delivery_ReturnsEn'=>'Content of delivery and returns in English'
            );

            $build_validator=Validator::make($request->all(),$validator_rule_array);
            $build_validator->setAttributeNames($niceNames);
            if($build_validator->fails()){
                return redirect()->back()->withInput()->withErrors($build_validator->errors());
            }

            $Image = trim(request('Image'));
            $Name = trim(request('Name'));
            $NameEn = trim(request('NameEn'));
            $Price = floatval(request('Price'));
            $S_price = floatval(request('S_price'));
            $Category = intval(request('Category'));
            $Volume = floatval(request('Volume'));
            $Alcohol = floatval(request('Alcohol'));
            $Stocks = intval(request('Stocks'));
            $ActDate = trim(request('ActDate'));
            $EndDate = trim(request('EndDate'));
            $BriefDesc = trim(request('BriefDesc'));
            $BriefDescEn = trim(request('BriefDescEn'));
            $Description = trim(request('Description'));
            $DescriptionEn = trim(request('DescriptionEn'));
            $Delivery_Returns = trim(request('Delivery_Returns'));
            $Delivery_ReturnsEn = trim(request('Delivery_ReturnsEn'));
            $OrderNumber = intval(request('OrderNumber'));
            $Status = intval(request('Status'));

            if(GiftsData::insert(array(
                'GiftId'=>Widget_Helper::createID(),
                'Image'=>$Image,
                'Name'=>$Name,
                'NameEn'=>$NameEn,
                'Price'=>$Price,
                'S_price'=>$S_price,
                'Volume'=>$Volume,
                'Alcohol'=>$Alcohol,
                'Category'=>$Category,
                'Stocks'=>$Stocks,
                'ActDate'=>$ActDate,
                'EndDate'=>$EndDate,
                'OrderNumber'=>$OrderNumber,
                'Status'=>$Status,
                'BriefDesc'=>$BriefDesc,
                'BriefDescEn'=>$BriefDescEn,
                'Description'=>$Description,
                'DescriptionEn'=>$DescriptionEn,
                'Delivery_Returns'=>$Delivery_Returns,
                'Delivery_ReturnsEn'=>$Delivery_ReturnsEn,
                'OrderNumber'=>$OrderNumber,
                'Status'=>$Status,
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s')
            ))){
                return redirect('/admin/gifts/list');
            }
            else{
                return redirect()->back()->withInput()->withErrors($build_validator->errors());
            }
        }
        $data['Image'] = '';
        $data['Name'] = '';
        $data['NameEn'] = '';
        $data['Price'] = 0.0;
        $data['S_price'] = 0.0;
        $data['Volume'] = 0.0;
        $data['Alcohol'] = 0.0;
        $data['Stocks'] = 0;
        $data['ActDate'] = date('Y-m-d');
        $data['EndDate'] = date('Y-m-d',strtotime("+7 day"));
        $data['OrderNumber'] = 0;
        $data['Status'] = 0;
        $data['BriefDesc'] = '';
        $data['Description'] = '';
        $data['Delivery_Returns'] = '';
        $data['BriefDescEn'] = '';
        $data['DescriptionEn'] = '';
        $data['Delivery_ReturnsEn'] = '';
        $data['Category'] = 1;
        $data['GiftCategories'] = DB::table('gift_categories')->get();

        $data['header_title'] = 'Gifts';
        $data['currentPage'] = 'gifts';
        return view('admin.gifts.maintain_gift',$data);
    }

    public function edit_gift(Request $request){
        $data['Image'] = '';
        $data['Name'] = '';
        $data['NameEn'] = '';
        $data['Price'] = 0.0;
        $data['S_price'] = 0.0;
        $data['Volume'] = 0.0;
        $data['Alcohol'] = 0.0;
        $data['Stocks'] = 0;
        $data['ActDate'] = date('Y-m-d');
        $data['EndDate'] = date('Y-m-d',strtotime("+7 day"));
        $data['OrderNumber'] = 0;
        $data['Status'] = 0;
        $data['BriefDesc'] = '';
        $data['Description'] = '';
        $data['Delivery_Returns'] = '';
        $data['BriefDescEn'] = '';
        $data['DescriptionEn'] = '';
        $data['Delivery_ReturnsEn'] = '';
        $data['Category'] = 1;
        $data['GiftCategories'] = DB::table('gift_categories')->get();

        $data['header_title'] = 'Gifts';
        $data['currentPage'] = 'gifts';

        $id = trim(request('id'));
        $giftInfo = GiftsData::where('GiftId',$id)->take(1)->first();
        if($giftInfo){
            if($request->isMethod('post')){
                $validator_rule_array=array(
                    'Image'=>'required|max:255',
                    'Name'=>'required|max:255',
                    'NameEn'=>'nullable|max:255',
                    'Price'=>'required|between:0.01,999999.99',
                    'S_price'=>'nullable|between:0.01,999999.99',
                    'Volume'=>'required|between:0.01,999999.99',
                    'Alcohol'=>'required|between:0.01,100.00',
                    'Stocks'=>'required|integer',
                    'ActDate'=>'required|date',
                    'EndDate'=>'required|date',
                    'OrderNumber'=>'required|integer',
                    'Status'=>'required|integer|in:0,1',
                    'BriefDesc'=>'required|max:500',
                    'Description'=>'required|max:4294967295',
                    'Delivery_Returns'=>'required|max:4294967295',
                    'BriefDescEn'=>'nullable|max:500',
                    'DescriptionEn'=>'nullable|max:4294967295',
                    'Delivery_ReturnsEn'=>'nullable|max:4294967295'
                );

                $niceNames = array(
                    'Image'=>'Image',
                    'NameEn'=>'Gift name in English',
                    'S_price'=>'Special price on sale',
                    'ActDate'=>'Enable date(Start)',
                    'EndDate'=>'Enable date(End)',
                    'OrderNumber'=>'Order of gift',
                    'BriefDesc'=>'Brief description',
                    'Delivery_Returns'=>'Content of delivery and returns',
                    'BriefDescEn'=>'Brief description in English',
                    'DescriptionEn'=>'Description in English',
                    'Delivery_ReturnsEn'=>'Content of delivery and returns in English'
                );

                $build_validator=Validator::make($request->all(),$validator_rule_array);
                $build_validator->setAttributeNames($niceNames);
                if($build_validator->fails()){
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }

                $Image = trim(request('Image'));
                $Name = trim(request('Name'));
                $NameEn = trim(request('NameEn'));
                $Price = floatval(request('Price'));
                $S_price = floatval(request('S_price'));
                $Category = intval(request('Category'));
                $Volume = floatval(request('Volume'));
                $Alcohol = floatval(request('Alcohol'));
                $Stocks = intval(request('Stocks'));
                $ActDate = trim(request('ActDate'));
                $EndDate = trim(request('EndDate'));
                $BriefDesc = trim(request('BriefDesc'));
                $BriefDescEn = trim(request('BriefDescEn'));
                $Description = trim(request('Description'));
                $DescriptionEn = trim(request('DescriptionEn'));
                $Delivery_Returns = trim(request('Delivery_Returns'));
                $Delivery_ReturnsEn = trim(request('Delivery_ReturnsEn'));
                $OrderNumber = intval(request('OrderNumber'));
                $Status = intval(request('Status'));

                if(GiftsData::where('GiftId',$id)->update(array(
                    'Image'=>$Image,
                    'Name'=>$Name,
                    'NameEn'=>$NameEn,
                    'Price'=>$Price,
                    'S_price'=>$S_price,
                    'Volume'=>$Volume,
                    'Alcohol'=>$Alcohol,
                    'Category'=>$Category,
                    'Stocks'=>$Stocks,
                    'ActDate'=>$ActDate,
                    'EndDate'=>$EndDate,
                    'OrderNumber'=>$OrderNumber,
                    'Status'=>$Status,
                    'BriefDesc'=>$BriefDesc,
                    'BriefDescEn'=>$BriefDescEn,
                    'Description'=>$Description,
                    'DescriptionEn'=>$DescriptionEn,
                    'Delivery_Returns'=>$Delivery_Returns,
                    'Delivery_ReturnsEn'=>$Delivery_ReturnsEn,
                    'OrderNumber'=>$OrderNumber,
                    'Status'=>$Status,
                    'updated_at'=>date('Y-m-d H:i:s')
                ))){
                    return redirect('/admin/gifts/list');
                }
                else{
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }
            }
            else{
                $giftInfo = $giftInfo->toArray();
                foreach($giftInfo as $key=>$value){
                    $data[$key] = $value;
                }
                return view('admin.gifts.maintain_gift',$data);
            }
        }
        return redirect('/admin/gifts/list');
    }

    public function delete_gift(Request $request){
        $id = trim(request('id'));
        GiftsData::where('GiftId',$id)->delete();
        return redirect('/admin/gifts/list');
    }
}