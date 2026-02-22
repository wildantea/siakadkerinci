<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'view_simple_mhs.nim',
    'view_simple_mhs.nama',
    'tb_data_pendaftaran_jenis.nama_jenis_pendaftaran',
    "CONCAT(
  CASE DAYNAME(tanggal_ujian)
    WHEN 'Sunday' THEN 'Minggu'
    WHEN 'Monday' THEN 'Senin'
    WHEN 'Tuesday' THEN 'Selasa'
    WHEN 'Wednesday' THEN 'Rabu'
    WHEN 'Thursday' THEN 'Kamis'
    WHEN 'Friday' THEN 'Jumat'
    WHEN 'Saturday' THEN 'Sabtu'
  END,
  ', ',
  DAY(tanggal_ujian),
  ' ',
  CASE MONTHNAME(tanggal_ujian)
    WHEN 'January' THEN 'Januari'
    WHEN 'February' THEN 'Februari'
    WHEN 'March' THEN 'Maret'
    WHEN 'April' THEN 'April'
    WHEN 'May' THEN 'Mei'
    WHEN 'June' THEN 'Juni'
    WHEN 'July' THEN 'Juli'
    WHEN 'August' THEN 'Agustus'
    WHEN 'September' THEN 'September'
    WHEN 'October' THEN 'Oktober'
    WHEN 'November' THEN 'November'
    WHEN 'December' THEN 'Desember'
  END,
  ' ',
  YEAR(tanggal_ujian)
)",
'tempat',
'judul',
    'concat(jam_mulai," ",jam_selesai)',
    'tb_data_pendaftaran_jadwal_ujian.id_pendaftaran',
  );

  //if you want to exclude column for searching, put columns name in array
  $datatable2->setDisableSearchColumn(
    'tb_data_pendaftaran_jenis.nama_jenis_pendaftaran',
    'tb_data_pendaftaran_jadwal_ujian.id_pendaftaran'
  );

function strip_tags_content($string) { 
    // ----- remove HTML TAGs ----- 
    $string = preg_replace ('/<[^>]*>/', ' ', $string); 
    // ----- remove control characters ----- 
    $string = str_replace("\r", '', $string);
    $string = str_replace("&#39;", "'", $string);
    $string = str_replace("&nbsp;", ' ', $string);
    $string = str_replace("\n", ' ', $string);
    $string = str_replace("\t", ' ', $string);
    // ----- remove multiple spaces ----- 
    $string = trim(preg_replace('/ {2,}/', ' ', $string));
    return $string; 

}

  //if you want to exclude column for searching, put columns name in quote and separate with comma if multi
  //$datatable2->setDisableSearchColumn("tb_data_pendaftaran_jadwal_ujian.jam_selesai","tb_data_pendaftaran_jadwal_ujian.id_jadwal_ujian");
  
        //set numbering is true
        $datatable2->setNumberingStatus(1);

  //set order by column
  //$datatable2->setOrderBy("tb_data_pendaftaran.id_pendaftaran desc");

$datatable2->setDebug(1);

$jur_kode = "";
//get default akses prodi 
$akses_prodi = getAksesProdi();
if ($akses_prodi) {
  $jur_kode = "and view_simple_mhs.kode_jur in(".$akses_prodi.")";
} else {
  //jika tidak group tidak punya akses prodi, set in 0
  $jur_kode = "and view_simple_mhs.kode_jur in(0)";
}

$periode = "";
$status = "";
$jenis_pendaftaran = "";
$sem_filter = "";
$fakultas = "";

if (isset($_POST['jurusan'])) {
    //if filter session enable
    if (getPengaturan('filter_session')=='Y') {
      $array_filter = array(
        'fakultas' => $_POST['fakultas'],
        'sem_filter' => $_POST['sem_filter'],
        'prodi' => $_POST['jurusan'],
        'periode' => $_POST['periode'],
        'jenis_pendaftaran' => $_POST['jenis_pendaftaran'],
        'input_search' => $_POST['input_search']
      );
      if (hasFakultas()) {
        $array_filter['fakultas'] = $_POST['fakultas'];
        if ($_POST['fakultas']!='all' && $_POST['fakultas']!='') {
          $fakultas = getProdiFakultas('tb_data_pendaftaran.kode_jurusan',$_POST['fakultas']);
        }
      }
      setFilter('filter_pendaftaran_jadwal',$array_filter);
    }
    if ($_POST['sem_filter']!='all') {
      $sem_filter = ' and tb_data_pendaftaran.id_semester="'.$_POST['sem_filter'].'"';
    }
    if ($_POST['jurusan']!='all') {
      $jur_kode = ' and view_simple_mhs.kode_jur="'.$_POST['jurusan'].'"';
    }
    if ($_POST['periode']!='all') {
        $periode = " and DATE_FORMAT(tb_data_pendaftaran.date_created,'%Y%m')='".$_POST['periode']."'";  }
    if ($_POST['jenis_pendaftaran']!='all') {
      $jenis_pendaftaran = ' and tb_data_pendaftaran_jenis.id_jenis_pendaftaran="'.$_POST['jenis_pendaftaran'].'"';
    }
}

  //set group by column
  //$datatable2->setGroupBy("tb_data_pendaftaran_jadwal_ujian.id_jadwal_ujian");

$dosen_nip = $_SESSION['username'];
$datatable2->setDebug(1);
  $datatable2->setFromQuery("tb_data_pendaftaran_jadwal_ujian right join tb_data_pendaftaran on tb_data_pendaftaran_jadwal_ujian.id_pendaftaran=tb_data_pendaftaran.id_pendaftaran inner join view_simple_mhs on tb_data_pendaftaran.nim=view_simple_mhs.nim inner join tb_data_pendaftaran_jenis_pengaturan on tb_data_pendaftaran.id_jenis_pendaftaran_setting=tb_data_pendaftaran_jenis_pengaturan.id_jenis_pendaftaran_setting inner join tb_data_pendaftaran_jenis on tb_data_pendaftaran_jenis_pengaturan.id_jenis_pendaftaran=tb_data_pendaftaran_jenis.id_jenis_pendaftaran where tb_data_pendaftaran.status in('1') $sem_filter $jur_kode $fakultas $periode $jenis_pendaftaran");
  $query = $datatable2->execQuery("select judul,ada_judul,view_simple_mhs.nama_jurusan,view_simple_mhs.nim,view_simple_mhs.nama,tb_data_pendaftaran_jenis.nama_jenis_pendaftaran,tb_data_pendaftaran_jadwal_ujian.tanggal_ujian,tb_data_pendaftaran_jadwal_ujian.jam_mulai,tb_data_pendaftaran_jadwal_ujian.jam_selesai,tb_data_pendaftaran.date_created,id_ruang,tb_data_pendaftaran.id_pendaftaran,tempat,
(
select group_concat(nama_gelar separator '#')
 from view_nama_gelar_dosen inner join tb_data_pendaftaran_penguji on nip=nip_dosen where tb_data_pendaftaran_penguji.id_jadwal_ujian=tb_data_pendaftaran_jadwal_ujian.id_jadwal_ujian order by penguji_ke asc
) as penguji,
(
select group_concat(nip_dosen separator '#')
 from view_nama_gelar_dosen inner join tb_data_pendaftaran_penguji on nip=nip_dosen where tb_data_pendaftaran_penguji.id_jadwal_ujian=tb_data_pendaftaran_jadwal_ujian.id_jadwal_ujian order by penguji_ke asc
) as nip_penguji,
  (
    SELECT COUNT(CASE WHEN nilai_ujian IS NOT NULL AND nilai_ujian != '' THEN 1 ELSE NULL END)
    FROM tb_data_pendaftaran_penguji
    WHERE tb_data_pendaftaran_penguji.id_jadwal_ujian = tb_data_pendaftaran_jadwal_ujian.id_jadwal_ujian
  ) AS count_nilai_penguji,
(
select matkul_syarat from tb_data_pendaftaran_jenis_pengaturan_prodi where kode_jur=view_simple_mhs.kode_jur and id_jenis_pendaftaran_setting=tb_data_pendaftaran_jenis_pengaturan.id_jenis_pendaftaran_setting
) as mat_syarat,
(
select group_concat(nama_gelar separator '#')
 from view_nama_gelar_dosen inner join tb_data_pendaftaran_pembimbing on nip=nip_dosen_pembimbing where tb_data_pendaftaran_pembimbing.id_pendaftaran=tb_data_pendaftaran.id_pendaftaran order by pembimbing_ke asc
 ) as pembimbing
  from tb_data_pendaftaran_jadwal_ujian right join tb_data_pendaftaran on tb_data_pendaftaran_jadwal_ujian.id_pendaftaran=tb_data_pendaftaran.id_pendaftaran inner join view_simple_mhs on tb_data_pendaftaran.nim=view_simple_mhs.nim inner join tb_data_pendaftaran_jenis_pengaturan on tb_data_pendaftaran.id_jenis_pendaftaran_setting=tb_data_pendaftaran_jenis_pengaturan.id_jenis_pendaftaran_setting inner join tb_data_pendaftaran_jenis on tb_data_pendaftaran_jenis_pengaturan.id_jenis_pendaftaran=tb_data_pendaftaran_jenis.id_jenis_pendaftaran where tb_data_pendaftaran.status in('1') $sem_filter $jur_kode $fakultas $periode $jenis_pendaftaran and (
select group_concat(nip_dosen separator '#')
 from view_nama_gelar_dosen inner join tb_data_pendaftaran_penguji on nip=nip_dosen where tb_data_pendaftaran_penguji.id_jadwal_ujian=tb_data_pendaftaran_jadwal_ujian.id_jadwal_ujian order by penguji_ke asc
) like '%$dosen_nip%'",$columns);

  //buat inisialisasi array data
  $data = array();

  $ruang = "";
  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    
    $ResultData[] = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"> <input type="checkbox" class="group-checkable check-selected"> <span></span></label>';  
    $ResultData[] = $value->nim;
    $ResultData[] = $value->nama;
    $ResultData[] = $value->nama_jenis_pendaftaran;
    if ($value->tempat=='Ruangan') {
      $ruang = getRuangName($value->id_ruang);
    } elseif($value->tempat=='Daring') {
      $ruang = 'Daring';
    }
    if ($value->tanggal_ujian!="") {
    $ResultData[] = getHariFromDate($value->tanggal_ujian).', '.tgl_indo($value->tanggal_ujian);
    $ResultData[] = $value->jam_mulai.'-'.$value->jam_selesai;
    $ResultData[] = $ruang; 
    //$ResultData[] = getHariFromDate($value->tanggal_ujian).', '.tgl_indo($value->tanggal_ujian).', @ '.$ruang;  
    } else {
      $ResultData[] = '<span class="btn btn-xs btn-primary edit_data" data-toggle="tooltip" data-title="Atur Jadwal" data-id="'.$value->id_pendaftaran.'"><i class="fa fa-calendar"></i> Tambah Jadwal</span>';
    }
    if ($value->penguji!='') {
        $nama_dosen = array_map('trim', explode('#', $value->penguji));
        foreach ($nama_dosen as $index => $dosen_uji) {
          $dosen_penguji[] = ($index+1).'. '.$dosen_uji;
        }
        //$nama_dosen = trim(implode("<br>", $dosen_penguji));
       // $ResultData[] = $nama_dosen;
    }/* else {
      if ($value->tanggal_ujian!="") {
        $ResultData[] = '<span class="btn btn-xs btn-success edit_data" data-toggle="tooltip" data-title="Atur Jadwal" data-id="'.$value->id_pendaftaran.'"><i class="fa fa-user"></i> Tambah Dosen</span>';
      } else {
         $ResultData[] = '';
      }
      
    }*/
/*    if ($value->pembimbing!='') {
        $nama_dosen_pembimbing = array_map('trim', explode('#', $value->pembimbing));
        foreach ($nama_dosen_pembimbing as $nomor => $dosen) {
          $dosen_pembimbing[] = ($nomor+1).'. '.$dosen;
        }
        $nama_dosen_pembimbing = trim(implode("<br>", $dosen_pembimbing));
        $ResultData[] = $nama_dosen_pembimbing;
    } else {
      $ResultData[] = '<span class="btn btn-xs btn-warning" data-toggle="tooltip" data-title="Sidang ini tidak Ada Dosen pembimbing">None</span>';
    }*/
    if ($value->ada_judul=='Y') {
      $ResultData[] = strip_tags_content($value->judul);
    } else {
      $ResultData[] = '<span class="btn btn-xs btn-warning" data-toggle="tooltip" data-title="Sidang ini tidak Ada Dosen pembimbing">None</span>';
    }
    


    if ($value->mat_syarat!="") {
      if ($value->penguji!='' && $value->count_nilai_penguji==0) {
        $ResultData[] = '<span class="btn btn-xs btn-danger" data-toggle="tooltip" data-title="Penguji belum input nilai"><i class="fa fa-diamond"></i></span>';
      } elseif($value->penguji!='' && $value->count_nilai_penguji> 0 && $value->count_nilai_penguji < count($dosen_penguji)) {
        $ResultData[] = '<span class="btn btn-xs btn-warning lihat-nilai" data-id="'.$value->id_pendaftaran.'" data-toggle="tooltip" data-title="Penguji baru sebagian input nilai, klik untuk lihat"><i class="fa fa-diamond"></i></span>';
      } elseif($value->penguji!='' && $value->count_nilai_penguji==count($dosen_penguji)) {
        $ResultData[] = '<span class="btn btn-xs btn-success lihat-nilai" data-id="'.$value->id_pendaftaran.'" data-toggle="tooltip" data-title="Semua Penguji sudah input nilai, klik untuk lihat"><i class="fa fa-diamond"></i></span>';
      }
    } else {
      $ResultData[] = '';
    }
    $ResultData[] = $value->nama_jurusan;
    if ($value->mat_syarat!="") {
      $ResultData[] = '<a data-id="'.$value->id_pendaftaran.'"  class="btn btn-primary btn-sm edit_data " data-toggle="tooltip" title="Input Nilai Ujian"><i class="fa fa-pencil"></i> Input Nilai</a>';
    } else {
      $ResultData[] = '';
    }
    

    $data[] = $ResultData;
    $dosen_penguji = array();
    $dosen_pembimbing = array();
    $i++;
  }

//set data
$datatable2->setData($data);
//create our json
$datatable2->createData();

?>