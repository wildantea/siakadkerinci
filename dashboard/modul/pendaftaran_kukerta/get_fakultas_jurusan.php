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
            $data = $db->query("select * from mahasiswa m inner join jurusan j on m.jur_kode=j.kode_jur
          inner join fakultas f on f.kode_fak=j.fak_kode where nim=?",array("nim" => $nim));
          foreach ($data as $dt) {
           ?>
              <div class="form-group">
                <label for="nama" class="control-label col-lg-2">Nama </label>
                <div class="col-lg-10">
                  <input type="text" name="nama" class="form-control" value="<?=$dt->nama;?>" readonly>
                </div>
              </div>
              <div class="form-group">
                <label for="kode_fakultas" class="control-label col-lg-2">Fakultas <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <select name="kode_fak" class="form-control chzn-select" tabindex="2" readonly>
                      <option name="kode_fak" value="<?=$dt->kode_fak;?>"><?=$dt->nama_resmi;?></option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label for="kode_jurusan" class="control-label col-lg-2">Jurusan <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <select name="kode_jurusan" class="form-control chzn-select" tabindex="2" readonly>
                      <option name="kode_jurusan" value="<?=$dt->kode_jur;?>"><?=$dt->nama_jur;?></option>
                  </select>
                </div>
              </div>
            <?php
          }
        if ($status_semester>=6 && $sudah_bayar=='1' && $total_sks>=80 && $sudah_ambil_kukerta) {
        
        }else{
    
    // if ($status_semester>=6 && $sudah_bayar=='1' && $total_sks>80) {
    ?>
     <div class="form-group">
                <label for="nama" class="control-label col-lg-2">Catatan </label>
                <div class="col-lg-10">
                   <h3 class="alert alert-warning">Mahasiswa ini tidak berhak mengikuti Kukerta, karena memiliki catatan sebagai berikut :</h3>
    <?php
    if ($status_semester<6) {
       ?>
      <div class="alert alert-danger" role="alert">
        <article>
          <strong>Mahasiswa ini sekarang semester <?= $status_semester ?>, sedangkan Kukerta bisa di ambil minimal semester 6 </strong>
        </article>
      </div> 
      <?php     
    }

    if ($sudah_bayar=='0') {
      ?>
      <div class="alert alert-danger" role="alert">
        <article>
          <strong>Belum melakukan pembayaran SPP pada semester ketika pengambilan Kukerta</strong>
        </article>
      </div>
      <?php    
    }

    if ($total_sks<80) {
      ?>
      <div class="alert alert-danger" role="alert">
        <article>
          <strong>Total SKS yang sudah mahasiswa ini ambil <?= $total_sks ?>, sedangkan syarat minimum mengambil Kukerta adalah 80 SKS </strong> 
        </article>
      </div>
      <?php    
    } 

    if (!$sudah_ambil_kukerta) {
      ?>
      <div class="alert alert-danger" role="alert">
        <article>
          <strong>Belum mengambil Mata Kuliah Kukerta </strong>
        </article>
      </div>
      <?php    
    } 
    ?>
    
                </div>
              </div>
   
  <?php
        }
      } else{
 ?>
        <div class="form-group">
          <samp class="col-xs-offset-2" style="color: red; margin-left: 165px">Nim tidak terdaftar.</samp>
        </div>
 <?php
      }
 ?>
