<?php
session_start();
include "../../inc/config.php";
session_check_json();
$time_start = microtime(true);
require('../../inc/lib/SpreadsheetReader.php');
// function get_bobot($nilai,$kode_jur)
// {
//   global $db;
//   $q=$db->query("select nilai_indeks from skala_nilai where
//                nilai_huruf='$nilai' and kode_jurusan='$kode_jur' ");
//   foreach ($q as $k) {
//     return $k->nilai_indeks;
//   }
// }


switch ($_GET["act"]) { 

  case 'buat_view':

   $semester_aktif = $_POST['sem'];
   if ($semester_aktif=='all') {
     $semester_aktif = $db->fetch_single_row("semester_ref","aktif",1)->id_semester;
   }
   $db->query("drop view view_krs_single");
   $db->query("create view view_krs_single as select `kelas`.`kls_nama` AS `kls_nama`,`kelas`.`id_matkul` AS `id_matkul`,`krs_detail`.`id_krs_detail` AS `id_krs_detail`,`krs_detail`.`disetujui` AS `disetujui`,`krs_detail`.`nim` AS `nim`,`krs_detail`.`id_semester` AS `id_semester`,`kelas`.`kelas_id` AS `kelas_id` from (`krs_detail` join `kelas` on(`krs_detail`.`id_kelas` = `kelas`.`kelas_id`)) where `krs_detail`.`id_semester` = '$semester_aktif' group by `krs_detail`.`nim`,`krs_detail`.`id_semester`"); 
    break; 

    case 'cetak_kartu':
      $nim = de($_POST['nim_mhs']);
      $sem = de($_POST['sem_mhs']);
      $ket = $_POST['ket_cetak'];
      $q = $db->query("select * from cetak_kartu where nim='$nim' and semester='$sem' and ket='$ket' ");
      if ($q->rowCount()>0) {
        echo "1";
      }else {
        echo "0";
      }
      break;

    case 'import_krs':
        if (!is_dir("../../../upload/upload_excel")) {
          mkdir("../../../upload/upload_excel");
        }


        if (!preg_match("/.(xls|xlsx)$/i", $_FILES["semester"]["name"]) ) {

          echo "pastikan file yang anda pilih xls|xlsx";
          exit();

        } else {
          move_uploaded_file($_FILES["semester"]["tmp_name"], "../../../upload/upload_excel/".$_FILES['semester']['name']);
          $semester = array("semester"=>$_FILES["semester"]["name"]);

        }

        $error_count = 0;
        $error = array();
        $sukses = 0;
        $values = "";
        $array_import = array();

        $Reader = new SpreadsheetReader("../../../upload/upload_excel/".$_FILES['semester']['name']);

        $get_label_kelas = array();
        $get_label_kelas = get_label_kelas();
        $values = array();
        foreach ($Reader as $key => $val)
        {


          if ($key>0) {

            if ($val[0]!='') {

                //first check kode_mk
              $nim = trim(trimmer($val[0]));
              $nama = trim(trimmer($val[1]));
              $semester = trim(trimmer($val[2]));
              $kode_mk = trim(trimmer($val[3]));
              $kelas = trim(trimmer($val[5]));
              $kode_jur = trim(trimmer($val[6]));

              //check nim exist
              $check_nim = $db->fetch_custom_single("select * from mahasiswa where trim(nim)=? and trim(jur_kode)=?",array(
                  'nim' => $nim,
                  'kode_jur' => $kode_jur
                )
              );
              if ($check_nim==false) {
                $error[] = "NIM $nim $nama tidak ditemukan";
                $error_count++;
              } else {
                  //check if kode mk is exist
                $check_kode_mk = $db->fetch_custom_single("select bobot_minimal_lulus,sum(matkul.sks_tm+matkul.sks_prak+matkul.sks_prak_lap+matkul.sks_sim) as sks, matkul.kode_mk,matkul.id_matkul from matkul inner join kurikulum on matkul.kur_id=kurikulum.kur_id
                    where trim(kode_mk)=? and trim(kurikulum.kode_jur)=? and matkul.id_matkul in(select id_matkul from kelas where id_matkul=matkul.id_matkul and kelas.sem_id='$semester') group by id_matkul order by id_matkul desc limit 1",array(
                      'kode_mk' => $kode_mk,
                      'kode_jur' => $kode_jur 
                    )
                  );
                  if ($check_kode_mk==false) {
                    $error[] = "Kode MK ".$check_kode_mk->kode_mk." tidak ditemukan";
                    $error_count++;
                  } else {
                      //check kelas exist
                      $check_kelas = $db->fetch_custom_single("SELECT kelas_id FROM `view_nama_kelas` WHERE `kode_mk` = ? AND `sem_id` = ? AND `kls_nama` = ? ",array(
                          'kode_mk' => $kode_mk,
                          'sem_id' => $semester,
                          'kls_nama' => $kelas
                        )
                      );
                      // print_r(array(
                      //     'sem_id' => $semester,
                      //     'id_matkul' => $check_kode_mk->id_matkul,
                      //     'kls_nama' => $kelas
                      //   )); 
                      if ($check_kelas==false) {
                        $error[] = "Kelas dengan nama $kelas dan kode MK $kode_mk tidak ditemukan";
                        $error_count++;
                      } else {
                        $check_krs = $db->fetch_custom_single("select * from krs_detail where trim(id_semester)=? and kode_mk=? and trim(nim)=?",array(
                          'id_semester' => $semester,
                          'kode_mk' => $check_kode_mk->id_matkul,
                          'nim' => $nim
                        )
                      );
                        if ($check_krs!=false) {
                          $error[] = "Krs $nim $nama $kode_mk di $semester sudah ada";
                          $error_count++;
                        } else {
                          $array_import[] = array(
                            'kode_mk' => $check_kode_mk->id_matkul,
                            'id_kelas' => $check_kelas->kelas_id,
                            'nim' => $check_nim->nim,
                            'id_semester' => $semester,
                            'disetujui' => '1',
                            'batal' => 0,
                            'sks' => $check_kode_mk->sks,
                            'tgl_perubahan' => date('Y-m-d H:i:s'),
                            'pengubah' => $_SESSION['nama']
                          );
                          $sukses++;
                        }


                      }
                  }
              }
      }

      }
      }


if (!empty($array_import)) {
  $db->insertMulti('krs_detail',$array_import);
}


        unlink("../../../upload/upload_excel/".$_FILES['semester']['name']);
        $msg = '';
        $time_end = microtime(true);
        $execution_time = ($time_end - $time_start);

        if (($sukses>0) || ($error_count>0)) {
          $msg =  "<div class=\"alert alert-warning alert-dismissible\" role=\"alert\" style=\"margin-bottom: 0;\" >
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>
          <font color=\"#3c763d\">".$sukses." data KRS baru berhasil di import</font><br />
          <font color=\"#ce4844\" >".$error_count." data tidak bisa ditambahkan </font>";
          if (!$error_count==0) {
            $msg .= "<a data-toggle=\"collapse\" href=\"#collapseExample\" aria-expanded=\"false\" aria-controls=\"collapseExample\">Detail error</a>";
          }
                  //echo "<br />Total: ".$i." baris data";
          $msg .= "<div class=\"collapse\" id=\"collapseExample\">";
          $i=1;
          foreach ($error as $pesan) {
            $msg .= "<div class=\"bs-callout bs-callout-danger\">".$i.". ".$pesan."</div><br />";
            $i++;
          }
          $msg .= "</div>";
          $msg .= "<p>Total Waktu Import : ". waktu_import($execution_time);
          $msg .= "</div>";

        }
        echo $msg;
    break;

   case 'showMatkul':
     $q=$db->query("select m.nama_mk,k.sks,k.bobot,k.nilai_huruf,s.id_semester,kr.mhs_id,a.ip,a.ipk
                    from krs_detail k
                    join krs kr on kr.krs_id=k.id_krs
                    join semester s on s.sem_id=kr.sem_id
                    join akm a on a.mhs_nim=kr.mhs_id and s.id_semester=a.sem_id
                    join matkul m on m.id_matkul=k.kode_mk where k.id_krs='".$_POST['krs_id']."'
                    and k.batal='0'");

     echo '<div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel" style="text-align:center">Detail Mata Kuliah Yang Diambil Pada '.$_POST["semester"].'</h4>
          </div>
           <div class="modal-body">';
     echo "
            <table class='table  table-bordered'>
             <thead>
               <tr>
                  <td>No</td>
                  <td>Nama Mata Kuliah</td>
                  <td>SKS</td>
                  <td>Bobot</td>
                  <td>Nilai</td>
               </tr>
             </thead>
             <tbody>";
      $n=1;
     $bobot=0;
     $sks=0;
     foreach ($q as $k) {
        $bobot =$bobot+$k->bobot;
        $sks = $sks + $k->sks;
        echo "<tr>
                <td>$n</td>
                <td>$k->nama_mk</td>
                <td>$k->sks</td>
                <td>$k->bobot</td>
                <td>$k->nilai_huruf</td>
              </tr>";
         $n++;
     }
     echo "<tr>
            <td colspan='2'><b>Total</b></td>
            <td>$sks</td>
            <td colspan='2'><b>$bobot</b></td>
           </tr>
           <tr>
            <td colspan='2'><b>IP</b></td>
            <td colspan='3'><b>$k->ip</b></td>
           </tr>
           <tr>
            <td colspan='2'><b>IPK</b></td>
            <td colspan='3'><b>$k->ipk</b></td>
           </tr>";

     echo "</tbody>
          </table>";
    echo  '</div>
          <div class="modal-footer">
             <button type="button" class="btn btn-outline" data-dismiss="modal"> Close</button>
           </div>';
  break;

  //show matkul mhs
  case 'showMatkul_mhs':
     $q=$db->query("select m.nama_mk,k.sks,k.bobot,k.nilai_huruf,s.id_semester,kr.mhs_id,a.ip,a.ipk
                    from krs_detail k
                    join krs kr on kr.krs_id=k.id_krs
                    join semester s on s.sem_id=kr.sem_id
                    join akm a on a.mhs_nim=kr.mhs_id and s.id_semester=a.sem_id
                    join matkul m on m.id_matkul=k.kode_mk where k.id_krs='".$_POST['krs_id']."'
                    and k.batal='0'");

     echo '<div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel" style="text-align:center">Detail Mata Kuliah Yang Diambil Pada '.$_POST["semester"].'</h4>
          </div>
           <div class="modal-body">';
     echo "
            <table class='table  table-bordered'>
             <thead>
               <tr>
                  <td>No</td>
                  <td>Nama Mata Kuliah</td>
                  <td>SKS</td>
                  <td>Bobot</td>
                  <td>Nilai</td>
               </tr>
             </thead>
             <tbody>";
      $n=1;
     $bobot=0;
     $sks=0;
     foreach ($q as $k) {
        $bobot =$bobot+$k->bobot;
        $sks = $sks + $k->sks;
        echo "<tr>
                <td>$n</td>
                <td>$k->nama_mk</td>
                <td>$k->sks</td>
                <td>$k->bobot</td>
                <td>$k->nilai_huruf</td>
              </tr>";
         $n++;
     }
     echo "<tr>
            <td colspan='2'><b>Total</b></td>
            <td>$sks</td>
            <td colspan='2'><b>$bobot</b></td>
           </tr>
           <tr>
            <td colspan='2'><b>IP</b></td>
            <td colspan='3'><b>$k->ip</b></td>
           </tr>
           <tr>
            <td colspan='2'><b>IPK</b></td>
            <td colspan='3'><b>$k->ipk</b></td>
           </tr>";

     echo "</tbody>
          </table>";
    echo  '</div>
          <div class="modal-footer">
             <button type="button" class="btn btn-outline" data-dismiss="modal"> Close</button>
           </div>';
  break;

  case 'add_krs':
  //echo "<pre>";
   $q = $db->query("select m.sks_tm,m.sks_prak,m.sks_sim,m.sks_prak_lap,kr.kur_id, m.semester,k.kelas_id,k.id_matkul from kelas k
                    join matkul m on k.id_matkul=m.id_matkul
                    join kurikulum kr on kr.kur_id=m.kur_id
                    where kr.kode_jur='".$_POST['kode_jur']."'
                    and k.sem_id='".$_POST['semester']."' group by k.id_matkul");
 //  print_r($_POST);
   $error = "";
   $sukses = "";
   foreach ($q as $k) {
     if (isset($_POST['krs-'.$k->id_matkul])) {
       //echo $_POST['krs-'.$k->id_matkul]." =x ".$_POST['kelas-'.$k->id_matkul]."<br>";
       $kls = explode("===", $_POST['kelas-'.$k->id_matkul]);


        $data = array('id_krs' => $_POST['krs_id'],
                    'kode_mk' => $_POST['krs-'.$k->id_matkul],
                    'id_kelas' => $kls[0],
                    'sks' => ($k->sks_tm+$k->sks_prak+$k->sks_sim+$k->sks_prak_lap),
                    'tgl_perubahan' => date("Y-m-d"),
                    'pengubah' => $_SESSION['nama'],
                    'disetujui' => '1');
        // print_r($data);
        $data_mk = array('id_krs'   => $_POST['krs_id'],
                         'kode_mk'  => $_POST['krs-'.$k->id_matkul],
                         'id_kelas' => $kls[0]);
        $check = $db->check_exist('krs_detail',$data_mk);
        if ($check==false) {
            if (cek_kelas_penuh($_POST['kelas-'.$k->id_matkul])==false) {
              $db->query("delete from krs_detail where id_krs='".$_POST['krs_id']."'
                       and kode_mk='".$_POST['krs-'.$k->id_matkul]."' ");
              if ($_POST['kelas-'.$k->id_matkul]=='') {
                  $error .= "- Anda belum memilih kelas untuk Mata Kuliah ".$_POST['matkul-'.$k->id_matkul]." <i class='fa fa-remove'></i><br>";
              }
              else{
                   $in =  $db->insert('krs_detail',$data);
                  if ($in=true) {
                     $sukses .= "- Mata kuliah ".$_POST['matkul-'.$k->id_matkul]." kelas ".$kls[1]." Berhasil diambil <i class='fa fa-check'></i><br>";
                    } else {
                      //echo "gagal";
                    }
             }
        }
        else{
           $error .= "- Mata kuliah ".$_POST['matkul-'.$k->id_matkul]." kelas ".$kls[1]." sudah penuh <i class='fa fa-remove'></i><br>";
        }

      }

     }

   }
   if ($sukses!='') {
       echo '<div id="info" class="alert alert-success alert-success"  role="alert">
           '.$sukses.'
          </div>';
   }
   if ($error!='') {
     echo ' <div class="alert alert-danger" role="alert" id="warning">
         '.$error.'</div>';
   }
   if ($sukses=='' && $error=='') {
       echo ' <div class="alert alert-danger" role="alert" id="warning">
                  Anda tidak melakukan penambahan/perubahan KRS
              </div>';
   }



    break;
    //add mhs
    case 'add_krs_mhs':
  //echo "<pre>";$k->sks_tm+$k->sks_prak+$k->sks_sim+$k->sks_prak_lap
   $q = $db->query("select m.sks_tm,m.sks_prak,m.sks_sim,m.sks_prak_lap,kr.kur_id,
                    m.semester,k.kelas_id,k.id_matkul from kelas k
                    join matkul m on k.id_matkul=m.id_matkul
                    join kurikulum kr on kr.kur_id=m.kur_id
                    where kr.kode_jur='".$_POST['kode_jur']."'
                    and k.sem_id='".$_POST['semester']."' group by k.id_matkul");
 //  print_r($_POST);
   $error = "";
   $sukses = "";
   $array_exist = array();

   foreach ($q as $k) {
     if (isset($_POST['krs-'.$k->id_matkul])) {
       //echo $_POST['krs-'.$k->id_matkul]." =x ".$_POST['kelas-'.$k->id_matkul]."<br>";
       $kls = explode("===", $_POST['kelas-'.$k->id_matkul]);

        if ($_POST['kelas-'.$k->id_matkul]=='') {
            $error .= "- Anda belum memilih kelas untuk Mata Kuliah ".$_POST['matkul-'.$k->id_matkul]." <i class='fa fa-remove'></i><br>";
        } else {


     /*  $array_new = array(
                    'kode_mk' => $_POST['krs-'.$k->id_matkul],
                    'id_kelas' => $kls[0],
                    'nim' => $_POST['nim'],
                    'id_semester' => $_POST['id_semester']
                  );
       $exist = $db->fetch_custom_single("select * from krs_detail where nim='".$_POST['nim']."' and id_semester='".$_POST['id_semester']."' and kode_mk='".$_POST['krs-'.$k->id_matkul]."' and id_kelas='".$kls[0]."'");
       if ($exist) {
            $array_exist = array(
                  'kode_mk' => $exist->kode_mk,
                  'id_kelas' => $exist->id_kelas,
                  'nim' => $exist->nim,
                  'id_semester' => $exist->id_semester
                );
       }*/

       // $diff = array_diff($array_new, $array_exist);
      //  if (!empty($diff)) {
             $data = array(
                    'kode_mk' => $_POST['krs-'.$k->id_matkul],
                    'id_kelas' => $kls[0],
                    'sks' => ($k->sks_tm+$k->sks_prak+$k->sks_sim+$k->sks_prak_lap),
                    'nim' => $_POST['nim'],
                    'id_semester' => $_POST['id_semester'],
                    'tgl_perubahan' => date("Y-m-d"),
                    'pengubah' => $_SESSION['nama'],
                    //'disetujui' => '0'
                  );

                if (cek_kelas_penuh($_POST['kelas-'.$k->id_matkul])==false) {
                  $db->query("delete from krs_detail where nim='".$_POST['nim']."' and id_semester='".$_POST['id_semester']."' and kode_mk='".$_POST['krs-'.$k->id_matkul]."' ");
                       $in =  $db->insert('krs_detail',$data);
                      if ($in=true) {
                         $sukses .= "- Mata kuliah ".$_POST['matkul-'.$k->id_matkul]." kelas ".$kls[1]." Berhasil diambil <i class='fa fa-check'></i><br>";
                        } else {
                          //echo "gagal";
                        }
            }
            else{
               $error .= "- Mata kuliah ".$_POST['matkul-'.$k->id_matkul]." kelas ".$kls[1]." sudah penuh <i class='fa fa-remove'></i><br>";
            }
       // }

      }

     }

   }
   if ($sukses!='') {
       echo '<div id="info" class="alert alert-success alert-success"  role="alert">
           '.$sukses.'
          </div>';
   }
   if ($error!='') {
     echo ' <div class="alert alert-danger" role="alert" id="warning">
         '.$error.'</div>';
   }
   if ($sukses=='' && $error=='') {
       echo ' <div class="alert alert-danger" role="alert" id="warning">
                  Anda tidak melakukan penambahan/perubahan KRS
              </div>';
   }



    break;
 case 'batal_krs_mhs':
   $db->query("delete from krs_detail where nim='".$_POST['nim']."'
    and kode_mk='".$_POST['id_matkul']."' and id_semester='".$_POST['sem_id']."' ");
   $q=$db->query("select * from matkul where id_matkul='".$_POST['id_matkul']."' ");
   foreach ($q as $k) {
     echo '
      <form class="form-horizontal" action="" method="post">
        <div class="form-group">
          <div class="col-lg-12">
            <div id="info" class="alert alert-warning alert-info" role="alert">
              Mata kuliah '.$k->nama_mk.' berhasil dibatalkan <i class="fa fa-check"></i>
            </div>
            <textarea class="form-control" id="pesan" name="pesan" cols="50" rows="5" autofocus>'.$k->nama_mk.' tidak dibatalkan karena</textarea>
          </div>
        </div>
      </form>
     ';
   }
    break;
  case 'batal_krs':
   $db->query("delete from krs_detail where id_krs='".$_POST['krs_id']."'
    and kode_mk='".$_POST['id_matkul']."' ");
   $q=$db->query("select * from matkul where id_matkul='".$_POST['id_matkul']."' ");
   foreach ($q as $k) {
     echo '
      <form class="form-horizontal" action="" method="post">
        <div class="form-group">
          <div class="col-lg-12">
            <div id="info" class="alert alert-warning alert-info" role="alert">
              Mata kuliah '.$k->nama_mk.' berhasil dibatalkan <i class="fa fa-check"></i>
            </div>
            <textarea class="form-control" id="pesan" name="pesan" cols="50" rows="5" autofocus>'.$k->nama_mk.' tidak dibatalkan karena</textarea>
          </div>
        </div>
      </form>
     ';
   }
    break;
  case 'batal_krs_pesan':
    $pesan = array(
      'pesan' => $_POST['pesan']
    );
    $nim = $_GET['nim'];
    $set = $db->update('mahasiswa',$pesan,'nim',$nim);
    if($set=true){
      echo "good";
    }else{
      return false;
    }
    break;
  case "in":




  $data = array(
      "mhs_id" => $_POST["mhs_id"],
      "sem_id" => $_POST["sem_id"],
      "id_matkul" => $_POST["id_matkul"],
      "nilai_id" => $_POST["nilai_id"],
      "pengubah" => $_POST["pengubah"],
      "tgl_perubahan" => $_POST["tgl_perubahan"],
  );




          if(isset($_POST["di_setujui"])=="on")
          {
            $di_setujui = array("di_setujui"=>"1");
            $data=array_merge($data,$di_setujui);
          } else {
            $di_setujui = array("di_setujui"=>"0");
            $data=array_merge($data,$di_setujui);
          }
    $in = $db->insert("krs",$data);


    if ($in=true) {
      echo "good";
    } else {
      return false;
    }
    break;
  case "delete":
    $db->delete("krs_detail","id_krs_detail",$_GET["id"]);
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("krs","krs_id",$id);
         }
    }
    break;
  case 'proses_krs':
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $nim_sem = $db->fetch_single_row("krs_detail","id_krs_detail",$id);
          if ($_POST['aksi']=='1') {
            $disetujui = '1';
            $jatah_sks=get_jatah_sks($nim_sem->id_semester,$nim_sem->nim);
            $qs=$db->query("select ma.jur_kode, ma.status_kul,ma.mulai_smt, k.sks,m.id_tipe_matkul,m.sks_tm from krs_detail k
            join matkul m on m.id_matkul=k.kode_mk
            join mahasiswa ma on ma.nim=k.nim  where k.nim='$nim_sem->nim'
            and k.id_semester='$nim_sem->id_semester' and batal='0' ");
            $sks_wajib=0;
            $sks_pilihan=0;
            $total_sks=0;
            foreach ($qs as $ks) {
              if ($ks->id_tipe_matkul=='A' || $ks->id_tipe_matkul=='C' || $ks->id_tipe_matkul=='W') {
                $sks_wajib=$sks_wajib + $ks->sks;
              }else{
                $sks_pilihan = $sks_pilihan + $ks->sks;
              }
              $jur_kode = $ks->jur_kode;
              // if ($ks->status_kul=='reguler') {
              //    $kode_tagihan = 'SKS_REGULER';
              // }else{
              //   $kode_tagihan = 'SKS_NON';
              // }
              $angkatan = $ks->mulai_smt;
            }
            $total_sks = $sks_wajib + $sks_pilihan;
            $data_akm = array('mhs_nim'     => $nim_sem->nim,
                               'sem_id'      => $nim_sem->id_semester,
                               'jatah_sks'   => $jatah_sks,
                               'id_stat_mhs' => 'A',
                               'sks_diambil' => $total_sks,
                               'sks_wajib'   => $sks_wajib,
                               'sks_pilihan' => $sks_pilihan,
                               'total_sks'   => $total_sks
                             );
             buat_akm($data_akm);
            // $id_tagihan_prodi = get_id_tagihan($jur_kode,$kode_tagihan,$angkatan);
            // buat_tagihan($nim_sem->nim,$id_tagihan_prodi,$nim_sem->id_semester);
          } else {
            $disetujui = '0';
          }
          $db->query("update krs_detail set disetujui='$disetujui' where nim=? and id_semester=?",array('nim' => $nim_sem->nim,'id_semester' => $nim_sem->id_semester));

         }
    }
    break;
  case "up":

   $data = array(
      "mhs_id" => $_POST["mhs_id"],
      "sem_id" => $_POST["sem_id"],
      "id_matkul" => $_POST["id_matkul"],
      "nilai_id" => $_POST["nilai_id"],
      "pengubah" => $_POST["pengubah"],
      "tgl_perubahan" => $_POST["tgl_perubahan"],
   );





          if(isset($_POST["di_setujui"])=="on")
          {
            $di_setujui = array("di_setujui"=>"1");
            $data=array_merge($data,$di_setujui);
          } else {
            $di_setujui = array("di_setujui"=>"0");
            $data=array_merge($data,$di_setujui);
          }

    $up = $db->update("krs",$data,"krs_id",$_POST["id"]);

    if ($up=true) {
      echo "good";
    } else {
      return false;
    }
    break;
  default:
    # code...
    break;
}

?>
