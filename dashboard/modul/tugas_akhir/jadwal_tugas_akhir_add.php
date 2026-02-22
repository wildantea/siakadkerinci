<?php
session_start();
include "../../inc/config.php";
session_check();
?>
<style type="text/css"> .datepicker {z-index: 1200 !important; } </style>
  <form id="input_tugas_akhir" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/tugas_akhir/tugas_akhir_action.php?act=in_jadwal">
          <div class="form-group">
            <label for="Nama Kelas" class="control-label col-lg-2">Priode</label>
              <div class="col-lg-10">
                <select name="priode_muna" id="priode_muna" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2" >
                 <option value=""></option>
                 <?php 
                 $sem = $db->query("select * from semester_ref s join jenis_semester j 
                  on s.id_jns_semester=j.id_jns_semester order by s.id_semester desc");
                 foreach ($sem as $isi2) {
                  if ($isi2->id_semester==$sem2) {
                   echo "<option name='priode_muna' value='".$enc->enc($isi2->id_semester)."' selected>$isi2->jns_semester $isi2->tahun/".($isi2->tahun+1)."</option>";
                 }else{
                  echo "<option name='priode_muna' value='".$enc->enc($isi2->id_semester)."'>$isi2->jns_semester $isi2->tahun/".($isi2->tahun+1)."</option>";
                }

              } ?>
            </select>
          </div>
         </div>
          <div class="form-group">
              <label for="penguji_1" class="control-label col-lg-2">Batas Awal<span style="color:#FF0000">*</span></label>
              <div class="col-lg-10">
                <input id="tgl3" type="text" name="batas_awal" placeholder="Awal Daftar" class="form-control" required>
              </div>
          </div><!-- /.form-group -->

          <div class="form-group">
              <label for="penguji_2" class="control-label col-lg-2">Batas Akhir<span style="color:#FF0000">*</span></label>
              <div class="col-lg-10">
                <input id="tgl2" type="text" name="batas_akhir" placeholder="Akhir Daftar" class="form-control" required>  
              </div>
          </div><!-- /.form-group -->

          <div class="form-group">
            <label for="tanggal_sidang" class="control-label col-lg-2">Tanggal Sidang<span style="color:#FF0000">*</span></label>
            <div class="col-lg-10">
              <input id="tgl1" type="text" name="tanggal_sidang" placeholder="Tanggal Sidang" class="form-control" required>
            </div>
          </div><!-- /.form-group -->

          <div class="form-group">
            <label for="tags" class="control-label col-lg-2">&nbsp;</label>
            <div class="col-lg-10">
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
          format: "yyyy-mm-dd",
      });

      $("#tgl4").datepicker( {
          format: "yyyy-mm",
      });

      //trigger validation onchange
      $('select').on('change', function() {
          $(this).valid();
      });
      //hidden validate because we use chosen select
      $.validator.setDefaults({ ignore: ":hidden:not(select)" });

    $("#input_tugas_akhir").validate({
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

          batas_awal: {
          required: true,
          //minlength: 2
          },

          batas_akhir: {
          required: true,
          //minlength: 2
          },

          tanggal_sidang: {
          required: true,
          //minlength: 2
          },

          priode_kompre: {
          required: true,
          //minlength: 2
          },          

        },
         messages: {

          batas_awal: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },

          batas_akhir: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },

          tanggal_sidang: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },

          priode_kompre: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          }          
        },

        submitHandler: function(form) {
            $("#loadnya").show();
            $(form).ajaxSubmit({
                type: "post",
                url: $(this).attr("action"),
                data: $("#input_tugas_akhir").serialize(),
                success: function(data) {
                    $('#modal_tugas_akhir').modal('hide');
                    $("#loadnya").hide();
                    if(data == "good") {
                      $(".notif_top").fadeIn(1000);
                      $(".notif_top").fadeOut(1000, function(){
                        dataTable.draw(false);   
                      });
                    } else if(data == "die") {
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