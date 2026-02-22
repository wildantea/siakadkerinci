<?php
header("Access-Control-Allow-Origin: *");
include "config.php";
/**
 * [trimmer trim for import excel
 *
 * @param  [type] $excel column value
 * @return [type]  trimmed value
 */
function trimmer($value)
{
    $result = preg_replace('/[^[:print:]]/', '', filter_var($value, FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH));
    return addslashes(trim($result));
}
$insert_mhs = array();
$error_count = 0;
$sukses_count = 0;
$nim_sukses = array();
$nim_error = array();
$array_sukses = array();
foreach ($_POST as $key) {
  $check = $db->checkExist('mahasiswa',array('nim' => $key['nipd']));
  if ($check==false) {
    $sukses_count++;
                $insert_mhs = array(
                      'nim' => trimmer($key['nipd']),
                      'no_pendaftaran' => trimmer($key['nipd']),
                      'nama' => trimmer($key['nm_pd']),
                      'tmpt_lahir' => trimmer($key['tmpt_lahir']),
                      'tgl_lahir' => trimmer($key['tgl_lahir']),
                      'jk' => trimmer($key['jk']),
                      'nik' => trimmer($key['nik']),
                      'id_agama' => trimmer($key['id_agama']),
                      'nisn' => trimmer($key['nisn']),
                      'id_jalur_masuk' => trimmer($key['id_jalur_masuk']), 
                      'kewarganegaraan' => trimmer($key['kewarganegaraan']),
                      'id_jns_daftar' => '1',
                      'id_jalur_masuk' => '12',
                      'mulai_smt' => trimmer($key['mulai_smt']),
                      'no_hp ' => trimmer($key['no_hp']),   
                      'email' => trimmer($key['email']),   
                      'a_terima_kps' => '0',
                      'jur_kode' => trimmer($key['jur_kode']),
                      'created_by' => 'PMB System',
                      'status' => 'CM',
                      'date_created_mhs' => date('Y-m-d H:i:s')
                );
      $insert_mhs = $db->insert('mahasiswa',$insert_mhs);
      echo $db->getErrorMessage();
      //get password
      $password = $db->fetchSingleRow("sys_users","username",$key['nipd']);
      $array_sukses[$key['nipd']] = array(
        'nomor' => trimmer($key['nipd']),
        'password' => $password->plain_pass
      );
      $nim_sukses[] = $key['nipd'];
  } else {
    $error_count++;
    $nim_error[] = $check->getData()->nim;
  }
}
/*if (!empty($insert_mhs)) {
  $db->insertMulti('mahasiswa',$insert_mhs);
  echo $db->getErrorMessage();
}
*/
$json_response = array();
$json_response['jumlah_sukses'] = $sukses_count;
$json_response['nim_sukses'] = $nim_sukses;
$json_response['array_sukses'] = $array_sukses;
$json_response['jumlah_error'] = $error_count;
$json_response['nim_error'] = $nim_error;
echo json_encode($json_response);

