<?php
include '../dashboard/inc/config.php';
$q = $dbo->query("select m.*,j.jrsn_kd as kode_jurusan,j.jurusan from mk m join jurusan j on j.id_jurusan=m.id_jurusan");
echo "<pre>";
foreach ($q as $k) {
	$qc = $db->query("select kode_mk from matkul where kode_mk='$k->kode_mk' ");
	if ($qc->rowCount()==0) {
		// $datak = array('kode_jur' => $k->kode_jurusan,
	 //                  'sem_id'  => '20051', 
	 //                  'nama_kurikulum' => $k->jurusan." Simak Lama",
	 //                   );
		$qck = $db->query("select kur_id from kurikulum where sem_id='20051' and kode_jur='$k->kode_jurusan'  ");
		foreach ($qck as $kc) {
			$kur_id = $kc->kur_id;
		}
		$data = array('kur_id' => $kur_id , 
	                  'kode_mk' => $k->kode_mk,
	                  'id_jenjang' => '30',
	                  'id_tipe_matkul' => 'A',
	                  'semester' => $k->semester,
	                  'nama_mk' => str_replace("`", "", $k->mk), 
	                  'sks_tm' => $k->sks,
	                  'bobot_minimal_lulus' => $k->sks,
	                  'a_wajib' => '1');
		print_r($data);
		$db->insert("matkul",$data);
		echo $db->getErrorMessage();  
	}   
	 
}

?>