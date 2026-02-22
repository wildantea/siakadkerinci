<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  case "in":
    
  if (!is_dir("../../../upload/upload_drive")) {
              mkdir("../../../upload/upload_drive");
            }
  
  
  $data = array(
      "nama" => $_POST["nama"],
  );
  
                    if (!preg_match("/.(pdf|txt|docx|doc|jpg|jpeg|png)$/i", $_FILES["file"]["name"]) ) {

              action_response($lang["upload_file_error_extention"]."pdf|txt|docx|doc|jpg|jpeg|png"); 
              exit();

            } else {
              move_uploaded_file($_FILES["file"]["tmp_name"], "../../../upload/upload_drive/".$_FILES['file']['name']);
              $data["file"] = $_FILES["file"]["name"];
            }
  
  
   
    $in = $db->insert("tes",$data);
    
    
    action_response($db->getErrorMessage());
    break;
  case "delete":
    
    
    $db->deleteDirectory("../../../upload/upload_drive/".$db->fetchSingleRow("tes","id",$_GET["id"])->file);
    $db->delete("tes","id",$_POST["id"]);
    action_response($db->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("tes","id",$id);
         }
    }
    action_response($db->getErrorMessage());
    break;
  case "up":
    
   $data = array(
      "nama" => $_POST["nama"],
   );
   
                         if(isset($_FILES["file"]["name"])) {
                        if (!preg_match("/.(pdf|txt|docx|doc|jpg|jpeg|png)$/i", $_FILES["file"]["name"]) ) {
              action_response($lang["upload_file_error_extention"]."pdf|txt|docx|doc|jpg|jpeg|png"); 
              exit();

            } else {
              move_uploaded_file($_FILES["file"]["tmp_name"], "../../../upload/upload_drive/".$_FILES['file']['name']);
              $db->deleteDirectory("../../../upload/upload_drive/".$db->fetchSingleRow("tes","id",$_POST["id"])->file);
              $data["file"] = $_FILES["file"]["name"];
            }

                         }
   
   

    
    
    $up = $db->update("tes",$data,"id",$_POST["id"]);
    
    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>