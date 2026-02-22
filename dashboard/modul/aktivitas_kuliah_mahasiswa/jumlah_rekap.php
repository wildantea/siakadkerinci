<?php
session_start();
header('Access-Control-Allow-Origin: *');
include "../../inc/config.php";

$json_response = array();

$nim = "";
$nim_delete = "";
$jumlah = 0;

//if nim is not filled
if ($_POST['nim_rekap']!='all') {
		$nim = "and akm.mhs_nim='".$_POST['value_nim_rekap']."'";
		$where_nim_mhs = "and mahasiswa.nim='".$_POST['value_nim_rekap']."'";
		$mhs = $db2->fetchSingleRow("view_simple_mhs_data","nim",$_POST['value_nim_rekap']);
		$mhs_lulus = $db2->fetchSingleRow("tb_data_kelulusan","nim",$_POST['value_nim_rekap']);

		//check if he has tahun keluar/lulus etc
		if ($mhs) {
			//if pilih semester is all
			if ($_POST['pilih_semester']=='all') {
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




					$insert = true;
					if (!empty($array_semester)) {
						foreach ($array_semester as $sem_id) {
								//insert unprocess akm
								$db2->query("insert ignore into akm (mhs_nim,sem_id,id_stat_mhs,ip,ipk,jatah_sks,sks_diambil,sks_wajib,sks_pilihan,total_sks,date_created)
								select nim,".$sem_id.",'N',0,0,0,0,0,0,0,now() from mahasiswa where nim not in(select mhs_nim from akm where mhs_nim=mahasiswa.nim and  sem_id=".$sem_id.") $where_nim_mhs");
								echo $db2->getErrorMessage();
								$array_s1_s3 = array('30','40');
								if (in_array($mhs->id_jenjang, $array_s1_s3)) {
									//delete akm yang statusnya lebih dari 14 semester
									$db2->query("delete akm from akm inner join view_simple_mhs_data on mhs_nim=nim where sem_id='$sem_id' and mhs_nim='$mhs->nim' and ((left('$sem_id',4)-left(mulai_smt,4))*2)+right('$sem_id',1)-(floor(right(mulai_smt,1)/2)) > 14");
								}

								$array_s2 = array('35','40');
								if (in_array($mhs->id_jenjang, $array_s2)) {
									//delete akm yang statusnya lebih dari 14 semester
									$db2->query("delete akm from akm inner join view_simple_mhs_data on mhs_nim=nim where sem_id='$sem_id' and mhs_nim='$mhs->nim' and ((left('$sem_id',4)-left(mulai_smt,4))*2)+right('$sem_id',1)-(floor(right(mulai_smt,1)/2)) > 8");
								}
								//delete akm in semester perbaikan if has no sks
								if (substr($sem_id, -1)=='3') {
									$db2->query("delete akm from akm inner join mahasiswa on mhs_nim=mahasiswa.nim where sem_id='$sem_id'  and mhs_nim='$mhs->nim' and mhs_nim not in(select nim from krs_detail where krs_detail.id_semester='".$sem_id."')");
								}

								if ($sem_id=='10') {
									$db2->query("delete akm from akm inner join mahasiswa on mhs_nim=mahasiswa.nim where sem_id='$sem_id'  and mhs_nim='$mhs->nim' and mhs_nim not in(select nim from krs_detail where krs_detail.id_semester='".$sem_id."')");
								}

						}
				            //get akm exist
				            $akm_sem = $db2->query("select sem_id from akm where right(sem_id,1)='3' and mhs_nim='$mhs->nim'");
				            if ($akm_sem->rowCount()>0) {
				              foreach ($akm_sem as $s_id) {
				                $db2->query("delete akm from akm inner join mahasiswa on mhs_nim=mahasiswa.nim where sem_id='$s_id->sem_id'  and mhs_nim='$mhs->nim' and mhs_nim not in(select nim from krs_detail where krs_detail.id_semester='".$s_id->sem_id."')");
				                echo $db2->getErrorMessage();
				              }
				            }
					}



					//and also delete akm less than tahun masuk
					$db2->query("delete akm from akm inner join mahasiswa on mhs_nim=mahasiswa.nim where mhs_nim='$mhs->nim' and sem_id < mulai_smt and sem_id!='10'");

					$jumlah = 1;
			} else {
				//if nim n semester is selected
				$sem_id = $_POST['semester_rekap'];
					$insert = true;
					//just delete current akm
					$db2->query("delete akm from akm inner join mahasiswa on mhs_nim=mahasiswa.nim where mhs_nim='$mhs->nim' and sem_id='$sem_id'");
								//insert unprocess akm
								$db2->query("insert ignore into akm (mhs_nim,sem_id,id_stat_mhs,ip,ipk,jatah_sks,sks_diambil,sks_wajib,sks_pilihan,total_sks,date_created)
								select nim,".$sem_id.",'N',0,0,0,0,0,0,0,now() from mahasiswa where nim not in(select mhs_nim from akm where mhs_nim=mahasiswa.nim and  sem_id=".$sem_id.") $where_nim_mhs");
								
								echo $db2->getErrorMessage();
								$array_s1_s3 = array('30','40');
								if (in_array($mhs->id_jenjang, $array_s1_s3)) {
									//delete akm yang statusnya lebih dari 14 semester
									$db2->query("delete akm from akm inner join view_simple_mhs_data on mhs_nim=nim where sem_id='$sem_id' and mhs_nim='$mhs->nim' and ((left('$sem_id',4)-left(mulai_smt,4))*2)+right('$sem_id',1)-(floor(right(mulai_smt,1)/2)) > 14");
								}

								$array_s2 = array('35','40');
								if (in_array($mhs->id_jenjang, $array_s2)) {
									//delete akm yang statusnya lebih dari 14 semester
									$db2->query("delete akm from akm inner join view_simple_mhs_data on mhs_nim=nim where sem_id='$sem_id' and mhs_nim='$mhs->nim' and ((left('$sem_id',4)-left(mulai_smt,4))*2)+right('$sem_id',1)-(floor(right(mulai_smt,1)/2)) > 8");
								}
								//delete akm in semester perbaikan if has no sks
								if (substr($sem_id, -1)=='3') {
									$db2->query("delete akm from akm inner join mahasiswa on mhs_nim=mahasiswa.nim where sem_id='$sem_id'  and mhs_nim='$mhs->nim' and mhs_nim not in(select nim from krs_detail where krs_detail.id_semester='".$sem_id."')");
									
									echo $db2->getErrorMessage();
								}


								if ($sem_id=='10') {
									$db2->query("delete akm from akm inner join mahasiswa on mhs_nim=mahasiswa.nim where sem_id='$sem_id'  and mhs_nim='$mhs->nim' and mhs_nim not in(select nim from krs_detail where krs_detail.id_semester='".$sem_id."')");
								}

					//and also delete akm less than tahun masuk
					$db2->query("delete akm from akm inner join mahasiswa on mhs_nim=mahasiswa.nim where mhs_nim='$mhs->nim' and sem_id < mulai_smt  and sem_id!='10'");

					$jumlah = 1;


			}
		}
} else {
	
	$jur_kode = aksesProdi('mahasiswa.jur_kode');
	$jur_kode_simple = aksesProdi('view_simple_mhs_data.jur_kode');
	$jur_kode_delete = aksesProdi('kode_jurusan');
	$periode = $_POST['semester_rekap'];

	if ($_POST['jur_rekap']!="all") {
		$jur_kode = "and mahasiswa.jur_kode='".trim($_POST['jur_rekap'])."'";
		$jur_kode_delete = "and kode_jurusan='".trim($_POST['jur_rekap'])."'";
	}

	
 //left(tanggal_keluar,4) < '2023'
	//insert unprocess akm
	$db2->query("insert ignore into akm (mhs_nim,sem_id,id_stat_mhs,ip,ipk,jatah_sks,sks_diambil,sks_wajib,sks_pilihan,total_sks,date_created)
	select nim,".$_POST['semester_rekap'].",'N',0,0,0,0,0,0,0,now() from mahasiswa where status='M' and
	 nim not in(select mhs_nim from akm where mhs_nim=mahasiswa.nim and sem_id=".$_POST['semester_rekap'].") $jur_kode
	 and ((left('$periode',4)-left(mulai_smt,4))*2)+right('$periode',1)-(floor(right(mulai_smt,1)/2)) < 15
	 and nim not in(select nim from tb_data_kelulusan where nim=mahasiswa.nim and semester < '$periode')
	 ");


	//delete akm yang statusnya lebih dari 14 semester
	$db2->query("delete akm from akm inner join view_simple_mhs_data on mhs_nim=nim where sem_id='$periode' $jur_kode_simple and id_jenjang in(30,40) and ((left('$periode',4)-left(mulai_smt,4))*2)+right('$periode',1)-(floor(right(mulai_smt,1)/2)) > 14");

	//delete s2 yang lebihd dari 8 semester
	$db2->query("delete akm from akm inner join view_simple_mhs_data on mhs_nim=nim where sem_id='$periode' $jur_kode_simple and id_jenjang in(35) and ((left('$periode',4)-left(mulai_smt,4))*2)+right('$periode',1)-(floor(right(mulai_smt,1)/2)) > 8");


	//delete akm lebih dari periode kelulusan
	$db2->query("delete akm from akm inner join mahasiswa on mhs_nim=mahasiswa.nim where sem_id='$periode' and mhs_nim in(select nim from tb_data_kelulusan where semester < '$periode')");

	//and also delete akm less than tahun masuk
	$db2->query("delete akm from akm inner join mahasiswa on mhs_nim=mahasiswa.nim where sem_id='$periode' and sem_id < mulai_smt and sem_id!='10'");
	echo $db2->getErrorMessage();


	//delete akm in semester perbaikan if has no sks
	if (substr($periode, -1)=='3') {
		$db2->query("delete akm from akm inner join mahasiswa on mhs_nim=mahasiswa.nim where sem_id='$periode' and mhs_nim not in(select nim from krs_detail where krs_detail.id_semester='".$periode."' and krs_detail.nim=akm.mhs_nim)");
	}

	if ($periode=='10') {
		$db2->query("delete akm from akm inner join mahasiswa on mhs_nim=mahasiswa.nim where sem_id='$periode' and mhs_nim not in(select nim from krs_detail where krs_detail.id_semester='".$periode."')");
	}

    //get akm exist
/*    $akm_sem = $db2->query("select sem_id from akm where right(sem_id,1)='3' and mhs_nim='$mhs->nim'");
    if ($akm_sem->rowCount()>0) {
      foreach ($akm_sem as $s_id) {
        $db2->query("delete akm from akm inner join mahasiswa on mhs_nim=mahasiswa.nim where sem_id='$s_id->sem_id'  and mhs_nim='$mhs->nim' and mhs_nim not in(select nim from krs_detail where krs_detail.id_semester='".$s_id->sem_id."')");
        echo $db2->getErrorMessage();
      }
    }*/

echo $db2->getErrorMessage();

	//get akm with double data
	$data_akm_double = $db->query("select akm_id,mhs_nim FROM akm
	where mhs_nim in(select nim from mahasiswa where nim=mhs_nim $jur_kode)
	and sem_id = '$periode' 
	GROUP BY mhs_nim,sem_id
	HAVING COUNT(*) > 1");
	$akm_id = array();

	if ($data_akm_double->rowCount()>0) {
		foreach ($data_akm_double as $akm_double) {
			$akm_id[] = $akm_double->akm_id;
		}
		$implode_id = implode(",", $akm_id);
	}
	if (!empty($akm_id)) {
		//dump($implode_id);
		//and also delete akm where count is > 1
		$db2->query("delete from akm where akm_id in($implode_id)");
		echo $db2->getErrorMessage();
	}

	$data = $db2->fetchCustomSingle("select count(*) as jml from akm inner join mahasiswa on akm.mhs_nim=mahasiswa.nim where sem_id=? $jur_kode",array('id_semester' => $_POST['semester_rekap']));

	$jumlah = $data->jml;
	//echo $jumlah;
}



$json_response['jumlah'] = $jumlah;

echo json_encode($json_response);

?>
