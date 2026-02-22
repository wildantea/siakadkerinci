<?php
include "../../inc/config.php";
?>
<style type="text/css"> .datepicker {z-index: 1200 !important; } </style>
      <form id="input_pendaftaran_beasiswa" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/pendaftaran_beasiswa/pendaftaran_beasiswa_action.php?act=in_jns">
                      
              <div class="form-group">
                <label for="NIM" class="control-label col-lg-2">Jenis Beasiswa <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="jenis_beasiswa" placeholder="Jenis Beasiswa" class="form-control" required>
                </div>
              </div><!-- /.form-group -->

              <div class="form-group">
                <label for="IPK" class="control-label col-lg-2">Keterangan <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <textarea class="form-control" name="keterangan" rows="10" placeholder="Keterangan Beasiswa"></textarea>
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
      
    $("#input_pendaftaran_beasiswa").validate({
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
            
          jenis_beasiswa: {
          required: true,
          //minlength: 2
          },
        
          keterangan: {
          required: true,
          //minlength: 2
          },
            
        },
         messages: {
            
          jenis_beasiswa: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          keterangan: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },        
        },
    
        submitHandler: function(form) {
            $("#loadnya").show();
            $(form).ajaxSubmit({
                type: "post",
                url: $(this).attr("action"),
                data: $("#input_pendaftaran_beasiswa").serialize(),
                success: function(data) {
                    $('#modal_pendaftaran_beasiswa').modal('hide');
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
