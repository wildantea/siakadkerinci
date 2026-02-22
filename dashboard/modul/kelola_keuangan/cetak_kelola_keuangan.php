<?php
include "../../inc/config.php";
?>
<style type="text/css"> .datepicker {z-index: 1200 !important; } </style>
  <form  class="form-horizontal foto_banyak" id="sem" target="__Blank" action="<?=base_admin().'modul/kelola_keuangan/cetak_kelolakeuangan.php'?>" method="post">
    <div class="form-group">
      <label for="Semester" class="control-label col-lg-2">Jurusan</label>
      <div class="col-lg-10">
        <select id="jurusan" name="jurusan" data-placeholder="Pilih Jurusan ..." class="form-control chzn-select" tabindex="2">
          <option value="all"></option>
           <?php
           foreach ($db->fetch_all("jurusan") as $isi) {
              echo "<option value='$isi->kode_jur'>$isi->nama_jur</option>";
           } ?>          
        </select>
      </div>
    </div>
    <div class="form-group">
      <label for="Semester" class="control-label col-lg-2">Tahun Ajaran</label>
      <div class="col-lg-10">
          <select name="semester" id="semester" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2" >
             <option value=""></option>
             <?php 
               $sem = $db->query("select * from semester_ref s join jenis_semester j 
                on s.id_jns_semester=j.id_jns_semester order by s.id_semester desc");
                foreach ($sem as $isi2) {
                  if ($isi2->id_semester==$sem2) {
                   echo "<option value='".$isi2->id_semester."' selected>$isi2->jns_semester $isi2->tahun/".($isi2->tahun+1)."</option>";
                  }else{
                    echo "<option value='".$isi2->id_semester."'>$isi2->jns_semester $isi2->tahun/".($isi2->tahun+1)."</option>";
                  }
                
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
