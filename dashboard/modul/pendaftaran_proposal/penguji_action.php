<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  case "up":
 $db->query("delete from tb_penguji where id_pendaftar=?",array('id_pendaftar' => $_POST['id_pendaftar']));
  $counter = 0;
  foreach ($_POST['dosen_penguji'] as $ds) {
    $data_insert = array(
      'id_pendaftar' => $_POST['id_pendaftar'],
      'penguji_ke' => $_POST['penguji_ke'][$counter],
      'nip_dosen' => $_POST['dosen_penguji'][$counter],
    );
    $counter++;
    $db->insert('tb_penguji',$data_insert);
  }
  $db->update('tb_data_pendaftaran',array('tanggal_seminar' => $_POST['tanggal_seminar']),'id',$_POST['id_pendaftar']);
    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>