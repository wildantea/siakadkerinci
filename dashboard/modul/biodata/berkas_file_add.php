<?php
session_start();
include "../../inc/config.php";
$id_label = $_POST['id_label'];

$label = $db->fetch_single_row("tb_data_file_label","id_file_label",$id_label);
$extensions = explode(",", $label->extension); // splits into array
// Quote each extension for SQL
$quoted_extensions = array_map(function($ext) {
    return "'" . addslashes(trim($ext)) . "'";
}, $extensions);
// Create a string like "'pdf','png'"
$in_clause = implode(",", $quoted_extensions);
$mime = $db->fetch_custom_single("select group_concat(mime) as mime from tb_data_file_extention where type in($in_clause)");
$ext = $mime->mime;
?>
 <div class="alert alert-danger error_data" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning"></span>
        </div>
      <form id="input_berkas_file" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/biodata/berkas_file_action.php?act=in">
                      
              <input type="hidden" name="id_file_label" value="<?=$_POST['id_label'];?>">
              </div><!-- /.form-group -->
              <div class="form-group">
                        <label for="label" class="control-label col-lg-2">Jenis File <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-10">
            <input type="text" class="form-control"  value="<?=$label->nama_label;?>" readonly>
            </div>
                      </div><!-- /.form-group -->
  <div class="form-group">
                        <label for="File" class="control-label col-lg-2">File </label>
                        <div class="col-lg-10">
              <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="input-group">
                              <div class="form-control uneditable-input span3" data-trigger="fileinput">
                                <i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span>
                              </div>
                              <span class="input-group-addon btn btn-default btn-file"><span class="fileinput-new">Select file</span> <span class="fileinput-exists">Change</span>
                                <input type="file" name="file_name" class="file-upload-data" required="" data-msg-required="Silakan Pilih File" accept="<?=$ext;?>" data-rule-filesize="<?=$label->size_file;?>" data-msg-filesize="Ukuran  File tidak boleh lebih dari <?=$label->max_size;?>">
                              </span>
                              <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                            </div>
                          </div>
                    </div>
                      </div><!-- /.form-group -->

              <div class="form-group">
                <div class="col-lg-12">
                  <div class="modal-footer"> 
                  <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> <?php echo $lang["cancel_button"];?></button>
                  <button type="submit" class="btn btn-primary save-data"><i class="fa fa-save"></i> <?php echo $lang["submit_button"];?></button>
                  </div>
                </div>
              </div><!-- /.form-group -->
 
      </form>
<script type="text/javascript">
    
    $(document).ready(function() {
        
          $.validator.addMethod('filesize', function(value, element, param) {
    // param = size (en bytes) 
    // element = element to validate (<input>)
    // value = value of the element (file name)
      return this.optional(element) || (element.files[0].size <= param) 
    });
    $("#input_berkas_file").validate({
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
            } else if (element.attr("accept") == "image/*") {
                element.parent().parent().parent().append(error);
            }
            else if (element.hasClass("tgl_picker_input")) {
               element.parent().parent().append(error);
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
                  $(".isi_warning").html(data.responseText);
                  $(".error_data").focus()
                  $(".error_data").fadeIn();
                },
                success: function(responseText) {
                  $("#loadnya").hide();
                  console.log(responseText);
                      $.each(responseText, function(index) {
                          console.log(responseText[index].status);
                          if (responseText[index].status=="die") {
                            $("#informasi").modal("show");
                          } else if(responseText[index].status=="error") {
                             $(".isi_warning").text(responseText[index].error_message);
                             $(".error_data").focus()
                             $(".error_data").fadeIn();
                          } else if(responseText[index].status=="good") {
                            $(".save-data").attr("disabled", "disabled");
                            $('#modal_data').modal('hide');
                            $(".error_data").hide();
                            $('.notif_title').html('Berhasil ditambahkan');
                            $(".notif_top").fadeIn(1000);
                            $(".notif_top").fadeOut(1000, function() {
                               // $('.th_<?=$id_label;?>').html(responseText[index].res);
                                 window.location='<?=base_url();?>dashboard/index.php/biodata';
                            });
                          }
                    });
                }

            });
        }
    });
});
</script>
