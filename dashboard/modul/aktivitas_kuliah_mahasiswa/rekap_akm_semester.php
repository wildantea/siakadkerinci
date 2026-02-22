<?php
function sortSemestersAsc($a, $b) {
    return $a - $b;
}
if ($_POST['total_data']>0) {

		$jumlah = array();
		$offset = $_POST['offset'];
		$jumlah['offset'] = $offset;

		$sukses_count = 0;
		$error_count = 0;


		$error_msg = array();
		$nim_data = array();
		$data_id_update = array();
		$data_update_akm = array();
		$data = array();
		$all_errors = array();
		$nim_mhs_periode = array();
		$array_semester = array();
		

		$error_code = "";

		$sem = "";
		$jur_kode = aksesProdi('mahasiswa.jur_kode');

	    $sem = "where akm.sem_id='".$_POST['semester_rekap']."' ";
		
		if ($_POST['jur_rekap']!="all") {
			$jur_kode = "and mahasiswa.jur_kode='".trim($_POST['jur_rekap'])."'";
		}

		if ($_POST['nim_rekap']!='all') {
			$nim = "and akm.mhs_nim='".$_POST['value_nim_rekap']."'";
		}

		$query_not_in = "and unik_id not in(".$_POST['random_number'].")";

		$datas = $db2->query("select akm_id,id_stat_mhs,akm.mhs_nim,akm.sem_id,(select sum(sks) from krs_detail inner join matkul 
on krs_detail.kode_mk=matkul.id_matkul
 where nim=akm.mhs_nim and id_semester=akm.sem_id  and krs_detail.disetujui='1' and batal=0) as sks,
(select format(sum(bobot * sks)/sum(sks),2) from krs_detail inner join matkul 
on krs_detail.kode_mk=matkul.id_matkul
 where nim=akm.mhs_nim and id_semester=akm.sem_id and krs_detail.disetujui='1' and batal=0 and bobot  is not null) as ip,
(select format(sum(bobot * sks)/sum(sks),2) from krs_detail inner join matkul 
on krs_detail.kode_mk=matkul.id_matkul
 where nim=akm.mhs_nim and id_semester<=akm.sem_id  and krs_detail.disetujui='1' and batal=0 and bobot is not null) as ipk,
(select sks_mak from jatah_sks j where  IFNULL((select akm.ip  from akm akm where akm.mhs_nim=akm.mhs_nim
 and akm.sem_id<akm.sem_id
and akm.id_stat_mhs='A' ORDER BY sem_id DESC LIMIT 1),4) BETWEEN j.ip_min and j.ip_mak) as jatah_sks,
(select SUM(IF(a_wajib = '1', sks,0)) from krs_detail 
inner join matkul on krs_detail.kode_mk=id_matkul where nim=akm.mhs_nim and id_semester=akm.sem_id  and krs_detail.disetujui='1' and batal=0) as sks_wajib_diambil_kumulatif,
(select SUM(IF(a_wajib = '0', sks,0)) from krs_detail
inner join matkul on krs_detail.kode_mk=id_matkul where nim=akm.mhs_nim and id_semester=akm.sem_id  and krs_detail.disetujui='1' and batal=0) as sks_pilihan_diambil_kumulatif,
(select SUM(sks) from krs_detail 
inner join matkul on krs_detail.kode_mk=id_matkul where nim=akm.mhs_nim and id_semester<=akm.sem_id  and krs_detail.disetujui='1' and batal=0) as sks_kumulatif,
(select nim from tb_data_cuti_mahasiswa inner join tb_data_cuti_mahasiswa_periode using(id_cuti) where nim=akm.mhs_nim and status_acc!='rejected' and periode='".$_POST['semester_rekap']."' limit 1) as is_cuti
 from akm 
inner join mahasiswa  on akm.mhs_nim=mahasiswa.nim where akm.sem_id='".$_POST['semester_rekap']."' $jur_kode $nim $query_not_in limit 50");

echo $db2->getErrormessage();
		foreach ($datas as $value) {
			//dump($value);
			if ($value->sks=="") {
				$sks_semester=0;
			} else {
				$sks_semester = $value->sks;
			}
			if ($value->ip=="") {
				$ip = 0;
			} else {
				$ip = $value->ip;
			}
			if ($value->ipk=="") {
				$ipk = 0;
			} else {
				$ipk = $value->ipk;
			}
			if ($value->sks_wajib_diambil_kumulatif=="") {
				$sks_wajib_kumulatif = 0;
			} else {
				$sks_wajib_kumulatif = $value->sks_wajib_diambil_kumulatif;
			}
			if ($value->sks_pilihan_diambil_kumulatif=="") {
				$sks_pilihan_kumulatif = 0;
			} else {
				$sks_pilihan_kumulatif = $value->sks_pilihan_diambil_kumulatif;
			}

			if ($value->jatah_sks=="") {
				$jatah_sks = 0;
			} else {
				$jatah_sks = $value->jatah_sks;
			}
			if ($value->sks_kumulatif=="") {
				$sks_total = 0;
			} else {
				$sks_total = $value->sks_kumulatif;
			}
			
			if ($value->id_stat_mhs=='N' && $sks_semester>0) {
				$id_stat_mhs = 'A';
			} elseif ($value->id_stat_mhs=='A' && $sks_semester==0) {
				$id_stat_mhs = 'N';
			} else {
				$id_stat_mhs = $value->id_stat_mhs;
			}
			if ($value->is_cuti!='') {
				$id_stat_mhs = 'C';
			}

			$data_update_akm[] = array(
				'ip' => $ip,
				'ipk' => $ipk,
				'id_stat_mhs' => $id_stat_mhs,
				'jatah_sks' => $jatah_sks,
				'sks_diambil' => $sks_semester,
				'sks_wajib' => $sks_wajib_kumulatif,
				'sks_pilihan' => $sks_pilihan_kumulatif,
				'total_sks' => $sks_total,
				'unik_id' => $_POST['random_number'],
				'date_updated' => date('Y-m-d H:i:s')
			);
			
			$nim_mhs_periode[$value->mhs_nim][] = $value->sem_id;
			$data_id_update[] = $value->akm_id;
			/*$data_update_krs[] = array(
				'jatah_sks' => $jatah_sks,
				'sks_diambil' => $sks_total
			);*/
			//$data_krs_id[] = $value->krs_id;
		}

		//dump($data_update_akm);

		

		if (!empty($data_update_akm)) {
			$db2->updateMulti('akm',$data_update_akm,'akm_id',$data_id_update);
			echo $db2->getErrormessage();
		}
		/*if (!empty($data_update_krs)) {
			$db2->updateMulti('tb_data_kelas_krs',$data_update_krs,'krs_id',$data_krs_id);	
		}*/
		$implode_id = implode(",", $data_id_update);
    $db->query(
        "update akm set unik_id=" .
            $_POST["random_number"] .
            " where akm_id in (" .
            $implode_id .
            ")"
    );
	if (isset($_POST['id_data'])) {
		$jml_error_sukses = $db2->fetchCustomSingle("SELECT COUNT(*) AS jml_sukses FROM akm where unik_id=".$_POST['random_number']);
		$sukses_count = $jml_error_sukses->jml_sukses;
		$jumlah_sukses = $sukses_count;
		$db2->query("update akm set unik_id=0 where  unik_id=".$_POST['random_number']);
		$msg =  "<div class=\"alert alert-warning \" role=\"alert\">
		<font color=\"#3c763d\">".$jumlah_sukses." data AKM berhasil Direkap</font><br />";
		//echo "<br />Total: ".$i." baris data";
		$msg .= "<div class=\"collapse\" id=\"collapseExample\">";
				$i=1;
				foreach ($all_errors as $pesan) {
						$msg .= "<div class=\"bs-callout bs-callout-danger\">".$i.". ".$pesan."</div><br />";
					$i++;
					}
		$msg .= "</div>
		</div>";


		$jumlah['last_notif'] = $msg;
	}


} else {
	$msg =  "<span style='color:#f00'>Tidak ada data yang bisa diproses</span><p>";
	$jumlah['last_notif'] = $msg;
}