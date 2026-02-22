<?php
error_reporting(0);
session_start();
include "../../inc/config.php";


$columns = array(
    'concat(tb_data_cuti_mahasiswa.nim," ",nama)',
    'date_created',
    '(select group_concat(periode) from tb_data_cuti_mahasiswa_periode where id_cuti=tb_data_cuti_mahasiswa.id_cuti)',
    'status_acc',
    'date_approved',
    'no_surat',
    'view_simple_mhs_data.jurusan',
    'tb_data_cuti_mahasiswa.id_cuti',
  );
 
 $Newtable->setDisableSearchColumn("(select group_concat(periode) from tb_data_cuti_mahasiswa_periode where id_cuti=tb_data_cuti_mahasiswa.id_cuti)");

  //set numbering is true
  $Newtable->setNumberingStatus(1);

  //set order by column
  //$Newtable->set_order_by("tb_data_cuti_mahasiswa.id_cuti");

  //set order by type
  //$Newtable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by tb_data_cuti_mahasiswa.id_cuti";

$akses_prodi = get_akses_prodi();
$akses_jur = $db->fetch_custom_single("select group_concat(kode_jur) as kode_jur from view_prodi_jenjang $akses_prodi");
if ($akses_jur) {
  $jur_filter = "and view_simple_mhs_data.jur_kode in(".$akses_jur->kode_jur.")";
} else {
//jika tidak group tidak punya akses prodi, set in 0
  $jur_filter = "and view_simple_mhs_data.jur_kode in(0)";
}

$sem_filter = "";
$disetujui = "";
$angkatan_filter = "";

  if (isset($_POST['jur_filter'])) {

  if ($_POST['jur_filter']!='all') {
    $jur_filter = ' and view_simple_mhs_data.jur_kode="'.$_POST['jur_filter'].'"';
  }

  if ($_POST['sem_filter']!='all') {
    $sem_filter = ' and (select group_concat(periode) from tb_data_cuti_mahasiswa_periode where id_cuti=tb_data_cuti_mahasiswa.id_cuti) like "%'.$_POST['sem_filter'].'%"';
  }

  if ($_POST['angkatan_filter']!='all') {
    $angkatan_filter = ' and mulai_smt="'.$_POST['angkatan_filter'].'"';
  }

  if ($_POST['disetujui']!='all') {
      $disetujui = "and status_acc='".$_POST['disetujui']."'";

  }



}

$Newtable->main_table = "from tb_data_cuti_mahasiswa inner join view_simple_mhs_data on tb_data_cuti_mahasiswa.nim=view_simple_mhs_data.nim where tb_data_cuti_mahasiswa.id_cuti is not null $jur_filter $sem_filter $angkatan_filter $disetujui";

  $query = $Newtable->execQuery("select concat(tb_data_cuti_mahasiswa.nim,' ',nama) as nim_nama,no_surat,date_approved,keterangan,status_acc,tb_data_cuti_mahasiswa.nim,view_simple_mhs_data.nama,view_simple_mhs_data.mulai_smt,view_simple_mhs_data.jk,view_simple_mhs_data.jurusan,tb_data_cuti_mahasiswa.id_cuti,date_created,
  (select group_concat(periode) from tb_data_cuti_mahasiswa_periode where id_cuti=tb_data_cuti_mahasiswa.id_cuti) as periode from tb_data_cuti_mahasiswa inner join view_simple_mhs_data on tb_data_cuti_mahasiswa.nim=view_simple_mhs_data.nim where tb_data_cuti_mahasiswa.id_cuti is not null $jur_filter $sem_filter $angkatan_filter $disetujui",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  $periode_ajuan = "";
  foreach ($query as $value) {
    $periodes = explode(",", $value->periode);
    //array data
    $ResultData = array();
     $ResultData[] = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"> <input type="checkbox" class="group-checkable check-selected"> <span></span></label>';
    $ResultData[] = $value->nim_nama;
    $ResultData[] = tgl_indo($value->date_created);
    foreach ($periodes as $per) {
       $periode_ajuan .=ganjil_genap($per)."<br>";
    }
    $ResultData[] = $periode_ajuan;
    if ($value->status_acc=='approved') {
       $ResultData[] = '<span class="btn btn-xs btn-success btn-xs" data-toggle="tooltip" data-title="Cuti Sudah Disetujui"><i class="fa fa-check"></i> Sudah Acc</span>';
    } elseif ($value->status_acc=='waiting') {
       $ResultData[] = '<span class="btn btn-xs btn-warning btn-xs" data-toggle="tooltip" data-title="Menunggu Persetujuan"><i class="fa fa-hourglass-start"></i> Belum Acc</span>';
    } else {
      $ResultData[] = '<span class="btn btn-xs btn-danger btn-xs" data-toggle="tooltip" data-title="Cuti Ditolak"><i class="fa fa-times-circle"></i> Ditolak</span>';
    }
    $ResultData[] = tgl_indo($value->date_approved);
    $ResultData[] = $value->no_surat;
    $ResultData[] = $value->jurusan;
    

    $ResultData[] = '<span class="btn btn-sm btn-success edit_data data_selected_id" data-toggle="tooltip" data-title="Edit" data-id="'.$value->id_cuti.'"><i class="fa fa-pencil"></i></span> <button data-id="'.$value->id_cuti.'" data-uri="'.base_admin().'modul/cuti_kuliah/cuti_kuliah_action.php" class="btn btn-danger hapus_dtb_notif btn-sm" data-toggle="tooltip" title="Hapus" data-variable="dtb_cuti_kuliah"><i class="fa fa-trash"></i></button>';
    $data[] = $ResultData;
    $i++;
    $periode_ajuan = "";
  }

$Newtable->setData($data);
//create our json
$Newtable->createData();

?>