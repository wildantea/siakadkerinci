<?php

switch (uri_segment(2)) {
    case "create":
        if ($db2->userCan("insert")) {
            include "kelas_add.php";
        }
        break;
    case "edit":
        $data_edit = $db2->fetchSingleRow("tb_data_kelas", "kelas_id", uri_segment(2));
        if ($db2->userCan("update")) {
            include "kelas_edit.php";
        }
        break;
    case 'development':
        include "kelas_view_dev.php";
        break;
    case "detail":
        $kelas = $db2->fetchSingleRow("view_nama_kelas", "kelas_id", uri_segment(3));
        $kelas_id = uri_segment(3);
        include "kelas_detail.php";
        break;
    default:
        //print_r($_SESSION); die();
        if ($_SESSION['group_level'] == 'admin') {
            include "kelas_view_admin.php";
        } elseif ($_SESSION['group_level'] == 'mahasiswa') {
            include "kelas_view_mahasiswa.php";
        } else if ($_SESSION['group_level'] == 'dosen') {
            if ($_SESSION['username'] == '199012112019031007') {
                include "kelas_view_dev.php";
            } else {
                $mhs_id = $_SESSION['username'];
                //include "kelas_view.php";
                include "kelas_view_dev.php";
            }
            // $sem_aktif = get_semester_aktif($_SESSION['id_jur']);
            // print_r($sem_aktif); 
            //  $akm_id = get_semester_aktif_mhs($sem_aktif->id_semester, $mhs_id);

        }

        break;
}

?>