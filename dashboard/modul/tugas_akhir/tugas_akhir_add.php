<?php
session_start();
include "../../inc/config.php";
session_check();
?>
<style type="text/css"> .datepicker {z-index: 1200 !important; } </style>
  <form id="input_tugas_akhir" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/tugas_akhir/tugas_akhir_action.php?act=in">
    <div class="form-group">
      <label for="nim" class="control-label col-lg-2">Nim<span style="color:#FF0000">*</span></label>
      <div class="col-lg-10">
        <input id="nim" type="text" name="nim" placeholder="nim" class="form-control" required>
      </div>
    </div><!-- /.form-group -->
    <div id="form_civitas"></div>
    <div class="form-group">
      <label for="judul_ta" class="control-label col-lg-2">Judul</label>
      <div class="col-lg-10">
        <textarea id="judul_ta" class="form-control col-xs-12" rows="5" name="judul_ta" ></textarea>
      </div>
    </div><!-- /.form-group -->
    <div class="form-group">
      <label for="priode_muna" class="control-label col-lg-2">Priode Muna</label>
      <div class="col-lg-10">
        <select name="priode_muna" data-placeholder="Pilih Priode Munaqasah ..." class="form-control chzn-select" tabindex="2" required>
          <option value=""></option>
          <?php
            foreach ($db->query("select * from jadwal_muna jm join semester_ref sr on jm.priode_muna=sr.id_semester join jenis_semester j on sr.id_jns_semester=j.id_jns_semester order by sr.id_semester desc") as $jm) {
              echo "<option value='$jm->id_muna'>$jm->priode_muna</option>";
            }
          ?>
        </select>
      </div>
    </div>
    <div class="form-group">
      <label for="pembimbing_1" class="control-label col-lg-2">Pembimbing 1 <span style="color:#FF0000">*</span></label>
      <div class="col-lg-10">
        <select name="pembimbing_1" data-placeholder="Pilih pembimbing_1 ..." class="form-control chzn-select" tabindex="2" required>
           <option value=""></option>
           <?php
            $dosen="";
             foreach ($db->fetch_all("dosen") as $isi) {
                if($isi->nidn != NULL) {
                  echo "<option value='$isi->id_dosen'>$isi->nama_dosen</option>";
                  $dosen="";
                } else{
                  $dosen="NULL";
                }
             }

             echo "<option value='$dosen'>$dosen</option>";
           ?>
          </select>
        </div>
    </div><!-- /.form-group -->
    <div class="form-group">
      <label for="pembimbing_2" class="control-label col-lg-2">Pembimbing 2 <span style="color:#FF0000">*</span></label>
      <div class="col-lg-10">
      <select name="pembimbing_2" data-placeholder="Pilih pembimbing_2 ..." class="form-control chzn-select" tabindex="2" required>
         <option value=""></option>
         <?php
          $dosen="";
           foreach ($db->fetch_all("dosen") as $isi) {
              if($isi->nidn != NULL) {
                echo "<option value='$isi->id_dosen'>$isi->nama_dosen</option>";
                $dosen="";
              } else{
                $dosen="NULL";
              }
           }

           echo "<option value='$dosen'>$dosen</option>";
         ?>
        </select>
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

          kode_fak: {
          required: true,
          //minlength: 2
          },

          kode_jurusan: {
          required: true,
          //minlength: 2
          },

          nim: {
          required: true,
          //minlength: 2
          },

          pembimbing_1: {
          required: true,
          //minlength: 2
          },

          pembimbing_2: {
          required: true,
          //minlength: 2
          }

        },
         messages: {

          kode_fak: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },

          kode_jurusan: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },

          nim: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },

          pembimbing_1: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },

          pembimbing_2: {
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

$("#kode_fak").change(function(){

      $.ajax({
      type : "post",
      url : "<?=base_admin();?>modul/tugas_akhir/get_jurusan_filter.php",
      data : {fakultas:this.value},
      success : function(data) {
          $("#kode_jurusan").html(data);
          $("#kode_jurusan").trigger("chosen:updated");

      }
  });

});

$("#nim").on('input',function(){

      $.ajax({
      type : "post",
      url : "<?=base_admin();?>modul/tugas_akhir/get_fakultas_jurusan.php",
      data : {nim:this.value},
      success : function(data) {
          $("#form_civitas").html(data);
          $("#form_civitas").trigger("chosen:updated");

      }
  });

});
</script>
