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


$free_digit = "12387867";

$trx_id =  mt_rand();
$amount = 30000;

$array = array(
    "trx_id" => "1230837187",
    "virtual_account" => "9882889000221122",
    "customer_name" => "Kang wildantea",
    "trx_amount" => "30000",
    "payment_amount" => "30000",
    "cumulative_payment_amount" => "30000",
    "payment_ntb" => "788860",
    "datetime_payment" => "2023-09-21 06:07:52",
    "datetime_payment_iso8601" => "2023-09-21T06:07:52+07:00"
);

$hashed_string = BniEnc::encrypt(
	$array,
	$client_id,
	$secret_key
);

$data = array(
	'client_id' => $client_id,
	'data' => $hashed_string,
);

echo json_encode($data);
exit();