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

    <div class="form-group">
        <label for="ruang_puasa" class="control-label col-lg-2">Ruangan</label>
        <div class="col-lg-5">
            <select id="ruang_puasa" name="ruang_puasa" data-placeholder="Pilih Ruangan ..."
                class="form-control chzn-select" tabindex="2" required="">
                <option value="all">Semua Ruangan</option>
                <?php
                $ruang_list = $db->query("SELECT ruang_id, nm_ruang FROM ruang_ref WHERE is_aktif='Y' ORDER BY nm_ruang ASC");
                foreach ($ruang_list as $r) {
                    echo "<option value='{$r->ruang_id}'>{$r->nm_ruang}</option>";
                }
                ?>
            </select>
        </div>
    </div><!-- /.form-group -->

    <div class="form-group">
        <label for="sem_puasa" class="control-label col-lg-2">Semester</label>
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
        <label for="hari_puasa" class="control-label col-lg-2">Hari</label>
        <div class="col-lg-3">
            <select id="hari_puasa" name="hari_puasa" data-placeholder="Pilih Hari ..." class="form-control"
                tabindex="2">
                <option value="all">Semua</option>
                <?php
                $array_hari = [
                    'senin'  => 'Senin',
                    'selasa' => 'Selasa',
                    'rabu'   => 'Rabu',
                    'kamis'  => 'Kamis',
                    'jumat'  => 'Jumat',
                    'sabtu'  => 'Sabtu',
                    'minggu' => 'Minggu'
                ];
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
            <div class="modal-footer">
                <button type="submit" class="btn btn-warning"><i class="fa fa-clock-o"></i> Sesuaikan Jam Bulan Puasa</button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Tutup</button>
            </div>
        </div>
    </div><!-- /.form-group -->

</form>
<script type="text/javascript">
    $(document).ready(function () {
        $(".chzn-select").chosen();
        $(".chzn-select-deselect").chosen({ allow_single_deselect: true });
        $('select').on('change', function () { $(this).valid(); });
        $.validator.setDefaults({ ignore: ":hidden:not(select)" });

        $("#input_data_puasa").validate({
            errorClass: "help-block",
            errorElement: "span",
            highlight: function (element, errorClass, validClass) {
                $(element).parents(".form-group").removeClass("has-success").addClass("has-error");
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents(".form-group").removeClass("has-error").addClass("has-success");
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
                        $(".error_data").focus();
                        $(".error_data").fadeIn();
                        console.log(data);
                    },
                    success: function (responseText) {
                        $("#loadnya").hide();
                        console.log(responseText);
                        $.each(responseText, function (index) {
                            if (responseText[index].status == "die") {
                                $("#informasi").modal("show");
                            } else if (responseText[index].status == "error") {
                                $(".isi_warning").text(responseText[index].error_message);
                                $(".notif_top_up").focus();
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
                                $(".notif_top_up").focus();
                                $(".notif_top_up").fadeIn();
                            }
                        });
                    }
                });
            }
        });
    });
</script>