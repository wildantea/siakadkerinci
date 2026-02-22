<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  case "generate_cuti":
    $periode_cuti = $_POST['periode_cuti']; 
    $q = $db->query("SELECT *
    FROM `v_mhs_aktif`
    WHERE `mulai_smt` >= '20141'
    and nim not in (SELECT nim
    FROM `v_bayar_mhs`
    WHERE `periode` = '$periode_cuti')
    and nim not like '%.%'
    and nim in (select nim from keu_tagihan_mahasiswa) 
    and nim not in (select nim from affirmasi_krs where periode='$periode_cuti') order by mulai_smt asc, nim asc ");
    $ket_cuti = 'Telat bayar SPP '.$periode_cuti;
    foreach ($q as $k) { 
      $data = array('nim' => $k->nim, 
                      'status_acc' => 'approved',
                      'alasan_cuti' => $ket_cuti,
                      'no_surat' => '-',
                      'date_created' => date("Y-m-d H:i:s"),
                      'date_approved' => date("Y-m-d H:i:s"),
                      'is_generate' => '1');
      $qc = $db->query("select nim from tb_data_cuti_mahasiswa where nim=? and alasan_cuti=? ",array($k->nim,$ket_cuti));
      if ($qc->rowCount()==0) {   
        $db->insert("tb_data_cuti_mahasiswa",$data); 
        $id_cuti = $db->last_insert_id(); 
        $data2   = array('id_cuti' => $id_cuti , 
                         'periode' => $periode_cuti,
                         'is_generate' => '1');
        $db->insert("tb_data_cuti_mahasiswa_periode",$data2); 
/*        $qq = $db->query("select * from akm where mhs_nim='$k->nim' order by sem_id desc limit 1 "); 
        foreach ($qq as $kk) { 
          $data_akm = (array)$kk;
              unset($data_akm['akm_id']);
              $data_akm['sem_id'] = $periode_cuti;
              $data_akm['id_stat_mhs'] = 'C';
              $data_akm['ip'] = '0.00';
              $data_akm['jatah_sks'] = '0';
              $data_akm['sks_diambil'] = '0';
              $data_akm['is_generate'] = '1'; 
              $db->insert("akm",$data_akm);     
        }*/
      }      
    }
  break;

  case "in":
    
  
  $periode_implode = implode(",", $_POST['periode']);
  //check periode ajuan if sudah ada
  $check_exist = $db->fetch_custom_single('select nim,periode from tb_data_cuti_mahasiswa inner join tb_data_cuti_mahasiswa_periode using(id_cuti) where nim=? and status_acc!="rejected" and periode in('.$periode_implode.')',array('nim' => $_POST['nim']));
  if ($check_exist) {
    action_response('Mahasiswa ini sudah ada pengajuan Cuti pada Periode yang dipilih');
  }
  $data = array(
      "nim" => $_POST['nim'],
      "status_acc" => $_POST["status_acc"],
      "alasan_cuti" => $_POST["alasan_cuti"],
      "no_surat" => $_POST["no_surat"],
      "date_created" => date('Y-m-d')
  );
  if ($_POST['status_acc']!='waiting') {
    $data['date_approved'] = $_POST["date_approved"];
  }
  if ($_POST['status_acc']=='rejected') {
    $data['keterangan'] = $_POST["keterangan"];
  }
    $in = $db->insert("tb_data_cuti_mahasiswa",$data);
    $last_id = $db->last_insert_id();
    foreach ($_POST['periode'] as $periode) {
          $data_periode = array(
            'id_cuti' => $last_id,
            'periode' => $periode
          );
          $db->insert('tb_data_cuti_mahasiswa_periode',$data_periode);
    }

    
    
    action_response($db->getErrorMessage());
    break;
  case "delete":
    
    $db->delete("tb_data_cuti_mahasiswa","id_cuti",$_GET["id"]);
    action_response($db->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("tb_data_cuti_mahasiswa","id_cuti",$id);
         }
    }
    action_response($db->getErrorMessage());
    break;
  case "up":
    $periode_implode = implode(",", $_POST['periode']);
    //check periode ajuan if sudah ada
    $check_exist = $db->fetch_custom_single('select nim,periode from tb_data_cuti_mahasiswa inner join tb_data_cuti_mahasiswa_periode using(id_cuti) where nim=? and tb_data_cuti_mahasiswa.id_cuti!=? and periode in('.$periode_implode.')',array('nim' => $_POST['nim'],'id_cuti' => $_POST['id']));
    if ($check_exist) {
      action_response('Mahasiswa ini sudah ada pengajuan Cuti pada Periode yang dipilih');
    }
    $data = array(
        "status_acc" => $_POST["status_acc"],
        "alasan_cuti" => $_POST["alasan_cuti"],
        "no_surat" => $_POST["no_surat"],
        "date_updated" => date('Y-m-d')
    );

    if ($_POST['status_acc']!='waiting') {
      $data['date_approved'] = $_POST["date_approved"];
    }
    if ($_POST['status_acc']=='rejected') {
    $data['keterangan'] = $_POST["keterangan"];
  }
    $in = $db->update("tb_data_cuti_mahasiswa",$data,'id_cuti',$_POST['id']);
    $db->query('delete from tb_data_cuti_mahasiswa_periode where id_cuti=?',array('id_cuti' => $_POST['id']));
    foreach ($_POST['periode'] as $periode) {
          $data_periode = array(
            'id_cuti' => $_POST['id'],
            'periode' => $periode
          );
      $db->insert('tb_data_cuti_mahasiswa_periode',$data_periode);
    }

    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>