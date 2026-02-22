<?php
	//doc route
	$app->get('/mega-sariah/doc',function() use ($db) {
		include "doc.php";
	});

	$status_token = $db->fetch_custom_single("SELECT enable_token_read,enable_token_create,enable_token_update,enable_token_delete,format_data,id_service FROM sys_services INNER JOIN sys_token ON sys_services.id=sys_token.id_service where nav_act=?",array("nav_act" => "mega-sariah"));
	
	//login action
	$app->post('/mega-sariah/login', function() use ($app,$db,$status_token) {
		auth_data($app,$db,$status_token->format_data);
	});
	
	$read_auth = ($status_token->enable_token_read=="Y")?$authenticate($status_token->format_data,$status_token->id_service,"read"):"noauth";

	//url route
	$app->get('/mega-sariah',$read_auth, function() use ($app,$apiClass,$pg,$status_token) {
		$data = $pg->query("select va_bank_mega.typeInq,va_bank_mega.nim,va_bank_mega.Gelombang,va_bank_mega.FormID,va_bank_mega.numBill,va_bank_mega.billAmount,view_simple_mhs_data.mulai_smt,view_simple_mhs_data.nama,view_simple_mhs_data.jurusan,view_simple_mhs_data.nama_fakultas from va_bank_mega  inner join view_simple_mhs_data on va_bank_mega.id=view_simple_mhs_data.nim");
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
        'typeInq' => $dt->typeInq,
        'nim' => $dt->nim,
        'Gelombang' => $dt->Gelombang,
        'FormID' => $dt->FormID,
        'numBill' => $dt->numBill,
        'billAmount' => $dt->billAmount,
        'TahunID' => $dt->mulai_smt,
        'Nama' => $dt->nama,
        'ProdiNama' => $dt->jurusan,
        'FakultasNama' => $dt->nama_fakultas,
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

	$app->get('/mega-sariah/:id',$read_auth, function($id) use ($app,$apiClass,$pg,$status_token) {
	$data = $pg->query("select va_bank_mega.typeInq,va_bank_mega.nim,va_bank_mega.Gelombang,va_bank_mega.FormID,va_bank_mega.numBill,va_bank_mega.billAmount,view_simple_mhs_data.mulai_smt,view_simple_mhs_data.nama,view_simple_mhs_data.jurusan,view_simple_mhs_data.nama_fakultas from va_bank_mega  inner join view_simple_mhs_data on va_bank_mega.id=view_simple_mhs_data.nim where id=?",array('id'=>$id));
	if ($data==true) {
		$response['status'] = array();

		if ($data->rowCount()>0) {
			$response['results'] = array();
			foreach ($data as $dt) {
			//status code
			$response['status']['code'] = 200;
			$response['status']['message'] = "Ok";
				
    $row = array(
        'typeInq' => $dt->typeInq,
        'nim' => $dt->nim,
        'Gelombang' => $dt->Gelombang,
        'FormID' => $dt->FormID,
        'numBill' => $dt->numBill,
        'billAmount' => $dt->billAmount,
        'TahunID' => $dt->mulai_smt,
        'Nama' => $dt->nama,
        'ProdiNama' => $dt->jurusan,
        'FakultasNama' => $dt->nama_fakultas,
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
	//post mega-sariah
	$app->post('/mega-sariah',$create_auth, function() use ($app,$db,$apiClass,$status_token) {
	 		$app = \Slim\Slim::getInstance();
	 		$request = $app->request();

	 		
	 		
    $validation = array(
    "nim" => array(
              "type" => "notEmpty",
              "alias" => "nim",
              "value" => $request->post("nim"),
              "allownull" => "no",
    ),
    "typeInq" => array(
              "type" => "no",
              "alias" => "typeInq",
              "value" => $request->post("typeInq"),
              "allownull" => "",
    ),
    "trxDateTime" => array(
              "type" => "no",
              "alias" => "trxDateTime",
              "value" => $request->post("trxDateTime"),
              "allownull" => "",
    ),
    "companyCode" => array(
              "type" => "no",
              "alias" => "companyCode",
              "value" => $request->post("companyCode"),
              "allownull" => "",
    ),
    "channeled" => array(
              "type" => "no",
              "alias" => "channeled",
              "value" => $request->post("channeled"),
              "allownull" => "",
    ),
    );
	 		
    $data = array(
            "nim" => $request->post("nim"),
            "typeInq" => $request->post("typeInq"),
            "trxDateTime" => $request->post("trxDateTime"),
            "companyCode" => $request->post("companyCode"),
            "channeled" => $request->post("channeled"),
    );

	 		$val = $apiClass->assert($validation);

	 		if (empty($apiClass->errors())) {
	 			
	 			$in = $db->insert('va_bank_mega',$data);

	 			if ($in==true) {
	 			$id = $db->last_insert_id();
	 			$response['status']['code'] = 201;
                $response['status']["message"] = "mega-sariah created successfully";
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

	//update mega-sariah
	$app->put('/mega-sariah/:id',$update_auth, function($id) use ($app,$db,$apiClass,$status_token) {
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
        if (isset($_PUT["typeInq"])) {
          $typeInq_validation = array(
            "typeInq" => array(
              "type" => "no",
              "alias" => "typeInq",
              "value" => $_PUT["typeInq"],
              "allownull" => "",
        ));
        $typeInq_data =  array(
            "typeInq" => $_PUT["typeInq"]
        );
        $validation = array_merge($validation,$typeInq_validation);
        $data = array_merge($data,$typeInq_data);
        }
        if (isset($_PUT["trxDateTime"])) {
          $trxDateTime_validation = array(
            "trxDateTime" => array(
              "type" => "no",
              "alias" => "trxDateTime",
              "value" => $_PUT["trxDateTime"],
              "allownull" => "",
        ));
        $trxDateTime_data =  array(
            "trxDateTime" => $_PUT["trxDateTime"]
        );
        $validation = array_merge($validation,$trxDateTime_validation);
        $data = array_merge($data,$trxDateTime_data);
        }
        if (isset($_PUT["companyCode"])) {
          $companyCode_validation = array(
            "companyCode" => array(
              "type" => "no",
              "alias" => "companyCode",
              "value" => $_PUT["companyCode"],
              "allownull" => "",
        ));
        $companyCode_data =  array(
            "companyCode" => $_PUT["companyCode"]
        );
        $validation = array_merge($validation,$companyCode_validation);
        $data = array_merge($data,$companyCode_data);
        }
        if (isset($_PUT["channeled"])) {
          $channeled_validation = array(
            "channeled" => array(
              "type" => "no",
              "alias" => "channeled",
              "value" => $_PUT["channeled"],
              "allownull" => "",
        ));
        $channeled_data =  array(
            "channeled" => $_PUT["channeled"]
        );
        $validation = array_merge($validation,$channeled_validation);
        $data = array_merge($data,$channeled_data);
        }

	 	      if (!empty($data)) {

	        if (!empty($validation)) {
	          $val = $apiClass->assert($validation);

	          if (empty($apiClass->errors())) {
	          	
	            $up = $db->update("va_bank_mega",$data,"id",$id);

	            if ($up==true) {
	              $response["status"]["code"] = 200;
	                      $response["status"]["message"] = ucwords("mega-sariah")." Updated successfully";
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
	            $up = $db->update("va_bank_mega",$data,"$primary_key",$id);

	            if ($up==true) {
	              $response["status"]["code"] = 200;
	                      $response["status"]["message"] = ucwords("mega-sariah")." Updated successfully";
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
	//delete mega-sariah
	$app->delete('/mega-sariah/delete/:id',$delete_auth, function($id) use ($app,$db,$apiClass,$status_token) {
			$single_data = $db->fetch_single_row("va_bank_mega","id",$id);
			
	 		$up = $db->delete('va_bank_mega','id',$id);

	 		if ($up==true) {
	 			$response['status']['code'] = 200;
                $response['status']["message"] = "mega-sariah Deleted successfully";
                echoResponse(200, $response,$status_token->format_data);
	 		} else {
				$response['status']['code'] = 422;
				$response['status']['message'] = $db->getErrorMessage();
				echoResponse(422, $response,$status_token->format_data);
	 		}

	});

	//search mega-sariah
	$app->get('/mega-sariah/search/:search',$read_auth, function($search) use ($app,$db,$pg,$apiClass,$status_token) {
          $search_condition = $db->getRawWhereFilterForColumns($search, array('va_bank_mega.typeInq','va_bank_mega.nim','va_bank_mega.Gelombang','va_bank_mega.FormID','va_bank_mega.numBill','va_bank_mega.billAmount','view_simple_mhs_data.mulai_smt','view_simple_mhs_data.nama','view_simple_mhs_data.jurusan','view_simple_mhs_data.nama_fakultas',));
          $search_condition = "where $search_condition";
	 	$data = $pg->query("select va_bank_mega.typeInq,va_bank_mega.nim,va_bank_mega.Gelombang,va_bank_mega.FormID,va_bank_mega.numBill,va_bank_mega.billAmount,view_simple_mhs_data.mulai_smt,view_simple_mhs_data.nama,view_simple_mhs_data.jurusan,view_simple_mhs_data.nama_fakultas from va_bank_mega  inner join view_simple_mhs_data on va_bank_mega.id=view_simple_mhs_data.nim $search_condition");
	 	if ($data==true) {
	 		$response['status'] = array();
	 		$response['results'] = array();
	 		foreach ($data as $dt) {
				//status code
				$response['status']['code'] = 200;
				$response['status']['message'] = "Ok";
				
    $row = array(
        'typeInq' => $dt->typeInq,
        'nim' => $dt->nim,
        'Gelombang' => $dt->Gelombang,
        'FormID' => $dt->FormID,
        'numBill' => $dt->numBill,
        'billAmount' => $dt->billAmount,
        'TahunID' => $dt->mulai_smt,
        'Nama' => $dt->nama,
        'ProdiNama' => $dt->jurusan,
        'FakultasNama' => $dt->nama_fakultas,
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

	