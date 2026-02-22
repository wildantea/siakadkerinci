<?php
include "inc/config.php";


class BniEnc
{

	const TIME_DIFF_LIMIT = 480;

	public static function encrypt(array $json_data, $cid, $secret) {
		return self::doubleEncrypt(strrev(time()) . '.' . json_encode($json_data), $cid, $secret);
	}

	public static function decrypt($hased_string, $cid, $secret) {
		$parsed_string = self::doubleDecrypt($hased_string, $cid, $secret);
		list($timestamp, $data) = array_pad(explode('.', $parsed_string, 2), 2, null);
		if (self::tsDiff(strrev($timestamp)) === true) {
			return json_decode($data, true);
		}
		return null;
	}

	private static function tsDiff($ts) {
		return abs($ts - time()) <= self::TIME_DIFF_LIMIT;
	}

	private static function doubleEncrypt($string, $cid, $secret) {
		$result = '';
		$result = self::enc($string, $cid);
		$result = self::enc($result, $secret);
		return strtr(rtrim(base64_encode($result), '='), '+/', '-_');
	}

	private static function enc($string, $key) {
		$result = '';
		$strls = strlen($string);
		$strlk = strlen($key);
		for($i = 0; $i < $strls; $i++) {
			$char = substr($string, $i, 1);
			$keychar = substr($key, ($i % $strlk) - 1, 1);
			$char = chr((ord($char) + ord($keychar)) % 128);
			$result .= $char;
		}
		return $result;
	}

	private static function doubleDecrypt($string, $cid, $secret) {
		$result = base64_decode(strtr(str_pad($string, ceil(strlen($string) / 4) * 4, '=', STR_PAD_RIGHT), '-_', '+/'));
		$result = self::dec($result, $cid);
		$result = self::dec($result, $secret);
		return $result;
	}

	private static function dec($string, $key) {
		$result = '';
		$strls = strlen($string);
		$strlk = strlen($key);
		for($i = 0; $i < $strls; $i++) {
			$char = substr($string, $i, 1);
			$keychar = substr($key, ($i % $strlk) - 1, 1);
			$char = chr(((ord($char) - ord($keychar)) + 256) % 128);
			$result .= $char;
		}
		return $result;
	}

}



$client_id = "38851";
$secret_key = "5968d057bb1200356aaf1b91da094e12";

$data_asli = array(
          'client_id' => $client_id,
          'trx_amount' => 1000,
          'customer_name' => 'UNYIL TEA',
          'customer_email' => 'unyilte4@gmail.com',
          'customer_phone' => '081395088383',
          'virtual_account' => '9883885125930816',
          'trx_id' => '17510436001232371532',
          'datetime_expired' => '2025-06-29 11:37:50', // billing will expire in 2 hours
          'description' => 'Formulir PMB',
          'billing_type' => 'c',
          'type' => 'createBilling'
      );


 $hashed_string = BniEnc::encrypt(
        $data_asli,
        $client_id,
        $secret_key
      );

dump($hashed_string);


$data = "FhspISVFFCImSEFfDEtRTU1cDXUASDwrCEhMICYaOBAMYWBfSn1VWV0DDgshAxQbHRs0QEBMDF1YCwZKXEYLSlRLDigSbi9BOGY3NiopChQITA4MD1ECVlt4d1BSVQYEHw9mWWIAVGFRR1VQV0ZKUhtRBX87FTxRVwoNV1tKCkhaUllXVT4fCBpSSxkcGBgjIhpMTEASNWJSDgpWTVh8R0ZLYGNbCQUnEVBLHB0gHRkZIE5PShRSIhw3QAplWw5DU1EQIQ1NHx8ZRU4cHRETHB8eREdVGkwdFj5FB05IEU5bT1lTT35ZWFgMfEYHIgoaFhtORksZQiEaOUMUKx1KHBggExcLe01gTwV-WV5OUFQPKDhYCFsHY04ACwg7JmYtMzwzCUJhLDs2X2wIDwVKVFZTfQcFRQdlWQE4Gw5PPxIFXGpeUjcdD1IJeEVeTSdNVFoCBH4GFw";
$data2 = 'FB8kIyZFFCImSEFfDEtRTU1cDXUASDwrCEhMICYaOBAMYWBfSn1VWV0DDgshEhMbHRc0dxNcC1lRAQtEWEgKTgkgDkM-cio0D25cIwcUCktbXA0ICEcHUE4Gc0xdDlQEWltqVFULTSEsegJKU1EPSVxbOD47TA9hVgYGTWBECFFZWE8LKj4VHhtNUxsTGyAeIho2RUBcfF5dEXdNS00ASVJdX2IPTwUmJ09GHCIdFhYdJ0xGTxVQExI3CFppSH9IDCcQGCJRGRocSFAZFxIVHh8eSUVTHEkMED59Rl5MEVJUS0tTaAlKWlR-ORwHGhgaGxZJT0gUThEaSkwWKCZPEgcZE09OCktfVQMJUllTAyAPNAUEBl4GV1Q3aTUwB0QLTFNWVVkKTEVeEwpLBR0KTgwTNg0XVngOIz55U1FNEUslUV1aVgNKD2w';
$data3 = 'FiAlIyZFFCImSEFfDEtRTU1cDXUASDwrCEhMICYaOBAMYWBfSn1VWV0DDgshAxQbHRs0QEBMDF1YCwZKXEYLSlRLDigSbi9BOGY3NiopChQITA4MD1ECVlt4d1BSVQYEHw9mWWIAVGFRR1VQV0ZKUhtRBX87FTxRVwoNV1tKCkhaUllXVT4fCBpSSxkcGBgjIhpMTEASNWJSDgpWTVh8R0ZLYGNbCQUnEVBLHB0gHRkZIE5PShRSIhw3QAplWw5DU1EQIQ1NHx8ZRU4cHRETHCYfREpRH0gjFD5FB05IEU5bT1lTT35ZWFgMfEYHIgoaFhtORksZQiEaOUMXKx5OHBkgExcLe01gTwV-WV5OUFQPKDhYCFsHY04ACwg7JmYtMzwzCUJhLDs2X2wIDwVKVFZTfQcFRQdlWQE4Gw5PPxIFXGpeUjcdD1IJeEVeTSdNVFoCBH4GFw';

$data_asli = BniEnc::decrypt($data3, $client_id, $secret_key);
var_dump($data_asli);