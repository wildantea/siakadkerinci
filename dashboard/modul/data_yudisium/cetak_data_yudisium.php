<?php
include "../../inc/config.php";
?>
<style type="text/css"> .datepicker {z-index: 1200 !important; } </style>
  <form  class="form-horizontal foto_banyak" id="sem" target="__Blank" action="<?=base_admin().'modul/data_yudisium/cetak_datayudisium.php'?>" method="post">
    <div class="form-group">
      <label for="Semester" class="control-label col-lg-2">Fakultas</label>
      <div class="col-lg-10">
        <select id="fakultas_filter" name="fakultas_filter" data-placeholder="Pilih Angkatan ..." class="form-control chzn-select" tabindex="2">
           <option value="all">Semua</option>
           <?php
           foreach ($db->fetch_all("fakultas") as $isi) {
              echo "<option value='$isi->kode_fak'>$isi->nama_resmi</option>";
           } ?>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label for="Semester" class="control-label col-lg-2">Jurusan</label>
      <div class="col-lg-10">
        <select id="jurusan" name="jurusan" data-placeholder="Pilih Jurusan ..." class="form-control chzn-select" tabindex="2">
          <option value="all">Semua</option>
           <?php
           foreach ($db->fetch_all("jurusan") as $isi) {
              echo "<option value='$isi->kode_jur'>$isi->nama_jur</option>";
           } ?>          
        </select>
      </div>
    </div>
    <div class="form-group">
      <label for="jalur_keluar" class="control-label col-lg-2">Jalur Keluar</label>
      <div class="col-lg-10">
        <select id="keluar_filter" class="form-control chzn-select" name="keluar_filter" data-id="Pilih Jalur Keluar ...">
          <option value="all">Semua</option>
          <?php
            foreach ($db->fetch_all('jenis_keluar') as $isi) {
              echo "<option value='$isi->id_jns_keluar'>$isi->ket_keluar</option>";
            }
          ?>
        </select>
      </div>
    </div>
    <div class="form-group">
      <label for="jalur_skripsi" class="control-label col-lg-2">Jalur Skripsi</label>
      <div class="col-lg-10">
        <select id="skripsi_filter" class="fomr-control chzn-select" name="skripsi_filter" data-id="Pilih Jalur Skripsi">
          <option value="all">Semua</option>
          <?php
            foreach ($db->fetch_all('jenis_skripsi') as $isi) {
              echo "<option value='$isi->id_jenis_skripsi'>$isi->ket_jenis_skripsi</option>";
            }
          ?>
        </select>
      </div>
    </div>
    <div class="form-group">
      <div class="col-lg-12">
        <div class="modal-footer"> <button type="submit" class="btn btn-primary">Cetak</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div><!-- /.form-group -->
  </form>
<script type="text/javascript">
$(document).ready(function() {
      //chosen select
      $(".chzn-select").chosen();
      $(".chzn-select-deselect").chosen({
          allow_single_deselect: true
      });



      //trigger validation onchange
      $('select').on('change', function() {
          $(this).valid();
      });
      //hidden validate because we use chosen select
      $.validator.setDefaults({ ignore: ":hidden:not(select)" });
});
</script>
