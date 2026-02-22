<?php
session_start();
include "config.php";


$json_response = array();

function check_dosen($username)
{
	global $db;
	$q = $db->query("select group_level,id from sys_users where username='$username' "); 
	$dat = array();
	if ($q->rowCount()>0) {
		foreach ($q as $k) {
			if ($k->group_level=='4') {
				$dat['id'] = $k->id;
				$dat['group_level'] = $k->group_level;
				$dat['status'] = true;
				//return true;
			}else{
				//return false;
				$dat['status'] = false;
			}
		}
	}else{
		$dat['status'] = false;
	}
	return $dat;
}

function simpan_dosen($data){
	global $db;
	$q = $db->query("select id from sys_users where username='".$data->nip."' ");
	if ($q->rowCount()==0) {
		
		$datd = array('nip' =>  $data->nip,
		              'nama_dosen' => $data->nama,
		              'email' => $data->email,
		              'gelar_depan' => $data->gelar_depan,
		              'gelar_belakang' => $data->gelar_belakang);
		$qd = $db->query("select nip from dosen where nip='$data->nip' ");
		if ($qd->rowCount()==0) {
			$db->insert("dosen",$datd);
		}
		
		$dat = array('first_name'  => $data->nama, 
	                 'username'    => $data->nip,
	                 'group_level' => '4',
	                 'aktif'       => 'Y',
	                 //'password'    => md5($data->password)
	             );
		$db->insert("sys_users",$dat);
        $id = $db->last_insert_id();
	}else{
		$q = $db->query("select id from sys_users where username='".$data->nip."' ");
		foreach ($q as $k) {
			$id = $k->id;
		}
	}
	return $id;
}

function updateLastLogin($username) {
	global $db;
	$db->update('sys_users',array('last_login' => date('Y-m-d H:i:s')),'username',$username);
}

function checkSurveyAktif() { 
	global $db;
	$status = array();

 $semester = $db->fetch_custom_single("SELECT * FROM semester_survey
WHERE CURDATE() BETWEEN periode_awal_mulai AND periode_awal_selesai
   OR CURDATE() BETWEEN periode_tengah_mulai AND periode_tengah_selesai
   OR CURDATE() BETWEEN periode_akhir_mulai AND periode_akhir_selesai OR
   CURDATE() BETWEEN periode_lainya_mulai AND periode_lainya_selesai
   order by id_semester desc limit 1");

//and id_semester='$is_aktif->id_semester'
if ($semester) {
		$status = array(
			'aktif' => true,
			'semester' => $semester->id_semester
		);
} else {
			$status = array(
			'aktif' => false,
			'semester' => $semester->id_semester
		);
}

	return $status;
}

function checkKrsAmbilSurvey($nim,$semester) {
	global $db;
	//check if mahasiswa ambil krs dan kelasnya ada dosen
	$jml_krs = $db->fetch_custom_single("select count(vnk.kelas_id) as jml_krs
 from kelas vnk
inner join dosen_kelas vj on vnk.kelas_id=vj.id_kelas
inner join krs_detail on vnk.kelas_id=krs_detail.id_kelas
where krs_detail.nim='$nim' and krs_detail.id_semester='$semester'");
	if ($jml_krs->jml_krs>0) {
		return true;
	} else {
		return false;
	}
}

//i only receive ajax request :D
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

		$data = array(
		'username'=>$_POST['username'],
		'password'=>md5($_POST['password'])
		);
		$check = $db->check_exist('sys_users',$data);
		//print_r($check);
		if ($check==true) {
			updateLastLogin($_POST['username']);
			$dt=$db->fetch_single_row('sys_users','username',$_POST['username']);
							//check if mahasiswa
				if ($check->getData()->group_level==3) {
					//check if mahasiswa
					$check_mhs = $db->fetch_single_row("mahasiswa","nim",$dt->username);
						if ($check_mhs->status=='M') {
						
						//check if survey aktif
						$check_survey = checkSurveyAktif();
						
						if($check_survey['aktif']==true) {
							//check is mahasiswa krs pada periode survey aktif
							$checkkrsSurvey = checkKrsAmbilSurvey($dt->username,$check_survey['semester']);

							$has_krs = 'no';
							if ($checkkrsSurvey) {
								$has_krs = 'yes';
							}
								//cek if student sudah survey di motekar
								$check_motekar = file_get_contents('https://survei.iainkerinci.ac.id/service/has_survey.php?nim='.$dt->username.'&semester='.$check_survey['semester'].'&has_krs='.$has_krs);

								if (json_decode($check_motekar)->status_survey=='belum') { 
									$status['status'] = "survey";
									array_push($json_response, $status);
									echo json_encode($json_response);
									exit();
								}

						}
						}
				}

		if ($check->getData()->group_level==4) {
			//check if dosen aktif
			$dosen=$db->fetch_single_row('dosen','nip',$_POST['username']);
			if ($dosen->aktif=='N') {
				$status['status'] = "bad";
				$status['error_log'] = $db->getErrorMessage();
				array_push($json_response, $status);
				echo json_encode($json_response);
				exit();
			}
		}

		
		$group_dt=$db->fetch_single_row('sys_group_users','id',$dt->group_level);
			$_SESSION['group_level']=$group_dt->level;
			$_SESSION['id_group_level']=$group_dt->id;
			$_SESSION['id_user']=$dt->id;
			$_SESSION['login']=1;
			$_SESSION['username'] = $dt->username;
			$_SESSION['nama'] = $dt->first_name." ".$dt->last_name;
			$_SESSION['level']=$dt->group_level;
			$status['redirect'] = "";
	        if ($_SESSION['last_page']!='') {
	            $status['redirect'] = $_SESSION['last_page'];
	        }
			$status['status'] = "good";
		} else {
			$status['status'] = "bad";
			$status['error_log'] = $db->getErrorMessage();
		}

} else {
	//hei , don't ever try if you're not ajax request, because you gonna die
	$status['status'] = "go out dude";
}


array_push($json_response, $status);
echo json_encode($json_response);

?>
