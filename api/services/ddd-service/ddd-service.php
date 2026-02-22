<?php
	//doc route
	$app->get('/ddd-service/doc',function() use ($db) {
		include "doc.php";
	});

	$status_token = $db->fetchCustomSingle("SELECT enable_token_read,enable_token_create,enable_token_update,enable_token_delete,format_data,id_service FROM sys_services INNER JOIN sys_token ON sys_services.id=sys_token.id_service where nav_act=?",array("nav_act" => "ddd-service"));
	
	//login action
	$app->post('/ddd-service/login', function() use ($app,$db,$status_token) {
		auth_data($app,$db,$status_token->format_data);
	});
	
	$read_auth = ($status_token->enable_token_read=="Y")?$authenticate($status_token->format_data,$status_token->id_service,"read"):"noauth";

	//url route
	$app->get('/ddd-service',$read_auth, function() use ($app,$apiClass,$pg,$status_token) {
		$data = $pg->query("select view_dosen.nip,view_dosen.nama_dosen from view_dosen");
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
				'nip' => $dt->nip,
				'nama_dosen' => $dt->nama_dosen,
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

	$app->get('/ddd-service/:id',$read_auth, function($id) use ($app,$apiClass,$pg,$status_token) {
	$data = $pg->query("select view_dosen.nip,view_dosen.nama_dosen from view_dosen where =?",array(''=>$id));
	if ($data==true) {
		$response['status'] = array();

		if ($data->rowCount()>0) {
			$response['results'] = array();
			foreach ($data as $dt) {
			//status code
			$response['status']['code'] = 200;
			$response['status']['message'] = "Ok";
				
		$row = array(
				'nip' => $dt->nip,
				'nama_dosen' => $dt->nama_dosen,
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
	//post ddd-service
	$app->post('/ddd-service',$create_auth, function() use ($app,$db,$apiClass,$status_token) {
	 		$app = \Slim\Slim::getInstance();
	 		$request = $app->request();

	 		
	 		
		$validation = array(
		"nip" => array(
              "type" => "notEmpty",
              "alias" => "nip",
              "value" => $request->post("nip"),
              "allownull" => "no",
		),
		"nama_dosen" => array(
              "type" => "notEmpty",
              "alias" => "nama_dosen",
              "value" => $request->post("nama_dosen"),
              "allownull" => "no",
		),
		);
	 		
		$data = array(
            "nip" => $request->post("nip"),
            "nama_dosen" => $request->post("nama_dosen"),
		);

	 		$val = $apiClass->assert($validation);

	 		if (empty($apiClass->errors())) {
	 			
	 			$in = $db->insert('view_dosen',$data);

	 			if ($in==true) {
	 			$id = $db->getLastInsertId();
	 			$response['status']['code'] = 201;
                $response['status']["message"] = "ddd-service created successfully";
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

	//update ddd-service
	$app->put('/ddd-service/:id',$update_auth, function($id) use ($app,$db,$apiClass,$status_token) {
	 		$app = \Slim\Slim::getInstance();
	 		$request = $app->request();
	 		
	 		$validation = array();
	 		$data = array();

	 		$data_stream = array();
			new lib\stream($data_stream);

	 		$_PUT = $data_stream['post'];
			$_FILES = $data_stream['file'];

	 		
        if (isset($_PUT["nip"])) {
          $nip_validation = array(
            "nip" => array(
              "type" => "notEmpty",
              "alias" => "nip",
              "value" => $_PUT["nip"],
              "allownull" => "no",
        ));
        $nip_data =  array(
            "nip" => $_PUT["nip"]
        );
        $validation = array_merge($validation,$nip_validation);
        $data = array_merge($data,$nip_data);
        }
        if (isset($_PUT["nama_dosen"])) {
          $nama_dosen_validation = array(
            "nama_dosen" => array(
              "type" => "notEmpty",
              "alias" => "nama_dosen",
              "value" => $_PUT["nama_dosen"],
              "allownull" => "no",
        ));
        $nama_dosen_data =  array(
            "nama_dosen" => $_PUT["nama_dosen"]
        );
        $validation = array_merge($validation,$nama_dosen_validation);
        $data = array_merge($data,$nama_dosen_data);
        }

	 	      if (!empty($data)) {

	        if (!empty($validation)) {
	          $val = $apiClass->assert($validation);

	          if (empty($apiClass->errors())) {
	          	
	            $up = $db->update("view_dosen",$data,"",$id);

	            if ($up==true) {
	              $response["status"]["code"] = 200;
	                      $response["status"]["message"] = ucwords("ddd-service")." Updated successfully";
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
	            $up = $db->update("view_dosen",$data,"$primary_key",$id);

	            if ($up==true) {
	              $response["status"]["code"] = 200;
	                      $response["status"]["message"] = ucwords("ddd-service")." Updated successfully";
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
	//delete ddd-service
	$app->delete('/ddd-service/delete/:id',$delete_auth, function($id) use ($app,$db,$apiClass,$status_token) {
			$single_data = $db->fetchSingleRow("view_dosen","",$id);
			
	 		$up = $db->delete('view_dosen','',$id);

	 		if ($up==true) {
	 			$response['status']['code'] = 200;
                $response['status']["message"] = "ddd-service Deleted successfully";
                echoResponse(200, $response,$status_token->format_data);
	 		} else {
				$response['status']['code'] = 422;
				$response['status']['message'] = $db->getErrorMessage();
				echoResponse(422, $response,$status_token->format_data);
	 		}

	});

	//search ddd-service
	$app->get('/ddd-service/search/:search',$read_auth, function($search) use ($app,$db,$pg,$apiClass,$status_token) {
          $search_condition = $db->getRawWhereFilterForColumns($search, array('view_dosen.nip','view_dosen.nama_dosen',));
          $search_condition = "where $search_condition";
	 	$data = $pg->query("select view_dosen.nip,view_dosen.nama_dosen from view_dosen $search_condition");
	 	if ($data==true) {
	 		$response['status'] = array();
	 		$response['results'] = array();
	 		foreach ($data as $dt) {
				//status code
				$response['status']['code'] = 200;
				$response['status']['message'] = "Ok";
				
		$row = array(
				'nip' => $dt->nip,
				'nama_dosen' => $dt->nama_dosen,
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

	