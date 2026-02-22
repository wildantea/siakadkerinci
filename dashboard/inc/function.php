<?php
$idt = $db->query("SELECT i.`isi` as header FROM identitas i WHERE i.`id_identitas`='1'");
foreach ($idt as $identitas) {
  $identitas = $identitas;
}
$idt2 = $db->query("SELECT i.`isi` as alamat FROM identitas i WHERE i.`id_identitas`='2'");
foreach ($idt2 as $identitas2) {
  $identitas2 = $identitas2;
}
$idt3 = $db->query("SELECT i.`isi` as kota FROM identitas i WHERE i.`id_identitas`='5'");
foreach ($idt3 as $identitas_kota) {
  $identitas_kota = $identitas_kota;
}
function session_check()
{
  if (empty($_SESSION['login'])) {
    echo "<script>alert('Sessio Anda Telah Habis'); window.location = '" . base_url() . "';</script>";
    exit();
  }
}
function getSemesterMahasiswa($nim, $periode)
{
  global $db2;
  $smt = $db2->fetchCustomSingle("select ((left($periode,4)-left(mulai_smt,4))*2)+right($periode,1)-(floor(right(mulai_smt,1)/2)) as smt from mahasiswa where nim=?", array('nim' => $nim));
  return $smt->smt;
}
function get_pejabat($jabatan)
{
  global $db;
  $q = $db->query("select d.nama_dosen,p.nip from pejabat p left join dosen d on d.nip=p.nip where p.jabatan='$jabatan'  ");
  $res = array();
  foreach ($q as $k) {
    $res['nip'] = $k->nip;
    $res['nama_pejabat'] = $k->nama_dosen;
  }
  return $res;
}
function upload_s3($type, $filename, $file, $file_type)
{
  global $db2;
  //get config
  $s3 = $db2->fetchSingleRow('s3_storage', 'type', $type);
  $bucket = $s3->bucket;

  $endpoint = $s3->url;

  $s3 = new Aws\S3\S3Client([

    "version" => "latest",

    "region" => "idn",
    'scheme' => 'http',

    "endpoint" => $endpoint,

    "use_path_style_endpoint" => true,

    "credentials" => [

      "key" => $s3->key,

      "secret" => $s3->secret

    ],

  ]);

  $result = $s3->putObject([

    "Bucket" => $bucket,

    "Key" => $filename,

    "Body" => "this is the body!",

    // you can use relative
    // "SourceFile" => "./aws-sdk-php-v3-developer-guide.pdf",

    // or absolute path
    "SourceFile" => $file,

    "ContentType" => $file_type,
    'ACL' => 'public-read',

  ]);

  return $result;

  //return $endpoint.'/'.$bucket.'/'.$filename;
}
function init_s3($type, $key, $secret, $endpoint)
{
  $s3 = new Aws\S3\S3Client([
    "version" => "latest",

    "region" => "idn",
    'scheme' => 'http',

    "endpoint" => $endpoint,

    "use_path_style_endpoint" => true,

    "credentials" => [

      "key" => $key,

      "secret" => $secret

    ],

  ]);
  return $s3;
}
function delete_s3($type, $file_name)
{
  global $db2;
  //get config
  $s3 = $db2->fetchSingleRow('s3_storage', 'type', $type);
  $bucket = $s3->bucket;
  $endpoint = $s3->url;
  $init = init_s3('file', $s3->key, $s3->secret, $endpoint);
  $results = $init->deleteObject(array(
    'Bucket' => $bucket,
    'Key' => $file_name
  ));
  return $results;
}
function getUser()
{
  global $db2;
  return $db2->fetchSingleRow("sys_users", "id", $_SESSION['id_user']);
}
function get_kajur($kode_jur)
{
  global $db;
  $q = $db->query("select d.nama_dosen,d.nip from jurusan p left join dosen d on d.id_dosen=p.ketua_jurusan where p.kode_jur='$kode_jur'  ");
  $res = array();
  foreach ($q as $k) {
    $res['nip'] = $k->nip;
    $res['nama_pejabat'] = $k->nama_dosen;
  }
  return $res;
}

function get_dekan($kode_fak)
{
  global $db;
  $q = $db->query("select d.nama_dosen,d.nip from fakultas p left join dosen d on d.id_dosen=p.dekan where p.kode_fak='$kode_fak'  ");
  $res = array();
  foreach ($q as $k) {
    $res['nip'] = $k->nip;
    $res['nama_pejabat'] = $k->nama_dosen;
  }
  return $res;
}

function ipk_terbilang($ipk)
{
  $terbilang = '';
  $array = str_split($ipk);
  foreach ($array as $char) {
    $terbilang .= getAngka($char) . ' ';
  }
  return $terbilang;
}

function getAngka($char)
{
  switch ($char) {
    case '1':
      return "satu";
      break;
    case '2':
      return "dua";
      break;
    case '3':
      return "tiga";
      break;
    case '4':
      return "empat";
      break;
    case '5':
      return "lima";
      break;
    case '6':
      return "enam";
      break;
    case '7':
      return "tujuh";
      break;
    case '8':
      return "delapan";
      break;
    case '9':
      return "sembilan";
      break;
    case '0':
      return "nol";
      break;
    case '.':
    case ',':
      return "koma";
      break;

  }
}

function get_kelamin($nim)
{
  global $db;
  $q = $db->query("select jk from mahasiswa where nim='$nim'  ");
  foreach ($q as $k) {
    return $k->jk;
  }
}

function cek_kuota_ppl($id_lokasi, $kode_jur = NULL, $kelamin = NULL)
{
  global $db;
  $res = array();

  $q = $db->query("select * from vlokasippl where id_lokasi='$id_lokasi' 
   and now() between concat(tgl_awal_daftar,' 00:00:00') and  concat(tgl_akhir_daftar,' 23:59:59')");
  if ($q->rowCount() > 0) {
    foreach ($q as $k) {

      if ($k->jml < $k->kuota) {
        $qq = $db->query("select 
            (select count(*) from ppl k join mahasiswa m on m.nim=k.nim where id_lokasi='$id_lokasi' and m.jur_kode='$kode_jur') as terisi,
            (select count(*) from ppl k join mahasiswa m on m.nim=k.nim where id_lokasi='$id_lokasi' and m.jk='L') as terisi_l,
            (select count(*) from ppl k join mahasiswa m on m.nim=k.nim where id_lokasi='$id_lokasi' and m.jk='P') as terisi_p,
            kuota, (select kuota_l from lokasi_ppl where id_lokasi='$id_lokasi') as kuota_l,
            (select kuota_p from lokasi_ppl where id_lokasi='$id_lokasi') as kuota_p,
            (kuota-(select count(*) from ppl k join mahasiswa m
            on m.nim=k.nim where id_lokasi='$id_lokasi' and m.jur_kode='$kode_jur' )) as sisa from kuota_jurusan_ppl k 
            where k.id_lokasi='$id_lokasi' and kode_jur='$kode_jur' ");
        if ($qq->rowCount() > 0) {
          foreach ($qq as $kk) {
            //jika kuota utama masih tersedia
            if ($kk->sisa > 0) {
              //jika pendaftar laki-laki
              if ($kelamin == 'L') {
                $sisa = $kk->kuota_l - $kk->terisi_l;
                if ($kk->kuota_l == 0) {
                  $res['status'] = false;
                  $res['pesan'] = "Kuota Laki-laki untuk lokasi $k->nama_lokasi tidak tersedia";
                } else {
                  if ($sisa > 0) {
                    $res['status'] = true;
                    $res['pesan'] = "Kuota Tersedia";
                  } else {
                    $res['status'] = false;
                    $res['pesan'] = "Kuota Laki-laki untuk lokasi $k->nama_lokasi sudah penuh";
                  }
                }

              }
              //jika pendaftar perempuan
              else {
                $sisa = $kk->kuota_p - $kk->terisi_p;
                if ($kk->kuota_p == 0) {
                  $res['status'] = false;
                  $res['pesan'] = "Kuota Perempuan untuk lokasi $k->nama_lokasi tidak tersedia";
                } else {
                  if ($sisa > 0) {
                    $res['status'] = true;
                    $res['pesan'] = "Kuota Tersedia";
                  } else {
                    $res['status'] = false;
                    $res['pesan'] = "Kuota Perempuan untuk lokasi $k->nama_lokasi sudah penuh";
                  }
                }

              }
            } else {
              $res['status'] = false;
              $res['pesan'] = "Kuota Jurusan Anda di lokasi $k->nama_lokasi sudah penuh";
            }
          }
        } else {
          $res['status'] = false;
          $res['pesan'] = "Tidak ada kuota untuk jurusan anda di lokasi $k->nama_lokasi";
        }
      } else {
        $res['status'] = false;
        $res['pesan'] = "Kuota Penuh";
      }
    }
  } else {
    $res['status'] = false;
    $res['pesan'] = "Waktu Pendaftaran/Perubahan terkait Lokasi Kukerta sudah habis";
  }

  return $res;
}

function cek_kuota_kukerta($id_lokasi, $kode_jur = NULL, $kelamin = NULL)
{
  global $db;
  $res = array();

  $q = $db->query("select * from vlokasikkn where id_lokasi='$id_lokasi' 
   and now() between concat(tgl_awal_daftar,' 00:00:00') and  concat(tgl_akhir_daftar,' 23:59:59')");
  if ($q->rowCount() > 0) {
    foreach ($q as $k) {

      if ($k->jml < $k->kuota) {
        $qq = $db->query("select 
            (select count(*) from kkn k join mahasiswa m on m.nim=k.nim where id_lokasi='$id_lokasi' and m.jur_kode='$kode_jur') as terisi,
            (select count(*) from kkn k join mahasiswa m on m.nim=k.nim where id_lokasi='$id_lokasi' and m.jk='L') as terisi_l,
            (select count(*) from kkn k join mahasiswa m on m.nim=k.nim where id_lokasi='$id_lokasi' and m.jk='P') as terisi_p,
            kuota, (select kuota_l from lokasi_kkn where id_lokasi='$id_lokasi') as kuota_l,
            (select kuota_p from lokasi_kkn where id_lokasi='$id_lokasi') as kuota_p,
            (kuota-(select count(*) from kkn k join mahasiswa m
            on m.nim=k.nim where id_lokasi='$id_lokasi' and m.jur_kode='$kode_jur' )) as sisa from kuota_jurusan_kkn k 
            where k.id_lokasi='$id_lokasi' and kode_jur='$kode_jur' ");
        if ($qq->rowCount() > 0) {
          foreach ($qq as $kk) {
            //jika kuota utama masih tersedia
            if ($kk->sisa > 0) {
              //jika pendaftar laki-laki
              if ($kelamin == 'L') {
                $sisa = $kk->kuota_l - $kk->terisi_l;
                if ($kk->kuota_l == 0) {
                  $res['status'] = false;
                  $res['pesan'] = "Kuota Laki-laki untuk lokasi $k->nama_lokasi tidak tersedia";
                } else {
                  if ($sisa > 0) {
                    $res['status'] = true;
                    $res['pesan'] = "Kuota Tersedia";
                  } else {
                    $res['status'] = false;
                    $res['pesan'] = "Kuota Laki-laki untuk lokasi $k->nama_lokasi sudah penuh";
                  }
                }

              }
              //jika pendaftar perempuan
              else {
                $sisa = $kk->kuota_p - $kk->terisi_p;
                if ($kk->kuota_p == 0) {
                  $res['status'] = false;
                  $res['pesan'] = "Kuota Perempuan untuk lokasi $k->nama_lokasi tidak tersedia";
                } else {
                  if ($sisa > 0) {
                    $res['status'] = true;
                    $res['pesan'] = "Kuota Tersedia";
                  } else {
                    $res['status'] = false;
                    $res['pesan'] = "Kuota Perempuan untuk lokasi $k->nama_lokasi sudah penuh";
                  }
                }

              }
            } else {
              $res['status'] = false;
              $res['pesan'] = "Kuota Jurusan Anda di lokasi $k->nama_lokasi sudah penuh";
            }
          }
        } else {
          $res['status'] = false;
          $res['pesan'] = "Tidak ada kuota untuk jurusan anda di lokasi $k->nama_lokasi";
        }
      } else {
        $res['status'] = false;
        $res['pesan'] = "Kuota Penuh";
      }
    }
  } else {
    $res['status'] = false;
    $res['pesan'] = "Waktu Pendaftaran/Perubahan terkait Lokasi Kukerta sudah habis";
  }

  return $res;
}

function get_total_sks($nim)
{
  global $db;
  $q = $db->query("SELECT ifnull(sum(sks),0) as sks  FROM `krs_detail` WHERE `nim` = '$nim' and batal='0' and bobot>0 ");
  foreach ($q as $k) {
    return $k->sks;
  }
}

function cek_status_semester($nim)
{
  global $db;
  $q = $db->query("select count(*) as jml
from akm a join semester_ref s on a.sem_id=s.id_semester
where (s.id_jns_semester='1' or s.id_jns_semester='2')
and a.mhs_nim='$nim' ");
  if ($q->rowCount() == 0) {
    return 0;
  } else {
    foreach ($q as $k) {
      return ($k->jml + 1);
    }
  }

}

function session_check_end()
{
  if (empty($_SESSION['login'])) {
    echo "<script>alert('Sessio Anda Telah Habis'); window.location = '" . base_url() . "';</script>";
    exit();
  }
}

function redirectJs($url)
{
  echo "<script>window.location = '" . $url . "';</script>";
}

function session_check_json()
{
  if (empty($_SESSION['login'])) {
    $json_response = array();
    $status['status'] = "die";
    array_push($json_response, $status);
    echo json_encode($json_response);
    exit();
  }
}


//submit form action json response
function action_response($error_message, $custom_response = array())
{
  $json_response = array();
  if ($error_message == '') {
    $status['status'] = "good";
    if (!empty($custom_response)) {
      foreach ($custom_response as $key => $value) {
        $status[$key] = $value;
      }

    }

  } else {
    $status['status'] = "error";
    $status['error_message'] = $error_message;
  }
  array_push($json_response, $status);
  echo json_encode($json_response);
  exit();
}

function getRuangName($id_ruang)
{
  global $db2;
  $ruangan = $db2->fetchSingleRow('ruang_ref', 'ruang_id', $id_ruang);

  if ($ruangan) {
    return $ruangan->nm_ruang;
  }
}



function get_akses_prodi()
{
  global $db;
  $data_prodi = array();
  $where = "";
  $get_akses_prodi = $db->fetch_single_row("sys_group_users", "id", $_SESSION['level']);
  if ($get_akses_prodi->akses_prodi != "") {
    $decode_prodi = json_decode($get_akses_prodi->akses_prodi);
    $where = "where kode_jur in(" . $decode_prodi->akses . ")";
  }
  return $where;
}

//uang
function rupiah($angka)
{

  $hasil_rupiah = number_format($angka, 0, ',', '.');
  return $hasil_rupiah;
}

//looping prodi berdasarkan akses prodi sesuai group users
function looping_prodi()
{
  global $db;
  $akses_prodi = get_akses_prodi();
  $jurusan = $db->query("select * from view_prodi_jenjang $akses_prodi");
  if ($jurusan->rowCount() < 1) {
    echo "<option value='' selected>Group User Ini Belum Punya Akses Prodi</option>";
  } else if ($jurusan->rowCount() == 1) {
    foreach ($jurusan as $dt) {
      echo "<option value='$dt->kode_jur' selected>$dt->jurusan</option>";
    }
  } else {
    echo "<option value='all'>Semua</option>";
    foreach ($jurusan as $dt) {
      echo "<option value='$dt->kode_jur'>$dt->jurusan</option>";
    }
  }
}

//looping prodi berdasarkan akses prodi sesuai group users
function looping_prodi_enc()
{
  global $db;
  require_once('encrypt.php');
  $enc = new Encrypt();
  $akses_prodi = get_akses_prodi();
  $jurusan = $db->query("select * from view_prodi_jenjang $akses_prodi");
  if ($jurusan->rowCount() < 1) {
    echo "<option value='' selected>Group User Ini Belum Punya Akses Prodi</option>";
  } else if ($jurusan->rowCount() == 1) {
    foreach ($jurusan as $dt) {
      echo "<option value='" . en($dt->kode_jur) . "' selected>$dt->jurusan</option>";
    }
  } else {
    echo "<option value='all'>Semua</option>";
    foreach ($jurusan as $dt) {
      if (array_key_exists("jur", $_GET) && $_GET['jur'] == en($dt->kode_jur)) {
        echo "<option value='" . en($dt->kode_jur) . "' selected>$dt->jurusan</option>";
      } else {
        echo "<option value='" . en($dt->kode_jur) . "'>$dt->jurusan</option>";
      }

    }
  }
}

//looping prodi berdasarkan akses prodi sesuai group users
function looping_matkul_kelas()
{
  global $db;
  $akses_prodi = get_akses_prodi();
  $jurusan = $db->query("select * from view_prodi_jenjang $akses_prodi");
  if ($jurusan->rowCount() == 1) {
    $akses_jur = $db->fetch_custom_single("select group_concat(kode_jur) as kode_jur from view_prodi_jenjang $akses_prodi");
    if ($akses_jur) {
      $jur_filter = "where vnk.kode_jur in(" . $akses_jur->kode_jur . ")";
    } else {
      //jika tidak group tidak punya akses prodi, set in 0
      $jur_filter = "where vnk.kode_jur in(0)";
    }
    //default semester aktif
    $sem_filter = "and vnk.sem_id='" . get_sem_aktif() . "'";
    $data = $db->query("select vnk.nm_matkul,vnk.id_matkul from view_nama_kelas vnk
        $jur_filter $sem_filter
group by vnk.id_matkul");
    echo "<option value='all'>Semua</option>";
    foreach ($data as $dt) {
      echo "<option value='$dt->id_matkul'>$dt->nm_matkul</option>";
    }
  } else {
    echo "<option value='all'>Semua</option>";
  }

}

//looping prodi berdasarkan akses prodi sesuai group users
function looping_kurikulum_kelas()
{
  global $db;
  $akses_prodi = get_akses_prodi();
  $jurusan = $db->query("select * from view_prodi_jenjang $akses_prodi");
  //jika hanya punya satu akses prodi
  if ($jurusan->rowCount() == 1) {
    $akses_jur = $db->fetch_custom_single("select group_concat(kode_jur) as kode_jur from view_prodi_jenjang $akses_prodi");
    if ($akses_jur) {
      $jur_filter = "where kode_jur in(" . $akses_jur->kode_jur . ")";
    } else {
      //jika tidak group tidak punya akses prodi, set in 0
      $jur_filter = "where kode_jur in(0)";
    }

    $data = $db->query("select kurikulum.kur_id,kurikulum.nama_kurikulum,view_semester.tahun_akademik from kurikulum
inner join view_semester on kurikulum.sem_id=view_semester.id_semester
        $jur_filter order by kurikulum.sem_id desc");
    echo "<option value=''>Pilih Kurikulum</option>";
    foreach ($data as $dt) {
      echo "<option value='$dt->kur_id'>$dt->nama_kurikulum $dt->tahun_akademik</option>";
    }
  } else {
    echo "<option value=''>Pilih Program Studi Dulu</option>";
  }

}

function get_tahun_akademik($sem)
{
  global $db;
  $semester = $db->fetch_single_row('view_semester', 'id_semester', $sem);
  return $semester->tahun_akademik;
}

function get_sem_aktif()
{
  global $db;
  $semester = $db->fetch_single_row('semester_ref', 'aktif', 1);
  return $semester->id_semester;
}

function get_sem_aktif_kkn()
{
  global $db;
  $semester = $db->fetch_single_row('priode_kkn', 'aktif', 1);
  return $semester->priode;
}

function cek_sudah_ambil_mk_kukerta($nim)
{
  global $db;
  // $q = $db->query("select kode_mk from krs_detail  where kode_mk in(select id_matkul from v_matkul_kukerta) and nim='$nim' 
  // and id_semester='".get_sem_aktif_kkn()."' "); 
  $q = $db->query("select kode_mk from krs_detail  where kode_mk in(select id_matkul from v_matkul_kukerta) and nim='$nim' ");
  if ($q->rowCount() > 0) {
    return true;
  } else {
    return false;
  }
}

function cek_sudah_ambil_mk_ppl($nim)
{
  global $db;
  $q = $db->query("select p.kode_mk,k.kode_mk from krs_detail k join v_matkul_ppl p on p.id_matkul=k.kode_mk where  k.nim='$nim'");
  if ($q->rowCount() > 0) {
    return true;
  } else {
    return false;
  }
}


function looping_semester()
{
  global $db;
  foreach ($db->fetch_all("view_semester") as $isi) {
    if ($isi->aktif == 1) {
      echo "<option value='$isi->id_semester' selected>$isi->tahun_akademik</option>";
      $aktif = $isi->tahun_akademik;
    } else {
      echo "<option value='$isi->id_semester'>$isi->tahun_akademik</option>";
    }

  }
}

//check if current date is periode krs or input nilai
/**
 * check current aktif per prodi
 * @param  string $type     pilihanya check krs atau lainya check periode input nilai
 * @param  int $periode  periode misal 20171
 * @param  int $kode_jur contoh 705
 * @return boolean
 */
function check_current_periode($type, $periode, $kode_jur)
{
  global $db;
  if ($type == 'krs') {
    $check = $db->query("select * from semester s where ((now() between s.tgl_mulai_krs and s.tgl_selesai_krs)
or (now() between s.tgl_mulai_pkrs and s.tgl_selesai_pkrs))
and id_semester='$periode' and kode_jur='$kode_jur'");
  } else {
    $check = $db->query("select * from semester s where now() between s.tgl_mulai_input_nilai and s.tgl_selesai_input_nilai and id_semester='$periode'  and kode_jur='$kode_jur'");
  }
  if ($check->rowCount() > 0) {
    return true;
  } else {
    return false;
  }

}


//for admin only
function session_check_adm()
{
  if ($_SESSION['group_level'] != 'admin') {
    exit();
  }
}
//redirection
function redirect($var)
{
  header("location:" . $var);
}


//root directory web
function base_url()
{
  $root = '';
  $protocol = isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';
  //$protocol = 'https://';
  $root = $protocol . $_SERVER['HTTP_HOST'];
  //$root .= dirname($_SERVER['SCRIPT_NAME']);
  $root .= "/" . DIR_MAIN;
  return $root;
}

//base admin is url until admin dir, ex:https://localhost/backend/admina
function base_admin()
{
  $root = '';
  $protocol = isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';
  //$protocol = 'https://';
  $root = $protocol . $_SERVER['HTTP_HOST'];
  $root .= "/" . DIR_ADMIN . "/";
  return $root;
}

//base admin is url until index.php, ex:https://localhost/backend/admina/index.php
function base_index()
{
  $root = '';
  $protocol = isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';
  // $protocol = 'https://';
  $root = $protocol . $_SERVER['HTTP_HOST'];
  $root .= str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']);
  $root .= 'index.php/';
  return $root;
}

//base admin is url until index.php, ex:http://localhost/backend/admina/index.php
function base_index_new()
{
  $root = '';
  $protocol = isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';
  // $protocol = 'https://';
  $root = $protocol . $_SERVER['HTTP_HOST'];
  //$root .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
  $root .= "/" . DIR_ADMIN . "/";
  $root .= 'index.php/';
  return $root;
}


//base admin is url until index.php, ex:https://localhost/backend/admina/index.php
function base_index_end()
{
  $root = '';
  $protocol = isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';
  // $protocol = 'https://';
  $root = $protocol . $_SERVER['HTTP_HOST'];
  $root .= SITE_ROOT;
  $root .= 'index.php/';
  return $root;
}


function validateDate($date, $format = 'Y-m-d')
{
  $d = DateTime::createFromFormat($format, $date);
  // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
  return $d && $d->format($format) === $date;
}

/**
 * return indonesian date format 
 * @param  text $date date text 2019-07-02
 * @return text       indonesian format 2 januari 2019
 */
function tgl_indo($date)
{ // fungsi atau method untuk mengubah tanggal ke format indonesia
  $date = substr($date, 0, 10);
  if (validateDate($date)) {
    // variabel BulanIndo merupakan variabel array yang menyimpan nama-nama bulan
    $BulanIndo = array(
      "Januari",
      "Februari",
      "Maret",
      "April",
      "Mei",
      "Juni",
      "Juli",
      "Agustus",
      "September",
      "Oktober",
      "November",
      "Desember"
    );

    $tahun = substr($date, 0, 4); // memisahkan format tahun menggunakan substring
    $bulan = substr($date, 5, 2); // memisahkan format bulan menggunakan substring
    $tgl = substr($date, 8, 2); // memisahkan format tanggal menggunakan substring

    $result = $tgl . " " . $BulanIndo[(int) $bulan - 1] . " " . $tahun;
    return ($result);
  } else {
    return '';
  }

}


function validateDateTime($date, $format = 'Y-m-d H:i:s')
{
  $d = DateTime::createFromFormat($format, $date);
  // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
  return $d && $d->format($format) === $date;
}
function tgl_time($date)
{ // fungsi atau method untuk mengubah tanggal ke format indonesia
  if (validateDateTime($date)) {
    // variabel BulanIndo merupakan variabel array yang menyimpan nama-nama bulan
    $BulanIndo = array(
      "Januari",
      "Februari",
      "Maret",
      "April",
      "Mei",
      "Juni",
      "Juli",
      "Agustus",
      "September",
      "Oktober",
      "November",
      "Desember"
    );

    $tahun = substr($date, 0, 4); // memisahkan format tahun menggunakan substring
    $bulan = substr($date, 5, 2); // memisahkan format bulan menggunakan substring
    $tgl = substr($date, 8, 2); // memisahkan format tanggal menggunakan substring
    $time = substr($date, -8);

    $result = $tgl . " " . $BulanIndo[(int) $bulan - 1] . " " . $tahun . ' ' . $time;
    return ($result);
  } else {
    return '';
  }

}

function get_atribut_mhs($nim)
{
  global $db;
  $q = $db->query("select * from mahasiswa m
      join jurusan j on m.jur_kode=j.kode_jur
    join fakultas f on j.fak_kode=f.kode_fak  where nim='$nim'
     ");
  foreach ($q as $k) {
    return $k;
  }
}

function get_jatah_sks($sem_id, $nim)
{
  global $db;
  $q = $db->query("select fungsi_get_jatah_sks('" . $nim . "','" . $sem_id . "') as jatah ");
  foreach ($q as $k) {
    return $k->jatah;
  }
}

function cek_kelas_penuh($id_kelas)
{
  global $db;
  $q = $db->query("select count(k.id_krs_detail) as terisi,
                  kl.peserta_max as max from krs_detail k
                  join kelas kl on k.id_kelas=kl.kelas_id
                  where k.id_kelas='$id_kelas' group by k.id_kelas ");
  foreach ($q as $k) {
    if (((int) $k->terisi) < ((int) $k->max)) {
      return false;
    } else {
      return true;
    }
  }
}

function cek_penuh_permatkul($id_matkul, $sem_id)
{
  global $db;
  $status = array();
  $q = $db->query("select count(k.id_krs_detail) as terisi,
                  kl.peserta_max as max from krs_detail k
                  right join kelas kl on k.id_kelas=kl.kelas_id
                  where kl.id_matkul in('$id_matkul') and kl.sem_id='$sem_id'   group by k.id_kelas ");
  foreach ($q as $k) {
    if (((int) $k->terisi) < ((int) $k->max)) {
      $status[] = 'kosong';
    }
  }
  if (empty($status)) {
    return true;
  } else {
    return false;
  }
}

//Fungsi cek prasyarat MK
function cek_prasyarat_mhs($id_mk, $mhs_id)
{
  // echo "<pre>";
  global $db;
  $ket = "";
  $ket2 = "";
  $ket3 = "";
  $q = $db->query("select * from prasyarat_mk where id_mk='" . $id_mk . "'");
  // return $q->rowCount();
  //jika tidak ada prasyarat
  if ($q->rowCount() == "0") {
    return "0";
  } else {

    $blm_lulus = 0;
    foreach ($q as $k) {
      $matkul = $k->id_mk;
      $matkul_prasyarat = $k->id_mk_prasyarat;
      $nama_mk = $db->fetch_single_row("matkul", "id_matkul", $matkul)->nama_mk;
      $nama_mk_prasyarat = $db->fetch_single_row("matkul", "id_matkul", $matkul_prasyarat)->nama_mk;
      $q2 = $db->query("select k.*,m.bobot_minimal_lulus from krs_detail k
                      join matkul m on m.id_matkul=k.kode_mk
                      where k.kode_mk='$matkul_prasyarat' and k.nim='$mhs_id'");

      if ($q2->rowCount() == 0) {
        $q3 = $db->query("select m.`*`, mm.nama_mk from matkul_setara m join matkul mm on mm.id_matkul=m.id_matkul_baru where m.id_matkul_lama='$matkul_prasyarat'");
        $setara_lulus = 0;
        $blm_ngambil_setara = 0;
        foreach ($q3 as $k_s) {
          $qs = $db->query("select k.*,m.bobot_minimal_lulus from krs_detail k
                            join matkul m on m.id_matkul=k.kode_mk
                           where k.kode_mk='$k_s->id_matkul_baru' and k.nim='$mhs_id'");
          if ($qs->rowCount() > 0) {
            foreach ($qs as $kk) {
              if ($kk->bobot < $kk->bobot_minimal_lulus) {
                //return "$nama_mk $nama_mk_prasyarat";
                $ket2 .= "- $nama_mk_prasyarat<br>";
                $blm_lulus++;
                $setara_lulus++;
              }
            }
          } else {
            $blm_ngambil_setara++;
          }

        }
        if ($blm_ngambil_setara == $q3->rowCount()) {
          $nama_mk_prasyarat = $db->fetch_single_row("matkul", "id_matkul", $matkul_prasyarat)->nama_mk;
          $ket2 .= "- $nama_mk_prasyarat<br>";
          $blm_lulus++;
        }
      } else {
        $setara_lulus = 0;
        foreach ($q2 as $kk) {
          if ($kk->bobot < $kk->bobot_minimal_lulus) {
            //return "$nama_mk $nama_mk_prasyarat";
            $ket2 .= "- $nama_mk_prasyarat<br>";
            $blm_lulus++;
          }
        }
      }

    }

    // $ket2 .="- $ket3<br>";
    if ($blm_lulus > 0) {
      $ket = "Tidak dapat mengambil mata kuliah $nama_mk karena belum lulus mata kuliah :<br>" . $ket2;
    } else {
      $ket = "0";
    }
    return $ket;
    // return $ket." Anda tidak dapat ambil mata kuliah $nama_mk karena belum lulus mata kuliah $nama_mk_prasyarat<br>";

  }
}

//Fungsi cek prasyarat MK
function cek_prasyarat($id_mk, $mhs_id)
{
  // echo "<pre>";
  global $db;
  $ket = "";
  $ket2 = "";
  $ket3 = "";
  $q = $db->query("select * from prasyarat_mk where id_mk='" . $id_mk . "'");
  // return $q->rowCount();
  //jika tidak ada prasyarat
  if ($q->rowCount() == "0") {
    return "0";
  } else {

    $blm_lulus = 0;
    foreach ($q as $k) {
      $matkul = $k->id_mk;
      $matkul_prasyarat = $k->id_mk_prasyarat;
      $nama_mk = $db->fetch_single_row("matkul", "id_matkul", $matkul)->nama_mk;
      $nama_mk_prasyarat = $db->fetch_single_row("matkul", "id_matkul", $matkul_prasyarat)->nama_mk;
      $q2 = $db->query("select k.*,m.bobot_minimal_lulus from krs_detail k join krs kr on kr.krs_id=k.id_krs
                      join matkul m on m.id_matkul=k.kode_mk
                      where k.kode_mk='$matkul_prasyarat' and kr.mhs_id='$mhs_id'");

      if ($q2->rowCount() == 0) {
        $q3 = $db->query("select m.`*`, mm.nama_mk from matkul_setara m join matkul mm on mm.id_matkul=m.id_matkul_baru where m.id_matkul_lama='$matkul_prasyarat'");
        $setara_lulus = 0;
        $blm_ngambil_setara = 0;
        foreach ($q3 as $k_s) {
          $qs = $db->query("select k.*,m.bobot_minimal_lulus from krs_detail k join krs kr on kr.krs_id=k.id_krs
                            join matkul m on m.id_matkul=k.kode_mk
                           where k.kode_mk='$k_s->id_matkul_baru' and kr.mhs_id='$mhs_id'");
          if ($qs->rowCount() > 0) {
            foreach ($qs as $kk) {
              if ($kk->bobot < $kk->bobot_minimal_lulus) {
                //return "$nama_mk $nama_mk_prasyarat";
                $ket2 .= "- $nama_mk_prasyarat<br>";
                $blm_lulus++;
                $setara_lulus++;
              }
            }
          } else {
            $blm_ngambil_setara++;
          }

        }
        if ($blm_ngambil_setara == $q3->rowCount()) {
          $nama_mk_prasyarat = $db->fetch_single_row("matkul", "id_matkul", $matkul_prasyarat)->nama_mk;
          $ket2 .= "- $nama_mk_prasyarat<br>";
          $blm_lulus++;
        }
      } else {
        $setara_lulus = 0;
        foreach ($q2 as $kk) {
          if ($kk->bobot < $kk->bobot_minimal_lulus) {
            //return "$nama_mk $nama_mk_prasyarat";
            $ket2 .= "- $nama_mk_prasyarat<br>";
            $blm_lulus++;
          }
        }
      }

    }

    // $ket2 .="- $ket3<br>";
    if ($blm_lulus > 0) {
      $ket = "Tidak dapat mengambil mata kuliah $nama_mk karena belum lulus mata kuliah :<br>" . $ket2;
    } else {
      $ket = "0";
    }
    return $ket;
    // return $ket." Anda tidak dapat ambil mata kuliah $nama_mk karena belum lulus mata kuliah $nama_mk_prasyarat<br>";

  }
}

function get_semester_aktif($kode_jur = NULL)
{
  global $db;
  //$q= $db->query("select id_semester,sem_id from semester where is_aktif='1' and kode_jur='$kode_jur' ");
  $q = $db->query("select id_semester from semester_ref where aktif='1' ");
  foreach ($q as $k) {
    return $k;
  }
}

function get_semester_aktif_mhs($sem_id, $nim)
{
  global $db;
  $q = $db->query("select akm_id from akm where mhs_nim='$nim' and sem_id='$sem_id' ");
  foreach ($q as $k) {
    return $k->akm_id;
  }
}

function get_avaliable_tanggal($jenis, $sem_id)
{
  global $db;
  if ($jenis == 'krs') {
    $q = "select * from semester s where now() between
               s.tgl_mulai_krs and s.tgl_selesai_krs and sem_id='$sem_id'";
  } else if ($jenis == 'pkrs') {
    $q = "select * from semester s where now() between
               s.tgl_mulai_pkrs and s.tgl_selesai_pkrs and sem_id='$sem_id'";
  } else if ($jenis == 'input_nilai') {
    $q = "select * from semester s where now() between
               s.tgl_mulai_input_nilai and s.tgl_selesai_input_nilai and sem_id='$sem_id'";
  }
  $qu = $db->query($q);
  if ($qu->rowCount() > 0) {
    return true;
  } else {
    return false;
  }
}

function cek_status_registrasi($mhs_id, $sem_id)
{
  global $db;
  $q = $db->query("select * from mhs_registrasi m where m.sem_id='$sem_id' and m.nim='$mhs_id'");
  if ($q->rowCount() > 0) {
    return true;
  } else {
    return false;
  }
}


function tampil_periode($sem_aktif)
{
  $sem = substr($sem_aktif, 0, 4);
  $sem2 = $sem + 1;
  if (substr($sem_aktif, -1) == '1') {
    $periode = "Ganjil";
  } else {
    $periode = "Genap";
  }
  return $periode . " $sem/$sem2";
}

function get_kode_jur_by_nim($nim)
{
  global $db;
  $q = $db->query("select jur_kode from mahasiswa where nim='$nim'");
  foreach ($q as $k) {
    return $k->jur_kode;
  }
}

function clean($string)
{
  return preg_replace('/[^\da-z ]/i', '', $string);// Removes special chars.
}

function buat_akm($data_akm)
{
  global $db;
  $q = $db->query("select * from akm where mhs_nim='" . $data_akm['mhs_nim'] . "'
    and sem_id='" . $data_akm['sem_id'] . "' ");
  if ($q->rowCount() == 0) {
    // $data = array('mhs_nim' => $nim, 'sem_id' => $sem);
    $db->insert("akm", $data_akm);
  }
}



/*function update_akm($nim){
 //  error_reporting(0);
   global $db;

   $q=$db->query("select s.id_semester from krs_detail k join semester_ref s on k.id_semester=s.id_semester
                  join mahasiswa m on m.nim=k.nim where k.nim='$nim' group by s.id_semester order by s.id_semester asc "); 
   $ipk=0;
   $bobot_ipk=0;  
   $sks_ipk=0;
 //  echo "<pre>";
    $total_sks =0; 
   foreach ($q as $k) {
     $qq = $db->query("select akm_id from akm where mhs_nim='$nim' and sem_id='$k->id_semester' ");
     if ($qq->rowCount()==0) {
       $datax = array('sem_id' => $k->id_semester ,
                      'mhs_nim' => $nim);
       $db->insert("akm",$datax); 
      // print_r($datax); 
     }
      $ip  = 0; 
      $ipk = 0; 
      // echo "select sum(k.sks) as jml_sks, 
      //   fungsi_get_jatah_sks('$nim','$k->id_semester') as jatah,
      //  sum(k.bobot * k.sks) as jml_bobot from krs_detail k
      //                      where k.nim='$nim' and k.id_semester='$k->id_semester' and k.batal='0' 
      //                      group by k.id_semester ";
     // echo "select ifnull(sum(k.sks),0) as jml_sks, 
     //    ifnull(fungsi_get_jatah_sks('$nim','$k->id_semester'),0) as jatah,
     //   ifnull(sum(k.bobot * k.sks),0) as jml_bobot from krs_detail k
     //                       where k.nim='$nim' and k.id_semester='$k->id_semester' and k.batal='0' 
     //                       group by k.id_semester order by k.id_semester asc";
      foreach ($db->query("select ifnull(sum(k.sks),0) as jml_sks, 
        ifnull(fungsi_get_jatah_sks('$nim','$k->id_semester'),0) as jatah,
       ifnull(sum(k.bobot * k.sks),0) as jml_bobot from krs_detail k
                           where k.nim='$nim' and k.id_semester='$k->id_semester' and k.batal='0' 
                           group by k.id_semester order by k.id_semester asc ")
              as $kk) { 
        // print_r($kk); 

         $total_sks = $total_sks + $kk->jml_sks; 
         $bobot_ipk = $bobot_ipk + $kk->jml_bobot;
         $jatah     = $kk->jatah; 
         $sks_ipk   = $sks_ipk + $kk->jml_sks;
         $ipk       = $bobot_ipk/$sks_ipk;
        // $sks_diambil = $kk->jml_sks;
         if ($kk->jml_sks==0 || $kk->jml_sks=='') {
            $ip        = 0;  
         }else{
           $ip        = $kk->jml_bobot/$kk->jml_sks; 
         }
        //  echo "$total_sks,";

         $db->query("update akm set jatah_sks='$jatah', ip='".number_format($ip,2)."',ipk='".number_format($ipk,2)."',
         sks_diambil='$kk->jml_sks',total_sks='$total_sks' where sem_id='$k->id_semester' and mhs_nim='$nim' ");
      //   echo "update akm set ip='".number_format($ip,2)."',ipk='".number_format($ipk,2)."' where sem_id='$k->id_semester' and mhs_nim='$nim'  <br>";
      }
   }
}*/

function update_akm($nim)
{
  global $db2;
  $mhs = $db2->fetchSingleRow("mahasiswa", "nim", $nim);
  $array_semester = array();

  //check if he has semester nilai pindah
  $sm_pindah = $db2->fetchCustomSingle("select group_concat(distinct id_semester) as id_semester from krs_detail where nim='" . $nim . "' and id_semester='10'");
  if ($sm_pindah) {
    $semester_pindah = explode(",", $sm_pindah->id_semester);
    $array_semester = array_merge($array_semester, $semester_pindah);
  }
  $array_semester = array_filter($array_semester);

  //check if he has semester pendek
  $sm_pendek = $db2->fetchCustomSingle("select group_concat(distinct id_semester) as id_semester from krs_detail where nim='" . $nim . "' and right(id_semester,1)='3'");
  if ($sm_pendek) {
    $semester_pendek = explode(",", $sm_pendek->id_semester);
    $array_semester = array_merge($array_semester, $semester_pendek);
  }
  $array_semester = array_filter($array_semester);
  //loop over semester from semester awal to current semester
  $loop_data_semester = $db2->fetchCustomSingle("select group_concat(distinct id_semester) as id_semester from semester where (id_semester>='" . $mhs->mulai_smt . "' and id_semester<='" . getSemesterAktif() . "') and right(id_semester,1) in(1,2)");
  $semester_data = explode(",", $loop_data_semester->id_semester);
  $array_semester = array_merge($array_semester, $semester_data);
  $array_semester = array_unique($array_semester);
  sort($array_semester);

  //dump($array_semester);
  //first check semester awal masuk

  if (!empty($array_semester)) {
    $mhs1 = $db2->fetchSingleRow("view_simple_mhs_data", "nim", $nim);
    $where_nim_mhs = "and mahasiswa.nim='" . $nim . "'";
    foreach ($array_semester as $sem_id) {
      //insert unprocess akm
      $db2->query("insert ignore into akm (mhs_nim,sem_id,id_stat_mhs,ip,ipk,jatah_sks,sks_diambil,sks_wajib,sks_pilihan,total_sks,date_created)
                select nim," . $sem_id . ",'N',0,0,0,0,0,0,0,now() from mahasiswa where nim not in(select mhs_nim from akm where mhs_nim=mahasiswa.nim and  sem_id=" . $sem_id . ") $where_nim_mhs");
      echo $db2->getErrorMessage();
      $array_s1_s3 = array('30', '40');
      if (in_array($mhs1->id_jenjang, $array_s1_s3)) {
        //delete akm yang statusnya lebih dari 14 semester
        $db2->query("delete akm from akm inner join view_simple_mhs_data on mhs_nim=nim where sem_id='$sem_id' and mhs_nim='$mhs1->nim' and ((left('$sem_id',4)-left(mulai_smt,4))*2)+right('$sem_id',1)-(floor(right(mulai_smt,1)/2)) > 14");
      }

      //delete akm in semester perbaikan if has no sks
      if (substr($sem_id, -1) == '3') {
        $db2->query("delete akm from akm inner join mahasiswa on mhs_nim=mahasiswa.nim where sem_id='$sem_id'  and mhs_nim='$mhs1->nim' and mhs_nim not in(select nim from krs_detail where krs_detail.id_semester='" . $sem_id . "')");
      }

      $array_s2 = array('35', '40');
      if (in_array($mhs1->id_jenjang, $array_s2)) {
        //delete akm yang statusnya lebih dari 14 semester
        $db2->query("delete akm from akm inner join view_simple_mhs_data on mhs_nim=nim where sem_id='$sem_id' and mhs_nim='$mhs1->nim' and ((left('$sem_id',4)-left(mulai_smt,4))*2)+right('$sem_id',1)-(floor(right(mulai_smt,1)/2)) > 8");
      }


      if ($sem_id == '10') {
        $db2->query("delete akm from akm inner join mahasiswa on mhs_nim=mahasiswa.nim where sem_id='$sem_id'  and mhs_nim='$mhs1->nim' and mhs_nim not in(select nim from krs_detail where krs_detail.id_semester='" . $sem_id . "')");
      }


    }
  }


  $array_semester = array_filter($array_semester);
  if (!empty($array_semester)) {


    $implode_semester = implode(",", $array_semester);



    $datas = $db2->query("select akm_id,id_stat_mhs,akm.mhs_nim,akm.sem_id,(select sum(sks) from krs_detail inner join matkul 
on krs_detail.kode_mk=matkul.id_matkul
 where nim=akm.mhs_nim and id_semester=akm.sem_id  and krs_detail.disetujui='1' and batal=0) as sks,
(select format(sum(bobot * sks)/sum(sks),2) from krs_detail inner join matkul 
on krs_detail.kode_mk=matkul.id_matkul
 where nim=akm.mhs_nim and id_semester=akm.sem_id and krs_detail.disetujui='1' and batal=0 and bobot  is not null) as ip,
(select format(sum(bobot * sks)/sum(sks),2) from krs_detail inner join matkul 
on krs_detail.kode_mk=matkul.id_matkul
 where nim=akm.mhs_nim and id_semester<=akm.sem_id  and krs_detail.disetujui='1' and batal=0 and bobot is not null) as ipk,
(select sks_mak from jatah_sks j where  IFNULL((select akm.ip  from akm akm where akm.mhs_nim=akm.mhs_nim
 and akm.sem_id<akm.sem_id
and akm.id_stat_mhs='A' ORDER BY sem_id DESC LIMIT 1),4) BETWEEN j.ip_min and j.ip_mak) as jatah_sks,
(select SUM(IF(a_wajib = '1', sks,0)) from krs_detail 
inner join matkul on krs_detail.kode_mk=id_matkul where nim=akm.mhs_nim and id_semester=akm.sem_id  and krs_detail.disetujui='1' and batal=0) as sks_wajib_diambil_kumulatif,
(select SUM(IF(a_wajib = '0', sks,0)) from krs_detail
inner join matkul on krs_detail.kode_mk=id_matkul where nim=akm.mhs_nim and id_semester=akm.sem_id  and krs_detail.disetujui='1' and batal=0) as sks_pilihan_diambil_kumulatif,
(select SUM(sks) from krs_detail 
inner join matkul on krs_detail.kode_mk=id_matkul where nim=akm.mhs_nim and id_semester<=akm.sem_id  and krs_detail.disetujui='1' and batal=0) as sks_kumulatif,
(select nim from tb_data_cuti_mahasiswa inner join tb_data_cuti_mahasiswa_periode using(id_cuti) where nim=akm.mhs_nim and status_acc!='rejected' and periode=akm.sem_id limit 1) as is_cuti
 from akm 
inner join mahasiswa  on akm.mhs_nim=mahasiswa.nim where akm.sem_id in($implode_semester) and akm.mhs_nim='" . $nim . "'");


    if ($datas->rowCount() > 0) {
      foreach ($datas as $value) {
        //dump($value);
        if ($value->sks == "") {
          $sks_semester = 0;
        } else {
          $sks_semester = $value->sks;
        }
        if ($value->ip == "") {
          $ip = 0;
        } else {
          $ip = $value->ip;
        }
        if ($value->ipk == "") {
          $ipk = 0;
        } else {
          $ipk = $value->ipk;
        }
        if ($value->sks_wajib_diambil_kumulatif == "") {
          $sks_wajib_kumulatif = 0;
        } else {
          $sks_wajib_kumulatif = $value->sks_wajib_diambil_kumulatif;
        }
        if ($value->sks_pilihan_diambil_kumulatif == "") {
          $sks_pilihan_kumulatif = 0;
        } else {
          $sks_pilihan_kumulatif = $value->sks_pilihan_diambil_kumulatif;
        }

        if ($value->jatah_sks == "") {
          $jatah_sks = 0;
        } else {
          $jatah_sks = $value->jatah_sks;
        }
        if ($value->sks_kumulatif == "") {
          $sks_total = 0;
        } else {
          $sks_total = $value->sks_kumulatif;
        }

        if ($value->id_stat_mhs == 'N' && $sks_semester > 0) {
          $id_stat_mhs = 'A';
        } elseif ($value->id_stat_mhs == 'A' && $sks_semester == 0) {
          $id_stat_mhs = 'N';
        } else {
          $id_stat_mhs = $value->id_stat_mhs;
        }
        if ($value->is_cuti != '') {
          $id_stat_mhs = 'C';
        }

        $data_update_akm[] = array(
          'ip' => $ip,
          'ipk' => $ipk,
          'id_stat_mhs' => $id_stat_mhs,
          'jatah_sks' => $jatah_sks,
          'sks_diambil' => $sks_semester,
          'sks_wajib' => $sks_wajib_kumulatif,
          'sks_pilihan' => $sks_pilihan_kumulatif,
          'total_sks' => $sks_total,
          'unik_id' => 0,
          'date_updated' => date('Y-m-d H:i:s')
        );
        $nim_mhs_periode[$value->mhs_nim][] = $value->sem_id;
        $data_id_update[] = $value->akm_id;

      }

      if (!empty($data_update_akm)) {
        $db2->updateMulti('akm', $data_update_akm, 'akm_id', $data_id_update);
        echo $db2->getErrorMessage();
      }


    }
  }

}
function sksDiambilMhs($nim, $id_semester)
{
  global $db;
  $sks_diambil = $db->fetch_custom_single("select COALESCE(sum(krs_detail.sks), 0) sks_diambil from krs_detail left join kelas
on id_kelas=kelas_id
left join matkul
using(id_matkul) where nim=? and id_semester=? and batal=0", array("nim" => $nim, "id_semester" => $id_semester));
  if ($sks_diambil) {
    return $sks_diambil->sks_diambil;
  }
}
function waktu_import($waktu)
{

  $hours = floor($waktu / 3600);
  $minutes = floor(($waktu / 60) % 60);
  $seconds = $waktu % 60;

  return ($hours < 1 ? '' : $hours . ' Jam') . ($minutes < 1 ? '' : $minutes . ' Menit') . $seconds . ' Detik';
}

/**
 * get data jurusan lokal from input jurusan dikti
 * @return [type] [description]
 */
function get_prodi_lokal()
{
  global $db;
  $array_jur_lokal = array();
  $array_jur_query = $db->query("select kode_dikti, kode_jur from jurusan");
  foreach ($array_jur_query as $jur_lokal) {
    $array_jur_lokal[$jur_lokal->kode_dikti] = $jur_lokal->kode_jur;
  }
  return $array_jur_lokal;
}

/**
 * get data jurusan lokal from input jurusan dikti
 * @return [type] [description]
 */
function get_prodi_dikti()
{
  global $db;
  $array_jur_lokal = array();
  $array_jur_query = $db->query("select kode_dikti, kode_jur from jurusan");
  foreach ($array_jur_query as $jur_lokal) {
    $array_jur_lokal[$jur_lokal->kode_jur] = $jur_lokal->kode_dikti;
  }
  return $array_jur_lokal;
}
function get_label_kelas()
{
  global $db;
  $array_jur_lokal = array();
  $array_jur_query = $db->query("select kode_paralel, nm_paralel from paralel_kelas_ref");
  foreach ($array_jur_query as $jur_lokal) {
    $array_jur_lokal[$jur_lokal->kode_paralel] = $jur_lokal->nm_paralel;
  }
  return $array_jur_lokal;
}

/**
 * get token briva
 * @return [string] [description]
 */

function get_token_briva()
{
  global $db;
  $url = "https://partner.api.bri.co.id/oauth/client_credential/accesstoken?grant_type=client_credentials";
  $data = "client_id=" . client_id . "&client_secret=" . client_secret;
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");  //for updating we have to use PUT method.
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
  $result = curl_exec($ch);
  $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);

  $json = json_decode($result, true);
  $accesstoken = $json['access_token'];

  return $accesstoken;
}

/*function generate_tagihan_sks()
{
  global $db;
  $q=$db->query("select * from keu_jenis_pembayaran where kode_pembayaran='SKS' ");
  if ($q->rowCount()==0) {
    $db->query("insert into keu_jenis_pembayaran values ('SKS','SKS')");
  }
  $qq=$db->query("select * from keu_jenis_tagihan t where t.kode_tagihan='SKS_REG'");
  if ($qq->rowCount()==0) {
    $db->query("insert into keu_jenis_tagihan values ('SKS_REGULER','SKS MAHASISWA REGULER','SKS','N')");
  }
  $qn=$db->query("select * from keu_jenis_tagihan t where t.kode_tagihan='SKS_NON'");
  if ($qn->rowCount()==0) {
    $db->query("insert into keu_jenis_tagihan values ('SKS_NON','SKS MAHASISWA NON REGULER','SKS','N')");
  }
  $qj=$db->query("select kode_jur from jurusan");
  foreach ($qj as $k) {
     $qa=$db->query("select m.mulai_smt from mahasiswa m group by m.mulai_smt");
     foreach ($qa as $ka) {
         $qt=$db->query("select * from keu_tagihan t where t.kode_prodi='$k->kode_jur'
         and t.kode_tagihan='SKS_REGULER' and t.berlaku_angkatan='$ka->mulai_smt' ");
         if ($qt->rowCount()==0) {
            $data = array('kode_prodi'       => $k->kode_jur ,
                          'kode_tagihan'     => 'SKS_REGULER',
                          'nominal_tagihan'  => get_tarif_sks('reguler') ,
                          'berlaku_angkatan' => $ka->mulai_smt,
                          'ket' => 'sks');
            $db->insert("keu_tagihan",$data);
         }else{
           foreach ($qt as $kt) {
             $id_keu = $kt->id;
           }
           $data = array('kode_prodi'       => $k->kode_jur ,
                         'kode_tagihan'     => 'SKS_REGULER',
                         'nominal_tagihan'  => get_tarif_sks('reguler') ,
                         'berlaku_angkatan' => $ka->mulai_smt,
                         'ket' => 'sks');
           $db->update("keu_tagihan",$data,"id",$id_keu);
         }
         $qtt=$db->query("select * from keu_tagihan t where t.kode_prodi='$k->kode_jur'
         and t.kode_tagihan='SKS_NON' and t.berlaku_angkatan='$ka->mulai_smt' ");
         if ($qtt->rowCount()==0) {
            $dataxx = array('kode_prodi'       => $k->kode_jur ,
                           'kode_tagihan'     => 'SKS_NON',
                           'nominal_tagihan'  => get_tarif_sks('non'),
                           'berlaku_angkatan' => $ka->mulai_smt,
                           'ket' => 'sks');
            // print_r($dataxx);
            // echo "<br>";
            $db->insert("keu_tagihan",$dataxx);
         }else{
           foreach ($qtt as $ktt) {
             $id_keu = $ktt->id;
           }
           $dataxx = array('kode_prodi'       => $k->kode_jur ,
                          'kode_tagihan'     => 'SKS_NON',
                          'nominal_tagihan'  => get_tarif_sks('non'),
                          'berlaku_angkatan' => $ka->mulai_smt,
                          'ket' => 'sks');
           $db->update("keu_tagihan",$dataxx,"id",$id_keu);
         }
     }
  }
}*/

function get_tarif_sks($kode_prodi, $ket, $kode_tagihan, $berlaku_angkatan)
{
  global $db;
  // $q=$db->query("select nominal from tarif_sks where ket='$ket' ");
  $q = $db->query("select nominal_tagihan from keu_tagihan k
    where k.kode_prodi='$kode_prodi'
    and k.ket='$ket'
    and k.kode_tagihan='$kode_tagihan'
    and k.berlaku_angkatan='$berlaku_angkatan'");
  foreach ($q as $k) {
    return $k->nominal_tagihan;
  }
}

function get_id_tagihan($kode_prodi, $kode_tagihan, $angkatan)
{
  global $db;
  $q = $db->query("select id from keu_tagihan t where t.kode_prodi='$kode_prodi'
        and t.berlaku_angkatan='$angkatan' and t.kode_tagihan='$kode_tagihan' ");
  foreach ($q as $k) {
    return $k->id;
  }
}

function buat_tagihan($nim, $id_tagihan_prodi, $periode)
{
  global $db;
  $q = $db->query("select * from keu_tagihan_mahasiswa WHERE
       nim='$nim' and id_tagihan_prodi='$id_tagihan_prodi'
       and periode='$periode' ");
  if ($q->rowCount() == 0) {
    $data = array(
      "nim" => $nim,
      "id_tagihan_prodi" => $id_tagihan_prodi,
      "periode" => $periode
    );
    $db->insert("keu_tagihan_mahasiswa", $data);
  }

}

// function get_kajur($kode_jur)
// {
//   global $db;
//   $q=$db->query("select d.nama_dosen, d.nip from jurusan j left join dosen d on d.id_dosen=j.ketua_jurusan
//     where j.kode_jur='$kode_jur'");
//     $data=array();
//     foreach ($q as $k) {
//       $data['nip'] = $k->nip;
//       $data['nama'] = $k->nama_dosen;
//     }
//     return $data;
// }

function get_foto($username)
{
  global $db;
  $q = $db->query("select s.foto_user from sys_users s where s.username='$username' ");
  $data = array();
  foreach ($q as $k) {
    return $k->foto_user;
  }
  //return $data;
}

function get_jenjang($kode_jur)
{
  global $db;
  $q = $db->query("select jp.jenjang from jurusan j join jenjang_pendidikan jp
on jp.id_jenjang=j.id_jenjang where j.kode_jur='$kode_jur'");
  $data = array();
  foreach ($q as $k) {
    return $k->jenjang;
  }
  //return $data;
}

// function get_pejabat($id)
// {
//   global $db;
//   $q = $db->query("select p.jabatan,p.id_pejabat,d.nama_dosen from pejabat p join dosen d
// on d.nip=p.nama_pejabat where p.id_pejabat='$id' ");
//   foreach ($q as $k) {
//     return $k;
//   }
// }

//looping prodi berdasarkan akses prodi sesuai group users
/**
 * get periode pendaftaran seminar/sidang
 * @param  varchar $kode_pendaftaran kode pendaftaran dari table jenis_pendaftaran
 * @return string                  dropdown select
 */
function looping_periode_pendaftaran($kode_pendaftaran = null)
{
  global $db;
  $akses_prodi = get_akses_prodi();
  $jurusan = $db->query("select * from view_prodi_jenjang $akses_prodi");
  if ($jurusan->rowCount() == 1) {
    $akses_jur = $db->fetch_custom_single("select group_concat(kode_jur) as kode_jur from view_prodi_jenjang $akses_prodi");
    if ($akses_jur) {
      $jur_filter = "where tdj.kode_jur in(" . $akses_jur->kode_jur . ")";
    } else {
      //jika tidak group tidak punya akses prodi, set in 0
      $jur_filter = "where tdj.kode_jur in(0)";
    }

    $kode_filter;
    if ($kode_pendaftaran != null) {
      $kode_filter = "and tj.kode='" . $kode_pendaftaran . "'";
    }
    //default semester aktif
    $sem_filter = "and tdj.semester='" . get_sem_aktif() . "'";
    $data = $db->query("select tdj.id, tdj.periode_bulan,status_aktif from tb_data_jadwal_pendaftaran tdj inner join tb_jenis_pendaftaran tj on tdj.id_pendaftaran=tj.id $jur_filter $sem_filter $kode_filter");
    echo "<option value='all'>Semua</option>";
    foreach ($data as $dt) {
      if ($dt->status_aktif == 'Y') {
        echo "<option value='$dt->id' selected>" . bulan_tahun($dt->periode_bulan) . "</option>";
      } else {
        echo "<option value='$dt->id'>" . bulan_tahun($dt->periode_bulan) . "</option>";
      }

    }
  } else {
    echo "<option value='all'>Semua</option>";
  }
}

function get_jumlah_sks_matkul($id_matkul)
{
  global $db;
  $jml_matkul = $db->fetch_custom_single("select (sks_tm+sks_prak+sks_sim+sks_prak_lap) as jml_sks from matkul where id_matkul=?", array('id_matkul' => $id_matkul));
  return $jml_matkul->jml_sks;
}
function hari($tgl)
{
  if ($tgl != "") {
    $hari = date('l', strtotime($tgl));
    $hari = getHari($hari);
  } else {
    $hari = "";
  }

  return $hari;

}

function getHari($hari)
{
  switch ($hari) {
    case 'Sunday':
      return "Minggu";
      break;
    case 'Monday':
      return "Senin";
      break;
    case 'Tuesday':
      return "Selasa";
      break;
    case 'Wednesday':
      return "Rabu";
      break;
    case 'Thursday':
      return "Kamis";
      break;
    case 'Friday':
      return "Jumat";
      break;
    case 'Saturday':
      return "Sabtu";
      break;
  }
}
/**
 * [trimmer trim for import excel
 *
 * @param  [type] $excel column value
 * @return [type]  trimmed value
 */
function trimmer($value)
{
  $result = preg_replace('/[^[:print:]]/', '', filter_var($value, FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH));
  return addslashes(trim($result));
}
function getTahunakademik($id_semester)
{
  $year_next = substr($id_semester, 0, 4) + 1;
  if (substr($id_semester, -1) == 1) {
    $periode = substr($id_semester, 0, 4) . "/" . $year_next . " Ganjil";
  } elseif (substr($id_semester, -1) == 2) {
    $periode = substr($id_semester, 0, 4) . "/" . $year_next . " Genap";
  } else {
    $periode = $id_semester;
  }
  return $periode;
}
function isSuperAdmin()
{
  if ($_SESSION['group_level'] == 'admin') {
    return true;
  } else {
    return false;
  }
}

function BRIVAgenerateToken($client_id, $secret_id)
{
  $url = "https://partner.api.bri.co.id/oauth/client_credential/accesstoken?grant_type=client_credentials";
  $data = "client_id=" . $client_id . "&client_secret=" . $secret_id;
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

  $result = curl_exec($ch);
  $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);

  $json = json_decode($result, true);
  $accesstoken = $json['access_token'];

  return $accesstoken;
}

/* Generate signature */
function BRIVAgenerateSignature($path, $verb, $token, $timestamp, $payload, $secret)
{
  $payloads = "path=$path&verb=$verb&token=Bearer $token&timestamp=$timestamp&body=$payload";
  $signPayload = hash_hmac('sha256', $payloads, $secret, true);
  return base64_encode($signPayload);
}

function createBriva($data)
{

  $client_id = client_id;
  $secret_id = client_secret;
  $timestamp = gmdate("Y-m-d\TH:i:s.000\Z");
  $secret = $secret_id;
  $token = BRIVAgenerateToken($client_id, $secret_id);
  $Date = date("Y-m-d H:i:s");
  // print_r($data);
  // die(); 
  if (array_key_exists('exp_date', $data)) {
    $exp_date = $data['exp_date'];
  } else {
    $exp_date = date('Y-m-d H:i:s', strtotime($Date . ' + 10 days'));
  }

  $datas = array(
    'institutionCode' => institutionCode,
    'brivaNo' => custCode,
    'custCode' => $data['no_briva'],
    'nama' => $data['nama'],
    'amount' => $data['nominal'],
    'keterangan' => substr($data['ket'], 0, 40),
    'expiredDate' => $exp_date
  );
  // die();

  $payload = json_encode($datas, true);
  $path = "/v1/briva";
  $verb = "POST";
  $base64sign = BRIVAgenerateSignature($path, $verb, $token, $timestamp, $payload, $secret);
  $request_headers = array(
    "Content-Type:" . "application/json",
    "Authorization:Bearer " . $token,
    "BRI-Timestamp:" . $timestamp,
    "BRI-Signature:" . $base64sign,
  );

  $urlPost = "https://partner.api.bri.co.id/v1/briva";
  $chPost = curl_init();
  curl_setopt($chPost, CURLOPT_URL, $urlPost);
  curl_setopt($chPost, CURLOPT_HTTPHEADER, $request_headers);
  curl_setopt($chPost, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($chPost, CURLOPT_POSTFIELDS, $payload);
  curl_setopt($chPost, CURLINFO_HEADER_OUT, true);
  curl_setopt($chPost, CURLOPT_RETURNTRANSFER, true);
  $resultPost = curl_exec($chPost);
  $httpCodePost = curl_getinfo($chPost, CURLINFO_HTTP_CODE);
  curl_close($chPost);
  return json_decode($resultPost, true);
}

function getStatusBriva($custCode)
{
  $client_id = client_id;
  $secret_id = client_secret;
  $timestamp = gmdate("Y-m-d\TH:i:s.000\Z");
  $secret = $secret_id;
  $token = BRIVAgenerateToken($client_id, $secret_id);

  $institutionCode = institutionCode;
  $brivaNo = custCode;

  $payload = false;
  $path = "/v1/briva/" . $institutionCode . "/" . $brivaNo . "/" . $custCode;
  $verb = "GET";
  $base64sign = BRIVAgenerateSignature($path, $verb, $token, $timestamp, $payload, $secret);

  $request_headers = array(
    "Authorization:Bearer " . $token,
    "BRI-Timestamp:" . $timestamp,
    "BRI-Signature:" . $base64sign,
  );

  $urlPost = "https://partner.api.bri.co.id/v1/briva/" . $institutionCode . "/" . $brivaNo . "/" . $custCode;
  $chPost = curl_init();
  curl_setopt($chPost, CURLOPT_URL, $urlPost);
  curl_setopt($chPost, CURLOPT_HTTPHEADER, $request_headers);
  curl_setopt($chPost, CURLOPT_CUSTOMREQUEST, "GET");
  curl_setopt($chPost, CURLOPT_POSTFIELDS, $payload);
  curl_setopt($chPost, CURLINFO_HEADER_OUT, true);
  curl_setopt($chPost, CURLOPT_RETURNTRANSFER, true);
  $resultPost = curl_exec($chPost);
  $httpCodePost = curl_getinfo($chPost, CURLINFO_HTTP_CODE);
  curl_close($chPost);

  return json_decode($resultPost, true);
}



// $data  = array(
//         'custCode'        => '120970509801',  
//         'statusBayar'     => 'N',  
//         'amount'          => '10000', 
//         'nama'            => 'Muhammad Deden Firdaus',
//         'keterangan'      => 'UKT 1',
//         'expiredDate'     => '2022-01-10 23:59:59'
//       );

function updateBriva($data)
{
  $client_id = client_id;
  $secret_id = client_secret;
  $timestamp = gmdate("Y-m-d\TH:i:s.000\Z");
  $secret = $secret_id;
  $token = BRIVAgenerateToken($client_id, $secret_id);


  $datax = array(
    'institutionCode' => institutionCode,
    'brivaNo' => custCode,
  );

  $datas = array_merge($datax, $data);

  $payload = json_encode($datas, true);
  $path = "/v1/briva";
  $verb = "PUT";
  $base64sign = BRIVAgenerateSignature($path, $verb, $token, $timestamp, $payload, $secret);

  $request_headers = array(
    "Content-Type:" . "application/json",
    "Authorization:Bearer " . $token,
    "BRI-Timestamp:" . $timestamp,
    "BRI-Signature:" . $base64sign,
  );

  $urlPost = "https://partner.api.bri.co.id/v1/briva";
  $chPost = curl_init();
  curl_setopt($chPost, CURLOPT_URL, $urlPost);
  curl_setopt($chPost, CURLOPT_HTTPHEADER, $request_headers);
  curl_setopt($chPost, CURLOPT_CUSTOMREQUEST, "PUT");
  curl_setopt($chPost, CURLOPT_POSTFIELDS, $payload);
  curl_setopt($chPost, CURLINFO_HEADER_OUT, true);
  curl_setopt($chPost, CURLOPT_RETURNTRANSFER, true);

  $resultPost = curl_exec($chPost);
  $httpCodePost = curl_getinfo($chPost, CURLINFO_HTTP_CODE);
  curl_close($chPost);

  return json_decode($resultPost, true);
}

function deleteBriva($nomorBriva)
{
  $client_id = client_id;
  $secret_id = client_secret;
  $timestamp = gmdate("Y-m-d\TH:i:s.000\Z");
  $secret = $secret_id;
  $token = BRIVAgenerateToken($client_id, $secret_id);

  $institutionCode = institutionCode;
  $brivaNo = custCode;

  $datas = array(
    'institutionCode' => institutionCode,
    'brivaNo' => custCode,
    'custCode' => $nomorBriva
  );

  $payload = "institutionCode=" . $institutionCode . "&brivaNo=" . $brivaNo . "&custCode=" . $nomorBriva;
  $path = "/v1/briva";
  $verb = "DELETE";
  $base64sign = BRIVAgenerateSignature($path, $verb, $token, $timestamp, $payload, $secret);

  $request_headers = array(
    "Authorization:Bearer " . $token,
    "BRI-Timestamp:" . $timestamp,
    "BRI-Signature:" . $base64sign,
  );

  $urlPost = "https://partner.api.bri.co.id/v1/briva";
  $chPost = curl_init();
  curl_setopt($chPost, CURLOPT_URL, $urlPost);
  curl_setopt($chPost, CURLOPT_HTTPHEADER, $request_headers);
  curl_setopt($chPost, CURLOPT_POSTFIELDS, $payload);
  curl_setopt($chPost, CURLINFO_HEADER_OUT, true);
  curl_setopt($chPost, CURLOPT_CUSTOMREQUEST, "DELETE");
  curl_setopt($chPost, CURLOPT_RETURNTRANSFER, true);

  $resultPost = curl_exec($chPost);
  $httpCodePost = curl_getinfo($chPost, CURLINFO_HTTP_CODE);
  curl_close($chPost);

  return json_decode($resultPost, true);
}

// curl -X GET https://sandbox.partner.api.bri.co.id/v1/briva/report/J104408/88888/20210112/20210113 -H 'Authorization: Bearer {{TOKEN}}' \
//   -H 'BRI-Signature: {{SIGNATURE}}' \
//   -H 'BRI-Timestamp: {{TIMESTAMP}}'


function get_report_briva($tgl_awal, $tgl_akhir)
{
  $client_id = client_id;
  $secret_id = client_secret;
  $timestamp = gmdate("Y-m-d\TH:i:s.000\Z");
  $secret = $secret_id;
  $token = BRIVAgenerateToken($client_id, $secret_id);

  $institutionCode = institutionCode;
  $brivaNo = custCode;

  $payload = null;
  $path = "/v1/briva/report/$institutionCode/$brivaNo/$tgl_awal/$tgl_akhir";
  $verb = "GET";
  $base64sign = BRIVAgenerateSignature($path, $verb, $token, $timestamp, $payload, $secret);

  $request_headers = array(
    "Authorization:Bearer " . $token,
    "BRI-Timestamp:" . $timestamp,
    "BRI-Signature:" . $base64sign,
  );

  $urlPost = "https://partner.api.bri.co.id/v1/briva/report/$institutionCode/$brivaNo/$tgl_awal/$tgl_akhir";
  $chPost = curl_init();
  curl_setopt($chPost, CURLOPT_URL, $urlPost);
  curl_setopt($chPost, CURLOPT_HTTPHEADER, $request_headers);
  curl_setopt($chPost, CURLOPT_CUSTOMREQUEST, "GET");
  curl_setopt($chPost, CURLOPT_POSTFIELDS, $payload);
  curl_setopt($chPost, CURLINFO_HEADER_OUT, true);
  curl_setopt($chPost, CURLOPT_RETURNTRANSFER, true);

  $resultPost = curl_exec($chPost);
  $httpCodePost = curl_getinfo($chPost, CURLINFO_HTTP_CODE);
  curl_close($chPost);

  return json_decode($resultPost, true);
}

function updateStatusBayar($custCode, $statusBayar)
{
  $client_id = client_id;
  $secret_id = client_secret;
  $timestamp = gmdate("Y-m-d\TH:i:s.000\Z");
  $secret = $secret_id;
  $token = BRIVAgenerateToken($client_id, $secret_id);


  $datas = array(
    'institutionCode' => institutionCode,
    'brivaNo' => custCode,
    'custCode' => $custCode,
    'statusBayar' => $statusBayar,
  );


  $payload = json_encode($datas, true);
  $path = "/v1/briva/status";
  $verb = "PUT";
  $base64sign = BRIVAgenerateSignature($path, $verb, $token, $timestamp, $payload, $secret);

  $request_headers = array(
    "Content-Type:" . "application/json",
    "Authorization:Bearer " . $token,
    "BRI-Timestamp:" . $timestamp,
    "BRI-Signature:" . $base64sign,
  );

  $urlPost = "https://partner.api.bri.co.id/v1/briva/status";
  $chPost = curl_init();
  curl_setopt($chPost, CURLOPT_URL, $urlPost);
  curl_setopt($chPost, CURLOPT_HTTPHEADER, $request_headers);
  curl_setopt($chPost, CURLOPT_CUSTOMREQUEST, "PUT");
  curl_setopt($chPost, CURLOPT_POSTFIELDS, $payload);
  curl_setopt($chPost, CURLINFO_HEADER_OUT, true);
  curl_setopt($chPost, CURLOPT_RETURNTRANSFER, true);

  $resultPost = curl_exec($chPost);
  $httpCodePost = curl_getinfo($chPost, CURLINFO_HTTP_CODE);
  curl_close($chPost);

  //echo "Response Post : ".$resultPost;
  return json_decode($resultPost, true);
}

function afirmasi_krs($nim, $semester)
{
  global $db;
  $q = $db->query("select id_affirmasi from affirmasi_krs where nim=? and periode=?", array('nim' => $nim, 'periode' => $semester));
  if ($q->rowCount() > 0) {
    return true;
  } else {
    return false;
  }
}

//filter session
/**
 * set filter 
 * @param [string] $filter_modul      filter modul name
 * @param [array] $array_filter_name two dimensional array of filter names
 */
function setFilter($filter_modul, $array_filter_name)
{
  $_SESSION[$filter_modul] = $array_filter_name;
}

/**
 * get filter session
 * @param  [type] $array_filter_name array filter modulname and filtername
 * @return string/boolean       value of filtername
 */
function getFilter($array_filter_name)
{
  foreach ($array_filter_name as $key => $value) {
    $filter_modul = $key;
    $filter_name = $value;
  }
  if (isset($_SESSION[$filter_modul][$filter_name])) {
    return $_SESSION[$filter_modul][$filter_name];
  } else {
    return '';
  }
}

/**
 * check if filter session is exist
 * @param  [type]  $filter_modul_name filter modul name
 * @return boolean                    boolean
 */
function hasFilter($filter_modul_name)
{
  if (isset($_SESSION[$filter_modul_name])) {
    return true;
  } else {
    return false;
  }
}

/**
 * remove filter session
 * @param  [string] $filter_modul filter modul names
 * @return [type]               [description]
 */
function resetFilter($filter_modul)
{
  unset($_SESSION[$filter_modul]);
}

function resetFilterButton($filter_modul)
{
  echo '<span data-name="' . $filter_modul . '" id="reset_filter" class="btn btn-danger" data-toggle="tooltip" data-title="Reset Filter"><i class="fa fa-times">  </i> Reset </span>';
}
function aksesProdi($column)
{
  //get default akses prodi 
  // $akses_prodi = get_akses_prodi(); 
  global $db;
  $data_prodi = array();
  $where = "";
  $get_akses_prodi = $db->fetch_single_row("sys_group_users", "id", $_SESSION['level']);
  if ($get_akses_prodi->akses_prodi != "") {
    $decode_prodi = json_decode($get_akses_prodi->akses_prodi);
    $where = $decode_prodi->akses;
  }
  if ($where != '') {
    $jur_kode = "and $column in(" . $where . ")";
  } else {
    //jika tidak group tidak punya akses prodi, set in 0
    $jur_kode = "and $column in(0)";
  }
  return $jur_kode;
}
/**
 * get periode akademik
 * @param  [type] $sem_id semester id ex : 20181
 * @return [type]         get periode akademik name
 */
function getPeriode($sem_id)
{
  global $db2;
  $periode = $db2->fetchSingleRow('view_semester', "id_semester", $sem_id);
  echo $sem_id;
  return $periode->tahun_akademik;
}

function ganjil_genap($id_semester)
{
  $year_next = substr($id_semester, 0, 4) + 1;
  if (substr($id_semester, -1) == 1) {
    $periode = substr($id_semester, 0, 4) . "/" . $year_next . " Ganjil";
  } elseif (substr($id_semester, -1) == 2) {
    $periode = substr($id_semester, 0, 4) . "/" . $year_next . " Genap";
  } else {
    $periode = $id_semester;
  }
  return $periode;
}
/**
 * get semeste aktif
 * @return int semester
 */
function getSemesterAktif()
{
  global $db2;
  $periode = $db2->fetchSingleRow('view_semester', "aktif", 1);
  return $periode->id_semester;
}

function getSemesterAktifPembayaran()
{
  global $db2;
  $q = $db2->query("select periode_bayar from periode_pembayaran where now() between concat(tgl_mulai,' 00:00:00') and concat(tgl_selesai,' 23:59:59')  ");
  if ($q->rowCount() > 0) {
    foreach ($q as $periode) {
      return $periode->periode_bayar;
    }
  } else {
    return "0";
  }


}

function getAngkatan($id_semester)
{
  if (substr($id_semester, -1) == 1) {
    $periode = substr($id_semester, 0, 4) . " Ganjil";
  } elseif (substr($id_semester, -1) == 2) {
    $periode = substr($id_semester, 0, 4) . " Genap";
  } else {
    $periode = $id_semester;
  }
  return $periode;
}

/**
 * check jika kampus punya fakultas
 *
 * @return boolean
 */
function hasFakultas()
{
  global $db2;
  $has_fakultas = getPengaturan('has_fakultas');
  if ($has_fakultas == 'Y') {
    return true;
  } else {
    return false;
  }
}

function getFakultasName($kode_jur)
{
  global $db2;
  $has_fakultas = getPengaturan('has_fakultas');
  if ($has_fakultas == 'Y') {
    $jurusan = $db2->fetchSingleRow("jurusan", "kode_jur", $kode_jur);
    $fakultas = $db2->fetchSingleRow("fakultas", "kode_fak", $jurusan->fak_kode);
    $nama_fakultas = $fakultas->nama_resmi;
  } else {
    $nama_fakultas = "";
  }
  return $nama_fakultas;
}
/**
 * return $pengaturan
 *
 * @param  [type] $short_name short name column call
 * @return [type]             isi pengaturan
 */
function getPengaturan($short_name)
{
  global $db2;
  $pengaturan = $db2->fetchSingleRow('tb_master_pengaturan_umum', "short_name", $short_name);
  return $pengaturan->isi_pengaturan;
}

/**
 * if session level is dosen
 * @return boolean boolean
 */
function isDosen()
{
  if ($_SESSION['group_level'] == 'dosen') {
    return true;
  } else {
    return false;
  }
}
/**
 * if session level is mahasiswa
 * @return boolean boolean
 */
function isMahasiswa()
{
  if ($_SESSION['group_level'] == 'mahasiswa') {
    return true;
  } else {
    return false;
  }
}
/**
 * check if biodata dosen is not complete
 * @param  [type] $id_user user_id
 * @return [type]           boolean 
 */
function checkBiodataDosen($id_user)
{
  global $db2;
  $array_required = array(
    'gelar_belakang',
    'id_agama',
    'tgl_lahir',
    'nik',
    'email',
    'bidang_ilmu',
    'jenjang_tertinggi',
    'tmpt_lahir',
    'jk',
    'no_hp',
    'jenis_dosen'
  );
  $where = checkEmpty($array_required);
  $id_dosen = $db2->fetchSingleRow("view_dosen", "id_user", $id_user);
  $result_query = $db2->query("select * from tb_master_dosen where id_dosen=? and ($where)", array('id_dosen' => $id_dosen->id_dosen));
  if ($result_query->rowCount() > 0) {
    return true;
  } else {
    return false;
  }
}
/**
 * Memeriksa apakah kolom-kolom biodata mahasiswa yang wajib diisi sudah lengkap.
 *
 * @param string $username NIM/ID Mahasiswa yang akan diperiksa.
 * @return array|bool Mengembalikan true jika semua kolom wajib terisi,
 * atau array dengan status false dan daftar nama kolom yang kosong.
 */
function checkBiodataMahasiswaDataDiri($username)
{
  global $db2; // Asumsi $db2 adalah objek koneksi database (misal: PDO)
  global $db;

  // Daftar kolom yang wajib diisi beserta label yang akan ditampilkan
  $array_required = array(
    'nim' => 'NIM',
    'nama' => 'Nama Lengkap',
    'jk' => 'Jenis Kelamin',
    'nik' => 'NIK KTP',
    'status_pernikahan' => 'Status Pernikahan',
    'nisn' => 'NISN',
    'kewarganegaraan' => 'Kewarganegaraan',
    'id_jalur_masuk' => 'Jalur Masuk Kuliah',
    'tmpt_lahir' => 'Tempat Lahir',
    'tgl_lahir' => 'Tanggal Lahir',
    'id_agama' => 'Agama',
    'id_jns_daftar' => 'Jenis Pendaftaran',
    'id_pembiayaan' => 'Jenis Pembiayaan Kuliah'
  );
  $array_jenjang = array(35, 31);
  //check mhs
  $check_mhs = $db->fetch_single_row("mahasiswa", "nim", $username);
  $jenjang = $db->fetch_custom_single('select id_jenjang from jurusan where kode_jur="' . $check_mhs->jur_kode . '"');
  if (in_array($jenjang->id_jenjang, $array_jenjang)) {
    $array_required['kode_pt_asal'] = 'Asal Perguruan Tinggi';
    //$array_required['kode_prodi_asal'] = 'Asal Program Studi';
  } else {
    $array_required['id_jenis_sekolah'] = 'Jenis Asal Sekolah';
    $array_required['npsn'] = 'Nama Asal Sekolah/Lembaga';
  }

  // Ambil semua nama kolom dari array_required untuk query SELECT
  $columns_to_select = implode(",", array_keys($array_required));

  // Lakukan query untuk mengambil data mahasiswa berdasarkan mhs_id
  // Asumsi 'nim' dalam array parameter adalah nilai untuk 'mhs_id' di database
  $result_query = $db2->query("SELECT $columns_to_select FROM mahasiswa WHERE nim=?", array($username));

  // Periksa apakah data mahasiswa ditemukan
  if ($result_query->rowCount() == 0) {
    // Jika tidak ada data ditemukan untuk username ini, kembalikan error
    return array('status' => false, 'data' => ['Data pengguna tidak ditemukan.']);
  }

  // Ambil baris data mahasiswa sebagai array asosiatif
  $mahasiswa_data = $result_query->fetch(PDO::FETCH_ASSOC);

  $empty_fields_labels = []; // Array untuk menyimpan label kolom yang kosong

  // Iterasi melalui kolom-kolom yang wajib diisi
  foreach ($array_required as $column_name => $label) {
    // Periksa apakah kolom ada dalam data yang diambil dan apakah nilainya kosong (setelah trim whitespace)
    // Menggunakan isset() untuk menghindari warning jika kolom tidak ada di hasil query
    if (!isset($mahasiswa_data[$column_name]) || trim((string) $mahasiswa_data[$column_name]) === '') {
      $empty_fields_labels[] = $label; // Tambahkan label kolom yang kosong
    }
  }

  // Jika array empty_fields_labels kosong, berarti semua kolom wajib terisi
  if (empty($empty_fields_labels)) {
    return $empty_fields_labels;
  } else {
    // Jika ada kolom yang kosong, kembalikan status false dan daftar label kolom yang kosong
    return $empty_fields_labels;
  }
}

function checkBiodataMahasiswaAlamat($username)
{
  global $db2; // Asumsi $db2 adalah objek koneksi database (misal: PDO)

  // Daftar kolom yang wajib diisi beserta label yang akan ditampilkan
  $array_required = array(
    'id_wil' => 'Kecamatan',
    'jln' => 'Alamat Jalan',
    'rt' => 'RT',
    'rw' => 'RW',
    'ds_kel' => 'Desa/Kelurahan',
    'kode_pos' => 'Kode Pos',
    'id_jns_tinggal' => 'Jenis Tinggal',
    'no_hp' => 'No Handphone',
    'email' => 'Email'
  );

  // Ambil semua nama kolom dari array_required untuk query SELECT
  $columns_to_select = implode(",", array_keys($array_required));

  // Lakukan query untuk mengambil data mahasiswa berdasarkan mhs_id
  // Asumsi 'nim' dalam array parameter adalah nilai untuk 'mhs_id' di database
  $result_query = $db2->query("SELECT $columns_to_select FROM mahasiswa WHERE nim=?", array($username));

  // Periksa apakah data mahasiswa ditemukan
  if ($result_query->rowCount() == 0) {
    // Jika tidak ada data ditemukan untuk username ini, kembalikan error
    return array('status' => false, 'data' => ['Data pengguna tidak ditemukan.']);
  }

  // Ambil baris data mahasiswa sebagai array asosiatif
  $mahasiswa_data = $result_query->fetch(PDO::FETCH_ASSOC);

  $empty_fields_labels = []; // Array untuk menyimpan label kolom yang kosong

  // Iterasi melalui kolom-kolom yang wajib diisi
  foreach ($array_required as $column_name => $label) {
    // Periksa apakah kolom ada dalam data yang diambil dan apakah nilainya kosong (setelah trim whitespace)
    // Menggunakan isset() untuk menghindari warning jika kolom tidak ada di hasil query
    if (!isset($mahasiswa_data[$column_name]) || trim((string) $mahasiswa_data[$column_name]) === '') {
      $empty_fields_labels[] = $label; // Tambahkan label kolom yang kosong
    }
  }

  // Jika array empty_fields_labels kosong, berarti semua kolom wajib terisi
  if (empty($empty_fields_labels)) {
    return $empty_fields_labels;
  } else {
    // Jika ada kolom yang kosong, kembalikan status false dan daftar label kolom yang kosong
    return $empty_fields_labels;
  }
}

function checkUploadDokumen($username)
{
  global $db;
  $empty_label = array();
  $check_query = $db->query("SELECT
    tfl.id_file_label,
    tfl.nama_label,
tf.id_file
FROM
    tb_data_file_label AS tfl
LEFT JOIN
    tb_data_file AS tf ON tfl.id_file_label = tf.id_file_label AND tf.nim = ?
WHERE
    tfl.is_required = 'Y' AND tf.nim IS NULL;
    ", array('nim' => $username));
  foreach ($check_query as $upl) {
    if ($upl->id_file == "") {
      $empty_label[] = $upl->nama_label;
    }
  }
  return $empty_label;

}
function checkBiodataMahasiswaOrtu($username)
{
  global $db2; // Asumsi $db2 adalah objek koneksi database (misal: PDO)

  // Daftar kolom yang wajib diisi beserta label yang akan ditampilkan
  $array_required = array(
    'nm_ibu_kandung' => 'Nama Ibu'
  );

  // Ambil semua nama kolom dari array_required untuk query SELECT
  $columns_to_select = implode(",", array_keys($array_required));

  // Lakukan query untuk mengambil data mahasiswa berdasarkan mhs_id
  // Asumsi 'nim' dalam array parameter adalah nilai untuk 'mhs_id' di database
  $result_query = $db2->query("SELECT $columns_to_select FROM mahasiswa WHERE nim=?", array($username));

  // Periksa apakah data mahasiswa ditemukan
  if ($result_query->rowCount() == 0) {
    // Jika tidak ada data ditemukan untuk username ini, kembalikan error
    return array('status' => false, 'data' => ['Data pengguna tidak ditemukan.']);
  }

  // Ambil baris data mahasiswa sebagai array asosiatif
  $mahasiswa_data = $result_query->fetch(PDO::FETCH_ASSOC);

  $empty_fields_labels = []; // Array untuk menyimpan label kolom yang kosong

  // Iterasi melalui kolom-kolom yang wajib diisi
  foreach ($array_required as $column_name => $label) {
    // Periksa apakah kolom ada dalam data yang diambil dan apakah nilainya kosong (setelah trim whitespace)
    // Menggunakan isset() untuk menghindari warning jika kolom tidak ada di hasil query
    if (!isset($mahasiswa_data[$column_name]) || trim((string) $mahasiswa_data[$column_name]) === '') {
      $empty_fields_labels[] = $label; // Tambahkan label kolom yang kosong
    }
  }

  // Jika array empty_fields_labels kosong, berarti semua kolom wajib terisi
  if (empty($empty_fields_labels)) {
    return $empty_fields_labels;
  } else {
    // Jika ada kolom yang kosong, kembalikan status false dan daftar label kolom yang kosong
    return $empty_fields_labels;
  }
}

function checkSubmit($username)
{
  global $db;
  $result = array();
  $check_mhs = $db->fetch_single_row("mahasiswa", "nim", $username);
  if ($check_mhs->is_submit_biodata == 'N') {
    $result[] = 'Silakan Kirimkan data jika sudah lengkap';
  }
  return $result;
}

function checkBiodataAll($username)
{
  if (!empty(checkBiodataMahasiswaDataDiri($username))) {
    redirectJs(base_index() . 'biodata/edit/datadiri');
  } elseif (!empty(checkBiodataMahasiswaAlamat($username))) {
    redirectJs(base_index() . 'biodata/edit/alamat');
  } elseif (!empty(checkBiodataMahasiswaOrtu($username))) {
    redirectJs(base_index() . 'biodata/edit/orangtua');
  } elseif (!empty(checkUploadDokumen($username))) {
    redirectJs(base_index() . 'biodata/edit/dokumen');
  } elseif (!empty(checkSubmit($username))) {
    redirectJs(base_index() . 'biodata/edit/pernyataan');
  }
}

function checkBiodataAllStatus($username)
{
  $is_empty = false;
  if (!empty(checkBiodataMahasiswaDataDiri($username))) {
    $is_empty = true;
  } elseif (!empty(checkBiodataMahasiswaAlamat($username))) {
    $is_empty = true;
  } elseif (!empty(checkBiodataMahasiswaOrtu($username))) {
    $is_empty = true;
  } elseif (!empty(checkUploadDokumen($username))) {
    $is_empty = true;
  } elseif (!empty(checkSubmit($username))) {
    $is_empty = true;
  }
  return $is_empty;
}

/**
 * Fungsi checkEmpty yang asli (tidak digunakan langsung dalam fungsi di atas,
 * tetapi tetap disertakan jika ada kebutuhan lain).
 * Membangun bagian WHERE clause untuk memeriksa kolom-kolom kosong dalam query SQL.
 *
 * @param array $array_data Array kolom yang akan diperiksa.
 * @return string Bagian WHERE clause SQL.
 */
function checkEmpty($array_data)
{
  $result = [];
  foreach (array_keys($array_data) as $dt) {
    $result[] = "coalesce($dt, '') = ''";
  }
  $data = implode(' or ', $result);
  return $data;
}

/**
 * return implode kode jur base on hak akses prodi
 * @return [type] list comma separated kode prodi
 */
function getAksesProdi()
{
  global $db2;
  $data_prodi = array();
  $kode_prodi = "";
  $get_akses_prodi = $db2->fetchSingleRow("sys_group_users", "level", $_SESSION['group_level']);
  if ($get_akses_prodi->akses_prodi != "") {
    $decode_prodi = json_decode($get_akses_prodi->akses_prodi);
    $kode_prodi = $decode_prodi->akses;
  }
  if ($kode_prodi != "") {
    return $kode_prodi;
  } else {
    return false;
  }
}


function getProdiFakultas($column, $id_fakultas)
{
  global $db2;
  //get default akses prodi
  $data_prodi_fakultas = $db2->fetchCustomSingle("select group_concat(kode_jur) as jur_kode from jurusan where fak_kode=?", array('fak_kode' => $id_fakultas));
  if ($data_prodi_fakultas) {
    $jur_kode = "and $column in(" . $data_prodi_fakultas->jur_kode . ")";
  } else {
    //jika tidak group tidak punya akses prodi, set in 0
    $jur_kode = "and $column in(0)";
  }
  return $jur_kode;
}
/**
 * menampilkan looping select list fakultas sesuai akses prodi yang diberikan
 * @return [type] select option list fakultas
 */
function loopingFakultas($filter_modul = "")
{
  if ($filter_modul != "") {
    $selected_filter_value = getFilter(array($filter_modul => 'fakultas'));

  }
  global $db2;
  $akses_prodi = get_akses_prodi();
  print_r($akses_prodi);
  if ($akses_prodi) {
    $fakultas = $db2->query("select * from view_prodi_jenjang  $akses_prodi group by id_fakultas");

    //jika jurusan hanya punya 1 akses prodi, misal admin prodi
    if ($fakultas->rowCount() == 1) {
      foreach ($fakultas as $dt) {
        echo "<option value='$dt->id_fakultas' selected>$dt->nama_fakultas</option>";
      }
    } else {
      //jika group user punya akses ke semua prodi
      echo "<option value='all'>Semua</option>";
      foreach ($fakultas as $dt) {
        if ($filter_modul != "" && $selected_filter_value == $dt->id_fakultas) {
          echo "<option value='$dt->id_fakultas' selected>" . ucwords(strtolower($dt->nama_fakultas)) . "</option>";
        } else {
          echo "<option value='$dt->id_fakultas'>" . ucwords(strtolower($dt->nama_fakultas)) . "</option>";
        }

      }
    }

  } else {
    echo "<option value='' selected>Akun ini belum punya akses prodi</option>";
  }
}

/**
 * menampilkan looping select list Jenjang sesuai akses prodi yang diberikan
 * @return [type] select option list fakultas
 */
function loopingJenjang($filter_modul = "")
{
  if ($filter_modul != "") {
    $selected_filter_value = getFilter(array($filter_modul => 'jenjang'));
  }
  global $db2;
  $akses_prodi = getAksesProdi();
  print_r($akses_prodi);
  if ($akses_prodi) {
    $fakultas = $db2->query("select * from view_prodi_jenjang where kode_jur in ($akses_prodi) group by id_jenjang");

    //jika jurusan hanya punya 1 akses prodi, misal admin prodi
    if ($fakultas->rowCount() == 1) {
      foreach ($fakultas as $dt) {
        echo "<option value='$dt->id_jenjang' selected>$dt->jenjang</option>";
      }
    } else {
      //jika group user punya akses ke semua prodi
      echo "<option value='all'>Semua</option>";
      foreach ($fakultas as $dt) {
        if ($filter_modul != "" && $selected_filter_value == $dt->id_jenjang) {
          echo "<option value='$dt->id_jenjang' selected>" . ucwords(strtolower($dt->jenjang)) . "</option>";
        } else {
          echo "<option value='$dt->id_jenjang'>" . ucwords(strtolower($dt->jenjang)) . "</option>";
        }

      }
    }

  } else {
    echo "<option value='' selected>Akun ini belum punya akses prodi</option>";
  }

}



/**
 * menampilkan looping select list prodi sesuai akses prodi yang diberikan
 * @return [type] select option list prodi
 */
function loopingProdi($filter_modul = "", $id_fakultas = "")
{
  if ($filter_modul != "") {
    $selected_filter_value = getFilter(array($filter_modul => 'prodi'));
  }
  $and_id_fakultas = "";
  if ($id_fakultas != "" && $id_fakultas != "all") {
    $and_id_fakultas = "and id_fakultas='" . $id_fakultas . "'";
  }
  global $db2;
  $akses_prodi = get_akses_prodi();
  if ($akses_prodi) {
    $jurusan = $db2->query("select *,jurusan as nama_jurusan from view_prodi_jenjang $akses_prodi $and_id_fakultas");
    //jika jurusan hanya punya 1 akses prodi, misal admin prodi
    if ($jurusan->rowCount() == 1) {
      foreach ($jurusan as $dt) {
        echo "<option value='$dt->kode_jur' selected>" . string_rapih($dt->nama_jurusan) . "</option>";
      }
    } else {
      //jika group user punya akses ke semua prodi
      echo "<option value='all'>Semua</option>";
      foreach ($jurusan as $dt) {
        if ($filter_modul != "" && $selected_filter_value == $dt->kode_jur) {
          echo "<option value='$dt->kode_jur' selected>" . string_rapih($dt->nama_jurusan) . "</option>";
        } else {
          echo "<option value='$dt->kode_jur'>" . string_rapih($dt->nama_jurusan) . "</option>";
        }

      }
    }

  } else {
    echo "<option value='' selected>Akun ini belum punya akses prodi</option>";
  }

}


/**
 * return session value
 * @param  [type] $session_name session name
 * @return [type]               session value
 */
function getSession($session_name)
{
  return $_SESSION[$session_name];
}

function isPhotoChange()
{
  global $db2;
  $photo = $db2->fetchSingleRow("sys_users", "id", getSession('id_user'));
  if ($photo->photo_changed == 'Y') {
    return true;
  } else {
    return false;
  }
}
/**
 * looping semester, selected if semester is aktif
 * @return [type] [description]
 */
function loopingSemester($filter_modul = "")
{
  if ($filter_modul != "") {
    $selected_filter_value = getFilter(array($filter_modul => 'semester'));

  }
  global $db2;
  foreach ($db2->query("select * from view_semester order by id_semester desc") as $isi) {
    if ($filter_modul != "" && $selected_filter_value == $isi->id_semester) {
      echo "<option value='$isi->id_semester' selected>$isi->tahun_akademik</option>";
    } else {
      if ($isi->aktif == 1) {
        echo "<option value='$isi->id_semester' selected>$isi->tahun_akademik</option>";
      } else {
        echo "<option value='$isi->id_semester'>$isi->tahun_akademik</option>";
      }
    }

  }
}
function loopingSemesterForm()
{
  global $db2;
  foreach ($db2->query("select * from view_semester order by id_semester desc") as $isi) {
    if ($isi->aktif == 1) {
      echo "<option value='$isi->id_semester' selected>$isi->tahun_akademik</option>";
    } else {
      echo "<option value='$isi->id_semester'>$isi->tahun_akademik</option>";
    }
  }
}

function getRuang($id_ruang)
{
  global $db2;
  $ruangan = $db2->fetchSingleRow('tb_data_ruang', 'ruang_id', $id_ruang);
  if ($ruangan) {
    return $ruangan->nm_ruang;
  }
}


//siganture goes here
function status_pengesah($pejabat, $posisi)
{
  //id mahasiswa n pembimbing akademik
  $mhs_pem = array(1, 2);
  if ($pejabat[$posisi]->status == 0) {
    $status_pengesah = "hide";
  } else {
    if (in_array($pejabat[$posisi]->kategori_jabatan, $mhs_pem)) {
      $status_pengesah = "hide";
    } else {
      $status_pengesah = "show";
    }
  }
  return $status_pengesah;
}

function kota($pejabat, $posisi)
{
  global $db2;
  $kota = '';

  if ($pejabat[$posisi]->status != 0) {
    if (is_numeric($pejabat[$posisi]->ada_kota)) {
      $record_kota = $db2->fetchSingleRow("tb_data_kota_signature", "id_kota", $pejabat[$posisi]->ada_kota);
      $kota = '<br>' . $record_kota->nama_kota . ', ';
    }
  }

  return $kota;
}

function ada_tgl($pejabat, $posisi)
{
  $tgl = '';
  if ($pejabat[$posisi]->status != 0) {
    if ($pejabat[$posisi]->ada_tgl == 'yes') {
      $tgl = tgl_indo(date('Y-m-d'));
    } elseif ($pejabat[$posisi]->ada_tgl == 'titik') {
      $tgl = ".................................";
    }
  }
  return $tgl;
}

function tipe_pengesah($pejabat, $posisi)
{
  global $db2;
  $tipe_pengesah = '';
  if ($pejabat[$posisi]->status != 0) {
    if ($pejabat[$posisi]->tipe_pengesah == 'no') {
      $tipe_pengesah = '';
    } else {
      $record_tipe_pengesah = $db2->fetchSingleRow("tb_data_jabatan_tipe_pengesah", "id_tipe_pengesah", $pejabat[$posisi]->tipe_pengesah);
      $tipe_pengesah = '<br>' . $record_tipe_pengesah->nama_tipe_pengesah;
    }
  }

  return $tipe_pengesah;
}

function kategori_pejabat($pejabat, $posisi)
{
  global $db2;
  $kat_pejabat = '';
  if ($pejabat[$posisi]->status != 0) {
    $record_kat_pejabat = $db2->fetchSingleRow("tb_data_jabatan_kategori", "id_jabatan_kat", $pejabat[$posisi]->kategori_jabatan);
    $kat_pejabat = '<br>' . $record_kat_pejabat->nama_kategori;
  }

  return $kat_pejabat;
}

function nip_pengesah($pejabat, $posisi, $nim = NULL)
{
  global $db2;
  $nip = '';
  if ($pejabat[$posisi]->status != 0) {
    if ($pejabat[$posisi]->kategori_jabatan == 1) {
      if ($nim != NULL) {
        $nip = 'NIM : ' . getInfoMhs($nim)->nim;
        ;
      } else {
        $nip = 'NIM : ';
      }

    } elseif ($pejabat[$posisi]->kategori_jabatan == 2) {
      if ($nim != NULL) {
        $nip = 'NIP : ' . getInfoMhs($nim)->nip_pembimbing;
        ;
      } else {
        $nip = 'NIP : ';
      }
    } else {
      $record_nip = $db2->fetchCustomSingle("select fungsi_get_pengesah_nama(is_dosen,id_dosen,id_pejabat) as nama_pejabat,fungsi_get_pengesah_nip(is_dosen,id_dosen,id_pejabat) as nip,id_pejabat from tb_data_jabatan_pejabat inner join tb_data_jabatan_kategori using(id_jabatan_kat) where id_pejabat=?", array("id_pejabat" => $pejabat[$posisi]->pengesah));

      $nip = 'NIP : ' . $record_nip->nip;
    }
  }
  return $nip;
}

function nama_pengesah($pejabat, $posisi, $nim = NULL)
{
  global $db2;
  $nama = '';
  if ($pejabat[$posisi]->status != 0) {
    if ($pejabat[$posisi]->kategori_jabatan == 1) {
      if ($nim != NULL) {
        $nama = getInfoMhs($nim)->nama;
        ;
      } else {
        $nama = 'Nama Mahasiswa';
      }
    } elseif ($pejabat[$posisi]->kategori_jabatan == 2) {
      if ($nim != NULL) {
        $nama = getInfoMhs($nim)->nama_pembimbing;
        ;
      } else {
        $nama = 'Nama Pembimbing';
      }
    } else {
      $record_nama = $db2->fetchCustomSingle("select fungsi_get_pengesah_nama(is_dosen,id_dosen,id_pejabat) as nama_pejabat,fungsi_get_pengesah_nip(is_dosen,id_dosen,id_pejabat) as nip,id_pejabat from tb_data_jabatan_pejabat inner join tb_data_jabatan_kategori using(id_jabatan_kat) where id_pejabat=?", array("id_pejabat" => $pejabat[$posisi]->pengesah));
      $nama = $record_nama->nama_pejabat;
    }
  }
  return $nama;
}

function getInfoMhs($nim)
{
  global $db2;
  return $db2->fetchSingleRow('view_simple_mhs', 'nim', $nim);
}


function string_rapih($word)
{
  return preg_replace('/[^[:print:]]/', '', str_replace("Dan", "dan", ucwords(strtolower($word))));
}

//check if current date is periode krs or input nilai
/**
 * check current aktif per prodi
 * @param  string $type     pilihanya check krs atau lainya check periode input nilai
 * @param  int $periode  periode misal 20171
 * @param  int $kode_jur contoh 705
 * @return boolean           
 */
function checkPeriodeInput($type, $periode, $kode_jur)
{
  global $db2;
  if ($type == 'krs') {
    $check = $db2->query("select * from tb_data_semester_prodi s where ((curdate() between s.tgl_mulai_krs and s.tgl_selesai_krs) 
or (curdate() between s.tgl_mulai_pkrs and s.tgl_selesai_pkrs))
and id_semester='$periode' and kode_jur='$kode_jur'");
  } else {
    $check = $db2->query("select * from tb_data_semester_prodi s where curdate() between s.tgl_mulai_input_nilai and s.tgl_selesai_input_nilai and id_semester='$periode' and kode_jur='$kode_jur'");
  }
  if ($check->rowCount() > 0) {
    return true;
  } else {
    return false;
  }

}
/**
 * check if mahasiswa has status bayar and boleh krs
 * @param  [type]  $nim         nim mahasiswa
 * @param  [type]  $id_semester periode semester, ex : 20192
 * @return boolean              true or false
 */
function isBayar($nim, $id_semester)
{
  global $db2;
  $is_bayar = $db2->fetchCustomSingle("select * from tb_data_status_bayar where nim=? and id_semester=? and boleh_krs=?", array('nim' => $nim, 'id_semester' => $id_semester, 'boleh_krs' => 'Y'));
  if ($is_bayar) {
    return true;
  } else {
    return false;
  }
}
/**
 * check if mahasiswa has status bayar and boleh krs
 * @param  [type]  $nim         nim mahasiswa
 * @param  [type]  $id_semester periode semester, ex : 20192
 * @return boolean              true or false
 */
function isDisetujuiKrs($nim, $id_semester)
{
  global $db2;
  $is_approved_krs = $db2->fetchCustomSingle("select * from tb_data_kelas_krs where nim=? and id_semester=? and disetujui=?", array('nim' => $nim, 'id_semester' => $id_semester, 'disetujui' => 1));
  if ($is_approved_krs) {
    return true;
  } else {
    return false;
  }
}
/**
 * Jatah SKS Mahasiswa
 * @param  [type]  $nim         nim mahasiswa
 * @param  [type]  $id_semester periode semester, ex : 20192
 * @return jatah sks or false
 */
function jatahSks($nim, $id_semester)
{
  global $db2;
  $jatah_sks = $db2->fetchCustomSingle("select (select sks_mak from tb_data_jatah_sks j where  IFNULL((select tb_data_akm.ip  from tb_data_akm where tb_data_akm.nim='" . $nim . "'
and tb_data_akm.id_semester<" . $id_semester . "
and tb_data_akm.id_stat_mhs='A' ORDER BY id_semester DESC LIMIT 1),4) BETWEEN j.ip_min and j.ip_mak) as jatah_sks");
  if ($jatah_sks) {
    return $jatah_sks->jatah_sks;
  } else {
    return false;
  }
}

function sksDiambil($nim, $id_semester)
{
  global $db2;
  $sks_diambil = $db2->fetchCustomSingle("select sum(total_sks) as sks_diambil from tb_data_kelas_krs_detail inner join tb_data_matakuliah
on matkul_id=id_matkul
inner join tb_data_kelas_krs using (krs_id) where nim=? and id_semester=? and batal=0", array("nim" => $nim, "id_semester" => $id_semester));
  if ($sks_diambil) {
    return $sks_diambil->sks_diambil;
  } else {
    return 0;
  }
}

function getTotalSksDiambil($nim, $id_semester = NULL)
{
  global $db2;
  $and_semester = "";
  if ($id_semester != NULL) {
    $and_semester = "and sem_id<=" . $id_semester;
  }
  //minimal SKS diambil
  $data_sks_diambil = $db2->query("select kode_mk,max(bobot),sks from krs_detail where nim=? and bobot is not null $and_semester  group by kode_mk", array('nim' => $nim));
  $sks = 0;
  if ($data_sks_diambil->rowCount() > 0) {
    foreach ($data_sks_diambil as $diambil) {
      $sks += $diambil->sks;
    }
  }
  return $sks;
}

function getTotalSksWajibDiambil($nim, $id_semester = NULL)
{
  global $db2;
  $and_semester = "";
  if ($id_semester != NULL) {
    $and_semester = "and sem_id<=" . $id_semester;
  }
  //minimal SKS diambil
  $data_sks_diambil = $db2->query("select id_matkul,max(nilai_indeks),sks from view_krs_mhs_kelas where nim=? and a_wajib=1 and nilai_indeks is not null $and_semester group by id_matkul", array('nim' => $nim));
  $sks = 0;
  if ($data_sks_diambil->rowCount() > 0) {
    foreach ($data_sks_diambil as $diambil) {
      $sks += $diambil->sks;
    }
  }
  return $sks;
}

function getTotalSksPilihanDiambil($nim, $id_semester = NULL)
{
  global $db2;
  $and_semester = "";
  if ($id_semester != NULL) {
    $and_semester = "and id_semester<=" . $id_semester;
  }
  //minimal SKS diambil
  $data_sks_diambil = $db2->query("select max(nilai_indeks),total_sks from tb_data_matakuliah
inner join tb_data_kelas_krs_detail on matkul_id=id_matkul
inner join tb_data_kelas_krs using(krs_id)
where nim=? and is_wajib=0  and nilai_indeks is not null $and_semester
group by id_matkul;", array('nim' => $nim));
  $sks = 0;
  if ($data_sks_diambil->rowCount() > 0) {
    foreach ($data_sks_diambil as $diambil) {
      $sks += $diambil->total_sks;
    }
  }
  return $sks;
}


/**
 * check apakah matakuliah sudah diambil atau beblum
 * @param  int $id_matkul it matakuliah
 * @param  [type] $nim       nim mahasiswa
 * @return [type]            return nilai huruf jika sudah diambil otherwise return false
 */
function checkMatDiambil($id_matkul, $nim)
{
  global $db2;
  $check_mk = $db2->fetchCustomSingle("select max(nilai_huruf) as nilai_huruf from tb_data_kelas_krs_detail inner join tb_data_kelas_krs using(krs_id) where matkul_id=? and nim=? group by matkul_id", array("matkul_id" => $id_matkul, "nim" => $nim));

  if ($check_mk) {
    if ($check_mk->nilai_huruf == "") {
      return "Kosong";
    } else {
      return $check_mk->nilai_huruf;
    }
  } else {
    //grab if ada matkul setara
    $check_setara = $db2->query("select id_matkul_setara from tb_data_matakuliah_setara where id_matkul=?", array("id_matkul" => $id_matkul));
    if ($check_setara->rowCount() > 0) {
      foreach ($check_setara as $setara) {
        $id_matkul_setara[] = $setara->id_matkul_setara;
      }
      $implode_id_mk_setara = implode(",", $id_matkul_setara);

      $check_mk_again = $db2->query("select max(nilai_huruf) as nilai_huruf from tb_data_kelas_krs_detail inner join tb_data_kelas_krs using(krs_id) where matkul_id in($implode_id_mk_setara) and nim=? group by matkul_id", array("nim" => $nim));
      if ($check_mk_again->rowCount() > 0) {
        foreach ($check_mk_again as $mk_setara_ambil) {
          $nilai_huruf_setara[] = $mk_setara_ambil->nilai_huruf;
        }
        sort($nilai_huruf_setara);
        if ($nilai_huruf_setara[0] == "") {
          return "Kosong";
        } else {
          return $nilai_huruf_setara[0];
        }
      }

    } else {
      return 0;
    }

  }
}

function getNamaMkKelas($kelas_id)
{
  global $db2;
  $data = $db2->fetchCustomSingle("select nama_mk,kls_nama from tb_data_kelas
inner join tb_data_matakuliah using(id_matkul) where kelas_id=?", array('kelas_id' => $kelas_id));
  return $data;
}

function checkDiambilCurrent($id_semester, $nim)
{
  global $db2;
  $id_mk = array();
  $check_mk = $db2->query("select krs_detail_id,matkul_id from tb_data_kelas_krs_detail inner join tb_data_kelas_krs using (krs_id) where id_semester=? and nim=? group by matkul_id", array("id_semester" => $id_semester, "nim" => $nim));
  if ($check_mk->rowCount() > 0) {
    foreach ($check_mk as $mk) {
      $id_mk[] = $mk->matkul_id;
    }
  }
  return $id_mk;
}

function isSemuaKelasPenuh($id_matkul, $id_semester)
{
  global $db2;
  $check_penuh = $db2->fetchCustomSingle("select sum(kuota) as kuota,sum((select count(krs_detail_id) from tb_data_kelas_krs_detail 
where kelas_id=tb_data_kelas.kelas_id and batal=0)) as peserta_kelas from tb_data_kelas where id_matkul=? and sem_id=?", array('id_matkul' => $id_matkul, 'sem_id' => $id_semester));
  if ($check_penuh->peserta_kelas >= $check_penuh->kuota) {
    return true;
  } else {
    return false;
  }
}

function isBentrok($kelas_id, $id_semester, $nim)
{
  global $db2;
  $bentrok = array();
  //kelas yang akan di bandingkan
  $data_kelas_check = $db2->fetchSingleRow("tb_data_kelas_jadwal", "kelas_id", $kelas_id);
  if ($data_kelas_check) {
    //loop jadwal kelas sudah diambil
    $loop_jadwal = $db2->query("select kelas_id from tb_data_kelas_krs_detail inner join tb_data_kelas_krs using(krs_id)
        inner join tb_data_kelas_jadwal using(kelas_id)
    where id_semester=? and nim=?", array('id_semester' => $id_semester, 'nim' => $nim));
    foreach ($loop_jadwal as $jd) {
      $check_bentrok_kelas = $db2->query("select * from tb_data_kelas_jadwal where
        ('" . $data_kelas_check->jam_mulai . "'>jam_mulai and '" . $data_kelas_check->jam_mulai . "'<jam_selesai or '" . $data_kelas_check->jam_selesai . "'> jam_mulai and '" . $data_kelas_check->jam_selesai . "'<jam_selesai or jam_mulai 
        > '" . $data_kelas_check->jam_mulai . "' and jam_mulai <'" . $data_kelas_check->jam_selesai . "' or jam_selesai>'" . $data_kelas_check->jam_mulai . "' and jam_selesai<'" . $data_kelas_check->jam_selesai . "' or jam_mulai='" . $data_kelas_check->jam_mulai . "' and jam_selesai='" . $data_kelas_check->jam_selesai . "' ) and id_hari='" . $data_kelas_check->id_hari . "' and kelas_id='" . $jd->kelas_id . "'");
      if ($check_bentrok_kelas->rowCount() > 0) {
        $bentrok[] = "Matakuliah " . getNamaMkKelas($data_kelas_check->kelas_id)->nama_mk . " kelas " . getNamaMkKelas($jd->kelas_id)->kls_nama . " Bentrok dengan " . getNamaMkKelas($jd->kelas_id)->nama_mk . ' Kelas ' . getNamaMkKelas($jd->kelas_id)->kls_nama;
      }
    }
  }

  return $bentrok;
}

function isKelasPenuh($id_kelas)
{
  global $db2;
  $check_penuh = $db2->fetchCustomSingle("select sum(kuota) as kuota,sum((select count(krs_detail_id) from tb_data_kelas_krs_detail 
where kelas_id=tb_data_kelas.kelas_id and batal=0)) as peserta_kelas from tb_data_kelas where kelas_id=?", array('kelas_id' => $id_kelas));
  if ($check_penuh->peserta_kelas >= $check_penuh->kuota) {
    return true;
  } else {
    return false;
  }
}

function getProfilUser($id_user)
{
  global $db2;
  return $db2->fetchSingleRow("sys_users", "id", $id_user);
}

function tanggalWaktu()
{
  return date('Y-m-d H:i:s');
}
/**
 * check apakah matakuliah sudah diambil syarat
 * @param  int $id_matkul it matakuliah
 * @param  [type] $nim       nim mahasiswa
 * @param  [type] string     syart L : harus lulus, A: ambil saja, S : sejajar
 * @return [type]            return nilai huruf jika sudah diambil otherwise return false
 */
function checkMatDiambilSyarat($id_matkul, $nim, $syarat)
{
  global $db2;
  $and_syarat = "";
  $join_matkul = "";
  if ($syarat == 'L') {
    $and_syarat = "and nilai_indeks>=bobot_minimal_lulus";
    $join_matkul = "inner join tb_data_matakuliah on matkul_id=id_matkul";
  }

  $check_mk = $db2->fetchCustomSingle("select max(nilai_huruf) as nilai_huruf from tb_data_kelas_krs_detail
  $join_matkul
  inner join tb_data_kelas_krs using(krs_id) where matkul_id=? and nim=? $and_syarat group by matkul_id", array("matkul_id" => $id_matkul, "nim" => $nim));

  if ($check_mk) {
    return 1;
  } else {
    //grab if ada matkul setara
    $check_setara = $db2->query("select id_matkul_setara from tb_data_matakuliah_setara where id_matkul=?", array("id_matkul" => $id_matkul));
    if ($check_setara->rowCount() > 0) {
      foreach ($check_setara as $setara) {
        $id_matkul_setara[] = $setara->id_matkul_setara;
      }
      $implode_id_mk_setara = implode(",", $id_matkul_setara);

      $check_mk_again = $db2->query("select max(nilai_huruf) as nilai_huruf from tb_data_kelas_krs_detail
         $join_matkul
         inner join tb_data_kelas_krs using(krs_id) where matkul_id in($implode_id_mk_setara) and nim=? $and_syarat group by matkul_id", array("nim" => $nim));
      if ($check_mk_again->rowCount() > 0) {
        return 1;
      } else {
        return 0;
      }

    } else {
      return 0;
    }
  }
}

function checkPrasyaratMk($id_matkul, $nim)
{
  global $db2;
  $error_syarat = array();
  //first check if matkul has prasyarat
  $has_prasyarat = $db2->fetchCustomSingle("select id_matkul from tb_data_matakuliah_prasyarat where id_matkul=?", array('id_matkul' => $id_matkul));
  if ($has_prasyarat) {
    $is_lulus_setara = array();
    //$mk_syarat = $db2->query("select id_matkul,nama_mk,bobot_minimal_lulus from tb_data_matakuliah where id_matkul in($id_matkul_syarat)");
    $mk_syarat = $db2->query("select tb_data_matakuliah.id_matkul,tb_data_matakuliah_prasyarat.syarat,tb_data_matakuliah.kode_mk,tb_data_matakuliah.nama_mk,id_matkul_prasyarat,prs.kode_mk as mk_prasyarat_kode_mk,prs.nama_mk as nama_mk_prasyarat,
prs.bobot_minimal_lulus as mk_prasyarat_bobot_minimal_lulus from tb_data_matakuliah_prasyarat
inner join tb_data_matakuliah on tb_data_matakuliah_prasyarat.id_matkul=tb_data_matakuliah.id_matkul
inner join tb_data_matakuliah prs on id_matkul_prasyarat=prs.id_matkul
 where tb_data_matakuliah_prasyarat.id_matkul=?", array('id_matkul' => $id_matkul));
    foreach ($mk_syarat as $dt_mk_syarat) {
      if (!checkMatDiambilSyarat($dt_mk_syarat->id_matkul_prasyarat, $nim, $dt_mk_syarat->syarat)) {
        if ($dt_mk_syarat->syarat == 'L') {
          $status = "harus sudah diambil & Lulus";
        } else {
          $status = "harus sudah diambil";
        }
        $error_syarat[] = "Matakuliah " . $dt_mk_syarat->nama_mk_prasyarat . " $status";
      }
    }
    return $error_syarat;
  } else {
    return $error_syarat;
  }
}

function getStatusMahasiswa()
{
  global $db2;
  $status_mahasiswa_data = $db2->query("select * from tb_ref_status_mahasiswa");
  foreach ($status_mahasiswa_data as $status) {
    $status_mahasiswa[$status->id_stat_mhs] = $status->nm_stat_mhs;
  }
  return $status_mahasiswa;
}

function getProdiJenjangOnly()
{
  global $db2;
  //echo "select * from view_prodi_jenjang ".get_akses_prodi();
  $prodi_jenjang = $db2->query("select * from view_prodi_jenjang");
  foreach ($prodi_jenjang as $prodi) {
    $data_prodi[$prodi->kode_jur] = string_rapih($prodi->jurusan);
  }
  return $data_prodi;
}

function getProdiJenjang()
{
  global $db2;
  //echo "select * from view_prodi_jenjang ".get_akses_prodi();
  $prodi_jenjang = $db2->query("select * from view_prodi_jenjang " . get_akses_prodi());
  foreach ($prodi_jenjang as $prodi) {
    $data_prodi[$prodi->kode_jur] = string_rapih($prodi->jurusan);
  }
  return $data_prodi;
}
function getJenjang()
{
  global $db2;
  //echo "select * from view_prodi_jenjang ".get_akses_prodi();
  $prodi_jenjang = $db2->query("select * from view_prodi_jenjang " . get_akses_prodi());
  foreach ($prodi_jenjang as $prodi) {
    $data_prodi[$prodi->kode_jur] = string_rapih($prodi->jenjang);
  }
  return $data_prodi;
}
/**
 * get IP mahasiswa pada periode semester 
 * @param  [type] $nim         [description]
 * @param  [type] $id_semester periode semester
 * @return [type]              ip 
 */
function getDataIpSks($nim, $id_semester)
{
  global $db2;
  $data_ip_sks = array();
  $krs_data = $db2->fetchCustomSingle("select sum(total_sks) as jml_sks,sum(nilai_indeks * total_sks) as jml_bobot from tb_data_kelas_krs_detail inner join tb_data_matakuliah 
on tb_data_kelas_krs_detail.matkul_id=tb_data_matakuliah.id_matkul
inner join tb_data_kelas_krs using(krs_id) where nim=? and id_semester=?  and batal=0 and nilai_indeks is not null", array('nim' => $nim, 'id_semester' => $id_semester));
  if ($krs_data->jml_sks > 0) {
    //ip adalah jumlah bobot nilai (bobot * sks) / jumlah sks
    $ip = $krs_data->jml_bobot / $krs_data->jml_sks;
    $ip = number_format($ip, 2);
    $data_ip_sks['sks'] = $krs_data->jml_sks;
    $data_ip_sks['ip'] = $ip;
  }
  return $data_ip_sks;
}
/**
 * get IPK mahasiswa pada periode semester 
 * @param  [type] $nim         [description]
 * @param  [type] $id_semester periode semester
 * @return [type]              ip 
 */
function getIpk($nim, $id_semester = NULL)
{
  global $db;
  $and_semester = "";
  // if ($id_semester!=NULL) {
  //   $and_semester = "and id_semester<=".$id_semester;
  // }
  $krs_data = $db->query("select ipk from akm where mhs_nim=? ", array('mhs_nim' => $nim));
  $ipk = 0;
  foreach ($krs_data as $k) {
    $ipk = $k->ipk;
  }

  return $ipk;
}
function updateAkmSemesterNim($id_semester, $nim, $status = NULL)
{
  global $db2;
  //check if akm exist
  $check_akm = $db2->checkExist('tb_data_akm', array('id_semester' => $id_semester, 'nim' => $nim));
  if ($check_akm == false) {
    $data_insert_akm = array(
      'nim' => $nim,
      'id_semester' => $id_semester,
      'id_stat_mhs' => $status,
      'ip' => 0,
      'ipk' => 0,
      'jatah_sks' => 0,
      'sks_semester' => 0,
      'sks_wajib_kumulatif' => 0,
      'sks_pilihan_kumulatif' => 0,
      'sks_total' => 0,
      'date_created' => date('Y-m-d')
    );
    $db2->insert('tb_data_akm', $data_insert_akm);
  }
  $data_akm = $db2->fetchCustomSingle("select akm_id,id_stat_mhs,tb_data_akm.nim,(select sum(total_sks) from tb_data_kelas_krs_detail inner join tb_data_matakuliah 
  on tb_data_kelas_krs_detail.matkul_id=tb_data_matakuliah.id_matkul
  inner join tb_data_kelas_krs using(krs_id) where nim=tb_data_akm.nim and id_semester=tb_data_akm.id_semester  and batal=0) as sks,
  (select format(sum(nilai_indeks * total_sks)/sum(total_sks),2) from tb_data_kelas_krs_detail inner join tb_data_matakuliah 
  on tb_data_kelas_krs_detail.matkul_id=tb_data_matakuliah.id_matkul
  inner join tb_data_kelas_krs using(krs_id) where nim=tb_data_akm.nim and id_semester=tb_data_akm.id_semester  and batal=0 and nilai_indeks  is not null) as ip,
  (select format(sum(nilai_indeks * total_sks)/sum(total_sks),2) from tb_data_kelas_krs_detail inner join tb_data_matakuliah 
  on tb_data_kelas_krs_detail.matkul_id=tb_data_matakuliah.id_matkul
  inner join tb_data_kelas_krs using(krs_id) where nim=tb_data_akm.nim and id_semester<=tb_data_akm.id_semester  and batal=0 and nilai_indeks is not null) as ipk,
  (select sks_mak from tb_data_jatah_sks j where  IFNULL((select akm.ip  from tb_data_akm akm where akm.nim=tb_data_akm.nim
   and akm.id_semester<tb_data_akm.id_semester
  and akm.id_stat_mhs='A' ORDER BY id_semester DESC LIMIT 1),4) BETWEEN j.ip_min and j.ip_mak) as jatah_sks,
  (select SUM(IF(is_wajib = '1', total_sks,0)) from tb_data_kelas_krs_detail inner join tb_data_kelas_krs using (krs_id)
  inner join tb_data_matakuliah on matkul_id=id_matkul where nim=tb_data_akm.nim and id_semester<=tb_data_akm.id_semester  and batal=0) as sks_wajib_diambil_kumulatif,
  (select SUM(IF(is_wajib = '0', total_sks,0)) from tb_data_kelas_krs_detail inner join tb_data_kelas_krs using (krs_id)
  inner join tb_data_matakuliah on matkul_id=id_matkul where nim=tb_data_akm.nim and id_semester<=tb_data_akm.id_semester  and batal=0) as sks_pilihan_diambil_kumulatif,
  (select SUM(total_sks) from tb_data_kelas_krs_detail inner join tb_data_kelas_krs using (krs_id)
  inner join tb_data_matakuliah on matkul_id=id_matkul where nim=tb_data_akm.nim and id_semester<=tb_data_akm.id_semester  and batal=0) as sks_kumulatif
   from tb_data_akm 
  inner join tb_master_mahasiswa using(nim) where tb_data_akm.id_semester=? and tb_data_akm.nim=?", array('id_semester' => $id_semester, 'nim' => $nim));
  if ($data_akm->sks == "") {
    $sks_semester = 0;
  } else {
    $sks_semester = $data_akm->sks;
  }
  if ($data_akm->ip == "") {
    $ip = 0;
  } else {
    $ip = $data_akm->ip;
  }
  if ($data_akm->ipk == "") {
    $ipk = 0;
  } else {
    $ipk = $data_akm->ipk;
  }

  if ($data_akm->sks_wajib_diambil_kumulatif == "") {
    $sks_wajib_kumulatif = 0;
  } else {
    $sks_wajib_kumulatif = $data_akm->sks_wajib_diambil_kumulatif;
  }
  if ($data_akm->sks_pilihan_diambil_kumulatif == "") {
    $sks_pilihan_kumulatif = 0;
  } else {
    $sks_pilihan_kumulatif = $data_akm->sks_pilihan_diambil_kumulatif;
  }

  if ($data_akm->jatah_sks == "") {
    $jatah_sks = 0;
  } else {
    $jatah_sks = $data_akm->jatah_sks;
  }
  if ($data_akm->sks_kumulatif == "") {
    $sks_total = 0;
  } else {
    $sks_total = $data_akm->sks_kumulatif;
  }

  if ($status != NULL) {
    $id_stat_mhs = $status;
  } else {
    if ($sks_semester == 0) {
      $id_stat_mhs = 'N';
    } else {
      $id_stat_mhs = 'A';
    }
  }

  $data_update_akm = array(
    'ip' => $ip,
    'ipk' => $ipk,
    'id_stat_mhs' => $id_stat_mhs,
    'jatah_sks' => $jatah_sks,
    'sks_semester' => $sks_semester,
    'sks_wajib_kumulatif' => $sks_wajib_kumulatif,
    'sks_pilihan_kumulatif' => $sks_pilihan_kumulatif,
    'sks_total' => $sks_total,
    'date_updated' => date('Y-m-d')
  );
  $db2->update('tb_data_akm', $data_update_akm, 'akm_id', $data_akm->akm_id);
}

/**
 * get nama bulan indonesia
 * @param  int $bln month number -> month(date_column)
 * @return string      nama bulan
 */
function getBulan($bln)
{
  switch ($bln) {
    case 1:
      return "Januari";
      break;
    case 2:
      return "Februari";
      break;
    case 3:
      return "Maret";
      break;
    case 4:
      return "April";
      break;
    case 5:
      return "Mei";
      break;
    case 6:
      return "Juni";
      break;
    case 7:
      return "Juli";
      break;
    case 8:
      return "Agustus";
      break;
    case 9:
      return "September";
      break;
    case 10:
      return "Oktober";
      break;
    case 11:
      return "Nopember";
      break;
    case 12:
      return "Desember";
      break;
  }
}
function dump($data)
{
  echo "<pre>";
  print_r($data);
  echo "</pre>";
}
function get_matkul_konversi($id)
{
  global $db;

  // $db->query("delete from krs_detail whrere ")
  $q = $db->query("select k.*,m.nim_baru as nim from konversi_matkul k join mhs_pindah m on m.id=k.id_pindah  where id_pindah='$id'
   limit 1 ");

  foreach ($q as $k) {
    update_akm($k->nim);
    $db->query("delete from krs_detail where nim='$k->nim' and id_semester='10' and id_kelas='10' ");
  }
  $q = $db->query("select k.*,m.nim_baru as nim from konversi_matkul k join mhs_pindah m on m.id=k.id_pindah  where id_pindah='$id' and (kode_baru is not null or kode_baru!='') ");
  foreach ($q as $k) {
    $data = array(
      'kode_mk' => $k->kode_baru,
      'id_kelas' => "10",
      'nim' => $k->nim,
      'id_semester' => "10",
      'disetujui' => '1',
      'batal' => '0',
      'nilai_huruf' => $k->nilai,
      'nilai_angka' => $k->nilai_angka,
      'sks' => $k->sks,
      'bobot' => $k->bobot,
      'date_created' => date("Y-m-d H:i:s"),
      'tgl_perubahan' => date("Y-m-d H:i:s"),
      'tgl_perubahan_nilai' => date("Y-m-d H:i:s"),
      'pengubah' => $_SESSION['nama']
    );
    $db->insert("krs_detail", $data);
  }
}

// function get_bobot($nilai)
// {
//    global $db; 
//   // echo "select nilai_indeks from skala_nilai where nilai_huruf='$nilai' limit 1  ";
//    $q = $db->query("select nilai_indeks from skala_nilai where nilai_huruf='$nilai' limit 1  ");
//    foreach ($q as $k) {
//       return $k->nilai_indeks;
//    }
// }

function get_bobot($nilai, $kode_jur = NULL)
{
  global $db;
  $wh = "";
  if ($kode_jur != '') {
    $wh = "  and kode_jurusan='$kode_jur'";
  }
  $q = $db->query("select nilai_indeks from skala_nilai where
               nilai_huruf='$nilai' ");
  foreach ($q as $k) {
    return $k->nilai_indeks;
  }
}

function get_nilai_konversi($nim)
{
  global $db;


  $db->query("delete from krs_detail where nim='$nim' and id_semester='10' and id_kelas='10' ");
  // echo "select k.*,m.nim_baru as nim from konversi_matkul k join mhs_pindah m on m.id=k.id_pindah  where m.nim='$nim' and (kode_baru is not null or kode_baru!='')";
  $q = $db->query("select k.*,m.nim_baru as nim from konversi_matkul k join mhs_pindah m on m.id=k.id_pindah  where m.nim_baru='$nim' and (kode_baru is not null or kode_baru!='') ");
  foreach ($q as $k) {
    //  print_r($k); 
    // echo get_bobot($k->nilai);
    $data = array(
      'kode_mk' => $k->kode_baru,
      'id_kelas' => "10",
      'nim' => $k->nim,
      'id_semester' => "10",
      'disetujui' => '1',
      'batal' => '0',
      'bobot' => get_bobot($k->nilai),
      'nilai_huruf' => $k->nilai,
      'nilai_angka' => $k->nilai_angka,
      'sks' => $k->sks,
      // 'bobot' => $k->bobot ,
      'date_created' => date("Y-m-d H:i:s"),
      'tgl_perubahan' => date("Y-m-d H:i:s"),
      'tgl_perubahan_nilai' => date("Y-m-d H:i:s"),
      'pengubah' => $_SESSION['nama']
    );
    //print_r($data);
    $db->insert("krs_detail", $data);
  }
}




function getHariFromDate($date)
{
  $date = substr($date, 0, 10);
  if (validateDate($date)) {
    $day = date('D', strtotime($date));
    $dayList = array(
      'Sun' => 'Minggu',
      'Mon' => 'Senin',
      'Tue' => 'Selasa',
      'Wed' => 'Rabu',
      'Thu' => 'Kamis',
      'Fri' => 'Jumat',
      'Sat' => 'Sabtu'
    );
    $nama_hari = $dayList[$day];
  } else {
    $nama_hari = "";
  }

  return $nama_hari;
}

function save_file($inPath, $outPath)
{ //Download images from remote server
  $in = fopen($inPath, "rb");
  $out = fopen($outPath, "wb");
  while ($chunk = fread($in, 8192)) {
    fwrite($out, $chunk, 8192);
  }
  fclose($in);
  fclose($out);
}

function cek_mhs_pindahan($nim)
{
  global $db;
  $q = $db->query("select nim_lama,nim_baru from mhs_pindah where nim_baru='$nim'  ");
  if ($q->rowCount() > 0) {
    return true;
  } else {
    return false;
  }
}

?>