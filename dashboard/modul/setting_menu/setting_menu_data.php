<?php
include "../../inc/config.php";

$columns = array(
    'sys_menu.page_name',
    'sys_menu.type_menu',
    'sys_menu.urutan_menu',
    'sys_menu.parent',
    'sys_menu.id',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('urutan_menu','sys_menu.id');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("sys_menu.id");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by sys_menu.id";

  $query = $datatable->get_custom("select sys_menu.page_name,sys_menu.type_menu,sys_menu.urutan_menu,sys_menu.parent,sys_menu.id from sys_menu where is_menu_pendaftaran='Y'",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->page_name;
    $ResultData[] = $value->type_menu;
    $ResultData[] = $value->urutan_menu;
    $ResultData[] = $value->parent;
    $ResultData[] = $value->id;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>