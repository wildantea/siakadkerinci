<?php
include "../../inc/config.php";
$data_edit = $db->fetch_single_row("jenis_keluar","id_jns_keluar",$_POST['id_data']);
?>
  <style type="text/css"> .datepicker {z-index: 1200 !important; } </style>
            <form id="edit_setting_jalur_keluar" method="post" class="form-horizontal" action="<?=base_admin();?>modul/setting_jalur_keluar/setting_jalur_keluar_action.php?act=up">
              <div class="form-group">
                <label for="id_jns_keluar" class="control-label col-lg-2">Id Jenis Keluar <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="id_jns_keluar" class="form-control" placeholder="Id Jenis Keluar" value="<?=$data_edit->id_jns_keluar;?>" required>
                </div>
              </div>     

              <div class="form-group">
                <label for="ket_keluar" class="control-label col-lg-2">ket_keluar <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="ket_keluar" value="<?=$data_edit->ket_keluar;?>" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
              <input type="hidden" name="id" value="<?=$data_edit->id_jns_keluar;?>">

              <div class="form-group">
                <div class="col-lg-12">
                  <div class="modal-footer"> <button type="submit" class="btn btn-primary"><?php echo $lang["submit_button"];?></button>
                  <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang["cancel_button"];?></button>
                  </div>
                </div>
              </div><!-- /.form-group -->

            </form>

<script type="text/javascript">
    $(document).ready(function() {
    
    
    
    $("#edit_setting_jalur_keluar").validate({
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
            
          ket_keluar: {
          required: true,
          //minlength: 2
          },
        
        },
         messages: {
            
          ket_keluar: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
        },
    
        submitHandler: function(form) {
            $("#loadnya").show();
            $(form).ajaxSubmit({
                type: "post",
                url: $(this).attr("action"),
                data: $("#edit_setting_jalur_keluar").serialize(),
                success: function(data) {
                    $('#modal_setting_jalur_keluar').modal('hide');
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
