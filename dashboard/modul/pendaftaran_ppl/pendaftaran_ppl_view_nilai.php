<?php
include "../../inc/config.php";
$data_edit = $db->fetch_single_row("kkn","id_kkn",$_POST['id_data']);
?>
  <style type="text/css"> .datepicker {z-index: 1200 !important; } </style>
            <form id="nilai_pendaftaran_kukerta" method="post" class="form-horizontal" action="<?=base_admin();?>modul/pendaftaran_kukerta/pendaftaran_kukerta_action.php?act=up_nilai">
                            
              <div class="form-group">
                <label for="Nim" class="control-label col-lg-2">Nilai <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                <?php
                  if($data_edit->nilai_kkn != NULL) {
                ?>
                  <input type="text" name="nilai" value="<?=$data_edit->nilai_kkn;?>" class="form-control" required>
                <?php
                  }else{
                ?>
                  <input type="text" name="nilai" class="form-control" required>
                <?php
                  }
                ?>  
                </div>
              </div><!-- /.form-group -->     
              
              <input type="hidden" name="id" value="<?=$data_edit->id_kkn;?>">

              <div class="form-group">
                <div class="col-lg-12">
                  <div class="modal-footer"> <button type="submit" class="btn btn-primary"><?php echo $lang["submit_button"];?> Nilai</button>
                  <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang["cancel_button"];?></button>
                  </div>
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
    
    $("#nilai_pendaftaran_kukerta").validate({
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
        
          id_priode: {
          required: true,
          //minlength: 2
          },
        
          id_lokasi: {
          required: true,
          //minlength: 2
          },
        
        },
         messages: {
            
          nim: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          id_priode: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          id_lokasi: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
        },
    
        submitHandler: function(form) {
            $("#loadnya").show();
            $(form).ajaxSubmit({
                type: "post",
                url: $(this).attr("action"),
                data: $("#nilai_pendaftaran_kukerta").serialize(),
                success: function(data) {
                    $('#modal_pendaftaran_kukerta_nilai').modal('hide');
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
