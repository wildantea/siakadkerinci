
        <div class="box box-solid box-primary">
          <div class="box-header">
            <h3 class="box-title">Silakan Upload Rencana Materi Perkuliahan</h3>
          </div>
          <div class="box-body">
           <div class="alert alert-danger error_data_materi" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning_rps"></span>
        </div>
       <a href="<?=base_url();?>upload/sample/template_materi.xlsx" class="btn btn-success btn-flat"><i class="fa fa-cloud-download"></i> Download Contoh Template XLS</a>
            <form id="upload_materi" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/kelas/rps/action.php?act=import_materi">
              <div class="form-group">
                        <label for="File" class="control-label col-lg-2">File <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-10">
                          <input type="hidden" name="id_matkul" value="<?=$kelas->id_matkul;?>">
                          <input type="hidden" name="id_kelas" value="<?=$kelas->kelas_id;?>">
                          <input type="hidden" name="semester_id" value="<?=$kelas->sem_id;?>">
                          <input type="hidden" name="kode_jur" value="<?=$kelas->kode_jur;?>">
                          <input type="hidden" name="nip" value="<?=$_SESSION['username'];?>">
              <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="input-group">
                              <div class="form-control uneditable-input span3" data-trigger="fileinput">
                                <i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span>
                              </div>
                              <span class="input-group-addon btn btn-default btn-file"><span class="fileinput-new">Select file</span> <span class="fileinput-exists">Change</span>
                                <input type="file" name="semester" required class="file-upload-data"  accept="application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" required>
                              </span>
                              <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                            </div>
                            <span class="help-block">File diijinkan xlsx</span>
                          </div>
                    </div>
                      </div><!-- /.form-group -->

                      
              <div class="form-group">
                <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                <div class="col-lg-10">
            <button type="submit" class="btn btn-primary save-datas"><i class="fa fa-save"></i> <?php echo $lang["submit_button"];?></button>
           
                </div>
              </div><!-- /.form-group -->

            </form>

          </div>
        </div>

<script type="text/javascript">
    $(document).ready(function() {
    
    $.validator.addMethod('filesize', function(value, element, param) {
    // param = size (en bytes) 
    // element = element to validate (<input>)
    // value = value of the element (file name)
      return this.optional(element) || (element.files[0].size <= param) 
    });
    
    $("#upload_materi").validate({
        errorClass: "help-block",
        errorElement: "span",
        highlight: function(element, errorClass, validClass) {
            $(element).parents(".form-group").removeClass(
                "has-success").addClass("has-error");
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).parents(".form-group").removeClass(
                "has-error").addClass("has-success");
        },
        errorPlacement: function(error, element) {
            if (element.hasClass("chzn-select")) {
                var id = element.attr("id");
                error.insertAfter("#" + id + "_chosen");
            } else if (element.hasClass("tgl_picker_input")) {
               element.parent().parent().append(error);
            } else if (element.attr("accept") == "image/*") {
                element.parent().parent().parent().append(error);
            }
            else if (element.hasClass("file-upload-data")) {
               element.parent().parent().parent().append(error);
            }
             else if (element.attr("type") == "checkbox") {
                element.parent().parent().append(error);
            } else if (element.attr("type") == "radio") {
                element.parent().parent().append(error);
            } else {
                error.insertAfter(element);
            }
        },

    
        submitHandler: function(form) {
            $("#loadnya").show();
            $(form).ajaxSubmit({
                url : $(this).attr("action"),
                dataType: "json",
                type : "post",
                error: function(data ) { 
                  $("#loadnya").hide();
                  console.log(data); 
                  $(".isi_warning_rps").html(data.responseText);
                  $(".error_data_materi").focus()
                  $(".error_data_materi").fadeIn();
                },
                success: function(responseText) {
                  $("#loadnya").hide();
                  console.log(responseText);
                      $.each(responseText, function(index) {
                          console.log(responseText[index].status);
                          if (responseText[index].status=="die") {
                            $("#informasi").modal("show");
                          } else if(responseText[index].status=="error") {
                             $(".isi_warning_rps").text(responseText[index].error_message);
                             $(".error_data_materi").focus()
                             $(".error_data_materi").fadeIn();
                          } else if(responseText[index].status=="good") {
                            $(".save-data").attr("disabled", "disabled");
                            $(".error_data_materi").hide();
                            $(".notif_top").fadeIn(1000);
                            $(".notif_top").fadeOut(1000, function() {
                                location.reload();
                                $('.action-title-rps').html('');
                                $("#isi_edit_rps").html('');
                                $("#isi_edit_rps").slideUp();
                                $('.button-top').show();
                                $('.action-title').html('');
                                $("#isi_tambah_pertemuan").html('');
                                $("#isi_tambah_pertemuan").slideUp();
                            });
                          }
                    });
                }

            });
        }
    });
});
</script>
