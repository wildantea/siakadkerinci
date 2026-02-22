<?php
include '../dashboard/inc/config.php';
$q = $dbo->query("select * from periode");

foreach ($q as $k) {
	if ($k->semester=='Ganjil') {
		$thn = $k->thnprd_kd;
		$jns = '1';
	}else{
		$thn = $k->thnprd_kd;
		$jns = '2';
	}
	$qc = $db->query("select id_semester from semester_ref where id_semester='$thn' ");
	$data = array('id_semester' => $thn,
	              'id_jns_semester' => $jns,
	              'semester' => $thn,
	              'tahun' => substr($k->thnprd_kd, 0 , 4),
	              'aktif' => '0' );
	//print_r($data); 
	if ($qc->rowCount()==0) {
		$db->insert("semester_ref",$data);
		$qj = $db->query("select kode_jur from jurusan");
		foreach ($qj as $kj) {
	       $data2 = array('id_semester' => $thn,
	       	              'kode_jur' => $kj->kode_jur,
			             // 'id_jns_semester' => $jns,
			             // 'semester' => $thn,
			              'is_aktif' => '0' );
	       $db->insert("semester",$data2);
	       echo $db->getErrorMessage();  
		}
	}else{
		foreach ($qc as $kc) {
			$db->update("semester_ref",$data,"id_semester",$kc->id_semester);
			echo $db->getErrorMessage();  
		} 
	}
}
?>