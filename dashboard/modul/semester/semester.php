<?php
switch (uri_segment(2)) {
    case "tambah":
          foreach ($db->fetch_all("sys_menu") as $isi) {
               if (uri_segment(1)==$isi->url&&uri_segment(2)=="tambah") {
                          if ($role_act["insert_act"]=="Y") {
                             include "semester_add.php";
                          } else {
                            echo "permission denied";
                          }
                       }

      }
    break;
  case "edit":
    $data_edit = $db->fetch_custom_single("select semester_ref.id_semester, semester_ref.id_jns_semester,semester_ref.tahun,jenis_semester.jns_semester,concat(tahun,'/',tahun+1,' ',jns_semester) as tahun_akademik,semester_ref.*
 from semester_ref inner join jenis_semester on semester_ref.id_jns_semester=jenis_semester.id_jns_semester
where semester_ref.id_semester=?
",array("semester_ref.id_semester" =>uri_segment(3)));


        foreach ($db->fetch_all("sys_menu") as $isi) {
                      if (uri_segment(1)==$isi->url&&uri_segment(2)=="edit") {
                          if ($role_act["up_act"]=="Y") {
                             include "semester_edit.php";
                          } else {
                            echo "permission denied";
                          }
                       }

      }

    break;
    case "detail":
    $data_edit = $db->fetch_custom_single("select semester_ref.id_semester, semester_ref.id_jns_semester,semester_ref.tahun,jenis_semester.jns_semester,concat(tahun,'/',tahun+1,' ',jns_semester) as tahun_akademik,semester_ref.*
 from semester_ref inner join jenis_semester on semester_ref.id_jns_semester=jenis_semester.id_jns_semester
where semester_ref.id_semester=?
",array("semester_ref.id_semester" =>uri_segment(3)));
    include "semester_detail.php";
    break;
    default:
    include "semester_view.php";
    break;
}

?>