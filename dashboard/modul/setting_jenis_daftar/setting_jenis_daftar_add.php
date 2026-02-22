<?php
include "../../inc/config.php";
$query = $db->query("select MAX(id_jenis_daftar) as new_id FROM jenis_daftar");
foreach ($query as $key) {
  $nilai = $key->new_id;
}

$id=$nilai + 1;
?>
<style type="text/css"> .datepicker {z-index: 1200 !important; } </style>
      <form id="input_setting_jenis_daftar" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/setting_jenis_daftar/setting_jenis_daftar_action.php?act=in">
                      
              <div class="form-group">
                <label for="id_jenis_daftar" class="control-label col-lg-2">Id Jenis Daftar <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="id_jenis_daftar" placeholder="id_jenis_daftar" class="form-control" value="<?=$id?>" readonly>
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="nm_jns_daftar" class="control-label col-lg-2">Keterangan Daftar <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="nm_jns_daftar" placeholder="nm_jns_daftar" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
                      

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
    
    
    
    $("#input_setting_jenis_daftar").validate({
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
            
          id_jenis_daftar: {
          required: true,
          //minlength: 2
          },
        
          nm_jns_daftar: {
          required: true,
          //minlength: 2
          },
        
        },
         messages: {
            
          id_jenis_daftar: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          nm_jns_daftar: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
        },
    
        submitHandler: function(form) {
            $("#loadnya").show();
            $(form).ajaxSubmit({
                type: "post",
                url: $(this).attr("action"),
                data: $("#input_setting_jenis_daftar").serialize(),
                success: function(data) {
                    $('#modal_setting_jenis_daftar').modal('hide');
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
