<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'nm_matkul',
    'nama_kelas',
    'krs_detail.sks',
    'nm_ruang',
    'waktu',
    'fungsi_dosen_kelas(vnk.kelas_id)',
    'fungsi_dosen_kelas(vnk.kelas_id)',
    'id_krs_detail'
  );

  //if you want to exclude column for searching, put columns name in array
 // $datatable->disable_search = array('jml_sks_wajib','count(matkul.id_matkul)','jml_sks_pilihan','sks_total','jml_matkul','kur_id');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("vnk.nm_matkul");

  //set order by type
  $datatable->set_order_type("desc");

$array_where = array(
    'nim' => $_POST['nim'],
    'sem' =>  $_POST['sem'],
  );

$data_query = "select m.kode_mk,m.nama_mk as nm_matkul,vnk.nama_kelas,krs_detail.sks,vj.nm_ruang,
vj.hari,concat(vj.jam_mulai,' s/d ',vj.jam_selesai) as jam,fungsi_dosen_kelas(vnk.kelas_id) as nama_dosen,id_krs_detail
 from view_nama_kelas vnk
left join view_jadwal vj on vnk.kelas_id=vj.kelas_id
left join krs_detail on vnk.kelas_id=krs_detail.id_kelas
left join matkul m on m.id_matkul=krs_detail.kode_mk
where krs_detail.nim=? and krs_detail.id_semester=?";
  $query = $datatable->get_custom($data_query, $columns,$array_where);

$disetujui_default = 1;
$disetujui = $db->fetch_custom_single("select fungsi_get_jatah_sks('".$_POST['nim']."',".$_POST['sem'].") as jatah_sks,fungsi_jml_sks_diambil('".$_POST['nim']."',".$_POST['sem'].") as diambil,fungsi_get_krs_disetujui_mhs('".$_POST['nim']."',".$_POST['sem'].") as status_disetujui");

$disetujui_default = $disetujui->status_disetujui;

    //check semester mahasiswa
    $semester_mhs = $db->fetch_custom_single("select count(akm_id) as semester from akm where mhs_nim=?",array('mhs_nim' => $_POST['nim']));
    $check_paket_semester = $db->fetch_single_row("data_paket_semester","id",1);
    $data_jatah_sks = $db->fetch_custom_single("select fungsi_get_jatah_sks(".$_POST['nim'].",".$_POST['sem'].") as jatah_sks");
    $jatah_sks = $disetujui->jatah_sks;
    $dapat_paket = "";
   if ($check_paket_semester) {
    if ($semester_mhs) {
      //semester paket
      $xpl_semester = explode(",", $check_paket_semester->semester_mhs);
      if (in_array($semester_mhs->semester,$xpl_semester)) {
        $jatah_sks = $check_paket_semester->jml_sks;
      }
    }
   }
 $sks_diambil = sksDiambilMhs($_POST['nim'],$_POST['sem']);
  //buat inisialisasi array data
  $data = array();

  $i=1;
  $jml_sks = 0;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
    $ResultData[] = $value->kode_mk.' '.$value->nm_matkul;
    $ResultData[] = $value->nama_kelas;
    $ResultData[] = $value->sks;
    $ResultData[] = $value->nm_ruang;
    $ResultData[] = ucwords($value->hari);
    $ResultData[] = $value->jam;    
        if ($value->nama_dosen!='') {
        $nama_dosen = array_map('trim', explode('#', $value->nama_dosen));
        $nama_dosen = trim(implode("<br>- ", $nama_dosen));
        $ResultData[] = '- '.$nama_dosen;
    } else {
      $ResultData[] = '';
    }
        if ($_POST['is_aktif_krs']=='yes') {
      $ResultData[] = '<button class="btn btn-danger btn-xs hapus-krs" data-toggle="tooltip" title="Hapus"  data-id="'.$value->id_krs_detail.'"><i class="fa fa-trash"></i></button>';
    } else {
       $ResultData[] = '';   
    }
   


    $data[] = $ResultData;
    $i++;
    $jml_sks+=$value->sks;
  }
$sisa_jatah = 0;
//echo $i;
 if ($disetujui_default<1 && $i>1) {
   $status_irs = 'STATUS IRS <span class="btn btn-info btn-xs">Menunggu Disetujui Dosen Wali</span>';
 } elseif ($disetujui_default>0 && $i>1) {
     $status_irs = 'STATUS IRS <span class="btn btn-success btn-xs"><i class="fa fa-check"></i> Sudah Disetujui Dosen Wali</span>';
 } else {
   $status_irs = "";
 }


//add callback footer jumlah sks
$datatable->set_callback(array('jumlah_sks' => $jml_sks,'status_irs' => $status_irs,'jatah_sks' => $jatah_sks,'sisa_jatah' => ($jatah_sks- $jml_sks )));
//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>