<?php
session_start();
include "../../inc/config.php";
require('../../inc/lib/spreadsheetreader/SpreadsheetReader.php');
session_check_json();
$time_start = microtime(true);
function get_object_nilai_komponen($nilai_id)
{
    $indek_id = 0;
    foreach ($_POST['id'][$nilai_id] as $id_komponen) {
        $array_komponen_value[] = array(
            'id' => $id_komponen,
            'nilai' => $_POST['nilai'][$nilai_id][$indek_id]
        );
        $indek_id++;
    }
    return json_encode($array_komponen_value);
}
switch ($_GET["act"]) {
    case 'publish':
        $db2->update("tb_data_kelas", array('is_umumkan' => $_POST['status']), "kelas_id", $_POST['id']);
        action_response($db2->getErrorMessage());
        break;
    case 'kunci':
        if ($_POST['status'] == 'buka') {
            $status = '0';
        } else {
            $status = '1';
        }
        $db2->update("tb_data_kelas", array('is_kunci' => $status), "kelas_id", $_POST['id']);
        action_response($db2->getErrorMessage());
        break;
    case 'input_nilai_komponen_admin':

        // =============== HELPER FUNCTIONS ===============

        // Hitung nilai akhir dari komponen per id_krs_detail
        function hitung_nilai_akhir($krs_detail_id)
        {
            if (!isset($_POST['nilai'][$krs_detail_id])) {
                return 0;
            }

            $komponen = $_POST['nilai'][$krs_detail_id];        // array nilai komponen
            $prosentase = $_POST['prosentase'][$krs_detail_id] ?? []; // array persen komponen

            $total = 0;

            foreach ($komponen as $i => $nilai) {
                $v = floatval(str_replace(',', '.', $nilai));

                $p = isset($prosentase[$i])
                    ? floatval(str_replace(',', '.', $prosentase[$i]))
                    : 0;

                $total += $v * ($p / 100);
            }

            // pembulatan 2 digit
            return round($total, 2);
        }

        // Ambil nilai huruf + indeks dari skala_nilai
        function get_nilai_huruf_server($kode_jurusan, $angkatan, $nilai_akhir)
        {
            global $db2; // gunakan koneksi yang sama dengan krs_detail & skala_nilai

            $where_berlaku = ($angkatan >= 20202)
                ? "AND berlaku_angkatan='" . $angkatan . "'"
                : "AND (berlaku_angkatan is null or berlaku_angkatan='')";

            $skala_nilai = $db2->query("
            SELECT *
            FROM skala_nilai
            WHERE kode_jurusan=? $where_berlaku
        ", ['kode_jurusan' => $kode_jurusan]);

            if ($skala_nilai->rowCount() == 0) {
                // fallback kalau skala belum dibuat
                return ['', 0];
            }

            foreach ($skala_nilai as $skala) {
                $min = floatval($skala->bobot_nilai_min);
                $max = floatval($skala->bobot_nilai_maks);

                if ($nilai_akhir >= $min && $nilai_akhir <= $max) {
                    return [$skala->nilai_huruf, $skala->nilai_indeks];
                }
            }

            // fallback kalau tidak masuk range manapun
            return ['', 0];
        }

        // =============== PROSES UPDATE ===============

        // Ambil semua key id_krs_detail dari nilai_akhir
        $implode_key = implode(",", array_keys($_POST['nilai_akhir']));

        // Ambil history lama
        $history = $db2->query("
        SELECT id_krs_detail AS krs_detail_id, history_nilai
        FROM krs_detail
        WHERE id_krs_detail IN($implode_key)
    ");

        $decode_history = [];
        foreach ($history as $his) {
            if ($his->history_nilai != "") {
                $decode_history[$his->krs_detail_id] = json_decode($his->history_nilai);
            }
        }

        $array_update_nilai = [];
        $id_krs = [];

        // Info user
        $profil_user = getProfilUser($_SESSION['id_user']);
        $nama_pengubah = str_replace("'", "", $profil_user->first_name . ' ' . $profil_user->last_name);
        $username_pengubah = $profil_user->username;
        $now = date('Y-m-d H:i:s');

        foreach ($_POST['nilai_akhir'] as $key_nilai => $value_nilai_client) {

            // Hitung ulang nilai akhir di server (ABA IKAN nilai_akhir dari client)
            $nilai_akhir = hitung_nilai_akhir($key_nilai);

            // Ambil kode_jurusan & angkatan dari POST (hidden input per baris)
            $kode_jurusan = $_POST['kode_jurusan'][$key_nilai] ?? '';
            $angkatan = $_POST['angkatan'][$key_nilai] ?? 0;

            // Dapatkan nilai huruf & indeks dari server
            list($nilai_huruf, $nilai_indeks) = get_nilai_huruf_server(
                $kode_jurusan,
                $angkatan,
                $nilai_akhir
            );

            // Tambahkan ke batch update
            $array_update_nilai[] = [
                'nilai_angka' => $nilai_akhir,
                'nilai_huruf' => $nilai_huruf,
                'use_rule' => 1,
                'bobot' => $nilai_indeks,
                'komponen_nilai' => get_object_nilai_komponen($key_nilai),
            ];

            $id_krs[] = $key_nilai;

            // Kalau masih mau update AKM / sesuatu yg lain, bisa di sini:
            // $get_nim_semestser = $db2->fetchCustomSingle("SELECT nim,id_semester FROM krs_detail WHERE id_krs_detail='".$key_nilai."'");
            // update_akm($get_nim_semestser->nim);

        }

        // Eksekusi batch update
        if (!empty($array_update_nilai) && !empty($id_krs)) {
            $db2->updateMulti('krs_detail', $array_update_nilai, 'id_krs_detail', $id_krs);
        }

        action_response($db2->getErrorMessage());
        break;
    case 'input_nilai_komponen':

        // =============== HELPER FUNCTIONS ===============

        // Hitung nilai akhir dari komponen per id_krs_detail
        function hitung_nilai_akhir($krs_detail_id)
        {
            if (!isset($_POST['nilai'][$krs_detail_id])) {
                return 0;
            }

            $komponen = $_POST['nilai'][$krs_detail_id];        // array nilai komponen
            $prosentase = $_POST['prosentase'][$krs_detail_id] ?? []; // array persen komponen

            $total = 0;

            foreach ($komponen as $i => $nilai) {
                $v = floatval(str_replace(',', '.', $nilai));

                $p = isset($prosentase[$i])
                    ? floatval(str_replace(',', '.', $prosentase[$i]))
                    : 0;

                $total += $v * ($p / 100);
            }

            // pembulatan 2 digit
            return round($total, 2);
        }

        // Ambil nilai huruf + indeks dari skala_nilai
        function get_nilai_huruf_server($kode_jurusan, $angkatan, $nilai_akhir)
        {
            global $db2; // gunakan koneksi yang sama dengan krs_detail & skala_nilai

            $where_berlaku = ($angkatan >= 20202)
                ? "AND berlaku_angkatan='" . $angkatan . "'"
                : "AND (berlaku_angkatan is null or berlaku_angkatan='')";

            $skala_nilai = $db2->query("
            SELECT *
            FROM skala_nilai
            WHERE kode_jurusan=? $where_berlaku
        ", ['kode_jurusan' => $kode_jurusan]);

            if ($skala_nilai->rowCount() == 0) {
                // fallback kalau skala belum dibuat
                return ['', 0];
            }

            foreach ($skala_nilai as $skala) {
                $min = floatval($skala->bobot_nilai_min);
                $max = floatval($skala->bobot_nilai_maks);

                if ($nilai_akhir >= $min && $nilai_akhir <= $max) {
                    return [$skala->nilai_huruf, $skala->nilai_indeks];
                }
            }

            // fallback kalau tidak masuk range manapun
            return ['', 0];
        }

        // =============== PROSES UPDATE ===============

        // Ambil semua key id_krs_detail dari nilai_akhir
        $implode_key = implode(",", array_keys($_POST['nilai_akhir']));

        // Ambil history lama
        $history = $db2->query("
        SELECT id_krs_detail AS krs_detail_id, history_nilai
        FROM krs_detail
        WHERE id_krs_detail IN($implode_key)
    ");

        $decode_history = [];
        foreach ($history as $his) {
            if ($his->history_nilai != "") {
                $decode_history[$his->krs_detail_id] = json_decode($his->history_nilai);
            }
        }

        $array_update_nilai = [];
        $id_krs = [];

        // Info user
        $profil_user = getProfilUser($_SESSION['id_user']);
        $nama_pengubah = str_replace("'", "", $profil_user->first_name . ' ' . $profil_user->last_name);
        $username_pengubah = $profil_user->username;
        $now = date('Y-m-d H:i:s');

        foreach ($_POST['nilai_akhir'] as $key_nilai => $value_nilai_client) {

            // Hitung ulang nilai akhir di server (ABA IKAN nilai_akhir dari client)
            $nilai_akhir = hitung_nilai_akhir($key_nilai);

            // Ambil kode_jurusan & angkatan dari POST (hidden input per baris)
            $kode_jurusan = $_POST['kode_jurusan'][$key_nilai] ?? '';
            $angkatan = $_POST['angkatan'][$key_nilai] ?? 0;

            // Dapatkan nilai huruf & indeks dari server
            list($nilai_huruf, $nilai_indeks) = get_nilai_huruf_server(
                $kode_jurusan,
                $angkatan,
                $nilai_akhir
            );

            // Siapkan data history baru
            $array_history = [
                'nilai_angka' => $nilai_akhir,
                'nilai_huruf' => $nilai_huruf,
                'nilai_indeks' => $nilai_indeks,
                'use_rule' => 1,
                'pengubah' => $nama_pengubah,
                'pengubah_nilai' => $nama_pengubah,
                'date_updated' => $now,
                'tgl_perubahan_nilai' => $now
            ];

            // Merge ke history lama kalau ada
            if (isset($decode_history[$key_nilai])) {
                $convert_obj = $db2->converObjToArray($decode_history[$key_nilai]);
                $data_history = array_merge($convert_obj, [$array_history]);
            } else {
                $data_history = [$array_history];
            }

            // Tambahkan ke batch update
            $array_update_nilai[] = [
                'nilai_angka' => $nilai_akhir,
                'nilai_huruf' => $nilai_huruf,
                'use_rule' => 1,
                'bobot' => $nilai_indeks,
                'komponen_nilai' => get_object_nilai_komponen($key_nilai),
                'pengubah' => $nama_pengubah,
                'pengubah_nilai' => $nama_pengubah,
                'date_updated' => $now,
                'tgl_perubahan_nilai' => $now,
                'history_nilai' => json_encode($data_history)
            ];

            $id_krs[] = $key_nilai;

            // Kalau masih mau update AKM / sesuatu yg lain, bisa di sini:
            // $get_nim_semestser = $db2->fetchCustomSingle("SELECT nim,id_semester FROM krs_detail WHERE id_krs_detail='".$key_nilai."'");
            // update_akm($get_nim_semestser->nim);

        }

        // Eksekusi batch update
        if (!empty($array_update_nilai) && !empty($id_krs)) {
            $db2->updateMulti('krs_detail', $array_update_nilai, 'id_krs_detail', $id_krs);
        }

        action_response($db2->getErrorMessage());
        break;

    case 'input_komponen':
        if (isset($_POST['nama_komponen'])) {
            if ($_POST['total_prosentase'] < 100 || $_POST['total_prosentase'] > 100) {
                action_response("Total Prosentase harus 100%");
            }
            $i = 0;
            foreach ($_POST['nama_komponen'] as $data) {
                $data_komponen['komponen'][] = array(
                    'id' => $_POST['id_komponen'][$i],
                    'nama_komponen' => $data,
                    'value_komponen' => $_POST['prosentase'][$i],
                );
                $i++;
            }
            $data_komponen['total_prosentase'] = $_POST['total_prosentase'];

            $array_update = array(
                'ada_komponen' => 'Y',
                'komponen' => json_encode($data_komponen)
            );
        } else {
            $array_update = array(
                'ada_komponen' => 'N',
                'komponen' => NULL
            );
        }

        $db2->update('kelas', $array_update, 'kelas_id', $_POST['kelas_id']);
        action_response($db2->getErrorMessage());
    case 'delete':
        $db2->delete("tb_data_kelas_krs_detail", "krs_detail_id", $_POST["id"]);
        action_response($db2->getErrorMessage());
        break;
    case "del_massal":
        $data_ids = $_REQUEST["data_ids"];
        $data_id_array = explode(",", $data_ids);
        if (!empty($data_id_array)) {
            foreach ($data_id_array as $id) {
                $db2->delete("tb_data_kelas_krs_detail", "krs_detail_id", $id);
            }
        }
        action_response($db2->getErrorMessage());
        break;
    case 'input_nilai':
        //check history
        $implode_key = implode(",", array_keys($_POST['nilai_angka']));
        //check history
        $history = $db2->query("select krs_detail_id, history_nilai from tb_data_kelas_krs_detail where krs_detail_id in($implode_key)");
        $decode_history = array();
        foreach ($history as $his) {
            if ($his->history_nilai != "") {
                //decode it
                $decode_history[$his->krs_detail_id] = json_decode($his->history_nilai);
            }
        }
        foreach ($_POST['nilai_angka'] as $id_krs_detail => $nilai) {
            $exp_nilai = explode("#", $_POST['nilai_huruf'][$id_krs_detail]);
            $array_history = array(
                'nilai_angka' => $nilai,
                'nilai_huruf' => $exp_nilai[0],
                'nilai_indeks' => $exp_nilai[1],
                'pengubah' => getProfilUser($_SESSION['id_user'])->full_name,
                'user_pengubah' => getProfilUser($_SESSION['id_user'])->username,
                'date_updated' => date('Y-m-d H:i:s')
            );
            if (isset($decode_history[$id_krs_detail])) {
                $convert_obj = $db2->converObjToArray($decode_history[$id_krs_detail]);
                $data_history = array_merge($convert_obj, array($array_history));
                //exit();
            } else {
                $data_history = array($array_history);
            }
            $data_update_nilai[] = array(
                'nilai_angka' => $nilai,
                'nilai_huruf' => $exp_nilai[0],
                'nilai_indeks' => $exp_nilai[1],
                'pengubah' => getProfilUser($_SESSION['id_user'])->full_name,
                'user_pengubah' => getProfilUser($_SESSION['id_user'])->username,
                'date_updated' => date('Y-m-d H:i:s'),
                'history_nilai' => json_encode($data_history)
            );
            $id_krs[] = $id_krs_detail;
            $get_nim_semestser = $db2->fetchCustomSingle("select nim,id_semester from tb_data_kelas_krs inner join tb_data_kelas_krs_detail using(krs_id) where krs_detail_id='" . $id_krs_detail . "'");
            // update_akm($get_nim_semestser->nim);
            $array_history = array();
            $convert_obj = array();
        }
        dump($data_update_nilai);
        //$db2->updateMulti('krs_detail',$data_update_nilai,'krs_detail_id',$id_krs);
        action_response($db2->getErrorMessage());
        break;
    case 'import':
        if (!preg_match("/.(xls|xlsx)$/i", $_FILES["semester"]["name"])) {

            echo "pastikan file yang anda pilih xls|xlsx";
            exit();

        } else {
            move_uploaded_file($_FILES["semester"]["tmp_name"], "../../../upload/upload_excel/" . $_FILES['semester']['name']);
            $semester = array("semester" => $_FILES["semester"]["name"]);
        }

        $error_count = 0;
        $error = array();
        $sukses = 0;
        $values = "";
        $data_error = array();
        $data_insert_krs_detail_nilai = array();

        $Reader = new SpreadsheetReader("../../../upload/upload_excel/" . $_FILES['semester']['name']);

        foreach ($Reader as $key => $val) {


            if ($key > 0) {

                if ($val[0] != '') {

                    //first check kode_mk
                    $kode_mk = trimmer($val[2]);
                    $kode_jur = trimmer($val[9]);
                    $nama_kelas = trimmer($val[5]);
                    $sem_id = trimmer($val[4]);
                    $nim = trimmer($val[0]);

                    $check_kode_mk = $db2->fetchCustomSingle("select kode_mk,id_matkul,a_wajib from tb_data_matakuliah inner join tb_data_kurikulum using(kur_id)
          where kode_mk='$kode_mk' and tb_data_kurikulum.kode_jur='$kode_jur'");
                    if ($check_kode_mk) {
                        //check kelas if not exist
                        $check_kelas_exist = $db2->fetchCustomSingle('select kelas_id from tb_data_kelas where sem_id="' . $sem_id . '" and kls_nama="' . $nama_kelas . '" and id_matkul="' . $check_kode_mk->id_matkul . '"');
                        if ($check_kelas_exist == true) {
                            //check if krs exist
                            $check_krs_exist = $db2->fetchCustomSingle('select krs_id from tb_data_kelas_krs where nim="' . $nim . '" and id_semester="' . $sem_id . '"');
                            if ($check_krs_exist) {
                                $krs_id = $check_krs_exist->krs_id;
                            } else {
                                $data_krs_id = array(
                                    'nim' => $nim,
                                    'id_semester' => $sem_id,
                                    'jatah_sks' => 0,
                                    'disetujui' => 1,
                                    'insert_method' => 2,
                                    'date_created' => tanggalWaktu()
                                );
                                $insert_krs_id = $db2->insert('tb_data_kelas_krs', $data_krs_id);
                                $krs_id = $db2->getLastInsertId();
                            }
                            $data_krs_id[] = $krs_id;
                            //check if nilai is exist
                            $check_nilai = $db2->fetchCustomSingle('select krs_detail_id from tb_data_kelas_krs_detail where krs_id="' . $krs_id . '" and matkul_id="' . $check_kode_mk->id_matkul . '"');
                            if ($check_nilai) {
                                $error_count++;
                                $data_error[] = array(
                                    $val[0],
                                    $val[1],
                                    $val[2],
                                    $val[3],
                                    $val[4],
                                    $val[5],
                                    $val[6],
                                    $val[7],
                                    $val[8],
                                    $val[9],
                                    "Nilai ini Sudah ada di Sistem"
                                );
                            } else {
                                //$data_update_krs[$krs_id] = $krs_id;
                                $sukses++;
                                $array_history[] = array(
                                    'nilai_angka' => $val[8],
                                    'nilai_huruf' => $val[6],
                                    'nilai_indeks' => $val[7],
                                    'pengubah' => getProfilUser($_SESSION['id_user'])->full_name,
                                    'user_pengubah' => getProfilUser($_SESSION['id_user'])->username,
                                    'date_updated' => tanggalWaktu(),
                                );
                                $data_insert_krs_detail_nilai[] = array(
                                    'krs_id' => $krs_id,
                                    'kelas_id' => $check_kelas_exist->kelas_id,
                                    'matkul_id' => $check_kode_mk->id_matkul,
                                    'batal' => 0,
                                    'disetujui' => 1,
                                    'is_wajib' => $check_kode_mk->a_wajib,
                                    'user_pembuat' => getProfilUser($_SESSION['id_user'])->username,
                                    'pembuat' => getProfilUser($_SESSION['id_user'])->full_name,
                                    'history_nilai' => addslashes(json_encode($array_history)),
                                    'date_created' => tanggalWaktu(),
                                    'pengubah' => getProfilUser($_SESSION['id_user'])->full_name,
                                    'user_pengubah' => getProfilUser($_SESSION['id_user'])->username,
                                    'date_updated' => tanggalWaktu(),
                                    'nilai_angka' => $val[8],
                                    'nilai_huruf' => $val[6],
                                    'nilai_indeks' => $val[7],
                                    'insert_method' => 2
                                );
                                $array_history = array();
                            }
                        } else {
                            $error_count++;
                            $data_error[] = array(
                                $val[0],
                                $val[1],
                                $val[2],
                                $val[3],
                                $val[4],
                                $val[5],
                                $val[6],
                                $val[7],
                                $val[8],
                                $val[9],
                                "Kelas Tidak ditemukan"
                            );
                        }

                    } else {
                        $error_count++;
                        $data_error[] = array(
                            $val[0],
                            $val[1],
                            $val[2],
                            $val[3],
                            $val[4],
                            $val[5],
                            $val[6],
                            $val[7],
                            $val[8],
                            $val[9],
                            "Kode Mk " . $kode_mk . " Tidak ditemukan"
                        );
                    }
                }
            }

        }

        if (!empty($data_error)) {
            include "download_error_import.php";
        }


        if (!empty($data_insert_krs_detail_nilai)) {
            $db2->insertMulti('tb_data_kelas_krs_detail', $data_insert_krs_detail_nilai);
            if ($db2->getErrorMessage() == "") {
                $implode_krs_id = implode(",", $data_krs_id);
                $db2->query("update tb_data_kelas_krs set disetujui='1' where krs_id in($implode_krs_id)");
                $db2->query("update tb_data_kelas_krs_detail set disetujui='1' where krs_id in($implode_krs_id)");
            }
            echo $db2->getErrorMessage();
        }
        unlink("../../../upload/upload_excel/" . $_FILES['semester']['name']);
        $msg = '';
        $time_end = microtime(true);
        $execution_time = ($time_end - $time_start);

        if (($sukses > 0) || ($error_count > 0)) {
            $msg = "<div class=\"alert alert-warning alert-dismissible\" role=\"alert\" style=\"margin-bottom: 0;\" >
    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>
    <font color=\"#3c763d\">" . $sukses . " data Nilai berhasil di import</font><br />
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
                $msg .= "</div><br><a href='" . base_url() . "upload/sample/mahasiswa/error_nilai.xlsx' class='btn btn-sm btn-primary' style='text-decoration:none;'>Download Data Error</a><br>";
            }

            $msg .= "<p>Total Waktu Import : " . waktu_import($execution_time);
            $msg .= "</div>";

        }
        echo $msg;
        break;
    default:
        # code...
        break;
}

?>