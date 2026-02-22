<?php
session_start();
include "../../inc/config.php";
session_check();
if ($_SESSION['group_level']!='admin') {
    if ($_POST['sks_mk']+$_POST['sks_dosen'] > 16) {
        echo "Maaf SKS Dosen ini melebihi 16 SKS";
    } else {
    $dosen = $db->fetch_custom_single("select * from view_dosen where id_dosen ='".$_POST['id_dosen']."'");
        echo "<tr class='komponen_list'>                     
                          <td>$dosen->nip <input type='hidden' name='dosen[]' value='$dosen->id_dosen'></td>
                          <td>$dosen->dosen</td>
                          <td>$dosen->jurusan_dosen</td>  
                          <td><input type='text' required style='width:100px' name='dosen_ke[]'></td>
                          <td><input type='text' required style='width:100px' name='jml_tm_renc[]'></td>                  
                          <td><span class='btn btn-danger hapus_komponen_dosen btn-sm'><i class='fa fa-minus'></i></span></td>
                         </tr>";
    }
} else {
    $dosen = $db->fetch_custom_single("select * from view_dosen where id_dosen ='".$_POST['id_dosen']."'");
        echo "<tr class='komponen_list'>                     
                          <td>$dosen->nip <input type='hidden' name='dosen[]' value='$dosen->id_dosen'></td>
                          <td>$dosen->dosen</td>
                          <td>$dosen->jurusan_dosen</td>  
                          <td><input type='text' required style='width:100px' name='dosen_ke[]'></td>
                          <td><input type='text' required style='width:100px' name='jml_tm_renc[]'></td>                  
                          <td><span class='btn btn-danger hapus_komponen_dosen btn-sm'><i class='fa fa-minus'></i></span></td>
                         </tr>";
}


?>