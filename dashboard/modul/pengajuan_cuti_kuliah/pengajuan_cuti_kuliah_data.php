<?php
session_start();
include "../../inc/config.php";


$columns = array(
    'tb_data_cuti_mahasiswa.nim',
    'view_simple_mhs_data.nama',
    'view_simple_mhs_data.mulai_smt',
    'view_simple_mhs_data.jk',
    'view_simple_mhs_data.jurusan',
    'tb_data_cuti_mahasiswa.id_cuti',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('date_updated','tb_data_cuti_mahasiswa.id_cuti');
  
  //set numbering is true
  $Newtable->setNumberingStatus(1);

  //set order by column
  //$Newtable->set_order_by("tb_data_cuti_mahasiswa.id_cuti");

  //set order by type
  //$Newtable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by tb_data_cuti_mahasiswa.id_cuti";

  $query = $Newtable->execQuery("select keterangan,status_acc,tb_data_cuti_mahasiswa.nim,view_simple_mhs_data.nama,view_simple_mhs_data.mulai_smt,view_simple_mhs_data.jk,view_simple_mhs_data.jurusan,tb_data_cuti_mahasiswa.id_cuti,date_created,
  (select group_concat(periode) from tb_data_cuti_mahasiswa_periode where id_cuti=tb_data_cuti_mahasiswa.id_cuti) as periode from tb_data_cuti_mahasiswa inner join view_simple_mhs_data on tb_data_cuti_mahasiswa.nim=view_simple_mhs_data.nim where tb_data_cuti_mahasiswa.nim='".$_SESSION['username']."'",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  $periode_ajuan = "";
  foreach ($query as $value) {
    $periodes = explode(",", $value->periode);
    //array data
    $ResultData = array();
    $ResultData[] = $Newtable->number($i);
    $ResultData[] = tgl_indo($value->date_created);
    foreach ($periodes as $per) {
       $periode_ajuan .=ganjil_genap($per)."<br>";
    }
    $ResultData[] = $periode_ajuan;
    if ($value->status_acc=='approved') {
       $ResultData[] = '<span class="btn btn-xs btn-success btn-xs" data-toggle="tooltip" data-title="Cuti Sudah Disetujui" data-id="'.$value->id_pendaftaran.'"><i class="fa fa-check"></i> Sudah Acc</span>';
    } elseif ($value->status_acc=='waiting') {
       $ResultData[] = '<span class="btn btn-xs btn-warning btn-xs" data-toggle="tooltip" data-title="Menunggu Persetujuan"><i class="fa fa-hourglass-start"></i> Belum Acc</span>';
    } else {
      $ResultData[] = '<span class="btn btn-xs btn-danger btn-xs" data-toggle="tooltip" data-title="Cuti Ditolak"><i class="fa fa-times-circle"></i> Ditolak</span>';
    }
    $ResultData[] = $value->keterangan;
    if ($value->status_acc=='waiting') {
      $ResultData[] = '<span class="btn btn-sm btn-success edit_data" data-toggle="tooltip" data-title="Edit" data-id="'.$value->id_cuti.'"><i class="fa fa-pencil"></i></span> <button data-id="'.$value->id_cuti.'" data-uri="'.base_admin().'modul/pengajuan_cuti_kuliah/pengajuan_cuti_kuliah_action.php" class="btn btn-danger hapus_dtb_notif btn-sm" data-toggle="tooltip" title="Hapus" data-variable="dtb_pengajuan_cuti_kuliah"><i class="fa fa-trash"></i></button>';
    } else {
      $ResultData[] = '';
    }

    $data[] = $ResultData;
    $i++;
    $periode_ajuan = "";
  }

$Newtable->setData($data);
//create our json
$Newtable->createData();

?>