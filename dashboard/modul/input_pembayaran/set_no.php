<?php
include "../../inc/config.php";
//9871
$data = $db->query("select keu_bayar_mahasiswa.*,sum(nominal_bayar) as jml_bayar,sum(keu_tagihan.nominal_tagihan) as jml_tagihan,nim from keu_bayar_mahasiswa 
	inner join keu_tagihan_mahasiswa on id_keu_tagihan_mhs=keu_tagihan_mahasiswa.id
	inner join keu_tagihan on id_tagihan_prodi=keu_tagihan.id
	where id_kwitansi is null group by no_kwitansi");

function getKodeProdi($nim) {
	global $db;
	return $db->fetch_single_row('mahasiswa','nim',$nim)->jur_kode;
}

function dateTonumber($date) {
	$result = str_replace(" ", "", $date);
	$result = str_replace("-", "", $result);
	$result = str_replace(":", "", $result);
	return $result;
}
$no = 1;
echo "<pre>";
foreach ($data as $dt) {
	echo $no.". ".$dt->id." ".$dt->tgl_validasi." ".dateTonumber($dt->tgl_validasi)."<br>";
	$no++;
	$data_result[dateTonumber($dt->tgl_validasi)] = $dt->tgl_validasi;
	//$db->update('keu_bayar_mahasiswa',array('no_kwitansi' => dateTonumber($dt->tgl_validasi)),'id',$dt->id);

	//$nominal_bayar[$dt->no_kwitansi] = 

	$data_quitansi = array(
	              'nominal_bayar' => $dt->jml_bayar,
	              'total_tagihan' => $dt->jml_tagihan,
	              'validator' => $dt->created_by,
	              'metode_bayar' => 2,
	              'id_bank' => $dt->id_bank,
	              'kode_jur' => getKodeProdi($dt->nim),
	              'no_kwitansi' => dateTonumber($dt->tgl_validasi),
	              'urutan_bayar' => 1,
	              'keterangan' => '',
	              'nim_mahasiswa' => $dt->nim,
	              'date_created' => $dt->tgl_validasi,
	              'tgl_bayar' => $dt->tgl_bayar,
	            );
	print_r($data_quitansi);
	$db->insert('keu_kwitansi',$data_quitansi);

}

echo count($data_result);