<?php
switch (uri_segment(1)) {
    case "create":
          if ($db->userCan("insert")) {
             include "downloaders_add.php";
          } 
      break;
    case "edit":
    $data_edit = $db->fetchSingleRow("mhs","id",uri_segment(2));
          if ($db->userCan("update")) {
             include "downloaders_edit.php";
          } 
      break;
      
    case "detail":
    $data_edit = $db->fetchSingleRow("mhs","id",uri_segment(2));
    include "downloaders_detail.php";
    break;
    default:
    include "downloaders_view.php";
    break;
}

?>