<?php
include "../../../inc/config.php";
$data_edit = $db2->fetchSingleRow('jadwal_kuliah', 'jadwal_id', $_POST['jadwal_id']);
$kelas_data = $db2->fetchSingleRow('view_nama_kelas', 'kelas_id', $_POST['kelas_id']);
$gedung_id = $db2->fetchSingleRow('ruang_ref', 'ruang_id', $data_edit->ruang_id);
?>
<link rel="stylesheet" type="text/css"
    href="<?= base_admin(); ?>assets/plugins/clockpicker/bootstrap-clockpicker.min.css">
<style type="text/css">
    .clockpicker-span-hours,
    .clockpicker-span-minutes {
        font-size: 24px;
    }

    .modal.left .modal-dialog,
    .modal.right .modal-dialog {
        top: 0;
        bottom: 0;
        position: fixed;
        overflow-y: scroll;
        overflow-x: hidden;
        margin: auto;
        -webkit-transform: translate3d(0%, 0, 0);
        -ms-transform: translate3d(0%, 0, 0);
        -o-transform: translate3d(0%, 0, 0);
        transform: translate3d(0%, 0, 0);
    }

    /*Right*/
    .modal.right.fade .modal-dialog {
        right: -320px;
        -webkit-transition: opacity 0.3s linear, right 0.3s ease-out;
        -moz-transition: opacity 0.3s linear, right 0.3s ease-out;
        -o-transition: opacity 0.3s linear, right 0.3s ease-out;
        transition: opacity 0.3s linear, right 0.3s ease-out;
    }

    .modal.right.fade.in .modal-dialog {
        right: 0;
    }




    .time-slot {
        margin: 5px 5px;
    }

    .day-button {
        margin: 5px 5px;
    }

    .gedung-button {
        margin: 5px 5px;
        flex: 1 0 auto;
    }

    .ruang-button {
        margin: 5px 5px;
    }

    .day-button.is-active {
        background-color: #5cb85c;
        /* Green background */
        color: white !important;
        /* White text on hover */
    }

    .time-slot.is-active {
        background-color: #5cb85c;
        /* Green background */
        color: white !important;
        /* White text on hover */
    }

    .time-slot:hover {
        background-color: #5cb85c !important;
        /* Green background on hover */
        color: white !important;
        /* White text on hover */
    }

    .day-button:hover {
        background-color: #5cb85c !important;
        /* Green background on hover */
        color: white !important;
        /* White text on hover */
    }

    .not-available {
        pointer-events: auto;
        margin: 5px 5px;
    }

    .gedung-button:hover {
        background-color: #5cb85c !important;
        /* Green background on hover */
        color: white !important;
        /* White text on hover */
    }

    .gedung-button.is-active {
        background-color: #5cb85c;
        /* Green background */
        color: white !important;
        /* White text on hover */
    }

    .ruang-button.is-active {
        background-color: #5cb85c;
        /* Green background */
        color: white !important;
        /* White text on hover */
    }

    .ruang-button:hover {
        background-color: #5cb85c !important;
        /* Green background on hover */
        color: white !important;
        /* White text on hover */
    }

    .form-group.has-success label {
        color: #444;
        /* White text on hover */
    }

    .fieldset-wrapper {
        margin-bottom: 5px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .legend-title {
        font-weight: bold;
        margin-bottom: 10px;
    }

    legend {
        display: block;
        padding: 0;
        font-size: 21px;
        line-height: inherit;
        color: #333;
        margin-bottom: 5px;
        border: 0;
    }

    .schedule-container {
        max-width: 600px;
        /* Max width for the schedule */
        margin: auto auto;
        /* Center the container */
        background-color: #ffffff;
        /* White background for the schedule */
        border-radius: 8px;
        /* Rounded corners */
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        /* Subtle shadow */
        padding: 2px;
        /* Padding inside the container */
    }

    .schedule-header {
        text-align: center;
        /* Center the header text */
        margin-bottom: 20px;
        /* Space below the header */
    }

    .schedule-item {
        background-color: #f1f1f1;
        /* Light gray background for items */
        border-radius: 5px;
        /* Rounded corners for items */
        padding: 11px;
        /* Padding inside each item */
        margin-bottom: 10px;
        /* Space between items */
        position: relative;
        /* Position for the dot */
    }

    .schedule-item .time {
        font-weight: bold;
        /* Bold time text */
    }

    .numbering.drag-handle {
        cursor: grab;
    }

    .numbering.drag-handle:hover {
        background-color: #f0f0f0;
        border: 1px solid #ccc;
        transition: all 0.2s ease;
    }

    .form-control.pengajar {
        cursor: pointer;
        /* Ensures the select element has a pointer cursor */
    }

    .schedule-item .dot {
        position: absolute;
        /* Position the dot */
        left: -10px;
        /* Position to the left */
        top: 15px;
        /* Center vertically */
        width: 18px;
        /* Dot size */
        height: 18px;
        /* Dot size */
        border-radius: 50%;
        /* Make it circular */
        display: flex;
        /* Flexbox for centering */
        align-items: center;
        /* Center vertically */
        justify-content: center;
        /* Center horizontally */
        color: white;
        /* Text color */
        font-weight: bold;
        /* Bold text */
    }

    .work {
        background-color: #007bff;
    }

    /* Blue for work */
    .personal {
        background-color: #28a745;
    }

    /* Green for personal */
    .design {
        background-color: #dc3545;
    }

    /* Red for design */
</style>
<div class="alert alert-danger error_data_jadwal" style="display:none">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <span class="isi_warning_jadwal"></span>
</div>
<div class="row">
    <div class="col-lg-12" id="left_form">
        <form method="post" class="form-horizontal" id="input_jadwal"
            action="<?= base_admin(); ?>modul/kelas_jadwal/jadwal/jadwal_action.php?act=input_jadwal">

            <input type="hidden" name="kelas_id" value="<?= $_POST['kelas_id']; ?>">
            <input type="hidden" name="sem_id" value="<?= $_POST['sem_id']; ?>">

            <div class="form-group">
                <div class="col-lg-12">
                    <fieldset class="fieldset-wrapper">
                        <legend style="width: 71px;">Gedung</legend>

                        <div class="row">
                            <div class="col-xs-12">

                                <?php
                                $gedung = $db->query("select * from view_ruang_prodi where kode_jur=? and is_aktif='Y' group by gedung_id", array('kode_jur' => $_POST['kode_jur']));
                                foreach ($gedung as $isi) {
                                    if ($isi->gedung_id == $gedung_id->gedung_id) {
                                        $gedung_id = $isi->gedung_id;
                                        ?>
                                        <div class="btn-group" data-toggle="buttons">
                                            <label class="btn btn-default gedung-button is-active">
                                                <input type="radio" class="gedung_id" name="gedung_id" checked
                                                    data-msg-required="Silakan Pilih Gedung" value="<?= $isi->gedung_id; ?>"
                                                    required> <?= $isi->nm_gedung; ?>
                                            </label>
                                        </div>
                                        <?php
                                    } else {
                                        ?>
                                        <div class="btn-group" data-toggle="buttons">
                                            <label class="btn btn-default gedung-button">
                                                <input type="radio" class="gedung_id" name="gedung_id"
                                                    data-msg-required="Silakan Pilih Gedung" value="<?= $isi->gedung_id; ?>"
                                                    required> <?= $isi->nm_gedung; ?>
                                            </label>
                                        </div>
                                        <?php
                                    }
                                    ?>

                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-12">
                    <span class="loader-ruang"></span>
                    <div id="data_ruang">
                        <div class="row">
                            <div class="col-xs-12">
                                <fieldset class="fieldset-wrapper">
                                    <legend style="width: 79px;">Ruangan</legend>

                                    <?php
                                    foreach ($db->query("select * from view_ruang_prodi
where is_aktif='Y' and gedung_id=? and kode_jur=?", array('gedung_id' => $gedung_id, 'kode_jur' => $_POST['kode_jur'])) as $isi) {

                                        if ($isi->ruang_id == $data_edit->ruang_id) {
                                            $gedung_id = $isi->gedung_id;
                                            $ruang_id = $isi->ruang_id;
                                            ?>
                                            <div class="btn-group" data-toggle="buttons">
                                                <label class="btn btn-default ruang-button is-active">
                                                    <input type="radio" class="ruang_id" name="ruang_id" checked
                                                        data-msg-required="Silakan Pilih Ruang" value="<?= $isi->ruang_id; ?>"
                                                        required> <?= $isi->nm_ruang; ?>
                                                </label>
                                            </div>
                                            <?php
                                        } else {
                                            ?>
                                            <div class="btn-group" data-toggle="buttons">
                                                <label class="btn btn-default ruang-button">
                                                    <input type="radio" class="ruang_id" name="ruang_id"
                                                        data-msg-required="Silakan Pilih Ruang" value="<?= $isi->ruang_id; ?>"
                                                        required> <?= $isi->nm_ruang; ?>
                                                </label>
                                            </div>
                                            <?php
                                        }
                                    }
                                    ?>
                                </fieldset>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <div class="form-group">
                <div class="col-lg-12">
                    <fieldset class="fieldset-wrapper">
                        <input type="hidden" name="id_ruang" class="id_ruang" value="<?= $ruang_id; ?>">
                        <legend style="width: 40px;">Hari</legend>
                        <div class="row">
                            <div class="col-xs-12">

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
                                    if ($data_edit->hari == $h) {
                                        ?>
                                        <div class="btn-group" data-toggle="buttons">
                                            <label class="btn btn-default day-button is-active">
                                                <input type="radio" class="hari_id" checked
                                                    data-msg-required="Silakan Pilih Hari" required name="hari"
                                                    value="<?= $h; ?>"> <?= $hari; ?>
                                            </label>
                                        </div>
                                        <?php
                                    } else {
                                        ?>
                                        <div class="btn-group" data-toggle="buttons">
                                            <label class="btn btn-default day-button">
                                                <input type="radio" class="hari_id" data-msg-required="Silakan Pilih Hari"
                                                    required name="hari" value="<?= $h; ?>"> <?= $hari; ?>
                                            </label>
                                        </div>
                                        <?php
                                    }
                                }

                                ?>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div><!-- /.form-group -->

            <fieldset class="fieldset-wrapper">
                <legend style="width: 140px;">Dosen Pengajar</legend>
                <div class="draggable-container has-pengajar">
                    <div id="sortable-container">

                        <div class="clone-div">
                            <?php
                            $pengajar_data = $db->query("select view_jadwal_dosen_kelas.*,(select nama_dosen from dosen where nip=view_jadwal_dosen_kelas.id_dosen) as nama_dosen from view_jadwal_dosen_kelas where id_kelas=? order by dosen_ke asc", array('id_kelas' => $kelas_data->kelas_id));
                            $indexer = 1;
                            if ($pengajar_data->rowCount() > 0) {
                                foreach ($pengajar_data as $pengajar) {
                                    $id_dosen[] = $pengajar->id_dosen;
                                    if ($indexer == 1) {
                                        ?>
                                        <div class="form-group row-clone-pengajar">
                                            <div class="col-lg-12 ">
                                                <div class="row">
                                                    <div class="col-lg-10" style="padding-top:2px;">
                                                        <div class="input-group">
                                                            <span
                                                                class="input-group-addon numbering drag-handle"><?= $indexer; ?></span>
                                                            <select name="pengajar[]" id="pengajar_<?= $indexer; ?>"
                                                                data-placeholder="Pilih Dosen Pengajar..."
                                                                class="form-control pengajar " tabindex="2" required
                                                                data-msg-required="Silakan Pilih Pengajar">
                                                                <option value="<?= $pengajar->id_dosen; ?>" selected>
                                                                    <?= $pengajar->nama_dosen; ?>
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1 show-hapus-pengajar"
                                                        style="padding-top:1px;padding-left:0;display: none;" data-toggle="tooltip"
                                                        data-title="Hapus pengajar">
                                                        <span class="btn btn-danger btn-sm hapus-pengajar"><i
                                                                class="fa fa-trash"></i></span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <?php
                                    } else {
                                        ?>
                                        <div class="form-group row-clone-pengajar">
                                            <div class="col-lg-12 ">
                                                <div class="row">
                                                    <div class="col-lg-10" style="padding-top:2px;">
                                                        <div class="input-group">
                                                            <span
                                                                class="input-group-addon numbering drag-handle"><?= $indexer; ?></span>
                                                            <select name="pengajar[]" id="pengajar_<?= $indexer; ?>"
                                                                data-placeholder="Pilih Dosen Pengajar..."
                                                                class="form-control pengajar " tabindex="2" required
                                                                data-msg-required="Silakan Pilih Pengajar">
                                                                <option value="<?= $pengajar->id_dosen; ?>" selected>
                                                                    <?= $pengajar->nama_dosen; ?>
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1 show-hapus-pengajar"
                                                        style="padding-top:1px;padding-left:0;" data-toggle="tooltip"
                                                        data-title="Hapus pengajar">
                                                        <span class="btn btn-danger btn-sm hapus-pengajar"><i
                                                                class="fa fa-trash"></i></span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <?php
                                    }
                                    $indexer++;
                                }

                            } else {
                                ?>
                                <div class="form-group row-clone-pengajar">
                                    <div class="col-lg-12 ">
                                        <div class="row">
                                            <div class="col-lg-10" style="padding-top:2px;">
                                                <div class="input-group">
                                                    <span class="input-group-addon numbering drag-handle">1</span>
                                                    <select name="pengajar[]" id="pengajar_1"
                                                        data-placeholder="Pilih Dosen Pengajar..."
                                                        class="form-control pengajar " tabindex="2" required
                                                        data-msg-required="Silakan Pilih Pengajar">

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-1 show-hapus-pengajar"
                                                style="padding-top:1px;padding-left:0;display: none;" data-toggle="tooltip"
                                                data-title="Hapus pengajar">
                                                <span class="btn btn-danger btn-sm hapus-pengajar"><i
                                                        class="fa fa-trash"></i></span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>


                        <div class="form-group">
                            <div class="col-lg-12">
                                <span class="btn btn-success btn-sm clone-btn"><i class="fa fa-plus"></i> Tambah Dosen
                                    Lain</span>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>


            <div class="form-group">
                <div class="col-lg-12">
                    <span class="loader-waktu"></span>
                    <fieldset class="fieldset-wrapper">
                        <legend style="width: 59px;">Waktu</legend>
                        <div class="row">
                            <div class="col-xs-12" id="isi_waktu">
                                <?php
                                // Fetch session durations
                                $session_time = $db->query("SELECT * FROM sesi_waktu ORDER BY jam_mulai ASC");
                                $index = 0;
                                foreach ($session_time as $sesi) {
                                    if ($index == 0) {
                                        $satu_sesi = strtotime($sesi->jam_selesai) - strtotime($sesi->jam_mulai);
                                        // minute session
                                        $minute_sesi = floor(($satu_sesi % 3600) / 60);
                                    }
                                    $index++;
                                    $sessions[] = array('jam_mulai' => $sesi->jam_mulai, 'jam_selesai' => $sesi->jam_selesai);
                                }

                                $hari = $data_edit->hari;
                                $sks = $kelas_data->sks;
                                $sem_id = $kelas_data->sem_id;
                                $ruang_id = $data_edit->ruang_id;

                                if ($hari) {


                                    // Required duration per session (50 minutes)
                                    $requiredDuration = $minute_sesi * 60; // 50 minutes = 3000 seconds
                                    $sessionCount = $sks; // Number of sessions being requested
                                
                                    // Fetch booked schedules
                                    $get_jadwal = $db->query("SELECT jam_mulai, jam_selesai FROM view_jadwal WHERE hari = ? AND ruang_id = ? AND sem_id = ? and kelas_id!='$kelas_data->kelas_id' ORDER BY jam_mulai ASC", array('hari' => $hari, 'ruang_id' => $ruang_id, 'sem_id' => $sem_id));

                                    $booked = [];
                                    if ($get_jadwal->rowCount() > 0) {
                                        foreach ($get_jadwal as $jd) {
                                            $booked[] = [
                                                'start' => strtotime($jd->jam_mulai),
                                                'end' => strtotime($jd->jam_selesai)
                                            ];
                                        }
                                    }


                                    // Fetch dosen schedules if available
                                    if (!empty($id_dosen)) {
                                        // Use array_map to add single quotes around each element
                                        $quotedArray = array_map(function ($item) {
                                            return "'" . $item . "'";
                                        }, $id_dosen);

                                        // Implode the quoted array into a single string
                                        $id_dos = implode(", ", $quotedArray);
                                        $booked2 = [];

                                        // Get dosen class schedules
                                        $get_jadwal_dosen = $db->query("SELECT * FROM view_jadwal_dosen_kelas WHERE hari = ? AND sem_id = ? AND id_dosen IN ($id_dos)  and id_kelas!='$kelas_data->kelas_id' ORDER BY jam_mulai ASC", array('hari' => $hari, 'sem_id' => $sem_id));

                                        if ($get_jadwal_dosen->rowCount() > 0) {
                                            foreach ($get_jadwal_dosen as $jds) {
                                                $booked2[] = [
                                                    'start' => strtotime($jds->jam_mulai),
                                                    'end' => strtotime($jds->jam_selesai)
                                                ];
                                            }
                                        }

                                        // Merge booked arrays and sort by start time
                                        $booked = array_merge($booked, $booked2);
                                        usort($booked, function ($a, $b) {
                                            return $a['start'] <=> $b['start'];
                                        });
                                    }

                                    //Fetch kelas schedules by kelas_name
                                    $kelas_name = $db->fetch_single_row("view_nama_kelas", "kelas_id", $_POST['kelas_id']);
                                    $get_jadwal_kelas = $db->query("select view_jadwal.jam_mulai,view_jadwal.jam_selesai,view_nama_kelas.nm_matkul,(select group_concat(id_dosen) from dosen_kelas where id_kelas=view_nama_kelas.kelas_id) as id_dosen from view_jadwal inner join view_nama_kelas using(kelas_id) where kelas_id!='" . $_POST['kelas_id'] . "' and view_jadwal.sem_id=? and kelas_jadwal=? and kode_jur=? and sem_matkul=? and hari=? order by jam_mulai asc", array('sem_id' => $sem_id, 'kelas_jadwal' => $kelas_name->nama_kelas, 'kode_jur' => $kelas_name->kode_jur, 'sem_matkul' => $kelas_name->sem_matkul, 'hari' => $hari));
                                    $booked3 = [];
                                    if ($get_jadwal_kelas->rowCount() > 0) {
                                        foreach ($get_jadwal_kelas as $jds) {
                                            $booked3[] = [
                                                'start' => strtotime($jds->jam_mulai),
                                                'end' => strtotime($jds->jam_selesai)
                                            ];
                                        }


                                        // Merge booked arrays and sort by start time
                                        $booked = array_merge($booked, $booked3);
                                        usort($booked, function ($a, $b) {
                                            return $a['start'] <=> $b['start'];
                                        });

                                    }

                                    // Convert sessions to timestamps and check availability
                                    $sessionsWithStatus = array_map(function ($session) use ($booked, $requiredDuration, $sessionCount) {
                                        $start = strtotime($session['jam_mulai']);
                                        $end = strtotime($session['jam_selesai']);
                                        $sessionDuration = $end - $start; // Calculate session duration
                                        $status = 'available';

                                        // Check if the requested time spans overlap with any booked times
                                        for ($i = 0; $i < $sessionCount; $i++) {
                                            $requestedStart = $start + ($i * $requiredDuration);
                                            $requestedEnd = $requestedStart + $requiredDuration;

                                            // Check for overlap with each booking
                                            foreach ($booked as $b) {
                                                if (
                                                    ($requestedStart >= $b['start'] && $requestedStart < $b['end']) || // Overlap start
                                                    ($requestedEnd > $b['start'] && $requestedEnd <= $b['end']) ||   // Overlap end
                                                    ($requestedStart <= $b['start'] && $requestedEnd >= $b['end'])   // Enclose booked
                                                ) {
                                                    $status = 'unavailable';
                                                    break 2;
                                                }
                                            }
                                        }

                                        return [
                                            'formatted' => "{$session['jam_mulai']}",
                                            'status' => $status
                                        ];
                                    }, $sessions);

                                    // Output the available and unavailable sessions
                                    foreach ($sessionsWithStatus as $session) {
                                        if ($session['status'] === 'unavailable') {
                                            ?>
                                            <div class="btn-group" data-toggle="tooltip" data-title="Waktu ini tidak tersedia"
                                                data-placement="right">
                                                <label class="btn btn-danger disabled not-available" style="color:#fff">
                                                    <span><?= $session['formatted']; ?></span>
                                                </label>
                                            </div>
                                            <?php
                                        } else {
                                            if (substr($data_edit->jam_mulai, 0, 5) == $session['formatted']) {
                                                ?>
                                                <div class="btn-group" data-toggle="buttons">
                                                    <label class="btn btn-default time-slot is-active">
                                                        <input type="radio" class="waktu" name="time" checked
                                                            data-msg-required="Jam Wajib dipilih" value="<?= $session['formatted']; ?>"
                                                            required> <?= $session['formatted']; ?>
                                                    </label>
                                                </div>
                                                <?php
                                            } else {
                                                ?>
                                                <div class="btn-group" data-toggle="buttons">
                                                    <label class="btn btn-default time-slot ">
                                                        <input type="radio" class="waktu" name="time"
                                                            data-msg-required="Jam Wajib dipilih" value="<?= $session['formatted']; ?>"
                                                            required> <?= $session['formatted']; ?>
                                                    </label>
                                                </div>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                    <p>
                                    <p>
                                        <span class="text-aqua">Dengan Jumlah <?= $sks; ?> SKS maka Matakuliah ini perlu
                                            <?= $sks * $minute_sesi; ?> Menit</span>
                                        <?php
                                }
                                ?>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-12">
                    <button type="button" class="btn btn-default cancel-modal-jadwal-add"><i class="fa fa-close"></i>
                        Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                </div>
            </div><!-- /.form-group -->
        </form>


    </div>
    <div class="col-lg-5" id="right_form">
        <span class="loader-jadwal-exist"></span>
        <div id="info-div">
        </div>
        <div id="info-div-dosen">
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        // Do this before you initialize any of your modals
        $.fn.modal.Constructor.prototype.enforceFocus = function () { };

    });
    $(document).ready(function () {
/*    $('.select2').select2({
        allowClear: true,
        width: "100%",
    });

$( ".pengajar" ).select2({
  ajax: {
    url: '<?= base_admin(); ?>modul / kelas_jadwal / jadwal / dosen_pengajar / data_dosen.php',
        dataType: 'json'
    },
        formatInputTooShort: "Cari & Pilih Nama Dosen",
        allowClear: true,
        width: "100%",
});*/


    // fix select2 bootstrap modal scroll bug
    $(document).on('select2:close', '.pengajar', function (e) {
        var evt = "scroll.select2"
        $(e.target).parents().off(evt)
        $(window).off(evt)
    })


    // Function to initialize select2
    function initializeSelect2(element) {
        $(element).select2({
            width: "100%",
            ajax: {
                url: '<?= base_admin(); ?>modul/kelas_jadwal/jadwal/dosen_pengajar/data_dosen.php',
                dataType: 'json',
                data: function (params) {
                    return {
                        q: params.term,
                        sem_id: '<?= $_POST['sem_id']; ?>'
                    };
                },
                processResults: function (data) {
                    var results = [];

                    $.each(data.results, function (index, item) {
                        console.log(item);

                        // Ensure sks and sks_max are treated as numbers
                        var sks = Number(item.sks);
                        var sksMax = Number(item.sks_max);

                        var disabled = true;
                        if (item.min === 'yes') {
                            disabled = false;
                        } else if (sks <= sksMax) {
                            // If the SKS value is within the allowed range, enable the option
                            disabled = false;
                        } else {
                            // Otherwise, keep it disabled
                            disabled = true;
                        }

                        results.push({
                            id: item.id,
                            text: item.text,
                            disabled: disabled // Disable the option if sks > sks_max
                        });

                    });
                    console.log(results);

                    return {
                        results: results
                    };
                }
            },
            language: {
                inputTooShort: function () {
                    return "Cari & Pilih Nama Dosen";
                }
            }
        });
    }



    function isSelect2(element) {
        return $(element).data('select2') != null;
    }

    // Initialize select2 on page load
    initializeSelect2('.pengajar');
    $('.clone-btn').on('click', function () {

        var clonedSelect = $('.row-clone-pengajar:last').find('.pengajar').clone();

        // Destroy select2 instance of original select before cloning
        if (isSelect2($('.row-clone-pengajar:last').find('.pengajar'))) {
            $('.row-clone-pengajar:last').find('.pengajar').select2('destroy');
        }


        /* // If there is more than one row, destroy select2 instance of original select before cloning
         if ($('.row-clone-pengajar').length > 1) {
             $('.row-clone-pengajar:last').find('.pengajar').select2('destroy');
         }*/

        var clonedRow = $('.row-clone-pengajar:last').clone();

        // Generate a unique ID for the cloned select
        var newId = 'pengajar_' + ($('.row-clone-pengajar').length + 1); // Increment ID based on current count
        clonedRow.find('.pengajar').attr('id', newId); // Set the new ID



        initializeSelect2($('.row-clone-pengajar:last').find('.pengajar'));
        // Increment the number beside input-group-addon
        var lastNumber = parseInt(clonedRow.find('.input-group-addon').text());
        clonedRow.find('.numbering').text(lastNumber + 1);
        clonedRow.find('.show-hapus-pengajar').show();

        // Clear the value of the cloned select
        clonedRow.find('.pengajar').val(null).trigger('change');


        // Append the cloned row
        clonedRow.appendTo('.clone-div');
        // Reinitialize select2 on the cloned element
        initializeSelect2(clonedRow.find('.pengajar'));

    });

    // Make rows sortable
    $('.clone-div').sortable({
        handle: '.drag-handle',
        update: function (event, ui) {
            // Rearrange the numbers beside input-group-addon
            $('.numbering').each(function (index) {
                $(this).text(index + 1);
            });
            $('.row-clone-pengajar').find('.show-hapus-pengajar').show();
            $('.row-clone-pengajar:first').find('.show-hapus-pengajar').hide();
        }
    });

    $(document).on('click', '.hapus-pengajar', function () {
        // Remove row if there are multiple rows
        if ($('.row-clone-pengajar').length > 1) {
            $(this).closest('.row-clone-pengajar').remove();

            // Rearrange the numbers beside input-group-addon
            $('.numbering').each(function (index) {
                $(this).text(index + 1);
            });
        }

        // Get selected values
        var selectedValues = $('.pengajar').map(function () {
            return $(this).val(); // Get the value of each selected option
        }).get(); // Convert to regular array

        var hari = $('.hari_id:checked').val();
        var ruang_id = $('.ruang_id:checked').val();
        var kelas_id = <?= $_POST['kelas_id'] ?>;
        var sem_id = <?= $_POST['sem_id'] ?>;
        var sks = <?= $kelas_data->sks ?>;

        // Helper function to make AJAX calls with retry functionality
        function makeAjaxCall(url, data, successCallback, loaderClass, retries = 3) {
            loaderForm('show', loaderClass); // Show loader

            function attemptAjax(retryCount) {
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: data,
                    success: function (result) {
                        loaderForm('hide', loaderClass); // Hide loader
                        if (result.trim() !== '') {
                            successCallback(result); // Call the callback if result is valid
                        }
                    },
                    error: function () {
                        if (retryCount > 0) {
                            console.warn(`Retrying ${url}, attempts left: ${retryCount - 1}`);
                            attemptAjax(retryCount - 1); // Retry the AJAX call
                        } else {
                            loaderForm('hide', loaderClass); // Hide loader on final failure
                            console.error(`Failed to load ${url} after multiple attempts.`);
                        }
                    }
                });
            }

            attemptAjax(retries); // Initial attempt
        }

        // Get jadwal ruang
        makeAjaxCall(
            '<?= base_admin(); ?>modul/kelas_jadwal/jadwal/edit_jadwal/get_jadwal_ruang.php',
            { hari: hari, kelas_id: kelas_id, ruang_id: ruang_id, sem_id: sem_id },
            function (result) {
                $("#info-div").html(result);
            },
            'loader-jadwal-exist'
        );

        // Get jadwal dosen
        makeAjaxCall(
            '<?= base_admin(); ?>modul/kelas_jadwal/jadwal/edit_jadwal/get_jadwal_dosen.php',
            { id_dosen: selectedValues, hari: hari, sem_id: sem_id, kelas_id: <?= $kelas_data->kelas_id; ?> },
            function (result) {
                $("#info-div-dosen").html(result);
            },
            'loader-jadwal-exist'
        );

        // Get waktu
        makeAjaxCall(
            '<?= base_admin(); ?>modul/kelas_jadwal/jadwal/edit_jadwal/get_waktu.php',
            { id_dosen: selectedValues, hari: hari, kelas_id: kelas_id, sem_id: sem_id, ruang_id: ruang_id, sks: sks },
            function (result) {
                $("#isi_waktu").html(result);
            },
            'loader-waktu'
        );
    });


  });


    $(".waktu").change(function () {
        $('.time-slot').removeClass('is-active');
        $(this).parent().addClass('is-active');
    });

    $('#isi_waktu').on('change', '.waktu', function () {
        $(this).valid();
        $('.time-slot').removeClass('is-active');
        $(this).parent().addClass('is-active');

    });


    $(".gedung_id").change(function () {
        const gedungId = $(this).val();
        console.log(`Selected Gedung ID: ${gedungId}`); // Log the selected Gedung ID

        // Show loader
        loaderForm('show', 'loader-ruang');

        // Update button styles
        $('.gedung-button').removeClass('is-active');
        $(this).parent().addClass('is-active');

        // Function to fetch Ruang Prodi with retry capability
        function fetchRuangProdi(retryCount = 0) {
            $.ajax({
                type: 'POST',
                url: '<?= base_admin(); ?>modul/kelas_jadwal/jadwal/get_ruang_prodi.php',
                data: {
                    gedung_id: gedungId,
                    kode_jur: <?= $_POST['kode_jur'] ?>
                },
                success: function (result) {
                    // Hide loader
                    loaderForm('hide', 'loader-ruang');

                    // Update content
                    $("#data_ruang").html(result);
                    $("#isi_waktu").html('');
                    $("#info-div").html('');
                    $("#info-div-dosen").html('');
                },
                error: function () {
                    if (retryCount < 3) {
                        console.log(`Retrying fetchRuangProdi... Attempt ${retryCount + 1}`);
                        fetchRuangProdi(retryCount + 1);
                    } else {
                        console.error('Failed to fetch Ruang Prodi after 3 retries.');
                        loaderForm('hide', 'loader-ruang');
                    }
                }
            });
        }

        // Fetch data with retries
        fetchRuangProdi();
    });



    // Remove previous event handlers to prevent duplicates
    $('.modal-body').off('select2:select', '.pengajar');

    // Event delegation for handling select2 selection
    $('.modal-body').on('select2:select', '.pengajar', function (e) {
        const selectedData = e.params.data.id; // Get the selected data ID
        console.log(selectedData); // Log the selected ID
        $(this).valid();

        const selectedValues = $('.pengajar').map(function () {
            return $(this).val(); // Get the value of each selected option
        }).get(); // Convert the jQuery object to a regular array

        console.log(selectedValues);

        // Function to fetch Jadwal Dosen with retry capability
        function fetchJadwalDosen(retryCount = 0) {
            $.ajax({
                type: 'POST',
                url: '<?= base_admin(); ?>modul/kelas_jadwal/jadwal/edit_jadwal/get_jadwal_dosen.php',
                data: {
                    id_dosen: selectedValues,
                    hari: $('.hari_id:checked').val(),
                    sem_id: <?= $_POST['sem_id'] ?>,
                    kelas_id: <?= $kelas_data->kelas_id; ?>
                },
                success: function (result) {
                    loaderForm('hide', 'loader-jadwal-exist');

                    if (result !== '') {
                        $("#info-div-dosen").html(result);
                        $("#left_form").removeClass().addClass('col-lg-7');
                        $("#right_form").removeClass().addClass('col-lg-5');
                    } else {
                        $("#info-div-dosen").html('');
                        $("#left_form").removeClass().addClass('col-lg-7');
                        $("#right_form").removeClass().addClass('col-lg-5');
                    }

                    /*                if (result !== '') {
                                        $("#info-div-dosen").html(result);
                                    } else {
                                        $("#info-div-dosen").html('');
                                    }*/
                },
                error: function () {
                    if (retryCount < 3) {
                        console.log(`Retrying fetchJadwalDosen... Attempt ${retryCount + 1}`);
                        fetchJadwalDosen(retryCount + 1);
                    } else {
                        console.error('Failed to fetch Jadwal Dosen after 3 retries.');
                        loaderForm('hide', 'loader-jadwal-exist');
                    }
                }
            });
        }

        // Function to handle retries for get_waktu
        function fetchWaktu(retryCount = 0, maxRetries = 3) {
            $.ajax({
                type: 'POST',
                url: '<?= base_admin(); ?>modul/kelas_jadwal/jadwal/get_waktu.php',
                data: {
                    id_dosen: selectedValues,
                    hari: $('.hari_id:checked').val(),
                    sem_id: <?= $_POST['sem_id'] ?>,
                    kelas_id: <?= $_POST['kelas_id']; ?>,
                    ruang_id: $('.id_ruang').val(),
                    sks: <?= $kelas_data->sks ?>
                },
                success: function (result) {
                    loaderForm('hide', 'loader-waktu');
                    $("#isi_waktu").html(result);
                },
                error: function () {
                    if (retryCount < maxRetries) {
                        console.log(`Retrying get_waktu... (${retryCount + 1}/${maxRetries})`);
                        setTimeout(() => fetchWaktu(retryCount + 1, maxRetries), 2000);
                    } else {
                        console.error('Failed to fetch get_waktu after multiple attempts.');
                    }
                }
            });
        }


        // Show loader and fetch data
        loaderForm('show', 'loader-jadwal-exist');
        loaderForm('show', 'loader-waktu');
        fetchJadwalDosen();
        fetchWaktu();
    });



    $(".hari_id").change(function () {
        $(this).valid();
        const val = $(this).val();
        $('.day-button').removeClass('is-active');
        $(this).parent().addClass('is-active');

        loaderForm('show', 'loader-jadwal-exist');
        loaderForm('show', 'loader-waktu');
        $("#isi_waktu").html('');

        const kelas_id = <?= $_POST['kelas_id'] ?>;
        const sem_id = <?= $_POST['sem_id'] ?>;
        const sks = <?= $kelas_data->sks ?>;
        const ruang_id = $('.id_ruang').val();
        const selectedValues = $('.pengajar').map(function () {
            return $(this).val();
        }).get();

        // Function to handle retries for get_jadwal_ruang
        function fetchJadwalRuang(retryCount = 0, maxRetries = 3) {
            $.ajax({
                type: 'POST',
                url: '<?= base_admin(); ?>modul/kelas_jadwal/jadwal/edit_jadwal/get_jadwal_ruang.php',
                data: { hari: val, ruang_id, kelas_id, sem_id },
                success: function (result) {
                    loaderForm('hide', 'loader-jadwal-exist');
                    if (result !== '') {
                        $("#info-div").html(result);
                        $("#left_form").removeClass().addClass('col-lg-7');
                        $("#right_form").removeClass().addClass('col-lg-5');
                    } else {
                        $("#info-div").html('');
                        $("#left_form").removeClass().addClass('col-lg-7');
                        $("#right_form").removeClass().addClass('col-lg-5');
                    }
                },
                error: function () {
                    if (retryCount < maxRetries) {
                        console.log(`Retrying get_jadwal_ruang... (${retryCount + 1}/${maxRetries})`);
                        setTimeout(() => fetchJadwalRuang(retryCount + 1, maxRetries), 2000);
                    } else {
                        console.error('Failed to fetch get_jadwal_ruang after multiple attempts.');
                    }
                }
            });
        }

        // Function to handle retries for get_jadwal_dosen
        function fetchJadwalDosen(retryCount = 0, maxRetries = 3) {
            $.ajax({
                type: 'POST',
                url: '<?= base_admin(); ?>modul/kelas_jadwal/jadwal/edit_jadwal/get_jadwal_dosen.php',
                data: { id_dosen: selectedValues, hari: val, sem_id, kelas_id: <?= $kelas_data->kelas_id; ?> },
                success: function (result) {
                    loaderForm('hide', 'loader-jadwal-exist');
                    if (result !== '') {
                        $("#info-div-dosen").html(result);
                    } else {
                        $("#info-div-dosen").html('');
                    }
                },
                error: function () {
                    if (retryCount < maxRetries) {
                        console.log(`Retrying get_jadwal_dosen... (${retryCount + 1}/${maxRetries})`);
                        setTimeout(() => fetchJadwalDosen(retryCount + 1, maxRetries), 2000);
                    } else {
                        console.error('Failed to fetch get_jadwal_dosen after multiple attempts.');
                    }
                }
            });
        }

        // Function to handle retries for get_waktu
        function fetchWaktu(retryCount = 0, maxRetries = 3) {
            $.ajax({
                type: 'POST',
                url: '<?= base_admin(); ?>modul/kelas_jadwal/jadwal/edit_jadwal/get_waktu.php',
                data: { id_dosen: selectedValues, hari: val, sem_id, kelas_id, ruang_id, sks },
                success: function (result) {
                    loaderForm('hide', 'loader-waktu');
                    $("#isi_waktu").html(result);
                },
                error: function () {
                    if (retryCount < maxRetries) {
                        console.log(`Retrying get_waktu... (${retryCount + 1}/${maxRetries})`);
                        setTimeout(() => fetchWaktu(retryCount + 1, maxRetries), 2000);
                    } else {
                        console.error('Failed to fetch get_waktu after multiple attempts.');
                    }
                }
            });
        }

        // Initial AJAX calls
        fetchJadwalRuang();
        fetchJadwalDosen();
        fetchWaktu();

        // Retry automatically when network reconnects
        window.addEventListener('online', function () {
            console.log('Network reconnected. Retrying pending requests...');
            fetchJadwalRuang();
            fetchJadwalDosen();
            fetchWaktu();
        });
    });





    // Event delegation for dynamically loaded ruang_id radio buttons
    $('#data_ruang').on('change', '.ruang_id', function () {
        $(this).valid();
        const val = $(this).val();
        $('.id_ruang').val(val);
        $('.ruang-button').removeClass('is-active');
        $(this).parent().addClass('is-active');
        loaderForm('show', 'loader-jadwal-exist');
        loaderForm('show', 'loader-waktu');
        $("#isi_waktu").html('');
        console.log(val);

        const hari = $('.hari_id:checked').val();
        const kelas_id = <?= $_POST['kelas_id'] ?>;
        const sem_id = <?= $_POST['sem_id'] ?>;
        const sks = <?= $kelas_data->sks ?>;
        const selectedValues = $('.pengajar').map(function () {
            return $(this).val();
        }).get();

        // Function to handle retries for get_jadwal_ruang
        function fetchJadwalRuang(retryCount = 0, maxRetries = 3) {
            $.ajax({
                type: 'POST',
                url: '<?= base_admin(); ?>modul/kelas_jadwal/jadwal/edit_jadwal/get_jadwal_ruang.php',
                data: { hari, ruang_id: val, kelas_id, sem_id },
                success: function (result) {
                    loaderForm('hide', 'loader-jadwal-exist');
                    $("#info-div-dosen").html('');
                    if (result.trim() !== '') {
                        console.log('hi');
                        $("#info-div").html(result);
                        $("#left_form").removeClass().addClass('col-lg-7');
                        $("#right_form").removeClass().addClass('col-lg-5');
                    } else {
                        $("#info-div").html('');
                        $("#left_form").removeClass().addClass('col-lg-7');
                        $("#right_form").removeClass().addClass('col-lg-5');
                    }

                },
                error: function () {
                    if (retryCount < maxRetries) {
                        console.log(`Retrying get_jadwal_ruang... (${retryCount + 1}/${maxRetries})`);
                        setTimeout(() => fetchJadwalRuang(retryCount + 1, maxRetries), 2000);
                    } else {
                        console.error('Failed to fetch get_jadwal_ruang after multiple attempts.');
                    }
                }
            });
        }

        // Function to handle retries for get_jadwal_dosen
        function fetchJadwalDosen(retryCount = 0, maxRetries = 3) {
            $.ajax({
                type: 'POST',
                url: '<?= base_admin(); ?>modul/kelas_jadwal/jadwal/edit_jadwal/get_jadwal_dosen.php',
                data: { id_dosen: selectedValues, hari, sem_id, kelas_id: <?= $kelas_data->kelas_id; ?> },
                success: function (result) {
                    loaderForm('hide', 'loader-jadwal-exist');
                    if (result.trim() !== '') {
                        $("#info-div-dosen").html(result);
                    } else {
                        $("#info-div-dosen").html('');
                    }
                },
                error: function () {
                    if (retryCount < maxRetries) {
                        console.log(`Retrying get_jadwal_dosen... (${retryCount + 1}/${maxRetries})`);
                        setTimeout(() => fetchJadwalDosen(retryCount + 1, maxRetries), 2000);
                    } else {
                        console.error('Failed to fetch get_jadwal_dosen after multiple attempts.');
                    }
                }
            });
        }

        // Function to handle retries for get_waktu
        function fetchWaktu(retryCount = 0, maxRetries = 3) {
            $.ajax({
                type: 'POST',
                url: '<?= base_admin(); ?>modul/kelas_jadwal/jadwal/edit_jadwal/get_waktu.php',
                data: { id_dosen: selectedValues, hari, kelas_id, sem_id, ruang_id: val, sks },
                success: function (result) {
                    loaderForm('hide', 'loader-waktu');
                    $("#isi_waktu").html(result);
                },
                error: function () {
                    if (retryCount < maxRetries) {
                        console.log(`Retrying get_waktu... (${retryCount + 1}/${maxRetries})`);
                        setTimeout(() => fetchWaktu(retryCount + 1, maxRetries), 2000);
                    } else {
                        console.error('Failed to fetch get_waktu after multiple attempts.');
                    }
                }
            });
        }

        // Initial AJAX calls
        fetchJadwalRuang();
        fetchJadwalDosen();
        fetchWaktu();

        // Retry automatically when network reconnects
        window.addEventListener('online', function () {
            console.log('Network reconnected. Retrying pending requests...');
            fetchJadwalRuang();
            fetchJadwalDosen();
            fetchWaktu();
        });
    });


    $('input[type="radio"]').on('change', function () {
        $(this).valid(); // This will validate the current radio button
    });


    $(".chzn-select").chosen();

    $('.cancel-modal-jadwal-add').click(function () {
        $("#form-input-jadwal").html('');
        $("#form-input-jadwal").slideUp();
        $('.tambah-jadwal').find('.fa').removeClass('fa-minus').addClass('fa-plus');
        $("#dtb_jadwal_modal").show();

        $("#form-input-jadwal-pengajar").html('');
        $("#form-input-jadwal-pengajar").slideUp();
        $('#add_dosen').find('.fa').removeClass('fa-minus').addClass('fa-plus');
        $("#dtb_dosen_pengajar").slideDown();
        $('#add_dosen').show();
    });
    //trigger validation onchange
    $('select').on('change', function () {
        $(this).valid();
    });
    //hidden validate because we use chosen select
    $.validator.setDefaults({ ignore: ":hidden:not(select)" });

    // Save the original checkForm method
    var originalCheckForm = $.validator.prototype.checkForm;

    // Override the checkForm method
    $.validator.prototype.checkForm = function () {
        this.prepareForm();

        // Loop through the elements
        for (var i = 0, elements = (this.currentElements = this.elements()); elements[i]; i++) {
            // Check if there are multiple elements with the same name
            var elementsByName = this.findByName(elements[i].name);

            if (elementsByName.length > 1) {
                // Loop through each element with the same name
                for (var cnt = 0; cnt < elementsByName.length; cnt++) {
                    this.check(elementsByName[cnt]); // Validate each element
                }
            } else {
                this.check(elements[i]); // Validate the single element
            }
        }

        // Return the overall validity
        return this.valid();
    };


    $("#input_jadwal").validate({

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
            } else if (element.hasClass("waktu")) {
                element.parent().parent().parent().append(error);
            } else if (element.attr("type") == "checkbox") {
                element.parent().parent().append(error);
            } else if (element.hasClass("dosen-ke")) {
                error.appendTo('.error-dosen');
            } else if (element.is("input[type='radio']")) {
                // For radio buttons, append the error after the last radio button in the group
                element.parent().parent().parent().append(error);
            } else if (element.is("legend")) {
                // Append error to the fieldset containing the legend
                error.appendTo(element.closest('fieldset'));
            } else if (element.parent().hasClass("input-group")) {
                element.parent().parent().append(error);
            } else {
                error.insertAfter(element);
            }
        },


        submitHandler: function (form) {
            $("#loadnya").show();
            $(form).ajaxSubmit({
                url: $(this).attr("action"),
                dataType: 'json',
                type: 'post',
                error: function (data) {
                    $("#loadnya").hide();
                    $(".isi_warning_jadwal").html(data.responseText);
                    $(".error_data_jadwal").focus()
                    $(".error_data_jadwal").fadeIn();
                },
                success: function (responseText) {
                    $("#loadnya").hide();
                    console.log(responseText);
                    $.each(responseText, function (index) {
                        console.log(responseText[index].status);
                        if (responseText[index].status == 'die') {
                            $("#informasi").modal("show");
                        } else if (responseText[index].status == 'error') {
                            $('.isi_warning_jadwal').text(responseText[index].error_message);
                            $('.error_data_jadwal').fadeIn();
                            $('html, body').animate({
                                scrollTop: ($('.error_data_jadwal').first().offset().top)
                            }, 500);
                        } else if (responseText[index].status == 'good') {
                            $('.error_data_jadwal').hide();
                            $(".notif_top_up").fadeIn(1000);
                            $(".notif_top_up").fadeOut(1000, function () {

                                $('#modal_kelas_jadwal').modal('hide');
                                /*$("#form-input-jadwal").html('');
                                $("#form-input-jadwal").slideUp();
                                $('.tambah-jadwal').show();
                                $("#dtb_jadwal_modal").slideDown();
                                $("#add_dosen").find('.fa').removeClass('fa-minus').addClass('fa-plus');
                                $("#add_dosen").show();
                                dtb_jadwal_modal.draw(false);*/
                                dataTable_jadwal.draw(false);
                            });
                        } else {
                            console.log(responseText);
                            $('.isi_warning_jadwal').text(responseText[index].error_message);
                            $('.error_data_jadwal').fadeIn();
                            $('html, body').animate({
                                scrollTop: ($('.error_data_jadwal').first().offset().top)
                            }, 500);
                        }
                    });
                }

            });
        }
    });
</script>