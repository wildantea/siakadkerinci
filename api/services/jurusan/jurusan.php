<?php
    $status_token = $db->fetch_custom_single("SELECT enable_token_read,enable_token_create,enable_token_update,enable_token_delete,format_data,id_service FROM sys_services INNER JOIN sys_token ON sys_services.id=sys_token.id_service where nav_act=?",array("nav_act" => "mega-sariah"));
	$app->post('/jurusan',function() use ($app,$db,$status_token) {
	$key = $_POST['key'];
	$k = explode(',', $key);
    foreach ($k as $key => $value) {
    	$jur[]= "'$value'";
    }
    $list_jur = implode(",", $jur);
	$token='';
	if (array_key_exists('token', $_POST)) {
		$token = $_POST['token'];  
	} 
	
	//$key = $id;
	
	if (($token=='2y10bJ09e9jzVxNjKe8wis8eIgIUSQi0rrgQGmck313jL0mNJK9G')) {
		$data = $db->query("select  j.kode_jur as kode_jurusan, nama_jur as jurusan, je.jenjang,f.nama_resmi as fakultas
from jurusan j join jenjang_pendidikan je on je.id_jenjang=j.id_jenjang
join fakultas f on f.kode_fak=j.fak_kode
where je.jenjang in ($list_jur) ");
		if ($data==true) {
				$response['status'] = array();


				if ($data->rowCount()>0) {
					$response['results'] = array();
					$response['jml_data'] = $data->rowCount();
					foreach ($data as $dt) {
					//status code
					$response['status']['code'] = 200;
					$response['status']['message'] = "Ok";
		
				
						
				$row = array(
						'kode_jurusan' => $dt->kode_jurusan,
						'nama_jurusan' => $dt->jurusan,
						'jenjang' => $dt->jenjang,
						'fakultas' => $dt->fakultas
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