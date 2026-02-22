<section class="content-header">
  <h1>Input Nilai Perkelas</h1>
  <ol class="breadcrumb">
    <li><a href="<?= base_index(); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="<?= base_index(); ?>nilai">Nilai</a></li>
    <li class="active">Input Nilai Perkelas</li>
  </ol>
</section>
<style type="text/css">
  .error {
    color: #f00;
  }

  .modal-edit-nilai {
    width: 90%;
    min-height: 40%;
    margin: auto auto;
    padding: 0;
  }

  .modal-content-nilai {
    height: auto;
    min-height: 90%;
    border-radius: 0;
  }
</style>
<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-body table-responsive">
          <a href="<?= base_index(); ?>nilai" class="btn btn-default" id="btn-simpan"><i class="fa fa-backward"></i>
            Kembali</a>
          <br><br>
          <form method="POST" action="<?= base_url() ?>dashboard/modul/nilai/nilai_action.php?act=input_nilai_coba"
            id="form_input_nilai">

            <table class="table table-bordered table-striped">

              <tbody>
                <tr>
                  <td>Program Studi </td>
                  <td> <?= $kelas_attribute->jurusan; ?></td>
                  <td>Periode Semester </td>
                  <!--  <td colspan="3">:  2017/2018 Genap</td>-->
                  <td colspan="9"> <?= get_tahun_akademik($kelas_attribute->sem_id); ?> </td>
                </tr>
                <tr>
                  <td>Matakuliah</td>
                  <td> <?= $kelas_attribute->nm_matkul; ?></td>
                  <td> Nama Kelas</td>
                  <td> <?= $kelas_attribute->kls_nama; ?></td>
                </tr>
                <tr>
                  <?php
                  $dosen_pengajar = $db->fetch_single_row("view_dosen_kelas", "id_kelas", $id_kelas);
                  $pengampu = explode("#", $dosen_pengajar->nama_dosen);
                  $dosen_pengampu = implode("<br>- ", $pengampu);
                  ?>
                  <td width="20%">Dosen Pengampu </td>
                  <td colspan="5"> - <?= $dosen_pengampu ?></td>
                </tr>

                <tr>
                  <th style="text-align: right;vertical-align: middle;">Komponen Penilaian</th>
                  <th>
                    <?php
                    $button_komponen = "Edit Prosentase";
                    $fa_komponen = "fa-pencil";
                    if ($kelas_attribute->ada_komponen == 'Y') {
                      $button_komponen = "Edit";
                      $fa_komponen = "fa-pencil";
                    }
                    ?>
                    <span type="submit" class="btn btn-success tambah-komponen" data-toggle="tooltip"
                      data-id="<?= $id_kelas; ?>" data-title="<?= $button_komponen; ?> Komponen Penilaian"><i
                        class="fa <?= $fa_komponen; ?>"></i> <?= $button_komponen; ?></span>
                  </th>
                </tr>
                <?php
                $prosentase = 0;
                $kelas_data = $db2->fetchSingleRow("kelas", "kelas_id", $id_kelas);
                if ($kelas_data->ada_komponen == 'Y') {
                  $komponen = json_decode($kelas_data->komponen);
                  foreach ($komponen as $key) {
                    if (is_array($key)) {
                      foreach ($key as $val) {
                        ?>
                        <tr class='komponen_list_<?= $val->id; ?>'>
                          <td style="text-align:right;vertical-align: middle;font-weight: bold"><?= $val->nama_komponen; ?></td>
                          <td>
                            <div class='input-group' style='width:100px'> <input type='text' class='form-control' readonly=""
                                value='<?= $val->value_komponen; ?>'> <span class='input-group-addon'> % </span> </div>
                          </td>
                        </tr>
                        <?php
                      }
                    }
                  }
                  $prosentase = $komponen->total_prosentase;

                }

                ?>
              </tbody>
            </table>

            <div class="lead">
              <button class="btn btn-primary edit-nilai" disabled="" style="display: none;"><i class="fa fa-pencil"></i>
                Edit Nilai</button>
              Silakan Checklist Nilai Yang akan di Ubah
            </div>

            <p>
              <!--               
            <span class="lead"><button type="submit" class="btn btn-success simpan-nilai" disabled=""><i class="fa fa-check"></i> Simpan</button> <a href="<?= base_index(); ?>nilai" class="btn btn-warning"><i class="fa fa-reply"></i> Batal</a>  Silakan Checklist Nilai Yang akan di Ubah</span>
            <p> -->

              <input type="hidden" name="jur" value="<?= en($jur) ?>">
            <table class="table table-bordered table-striped">

              <thead>
                <tr class="bg-light-blue color-palette">
                  <th rowspan="2"
                    style="padding-right:0;width: 7%;text-align: center;vertical-align: middle;background: #ffffff;color:#000">
                    <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline "> <input type="checkbox"
                        class="group-checkable bulk-check"> <span></span></label>No
                  </th>
                  <th rowspan="2" style="text-align: center;vertical-align: middle">NIM</th>
                  <th rowspan="2" style="text-align: center;vertical-align: middle">Nama</th>
                  <th rowspan="2" style="text-align: center;vertical-align: middle">Angkatan</th>
                  <th colspan="2" style="text-align: center;vertical-align: middle">Nilai</th>
                </tr>
                <tr class="bg-light-blue color-palette">
                  <th rowspan="2" style="text-align: center;vertical-align: middle">Nilai Angka</th>
                  <th rowspan="2" style="text-align: center;vertical-align: middle">Nilai Huruf</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $no = 1;
                $array_skala = array();
                $skala_nilai = $db->query("select * from skala_nilai where kode_jurusan=?", array('kode_jurusan' => $kelas_attribute->kode_jur));
                foreach ($skala_nilai as $skala) {
                  $array_skala[$skala->nilai_huruf . "#" . $skala->nilai_indeks] = $skala->nilai_huruf . " (" . $skala->nilai_indeks . ")";
                }
                $nilai_data = $db->query("
SELECT 
  id_krs_detail,

  CASE
    -- =============================
    -- SEMESTER LAMA → nilai_angka
    -- =============================
    WHEN {$kelas_attribute->sem_id} <= '20251'
    THEN
      CASE 
        WHEN TRIM(nilai_angka) IS NULL OR TRIM(nilai_angka) = '' 
        THEN 1 ELSE 0 
      END

    -- =============================
    -- SEMESTER BARU & MK REGULER → komponen
    -- =============================
    WHEN {$kelas_attribute->sem_id} > '20251'
     AND '{$kelas_attribute->id_tipe_matkul}' != 'S'
     AND '{$kelas_attribute->nm_matkul}' NOT REGEXP '(?i)skrip|tesis|kkn|ppl|pengabdian'
    THEN
      CASE
        WHEN
          komponen_nilai IS NULL
          OR komponen_nilai = ''
          OR komponen_nilai = '[]'
          OR (
            komponen_nilai REGEXP '\"id\":\"2\",\"nilai\":\"0\"'
            AND komponen_nilai REGEXP '\"id\":\"3\",\"nilai\":\"0\"'
            AND komponen_nilai REGEXP '\"id\":\"4\",\"nilai\":\"0\"'
            AND komponen_nilai REGEXP '\"id\":\"5\",\"nilai\":\"0\"'
            AND komponen_nilai REGEXP '\"id\":\"6\",\"nilai\":\"0\"'
          )
        THEN 1 ELSE 0
      END

    -- =============================
    -- SEMESTER BARU & NON REGULER
    -- =============================
    ELSE
      CASE 
        WHEN TRIM(nilai_angka) IS NULL OR TRIM(nilai_angka) = '' 
        THEN 1 ELSE 0 
      END
  END AS belum_dinilai,

  nilai_angka,
  nilai_huruf,
  bobot,
  krs_detail.nim,
  nama,
  LEFT(mulai_smt,4) AS angkatan,
  mulai_smt

FROM krs_detail
INNER JOIN mahasiswa USING(nim)
WHERE id_kelas = ?
  AND disetujui = '1'
ORDER BY nama ASC
", [$id_kelas]);

                foreach ($nilai_data as $nilai) {
                  $bobot = $nilai->nilai_huruf . "#" . $nilai->bobot;
                  $bg_color = "#eee";
                  if ($nilai->belum_dinilai == '1') {
                    $bg_color = "#cd8d8d";
                  }
                  ?>
                  <tr class="row-data" style="background: <?= $bg_color; ?>">
                    <td style="background: #fff;text-align: center;vertical-align: middle"><label
                        class="mt-checkbox mt-checkbox-single mt-checkbox-outline"> <input type="checkbox"
                          class="group-checkable check-selected" data-id="<?= $nilai->id_krs_detail; ?>">
                        <span></span></label><?= $no; ?></td>
                    <td style="vertical-align: middle"><?= $nilai->nim; ?></td>
                    <td style="vertical-align: middle"><?= $nilai->nama; ?></td>
                    <td style="vertical-align: middle"><?= $nilai->angkatan; ?></td>
                    <input type="hidden" name="nim[<?= $nilai->id_krs_detail; ?>]" value="<?= $nilai->nim; ?>">
                    <td><input type="text" name="nilai_angka[<?= $nilai->id_krs_detail; ?>]" disabled=""
                        value="<?= str_replace(".", ",", $nilai->nilai_angka); ?>"
                        onkeydown="return onlyNumber(event,this,true,false)" class="form-control nilai_angka"
                        data-rule-decimalSeparator="100" data-msg-decimalSeparator="Nilai tidak boleh lebih dari 100"
                        maxlength="5" size="5" data-angkatan="<?= $nilai->mulai_smt; ?>"
                        data-jurusan="<?= $kelas_attribute->kode_jur; ?>"></td>
                    <td>
                      <select name="nilai_huruf[<?= $nilai->id_krs_detail; ?>]" class="form-control nilai_huruf"
                        disabled="">
                        <option value=""> </option>
                        <?php
                        foreach ($array_skala as $id_skala => $skala_bobot) {
                          if ($bobot == $id_skala) {
                            echo "<option value='$id_skala' selected>$skala_bobot</option>";
                          }/* else {
                         echo "<option value='$id_skala'>$skala_bobot</option>";
                       }*/

                        }
                        ?>
                      </select>
                    </td>
                  </tr>
                  <?php
                  $no++;
                }
                ?>
              </tbody>

            </table>
            <span class="lead"><span class="btn btn-primary edit-nilai" disabled="" style="display:none"><i
                  class="fa fa-pencil"></i> Edit Nilai</span> Silakan Checklist Nilai Yang akan di Ubah</span>
            <p>
          </form>
        </div>

      </div>
    </div>
  </div>
  <div class="modal fade" id="modal_komponen_penilaian" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
              aria-hidden="true"><i class="glyphicon glyphicon-remove"></i></span></button>
          <h4 class="modal-title">Pengaturan Komponen Penilaian</h4>
        </div>
        <div class="modal-body" id="isi_komponen_penilaian"> </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div>
  <div class="modal" id="modal_edit_nilai" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg modal-edit-nilai">
      <div class="modal-content modal-content-nilai">
        <div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
              aria-hidden="true"><i class="glyphicon glyphicon-remove"></i></span></button>
          <h4 class="modal-title">Input/Edit Nilai Perkuliahan</h4>
        </div>
        <div class="modal-body" id="isi_edit_nilai"> </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div>
</section>
<script type="text/javascript">
  $(".tambah-komponen").click(function (event) {
    $("#loadnya").show();
    event.preventDefault();
    var currentBtn = $(this);

    id = currentBtn.attr('data-id');

    $.ajax({
      url: "<?= base_admin(); ?>modul/nilai_per_kelas/komponen/komponen_view.php",
      type: "post",
      data: { kelas_id: id },
      success: function (data) {
        $("#isi_komponen_penilaian").html(data);
        $("#loadnya").hide();
      }
    });

    $('#modal_komponen_penilaian').modal({ keyboard: false, backdrop: 'static' });

  });

  $(".edit-nilai").click(function (event) {
    $("#loadnya").show();
    event.preventDefault();
    var data_array_id = check_selected();
    var all_ids = data_array_id.toString();
    $.ajax({
      url: "<?= base_admin(); ?>modul/nilai_per_kelas/edit_nilai_with_komponen.php",
      type: "post",
      data: { kelas_id: <?= $kelas_data->kelas_id; ?>, nilai_id: all_ids },
      success: function (data) {
        $("#isi_edit_nilai").html(data);
        $("#loadnya").hide();
      }
    });

    $('#modal_edit_nilai').modal({ keyboard: false, backdrop: 'static' });

  });


  function jml_checked() {
    var jumlah = $('.check-selected:checked').length;
    if (jumlah > 0) {
      $(".edit-nilai").show();
      $(".edit-nilai").attr("disabled", false);
      $("#focus-button").addClass('glow');
    } else {
      $(".edit-nilai").hide();
      $(".edit-nilai").attr("disabled", true);
      $("#focus-button").removeClass('glow');
    }
  }
  function findTotal() {
    var arr = document.getElementsByClassName('prosentase');
    var tot = 0;
    for (var i = 0; i < arr.length; i++) {
      if (parseInt(arr[i].value))
        tot += parseInt(arr[i].value);
    }
    document.getElementById('total_prosentase').value = tot;
  }
  function check_selected() {
    var table_select = $('.check-selected:checked');
    var array_data_delete = [];
    table_select.each(function () {
      var check_data = $(this).attr('data-id');
      if (typeof check_data != 'undefined') {
        array_data_delete.push(check_data)
      }
    });
    return array_data_delete
  }
  $(document).ready(function () {

    $('.bulk-check').click(function () {
      if (!$(this).is(':checked')) {
        $('.check-selected').prop('checked', false);
        /*        $('.nilai_angka').prop("disabled", true);
                //$('.nilai_angka').val('');
                $('.nilai_angka').attr("required", false);
                $('.nilai_huruf').attr("disabled", true); 
                $('.nilai_huruf').val('').attr("required", false);*/
        $('.row-data').css("background-color", "#eee");
      } else {
        /*         $('.nilai_angka').prop("disabled", false);
                 $('.nilai_angka').attr("required", true);
                 $('.nilai_huruf').attr("disabled", false); 
                 $('.nilai_huruf') .attr("required", true);*/
        $('.check-selected').prop('checked', true);
        $('.row-data').css("background-color", "#ffffff");
      }
      jml_checked();

    });

    $('.check-selected').click(function () {
      if (!$(this).is(':checked')) {
        $(this).closest('tr').css("background-color", "#eee");
        jml_checked();
      } else {
        $(this).closest('tr').css("background-color", "#ffffff");
        jml_checked();
      }
    });

  });

  function onlyNumber(e, t, n, a) {
    var o = e.keyCode || e.which,
      i = t.value;
    return o > 57 && 96 > o || o > 105 || 32 == o ? 188 == o && n ? "" == i ? !1 : i.indexOf(",") > -1 ? !1 : !0 : a && (110 == o || 190 == o) || 116 == o ? !0 : !1 : void 0
  }


  $(".nilai_angka").blur(function () {
    var btn = $(this);
    jurusan = btn.attr('data-jurusan');
    nilai_huruf = $(this).closest('td').siblings().find('.nilai_huruf');
    $.ajax({
      type: "post",
      url: "<?= base_admin(); ?>modul/nilai_per_kelas/get_nilai_huruf.php",
      data: { nilai: this.value, kode_jurusan: jurusan },
      success: function (data) {
        nilai_huruf.html(data);
      }
    });

  });

  $(document).ready(function () {
    $.validator.addMethod("decimalSeparator", function (value, element, param) {
      if (value.includes(",")) {
        value = value.replace(",", ".");
      }
      return this.optional(element) || parseFloat(value) <= param;
    }, "Nilai tidak boleh lebih dari {0}.");

    $("#form_input_nilai").validate({
      submitHandler: function (form) {
        $("#loadnya").show();
        $(form).ajaxSubmit({
          url: $(this).attr("action"),
          dataType: "json",
          type: "post",
          error: function (data) {
            $("#loadnya").hide();
            //console.log(data); 
            $(".isi_warning").html(data.responseText);
            $(".error_data").focus()
            $(".error_data").fadeIn();
          },
          success: function (responseText) {
            $("#loadnya").hide();
            console.log(responseText);
            $.each(responseText, function (index) {
              console.log(responseText[index].status);
              if (responseText[index].status == "die") {
                $("#informasi").modal("show");
              } else {
                $(".save-nilai").attr("disabled", "disabled");
                $(".error_data").hide();
                $(".notif_top_up").fadeIn(1000);
                $(".notif_top_up").fadeOut(1000, function () {
                  location.reload();
                });
              }
            });
          }

        });
      }
    });

  });
</script>