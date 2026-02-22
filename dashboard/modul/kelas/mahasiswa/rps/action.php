<?php
error_reporting(0);
session_start();
$time_start = microtime(true); 
include "../../../inc/config.php";
session_check();

switch ($_GET["act"]) {
  case 'in':
 $data = array(
      "id_matkul" => $_POST["id_matkul"],
      "semester" => getSemesterAktif(),
      "createdAt" => date('Y-m-d H:i:s'),
      "createdBy" => getUser()->first_name.' '.getUser()->last_name,
      "nip" => getUser()->username
  );


 //check file size
  
   if($_FILES['file_url']['size'] > 2000000) { //10 MB (size is also in bytes)
        action_response("File tidak boleh lebih dari 2Mb");
    }

  $filename = $_FILES["file_url"]["name"];
  $filename = preg_replace("#[^a-z.0-9]#i", "", $filename);
  $upload = upload_s3('file',$filename,$_FILES["file_url"]["tmp_name"],$_FILES['file_url']['type']);
  $data['file_rps'] = $upload['ObjectURL'];


 	$in = $db2->insert("rps_file",$data);
    action_response($db2->getErrorMessage());
  break;
  case 'up':
    $file_name = $db2->fetchSingleRow("rps_file","id_rps",$_POST["id_rps"]);
      $exp = explode("/",$file_name->file_rps);
      $delete_s3 = delete_s3('file',$exp[4]);

    $data = array(
      "updatedAt" => date('Y-m-d H:i:s'),
      "updatedBy" => getUser()->first_name.' '.getUser()->last_name,
      "nip" => getUser()->username
  );

  $filename = $_FILES["file_url"]["name"];
  $filename = preg_replace("#[^a-z.0-9]#i", "", $filename);
  $upload = upload_s3('file',$filename,$_FILES["file_url"]["tmp_name"],$_FILES['file_url']['type']);
  $data['file_rps'] = $upload['ObjectURL'];
 	$in = $db2->update("rps_file",$data,'id_rps',$_POST['id_rps']);

    action_response($db2->getErrorMessage());

  break;
}