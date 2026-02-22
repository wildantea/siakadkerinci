<?php
include "../../inc/config.php";

$columns = array(
    'tb_token.email',
    'tb_token.redirect_url',
    'tb_token.login',
    'tb_token.id',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('access_token','tb_token.id');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("tb_token.id");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by tb_token.id";

  $query = $datatable->get_custom("select * from tb_token",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
    $ResultData[] = $value->email;
    $ResultData[] = $value->redirect_url;
    if ($value->login=='N') {
      $ResultData[] = "<a class='btn btn-xs btn-primary' href='$value->redirect_url'>Click Login Now</a>";
    } else {  
    $ResultData[] = '<span class="btn btn-xs btn-success">Yes</span>';
    }
    if ($value->aktif=='N') {
      $ResultData[] = '<span class="btn btn-xs btn-danger">No</span>';
    } else {  
    $ResultData[] = '<span class="btn btn-xs btn-success">Yes</span>';
    }
    $ResultData[] = $value->id;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>