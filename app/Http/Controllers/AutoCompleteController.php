<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\SubmenuData;
use App\PrimaryCatgData;
use App\MinorCatgData;
use App\ContentsData;

class AutoCompleteController extends Controller
{
	public function doSearch(Request $request,$target){
		$searchTxt=request('term');
		$searchRes=false;
		$rtnSearchRes=array();
		if($searchTxt!=''){
			if($target=='main_menus'){
				$searchRes=DB::table('main_menu')->where('MenuName','like',"%$searchTxt%")->get();
			}
			elseif($target=='submenus'){
				$searchRes=SubmenuData::where('SubmenuName','like',"%$searchTxt%")->get();
			}
			elseif($target=='primary_catgs'){
				$searchRes=PrimaryCatgData::where('PrimaryCatgName','like',"%$searchTxt%")->get();	
			}
			elseif($target=='contents'){
				$searchRes=ContentsData::where('ContentTitle','like',"%$searchTxt%")->get();		
			}

			if($searchRes){
				foreach($searchRes as $item){
					if($target=='main_menus'){
						array_push($rtnSearchRes,array(
							'label'=>$item->MenuName,
							'value'=>$item->MenuId
						));
					}
					elseif($target=='submenus'){
						array_push($rtnSearchRes,array(
							'label'=>$item->SubmenuName,
							'value'=>$item->SubmenuId
						));						
					}
					elseif($target=='primary_catgs'){
						array_push($rtnSearchRes,array(
							'label'=>$item->PrimaryCatgName,
							'value'=>$item->PrimaryCatgId
						));	
					}
					elseif($target=='contents'){
						array_push($rtnSearchRes,array(
							'label'=>$item->ContentTitle,
							'value'=>$item->ContentId
						));						
					}
				}
			}
		}
		echo json_encode($rtnSearchRes);
	}
}
