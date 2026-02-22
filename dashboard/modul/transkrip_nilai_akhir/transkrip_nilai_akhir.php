<?php
switch (uri_segment(2)) {
    case "show-nilai":
   // echo de(uri_segment(3)); die();
    $nim=de(uri_segment(3));
    $q=$db->query("select m.nim,m.nama,j.nama_jur as jurusan,f.nama_resmi as fakultas from mahasiswa m
                  join jurusan j on j.kode_jur=m.jur_kode
                  join fakultas f on f.kode_fak=j.fak_kode where m.nim='$nim'");
    foreach ($q as $k) {
       include "transkrip_nilai_all_semester.php";
    }

    break;

    case "detail":
    $data_edit = $db->fetch_single_row("mahasiswa","mhs_id",uri_segment(3));
    include "transkrip_nilai_akhir_detail.php";
    break;
    default:
    include "transkrip_nilai_akhir_view.php";
    break;
}

?>
