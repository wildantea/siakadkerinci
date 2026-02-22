<?php
session_start();
include "../../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  case "in":
  $data = array(
      "id_mk" => $_POST["id_mk"],
      "id_mk_prasyarat" => $_POST["id_mk_prasyarat"],
      "syarat" => $_POST["syarat"]
  );
  //dump($data);
    $in = $db->insert("prasyarat_mk",$data);

    action_response($db->getErrorMessage());
    break;
  case "delete":
    $id = explode("#", $_POST['id']);
    $id_mk = $id[0];
    $id_syarat = $id[1];
    $db->query("delete from prasyarat_mk where id_mk='$id_mk' and id_mk_prasyarat='$id_syarat'");
    action_response($db->getErrorMessage());
    break;
}

?>