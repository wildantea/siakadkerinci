 <?php
session_start();
include "../../../../inc/config.php";
session_check();

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

$hari = $_POST['hari'];
$sks = $_POST['sks'];
$sem_id = $_POST['sem_id'];
$ruang_id = $_POST['ruang_id'];

if ($hari) {


// Required duration per session (50 minutes)
$requiredDuration = $minute_sesi * 60; // 50 minutes = 3000 seconds
$sessionCount = $sks; // Number of sessions being requested

// Fetch booked schedules
$get_jadwal = $db->query("SELECT jam_mulai, jam_selesai FROM view_jadwal WHERE hari = ? AND ruang_id = ? AND sem_id = ? and kelas_id!='".$_POST['kelas_id']."' ORDER BY jam_mulai ASC", array('hari' => $hari, 'ruang_id' => $ruang_id, 'sem_id' => $sem_id));

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
$id_dosen = $_POST['id_dosen'];
if (!empty($id_dosen)) {
    // Use array_map to add single quotes around each element
    $quotedArray = array_map(function($item) {
        return "'" . $item . "'";
    }, $id_dosen);

    // Implode the quoted array into a single string
    $id_dos = implode(", ", $quotedArray);
    $booked2 = [];
    
    // Get dosen class schedules
    $get_jadwal_dosen = $db->query("SELECT * FROM view_jadwal_dosen_kelas WHERE hari = ? AND sem_id = ? AND id_dosen IN ($id_dos) and id_kelas!='".$_POST['kelas_id']."' ORDER BY jam_mulai ASC", array('hari' => $hari, 'sem_id' => $sem_id));
    
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
$kelas_name = $db->fetch_single_row("view_nama_kelas","kelas_id",$_POST['kelas_id']);
$get_jadwal_kelas = $db->query("select view_jadwal.jam_mulai,view_jadwal.jam_selesai,view_nama_kelas.nm_matkul,(select group_concat(id_dosen) from dosen_kelas where id_kelas=view_nama_kelas.kelas_id) as id_dosen from view_jadwal inner join view_nama_kelas using(kelas_id) where kelas_id!='".$_POST['kelas_id']."' and view_jadwal.sem_id=? and kelas_jadwal=? and kode_jur=? and sem_matkul=? and hari=? order by jam_mulai asc",array('sem_id' => $sem_id,'kelas_jadwal' => $kelas_name->nama_kelas,'kode_jur' => $kelas_name->kode_jur,'sem_matkul' => $kelas_name->sem_matkul,'hari' => $hari));
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
        <div class="btn-group"  data-toggle="tooltip" data-title="Waktu ini tidak tersedia" data-placement="right">
            <label class="btn btn-danger disabled not-available" style="color:#fff">
                <span ><?= $session['formatted']; ?></span>
            </label>
        </div>
        <?php
    } else {
        ?>
      <div class="btn-group" data-toggle="buttons">
                 <label class="btn btn-default time-slot">
                <input type="radio" class="waktu" name="time" data-msg-required="Jam Wajib dipilih" value="<?= $session['formatted']; ?>" required> <?= $session['formatted']; ?>
            </label>
          </div>

      
        <?php
    }
}
?>
<p><p>
<span class="text-aqua">Dengan Jumlah <?=$sks;?> SKS maka Matakuliah ini perlu <?=$sks * $minute_sesi;?> Menit</span>
<?php
}
?>
