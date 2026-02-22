<?php
include "../../inc/config.php";
?>
<style type="text/css"> .datepicker {z-index: 1200 !important; } </style>
      <form id="input_setting_jalur_keluar" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/setting_jalur_keluar/setting_jalur_keluar_action.php?act=in">
          <div class="form-group">
            <label for="id_jns_keluar" class="control-label col-lg-2">Id Jenis Keluar <span style="color:#FF0000">*</span></label>
            <div class="col-lg-10">
              <input id="id_jns_keluar" type="text" name="id_jns_keluar" class="form-control" placeholder="Id Jenis Keluar" required>
            </div>
          </div>
          <div id="form_civitas"></div>            
          <div class="form-group">
            <label for="ket_keluar" class="control-label col-lg-2">Keterangan Keluar <span style="color:#FF0000">*</span></label>
            <div class="col-lg-10">
              <input type="text" name="ket_keluar" placeholder="ket_keluar" class="form-control" required>
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

    $("#input_setting_jalur_keluar").validate({
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
                data: $("#input_setting_jalur_keluar").serialize(),
                success: function(data) {
                    $('#modal_setting_jalur_keluar').modal('hide');
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

    $("#id_jns_keluar").on('input',function(){

      $.ajax({
      type : "post",
      url : "<?=base_admin();?>modul/setting_jalur_keluar/checkout.php",
      data : {id_jns_keluar:this.value},
      success : function(data) {
          $("#form_civitas").html(data);
          $("#form_civitas").trigger("chosen:updated");

      }
  });

});
</script>
