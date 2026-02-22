<?php
include '../dashboard/inc/config.php';



$q = $dbo->query("select k.id_kontrakx, k.id_periode,k.id_kelas,k.kntrk_mkdis,k.angkatan, replace(d.nis,' ','') as nip, k.id_kontrakx, k.id_mk,k.id_periode,k.id_kelas,p.thnprd_kd as semester,mk.kode_mk,kl.kelas
	from kontrakx k join periode p on p.id_periode=k.id_periode

join kelas kl on kl.id_kelas=k.id_kelas
join mk_dis md on md.id_mk_dis=k.kntrk_mkdis 
join mk on mk.id_mk=md.id_mk 
left join dosen d on d.id_dosen=k.id_dosen where migrasi='0' limit 10"); 
echo "<pre>";
//$gagal = array('' => , );
foreach ($q as $k) {
    $qm = $db->query("select * from matkul where kode_mk='$k->kode_mk' ");
    if ($qm->rowCount()>0) {
    
    	foreach ($qm as $km) {
    	$id_mk = $km->id_matkul;
       }
       $data = array('sem_id' =>  $k->semester , 
                  'id_matkul' => $id_mk,
                  'kls_nama' => $k->kelas, 
                  'oleh' => "dthan",
                  'peserta_max' => '40',
                  'peserta_min' => '10',
                  'id_jenis_kelas' => '1',
                  'is_open' =>  'Y',
                  'old_id_periode' => $k->id_periode,
                  'old_kelas' => $k->id_kelas,
                  'old_kntrk_mkdis' => $k->kntrk_mkdis,
                  'old_angkatan' => $k->angkatan,
                  'old_idkontrakx' => $k->id_kontrakx ); 
       $qk = $db->query("select kelas_id from kelas where id_matkul='$id_mk' and kls_nama='$k->kelas'
       and sem_id='$k->semester' "); 
       echo "select kelas_id from kelas where id_matkul='$id_mk' and kls_nama='$k->kelas'
       and sem_id='$k->semester' <br>";
       if ($qk->rowCount()==0) { 
         $db->insert("kelas",$data);
       }else{
       	 foreach ($qk as $kk) {
       	 	$kelas_id = $kk->kelas_id;
       	 	$db->update("kelas",$data,"kelas_id",$kk->kelas_id);
       	 }       	  
       }	  
       print_r($data); 
      // $dbo->query("update kontrakx set migrasi='1' where id_kontrakx='$k->id_kontrakx' ");  
    }
    
	
}
?>