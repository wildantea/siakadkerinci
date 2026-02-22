<?php
include '/var/www/html/dashboard/inc/config.php'; 
require_once '/var/www/html/dashboard/inc/lib/vendor/autoload.php';

function base64url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function base64url_decode($data) {
    return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
}

function sendCurlRequest($url, $data) {
    // Initialize cURL
    $ch = curl_init($url);

    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json', 
        'Accept: application/json'
    ));

    // Execute cURL request
    $response = curl_exec($ch);

    // Check for errors
    if (curl_errno($ch)) {
        $error = "cURL Error: " . curl_error($ch);
        curl_close($ch);
        return $error;
    }

    // Close cURL session
    curl_close($ch);
    
    return $response;
}
//include "/var/www/html/dashboard/inc/lib/phpmailer/classes/class.phpmailer.php";
 $q = $db->query("select no from counter");
 foreach ($q as $k) {
  $db->query("update counter set no='".($k->no+1)."' ");
 }
echo "<pre>";     
   
 
function generate_nim($no_pendaftaran)
{
  global $db;
  $data= array();
  $q= $db->query("select m.nama,kode_lokal, m.mulai_smt,m.`jur_kode` as kode_jur,j.kode_urut,f.`kode_nim` as fak,
            j.`nama_jur`,substr(m.mulai_smt,3,2) as thn_masuk, jp.`jenjang`,no_pendaftaran,
(select nim from mahasiswa where substr(mulai_smt,1,4)=substr(m.mulai_smt,1,4) and jur_kode=m.`jur_kode` and status='M' order by nim asc limit 1) as first_nim,
(select nim from mahasiswa where substr(mulai_smt,1,4)=substr(m.mulai_smt,1,4) and jur_kode=m.`jur_kode` and status='M' order by nim desc limit 1) as last_nim,
(select group_concat(right(nim,3)) from mahasiswa where substr(mulai_smt,1,4)=substr(m.mulai_smt,1,4) and jur_kode=m.`jur_kode` and status='M' order by nim asc) as all_nim,
            (select count(*) from mahasiswa where substr(mulai_smt,1,4)=substr(m.mulai_smt,1,4) and jur_kode=m.`jur_kode` and status='M')+1 as 
urut from mahasiswa m join jurusan j on m.`jur_kode`=j.`kode_jur` join fakultas f on f.`kode_fak`=j.`fak_kode`
            join jenjang_pendidikan jp on jp.id_jenjang=j.`id_jenjang`
            where m.mhs_id='$no_pendaftaran'
            ");

  foreach ($q as $k) {
     $data['nama'] = $k->nama;
     $data['mulai_smt'] = $k->mulai_smt;
     $data['thn_masuk'] = $k->thn_masuk;
     $data['kode_jur'] = $k->kode_jur;
     $data['no_daftar'] = $k->no_pendaftaran;
     $kode_lokal = $k->kode_lokal;
     //$data['urut'] = $k->urut;
     $data['jenjang'] = $k->jenjang;
     $format_nim = "";

     $missing = array();


         if ($k->jenjang=='S1' || $k->jenjang=='Profesi') {
           $jenjang='1';
           $left_unik = $k->thn_masuk.$jenjang.$k->fak.$k->kode_urut;
           $format_nim = $k->thn_masuk.$jenjang.$k->fak.$k->kode_urut;
         }elseif ($k->jenjang=='S2' || $k->jenjang=='S3') {
           $jenjang='2';
           $format_nim = $k->kode_urut."0".$k->thn_masuk;
           $left_unik = $k->kode_urut."0".$k->thn_masuk;
         } 

         if ($k->urut > 1) {
          $exp = explode(",", $k->all_nim);
          foreach ($exp as $urutan_exist) {
            $array_exist[] = intval(substr($urutan_exist, -3));
          }
          sort($array_exist);


         //check if nim has missing number
          // Extract the last 4 digits and convert to integer
          $first_3_first = intval(substr($k->first_nim, -3));
          // Extract the last 4 digits and convert to integer
          $last_3_last = intval(substr($k->last_nim, -3));
          for ($i=1; $i <= $last_3_last; $i++) { 
            $array_full[] = $i;
          }

          $missing = array_diff($array_full, $array_exist);

          dump($array_full);

          dump($array_exist);

          $missing = array_values($missing);

          dump($missing);

          if (!empty($missing)) {
            $k->urut = $missing[0];
          }

          if ($k->urut <= $max_nim) {
            $k->urut = $max_nim+1;
          }


         }
         // elseif ($k->jenjang=='S3') {
         //   $jenjang='3';
         //   $format_nim = $k->thn_masuk.$jenjang.$k->fak.$k->kode_urut;
         //   $left_unik = $k->thn_masuk.$jenjang.$k->fak.$k->kode_urut;
         // }

        /* print_r($k);

         exit();*/

 dump($k);

          if ($k->urut=='') {
            $k->urut = 1;
          }
         // $k->urut = 5;

   // if ($k->urut=='1') {
         if ($k->urut<10) {
           $urut = "00".$k->urut;
         }elseif ($k->urut>=10 && $k->urut<100) {
           $urut = "0".$k->urut;
         }elseif ($k->urut>=100) {
           $urut = $k->urut;
         }
         $final_nim = $format_nim.$urut;

           dump($final_nim);
         $data['nim'] = $final_nim;

/*    }else{

       if ($k->jenjang=='S1' || $k->jenjang=='Profesi') {
        $qq = $db->query("select (nim+1) as nim from  mahasiswa where left(nim,7)='$left_unik' and status='M' order by right(nim,3) desc limit 1");
          foreach ($qq as $kk) {
           $data['nim'] = $kk->nim;
          }
          dump($data['nim']);
       }else{
         $qq = $db->query("select (nim+1) as nim from  mahasiswa where left(nim,6)='$left_unik' and status='M' order by right(nim,3) desc limit 1");
          foreach ($qq as $kk) {
           $data['nim'] = $kk->nim;
          }
       } 
      
      
    }*/
      
  }
  return $data;  
}



//print_r(generate_nim('37839'));


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


$q = $db->query("SELECT * FROM `v_bayar_calon_mhs` where nominal_bayar is not null group by nim limit 1");

   $no = 1;
foreach ($q as $k) {
  dump($k);

  if ($email_data->login=='Y') {
    $data_mhs = generate_nim($k->mhs_id);

      dump($data_mhs);

   $pass=  strtoupper(substr(str_shuffle(str_repeat("123456789abcdefghijklmnpqrstuvwxyz", 5)), 0, 5));
   $body = "<img src='https://press.iainkerinci.ac.id/sites/press.iainkerinci.ac.id/files/logo_iain_progresif_2023.png' style='height:100px' /><br><h2 >Selamat</h2>
                      
                      Anda telah terdaftar di <a href='siakad.iainkerinci.ac.id' terget='_BLANK'> siakad.iainkerinci.ac.id</a>. Berikut informasi akun anda<br>
                      <table>
                        <tr>
                          <td>Nama</td><td>:</td><td>".$data_mhs['nama']."</td>
                        </tr>
                        <tr>
                          <td>NIM</td><td>:</td><td>".$data_mhs['nim']."</td> 
                        </tr>
                        <tr> 
                          <td>Username</td><td>:</td><td>".$data_mhs['nim']."</td> 
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
                     <a href='https://siakad.iainkerinci.ac.id' style='text-decoration:none'>Login Siakad</a><br><br><br>
                     Hormat Kami,<br><br><br><br>
                     IAIN Kerinci
                     "; 

                  $refreshToken = $email_data->refresh_token; 
         $client->refreshToken($refreshToken);
         $client->setAccessToken($client->getAccessToken());

         $subject = "Akun SIAKAD IAIN Kerinci";

         $mime = new Mail_mime();

                  $mime->addTo($k->email);
                  $mime->setTXTBody($body);
                  $mime->setHTMLBody($body);
                  $mime->setSubject($subject);
                 
                  $message_body = $mime->getMessage();

                  $encoded_message = base64url_encode($message_body);

                  //dump($mime);

                  // Gmail Message Body
                  $message = new Google_Service_Gmail_Message();

                  $message->setRaw($encoded_message); 

                  try {
                      $email_send = $service->users_messages->send('me',$message);

                  $db->query("update mahasiswa set status='M',nim='".$data_mhs['nim']."',ket='generate',date_updated='".date("Y-m-d H:i:s")."' where mhs_id='$k->mhs_id' "); 

                   $db->query("update sys_users set plain_pass='".$pass."', username='".$data_mhs['nim']."',password='".md5($pass)."' where username='".$data_mhs['no_daftar']."' ");     
                  echo $db->getErrorMessage();
                  //upda  tagihan mahasiswa
                 /* $db->query("update keu_tagihan_mahasiswa set nim='".$data_mhs['nim']."' where nim='$k->no_pendaftaran'"); 
                  //also update kwitansi
                  $db->query("update keu_kwitansi set nim_mahasiswa='".$data_mhs['nim']."' where nim_mahasiswa='$k->no_pendaftaran'");*/
                  //update keranjang
                  $db->query("update keu_keranjang_va set nim_mhs='".$data_mhs['nim']."',old_no='".$data_mhs['no_daftar']."' where nim_mhs='".$data_mhs['no_daftar']."'");


                 //check if calon in calon suday survey
                $is_calon_survey = $db->fetch_custom_single("select * from calon_sudah_survey where no_daftar=? and has_nim='N'",array('no_daftar' => $data_mhs['no_daftar']));
                if ($is_calon_survey) {
                  $data = array(
                    'id_user' => $is_calon_survey->no_daftar,
                    'nim' => $data_mhs['nim']
                  );
                  sendCurlRequest('https://survei.iainkerinci.ac.id/service/update_no_daftar.php',$data);
                   $db->update("calon_sudah_survey",array('new_nim' => $data_mhs['nim'],'has_nim' => 'Y'),'no_daftar',$is_calon_survey->no_daftar);
                }
                                   
                  //  $data = array('no_pendaftaran' => $no_pendaftaran,
                  //                 'email'=> $email,
                  //                 'no_resi' =>  $_POST['no_resi'],
                  //                 'id_bank' => $_POST['id_bank'],
                  //                 'tgl_validasi' => date("Y-m-d H:i:s"),
                  //                 'validator' => $_SESSION['nama']);
                  // $db->insert("validasi_mhs_baru",$data);

                  // echo $db->getErrorMessage();
                  // $db->query("update mahasiswa set status='M',nim='$nim',email='$email' where no_pendaftaran='$no_pendaftaran' ");
                  // echo $db->getErrorMessage();
                  //create_user($data_mhs['nim'],$data_mhs['nama'],$k->email,$pass);
                 
                       echo 'sukses';

                  } catch (Exception $e) {
                    //dump($e);
                      
                      echo $e->getMessage();
                      echo ' <div class="alert alert-danger" id="gagal_email" >
                      Email Gagal terkirim
                  </div>';
                      
                  }





                  echo $db->getErrorMessage();
                  

      } else {
        echo "login dulu brow";
      }
       
} 
// update_akm('1810402112');  
// echo "$no"; 
   
?>