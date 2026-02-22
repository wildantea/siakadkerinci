<?php
include "inc/config.php";
include "modul/tagihan_kuliah/bni.php";

$va = $prefix . $client_id . $_POST['no_pendaftaran'];

if ($_POST['password']=='bnisecret2025') {
	$trx_id = strtotime(date('Y-m-d')).rand();  
	$va = $prefix . $client_id . $_POST['no_pendaftaran'];
	

      $data_asli = array(
          'client_id' => $client_id,
          'trx_amount' => $_POST['nominal'],
          'customer_name' => $_POST['nama'],
          'customer_email' => $_POST['email'],
          'customer_phone' => $_POST['no_hp'],
          'virtual_account' => $va,
          'trx_id' => $trx_id,
          'datetime_expired' => $_POST['exp_date'], // billing will expire in 2 hours
          'description' => $_POST['keterangan'],
          'billing_type' => 'c',
          'type' => 'createBilling'
      );

      $hashed_string = BniEnc::encrypt(
        $data_asli,
        $client_id,
        $secret_key
      );

      $data = array(
        'client_id' => $client_id,
        'prefix' => $prefix,
        'data' => $hashed_string,
      );

  
      $response = get_content($url, json_encode($data));
      $response_json = json_decode($response, true);
        if ($response_json['status']=='000') {
        	$data_formulir = array(
				'nominal' => $_POST['nominal'],
				'no_pendaftaran' => $_POST['no_pendaftaran'],
				'nama' => $_POST['nama'],
				'email' => $_POST['email'],
				'no_hp' => $_POST['no_hp'],
				'no_va' => $va,
				'trx_id' => $trx_id,
				'created' => date('Y-m-d H:i:s'),
				'id_bank' => '04',
				'exp_date' => $_POST['exp_date'],
				'bni_attr' => $hashed_string
			);

			$db->insert('tb_data_formulir',$data_formulir);
           echo json_encode(array('va' => $va,'trx_id' => $trx_id,'status' => '000','error_message' => 'good'));
        } else {
          echo json_encode(array('status' => $response_json['status'],'error_message' => $response_json['message']));
        }

}