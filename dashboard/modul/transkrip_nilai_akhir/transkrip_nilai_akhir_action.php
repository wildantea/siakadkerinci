<?php
session_start();
include "../../inc/config.php";
session_check();
switch ($_GET["act"]) {
  case "update_nilai":
    foreach ($db->query("select k.*, km.nama_komponen from kelas_penilaian k
                        join komponen_nilai km on km.id=k.id_komponen
                        join krs_detail kr on kr.id_kelas=k.id_kelas where
                        kr.id_krs_detail='".$_POST['id']."'") as $k) {
             $komponen[$k->id_komponen]=$k->nilai;
    }

    $komponen_kosong=true;
    $nilai_angka=0;
    foreach ($komponen as $key => $value) {
        if ($_POST['komponen_'.$key]!='') {
           $db->query("update krs_penilaian set nilai_angka='".$_POST['komponen_'.$key]."',
                       edit_by='".$_SESSION['nama']."'
                       where id_krs_detail='".$_POST['id']."' and id_komponen='$key' ");
           if ($value!=0) {
             $nilai_angka = $nilai_angka + (((int)$_POST['komponen_'.$key]/100)*$value);
           }
           $komponen_kosong=false;
        }
    }

    if (isset($_POST['use_rule'])) {
       $nilai_total = $nilai_angka;
       $db->query("update krs_detail set use_rule='1' where id_krs_detail='".$_POST['id']."' ");
        foreach ($db->query("select * from nilai_ref n where n.batas_bawah<=$nilai_total and $nilai_total<=n.batas_atas
                                and prodi_id='".$_POST['jur']."' ") as $n) {
              if ($nilai_total==0) {
                //$data['bobot'] =  $n->bobot;
                $bobotq="bobot=NULL";
                $nilai_hurufq="nilai_huruf=NULL";
              }else{
                 $bobotq="bobot='$n->bobot'";
                 $nilai_hurufq="nilai_huruf='$n->nilai_huruf' ";
              }

              $db->query("update krs_detail set nilai_angka='$nilai_total',
                          $bobotq,$nilai_hurufq, pengubah='".$_SESSION['nama']."'
                          where id_krs_detail='".$_POST['id']."' ");
          echo $n->bobot."-".$n->nilai_huruf."-".$_POST['id'];
        }
    }else{
        $db->query("update krs_detail set use_rule='0' where id_krs_detail='".$_POST['id']."' ");
        $nilai = explode("-", $_POST['nilai_huruf']);
        $db->query("update krs_detail set bobot='".$nilai[0]."',nilai_huruf='".$nilai[1]."',
                    pengubah='".$_SESSION['nama']."',tgl_perubahan='".date("Y-m-d H-i-s")."'
                    where id_krs_detail='".$_POST['id']."' ");
        $data = array('krs_id' => $_POST['id'],
                      'nilai_huruf' => $nilai[1],
                      'tgl_perubahan' => date("Y-m-d H-i-s"),
                      'pengubah' =>  $_SESSION['nama']);
        $db->insert("krs_history",$data);
        echo $nilai[0]."-".$nilai[1]."-".$_POST['id'];
    }
    update_akm($_POST['mhs_id']);



  break;
  case "show_update_nilai":
   echo "<table class='table'>";
   foreach ($db->query("select k.`*`, km.nama_komponen,m.kode_mk,m.nama_mk,
                        kr.bobot,kr.nilai_huruf,kr.id_krs_detail,j.kode_jur,krs.mhs_id from kelas_penilaian k
                        join komponen_nilai km on km.id=k.id_komponen
                        join krs_detail kr on kr.id_kelas=k.id_kelas
                        join matkul m on m.id_matkul=kr.kode_mk
                        join kurikulum ku on ku.kur_id=m.kur_id
                        join jurusan j on j.kode_jur=ku.kode_jur
                        join krs on krs.krs_id=kr.id_krs
                        where kr.id_krs_detail='".$_POST['krs_id']."' group by m.kode_mk")
         as $kk) {
     echo "<tr>
            <input type='hidden' name='jur' value='$kk->kode_jur'>
            <input type='text' name='mhs_id' value='$kk->mhs_id'>
            <tr><td colspan='2'><b>Matakuliah</b></td></tr>
            <tr><td>Kode MK</td><td>$kk->kode_mk</td></tr>
            <tr><td>Nama MK</td><td>$kk->nama_mk</td></tr>
           </tr>";
   }
   echo "<tr><td colspan='2'><b>Komponen Penilaian</b> </td></tr>";
   foreach ($db->query("select k.`*`, km.nama_komponen from kelas_penilaian k
                        join komponen_nilai km on km.id=k.id_komponen
                        join krs_detail kr on kr.id_kelas=k.id_kelas where
                        kr.id_krs_detail='".$_POST['krs_id']."'") as $k) {
        echo "<tr>
                <td style='width:100px'>$k->nama_komponen</td>
                <td><input style='width:70px;height:30px' type='number' class='komponen_nilai' name='komponen_$k->id_komponen'> $k->nilai %</td>
              </tr>              ";
   }
   echo "<tr>
              <td>Nilai Huruf</td>
              <td><select name='nilai_huruf'>";
   foreach ($db->query("select * from nilai_ref") as $n) {
     if ($kk->nilai_huruf==$n->nilai_huruf) {
      echo "<option value='$n->bobot-$n->nilai_huruf' selected>$n->nilai_huruf</option>";
     }else{
      echo "<option value='$n->bobot-$n->nilai_huruf'>$n->nilai_huruf</option>";
     }

   }
   echo "</select></td></tr>
        <tr>
         <td>Gunakan Rule Komponen</td>
         <td><input type='checkbox' name='use_rule' value='1' onchange='set_rule(this)' style='width:20px;height:20px'></td>
        </tr></table>
        <input type='hidden' name='id' value='$kk->id_krs_detail'>";
    break;
  case "cari_mhs":
    # code...
    break;
  case "in":

  


  $data = array(
      "nm_agama" => $_POST["nm_agama"],
  );




    $in = $db->insert("agama",$data);


    if ($in=true) {
      echo "good";
    } else {
      return false;
    }
    break;
  case "delete":



    $db->delete("agama","id_agama",$_GET["id"]);
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("agama","id_agama",$id);
         }
    }
    break;
  case "up":

   $data = array(
      "nm_agama" => $_POST["nm_agama"],
   );






    $up = $db->update("agama",$data,"id_agama",$_POST["id"]);

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
