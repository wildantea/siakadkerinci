<?php
switch (uri_segment(2)) {
    case "tambah":
          foreach ($db->fetch_all("sys_menu") as $isi) {
               if (uri_segment(1)==$isi->url&&uri_segment(2)=="tambah") {
                          if ($role_act["insert_act"]=="Y") {
                             include "ws_briva_add.php";
                          } else {
                            echo "permission denied";
                          }
                       }

      }
    break;
  case "edit":
    $data_edit = $db->fetch_single_row("agama","id_agama",uri_segment(3));
        foreach ($db->fetch_all("sys_menu") as $isi) {
                      if (uri_segment(1)==$isi->url&&uri_segment(2)=="edit") {
                          if ($role_act["up_act"]=="Y") {
                             include "ws_briva_edit.php";
                          } else {
                            echo "permission denied";
                          }
                       }

      }

    break;

    case "update_briva":
       include "ws_briva_update.php";
      break;

    case "create":
     include "ws_briva_create.php";
      break;
    case "get_status":
     include "ws_briva_get.php";
      break;

     case "get_report":
     include "ws_briva_report.php";
      break;
    case "delete_briva":
     include "ws_briva_delete.php"; 
      break;
    case "detail":
    $data_edit = $db->fetch_single_row("agama","id_agama",uri_segment(3));
    include "ws_briva_detail.php";
    break;
    default:
    include "ws_briva_view.php";
    break;
}

?>