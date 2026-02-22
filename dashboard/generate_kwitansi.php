<?php
include "inc/config.php";
/*$datas = $db->query("select nominal_tagihan,potongan,nim,keu_tagihan_mahasiswa.id as id_keu_tagihan_mhs,kode_prodi,
(select sum(nominal_bayar) from keu_bayar_mahasiswa where id_keu_tagihan_mhs=keu_tagihan_mahasiswa.id) as jml_bayar,
(select tgl_bayar from keu_bayar_mahasiswa where id_keu_tagihan_mhs=keu_tagihan_mahasiswa.id limit 1) as tanggal_bayar,
(select created_by from keu_bayar_mahasiswa where id_keu_tagihan_mhs=keu_tagihan_mahasiswa.id limit 1) as validator
 from keu_tagihan_mahasiswa 
inner join keu_tagihan on keu_tagihan.id=id_tagihan_prodi

where periode='20212' and keu_tagihan_mahasiswa.id in(select id_keu_tagihan_mhs from keu_bayar_mahasiswa)

and (select sum(nominal_bayar) from keu_bayar_mahasiswa where id_keu_tagihan_mhs=keu_tagihan_mahasiswa.id) > nominal_tagihan
group by nim");*/

//check minus
/*select keu_bayar_mahasiswa.id,id_keu_tagihan_mhs,nim,nominal_tagihan,count(*),sum(nominal_bayar),tgl_bayar from keu_bayar_mahasiswa 
inner join keu_tagihan_mahasiswa on 
id_keu_tagihan_mhs=keu_tagihan_mahasiswa.id
inner join keu_tagihan on id_tagihan_prodi=keu_tagihan.id
  group by id_keu_tagihan_mhs having count(*) > 1*/
  
$data_new = $db->query("select * from keu_kwitansi_coba");

$no = 1;
foreach ($data_new as $dt) {
	//select datas

	//$no_kwitansi = $no_kwitansi = strtotime($dt->tanggal_bayar).rand();
	//kwitansi data
	$data_quitansi = array(
              'nominal_bayar' => $dt->nominal_bayar,
              'total_tagihan' => $dt->total_tagihan,
              'validator' => 'API BRIVA',
              'kode_jur' => $dt->kode_jur,
              'metode_bayar' => 3,
              'id_bank' => '01',
              'tgl_bayar' => $dt->tgl_bayar,
              'no_kwitansi' => $dt->no_kwitansi,
              'nim_mahasiswa' => $dt->nim_mahasiswa,
              'date_created' => $dt->date_created,
              'is_generated' => 1
            );
	$db->insert('keu_kwitansi',$data_quitansi);
	$last_insert_id = $db->last_insert_id();
	echo $no.' nim : '.$dt->nim_mahasiswa.' '.$db->getErrorMessage().'<br>';
	$data_sub = $db->fetch_custom_single("select * from keu_bayar_mahasiswa_coba where id_kwitansi='$dt->id_kwitansi'");
        $data = array(
            'id_keu_tagihan_mhs' => $data_sub->id_keu_tagihan_mhs,
            'tgl_bayar' => $data_sub->tgl_bayar,
            'tgl_validasi' => $data_sub->tgl_validasi,
            'created_by' => $data_sub->created_by,
            'id_kwitansi' => $last_insert_id,
            'nominal_bayar' => $data_sub->nominal_bayar,
            'is_generated' => 1
        );
      $db->insert("keu_bayar_mahasiswa",$data);
      echo $no.' nim : '.$dt->nim_mahasiswa.' keu_bayar is good'.$db->getErrorMessage().'<br>';

	$no++;
}
