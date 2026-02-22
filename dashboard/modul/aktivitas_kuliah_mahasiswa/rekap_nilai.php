<?php
session_start();
include "../../inc/config.php";
session_check_json();

function update_akm($nim){
   global $db;
   $data_akm=$db->query("select k.krs_id, s.kode_jur,s.id_semester from krs k join semester s on s.sem_id=k.sem_id
                  where k.mhs_id='$nim'");
   $ipk=0;
   $bobot_ipk=0;
   $sks_ipk=0;
   foreach ($q as $k) {
      $ip=0;
      $ipk=0;
      foreach ($db->query("select sum(k.sks) as jml_sks, sum(k.bobot * k.sks) as jml_bobot from krs_detail k 
                           where k.krs_detail_id='$k->krs_id' and k.batal='0' group by k.krs_detail_id ") 
              as $kk) {
         $bobot_ipk = $bobot_ipk + $kk->jml_bobot;
         $sks_ipk = $sks_ipk + $kk->jml_sks;
         $ipk=$bobot_ipk/$sks_ipk;
         $ip=$kk->jml_bobot/$kk->jml_sks;
         $db->query("update akm set ip='".number_format($ip,2)."',ipk='".number_format($ipk,2)."' where sem_id='$k->id_semester'
                     and mhs_nim='$nim' ");
      }
   }
}

?>