<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  case "in":
  $nav_act = str_replace(" ", "_", strtolower($_POST['page_name']));
  //check if menu exist
  $check_menu = $db->query("select * from sys_menu where nav_act='$nav_act'");
  if ($check_menu->rowCount()>0) {
   action_response('Nama Menu ini sudah ada');
  }
 
 //jika type menu adalah single menu, parent set jadi 0
if ($_POST['type_menu']=='page') {
   $parent       = 0;
   $parent_name  = $db->fetch_single_row('sys_menu','id',$parent)->page_name;
   $parent_name  = $parent_name;
} else {
   $parent       = $_POST['parent'];
   $parent_name  = "";
}

 if ($_POST['type_menu']=='main') {

  $data = array(
    'page_name'   => strtolower($_POST['page_name']),
    'icon'        => $_POST['icon'],
    'urutan_menu' => $_POST['urutan_menu'],
    'parent'      => $parent,
    'parent_name' => $parent_name,
    'tampil'      => $tampil,
    'type_menu'   => $_POST['type_menu']
    );
  $db->insert('sys_menu',$data);

    $last_id= $db->last_insert_id();

  foreach ($db->fetch_all('sys_group_users') as $group) {
    if ($group->level=='admin') {
      $db->query("insert into sys_menu_role(id_menu,group_level,read_act,insert_act,update_act,delete_act)
  values (".$last_id.",'".$group->level."','Y','Y','Y','Y')");
    } else {
      $db->query("insert into sys_menu_role(id_menu,group_level,read_act,insert_act,update_act,delete_act)
  values (".$last_id.",'".$group->level."','N','N','N','N')");
    }

  }
  exit();
}

  $data = array(
      "page_name" => $_POST["page_name"],
      "icon" => $_POST["icon"],
      "type_menu" => $_POST["type_menu"],
      "parent" => $_POST["parent"],
      "urutan_menu" => $_POST["urutan_menu"],
  );
  
  
  
   
    $in = $db->insert("sys_menu",$data);
    
    
    action_response($db->getErrorMessage());
    break;
  case "delete":
    
    
    
    $db->delete("sys_menu","id",$_GET["id"]);
    action_response($db->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("sys_menu","id",$id);
         }
    }
    action_response($db->getErrorMessage());
    break;
  case "up":
    
   $data = array(
      "page_name" => $_POST["page_name"],
      "icon" => $_POST["icon"],
      "type_menu" => $_POST["type_menu"],
      "parent" => $_POST["parent"],
      "urutan_menu" => $_POST["urutan_menu"],
   );
   
   
   

    
    
    $up = $db->update("sys_menu",$data,"id",$_POST["id"]);
    
    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>