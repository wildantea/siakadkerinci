<?php
include '/var/www/html/dashboard/inc/config.php'; 
require_once '/var/www/html/dashboard/inc/lib/vendor/autoload.php';

function base64url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function base64url_decode($data) {
    return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
}


$email_data = $db->fetch_single_row('tb_token','id',1);

/*$data_user = $db->fetch_custom_single("select * from sys_users where id=?",array('id' => $_POST["id"]));
$email = $data_user->email;*/

$notice = '';
$authException = false;

// Setup Google API Client
$client = new Google_Client();
$client->setClientId($email_data->client_id);

$client->setClientSecret($email_data->client_secret);
$client->setRedirectUri($email_data->redirect_url);
$client->addScope('https://mail.google.com/');
$client->setAccessType('offline');

// Create GMail Service
$service = new Google_Service_Gmail($client);


$array_send = array(
array (
'nim' => '12345',
'password' => '2343',
'email' => 'wildannudin@gmail.com'
),
array (
'nim' => '54321',
'password' => '2343',
'email' => 'unyilte4@gmail.com'
)
);
dump($array_send);
   $no = 1;
foreach ($array_send as $k) {
 // exit();

   print_r($k);

  if ($email_data->login=='Y') {

          $pass=  strtoupper(substr(str_shuffle(str_repeat("123456789abcdefghijklmnpqrstuvwxyz", 5)), 0, 5));
          $body = "<img src='https://press.iainkerinci.ac.id/sites/press.iainkerinci.ac.id/files/logo_iain_progresif_2023.png' style='height:100px' /><br><h2 >Selamat</h2>
                      
                      Anda telah terdaftar di <a href='siakad.iainkerinci.ac.id' terget='_BLANK'> siakad.iainkerinci.ac.id</a>. Berikut informasi akun anda<br>
                      <table>
                        <tr>
                          <td>NIM</td><td>:</td><td>".$k['nim']."</td> 
                        </tr>
                        <tr> 
                          <td>Password</td><td>:</td><td>".$pass."</td> 
                        </tr> 
                         <tr> 
                          <td>Password</td><td>:</td><td>".$k['email']."</td> 
                        </tr> 
                       
         
                      </table>
                      <b>simpan baik-baik email ini untuk pengingat anda login</b>. <br>
                      Silahkan klik tombol berikut untuk login. 
                     <a href='https://siakad.iainkerinci.ac.id' style='text-decoration:none'>Login Siakad</a><br><br><br>
                     Hormat Kami,<br><br><br><br>
                     IAIN Kerinci
                     "; 

                  $refreshToken = $email_data->refresh_token; 
         $client->refreshToken($refreshToken);
         $client->setAccessToken($client->getAccessToken());

         $subject = "Akun SIAKAD IAIN Kerinci";

         $mime = new Mail_mime();

                  $mime->addTo($k['email']);
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
                  } catch (Exception $e) {
                      
                      echo $e->getMessage();
                      echo ' <div class="alert alert-danger" id="gagal_email" >
                      Email Gagal terkirim
                  </div>';
                      
                  }
                
                       echo 'sukses';

      } else {
        echo "login dulu brow";
      }
} 
// update_akm('1810402112');  
// echo "$no"; 
   
?>