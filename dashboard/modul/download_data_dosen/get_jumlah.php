<?php
header("Access-Control-Allow-Origin: *");
include "../../inc/config.php";

$type_user = $_POST['type_user'];

$jumlah = $db->query("SELECT *
FROM `sys_users`
WHERE is_photo_drived='N' and foto_user!='default_user.png' AND `group_level` = '".$type_user."' and username in(select nim from mahasiswa where mulai_smt > 20181 and mulai_smt!='20241'  and status='M') and photo_not_found='N'");


if ($jumlah->rowCount() > 0) {
    $json_response["jumlah"] = $jumlah->rowCount();
} else {
    $json_response["jumlah"] = 0;
}
echo json_encode($json_response);
?>
