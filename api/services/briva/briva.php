<?php
   // include 'file';
    $status_token = $db->fetch_custom_single("SELECT enable_token_read,enable_token_create,enable_token_update,enable_token_delete,format_data,id_service FROM sys_services INNER JOIN sys_token ON sys_services.id=sys_token.id_service where nav_act=?",array("nav_act" => "mega-sariah"));
	$app->post('/briva/create',function() use ($app,$db,$status_token) {
			//$key = $_POST['key'];
			$token='';
			if (array_key_exists('token', $_POST)) {   
				$token = $_POST['token'];  
			} 
			
			//$key = $id;
			
			if (($token=='2y10bJ09e9jzVxNjKe8wis8eIgIUSQi0rrgQGmck313jL0mNJK9G')) {
				$data = array('no_briva' => $_POST['no_briva'],  
						      'nama'     => $_POST['nama'], 
						      'nominal'  => $_POST['nominal'],
						      'ket'      => $_POST['ket']);
				$res = createBriva($data); 
				//print_r($res);
				$response['status']['code'] = 200; 
				//$response['jml_data'] = 0;
				$response['status']['message'] = $res;
				echoResponse(200, $res,'json');
			}else{
				$response['status']['code'] = 422;
				$response['jml_data'] = 0;
				$response['status']['message'] = "Invalid / empty Token"; 
				echoResponse(422, $response,'json');
			}
			
	});

	$app->post('/briva/update',function() use ($app,$db,$status_token) {
			//$key = $_POST['key'];
			$token='';
			if (array_key_exists('token', $_POST)) {    
				$token = $_POST['token'];  
			} 
			
			//$key = $id;
			//echo $status_token->format_data;
			
			if (($token=='2y10bJ09e9jzVxNjKe8wis8eIgIUSQi0rrgQGmck313jL0mNJK9G')) {
				$no_briva = $_POST['no_briva'];
				$res = getStatusBriva($no_briva);
				//print_r($res);
				$response['status']['code'] = 200; 
				//$response['jml_data'] = 0;
				$response['status']['message'] = $res;
				echoResponse(200, $response,'json');
			}else{
				$response['status']['code'] = 422;
				$response['jml_data'] = 0;
				$response['status']['message'] = "Invalid / empty Token"; 
				echoResponse(422, $response,'json');
			}
			
	});

	$app->post('/briva/delete',function() use ($app,$db,$status_token) {
			//$key = $_POST['key'];
			$token='';
			if (array_key_exists('token', $_POST)) {     
				$token = $_POST['token'];  
			} 
			
			//$key = $id;
			//echo $status_token->format_data;
			
			if (($token=='2y10bJ09e9jzVxNjKe8wis8eIgIUSQi0rrgQGmck313jL0mNJK9G')) {
				$no_briva = $_POST['no_briva'];
				$res = deleteBriva($no_briva);
				//print_r($res);
				$response['status']['code'] = 200; 
				//$response['jml_data'] = 0;
				$response['status']['message'] = $res;
				echoResponse(200, $res,'json');
			}else{
				$response['status']['code'] = 422;
				$response['jml_data'] = 0;
				$response['status']['message'] = "Invalid / empty Token"; 
				echoResponse(422, $response,'json');
			}
			
	}); 

	$app->get('/briva/delete',function() use ($app,$db,$status_token) {
			//$key = $_POST['key'];
			$token='';
			if (array_key_exists('token', $_GET)) {     
				$token = $_GET['token'];  
			} 
			
			//$key = $id;
			//echo $status_token->format_data;
			
			if (($token=='2y10bJ09e9jzVxNjKe8wis8eIgIUSQi0rrgQGmck313jL0mNJK9G')) {
				$no_briva = $_GET['no_briva'];
				$res = deleteBriva($no_briva);
				//print_r($res);
				$response['status']['code'] = 200; 
				//$response['jml_data'] = 0;
				$response['status']['message'] = $res;
				echoResponse(200, $res,'json');
			}else{
				$response['status']['code'] = 422;
				$response['jml_data'] = 0;
				$response['status']['message'] = "Invalid / empty Token"; 
				echoResponse(422, $response,'json');
			}
			
	}); 

	$app->post('/briva/get_status',function() use ($app,$db,$status_token) {
			//$key = $_POST['key'];
			$token='';
			if (array_key_exists('token', $_POST)) {    
				$token = $_POST['token'];  
			} 
			
			//$key = $id;
			//echo $status_token->format_data;
			
			if (($token=='2y10bJ09e9jzVxNjKe8wis8eIgIUSQi0rrgQGmck313jL0mNJK9G')) {
				$no_briva = $_POST['no_briva'];
				$res = getStatusBriva($no_briva);
				//print_r($res);
				$response['status']['code'] = 200; 
				//$response['jml_data'] = 0;
				$response['status']['message'] = $res;
				echoResponse(200, $res,'json');
			}else{
				$response['status']['code'] = 422;
				$response['jml_data'] = 0;
				$response['status']['message'] = "Invalid / empty Token"; 
				echoResponse(422, $response,'json');
			}
			
	});

	$app->get('/briva/get_status',function() use ($app,$db,$status_token) {
			//$key = $_POST['key'];
			$token='';
			if (array_key_exists('token', $_GET)) {     
				$token = $_GET['token'];  
			} 
			
			//$key = $id;
			//echo $status_token->format_data;
			
			if (($token=='2y10bJ09e9jzVxNjKe8wis8eIgIUSQi0rrgQGmck313jL0mNJK9G')) {
				$no_briva = $_GET['no_briva'];
				$res = getStatusBriva($no_briva);
				//print_r($res);
				$response['status']['code'] = 200; 
				//$response['jml_data'] = 0;
				$response['status']['message'] = $res;
				echoResponse(200, $res,'json');
			}else{
				$response['status']['code'] = 422;
				$response['jml_data'] = 0;
				$response['status']['message'] = "Invalid / empty Token"; 
				echoResponse(422, $response,'json');
			}
			
	});

	$app->get('/briva/get_report',function() use ($app,$db,$status_token) {
			//$key = $_POST['key'];
			$token='';
			$tgl_awal = $_GET['tgl_awal']; 
			$tgl_akhir = $_GET['tgl_akhir'];
			if (array_key_exists('token', $_GET)) {     
				$token = $_GET['token'];  
			} 
			
			//$key = $id;
			//echo $status_token->format_data;
			
			if (($token=='2y10bJ09e9jzVxNjKe8wis8eIgIUSQi0rrgQGmck313jL0mNJK9G')) {
				$no_briva = $_GET['no_briva'];
				$res = get_report_briva($tgl_awal,$tgl_akhir);
				//print_r($res);
				$response['status']['code'] = 200; 
				//$response['jml_data'] = 0;
				$response['status']['message'] = $res;
				echoResponse(200, $res,'json');
			}else{
				$response['status']['code'] = 422;
				$response['jml_data'] = 0;
				$response['status']['message'] = "Invalid / empty Token"; 
				echoResponse(422, $response,'json');
			}
			
	});

	
?>