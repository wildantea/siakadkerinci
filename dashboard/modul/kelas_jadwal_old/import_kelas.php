<?php
session_start();
//import page
include "../../inc/config.php";
?>
        <div class="alert isi_informasi" style="display:none;margin-bottom: 0px;">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          
        </div>
                     <form id="input_import" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/kelas_jadwal/kelas_jadwal_action.php?act=import_kelas">
                     <div class="form-group">
                        <label for="nim" class="control-label col-lg-2">&nbsp;</label>
                        <div class="col-lg-10"> <a href="<?=base_url();?>upload/sample/template_import_kelas.xlsx" class="btn btn-success btn-flat"><i class="fa fa-cloud-download"></i> Download Contoh Template XLS</a></div>
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

                      <div class="form-group">
                        <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                        <div class="col-lg-10">
                         <a data-dismiss="modal" class="btn btn-default btn-flat"><i class="fa fa-close"></i> Tutup</a>
                          <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-cloud-upload"></i> Upload Data</button>
                        </div>
                      </div><!-- /.form-group -->
                    </form>
<script type="text/javascript">
  $(document).ready(function(){
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
                          $('.fileinput').fileinput('clear');
                          $(".isi_informasi").show();
                          $(".isi_informasi").html('');
                          $(".isi_informasi").html(data);
                          dataTable_jadwal.draw( false );
                          $('#loadnya').hide();
                     
                      }
                    });
            }

        });  

});
</script>