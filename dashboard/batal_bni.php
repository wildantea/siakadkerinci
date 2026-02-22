<?php
include "inc/config.php";
include "modul/tagihan_kuliah/bni.php";

if ($_POST['password']=='bnisecret2025') {
        $data_asli_update = array(
            'client_id' => $client_id,
            'trx_amount' => $_POST['nominal'],
            'customer_name' => $_POST['nama'],
            'customer_email' => $_POST['email'],
            'customer_phone' => $_POST['no_hp'],
            'virtual_account' => $_POST['va'],
            'trx_id' => $_POST['trx_id'],
            'datetime_expired' => date('Y-m-d H:i:s', strtotime('-3 days')),
            'billing_type' => 'i',
            'type' => 'updatebilling'
        );
        $update_formulir = array(
          'is_active' => 'N',
          'exp_date' => date('Y-m-d H:i:s', strtotime('-3 days'))
        );
        $db->update('tb_data_formulir',$update_formulir,'trx_id',$_POST['trx_id']);
        echo $db->getErrorMessage();

        $hashed_string = BniEnc::encrypt(
          $data_asli_update,
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
           echo json_encode(array('status' => '000','error_message' => 'good'));
        } else {
          echo json_encode(array('status' => $response_json['status'],'error_message' => $response_json['message']));
        }
       
}