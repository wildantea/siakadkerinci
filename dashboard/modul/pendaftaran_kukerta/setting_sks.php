<?php
//session_start();
include "../../inc/config.php";
$data_edit = $db->fetch_single_row("batas_sks","ket","kukerta");
//print_r($_SESSION);
?>
<style type="text/css"> .datepicker {z-index: 1200 !important; } </style>
<form id="input_pendaftaran_kukerta" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/pendaftaran_kukerta/pendaftaran_kukerta_action.php?act=up_sks">
                
        
        <div class="form-group">
          <label for="Lokasi" class="control-label col-lg-3">Syarat SKS Minimal Diambil <span style="color:#FF0000">*</span></label>
          <div class="col-lg-2">
           <input type="text" class="form-control" name="jlm_sks" value="<?=$data_edit->jlm_sks;?>">
          </div>
        </div><!-- /.form-group -->                

      <div class="form-group">
        <label for="tags" class="control-label col-lg-2">&nbsp;</label>
        <div class="col-lg-10">
          <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-step-backward"></span> <?php echo $lang["cancel_button"];?></button>
          <button type="submit" id="btn_simpan" class="btn btn-primary"><span class="fa fa-floppy-o"></span> <?php echo $lang["submit_button"];?></button>
        </div>
      </div><!-- /.form-group -->


</form>
<script type="text/javascript">


    $(document).ready(function() {
    
    
    $("#input_pendaftaran_kukerta").validate({
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
        submitHandler: function(form) {
            $("#loadnya").show();
            $(form).ajaxSubmit({
                type: "post",
                url: $(this).attr("action"),
                data: $("#input_pendaftaran_kukerta").serialize(),
                success: function(data) {
                    $('#modal_pendaftaran_kukerta').modal('hide');
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
