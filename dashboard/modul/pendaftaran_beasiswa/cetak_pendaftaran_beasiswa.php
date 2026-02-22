<?php
session_start();
include "../../inc/config.php";
session_check();
?>
<style type="text/css"> .datepicker {z-index: 1200 !important; } </style>
  <form  class="form-horizontal foto_banyak" id="sem" target="__Blank" action="<?=base_admin().'modul/pendaftaran_beasiswa/cetak_pendaftaranbeasiswa.php'?>" method="post">
    <div class="form-group">
      <label for="Semester" class="control-label col-lg-2">Fakultas <span style="color:#FF0000">*</span></label>
      <div class="col-lg-10">
        <select id="fakultas_filter" name="fakultas_filter" data-placeholder="Pilih Fakultas ..." class="form-control chzn-select" tabindex="2" required>
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
        <select id="jurusan_filter" name="jurusan_filter" data-placeholder="Pilih Jurusan ..." class="form-control chzn-select" tabindex="2" required>        
        </select>
      </div>
    </div>
    <div class="form-group">
      <label for="Jenis Beasiswa" class="control-label col-lg-2">Jenis Beasiswa <span style="color:#FF0000">*</span></label>
      <div class="col-lg-10">
        <select id="id_beasiswajns" name="id_beasiswajns" data-placeholder="Pilih Jenis Beasiswa ..." class="form-control chzn-select" tabindex="2" required>
           <option value=""></option>
           <?php foreach ($db->fetch_all("beasiswa_jenis") as $isi) {
              echo "<option value='$isi->id_beasiswajns'>$isi->jenis_beasiswajns</option>";
           } ?>
          </select>
      </div>
    </div><!-- /.form-group -->
    <div class="form-group">
      <label for="Beasiswa" class="control-label col-lg-2">Beasiswa <span style="color:#FF0000">*</span></label>
      <div class="col-lg-10">
        <select id="id_beasiswa" name="id_beasiswa" data-placeholder="Pilih Beasiswa ..." class="form-control chzn-select" tabindex="2" required>            
        </select>
      </div>
    </div><!-- /.form-group -->
    <div class="form-group">
      <label for="Priode Beasiswa" class="control-label col-lg-2">Priode Beasiswa <span style="color:#FF0000">*</span></label>
      <div class="col-lg-10">
        <select id="priode_beasiswa" name="priode_beasiswa" id="sem" data-placeholder="Pilih Priode Beasiswa ..." class="form-control chzn-select" tabindex="2" required>
         <option value="all">Semua</option>
         <?php 
           $sem = $db->query("select * from semester_ref s join jenis_semester j 
            on s.id_jns_semester=j.id_jns_semester order by s.id_semester desc");
            foreach ($sem as $isi2) {
              if ($isi2->id_semester==$sem2) {
               echo "<option value='".$enc->enc($isi2->id_semester)."' selected>$isi2->jns_semester $isi2->tahun/".($isi2->tahun+1)."</option>";
              }else{
                echo "<option value='".$enc->enc($isi2->id_semester)."'>$isi2->jns_semester $isi2->tahun/".($isi2->tahun+1)."</option>";
              }
            
         } ?>
        </select>
      </div>
    </div><!-- /.form-group -->

    <div class="form-group">
      <label for="cetak" class="control-label col-lg-2">&nbsp;</label>
      <div class="col-lg-4">
        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-step-backward"></span> <?php echo $lang["cancel_button"];?></button>
        <button type="submit" class="btn btn-primary"><span class="fa fa-print"></span> Cetak </button>
      </div>
    </div>
</form>
<script type="text/javascript">
  $("#id_beasiswajns").change(function(){

        $.ajax({
        type : "post",
        url : "<?=base_admin();?>modul/pendaftaran_beasiswa/get_beasiswa_filter.php",
        data : {jenisbeasiswa:this.value},
        success : function(data) {
            $("#id_beasiswa").html(data);
            $("#id_beasiswa").trigger("chosen:updated");

        }
    });
  });

  $("#fakultas_filter").change(function(){

        $.ajax({
        type : "post",
        url : "<?=base_admin();?>modul/pendaftaran_beasiswa/get_jurusan_filter.php",
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
