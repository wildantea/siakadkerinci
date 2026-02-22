<?php
session_start();
$kelas_id = $_POST['kelas_id'];
//import page
include "../../inc/config.php";
$act = $_POST['act'];
if ($act=='reg') {
    $download_url = "download_template.php?kelas_id=$kelas_id";
} else {
    $download_url = "download_template_kom.php?kelas_id=$kelas_id";
}
?>
<div class="callout callout-info">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
<h4><i class="icon fa fa-warning"></i> Perhatian</h4>
<p>Pastikan file yang diupload format dan urutan kolomnya sesuai dengan Template excel yang di download. Klik tombol dibawah untuk download Template</p>
</div>
        <div class="alert isi_informasi" style="display:none;margin-bottom: 0px;">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          
        </div>
                     <form id="input_import" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/nilai_per_kelas/action_import.php?act=<?=$act;?>">
                     <div class="form-group">
                        <label for="nim" class="control-label col-lg-2">&nbsp;</label>

                        <div class="col-lg-10"> <a class="btn btn-success btn-flat" target="_blank" href="<?=base_admin();?>modul/nilai_per_kelas/<?=$download_url;?>"><i class="fa fa-cloud-download"></i> Download Template Excel Nilai</a></div>
                      </div><!-- /.form-group -->
                      <div class="form-group">
                        <label for="Upload Data Akm" class="control-label col-lg-2">Upload Data</label>
                        <div class="col-lg-10">
                          <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="input-group">
                              <div class="form-control uneditable-input span3" data-trigger="fileinput">
                                <i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span>
                              </div>
                              <span class="input-group-addon btn btn-default btn-file"><span class="fileinput-new">Select file</span> <span class="fileinput-exists">Change</span>
                                <input type="file" name="semester" required>
                              </span>
                              <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                            </div>
                          </div>
                        </div>
                      </div><!-- /.form-group -->

                      <input type="hidden" name="kelas_id" value="<?=$kelas_id;?>">

                      <div class="form-group">
                        <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                        <div class="col-lg-10">
                         <a class="btn btn-default btn-flat close-import"><i class="fa fa-close"></i> Tutup</a>
                          <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-cloud-upload"></i> Upload Data</button>
                        </div>
                      </div><!-- /.form-group -->
                    </form>
<script type="text/javascript">
  $(document).ready(function(){
    $(".close-import").click(function(){
        location.reload();
    });
   $("#input_import").validate({
        errorClass: 'help-block',
        errorElement: 'span',
        highlight: function(element, errorClass, validClass) {
            $(element).parents('.form-group').removeClass('has-success').addClass('has-error');
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).parents('.form-group').removeClass('has-error').addClass('has-success');
        },

            submitHandler: function(form) {
               $('#loadnya').show();
                   $(form).ajaxSubmit({
                          type: "post",
                          url: $(this).attr('action'),
                          data: $("#input_import").serialize(),
                       //  enctype:  'multipart/form-data'
                        success: function(data){
                          console.log(data);
                          $('#loadnya').hide();
                          $('.fileinput').fileinput('clear');
                          $(".isi_informasi").show();
                          $(".isi_informasi").html('');
                          $(".isi_informasi").html(data);
                         
                      }
                    });
            }

        });  

});
</script>