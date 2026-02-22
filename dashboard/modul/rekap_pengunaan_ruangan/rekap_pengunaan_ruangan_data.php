<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'jadwal_kuliah.hari',
    'view_ruang_prodi.nm_gedung',
    'view_ruang_prodi.nm_ruang',
    'jadwal_kuliah.jam_mulai',
  );


  //if you want to exclude column for searching, put columns name in array
  $datatable2->setDisableSearchColumn(
  );
  
  //set numbering is true
  $datatable2->setNumberingStatus(1);

  //set order by column
  //$datatable2->set_order_by("keu_tagihan_mahasiswa.id");

  //set order by type
  //$datatable2->set_order_type("desc");

$kode_prodi = aksesProdi('kode_jur');
$hari = $_POST['hari'];
$ruang = "";
$gedung = "";
$semester = $_POST['semester'];
$jur_filter = "";
$fakultas = "";
$query_hari = "'".ucwords($hari)."' as hari,";

  if ($_POST['jur_filter']!='all') {
    $jur_filter = ' and r.kode_jur="'.$_POST['jur_filter'].'"';
  }
  if ($_POST['fakultas']!='all') {
    $fakultas = getProdiFakultas('r.kode_jur',$_POST['fakultas']);
  }
  
  if ($_POST['gedung']!='all') {
    $gedung = ' and gedung_id="'.$_POST['gedung'].'"';
  }

  if ($_POST['ruang']!='all') {
    $ruang = ' and ruang_id="'.$_POST['ruang'].'"';
  }

 if ($_POST['hari']!='all') {
  $hari = array($_POST['hari']);
} else {
  $hari = array(
            'senin' ,
            'selasa',
            'rabu', 
            'kamis',
            'jumat',
            'sabtu',
            'minggu'
          );
}





foreach ($hari as $hr) {
  $increment = 1;
  $sesi = $db->query("select * from sesi_waktu order by jam_mulai asc");
$jml_sesi = $sesi->rowCount();
  foreach ($sesi as $jam) {
          $query_where[$hr][] = "
    '$jam->jam_mulai' AS Time_Start_$increment,
    '$jam->jam_selesai' AS Time_End_$increment,
    CASE 
        WHEN EXISTS (
            SELECT 1 
            FROM view_jadwal vj 
            JOIN view_nama_kelas k ON vj.kelas_id = k.kelas_id
            WHERE vj.ruang_id = r.ruang_id 
              AND vj.hari = '$hr'
              AND vj.sem_id = '$semester'
              AND vj.jam_mulai <= '$jam->jam_mulai' 
              AND vj.jam_selesai > '$jam->jam_mulai'
        ) 
        THEN (
            SELECT concat(k.kode_jur,'/',k.nm_matkul)
            FROM view_jadwal vj 
            JOIN view_nama_kelas k ON vj.kelas_id = k.kelas_id
            WHERE vj.ruang_id = r.ruang_id 
              AND vj.hari = '$hr'
              AND vj.sem_id = '$semester'
              AND vj.jam_mulai <= '$jam->jam_mulai' 
              AND vj.jam_selesai > '$jam->jam_mulai'
            LIMIT 1
        )
        ELSE ''
    END AS Availability_$increment";
    $increment++;
    }

}

foreach ($hari as $hri) {
  $hasil_query = implode(", ",$query_where[$hri]);
  $query[] = "SELECT 
   r.nm_gedung,r.nm_ruang,
    
'".ucwords($hri)."' as hari,

$hasil_query

FROM 
    view_ruang_prodi r
where is_aktif='Y'
$gedung $ruang $jur_filter $fakultas";
}

$query_akhir = implode(" union all ", $query);



  //set group by column
  //$new_table->group_by = "group by keu_tagihan_mahasiswa.id";
$datatable2->setDebug(1);
$datatable2->setUnion();
$datatable2->setFromQuery($query_akhir);
  $query = $datatable2->execQuery($query_akhir,$columns);
  //buat inisialisasi array data
  $data = array();

  $i=1;
  $login_as = "";
  $array_allowed_login_as = array(
    'admin'
    //,'keuangan','rektorat','admin_akademik','tipd'
  );
  foreach ($query as $value) {

    //array data
    $ResultData = array();
     $ResultData[] = $datatable->number($i);
        $ResultData[] = ucwords($value->hari);
    $ResultData[] = $value->nm_gedung;
    $ResultData[] = $value->nm_ruang;
    for ($is=1; $is <= $jml_sesi; $is++) { 
      $ResultData[] = $value->{"Availability_".$is};
    }

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable2->setData($data);
//create our json
$datatable2->createData();
?>