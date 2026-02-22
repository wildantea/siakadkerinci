<?php
session_start();
include "../../inc/config.php";
session_check();
$q = $db->query("select * from view_dosen where id_dosen ='".$_POST['id_dosen']."'");
foreach ($q as $k) {
	echo "<tr class='komponen_list'>                     
                      <td>$k->nip <input type='hidden' name='dosen[]' value='$k->id_dosen'></td>
                      <td>$k->dosen</td>
                      <td>$k->jurusan_dosen</td>  
                      <td><input type='text' required style='width:100px' name='dosen_ke[]'></td>
                      <td><input type='text' required style='width:100px' name='jml_tm_renc[]'></td>                  
                      <td><span class='btn btn-danger hapus_komponen_dosen btn-sm'><i class='fa fa-minus'></i></span></td>
                     </tr>";
}
?>