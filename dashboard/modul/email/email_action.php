<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  case "in":
    
  
  
  
  $data = array(
      "email" => $_POST["email"],
      "client_id" => $_POST["client_id"],
      "client_secret" => $_POST["client_secret"],
      "redirect_url" => $_POST["redirect_url"],
      "refresh_token" => $_POST["refresh_token"],
      "access_token" => $_POST["access_token"],
  );
  
          if(isset($_POST["aktif"])=="on")
          {
            $data['aktif'] = 'Y';
          } else {
            $data['aktif'] = 'N';
          }
  
   
          if(isset($_POST["login"])=="on")
          {
            $login = array("login"=>"Y");
            $data=array_merge($data,$login);
          } else {
            $login = array("login"=>"N");
            $data=array_merge($data,$login);
          }
    $in = $db->insert("tb_token",$data);
    
    
    action_response($db->getErrorMessage());
    break;
  case "delete":
    
    
    
    $db->delete("tb_token","id",$_GET["id"]);
    action_response($db->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("tb_token","id",$id);
         }
    }
    action_response($db->getErrorMessage());
    break;
  case "up":
    
   $data = array(
      "email" => $_POST["email"],
      "client_id" => $_POST["client_id"],
      "client_secret" => $_POST["client_secret"],
      "redirect_url" => $_POST["redirect_url"],
     // "refresh_token"=>$_POST["refresh_token"],
     // "access_token"=>$_POST["access_token"],
   );
   
   
   
  
          if(isset($_POST["aktif"])=="on")
          {
            $data['aktif'] = 'Y';
          } else {
            $data['aktif'] = 'N';
          }
    
          if(isset($_POST["login"])=="on")
          {
            $login = array("login"=>"Y");
            $data=array_merge($data,$login);
          } else {
            $login = array("login"=>"N");
            $data=array_merge($data,$login);
          }
    
    $up = $db->update("tb_token",$data,"id",$_POST["id"]);
    
    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>