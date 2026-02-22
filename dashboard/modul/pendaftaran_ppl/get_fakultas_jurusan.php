<?php
      session_start();
      include "../../inc/config.php";

      $nim = $_POST["nim"];
      $check = $db->check_exist('mahasiswa',array('nim'=>$nim));
    //  print_r($check);
      if ($check) { 
        $data = $db->query("select * from mahasiswa m inner join jurusan j on m.jur_kode=j.kode_jur
        inner join fakultas f on f.kode_fak=j.fak_kode ".get_akses_prodi()." and nim=?",array("nim" => $nim));
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
      } else{
 ?>
        <div class="form-group">
          <samp class="col-xs-offset-2" style="color: red; margin-left: 165px">Nim tidak terdaftar.</samp>
        </div>
 <?php
      }
 ?>
