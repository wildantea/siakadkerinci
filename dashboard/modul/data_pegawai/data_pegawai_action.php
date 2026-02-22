<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  case "in":
    
  
  if (!is_dir("../../../upload/data_pegawai")) {
              mkdir("../../../upload/data_pegawai");
            }
  
  $data = array(
      "nip" => $_POST["nip"],
      "nama_pegawai" => $_POST["nama_pegawai"],
      "gelar_depan" => $_POST["gelar_depan"],
      "gelar_belakang" => $_POST["gelar_belakang"],
      "no_hp" => $_POST["no_hp"],
      "email" => $_POST["email"],
      "alamat" => $_POST["alamat"],
      "jk" => $_POST["jk"],
  );
  
  
                    if (!preg_match("/.(png|jpg|jpeg|gif|bmp)$/i", $_FILES["foto"]["name"]) ) {

              echo "pastikan file yang anda pilih png|jpg|jpeg|gif";
              exit();

            } else {
$db->compressImage($_FILES["foto"]["type"],$_FILES["foto"]["tmp_name"],"../../../upload/data_pegawai/",$_FILES["foto"]["name"],400,600);
            $foto = array("foto"=>$_FILES["foto"]["name"]);
              $data = array_merge($data,$foto);
            }
  
   
    $in = $db->insert("pegawai",$data);
    
    
    action_response($db->getErrorMessage());
    break;
  case "delete":
    
    
    $db->deleteDirectory("../../../upload/data_pegawai/".$db->fetch_single_row("pegawai","id",$_POST["id"])->foto);
    
    $db->delete("pegawai","id",$_GET["id"]);
    action_response($db->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("pegawai","id",$id);
         }
    }
    action_response($db->getErrorMessage());
    break;
  case "up":
    
   $data = array(
      "nip" => $_POST["nip"],
      "nama_pegawai" => $_POST["nama_pegawai"],
      "gelar_depan" => $_POST["gelar_depan"],
      "gelar_belakang" => $_POST["gelar_belakang"],
      "no_hp" => $_POST["no_hp"],
      "email" => $_POST["email"],
      "alamat" => $_POST["alamat"],
      "jk" => $_POST["jk"],
   );
   
   
                         if(isset($_FILES["foto"]["name"])) {
                        if (!preg_match("/.(png|jpg|jpeg|gif|bmp)$/i", $_FILES["foto"]["name"]) ) {

              echo "pastikan file yang anda pilih gambar";
              exit();

            } else {
$db->compressImage($_FILES["foto"]["type"],$_FILES["foto"]["tmp_name"],"../../../upload/data_pegawai/",$_FILES["foto"]["name"],400,600);
              $db->deleteDirectory("../../../upload/data_pegawai/".$db->fetch_single_row("pegawai","id",$_POST["id"])->foto);
              $foto = array("foto"=>$_FILES["foto"]["name"]);
              $data = array_merge($data,$foto);
            }

                         }
   

    
    
    $up = $db->update("pegawai",$data,"id",$_POST["id"]);
    
    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>