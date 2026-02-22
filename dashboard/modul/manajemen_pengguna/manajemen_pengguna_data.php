<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'sys_users.first_name',
    'sys_users.username',
    'sys_users.email',
    'sys_group_users.level',
    'sys_group_users.level_name',
    'sys_users.aktif',
    'sys_users.id',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('aktif','sys_users.id');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("sys_users.id");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by sys_users.id";

$jenis_akun = "";
$group_level = "";
$aktif = "";

    if ($_POST['jenis_akun']!='all') {
      $jenis_akun = ' and sys_group_level.level="'.$_POST['jenis_akun'].'"';
    }
    if ($_POST['group_level']!='all') {
      $group_level = ' and group_level="'.$_POST['group_level'].'"';
    }
    if ($_POST['aktif']!='all') {
      $aktif = ' and aktif="'.$_POST['aktif'].'"';
    }


  $query = $datatable->get_custom("select sys_group_users.level, username,concat(sys_users.first_name,' ',last_name) as first_name, sys_group_users.level,sys_users.email,sys_group_users.level_name,
sys_users.aktif,sys_users.id from sys_users left join sys_group_users on sys_users.group_level=sys_group_users.id 
left join sys_group_level on sys_group_users.level=sys_group_level.level
where group_level not in(3,4) and  sys_group_users.level!='root'  and sys_users.id not in(1) $jenis_akun $group_level $aktif",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->first_name;
    $ResultData[] = $value->username;
    $ResultData[] = $value->email;
    $ResultData[] = $value->level;
    $ResultData[] = $value->level_name;
    if ($value->aktif=='Y') {
       $ResultData[] = '<span class="btn btn-success btn-xs"><i class="fa fa-check"></i> Aktif</span> <a href="'.base_admin().'inc/login_as.php?id='.$value->id.'&adm_id='.$_SESSION['id_user'].'&url=manajemen-pengguna&back_uri=manajemen-pengguna" class="btn btn-success btn-sm" data-toggle="tooltip" title="" data-original-title="Login As"><i class="fa fa-user"></i></a>';
    } else {
       $ResultData[] = '<span class="btn btn-danger btn-xs"><i class="fa fa-close"></i> Non Aktif</span> <a href="'.base_admin().'inc/login_as.php?id='.$value->id.'&adm_id='.$_SESSION['id_user'].'&url=manajemen-pengguna&back_uri=manajemen-pengguna" class="btn btn-success btn-sm" data-toggle="tooltip" title="" data-original-title="Login As"><i class="fa fa-user"></i></a>';
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