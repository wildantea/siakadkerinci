<?php
session_start();
include "../../inc/config.php";
session_check_json();
$time_start = microtime(true); 
require('../../inc/lib/spreadsheetreader/SpreadsheetReader.php');
function get_object_nilai_komponen($id_componen,$val,$has_presensi,$nim) {
    $indek_id = 4;
    foreach ($id_componen as $id_komponen) {
            $array_komponen_value[] = array(
                'id' => $id_komponen,
                'nilai' => $val[$indek_id]
            );
            $indek_id++;
    }
    if ($has_presensi>0) {
      global $db2;
      $nilai_absen = 0;
      $jml_pertemuan = $db2->fetchCustomSingle("select count(id_pertemuan) as jml_pertemuan from tb_data_kelas_pertemuan where kelas_id='".$_POST['kelas_id']."'");
      if ($jml_pertemuan->jml_pertemuan>0) {
        $nilai_absen = ((get_nilai_absen($nim)/$jml_pertemuan->jml_pertemuan)*100);
      }
        $array_komponen_value[] = array(
                'id' => 1,
                'nilai' => round($nilai_absen,2)
        );
    }
   
    return json_encode($array_komponen_value);
}
function get_absen($kelas_id,$nim_user) {
    global $db2;
    $absen = $db2->query("select isi_absensi from tb_data_kelas_absensi inner join tb_data_kelas_pertemuan using(id_pertemuan)
    where kelas_id=$kelas_id");
    $jml_hadir = 0;
    $status_absen = array();
    if ($absen->rowCount()>0) {
      foreach ($absen as $ab ) {
        $data_absen = json_decode($ab->isi_absensi);
        foreach ($data_absen as $nim ) {
          if ($nim->status_absen=='Hadir') {
            $status_absen[$nim->nim][] = $nim->status_absen;
          }
        }
      }
    }
    if (isset($status_absen[$nim_user])) {
      $jml_hadir = count($status_absen[$nim_user]);
    } else {
      $jml_hadir = $jml_hadir;
    }
    return $jml_hadir;
  }
  function get_nilai_absen($nim_user) {
    global $db2;
    $kelas_id = $_POST['kelas_id'];
    $absen = $db2->query("select isi_absensi from tb_data_kelas_absensi inner join tb_data_kelas_pertemuan using(id_pertemuan)
    where kelas_id=$kelas_id");
    $jml_hadir = 0;
    $status_absen = array();
    if ($absen->rowCount()>0) {
      foreach ($absen as $ab ) {
        $data_absen = json_decode($ab->isi_absensi);
        foreach ($data_absen as $nim ) {
          if ($nim->status_absen=='Hadir') {
            $status_absen[$nim->nim][] = $nim->status_absen;
          }
        }
      }
    }
    if (isset($status_absen[$nim_user])) {
      $jml_hadir = count($status_absen[$nim_user]);
    } else {
      $jml_hadir = $jml_hadir;
    }
    return $jml_hadir;
  }
function get_nilai_akhir($value_componen,$val,$has_presensi,$nim) {
    global $db2;
    $indek_id = 4;
    $nilai = 0;
    foreach ($value_componen as $key) {
        $nilai+= $val[$indek_id] * ($key/100);
        $indek_id++;
    }
    if ($has_presensi>0) {
      $nilai_absen = 0;
      $jml_pertemuan = $db2->fetchCustomSingle("select count(id_pertemuan) as jml_pertemuan from tb_data_kelas_pertemuan where kelas_id='".$_POST['kelas_id']."'");
      if ($jml_pertemuan->jml_pertemuan>0) {
        $nilai_absen = ((get_nilai_absen($nim)/$jml_pertemuan->jml_pertemuan)*100);
        $nilai_absen = round($nilai_absen * ($has_presensi/100),2);
      }
      $nilai+=$nilai_absen;
    }
   
    $nilai_akhir = $nilai;

    // dump($nilai_akhir.'nim'.$nim);
    return $nilai_akhir;
}
function get_nilai_huruf($kode_jurusan,$nilai_angka) {
    global $db2;
$bobot=$nilai_angka;
$nilai = array();
$skala_nilai = $db2->query("select * from tb_data_skala_nilai where kode_jurusan='$kode_jurusan'");
      foreach ($skala_nilai as $skala) {
      	$min = $skala->bobot_nilai_min;
      	$max = $skala->bobot_nilai_maks;
      	if ( $bobot >=$min && $bobot <=$max) {
      		$nilai = array(
                'huruf' => $skala->nilai_huruf,
                'indeks' => $skala->nilai_indeks
            );
      	}
      }
      return $nilai;
}
$act = $_GET['act'];
switch ($act) {
    case 'kom':

        if (!preg_match("/.(xls|xlsx)$/i", $_FILES["semester"]["name"]) ) {
            echo "pastikan file yang anda pilih xls|xlsx";
            exit();
        } else {
            move_uploaded_file($_FILES["semester"]["tmp_name"], "../../../upload/upload_excel/".$_FILES['semester']['name']);
            $semester = array("semester"=>$_FILES["semester"]["name"]);

        }

        $error_count = 0;
        $error = array();
        $sukses = 0;
        $values = "";
        $data_insert = array();
        $data_error = array();
        $data_insert = array();


        $Reader = new SpreadsheetReader("../../../upload/upload_excel/".$_FILES['semester']['name']);

        $kelas_data = $db2->fetchCustomSingle("SELECT tdk.kelas_id,tdk.kls_nama,kuota,kode_jur,ada_komponen,komponen,vmk.kode_mk,tdk.sem_id,vmk.nama_mk,vmk.total_sks,vmk.kode_jur,vmk.kode_jur, vmk.nama_jurusan from tb_data_kelas tdk
        INNER JOIN view_matakuliah_kurikulum vmk using(id_matkul) where kelas_id=?",array('kelas_id' => $_POST['kelas_id']));

        $komponen = json_decode($kelas_data->komponen);
        foreach ($komponen as $key) {
            if (is_array($key)) {
                foreach ($key as $val) {
                    if ($val->id!=1) {
                        $id_componen[] = $val->id;
                        $value_componen[] = $val->value_komponen;
                    } else {
                        if ($val->id==1) {
                            $has_presensi = $val->value_komponen;
                        } else {
                            $has_presensi = 0;
                        }
                        
                        
                    }

                }
            }
        }
      
$array_update = array();
$last_col_attribute_mhs = 4; //column D
$nilai_akhir = count($id_componen)+$last_col_attribute_mhs;
$kelas_id = $_POST['kelas_id'];
$kode_jur = $kelas_data->kode_jur;


        foreach ($Reader as $key => $val)
        {

            if ($key>4) {
                if ($val[0]!='') {
                    $nim = $val[1];
                    //get krs id
                    $krs = $db2->fetchCustomSingle("select tb_data_kelas_krs_detail.krs_id,history_nilai,krs_detail_id from tb_data_kelas_krs_detail inner join tb_data_kelas_krs using(krs_id)
                    where nim='$nim' and kelas_id='$kelas_id'");
                    if ($krs) {
                        $krs_id = $krs->krs_id;
                        $decode_history = array();
                            if ($krs->history_nilai!="") {
                                //decode it
                                $decode_history =  json_decode($krs->history_nilai);
                            }

                        $array_history = array(
                            'nilai_angka' => get_nilai_akhir($value_componen,$val,$has_presensi,$nim),
                            'nilai_huruf' => get_nilai_huruf($kode_jur,get_nilai_akhir($value_componen,$val,$has_presensi,$nim))['huruf'],
                            'nilai_indeks' => get_nilai_huruf($kode_jur,get_nilai_akhir($value_componen,$val,$has_presensi,$nim))['indeks'],
                            'use_rule' => 1,
                            'pengubah' => getProfilUser($_SESSION['id_user'])->full_name,
                            'user_pengubah' => getProfilUser($_SESSION['id_user'])->username,
                            'date_updated' => date('Y-m-d H:i:s')
                        );

                        if (!empty($decode_history)) {
                            $convert_obj = $db2->converObjToArray($decode_history);
                            $data_history = array_merge($convert_obj,array($array_history));
                            //convert_obj
                        } else {
                            $data_history = array($array_history);
                        }
                        $krs_detail_id[] = $krs->krs_detail_id;
                        $array_update[] = array(
                            'nilai_angka' => get_nilai_akhir($value_componen,$val,$has_presensi,$nim),
                            'nilai_huruf' => get_nilai_huruf($kode_jur,get_nilai_akhir($value_componen,$val,$has_presensi,$nim))['huruf'],
                            'nilai_indeks' => get_nilai_huruf($kode_jur,get_nilai_akhir($value_componen,$val,$has_presensi,$nim))['indeks'],
                            'pengubah' => getProfilUser($_SESSION['id_user'])->full_name,
                            'user_pengubah' => getProfilUser($_SESSION['id_user'])->username,
                            'date_updated' => date('Y-m-d H:i:s'),
                            'history_nilai' => json_encode($data_history),
                            'komponen_nilai' => get_object_nilai_komponen($id_componen,$val,$has_presensi,$nim)
                        );
                        $sukses++;
                    } else {
                        $error_count++;
                        $error[] = "NIM Mahasiswa ".$nim." Tidak ada di Kelas ini";
                    }

                }

            }
        }

       

        if (!empty($array_update)) {
         $insert = $db2->updateMulti('tb_data_kelas_krs_detail',$array_update,'krs_detail_id',$krs_detail_id);
          echo $db2->getErrorMessage();
        }
        unlink("../../../upload/upload_excel/".$_FILES['semester']['name']);
        $msg = '';
        $time_end = microtime(true);
        $execution_time = ($time_end - $time_start);
        
        if (($sukses>0) || ($error_count>0)) {
          $msg =  "<div class=\"alert alert-warning alert-dismissible\" role=\"alert\" style=\"margin-bottom: 0;\" >
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>
          <font color=\"#3c763d\">".$sukses." data Nilai berhasil di import</font><br />
          <font color=\"#ce4844\" >".$error_count." data tidak bisa ditambahkan </font>";
          if (!$error_count==0) {
              $msg .= "<a data-toggle=\"collapse\" href=\"#collapseExample\" aria-expanded=\"false\" aria-controls=\"collapseExample\">Detail error</a>";
          }
          
          if ($error_count>0) {
              $msg .= "<div class=\"collapse\" id=\"collapseExample\">";
              $i=1;
              foreach ($error as $pesan) {
                  $msg .= "<div class=\"bs-callout bs-callout-danger\">".$i.". ".$pesan."</div><br />";
                  $i++;
              }
            //  $msg .= "</div><br><a href='".base_url()."upload/sample/dosen/error_dosen.xlsx' class='btn btn-sm btn-primary' style='text-decoration:none;'>Download Data Error</a><br>";
          }
        
          $msg .= "<p>Total Waktu Import : ". waktu_import($execution_time);
          $msg .= "</div>";
        
        }
        echo $msg;
            break; 
    
    default:
        # code...
        break;
}
