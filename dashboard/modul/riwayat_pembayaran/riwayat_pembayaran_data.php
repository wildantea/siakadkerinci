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
  $datatable2->setNumberingStatus(1);

  //set order by column
  $datatable2->setOrderBy("keu_tagihan_mahasiswa.periode asc");

  //$datatable2->setGroupBy("kbm.id_kwitansi");
$nim = $_SESSION['username'];


$datatable2->setDebug(1);

$query = $datatable2->setFromQuery("keu_tagihan_mahasiswa  
INNER JOIN 
    keu_tagihan ON keu_tagihan_mahasiswa.id_tagihan_prodi = keu_tagihan.id
INNER JOIN 
    keu_jenis_tagihan ON keu_tagihan.kode_tagihan = keu_jenis_tagihan.kode_tagihan
LEFT JOIN 
    keu_bayar_mahasiswa ON keu_bayar_mahasiswa.id_keu_tagihan_mhs = keu_tagihan_mahasiswa.id
LEFT JOIN 
    keu_bank ON keu_bayar_mahasiswa.id_bank = keu_bank.kode_bank
WHERE 
    nim = $nim
    AND (
        (SELECT SUM(kb.nominal_bayar) 
         FROM keu_bayar_mahasiswa kb 
         WHERE kb.id_keu_tagihan_mhs = keu_tagihan_mahasiswa.id) > 0
        OR 
        nim IN (SELECT nim FROM affirmasi_krs)
    )");
  $query = $datatable2->execQuery("SELECT 
    keu_jenis_tagihan.nama_tagihan,
    keu_tagihan.nominal_tagihan,
    id_kwitansi,
    nama_singkat,
    keu_tagihan_mahasiswa.periode,
    keu_bayar_mahasiswa.nominal_bayar,
    tgl_bayar,
    keu_tagihan_mahasiswa.nim,
    potongan,
    keu_tagihan.nominal_tagihan - potongan -(
        SELECT SUM(kb.nominal_bayar) 
        FROM keu_bayar_mahasiswa kb 
        WHERE kb.id_keu_tagihan_mhs = keu_tagihan_mahasiswa.id
    ) AS sisa,
    ((LEFT(keu_tagihan_mahasiswa.periode, 4) - LEFT(berlaku_angkatan, 4)) * 2) + 
    RIGHT(keu_tagihan_mahasiswa.periode, 1) - 
    (FLOOR(RIGHT(berlaku_angkatan, 1) / 2)) AS smt,
    (SELECT nim 
     FROM affirmasi_krs 
     WHERE nim = keu_tagihan_mahasiswa.nim 
     AND periode = keu_tagihan_mahasiswa.periode 
     LIMIT 1) AS affirmasi
FROM 
    keu_tagihan_mahasiswa  
INNER JOIN 
    keu_tagihan ON keu_tagihan_mahasiswa.id_tagihan_prodi = keu_tagihan.id
INNER JOIN 
    keu_jenis_tagihan ON keu_tagihan.kode_tagihan = keu_jenis_tagihan.kode_tagihan
LEFT JOIN 
    keu_bayar_mahasiswa ON keu_bayar_mahasiswa.id_keu_tagihan_mhs = keu_tagihan_mahasiswa.id
LEFT JOIN 
    keu_bank ON keu_bayar_mahasiswa.id_bank = keu_bank.kode_bank
WHERE 
    nim = $nim
    AND (
        (SELECT SUM(kb.nominal_bayar) 
         FROM keu_bayar_mahasiswa kb 
         WHERE kb.id_keu_tagihan_mhs = keu_tagihan_mahasiswa.id) > 0
        OR 
        nim IN (SELECT nim FROM affirmasi_krs)
    )

",$columns);

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
          if ($value->nominal_bayar!='') {
            $ResultData[] = 'Bayar Cash';
          } else {
            $ResultData[] = '';
          }
          
        }
        $ResultData[] = rupiah($value->nominal_tagihan);
        $ResultData[] = rupiah($value->nominal_bayar);
        $ResultData[] = rupiah($value->potongan);
        $ResultData[] = rupiah($value->sisa);
        $cetak = '<a href="'.base_admin().'modul/riwayat_pembayaran/kuitansi.php?nim='.$enc->enc($value->nim).'&kid='.$enc->enc($value->id_kwitansi).'" target="_blank"  class="btn btn-success btn-sm" data-toggle="tooltip" title="Cetak Kwitansi Pembayaran"><i class="fa fa-print"></i></a>';

        if ($value->affirmasi!='') {
          $status = '<span class="btn btn-info btn-sm">Affirmasi</span>';
          $cetak = '';
        } else {
          if($value->nominal_bayar > 0) {
            $status = '<span class="btn btn-success btn-sm">Lunas</span>';
          } else {
            $status = '<span class="btn btn-warning btn-sm">Belum Bayar</span>';
             $cetak = '';
          }
        }
        $ResultData[] = $status;
        $ResultData[] = $cetak;
        
        $total_bayar += $value->nominal_bayar;
    $data[] = $ResultData;
    $i++;
  }

$datatable2->setCallback(array('total_bayar' => rupiah($total_bayar)));
//set data
$datatable2->setData($data);
//create our json
$datatable2->createData();

?>