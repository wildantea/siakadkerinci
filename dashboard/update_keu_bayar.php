<?php
include 'inc/config.php';

 
function get_no_kwitansi($kode_jur,$tgl_bayar)
{
	global $db;
	$tgl_bayar = date("Ymd",strtotime($tgl_bayar));
	$q = $db->query("select (count(*) +1 ) as jml from keu_kwitansi where
	 kode_jur='$kode_jur' and date(date_created)='".date("Y-m-d",strtotime($tgl_bayar) )."' ");
	foreach ($q as $k) {
		$ujung = $k->jml;
	}
	if ($ujung<10) {
		$ujung = "00".$ujung;
	}elseif ($ujung>=10 && $ujung<100) {
		$ujung = "0".$ujung;
	}
	$no_kwitansi = $tgl_bayar.$kode_jur.$ujung;
	return $no_kwitansi;
}

$q = $db->query("select c.*,year(tgl_bayar) as tahun, LPAD(day(tgl_bayar),2,'0') as tgl,
LPAD(MONTH(tgl_bayar),2,'0') as bulan,t.nim,m.jur_kode
from keu_cicilan c
join keu_tagihan_mahasiswa t on t.id=c.id_tagihan_mhs
join mahasiswa m on m.nim=t.nim
 where c.id_tagihan_mhs not in
(select b.id_keu_tagihan_mhs from keu_bayar_mahasiswa b 
where b.id_keu_tagihan_mhs=c.id_tagihan_mhs)");  
echo "<pre>";
foreach ($q as $k) {  
//  print_r($k); 
  $no_kwitansi = get_no_kwitansi($k->jur_kode,$k->tgl_bayar);
  $data_kwitansi = array('nominal_bayar' => $k->jml_bayar , 
                         'kode_jur' => $k->jur_kode,
                         'total_tagihan' => $k->jml_bayar,
                         'validator' => $k->validator,
                         'metode_bayar' => '2',
                         'id_bank' => '01',
                         'no_kwitansi' => $no_kwitansi,
                         'urutan_bayar' => '1',
                         'is_deleted' => '0',
                         'date_created' => $k->tgl_bayar );
 // print_r($data_kwitansi);
  $db->insert("keu_kwitansi",$data_kwitansi); 
  $id_kwitansi = $db->last_insert_id();  
  $data_keu_bayar = array('id_keu_tagihan_mhs' => $k->id_tagihan_mhs , 
                          'tgl_bayar' => $k->tgl_bayar,
                          'tgl_validasi' => $k->tgl_bayar,
                          'created_by' => $k->validator,
                          'nominal_bayar' => $k->jml_bayar,
                          'no_kwitansi' => $no_kwitansi,
                          'urutan_bayar_prodi' => '1',
                          'id_bank' => '01',
                          'id_kwitansi' => $id_kwitansi,
                          'is_removed' => '0',
                          'afirmasi' => '0');
  $db->insert("keu_bayar_mahasiswa",$data_keu_bayar);  
  print_r($data_keu_bayar);   
} 
?> 