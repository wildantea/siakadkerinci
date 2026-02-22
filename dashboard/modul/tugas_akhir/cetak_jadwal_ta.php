<?php
session_start();
include "../../inc/config.php";
session_check();
?>
<style type="text/css"> .datepicker {z-index: 1200 !important; } </style>
  <form  class="form-horizontal foto_banyak" id="sem" target="__Blank" action="<?=base_admin().'modul/tugas_akhir/cetak_jadwalta.php'?>" method="post">
    <div class="form-group">
        <label for="penguji_1" class="control-label col-lg-2">No SK<span style="color:#FF0000">*</span></label>
        <div class="col-lg-10">
          <input type="text" name="no_sk" placeholder="No SK" class="form-control" required>
        </div>
    </div><!-- /.form-group -->
    <div class="form-group">
      <label for="Semester" class="control-label col-lg-2">Fakultas <span style="color:#FF0000">*</span></label>
      <div class="col-lg-10">
        <select id="fakultas_jadwal" name="fakultas_filter" data-placeholder="Pilih Fakultas ..." class="form-control chzn-select" tabindex="2" required>
          <option value=""></option>
           <?php
           foreach ($db->fetch_all("fakultas") as $isi) {
              echo "<option value='$isi->kode_fak'>$isi->nama_resmi</option>";
           } ?>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label for="Semester" class="control-label col-lg-2">Jurusan <span style="color:#FF0000">*</span></label>
      <div class="col-lg-10">
        <select id="jurusan_jadwal" name="jurusan_filter" data-placeholder="Pilih Jurusan ..." class="form-control chzn-select" tabindex="2" required>
          <option value=""></option>        
        </select>
      </div>
    </div>

    <div class="form-group">
      <label for="Priode" class="control-label col-lg-2">Priode <span style="color:#FF0000">*</span></label>
      <div class="col-lg-10">
        <select id="Priode" name="priode_filter" data-placeholder="Pilih Priode ..." class="form-control chzn-select" tabindex="2" required="">
          <option  value=""></option>
          <?php
          foreach ($db->query("select * from jadwal_muna jm join semester_ref sr on jm.priode_muna=sr.id_semester join jenis_semester j on sr.id_jns_semester=j.id_jns_semester order by sr.id_semester desc") as $isi) {
              echo "<option value='$isi->id_muna'>$isi->jns_semester $isi->tahun/".($isi->tahun+1)."</option>";
          }
          ?>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label for="cetak" class="control-label col-lg-2">&nbsp;</label>
      <div class="col-lg-4">
        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-step-backward"></span> <?php echo $lang["cancel_button"];?></button>
        <button type="submit" class="btn btn-primary"><span class="fa fa-print"></span> Cetak </button>
      </div>
    </div>
</form>
<script type="text/javascript">
  $("#fakultas_jadwal").change(function(){

        $.ajax({
        type : "post",
        url : "<?=base_admin();?>modul/tugas_akhir/get_jurusan_filter.php",
        data : {fakultas:this.value},
        success : function(data) {
            $("#jurusan_jadwal").html(data);
            $("#jurusan_jadwal").trigger("chosen:updated");

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
