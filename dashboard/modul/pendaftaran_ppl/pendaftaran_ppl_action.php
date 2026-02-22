<?php
session_start();
include "../../inc/config.php";
session_check();
switch ($_GET["act"]) {

  case "download_data":
  header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=peserta_ppl.xls");  //File name extension was wrong
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);
   $fakultas="";
  $jurusan="";
  $priode="";
  $lokasi ="";
  $jk = ""; 

  $jur_filter = "";
//get default akses prodi 


  if(isset($_GET['fakultas'])) {
    if($_GET['fakultas']!='all') {
      $fakultas = ' and fakultas.kode_fak="'.$_GET['fakultas'].'"';
    }
  }

  if(isset($_GET['jurusan'])) {
    if($_GET['jurusan']!='all') {
      $jur_filter = ' and jurusan.kode_jur="'.$_GET['jurusan'].'"';
    }
  }

  if(isset($_GET['priode'])) {
    if($_GET['priode']!='all') {
      $priode = ' and priode_ppl.id_priode="'.$_GET['priode'].'"';
    }
  }  

  if(isset($_GET['id_lokasi'])) {
    if($_GET['id_lokasi']!='all') {
      $lokasi = ' and lk.id_lokasi="'.$_GET['id_lokasi'].'"';
    }
  } 

  if(isset($_GET['jk'])) { 
    if($_GET['jk']!='undefined') { 
      $jk = ' and mahasiswa.jk="'.$_GET['jk'].'"';
    }
  }  

  // if($_SESSION['level']=='6'){
  //   if($_SESSION['id_fak'] != NULL){
  //     $fakultas = ' and fakultas.kode_fak="'.$_SESSION['id_fak'].'"';
  //   }
  // }
  ?>
    <table border="1">
        <thead>
          <tr>
            <th>No</th>
            <th>Nim</th>
            <th>Nama</th>
            <th>Kelamin</th>
            <th>Fakultas</th>
            <th>Jurusan</th>
            <th>Lokasi Kukerta</th>
             <th>Kode MK</th>
            <th>Nama MK</th>
            <th>Nilai Angka</th>
            <th>Nilai Huruf</th>
           
          </tr>
        </thead>
        <tbody>
       
  <?php
  $no=1;

 // die();
  $q  = $db->query("select lk.nama_lokasi,mahasiswa.jk, ppl.nim,mahasiswa.nama,fakultas.nama_resmi,jurusan.nama_jur,ppl.id_kkn, 
    (select ifnull(matkul.kode_mk,'-') from krs_detail join matkul on matkul.id_matkul=krs_detail.kode_mk
 where krs_detail.kode_mk in (select id_matkul from v_matkul_ppl) and krs_detail.nim=ppl.nim and nilai_huruf is not null order by nilai_huruf asc limit 1) as kode_mk,  
(select ifnull(matkul.nama_mk,'-') from krs_detail join matkul on matkul.id_matkul=krs_detail.kode_mk
 where krs_detail.kode_mk in (select id_matkul from v_matkul_ppl) and krs_detail.nim=ppl.nim and nilai_huruf is not null order by nilai_huruf asc limit 1) as nama_mk, 
(select ifnull(nilai_angka,'-') from krs_detail where kode_mk in (select id_matkul from v_matkul_ppl) and krs_detail.nim=ppl.nim and nilai_huruf is not null order by nilai_huruf asc limit 1) as nilai_angka, 
(select ifnull(nilai_huruf,'-') from krs_detail where kode_mk in (select id_matkul from v_matkul_ppl) and krs_detail.nim=ppl.nim and nilai_huruf is not null order by nilai_huruf asc limit 1) as nilai_huruf from ppl 
inner join mahasiswa on ppl.nim=mahasiswa.nim inner join fakultas on ppl.kode_fak=fakultas.kode_fak inner join jurusan on ppl.kode_jur=jurusan.kode_jur left join priode_ppl on priode_ppl.id_priode=ppl.id_priode 
left join lokasi_ppl lk on lk.id_lokasi=ppl.id_lokasi where  id_kkn is not null $fakultas $jur_filter $priode $lokasi $jk"); 
  foreach ($q as $k) {
      echo "<tr>
       <td>$no</td>
       <td>$k->nim</td>
       <td>$k->nama</td>
       <td>$k->jk</td>
       <td>$k->nama_resmi</td>
       <td>$k->nama_jur</td>
       <td>$k->nama_lokasi</td>
       <td>$k->kode_mk</td>
       <td>$k->nama_mk</td>
       <td>$k->nilai_angka</td>
       <td>$k->nilai_huruf</td>
  
      </tr>";
      $no++;
  }
  ?>
</tbody>
</table>
  <?php
  break;
   
   case "set_aktif_periode":
     $db->query("update priode_ppl set aktif='0' ");
    $db->query("update priode_ppl set aktif='1' where id_priode='".$_POST['id']."' ");
     break;

  case "in":

    $datap = array(
        "nim"         => $_POST["nim"],
        "kode_fak"    => $_POST["kode_fak"],
        "kode_jur"    => $_POST["kode_jurusan"],
        "id_priode"   => $_POST["id_priode"],
        "id_lokasi"   => $_POST["id_lokasi"],
        "created_at"  => date("Y-m-d H:i:s"),
        "last_update" => $_SESSION["id_user"]
    );
   // print_r($datap);

    $in = $db->insert("ppl",$datap);
   // var_dump($in);
   // echo $db->getErrorMessage();
      
    if ($in=true) {
      echo "good";
    } else {
      return false;
    }
    break;
  case "in_mhs":
  // print_r($_POST);
    $kelamin = get_kelamin($_POST["nim"]);   
    $status = cek_kuota_kukerta($_POST["id_lokasi"],$_POST["kode_jur"],$kelamin);
    if ($status['status']) {
      $data = array(
        "nim" => $_POST["nim"],
        "kode_fak"  => $_POST["kode_fak"],
        "kode_jur"  => $_POST["kode_jur"],
        "id_priode" => $_POST["id_priode"],
        "id_lokasi" => $_POST["id_lokasi"],
        "created_at"  => date("Y-m-d H:i:s"),
        "last_update" => $_SESSION["id_user"]
        );

        $in_mhs = $db->insert("ppl",$data);
          
        if ($in=true) { 
          echo "good"; 
        } else {
          return false;
        }
    }else{
       echo "<div class='alert alert-danger'>".$status['pesan']."</div>";
    }
    
    break;
  case "delete":

    $db->delete("ppl","id_kkn",$_GET["id"]);
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("ppl","id_ppl",$id);
         }
    }
    break;
  case "up":
   $kelamin = get_kelamin($_POST["nim"]);   
   $status = cek_kuota_ppl($_POST["id_lokasi"],$_POST["kode_jur"],$kelamin);
    if ($status['status']) {
       $data = array(
          "nim" => $_POST["nim"],
          // "kode_fak"  => $_POST["kode_fak"],
          // "kode_jur"  => $_POST["kode_jurusan"],
          // "id_priode" => $_POST["id_priode"],
          "id_lokasi" => $_POST["id_lokasi"],
         "updated_at"   => date("Y-m-d H:i:s"), 
          "last_update" => $_SESSION["id_user"]
       );
       
        $up = $db->update("ppl",$data,"id_ppl",$_POST["id"]);
      
        if ($up=true) {
          echo "good";
        } else {
          return false;
        }
  }else{
       echo "<div class='alert alert-danger'>".$status['pesan']."</div>";
  }   

  break;
  case "up_admin":
   $kelamin = get_kelamin($_POST["nim"]);   
       $data = array(
          "nim" => $_POST["nim"],
          // "kode_fak"  => $_POST["kode_fak"],
          // "kode_jur"  => $_POST["kode_jurusan"],
          // "id_priode" => $_POST["id_priode"],
          "id_lokasi" => $_POST["id_lokasi"],
         "updated_at"   => date("Y-m-d H:i:s"), 
          "last_update" => $_SESSION["id_user"]
       );
       
        $up = $db->update("ppl",$data,"id_kkn",$_POST["id"]);
        if ($up=true) {
          echo "good";
        } else {
          return false;
        }

  break;
  case 'in_priode':
    $data = array(
      "priode"  => $_POST["priode_beasiswa"],
      "tgl_awal"  => $_POST["tgl2"],
      "nama_periode" => $_POST["nama_periode"],
      "tgl_akhir" => $_POST["tgl3"],
      "tgl_awal_daftar"  => $_POST["tgl_awal_daftar"],
      "tgl_akhir_daftar"  => $_POST["tgl_akhir_daftar"],
       "tgl_awal_input_nilai"  => $_POST["tgl_awal_input_nilai"],
       "tgl_akhir_input_nilai"  => $_POST["tgl_akhir_input_nilai"],
      "created_at"  => date("Y-m-d H:i:s"),
      "last_update" => $_SESSION["id_user"]
    );

    $in_priode = $db->insert("priode_ppl",$data);

    if($in_priode=true){
      echo "good";
    }else {
      return false;
    }
    break;
  case 'up_priode':
    $data = array(
       "priode"  => $_POST["priode_beasiswa"],
       "tgl_awal"  => $_POST["tgl2"],
       "nama_periode" => $_POST["nama_periode"],
       "tgl_awal_daftar"  => $_POST["tgl_awal_daftar"],
       "tgl_akhir_daftar"  => $_POST["tgl_akhir_daftar"],
       "tgl_awal_input_nilai"  => $_POST["tgl_awal_input_nilai"],
       "tgl_akhir_input_nilai"  => $_POST["tgl_akhir_input_nilai"],
       "tgl_akhir" => $_POST["tgl3"],
       "created_at"  => date("Y-m-d H:i:s"),
       "last_update" => $_SESSION["id_user"]
    );
   
    $up_priode = $db->update("priode_ppl",$data,"id_priode",$_POST["id"]);
    
    if ($up_priode=true) {
      echo "good";
    } else {
      return false;
    }
    break;
  case "delete_priode":

    $db->delete("priode_ppl","id_priode",$_POST['id_data']);
    break;

  case "del_massal_priode":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("priode_ppl","id_priode",$id);
         }
    }
    break;

  case 'in_lokasi':
    $data = array(
      "nama_lokasi"  => $_POST["lokasi"],
      "dpl"          => $_POST["id_dpl"],
      "dpl2"         => $_POST["dpl2"],
      "id_periode"   => $_POST["id_periode"],
      "kuota_p"      => $_POST["kuota_p"],
      "kuota_l"      => $_POST["kuota_l"],
      "kuota"        => $_POST['kuota'],
      "ket"          => $_POST['ket'],
      "created_at"   => date("Y-m-d H:i:s"),
      "last_update"  => $_SESSION["id_user"] 
    );
    $data['dpl2'] = 0;
    if ($_POST['dpl2']!='all') {
      $data['dpl2'] = $_POST['dpl2'];
    }
   // print_r($data);

    $in_lokasi = $db->insert("lokasi_ppl",$data);
    echo $db->getErrorMessage();
   // echo $db->getErrorMessage(); 

    if($in_lokasi=true){
      $id_lokasi = $db->last_insert_id();
      $qj = $db->query("select kode_jur,nama_jur from jurusan");
      foreach ($qj as $kj) {
        $kuota_jur = $_POST['kuota_jur_'.$kj->kode_jur];
        $dat = array('id_lokasi' => $id_lokasi ,  
                     'kode_jur'  => $kj->kode_jur,
                     'kuota'     => $kuota_jur,
                     'date_created' => date("Y-m-d H:i:s"),
                     'user'      => $_SESSION['username']);
        if ($kuota_jur!='') {
          $db->insert("kuota_jurusan_ppl",$dat); 
           echo $db->getErrorMessage();
        }
      }
      echo "good";
    }else {
      return false;
    }
  break;

  case 'up_lokasi':
    $data = array(
      "nama_lokasi"  => $_POST["lokasi"],
      "dpl"          => $_POST["id_dpl"],
      "id_periode"     => $_POST["id_periode"], 
      "kuota_p"      => $_POST["kuota_p"],
      "kuota_l"      => $_POST["kuota_l"],
      "kuota"        => $_POST['kuota'],
      "ket"          => $_POST['ket'],
      "created_at"   => date("Y-m-d H:i:s"),
      "last_update"  => $_SESSION["id_user"]
    );

     $data['dpl2'] = 0;
    if ($_POST['dpl2']!='all') {
      $data['dpl2'] = $_POST['dpl2'];
    }
   
    $up_lokasi = $db->update("lokasi_ppl",$data,"id_lokasi",$_POST["id"]);

    echo $db->getErrorMessage();
    
    if ($up_lokasi=true) { 
     // $id_lokasi = $db->last_insert_id();
       $db->query("delete from kuota_jurusan_ppl where id_lokasi='".$_POST["id"]."'  "); 
      $qj = $db->query("select kode_jur,nama_jur from jurusan");
      foreach ($qj as $kj) {
        $kuota_jur = $_POST['kuota_jur_'.$kj->kode_jur];
        $dat = array('id_lokasi' => $_POST["id"], 
                     'kode_jur' => $kj->kode_jur,
                     'kuota' => $kuota_jur,
                     'date_created' => date("Y-m-d H:i:s"),
                     'user' => $_SESSION['username']);
        if ($kuota_jur!='') {
          $db->insert("kuota_jurusan_ppl",$dat); 
        }
      }
      echo "good";
    } else {
      return false;
    }
    break;

  case "delete_lokasi":

    $db->delete("lokasi_ppl","id_lokasi",$_POST['id_data']);
    break;

  case "del_massal_lokasi":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("lokasi_ppl","id_lokasi",$id);
         }
    }
    break;
  case 'up_nilai':
    $data = array(
      "nilai_ppl" => $_POST["nilai"]
    );

    $up_nilai = $db->update("ppl",$data,"id_ppl",$_POST["id"]);

    if($up_nilai=true) {
      echo "good";
    }else {
      return false;
    }  
    break;      
  default:
    # code...
    break;
}

?>