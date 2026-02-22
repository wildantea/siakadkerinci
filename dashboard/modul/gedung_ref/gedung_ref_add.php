<?php
include "../../inc/config.php";
?>
<style type="text/css"> .datepicker {z-index: 1200 !important; } </style>
      <form id="input_gedung_ref" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/gedung_ref/gedung_ref_action.php?act=in">
                      
              <div class="form-group">
                <label for="Kode Gedung" class="control-label col-lg-2">Kode Gedung</label>
                <div class="col-lg-10">
                  <input type="text" name="kode_gedung" placeholder="Kode Gedung" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Nama Gedung" class="control-label col-lg-2">Nama Gedung</label>
                <div class="col-lg-10">
                  <input type="text" name="nm_gedung" placeholder="Nama Gedung" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
          <div class="form-group">
              <label for="Aktif" class="control-label col-lg-2">Aktif</label>
              <div class="col-lg-10">
                <input name="is_aktif" class="make-switch" type="checkbox" checked>
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
             $.each($(".make-switch"), function () {
            $(this).bootstrapSwitch({
            onText: $(this).data("onText"),
            offText: $(this).data("offText"),
            onColor: $(this).data("onColor"),
            offColor: $(this).data("offColor"),
            size: $(this).data("size"),
            labelText: $(this).data("labelText")
            });
          });  
    
    
    $("#input_gedung_ref").validate({
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
            
          kode_gedung: {
          required: true,
          //minlength: 2
          },
        
          nm_gedung: {
          required: true,
          //minlength: 2
          },
        
          is_aktif: {
          required: true,
          //minlength: 2
          },
        
        },
         messages: {
            
          kode_gedung: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          nm_gedung: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          }
        
        },
    
        submitHandler: function(form) {
            $("#loadnya").show();
            $(form).ajaxSubmit({
                type: "post",
                url: $(this).attr("action"),
                data: $("#input_gedung_ref").serialize(),
                success: function(data) {
                    $('#modal_gedung_ref').modal('hide');
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
