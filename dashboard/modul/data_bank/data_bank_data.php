<?php
include "../../inc/config.php";

$columns = array(
    'keu_bank.kode_bank',
    'keu_bank.nomor_rekening',
    'keu_bank.nama_singkat',
    'keu_bank.nama_bank',
    'keu_bank.cabang',
    'keu_bank.kode_bank',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('cabang','keu_bank.kode_bank');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("keu_bank.kode_bank");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by keu_bank.kode_bank";

  $query = $datatable->get_custom("select keu_bank.kode_bank,keu_bank.nomor_rekening,peruntukan,aktif,keu_bank.nama_singkat,keu_bank.nama_bank,keu_bank.cabang from keu_bank",$columns);

$array_jenjang = array();
$jenjang_prodi = $db->query("select * from view_prodi_jenjang group by id_jenjang");
if ($jenjang_prodi->rowCount()>0) {
    foreach ($jenjang_prodi as $jnj) {
    $array_jenjang[$jnj->id_jenjang] = $jnj->jenjang;
}
}
  //buat inisialisasi array data
  $data = array();

  $i=1;
  $peruntukan_array = array();
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->kode_bank;
    $ResultData[] = $value->nomor_rekening;
    $ResultData[] = $value->nama_singkat;
    $ResultData[] = $value->nama_bank;
    $ResultData[] = $value->cabang;
    if ($value->aktif=='Y') {
      $ResultData[] = '<span class="btn btn-success btn-xs"><i class="fa fa-check"></i> Aktif</span>';
    } else {
      $ResultData[] = '<span class="btn btn-danger btn-xs"><i class="fa fa-close"></i> Non Aktif</span>';
    }

    $peruntukan = json_decode($value->peruntukan);
    if (!empty($peruntukan)) {
      foreach ($peruntukan as $pr) {
        if (in_array($pr, array_keys($array_jenjang))) {
          $peruntukan_array[] = '<span class="badge bg-green">'.$array_jenjang[$pr].'</span>';
        }
      }
      $ResultData[] = implode("<br>", $peruntukan_array);
    } else {
      $ResultData[] = '';
    }
    $ResultData[] = $value->kode_bank;

    $peruntukan_array = array();

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>