<?php
function convertTime($time) {
  $year = substr($time,0,4);
  $month = substr($time,4,2);
  $day = substr($time,6,2);
  $hour = substr($time,8,2);
  $minute = substr($time,10,2);
  $second = substr($time,-2);
  $date_time = $year . "-" .$month.'-'.$day.' '.$hour.':'.$minute.':'.$second;
  if (validateDateTime($date_time)) {
  	$date = $date_time;
  } else {
  	$date = date('Y-m-d H:i:s');
  }
  return $date;
}
function convertTimeDate($time) {
  $year = substr($time,0,4);
  $month = substr($time,4,2);
  $day = substr($time,6,2);
  $hour = substr($time,8,2);
  $minute = substr($time,10,2);
  $second = substr($time,-2);
  $date_time = $year . "-" .$month.'-'.$day.' '.$hour.':'.$minute.':'.$second;
  if (validateDateTime($date_time)) {
  	$date = $year.$month.$day;
  } else {
  	$date = date('Ymd');
  }
  return $date;
}
function convertTimeDateOnly($time) {
  $year = substr($time,0,4);
  $month = substr($time,4,2);
  $day = substr($time,6,2);
  $hour = substr($time,8,2);
  $minute = substr($time,10,2);
  $second = substr($time,-2);
  $date_time = $year . "-" .$month.'-'.$day.' '.$hour.':'.$minute.':'.$second;
  if (validateDateTime($date_time)) {
  	$date = $year . "-" .$month.'-'.$day;
  } else {
  	$date = date('Y-m-d');
  }
  return $date;
}
	//doc route
	$app->get('/bjbs/doc',function() use ($db) {
		include "doc.php";
	});

	$status_token = $db->fetch_custom_single("SELECT enable_token_read,enable_token_create,enable_token_update,enable_token_delete,format_data,id_service FROM sys_services INNER JOIN sys_token ON sys_services.id=sys_token.id_service where nav_act=?",array("nav_act" => "bjbs"));
	
	//login action
	$app->post('/bjbs/login', function() use ($app,$db,$status_token) {
		auth_data($app,$db,$status_token->format_data);
	});
	
	$read_auth = ($status_token->enable_token_read=="Y")?$authenticate($status_token->format_data,$status_token->id_service,"read"):"noauth";

//payment
	//auth status
	$create_auth = ($status_token->enable_token_create=="Y")?$authenticate($status_token->format_data,$status_token->id_service,"create"):"noauth";
	//post mega-syariah
	$app->post('/bjbs/payment',$create_auth, function() use ($app,$db,$apiClass,$status_token) {
	 		$app = \Slim\Slim::getInstance();
	 		$request = $app->request();
	 		
    $validation = array(
    "nim" => array(
              "type" => "notEmpty",
              "alias" => "nim",
              "value" => $request->post("nim"),
              "allownull" => "no",
    ),
    "no_va" => array(
              "type" => "no",
              "alias" => "no_va",
              "value" => $request->post("no_va"),
              "allownull" => "",
    ),
    "trxDateTime" => array(
              "type" => "no",
              "alias" => "trxDateTime",
              "value" => $request->post("trxDateTime"),
              "allownull" => "",
    ),
    "paymentAmount" => array(
              "type" => "no",
              "alias" => "paymentAmount",
              "value" => $request->post("paymentAmount"),
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

			$typeInq = $_POST['no_va'];
		$bill_amount = 0;
		$index = 0;
		foreach ($data as $dt) {
		if ($index==0) {
			$tanggal_awal = $dt->tanggal_awal;
			$tanggal_akhir = $dt->tanggal_akhir;
		}
		$bill_amount += $dt->billAmount;
    $row = array(
        'no_va' => $typeInq,
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
    'tgl_bayar' => convertTime($_POST['trxDateTime']),
    'tgl_validasi' => convertTime($_POST['trxDateTime']),
    'created_by' => 'BJBS API',
    'nominal_bayar' => $dt->billAmount
);
	//result data
			array_push($response_billDetails['billDetails'],$bill_detail);
			$index++;

		}
	$insert_mega_history = array(
		'nim' => $dt->nim,
		'no_va' => $_POST['no_va'],
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
					    'date_created' => convertTimeDateOnly($_POST['trxDateTime'])
					  )
					);

					if ($urutan_bayar_prodi) {
					  $urutan = $urutan_bayar_prodi->urutan_bayar;
					  $urutan = $urutan+1;
					  if ($urutan<10) {
					    $no_kwitansi = convertTimeDate($_POST['trxDateTime']).$kode_jurusan.'000'.($urutan);
					  } else {
					    $no_kwitansi = convertTimeDate($_POST['trxDateTime']).$kode_jurusan.($urutan);
					  }
					} else {
					  $no_kwitansi = convertTimeDate($_POST['trxDateTime']).$kode_jurusan.'0001';
					  $urutan = 1;
					}

					
				$row['TransactionID'] = $no_kwitansi;
				//$row['UserLogin'] = '';
				//$row['Password'] = '';
				$row['paymentAmount'] = $_POST['paymentAmount'];
				$row['numBill'] = $index;
				$row['errorCode'] = '00';
			$row['statusDescription'] = 'Success';
			$insert_mega_history['error_desc'] = 'Success';
			$insert_mega_history['TransactionID'] = $no_kwitansi;

			$data_quitansi = array(
			              'nominal_bayar' => $_POST['paymentAmount'],
			              'total_tagihan' => $_POST['paymentAmount'],
			              'validator' => 'H2H BJBS Syariah',
			              'metode_bayar' => 3,
			              'id_bank' => 02,
			              'kode_jur' => $kode_jurusan,
			              'no_kwitansi' => $no_kwitansi,
			              'urutan_bayar' => $urutan,
			              'date_created' => convertTime($_POST['trxDateTime'])
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

	