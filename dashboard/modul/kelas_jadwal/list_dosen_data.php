<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'nip',
    'dosen',
    'sks',
    'jurusan_dosen'
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('nama_kec','kecamatan.id_kec');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("dosen");

  //set order by type
  $datatable->set_order_type("asc");

  //set group by column
  //$new_table->group_by = "group by kecamatan.id_kec"; sum(sks_tm+sks_prak+sks_prak_lap+sks_sim) as total_

  $query = $datatable->get_custom("SELECT view_dosen.*, IFNULL(subquery.sks, 0) AS sks
FROM view_dosen
LEFT JOIN (
    SELECT dk.id_dosen, IFNULL(SUM(sks_tm+sks_prak+sks_prak_lap+sks_sim), 0) AS sks
    FROM dosen_kelas dk
    JOIN kelas k ON k.kelas_id = dk.id_kelas
    JOIN matkul m ON m.id_matkul = k.id_matkul
    WHERE k.sem_id = '".$_POST['sem_id']."'
    GROUP BY dk.id_dosen
) AS subquery ON subquery.id_dosen = view_dosen.nip where aktif='Y'",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {
     $ResultData = array();
/*    $qd = $db->query("select ifnull(sum(m.bobot_minimal_lulus),0) as sks
            from dosen_kelas dk join kelas k on k.kelas_id=dk.id_kelas
            join matkul m on m.id_matkul=k.id_matkul
            where dk.id_dosen='$value->nip' and k.sem_id='".$_POST['sem_id']."'");
    if ($qd->rowCount()>0) {
       foreach ($qd as $kd) {
          $sks = $kd->sks;
       }
    }else{
       $sks = 0;
    }*/
    //array data
    if ($_SESSION['group_level']!='admin') {
       if ($value->sks<16) {
       $ResultData[] = '<button class="btn btn-success" data-toggle="tooltip" title="Pilih Dosen" onclick="pilih_dosen('.$value->id_dosen.','.$value->sks.')"><i class="fa fa-plus"></i></button>'; 
      }else{
         $ResultData[] = "";
      }
    }else{
       $ResultData[] = '<button class="btn btn-success" data-toggle="tooltip" title="Pilih Dosen" onclick="pilih_dosen('.$value->id_dosen.','.$value->sks.',)"><i class="fa fa-plus"></i></button>'; 
    }
    
    
    $ResultData[] = $value->nip;
    $ResultData[] = $value->dosen;
    $ResultData[] = $value->sks;
    $ResultData[] = $value->jurusan_dosen;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>