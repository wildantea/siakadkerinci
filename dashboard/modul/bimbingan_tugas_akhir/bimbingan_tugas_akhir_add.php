<?php
include "../../inc/config.php";
?>
<style type="text/css"> .datepicker {z-index: 1200 !important; } </style>
      <form id="input_bimbingan_tugas_akhir" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/bimbingan_tugas_akhir/bimbingan_tugas_akhir_action.php?act=in">
                      
              <div class="form-group">
                <label for="nim" class="control-label col-lg-2">NIM <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="nim" placeholder="nim" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="pembimbing_1" class="control-label col-lg-2">Pembimbing 1 <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="pembimbing_1" placeholder="pembimbing_1" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="pembimbing_2" class="control-label col-lg-2">Pembimbing 2 <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="pembimbing_2" placeholder="pembimbing_2" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
                      

              <div class="form-group">
                <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                <div class="col-lg-10">
                  <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-step-backward"></span> <?php echo $lang["cancel_button"];?></button>
                  <button type="submit" class="btn btn-primary"><span class="fa fa-floppy-o"></span> <?php echo $lang["submit_button"];?></button>
                </div>
              </div><!-- /.form-group -->

      </form>
<script type="text/javascript">
    $(document).ready(function() {
    
    
    
    $("#input_bimbingan_tugas_akhir").validate({
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
        
          pembimbing_1: {
          required: true,
          //minlength: 2
          },
        
          pembimbing_2: {
          required: true,
          //minlength: 2
          },
        
        },
         messages: {
            
          nim: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          pembimbing_1: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          pembimbing_2: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
        },
    
        submitHandler: function(form) {
            $("#loadnya").show();
            $(form).ajaxSubmit({
                type: "post",
                url: $(this).attr("action"),
                data: $("#input_bimbingan_tugas_akhir").serialize(),
                success: function(data) {
                    $('#modal_bimbingan_tugas_akhir').modal('hide');
                    $("#loadnya").hide();
                    if (data == "good") {
                        $(".notif_top").fadeIn(1000);
                        $(".notif_top").fadeOut(1000, function() {
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
