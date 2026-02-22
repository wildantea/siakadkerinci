<?php
session_start();
include "../../inc/config.php";
    //if filter session enable
    if (getPengaturan('filter_session')=='Y') {
      $array_filter = array(
        'show_filter' => $_POST['show_filter']
      );
      setFilter($_POST['filter_name'],$array_filter);
    }
?>