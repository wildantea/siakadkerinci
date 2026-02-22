<?php
session_start();
include "../../inc/config.php";
session_check();
switch ($_GET["act"]) {

  case "cetak":
    $db->query("select kls_nama from kelas where sem_id='".$_GET['semester']."'");
    break;
  
  case "hapusD":
  //echo "stringxx";
  // print_r($_GET);
    $db->query("delete from dosen_kelas where id_kelas='".$_GET['kelas']."' and id_dosen='".$_GET['nip']."' ");
    break;

  case "in":
    
  
  
  
  $data = array(
      "kls_nama" => $_POST["kls_nama"],
      "kode_paralel" => $_POST["kode_paralel"],
      "id_matkul" => $_POST["id_matkul"],
      "id_matkul_setara" => $_POST["id_matkul_setara"],
      "sem_id" => $_POST["sem_id"],
      "peserta_max" => $_POST["peserta_max"],
      "peserta_min" => $_POST["peserta_min"],
      "catatan" => $_POST["catatan"],
  );
   
          if(isset($_POST["is_open"])=="on")
          {
            $is_open = array("is_open"=>"Y");
            $data=array_merge($data,$is_open);
          } else {
            $is_open = array("is_open"=>"N");
            $data=array_merge($data,$is_open);
          }
    $in = $db->insert("kelas",$data);
    $id_kelas = $db->last_insert_id();

/*    $data_penilaian = array('presensi' => $_POST['presensi'],
                            'mandiri'  => $_POST['mandiri'],
                            'terstruktur' => $_POST['terstruktur'],
                            'lain_lain' => $_POST['lain_lain'],
                            'uts' => $_POST['uts'],
                            'uas' => $_POST['uas'],
                            'id_kelas' => $id_kelas );*/
    foreach ($db->query("select * from komponen_nilai  where isShow='1' ") as $kp) { 
      if (isset($_POST['komponen_'.$kp->id])) {
         $data_komponen = array('id_kelas' => $id_kelas,
                             'id_komponen' => $kp->id,
                             'nilai' => $_POST['komponen_'.$kp->id] );
        $in_kelas = $db->insert('kelas_penilaian',$data_komponen);
      }     
    }                      
   
    $jml_dosen=$_POST['jml_dosen'];
    for ($i=1; $i <=$jml_dosen ; $i++) { 
      if (isset($_POST['dosen_'.$i])) {
        if (isset($_POST['dapat_input_'.$i]) && $_POST['dapat_input_'.$i]) {
          $dapat_input = 'Y';
        }else{
          $dapat_input = 'N';
        }
        $dosen_ajar = array('id_kelas' => $id_kelas,
                            'id_dosen' => $_POST['dosen_'.$i],
                            'dosen_ke' => $_POST['dosen_input_'.$i],
                            'dapat_input' => $dapat_input);
       // print_r($dosen_ajar);
        $in_dosen = $db->insert('dosen_kelas',$dosen_ajar);
      }
    }
/*    var_dump($in);
    var_dump($in_kelas);
    var_dump($in_dosen);*/
    if ($in) {
        $db->insert("jadwal_kuliah",array('kelas_id' => $id_kelas ));
        echo "good";
    } else {
      return false;
    }
    break;
  case "delete":
    
    
    
    $db->delete("kelas","kelas_id",$_GET["id"]);
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("kelas","kelas_id",$id);
         }
    }
    break;
  case "up":
    //echo $_POST['id'];
           $data = array(
              "kls_nama" => $_POST["kls_nama"],
              "kode_paralel" => $_POST["kode_paralel"],
              "id_matkul" => $_POST["id_matkul"],
              "id_matkul_setara" => $_POST["id_matkul_setara"],
              "sem_id" => $_POST["sem_id"],
              "peserta_max" => $_POST["peserta_max"],
              "peserta_min" => $_POST["peserta_min"],
              "catatan" => $_POST["catatan"],
          );

          if(isset($_POST["is_open"])=="on")
          {
            $is_open = array("is_open"=>"Y");
            $data=array_merge($data,$is_open);
          } else {
            $is_open = array("is_open"=>"N");
            $data=array_merge($data,$is_open);
          }
    
    $up = $db->update("kelas",$data,"kelas_id",$_POST["id"]);
    $db->query("delete from kelas_penilaian where id_kelas='".$_POST["id"]."' ");
    foreach ($db->query("select * from komponen_nilai  where isShow='1' ") as $kp) { 
      if (isset($_POST['komponen_'.$kp->id])) {
         $data_komponen = array('id_kelas' => $_POST["id"],
                                'id_komponen' => $kp->id,
                                'nilai' => $_POST['komponen_'.$kp->id] );
        $in_kelas = $db->insert('kelas_penilaian',$data_komponen);
      }     
    } 
    $jml_dosen=$_POST['jml_dosen'];
    $db->query("delete from dosen_kelas where id_kelas='".$_POST["id"]."'");
    for ($i=1; $i <=$jml_dosen ; $i++) { 
      if (isset($_POST['dosen_'.$i])) {
        if (isset($_POST['dapat_input_'.$i]) && $_POST['dapat_input_'.$i]) {
          $dapat_input = 'Y';
        }else{
          $dapat_input = 'N';
        }
        $dosen_ajar = array('id_kelas' => $_POST['id'],
                            'id_dosen' => $_POST['dosen_'.$i],
                            'dosen_ke' => $_POST['dosen_input_'.$i],
                            'dapat_input' => $dapat_input);
        $db->insert('dosen_kelas',$dosen_ajar);
      }
    }
    if ($up=true) {
      echo "good";
    } else {
      return false;
    }
    break;
  default:
  //echo "string";
    break;
}

?>