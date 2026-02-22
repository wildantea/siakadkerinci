<?php
session_start();
include "../../inc/config.php";
?>
<style type="text/css">
    .datepicker {
        z-index: 1200 !important;
    }
</style>
<div class="alert alert-danger error_data" style="display:none">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <span class="isi_warning"></span>
</div>
<form id="input_data_puasa" method="post" class="form-horizontal foto_banyak"
    action="<?= base_admin(); ?>modul/rekap_presensi/rekap_presensi_action.php?act=jadwal_puasa">

    <?php
    if (hasFakultas()) {
        ?>
        <div class="form-group">
            <label for="Semester" class="control-label col-lg-2">Fakultas</label>
            <div class="col-lg-5">
                <select id="fakultas_puasa" name="fakultas_puasa" data-placeholder="Pilih Fakultas ..."
                    class="form-control chzn-select" tabindex="2" required="">
                    <?php
                    loopingFakultas('filter_kelas');
                    ?>
                </select>
            </div>
        </div><!-- /.form-group -->
        <?php
    }
    ?>

    <div class="form-group">
        <label for="Semester" class="control-label col-lg-2">Program Studi</label>
        <div class="col-lg-5">
            <select id="jur_puasa" name="jur_puasa" data-placeholder="Pilih Semester ..."
                class="form-control chzn-select" tabindex="2" required="">
                <?php
                if ($_SESSION['group_level'] == 'dosen') {
                    $q = $db->query("select k.kelas_id,k.kls_nama, m.nama_mk,m.kode_mk,ku.kode_jur,
                                   j.nama_jur from kelas k join dosen_kelas dk on k.kelas_id=dk.id_kelas inner
                                    join dosen ds on ds.nip=dk.id_dosen inner join matkul m on m.id_matkul=k.id_matkul
                                    inner join kurikulum ku on ku.kur_id=m.kur_id inner join jurusan j on
                                    j.kode_jur=ku.kode_jur where dk.id_dosen='" . $_SESSION['username'] . "' group by ku.kode_jur");
                    foreach ($q as $k) {
                        echo "<option value='$k->kode_jur'>$k->nama_jur</option>";
                    }
                } else {
                    looping_prodi();
                }
                ?>
            </select>
        </div>
    </div><!-- /.form-group -->
    <div class="form-group">
        <label for="Semester" class="control-label col-lg-2">Semester</label>
        <div class="col-lg-5">
            <select id="sem_puasa" name="sem_puasa" data-placeholder="Pilih Semester ..."
                class="form-control chzn-select" tabindex="2" required="">
                <option value="<?= get_sem_aktif() ?>">
                    <?= get_tahun_akademik(get_sem_aktif()); ?>
                </option>
            </select>

        </div>
    </div><!-- /.form-group -->
    <div class="form-group">
        <label for="Semester" class="control-label col-lg-2">Matakuliah</label>
        <div class="col-lg-5">
            <select id="matkul_puasa" name="matkul_puasa" data-placeholder="Pilih Semester ..."
                class="form-control chzn-select" tabindex="2">
                <?php
                looping_matkul_kelas();
                ?>
            </select>
        </div>
    </div><!-- /.form-group -->
    <div class="form-group">
        <label for="Semester" class="control-label col-lg-2">Hari</label>
        <div class="col-lg-3">
            <select id="hari_puasa" name="hari_puasa" data-placeholder="Pilih Hari ..." class="form-control"
                tabindex="2">
                <option value="all">Semua</option>
                <?php
                $array_hari = array(
                    'senin' => 'Senin',
                    'selasa' => 'Selasa',
                    'rabu' => 'Rabu',
                    'kamis' => 'Kamis',
                    'jumat' => 'Jumat',
                    'sabtu' => 'Sabtu',
                    'minggu' => 'Minggu'
                );
                foreach ($array_hari as $h => $hari) {
                    echo "<option value='$h'>$hari</option>";
                }
                ?>
            </select>
        </div>
    </div><!-- /.form-group -->

    <div class="form-group">
        <label class="control-label col-lg-2">Mulai Pertemuan</label>
        <div class="col-lg-8">
            <select name="mulai_pertemuan" class="form-control" required style="width:200px; display:inline-block">
                <?php for ($i = 1; $i <= 16; $i++)
                    echo "<option value='$i'>Pertemuan $i</option>"; ?>
            </select>
            <p class="help-block">Range pertemuan yang akan disesuaikan waktunya.</p>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-lg-2">Sampai Pertemuan</label>
        <div class="col-lg-8">
            <select name="sampai_pertemuan" class="form-control" required style="width:200px; display:inline-block">
                <?php for ($i = 1; $i <= 16; $i++)
                    echo "<option value='$i' " . ($i == 16 ? 'selected' : '') . ">Pertemuan $i</option>"; ?>
            </select>
        </div>
    </div>


    <div class="form-group">
        <div class="col-lg-12">
            <div class="modal-footer"> <button type="submit" class="btn btn-warning"><i class="fa fa-clock-o"></i>
                    Sesuaikan Jam Bulan Puasa</button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i>
                    Tutup</button>
            </div>
        </div>
    </div><!-- /.form-group -->

</form>
<script type="text/javascript">
    $(document).ready(function () {
        //chosen select
        $(".chzn-select").chosen();
        $(".chzn-select-deselect").chosen({
            allow_single_deselect: true
        });

        //trigger validation onchange
        $('select').on('change', function () {
            $(this).valid();
        });
        //hidden validate because we use chosen select
        $.validator.setDefaults({ ignore: ":hidden:not(select)" });

        $("#sem_puasa").change(function () {
            if ($("#jur_puasa").val() != "" && $("#sem_puasa").val() != "") {
                $.ajax({
                    url: "<?= base_admin(); ?>modul/kelas_jadwal/get_matkul.php",
                    type: "POST",
                    data: { jur_filter: $("#jur_puasa").val(), sem_filter: $("#sem_puasa").val() },
                    success: function (data) {
                        $("#matkul_puasa").html(data);
                        $("#matkul_puasa").trigger("chosen:updated");
                    }
                });
            }
        });
        $("#fakultas_puasa").change(function () {
            $.ajax({
                type: "post",
                url: "<?= base_admin(); ?>modul/kelas_jadwal/get_prodi.php",
                data: { id_fakultas: this.value },
                success: function (data) {
                    $("#jur_puasa").html(data);
                    $("#jur_puasa").trigger("chosen:updated");

                }
            });
        });
        $("#jur_puasa").change(function () {
            if ($("#jur_puasa").val() != "" && $("#sem_puasa").val() != "") {
                $.ajax({
                    url: "<?= base_admin(); ?>modul/kelas_jadwal/get_matkul.php",
                    type: "POST",
                    data: { jur_filter: $("#jur_puasa").val(), sem_filter: $("#sem_puasa").val() },
                    success: function (data) {
                        $("#matkul_puasa").html(data);
                        $("#matkul_puasa").trigger("chosen:updated");
                    }
                });
            }
        });

        $("#input_data_puasa").validate({
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

            submitHandler: function (form) {
                $("#loadnya").show();
                $(form).ajaxSubmit({
                    url: $(this).attr("action"),
                    dataType: "json",
                    type: "post",
                    error: function (data) {
                        $("#loadnya").hide();
                        $(".isi_warning").text(data.responseText);
                        $(".error_data").focus()
                        $(".error_data").fadeIn();
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
                                $(".notif_top_up").focus()
                                $(".notif_top_up").fadeIn();
                            } else if (responseText[index].status == "good") {
                                $('#modal_data_pegawai').modal('hide');
                                $(".error_data").hide();
                                $(".notif_top_up").fadeIn(1000);
                                $(".notif_top_up").fadeOut(1000, function () {
                                    location.reload();
                                });
                            } else {
                                console.log(responseText);
                                $(".isi_warning").text(responseText[index].error_message);
                                $(".notif_top_up").focus()
                                $(".notif_top_up").fadeIn();
                            }
                        });
                    }
                });
            }
        });
    });
</script>