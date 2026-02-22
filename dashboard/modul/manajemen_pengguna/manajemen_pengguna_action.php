<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
    case 'reset':
  $data = array('password'=>md5($_POST['password_baru']));
      $up = $db->update("sys_users",$data,"id",$_POST["id"]);
    if ($up=true) {
       $q = $db->query("select username from sys_users where id='".$_POST["id"]."' ");
       foreach ($q as $k) {
         $username = $k->username;
       }
      $curl = curl_init(); 
      curl_setopt_array($curl, array(
                  CURLOPT_URL => "https://sip.uinsgd.ac.id/sip_module/ws/reset_password",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_SSL_VERIFYHOST => 0, 
                  CURLOPT_SSL_VERIFYPEER => 0, 
                  CURLOPT_TIMEOUT => 30,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => "POST",
                  CURLOPT_POSTFIELDS => "nip=".$username."&password=".$_POST['password_baru']."&token=2y10bJ09e9jzVxNjKe8wis8eIgIUSQi0rrgQGmck313jL0mNJK9G",  
                  CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/x-www-form-urlencoded",
                    "Postman-Token: 1e2411b7-262f-4df1-af5b-88ac11c7a20a",
                    "cache-control: no-cache",
                    "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW"
                  ),
                )); 

                $response = json_decode(curl_exec($curl));
                $err = curl_error($curl);
                curl_close($curl);
      echo "good";
    } else {
      return false;
    }
    break;
  case "in":
$data = array('username'=>$_POST['username']);
    $check = $db->check_exist('sys_users',$data);
    if ($check==true) {
      action_response('Maaf Username Sudah Digunakan');
    }
    $data = array('email'=>$_POST['email']);
    $check = $db->check_exist('sys_users',$data);
    if ($check==true) {
      action_response('Maaf Email Sudah Digunakan');
    }



  $data = array("first_name"=>$_POST["first_name"],"last_name"=>$_POST["last_name"],"username"=>$_POST["username"],"password"=>md5($_POST["password_baru"]),"email"=>$_POST["email"],"group_level"=>$_POST["id_group"],"date_created"=>date('Y-m-d'));

   if(isset($_POST["aktif"])=="on")
    {
      $aktif = array("aktif"=>"Y");
      $data=array_merge($data,$aktif);
    } else {
      $aktif = array("aktif"=>"N");
      $data=array_merge($data,$aktif);
    }

if(isset($_FILES["foto_user"]["name"])) {
                    if (!preg_match("/.(png|jpg|jpeg|gif|bmp)$/i", $_FILES["foto_user"]["name"]) ) {

              echo "pastikan file yang anda pilih png|jpg|jpeg|gif";

              exit();

            } else {

$db->compressImage($_FILES["foto_user"]["type"],$_FILES["foto_user"]["tmp_name"],"../../../upload/back_profil_foto/",$_FILES["foto_user"]["name"],200,200);
$foto_user = array("foto_user"=>$_FILES["foto_user"]["name"]);
              $data = array_merge($data,$foto_user);

            }
}
    $in = $db->insert("sys_users",$data);
    action_response($db->getErrorMessage());
    break;
  case "delete":
    
    
    
    $db->delete("sys_users","id",$_GET["id"]);
    action_response($db->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("sys_users","id",$id);
         }
    }
    action_response($db->getErrorMessage());
    break;
  case "up":

      $check = array('email'=>$_POST['email'],'id' => $_POST['id']);
    $check = $db->query('select * from sys_users where email=? and id !=?',$check);
    if ($check->rowCount()>0) {
      action_response('Maaf Email Sudah Digunakan');
    }
    
 $data = array("first_name"=>$_POST["first_name"],"last_name"=>$_POST["last_name"],"email"=>$_POST["email"],"group_level"=>$_POST["id_group"],);

  if(isset($_FILES["foto_user"]["name"])) {
                        if (!preg_match("/.(png|jpg|jpeg|gif|bmp)$/i", $_FILES["foto_user"]["name"]) ) {

              echo "pastikan file yang anda pilih gambar";
              exit();

            } else {
$db->compressImage($_FILES["foto_user"]["type"],$_FILES["foto_user"]["tmp_name"],"../../../upload/back_profil_foto/",$_FILES["foto_user"]["name"],200,200);
$db->deleteDirectory("../../../upload/back_profil_foto/".$db->fetch_single_row("sys_users","id",$_POST["id"])->foto_user);
              $foto_user = array("foto_user"=>$_FILES["foto_user"]["name"]);
              $data = array_merge($data,$foto_user);
            }

                         }

 if(isset($_POST["aktif"])=="on")
    {
      $aktif = array("aktif"=>"Y");
      $data=array_merge($data,$aktif);
    } else {
      $aktif = array("aktif"=>"N");
      $data=array_merge($data,$aktif);
    }

    $up = $db->update("sys_users",$data,"id",$_POST["id"]);
    
    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>