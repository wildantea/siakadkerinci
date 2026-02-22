<?php
include "../../inc/config.php";
$data_edit = $db->fetch_single_row("tugas_akhir","id_ta",$_POST['id_data']);
?>
<style type="text/css"> .datepicker {z-index: 1200 !important; } </style>
      <form id="edit_data_sk" method="post" class="form-horizontal" action="<?=base_admin();?>modul/data_yudisium/data_yudisium_action.php?act=up_sk">
          <div class="form-group">
              <label for="id_jenis_keluar" class="control-label col-lg-2">Jenis Keluar <span style="color:#FF0000">*</span></label>
              <div class="col-lg-10">
                <select name="id_jenis_keluar" data-placeholder="Pilih Jenis Keluar ..." class="form-control chzn-select" tabindex="2" required>
                  <option name="id_jenis_keluar" value=""></option>
                  <?php
                    foreach ($db->fetch_all('jenis_keluar') as $isi) {
                      echo "<option name='id_jenis_keluar' value='$isi->id_jns_keluar'>$isi->ket_keluar</option>";
                    }
                  ?>
                </select>
              </div>
          </div><!-- /.form-group -->

          <div class="form-group">
            <label for="tanggal_keluar" class="control-label col-lg-2">Tanggal Keluar <span style="color:#FF0000">*</span></label>
            <div class="col-lg-10">
              <input id="tgl1" type="text" name="tanggal_keluar" placeholder="Tanggal Keluar" class="form-control" required>
            </div>
          </div><!-- /.form-group -->

          <div class="form-group">
            <label for="sk_yudisium" class="control-label col-lg-2">SK Yudisium <span style="color:#FF0000">*</span></label>
            <div class="col-lg-10">
              <input type="text" name="sk_yudisium" placeholder="SK Yudisium" class="form-control" required>
            </div>
          </div><!-- /.form-group -->

          <div class="form-group">
            <label for="tgl_sk_yudisium" class="control-label col-lg-2">Tgl SK Yudisium <span style="color:#FF0000">*</span></label>
            <div class="col-lg-10">
              <input id="tgl2" type="text" name="tgl_sk_yudisium" placeholder="Tgl SK Yudisium" class="form-control" required>
            </div>
          </div><!-- /.form-group -->

          <div class="form-group">
            <label for="no_seri_ijasah" class="control-label col-lg-2">No Seri Ijasah </label>
            <div class="col-lg-10">
              <input type="text" name="no_seri_ijasah" placeholder="No Seri Ijasah" class="form-control" >
            </div>
          </div><!-- /.form-group -->

          <div class="form-group">
            <label for="jalur_skripsi" class="control-label col-lg-2">Jalur Skripsi <span style="color:#FF0000">*</span></label>
            <div class="col-lg-10">
              <select name="jalur_skripsi" data-placeholder="Pilih Jalur Skripsi..." class="form-control chzn-select" tabindex="2" required="">
                <option name="jalur_skripsi" value=""></option>
                <?php
                  foreach($db->fetch_all("jenis_skripsi") as $isi){
                    echo "<option name='jalur_skripsi' value='$isi->id_jenis_skripsi'>$isi->ket_jenis_skripsi</option>";
                  }
                ?>
              </select>
            </div>
          </div><!-- /.form-group -->

          <div class="form-group">
            <label for="bulan_awal_bimbingan" class="control-label col-lg-2">Bulan Awal Bimbingan </label>
            <div class="col-lg-10">
              <input id="tgl3" type="text" name="bulan_awal_bimbingan" placeholder="Bulan Awal Bimbingan" class="form-control" >
            </div>
          </div><!-- /.form-group -->

          <div class="form-group">
            <label for="bulan_akhir_bimbingan" class="control-label col-lg-2">Bulan Akhir Bimbingan </label>
            <div class="col-lg-10">
              <input id="tgl4" type="text" name="bulan_akhir_bimbingan" placeholder="Bulan Akhir Bimbingan" class="form-control" >
            </div>
          </div><!-- /.form-group -->

          <input type="hidden" value="<?=$data_edit->id_ta;?>" name="id" >

          <div class="form-group">
            <div class="col-lg-12">
              <div class="modal-footer"> <button type="submit" class="btn btn-primary"><?php echo $lang["submit_button"];?></button>
              <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang["cancel_button"];?></button>
              </div>
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
                format: "yyyy-mm-dd",
            });

            //trigger validation onchange
            $('select').on('change', function() {
                $(this).valid();
            });
            //hidden validate because we use chosen select
            $.validator.setDefaults({ ignore: ":hidden:not(select)" });

          $("#edit_data_sk").validate({
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

                id_jenis_keluar: {
                required: true,
                //minlength: 2
                },

                tanggal_keluar: {
                required: true,
                //minlength: 2
                },

                sk_yudisium: {
                required: true,
                //minlength: 2
                },

                tgl_sk_yudisium: {
                required: true,
                //minlength: 2
                },

                jalur_skripsi: {
                required: true,
                //minlength: 2
                },

              },
               messages: {

                id_jenis_keluar: {
                required: "This field is required",
                //minlength: "Your username must consist of at least 2 characters"
                },

                tanggal_keluar: {
                required: "This field is required",
                //minlength: "Your username must consist of at least 2 characters"
                },

                sk_yudisium: {
                required: "This field is required",
                //minlength: "Your username must consist of at least 2 characters"
                },

                tgl_sk_yudisium: {
                required: "This field is required",
                //minlength: "Your username must consist of at least 2 characters"
                },

                jalur_skripsi: {
                required: "This field is required",
                //minlength: "Your username must consist of at least 2 characters"
                },

              },

              submitHandler: function(form) {
                  $("#loadnya").show();
                  $(form).ajaxSubmit({
                      type: "POST",
                      url: $(this).attr("action"),
                      data: $("#edit_data_sk").serialize(),
                      success: function(data) {
                          $('#modal_data_sk').modal('hide');
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
