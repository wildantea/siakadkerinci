<?php
session_start();
include "../../inc/config.php";
session_check_json();
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

function get_tagihan($nim,$kode_prodi,$kode_tagihan,$angkatan,$id_bank)
{
   global $db;
   $q = $db->query("select * from keu_tagihan join keu_jenis_tagihan j on j.kode_tagihan=keu_tagihan.`kode_tagihan`
                     where keu_tagihan.kode_prodi='$kode_prodi' and keu_tagihan.kode_tagihan='3' and keu_tagihan.berlaku_angkatan='$angkatan'");
   if ($q->rowCount()>0) {
     foreach ($q as $k) {
       //return $k;
       $qq=$db->query("select id from keu_tagihan_mahasiswa where nim='$nim' and id_tagihan_prodi='3' and periode='".get_sem_aktif()."' ");
       if ($qq->rowCount()==0) {
          $data = array('nim' => $nim,
                         'jumlah' => $k->nominal_tagihan,
                         'ket' => $k->nama_tagihan,
                         'exp_tagihan' => date("Y-m-d H:i:s"),
                         'id_tagihan_prodi' => $k->id,
                         'periode' => get_sem_aktif(),
                         'date_created' =>  date("Y-m-d H:i:s"),
                         'lunas' => '1');
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
             if ($urutan_bayar_prodi) {
              $urutan = $urutan_bayar_prodi->urutan_bayar_prodi;
              if ($urutan<10) {
                $no_kwitansi = $pem->periode.$pem->kode_prodi.'0'.($urutan+1);
              } else {
                $no_kwitansi = $pem->periode.$pem->kode_prodi.($urutan+1);
              }
            } else {
              $no_kwitansi = $pem->periode.$pem->kode_prodi.'01';
              $urutan = 1;
            }

           $data2 = array('id_keu_tagihan_mhs' => $id_tagihan_mhs,
                          'tgl_bayar' => date("Y-m-d H:i:s"),
                          'tgl_validasi' => date("Y-m-d H:i:s"),
                          'created_by' => $_SESSION['nama'],
                          'nominal_bayar' => $k->nominal_tagihan,
                          'no_kwitansi' => $no_kwitansi,
                          'urutan_bayar_prodi'  => $urutan,
                          'id_bank' => $id_bank );
            //print_r($data2);
            $db->insert("keu_bayar_mahasiswa",$data2);
            $data3 = array('id_tagihan_mhs' => $id_tagihan_mhs , 
                            'jml_bayar' => $k->nominal_tagihan,
                            'tgl_bayar' => date("Y-m-d H:i:s"),
                            'validator' => $_SESSION['nama']);
            $db->insert("keu_cicilan",$data3);
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

function generate_nim($no_pendaftaran)
{
  global $db;
  $data= array();
  $q= $db->query("select m.nama,kode_lokal, m.mulai_smt,m.`jur_kode` as kode_jur,j.kode_urut,f.`kode_nim` as fak,
            j.`nama_jur`,substr(m.mulai_smt,3,2) as thn_masuk, jp.`jenjang`,
            (select count(*) from mahasiswa where substr(mulai_smt,1,4)=substr(m.mulai_smt,1,4) and jur_kode=m.`jur_kode` and status='M')+1 as urut from mahasiswa m join jurusan j on m.`jur_kode`=j.`kode_jur` join fakultas f on f.`kode_fak`=j.`fak_kode`
            join jenjang_pendidikan jp on jp.id_jenjang=j.`id_jenjang`
            where m.mhs_id='$no_pendaftaran'
            ");
  foreach ($q as $k) {
  	 $data['nama'] = $k->nama;
     $data['mulai_smt'] = $k->mulai_smt;
     $data['thn_masuk'] = $k->thn_masuk;
     $data['kode_jur'] = $k->kode_jur;
     $kode_lokal = $k->kode_lokal;
     //$data['urut'] = $k->urut;
     $data['jenjang'] = $k->jenjang;
     $format_nim = "";

         if ($k->jenjang=='S1') {
           $jenjang='1';
           //$left_unik = $k->thn_masuk.$jenjang.$k->fak.$k->kode_urut;
           $format_nim = $k->thn_masuk.$jenjang.$k->fak.$k->kode_urut;
         }elseif ($k->jenjang=='S2') {
           $jenjang='2';
           $format_nim = $kode_lokal.$k->thn_masuk;
         }
         elseif ($k->jenjang=='S3') {
           $jenjang='3';
           $format_nim = $k->thn_masuk.$jenjang.$k->fak.$k->kode_urut;
           //$left_unik = $k->thn_masuk.$jenjang.$k->fak.$k->kode_urut;
         }

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
      
    	$qq = $db->query("select (nim+1) as nim from  mahasiswa where left(nim,7)='$left_unik' and status='M' order by right(nim,3) desc limit 1");
  	  foreach ($qq as $kk) {
  	 	$data['nim'] = $kk->nim;
  	 }
    }
  	 

     


  }
  return $data;
}

switch ($_GET["act"]) {

  case "get_email":
   $id = $_POST['id'];
   $is_ok = false;
   $res = array();
   $q = $db->fetch_custom_single("select nim,email,mulai_smt,no_pendaftaran,email from mahasiswa where mhs_id='$id' ");
   //check if tagihan exist
   $tagihan = $db->fetch_custom_single("select * from keu_tagihan_mahasiswa where nim='$q->nim' and periode='$q->mulai_smt'");
   $res['error'] = '';
   $res['is_affirmasi'] = 'no';
   if (!$tagihan) {
     $res['error'] = 'Silakan Buat Tagihan Untuk Calon Mahasiswa ini';
   } else {
   $is_bayar = $db->fetch_custom_single("SELECT SUM(IFNULL(nominal_bayar, 0)) as jml_bayar
     FROM keu_bayar_mahasiswa
     RIGHT JOIN keu_tagihan_mahasiswa ON keu_bayar_mahasiswa.id_keu_tagihan_mhs = keu_tagihan_mahasiswa.id
     RIGHT JOIN keu_tagihan ON keu_tagihan_mahasiswa.id_tagihan_prodi = keu_tagihan.id
     INNER JOIN keu_jenis_tagihan USING(kode_tagihan)
     WHERE keu_jenis_tagihan.syarat_krs = 'Y' 
       AND keu_tagihan_mahasiswa.nim = '$q->nim'
       AND keu_tagihan_mahasiswa.periode = '$q->mulai_smt'");
      if ($is_bayar->jml_bayar>0) {
        $is_ok = true;
        $res['email'] = $q->email;
      } else {
            //afirmasi
          $check_afirmasi = $db->fetch_custom_single(" select IFNULL(
          (SELECT id_affirmasi 
           FROM affirmasi_krs 
           WHERE nim = '$q->nim'
             AND periode = '$q->mulai_smt' 
           LIMIT 1), 
          0
        ) as afirmasi
        ");
          if ($check_afirmasi->afirmasi>0) {
            $is_ok = true;
            $res['email'] = $q->email;
            $res['is_affirmasi'] = 'yes';
          } else {
            $res['error'] = 'Silakan input data Afirmasi untuk mahasiswa ini jika lewat jalur afirmasi';
          }
      }

   }

   echo json_encode($res);
  break;

  case "batal_validasi":
    $nim = $_POST['nim'];

    $no_pendaftaran = $db->fetch_single_row("mahasiswa","nim",$nim);

    $no_daftar = $no_pendaftaran->no_pendaftaran;
      
    $db->query("update mahasiswa set nim=no_pendaftaran,ket='', status='CM' where nim='$nim' ");

    echo $db->getErrorMessage();

  //upda  tagihan mahasiswa
  $db->query("update keu_tagihan_mahasiswa set nim='".$no_daftar."' where nim='".$nim."'"); 
  //also update kwitansi
  $db->query("update keu_kwitansi set nim_mahasiswa='".$no_daftar."'  where nim='".$nim."'"); 
  //update keranjang
  $db->query("update keu_keranjang_va set nim_mhs='".$no_daftar."'  where nim_mhs='".$nim."'");

  $db->query("update sys_users set username='".$no_daftar."' where username='".$nim."'");  

 echo $db->getErrorMessage();

  break;
  
  case "validasi":
 
    include "../../inc/lib/phpmailer/classes/class.phpmailer.php";
   $mail = new PHPMailer;

    $no_pendaftaran = $_POST['no_pendaftaran'];
    $email = $_POST['email'];
    $data_mhs = generate_nim($no_pendaftaran); 
     print_r($data_mhs);
     die();
     
   // if (cek_no_daftar($no_pendaftaran)) {
      $nim = $data_mhs['nim'];  
      $nominal_tagihan = get_tagihan($nim, $data_mhs['kode_jur'],'3',$data_mhs['mulai_smt'],$_POST['id_bank']);
      if ($nominal_tagihan!=0) {
          $mail->IsSMTP();
          $mail->SMTPDebug = 1;  
          $mail->SMTPAuth = TRUE;
          $mail->SMTPSecure = "SSL"; 
        //  $mail->SMTPDebug = 1;
          $mail->Port     = '465';  
          $mail->Username = "keuanganiainkerinci@gmail.com";
          $mail->Password = "R3almadrid";
          $mail->Host     = "smtp.gmail.com";
          $mail->Mailer   = "smtp";
          $mail->SetFrom("keuanganiainkerinci@gmail.com", "Siakad IAIN Kerinci");
          $mail->AddReplyTo("keuanganiainkerinci@gmail.com", "Keuangan Kerinci");
          $mail->AddAddress($email);
          $mail->Subject = "Akun SIAKAD IAIN Kerinci";
          $mail->WordWrap   = 80; 
          //$nim =  "1209705098";
          $pass=  strtoupper(substr(str_shuffle(str_repeat("123456789abcdefghijklmnpqrstuvwxyz", 5)), 0, 5));
          $content = "<h2 >Selamat</h2>
                      Anda telah terdaftar di <a href='siakad.iainkerinci.ac.id' terget='_BLANK'> siakad.iainkerinci.ac.id</a>. Berikut informasi akun anda<br>
                      <table>
                        <tr>
                          <td>Nama</td><td>:</td>".$data_mhs['nama']."</td>
                        </tr>
                        <tr>
                          <td>NIM</td><td>:</td>$nim</td> 
                        </tr>
                        <tr>
                          <td>Username</td><td>:</td>$nim</td>
                        </tr>
                        <tr>
                          <td>Password</td><td>:</td>$pass</td>
                        </tr>
         
                      </table>
                      <b>simpan baik-baik email ini untuk pengingat anda login</b>. <br>
                      Silahkan klik tombol berikut untuk login. 
                     <a href='https://siakad.iainkerinci.ac.id' style='text-decoration:none'>Login Siakad</a><br><br><br>
                     Hormat Kami,<br><br><br><br>
                     IAIN Kerinci
                     "; 
          $mail->MsgHTML($content);
          $mail->IsHTML(true);
          if($mail->Send()) {
             
             $data = array('no_pendaftaran' => $no_pendaftaran,
                            'email'=> $email,
                            'no_resi' =>  $_POST['no_resi'],
                            'id_bank' => $_POST['id_bank'],
                            'tgl_validasi' => date("Y-m-d H:i:s"),
                            'validator' => $_SESSION['nama']);
            $db->insert("validasi_mhs_baru",$data);

            echo $db->getErrorMessage();
            $db->query("update mahasiswa set status='M',nim='$nim',email='$email' where no_pendaftaran='$no_pendaftaran' ");
            echo $db->getErrorMessage();
            create_user($nim,$data_mhs['nama'],$email,$pass);

            echo '<div class="alert alert-success" id="sukses" ">
                              Validasi Sukses, Username dan Password Terkirim ke email '.$email.'
                            </div>';
          }
          else {          
            echo ' <div class="alert alert-danger" id="gagal_email" >
                      Email Gagal terkirim
                  </div>';
        }
      }else{
         echo ' <div class="alert alert-danger" id="gagal_email" >
                      Tagihan Belum di set untuk SPP angkatan jurusan mahasiswa bersangkutan
                  </div>';
      }

    // }else{
    //     echo '<div class="alert alert-danger" id="gagal" >
    //                 No Pendaftaran tidak terdaftar
    //               </div>';
    // }
 
  break;

  case "in":
    
  if (!is_dir("../../../upload/validasi_mahasiswa_baru")) {
              mkdir("../../../upload/validasi_mahasiswa_baru");
            }
  
  
  $data = array(
      "nim" => $_POST["nim"],
      "nama" => $_POST["nama"],
      "jur_kode" => $_POST["jur_kode"],
      "id_jalur_masuk" => $_POST["id_jalur_masuk"],
  );
  
                    if (!preg_match("/.(pdf|txt|docx|doc|jpg|jpeg|png|gif)$/i", $_FILES["pesan"]["name"]) ) {

              echo "pastikan file yang anda pilih pdf|txt|docx|doc|jpg|jpeg|png|gif";
              exit();

            } else {
              move_uploaded_file($_FILES["pesan"]["tmp_name"], "../../../upload/validasi_mahasiswa_baru/".$_FILES['pesan']['name']);
              $pesan = array("pesan"=>$_FILES["pesan"]["name"]);
              $data = array_merge($data,$pesan);
            }
  
  
   
    $in = $db->insert("mahasiswa",$data);
    
    
    action_response($db->getErrorMessage());
    break;
  case "delete":
    
    
    $db->deleteDirectory("../../../upload/validasi_mahasiswa_baru/".$db->fetch_single_row("mahasiswa","mhs_id",$_POST["id"])->pesan);
    $db->delete("mahasiswa","mhs_id",$_GET["id"]);
    action_response($db->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("mahasiswa","mhs_id",$id);
         }
    }
    action_response($db->getErrorMessage());
    break;
  case "up":
    
   $data = array(
      "nim" => $_POST["nim"],
      "nama" => $_POST["nama"],
      "jur_kode" => $_POST["jur_kode"],
      "id_jalur_masuk" => $_POST["id_jalur_masuk"],
   );
   
                         if(isset($_FILES["pesan"]["name"])) {
                        if (!preg_match("/.(pdf|txt|docx|doc|jpg|jpeg|png|gif)$/i", $_FILES["pesan"]["name"]) ) {

              echo "pastikan file yang anda pilih pdf|txt|docx|doc|jpg|jpeg|png|gif";
              exit();

            } else {
              move_uploaded_file($_FILES["pesan"]["tmp_name"], "../../../upload/validasi_mahasiswa_baru/".$_FILES['pesan']['name']);
              $db->deleteDirectory("../../../upload/validasi_mahasiswa_baru/".$db->fetch_single_row("mahasiswa","mhs_id",$_POST["id"])->pesan);
              $pesan = array("pesan"=>$_FILES["pesan"]["name"]);
              $data = array_merge($data,$pesan);
            }

                         }
   
   

    
    
    $up = $db->update("mahasiswa",$data,"mhs_id",$_POST["id"]);
    
    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>