<?php
include '/var/www/html/dashboard/inc/config.php';
function sendCurlRequest($url, $data) {
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
        $error = "cURL Error: " . curl_error($ch);
        curl_close($ch);
        return $error;
    }

    // Close cURL session
    curl_close($ch);
    
    return $response;
} 
$data_mhs['no_daftar'] = '0071442966';
$data_mhs['nim'] = '12345';
dump($data_mhs);


                 //check if calon in calon suday survey
                $is_calon_survey = $db->fetch_custom_single("select * from calon_sudah_survey where no_daftar=? and has_nim='N'",array('no_daftar' => $data_mhs['no_daftar']));
                if ($is_calon_survey) {
                  $data = array(
                    'id_user' => $is_calon_survey->no_daftar,
                    'nim' => $data_mhs['nim']
                  );
                  sendCurlRequest('https://survei.iainkerinci.ac.id/service/update_no_daftar.php',$data);
                   $db->update("calon_sudah_survey",array('new_nim' => $data_mhs['nim'],'has_nim' => 'Y'),'no_daftar',$is_calon_survey->no_daftar);
                }

