<?php
include "../../inc/config.php";
$data_edit = $db->fetch_single_row("cuti_mahasiswa","id_cuti",$_POST['id_data']);
?>
  <style type="text/css"> .datepicker {z-index: 1200 !important; } </style>
            <form id="edit_list_cuti_mahasiswa" method="post" class="form-horizontal" action="<?=base_admin();?>modul/list_cuti_mahasiswa/list_cuti_mahasiswa_action.php?act=up">
                            
              <div class="form-group">
                <label for="nim" class="control-label col-lg-2">Nim <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="nim" value="<?=$data_edit->nim;?>" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="jenis_keluar" class="control-label col-lg-2">Jenis Keluar <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="jenis_keluar" value="<?=$data_edit->jenis_keluar;?>" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="tgl_keluar" class="control-label col-lg-2">Tanggal Keluar <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input id="tgl1" type="text" name="tgl_keluar" value="<?=$data_edit->tgl_keluar;?>" class="form-control" required>
                </div>
              </div><!-- /.form-group -->

              <div class="form-group">
                <label for="tgl_keluar" class="control-label col-lg-2">Tanggal Berakhir <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input id="tgl2" type="text" name="tgl_berakhir" value="<?=$data_edit->tgl_berakhir;?>" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="file_sk" class="control-label col-lg-2">File <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="file_sk" value="<?=$data_edit->file_sk;?>" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
              
              <input type="hidden" name="id" value="<?=$data_edit->id_cuti;?>">

              <div class="form-group">
                <div class="col-lg-12">
                  <div class="modal-footer"> <button type="submit" class="btn btn-primary"><?php echo $lang["submit_button"];?></button>
                  <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang["cancel_button"];?></button>
                  </div>
                </div>
              </div><!-- /.form-group -->

            </form>

<script type="text/javascript">

    $("#tgl1").datepicker({
      format: "yyyy-mm-dd",
    });

    $("#tgl2").datepicker({
      format: "yyyy-mm-dd",
    });
    $(document).ready(function() {
    
    $("#edit_list_cuti_mahasiswa").validate({
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
            } else if (element.attr("type") == "checkbox") {
                element.parent().parent().append(error);
            } else if (element.attr("type") == "radio") {
                element.parent().parent().append(error);
            } else {
                error.insertAfter(element);
            }
        },
        
        rules: {
            
          nim: {
          required: true,
          //minlength: 2
          },
        
          jenis_keluar: {
          required: true,
          //minlength: 2
          },
        
          tgl_keluar: {
          required: true,
          //minlength: 2
          },

          tgl_berakhir: {
            required: true,
          //minlength: 2  
          }
        
          file_sk: {
          required: true,
          //minlength: 2
          },
        
        
        },
         messages: {
            
          nim: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          jenis_keluar: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          tgl_keluar: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          file_sk: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
        },
    
        submitHandler: function(form) {
            $("#loadnya").show();
            $(form).ajaxSubmit({
                type: "post",
                url: $(this).attr("action"),
                data: $("#edit_list_cuti_mahasiswa").serialize(),
                success: function(data) {
                    $('#modal_list_cuti_mahasiswa').modal('hide');
                    $("#loadnya").hide();
                    if (data == "good") {
                        $(".notif_top_up").fadeIn(1000);
                        $(".notif_top_up").fadeOut(1000, function() {
                               dataTable.draw(false);
                            });
                    } else if (data == "die") {
                        $("#informasi").modal("show");
                    } else {
                        $(".errorna").fadeIn();
                    }
                }
            });
        }
    });
});
</script>
