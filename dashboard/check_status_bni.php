<?php
include "inc/config.php";

try {
    // Sanitize input to prevent SQL injection
    $trx_id = filter_var($_POST['trx_id'], FILTER_SANITIZE_STRING);
    
    // Query the database
    $check = $db->fetch_custom_single("SELECT * FROM tb_data_formulir WHERE trx_id = ?", array($trx_id));
    
    // Initialize response array
    $array_status = array(
        'status' => 1,
        'data' => array(
            'statusBayar' => ($check && $check->is_lunas === 'Y') ? 'Y' : 'N'
        )
    );
    
    // Set JSON header
    header('Content-Type: application/json');
    
    // Output JSON
    echo json_encode($array_status);
    
} catch (Exception $e) {
    // Error response
    $array_status = array(
        'status' => 0,
        'error' => 'An error occurred: ' . $e->getMessage()
    );
    
    header('Content-Type: application/json');
    echo json_encode($array_status);
}