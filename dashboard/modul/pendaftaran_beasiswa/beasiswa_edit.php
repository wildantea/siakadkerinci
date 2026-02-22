<?php
include "../../inc/config.php";
$data_edit = $db->fetch_single_row("beasiswa","id_beasiswa",$_POST['id_data']);
?>
  <style type="text/css"> .datepicker {z-index: 1200 !important; } </style>
            <form id="edit_pendaftaran_beasiswa" method="post" class="form-horizontal" action="<?=base_admin();?>modul/pendaftaran_beasiswa/pendaftaran_beasiswa_action.php?act=up_beasiswa">
                            
              <div class="form-group">
                <label for="Nama Beasiswa" class="control-label col-lg-2">Nama Beasiswa <span style="color: #FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="nama_beasiswa" class="form-control" placeholder="Nama Beasiswa" value="<?=$data_edit->nama_beasiswa?>" required>
                </div>
              </div>              
              <div class="form-group">
                <label for="Jenis Beasiswa" class="control-label col-lg-2">Jenis Beasiswa <span style="color:#FF0000">*</span>
                </label>
                <div class="col-lg-10">
                  <select name="jenis_beasiswa" data-placeholder="Pilih Jenis Beasiswa ..." class="form-control chzn-select" tabindex="2" required>
                     <option value=""></option>
                     <?php foreach ($db->fetch_all("beasiswa_jenis") as $isi) {
                        if($data_edit->jns_beasiswa == $isi->id_beasiswajns) {
                          echo "<option value='$isi->id_beasiswajns' selected>$isi->jenis_beasiswajns</option>";
                        }else {
                          echo "<option value='$isi->id_beasiswajns'>$isi->jenis_beasiswajns</option>";
                        }
                     } ?>
                  </select>
                </div>
              </div><!-- /.form-group -->
              <div class="form-group">
                <label for="Priode Beasiswa" class="control-label col-lg-2">Priode Beasiswa</label>
                <div class="col-lg-10">
                    <select name="priode_beasiswa" id="sem" data-placeholder="Pilih Priode Beasiswa ..." class="form-control chzn-select" tabindex="2" >
                       <option value=""></option>
                       <?php 
                         $sem = $db->query("select * from semester_ref s join jenis_semester j 
                          on s.id_jns_semester=j.id_jns_semester order by s.id_semester desc");
                          foreach ($sem as $isi2) {
                            if ($data_edit->priode_beasiswa == $isi2->id_semester) {
                             echo "<option value='".$enc->enc($isi2->id_semester)."' selected>$isi2->jns_semester $isi2->tahun/".($isi2->tahun+1)."</option>";
                            }else{
                              echo "<option value='".$enc->enc($isi2->id_semester)."'>$isi2->jns_semester $isi2->tahun/".($isi2->tahun+1)."</option>";
                            }
                          
                       } ?>
                      </select>
                </div>
              </div><!-- /.form-group -->
              <div class="form-group">
                <label for="Batas Awal" class="control-label col-lg-2">Batas Awal <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input id="tgl1" type="text" name="batas_awal" class="form-control" placeholder="Batas Awal Beasiswa" value="<?=$data_edit->batas_awal?>" required>
                </div>
              </div>
              <div class="form-group">
                <label for="Batas Akhir" class="control-label col-lg-2">Batas Akhir <span style="color: #FF0000">*</span></label>
                <div class="col-lg-10">
                  <input id="tgl2" type="text" name="batas_akhir" class="form-control" placeholder="Batas Akhir Beasiswa" value="<?=$data_edit->batas_akhir?>" required>
                </div>
              </div>
              <div class="form-group">
                <label for="Syarat" class="control-label col-lg-2">Syarat <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <textarea name="syarat_beasiswa" class="form-control" rows="10"><?=$data_edit->syarat?></textarea>
                </div>
              </div>
              
              <input type="hidden" name="id" value="<?=$data_edit->id_beasiswa;?>">

              <div class="form-group">
                <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                <div class="col-lg-4">
                  <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-step-backward""></span> <?php echo $lang["cancel_button"];?></button>
                  <button type="submit" class="btn btn-primary"><span class="fa fa-floppy-o"></span> <?php echo $lang["submit_button"];?></button>
                </div>
              </div><!-- /.form-group -->

            </form>

<script type="text/javascript">
    $(document).ready(function() {
    
          //chosen select
          $(".chzn-select").chosen();
          $(".chzn-select-deselect").chosen({
              allow_single_deselect: true
          });
        
      $("#tgl1").datepicker( {
        format: "yyyy-mm-dd",
      });

      $("#tgl2").datepicker( {
        format: "yyyy-mm-dd",
      });
    
      //trigger validation onchange
      $('select').on('change', function() {
          $(this).valid();
      });
      //hidden validate because we use chosen select
      $.validator.setDefaults({ ignore: ":hidden:not(select)" });
      
    $("#edit_pendaftaran_beasiswa").validate({
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
            
          jenis_beasiswa: {
          required: true,
          //minlength: 2
          },
        
          keterangan: {
          required: true,
          //minlength: 2
          },
        
        },
         messages: {
            
          jenis_beasiswa: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          keterangan: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },   
        
        },
    
        submitHandler: function(form) {
            $("#loadnya").show();
            $(form).ajaxSubmit({
                type: "post",
                url: $(this).attr("action"),
                data: $("#edit_pendaftaran_beasiswa").serialize(),
                success: function(data) {
                    $('#modal_edit_pendaftaran_beasiswa').modal('hide');
                    $("#loadnya").hide();
                    if (data == "good") {
                        $(".notif_top_up").fadeIn(1000);
                        $(".notif_top_up").fadeOut(1000, function() {
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
