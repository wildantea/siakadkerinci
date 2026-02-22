<?php
      session_start();
      include "../../inc/config.php";

      $nim = $_POST["nim"];
      $check = $db->check_exist('mahasiswa',array('nim'=>$nim));
      if ($check > 0) {
        $status_semester = cek_status_semester($nim);
        $total_sks       = get_total_sks($nim);
        $sudah_ambil_kukerta = cek_sudah_ambil_mk_kukerta($nim);
        $data_mhsx = $db->query("select `m`.`nim` AS `nim`,`m`.`nama` AS `nama`,`m`.`mulai_smt` AS `mulai_smt`,`m`.`jk` AS `jk`,`jd`.`id_jenis_daftar` AS `id_jenis_daftar`,`jd`.`nm_jns_daftar` AS `nm_jns_daftar`,ifnull(`jk`.`ket_keluar`,'Aktif') AS `jenis_keluar`,`view_semester`.`angkatan` AS `angkatan`,`m`.`mhs_id` AS `mhs_id`,`vpj`.`kode_jur` AS `jur_kode`,`view_dosen`.`nip` AS `nip_dosen_pa`,`view_dosen`.`dosen` AS `dosen_pa`,`vpj`.`jurusan` AS `jurusan` from (((((`mahasiswa` `m` join `view_prodi_jenjang` `vpj` on((`m`.`jur_kode` = `vpj`.`kode_jur`))) left join `jenis_keluar` `jk` on((`m`.`id_jns_keluar` = `jk`.`id_jns_keluar`))) left join `view_dosen` on((`m`.`dosen_pemb` = `view_dosen`.`nip`))) join `view_semester` on((`m`.`mulai_smt` = `view_semester`.`id_semester`))) join `jenis_daftar` `jd` on((`m`.`id_jns_daftar` = `jd`.`id_jenis_daftar`))) where nim='$nim' "); 
          foreach($data_mhsx as $data_mhs){
                 $check_if_bayar = $db->fetch_custom_single("select fungsi_cek_pembayaran_periode(".get_sem_aktif_kkn().",".$data_mhs->jur_kode.",".$data_mhs->nim.") as is_bayar");
                $affirmasi = afirmasi_krs($data_mhs->nim,get_sem_aktif_kkn());
                 $qc = $db->query("SELECT * FROM keu_tagihan_mahasiswa m 
                  WHERE m.nim='$data_mhs->nim'
                  AND m.periode='".get_sem_aktif_kkn()."' "); 
                  $ada_tagihan = $qc->rowCount();
                 //echo "$check_if_bayar->is_bayar , $ada_tagihan , ".var_dump($affirmasi);
               
                if (($check_if_bayar->is_bayar=='0' || $ada_tagihan==0 ) && (!$affirmasi) ){
                   $sudah_bayar = 0;
                }else{
                   $sudah_bayar = 1;
                }
          }
         // echo " = $sudah_bayar";
      //  echo "$status_semester";
       // if($sks <= $total_sks){
        // $validasi = array(
        //             "semester"
        //  );
        if (($status_semester>=6 && $sudah_bayar=='1' && $total_sks>=80 && $sudah_ambil_kukerta) || $_SESSION['level']=='1') {
           echo "1";
        }else{
           echo "0";
        }
      }

 ?>
