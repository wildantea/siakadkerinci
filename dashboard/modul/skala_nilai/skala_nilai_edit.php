<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>Skala Nilai</h1>
  <ol class="breadcrumb">
    <li>
      <a href="<?= base_index(); ?>"><i class="fa fa-dashboard"></i> Home</a>
    </li>
    <li>
      <a href="<?= base_index(); ?>skala-nilai">Skala Nilai</a>
    </li>
    <li class="active">Edit Skala Nilai</li>
  </ol>
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-lg-12">
      <div class="box box-solid box-primary">
        <div class="box-header">
          <h3 class="box-title">Edit Skala Nilai</h3>
          <div class="box-tools pull-right">
            <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-pencil"></i></button>
          </div>
        </div>
        <div class="box-body">
          <div class="alert alert-danger error_data" style="display:none">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <span class="isi_warning"></span>
          </div>
          <form id="edit_skala_nilai" method="post" class="form-horizontal"
            action="<?= base_admin(); ?>modul/skala_nilai/skala_nilai_action.php?act=up">

            <div class="form-group">
              <label for="Nilai Huruf " class="control-label col-lg-2">Nilai Huruf <span
                  style="color:#FF0000">*</span></label>
              <div class="col-lg-10">
                <input type="text" name="nilai_huruf" value="<?= $data_edit->nilai_huruf; ?>" class="form-control"
                  required>
              </div>
            </div><!-- /.form-group -->

            <div class="form-group">
              <label for="Nilai Indeks" class="control-label col-lg-2">Nilai Indeks <span
                  style="color:#FF0000">*</span></label>
              <div class="col-lg-10">
                <input type="text" name="nilai_indeks" value="<?= $data_edit->nilai_indeks; ?>" class="form-control"
                  required>
              </div>
            </div><!-- /.form-group -->

            <div class="form-group">
              <label for="Bobot Nilai Minimum" class="control-label col-lg-2">Bobot Nilai Minimum <span
                  style="color:#FF0000">*</span></label>
              <div class="col-lg-10">
                <input type="text" name="bobot_nilai_min" value="<?= $data_edit->bobot_nilai_min; ?>"
                  class="form-control" required>
              </div>
            </div><!-- /.form-group -->

            <div class="form-group">
              <label for="Bobot Nilai Maksimum" class="control-label col-lg-2">Bobot Nilai Maksimum <span
                  style="color:#FF0000">*</span></label>
              <div class="col-lg-10">
                <input type="text" name="bobot_nilai_maks" value="<?= $data_edit->bobot_nilai_maks; ?>"
                  class="form-control" required>
              </div>
            </div><!-- /.form-group -->

            <div class="form-group">
              <label for="Tanggal Mulai Efektif " class="control-label col-lg-2">Tanggal Mulai Efektif <span
                  style="color:#FF0000">*</span></label>
              <div class="col-lg-3">
                <div class="input-group date" id="tgl1">
                  <input type="text" class="form-control" value="<?= $data_edit->tgl_mulai_efektif; ?>"
                    name="tgl_mulai_efektif" required />
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                </div>
              </div>
            </div><!-- /.form-group -->

            <div class="form-group">
              <label for="Tanggal Akhir Efektif " class="control-label col-lg-2">Tanggal Akhir Efektif <span
                  style="color:#FF0000">*</span></label>
              <div class="col-lg-3">
                <div class="input-group date" id="tgl2">
                  <input type="text" class="form-control" value="<?= $data_edit->tgl_akhir_efektif; ?>"
                    name="tgl_akhir_efektif" required />
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                </div>
              </div>
            </div><!-- /.form-group -->
            <div class="form-group">
              <label for="Progra Studi" class="control-label col-lg-2">Program Studi <span
                  style="color:#FF0000">*</span></label>
              <div class="col-lg-10">
                <select id="kode_jurusan" name="kode_jurusan" data-placeholder="Pilih Progra Studi..."
                  class="form-control chzn-select" tabindex="2" required>
                  <option value=""></option>
                  <?php foreach ($db->fetch_all("view_prodi_jenjang") as $isi) {

                    if ($data_edit->kode_jurusan == $isi->kode_jur) {
                      echo "<option value='$isi->kode_jur' selected>$isi->jurusan</option>";
                    } else {
                      echo "<option value='$isi->kode_jur'>$isi->jurusan</option>";
                    }
                  } ?>
                </select>
              </div>
            </div><!-- /.form-group -->

            <div class="form-group">
              <label for="Tagihan Untuk Angkatan" class="control-label col-lg-2">Skala Untuk Angkatan <span
                  style="color:#FF0000">*</span></label>
              <div class="col-lg-10">

                <select id="berlaku_angkatan" name="berlaku_angkatan" data-placeholder="Pilih Jenis Tagihan..."
                  class="form-control chzn-select" tabindex="2">
                  <option value='' selected>Null</option>
                  <?php
                  $angkatan_exist = $db->query("select mahasiswa.mulai_smt,view_semester.angkatan from mahasiswa inner join view_semester 
on mahasiswa.mulai_smt=view_semester.id_semester
group by mahasiswa.mulai_smt
order by mulai_smt desc");

                  foreach ($angkatan_exist as $ak) {
                    if ($ak->mulai_smt == $data_edit->berlaku_angkatan) {
                      echo "<option value='$ak->mulai_smt' selected>$ak->angkatan</option>";
                    } else {
                      echo "<option value='$ak->mulai_smt'>$ak->angkatan</option>";
                    }

                  }
                  ?>
                </select>
              </div>
            </div><!-- /.form-group -->



            <input type="hidden" name="id" value="<?= $data_edit->id; ?>">
            <div class="form-group">
              <label for="tags" class="control-label col-lg-2">&nbsp;</label>
              <div class="col-lg-10">
                <a href="<?= base_index(); ?>skala-nilai" class="btn btn-default "><i class="fa fa-step-backward"></i>
                  <?php echo $lang["back_button"]; ?></a>
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>
                  <?php echo $lang["submit_button"]; ?></button>
              </div>
            </div><!-- /.form-group -->
          </form>
        </div>
      </div>
    </div>
</section><!-- /.content -->

<script type="text/javascript">
  $(document).ready(function () {

    $("#tgl1").datepicker({
      format: "yyyy-mm-dd",
      autoclose: true,
      todayHighlight: true
    }).on("change", function () {
      $("#tgl1 :input").valid();
    });
    $("#tgl2").datepicker({
      format: "yyyy-mm-dd",
      autoclose: true,
      todayHighlight: true
    }).on("change", function () {
      $("#tgl2 :input").valid();
    });
    $("#tgl2").datepicker({
      format: "yyyy-mm-dd",
      autoclose: true,
      todayHighlight: true
    }).on("change", function () {
      $("#tgl2 :input").valid();
    });

    //trigger validation onchange
    $('select').on('change', function () {
      $(this).valid();
    });
    //hidden validate because we use chosen select
    $.validator.setDefaults({ ignore: ":hidden:not(select)" });

    $("#edit_skala_nilai").validate({
      errorClass: "help-block",
      errorElement: "span",
      highlight: function (element, errorClass, validClass) {
        $(element).parents(".form-group").removeClass(
          "has-success").addClass("has-error");
      },
      unhighlight: function (element, errorClass, validClass) {
        $(element).parents(".form-group").removeClass(
          "has-error").addClass("has-success");
      },
      errorPlacement: function (error, element) {
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

        nilai_huruf: {
          required: true,
          //minlength: 2
        },

        nilai_indeks: {
          required: true,
          //minlength: 2
        },

        bobot_nilai_min: {
          required: true,
          //minlength: 2
        },

        bobot_nilai_maks: {
          required: true,
          //minlength: 2
        },

        tgl_mulai_efektif: {
          required: true,
          //minlength: 2
        },

        tgl_akhir_efektif: {
          required: true,
          //minlength: 2
        },

        kode_jurusan: {
          required: true,
          //minlength: 2
        },

      },
      messages: {

        nilai_huruf: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
        },

        nilai_indeks: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
        },

        bobot_nilai_min: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
        },

        bobot_nilai_maks: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
        },

        tgl_mulai_efektif: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
        },

        tgl_akhir_efektif: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
        },

        kode_jurusan: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
        },

      },


      submitHandler: function (form) {
        $("#loadnya").show();
        $(form).ajaxSubmit({
          url: $(this).attr("action"),
          dataType: "json",
          type: "post",
          error: function (data) {
            $("#loadnya").hide();
            console.log(data);
          },
          success: function (responseText) {
            $("#loadnya").hide();
            console.log(responseText);
            $.each(responseText, function (index) {
              console.log(responseText[index].status);
              if (responseText[index].status == "die") {
                $("#informasi").modal("show");
              } else if (responseText[index].status == "error") {
                $(".isi_warning").text(responseText[index].error_message);
                $(".error_data").focus()
                $(".error_data").fadeIn();
              } else if (responseText[index].status == "good") {
                $(".error_data").hide();
                $(".notif_top_up").fadeIn(1000);
                $(".notif_top_up").fadeOut(1000, function () {
                  window.history.back();
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