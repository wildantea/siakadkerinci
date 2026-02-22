<?php
session_start();
include "../../../inc/config.php";

$columns = array(
    'view_simple_mhs.nim',
    'view_simple_mhs.nama',
    'view_simple_mhs.angkatan',
    'view_simple_mhs.nama_jurusan',
    'view_krs_mhs_kelas.krs_detail_id',
  );

  //if you want to exclude column for searching, put columns name in quote and separate with comma if multi
  //$datatable2->setDisableSearchColumn("tb_data_kelas_krs.nim","tb_data_kelas_krs.krs_detail_id");
  
  //set numbering is true
  $datatable2->setNumberingStatus(1);

  //set order by column
  //$datatable2->setOrderBy("tb_data_kelas_krs.krs_detail_id desc");

  $kelas_id = $_POST['kelas_id'];

function get_absen($kelas_id, $nim_user) {
    global $db;

    $jml_hadir = 0;
    $status_absen = array();

    if (!$kelas_id) {
        return 0; // kalau kelas_id tidak valid
    }

    $absen = $db->query("SELECT isi_absensi 
                         FROM tb_data_kelas_absensi 
                         INNER JOIN tb_data_kelas_pertemuan USING(id_pertemuan)
                         WHERE kelas_id = $kelas_id");

    if ($absen && $absen->rowCount() > 0) {
        foreach ($absen as $ab) {
            if (empty($ab->isi_absensi)) {
                continue;
            }

            $data_absen = json_decode($ab->isi_absensi);

            if (json_last_error() !== JSON_ERROR_NONE || (!is_array($data_absen) && !is_object($data_absen))) {
                // JSON tidak valid → skip
                continue;
            }

            foreach ($data_absen as $nim) {
                if (isset($nim->nim, $nim->status_absen) && $nim->status_absen === 'Hadir') {
                    $status_absen[$nim->nim][] = $nim->status_absen;
                }
            }
        }
    }

    if (isset($status_absen[$nim_user])) {
        $jml_hadir = count($status_absen[$nim_user]);
    }

    return $jml_hadir;
}
  //set group by column
  //$datatable2->setGroupBy("tb_data_kelas_krs.krs_detail_id");
  $datatable2->setDebug(1);
  $datatable2->setFromQuery("krs_detail inner join view_simple_mhs on krs_detail.nim=view_simple_mhs.nim
inner join sys_users on view_simple_mhs.nim=sys_users.username

 where id_kelas='$kelas_id' and disetujui='1'");
  $query = $datatable2->execQuery("select view_simple_mhs.nim,mhs_id,foto_user,view_simple_mhs.nama,view_simple_mhs.angkatan,view_simple_mhs.nama_jurusan,
(select count(id_pertemuan) as jml_pertemuan from tb_data_kelas_pertemuan where kelas_id=krs_detail.id_kelas) as jml_pertemuan,is_photo_drived

 from krs_detail inner join view_simple_mhs on krs_detail.nim=view_simple_mhs.nim
inner join sys_users on view_simple_mhs.nim=sys_users.username

 where id_kelas='$kelas_id' and disetujui='1'",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
  $ResultData[] = $datatable2->number($i);

    $jml_hadir = get_absen($kelas_id, $value->nim);
    if ($value->jml_pertemuan > 0) {
        $persen = round(($jml_hadir / $value->jml_pertemuan) * 100, 2);
        $absen = " (<b>{$persen}%</b>)";
    } else {
        $absen = '';
    }

  
    $ResultData[] = $value->nim;
    $ResultData[] = $value->nama;
    $ResultData[] = $value->angkatan;
    $ResultData[] = $value->nama_jurusan;
    $ResultData[] = get_absen($kelas_id,$value->nim).'/'.$value->jml_pertemuan.$absen;
    if ($value->is_photo_drived=='Y') {
       $ResultData[] = '<img data-id="'.$value->mhs_id.'" class="look-photo" width="25" src="'.$value->foto_user.'">';
    } else {
       $ResultData[] = '<img data-id="'.$value->mhs_id.'" class="look-photo" width="25" src="'.base_url()."upload/back_profil_foto/".$value->foto_user.'">';
    }
   
    

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable2->setData($data);
//create our json
$datatable2->createData();

?>