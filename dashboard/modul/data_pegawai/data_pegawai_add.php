<?php
include "../../inc/config.php";
?>
<style type="text/css"> .datepicker {z-index: 1200 !important; } </style>
 <div class="alert alert-danger error_data" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning"></span>
        </div>
      <form id="input_data_pegawai" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/data_pegawai/data_pegawai_action.php?act=in">
                      
              <div class="form-group">
                <label for="NIP / NIK" class="control-label col-lg-2">NIP / NIK <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="nip" placeholder="NIP / NIK" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Nama Pegawai" class="control-label col-lg-2">Nama Pegawai <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="nama_pegawai" placeholder="Nama Pegawai" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Gelar Depan" class="control-label col-lg-2">Gelar Depan </label>
                <div class="col-lg-10">
                  <input type="text" name="gelar_depan" placeholder="Gelar Depan" class="form-control" >
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Gelar Belakang" class="control-label col-lg-2">Gelar Belakang </label>
                <div class="col-lg-10">
                  <input type="text" name="gelar_belakang" placeholder="Gelar Belakang" class="form-control" >
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="No HP" class="control-label col-lg-2">No HP <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="no_hp" placeholder="No HP" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Email" class="control-label col-lg-2">Email <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="email" placeholder="Email" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Alamat" class="control-label col-lg-2">Alamat <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="alamat" placeholder="Alamat" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
                <div class="form-group">
                  <label for="Jenis Kelamin" class="control-label col-lg-2">Jenis Kelamin <span style="color:#FF0000">*</span></label>
                  <div class="col-lg-10">
                    
                <div class="radio radio-success radio-inline">
                  <input type="radio" name="jk"  id="radio1" value="Laki-laki" required>
                    <label for="radio1" style="padding-left: 5px;">
                      Laki-laki
                    </label>
                </div>
                
                <div class="radio radio-success radio-inline">
                  <input type="radio" name="jk"  id="radio2" value="Perempuan" required>
                    <label for="radio2" style="padding-left: 5px;">
                      Perempuan
                    </label>
                </div>
                
                  </div>
                </div><!-- /.form-group -->
                <div class="form-group">
                        <label for="Foto" class="control-label col-lg-2">Foto </label>
                        <div class="col-lg-10">
                      <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                              <img data-src="holder.js/100%x100%" alt="...">
                            </div>
                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"></div>
                            <div>
                              <span class="btn btn-default btn-file"><span class="fileinput-new">Select image</span> <span class="fileinput-exists">Change</span>
                                <input type="file" name="foto" accept="image/*">
                              </span>
                              <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                            </div>
                          </div>
                        </div>
                      </div><!-- /.form-group -->

                      

              <div class="form-group">
                <div class="col-lg-12">
                  <div class="modal-footer"> <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> <?php echo $lang["submit_button"];?></button>
                  <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> <?php echo $lang["cancel_button"];?></button>
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
      
    $("#input_data_pegawai").validate({
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
            
          nip: {
          required: true,
          //minlength: 2
          },
        
          nama_pegawai: {
          required: true,
          //minlength: 2
          },
        
          no_hp: {
          required: true,
          //minlength: 2
          },
        
          email: {
          required: true,
          //minlength: 2
          },
        
          alamat: {
          required: true,
          //minlength: 2
          },
        
          jk: {
          required: true,
          //minlength: 2
          },
        
        },
         messages: {
            
          nip: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          nama_pegawai: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          no_hp: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          email: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          alamat: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          jk: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
        },
    
    submitHandler: function(form) {
            $("#loadnya").show();
            $(form).ajaxSubmit({
                url : $(this).attr("action"),
                dataType: "json",
                type : "post",
                error: function(data ) { 
                  $("#loadnya").hide();
                  console.log(data); 
                },
                success: function(responseText) {
                  $("#loadnya").hide();
                  console.log(responseText);
                      $.each(responseText, function(index) {
                          console.log(responseText[index].status);
                          if (responseText[index].status=="die") {
                            $("#informasi").modal("show");
                          } else if(responseText[index].status=="error") {
                             $(".isi_warning").text(responseText[index].error_message);
                             $(".error_data").focus()
                             $(".error_data").fadeIn();
                          } else if(responseText[index].status=="good") {
                            $('#modal_data_pegawai').modal('hide');
                            $(".error_data").hide();
                            $(".notif_top").fadeIn(1000);
                            $(".notif_top").fadeOut(1000, function() {
                                dtb_data_pegawai.draw();
                            });
                          } else {
                             console.log(responseText);
                             $(".isi_warning").text(responseText[index].error_message);
                             $(".error_data").focus()
                             $(".error_data").fadeIn();
                          }
                    });
                }

            });
        }
    });
});
</script>
