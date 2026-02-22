<?php
// Custom comparison function to sort the inner arrays in ascending order
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

		$nim = "and akm.mhs_nim='".$_POST['value_nim_rekap']."'";
		$mhs = $db2->fetchSingleRow("mahasiswa","nim",$_POST['value_nim_rekap']);
		$array_semester = array();

		//check if he has semester nilai pindah
		$sm_pindah = $db2->fetchCustomSingle("select group_concat(distinct id_semester) as id_semester from krs_detail where nim='".$_POST['value_nim_rekap']."' and id_semester='10'");
		if ($sm_pindah) {
			$semester_pindah = explode(",",  $sm_pindah->id_semester);
			$array_semester = array_merge($array_semester, $semester_pindah);
		}
		$array_semester = array_filter($array_semester);
					
		//check if he has semester pendek
		$sm_pendek = $db2->fetchCustomSingle("select group_concat(distinct id_semester) as id_semester from krs_detail where nim='".$_POST['value_nim_rekap']."' and right(id_semester,1)='3'");
		if ($sm_pendek) {
			$semester_pendek = explode(",",  $sm_pendek->id_semester);
			$array_semester = array_merge($array_semester, $semester_pendek);
		}
		$array_semester = array_filter($array_semester);
		//loop over semester from semester awal to current semester
		$loop_data_semester = $db2->fetchCustomSingle("select group_concat(distinct id_semester) as id_semester from semester where (id_semester>='".$mhs->mulai_smt."' and id_semester<='".getSemesterAktif()."') and right(id_semester,1) in(1,2)");
		$semester_data = explode(",",  $loop_data_semester->id_semester);
		$array_semester = array_merge($array_semester, $semester_data);
		$array_semester = array_unique($array_semester);
		sort($array_semester);
		
		//dump($array_semester);
		//first check semester awal masuk

		$implode_semester = implode(",", $array_semester);




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
(select nim from tb_data_cuti_mahasiswa inner join tb_data_cuti_mahasiswa_periode using(id_cuti) where nim=akm.mhs_nim and status_acc!='rejected' and periode=akm.sem_id limit 1) as is_cuti
 from akm 
inner join mahasiswa  on akm.mhs_nim=mahasiswa.nim where akm.sem_id in($implode_semester) 
$jur_kode $nim $query_not_in limit 50");

echo $db2->getErrorMessage();
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
			
		}


		if (!empty($data_update_akm)) {
			$db2->updateMulti('akm',$data_update_akm,'akm_id',$data_id_update);
		}
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
?>