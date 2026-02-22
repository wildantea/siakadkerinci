<?php
session_start();
include "../../inc/config.php";
session_check();
$q = $db->query("select d.id_dosen, d.nip,d.nama_dosen,j.nama_jur from dosen d 
join jurusan j on d.kode_jur=j.kode_jur where id_dosen ='".$_POST['nip']."' ");
foreach ($q as $k) {
	echo "<tr id='dosen_".$_POST['jml_dosen']."'>                     
                      <td> <input type='hidden' name='dosen_".$_POST['jml_dosen']."' value='$k->nip'>$k->nip</td>
                      <td>$k->nama_dosen</td>
                      <td>$k->nama_jur</td>  
                      <td><input type='text' required style='width:100px' name='dosen_input_".$_POST['jml_dosen']."'><td>                  
                      <td><input type='checkbox' value='Y' name='dapat_input_".$_POST['jml_dosen']."' checked><button onclick='hapus_dosen(".$_POST['jml_dosen'].")' type='submit' class='btn btn-primary ' style='float:right' ><i class='fa fa-minus'></i></button></td>
                     </tr>";
}
?>