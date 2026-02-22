<?php
include '/var/www/html/dashboard/inc/config.php'; 
require_once '/var/www/html/dashboard/lib/mail/vendor/autoload.php';

function base64url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function base64url_decode($data) {
    return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
}


include "/var/www/html/dashboard/inc/lib/phpmailer/classes/class.phpmailer.php";
   

$email_data = $db->fetch_single_row('tb_token','id',1);

/*$data_user = $db->fetch_custom_single("select * from sys_users where id=?",array('id' => $_POST["id"]));
$email = $data_user->email;*/

$notice = '';
$authException = false;
$mime = new Mail_mime();
// Setup Google API Client
$client = new Google_Client();
$client->setClientId($email_data->client_id);

$client->setClientSecret($email_data->client_secret);
$client->setRedirectUri($email_data->redirect_url);
$client->addScope('https://mail.google.com/');
$client->setAccessType('offline');

// Create GMail Service
$service = new Google_Service_Gmail($client);


$q = $db->query("select * from email_send_2024 where is_send='N' limit 1");
   $no = 1;
foreach ($q as $k) {

 //  print_r($k);



  if ($email_data->login=='Y') {



          $pass=  strtoupper(substr(str_shuffle(str_repeat("123456789abcdefghijklmnpqrstuvwxyz", 5)), 0, 5));
          $body = "<img src='https://press.iainkerinci.ac.id/sites/press.iainkerinci.ac.id/files/logo_iain_progresif_2023.png' style='height:100px' /><br><h2 >Selamat</h2>
                      
                      Berikut Informasi Terbaru Akun Siakad Anda<br>
                      <table>
                        <tr>
                          <td>Nama</td><td>:</td><td>".$k->nama."</td>
                        </tr>
                        <tr>
                          <td>NIM</td><td>:</td><td>".$k->nim."</td> 
                        </tr>
                        <tr> 
                          <td>Username</td><td>:</td><td>".$k->nim."</td> 
                        </tr> 
                        <tr> 
                          <td>Password</td><td>:</td><td>".$pass."</td> 
                        </tr> 
                        <tr>
                          <td>Jurusan</td><td>:</td><td>$k->jurusan</td>
                        </tr>  
                         <tr>
                          <td>Fakultas</td><td>:</td><td>$k->fakultas</td>
                        </tr>  
         
                      </table>
                      <b>simpan baik-baik email ini untuk pengingat anda login</b>. <br>
                      Silahkan klik tombol berikut untuk login. 
                     <a href='https://siakad.iainkerinci.ac.id' style='text-decoration:none' target='_blank'>Login Siakad</a><br><br><br>
                     Hormat Kami,<br><br><br><br>
                     IAIN Kerinci
                     "; 

                  $refreshToken = $email_data->refresh_token; 
         $client->refreshToken($refreshToken);
         $client->setAccessToken($client->getAccessToken());

         $subject = "Akun SIAKAD IAIN Kerinci";

                  $mime->addTo($k->email);
                  $mime->setTXTBody($body);
                  $mime->setHTMLBody($body);
                  $mime->setSubject($subject);
                 
                  $message_body = $mime->getMessage();

                  $encoded_message = base64url_encode($message_body);

                  // Gmail Message Body
                  $message = new Google_Service_Gmail_Message();

                  $message->setRaw($encoded_message); 




                  try {
                      $email_send = $service->users_messages->send('me',$message);
                  $db->query("update sys_users set plain_pass='".$pass."',password='".md5($pass)."' where username='".$k->nim."' ");   
                  $db->query("update email_send_2024 set is_send='Y' where id='$k->id'");  
                 echo 'sukses';
                 $no++;

                  } catch (Exception $e) {
                      
                      echo $e->getMessage();
                      echo ' <div class="alert alert-danger" id="gagal_email" >
                      Email Gagal terkirim
                  </div>';
                      
                  }

      } else {
        echo "login dulu brow";
      }
} 
// update_akm('1810402112');  
// echo "$no"; 
   
?>