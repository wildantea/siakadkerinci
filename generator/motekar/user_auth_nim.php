<?php
header('Access-Control-Allow-Origin: *');

include "../pddikti/config.php";

  $data = $db->query("SELECT sys_users.*,mahasiswa.jur_kode,left(mulai_smt,4) as angkatan,nama from sys_users inner join mahasiswa on username=nim where username='".$_GET['user']."' limit 1");


$json_response = array();
if ($data->rowCount()>0) {
    foreach ($data as $dt) {
        $row_array['status'] = 'yes';
        $row_array['nim'] = $dt->username;
         $row_array['level'] = $dt->group_level;
        $row_array['full_name'] = $dt->nama;
        $row_array['angkatan'] = $dt->angkatan;
        $row_array['kode_prodi'] = $dt->jur_kode;

         array_push($json_response,$row_array);
        echo json_encode($json_response);

    }
} else {
    $row_array['status'] = 'no';
    array_push($json_response,$row_array);
    echo json_encode($json_response);
}

?>