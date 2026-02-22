<?php
  session_start();
  include "../../inc/config.php";

  $nim = $_POST['nim'];
  $data = $db->query("select * from tugas_akhir ta inner join mahasiswa m on ta.nim=m.nim where ta.nim=?",
  array("ta.nim" => $nim));

  foreach ($data as $ky) {
    $data = array(
      'judul_ta' => $ky['judul_ta']
    );
  }
  echo json_encode($data);
?>
