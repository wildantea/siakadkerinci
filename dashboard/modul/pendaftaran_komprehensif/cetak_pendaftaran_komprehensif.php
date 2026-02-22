<?php
session_start();
include "../../inc/config.php";
session_check();
?>
<style type="text/css"> .datepicker {z-index: 1200 !important; } </style>
  <form  class="form-horizontal foto_banyak" id="sem" target="__Blank" action="<?=base_admin().'modul/pendaftaran_komprehensif/cetak_pendaftarankomprehensif.php'?>" method="post">
    
    <?php
      if($_SESSION['level']=='1'){
    ?>
    <div class="form-group">
      <label for="Semester" class="control-label col-lg-2">Fakultas <span style="color:#FF0000">*</span></label>
      <div class="col-lg-10">
        <select id="fakultas_jadwal" name="fakultas_filter" data-placeholder="Pilih Fakultas ..." class="form-control chzn-select" tabindex="2">
           <option value=""></option>
           <?php
           echo "<option value='all'>Semua</option>";
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
      <label for="Semester" class="control-label col-lg-2">Priode <span style="color:#FF0000">*</span></label>
      <div class="col-lg-10">
        <select id="priode_filter" name="priode_filter" data-placeholder="Pilih Priode Kompre ..." class="form-control chzn-select" tabindex="2">
           <option value=""></option>
           <?php
           echo "<option value='all'>Semua</option>";
           foreach ($db->query("select * from jadwal_kompre jk join semester_ref sr on jk.priode_kompre=sr.id_semester join jenis_semester j on sr.id_jns_semester=j.id_jns_semester order by sr.id_semester desc") as $isi) {
              echo "<option value='$isi->id_kompre'>$isi->jns_semester $isi->tahun/".($isi->tahun+1)."</option>";
           } ?>
        </select>        
      </div>
    </div>     
    <?php
      }elseif($_SESSION['level']=='6'){
    ?>
    <div class="form-group">
      <label for="Semester" class="control-label col-lg-2">Jurusan</label>
      <div class="col-lg-10">
        <select id="jurusan_filter" name="jurusan_filter" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2">
          <option value=""></option>
           <?php
           $fakultas = $_SESSION['id_fak'];
           echo "<option value='all'>Semua</option>";
           foreach ($db->query("SELECT * FROM jurusan WHERE fak_kode='$fakultas'") as $isi) {
              echo "<option value='$isi->kode_jur'>$isi->nama_jur</option>";
           } ?>          
        </select>
      </div>
    </div>
    <div class="form-group">
      <label for="Semester" class="control-label col-lg-2">Priode</label>
      <div class="col-lg-10">
        <select id="priode_filter" name="priode_filter" data-placeholder="Pilih Priode Kompre ..." class="form-control chzn-select" tabindex="2">
           <option value="all">Semua</option>
           <?php
           foreach ($db->query("select * from jadwal_kompre jk join semester_ref sr on jk.priode_kompre=sr.id_semester join jenis_semester j on sr.id_jns_semester=j.id_jns_semester order by sr.id_semester desc") as $isi) {
              echo "<option value='$isi->id_kompre'>$isi->jns_semester $isi->tahun/".($isi->tahun+1)."</option>";
           } ?>
        </select>        
      </div>
    </div>      
    <?php
      }elseif($_SESSION['level']=='5') {
    ?>
    <div class="form-group">
      <label for="Semester" class="control-label col-lg-2">Priode</label>
      <div class="col-lg-10">
        <select id="priode_filter" name="priode_filter" data-placeholder="Pilih Priode Kompre ..." class="form-control chzn-select" tabindex="2">
           <option value="all">Semua</option>
           <?php
           foreach ($db->query("select * from jadwal_kompre jk join semester_ref sr on jk.priode_kompre=sr.id_semester join jenis_semester j on sr.id_jns_semester=j.id_jns_semester order by sr.id_semester desc") as $isi) {
              echo "<option value='$isi->id_kompre'>$isi->jns_semester $isi->tahun/".($isi->tahun+1)."</option>";
           } ?>
        </select>        
      </div>
    </div> 
      <input type="hidden" name="jurusan_filter" id="jurusan_filter" value="<?=$_SESSION['id_jur']?>">
    <?php
      }
    ?>

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
