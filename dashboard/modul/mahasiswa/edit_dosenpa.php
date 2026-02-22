<?php
session_start();
include "../../inc/config.php";
$data_edit = $db->fetch_single_row("view_simple_mhs_data","mhs_id",$_POST['id_data']);

?>
   <div class="alert alert-danger error_data" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning"></span>
        </div>
            <form id="edit_bim_akademis" method="post" class="form-horizontal" action="<?=base_admin();?>modul/mahasiswa/mahasiswa_action.php?act=up_dosen">
                            
              <div class="form-group">
                <label for="Nim" class="control-label col-lg-3">Nim </label>
                <div class="col-lg-8">
                  <input type="text" name="nim" readonly="" value="<?=$data_edit->nim;?>" class="form-control" >
                </div>
              </div><!-- /.form-group -->

    <div class="form-group">
          <label for="nama" class="control-label col-lg-3">&nbsp;</label>
          <div class="col-lg-4">
            <input type="text" id="nama" name="nama" class="form-control" readonly value="<?=$data_edit->nama;?>">
          </div>
          <div class="col-lg-4">
            <input type="text" id="jurusan_field" name="jurusan_field" class="form-control" readonly value="<?=$data_edit->nama_jurusan;?>">
            <input type="hidden" name="kode_jur" id="kode_jur">
          </div>
        </div>

              <div class="form-group">
                        <label for="Pembimbing Akademik" class="control-label col-lg-3">Pembimbing Akademik <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-8">
              <select  id="dosen_pemb" name="dosen_pemb" data-placeholder="Pilih Pembimbing Akademik..." class="form-control chzn-select" tabindex="2" required>
               <option value=""></option>
               <?php foreach ($db->query("select * from view_dosen") as $isi) {

                  if ($data_edit->nip_dosen_pa==$isi->nip) {
                    echo "<option value='$isi->nip' selected>$isi->nip - $isi->dosen</option>";
                  } else {
                    if ($isi->aktif=='Y') {
                        echo "<option value='$isi->nip'>$isi->nip - $isi->dosen</option>";
                    }
                 
                    }
               } ?>
              </select>
          </div>
                      </div><!-- /.form-group -->

              <input type="hidden" name="id" value="<?=$data_edit->mhs_id;?>">

              <div class="form-group">
                <div class="col-lg-12">
                  <div class="modal-footer"> 
                  <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> <?php echo $lang["cancel_button"];?></button>
                  <button type="submit" class="btn btn-primary save-data"><i class="fa fa-save"></i> <?php echo $lang["submit_button"];?></button>
                  </div>
                </div>
              </div><!-- /.form-group -->

            </form>

<script type="text/javascript">
    
    $(document).ready(function() {
    
    
    
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
        
    
    $("#edit_bim_akademis").validate({
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
            } else if (element.attr("accept") == "image/*") {
                element.parent().parent().parent().append(error);
            }
            else if (element.hasClass("tgl_picker_input")) {
               element.parent().parent().append(error);
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
        
        rules: {
            
          dosen_pemb: {
          required: true,
          //minlength: 2
          },
        
        },
         messages: {
            
          dosen_pemb: {
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
                            $('#modal_bim_akademis').modal('hide');
                            $(".error_data").hide();
                            $(".notif_top_up").fadeIn(1000);
                            $(".notif_top_up").fadeOut(1000, function() {
                                 dtb_mahasiswa.draw();
                            });
                          }
                    });
                }

            });
        }
    });
});
</script>
