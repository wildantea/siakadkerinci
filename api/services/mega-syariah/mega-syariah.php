<?php
	//doc route
	$app->get('/mega-syariah/doc',function() use ($db) {
		include "doc.php";
	});

	$status_token = $db->fetch_custom_single("SELECT enable_token_read,enable_token_create,enable_token_update,enable_token_delete,format_data,id_service FROM sys_services INNER JOIN sys_token ON sys_services.id=sys_token.id_service where nav_act=?",array("nav_act" => "mega-syariah"));
	
	//login action
	$app->post('/mega-syariah/login', function() use ($app,$db,$status_token) {
		auth_data($app,$db,$status_token->format_data);
	});


	//auth status
	$create_auth = ($status_token->enable_token_create=="Y")?$authenticate($status_token->format_data,$status_token->id_service,"create"):"noauth";
	//post mega-syariah
	$app->post('/mega-syariah/inquiry',$create_auth, function() use ($app,$db,$apiClass,$status_token) {
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

	 		$val = $apiClass->assert($validation);

	 		if (empty($apiClass->errors())) {

	 			  //check mhs
	 			  $mhs = $db->fetch_custom_single("select mahasiswa.nim,mahasiswa.mulai_smt,mahasiswa.nama,jurusan.nama_jur,fakultas.nama_resmi
from mahasiswa 
inner join keu_tagihan_mahasiswa using(nim)
inner join jurusan on mahasiswa.jur_kode=jurusan.kode_jur
inner join fakultas on jurusan.fak_kode=fakultas.kode_fak
where right(mahasiswa.nim,10)=?",array('nim'=>$request->post("nim")));
	 			  $response = array();
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
				        'typeInq' => $_POST['typeInq'],
				        'nim' => $request->post("nim"),
				        'TahunID' => $mhs->mulai_smt,
				        'Nama' => $mhs->nama,
				        'ProdiNama' => $mhs->nama_jur,
				        'FakultasNama' => $mhs->nama_resmi,
				        'Gelombang' => 1,
				        'FormID' => 'REG1',
				        );
					    $insert_mega_history = array(
					            "nim" => $request->post("nim"),
					            "no_va" => "506905300".$request->post("nim"),
					            "typeInq" => $request->post("typeInq"),
					            "trxDateTime" => $request->post("trxDateTime"),
					            "companyCode" => $request->post("companyCode"),
					            "channeled" => $request->post("channeled"),
					    				"type_transaction" => 1,
					    				"date_created" => date('Y-m-d H:i:s')
					    );

				//if ($dt->nim!="") {
						
						if ($data->rowCount()>0) {
							$response_billDetails['billDetails'] = array();

							$typeInq = $_POST['typeInq'];
						$bill_amount = 0;
						$index = 0;
						foreach ($data as $dt) {
						if ($index==0) {
							$tanggal_awal = $dt->tanggal_awal;
							$tanggal_akhir = $dt->tanggal_akhir;
						}
						$bill_amount += $dt->billAmount;

				    $bill_detail = array(
				        'billID' => $dt->billID,
				        'billNameID' => $dt->billNameID,
				        'billName' => $dt->billName,
				        'billAmount' => $dt->billAmount,
				        'billAmountBayar' => $dt->billAmountBayar,
				        'billAmountPay' => $dt->billAmountPay,
				        'TahunID' => $dt->TahunID
				        );
							//result data
									$index++;
									array_push($response_billDetails['billDetails'],$bill_detail);

								}
								$curent_date = date('Y-m-d H:i:s');
								if ( $curent_date >= $tanggal_awal && $curent_date <= $tanggal_akhir) {
										
										$response = array_merge($response_billDetails,$response);
										$row['billAmount'] = $bill_amount;
										$row['numBill'] = $index;
										$row['errorCode'] = '00';
					    			$row['statusDescription'] = 'Success';
					    			$insert_mega_history['error_desc'] = 'Success';
								} else {
										$row['billAmount'] = 0;
										$row['numBill'] = 0;
										$row['errorCode'] = '17';
					    			$row['statusDescription'] = 'Payment period is expired';
					    			$insert_mega_history['error_desc'] = 'Payment period is expired';
								}
					    } else {
								$row['billAmount'] = 0;
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
					    	$response['statusDescription'] = 'Data is not available';
					    	//$response = array_merge($row,$response);
					    	echoResponse(200, $response,$status_token->format_data);
					    }
	 		} else {
					//$response['status']['code'] = 422;
					/*foreach ($apiClass->errors() as $error) {
						$response['status']['message'] = $error;	
					}*/
		    	$response['errorCode'] = '04';
		    	$response['statusDescription'] = 'Data is not available';
					echoResponse(422, $response,$status_token->format_data);
	 		}

	});


//payment
	//auth status
	$create_auth = ($status_token->enable_token_create=="Y")?$authenticate($status_token->format_data,$status_token->id_service,"create"):"noauth";
	//post mega-syariah
	$app->post('/mega-syariah/payment',$create_auth, function() use ($app,$db,$apiClass,$status_token) {
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

	 		$val = $apiClass->assert($validation);

	 		if (empty($apiClass->errors())) {
	 			  //check mhs
	 			  $mhs = $db->fetch_custom_single("select mahasiswa.nim,mahasiswa.mulai_smt,mahasiswa.nama,jurusan.nama_jur,fakultas.nama_resmi
from mahasiswa
inner join keu_tagihan_mahasiswa using(nim)
inner join jurusan on mahasiswa.jur_kode=jurusan.kode_jur
inner join fakultas on jurusan.fak_kode=fakultas.kode_fak
where right(mahasiswa.nim,10)=?",array('nim'=>$request->post("nim")));
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
where keu_tagihan_mahasiswa.id not in(select id_keu_tagihan_mhs from keu_bayar_mahasiswa where is_removed=0)
and is_aktif=1
and right(keu_tagihan_mahasiswa.nim,10)=?
order by keu_tagihan_mahasiswa.periode desc,tanggal_awal desc
",
array(
	'nim'=>$request->post("nim")
)
);

					//echo $db->getErrorMessage();

				//if ($dt->nim!="") {
						$response = array();
						if ($data->rowCount()>0) {
							$response_billDetails['billDetails'] = array();

							$typeInq = $_POST['typeInq'];
						$bill_amount = 0;
						$index = 0;
						foreach ($data as $dt) {
						if ($index==0) {
							$tanggal_awal = $dt->tanggal_awal;
							$tanggal_akhir = $dt->tanggal_akhir;
						}
						$bill_amount += $dt->billAmount;
				    $row = array(
				        'typeInq' => $typeInq,
				        'nim' => $request->post("nim"),
/*				        'TahunID' => $dt->mulai_smt,
				        'Nama' => $dt->nama,
				        'ProdiNama' => $dt->nama_jur,
				        'FakultasNama' => $dt->nama_resmi,
				        'Gelombang' => 1,
				        'FormID' => 'REG1',*/
				        );
				    $bill_detail = array(
				        'billID' => $dt->billID,
				        'billNameID' => $dt->billNameID,
				        'billName' => $dt->billName,
				        'billAmount' => $dt->billAmount,
				        'billAmountBayar' => $dt->billAmountBayar,
				        'billAmountPay' => $dt->billAmount,
				        'TahunID' => $dt->TahunID
				        );
		        $data_bayar[] = array(
		            'id_keu_tagihan_mhs' => $dt->billID,
		            'tgl_bayar' => date('Y-m-d H:m:s'),
		            'tgl_validasi' => date('Y-m-d H:m:s'),
		            'created_by' => 'H2H Mega Syariah',
		            'nominal_bayar' => $dt->billAmount
		        );
							//result data
									array_push($response_billDetails['billDetails'],$bill_detail);
									$index++;

								}
				    		$insert_mega_history = array(
				    			'nim' => $dt->nim,
				    			'no_va' => '506905300'.$request->post('nim'),
				    			'typeInq' => $_POST['typeInq'],
				    			'paymentAmount' => $_POST['paymentAmount'],
				    			'trxDateTime' => $_POST['trxDateTime'],
				    			'type_transaction' => 2,
				    			'date_created' => date('Y-m-d H:i:s')
				    		);
/*								$row['billAmount'] = $bill_amount;
								$row['numBill'] = $index;*/
								if (date('Y-m-d H:i:s') < $tanggal_awal) {
										$row['errorCode'] = '17';
					    			$row['statusDescription'] = 'Payment period is expired';
					    			$insert_mega_history['error_desc'] = 'Payment period is expired';
								} elseif(date('Y-m-d H:i:s') > $tanggal_akhir) {
										$row['errorCode'] = '17';
					    			$row['statusDescription'] = 'Payment period is expired';
					    			$insert_mega_history['error_desc'] = 'Payment period is expired';
								} elseif($_POST['paymentAmount']<$bill_amount) {
										$row['errorCode'] = '11';
					    			$row['statusDescription'] = 'Amount is under paid';
					    			$insert_mega_history['error_desc'] = 'Amount is under paid';
								} elseif($_POST['paymentAmount']>$bill_amount) {
										$row['errorCode'] = '12';
					    			$row['statusDescription'] = 'Amount is over paid';
					    			$insert_mega_history['error_desc'] = 'Amount is over paid';
								} else {
									$response = array_merge($response_billDetails,$response);

									$kode_jur = $db->fetch_single_row('mahasiswa','nim',$dt->nim);
											$kode_jurusan = $kode_jur->jur_kode;
											$urutan_bayar_prodi = $db->fetch_custom_single("select urutan_bayar from keu_kwitansi where kode_jur=? and date(date_created)=? order by urutan_bayar desc",
											  array(
											    'kode_jur' => $kode_jurusan,
											    'date_created' => date('Y-m-d')
											  )
											);

											if ($urutan_bayar_prodi) {
											  $urutan = $urutan_bayar_prodi->urutan_bayar;
											  $urutan = $urutan+1;
											  if ($urutan<10) {
											    $no_kwitansi = date('Ymd').$kode_jurusan.'000'.($urutan);
											  } else {
											    $no_kwitansi = date('Ymd').$kode_jurusan.($urutan);
											  }
											} else {
											  $no_kwitansi = date('Ymd').$kode_jurusan.'0001';
											  $urutan = 1;
											}

											
										$row['TransactionID'] = $no_kwitansi;
										$row['UserLogin'] = '';
										$row['Password'] = '';
										$row['paymentAmount'] = $_POST['paymentAmount'];
										$row['numBill'] = $index;
										$row['errorCode'] = '00';
					    			$row['statusDescription'] = 'Success';
					    			$insert_mega_history['error_desc'] = 'Success';
					    			$insert_mega_history['TransactionID'] = $no_kwitansi;

									$data_quitansi = array(
									              'nominal_bayar' => $_POST['paymentAmount'],
									              'total_tagihan' => $_POST['paymentAmount'],
									              'validator' => 'H2H Mega Syariah',
									              'metode_bayar' => 3,
									              'id_bank' => 01,
									              'kode_jur' => $kode_jurusan,
									              'no_kwitansi' => $no_kwitansi,
									              'urutan_bayar' => $urutan,
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

		              $db->commit();
								}
								    $db->insert('va_bank_mega',$insert_mega_history);

								$response = array_merge($row,$response);
							//bill detail
							//$bill_detail = $db->query("select * from va_bank_mega_bill_detail where ")
					        echoResponse(200, $response,$status_token->format_data);
					    } else {
								/*$response['paymentAmount'] = $_POST['paymentAmount'];
								$response['numBill'] = 0;*/
					    	$response['errorCode'] = '09';
					    	$response['statusDescription'] = 'Bill is already paid';
					    	//$response = array_merge($row,$response);
					    	echoResponse(200, $response,$status_token->format_data);
					    }
	 		} else {
					//$response['status']['code'] = 422;
/*					foreach ($apiClass->errors() as $error) {
						$response['status']['message'] = $error;	
					}*/
		    	$response['errorCode'] = '04';
		    	$response['statusDescription'] = 'Data is not available';
					echoResponse(422, $response,$status_token->format_data);
	 		}

	 	} else {
		    	$response['errorCode'] = '04';
		    	$response['statusDescription'] = 'Data is not available';
					echoResponse(422, $response,$status_token->format_data);
	 	}

	});



//reversal
	//auth status
	$create_auth = ($status_token->enable_token_create=="Y")?$authenticate($status_token->format_data,$status_token->id_service,"create"):"noauth";
	//post mega-syariah
	$app->post('/mega-syariah/reversal',$create_auth, function() use ($app,$db,$apiClass,$status_token) {
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
	 		
/*    $data = array(
            "nim" => $request->post("nim"),
            "typeInq" => $request->post("typeInq"),
            "trxDateTime" => $request->post("trxDateTime"),
            "companyCode" => $request->post("companyCode"),
            "channeled" => $request->post("channeled"),
    );*/

	 		$val = $apiClass->assert($validation);

	 		if (empty($apiClass->errors())) {
	 			  //check mhs
	 			  $mhs = $db->fetch_custom_single("select mahasiswa.nim,mahasiswa.mulai_smt,mahasiswa.nama,jurusan.nama_jur,fakultas.nama_resmi
from mahasiswa
inner join keu_tagihan_mahasiswa using(nim)
inner join jurusan on mahasiswa.jur_kode=jurusan.kode_jur
inner join fakultas on jurusan.fak_kode=fakultas.kode_fak
where right(mahasiswa.nim,10)=?",array('nim'=>$request->post("nim")));
	 			  if ($mhs) {
					$data = $db->fetch_custom_single("select id_kwitansi,no_kwitansi,is_deleted from keu_kwitansi where no_kwitansi=?",array('no_kwitansi'=>$request->post("TransactionID")));

						if ($data) {
					    		//insert history process
					    		$insert_mega_history = array(
					    			'nim' => $_POST['nim'],
					    			'no_va' => '506905300'.$request->post('nim'),
					    			'typeInq' => $_POST['typeInq'],
					    			'TransactionID' => $data->no_kwitansi,
					    			'paymentAmount' => $_POST['paymentAmount'],
					    			'trxDateTime' => $_POST['trxDateTime'],
					    			'type_transaction' => 3,
					    			'date_created' => date('Y-m-d H:i:s')
					    		);
								if ($data->is_deleted>0) {
										$response['errorCode'] = '15';
							    	$response['statusDescription'] = 'Reversal is already done';
							    	$insert_mega_history['error_desc'] = 'Reversal is already done';
								} else {
										//update kwitansi to is deleted
										$update_kwitansi = $db->update('keu_kwitansi',array('is_deleted' => 1),'id_kwitansi',$data->id_kwitansi);
										if ($update_kwitansi) {
													$db->update('keu_bayar_mahasiswa',array('is_removed' => 1),'id_kwitansi',$data->id_kwitansi);
													$response['errorCode'] = '00';
								    			$response['statusDescription'] = 'Success';
								    			$insert_mega_history['error_desc'] = 'Success';
								    	//$response = array_merge($row,$response);
										} else {
												$response['errorCode'] = '07';
									    	$response['statusDescription'] = 'Reversal is not allowed at the moment';
									    	$insert_mega_history['error_desc'] = 'Reversal is not allowed at the moment'.$db->getErrorMessage();
										}
								}
					    		$db->insert('va_bank_mega',$insert_mega_history);
					    } else {
					    	$response['errorCode'] = '04';
					    	$response['statusDescription'] = 'Data is not available';
					    }
					    echoResponse(200, $response,$status_token->format_data);
	 		} else {
					//$response['status']['code'] = 422;
/*					foreach ($apiClass->errors() as $error) {
						$response['status']['message'] = $error;	
					}*/
		    	$response['errorCode'] = '04';
		    	$response['statusDescription'] = 'Data is not available';
					echoResponse(422, $response,$status_token->format_data);
	 		}

	 	} else {
		    	$response['errorCode'] = '04';
		    	$response['statusDescription'] = 'Data is not available';
					echoResponse(422, $response,$status_token->format_data);
	 	}

	});
	

	