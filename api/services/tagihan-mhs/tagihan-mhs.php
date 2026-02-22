<?php
	//doc route
	$app->get('/tagihan-mhs/doc',function() use ($db) {
		include "doc.php";
	});

	$status_token = $db->fetch_custom_single("SELECT enable_token_read,enable_token_create,enable_token_update,enable_token_delete,format_data,id_service FROM sys_services INNER JOIN sys_token ON sys_services.id=sys_token.id_service where nav_act=?",array("nav_act" => "tagihan-mhs"));
	
	//login action
	$app->post('/tagihan-mhs/login', function() use ($app,$db,$status_token) {
		auth_data($app,$db,$status_token->format_data);
	});

//auth status
	$create_auth = ($status_token->enable_token_create=="Y")?$authenticate($status_token->format_data,$status_token->id_service,"create"):"noauth";
	//post mega-syariah
	$app->post('/tagihan-mhs/inquiry',$create_auth, function() use ($app,$db,$apiClass,$status_token) {
	 		$app = \Slim\Slim::getInstance();
	 		$request = $app->request();
	 		
    $validation = array(
    "nim" => array(
              "type" => "notEmpty",
              "alias" => "nim",
              "value" => $request->post("nim"),
              "allownull" => "no",
    )
    );

	 		$val = $apiClass->assert($validation);

	 	//	var_dump($apiClass->errors());

	 		if (empty($apiClass->errors())) {

	 			  //check mhs
	 			  $mhs = $db->fetch_custom_single("select mahasiswa.nim,mahasiswa.mulai_smt,mahasiswa.nama,jurusan.nama_jur,fakultas.nama_resmi
from mahasiswa 
inner join keu_tagihan_mahasiswa using(nim)
inner join jurusan on mahasiswa.jur_kode=jurusan.kode_jur
inner join fakultas on jurusan.fak_kode=fakultas.kode_fak
where right(mahasiswa.nim,10)=?",array('nim'=> substr($request->post("nim"), -10)));
	 			  $response = array();
	 			  if ($mhs) {
						$data = $db->query("select keu_tagihan_mahasiswa.nim,mahasiswa.mulai_smt,mahasiswa.nama,jurusan.nama_jur,fakultas.nama_resmi,
						tanggal_awal,
						tanggal_akhir,
						keu_tagihan_mahasiswa.id as billID,keu_tagihan.id as billNameID,
						kode_tagihan as billName,potongan,keu_tagihan.nominal_tagihan-potongan as billAmount,nominal_tagihan,0 as billAmountBayar,0 as billAmountPay,
						keu_tagihan_mahasiswa.periode as TahunID from keu_tagihan_mahasiswa
						inner join keu_tagihan on keu_tagihan_mahasiswa.id_tagihan_prodi=keu_tagihan.id
						inner join mahasiswa on keu_tagihan_mahasiswa.nim=mahasiswa.nim
						inner join jurusan on mahasiswa.jur_kode=jurusan.kode_jur
						inner join fakultas on jurusan.fak_kode=fakultas.kode_fak
						where keu_tagihan_mahasiswa.id not in(select id_keu_tagihan_mhs from keu_bayar_mahasiswa where is_removed=0)
						and is_aktif=1 
						and right(keu_tagihan_mahasiswa.nim,10)=?
						order by keu_tagihan_mahasiswa.periode desc,tanggal_awal desc
						",
						array(
								'nim'=>$request->post("nim")
							)
					);

				    $row = array(
				        'nim' => $request->post("nim"),
				        'angkatan' => $mhs->mulai_smt,
				        'Nama' => $mhs->nama,
				        'ProdiNama' => $mhs->nama_jur,
				        'FakultasNama' => $mhs->nama_resmi,
				       // 'Gelombang' => 1,
				       // 'FormID' => 'REG1',
				        );


				//if ($dt->nim!="") {
						
						if ($data->rowCount()>0) {
							$response_billDetails['billDetails'] = array();

						$bill_amount = 0;
						$bill_amountOriginal = 0;
						$index = 0;
						foreach ($data as $dt) {
						if ($index==0) {
							$tanggal_awal = $dt->tanggal_awal;
							$tanggal_akhir = $dt->tanggal_akhir;
						}
						$bill_amount += $dt->billAmount;
						$bill_amountOriginal += $dt->nominal_tagihan;

				    $bill_detail = array(
				        'billID' => $dt->billID,
				        'billNameID' => $dt->billNameID,
				        'billName' => $dt->billName,
				        'billAmountOriginal' => $dt->nominal_tagihan,
				        'potongan' => $dt->potongan,
				        'billAmount' => $dt->billAmount,
				        'billAmountBayar' => $dt->billAmountBayar,
				        'billAmountPay' => $dt->billAmountPay,
				        'periode' => $dt->TahunID
				        );
							//result data
									$index++;
									array_push($response_billDetails['billDetails'],$bill_detail);

								}
								$curent_date = date('Y-m-d H:i:s');
								if ( $curent_date >= $tanggal_awal && $curent_date <= $tanggal_akhir) {
										
										$response = array_merge($response_billDetails,$response);
										$row['jmlTagihanOriginal'] = $bill_amountOriginal;
										$row['jmlTagihan'] = $bill_amount;
										$row['numBill'] = $index;
										$row['errorCode'] = '00';
					    			$row['statusDescription'] = 'Success';
					    			$insert_mega_history['error_desc'] = 'Success';
								} else {
										$row['jmlTagihan'] = 0;
										$row['numBill'] = 0;
										$row['errorCode'] = '17';
					    			$row['statusDescription'] = 'Payment period is expired';
					    			$insert_mega_history['error_desc'] = 'Payment period is expired';
								}
					    } else {
								$row['jmlTagihan'] = 0;
								$row['numBill'] = 0;
					    	$response['errorCode'] = '09';
					    	$response['statusDescription'] = 'Bill is already paid';
					    	$insert_mega_history['error_desc'] = 'Bill is already paid';
					    }

							$response = array_merge($row,$response);
				        echoResponse(200, $response,$status_token->format_data);

					    //$db->insert('va_bank_mega',$insert_mega_history);
	 			  } else {
					    	$response['errorCode'] = '04';
					    	$response['statusDescription'] = 'Data is not availabled';
					    	//$response = array_merge($row,$response);
					    	echoResponse(200, $response,$status_token->format_data);
					    }
	 		} else {
					//$response['status']['code'] = 422;
					/*foreach ($apiClass->errors() as $error) {
						$response['status']['message'] = $error;	
					}*/
		    	$response['errorCode'] = '04';
		    	$response['statusDescription'] = 'Data is not availables';
					echoResponse(422, $response,$status_token->format_data);
	 		}

	});

	
	$read_auth = ($status_token->enable_token_read=="Y")?$authenticate($status_token->format_data,$status_token->id_service,"read"):"noauth";

	//url route
	$app->get('/tagihan-mhss',$read_auth, function() use ($app,$apiClass,$pg,$status_token) {
		$data = $pg->query("select keu_tagihan_mahasiswa.nim,keu_tagihan_mahasiswa.periode from keu_tagihan_mahasiswa");
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
			
    $row = array(
        'nim' => $dt->nim,
        'periode' => $dt->periode,
        );
		//result data
		array_push($response['results'],$row);

		}
		//paginations link
		$response['paginations'] = array();
		$response['paginations']['self'] = $pg->api_current_uri($apiClass->uri_segment(0));
		$response['paginations']['first'] = $pg->api_first($apiClass->uri_segment(0));
		$response['paginations']['prev'] = $pg->api_prev($apiClass->uri_segment(0));
		$response['paginations']['next'] = $pg->api_next($apiClass->uri_segment(0));
		$response['paginations']['last'] = $pg->api_last($apiClass->uri_segment(0));
		
        echoResponse(200, $response,$status_token->format_data);
		} else {
			$response['status']['code'] = 422;
			$response['status']['message'] = $pg->getErrorMessage();
			echoResponse(422, $response,$status_token->format_data);
		}
	});

	$app->get('/tagihan-mhs/:id',$read_auth, function($id) use ($app,$apiClass,$pg,$status_token) {
	$data = $pg->query("select keu_tagihan_mahasiswa.nim,keu_tagihan_mahasiswa.periode from keu_tagihan_mahasiswa where id=?",array('id'=>$id));
	if ($data==true) {
		$response['status'] = array();

		if ($data->rowCount()>0) {
			$response['results'] = array();
			foreach ($data as $dt) {
			//status code
			$response['status']['code'] = 200;
			$response['status']['message'] = "Ok";
				
    $row = array(
        'nim' => $dt->nim,
        'periode' => $dt->periode,
        );
			//result data
			array_push($response['results'],$row);
			}
	        echoResponse(200, $response,$status_token->format_data);
		} else {
			$response['status']['code'] = 404;
            $response['status']["message"] = "The requested resource doesn't exists";
            echoResponse(404, $response,$status_token->format_data);
		}

	} else {
		$response['status']['code'] = 422;
		$response['status']['message'] = $pg->getErrorMessage();
		echoResponse(422, $response,$status_token->format_data);
	}
	});

	//auth status
	$create_auth = ($status_token->enable_token_create=="Y")?$authenticate($status_token->format_data,$status_token->id_service,"create"):"noauth";
	//post tagihan-mhs
	$app->post('/tagihan-mhs',$create_auth, function() use ($app,$db,$apiClass,$status_token) {
	 		$app = \Slim\Slim::getInstance();
	 		$request = $app->request();

	 		
	 		
    $validation = array(
    "nim" => array(
              "type" => "notEmpty",
              "alias" => "nim",
              "value" => $request->post("nim"),
              "allownull" => "no",
    ),
    );
	 		
    $data = array(
            "nim" => $request->post("nim"),
    );

	 		$val = $apiClass->assert($validation);

	 		if (empty($apiClass->errors())) {
	 			
	 			$in = $db->insert('keu_tagihan_mahasiswa',$data);

	 			if ($in==true) {
	 			$id = $db->last_insert_id();
	 			$response['status']['code'] = 201;
                $response['status']["message"] = "tagihan-mhs created successfully";
                $response['status']["id"] = $id;
                echoResponse(201, $response,$status_token->format_data);
		 		} else {
					$response['status']['code'] = 422;
					$response['status']['message'] = $db->getErrorMessage();
					echoResponse(422, $response,$status_token->format_data);
		 		}
	 		} else {
					$response['status']['code'] = 422;
					foreach ($apiClass->errors() as $error) {
						$response['status']['message'] = $error;	
					}
					echoResponse(422, $response,$status_token->format_data);
	 		}

	});

	//auth status
	$update_auth = ($status_token->enable_token_update=="Y")?$authenticate($status_token->format_data,$status_token->id_service,"update"):"noauth";

	//update tagihan-mhs
	$app->put('/tagihan-mhs/:id',$update_auth, function($id) use ($app,$db,$apiClass,$status_token) {
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
              "type" => "notEmpty",
              "alias" => "nim",
              "value" => $_PUT["nim"],
              "allownull" => "no",
        ));
        $nim_data =  array(
            "nim" => $_PUT["nim"]
        );
        $validation = array_merge($validation,$nim_validation);
        $data = array_merge($data,$nim_data);
        }

	 	      if (!empty($data)) {

	        if (!empty($validation)) {
	          $val = $apiClass->assert($validation);

	          if (empty($apiClass->errors())) {
	          	
	            $up = $db->update("keu_tagihan_mahasiswa",$data,"id",$id);

	            if ($up==true) {
	              $response["status"]["code"] = 200;
	                      $response["status"]["message"] = ucwords("tagihan-mhs")." Updated successfully";
	                      echoResponse(200, $response,$status_token->format_data);
	            } else {
	              $response["status"]["code"] = 422;
	              $response["status"]["message"] = $apiClass->pdo->getErrorMessage();
	              echoResponse(422, $response,$status_token->format_data);
	            }
	          } else {
	              $response["status"]["code"] = 422;
	              foreach ($apiClass->errors() as $error) {
	                $response["status"]["message"] = $error;  
	              }
	              echoResponse(422, $response,$status_token->format_data);
	          }
	        } else {
	            $up = $db->update("keu_tagihan_mahasiswa",$data,"$primary_key",$id);

	            if ($up==true) {
	              $response["status"]["code"] = 200;
	                      $response["status"]["message"] = ucwords("tagihan-mhs")." Updated successfully";
	                      echoResponse(200, $response,$status_token->format_data);
	            } else {
	              $response["status"]["code"] = 422;
	              $response["status"]["message"] = $db->getErrorMessage();
	              echoResponse(422, $response,$status_token->format_data);
	            }
	        }

	      } else {
	          $response["status"]["code"] = 422;
	                  $response["status"]["message"] = "Unprocessable Entity";
	                  echoResponse(422, $response,$status_token->format_data);
	      }
	});


	//auth status
	$delete_auth = ($status_token->enable_token_delete=="Y")?$authenticate($status_token->format_data,$status_token->id_service,"delete"):"noauth";
	//delete tagihan-mhs
	$app->delete('/tagihan-mhs/delete/:id',$delete_auth, function($id) use ($app,$db,$apiClass,$status_token) {
			$single_data = $db->fetch_single_row("keu_tagihan_mahasiswa","id",$id);
			
	 		$up = $db->delete('keu_tagihan_mahasiswa','id',$id);

	 		if ($up==true) {
	 			$response['status']['code'] = 200;
                $response['status']["message"] = "tagihan-mhs Deleted successfully";
                echoResponse(200, $response,$status_token->format_data);
	 		} else {
				$response['status']['code'] = 422;
				$response['status']['message'] = $db->getErrorMessage();
				echoResponse(422, $response,$status_token->format_data);
	 		}

	});

	//search tagihan-mhs
	$app->get('/tagihan-mhs/search/:search',$read_auth, function($search) use ($app,$db,$pg,$apiClass,$status_token) {
          $search_condition = $db->getRawWhereFilterForColumns($search, array('keu_tagihan_mahasiswa.nim','keu_tagihan_mahasiswa.periode',));
          $search_condition = "where $search_condition";
	 	$data = $pg->query("select keu_tagihan_mahasiswa.nim,keu_tagihan_mahasiswa.periode from keu_tagihan_mahasiswa $search_condition");
	 	if ($data==true) {
	 		$response['status'] = array();
	 		$response['results'] = array();
	 		foreach ($data as $dt) {
				//status code
				$response['status']['code'] = 200;
				$response['status']['message'] = "Ok";
				
    $row = array(
        'nim' => $dt->nim,
        'periode' => $dt->periode,
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
			echoResponse(200, $response,$status_token->format_data);
	 	} else {
			$response['status']['code'] = 422;
			$response['status']['message'] = $db->getErrorMessage();
			echoResponse(422, $response,$status_token->format_data);
	 	}
	});

	