<?php
session_start();
require "../../inc/config.php";
 $foto = $db2->fetchSingleRow("view_simple_mhs", "mhs_id", $_POST['id']);
 $fotos = $db2->fetchSingleRow("sys_users", "username", $foto->nim);
 
  if ($fotos->is_photo_drived=='Y') {
       echo '<img src="'.$fotos->foto_user.'">';
    } else {
    	?>
    	<img src="<?php echo base_url()."upload/back_profil_foto/".$fotos->foto_user;?>">
    	<?php
    }


?>

