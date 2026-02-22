<?php
error_reporting(0);
switch (uri_segment(2)) {
    case "detail":
    $data_edit = $db->fetch_single_row("mahasiswa","mhs_id",uri_segment(3));
    include "akm_detail.php";
    break;
    default:
     if ($_SESSION['level']=='1') {
       include "akm_view.php";
    }
     elseif ($_SESSION['level']=='6') {
       include "akm_view_fak.php";
    }
    elseif ($_SESSION['level']=='5') {
       include "akm_view_jur.php";
    }
  
    break;
}

?>