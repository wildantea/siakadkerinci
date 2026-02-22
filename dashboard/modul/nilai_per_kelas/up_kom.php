<?php
include "../../inc/config.php";

$kelas = $db->query("
    SELECT kelas_id, komponen 
    FROM kelas 
    WHERE sem_id='20251' 
      AND komponen IS NOT NULL
");

foreach ($kelas as $k) {

    $json = json_decode($k->komponen, true);

    dump($json);

    if (!isset($json['komponen']) || !is_array($json['komponen'])) {
        echo "SKIPPED (JSON rusak) kelas_id: {$k->kelas_id}<br>";
        continue;
    }

    // FIX: TANPA REFERENCE (&)
    foreach ($json['komponen'] as $key => $item) {

        $nama = strtolower(trim($item['nama_komponen']));

        if ($nama === "quiz") {
            $json['komponen'][$key]['value_komponen'] = "10";
        }

        if ($nama === "tugas") {
            $json['komponen'][$key]['value_komponen'] = "10";
        }

        if ($nama === "uts") {
            $json['komponen'][$key]['value_komponen'] = "20";
        }

        if ($nama === "uas") {
            $json['komponen'][$key]['value_komponen'] = "30";
        }
    }

    // Hitung ulang total
    $total = 0;
    foreach ($json['komponen'] as $item) {
        $total += intval($item['value_komponen']);
    }

    $json['total_prosentase'] = strval($total);

    $new_json = json_encode($json, JSON_UNESCAPED_UNICODE);

    dump($new_json);

    $db->update(
        "kelas",
        ["komponen" => $new_json],
        "kelas_id",
        $k->kelas_id
    );

    echo "UPDATED kelas_id: {$k->kelas_id} → total_prosentase = $total<br>";
}

echo "<hr>ALL DONE!";
