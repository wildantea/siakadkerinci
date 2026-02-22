<?php
session_start();
ini_set("display_errors", true);
include "../../inc/config.php";
session_check_json();
$time_start = microtime(true);
require('../../inc/lib/SpreadsheetReader.php');
switch ($_GET["act"]) {

    case 'input_nilai_coba':
    $array_id_krs = array_keys($_POST['nilai_angka']);
    foreach ($_POST['nim'] as $id_krs_detail => $nim) {
        if (in_array($id_krs_detail, $array_id_krs)) {
            $nim_implode[] = "'$nim'";
            $nilai_angka[$nim] = str_replace(",", ".", $_POST['nilai_angka'][$id_krs_detail]);
            $id_nilai[$nim] = $id_krs_detail;
        }

    }
    $nims = implode(",", $nim_implode);
    $data_mhs = $db->query("select nim,jur_kode,mulai_smt from mahasiswa where nim in($nims)");
    foreach ($data_mhs as $mhs) {
        $nilai = $nilai_angka[$mhs->nim];
        if ($mhs->mulai_smt>=20202) {
              $where_berlaku = "and berlaku_angkatan='".$mhs->mulai_smt."'"; 
        } else{
              $where_berlaku = "and berlaku_angkatan is null"; 
        }

        $skala_nilai = $db->fetch_custom_single("select * from skala_nilai where $nilai >= bobot_nilai_min && $nilai <= bobot_nilai_maks and kode_jurusan=? $where_berlaku",array('kode_jurusan' => $mhs->jur_kode));

        $data_update_nilai[] = array(
                'nilai_angka' => $nilai,
                'nilai_huruf' => $skala_nilai->nilai_huruf,
                'bobot' => $skala_nilai->nilai_indeks,
                'tgl_perubahan_nilai' => date('Y-m-d H:i:s'),
                'pengubah_nilai' =>  addslashes($_SESSION['nama'])
        );
        $id_krs[] = $id_nilai[$mhs->nim];
    }

$db->updateMulti('krs_detail',$data_update_nilai,'id_krs_detail',$id_krs);
        action_response($db->getErrorMessage());
      
        break;
    
    case 'update_nilai_all':

      $limit = $_GET['limit'];

      $q = $db->query("select nim from mahasiswa where update_akm='0'group by nim  limit $limit "); 
      foreach ($q as $k) {
           update_akm($k->nim); 
           $db->query("update mahasiswa set update_akm='1' where nim='$k->nim' ");
      } 
      
      echo "sukses"; 
        break;

    case 'input_nilai_new':
        foreach ($_POST['nilai_angka'] as $id_krs_detail => $nilai) {
            $exp_nilai = explode("#", $_POST['nilai_huruf'][$id_krs_detail]);
            $nilai_angka = str_replace(",", ".", $nilai);
            $data_update_nilai[] = array(
                'nilai_angka' => $nilai_angka,
                'nilai_huruf' => $exp_nilai[0],
                'bobot' => $exp_nilai[1],
                'tgl_perubahan_nilai' => date('Y-m-d H:i:s'),
                'pengubah_nilai' =>  addslashes($_SESSION['nama'])
            );
            $id_krs[] = $id_krs_detail;
        }
      
        $db->updateMulti('krs_detail',$data_update_nilai,'id_krs_detail',$id_krs);
        action_response($db->getErrorMessage());
        break;
    case 'import':
        if (!is_dir("../../../upload/upload_excel")) {
            mkdir("../../../upload/upload_excel");
        }
        
        
        if (!preg_match("/.(xls|xlsx)$/i", $_FILES["semester"]["name"])) {
            
            echo "pastikan file yang anda pilih xls|xlsx";
            exit();
            
        } else {
            move_uploaded_file($_FILES["semester"]["tmp_name"], "../../../upload/upload_excel/" . $_FILES['semester']['name']);
            $semester = array(
                "semester" => $_FILES["semester"]["name"]
            );
            
        }
        
        $error_count = 0;
        $error       = array();
        $sukses      = 0;
        $values      = "";
        $data_insert = array();
        $data_error  = array();
        
        
        $Reader = new SpreadsheetReader("../../../upload/upload_excel/" . $_FILES['semester']['name']);
        
        foreach ($Reader as $key => $val) {
            
            
            if ($key > 0) {
                
                if ($val[0] != '') {
                    //first check kode_mk
                    $nim          = trim(trimmer($val[0]));
                    $nama         = trim(trimmer($val[1]));
                    $kode_mk      = trim(trimmer($val[2]));
                    $nama_mk      = trim(trimmer($val[3]));
                    $semester     = trim(trimmer($val[4]));
                    $kelas        = trim(trimmer($val[5]));
                    $nilai_huruf  = trim(trimmer($val[6]));
                    $nilai_indeks = trim(trimmer($val[7]));
                    $nilai_angka  = trim(trimmer($val[8]));
                    $kode_jur     = trim(trimmer($val[9]));
                    
                    
                    
                    //check nim exist
                    $check_nim = $db->fetch_custom_single("select * from mahasiswa where trim(nim)=? and trim(jur_kode)=?", array(
                        'nim' => $nim,
                        'kode_jur' => $kode_jur
                    ));
                    
                    if ($check_nim == false) {
                        $error[]      = "NIM $nim $nama tidak ditemukan";
                        $data_error[] = array(
                            $nim,
                            $nama,
                            $kode_mk,
                            $nama_mk,
                            $semester,
                            $kelas,
                            $nilai_huruf,
                            $nilai_indeks,
                            $nilai_angka,
                            $kode_jur,
                            "NIM $nim $nama tidak ditemukan"
                        );
                        $error_count++;
                    } else {
                       //check if kode mk is exist
                            $check_kode_mk = $db->fetch_custom_single("select bobot_minimal_lulus, matkul.kode_mk,matkul.id_matkul from matkul inner join kurikulum on matkul.kur_id=kurikulum.kur_id where trim(kode_mk)=? and trim(kurikulum.kode_jur)=? group by id_matkul order by id_matkul desc limit 1", array(
                                'kode_mk' => $kode_mk,
                                'kode_jur' => $kode_jur
                              ));
                                if ($check_kode_mk == false) {
                                    $error[]      = "Kode MK " . $kode_mk . " tidak ditemukan";
                                    $data_error[] = array(
                                        $nim,
                                        $nama,
                                        $kode_mk,
                                        $nama_mk,
                                        $semester,
                                        $kelas,
                                        $nilai_huruf,
                                        $nilai_indeks,
                                        $nilai_angka,
                                        $kode_jur,
                                        "Kode MK " . $kode_mk . " tidak ditemukan"
                                    );
                                    $error_count++;
                                } else {
                                    //check kelas exist
                                    $check_kelas = $db->fetch_custom_single("SELECT kelas_id FROM `view_nama_kelas` WHERE `kode_mk` = ? AND `sem_id` = ? AND `kls_nama` = ? ",array(
                                          'kode_mk' => $kode_mk,
                                          'sem_id' => $semester,
                                          'kls_nama' => $kelas
                                        )
                                      );
                                if ($check_kelas == false) {
                                        $error[]      = "Kelas dengan nama $kelas dan kode MK $kode_mk Periode $semester tidak ditemukan";
                                        $data_error[] = array(
                                            $nim,
                                            $nama,
                                            $kode_mk,
                                            $nama_mk,
                                            $semester,
                                            $kelas,
                                            $nilai_huruf,
                                            $nilai_indeks,
                                            $nilai_angka,
                                            $kode_jur,
                                            "Kelas dengan nama $kelas dan kode MK $kode_mk tidak ditemukan"
                                        );
                                        $error_count++;
                                    } else {
                                        $check_krs = $db->fetch_custom_single("select * from krs_detail where trim(id_semester)=? and kode_mk=? and trim(nim)=?", array(
                                            'id_semester' => $semester,
                                            'kode_mk' => $check_kode_mk->id_matkul,
                                            'nim' => $nim
                                        ));
                                        if ($check_krs == false) {
                                            $error[]      = "Krs $nim $nama $kode_mk di $semester tidak ditemukan";
                                            $data_error[] = array(
                                                $nim,
                                                $nama,
                                                $kode_mk,
                                                $nama_mk,
                                                $semester,
                                                $kelas,
                                                $nilai_huruf,
                                                $nilai_indeks,
                                                $nilai_angka,
                                                $kode_jur,
                                                "Krs $nim $nama $kode_mk di $semester tidak ditemukan"
                                            );
                                            $error_count++;
                                        } else {
                                            $array_import[] = array(
                                                'nilai_huruf' => $nilai_huruf,
                                                'nilai_angka' => $nilai_angka,
                                                'bobot' => $nilai_indeks,
                                                'pengubah' => $_SESSION['nama'],
                                                'tgl_perubahan' => date('Y-m-d H:i:s'),
                                                'sdh_dinilai' => '1'
                                            );
                                            $primary_value[] = $check_krs->id_krs_detail;
                                            update_akm($nim);
                                            $sukses++;
                                        }
                                        
                                    }
                                }
                                //end check nim
                              }
                              //end val!=0
                    }
                
            }
            
        }


      if (!empty($data_error)) {
          include "download_error_import.php";
      }

        if (!empty($array_import)) {
            $update = $db->updateMulti('krs_detail', $array_import,'id_krs_detail',$primary_value);
            echo $db->getErrorMessage();

        }
        
        unlink("../../../upload/upload_excel/" . $_FILES['semester']['name']);
        $msg            = '';
        $time_end       = microtime(true);
        $execution_time = ($time_end - $time_start);
        
        if (($sukses > 0) || ($error_count > 0)) {
            $msg = "<div class=\"alert alert-warning alert-dismissible\" role=\"alert\" style=\"margin-bottom: 0;\" >
    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>
    <font color=\"#3c763d\">" . $sukses . " data Nilai baru berhasil di import</font><br />
    <font color=\"#ce4844\" >" . $error_count . " data tidak bisa ditambahkan </font>";
            if (!$error_count == 0) {
                $msg .= "<a data-toggle=\"collapse\" href=\"#collapseExample\" aria-expanded=\"false\" aria-controls=\"collapseExample\">Detail error</a>";
            }
            
            if ($error_count > 0) {
                $msg .= "<div class=\"collapse\" id=\"collapseExample\">";
                $i = 1;
                foreach ($error as $pesan) {
                    $msg .= "<div class=\"bs-callout bs-callout-danger\">" . $i . ". " . $pesan . "</div><br />";
                    $i++;
                }
                $msg .= "</div><br><a href='" . base_url() . "upload/sample/nilai/error_nilai.xlsx' class='btn btn-sm btn-primary' style='text-decoration:none;'>Download Data Error</a><br>";
            }
            
            $msg .= "<p>Total Waktu Import : " . waktu_import($execution_time);
            $msg .= "</div>";
            
        }
        echo $msg;
        break;
    case "input_nilai":
        $presensi    = 0;
        $mandiri     = 0;
        $terstruktur = 0;
        $lain_lain   = 0;
        $uts         = 0;
        $uas         = 0;
        /*   echo "<pre>";
        print_r($_POST); die();*/
        $komponen    = array();
        foreach ($db->query("select kp.id_komponen, k.kls_nama,m.nama_mk,kn.nama_komponen,kp.nilai from kelas k 
                          join matkul m on k.id_matkul=m.id_matkul
                          join kelas_penilaian kp on kp.id_kelas=k.kelas_id
                          join komponen_nilai kn on kn.id=kp.id_komponen
                          where k.kelas_id='" . $_POST['id_kelas'] . "'") as $data_kelas) {
            $komponen[$data_kelas->id_komponen] = $data_kelas->nilai;
        }
        foreach ($db->query("select k.id_krs_detail, m.nim,m.nama,k.mandiri, k.terstruktur,
                        k.lain_lain,k.uts,k.uas,k.presensi from krs_detail k  join mahasiswa m 
                        on m.nim=k.nim where k.id_kelas='" . $_POST['id_kelas'] . "'") as $k) {
            /*      $data = array('presensi' => $_POST['presensi-'.$k->id_krs_detail] ,
            'mandiri'  => $_POST['mandiri-'.$k->id_krs_detail],
            'terstruktur' => $_POST['terstruktur-'.$k->id_krs_detail],
            'lain_lain' => $_POST['lain_lain-'.$k->id_krs_detail],
            'uts' => $_POST['uts-'.$k->id_krs_detail],
            'uas' => $_POST['uas-'.$k->id_krs_detail] );*/
            $nilai_angka     = 0;
            $komponen_kosong = true;
            if (count($komponen) > 0) {
                foreach ($komponen as $key => $value) {
                    if ($_POST['komponen-' . $k->id_krs_detail . "-" . $key] != '') {
                        
                        $db->query("update krs_penilaian set nilai_angka='" . $_POST['komponen-' . $k->id_krs_detail . "-" . $key] . "' ,
                      edit_by='" . $_SESSION['nama'] . "'
                      where id_krs_detail='$k->id_krs_detail' and id_komponen='$key' ");
                        if ($value != 0) {
                            $nilai_angka = $nilai_angka + (((int) $_POST['komponen-' . $k->id_krs_detail . "-" . $key] / 100) * $value);
                        }
                        $komponen_kosong = false;
                    }
                }
            }
            
            if (isset($_POST['rule_komponen-' . $k->id_krs_detail])) {
                // $use_rule=",use_rule='1' ";
                $db->query("update krs_detail set use_rule='1' where id_krs_detail='$k->id_krs_detail' ");
            } else {
                // $use_rule=",use_rule='0' ";
                $db->query("update krs_detail set use_rule='0' where id_krs_detail='$k->id_krs_detail' ");
                
            }
            // if ($komponen_kosong==false) {
            if (($_POST['nilai_huruf-' . $k->id_krs_detail] == '' || $_POST['nilai_huruf-' . $k->id_krs_detail] != '') && $komponen_kosong == false && isset($_POST['rule_komponen-' . $k->id_krs_detail])) {
                $nilai_total         = $nilai_angka;
                $data['nilai_angka'] = $nilai_total;
                foreach ($db->query("select * from nilai_ref n where n.batas_bawah<=$nilai_total and $nilai_total<=n.batas_atas
                                and prodi_id='" . de($_POST['jur']) . "' ") as $n) {
                    if ($nilai_total == 0) {
                        //$data['bobot'] =  $n->bobot;
                        $bobotq       = "bobot=NULL";
                        $nilai_hurufq = "nilai_huruf=NULL";
                    } else {
                        $bobotq       = "bobot='$n->bobot'";
                        $nilai_hurufq = "nilai_huruf='$n->nilai_huruf' ";
                    }
                    $db->query("update krs_detail set nilai_angka='$nilai_angka',
                         $bobotq,$nilai_hurufq, pengubah='" . $_SESSION['nama'] . "'
                          where id_krs_detail='$k->id_krs_detail' ");
                }
            } else {
                $nilai               = explode("-", $_POST['nilai_huruf-' . $k->id_krs_detail]);
                $data['bobot']       = $nilai[0];
                $data['nilai_huruf'] = $nilai[1];
                $db->query("update krs_detail set nilai_angka='$nilai_angka',
                       bobot='" . $nilai[0] . "',nilai_huruf='" . $nilai[1] . "',
                       pengubah='" . $_SESSION['nama'] . "' where id_krs_detail='$k->id_krs_detail'");
                // $data['test'] = "999";
            }
            // }
            
            
            // print_r($data);
            //$db->update("krs_detail",$data,"id_krs_detail",$_POST["id_krs_detail-".$k->id_krs_detail]);
            
        }
        
        break;
    case "in":
        
        
        
        
        $data = array(
            "id_agama" => $_POST["id_agama"],
            "nm_agama" => $_POST["nm_agama"]
        );
        
        
        
        
        $in = $db->insert("agama", $data);
        
        
        if ($in = true) {
            echo "good";
        } else {
            return false;
        }
        break;
    case "delete":
        
        
        
        $db->delete("agama", "id_agama", $_GET["id"]);
        break;
    case "del_massal":
        $data_ids      = $_REQUEST["data_ids"];
        $data_id_array = explode(",", $data_ids);
        if (!empty($data_id_array)) {
            foreach ($data_id_array as $id) {
                $db->delete("agama", "id_agama", $id);
            }
        }
        break;
    case "up":
        
        $data = array(
            "id_agama" => $_POST["id_agama"],
            "nm_agama" => $_POST["nm_agama"]
        );
        
        
        
        
        
        
        $up = $db->update("agama", $data, "id_agama", $_POST["id"]);
        
        if ($up = true) {
            echo "good";
        } else {
            return false;
        }
        break;
    default:
        # code...
        break;
}

?>