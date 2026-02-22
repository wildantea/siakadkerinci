<?php
session_start();
include "../../inc/config.php";
session_check_json();
$data_dosen = $db2->query("select * from view_dosen");
$pendaftaran_setting = $db2->fetchSingleRow('tb_data_pendaftaran_jenis_pengaturan','id_jenis_pendaftaran_setting',$_POST['id_jenis_pendaftaran_setting']);


?>



            <?php
if ($pendaftaran_setting->ada_judul=='Y') {
  ?>
            <div class="form-group">
              <label for="Judul " class="control-label col-lg-2">Judul</label>
              <div class="col-lg-10">
                <textarea class="form-control col-xs-12 editbox" rows="5" name="judul" required=""></textarea>
              </div>
          </div><!-- /.form-group -->
<?php
}

if ($pendaftaran_setting->ada_pembimbing=='Y') {
  for ($i=1; $i <= $pendaftaran_setting->jumlah_pembimbing; $i++) { 
?>
          <div class="form-group">
          <label for="Nama Pendaftaran" class="control-label col-lg-2">Pembimbing <?=$i;?> </label>
            <div class="col-lg-10">
              <select id="pembimbing_<?=$i;?>" name="pembimbing[]" data-placeholder="Pilih Dosen Pembimbing <?=$i;?>..." class="form-control pembimbing" tabindex="2">
              </select>
            </div>
          </div><!-- /.form-group -->
<?php
  }
}
?>
              <div class="form-group">
                <label for="Semester" class="control-label col-lg-2 kiri">Periode Akademik</label>
                <div class="col-lg-4">
                        <select id="id_semester" name="id_semester" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2" required="">
                        <?php 
                        loopingSemesterForm();
                        ?>
                        </select>
                </div>
            </div>

              <div class="form-group">
                 <label for="Semester" class="control-label col-lg-2">Tanggal Daftar</label>
                <div class="col-lg-3">
                   <div class="input-group date tgl_picker">
                      <span class="input-group-addon">
                          <span class="glyphicon glyphicon-calendar"></span>
                      </span>
                      <input type="text" autocomplete="off" class="form-control tgl_picker_input" name="date_created" value="<?=date('Y-m-d');?>"  />
                   </div>
                </div>
            </div>
            <div class="form-group">
                        <label for="Ruangan" class="control-label col-lg-2">Status</label>
                        <div class="col-lg-9">
        <select name="status" id="status_pendaftaran" data-placeholder="Pilih Status ..." class="form-control chzn-select" tabindex="2" required="" >
          <?php
          $array_status = array(1 => 'Sudah Acc',0 => 'Belum Acc',2 => 'Ditolak', 3 => 'Tidak Lulus',4 => 'Selesai/Lulus');
          foreach ($array_status as $status => $label) {
            if ($status==4) {
              echo "<option value='$status' selected>$label</option>";
            } else {
              echo "<option value='$status'>$label</option>";
            }
            
          }
          ?>
              </select>
                        </div>
            </div>
              <div class="form-group">
                <div class="col-lg-12">
                  <div class="modal-footer"> 
                  <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> <?php echo $lang["cancel_button"];?></button>
                  <button type="submit" class="btn btn-primary save-data"><i class="fa fa-save"></i> <?php echo $lang["submit_button"];?></button>
                  </div>
                </div>
              </div><!-- /.form-group -->
<script type="text/javascript">
$( ".pembimbing" ).select2({
    allowClear: true,
  width: "100%",
  ajax: {
    url: '<?=base_admin();?>modul/pendaftaran/mahasiswa/data_dosen.php',
    dataType: 'json'
    // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
  },
  formatInputTooShort: "Cari & Pilih Nama Dosen"
});
     $("textarea.editbox" ).ckeditor();

        $(".tgl_picker").datepicker({
        format: "yyyy-mm-dd",
        autoclose: true,
        todayHighlight: true
        }).on("change",function(){
          $(":input",this).valid();
        });

    $('.radio-change').change(function() {
        var id = $(this).data('id');
        //if type upload
        if (this.value=='1') {
          $("#show-upload-"+id).show();
          $("#show-link-"+id).hide();
          $("#link_upload_"+id).prop('required',false);
          $("#file_upload_"+id).prop('required',true);
        } else {
          $("#show-upload-"+id).hide();
          $("#show-link-"+id).show();
          $("#link_upload_"+id).prop('required',true);
          $("#file_upload_"+id).prop('required',false);
          $( ".remove-"+id).trigger( "click" );
        }
    });

    $(document).ready(function() {
      //trigger validation onchange
      $('select').on('change', function() {
          $(this).valid();
      });
      //hidden validate because we use chosen select
      $.validator.setDefaults({ ignore: ":hidden:not(select)" });
      //chosen select
      $(".chzn-select").chosen();
      $(".chzn-select-deselect").chosen({
          allow_single_deselect: true
      });

});
</script>