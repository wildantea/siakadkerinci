<?php
      session_start();
      include "../../inc/config.php";

      $nim = $_POST["nim"];

      $data = $db->query("select * from mahasiswa m inner join jurusan j on m.jur_kode=j.kode_jur
      inner join fakultas f on f.kode_fak=j.fak_kode where nim=?",array("nim" => $nim));
      foreach ($data as $dt) {
?>
      <div class="form-group">
        <label for="nama" class="control-label col-lg-1">Nama </label>
        <div class="col-lg-11">
          <input type="text" name="nama" class="form-control" value="<?=$dt->nama;?>" readonly>
        </div>
      </div>
      <div class="form-group">
        <label for="kode_fakultas" class="control-label col-lg-1">Fakultas <span style="color:#FF0000">*</span></label>
        <div class="col-lg-11">
          <select name="kode_fak" class="form-control chzn-select" tabindex="2" readonly>
              <option name="kode_fak" value="<?=$dt->kode_fak;?>"><?=$dt->nama_resmi;?></option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label for="kode_jurusan" class="control-label col-lg-1">Jurusan <span style="color:#FF0000">*</span></label>
        <div class="col-lg-11">
          <select name="kode_jurusan" class="form-control chzn-select" tabindex="2" readonly>
              <option name="kode_jurusan" value="<?=$dt->kode_jur;?>"><?=$dt->nama_jur;?></option>
          </select>
        </div>
      </div>
<?php
      }
 ?>
