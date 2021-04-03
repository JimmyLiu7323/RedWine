<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class TinymceController extends Controller
{
	public function upload_image(Request $request){
        if($request->file('file')){
            if($uploadRes=Storage::disk('public_uploads')->put(".",$request->file('file'))){
                $tinymce_image=substr($uploadRes,2);
                echo json_encode(array('location'=>asset('/uploads/'.$tinymce_image)));
            }
            else{
                echo json_encode(array('location'=>'fail'));
            }
        }
    }
}
