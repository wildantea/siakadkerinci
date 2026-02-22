<?php
session_start();
include "../../inc/config.php";


$columns = array(
    'view_krs_single.nim',
    'nama',
    'angkatan',
    'fungsi_cek_pembayaran_periode(view_krs_single.id_semester,vpj.kode_jur,view_krs_single.nim)',
    'disetujui',
    'jurusan',
    'view_krs_single.nim',
  );

  //if you want to exclude column for searching, put columns name in array
  $datatable2->setDisableSearchColumn(
    'angkatan',
    'fungsi_cek_pembayaran_periode(view_krs_single.id_semester,vpj.kode_jur,view_krs_single.nim)',
    'disetujui',
    'jurusan'
    );

  //if you want to exclude column for searching, put columns name in array
  //$datatable->disable_search = array('fungsi_cek_pembayaran_periode(view_krs_single.id_semester,vpj.kode_jur,view_krs_single.nim)');
  
  //set numbering is true
 $datatable2->setNumberingStatus(1);

 $datatable2->setOrderBy("view_krs_single.nim asc");

 

  //set group by column
  //$new_table->group_by = "group by kelas.kelas_id";
$jur_filter = "";
//get default akses prodi 
$akses_prodi = get_akses_prodi();
$akses_jur = $db->fetch_custom_single("select group_concat(kode_jur) as kode_jur from view_prodi_jenjang $akses_prodi");
if ($akses_jur) {
  $jur_filter = "and vpj.kode_jur in(".$akses_jur->kode_jur.")";
} else {
  //jika tidak group tidak punya akses prodi, set in 0
  $jur_filter = "and vpj.kode_jur in(0)";
}
$jur_filter= "";
//default semester aktif
$semester_aktif = $db->fetch_single_row("semester_ref","aktif",1);
$sem_filter = "and view_krs_single.id_semester='".$semester_aktif->id_semester."'";
$is_bayar = "";
$disetuji = "";
$angkatan_filter = "";

  if (isset($_POST['jur_filter'])) {

  if ($_POST['jur_filter']!='all') {
    $jur_filter = ' and vpj.kode_jur="'.$_POST['jur_filter'].'"';
  }

  if ($_POST['sem_filter']!='all') {
    $sem_filter = ' and view_krs_single.id_semester="'.$_POST['sem_filter'].'"';
  }

  if ($_POST['angkatan_filter']!='all') {
    $angkatan_filter = ' and mulai_smt="'.$_POST['angkatan_filter'].'"';
  }

  if ($_POST['disetujui']!='all') {
      $disetuji = "and disetujui='".$_POST['disetujui']."'";

  }



}

$datatable2->setDebug(1);
$datatable2->setFromQuery("view_krs_single
 inner join mahasiswa on view_krs_single.nim=mahasiswa.nim
  inner join view_prodi_jenjang vpj on mahasiswa.jur_kode=vpj.kode_jur
  inner join view_semester on mahasiswa.mulai_smt=view_semester.id_semester
left join (
select ktm.nim,keu_tagihan.nominal_tagihan,nominal_tagihan - ktm.potongan as nominal_akhir_tagihan,nominal_bayar,
(keu_tagihan.nominal_tagihan - potongan) - sum(kbm.nominal_bayar) as sisa_tagihan,
sum(kbm.nominal_bayar),potongan,syarat_krs,periode FROM keu_tagihan_mahasiswa ktm
          JOIN keu_bayar_mahasiswa kbm ON ktm.id=kbm.id_keu_tagihan_mhs
          JOIN keu_tagihan ON ktm.id_tagihan_prodi=keu_tagihan.id
JOIN keu_jenis_tagihan kjt ON keu_tagihan.kode_tagihan=kjt.kode_tagihan
group by ktm.id

having 
#sum(nominal_bayar) <  nominal_tagihan - ktm.potongan 
#and 
 kjt.syarat_krs='Y'
) tagihan on tagihan.nim=view_krs_single.nim and tagihan.periode=view_krs_single.id_semester

where view_krs_single.kelas_id is not null $sem_filter $jur_filter  $angkatan_filter $is_bayar $disetuji and mahasiswa.dosen_pemb='".$_SESSION['username']."'");
  $query = $datatable2->execQuery("select view_krs_single.id_semester,id_krs_detail,angkatan,view_krs_single.nim
,tagihan.sisa_tagihan,tagihan.nominal_akhir_tagihan,
(select id_affirmasi from affirmasi_krs where nim=view_krs_single.nim and periode=view_krs_single.id_semester) as affirmasi,
 view_krs_single.disetujui,
 fungsi_get_jatah_sks(view_krs_single.nim,view_krs_single.id_semester) as jatah,
  fungsi_jml_sks_diambil(view_krs_single.nim,view_krs_single.id_semester) as sks_diambil,
 nama,jurusan from view_krs_single
 inner join mahasiswa on view_krs_single.nim=mahasiswa.nim
  inner join view_prodi_jenjang vpj on mahasiswa.jur_kode=vpj.kode_jur
  inner join view_semester on mahasiswa.mulai_smt=view_semester.id_semester
left join (
select ktm.nim,keu_tagihan.nominal_tagihan,nominal_tagihan - ktm.potongan as nominal_akhir_tagihan,nominal_bayar,
(keu_tagihan.nominal_tagihan - potongan) - sum(kbm.nominal_bayar) as sisa_tagihan,
sum(kbm.nominal_bayar),potongan,syarat_krs,periode FROM keu_tagihan_mahasiswa ktm
          JOIN keu_bayar_mahasiswa kbm ON ktm.id=kbm.id_keu_tagihan_mhs
          JOIN keu_tagihan ON ktm.id_tagihan_prodi=keu_tagihan.id
JOIN keu_jenis_tagihan kjt ON keu_tagihan.kode_tagihan=kjt.kode_tagihan
group by ktm.id

having 
#sum(nominal_bayar) <  nominal_tagihan - ktm.potongan 
#and 
 kjt.syarat_krs='Y'
) tagihan on tagihan.nim=view_krs_single.nim and tagihan.periode=view_krs_single.id_semester

where view_krs_single.kelas_id is not null $sem_filter $jur_filter  $angkatan_filter $is_bayar $disetuji and mahasiswa.dosen_pemb='".$_SESSION['username']."'",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  $dos = array();
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    //$ResultData[] = '<div class="checkbox checkbox-primary"><input class="styled styled-primary check-selected" type="checkbox" id="bulk_check"> <label for="checkbox2">'.$datatable->number($i).'</label></div>';
    $ResultData[] = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"> <input type="checkbox" class="group-checkable check-selected"> <span></span></label>'.$datatable->number($i);
   $ResultData[] = $value->nim;
    $ResultData[] = $value->nama;
    $ResultData[] = $value->angkatan;
    if ($value->sisa_tagihan > 0 && $value->affirmasi=="" && $value->sisa_tagihan < $value->nominal_akhir_tagihan) {
      $ResultData[] = '<span class="btn btn-warning btn-xs" data-title="Baru bayar sebagian" data-toggle="tooltip">Belum Lunas</span>';
    } elseif ($value->affirmasi!="") {
      $ResultData[] = '<span class="btn btn-info btn-xs">Affirmasi</span>';
    } elseif ($value->sisa_tagihan > 0 && $value->affirmasi==""  && $value->sisa_tagihan == $value->nominal_akhir_tagihan) {
      $ResultData[] = '<span class="btn btn-danger btn-xs">Belum Bayar</span>';
    } elseif ($value->sisa_tagihan==0 or $value->sisa_tagihan < 0) {
      $ResultData[] = '<span class="btn btn-success btn-xs">Sudah</span>';
    } else {
      $ResultData[] = 'aneh';
    }
    if ($value->disetujui=='0') {
      $ResultData[] = '<span class="btn btn-danger btn-xs">Belum</span>';
    } else {
       $ResultData[] = '<span class="btn btn-success btn-xs">Sudah</span>';
    }
    $ResultData[] = $value->jatah;
    $ResultData[] = sksDiambilMhs($value->nim,$value->id_semester);
    $ResultData[] = $value->jurusan;
    $ResultData[] = '<a data-id='.$value->id_krs_detail.' href="rencana-studi/detail/?n='.$enc->enc($value->nim).'&s='.$enc->enc($value->id_semester).'" data-toggle="tooltip" title="" class="btn btn-primary btn-xs data_selected_id" data-original-title="Lihat Detail IRS"><i class="fa fa-search"></i></a>';
    $data[] = $ResultData;
     $dos = array();
    $i++;
  }

//set data
$datatable2->setData($data);
//create our json
$datatable2->createData();


?>