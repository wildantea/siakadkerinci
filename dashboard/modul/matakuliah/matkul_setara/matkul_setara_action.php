<?php
session_start();
include "../../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  case "in":
  $data = array(
      "id_matkul_lama" => $_POST["id_matkul"],
      "id_matkul_baru" => $_POST["id_matkul_setara"]
  );
  $in = $db->insert("matkul_setara",$data);

    if(isset($_POST["bolak_balik"])=="on")
    {
    $data_balik = array(
        "id_matkul_lama" => $_POST["id_matkul_setara"],
        "id_matkul_baru" => $_POST["id_matkul"]
    );
    $in = $db->insert("matkul_setara",$data_balik);
    }


    action_response($db->getErrorMessage());
    break;
  case "delete":
    $id = explode("#", $_POST['id']);
    $id_mk = $id[0];
    $id_syarat = $id[1];
    $db->query("delete from matkul_setara where id_matkul_lama='$id_mk' and id_matkul_baru='$id_syarat'");
    //$db->query("delete from matkul_setara where id_matkul='$id_syarat' and id_matkul_setara='$id_mk'");
    action_response($db->getErrorMessage());
    break;
}

?>