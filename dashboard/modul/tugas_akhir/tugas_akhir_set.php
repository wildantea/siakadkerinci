<?php
include "../../inc/config.php";
$data_edit = $db->fetch_single_row("tugas_akhir","id_ta",$_POST['id_data']);
?>
<style type="text/css"> .datepicker {z-index: 1200 !important; } </style>
      <form id="edit_data_set" method="post" class="form-horizontal" action="<?=base_admin();?>modul/tugas_akhir/tugas_akhir_action.php?act=up_set">
          <div class="form-group">
              <label for="ketua_sidang" class="control-label col-lg-2">Ketua Sidang<span style="color:#FF0000">*</span></label>
              <div class="col-lg-10">
                <select name="ketua_sidang" data-placeholder="Pilih Jenis Ketua Sidang..." class="form-control chzn-select" tabindex="2" required>
                  <option name="ketua_sidang" value=""></option>
                  <?php
                    foreach ($db->fetch_all('dosen') as $isi) {
                      if($isi->id_dosen == $data_edit->ketua_sidang) {
                        echo "<option name='ketua_sidang' value='$isi->id_dosen' selected>$isi->nama_dosen</option>";
                      } else{
                        echo "<option name='ketua_sidang' value='$isi->id_dosen'>$isi->nama_dosen</option>";
                      }
                    }
                  ?>
                </select>
              </div>
          </div><!-- /.form-group -->

          <div class="form-group">
              <label for="sekertaris_sidang" class="control-label col-lg-2">Sekertaris Sidang<span style="color:#FF0000">*</span></label>
              <div class="col-lg-10">
                <select name="sekertaris_sidang" data-placeholder="Pilih Jenis Sekertaris Sidang..." class="form-control chzn-select" tabindex="2" required>
                  <option name="sekertaris_sidang" value=""></option>
                  <?php
                    foreach ($db->fetch_all('dosen') as $isi) {
                      if($isi->id_dosen == $data_edit->sekertaris_sidang) {
                        echo "<option name='sekertaris_sidang' value='$isi->id_dosen' selected>$isi->nama_dosen</option>";
                      } else{
                        echo "<option name='sekertaris_sidang' value='$isi->id_dosen'>$isi->nama_dosen</option>";
                      }
                    }
                  ?>
                </select>
              </div>
          </div><!-- /.form-group -->
          
          <div class="form-group">
              <label for="penguji_1" class="control-label col-lg-2">Penguji 1<span style="color:#FF0000">*</span></label>
              <div class="col-lg-10">
                <select name="penguji_1" data-placeholder="Pilih Jenis Penguji 1..." class="form-control chzn-select" tabindex="2" required>
                  <option name="penguji_1" value=""></option>
                  <?php
                    foreach ($db->fetch_all('dosen') as $isi) {
                      if($isi->id_dosen == $data_edit->penguji_1) {
                        echo "<option name='penguji_1' value='$isi->id_dosen' selected>$isi->nama_dosen</option>";
                      } else{
                        echo "<option name='penguji_1' value='$isi->id_dosen'>$isi->nama_dosen</option>";
                      }
                    }
                  ?>
                </select>
              </div>
          </div><!-- /.form-group -->

          <div class="form-group">
              <label for="penguji_2" class="control-label col-lg-2">Penguji 2<span style="color:#FF0000">*</span></label>
              <div class="col-lg-10">
                <select name="penguji_2" data-placeholder="Pilih Jenis Penguji 2 ..." class="form-control chzn-select" tabindex="2" required>
                  <option name="penguji_2" value=""></option>
                  <?php
                    foreach ($db->fetch_all('dosen') as $isi) {
                      if($isi->id_dosen == $data_edit->penguji_2) {
                        echo "<option name='penguji_1' value='$isi->id_dosen' selected>$isi->nama_dosen</option>";
                      } else{
                        echo "<option name='penguji_1' value='$isi->id_dosen'>$isi->nama_dosen</option>";
                      }
                    }
                  ?>
                </select>
              </div>
          </div><!-- /.form-group -->

          <div class="form-group">
              <label for="penguji_3" class="control-label col-lg-2">Penguji 3<span style="color:#FF0000">*</span></label>
              <div class="col-lg-10">
                <select name="penguji_3" data-placeholder="Pilih Jenis Penguji 3 ..." class="form-control chzn-select" tabindex="2" required>
                  <option name="penguji_3" value=""></option>
                  <?php
                    foreach ($db->fetch_all('dosen') as $isi) {
                      if($isi->id_dosen == $data_edit->penguji_3) {
                        echo "<option name='penguji_1' value='$isi->id_dosen' selected>$isi->nama_dosen</option>";
                      } else{
                        echo "<option name='penguji_1' value='$isi->id_dosen'>$isi->nama_dosen</option>";
                      }
                    }
                  ?>
                </select>
              </div>
          </div><!-- /.form-group -->

          <input id="id" type="hidden" value="<?=$data_edit->id_ta;?>" name="id" >

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

            //trigger validation onchange
            $('select').on('change', function() {
                $(this).valid();
            });
            //hidden validate because we use chosen select
            $.validator.setDefaults({ ignore: ":hidden:not(select)" });

          $("#edit_data_set").validate({
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

                penguji_1: {
                required: true,
                //minlength: 2
                },

                penguji_1: {
                required: true,
                //minlength: 2
                },

              },
               messages: {

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
                      type: "POST",
                      url: $(this).attr("action"),
                      data: $("#edit_data_set").serialize(),
                      success: function(data) {
                          $('#modal_tugas_akhir_set').modal('hide');
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
