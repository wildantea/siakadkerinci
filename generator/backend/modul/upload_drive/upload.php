<?php
session_start();
include "../../inc/lib/vendor/autoload.php";
include "../../inc/config.php";
dump($_SESSION);
// setting config untuk layanan akses ke google drive
$client = new Google_Client();
$client->setAuthConfig("oauth-credentials.json");
$client->addScope("https://www.googleapis.com/auth/drive");
$service = new Google_Service_Drive($client);
 
// proses membaca token pasca login
if (isset($_GET['code'])) {
  $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
  // simpan token ke session
  $_SESSION['upload_token'] = $token;
}
 
?>