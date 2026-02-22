<?php
session_start();
include "../../inc/config.php";

$nilai_id_post = $_POST['nilai_id'];
$kelas_data = $db2->fetchCustomSingle("SELECT tdk.kelas_id, tdk.kls_nama, ada_komponen, komponen, vmk.kode_mk, tdk.sem_id, vmk.nama_mk, vmk.total_sks, vmk.kode_jur, vmk.nama_jurusan 
    FROM kelas tdk 
    INNER JOIN view_matakuliah_kurikulum vmk USING(id_matkul)
    WHERE kelas_id IN (SELECT id_kelas FROM krs_detail WHERE id_krs_detail = ?)", [$nilai_id_post]);


$list_komponen = ($kelas_data->ada_komponen == 'Y') ? json_decode($kelas_data->komponen)->komponen : [];

function get_nilai_absen_refactored($nim_user, $kelas_id)
{
    global $db2;
    $absen = $db2->query("SELECT isi_absensi FROM tb_data_kelas_absensi INNER JOIN tb_data_kelas_pertemuan USING(id_pertemuan) WHERE kelas_id = ?", [$kelas_id]);
    $hadir = 0;
    $total_pertemuan = $absen->rowCount();
    if ($total_pertemuan > 0) {
        foreach ($absen as $ab) {
            $data = json_decode($ab->isi_absensi);
            foreach ($data as $d) {
                if ($d->nim == $nim_user && $d->status_absen == 'Hadir')
                    $hadir++;
            }
        }
        return round(($hadir / $total_pertemuan) * 100, 2);
    }
    return 0;
}
?>

<style>
    .info-table {
        width: 100%;
        margin-bottom: 20px;
        border: 1px solid #ddd;
    }

    .info-table td {
        padding: 8px;
        border-bottom: 1px solid #eee;
    }

    .info-label {
        background: #f4f4f4;
        width: 150px;
        font-weight: bold;
    }

    .table-grade thead th {
        text-align: center;
        vertical-align: middle;
        background: #3c8dbc;
        color: #fff;
        font-size: 12px;
    }

    .input-nilai {
        text-align: center;
        font-weight: bold;
    }

    .final-grade {
        background: #eef9ff !important;
        font-weight: bold;
        text-align: center;
        color: #000;
    }

    .select-huruf {
        font-weight: bold;
    }
</style>

<div class="table-responsive">
    <table class="info-table">
        <tr>
            <td class="info-label">Program Studi</td>
            <td>: <?= $kelas_data->nama_jurusan ?></td>
        </tr>
        <tr>
            <td class="info-label">Periode</td>
            <td>: <?= getPeriode($kelas_data->sem_id) ?></td>
        </tr>
        <tr>
            <td class="info-label">Matakuliah</td>
            <td>: <?= $kelas_data->kode_mk ?> - <?= $kelas_data->nama_mk ?> (<?= $kelas_data->total_sks ?> SKS)</td>
        </tr>
        <tr>
            <td class="info-label">Kelas</td>
            <td>: <?= $kelas_data->kls_nama ?></td>
        </tr>
    </table>

    <form id="form_edit_nilai" method="POST"
        action="<?= base_url() ?>dashboard/modul/nilai_per_kelas/nilai_per_kelas_action.php?act=input_nilai_komponen_admin">
        <table class="table table-bordered table-striped table-hover table-grade">
            <thead>
                <tr>
                    <th rowspan="2" width="30">No</th>
                    <th rowspan="2" width="150">Mahasiswa</th>
                    <th colspan="<?= count($list_komponen) ?>">Komponen Nilai</th>
                    <th colspan="2">Hasil Akhir</th>
                </tr>
                <tr>
                    <?php foreach ($list_komponen as $k): ?>
                        <th width="80"><?= $k->nama_komponen ?><br>(<?= $k->value_komponen ?>%)</th>
                    <?php endforeach; ?>
                    <th width="80">Angka</th>
                    <th width="100">Huruf</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $nilai_data = $db2->query("SELECT kd.id_krs_detail, kd.komponen_nilai, kd.nilai_angka, kd.nilai_huruf, kd.bobot, m.nim, m.nama, m.mulai_smt ,m.jur_kode,m.mulai_smt
                                         FROM krs_detail kd INNER JOIN mahasiswa m USING(nim) 
                                         WHERE kd.id_krs_detail IN ($nilai_id_post) ORDER BY m.nim ASC");
                $no = 1;
                foreach ($nilai_data as $row):
                    $saved_komponen = json_decode($row->komponen_nilai, true) ?? [];
                    ?>
                    <tr class="row-mhs" data-id="<?= $row->id_krs_detail ?>">
                        <td class="text-center"><?= $no++ ?></td>
                        <td>
                            <small class="text-muted"><?= $row->nim ?></small><br>
                            <strong><?= $row->nama ?></strong>
                        </td>

                        <?php foreach ($list_komponen as $k):
                            $val_now = 0;
                            foreach ($saved_komponen as $sk) {
                                if ($sk['id'] == $k->id) {
                                    $val_now = $sk['nilai'];
                                    break;
                                }
                            }
                            if ($k->id == 1 && $val_now <= 0)
                                $val_now = get_nilai_absen_refactored($row->nim, $kelas_data->kelas_id);
                            ?>
                            <td>
                                <input type="hidden" name="id[<?= $row->id_krs_detail ?>][]" value="<?= $k->id ?>">
                                <input type="hidden" name="prosentase[<?= $row->id_krs_detail ?>][]"
                                    value="<?= $k->value_komponen ?>">
                                <input type="number" step="any" min="0" max="100" name="nilai[<?= $row->id_krs_detail ?>][]"
                                    value="<?= $val_now ?>" class="form-control input-sm input-nilai"
                                    data-bobot="<?= $k->value_komponen ?>">
                            </td>
                        <?php endforeach; ?>

                        <td>
                            <input type="text" name="nilai_akhir[<?= $row->id_krs_detail ?>]"
                                value="<?= $row->nilai_angka ?>" class="form-control input-sm final-grade" readonly>
                        </td>
                        <td>
                            <select name="nilai_huruf[<?= $row->id_krs_detail ?>]"
                                class="form-control input-sm select-huruf">
                                <option value="<?= $row->nilai_huruf ?>#<?= $row->bobot ?>"><?= $row->nilai_huruf ?>
                                    (<?= $row->bobot ?>)</option>
                            </select>
                            <input type="hidden" name="kode_jurusan[<?= $row->id_krs_detail; ?>]"
                                value="<?= $row->jur_kode; ?>">
                            <input type="hidden" name="angkatan[<?= $row->id_krs_detail; ?>]"
                                value="<?= $row->mulai_smt; ?>">

                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div style="margin-top: 20px;">
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>
                Simpan Data Nilai</button>
        </div>
    </form>
</div>
<script>
    $(document).ready(function () {

        // 1. Fungsi Kalkulasi Row
        function calculateRow(row) {
            let grandTotal = 0;
            row.find('.input-nilai').each(function () {
                let val = parseFloat($(this).val()) || 0;
                let bobot = parseFloat($(this).data('bobot')) || 0;
                grandTotal += val * (bobot / 100);
            });

            let fixedTotal = grandTotal.toFixed(2);
            row.find('.final-grade').val(fixedTotal);

            // Ambil data jurusan & angkatan dari hidden input di dalam row yang sama
            let jurusan = row.find('input[name^="kode_jurusan"]').val();
            let angkatan = row.find('input[name^="angkatan"]').val();
            let selectHuruf = row.find('.select-huruf');

            $.post("<?= base_admin(); ?>modul/nilai_per_kelas/get_nilai_huruf.php", {
                nilai: fixedTotal,
                kode_jurusan: jurusan,
                angkatan: angkatan
            }, function (data) {
                selectHuruf.html(data);
            });
        }

        // 2. Event Listener Input
        $(document).on('input', '.input-nilai', function () {
            let v = parseFloat($(this).val());
            if (v > 100) $(this).val(100);
            calculateRow($(this).closest('tr'));
        });

        // 3. Navigasi Keyboard
        $(document).on('keydown', '.input-nilai', function (e) {
            if (e.which === 13) {
                e.preventDefault();
                let colIndex = $(this).closest('td').index();
                $(this).closest('tr').next().find('td').eq(colIndex).find('.input-nilai').focus().select();
            }
        });
    });

    $(document).ready(function () {

        $("#form_edit_nilai").validate({
            submitHandler: function (form) {
                $("#loadnya").show();
                $(form).ajaxSubmit({
                    url: $(this).attr("action"),
                    dataType: "json",
                    type: "post",
                    error: function (data) {
                        $("#loadnya").hide();
                        //console.log(data); 
                        $(".isi_warning_nilai_komponen").html(data.responseText);
                        $(".error_data_nilai_komponen").focus()
                        $(".error_data_nilai_komponen").fadeIn();
                        $(".simpan-nilai").prop("readonly", false);
                    },
                    success: function (responseText) {
                        $("#loadnya").hide();
                        console.log(responseText);
                        $.each(responseText, function (index) {
                            console.log(responseText[index].status);
                            if (responseText[index].status == "die") {
                                $("#informasi").modal("show");
                            } else {
                                $(".simpan-nilai").attr("readonly", "readonly");
                                $(".error_data_nilai_komponen").hide();
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