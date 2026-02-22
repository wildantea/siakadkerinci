<?php
include "../../inc/config.php";
$data_edit = $db->fetch_single_row("keu_bank","kode_bank",$_POST['id_data']);
?>
  <style type="text/css"> .datepicker {z-index: 1200 !important; } </style>
   <div class="alert alert-danger error_data" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning"></span>
        </div>
            <form id="edit_data_bank" method="post" class="form-horizontal" action="<?=base_admin();?>modul/data_bank/data_bank_action.php?act=up">
                            
              <div class="form-group">
                <label for="Kode Bank" class="control-label col-lg-3">Kode Bank <span style="color:#FF0000">*</span></label>
                <div class="col-lg-9">
                  <input type="text" name="kode_bank" value="<?=$data_edit->kode_bank;?>" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Nomor Rekening" class="control-label col-lg-3">Nomor Rekening <span style="color:#FF0000">*</span></label>
                <div class="col-lg-9">
                  <input type="text" name="nomor_rekening" value="<?=$data_edit->nomor_rekening;?>" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Nama Singkat" class="control-label col-lg-3">Nama Singkat <span style="color:#FF0000">*</span></label>
                <div class="col-lg-9">
                  <input type="text" name="nama_singkat" value="<?=$data_edit->nama_singkat;?>" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Nama Bank" class="control-label col-lg-3">Nama Bank <span style="color:#FF0000">*</span></label>
                <div class="col-lg-9">
                  <input type="text" name="nama_bank" value="<?=$data_edit->nama_bank;?>" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Nama Cabang" class="control-label col-lg-3">Nama Cabang <span style="color:#FF0000">*</span></label>
                <div class="col-lg-9">
                  <input type="text" name="cabang" value="<?=$data_edit->cabang;?>" class="form-control" required>
                </div>
              </div><!-- /.form-group -->


              <div class="form-group">
                  <label for="Gedung " class="control-label col-lg-3">Pembayaran Untuk Jenjang</label>
                              <div class="col-lg-9">
                  <select  id="peruntukan" name="peruntukan[]" data-placeholder="Bisa Multi Prodi  ..." class="form-control chzn-select" tabindex="2" multiple="">
                     <option value=""></option>
                     <?php
                     $array_jenjang = json_decode($data_edit->peruntukan);
                     $jenjang_prodi = $db->query("select * from view_prodi_jenjang group by id_jenjang");
                     if ($jenjang_prodi->rowCount()>0) {
                         foreach ($jenjang_prodi as $jnj) {
                            if (in_array($jnj->id_jenjang, $array_jenjang)) {
                              echo "<option value='$jnj->id_jenjang' selected>$jnj->jenjang</option>";
                            } else {
                              echo "<option value='$jnj->id_jenjang'>$jnj->jenjang</option>";
                              } 
                          }
                      }
                      ?>
                    </select>
                  </div>
              </div><!-- /.form-group -->

                <div class="form-group">
                <label for="Aktif" class="control-label col-lg-3">Aktif</label>
                <div class="col-lg-9">
                <?php if ($data_edit->aktif=="Y") {
                ?>
                  <input name="aktif" class="make-switch" data-on-text="Aktif" data-off-text="Tidak" type="checkbox" checked>
                <?php
              } else {
                ?>
                  <input name="aktif" class="make-switch" data-on-text="Aktif" data-off-text="Tidak" type="checkbox">
                <?php
              }?>

                </div>
            </div><!-- /.form-group -->

              
              <input type="hidden" name="id" value="<?=$data_edit->kode_bank;?>">

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
      
    $("#edit_data_bank").validate({
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
            
          kode_bank: {
          required: true,
          //minlength: 2
          },
        
          nomor_rekening: {
          required: true,
          //minlength: 2
          },
        
          nama_singkat: {
          required: true,
          //minlength: 2
          },
        
          nama_bank: {
          required: true,
          //minlength: 2
          },
        
          cabang: {
          required: true,
          //minlength: 2
          },
        
        },
         messages: {
            
          kode_bank: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          nomor_rekening: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          nama_singkat: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          nama_bank: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          cabang: {
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
                            $('#modal_data_bank').modal('hide');
                            $(".error_data").hide();
                            $(".notif_top_up").fadeIn(1000);
                            $(".notif_top_up").fadeOut(1000, function() {
                                 dtb_data_bank.draw();
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
