<?php
include 'inc/config.php';
$no_briva = $_GET['no_briva'];
$status = $_GET['status']; 
 //$res = updateStatusBayar($no_briva,$status); 
// $res2 = getStatusBriva($no_briva);
 $res3 = get_report_briva("20220107","20220108"); 
  echo "<pre>";
print_r($res3); 
// print_r($res);
// print_r($res2);    
?> 