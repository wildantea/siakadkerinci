<?php
include "../../inc/config.php";
?>
<style type="text/css"> .datepicker {z-index: 1200 !important; } </style>
      <form id="input_pendaftaran_beasiswa" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/pendaftaran_beasiswa/pendaftaran_beasiswa_action.php?act=in_beasiswa">
        <div class="form-group">
          <label for="Nama Beasiswa" class="control-label col-lg-2">Nama Beasiswa <span style="color: #FF0000">*</span></label>
          <div class="col-lg-10">
            <input type="text" name="nama_beasiswa" class="form-control" placeholder="Nama Beasiswa" required>
          </div>
        </div>              
        <div class="form-group">
          <label for="Jenis Beasiswa" class="control-label col-lg-2">Jenis Beasiswa <span style="color:#FF0000">*</span>
          </label>
          <div class="col-lg-10">
            <select name="jenis_beasiswa" data-placeholder="Pilih Jenis Beasiswa ..." class="form-control chzn-select" tabindex="2" required>
               <option value=""></option>
               <?php foreach ($db->fetch_all("beasiswa_jenis") as $isi) {
                  echo "<option value='$isi->id_beasiswajns'>$isi->jenis_beasiswajns</option>";
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
                      if ($isi2->id_semester==$sem2) {
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
            <input id="tgl1" type="text" name="batas_awal" class="form-control" placeholder="Batas Awal Beasiswa" required>
          </div>
        </div>
        <div class="form-group">
          <label for="Batas Akhir" class="control-label col-lg-2">Batas Akhir <span style="color: #FF0000">*</span></label>
          <div class="col-lg-10">
            <input id="tgl2" type="text" name="batas_akhir" class="form-control" placeholder="Batas Akhir Beasiswa" required>
          </div>
        </div>
        <div class="form-group">
          <label for="Syarat" class="control-label col-lg-2">Syarat <span style="color:#FF0000">*</span></label>
          <div class="col-lg-10">
            <textarea name="syarat_beasiswa" class="form-control" rows="10"></textarea>
          </div>
        </div>


        <div class="form-group">
            <label for="tags" class="control-label col-lg-2">&nbsp;</label>
            <div class="col-lg-4">
              <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-step-backward"></span> <?php echo $lang["cancel_button"];?></button>
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

      $("#tgl3").datepicker( {
        format: "yyyy-mm",
      });

    
      //trigger validation onchange
      $('select').on('change', function() {
          $(this).valid();
      });
      //hidden validate because we use chosen select
      $.validator.setDefaults({ ignore: ":hidden:not(select)" });
      
    $("#input_pendaftaran_beasiswa").validate({
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
            
          nama_beasiswa: {
          required: true,
          //minlength: 2
          },

          jenis_beasiswa: {
          required: true,
          //minlength: 2
          },

          priode_beasiswa: {
          required: true,
          //minlength: 2
          },

          batas_awal: {
          required: true,
          //minlength: 2
          },

          batas_akhir: {
          required: true,
          //minlength: 2
          },
        
          syarat: {
          required: true,
          //minlength: 2
          }
            
        },
         messages: {
            
          nama_beasiswa: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },

          priode_beasiswa: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },

          batas_awal: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },

          batas_akhir: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          syarat: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          }       
        },
    
        submitHandler: function(form) {
            $("#loadnya").show();
            $(form).ajaxSubmit({
                type: "post",
                url: $(this).attr("action"),
                data: $("#input_pendaftaran_beasiswa").serialize(),
                success: function(data) {
                    $('#modal_pendaftaran_beasiswa').modal('hide');
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
