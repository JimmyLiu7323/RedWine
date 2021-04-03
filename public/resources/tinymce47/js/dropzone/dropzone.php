<?php
function url(){
    return sprintf("%s://%s",isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',$_SERVER['SERVER_NAME']);
}
$ext_img = array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff'); //Images
if($_POST){
	if(isset($_POST['hidden_pn'])){
        $newPN=$_POST['hidden_pn'];
        $scanDir=scandir("../../../filemanager");
        if(!in_array($_POST['hidden_pn'],$scanDir)) mkdir("../../../filemanager/".$newPN);
        $filelist=scandir("../../../filemanager/".$newPN);
        $count_uploadedFiles=0;
        foreach($filelist as $i=>$v){
        	if($v=="."||$v=="..") continue;
            if(strpos($v,$newPN)===0){
            	$count_uploadedFiles++;
            }
        }
        $pictureSN=++$count_uploadedFiles;
        if (!empty($_FILES)){
		    $tempFile=$_FILES['file']['tmp_name'];   
		    $imginfo=getimagesize($tempFile);
		    $shouldBeType=end(explode("/",$imginfo['mime']));
		    $targetPath="../../../filemanager/".$newPN;
		    // $path=$_FILES['file']['name'];
            // $ext=pathinfo($path,PATHINFO_EXTENSION);
		    $targetFile=$targetPath."/".$newPN."_".$pictureSN.".".$shouldBeType;
		    if(move_uploaded_file($tempFile,$targetFile)){
			    if(in_array(strtolower(substr(strrchr($_FILES['file']['name'],'.'),1)),$ext_img)) $is_img=true;
			    else $is_img=false;

			    if($is_img){
				    $imginfo=getimagesize($targetFile);
				    $srcWidth=$imginfo[0];
				    $srcHeight=$imginfo[1];
				    $shouldBeType=end(explode("/",$imginfo['mime']));
				    if($srcWidth>720){
				    	require_once("php_image_magician.php");
				    	$newHeight=intval(720*$srcHeight/$srcWidth); 
	                    $magicianObj=new imageLib($targetFile);
					    // *** Resize to best fit then crop
					    $magicianObj->resizeImage(720,$newHeight,'crop');  
					    // *** Save resized image as a PNG
					    $newFile=$targetPath."/".$newPN."_".$pictureSN.".".$shouldBeType;
					    $magicianObj->saveImage($newFile);
					    echo $newPN."/".$newPN."_".$pictureSN.".".$shouldBeType;
					    exit();
				    }   
			    }
			    echo $newPN."/".$newPN."_".$pictureSN.".".$shouldBeType;
			}
		}
	}
}
?>