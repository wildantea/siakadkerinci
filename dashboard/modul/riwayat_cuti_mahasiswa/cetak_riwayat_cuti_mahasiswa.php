<?php
session_start();
include "../../inc/config.php";
session_check();
?>
<style type="text/css"> .datepicker {z-index: 1200 !important; } </style>
  <form  class="form-horizontal foto_banyak" id="sem" target="__Blank" action="<?=base_admin().'modul/riwayat_cuti_mahasiswa/cetak_riwayatcutimahasiswa.php'?>" method="post">
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
        <select id="jurusan_filter" name="jurusan_filter" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2">
          <option value="all">Semua</option>
           <?php
           foreach ($db->fetch_all("jurusan") as $isi) {
              echo "<option value='$isi->kode_jur'>$isi->nama_jur</option>";
           } ?>          
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
$("#fakultas_filter").change(function(){

      $.ajax({
      type : "post",
      url : "<?=base_admin();?>modul/data_yudisuim/get_jurusan_filter.php",
      data : {fakultas:this.value},
      success : function(data) {
          $("#jurusan_filter").html(data);
          $("#jurusan_filter").trigger("chosen:updated");

      }
  });

});

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
