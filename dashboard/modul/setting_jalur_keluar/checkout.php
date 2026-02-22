<?php
      session_start();
      include "../../inc/config.php";

      $id_jns_keluar = $_POST["id_jns_keluar"];

      $check = $db->check_exist('jenis_keluar',array('id_jns_keluar'=>$id_jns_keluar));

      if($check > 0) {
      	echo 
            "
            <div class='form-group'>
                  <label class='control-label col-md-5' style='color: red;'>
                        Id jenis keluar telah digunakan.
                  </label>
            </div>
            ";
      } else{
      	echo 
            "
            <div class='form-group'>
                  <label class='control-label col-md-5' style='color: green;'>
                        Id jenis keluar dapat digunakan.
                  </label>
            </div>
            ";
      }
 ?>
      