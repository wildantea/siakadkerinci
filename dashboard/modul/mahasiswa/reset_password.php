<?php
include "../../inc/config.php";
?>
<style type="text/css"> .datepicker {z-index: 1200 !important; } </style>
      <form id="input_reset_pass" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/manajemen_pengguna/manajemen_pengguna_action.php?act=reset">
                      
     <div class="form-group">
                        <label for="Password Baru" class="control-label col-lg-3">Password Baru</label>
                        <div class="col-lg-9">
                          <input type="password" id="password_baru" name="password_baru"  class="form-control" required=""> 
                        </div>
                      </div><!-- /.form-group -->
                       <div class="form-group">
                        <label for="Password Baru" class="control-label col-lg-3">Ulangi Password Baru</label>
                        <div class="col-lg-9">
                          <input type="password" id="password_confirm" name="password_confirm" class="form-control" required=""> 
                        </div>
                      </div><!-- /.form-group -->
<?php
$id_mhs = $db->fetch_custom_single("select sys_users.id from sys_users inner join mahasiswa on sys_users.username=mahasiswa.nim where mahasiswa.mhs_id=?",array('id' => $_POST['id_data']));

?>
                      <input type="hidden" name="id" value="<?=$id_mhs->id?>">
                      

              <div class="form-group">
                <div class="col-lg-12">
                  <div class="modal-footer"> <button type="submit" class="btn btn-primary">Reset Password</button>
                  <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang["cancel_button"];?></button>
                  </div>
                </div>
              </div><!-- /.form-group -->

      </form>
<script type="text/javascript">
    $(document).ready(function() {
    
    
    
    $("#input_reset_pass").validate({
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
            
          password_confirm: {
          required: true,
          equalTo : '#password_baru',
          //minlength: 2
          },
        
        },
         messages: {

          password_confirm: {
          equalTo: "Password Harus Sama",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
        },
    
        submitHandler: function(form) {
            $("#loadnya").show();
            $(form).ajaxSubmit({
                type: "post",
                url: $(this).attr("action"),
                data: $("#input_reset_pass").serialize(),
                success: function(data) {
                    $('#modal_reset_pass').modal('hide');
                    $("#loadnya").hide();
                    if (data == "good") {
                        $(".error_data_delete").fadeIn(1000);
                        $(".isi_warning_delete").html('Password Berhasil di Reset');
                        $(".error_data_delete").fadeOut(2000);
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
