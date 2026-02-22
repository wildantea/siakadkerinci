<?php
session_start();
include "../../../inc/config.php";
//session_check_json();

include "BniEnc.php";

function get_content($url, $post = '') {
	$usecookie = __DIR__ . "/cookie.txt";
	$header[] = 'Content-Type: application/json';
	$header[] = "Accept-Encoding: gzip, deflate";
	$header[] = "Cache-Control: max-age=0";
	$header[] = "Connection: keep-alive";
	$header[] = "Accept-Language: en-US,en;q=0.8,id;q=0.6";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_VERBOSE, false);
	// curl_setopt($ch, CURLOPT_NOBODY, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_ENCODING, true);
	curl_setopt($ch, CURLOPT_AUTOREFERER, true);
	curl_setopt($ch, CURLOPT_MAXREDIRS, 5);

	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.120 Safari/537.36");

	if ($post)
	{
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	}

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	$rs = curl_exec($ch);

	if(empty($rs)){
		var_dump($rs, curl_error($ch));
		curl_close($ch);
		return false;
	}
	curl_close($ch);
	return $rs;
}





// FROM BNI
$client_id = '28890';
$secret_key = '9445051426bb163df98acebf18e86180';
$url = 'https://apibeta.bni-ecollection.com/';
$prefix = '988';

//production
$client_id = '38851';
$secret_key = '5968d057bb1200356aaf1b91da094e12';
$url = 'https://api.bni-ecollection.com/';
$prefix = '988';


$free_digit = "025230019";

$trx_id =  mt_rand();
$amount = 4700000;

$data_asli = array(
    'client_id' => $client_id,
    'virtual_account' => "9883885125211029",
    'trx_id' => '17518212001434830589', // fill with Billing ID
    'type' => 'inquirybilling'
);


// Print the array as a formatted JSON string



echo "<H1>REQUEST JSON</H1>";
dump($data_asli);

echo "why";





$data_callback = array(
    "virtual_account" => $prefix.$client_id.'10201065',
    "customer_name" =>  'Mr. wildan',
    "trx_id" =>  $trx_id,
    "trx_amount" =>  $amount,
    "payment_amount" =>  $amount,
    "cumulative_payment_amount" =>  $amount,
    "payment_ntb" =>  '',
    "datetime_payment" =>  '',
    "datetime_payment_iso8601" => ''
);
//$db->insert("bni_temp_payment",$data_callback);



$hashed_string = BniEnc::encrypt(
	$data_asli,
	$client_id,
	$secret_key
);



/*$data_callback = array(
    "virtual_account" => "9882889010201065",
    "customer_name" => "Mr. wildan",
    "trx_id" => "643616268",
    "trx_amount" => "120000",
    "payment_amount" => "120000",
    "cumulative_payment_amount" => "120000",
    "payment_ntb" => "233171",
    "datetime_payment" => "2024-09-04 14:00:00",
    "datetime_payment_iso8601" => "2024-09-04T14:00:00+07:00"
);


$hashed_string_callback = BniEnc::encrypt(
	$data_callback,
	$client_id,
	$secret_key
);

dump($hashed_string_callback);
exit();*/



$data = array(
	'client_id' => $client_id,
	'prefix' => $prefix,
	'data' => $hashed_string,
);

$response = get_content($url, json_encode($data));
$response_json = json_decode($response, true);

echo "<BR><H1>RESPONSE ARRAY</H1>";
if ($response_json['status'] !== '000') {
	// handling jika gagal
	var_dump($response_json);
}
else {
	$data_response = BniEnc::decrypt($response_json['data'], $client_id, $secret_key);
	// $data_response will contains something like this: 
	// array(
	// 	'virtual_account' => 'xxxxx',
	// 	'trx_id' => 'xxx',
	// );
	dump($data_response);
}