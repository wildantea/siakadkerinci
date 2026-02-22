<?php
include "../../inc/config.php";

$columns = array(
    '.',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('','.');
  

  //set order by column
  $datatable->set_order_by("");

  //set order by type
  $datatable->set_order_type("");

  //set group by column
  //$new_table->group_by = "group by .";

  $query = $datatable->get_custom("select ,. from ",$columns);

  //buat inisialisasi array data
  $data = array();

  
  foreach ($query as $value) {

    //array data
    $ResultData = array();
  
  
    $ResultData[] = $value->;

    $data[] = $ResultData;
    
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>