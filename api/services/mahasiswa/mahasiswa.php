<?php
	//doc route
	$app->get('/mahasiswa/doc',function() use ($db) {
		include "doc.php";
	});


$status_token = $db->fetch_custom_single("SELECT enable_token_read,enable_token_create,enable_token_update,enable_token_delete,format_data,id_service FROM sys_services INNER JOIN sys_token ON sys_services.id=sys_token.id_service where nav_act=?",array("nav_act" => "mahasiswa"));
	
	//login action
	$app->post('/mahasiswa/login', function() use ($app,$db,$status_token) {
		auth_data($app,$db,$status_token->format_data);
	});
	
	$read_auth = ($status_token->enable_token_read=="Y")?$otorisasi($status_token->format_data,$status_token->id_service,"read"):"noauth";


	//url route
	$app->get('/mahasiswa',$read_auth, function() use ($app,$apiClass,$pg,$db2,$status_token) {
$periode_cuti = getSemesterAktif();
$tahun_aktif = substr($periode_cuti, 0,4);
$sem_id = substr($periode_cuti, -1);

$jml = $db2->fetchCustomSingle("select count(*) as jml from mahasiswa  inner join view_prodi_jenjang on mahasiswa.jur_kode=view_prodi_jenjang.kode_jur
where status='M'");
$jumlah = $jml->jml;

		$data = $pg->query("select mahasiswa.nama,mahasiswa.nim,mahasiswa.tgl_lahir,mahasiswa.jk,mahasiswa.email,
mahasiswa.no_hp,mahasiswa.id_agama,mahasiswa.status_pernikahan,mahasiswa.jln,
mahasiswa.tgl_masuk_sp,mahasiswa.rt,mahasiswa.rw,mahasiswa.nm_dsn,mahasiswa.ds_kel,
mahasiswa.kode_pos,mahasiswa.no_tel_rmh,mahasiswa.a_terima_kps,mahasiswa.no_kps,
mahasiswa.stat_pd,mahasiswa.nm_ayah,nama_jurusan,jenjang,
    (($tahun_aktif-left(mulai_smt,4))*2)+$sem_id-(floor(right(mulai_smt,1)/2)) as smt,
    ( select concat(data_wilayah.nm_wil,' - ',dw.nm_wil) from data_wilayah
inner join data_wilayah dw on data_wilayah.id_wil=dw.id_induk_wilayah
inner join data_wilayah dwc on dw.id_wil=dwc.id_induk_wilayah
where data_wilayah.id_level_wil='1' and dwc.id_wil=mahasiswa.id_wil) as provinsi_mhs,

(select ipk from akm where mhs_nim=mahasiswa.nim order by sem_id desc limit 1) as ipk,
(select total_sks from akm where mhs_nim=mahasiswa.nim and total_sks is not null order by sem_id desc limit 1) as total_sks,
(select id_jenis_keluar from tb_data_kelulusan where tb_data_kelulusan.nim=mahasiswa.nim limit 1) as jns_keluar,
(select tb_data_cuti_mahasiswa.id_cuti from tb_data_cuti_mahasiswa inner join tb_data_cuti_mahasiswa_periode
using (id_cuti) where tb_data_cuti_mahasiswa_periode.periode='$periode_cuti' and tb_data_cuti_mahasiswa.nim=mahasiswa.nim) as is_cuti

 from mahasiswa  inner join view_prodi_jenjang on mahasiswa.jur_kode=view_prodi_jenjang.kode_jur
where status='M'",null,$jumlah);


		$pg->getErrorMessage();
function getJenisKeluar() {
  global $db2;
  $jns_keluars = $db2->query("select * from jenis_keluar");
  foreach ($jns_keluars as $jns_keluar) {
    $data_jenis_keluar[$jns_keluar->id_jns_keluar] = $jns_keluar->ket_keluar;
  }
  return $data_jenis_keluar;
}
 $jenis_keluar_array = getJenisKeluar();

		if ($data==true) {
		$response['status'] = array();
		//meta data
		$response['meta']['total-records'] = $pg->total_record;
		$response['meta']['current-records'] = $pg->total_current_record;
		$response['meta']['total-pages'] = $pg->total_pages;
		$response['meta']['current-page'] = $pg->page;

		$response['results'] = array();
		foreach ($data as $dt) {
		//status code
		$response['status']['code'] = 200;
		$response['status']['message'] = "Ok";


    if ($dt->jns_keluar=='') {
       if ($dt->is_cuti!='') {
         $status_mhs = 'Cuti';
       } else {
          $status_mhs = 'Aktif';
        }

    } else {
    	$status_mhs = $jenis_keluar_array[$dt->jns_keluar];
    }
		$row = array(
				'nama_mahasiswa' => $dt->nama,
				'nim' => $dt->nim,
				'tanggal_lahir' => $dt->tgl_lahir,
				'jenis_kelamin' => $dt->jk,
				'email' => $dt->email,
				'no_hp' => $dt->no_hp,
				'agama' => $dt->id_agama,
				'status_pernikahan' => $dt->status_pernikahan,
				'status_kemahasiswaan' => $status_mhs,
				'tanggal_masuk_kuliah' => $dt->tgl_masuk_sp,
				'nama_perguruan_tinggi' => 'Institut Agama Islam Negeri Kerinci',
				'kode_perguruan_tinggi' => '202036',
				'nama_program_studi' => $dt->nama_jurusan,
				'jenjang' => $dt->jenjang,
				'semester' => $dt->smt,
				'ipk' => $dt->ipk,
				'jumlah_sks_ditempuh' => $dt->total_sks,
				'provinsi_kota_kampus' => 'Provinsi Jambi - Kota Sungai Penuh',
				'alamat_domisili' => $dt->jln,
				'provinsi_kota_mahasiswa' => $dt->provinsi_mhs,
				);
		//result data
		array_push($response['results'],$row);

		}
		//paginations link
		$response['paginations'] = array();
		$response['paginations']['self'] = $pg->api_current_uri($apiClass->uri_segment(0).'/'.$apiClass->uri_segment(1));
		$response['paginations']['first'] = $pg->api_first($apiClass->uri_segment(0).'/'.$apiClass->uri_segment(1));
		$response['paginations']['prev'] = $pg->api_prev($apiClass->uri_segment(0).'/'.$apiClass->uri_segment(1));
		$response['paginations']['next'] = $pg->api_next($apiClass->uri_segment(0).'/'.$apiClass->uri_segment(1));
		$response['paginations']['last'] = $pg->api_last($apiClass->uri_segment(0).'/'.$apiClass->uri_segment(1));
		
        echoResponse(200, $response,"json");
		} else {
			$response['status']['code'] = 422;
			$response['status']['message'] = $pg->getErrorMessage();
			echoResponse(422, $response,"json");
		}
	});

	$app->get('/mahasiswa/:id',$read_auth, function($id) use ($app,$apiClass,$pg) {
		$periode_cuti = getSemesterAktif();
$tahun_aktif = substr($periode_cuti, 0,4);
$sem_id = substr($periode_cuti, -1);
	$data = $pg->query("select mahasiswa.nama,mahasiswa.nim,mahasiswa.tgl_lahir,mahasiswa.jk,mahasiswa.email,
mahasiswa.no_hp,mahasiswa.id_agama,mahasiswa.status_pernikahan,mahasiswa.jln,
mahasiswa.tgl_masuk_sp,mahasiswa.rt,mahasiswa.rw,mahasiswa.nm_dsn,mahasiswa.ds_kel,
mahasiswa.kode_pos,mahasiswa.no_tel_rmh,mahasiswa.a_terima_kps,mahasiswa.no_kps,
mahasiswa.stat_pd,mahasiswa.nm_ayah,nama_jurusan,jenjang,
    (($tahun_aktif-left(mulai_smt,4))*2)+$sem_id-(floor(right(mulai_smt,1)/2)) as smt,
    ( select concat(data_wilayah.nm_wil,' - ',dw.nm_wil) from data_wilayah
inner join data_wilayah dw on data_wilayah.id_wil=dw.id_induk_wilayah
inner join data_wilayah dwc on dw.id_wil=dwc.id_induk_wilayah
where data_wilayah.id_level_wil='1' and dwc.id_wil=mahasiswa.id_wil) as provinsi_mhs,

(select ipk from akm where mhs_nim=mahasiswa.nim order by sem_id desc limit 1) as ipk,
(select total_sks from akm where mhs_nim=mahasiswa.nim and total_sks is not null order by sem_id desc limit 1) as total_sks,
(select id_jenis_keluar from tb_data_kelulusan where tb_data_kelulusan.nim=mahasiswa.nim limit 1) as jns_keluar,
(select tb_data_cuti_mahasiswa.id_cuti from tb_data_cuti_mahasiswa inner join tb_data_cuti_mahasiswa_periode
using (id_cuti) where tb_data_cuti_mahasiswa_periode.periode='$periode_cuti' and tb_data_cuti_mahasiswa.nim=mahasiswa.nim) as is_cuti

 from mahasiswa  inner join view_prodi_jenjang on mahasiswa.jur_kode=view_prodi_jenjang.kode_jur
where status='M' and mahasiswa.nim=?",array('mhs_id'=>$id));
	if ($data==true) {
		$response['status'] = array();


		$pg->getErrorMessage();
function getJenisKeluar() {
  global $db2;
  $jns_keluars = $db2->query("select * from jenis_keluar");
  foreach ($jns_keluars as $jns_keluar) {
    $data_jenis_keluar[$jns_keluar->id_jns_keluar] = $jns_keluar->ket_keluar;
  }
  return $data_jenis_keluar;
}
 $jenis_keluar_array = getJenisKeluar();
 
		if ($data->rowCount()>0) {
			$response['results'] = array();
			foreach ($data as $dt) {
			//status code
			$response['status']['code'] = 200;
			$response['status']['message'] = "Ok";
				
		 if ($dt->jns_keluar=='') {
       if ($dt->is_cuti!='') {
         $status_mhs = 'Cuti';
       } else {
          $status_mhs = 'Aktif';
        }

    } else {
    	$status_mhs = $jenis_keluar_array[$dt->jns_keluar];
    }
		$row = array(
				'nama_mahasiswa' => $dt->nama,
				'nim' => $dt->nim,
				'tanggal_lahir' => $dt->tgl_lahir,
				'jenis_kelamin' => $dt->jk,
				'email' => $dt->email,
				'no_hp' => $dt->no_hp,
				'agama' => $dt->id_agama,
				'status_pernikahan' => $dt->status_pernikahan,
				'status_kemahasiswaan' => $status_mhs,
				'tanggal_masuk_kuliah' => $dt->tgl_masuk_sp,
				'nama_perguruan_tinggi' => 'Institut Agama Islam Negeri Kerinci',
				'kode_perguruan_tinggi' => '202036',
				'nama_program_studi' => $dt->nama_jurusan,
				'jenjang' => $dt->jenjang,
				'semester' => $dt->smt,
				'ipk' => $dt->ipk,
				'jumlah_sks_ditempuh' => $dt->total_sks,
				'provinsi_kota_kampus' => 'Provinsi Jambi - Kota Sungai Penuh',
				'alamat_domisili' => $dt->jln,
				'provinsi_kota_mahasiswa' => $dt->provinsi_mhs,
				);
			//result data
			array_push($response['results'],$row);
			}
	        echoResponse(200, $response,"json");
		} else {
			$response['status']['code'] = 404;
            $response['status']["message"] = "The requested resource doesn't exists";
            echoResponse(404, $response,"json");
		}

	} else {
		$response['status']['code'] = 422;
		$response['status']['message'] = $pg->getErrorMessage();
		echoResponse(422, $response,"json");
	}
	});

	//auth status

	$app->post('/mahasiswa',$read_auth, function() use ($app,$apiClass,$pg,$db2,$status_token) {

	 		$app = \Slim\Slim::getInstance();
	 		$request = $app->request();


	 		exit();
	 		
		$validation = array(
		"nim" => array(
              "type" => "no",
              "alias" => "nim",
              "value" => $request->post("nim"),
              "allownull" => "",
		),
		"nama" => array(
              "type" => "no",
              "alias" => "nama_mahasiswa",
              "value" => $request->post("nama_mahasiswa"),
              "allownull" => "",
		),
		);
	 		
		$data = array(
            "nim" => $request->post("nim"),
            "nama" => $request->post("nama_mahasiswa"),
		);

	 		$val = $apiClass->assert($validation);

	 		if (empty($apiClass->errors())) {
	 			
	 			$in = $db->insert('mahasiswa',$data);

	 			if ($in==true) {
	 			$id = $db->last_insert_id();
	 			$response['status']['code'] = 201;
                $response['status']["message"] = "mahasiswa created successfully";
                $response['status']["id"] = $id;
                echoResponse(201, $response,"json");
		 		} else {
					$response['status']['code'] = 422;
					$response['status']['message'] = $db->getErrorMessage();
					echoResponse(422, $response,"json");
		 		}
	 		} else {
					$response['status']['code'] = 422;
					foreach ($apiClass->errors() as $error) {
						$response['status']['message'] = $error;	
					}
					echoResponse(422, $response,"json");
	 		}

	});

	//auth status
	$update_auth = ($db->fetch_single_row('sys_services','nav_act','mahasiswa')->update_auth=="Y")?$authenticate('xml'):"noauth";

	//update mahasiswa
	$app->put('/mahasiswa/:id',$update_auth, function($id) use ($app,$db,$apiClass) {
	 		$app = \Slim\Slim::getInstance();
	 		$request = $app->request();
	 		
	 		$validation = array();
	 		$data = array();

	 		$data_stream = array();
			new lib\stream($data_stream);

	 		$_PUT = $data_stream['post'];
			$_FILES = $data_stream['file'];

	 		
        if (isset($_PUT["nim"])) {
          $nim_validation = array(
            "nim" => array(
              "type" => "no",
              "alias" => "nim",
              "value" => $_PUT["nim"],
              "allownull" => "",
        ));
        $nim_data =  array(
            "nim" => $_PUT["nim"]
        );
        $validation = array_merge($validation,$nim_validation);
        $data = array_merge($data,$nim_data);
        }
        if (isset($_PUT["nama_mahasiswa"])) {
          $nama_mahasiswa_validation = array(
            "nama_mahasiswa" => array(
              "type" => "no",
              "alias" => "nama_mahasiswa",
              "value" => $_PUT["nama_mahasiswa"],
              "allownull" => "",
        ));
        $nama_mahasiswa_data =  array(
            "nama" => $_PUT["nama_mahasiswa"]
        );
        $validation = array_merge($validation,$nama_mahasiswa_validation);
        $data = array_merge($data,$nama_mahasiswa_data);
        }

	 	      if (!empty($data)) {

	        if (!empty($validation)) {
	          $val = $apiClass->assert($validation);

	          if (empty($apiClass->errors())) {
	          	
	            $up = $db->update("mahasiswa",$data,"mhs_id",$id);

	            if ($up==true) {
	              $response["status"]["code"] = 200;
	                      $response["status"]["message"] = ucwords("mahasiswa")." Updated successfully";
	                      echoResponse(200, $response,"json");
	            } else {
	              $response["status"]["code"] = 422;
	              $response["status"]["message"] = $apiClass->pdo->getErrorMessage();
	              echoResponse(422, $response,"json");
	            }
	          } else {
	              $response["status"]["code"] = 422;
	              foreach ($apiClass->errors() as $error) {
	                $response["status"]["message"] = $error;  
	              }
	              echoResponse(422, $response,"json");
	          }
	        } else {
	            $up = $db->update("mahasiswa",$data,"$primary_key",$id);

	            if ($up==true) {
	              $response["status"]["code"] = 200;
	                      $response["status"]["message"] = ucwords("mahasiswa")." Updated successfully";
	                      echoResponse(200, $response,"json");
	            } else {
	              $response["status"]["code"] = 422;
	              $response["status"]["message"] = $db->getErrorMessage();
	              echoResponse(422, $response,"json");
	            }
	        }

	      } else {
	          $response["status"]["code"] = 422;
	                  $response["status"]["message"] = "Unprocessable Entity";
	                  echoResponse(422, $response,"json");
	      }
	});


//auth status
$delete_auth = ($db->fetch_single_row('sys_services','nav_act','mahasiswa')->delete_auth=="Y")?$authenticate('xml'):"noauth";

	//delete mahasiswa
	$app->delete('/mahasiswa/delete/:id',$delete_auth, function($id) use ($app,$db,$apiClass) {
			$single_data = $db->fetch_single_row("mahasiswa","mhs_id",$id);
			
	 		$up = $db->delete('mahasiswa','mhs_id',$id);

	 		if ($up==true) {
	 			$response['status']['code'] = 200;
                $response['status']["message"] = "mahasiswa Deleted successfully";
                echoResponse(200, $response,"json");
	 		} else {
				$response['status']['code'] = 422;
				$response['status']['message'] = $db->getErrorMessage();
				echoResponse(422, $response,"json");
	 		}

	});

	//search mahasiswa
	$app->get('/mahasiswa/search/:search',$read_auth, function($search) use ($app,$db,$pg,$apiClass) {
          $search_condition = $db->getRawWhereFilterForColumns($search, array('mahasiswa.nama','mahasiswa.nim','mahasiswa.tgl_lahir','mahasiswa.jk','mahasiswa.email','mahasiswa.no_hp','mahasiswa.id_agama','mahasiswa.status_pernikahan','mahasiswa.jln','mahasiswa.tgl_masuk_sp','mahasiswa.rt','mahasiswa.rw','mahasiswa.nm_dsn','mahasiswa.ds_kel','mahasiswa.kode_pos','mahasiswa.no_tel_rmh','mahasiswa.a_terima_kps','mahasiswa.no_kps','mahasiswa.stat_pd','mahasiswa.nm_ayah',));
          $search_condition = "where $search_condition";
	 	$data = $pg->query("select mahasiswa.nama,mahasiswa.nim,mahasiswa.tgl_lahir,mahasiswa.jk,mahasiswa.email,mahasiswa.no_hp,mahasiswa.id_agama,mahasiswa.status_pernikahan,mahasiswa.jln,mahasiswa.tgl_masuk_sp,mahasiswa.rt,mahasiswa.rw,mahasiswa.nm_dsn,mahasiswa.ds_kel,mahasiswa.kode_pos,mahasiswa.no_tel_rmh,mahasiswa.a_terima_kps,mahasiswa.no_kps,mahasiswa.stat_pd,mahasiswa.nm_ayah from mahasiswa  inner join tb_informasi on mahasiswa.mhs_id=tb_informasi.id $search_condition");
	 	if ($data==true) {
	 		$response['status'] = array();
	 		$response['results'] = array();
	 		foreach ($data as $dt) {
				//status code
				$response['status']['code'] = 200;
				$response['status']['message'] = "Ok";
				
		$row = array(
				'nama_mahasiswa' => $dt->nama,
				'nim' => $dt->nim,
				'tanggal_lahir' => $dt->tgl_lahir,
				'jenis_kelamin' => $dt->jk,
				'email' => $dt->email,
				'no_hp' => $dt->no_hp,
				'agama' => $dt->id_agama,
				'status_pernikahan' => $dt->status_pernikahan,
				'status_kemahasiswaan' => $dt->jln,
				'tanggal_masuk_kuliah' => $dt->tgl_masuk_sp,
				'nama_perguruan_tinggi' => $dt->rt,
				'kode_perguruan_tinggi' => $dt->rw,
				'nama_program_studi' => $dt->nm_dsn,
				'jenjang' => $dt->ds_kel,
				'semester' => $dt->kode_pos,
				'ipk' => $dt->no_tel_rmh,
				'jumlah_sks_ditempuh' => $dt->a_terima_kps,
				'provinsi_kota_kampus' => $dt->no_kps,
				'alamat_domisili' => $dt->stat_pd,
				'provinsi_kota_mahasiswa' => $dt->nm_ayah,
				);
				//result data
				array_push($response['results'],$row);	 			
	 		}
	 				//paginations link
		$response['paginations'] = array();
		$response['paginations']['self'] = $pg->api_current_uri($apiClass->uri_segment(0),$apiClass->uri_segment(2));
		$response['paginations']['first'] = $pg->api_first($apiClass->uri_segment(0),$apiClass->uri_segment(2));
		$response['paginations']['prev'] = $pg->api_prev($apiClass->uri_segment(0),$apiClass->uri_segment(2));
		$response['paginations']['next'] = $pg->api_next($apiClass->uri_segment(0),$apiClass->uri_segment(2));
		$response['paginations']['last'] = $pg->api_last($apiClass->uri_segment(0),$apiClass->uri_segment(2));
			echoResponse(200, $response,"json");
	 	} else {
			$response['status']['code'] = 422;
			$response['status']['message'] = $db->getErrorMessage();
			echoResponse(422, $response,"json");
	 	}
	});

	