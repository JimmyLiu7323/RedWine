<?php
namespace App\Http\Controllers\Backend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Members\MembersData;

class AdminMembersController extends Controller
{
    public function __construct(){

    }

    public function index(Request $request){
        $whereConditions=array();
        $searchQ = "";
        $searchType = "";
        if($request->has('type') && $request->has('q')){
            if(trim(request('type'))!=='' && trim(request('q'))!==''){
                $searchType = request('type');
                $searchQ = request('q');
                if($searchType==='id'){
                    array_push($whereConditions,array(
                        'MemberId','=',$searchQ
                    ));
                }
                elseif($searchType==='name'){
                    array_push($whereConditions,array('FirstName','LIKE','%'.$searchQ.'%'));
                    array_push($whereConditions,array('MiddleName','LIKE','%'.$searchQ.'%'));
                    array_push($whereConditions,array('LastName','LIKE','%'.$searchQ.'%'));
                }
            }
        }

        $members = MembersData::where(function($query) use ($whereConditions){
            if(count($whereConditions)>0){
                foreach($whereConditions as $condition){
                    $query->orWhere($condition[0],$condition[1],$condition[2]);
                }
            }
        })->orderBy('Country','ASC')->paginate(10);

        $data['searchType'] = $searchType;
        $data['members'] = $members;
        $data['searchQ'] = $searchQ;
        $data['currentPage']='members';
        $data['header_title']='Members';
        return view('admin.members.index',$data);
    }

    public function delete_member(){
        $id = request('id');
        MembersData::where('MemberId',$id)->delete();
        return redirect()->back();
    }
}