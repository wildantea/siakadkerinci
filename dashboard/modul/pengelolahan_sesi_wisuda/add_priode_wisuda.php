<?php
session_start();
include "../../inc/config.php";
session_check();
?>
<style type="text/css"> .datepicker {z-index: 1200 !important; } </style>
  <form id="input_pengelolahan_sesi_wisuda" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/pengelolahan_sesi_wisuda/pengelolahan_sesi_wisuda_action.php?act=in_priode">
   <div class="form-group">
          <label for="nama_wisuda" class="control-label col-lg-2">Nama Wisuda <span style="color:#FF0000">*</span></label>
          <div class="col-lg-10">
            <input type="text" name="nama_wisuda" class="form-control" required>
          </div>
        </div>
        <div class="form-group">
          <label for="priode" class="control-label col-lg-2">Priode Wisuda <span style="color:#FF0000">*</span></label>
          <div class="col-lg-10">
            <input id="tgl4" type="text" name="priode" class="form-control" required>
          </div>
        </div>
        <div class="form-group">
          <label for="tempat" class="control-label col-lg-2">Tempat Wisuda <span style="color:#FF0000">*</span></label>
          <div class="col-lg-10">
            <input type="text" name="tempat" class="form-control" required>
          </div>
        </div>
        <div class="form-group">
          <label for="kuota" class="control-label col-lg-2">Kuota <span style="color:#FF0000">*</span></label>
          <div class="col-lg-10">
            <input type="text" name="kuota" class="form-control" required>
          </div>
        </div>
        <div class="form-inline form-group">
          <label for="biaya" class="control-label col-lg-2">Biaya <span style="color:#FF0000">*</span></label>
          <div class="col-lg-10">
            <div class="input-group">
              <div class="input-group-addon">Rp.</div>
              <input id="auto" type="text" name="biaya" class="form-control" data-a-sep="." data-a-dec="," required>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label for="tgl_wisuda" class="control-label col-lg-2">Tanggal Wisuda <span style="color:#FF0000">*</span></label>
          <div class="col-lg-10">
            <input id="tgl1" type="text" name="tgl_wisuda" class="form-control" required>
          </div>
        </div>
        <div class="form-group">
          <label for="tgl_awal" class="control-label col-lg-2">Daftar Awal Wisuda<span style="color:#FF0000">*</span></label>
          <div class="col-lg-10">
            <input id="tgl2" type="text" name="tgl_awal" class="form-control" required>
          </div>
        </div>
        <div class="form-group">
          <label for="tgl_akhir" class="control-label col-lg-2">Daftar Akhir Akhir<span style="color:#FF0000">*</span></label>
          <div class="col-lg-10">
            <input id="tgl3" type="text" name="tgl_akhir" class="form-control" required>
          </div>
        </div>

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

        $('#auto').autoNumeric("init");
    

      //trigger validation onchange
      $('select').on('change', function() {
          $(this).valid();
      });
      //hidden validate because we use chosen select
      $.validator.setDefaults({ ignore: ":hidden:not(select)" });

    $("#input_pengelolahan_sesi_wisuda").validate({
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

          priode: {
          required: true,
          //minlength: 2
          },

          nama_wisuda: {
          required: true,
          //minlength: 2
          },

          tempat: {
          required: true,
          //minlength: 2
          },

          biaya: {
          required: true,
          //minlength: 2
          },

          kuota: {
          required: true,
          //minlength: 2
          },

          tgl_wisuda: {
          required: true,
          //minlength: 2
          },

          tgl_awal: {
          required: true,
          //minlength: 2
          },

          tgl_akhir: {
          required: true,
          //minlength: 2
          }

        },
         messages: {

          priode: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },

          nama_wisuda: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },

          tempat: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },

          biaya: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },

          kuota: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },

          tgl_wisuda: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },

          tgl_awal: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },

          tgl_akhir: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          }                              
        },

        submitHandler: function(form) {
            $("#loadnya").show();
            $(form).ajaxSubmit({
                type: "post",
                url: $(this).attr("action"),
                data: $("#input_pengelolahan_sesi_wisuda").serialize(),
                success: function(data) {
                    console.log(data);
                    $('#modal_periode').modal('hide');
                    $("#loadnya").hide();
                    if (data == "good") {
                        $(".notif_top").fadeIn(1000);
                        $(".notif_top").fadeOut(1000, function() {
                               location.reload();
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
  url : "<?=base_admin();?>modul/pengelolahan_sesi_wisuda/get_jurusan_fakultas.php",
  data : {nim:this.value},
  success : function(data) {
      $("#form_civitas").html(data);
      $("#form_civitas").trigger("chosen:updated");

  }
});

});
</script>