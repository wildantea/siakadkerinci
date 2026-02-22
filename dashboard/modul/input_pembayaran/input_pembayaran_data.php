<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'keu_tagihan_mahasiswa.nim',
    'mahasiswa.nama',
    'keu_tagihan_mahasiswa.id_tagihan_prodi',
    'keu_tagihan.nominal_tagihan',
    'potongan',
    'keu_tagihan.nominal_tagihan-potongan',
    'keu_tagihan_mahasiswa.is_aktif',
    'keu_tagihan_mahasiswa.periode',
    'tanggal_awal',
    'tanggal_akhir',
    'view_prodi_jenjang.jurusan',
  );

  //if you want to exclude column for searching, put columns name in array
  $datatable2->setDisableSearchColumn(
    'keu_tagihan_mahasiswa.id_tagihan_prodi',
    'keu_tagihan.nominal_tagihan',
    'potongan',
    'keu_tagihan.nominal_tagihan-potongan',
    'keu_tagihan_mahasiswa.periode',
/*    'tanggal_awal',
    'tanggal_akhir',*/
    'view_prodi_jenjang.jurusan',
    'keu_tagihan_mahasiswa.id'
  );
  
  //set numbering is true
  $datatable2->setNumberingStatus(1);

  //set order by column
  //$datatable2->set_order_by("keu_tagihan_mahasiswa.id");

  //set order by type
  //$datatable2->set_order_type("desc");

$kode_prodi = aksesProdi('keu_tagihan.kode_prodi');
//echo "$kode_prodi";
//default semester aktif
$semester_aktif = $db->fetch_single_row("semester_ref","aktif",1);
$periode = "and periode='".$semester_aktif->id_semester."'";
$periode = "";
$kode_tagihan = "";
$kode_pembayaran = "";
$fakultas = "";
$mulai_smt = "";
$status = "";
  if (isset($_POST['kode_prodi'])) {

  if ($_POST['kode_prodi']!='all') {
    $kode_prodi = ' and keu_tagihan.kode_prodi="'.$_POST['kode_prodi'].'"';
  }

  if ($_POST['periode']!='all') {
    $periode = ' and periode="'.$_POST['periode'].'"';
  }
  if ($_POST['mulai_smt']!='all') {
    $mulai_smt = ' and mulai_smt="'.$_POST['mulai_smt'].'"';
  }
  
  
  if ($_POST['kode_tagihan']!='all') {
    $kode_tagihan = ' and keu_tagihan.kode_tagihan="'.$_POST['kode_tagihan'].'"';
  }
  if ($_POST['fakultas']!='all') {
    $fakultas = getProdiFakultas('mahasiswa.jur_kode',$_POST['fakultas']);
  }

  //status tagihan
  $status_tagihan = $_POST['status_tagihan'];
  if ($status_tagihan!='all') {
    if ($status_tagihan=='1') {
      $status = "and keu_tagihan_mahasiswa.is_aktif='1'";
    } elseif($status_tagihan=='2') {
       $status = "and keu_tagihan_mahasiswa.is_aktif='0'";
    } elseif($status_tagihan=='3') {
       $status = "and (select sum(nominal_bayar) from keu_bayar_mahasiswa where id_keu_tagihan_mhs=keu_tagihan_mahasiswa.id)=keu_tagihan.nominal_tagihan-potongan";
    } elseif($status_tagihan=='4') {
       $status = "and keu_tagihan.nominal_tagihan=potongan";
    } elseif($status_tagihan=='5') {
       $status = "and (select sum(nominal_bayar) from keu_bayar_mahasiswa where id_keu_tagihan_mhs=keu_tagihan_mahasiswa.id) is null and keu_tagihan.nominal_tagihan!=potongan";
    }
  }

}



  //set group by column
  //$new_table->group_by = "group by keu_tagihan_mahasiswa.id";
$datatable2->setDebug(1);
$datatable2->setFromQuery("keu_tagihan_mahasiswa
 inner join mahasiswa on keu_tagihan_mahasiswa.nim=mahasiswa.nim
 inner join view_prodi_jenjang on mahasiswa.jur_kode=view_prodi_jenjang.kode_jur
 inner join keu_tagihan on keu_tagihan_mahasiswa.id_tagihan_prodi=keu_tagihan.id
 inner join keu_jenis_tagihan on keu_tagihan.kode_tagihan=keu_jenis_tagihan.kode_tagihan
 inner join keu_jenis_pembayaran kjp on keu_jenis_tagihan.kode_pembayaran=kjp.kode_pembayaran
where keu_tagihan_mahasiswa.id is not null $kode_prodi $fakultas $mulai_smt $periode $kode_pembayaran $kode_tagihan $status");
  $query = $datatable2->execQuery("select keu_tagihan_mahasiswa.nim,mahasiswa.nama,view_prodi_jenjang.jurusan,keu_tagihan_mahasiswa.is_aktif,tanggal_awal,tanggal_akhir,keu_tagihan_mahasiswa.periode,mulai_smt,
keu_jenis_tagihan.nama_tagihan,keu_tagihan.nominal_tagihan,potongan,keu_tagihan.nominal_tagihan-potongan as total_akhir,
(select sum(nominal_bayar) from keu_bayar_mahasiswa where id_keu_tagihan_mhs=keu_tagihan_mahasiswa.id ) as is_lunas,
keu_tagihan_mahasiswa.id from keu_tagihan_mahasiswa
 inner join mahasiswa on keu_tagihan_mahasiswa.nim=mahasiswa.nim
 inner join view_prodi_jenjang on mahasiswa.jur_kode=view_prodi_jenjang.kode_jur
 inner join keu_tagihan on keu_tagihan_mahasiswa.id_tagihan_prodi=keu_tagihan.id
 inner join keu_jenis_tagihan on keu_tagihan.kode_tagihan=keu_jenis_tagihan.kode_tagihan
 inner join keu_jenis_pembayaran kjp on keu_jenis_tagihan.kode_pembayaran=kjp.kode_pembayaran
where keu_tagihan_mahasiswa.id is not null $kode_prodi $fakultas $mulai_smt $periode $kode_pembayaran $kode_tagihan $status",$columns);
//   echo "select keu_tagihan_mahasiswa.nim,mahasiswa.nama,view_prodi_jenjang.jurusan,keu_tagihan_mahasiswa.is_aktif,tanggal_awal,tanggal_akhir,keu_tagihan_mahasiswa.periode,mulai_smt,
// keu_jenis_tagihan.nama_tagihan,keu_tagihan.nominal_tagihan,potongan,keu_tagihan.nominal_tagihan-potongan as total_akhir,
// (select sum(nominal_bayar) from keu_bayar_mahasiswa where id_keu_tagihan_mhs=keu_tagihan_mahasiswa.id ) as is_lunas,
// keu_tagihan_mahasiswa.id from keu_tagihan_mahasiswa
//  inner join mahasiswa on keu_tagihan_mahasiswa.nim=mahasiswa.nim
//  inner join view_prodi_jenjang on mahasiswa.jur_kode=view_prodi_jenjang.kode_jur
//  inner join keu_tagihan on keu_tagihan_mahasiswa.id_tagihan_prodi=keu_tagihan.id
//  inner join keu_jenis_tagihan on keu_tagihan.kode_tagihan=keu_jenis_tagihan.kode_tagihan
//  inner join keu_jenis_pembayaran kjp on keu_jenis_tagihan.kode_pembayaran=kjp.kode_pembayaran
// where keu_tagihan_mahasiswa.id is not null $kode_prodi $fakultas $mulai_smt $periode $kode_pembayaran $kode_tagihan $status";

  //buat inisialisasi array data
  $data = array();

  $i=1;
  $login_as = "";
  $array_allowed_login_as = array(
    'admin'
    //,'keuangan','rektorat','admin_akademik','tipd'
  );
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"> <input type="checkbox" class="group-checkable check-selected data_selected_id" data-id="'.$value->id.'"> <span></span></label>';
  
    $ResultData[] = $value->nim;
    $ResultData[] = $value->nama;
    $ResultData[] = getAngkatan($value->mulai_smt);
    $ResultData[] = $value->nama_tagihan;
    $ResultData[] = rupiah($value->nominal_tagihan);
    $ResultData[] = rupiah($value->potongan);
    $ResultData[] = rupiah($value->total_akhir);
    
    if ($value->is_lunas!="") {
      if ($value->is_lunas<$value->total_akhir) {
      $is_lunas = '<span class="btn btn-warning btn-xs" data-toggle="tooltip" data-title="Belum Lunas"><i class="fa fa-check" ></i> Belum</span>';
      } else {
      $is_lunas = '<span class="btn btn-success btn-xs" data-toggle="tooltip" data-title="Sudah Lunas"><i class="fa fa-check"></i> Lunas</span>';
      }

    } else {
      if ($value->total_akhir==0) {
        $is_lunas = '<span class="btn btn-info btn-xs" data-toggle="tooltip" data-title="Bebas bayar Karena Total 0"><i class="fa fa-check"></i> Bebas</span>';
      } else {
        $is_lunas = '<span class="btn btn-danger btn-xs" data-toggle="tooltip" data-title="Belum Bayar"><i class="fa fa-close"></i> Belum</span>';
      }
      
    }
    $ResultData[] = $is_lunas;
    $ResultData[] = $value->periode;
    $ResultData[] = tgl_indo($value->tanggal_awal);
    $ResultData[] = tgl_indo($value->tanggal_akhir);
    if (strtotime(date('Y-m-d H:i:s')) >= strtotime($value->tanggal_awal) && strtotime(date('Y-m-d H:i:s')) <= strtotime($value->tanggal_akhir)) {
      $ResultData[] = '<span class="btn btn-success btn-xs" data-toggle="tooltip" data-title="Tagihan ini Aktif"><i class="fa fa-check"></i> Aktif</span> '.$login_as;
    } else {
       $ResultData[] = '<span class="btn btn-danger btn-xs" data-toggle="tooltip" data-title="Tagihan ini Sudah Expired"><i class="fa fa-close"></i> Tidak</span> '.$login_as;
    }
    $ResultData[] = $value->jurusan;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable2->setData($data);
//create our json
$datatable2->createData();

?>