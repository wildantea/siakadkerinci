<?php
session_start();
include "../../../../inc/config.php";

$columns = array(
    'tb_data_kelas_pertemuan.pertemuan',
    'tb_data_kelas_pertemuan.tanggal_pertemuan',
    'tb_data_kelas_pertemuan.jam_mulai',
    'tb_data_kelas_pertemuan.nip_dosen',
    'tb_data_kelas_pertemuan.id_pertemuan',
    'materi',
    'link_materi'
  );

  //if you want to exclude column for searching, put columns name in quote and separate with comma if multi
  //$datatable2->setDisableSearchColumn("tb_data_kelas_pertemuan.updated_by","tb_data_kelas_pertemuan.id_pertemuan");
  
  //set numbering is true
  $datatable2->setNumberingStatus(0);

  //set order by column
  $datatable2->setOrderBy("tb_data_kelas_pertemuan.pertemuan asc");


  //set group by column
  //$datatable2->setGroupBy("tb_data_kelas_pertemuan.id_pertemuan");

$datatable2->setDebug(1);
  //get dosen kelas
  $array_dosen = array();
  $kelas_id = $_POST['kelas_id'];

  function get_nama_dosen($nip) {
    global $db2;
    $dosen_kelas = $db2->query("select nip,nama_gelar from view_jadwal_dosen_kelas
inner join view_nama_gelar_dosen on view_jadwal_dosen_kelas.id_dosen=view_nama_gelar_dosen.nip  where id_kelas='".$_POST['kelas_id']."'");
    foreach ($dosen_kelas as $dosen ) {
      $array_dosen[$dosen->nip] = $dosen->nama_gelar;
    }
    return $array_dosen[$nip];
  }
  $query = $datatable2->execQuery("select tb_data_kelas_pertemuan.pertemuan,lampiran_materi,tb_data_kelas_pertemuan.status_pertemuan,tb_data_kelas_pertemuan.tanggal_pertemuan,tb_data_jenis_pertemuan.nama_jenis_pertemuan,tb_data_kelas_pertemuan.jam_mulai,jam_selesai,
realisasi_materi,tb_data_kelas_pertemuan.nip_dosen,tb_data_kelas_pertemuan.id_pertemuan,isi_absensi,tb_data_kelas_pertemuan.kelas_id,
(select materi from rps_materi_kuliah where id_kelas=tb_data_kelas_pertemuan.kelas_id and pertemuan=tb_data_kelas_pertemuan.pertemuan) as materi,
(select link_materi from rps_materi_kuliah where id_kelas=tb_data_kelas_pertemuan.kelas_id and pertemuan=tb_data_kelas_pertemuan.pertemuan) as link_materi,
(select id_materi from rps_materi_kuliah where id_kelas=tb_data_kelas_pertemuan.kelas_id and pertemuan=tb_data_kelas_pertemuan.pertemuan) as id_materi,
(
  select count(id_krs_detail) as jml_peserta FROM krs_detail where id_kelas=tb_data_kelas_pertemuan.kelas_id
) as jml_peserta,
(
  select group_concat(nim) FROM krs_detail where id_kelas=tb_data_kelas_pertemuan.kelas_id
) as nim_peserta
 from tb_data_kelas_pertemuan 
inner join tb_data_jenis_pertemuan on tb_data_kelas_pertemuan.id_jenis_pertemuan=tb_data_jenis_pertemuan.id_jenis_pertemuan

   left join tb_data_kelas_absensi using(id_pertemuan) where kelas_id='".$kelas_id."'
    ",$columns);

 //buat inisialisasi array data
  $data = array();

  function get_hadir($obj) {
    $obj = json_decode($obj);
    $hadir = 0;
     $total = 0;
    foreach ($obj as $data) {
      if ($data->status_absen=='Hadir') {
        $hadir++;
      }
      $total++;
    }
    $persen = round(($hadir/$total)*100,0);
    return array('hadir' => $hadir,'total' => $total,'persen' => $persen);
  }

    function option($selected) {
    $select_data = "";
    $array_select = array(
      '','Hadir','Ijin','Sakit','Alpa'
    );
    foreach ($array_select as $select ) {
      if ($select == $selected) {
         $select_data.= "<option value='$select' selected>$select</option>";
      }
    }
    return $select_data;
  }

  function absen_label($status) {
    $colors = [
        'Hadir' => 'success',
        'Ijin'  => 'info',
        'Sakit' => 'warning',
        'Alpa'  => 'danger',
        ''      => 'default'
    ];
    $color = isset($colors[$status]) ? $colors[$status] : 'default';
    return "<span class='label label-$color'>$status</span>";
}

  function get_nim_absen($obj) {
    global $db;
    foreach ($obj as $key) {
      $nim[$key->nim] = $key;
    }
    return $nim;
  }

  $i=1;
  foreach ($query as $value) {

    $values = array();
    $nim_user = array();
    if ($value->isi_absensi!="") {
      $absen = json_decode($value->isi_absensi);
      $nim_user = get_nim_absen($absen);
    }
      $hadir = '';
      $persen = '';
    if ($value->isi_absensi!="") {
      $stat = get_hadir($value->isi_absensi);
      $hadir = $stat['hadir'];
      $total = $stat['total'];
      $persen = $stat['persen'].'%';
    }
    
    //array data
    $ResultData = array();
    $ResultData[] = $value->pertemuan;
    $ResultData[] = getHariFromDate($value->tanggal_pertemuan).', '.tgl_indo($value->tanggal_pertemuan);
    $ResultData[] = $value->jam_mulai.' s/d '.$value->jam_selesai;

    $nama_dosen = array_map('get_nama_dosen', explode('#', $value->nip_dosen));
    $nama_dosen = trim(implode("<br>", $nama_dosen));
    $ResultData[] = $nama_dosen;


    if (in_array(getUser()->username, array_keys($nim_user))) {
        $ResultData[] = absen_label($nim_user[getUser()->username]->status_absen);
    } else {
        $ResultData[] = absen_label('');
    }

     $ResultData[] = $value->materi;
     $ResultData[] = "<a href='$value->link_materi' target='_blank' class='btn btn-primary btn-sm'><i class='fa fa-link'></i></a>";
/*    if ($value->materi!='') {
      $ResultData[] = $download."<a data-id='$value->id_materi' data-kelas='$value->kelas_id'  class='btn btn-primary input-materi' data-toggle='tooltip' title='Lihat Isi Materi'><i class='fa fa-book'></i></a>";
    } else {
      $ResultData[] = "";
    }*/
    

    $data[] = $ResultData;
    $i++;
  }


//set data
$datatable2->setData($data);
//create our json
$datatable2->createData();

?>