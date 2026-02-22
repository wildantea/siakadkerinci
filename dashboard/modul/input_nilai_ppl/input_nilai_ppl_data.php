<?php
session_start();
include "../../inc/config.php";


$columns = array(
    'v_dpl.priode',
    'v_dpl.nama_lokasi',
    '(select count(id_kkn) from ppl where id_priode=v_dpl.id_priode and id_lokasi=v_dpl.id_lokasi)',
    '(select count(ppl.id_kkn) from krs_detail inner join ppl  on krs_detail.nim=ppl.nim and kode_mk in (select id_matkul from v_matkul_ppl) and id_priode=v_dpl.id_priode and id_lokasi=v_dpl.id_lokasi and krs_detail.nilai_huruf is not null)',
    '(select count(ppl.id_kkn) from krs_detail inner join ppl  on krs_detail.nim=ppl.nim and kode_mk in (select id_matkul from v_matkul_ppl) and id_priode=v_dpl.id_priode and id_lokasi=v_dpl.id_lokasi and krs_detail.nilai_huruf is null)',
    "CONCAT(IFNULL(nm_dpl1, ''), ' ', IFNULL(nm_dpl2, ''))",
    'v_dpl.id_lokasi',
  );
  //if you want to exclude column for searching, put columns name in array
  $datatable2->setDisableSearchColumn(
 'v_dpl.priode',
   'v_dpl.id_lokasi',
    '(select count(id_kkn) from ppl where id_priode=v_dpl.id_priode and id_lokasi=v_dpl.id_lokasi)',
    '(select count(ppl.id_kkn) from krs_detail inner join ppl  on krs_detail.nim=ppl.nim and kode_mk in (select id_matkul from v_matkul_ppl) and id_priode=v_dpl.id_priode and id_lokasi=v_dpl.id_lokasi and krs_detail.nilai_huruf is not null)',
    '(select count(ppl.id_kkn) from krs_detail inner join ppl  on krs_detail.nim=ppl.nim and kode_mk in (select id_matkul from v_matkul_ppl) and id_priode=v_dpl.id_priode and id_lokasi=v_dpl.id_lokasi and krs_detail.nilai_huruf is null)'
  );
  
  //set numbering is true
  $datatable2->setNumberingStatus(1);

  //set order by column
  $datatable2->setOrderBy("v_dpl.priode desc");


  $fakultas="";
  $prodi="";
  $priode="";
  $lokasi ="";

//get default akses prodi 
/*if ($_SESSION['group_level']!='dosen') {
  //echo "string";
  $akses_prodi = get_akses_prodi();
  $akses_jur = $db->fetch_custom_single("select group_concat(kode_jur) as kode_jur from view_prodi_jenjang $akses_prodi");
  if ($akses_jur) {
    $prodi = "and jurusan.kode_jur in(".$akses_jur->kode_jur.")";
  } else {
  //jika tidak group tidak punya akses prodi, set in 0
    $prodi = "and jurusan.kode_jur in(0)";
  }
}*/

if (isset($_POST['periode'])) {

  if ($_POST['fakultas']!='all') {
     $fak = $db->fetch_custom_single("select GROUP_CONCAT(CONCAT(\"'\", kode_jur, \"'\")) as jur from jurusan where fak_kode='".$_POST['fakultas']."'");
     $fakultas = ' and id_lokasi in(select id_lokasi from kuota_jurusan_ppl where kode_jur in('.$fak->jur.'))';
    
  }

  if ($_POST['prodi']!='all') {
    $prodi = ' and id_lokasi in(select id_lokasi from kuota_jurusan_ppl where kode_jur="'.$_POST['prodi'].')';
  }

  if ($_POST['periode']!='all') {
    $periode = ' and id_priode="'.$_POST['periode'].'"';
  }
  if ($_POST['lokasi']!='all') {
    $lokasi = ' and id_lokasi="'.$_POST['lokasi'].'"';
  }

}

 $wh = ""; 
  if ($_SESSION['group_level']=='dosen') { 
    $wh = " where nip='".$_SESSION['username']."' or nip2='".$_SESSION['username']."'   ";
  } else {
    $wh = " where 1=1 $fakultas $prodi $periode $lokasi";
  }



  //set group by column
  //$new_table->group_by = "group by keu_tagihan_mahasiswa.id";
$datatable2->setDebug(1);
$datatable2->setFromQuery("v_dpl_ppl v_dpl $wh");
  $query = $datatable2->execQuery("select tgl_awal_input_nilai,tgl_akhir_input_nilai,v_dpl.priode,v_dpl.nama_periode,v_dpl.nama_lokasi,v_dpl.nm_dpl1,v_dpl.nm_dpl2,v_dpl.id_lokasi,
    (select count(id_kkn) from ppl where id_priode=v_dpl.id_priode and id_lokasi=v_dpl.id_lokasi) as jml_peserta,

(select count(ppl.id_kkn) from krs_detail inner join ppl  on krs_detail.nim=ppl.nim and kode_mk in (select id_matkul from v_matkul_ppl) and ppl.id_priode=v_dpl.id_priode and ppl.id_lokasi=v_dpl.id_lokasi and krs_detail.nilai_huruf is not null) as jml_dinilai, 
(select count(ppl.id_kkn) from krs_detail inner join ppl  on krs_detail.nim=ppl.nim and kode_mk in (select id_matkul from v_matkul_ppl) and ppl.id_priode=v_dpl.id_priode and ppl.id_lokasi=v_dpl.id_lokasi and krs_detail.nilai_huruf is null) as jml_belum_dinilai

   from v_dpl_ppl v_dpl $wh",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  $login_as = "";
  foreach ($query as $value) {
    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
    $ResultData[] = ganjil_genap($value->priode);
    $ResultData[] = $value->nama_lokasi;
/*    if ($value->jml_peserta!=($value->jml_dinilai+$value->jml_belum_dinilai)) {
      $ResultData[] = $value->jml_peserta.' ='.($value->jml_dinilai+$value->jml_belum_dinilai).'lok'.$value->id_lokasi;
    } else {
      $ResultData[] = $value->jml_peserta;
    }*/

    $ResultData[] = ($value->jml_dinilai+$value->jml_belum_dinilai);
    
    if ($value->jml_dinilai>0 && $value->jml_belum_dinilai==0) {
           $ResultData[] = '<span class="btn btn-xs btn-success">'.$value->jml_dinilai.'</span>';
        
    } else {
        $ResultData[] = $value->jml_dinilai;
    }
    if ($value->jml_belum_dinilai>0) {
        $ResultData[] = '<span class="btn btn-xs btn-danger">'.$value->jml_belum_dinilai.'</span>';
    } else {
        $ResultData[] = $value->jml_belum_dinilai;
    }
    
    $ResultData[] = "1. $value->nm_dpl1 <br>2. $value->nm_dpl2";
    if (date('Y-m-d') >= $value->tgl_awal_input_nilai && date('Y-m-d') <= $value->tgl_akhir_input_nilai) {
       $ResultData[] =  '<a href="'.base_index_new().'input-nilai-ppl/input_nilai/'.en($value->id_lokasi).'" class="btn btn-primary btn-sm edit_data" data-toggle="tooltip" title="Edit"><i class="fa fa-book"></i> Input Nilai</a> <a target="_BLANK" href="'.base_url().'dashboard/modul/input_nilai_ppl/cetak.php?id='.en($value->id_lokasi).'" class="btn edit_data btn-success "><i class="fa fa-print"></i> Cetak</a>';
    } else {
       $ResultData[] = '<span class="btn btn-xs btn-danger"><i class="icon fa fa-warning"></i> Waktu Input Nilai Berakhir '.tgl_indo($value->tgl_akhir_input_nilai).'</span> <a target="_BLANK" href="'.base_url().'dashboard/modul/input_nilai_ppl/cetak.php?id='.en($value->id_lokasi).'" class="btn edit_data btn-success "><i class="fa fa-print"></i> Cetak</a>';
    }
  

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable2->setData($data);
//create our json
$datatable2->createData();

?>