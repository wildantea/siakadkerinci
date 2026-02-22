<?php
include "../../inc/config.php";
?>
<style type="text/css"> .datepicker {z-index: 1200 !important; } </style>
      <form id="input_data_yudisium" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/data_yudisium/data_yudisium_action.php?act=in">

              <div class="form-group">
                <label for="nim" class="control-label col-lg-2">NIM <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="nim" placeholder="nim" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              <div class="form-group">
                        <label for="kode_fak" class="control-label col-lg-2">Fakultas <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-10">
            <select name="kode_fak" data-placeholder="Pilih Fakultas ..." class="form-control chzn-select" tabindex="2" required>
               <option value=""></option>
               <?php foreach ($db->fetch_all("fakultas") as $isi) {
                  echo "<option value='$isi->kode_fak'>$isi->nama_resmi</option>";
               } ?>
              </select>
            </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="kode_jurusan" class="control-label col-lg-2">Jurusan <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-10">
            <select name="kode_jurusan" data-placeholder="Pilih Jurusan..." class="form-control chzn-select" tabindex="2" required>
               <option value=""></option>
               <?php foreach ($db->fetch_all("jurusan") as $isi) {
                  echo "<option value='$isi->kode_jur'>$isi->nama_jur</option>";
               } ?>
              </select>
            </div>
                      </div><!-- /.form-group -->

              <div class="form-group">
                <label for="nama" class="control-label col-lg-2">Nama </label>
                <div class="col-lg-10">
                  <input type="text" name="nama" placeholder="nama" class="form-control" >
                </div>
              </div><!-- /.form-group -->

            <div class="form-group">
                <label for="id_jenis_keluar" class="control-label col-lg-2">Jenis Keluar <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <select name="id_jenis_keluar" data-placeholder="Pilih Jenis Keluar ..." class="form-control chzn-select" tabindex="2" required>
                    <option value=""></option>
                    <?php
                      foreach ($db->fetch_all('jenis_keluar') as $isi) {
                        echo "<option value='$isi->id_jns_keluar'>$isi->ket_keluar</option>";
                      }
                    ?>
                  </select>
                </div>
            </div><!-- /.form-group -->

              <div class="form-group">
                <label for="tanggal_keluar" class="control-label col-lg-2">Tanggal Keluar <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="date" name="tanggal_keluar" placeholder="Tanggal Keluar" class="form-control" required>
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
                  <input type="date" name="tgl_sk_yudisium" placeholder="Tgl SK Yudisium" class="form-control" required>
                </div>
              </div><!-- /.form-group -->

              <div class="form-group">
                <label for="ipk" class="control-label col-lg-2">IPK </label>
                <div class="col-lg-10">
                  <input type="text" name="ipk" placeholder="ipk" class="form-control" >
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
                    <option value=""></option>
                    <?php
                      foreach($db->fetch_all("jenis_skripsi") as $isi){
                        echo "<option value='$isi->id_jenis_skripsi'>$isi->ket_jenis_skripsi</option>";
                      }
                    ?>
                  </select>
                </div>
              </div><!-- /.form-group -->

              <div class="form-group">
                <label for="judul_skripsi" class="control-label col-lg-2">Judul Skripsi </label>
                <div class="col-lg-10">
                  <input type="text" name="judul_skripsi" placeholder="Judul Skripsi" class="form-control" >
                </div>
              </div><!-- /.form-group -->

              <div class="form-group">
                <label for="bulan_awal_bimbingan" class="control-label col-lg-2">Bulan Awal Bimbingan </label>
                <div class="col-lg-10">
                  <input type="date" name="bulan_awal_bimbingan" placeholder="Bulan Awal Bimbingan" class="form-control" >
                </div>
              </div><!-- /.form-group -->

              <div class="form-group">
                <label for="bulan_akhir_bimbingan" class="control-label col-lg-2">Bulan Akhir Bimbingan </label>
                <div class="col-lg-10">
                  <input type="date" name="bulan_akhir_bimbingan" placeholder="Bulan Akhir Bimbingan" class="form-control" >
                </div>
              </div><!-- /.form-group -->

              <div class="form-group">
                <label for="pembimbing_1" class="control-label col-lg-2">Pembimbing 1 <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <select name="pembimbing_1" data-placeholder="Pilih Pembimbing 1 ..." class="form-control chzn-select" tabindex="2" required>
                   <option value="NULL">NULL</option>
                   <?php foreach ($db->fetch_all("dosen") as $isi) {
                      if($isi->nidn != NULL) {
                        echo "<option value='$isi->nidn'>$isi->nama_dosen</option>";
                      }
                   } ?>
                  </select>
                </div>
              </div><!-- /.form-group -->

              <div class="form-group">
                <label for="pembimbing_2" class="control-label col-lg-2">Pembimbing 2 <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <select name="pembimbing_2" data-placeholder="Pilih Pembimbing 2 ..." class="form-control chzn-select" tabindex="2" required>
                   <option value="NULL">NULL</option>
                   <?php foreach ($db->fetch_all("dosen") as $isi) {
                      if($isi->nidn != NULL) {
                        echo "<option value='$isi->nidn'>$isi->nama_dosen</option>";
                      }
                   } ?>
                  </select>
                </div>
              </div><!-- /.form-group -->

              <div class="form-group">
                <label for="status_error" class="control-label col-lg-2">status_error </label>
                <div class="col-lg-10">
                  <input type="text" name="status_error" placeholder="status_error" class="form-control" >
                </div>
              </div><!-- /.form-group -->

              <div class="form-group">
                <label for="keterangan" class="control-label col-lg-2">Keterangan </label>
                <div class="col-lg-10">
                  <input type="text" name="keterangan" placeholder="keterangan" class="form-control" >
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

    $("#input_data_yudisium").validate({
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

          nim: {
          required: true,
          //minlength: 2
          },

          kode_fak: {
          required: true,
          //minlength: 2
          },

          kode_jurusan: {
          required: true,
          //minlength: 2
          },

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

          pembimbing_1: {
          required: true,
          //minlength: 2
          },

          pembimbing_2: {
          required: true,
          //minlength: 2
          },

        },
         messages: {

          nim: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },

          kode_fak: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },

          kode_jurusan: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },

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

          pembimbing_1: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },

          pembimbing_2: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },

        },

        submitHandler: function(form) {
            $("#loadnya").show();
            $(form).ajaxSubmit({
                type: "post",
                url: $(this).attr("action"),
                data: $("#input_data_yudisium").serialize(),
                success: function(data) {
                    $('#modal_data_yudisium').modal('hide');
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
