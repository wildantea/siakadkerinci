<?php
switch (uri_segment(2)) {
    case "add":
        $id = $dec->dec(uri_segment(3));
        $kelas_data = $db2->fetchCustomSingle("SELECT tdk.kelas_id,tdk.kls_nama,kuota,kode_jur,ada_komponen,komponen,vmk.kode_mk,tdk.sem_id,vmk.nama_mk,vmk.total_sks,vmk.kode_jur,vmk.kode_jur, vmk.nama_jurusan,(select group_concat(nama_gelar separator '#') from view_dosen_kelas where kelas_id=tdk.kelas_id) as nama_dosen from tb_data_kelas tdk
INNER JOIN view_matakuliah_kurikulum vmk using(id_matkul) where kelas_id=?", array('kelas_id' => $id));

        dump($db->getErrorMessage());

        if (isDosen()) {
            if ($db2->userCan("insert")) {
                if ($kelas_data->ada_komponen == 'Y') {
                    include "nilai_per_kelas_add_komponen_dosen.php";
                } else {
                    include "input_nilai_dosen.php";
                }
            }
        } else {
            if ($db2->userCan("insert")) {
                if ($kelas_data->ada_komponen == 'Y') {
                    include "nilai_per_kelas_add_komponen.php";
                } else {
                    include "nilai_per_kelas_add.php";
                }
            }
        }

        break;
    case 'detail':
        $id = $dec->dec(uri_segment(3));
        $kelas_data = $db2->fetchCustomSingle("SELECT tdk.kelas_id,tdk.kls_nama,kuota,ada_komponen,komponen,vmk.kode_mk,tdk.sem_id,vmk.nama_mk,vmk.total_sks,vmk.kode_jur,vmk.kode_jur, vmk.nama_jurusan from tb_data_kelas tdk
INNER JOIN view_matakuliah_kurikulum vmk using(id_matkul) where kelas_id=?", array('kelas_id' => $id));
        include "nilai_per_kelas_detail.php";
        break;
    default:
        if (isDosen()) {
            include "nilai_per_kelas_view_dosen.php";
        } else {
            include "nilai_per_kelas_view.php";
        }

        break;
}

?>