<?php
session_start();
include "../../inc/config.php";
session_check();
switch ($_GET["act"]) {
  case 'up_logo':
      $data = array(
   );
   
   
   
    if(isset($_FILES["logo"]["name"])) {
                        if (!preg_match("/.(png|jpg|jpeg|gif|bmp)$/i", $_FILES["logo"]["name"]) ) {

              echo "pastikan file yang anda pilih gambar";
              exit();

            } else {
      move_uploaded_file($_FILES["logo"]["tmp_name"], "../../../upload/logo/".$_FILES['logo']['name']);

              $logo = array("logo"=>$_FILES["logo"]["name"]);
              $data = array_merge($data,$logo);
            }

                         }

    
    
    $up = $db->update("identitas_logo",$data,"id_logo",$_POST["id"]);
    
    if ($up=true) {
      echo "good";
    } else {
      return false;
    }
    break;
  case "in":
    
  //removing first and last php tag from tinymce  
  $str = preg_replace(array('/^\<p\>/','/\<\/p\>$/'), "", $_POST["isi"]);

  $data = array(
      "id_identitas" => $_POST["id_identitas"],
      "ket" => $_POST["ket"],
      "isi" => $str,
  );
  
   
    $in = $db->insert("identitas",$data);
    
    
    if ($in=true) {
      echo "good";
    } else {
      return false;
    }
    break;
  case "delete":
    
    
    
    $db->delete("identitas","id_identitas",$_GET["id"]);
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("identitas","id_identitas",$id);
         }
    }
    break;
  case "up":
    
  //removing first and last php tag from tinymce  
  $str = preg_replace(array('/^\<p\>/','/\<\/p\>$/'), "", $_POST["isi"]);

  $data = array(
      "id_identitas" => $_POST["id_identitas"],
      "ket" => $_POST["ket"],
      "isi" => $str,
  );
  

    
    
    $up = $db->update("identitas",$data,"id_identitas",$_POST["id"]);
    
    if ($up=true) {
      echo "good";
    } else {
      return false;
    }
    break;
  default:
    # code...
    break;
}

?>