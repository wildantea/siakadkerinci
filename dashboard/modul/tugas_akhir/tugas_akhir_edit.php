<?php
include "../../inc/config.php";
$data_edit = $db->fetch_single_row("tugas_akhir","id_ta",$_POST['id_data']);
?>
  <style type="text/css"> .datepicker {z-index: 1200 !important; } </style>
  <form id="edit_tugas_akhir" method="post" class="form-horizontal" action="<?=base_admin();?>modul/tugas_akhir/tugas_akhir_action.php?act=up">
    <div class="form-group">
      <label for="kode_fak" class="control-label col-lg-2">Fakultas <span style="color:#FF0000">*</span></label>
      <div class="col-lg-10">
          <select name="kode_fak" data-placeholder="Pilih kode_fak..." class="form-control chzn-select" tabindex="2" required>
           <option value=""></option>
           <?php foreach ($db->fetch_all("fakultas") as $isi) {

              if ($data_edit->kode_fak==$isi->kode_fak) {
                echo "<option value='$isi->kode_fak' selected>$isi->nama_resmi</option>";
              } else {
              echo "<option value='$isi->kode_fak'>$isi->nama_resmi</option>";
                }
           } ?>
          </select>
      </div>
    </div><!-- /.form-group -->
    <div class="form-group">
      <label for="kode_jurusan" class="control-label col-lg-2">Jurusan <span style="color:#FF0000">*</span></label>
      <div class="col-lg-10">
          <select name="kode_jurusan" data-placeholder="Pilih kode_jurusan..." class="form-control chzn-select" tabindex="2" required>
           <option value=""></option>
           <?php foreach ($db->fetch_all("jurusan") as $isi) {

              if ($data_edit->kode_jurusan==$isi->kode_jur) {
                echo "<option value='$isi->kode_jur' selected>$isi->nama_jur</option>";
              } else {
              echo "<option value='$isi->kode_jur'>$isi->nama_jur</option>";
                }
           } ?>
          </select>
      </div>
    </div><!-- /.form-group -->

    <div class="form-group">
        <label for="judul_ta" class="control-label col-lg-2">Judul TA </label>
        <div class="col-lg-10">
        <textarea class="form-control col-xs-12" rows="5" name="judul_ta"><?=$data_edit->judul_ta;?> </textarea>
        </div>
    </div><!-- /.form-group -->
    <div class="form-group">
      <label for="pembimbing_1" class="control-label col-lg-2">Pembimbing 1 <span style="color:#FF0000">*</span></label>
      <div class="col-lg-10">
        <select name="pembimbing_1" data-placeholder="Pilih pembimbing_1..." class="form-control chzn-select" tabindex="2" required>
         <option value=""></option>
         <?php foreach ($db->fetch_all("dosen") as $isi) {

            if ($data_edit->pembimbing_1==$isi->id_dosen) {
              echo "<option value='$isi->id_dosen' selected>$isi->nama_dosen</option>";
            } else {
            echo "<option value='$isi->id_dosen'>$isi->nama_dosen</option>";
              }
         } ?>
          </select>
      </div>
    </div><!-- /.form-group -->
    <div class="form-group">
      <label for="pembimbing_2" class="control-label col-lg-2">Pembimbing 2 <span style="color:#FF0000">*</span></label>
      <div class="col-lg-10">
          <select name="pembimbing_2" data-placeholder="Pilih pembimbing_2..." class="form-control chzn-select" tabindex="2" required>
           <option value=""></option>
           <?php foreach ($db->fetch_all("dosen") as $isi) {

              if ($data_edit->pembimbing_2==$isi->id_dosen) {
                echo "<option value='$isi->id_dosen' selected>$isi->nama_dosen</option>";
              } else {
              echo "<option value='$isi->id_dosen'>$isi->nama_dosen</option>";
                }
           } ?>
          </select>
      </div>
    </div><!-- /.form-group -->

    <input type="hidden" name="id" value="<?=$data_edit->id_ta;?>">

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

    $("#edit_tugas_akhir").validate({
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
          },

          penguji_1: {
          required: true,
          //minlength: 2
          },

          penguji_2: {
          required: true,
          //minlength: 2
          },

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
          },

          penguji_1: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },

          penguji_2: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },

        },

        submitHandler: function(form) {
            $("#loadnya").show();
            $(form).ajaxSubmit({
                type: "post",
                url: $(this).attr("action"),
                data: $("#edit_tugas_akhir").serialize(),
                success: function(data) {
                    $('#modal_tugas_akhir').modal('hide');
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
