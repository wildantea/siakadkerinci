<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  case "in":

  
  $data = array(
      "level" => $_POST["level"],
      "level_name" => $_POST["level_name"],
      "deskripsi" => $_POST["deskripsi"],
  );
  
  //check if menu role is not exist
  $exist = $db->fetch_custom_single("select * from sys_menu_role where group_level='".$_POST['level']."'");
  if (!$exist) {
    $db->query("insert into sys_menu_role(id_menu,group_level,read_act,insert_act,update_act,delete_act)
select sys_menu.id,'".$_POST["level"]."','N','N','N','N' from sys_menu");
  }
    
  

    $in = $db->insert("sys_group_users",$data);
    
    
    action_response($db->getErrorMessage());
    break;
  case "delete":
    
    
    
    $db->delete("sys_group_users","id",$_GET["id"]);
    action_response($db->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("sys_group_users","id",$id);
         }
    }
    action_response($db->getErrorMessage());
    break;
  case "up":
    
   $data = array(
      "level" => $_POST["level"],
      "level_name" => $_POST["level_name"],
      "deskripsi" => $_POST["deskripsi"],
   );


    $up = $db->update("sys_group_users",$data,"id",$_POST["id"]);
    $exist = $db->fetch_custom_single("select * from sys_menu_role where group_level='".$_POST['level']."'");
   if (!$exist) {
    $db->query("insert into sys_menu_role(id_menu,group_level,read_act,insert_act,update_act,delete_act) select sys_menu.id,'".$_POST["level"]."','N','N','N','N' from sys_menu");
  }
    
    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>