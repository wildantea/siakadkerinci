<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'keu_tagihan_mahasiswa.nim',
    'mahasiswa.nama',
    'view_semester.angkatan',
    'view_prodi_jenjang.jurusan',
    'keu_jenis_tagihan.nama_tagihan',
    'keu_tagihan.nominal_tagihan',
    'keu_tagihan_mahasiswa.id',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('periode','keu_tagihan_mahasiswa.id');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("keu_tagihan_mahasiswa.id");

  //set order by type
  $datatable->set_order_type("desc");

$kode_prodi = "";
//get default akses prodi 
$akses_prodi = get_akses_prodi();
$akses_jur = $db->fetch_custom_single("select group_concat(kode_jur) as kode_jur from view_prodi_jenjang $akses_prodi");
if ($akses_jur) {
  $kode_prodi = "and keu_tagihan.kode_prodi in(".$akses_jur->kode_jur.")";
} else {
  //jika tidak group tidak punya akses prodi, set in 0
  $kode_prodi = "and keu_tagihan.kode_prodi in(0)";
}
//default semester aktif
$semester_aktif = $db->fetch_single_row("semester_ref","aktif",1);
$periode = "and periode='".$semester_aktif->id_semester."'";
$kode_tagihan = "";
$kode_pembayaran = "";
$not = "";
  if (isset($_POST['kode_prodi'])) {

  if ($_POST['kode_prodi']!='all') {
    $kode_prodi = ' and keu_tagihan.kode_prodi="'.$_POST['kode_prodi'].'"';
  }

  if ($_POST['periode']!='all') {
    $periode = ' and periode="'.$_POST['periode'].'"';
  }

    if ($_POST['kode_pembayaran']!='all') {
    $kode_pembayaran = ' and kjp.kode_pembayaran="'.$_POST['kode_pembayaran'].'"';
  }
      if ($_POST['kode_tagihan']!='all') {
    $kode_tagihan = ' and keu_tagihan.kode_tagihan="'.$_POST['kode_tagihan'].'"';
  }

$created_by = $_SESSION['username'];
$create = $db->query("insert into keu_bayar_mahasiswa (id_keu_tagihan_mhs,tgl_bayar,tgl_validasi,created_by,nominal_bayar,no_kwitansi
,id_bank) select keu_tagihan_mahasiswa.id,NOW(),NOW(),'$created_by',keu_tagihan.nominal_tagihan,
FLOOR(RAND() * 401) + 203143,'".$_POST['kode_bank']."'
 from keu_tagihan_mahasiswa
 inner join mahasiswa on keu_tagihan_mahasiswa.nim=mahasiswa.nim
 inner join view_prodi_jenjang on mahasiswa.jur_kode=view_prodi_jenjang.kode_jur 
 inner join view_semester on mahasiswa.mulai_smt=view_semester.id_semester 
 inner join keu_tagihan on keu_tagihan_mahasiswa.id_tagihan_prodi=keu_tagihan.id
 inner join keu_jenis_tagihan on keu_tagihan.kode_tagihan=keu_jenis_tagihan.kode_tagihan
 inner join keu_jenis_pembayaran kjp on keu_jenis_tagihan.kode_pembayaran=kjp.kode_pembayaran
 inner join view_semester vs on keu_tagihan_mahasiswa.periode=vs.id_semester
 left join keu_bayar_mahasiswa kb on keu_tagihan_mahasiswa.id=kb.id_keu_tagihan_mhs
  where kb.id is null $kode_prodi $periode $kode_pembayaran $kode_tagihan ");

  $not = " not "; 
}

  //set group by column
  //$new_table->group_by = "group by keu_tagihan_mahasiswa.id";

  $query = $datatable->get_custom("select keu_tagihan_mahasiswa.nim,mahasiswa.nama,vs.tahun_akademik,view_prodi_jenjang.jurusan,
keu_jenis_tagihan.nama_tagihan,keu_tagihan.nominal_tagihan,keu_tagihan_mahasiswa.id,kb.id as status
 from keu_tagihan_mahasiswa
 inner join mahasiswa on keu_tagihan_mahasiswa.nim=mahasiswa.nim
 inner join view_prodi_jenjang on mahasiswa.jur_kode=view_prodi_jenjang.kode_jur 
 inner join view_semester on mahasiswa.mulai_smt=view_semester.id_semester 
 inner join keu_tagihan on keu_tagihan_mahasiswa.id_tagihan_prodi=keu_tagihan.id
 inner join keu_jenis_tagihan on keu_tagihan.kode_tagihan=keu_jenis_tagihan.kode_tagihan
 inner join keu_jenis_pembayaran kjp on keu_jenis_tagihan.kode_pembayaran=kjp.kode_pembayaran
 inner join view_semester vs on keu_tagihan_mahasiswa.periode=vs.id_semester
 left join keu_bayar_mahasiswa kb on keu_tagihan_mahasiswa.id=kb.id_keu_tagihan_mhs
  where kb.id is $not null $kode_prodi $periode $kode_pembayaran $kode_tagihan ",$columns);


  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->nim;
    $ResultData[] = $value->nama;
    $ResultData[] = $value->nama_tagihan;
    $ResultData[] = 'Rp. '.rupiah($value->nominal_tagihan);
     $ResultData[] = $value->tahun_akademik;
     $ResultData[] = $value->jurusan;
     if ($not=='') {
       $ResultData[] = '<span class="bt btn-xs btn-danger">Belum</span>';
     }else{
       $ResultData[] = '<span class="bt btn-xs btn-success">Sudah</span>';
     }
    


    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>