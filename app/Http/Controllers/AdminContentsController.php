<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\SubmenuData;
use App\PrimaryCatgData;
use App\MinorCatgData;
use App\ContentsData;
use Widget_Helper;

class AdminContentsController extends Controller
{
	public function quick_init(Request $request){
		$parent_main_menu=intval($request->input('parent'));
		$sub_menu=$request->input('sub');
		$primaryCatg=$request->input('primaryCatg');

		if($submenuInfo=SubmenuData::where('BelongMainMenu',$parent_main_menu)->where('SubmenuId',$sub_menu)->where('Definition','file')->take(1)->first()){
			$contentId=Widget_Helper::createID();
			DB::table('content_architecture')->insert(array('ContentId'=>$contentId,'Main_menu'=>$parent_main_menu,'Submenu'=>$sub_menu,'PrimaryCatg'=>NULL,'MinorCatg'=>NULL));
			return redirect('/admin/fileApply/editContent/'.$contentId);
		}
		elseif($primaryCatgInfo=PrimaryCatgData::where('BelongMainMenu',$parent_main_menu)->where('BelongSub',$sub_menu)->where('PrimaryCatgId',$primaryCatg)->take(1)->first()){
			$contentId=Widget_Helper::createID();
			DB::table('content_architecture')->insert(array('ContentId'=>$contentId,'Main_menu'=>$parent_main_menu,'Submenu'=>$sub_menu,'PrimaryCatg'=>$primaryCatg,'MinorCatg'=>NULL));
			return redirect('/admin/fileApply/editContent/'.$contentId);
		}
		return redirect('/admin/layers/main');
	}

	public function quick_getInformation(Request $request){
		$InfoJSON=array(
			'Title'=>'',
			'Recommendation'=>0,
			'Visitors'=>0,
		);
		$ContentId=$request->input('id');
		$Info=ContentsData::where('ContentId',$ContentId)->take(1)->first();
		if($Info){
			$InfoJSON['Title']=$Info->ContentTitle;
			$InfoJSON['Recommendation']=$Info->Recommendation;
			$InfoJSON['Visitors']=$Info->Visitors;
		}
		return json_encode($InfoJSON);
	}

	public function update_recommendation(Request $request){
		$Recommendation=intval(request('Recommendation'));
		$editingContent=request('editingContent');
		$contentAddVisitors=intval(request('contentAddVisitors'));
		$sourceTarget=request('sourceTarget');
		if($sourceTarget==='popular_science'){
			ContentsData::where('ContentId',$editingContent)->update(array(
				'Recommendation'=>$Recommendation,
				'updated_at'=>date('Y-m-d H:i:s')
			));
		}
		else{
			ContentsData::where('ContentId',$editingContent)->update(array(
				'Recommendation'=>$Recommendation,
				'Visitors'=>DB::raw('Visitors + '.$contentAddVisitors),
				'updated_at'=>date('Y-m-d H:i:s')
			));
		}
		return redirect('/admin/index_management/'.$sourceTarget);
		
	}
}
