<?php
include "../../inc/config.php";
?>
<style type="text/css"> .datepicker {z-index: 1200 !important; } </style>
 <div class="alert alert-danger error_data" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning"></span>
        </div>
      <form id="input_bimbingan_pa" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/bimbingan_pa/bimbingan_pa_action.php?act=in">

              <div class="form-group">
                <label for="Pertanyaan / Keluhan" class="control-label col-lg-3">Pertanyaan / Keluhan <span style="color:#FF0000">*</span></label>
                <div class="col-lg-9">
                  <textarea type="text" name="pertanyaan" rows="5" placeholder="Silakan isikan pertanyaan/pesan untuk dosen pembimbing anda" class="form-control" required></textarea>
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Jawaban Saran" class="control-label col-lg-3">Jawaban Saran <span style="color:#FF0000">*</span></label>
                <div class="col-lg-9">
                  <input type="text" name="jawaban" placeholder="Jawaban Saran" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Tanggal Konsultasi" class="control-label col-lg-3">Tanggal Konsultasi </label>
                <div class="col-lg-9">
                  <input type="text" name="tgl_tanya" placeholder="Tanggal Konsultasi" class="form-control" >
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="tgl_jawab" class="control-label col-lg-3">tgl_jawab </label>
                <div class="col-lg-9">
                  <input type="text" name="tgl_jawab" placeholder="tgl_jawab" class="form-control" >
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="id_semester" class="control-label col-lg-3">id_semester </label>
                <div class="col-lg-9">
                  <input type="text" name="id_semester" placeholder="id_semester" class="form-control" >
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="kategori_konsultasi" class="control-label col-lg-3">kategori_konsultasi </label>
                <div class="col-lg-9">
                  <input type="text" name="kategori_konsultasi" placeholder="kategori_konsultasi" class="form-control" >
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
    
    
    $("#input_bimbingan_pa").validate({
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
                            $('#modal_gedung_ref').modal('hide');
                            $(".error_data").hide();
                            $(".notif_top").fadeIn(1000);
                            $(".notif_top").fadeOut(1000, function() {
                                dtb_dosen.draw();
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
