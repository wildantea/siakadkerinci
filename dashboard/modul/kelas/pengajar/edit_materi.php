<?php
session_start();
include "../../../inc/config.php";

$data_materi = $db2->fetchSingleRow("rps_materi_kuliah","id_materi",$_POST['id_materi']);

?>
<style type="text/css">
  #presensi > tbody > tr > td, .table > tfoot > tr > td {
    vertical-align: middle;
    padding:2px;
  }
 .help-block {
    color: #dd4b39;
}
.mt-checkbox {
  margin-bottom:0
}
#presensi.dataTable {
  border-color: #9e9595;
}
</style>
     <div class="alert alert-danger error_data" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning"></span>
        </div>
          
            <form id="input_kelas_jadwal" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/kelas/kelas_action.php?act=up_materi">
          <div class="form-group">
              
              <div class="col-lg-11">
              <label for="Catatan" class="control-label">Materi</label>
                <textarea class="form-control" rows="5" name="materi"><?=$data_materi->materi;?></textarea>
              </div>
          </div>

          <div class="form-group">
              
              <div class="col-lg-11">
              <label for="Catatan" class="control-label">Link Materi/Bukti Ajar </label>
                <input type="text" class="form-control" name="link_materi" value="<?=$data_materi->link_materi;?>">
              </div>
          </div>

          <input type="hidden" name="id_materi" value="<?=$_POST['id_materi'];?>">
      
              <div class="form-group">
                <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                <div class="col-lg-10">
             <a class="btn btn-default " data-dismiss="modal"><i class="fa fa-step-backward"></i> <?php echo $lang["cancel_button"];?></a>
            <button type="submit" class="btn btn-primary save-data"><i class="fa fa-save"></i> <?php echo $lang["submit_button"];?></button>
           
                </div>
              </div><!-- /.form-group -->

            </form>


<script type="text/javascript">
    $(document).ready(function() {
    $("#input_kelas_jadwal").validate({
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
            } else if (element.hasClass("tgl_picker_input")) {
               element.parent().parent().append(error);
            } else if (element.attr("accept") == "image/*") {
                element.parent().parent().parent().append(error);
            }
            else if (element.hasClass("file-upload-data")) {
               element.parent().parent().parent().append(error);
            }
             else if (element.attr("type") == "checkbox") {
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
                  $(".isi_warning").html(data.responseText);
                  $(".error_data").focus()
                  $(".error_data").fadeIn();
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
                            $(".save-data").attr("disabled", "disabled");
                            $(".error_data").hide();
                            $(".notif_top_up").fadeIn(1000);
                            $(".notif_top_up").fadeOut(1000, function() {
                                $('#modal_input_absen').modal('hide');
                                dtb_materi.draw();
                            });
                          }
                    });
                }

            });
        }
    });

});

</script>
