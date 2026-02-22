<?php
//check if rps is exist
$nips = $db2->fetchCustomSingle("select group_concat(id_dosen) as nip from dosen_kelas where id_kelas='$kelas_id'");
$check_exist = $db2->query("select * from rps_file where semester=? and id_matkul=? and nip in($nips->nip)",array('semester' => getSemesterAktif(),'id_matkul' => $kelas_data->id_matkul));
include "view_rps.php";