<?php
    $status_token = $db->fetch_custom_single("SELECT enable_token_read,enable_token_create,enable_token_update,enable_token_delete,format_data,id_service FROM sys_services INNER JOIN sys_token ON sys_services.id=sys_token.id_service where nav_act=?",array("nav_act" => "mega-sariah"));
	$app->post('/mahasiswa',function() use ($app,$db,$status_token) {
	$key = $_POST['key'];
	$token='';
	if (array_key_exists('token', $_POST)) {
		$token = $_POST['token'];  
	} 
	
	//$key = $id;
	
	if (($token=='2y10bJ09e9jzVxNjKe8wis8eIgIUSQi0rrgQGmck313jL0mNJK9G')) {
		$data = $db->query("select m.telepon_seluler as telp, m.jln,m.nm_dsn, m.rt,m.rw, m.nm_dsn as alamat,m.mulai_smt as angkatan, f.nama_resmi as fakultas, j.nama_jur as jurusan, j.kode_jur as kode_jur, 
m.nim as nomor_induk,m.nama,'mahasiswa' as status ,
(select concat(j.jns_semester,' ',s.tahun,'/',(s.tahun+1)) as sem_aktif
from semester_ref s join jenis_semester j  on s.id_jns_semester=j.id_jns_semester
where s.aktif='1') as sem_aktif,
(select j.jns_semester as sem_aktif
from semester_ref s join jenis_semester j  on s.id_jns_semester=j.id_jns_semester
where s.aktif='1') as ket_semester,
(select (s.tahun) as sem_aktif
from semester_ref s join jenis_semester j  on s.id_jns_semester=j.id_jns_semester
where s.aktif='1') as tahun_sekarang,jk.ket_keluar as status_aktif  from mahasiswa m 
join jurusan j on j.kode_jur=m.jur_kode
join fakultas f on f.kode_fak=j.fak_kode
left join tb_data_kelulusan kl on kl.nim=m.nim
left join jenis_keluar jk on jk.id_jns_keluar=kl.id_jenis_keluar where m.nim  like '%$key%' or m.nama like '%$key%' ");
		if ($data==true) {
				$response['status'] = array();


				if ($data->rowCount()>0) {
					$response['results'] = array();
					$response['jml_data'] = $data->rowCount();
					foreach ($data as $dt) {
					//status code
					$response['status']['code'] = 200;
					$response['status']['message'] = "Ok";
				if ($dt->status_aktif=='' or $dt->status_aktif==NULL) {
					$semester = $dt->tahun_sekarang - (substr($dt->angkatan, 0,4));
					if ($dt->ket_semester=='Genap') {
						$semester = ($semester * 2) + 2;
					}else{
						$semester = ($semester * 2) + 1;
					}
				}else{
					$semester = $dt->status_aktif;
				}
				
						
				$row = array(
						'status' => $dt->status,
						'nomor_induk' => $dt->nomor_induk,
						'nama' => $dt->nama,
						'jurusan' => $dt->jurusan,
						'alamat' => $dt->jln." RT $dt->rt/RW $dt->rw, ".$dt->alamat,   
						'angkatan' => substr($dt->angkatan, 0,4) ,  
						'sem_aktif' => $dt->sem_aktif,
						'fakultas' => $dt->fakultas,
						'semester' => $semester,
						'telp' => $dt->telp,
						);
					//result data
					array_push($response['results'],$row);
					}
			        echoResponse(200, $response,$status_token->format_data);
				} else {
					$response['status']['code'] = 404;
					$response['jml_data'] = 0;
		            $response['status']["message"] = "The requested resource doesn't exists";
		            echoResponse(404, $response,$status_token->format_data);
				}

			} else {
				$response['status']['code'] = 422;
				$response['jml_data'] = 0;
				$response['status']['message'] = $db->getErrorMessage();
				echoResponse(422, $response,$status_token->format_data);
			}
	}else{
		$response['status']['code'] = 422;
		$response['jml_data'] = 0;
		$response['status']['message'] = "Invalid / empty Token"; 
		echoResponse(422, $response,$status_token->format_data);
	}
			
	});

	
?>