<?php
include "../../inc/config.php";
$data_nilai = $db->fetch_single_row("kompre","id",$_POST['id_data']);
?>
<style type="text/css"> .datepicker {z-index: 1200 !important; } </style>
      <form id="nilai_pendaftaran_komprehensif" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/pendaftaran_komprehensif/pendaftaran_komprehensif_action.php?act=nilai">
                      
              <div class="form-group">
                <label for="nim" class="control-label col-lg-2">Nilai <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <?php
                    if($data_nilai->nilai_kompre != NULL) {
                      echo "<input type='text' class='form-control' name='nilai' value='$data_nilai->nilai_kompre'>";
                    }else {
                      echo "<input type='text' class='form-control' name='nilai' placeholder='Nilai Komprehensif'>";
                    }
                  ?>
                </div>
              </div><!-- /.form-group -->
              
              <input type="hidden" name="id" value="<?=$data_nilai->id;?>">

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
    
    
    
    $("#nilai_pendaftaran_komprehensif").validate({
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
            
          nilai: {
          required: true,
          //minlength: 2
          },
        
        },
         messages: {
            
          nilai: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
        },
    
        submitHandler: function(form) {
            $("#loadnya").show();
            $(form).ajaxSubmit({
                type: "post",
                url: $(this).attr("action"),
                data: $("#nilai_pendaftaran_komprehensif").serialize(),
                success: function(data) {
                    $('#modal_pendaftaran_komprehensif_nilai').modal('hide');
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
