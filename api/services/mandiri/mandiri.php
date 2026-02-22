<?php
	//doc route
	$app->get('/mandiri/doc',function() use ($db) {
		include "doc.php";
	});

	$status_token = $db->fetch_custom_single("SELECT enable_token_read,enable_token_create,enable_token_update,enable_token_delete,format_data,id_service FROM sys_services INNER JOIN sys_token ON sys_services.id=sys_token.id_service where nav_act=?",array("nav_act" => "mandiri"));
	
	//login action
	$app->post('/mandiri/login', function() use ($app,$db,$status_token) {
		auth_data($app,$db,$status_token->format_data);
	});


	//auth status
	$create_auth = ($status_token->enable_token_create=="Y")?$authenticate($status_token->format_data,$status_token->id_service,"create"):"noauth";
	//post mega-syariah
	$app->post('/mandiri/inquiry',$create_auth, function() use ($app,$db,$apiClass,$status_token) {
	 		$app = \Slim\Slim::getInstance();
	 		$request = $app->request();
	 		
			 $json = $app->request->getBody();
			 $data_decode = json_decode($json, true); 
		 // echo $data['InquiryRequest']->billKey1;
		 //print_r($data['InquiryRequest']);
		 
			 //dump($data); 
			 $nim_mahasiswa_res = $data_decode['InquiryRequest']['billKey1'];
			 $nim_mahasiswa = substr($nim_mahasiswa_res,5,20);

    $validation = array(
    "billKey1" => array(
              "type" => "notEmpty",
              "alias" => "billKey1",
              "value" => $nim_mahasiswa,
              "allownull" => "no",
    ),

/*     "trxDateTime" => array(
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
    ), */
    );

	 		$val = $apiClass->assert($validation);
			 $array_log = array();

	 		if (empty($apiClass->errors())) {

	 			  //check mhs
	 			  $mhs = $db->fetch_custom_single("select mahasiswa.nim,mahasiswa.mulai_smt,mahasiswa.nama,jurusan.nama_jur,id_jenjang,fakultas.nama_resmi
from mahasiswa 
inner join keu_tagihan_mahasiswa using(nim)
inner join jurusan on mahasiswa.jur_kode=jurusan.kode_jur
inner join fakultas on jurusan.fak_kode=fakultas.kode_fak
where mahasiswa.nim=? or right(mahasiswa.nim,10)='".$nim_mahasiswa."'",array('nim'=>$nim_mahasiswa));
	 			  $response = array();
				   $array_log = array(
					'data_json' => $json,
					'type_request' => 1,
					'date_created' => date('Y-m-d H:i:s')
				);
	 			  if ($mhs) {
						$data = $db->query("select keu_tagihan_mahasiswa.nim,mahasiswa.mulai_smt,mahasiswa.nama,jurusan.nama_jur,fakultas.nama_resmi,
						tanggal_awal,
						tanggal_akhir,
						keu_tagihan_mahasiswa.id as billID,keu_tagihan.id as billNameID,
						keu_jenis_tagihan.nama_tagihan as billName,keu_jenis_tagihan.kode_tagihan as billShortName,keu_tagihan.nominal_tagihan-potongan as billAmount,0 as billAmountBayar,0 as billAmountPay,
						keu_tagihan_mahasiswa.periode as TahunID from keu_tagihan_mahasiswa
						inner join keu_tagihan on keu_tagihan_mahasiswa.id_tagihan_prodi=keu_tagihan.id
						inner join keu_jenis_tagihan on keu_tagihan.kode_tagihan=keu_jenis_tagihan.kode_tagihan
						inner join mahasiswa on keu_tagihan_mahasiswa.nim=mahasiswa.nim
						inner join jurusan on mahasiswa.jur_kode=jurusan.kode_jur
						inner join fakultas on jurusan.fak_kode=fakultas.kode_fak
						where keu_tagihan_mahasiswa.id not in(select id_keu_tagihan_mhs from keu_bayar_mahasiswa where is_removed=0 and id_keu_tagihan_mhs is not null)
						and is_aktif=1 
						and (keu_tagihan_mahasiswa.nim=? or right(keu_tagihan_mahasiswa.nim,10)='".$nim_mahasiswa."') and now() between tanggal_awal and tanggal_akhir
						and keu_tagihan_mahasiswa.nim in(select nim_mhs from keu_keranjang_va inner join keu_keranjang_va_detail using(no_kwitansi) where nim_mhs='".$nim_mahasiswa."' and is_lunas='N' and is_active='Y' and exp_date > now())
						order by keu_tagihan_mahasiswa.periode desc,tanggal_awal desc
						",
						array(
								'nim'=> $nim_mahasiswa
							)
					);

				    $row = array(
				        //'language' => '00',
				        'billInfo1' => $nim_mahasiswa_res,
						'billInfo2' => $mhs->nama,
				        'billInfo3' => $mhs->nama_jur.' '.$mhs->mulai_smt
				        //'ProdiNama' => $mhs->nama_jur,
				        //'FakultasNama' => $mhs->nama_resmi,
				        //'Gelombang' => 1,
				        //'FormID' => 'REG1',
				        );
						/* if ($mhs->mulai_smt=='20221' && $mhs->id_jenjang=='30') {
							$row['nim'] = substr($mhs->nim,4,20);
						}
						 */

				//if ($dt->nim!="") {
						
						if ($data->rowCount()>0) {
							$response_billDetails['BillDetail'] = array();

							//$typeInq = $_POST['typeInq'];
						$bill_amount = 0;
						$index = 0;
						$code=1;
						foreach ($data as $dt) {
						if ($index==0) {
							$tanggal_awal = $dt->tanggal_awal;
							$tanggal_akhir = $dt->tanggal_akhir;
						}
						$bill_amount += $dt->billAmount;

				    $bill_detail = array(
				        'billCode' => '0'.$code,
				        'billName' => $dt->billName.' '.$dt->TahunID,
						'billShortName' => $dt->billShortName,
				        'billAmount' => $dt->billAmount,
						'reference1' => $request->post("reference1"),
						'reference2' => $request->post("reference2"),
						'reference3' => $request->post("reference3")
				        );
							//result data
									$index++;
									$code++;
									array_push($response_billDetails['BillDetail'],$bill_detail);

								}

								$curent_date = date('Y-m-d H:i:s');
								if ( $curent_date >= $tanggal_awal && $curent_date <= $tanggal_akhir) {
										$resp = array_merge($response_billDetails,$response);
										$row['billDetails'] = $resp;
										$row['billAmount'] = $bill_amount;
										$row['numBill'] = $index;
										$row['currency'] = '360';
										$row['status']['isError'] = false;
										$row['status']['errorCode'] = '00';
					    				$row['status']['statusDescription'] = 'Success';
										$log_mandiri['json_response'] = 'Success';
								} else {
										$row['billAmount'] = 0;
										$row['numBill'] = 0;
										$row['status']['errorCode'] = 'B5';
					    			$row['status']['statusDescription'] = 'Payment period is expired';
									$log_mandiri['json_response'] = 'Payment period is expired';
								}
					    } else {
								$row['billAmount'] = 0;
								$row['numBill'] = 0;
							$response['status']['isError'] = true;
					    	$response['status']['errorCode'] = 'B8';
					    	$response['status']['statusDescription'] = 'Bill is already paid';
							$log_mandiri['json_response'] = 'Bill is already paid';
					    }

							$response = array_merge($row,$response);
							$new_response['InquiryResponse'] = $response;
				        echoResponse(200, $new_response,$status_token->format_data);

					    //$db->insert('va_bank_mega',$insert_mega_history);
	 			  } else {
							$response['status']['isError'] = true;
					    	$response['status']['errorCode'] = 'B5';
					    	$response['status']['statusDescription'] = 'Bill not found';
							$log_mandiri['json_response'] = 'Bill not found';
					    	//$response = array_merge($row,$response);
							$new_response['InquiryResponse'] = $response;
					    	echoResponse(200, $new_response,$status_token->format_data);
					    }
	 		} else {
					//$response['status']['code'] = 422;
					/*foreach ($apiClass->errors() as $error) {
						$response['status']['message'] = $error;	
					}*/
		    	$response['errorCode'] = 'B5';
		    	$response['statusDescription'] = 'Data is not available';
				$log_mandiri['json_response'] = 'Data is not available';
				$new_response['InquiryResponse'] = $response;
				echoResponse(422, $new_response,$status_token->format_data);
	 		}

			if (!empty($array_log)) {
				$in_log = array_merge($array_log,array('json_response' => json_encode($log_mandiri['json_response'])));
			 
				$db->insert('log_mandiri',$in_log);
				echo $db->getErrorMessage();
			}


	});


//payment
	//auth status
	$create_auth = ($status_token->enable_token_create=="Y")?$authenticate($status_token->format_data,$status_token->id_service,"create"):"noauth";
	//post mega-syariah
	$app->post('/mandiri/payment',$create_auth, function() use ($app,$db,$apiClass,$status_token) {
	 		$app = \Slim\Slim::getInstance();
	 		$request = $app->request();

			 $json = $app->request->getBody();
			 $data_decode = json_decode($json, true); 
			 $nim_mahasiswa_res = $data_decode['paymentRequest']['billKey1'];
			 $paymentAmount = $data_decode['paymentRequest']['paymentAmount'];
			 $paymentAmount = explode(".",$paymentAmount);
			 $paymentAmount = $paymentAmount[0];
			 $nim_mahasiswa = substr($nim_mahasiswa_res,5,20);
			 $paymentAmount = $data_decode['paymentRequest']['paymentAmount'];
			 $paymentAmount = explode(".",$paymentAmount);
			 $paymentAmount = $paymentAmount[0];
    $validation = array(
    "billKey1" => array(
              "type" => "notEmpty",
              "alias" => "billKey1",
              "value" => $nim_mahasiswa,
              "allownull" => "no",
    )
/*     "typeInq" => array(
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
    ),*/
    );

/*    if ($nim_mahasiswa=='1210705092') {
    	sleep(700);
    	exit();
    }
    if ($nim_mahasiswa=='1910101050') {
		$row['status']['isError'] = true;
		$row['status']['errorCode'] = 'C0';
		$row['status']['statusDescription'] = 'Bill Suspend';
		$log_mandiri['json_response'] = 'Bill Suspend';
		$response['paymentResponse'] =$row;
		echoResponse(422, $response,$status_token->format_data);
    }*/

	 		$val = $apiClass->assert($validation);
			 $array_log = array(
				'data_json' => $json,
				'type_request' => 2,
				'date_created' => date('Y-m-d H:i:s')
			);

			//var_dump($json);
	 		if (empty($apiClass->errors())) {
	 			  //check mhs
	 			  $mhs = $db->fetch_custom_single("select mahasiswa.nim,mahasiswa.mulai_smt,mahasiswa.nama,id_jenjang,jurusan.nama_jur,fakultas.nama_resmi
from mahasiswa
inner join keu_tagihan_mahasiswa using(nim)
inner join jurusan on mahasiswa.jur_kode=jurusan.kode_jur
inner join fakultas on jurusan.fak_kode=fakultas.kode_fak
where mahasiswa.nim=? or right(mahasiswa.nim,10)='".$nim_mahasiswa."'",array('nim'=>$nim_mahasiswa));
	 			  if ($mhs) {
					$data = $db->query("select keu_tagihan_mahasiswa.nim,mahasiswa.mulai_smt,mahasiswa.nama,jurusan.nama_jur,fakultas.nama_resmi,
tanggal_awal,
tanggal_akhir,
keu_tagihan_mahasiswa.id as billID,keu_tagihan.id as billNameID,
kode_tagihan as billName,keu_tagihan.nominal_tagihan-potongan as billAmount,0 as billAmountBayar,0 as billAmountPay,
keu_tagihan_mahasiswa.periode as TahunID from keu_tagihan_mahasiswa
inner join keu_tagihan on keu_tagihan_mahasiswa.id_tagihan_prodi=keu_tagihan.id
inner join mahasiswa on keu_tagihan_mahasiswa.nim=mahasiswa.nim
inner join jurusan on mahasiswa.jur_kode=jurusan.kode_jur
inner join fakultas on jurusan.fak_kode=fakultas.kode_fak
where keu_tagihan_mahasiswa.id not in(select id_keu_tagihan_mhs from keu_bayar_mahasiswa where is_removed=0 and id_keu_tagihan_mhs is not null)
and is_aktif=1
and (keu_tagihan_mahasiswa.nim=? or right(keu_tagihan_mahasiswa.nim,10)='".$nim_mahasiswa."') and now() between tanggal_awal and tanggal_akhir
and keu_tagihan_mahasiswa.nim in(select nim_mhs from keu_keranjang_va inner join keu_keranjang_va_detail using(no_kwitansi) where nim_mhs='".$nim_mahasiswa."' and is_lunas='N' and is_active='Y' and exp_date > now())
order by keu_tagihan_mahasiswa.periode desc,tanggal_awal desc
",
array(
	'nim'=> $nim_mahasiswa
)
);



					//echo $db->getErrorMessage();

				//if ($dt->nim!="") {
						

						if ($data->rowCount()>0) {
							//$response_billDetails['paymentResponse'] = array();

							//$typeInq = $_POST['typeInq'];
						$bill_amount = 0;
						$index = 0;
						foreach ($data as $dt) {
						if ($index==0) {
							$tanggal_awal = $dt->tanggal_awal;
							$tanggal_akhir = $dt->tanggal_akhir;
						}
						$bill_amount += $dt->billAmount;
				    $row = array(
				       // 'typeInq' => $typeInq,
				        'billInfo1' => $nim_mahasiswa_res,
/*				        'TahunID' => $dt->mulai_smt,*/
				        'billInfo2' => $dt->nama,
				        /* 'ProdiNama' => $dt->nama_jur,
				        'FakultasNama' => $dt->nama_resmi,
				        'Gelombang' => 1,
				        'FormID' => 'REG1', */
				        );
/* 						if ($mhs->mulai_smt=='20221' && $mhs->id_jenjang=='30') {
							$row['nim'] = substr($mhs->nim,4,20);
						} */
/* 				    $bill_detail = array(
				        'billID' => $dt->billID,
				        'billNameID' => $dt->billNameID,
				        'billName' => $dt->billName,
				        'billAmount' => $dt->billAmount,
				        'billAmountBayar' => $dt->billAmountBayar,
				        'billAmountPay' => $dt->billAmount,
				        'TahunID' => $dt->TahunID
				        ); */
		        $data_bayar[] = array(
		            'id_keu_tagihan_mhs' => $dt->billID,
		            'tgl_bayar' => date('Y-m-d H:i:s'),
		            'tgl_validasi' => date('Y-m-d H:i:s'),
		            'created_by' => 'H2H BMRI',
		            'nominal_bayar' => $dt->billAmount
		        );
							//result data
									//array_push($response_billDetails['paymentResponse'],$bill_detail);
									$index++;

								}
/* 				    		$insert_mega_history = array(
				    			'nim' => $dt->nim,
				    			'no_va' => '506905300'.$mhs->nim,
				    			'typeInq' => $_POST['typeInq'],
				    			'paymentAmount' => $paymentAmount,
				    			'trxDateTime' => $_POST['trxDateTime'],
				    			'type_transaction' => 2,
				    			'date_created' => date('Y-m-d H:i:s')
				    		); */
/*								$row['billAmount'] = $bill_amount;
								$row['numBill'] = $index;*/
								if (date('Y-m-d H:i:s') < $tanggal_awal) {
									$row['status']['isError'] = true;
									$row['status']['errorCode'] = '01';
									$row['status']['statusDescription'] = 'Payment period is expired';
					    			$log_mandiri['json_response'] = 'Payment period is expired';
								} elseif(date('Y-m-d H:i:s') > $tanggal_akhir) {
									$row['status']['isError'] = true;
									$row['status']['errorCode'] = '01';
									$row['status']['statusDescription'] = 'Payment period is expired';
									$log_mandiri['json_response'] = 'Payment period is expired';
								} elseif($paymentAmount<$bill_amount) {
									$row['status']['isError'] = true;
									$row['status']['errorCode'] = '01';
									$row['status']['statusDescription'] = 'Amount is under paid';
					    			$insert_mega_history['error_desc'] = 'Amount is under paid';
									$log_mandiri['json_response'] = 'Amount is under paid';
								} elseif($paymentAmount>$bill_amount) {
									$row['status']['isError'] = true;
									$row['status']['errorCode'] = '01';
									$row['status']['statusDescription'] = 'Amount is over paid';
									$log_mandiri['json_response'] = 'Amount is over paid';
								} else {
									//$response = array_merge($response_billDetails,$response);

									$kode_jur = $db->fetch_single_row('mahasiswa','nim',$dt->nim);
											$kode_jurusan = $kode_jur->jur_kode;
											

											$no_kwitansi = strtotime(date('Y-m-d H:i:s')).rand();
											
										/* $row['TransactionID'] = $no_kwitansi;
										$row['UserLogin'] = '';
										$row['Password'] = '';
										$row['paymentAmount'] = $paymentAmount;
										$row['numBill'] = $index; */
										$row['status']['isError'] = false;
										$row['status']['errorCode'] = '00';
					    				$row['status']['statusDescription'] = 'Success';
										$log_mandiri['json_response'] = 'Success';
					    				//$row['statusDescription'] = 'Success';
					    			//$insert_mega_history['error_desc'] = 'Success';
					    			//$insert_mega_history['TransactionID'] = $no_kwitansi;

									$data_quitansi = array(
									              'nominal_bayar' => $paymentAmount,
									              'total_tagihan' => $paymentAmount,
									              'validator' => 'H2H BMRI',
									              'metode_bayar' => 3,
									              'id_bank' => '03',
									              'kode_jur' => $kode_jurusan,
									              'no_kwitansi' => $no_kwitansi,
									              'nim_mahasiswa' => $dt->nim,
									              //'urutan_bayar' => $urutan,
									              'tgl_bayar' => date('Y-m-d H:i:s'),
									              'date_created' => date('Y-m-d H:i:s')
									            );
									$db->begin_transaction();
									$insert_kwitansi = $db->insert('keu_kwitansi',$data_quitansi);
									if ($insert_kwitansi) {
										$id_kwitansi = $db->last_insert_id();

										foreach ($data_bayar as $bayar) {
											$bayar['id_kwitansi'] = $id_kwitansi;
											$data_bayar_akhir[] = $bayar;
										}
										$insert_bayar = $db->insertMulti('keu_bayar_mahasiswa',$data_bayar_akhir);
			              if ($insert_bayar==false) {
			                $db->rollback();
			              }
									}
									$db->query("update keu_keranjang_va set is_active='N' where nim_mhs='".$dt->nim."'");
									$db->query("update keu_keranjang_va set is_lunas='Y',updated='".date('Y-m-d H:i:s')."' where nim_mhs='".$dt->nim."'"); $db->commit();
								}
							$response['paymentResponse'] =$row;
							echoResponse(200, $response,$status_token->format_data);
					    } else {
								/*$response['paymentAmount'] = $paymentAmount;
								$response['numBill'] = 0;*/
							$row['status']['isError'] = true;
							$row['status']['errorCode'] = 'B8';
							$row['status']['statusDescription'] = 'Bill is already paid';
							$log_mandiri['json_response'] = 'Bill is already paid';
							$response['paymentResponse'] =$row;
							echoResponse(200, $response,$status_token->format_data);
					    }
	 		} else {
				$row['status']['isError'] = true;
				$row['status']['errorCode'] = 'B8';
				$row['status']['statusDescription'] = 'Bill not found';
				$log_mandiri['json_response'] = 'Bill is already paid';
				$response['paymentResponse'] =$row;
				echoResponse(422, $response,$status_token->format_data);
	 		}

	 	} else {
				$row['status']['isError'] = true;
				$row['status']['errorCode'] = 'B8';
				$row['status']['statusDescription'] = 'Bill not found';
				$log_mandiri['json_response'] = 'Bill is already paid';
				$response['paymentResponse'] =$row;
				echoResponse(422, $response,$status_token->format_data);
	 	}

		if (!empty($array_log)) {
			$in_log = array_merge($array_log,array('json_response' => json_encode($log_mandiri['json_response'])));
		 
			$db->insert('log_mandiri',$in_log);
			echo $db->getErrorMessage();
		}

	});

	