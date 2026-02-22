<?php
      session_start();
      include "../../inc/config.php";

      $nim = $_POST["nim"];
      $check = $db->check_exist('mahasiswa',array('nim'=>$nim));
      if ($check > 0) {
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
            <select name="kode_jur" class="form-control chzn-select" tabindex="2" readonly>
                <option name="kode_jur" value="<?=$dt->kode_jur;?>"><?=$dt->nama_jur;?></option>
            </select>
          </div>
        </div>
<?php
        }
        $data = $db->query("select ipk from akm where mhs_nim='$nim' order by ipk desc limit 1");
        foreach ($data as $ipk) {
?>

        <div class="form-group">
          <label for="IPK" class="control-label col-lg-2">IPK <span style="color:#FF0000">*</span></label>
          <div class="col-lg-10">
            <input type="text" name="ipk_beasiswamhs" placeholder="IPK" class="form-control" value="<?=$ipk->ipk?>" required readonly>
          </div>
        </div><!-- /.form-group -->
<?php
        }
      } else{
 ?>
        <div class="form-group">
          <samp class="col-xs-offset-2" style="color: red; margin-left: 165px">Nim tidak terdaftar atau belum melaksanakan tugas akhir!.</samp>
        </div>
 <?php
      }
 ?>
