<?php
switch (uri_segment(2)) {
   case "show-nilai":
   // echo de(uri_segment(3)); die();
   $nim = de(uri_segment(3));
    $q=$db->query("select m.nim,m.nama,j.nama_jur as jurusan,f.nama_resmi as fakultas from mahasiswa m
                  join jurusan j on j.kode_jur=m.jur_kode
                  join fakultas f on f.kode_fak=j.fak_kode where m.nim='$nim'");
    foreach ($q as $k) {
       include "hasil_studi_mahasiswa_all.php";
    }

    break;
    case "tambah":
          foreach ($db->fetch_all("sys_menu") as $isi) {
               if (uri_segment(1)==$isi->url&&uri_segment(2)=="tambah") {
                          if ($role_act["insert_act"]=="Y") {
                             include "hasil_studi_mahasiswa_add.php";
                          } else {
                            echo "permission denied";
                          }
                       }

      }
    break;
  case "edit":
    $data_edit = $db->fetch_single_row("krs_detail","id_krs_detail",uri_segment(3));
        foreach ($db->fetch_all("sys_menu") as $isi) {
                      if (uri_segment(1)==$isi->url&&uri_segment(2)=="edit") {
                          if ($role_act["up_act"]=="Y") {
                             include "hasil_studi_mahasiswa_edit.php";
                          } else {
                            echo "permission denied";
                          }
                       }

      }

    break;
    case "detail":
    $data_edit = $db->fetch_single_row("krs_detail","id_krs_detail",uri_segment(3));
    include "hasil_studi_mahasiswa_detail.php";
    break;
  case 'coba':
    //include "hasil_studi_view_admin.php";

function update_akm_coba($nim) {
  global $db2;
    $mhs = $db2->fetchSingleRow("mahasiswa","nim",$nim);
    $array_semester = array();
    //check if he has semester pendek
    $sm_pendek = $db2->fetchCustomSingle("select group_concat(distinct id_semester) as id_semester from krs_detail where nim='".$nim."' and right(id_semester,1)='3'");
    if ($sm_pendek) {
      $semester_pendek = explode(",",  $sm_pendek->id_semester);
      $array_semester = array_merge($array_semester, $semester_pendek);
    }
    $array_semester = array_filter($array_semester);
    //loop over semester from semester awal to current semester
    $loop_data_semester = $db2->fetchCustomSingle("select group_concat(distinct id_semester) as id_semester from semester where (id_semester>='".$mhs->mulai_smt."' and id_semester<='".getSemesterAktif()."') and right(id_semester,1) in(1,2)");
    $semester_data = explode(",",  $loop_data_semester->id_semester);
    $array_semester = array_merge($array_semester, $semester_data);
    $array_semester = array_unique($array_semester);
    sort($array_semester);


          if (!empty($array_semester)) {
            $mhs1 = $db2->fetchSingleRow("view_simple_mhs_data","nim",$nim);
            $where_nim_mhs = "and mahasiswa.nim='".$nim."'";
            dump($array_semester);
            foreach ($array_semester as $sem_id) {
                //insert unprocess akm
                $db2->query("insert ignore into akm (mhs_nim,sem_id,id_stat_mhs,ip,ipk,jatah_sks,sks_diambil,sks_wajib,sks_pilihan,total_sks,date_created)
                select nim,".$sem_id.",'N',0,0,0,0,0,0,0,now() from mahasiswa where nim not in(select mhs_nim from akm where mhs_nim=mahasiswa.nim and  sem_id=".$sem_id.") $where_nim_mhs");
                echo $db2->getErrorMessage();
                $array_s1_s3 = array('30','40');
                if (in_array($mhs1->id_jenjang, $array_s1_s3)) {
                  //delete akm yang statusnya lebih dari 14 semester
                  $db2->query("delete akm from akm inner join view_simple_mhs_data on mhs_nim=nim where sem_id='$sem_id' and mhs_nim='$mhs1->nim' and ((left('$sem_id',4)-left(mulai_smt,4))*2)+right('$sem_id',1)-(floor(right(mulai_smt,1)/2)) > 14");
                }

                $array_s2 = array('35','40');
                if (in_array($mhs1->id_jenjang, $array_s2)) {
                  //delete akm yang statusnya lebih dari 14 semester
                  $db2->query("delete akm from akm inner join view_simple_mhs_data on mhs_nim=nim where sem_id='$sem_id' and mhs_nim='$mhs1->nim' and ((left('$sem_id',4)-left(mulai_smt,4))*2)+right('$sem_id',1)-(floor(right(mulai_smt,1)/2)) > 8");
                }
            }
            //get akm exist
            $akm_sem = $db2->query("select sem_id from akm where right(sem_id,1)='3' and mhs_nim='$nim'");
            if ($akm_sem->rowCount()>0) {
              foreach ($akm_sem as $s_id) {
                 dump($s_id);
                $db2->query("delete akm from akm inner join mahasiswa on mhs_nim=mahasiswa.nim where sem_id='$s_id->sem_id'  and mhs_nim='$nim' and mhs_nim not in(select nim from krs_detail where krs_detail.id_semester='".$s_id->sem_id."')");
                echo $db2->getErrorMessage();
              }
            }
          }


    $implode_semester = implode(",", $array_semester);

    $datas = $db2->query("select akm_id,id_stat_mhs,akm.mhs_nim,akm.sem_id,(select sum(sks) from krs_detail inner join matkul 
on krs_detail.kode_mk=matkul.id_matkul
 where nim=akm.mhs_nim and id_semester=akm.sem_id  and krs_detail.disetujui='1' and batal=0) as sks,
(select format(sum(bobot * sks)/sum(sks),2) from krs_detail inner join matkul 
on krs_detail.kode_mk=matkul.id_matkul
 where nim=akm.mhs_nim and id_semester=akm.sem_id and krs_detail.disetujui='1' and batal=0 and bobot  is not null) as ip,
(select format(sum(bobot * sks)/sum(sks),2) from krs_detail inner join matkul 
on krs_detail.kode_mk=matkul.id_matkul
 where nim=akm.mhs_nim and id_semester<=akm.sem_id  and krs_detail.disetujui='1' and batal=0 and bobot is not null) as ipk,
(select sks_mak from jatah_sks j where  IFNULL((select akm.ip  from akm akm where akm.mhs_nim=akm.mhs_nim
 and akm.sem_id<akm.sem_id
and akm.id_stat_mhs='A' ORDER BY sem_id DESC LIMIT 1),4) BETWEEN j.ip_min and j.ip_mak) as jatah_sks,
(select SUM(IF(a_wajib = '1', sks,0)) from krs_detail 
inner join matkul on krs_detail.kode_mk=id_matkul where nim=akm.mhs_nim and id_semester=akm.sem_id  and krs_detail.disetujui='1' and batal=0) as sks_wajib_diambil_kumulatif,
(select SUM(IF(a_wajib = '0', sks,0)) from krs_detail
inner join matkul on krs_detail.kode_mk=id_matkul where nim=akm.mhs_nim and id_semester=akm.sem_id  and krs_detail.disetujui='1' and batal=0) as sks_pilihan_diambil_kumulatif,
(select SUM(sks) from krs_detail 
inner join matkul on krs_detail.kode_mk=id_matkul where nim=akm.mhs_nim and id_semester<=akm.sem_id  and krs_detail.disetujui='1' and batal=0) as sks_kumulatif,
(select nim from tb_data_cuti_mahasiswa inner join tb_data_cuti_mahasiswa_periode using(id_cuti) where nim=akm.mhs_nim and status_acc!='rejected' and periode=akm.sem_id limit 1) as is_cuti
 from akm 
inner join mahasiswa  on akm.mhs_nim=mahasiswa.nim where akm.sem_id in($implode_semester) and akm.mhs_nim='".$nim."'");

    if ($datas->rowCount()>0) {
      foreach ($datas as $value) {
      //dump($value);
      if ($value->sks=="") {
        $sks_semester=0;
      } else {
        $sks_semester = $value->sks;
      }
      if ($value->ip=="") {
        $ip = 0;
      } else {
        $ip = $value->ip;
      }
      if ($value->ipk=="") {
        $ipk = 0;
      } else {
        $ipk = $value->ipk;
      }
      if ($value->sks_wajib_diambil_kumulatif=="") {
        $sks_wajib_kumulatif = 0;
      } else {
        $sks_wajib_kumulatif = $value->sks_wajib_diambil_kumulatif;
      }
      if ($value->sks_pilihan_diambil_kumulatif=="") {
        $sks_pilihan_kumulatif = 0;
      } else {
        $sks_pilihan_kumulatif = $value->sks_pilihan_diambil_kumulatif;
      }

      if ($value->jatah_sks=="") {
        $jatah_sks = 0;
      } else {
        $jatah_sks = $value->jatah_sks;
      }
      if ($value->sks_kumulatif=="") {
        $sks_total = 0;
      } else {
        $sks_total = $value->sks_kumulatif;
      }
      
      if ($value->id_stat_mhs=='N' && $sks_semester>0) {
        $id_stat_mhs = 'A';
      } elseif ($value->id_stat_mhs=='A' && $sks_semester==0) {
        $id_stat_mhs = 'N';
      } else {
        $id_stat_mhs = $value->id_stat_mhs;
      }
      if ($value->is_cuti!='') {
        $id_stat_mhs = 'C';
      }

      $data_update_akm[] = array(
        'ip' => $ip,
        'ipk' => $ipk,
        'id_stat_mhs' => $id_stat_mhs,
        'jatah_sks' => $jatah_sks,
        'sks_diambil' => $sks_semester,
        'sks_wajib' => $sks_wajib_kumulatif,
        'sks_pilihan' => $sks_pilihan_kumulatif,
        'total_sks' => $sks_total,
        'unik_id' => 0,
        'date_updated' => date('Y-m-d H:i:s')
      );
      $nim_mhs_periode[$value->mhs_nim][] = $value->sem_id;
      $data_id_update[] = $value->akm_id;
      
    }

    if (!empty($data_update_akm)) {
      dump($data_update_akm);
      $db2->updateMulti('akm',$data_update_akm,'akm_id',$data_id_update);
      echo $db2->getErrorMessage();
    }


    }

}
update_akm_coba('2010207059');


    break;
   default:
    //     if ($_SESSION['group_level']=='admin') {
    //    include "hasil_studi_mahasiswa_view.php";
    //    $btn_simpan = "Simpan KRS";
    // }
    if ($_SESSION['group_level']=='mahasiswa') {
        // print_r($_SESSION);
         $mhs_id = $_SESSION['username'];
         $sem_aktif = get_semester_aktif();
         $akm_id = get_semester_aktif_mhs($sem_aktif->id_semester, $mhs_id);
         $btn_simpan = "Ajukan KRS";
    $qq=$db->query("select m.nim,m.nama,j.nama_jur as jurusan,f.nama_resmi as fakultas from mahasiswa m
                  join jurusan j on j.kode_jur=m.jur_kode
                  join fakultas f on f.kode_fak=j.fak_kode where m.nim='$mhs_id'");
    foreach ($qq as $kk) {
          //include "rencana_studi_add.php";
        include "hasil_studi_mahasiswa_all_mhs.php";
      }
    }else{
        //include "hasil_studi_mahasiswa_view.php";
       include "hasil_studi_view_admin.php";
       $btn_simpan = "Simpan KRS";
    }

    // elseif ($_SESSION['level']=='5') {
    //   include "hasil_studi_mahasiswa_view_jur.php";
    // }
    // elseif ($_SESSION['level']=='6') {
    //   include "hasil_studi_mahasiswa_view_fak.php";
    // }
    break;
}

?>