<?php
include "../../inc/config.php";

$columns = array(
    'semester_survey.id_semester',
    'semester_survey.periode_awal_mulai',
    'semester_survey.periode_awal_selesai',
    'semester_survey.periode_tengah_mulai',
    'semester_survey.periode_tengah_selesai',
    'semester_survey.periode_akhir_mulai',
    'semester_survey.periode_akhir_selesai',
     'periode_lainya_mulai',
    'periode_lainya_selesai',
    'semester_survey.id_sem_survey',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('periode_akhir_selesai','semester_survey.id_sem_survey');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("semester_survey.id_semester");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by semester_survey.id_sem_survey";

  $query = $datatable->get_custom("select semester_survey.id_semester,semester_survey.periode_awal_mulai,semester_survey.periode_awal_selesai,semester_survey.periode_tengah_mulai,semester_survey.periode_tengah_selesai,semester_survey.periode_akhir_mulai,semester_survey.periode_akhir_selesai,
 periode_lainya_mulai,periode_lainya_selesai,
    semester_survey.id_sem_survey from semester_survey",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->id_semester;
    $ResultData[] = tgl_indo($value->periode_awal_mulai);
    $ResultData[] = tgl_indo($value->periode_awal_selesai);

if (strtotime(date('Y-m-d')) >= strtotime($value->periode_awal_mulai) && 
    strtotime(date('Y-m-d')) <= strtotime($value->periode_awal_selesai)) {
    $ResultData[] = '<span class="btn btn-success btn-xs" data-toggle="tooltip" data-title="Tagihan ini Aktif"><i class="fa fa-check"></i> Aktif</span> ';
} else {
    $ResultData[] = '<span class="btn btn-danger btn-xs" data-toggle="tooltip" data-title="Tagihan ini Sudah Expired"><i class="fa fa-close"></i> Tidak</span> ';
}

    $ResultData[] = tgl_indo($value->periode_tengah_mulai);
    $ResultData[] = tgl_indo($value->periode_tengah_selesai);
if (strtotime(date('Y-m-d')) >= strtotime($value->periode_tengah_mulai) && 
    strtotime(date('Y-m-d')) <= strtotime($value->periode_tengah_selesai)) {
    $ResultData[] = '<span class="btn btn-success btn-xs" data-toggle="tooltip" data-title="Tagihan ini Aktif"><i class="fa fa-check"></i> Aktif</span> ';
} else {
    $ResultData[] = '<span class="btn btn-danger btn-xs" data-toggle="tooltip" data-title="Tagihan ini Sudah Expired"><i class="fa fa-close"></i> Tidak</span> ';
}

    $ResultData[] = tgl_indo($value->periode_akhir_mulai);
    $ResultData[] = tgl_indo($value->periode_akhir_selesai);
    if (strtotime(date('Y-m-d')) >= strtotime($value->periode_akhir_mulai) && 
        strtotime(date('Y-m-d')) <= strtotime($value->periode_akhir_selesai)) {
        $ResultData[] = '<span class="btn btn-success btn-xs" data-toggle="tooltip" data-title="Tagihan ini Aktif"><i class="fa fa-check"></i> Aktif</span> ';
    } else {
        $ResultData[] = '<span class="btn btn-danger btn-xs" data-toggle="tooltip" data-title="Tagihan ini Sudah Expired"><i class="fa fa-close"></i> Tidak</span> ';
    }

    $ResultData[] = tgl_indo($value->periode_lainya_mulai);
    $ResultData[] = tgl_indo($value->periode_lainya_selesai);
    if (strtotime(date('Y-m-d')) >= strtotime($value->periode_lainya_mulai) && 
        strtotime(date('Y-m-d')) <= strtotime($value->periode_lainya_selesai)) {
        $ResultData[] = '<span class="btn btn-success btn-xs" data-toggle="tooltip" data-title="Tagihan ini Aktif"><i class="fa fa-check"></i> Aktif</span> ';
    } else {
        $ResultData[] = '<span class="btn btn-danger btn-xs" data-toggle="tooltip" data-title="Tagihan ini Sudah Expired"><i class="fa fa-close"></i> Tidak</span> ';
    }

    $ResultData[] = $value->id_sem_survey;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>