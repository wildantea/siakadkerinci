<?php
include "../../inc/config.php";
$id_val = $_POST['id_data'];
$data_edit = $db->query("select *,ta.id_ta,ta.nim,ta.pembimbing_1,pem1.nama_dosen as pem_1,ta.pembimbing_2,pem2.nama_dosen as pem_2,mhs.nama,j.nama_jur from tugas_akhir ta
  inner join mahasiswa mhs on ta.nim=mhs.nim 
  inner join dosen pem1 on ta.pembimbing_1=pem1.id_dosen
  inner join jadwal_muna p on p.id_muna=ta.priode_muna
  inner join dosen pem2 on ta.pembimbing_2=pem2.id_dosen 
  inner join fakultas f on ta.kode_fak=f.kode_fak
  inner join jurusan j on ta.kode_jurusan=j.kode_jur
  where ta.id_ta='$id_val'");

foreach ($data_edit as $de) {
  # code...
}
?>
<style type="text/css"> .datepicker {z-index: 1200 !important; } </style>
  <form  class="form-horizontal foto_banyak" id="sem" target="__Blank" action="<?=base_admin().'modul/tugas_akhir/cetak_skpembimbing.php'?>" method="post">
          <div class="form-group">
              <label for="penguji_1" class="control-label col-lg-2">No SK<span style="color:#FF0000">*</span></label>
              <div class="col-lg-10">
                <input type="text" name="no_sk" placeholder="No SK" class="form-control" required>
              </div>
          </div><!-- /.form-group -->

          <div class="form-group">
              <label for="penguji_1" class="control-label col-lg-2">NIM<span style="color:#FF0000">*</span></label>
              <div class="col-lg-10">
                <input type="text" name="nim" placeholder="Judul Tugas Akhir" class="form-control" value="<?=$de->nim;?>" readonly>
              </div>
          </div><!-- /.form-group -->

          <div class="form-group">
              <label for="penguji_1" class="control-label col-lg-2">Nama<span style="color:#FF0000">*</span></label>
              <div class="col-lg-10">
                <input type="text" name="nama" placeholder="Judul Tugas Akhir" class="form-control" value="<?=$de->nama;?>" readonly>
              </div>
          </div><!-- /.form-group -->

          <div class="form-group">
              <label for="penguji_1" class="control-label col-lg-2">Fakultas<span style="color:#FF0000">*</span></label>
              <div class="col-lg-10">
                <input type="text" name="fakultas" placeholder="Judul Tugas Akhir" class="form-control" value="<?=$de->nama_resmi;?>" readonly>
              </div>
          </div><!-- /.form-group -->

          <div class="form-group">
              <label for="penguji_1" class="control-label col-lg-2">Jurusan<span style="color:#FF0000">*</span></label>
              <div class="col-lg-10">
                <input type="text" name="jurusan" placeholder="Judul Tugas Akhir" class="form-control" value="<?=$de->nama_jur;?>" readonly>
              </div>
          </div><!-- /.form-group -->                              

          <div class="form-group">
              <label for="penguji_1" class="control-label col-lg-2">Judul TA<span style="color:#FF0000">*</span></label>
              <div class="col-lg-10">
                <input type="text" name="judul_ta" placeholder="Judul Tugas Akhir" class="form-control" value="<?=$de->judul_ta;?>" readonly>
              </div>
          </div><!-- /.form-group -->
            
          <div class="form-group">
              <label for="penguji_1" class="control-label col-lg-2">Pembimbing 1<span style="color:#FF0000">*</span></label>
              <div class="col-lg-10">
                <select name="pembimbing_1" data-placeholder="Pilih Jenis Pembimbing 1..." class="form-control chzn-select" tabindex="2" required>
                  <option name="pembimbing_1" value=""></option>
                  <?php
                    foreach ($db->fetch_all('dosen') as $isi) {
                      if($de->pembimbing_1 == $isi->id_dosen){
                        echo "<option name='pembimbing_1' value='$isi->id_dosen' selected>$isi->nama_dosen</option>";
                      }else {
                        echo "<option name='pembimbing_1' value='$isi->id_dosen'>$isi->nama_dosen</option>";
                      }
                    }
                  ?>
                </select>
              </div>
          </div><!-- /.form-group -->

          <div class="form-group">
              <label for="penguji_2" class="control-label col-lg-2">Pembimbing 2<span style="color:#FF0000">*</span></label>
              <div class="col-lg-10">
                <select name="pembimbing_2" data-placeholder="Pilih Jenis Pembimbing 2 ..." class="form-control chzn-select" tabindex="2" required>
                  <option name="pembimbing_2" value=""></option>
                  <?php
                    foreach ($db->fetch_all('dosen') as $isi) {
                      if($de->pembimbing_2 == $isi->id_dosen){
                        echo "<option name='pembimbing_2' value='$isi->id_dosen' selected>$isi->nama_dosen</option>";
                      }else {
                        echo "<option name='pembimbing_2' value='$isi->id_dosen'>$isi->nama_dosen</option>";
                      }
                    }
                  ?>
                </select>
              </div>
          </div><!-- /.form-group -->

          <input id="id" type="hidden" value="<?=$data_edit->id_ta;?>" name="id" >

          <div class="form-group">
            <label for="tags" class="control-label col-lg-2">&nbsp;</label>
            <div class="col-lg-10">
              <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-step-backward"></span> <?php echo $lang["cancel_button"];?></button>
              <button type="submit" class="btn btn-primary"><span class="fa fa-print"></span>Print</button>
            </div>
          </div><!-- /.form-group -->
      </form>
<script type="text/javascript">
  $("#fakultas").change(function(){

        $.ajax({
        type : "post",
        url : "<?=base_admin();?>modul/tugas_akhir/get_jurusan_filter.php",
        data : {fakultas:this.value},
        success : function(data) {
            $("#jurusan").html(data);
            $("#jurusan").trigger("chosen:updated");

        }
    });

  });

  $("#val").change(function(){
        $.ajax({
        type : "post",
        url : "<?=base_admin();?>modul/tugas_akhir/tugas_akhir_action.php?act=validasi",
        data : {validasi:this.value},
        success : function(data) {
            if(data="good") {

            }
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