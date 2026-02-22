<?php
include '../dashboard/inc/config.php';
$q = $dbo->query("select m.*,j.jrsn_kd,d.nis as nip from mhs m 
	join jurusan j on j.id_jurusan=m.id_jurusan 
	left join dosen d on d.id_dosen=m.id_dosen  where migrasi='0' limit 1000 "); 
echo "<pre>";


foreach ($q as $k) {
	$jk = 'L';
	$stat_pd = 'A';
	if ($k->sex=='Perempuan') { 
		$jk = 'P';
	} 
	if ($k->status_kuliah!='Aktiv') {
		$stat_pd = 'N'; 
	}
	$dosen_pemb = str_replace(" ", "", $k->nip);
	$data = array('nim' => $k->npm , 
                  'nama' => $k->nama, 
                  'jur_kode' => $k->jrsn_kd, 
                  'id_jns_daftar' => '1', 
                  'id_agama' => '1' ,
                  'mulai_smt' => $k->angkatan."1" , 
                  'jk' => $jk ,  
                  'nik' =>  $k->mhs_nik, 
                  'tmpt_lahir' => $k->tempat_lahir , 
                  'jln' => $k->alamat , 
                  'tgl_lahir' => $k->tgl_lahir , 
                  'stat_pd' => $stat_pd, 
                  'nm_ayah' => $k->nama_ortu , 
                  'nm_ibu_kandung' => $k->nama_ortu2 , 
                  'kewarganegaraan' => 'ID' ,
                  'dosen_pemb' => $dosen_pemb , 
                  'status' => 'M' , );
	$qc = $db->query("select nim from mahasiswa where nim='$k->npm' ");
	if ($qc->rowCount()==0) {		
		$db->insert("mahasiswa",$data);  
		$db->query("update sys_users set password='".md5($k->password)."',plain_pass='$k->password' where username='$k->npm' ");
		
	}else{ 
		$db->update("mahasiswa",$data,"nim",$k->npm);
		$db->query("update sys_users set password='".md5($k->password)."',plain_pass='$k->password' where username='$k->npm' ");
	}
	echo $db->getErrorMessage()."<br>"; 
	//print_r($data);
	$dbo->query("update mhs set migrasi='1' where npm='$k->npm' "); 
	
}
?>