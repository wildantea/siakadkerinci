<?php
include "../../inc/config.php";
?>
<style type="text/css"> .datepicker {z-index: 1200 !important; } </style>
 <div class="alert alert-danger error_data" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning"></span>
        </div>
      <form id="input_ruang_kuliah" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/ruang_kuliah/ruang_kuliah_action.php?act=in">
        <div class="form-group">
                        <label for="Gedung " class="control-label col-lg-3">Gedung  <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-9">
            <select  id="gedung_id" name="gedung_id" data-placeholder="Pilih Gedung  ..." class="form-control chzn-select" tabindex="2" required>
               <option value=""></option>
               <?php foreach ($db->fetch_all("gedung_ref") as $isi) {
                  echo "<option value='$isi->gedung_id'>$isi->nm_gedung</option>";
               } ?>
              </select>
            </div>
        </div><!-- /.form-group -->
              <div class="form-group">
                <label for="Nama Ruang" class="control-label col-lg-3">Kode Ruang <span style="color:#FF0000">*</span></label>
                <div class="col-lg-9">
                  <input type="text" name="kode_ruang" placeholder="Kode Ruang" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              <div class="form-group">
                <label for="Nama Ruang" class="control-label col-lg-3">Nama Ruang <span style="color:#FF0000">*</span></label>
                <div class="col-lg-9">
                  <input type="text" name="nm_ruang" placeholder="Nama Ruang" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              <div class="form-group">
                  <label for="Gedung " class="control-label col-lg-3">Penggunaan Untuk Prodi<span style="color:#FF0000">*</span></label>
                              <div class="col-lg-9">
                  <select  id="kode_jur" name="kode_jur[]" data-placeholder="Bisa Multi Prodi  ..." class="form-control chzn-select" tabindex="2" required multiple="">
                     <option value=""></option>
                     <?php foreach ($db->fetch_all("view_prodi_jenjang") as $isi) {
                        echo "<option value='$isi->kode_jur'>$isi->jurusan</option>";
                     } ?>
                    </select>
                  </div>
              </div><!-- /.form-group -->

              <div class="form-group">
                <label for="Ket" class="control-label col-lg-3">Ket </label>
                <div class="col-lg-9">
                  <input type="text" name="ket" placeholder="Ket" class="form-control" >
                </div>
              </div><!-- /.form-group -->
                          <div class="form-group">
                <label for="Aktif" class="control-label col-lg-3">Aktif</label>
                <div class="col-lg-9">
                  <input name="is_aktif" class="make-switch" type="checkbox" checked>

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
      
    $("#input_ruang_kuliah").validate({
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
            
          gedung_id: {
          required: true,
          //minlength: 2
          },
        
          kode_jur: {
          required: true,
          //minlength: 2
          },
        
          nm_ruang: {
          required: true,
          //minlength: 2
          },
        
        },
         messages: {
            
          gedung_id: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          kode_jur: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          nm_ruang: {
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
                            $('#modal_ruang_kuliah').modal('hide');
                            $(".error_data").hide();
                            $(".notif_top").fadeIn(1000);
                            $(".notif_top").fadeOut(1000, function() {
                                dtb_ruang_kuliah.draw();
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
