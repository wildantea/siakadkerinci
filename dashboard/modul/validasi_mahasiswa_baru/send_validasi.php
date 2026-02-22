<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include "../../inc/config.php";
session_check_json();
$time_start = microtime(true);
require_once '../../inc/lib/vendor/autoload.php';

function base64url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function base64url_decode($data) {
    return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
}

function generate_nim($no_pendaftaran)
{
  global $db;
  $data= array();
  $q= $db->query("select m.nama,kode_lokal, m.mulai_smt,m.`jur_kode` as kode_jur,j.kode_urut,f.`kode_nim` as fak,m.no_pendaftaran,nim as nim_mhs,
            j.`nama_jur`,substr(m.mulai_smt,3,2) as thn_masuk, jp.`jenjang`,
            (select count(*) from mahasiswa where substr(mulai_smt,1,4)=substr(m.mulai_smt,1,4) and jur_kode=m.`jur_kode` and status='M')+1 as urut from mahasiswa m join jurusan j on m.`jur_kode`=j.`kode_jur` join fakultas f on f.`kode_fak`=j.`fak_kode`
            join jenjang_pendidikan jp on jp.id_jenjang=j.`id_jenjang`
            where m.mhs_id='$no_pendaftaran'
            ");
  foreach ($q as $k) {

     $data['nama'] = $k->nama;
     $data['no_daftar'] = $k->nim_mhs;
     $data['mulai_smt'] = $k->mulai_smt;
     $data['thn_masuk'] = $k->thn_masuk;
     $data['kode_jur'] = $k->kode_jur;
     $kode_lokal = $k->kode_lokal;
     //$data['urut'] = $k->urut;
     $data['jenjang'] = $k->jenjang;
     $format_nim = "";

         if ($k->jenjang=='S1' || $k->jenjang=='Profesi') {
           $jenjang='1';
           $left_unik = $k->thn_masuk.$jenjang.$k->fak.$k->kode_urut;
           $format_nim = $k->thn_masuk.$jenjang.$k->fak.$k->kode_urut;
         } elseif ($k->jenjang=='S2' || $k->jenjang=='S3') {
           $jenjang='2';
           $format_nim = $k->kode_urut."0".$k->thn_masuk;
           $left_unik = $k->kode_urut."0".$k->thn_masuk; 
         } 
         // elseif ($k->jenjang=='S3') {
         //   $jenjang='3';
         //   $format_nim = $k->thn_masuk.$jenjang.$k->fak.$k->kode_urut;
         //   $left_unik = $k->thn_masuk.$jenjang.$k->fak.$k->kode_urut;
         // }

        /* print_r($k);

         exit();*/

     
    if ($k->urut=='1') {
         if ($k->urut<10) {
           $urut = "00".$k->urut;
         }elseif ($k->urut>=10 && $k->urut<100) {
           $urut = "0".$k->urut;
         }elseif ($k->urut>=100 && $k->urut<1000) {
           $urut = $k->urut;
         }
         $final_nim = $format_nim.$urut;
         $data['nim'] = $final_nim;
    }else{

       if ($k->jenjang=='S1' || $k->jenjang=='Profesi') {
        $qq = $db->query("select (nim+1) as nim from  mahasiswa where left(nim,7)='$left_unik' and status='M' order by right(nim,3) desc limit 1");

          foreach ($qq as $kk) {
           $data['nim'] = $kk->nim;
          }
       }else{
         $qq = $db->query("select (nim+1) as nim from  mahasiswa where left(nim,6)='$left_unik' and status='M' order by right(nim,3) desc limit 1");
          foreach ($qq as $kk) {
           $data['nim'] = $kk->nim;
          }
       } 
      
      
    }
      
  }
  return $data;  
}

function cek_no_daftar($id)
{
   global $db;
   $q= $db->query("select no_pendaftaran from mahasiswa where no_pendaftaran='$id' ");
   if ($q->rowCount()>0) {
     return true;
   }else{
     return false;
   }

}

function get_angkatan($nim)
{
	global $db;
	$q = $db->query("select mulai_smt from mahasiswa where no_pendaftaran='$nim' "); 
  $angkatan = "";
	foreach ($q as $k) {
		$angkatan = $k->mulai_smt;
	}
  if ($angkatan=="") {
     return get_sem_aktif();
  }else{
    return $angkatan;
  }
}

function get_tagihan($nim,$kode_prodi,$kode_tagihan,$angkatan,$id_bank,$no_pendaftaran)
{
   global $db;
   $q = $db->query("select * from keu_tagihan join keu_jenis_tagihan j on j.kode_tagihan=keu_tagihan.`kode_tagihan`
                     where keu_tagihan.kode_prodi='$kode_prodi' and keu_tagihan.kode_tagihan='3' and keu_tagihan.berlaku_angkatan='$angkatan'");
   if ($q->rowCount()>0) {
     foreach ($q as $k) {
       //return $k;
       $qq=$db->query("select id from keu_tagihan_mahasiswa where nim='$nim' and id_tagihan_prodi='3' and periode='".get_angkatan($nim)."' "); 
       if ($qq->rowCount()==0) {
/*          $data = array('nim' => $nim,
                         'jumlah' => $k->nominal_tagihan,
                         'ket' => $k->nama_tagihan,
                         'exp_tagihan' => date("Y-m-d H:i:s"),
                         'id_tagihan_prodi' => $k->id,
                         'periode' => get_angkatan($no_pendaftaran), 
                         'date_created' =>  date("Y-m-d H:i:s"),
                         'lunas' => '1');*/
            $data = array(
                "nim" => $nim,
                "id_tagihan_prodi" => $k->id,
                "potongan" => 0,
                "periode" => get_angkatan($no_pendaftaran),
                "created_date_tagihan_mhs" => date('Y-m-d H:i:s')
            );
           $db->insert("keu_tagihan_mahasiswa",$data); 
           $id_tagihan_mhs = $db->last_insert_id();

           $pem = $db->fetch_custom_single('select ktm.periode, kt.kode_prodi,kt.nominal_tagihan,ktm.id 
              from keu_tagihan_mahasiswa ktm  inner join keu_tagihan kt on ktm.id_tagihan_prodi=kt.id
              where ktm.id=?',array('id' => $id_tagihan_mhs));
             $urutan_bayar_prodi = $db->fetch_custom_single("select kbm.urutan_bayar_prodi from keu_bayar_mahasiswa kbm 
              inner join keu_tagihan_mahasiswa ktm on kbm.id_keu_tagihan_mhs=ktm.id
              inner join keu_tagihan kt on ktm.id_tagihan_prodi=kt.id
              where periode='".$pem->periode."' and kode_prodi='$pem->kode_prodi'
              order by urutan_bayar_prodi desc limit 1");
/*             if ($urutan_bayar_prodi) {
              $urutan = $urutan_bayar_prodi->urutan_bayar_prodi;
              if ($urutan<10) {
                $no_kwitansi = $pem->periode.$pem->kode_prodi.'0'.($urutan+1);
              } else {
                $no_kwitansi = $pem->periode.$pem->kode_prodi.($urutan+1);
              }
            } else {
              $no_kwitansi = $pem->periode.$pem->kode_prodi.'01';
              $urutan = 1;
            }*/

            if ($urutan_bayar_prodi) {
              $urutan = $urutan_bayar_prodi->urutan_bayar_prodi;
              $urutan = $urutan+1;
              if ($urutan<10) {
                $no_kwitansi = date('Ymd').$pem->kode_prodi.'00'.($urutan);
              } else {
                $no_kwitansi = date('Ymd').$pem->kode_prodi.($urutan);
              }
            } else {
              $no_kwitansi = date('Ymd').$pem->kode_prodi.'001';
              $urutan = 1; 
            }

            $data_quitansi = array(
              'nominal_bayar' => $k->nominal_tagihan,
              'total_tagihan' => $k->nominal_tagihan,
              'validator' => $_SESSION['username'],
              'kode_jur' => $pem->kode_prodi,
              'no_kwitansi' => $no_kwitansi,
              'urutan_bayar' => $urutan,
              'nim_mahasiswa' => $nim,
              'date_created' => date('Y-m-d H:i:s')
            );

              $data_quitansi['metode_bayar'] = 2;
              $tanggal_bayar =  date('Y-m-d H:i:s');
              $data_quitansi['id_bank'] = $id_bank;

            $data_quitansi['tgl_bayar'] = $tanggal_bayar;

            //print_r($data_quitansi);

            $db->insert('keu_kwitansi',$data_quitansi);
            $last_insert_id = $db->last_insert_id();

        $data2 = array(
            'id_keu_tagihan_mhs' => $id_tagihan_mhs,
            'tgl_bayar' => $tanggal_bayar,
            'tgl_validasi' => date('Y-m-d H:m:s'),
            'created_by' => $_SESSION['username'],
            'id_kwitansi' => $last_insert_id,
            'nominal_bayar' => $k->nominal_tagihan,
        );


           /*$data2 = array('id_keu_tagihan_mhs' => $id_tagihan_mhs,
                          'tgl_bayar' => date("Y-m-d H:i:s"),
                          'tgl_validasi' => date("Y-m-d H:i:s"),
                          'created_by' => $_SESSION['nama'],
                          'nominal_bayar' => $k->nominal_tagihan,
                          'no_kwitansi' => $no_kwitansi,
                          'urutan_bayar_prodi'  => $urutan,
                          'id_bank' => $id_bank );*/
            //print_r($data2);
            $db->insert("keu_bayar_mahasiswa",$data2);
            /*$data3 = array('id_tagihan_mhs' => $id_tagihan_mhs , 
                            'jml_bayar' => $k->nominal_tagihan,
                            'tgl_bayar' => date("Y-m-d H:i:s"),
                            'validator' => $_SESSION['nama']);
            $db->insert("keu_cicilan",$data3);*/
            return 1;
       }else{
           return 1;
       }
           
     }
   }else{
       return 0;
   }
}

function create_user($nim,$nama,$email,$pass) 
{
  global $db;
  $q=$db->query("select * from sys_users where username='$nim' ");

  if ($q->rowCount()==0) {
      $data = array('username' => $nim ,
                    'first_name' => $nama,
                    'plain_pass' => $pass,
                    'password'   => md5($pass),
                    'foto_user'  => 'default_user.png',
                    'aktif' => 'Y',
                    'group_level' => '3',
                    'email' => $email,
                    'date_created' => date("Y-m-d")
                     );
      $db->insert("sys_users",$data);
      return $pass;
  }
}

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

//jika sudah pernah login maka lakukan query 
if ($email_data->login=='Y') {


    $no_pendaftaran = $_POST['no_pendaftaran'];
    $email = $_POST['email'];
    $data_mhs = generate_nim($no_pendaftaran);

    $user = $db->fetch_single_row("sys_users","username",$data_mhs['no_daftar']);

   



 // if (cek_no_daftar($no_pendaftaran)) {
      $nim = $data_mhs['nim'];  


/*      $nominal_tagihan = get_tagihan($nim, $data_mhs['kode_jur'],'3',$data_mhs['mulai_smt'],$_POST['id_bank'],$no_pendaftaran);
      if ($nominal_tagihan!=0) {*/
          //$nim =  "1209705098";
          $pass=  strtoupper(substr(str_shuffle(str_repeat("123456789abcdefghijklmnpqrstuvwxyz", 5)), 0, 5));     
         $refreshToken = $email_data->refresh_token; 
         $client->refreshToken($refreshToken);
         $client->setAccessToken($client->getAccessToken());
       //data mail
         $subject = "Akun SIAKAD IAIN Kerinci";
              //  print_r($dt);

               //   $bcc = $_POST['bcc'];
                //  $cc = $_POST['cc'];
                  $body = "<h2 >Selamat</h2>
                      Anda telah terdaftar di <a href='siakad.iainkerinci.ac.id' terget='_BLANK'> siakad.iainkerinci.ac.id</a>. Berikut informasi akun anda<br>
                      <table>
                        <tr>
                          <td>Nama</td><td>:</td>".$data_mhs['nama']."</td>
                        </tr>
                        <tr>
                          <td>NIM</td><td>:</td>".$data_mhs['nim']."</td> 
                        </tr>
                        <tr>
                          <td>Username</td><td>:</td>".$data_mhs['nim']."</td>
                        </tr>
                        <tr>
                          <td>Password</td><td>:</td>$user->plain_pass</td>
                        </tr>
         
                      </table>
                      <b>simpan baik-baik email ini untuk pengingat anda login</b>. <br>
                      Silahkan klik tombol berikut untuk login. 
                     <a href='https://siakad.iainkerinci.ac.id' style='text-decoration:none'>Login Siakad</a><br><br><br>
                     Hormat Kami,<br><br><br><br>
                     IAIN Kerinci
                     ";

                  


                  $mime->addTo($email);
                  $mime->setTXTBody($body);
                  $mime->setHTMLBody($body);
                  $mime->setSubject($subject);
                  //$mime->setFrom('wildante <wildannudin@gmail.com>');
                 
                  $message_body = $mime->getMessage();

                //  $mime->setFrom('wildannudin@gmail.com');

                  $encoded_message = base64url_encode($message_body);

                  // Gmail Message Body
                  $message = new Google_Service_Gmail_Message();

                  $message->setRaw($encoded_message); 

                  
                //print_r($mime);





                  // Send the Email

                  try {
                      $email_send = $service->users_messages->send('me',$message);
                     $data = array('no_pendaftaran' => $no_pendaftaran,
                                    'email'=> $email,
                                   /* 'no_resi' =>  $_POST['no_resi'],
                                    'id_bank' => $_POST['id_bank'],*/
                                    'ket_validasi' => $_POST['is_affirmasi'],
                                    'tgl_validasi' => date("Y-m-d H:i:s"),
                                    'validator' => $_SESSION['nama']);
                    $db->insert("validasi_mhs_baru",$data); 
/*
                    echo $db->getErrorMessage();
                    $db->query("update mahasiswa set status='M',nim='$nim',email='$email' where mhs_id='$no_pendaftaran' ");
                    echo $db->getErrorMessage();
                    create_user($nim,$data_mhs['nama'],$email,$pass);*/


            $db->query("update mahasiswa set status='M',ket='validasi',nim='".$data_mhs['nim']."',email='$email',date_updated='".date("Y-m-d H:i:s")."' where mhs_id='$no_pendaftaran' "); 
            echo $db->getErrorMessage();
            //upda  tagihan mahasiswa
            $db->query("update keu_tagihan_mahasiswa set nim='".$data_mhs['nim']."' where nim='".$data_mhs['no_daftar']."'"); 
            //also update kwitansi
            $db->query("update keu_kwitansi set nim_mahasiswa='".$data_mhs['nim']."'  where nim='".$data_mhs['no_daftar']."'"); 
            //update keranjang
            $db->query("update keu_keranjang_va set nim_mhs='".$data_mhs['nim']."'  where nim_mhs='".$data_mhs['no_daftar']."'");

            $db->query("update sys_users set username='".$data_mhs['nim']."' where username='".$data_mhs['no_daftar']."'");  


                    echo '<div class="alert alert-success" id="sukses" ">
                                      Validasi Sukses, Username dan Password Terkirim ke email '.$email.'
                                    </div>';
                  } catch (Exception $e) {
                      
                      echo $e->getMessage();
                      echo ' <div class="alert alert-danger" id="gagal_email" >
                      Email Gagal terkirim
                  </div>';
                      
                  }

    /*  }else{
         echo ' <div class="alert alert-danger" id="gagal_email" >
                      Tagihan Belum di set untuk SPP angkatan jurusan mahasiswa bersangkutan
                  </div>';
      }*/

    // }else{
    //     echo '<div class="alert alert-danger" id="gagal" >
    //                 No Pendaftaran tidak terdaftar
    //               </div>';
    // }

//execution time of the script
//echo '<b>Total Execution Time '.($time_end - $time_start).' Second :</b> '.$execution_time.' Mins';
} else {
    echo "login dulu brow";
}