<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'keu_tagihan_mahasiswa.periode',
    '((left(keu_tagihan_mahasiswa.periode,4)-left(berlaku_angkatan,4))*2)+right(keu_tagihan_mahasiswa.periode,1)-(floor(right(berlaku_angkatan,1)/2))',
    'keu_jenis_tagihan.nama_tagihan',
    'keu_bayar_mahasiswa.tgl_bayar',
    'keu_bank.nama_bank',
    'keu_bayar_mahasiswa.nominal_bayar',
    'keu_bayar_mahasiswa.id',
  );
  //set numbering is true
  $Newtable->setNumberingStatus(1);

  //set order by column
  $Newtable->setOrderBy("keu_bayar_mahasiswa.id desc");

  //$Newtable->setGroupBy("kbm.id_kwitansi");
$nim = $_SESSION['username'];


$Newtable->setDebug(1);

  $query = $Newtable->execQuery("select keu_tagihan_mahasiswa.periode,keu_jenis_tagihan.nama_tagihan,keu_bayar_mahasiswa.tgl_bayar,keu_bank.nama_bank,keu_bayar_mahasiswa.nominal_bayar,nama_singkat,keu_kwitansi.id_kwitansi,nim,
  ((left(keu_tagihan_mahasiswa.periode,4)-left(berlaku_angkatan,4))*2)+right(keu_tagihan_mahasiswa.periode,1)-(floor(right(berlaku_angkatan,1)/2)) as smt,
  keu_bayar_mahasiswa.id from keu_bayar_mahasiswa inner join keu_kwitansi on keu_bayar_mahasiswa.id_kwitansi=keu_kwitansi.id_kwitansi inner join keu_tagihan_mahasiswa on keu_bayar_mahasiswa.id_keu_tagihan_mhs=keu_tagihan_mahasiswa.id inner join keu_tagihan on keu_tagihan_mahasiswa.id_tagihan_prodi=keu_tagihan.id inner join keu_jenis_tagihan on keu_tagihan.kode_tagihan=keu_jenis_tagihan.kode_tagihan left join keu_bank on keu_kwitansi.id_bank=keu_bank.kode_bank where nim='$nim'",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  $total_bayar = 0;
  foreach ($query as $value) {

        //array data
        $ResultData = array();
        $ResultData[] = $datatable->number($i);
      
        $ResultData[] = getAngkatan($value->periode);
        $ResultData[] = $value->smt;
        $ResultData[] = $value->nama_tagihan;
        $ResultData[] = tgl_indo($value->tgl_bayar);
    
        if ($value->nama_singkat!="") {
          $ResultData[] = $value->nama_singkat;
        } else {
          $ResultData[] = 'Bayar Cash';
        }
        $ResultData[] = rupiah($value->nominal_bayar);
        $ResultData[] = '<a href="'.base_admin().'modul/riwayat_pembayaran/kuitansi.php?nim='.$enc->enc($value->nim).'&kid='.$enc->enc($value->id_kwitansi).'" target="_blank"  class="btn btn-success btn-sm" data-toggle="tooltip" title="Cetak Kwitansi Pembayaran"><i class="fa fa-print"></i></a>';
        $total_bayar += $value->nominal_bayar;
    $data[] = $ResultData;
    $i++;
  }

$Newtable->setCallback(array('total_bayar' => rupiah($total_bayar)));
//set data
$Newtable->setData($data);
//create our json
$Newtable->createData();

?>