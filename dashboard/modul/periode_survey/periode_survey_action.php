<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  case "in":
    

  $data = array(
      "id_semester" => $_POST["tahun"].$_POST["id_jns_semester"],
      "periode_awal_mulai" => $_POST["periode_awal_mulai"],
      "periode_awal_selesai" => $_POST["periode_awal_selesai"],
      "periode_tengah_mulai" => $_POST["periode_tengah_mulai"],
      "periode_tengah_selesai" => $_POST["periode_tengah_selesai"],
      "periode_akhir_mulai" => $_POST["periode_akhir_mulai"],
      "periode_akhir_selesai" => $_POST["periode_akhir_selesai"],
      "periode_lainya_mulai" => $_POST["periode_lainya_mulai"],
      "periode_lainya_selesai" => $_POST["periode_lainya_selesai"],
  );
  
  
    // Target URL
    $url = "https://survei.iainkerinci.ac.id/angket/modul/periode_survey/input_periode.php"; // Replace with the actual endpoint

    // Initialize cURL
    $ch = curl_init($url);

    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json', 
        'Accept: application/json'
    ));

    // Execute cURL request
    $response = curl_exec($ch);

    // Check for errors
    if (curl_errno($ch)) {
        echo "cURL Error: " . curl_error($ch);
    }

    // Close cURL session
    curl_close($ch);
  
   
    $in = $db->insert("semester_survey",$data);
    
    
    action_response($db->getErrorMessage());
    break;
  case "delete":
    
    
    
    $db->delete("semester_survey","id_sem_survey",$_GET["id"]);
    action_response($db->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("semester_survey","id_sem_survey",$id);
         }
    }
    action_response($db->getErrorMessage());
    break;
  case "up":
    
  $data = array(
      "id_semester" => $_POST["id_semester"],
      "periode_awal_mulai" => $_POST["periode_awal_mulai"],
      "periode_awal_selesai" => $_POST["periode_awal_selesai"],
      "periode_tengah_mulai" => $_POST["periode_tengah_mulai"],
      "periode_tengah_selesai" => $_POST["periode_tengah_selesai"],
      "periode_akhir_mulai" => $_POST["periode_akhir_mulai"],
      "periode_akhir_selesai" => $_POST["periode_akhir_selesai"],
      "periode_lainya_mulai" => $_POST["periode_lainya_mulai"],
      "periode_lainya_selesai" => $_POST["periode_lainya_selesai"],
  );


  
    // Target URL
    $url = "https://survei.iainkerinci.ac.id/angket/modul/periode_survey/input_periode.php"; // Replace with the actual endpoint

    // Initialize cURL
    $ch = curl_init($url);

    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json', 
        'Accept: application/json'
    ));

    // Execute cURL request
    $response = curl_exec($ch);

    // Check for errors
    if (curl_errno($ch)) {
        echo "cURL Error: " . curl_error($ch);
    }

    // Close cURL session
    curl_close($ch);

    
    
    $up = $db->update("semester_survey",$data,"id_sem_survey",$_POST["id"]);
    
    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>