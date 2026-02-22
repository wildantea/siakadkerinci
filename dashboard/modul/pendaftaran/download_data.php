<?php
session_start();
include "../../inc/config.php";
session_check_json();
require_once '../../inc/lib/Writer.php';
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
$writer = new XLSXWriter();
$style =
        array (
            array(
              'border' => array(
                'style' => 'thin',
                'color' => '000000'
                ),
            'allfilleddata' => true
            ),

            array(
                'fill' => array(
                    'color' => '00ff00'
                    ),
                'cells' => array(
                    'A1',
                    'B1',
                    'C1',
                    'D1',
                    'E1',
                    'F1',
                    'G1',
                    'H1',
                    'I1',
                    'J1',
                    'K1',
                    'L1',
                    'M1',
                    'N1',
                    'O1',
                    'P1',
                    'Q1',
                    'R1',
                    'S1',
                    'T1',
                    'U1',
                    'V1',
                    'W1',
                    'X1',
                    'Y1',
                    'Z1',
                    ),
                'border' => array(
                    'style' => 'thin',
                    'color' => '000000'
                    ),
                'verticalAlign' => 'center',
                'horizontalAlign' => 'center',
            ),
            );


$jur_kode_setting = "";
//get default akses prodi 
$akses_prodi = getAksesProdi();
if ($akses_prodi) {
  $jur_kode_setting = "and view_jenis_pendaftaran.kode_jur in(".$akses_prodi.")";
} else {
  //jika tidak group tidak punya akses prodi, set in 0
  $jur_kode_setting = "and view_jenis_pendaftaran.kode_jur in(0)";
}

$periode = "";
$status = "";
$jenis_pendaftaran = "";
$fakultas = "";
$sem_filter = "";

if (isset($_POST['jurusan'])) {
    if ($_POST['fakultas']!='all') {
      $fakultas = ' and view_simple_mhs.id_fakultas="'.$_POST['fakultas'].'"';
    }
    if ($_POST['jurusan']!='all') {
      $jur_kode_setting = ' and view_jenis_pendaftaran.kode_jur="'.$_POST['jurusan'].'"';
    }
    if ($_POST['sem_filter']!='all') {
      $sem_filter = ' and tb_data_pendaftaran.id_semester="'.$_POST['sem_filter'].'"';
    }
    if ($_POST['periode']!='all') {
      $periode = ' and EXTRACT( YEAR_MONTH FROM tb_data_pendaftaran.date_created )="'.$_POST['periode'].'"';
    }
    if ($_POST['jenis_pendaftaran']!='all') {
      $jenis_pendaftaran = ' and tb_data_pendaftaran_jenis.id_jenis_pendaftaran="'.$_POST['jenis_pendaftaran'].'"';
    }
    if ($_POST['status']!='all') {
      $status = ' and tb_data_pendaftaran.status="'.$_POST['status'].'"';
    }
}

$setting = $db2->fetchCustomSingle("SELECT view_jenis_pendaftaran.* from view_jenis_pendaftaran inner join tb_data_pendaftaran using(id_jenis_pendaftaran_setting)
where id_jenis_pendaftaran='".$_POST['jenis_pendaftaran']."' $jur_kode_setting");


$nama_pendaftaran = str_replace(" ", "_", $setting->nama_jenis_pendaftaran);


//column width
$col_width = array(
1 => 27,
2 => 27,
3 => 27,
4 => 40,
5 => 18,
6 => 20,
7 => 20,
8 => 20,
9 => 20,
10 => 25,
11 => 25,
12 => 20,
13 => 20,
14 => 20,
15 => 20
  );
$writer->setColWidth($col_width);

$header = array(
"NIM" => 'string',
"Nama" => "string",
"Jurusan" => "string",
);

if ($setting->ada_judul=='Y') {
    $header["Judul"] = 'string';
}
if ($setting->ada_penguji=='Y') {
    $header["Hari Ujian"] = 'string';
    $header["Tanggal Ujian"] = 'string';
    $header["Waktu"] = 'string';
    $header["Tempat"] = 'string';
}

if ($setting->ada_pembimbing=='Y') {
    for ($i=1; $i <=$setting->jumlah_pembimbing; $i++) { 
        $header["NIP Pembimbing $i"] = 'string';
        $header["Pembimbing $i"] = 'string';
    }
}

if ($setting->ada_penguji=='Y') {
    for ($j=1; $j <=$setting->jumlah_penguji; $j++) { 
        $header["NIP Penguji $j"] = 'string';
        $header["Penguji $j"] = 'string';
    }

}

if ($setting->has_attr=='Y') {
    $header_attr = json_decode($setting->attr_value);
    foreach ($header_attr as $attr_head) {
        $header[$attr_head->attr_label] = 'string';
    }
}




/*echo "<pre>";
print_r($header);

exit();*/


$jur_kode = "";
if ($akses_prodi) {
  $jur_kode = "and view_simple_mhs.jur_kode in(".$akses_prodi.")";
} else {
  //jika tidak group tidak punya akses prodi, set in 0
  $jur_kode = "and view_simple_mhs.jur_kode in(0)";
}

if ($_POST['jurusan']!='all') {
  $jur_kode = ' and view_simple_mhs.jur_kode="'.$_POST['jurusan'].'"';
}




$data_rec = array();


        $temp_rec = $db2->query(
            "select tb_data_pendaftaran.nim,view_simple_mhs.nama,tb_data_pendaftaran_jenis.nama_jenis_pendaftaran,tb_data_pendaftaran.date_created,
tb_data_pendaftaran.status,judul,view_simple_mhs.nama_jurusan,tb_data_pendaftaran.id_pendaftaran,

    (select group_concat(nama_gelar separator '#')
 from view_nama_gelar_dosen inner join tb_data_pendaftaran_pembimbing on view_nama_gelar_dosen.nip=tb_data_pendaftaran_pembimbing.nip_dosen_pembimbing where tb_data_pendaftaran_pembimbing.id_pendaftaran=tb_data_pendaftaran.id_pendaftaran order by pembimbing_ke asc) as pembimbing,
    (select group_concat(tb_data_pendaftaran_pembimbing.nip_dosen_pembimbing separator '#')
 from view_nama_gelar_dosen inner join tb_data_pendaftaran_pembimbing on view_nama_gelar_dosen.nip=tb_data_pendaftaran_pembimbing.nip_dosen_pembimbing where tb_data_pendaftaran_pembimbing.id_pendaftaran=tb_data_pendaftaran.id_pendaftaran order by pembimbing_ke asc) as nip_pembimbing,
tb_data_pendaftaran_jenis_pengaturan.id_jenis_pendaftaran_setting,tanggal_ujian,jam_mulai,jam_selesai,tempat,id_ruang,
  (select group_concat(nama_gelar separator '#')
 from view_nama_gelar_dosen inner join tb_data_pendaftaran_penguji on view_nama_gelar_dosen.nip=tb_data_pendaftaran_penguji.nip_dosen where tb_data_pendaftaran_penguji.id_jadwal_ujian=tb_data_pendaftaran_jadwal_ujian.id_jadwal_ujian order by penguji_ke asc
) as penguji,
  (select group_concat(tb_data_pendaftaran_penguji.nip_dosen separator '#')
 from view_nama_gelar_dosen inner join tb_data_pendaftaran_penguji on view_nama_gelar_dosen.nip=tb_data_pendaftaran_penguji.nip_dosen where tb_data_pendaftaran_penguji.id_jadwal_ujian=tb_data_pendaftaran_jadwal_ujian.id_jadwal_ujian order by penguji_ke asc
) as nip_penguji,
tb_data_pendaftaran.attr_value from tb_data_pendaftaran 
inner join view_simple_mhs on tb_data_pendaftaran.nim=view_simple_mhs.nim 
inner join tb_data_pendaftaran_jenis_pengaturan USING(id_jenis_pendaftaran_setting)
inner join tb_data_pendaftaran_jenis USING(id_jenis_pendaftaran)
left join tb_data_pendaftaran_jadwal_ujian using(id_pendaftaran) 
where tb_data_pendaftaran.id_pendaftaran is not null $jur_kode $fakultas $sem_filter $periode $jenis_pendaftaran $status
        "
        );
echo $db2->getErrorMessage();
        foreach ($temp_rec as $index => $key) {

            $data_rec[] = array(
                $key->nim,
                $key->nama,
                $key->nama_jurusan
            );

            if ($setting->ada_judul=='Y') {
                array_push($data_rec[$index], trimmer(strip_tags_content($key->judul)));
            }

            if ($setting->ada_penguji=='Y') {
                if ($key->tanggal_ujian!="") {
                    if ($key->tempat=='Ruangan') {
                      $ruang = getRuang($key->id_ruang);
                    } elseif($key->tempat=='Daring') {
                      $ruang = 'Daring';
                    } else {
                      $ruang = "";
                    }
                    array_push($data_rec[$index], getHariFromDate($key->tanggal_ujian));
                    array_push($data_rec[$index], tgl_indo($key->tanggal_ujian));
                    array_push($data_rec[$index], $key->jam_mulai.' - '.$key->jam_selesai);
                    array_push($data_rec[$index], $ruang);
                } else {
                    array_push($data_rec[$index], '');
                    array_push($data_rec[$index], '');
                    array_push($data_rec[$index], '');
                    array_push($data_rec[$index], '');
                }

            }


            if ($setting->ada_pembimbing=='Y') {
                $nama_dosen_pembimbing = array_map('trim', explode('#', $key->pembimbing));
                $nip_dosen_pembimbing = array_map('trim', explode('#', $key->nip_pembimbing));


                for ($i=0; $i <$setting->jumlah_pembimbing; $i++) { 
                    if( isset($nama_dosen_pembimbing[$i]) ){
                        array_push($data_rec[$index], $nip_dosen_pembimbing[$i]);
                        array_push($data_rec[$index], $nama_dosen_pembimbing[$i]);
                    } else {
                        array_push($data_rec[$index], "");
                        array_push($data_rec[$index], "");
                    }
                }
            }

            if ($setting->ada_penguji=='Y') {
                $dosen_penguji = array();
                if ($key->penguji!="") {
                    $nama_dosen_penguji = array_map('trim', explode('#', $key->penguji));
                    $nip_dosen_penguji = array_map('trim', explode('#', $key->nip_penguji));
                }


                for ($j=0; $j <$setting->jumlah_penguji; $j++) { 
                    if( isset($nama_dosen_penguji[$j]) ){
                        array_push($data_rec[$index], $nip_dosen_penguji[$j]);
                        array_push($data_rec[$index], $nama_dosen_penguji[$j]);
                    } else {
                        array_push($data_rec[$index], "");
                        array_push($data_rec[$index], "");
                    }
                }
            }

            if ($setting->has_attr=='Y') {
                $header_attrs = json_decode($setting->attr_value);
                $value_atribute = json_decode($key->attr_value);
                foreach ($header_attrs as $attr_head) {
                    $attribute_name = $attr_head->attr_name;
                    if (isset($value_atribute->{$attribute_name})) {
                      array_push($data_rec[$index], $value_atribute->{$attribute_name});
                    } else {
                       array_push($data_rec[$index], "");
                    }
                }
            }

            $i=0;
            $j=0;
            $nama_dosen_penguji = array();
            $nip_dosen_penguji = array();
            $nama_dosen_pembimbing = array();
            $nip_dosen_pembimbing = array();


        }
/*
        echo "<pre>";
print_r($header);

        print_r($data_rec);

        exit();
*/


        $filename = 'data_pendaftaran_'.$nama_pendaftaran.'.xlsx';
        header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        $writer->writeSheet($data_rec, 'Data Pendaftar', $header, $style);
        $writer->writeToStdOut();
        exit(0);
        ?>
