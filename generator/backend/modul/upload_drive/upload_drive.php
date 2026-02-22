<?php

switch (uri_segment(1)) {
    case "create":
          if ($db->userCan("insert")) {
             include "upload_drive_add.php";
          } 
      break;
    case "edit":
    $data_edit = $db->fetchSingleRow("tes","id",uri_segment(2));
          if ($db->userCan("update")) {
             include "upload_drive_edit.php";
          } 
      break;
      
    case "detail":
    $data_edit = $db->fetchSingleRow("tes","id",uri_segment(2));
    include "upload_drive_detail.php";
    break;
    case 'auth':

    // setting config untuk layanan akses ke google drive
    $client = new Google_Client();
    $client->setAuthConfig("modul/upload_drive/oauth-credentials.json");
    $client->addScope("https://www.googleapis.com/auth/drive");
    $service = new Google_Service_Drive($client);
       include "upload.php";
        break;
    default:

    include "upload_drive_view.php";
    break;
}

?>