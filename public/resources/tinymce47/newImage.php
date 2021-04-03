<?php
session_start();
$editor_folder=$_SESSION['editor_folder'];
$adminInfo=$_SESSION['admin'];
if(isset($adminInfo['id'])){
	$newImage=$_FILES['file'];
	$newImage_type=explode('/',$newImage['type']);
	$newImage_type=$newImage_type[1];
	$newImage_name=uniqid().'.'.$newImage_type;
	if(move_uploaded_file($newImage['tmp_name'],'/home/ytop/public_html/archive/'.$editor_folder.'/images/'.$newImage_name)){
		echo json_encode(array('location'=>'archive/'.$editor_folder.'/images/'.$newImage_name));
	}
	else{
		echo json_encode(array('location'=>'fail'));
	}
}
else{
	header('HTTP/1.1 403 Forbidden');
}
?>