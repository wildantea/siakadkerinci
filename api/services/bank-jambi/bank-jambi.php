<?php
	//doc route
	$app->get('/bank-jambi/doc',function() use ($db) {
		include "doc.php";
	});

	$status_token = $db->fetch_custom_single("SELECT enable_token_read,enable_token_create,enable_token_update,enable_token_delete,format_data,id_service FROM sys_services INNER JOIN sys_token ON sys_services.id=sys_token.id_service where nav_act=?",array("nav_act" => "bank-jambi"));
	
	//login action
	$app->post('/bank-jambi/login', function() use ($app,$db,$status_token) {
		auth_data($app,$db,$status_token->format_data);
	});

	//auth status
	$coba_auth = ($status_token->enable_token_create=="Y")?$authenticates($status_token->format_data,$db,"create"):"noauth";
	//post bank-jambi
	$app->post('/bank-jambi/coba',$coba_auth, function() use ($app,$db,$apiClass,$status_token) {
	 		$app = \Slim\Slim::getInstance();
	 		$request = $app->request();

	 		//print_r($request);
	 		$response['nim'] = $request->post("nim");
	 		echoResponse(200, $response,$status_token->format_data);

	});


	//auth status
	$create_auth = ($status_token->enable_token_create=="Y")?$authenticates($status_token->format_data,$db,"create"):"noauth";
	//post bank-jambi
	$app->post('/bank-jambi/inquiry',$create_auth, function() use ($app,$db,$apiClass,$status_token) {
	 		$app = \Slim\Slim::getInstance();
	 		$request = $app->request();
	 		

    $validation = array(
    "nim" => array(
              "type" => "notEmpty",
              "alias" => "nim",
              "value" => $request->post("customerReference"),
              "allownull" => "no",
    ),
   /* "typeInq" => array(
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



	 		$val = $apiClass->assert($validation);

	 		//var_dump($val);

	 		if (empty($apiClass->errors())) {

	 			  //check mhs
	 			  $mhs = $db->fetch_custom_single("
select mahasiswa.nim,mahasiswa.mulai_smt,mahasiswa.nama,jurusan.nama_jur,fakultas.nama_resmi
from mahasiswa 
inner join jurusan on mahasiswa.jur_kode=jurusan.kode_jur
inner join fakultas on jurusan.fak_kode=fakultas.kode_fak
where mahasiswa.nim in(select nim from keu_tagihan_mahasiswa where nim=?)",array('nim'=>$request->post("customerReference")));
	 			/*  var_dump($mhs);
	 			  echo "why";*/
	 			  $response = array();
	 			  if ($mhs) {
						$data = $db->query("select keu_tagihan_mahasiswa.nim,mahasiswa.mulai_smt,mahasiswa.nama,jurusan.nama_jur,fakultas.nama_resmi,nama_tagihan,
						keu_tagihan_mahasiswa.id as billID,keu_tagihan.id as billNameID,
						kode_tagihan as billName,keu_tagihan.nominal_tagihan-potongan as billAmount,0 as billAmountBayar,0 as billAmountPay,
						keu_tagihan_mahasiswa.periode as TahunID from keu_tagihan_mahasiswa
						inner join keu_tagihan on keu_tagihan_mahasiswa.id_tagihan_prodi=keu_tagihan.id
						inner join keu_jenis_tagihan using (kode_tagihan) 
						inner join mahasiswa on keu_tagihan_mahasiswa.nim=mahasiswa.nim
						inner join jurusan on mahasiswa.jur_kode=jurusan.kode_jur
						inner join fakultas on jurusan.fak_kode=fakultas.kode_fak
						where keu_tagihan_mahasiswa.id not in(select id_keu_tagihan_mhs from keu_bayar_mahasiswa where is_removed=0)
						and is_aktif=1 
						and keu_tagihan_mahasiswa.nim=?
						order by keu_tagihan_mahasiswa.periode desc
						",
						array(
								'nim'=>$request->post("customerReference")
							)
					);

				    $row = array(
				        //'typeInq' => $_POST['typeInq'],
				        'customerReference' => $request->post("customerReference"),
				        'customerName' => $mhs->nama,
				        'totalArrears' => 0,
				        'totalFee' => 0,
				        'description' => $mhs->mulai_smt,
				        'description2' => $mhs->nama_jur,
				        'description3' => $mhs->nama_resmi,
/*				        'Gelombang' => 1,
				        'FormID' => 'REG1',*/
				        );
/*					    $insert_mega_history = array(
					            "nim" => $request->post("customerReference"),
					            "no_va" => "506905300".$request->post("customerReference"),
					            //"typeInq" => $request->post("typeInq"),
					            "trxDateTime" => $request->post("trxDateTime"),
					            "companyCode" => $request->post("companyCode"),
					            "channeled" => $request->post("channeled"),
					    				"type_transaction" => 1,
					    				"date_created" => date('Y-m-d H:i:s')
					    );*/

				//if ($dt->nim!="") {
						
						if ($data->rowCount()>0) {
							$response_billDetails['billDetails'] = array();

						//$typeInq = $_POST['typeInq'];
						$bill_amount = 0;
						$totalPay = 0;
						$index = 0;
						foreach ($data as $dt) {
/*						if ($index==0) {
							$tanggal_awal = $dt->tanggal_awal;
							$tanggal_akhir = $dt->tanggal_akhir;
						}*/
						$bill_amount += $dt->billAmount;
						$totalPay += $dt->billAmountPay;

				    $bill_detail = array(
				        'billId' => $dt->billID,
				        'billName' => $dt->nama_tagihan,
				        'billAmount' => $dt->billAmount,
				        'billPay' => $dt->billAmountPay,
				        'billPaid' => $dt->billAmountBayar,
				        'billFee' => 0,
				        'billTime' => date('Y-m-d'),
				        'billDescription' => $dt->TahunID
				        );
							//result data
									$index++;
									array_push($response_billDetails['billDetails'],$bill_detail);

								}
/*								$curent_date = date('Y-m-d H:i:s');
								if ( $curent_date >= $tanggal_awal && $curent_date <= $tanggal_akhir) {*/
										
										$response = array_merge($response_billDetails,$response);
										$row['totalAmount'] = $bill_amount;
										$row['totalPay'] = $totalPay;
										$row['numBill'] = $index;
										$row['responseCode'] = '00';
					    			$row['responseDescription'] = 'Sukses';
					    			//$insert_mega_history['error_desc'] = 'Sukses';
/*								} else {
										$row['billAmount'] = 0;
										$row['numBill'] = 0;
										$row['responseCode'] = '17';
					    			$row['responseDescription'] = 'Payment period is expired';
					    			$insert_mega_history['error_desc'] = 'Payment period is expired';
								}*/
					    } else {
								$row['totalAmount'] = 0;
								$row['numBill'] = 0;
					    	$response['responseCode'] = '10';
					    	$response['responseDescription'] = 'Tagihan telah dibayar';
					    	//$insert_mega_history['error_desc'] = 'Tagihan telah dibayar';
					    }

							$response = array_merge($row,$response);
				        echoResponse(200, $response,$status_token->format_data);

					    //$db->insert('va_bank_jambi',$insert_mega_history);
	 			  } else {
					    	$response['responseCode'] = '11';
					    	$response['responseDescription'] = 'Tagihan tidak tersedia';
					    	//$response = array_merge($row,$response);
					    	echoResponse(200, $response,$status_token->format_data);
					    }
	 		} else {
					//$response['status']['code'] = 422;
					/*foreach ($apiClass->errors() as $error) {
						$response['status']['message'] = $error;	
					}*/
			    	$response['responseCode'] = '11';
			    	$response['responseDescription'] = 'Tagihan tidak tersedia';
					echoResponse(422, $response,$status_token->format_data);
	 		}

	});


//payment
	//auth status
	$create_auth = ($status_token->enable_token_create=="Y")?$authenticates($status_token->format_data,$db,"create"):"noauth";
	//post bank-jambi
	$app->post('/bank-jambi/payment',$create_auth, function() use ($app,$db,$apiClass,$status_token) {
	 		$app = \Slim\Slim::getInstance();
	 		$request = $app->request();
	 		
    $validation = array(
	    "customerReference" => array(
	              "type" => "notEmpty",
	              "alias" => "customerReference",
	              "value" => $request->post("customerReference"),
	              "allownull" => "no",
	    ),
	    "trxDate" => array(
	              "type" => "notEmpty",
	              "alias" => "trxDate",
	              "value" => $request->post("trxDate"),
	              "allownull" => "",
	    ),
	    "amount" => array(
	              "type" => "notEmpty",
	              "alias" => "amount",
	              "value" => $request->post("amount"),
	              "allownull" => "no",
	    ),
	    "channelId" => array(
	              "type" => "notEmpty",
	              "alias" => "channelId",
	              "value" => $request->post("channelId"),
	              "allownull" => "no",
	    ),
	    "terminalId" => array(
	              "type" => "notEmpty",
	              "alias" => "terminalId",
	              "value" => $request->post("terminalId"),
	              "allownull" => "no",
	    ),
	    "referenceNumber" => array(
	              "type" => "notEmpty",
	              "alias" => "referenceNumber",
	              "value" => $request->post("referenceNumber"),
	              "allownull" => "no",
	    ),
    );

	 		$val = $apiClass->assert($validation);

	 		if (empty($apiClass->errors())) {
	 			  //check mhs
	 			  $mhs = $db->fetch_custom_single("
select mahasiswa.nim,mahasiswa.mulai_smt,mahasiswa.nama,jurusan.nama_jur,fakultas.nama_resmi
from mahasiswa 
inner join jurusan on mahasiswa.jur_kode=jurusan.kode_jur
inner join fakultas on jurusan.fak_kode=fakultas.kode_fak
where mahasiswa.nim in(select nim from keu_tagihan_mahasiswa where nim=?)",array('nim'=>$request->post("customerReference")));
	 			  if ($mhs) {
					$data = $db->query("select keu_tagihan_mahasiswa.nim,mahasiswa.mulai_smt,mahasiswa.nama,jurusan.nama_jur,fakultas.nama_resmi,
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
order by keu_tagihan_mahasiswa.periode desc
",
array(
	'nim'=>$request->post("customerReference")
)
);

					//echo $db->getErrorMessage();

				//if ($dt->nim!="") {
						$response = array();
						if ($data->rowCount()>0) {
						//$response_billDetails['billDetails'] = array();
						//$typeInq = $_POST['typeInq'];
						$bill_amount = 0;
						$index = 0;
						foreach ($data as $dt) {
/*						if ($index==0) {
							$tanggal_awal = $dt->tanggal_awal;
							$tanggal_akhir = $dt->tanggal_akhir;
						}*/
						$bill_amount += $dt->billAmount;
				    $row = array(
				        //'typeInq' => $typeInq,
				        'customerReference' => $request->post("customerReference"),

/*				        'TahunID' => $dt->mulai_smt,
				        'Nama' => $dt->nama,
				        'ProdiNama' => $dt->nama_jur,
				        'FakultasNama' => $dt->nama_resmi,
				        'Gelombang' => 1,
				        'FormID' => 'REG1',*/
				        );
/*				    $bill_detail = array(
				        'billID' => $dt->billID,
				        'billNameID' => $dt->billNameID,
				        'billName' => $dt->billName,
				        'billAmount' => $dt->billAmount,
				        'billAmountBayar' => $dt->billAmountBayar,
				        'billAmountPay' => $dt->billAmount,
				        'TahunID' => $dt->TahunID
				        );*/
				        $data_bayar[] = array(
				            'id_keu_tagihan_mhs' => $dt->billID,
				            'tgl_bayar' => date('Y-m-d H:m:s'),
				            'tgl_validasi' => date('Y-m-d H:m:s'),
				            'created_by' => 'H2H Bank Jambi',
				            'nominal_bayar' => $dt->billAmount
				        );
							//result data
									//array_push($response_billDetails['billDetails'],$bill_detail);
									$index++;

								}

				    		$insert_mega_history = array(
				    			'nim' => $dt->nim,
				    			'referenceNumber' => $request->post('referenceNumber'),
				    			'billAmount' => $request->post('amount'),
				    			'paymentAmount' => $request->post('amount'),
				    			'channelId' => $request->post('channelId'),
				    			'terminalId' => $request->post('terminalId'),
				    			'trxDate' => $request->post('trxDate'),
				    			'type_transaction' => 2,
				    			'date_created' => date('Y-m-d H:i:s')
				    		);
/*								$row['billAmount'] = $bill_amount;
								$row['numBill'] = $index;*/
/*								if (date('Y-m-d H:i:s') < $tanggal_awal) {
										$row['responseCode'] = '17';
					    			$row['responseDescription'] = 'Payment period is expired';
					    			$insert_mega_history['error_desc'] = 'Payment period is expired';
								} elseif(date('Y-m-d H:i:s') > $tanggal_akhir) {
										$row['responseCode'] = '17';
					    			$row['responseDescription'] = 'Payment period is expired';
					    			$insert_mega_history['error_desc'] = 'Payment period is expired';
								} else*/
								if($_POST['amount']<$bill_amount) {
										$row['responseCode'] = '02';
					    			$row['responseDescription'] = 'Amount is under paid';
					    			$insert_mega_history['error_desc'] = 'Amount is under paid';
								} elseif($_POST['amount']>$bill_amount) {
										$row['responseCode'] = '02';
					    			$row['responseDescription'] = 'Amount is over paid';
					    			$insert_mega_history['error_desc'] = 'Amount is over paid';
								} else {
									//$response = array_merge($response_billDetails,$response);

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

											
									$row['referenceNumber'] = $request->post("referenceNumber");
									$row['transactionId'] = $no_kwitansi;
									$row['trxDate'] = $request->post("trxDate");
									//$row['paymentAmount'] = request->post("amount");
									//$row['numBill'] = $index;
								 	$row['description'] = $mhs->mulai_smt;
							        $row['description2'] = $mhs->nama_jur;
							        $row['description3'] = $mhs->nama_resmi;
									$row['responseCode'] = '00';
					    			$row['responseDescription'] = 'Sukses';
					    			$insert_mega_history['error_desc'] = 'Sukses';
					    			$insert_mega_history['TransactionID'] = $no_kwitansi;

									$data_quitansi = array(
									              'nominal_bayar' => $request->post("amount"),
									              'total_tagihan' => $request->post("amount"),
									              'validator' => 'H2H Bank Jambi',
									              'metode_bayar' => 3,
									              'nim_mahasiswa' => $request->post("customerReference"),
									              'id_bank' => '02',
									              'kode_jur' => $kode_jurusan,
									              'no_kwitansi' => $no_kwitansi,
									              'urutan_bayar' => $urutan,
									              'referenceNumber' => $request->post("referenceNumber"),
									              'trxDate' => $request->post("trxDate"),
									              'tgl_bayar' => date('Y-m-d H:i:s'),
									              'date_created' => date('Y-m-d H:i:s')
									            );
									$db->begin_transaction();
									$insert_kwitansi = $db->insert('keu_kwitansi',$data_quitansi);
									//echo $db->getErrorMessage();
									//print_r($data_quitansi);
									//exit();
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
								    $db->insert('va_bank_jambi',$insert_mega_history);

								$response = array_merge($row,$response);
							//bill detail
							//$bill_detail = $db->query("select * from va_bank_jambi_bill_detail where ")
					        echoResponse(200, $response,$status_token->format_data);
					    } else {
								/*$response['paymentAmount'] = $_POST['paymentAmount'];
								$response['numBill'] = 0;*/
					    	$response['responseCode'] = '10';
					    	$response['responseDescription'] = 'Tagihan telah dibayar';
					    	//$response = array_merge($row,$response);
					    	echoResponse(200, $response,$status_token->format_data);
					    }
	 		} else {
					//$response['status']['code'] = 422;
/*					foreach ($apiClass->errors() as $error) {
						$response['status']['message'] = $error;	
					}*/
		    	$response['responseCode'] = '11';
		    	$response['responseDescription'] = 'Tagihan tidak tersedia';
					echoResponse(422, $response,$status_token->format_data);
	 		}

	 	} else {
		    	$response['responseCode'] = '11';
		    	$response['responseDescription'] = 'Tagihan tidak tersedia';
					echoResponse(422, $response,$status_token->format_data);
	 	}

	});



	
//reversal
	//auth status
	$create_auth = ($status_token->enable_token_create=="Y")?$authenticates($status_token->format_data,$db,"create"):"noauth";
	//post bank-jambi
	$app->post('/bank-jambi/reversal',$create_auth, function() use ($app,$db,$apiClass,$status_token) {
	 		$app = \Slim\Slim::getInstance();
	 		$request = $app->request();
	 		
    $validation = array(
    "nim" => array(
              "type" => "notEmpty",
              "alias" => "nim",
              "value" => $request->post("customerReference"),
              "allownull" => "no",
    )
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
where mahasiswa.nim=?",array('nim'=>$request->post("customerReference")));
	 			  if ($mhs) {
					$data = $db->fetch_custom_single("select id_kwitansi,no_kwitansi,is_deleted from keu_kwitansi where nim_mahasiswa=?",array('nim_mahasiswa'=>$request->post("customerReference")));

						if ($data) {
										//update kwitansi to is deleted
										$update_kwitansi = $db->delete('keu_kwitansi','no_kwitansi',$data->no_kwitansi);
										if ($update_kwitansi) {
													$response['responseCode'] = '00';
								    			$response['responseDescription'] = 'Sukses';
										} else {
												$response['responseCode'] = '07';
									    	$response['responseDescription'] = 'Reversal is not allowed at the moment';
										}
					    } else {
					    	$response['responseCode'] = '04';
					    	$response['responseDescription'] = 'Data is not available';
					    }
					    echoResponse(200, $response,$status_token->format_data);
	 		} else {
					//$response['status']['code'] = 422;
/*					foreach ($apiClass->errors() as $error) {
						$response['status']['message'] = $error;	
					}*/
		    	$response['responseCode'] = '04';
		    	$response['responseDescription'] = 'Data is not available';
					echoResponse(422, $response,$status_token->format_data);
	 		}

	 	} else {
		    	$response['responseCode'] = '04';
		    	$response['responseDescription'] = 'Data is not available';
					echoResponse(422, $response,$status_token->format_data);
	 	}

	});
	

	