<?php
include "../../inc/config.php";
$data_edit = $db->fetch_single_row("kompre","id",$_POST['id_data']);
?>
  <style type="text/css"> .datepicker {z-index: 1200 !important; } </style>
            <form id="edit_pendaftaran_komprehensif" method="post" class="form-horizontal" action="<?=base_admin();?>modul/pendaftaran_komprehensif/pendaftaran_komprehensif_action.php?act=up">
                            
              <div class="form-group">
                <label for="nim" class="control-label col-lg-2">nim <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input id="nim" type="text" name="nim" value="<?=$data_edit->nim;?>" class="form-control" required readonly>
                </div>
              </div><!-- /.form-group -->

              <div id="form_civitas"></div>
              
              <input type="hidden" name="id" value="<?=$data_edit->id;?>">

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
    
    
    
    $("#edit_pendaftaran_komprehensif").validate({
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
        
        },
         messages: {
            
          nim: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
        },
    
        submitHandler: function(form) {
            $("#loadnya").show();
            $(form).ajaxSubmit({
                type: "post",
                url: $(this).attr("action"),
                data: $("#edit_pendaftaran_komprehensif").serialize(),
                success: function(data) {
                    $('#modal_pendaftaran_komprehensif').modal('hide');
                    $("#loadnya").hide();
                    if (data == "good") {
                        $(".notif_top_up").fadeIn(1000);
                        $(".notif_top_up").fadeOut(1000, function() {
                               location.reload();
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

$("#nim").on('input',function(){

      $.ajax({
      type : "post",
      url : "<?=base_admin();?>modul/pendaftaran_komprehensif/get_fakultas_jurusan.php",
      data : {nim:this.value},
      success : function(data) {
          $("#form_civitas").html(data);
          $("#form_civitas").trigger("chosen:updated");

      }
  });

});
</script>
