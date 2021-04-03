<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\SubmenuData;
use App\PrimaryCatgData;
use App\SourcesData;
use App\ContentsData;
use Widget_Helper;
use Validator;
use Session;

class AdminIndexController extends Controller
{
    private $_popularScience_static_urlCode="chatKnowledge";
    private $_popularScience_main_menu_code=0;
    private $_popularScience_main_menu_name="";
    public function __construct(){
        parent::__construct();
        $menus=parent::getGlobalMainMenus();
        $this->_popularScience_main_menu_code=$menus[$this->_popularScience_static_urlCode]['MenuId'];
        $this->_popularScience_main_menu_name=$menus[$this->_popularScience_static_urlCode]['MenuName'];
    }
    public function popular_science(Request $request){
        Session::flash('edit_prev_url','/admin/index_management/popular_science');
        $showDateTime=date('Y-m-d H:i:s');
        $data['showDateTime']=$showDateTime;
        $org_Sources=array();
        $articles=array();
        // get source first
        $ListOrder=1;
        $Sources=SourcesData::where('SourceKey','popular_science')->select('SourceJSON','ListOrder')->take(1)->first();
        if($Sources){
            $ListOrder=(INT)$Sources->ListOrder;
            $SourceJSON=json_decode($Sources->SourceJSON);
            foreach($SourceJSON as $set){
                $set_MainMenu=$set->MainMenu;
                $set_Submenu=$set->Submenu;
                $set_PrimaryCatg=$set->PrimaryCatg;
                if($set_MainMenu!==''&&$set_Submenu!==''&&$set_PrimaryCatg!==''){
                    // verify primary catg is textWithPic or not
                    $verifyRes=PrimaryCatgData::where('BelongMainMenu',$set_MainMenu)->where('BelongSub',$set_Submenu)->where('PrimaryCatgId',$set_PrimaryCatg)->where('InnerType','textWithPic')->take(1)->first();
                }
                elseif($set_MainMenu!==''&&$set_Submenu!==''){
                    // verify submenu is textWithPic or not
                    $verifyRes=SubmenuData::where('BelongMainMenu',$set_MainMenu)->where('SubmenuId',$set_Submenu)->where('InnerType','textWithPic')->take(1)->first();
                }
                if($verifyRes){
                    array_push($org_Sources,array(
                        'MainMenu'=>$set_MainMenu,
                        'Submenu'=>$set_Submenu,
                        'PrimaryCatg'=>$set_PrimaryCatg
                    ));
                }
            }
        }

        $combineWhere=array();
        foreach($org_Sources as $sourceSet){
            if($sourceSet['MainMenu']!==''&&$sourceSet['Submenu']!==''&&$sourceSet['PrimaryCatg']!==''){
                array_push($combineWhere,array(
                    array('Main_menu','=',$sourceSet['MainMenu']),
                    array('Submenu','=',$sourceSet['Submenu']),
                    array('PrimaryCatg','=',$sourceSet['PrimaryCatg']),
                ));
            }
            elseif($sourceSet['MainMenu']!==''&&$sourceSet['Submenu']!==''){
                array_push($combineWhere,array(
                    array('Main_menu','=',$sourceSet['MainMenu']),
                    array('Submenu','=',$sourceSet['Submenu']),
                ));
            }
        }

        if($ListOrder===1){
            $articles=ContentsData::join('content_architecture','content_architecture.ContentId','=','contents.ContentId')->where(function($query) use ($combineWhere){
                foreach($combineWhere as $whereTextIdx=>$whereText){
                    if($whereTextIdx===0){
                        $query->where($whereText);
                    }
                    else{
                        $query->orWhere($whereText);
                    }
                }
            })->orderBy('contents.Recommendation','DESC')->orderBy('OnDateTime','DESC')->orderBy('OnTop','DESC')->orderBy('OrderNumber','ASC')->get();
        }
        elseif($ListOrder===2){
            $articles=ContentsData::join('content_architecture','content_architecture.ContentId','=','contents.ContentId')->where(function($query) use ($combineWhere){
                foreach($combineWhere as $whereTextIdx=>$whereText){
                    if($whereTextIdx===0){
                        $query->where($whereText);
                    }
                    else{
                        $query->orWhere($whereText);
                    }
                }
            })->orderBy('OnDateTime','DESC')->orderBy('OnTop','DESC')->orderBy('OrderNumber','ASC')->get();            
        }
        $data['ListOrder']=$ListOrder;
        $data['articles']=$articles;
        $data['currentPage']='index_management';
        $data['header_title']='科普新知';
        return view('admin.index_management.adminPopularScience',$data);
    }

    public function setSource_PopularScience(Request $request){
        if($request->isMethod('post')){
            $sourceJSON=array();
            $MainMenus=request('MainMenus');
            $Submenus=request('Submenus');
            $PrimaryCatgs=request('PrimaryCatgs');
            for($i=0;$i<count($MainMenus);$i++){
                $newSource=array();
                $newSource['MainMenu']=$MainMenus[$i];
                $newSource['Submenu']='';
                $newSource['PrimaryCatg']='';
                if(isset($Submenus[$i]))
                    $newSource['Submenu']=$Submenus[$i];
                if(isset($PrimaryCatgs[$i]))
                    $newSource['PrimaryCatg']=$PrimaryCatgs[$i];
                array_push($sourceJSON,$newSource);
            }
            if(SourcesData::where('SourceKey','popular_science')->take(1)->first()){
                $maintainRes=SourcesData::where('SourceKey','popular_science')->update(array(
                    'SourceJSON'=>json_encode($sourceJSON),
                    'updated_at'=>date('Y-m-d H:i:s')
                ));
            }
            else{
                $maintainRes=SourcesData::insert(
                    array(
                        'SourceKey'=>'popular_science',
                        'SourceJSON'=>json_encode($sourceJSON),
                        'ListOrder'=>1,
                        'created_at'=>date('Y-m-d H:i:s'),
                        'updated_at'=>date('Y-m-d H:i:s')
                    )
                );
            }

            if($maintainRes){
                Session::flash('maintain_message',true);
                Session::flash('maintain_message_success','設定成功。');
            }
            else{
                Session::flash('maintain_message',true);
                Session::flash('maintain_message_fail','設定失敗，請重試。');
            }
            return redirect('/admin/index_management/setSource_PopularScience');
        }
        else{
            $chooseSubmenus=array();
            $choosePrimaryCatgs=array();
            $submenusQuery=SubmenuData::where(array(
                array('BelongMainMenu','=',$this->_popularScience_main_menu_code),
                array('Definition','=','layer')
            ))->orWhere(array(
                array('BelongMainMenu','=',$this->_popularScience_main_menu_code),
                array('Definition','=','file'),
                array('InnerType','=','textWithPic')
            ))->get();

            foreach($submenusQuery as $submenuQueryItemIdx=>$submenuQueryItem){
                if($submenuQueryItem->Definition=='layer'){
                    $childrenPrimaryCatg=PrimaryCatgData::where('BelongMainMenu',$this->_popularScience_main_menu_code)->where('BelongSub',$submenuQueryItem->SubmenuId)->where('InnerType','textWithPic')->get();
                    if($childrenPrimaryCatg->count()>0){
                       $chooseSubmenus[$submenuQueryItem->SubmenuId]=$submenuQueryItem->SubmenuName;
                    }
                }
                elseif($submenuQueryItem->Definition=='file'&&$submenuQueryItem->InnerType=='textWithPic'){
                    $chooseSubmenus[$submenuQueryItem->SubmenuId]=$submenuQueryItem->SubmenuName;
                }
            }
            $sourceRows=array();
            $oldSources=SourcesData::where('SourceKey','popular_science')->take(1)->first();
            if($oldSources){
                $decodeSources=json_decode($oldSources->SourceJSON);
                foreach($decodeSources as $decodeSourceIdx=>$decodeSource){
                    $newSourceRow=array();
                    $newSourceRow['notice']=0;
                    $newSourceRow['MainMenu']=array(
                        'val'=>$this->_popularScience_main_menu_code,
                        'text'=>$this->_popularScience_main_menu_name
                    );
                    $newSourceRow['choosePrimaryCatgs']=array();
                    if(!isset($chooseSubmenus[$decodeSource->Submenu])){
                        $newSourceRow['notice']=1;
                        $temp_submenuName=SubmenuData::where('BelongMainMenu',$this->_popularScience_main_menu_code)->where('SubmenuId',$decodeSource->Submenu)->select('SubmenuName')->take(1)->first();
                        if($temp_submenuName){
                            $chooseSubmenus[$decodeSource->Submenu]=$temp_submenuName->SubmenuName;
                            $newSourceRow['Submenu']=$decodeSource->Submenu;
                            $newSourceRow['PrimaryCatg']=$decodeSource->PrimaryCatg;

                            array_push($sourceRows,$newSourceRow);
                        }
                    }
                    else{
                        $choosePrimaryCatgsQuery=PrimaryCatgData::where('BelongMainMenu',$this->_popularScience_main_menu_code)->where('BelongSub',$decodeSource->Submenu)->where('InnerType','textWithPic')->get();
                        $initCheckPrimaryCatg=PrimaryCatgData::where('BelongMainMenu',$this->_popularScience_main_menu_code)->where('BelongSub',$decodeSource->Submenu)->where('PrimaryCatgId',$decodeSource->PrimaryCatg)->take(1)->first();

                        $choosePrimaryCatgsQuery=PrimaryCatgData::where('BelongMainMenu',$this->_popularScience_main_menu_code)->where('BelongSub',$decodeSource->Submenu)->where('InnerType','textWithPic')->get();
                        $hasExist=(bool)false;
                        foreach($choosePrimaryCatgsQuery as $choosePrimaryCatgQuery){
                            if($choosePrimaryCatgQuery->PrimaryCatgId==$decodeSource->PrimaryCatg){
                                $hasExist=(bool)true;
                            }
                            $choosePrimaryCatgs[$choosePrimaryCatgQuery->PrimaryCatgId]=$choosePrimaryCatgQuery->PrimaryCatgName;
                        }

                        if($hasExist===false){
                            $newSourceRow['notice']=1;
                            if(!isset($choosePrimaryCatgs[$decodeSource->PrimaryCatg])&&$initCheckPrimaryCatg){
                                $choosePrimaryCatgs[$decodeSource->PrimaryCatg]=$initCheckPrimaryCatg->PrimaryCatgName;
                            }
                        }
                        $newSourceRow['choosePrimaryCatgs']=$choosePrimaryCatgs;
                        $newSourceRow['Submenu']=$decodeSource->Submenu;
                        $newSourceRow['PrimaryCatg']=$decodeSource->PrimaryCatg;

                        array_push($sourceRows,$newSourceRow);
                    }
                }
                $data['chooseSubmenus']=$chooseSubmenus;
                $data['sourceRows']=$sourceRows;
            }
            $data['currentPage']='index_management';
            $data['header_title']='科普新知 - 來源管理';
            return view('admin.index_management.setSource_PopularScience',$data);
        }
    }

    public function getTree(Request $request){
        $main_menu_id=0;
        $main_menu_name="";
        $target=trim($request->input('target'));
        $submenu=trim($request->input('submenu'));

        $submenus=array();
        $primaryCatgs=array();
        if($target==='popular_science'){
            $main_menu_id=$this->_popularScience_main_menu_code;
            $main_menu_name=$this->_popularScience_main_menu_name;
            if($submenu===''){
                $submenusQuery=SubmenuData::where(array(
                    array('BelongMainMenu','=',$this->_popularScience_main_menu_code),
                    array('Definition','=','layer')
                ))->orWhere(array(
                    array('BelongMainMenu','=',$this->_popularScience_main_menu_code),
                    array('Definition','=','file'),
                    array('InnerType','=','textWithPic')
                ))->get();

                foreach($submenusQuery as $submenuQueryItemIdx=>$submenuQueryItem){
                    if($submenuQueryItem->Definition=='layer'){
                        if(count($primaryCatgs)===0){
                            $childrenPrimaryCatg=PrimaryCatgData::where('BelongMainMenu',$this->_popularScience_main_menu_code)->where('BelongSub',$submenuQueryItem->SubmenuId)->where('InnerType','textWithPic')->get();
                            if($childrenPrimaryCatg->count()>0){
                                array_push($submenus,$submenuQueryItem);
                                $primaryCatgs=$childrenPrimaryCatg;
                            }
                        }
                        else{
                            $childrenPrimaryCatg=PrimaryCatgData::where('BelongMainMenu',$this->_popularScience_main_menu_code)->where('BelongSub',$submenuQueryItem->SubmenuId)->where('InnerType','textWithPic')->take(1)->first();
                            if($childrenPrimaryCatg){
                                array_push($submenus,$submenuQueryItem);
                            }
                        }
                    }
                    elseif($submenuQueryItem->Definition=='file'&&$submenuQueryItem->InnerType=='textWithPic'){
                        array_push($submenus,$submenuQueryItem);
                    }
                }
                return json_encode(array(
                    'MainMenuId'=>$main_menu_id,
                    'MainMenuName'=>$main_menu_name,
                    'Submenus'=>$submenus,
                    'PrimaryCatgs'=>$primaryCatgs
                ));
            }
            else{
                $primaryCatgs=PrimaryCatgData::where('BelongMainMenu',$this->_popularScience_main_menu_code)->where('BelongSub',$submenu)->where('InnerType','textWithPic')->get();
                return json_encode(array(
                    'PrimaryCatgs'=>$primaryCatgs
                ));
            }
        }
        elseif($target==="popular_video"){
            $main_menu_id=$this->_popularScience_main_menu_code;
            $main_menu_name=$this->_popularScience_main_menu_name;
            if($submenu===''){
                $submenusQuery=SubmenuData::where(array(
                    array('BelongMainMenu','=',$this->_popularScience_main_menu_code),
                    array('Definition','=','layer')
                ))->orWhere(array(
                    array('BelongMainMenu','=',$this->_popularScience_main_menu_code),
                    array('Definition','=','file'),
                    array('InnerType','=','media')
                ))->get();

                foreach($submenusQuery as $submenuQueryItemIdx=>$submenuQueryItem){
                    if($submenuQueryItem->Definition=='layer'){
                        if(count($primaryCatgs)===0){
                            $childrenPrimaryCatg=PrimaryCatgData::where('BelongMainMenu',$this->_popularScience_main_menu_code)->where('BelongSub',$submenuQueryItem->SubmenuId)->where('InnerType','media')->get();
                            if($childrenPrimaryCatg->count()>0){
                                array_push($submenus,$submenuQueryItem);
                                $primaryCatgs=$childrenPrimaryCatg;
                            }
                        }
                        else{
                            $childrenPrimaryCatg=PrimaryCatgData::where('BelongMainMenu',$this->_popularScience_main_menu_code)->where('BelongSub',$submenuQueryItem->SubmenuId)->where('InnerType','media')->take(1)->first();
                            if($childrenPrimaryCatg){
                                array_push($submenus,$submenuQueryItem);
                            }
                        }
                    }
                    elseif($submenuQueryItem->Definition=='file'&&$submenuQueryItem->InnerType=='media'){
                        array_push($submenus,$submenuQueryItem);
                    }
                }
                return json_encode(array(
                    'MainMenuId'=>$main_menu_id,
                    'MainMenuName'=>$main_menu_name,
                    'Submenus'=>$submenus,
                    'PrimaryCatgs'=>$primaryCatgs
                ));
            }
            else{
                $primaryCatgs=PrimaryCatgData::where('BelongMainMenu',$this->_popularScience_main_menu_code)->where('BelongSub',$submenu)->where('InnerType','media')->get();
                return json_encode(array(
                    'PrimaryCatgs'=>$primaryCatgs
                ));
            }
        }
    }

    public function setOrder_PopularScience(Request $request){
        $newOrderBy=intval(request('order_popularScience'));
        SourcesData::where('SourceKey','popular_science')->update(array(
            'ListOrder'=>$newOrderBy,
            'updated_at'=>date('Y-m-d H:i:s')
        ));
        return redirect('/admin/index_management/popular_science');
    }

    // hotvideo
    public function popular_video(Request $request){
        Session::flash('edit_prev_url','/admin/index_management/popular_video');
        $showDateTime=date('Y-m-d H:i:s');
        $data['showDateTime']=$showDateTime;
        $org_Sources=array();
        $articles=array();
        // get source first
        $ListOrder=1;
        $Sources=SourcesData::where('SourceKey','popular_video')->select('SourceJSON','ListOrder')->take(1)->first();
        if($Sources){
            $ListOrder=(INT)$Sources->ListOrder;
            $SourceJSON=json_decode($Sources->SourceJSON);
            foreach($SourceJSON as $set){
                $set_MainMenu=$set->MainMenu;
                $set_Submenu=$set->Submenu;
                $set_PrimaryCatg=$set->PrimaryCatg;
                if($set_MainMenu!==''&&$set_Submenu!==''&&$set_PrimaryCatg!==''){
                    // verify primary catg is media or not
                    $verifyRes=PrimaryCatgData::where('BelongMainMenu',$set_MainMenu)->where('BelongSub',$set_Submenu)->where('PrimaryCatgId',$set_PrimaryCatg)->where('InnerType','media')->take(1)->first();
                }
                elseif($set_MainMenu!==''&&$set_Submenu!==''){
                    // verify submenu is media or not
                    $verifyRes=SubmenuData::where('BelongMainMenu',$set_MainMenu)->where('SubmenuId',$set_Submenu)->where('InnerType','media')->take(1)->first();
                }
                if($verifyRes){
                    array_push($org_Sources,array(
                        'MainMenu'=>$set_MainMenu,
                        'Submenu'=>$set_Submenu,
                        'PrimaryCatg'=>$set_PrimaryCatg
                    ));
                }
            }
        }

        $combineWhere=array();
        foreach($org_Sources as $sourceSet){
            if($sourceSet['MainMenu']!==''&&$sourceSet['Submenu']!==''&&$sourceSet['PrimaryCatg']!==''){
                array_push($combineWhere,array(
                    array('Main_menu','=',$sourceSet['MainMenu']),
                    array('Submenu','=',$sourceSet['Submenu']),
                    array('PrimaryCatg','=',$sourceSet['PrimaryCatg']),
                ));
            }
            elseif($sourceSet['MainMenu']!==''&&$sourceSet['Submenu']!==''){
                array_push($combineWhere,array(
                    array('Main_menu','=',$sourceSet['MainMenu']),
                    array('Submenu','=',$sourceSet['Submenu']),
                ));
            }
        }

        if($ListOrder===1){
            $articles=ContentsData::join('content_architecture','content_architecture.ContentId','=','contents.ContentId')->where(function($query) use ($combineWhere){
                foreach($combineWhere as $whereTextIdx=>$whereText){
                    if($whereTextIdx===0){
                        $query->where($whereText);
                    }
                    else{
                        $query->orWhere($whereText);
                    }
                }
            })->orderBy('contents.Recommendation','DESC')->orderBy('Visitors','DESC')->orderBy('OnTop','DESC')->orderBy('OrderNumber','ASC')->get();
        }
        elseif($ListOrder===2){
            $articles=ContentsData::join('content_architecture','content_architecture.ContentId','=','contents.ContentId')->where(function($query) use ($combineWhere){
                foreach($combineWhere as $whereTextIdx=>$whereText){
                    if($whereTextIdx===0){
                        $query->where($whereText);
                    }
                    else{
                        $query->orWhere($whereText);
                    }
                }
            })->orderBy('Visitors','DESC')->orderBy('OnTop','DESC')->orderBy('OrderNumber','ASC')->get();            
        }
        $data['ListOrder']=$ListOrder;
        $data['articles']=$articles;
        $data['currentPage']='index_management';
        $data['header_title']='熱門影音';
        return view('admin.index_management.adminPopularVideo',$data);        
    }

    public function setSource_PopularVideo(Request $request){
        if($request->isMethod('post')){
            $sourceJSON=array();
            $MainMenus=request('MainMenus');
            $Submenus=request('Submenus');
            $PrimaryCatgs=request('PrimaryCatgs');
            for($i=0;$i<count($MainMenus);$i++){
                $newSource=array();
                $newSource['MainMenu']=$MainMenus[$i];
                $newSource['Submenu']='';
                $newSource['PrimaryCatg']='';
                if(isset($Submenus[$i]))
                    $newSource['Submenu']=$Submenus[$i];
                if(isset($PrimaryCatgs[$i]))
                    $newSource['PrimaryCatg']=$PrimaryCatgs[$i];
                array_push($sourceJSON,$newSource);
            }
            if(SourcesData::where('SourceKey','popular_video')->take(1)->first()){
                $maintainRes=SourcesData::where('SourceKey','popular_video')->update(array(
                    'SourceJSON'=>json_encode($sourceJSON),
                    'updated_at'=>date('Y-m-d H:i:s')
                ));
            }
            else{
                $maintainRes=SourcesData::insert(
                    array(
                        'SourceKey'=>'popular_video',
                        'SourceJSON'=>json_encode($sourceJSON),
                        'ListOrder'=>1,
                        'created_at'=>date('Y-m-d H:i:s'),
                        'updated_at'=>date('Y-m-d H:i:s')
                    )
                );
            }

            if($maintainRes){
                Session::flash('maintain_message',true);
                Session::flash('maintain_message_success','設定成功。');
            }
            else{
                Session::flash('maintain_message',true);
                Session::flash('maintain_message_fail','設定失敗，請重試。');
            }
            return redirect('/admin/index_management/setSource_PopularVideo');
        }
        else{
            $chooseSubmenus=array();
            $choosePrimaryCatgs=array();
            $submenusQuery=SubmenuData::where(array(
                array('BelongMainMenu','=',$this->_popularScience_main_menu_code),
                array('Definition','=','layer')
            ))->orWhere(array(
                array('BelongMainMenu','=',$this->_popularScience_main_menu_code),
                array('Definition','=','file'),
                array('InnerType','=','media')
            ))->get();

            foreach($submenusQuery as $submenuQueryItemIdx=>$submenuQueryItem){
                if($submenuQueryItem->Definition=='layer'){
                    $childrenPrimaryCatg=PrimaryCatgData::where('BelongMainMenu',$this->_popularScience_main_menu_code)->where('BelongSub',$submenuQueryItem->SubmenuId)->where('InnerType','media')->get();
                    if($childrenPrimaryCatg->count()>0){
                       $chooseSubmenus[$submenuQueryItem->SubmenuId]=$submenuQueryItem->SubmenuName;
                    }
                }
                elseif($submenuQueryItem->Definition=='file'&&$submenuQueryItem->InnerType=='media'){
                    $chooseSubmenus[$submenuQueryItem->SubmenuId]=$submenuQueryItem->SubmenuName;
                }
            }
            $sourceRows=array();
            $oldSources=SourcesData::where('SourceKey','popular_video')->take(1)->first();
            if($oldSources){
                $decodeSources=json_decode($oldSources->SourceJSON);
                foreach($decodeSources as $decodeSourceIdx=>$decodeSource){
                    $newSourceRow=array();
                    $newSourceRow['notice']=0;
                    $newSourceRow['MainMenu']=array(
                        'val'=>$this->_popularScience_main_menu_code,
                        'text'=>$this->_popularScience_main_menu_name
                    );
                    $newSourceRow['choosePrimaryCatgs']=array();
                    if(!isset($chooseSubmenus[$decodeSource->Submenu])){
                        $newSourceRow['notice']=1;
                        $temp_submenuName=SubmenuData::where('BelongMainMenu',$this->_popularScience_main_menu_code)->where('SubmenuId',$decodeSource->Submenu)->select('SubmenuName')->take(1)->first();
                        if($temp_submenuName){
                            $chooseSubmenus[$decodeSource->Submenu]=$temp_submenuName->SubmenuName;
                            $newSourceRow['Submenu']=$decodeSource->Submenu;
                            $newSourceRow['PrimaryCatg']=$decodeSource->PrimaryCatg;

                            array_push($sourceRows,$newSourceRow);
                        }
                    }
                    else{
                        $choosePrimaryCatgsQuery=PrimaryCatgData::where('BelongMainMenu',$this->_popularScience_main_menu_code)->where('BelongSub',$decodeSource->Submenu)->where('InnerType','media')->get();
                        $initCheckPrimaryCatg=PrimaryCatgData::where('BelongMainMenu',$this->_popularScience_main_menu_code)->where('BelongSub',$decodeSource->Submenu)->where('PrimaryCatgId',$decodeSource->PrimaryCatg)->take(1)->first();

                        $choosePrimaryCatgsQuery=PrimaryCatgData::where('BelongMainMenu',$this->_popularScience_main_menu_code)->where('BelongSub',$decodeSource->Submenu)->where('InnerType','media')->get();
                        $hasExist=(bool)false;
                        foreach($choosePrimaryCatgsQuery as $choosePrimaryCatgQuery){
                            if($choosePrimaryCatgQuery->PrimaryCatgId==$decodeSource->PrimaryCatg){
                                $hasExist=(bool)true;
                            }
                            $choosePrimaryCatgs[$choosePrimaryCatgQuery->PrimaryCatgId]=$choosePrimaryCatgQuery->PrimaryCatgName;
                        }

                        if($hasExist===false){
                            $newSourceRow['notice']=1;
                            if(!isset($choosePrimaryCatgs[$decodeSource->PrimaryCatg])&&$initCheckPrimaryCatg){
                                $choosePrimaryCatgs[$decodeSource->PrimaryCatg]=$initCheckPrimaryCatg->PrimaryCatgName;
                            }
                        }
                        $newSourceRow['choosePrimaryCatgs']=$choosePrimaryCatgs;
                        $newSourceRow['Submenu']=$decodeSource->Submenu;
                        $newSourceRow['PrimaryCatg']=$decodeSource->PrimaryCatg;

                        array_push($sourceRows,$newSourceRow);
                    }
                }
                $data['chooseSubmenus']=$chooseSubmenus;
                $data['sourceRows']=$sourceRows;
            }
            $data['currentPage']='index_management';
            $data['header_title']='熱門影音 - 來源管理';
            return view('admin.index_management.setSource_PopularVideo',$data);
        }
    }

    public function setOrder_PopularVideo(Request $request){
        $newOrderBy=intval(request('order_popularVideo'));
        SourcesData::where('SourceKey','popular_video')->update(array(
            'ListOrder'=>$newOrderBy,
            'updated_at'=>date('Y-m-d H:i:s')
        ));
        return redirect('/admin/index_management/popular_video');
    }
}
