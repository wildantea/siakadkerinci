<?php
include "../../inc/config.php";
function post_data($url,$post=array(),$get=array()) 
{ 
   $ch = curl_init(); 
   $postvars = "";
   $get_vars = "";
   if (!empty($post)) {
        $array_post_var = array();
        foreach($post as $key=>$value) {
            $array_post_var[] = $key . "=" . $value;
        }
        $postvars = implode("&", $array_post_var);
   }
   if (!empty($get)) {
        $array_get_var = array();
        foreach($get as $key=>$value) {
            $array_get_var[] = $key . "=" . $value;
        }
        $get_vars = implode("&", $array_get_var);
        curl_setopt ($ch, CURLOPT_URL, $url."?".$get_vars); 
   } else {
        curl_setopt ($ch, CURLOPT_URL, $url); 
   }

   curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
   if (!empty($post)) {
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars); 
   }
   curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
   curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 5);
   $result = curl_exec($ch); 
   //dump($result);


   //$http_respond = trim( strip_tags( $result ) );
   $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );

   curl_close($ch); 
   return $result;
}
/**
 * [trimmer trim for import excel
 *
 * @param  [type] $excel column value
 * @return [type]  trimmed value
 */
function trimmer($value)
{
    $result = preg_replace("/[^[:print:]]/", "", filter_var($value, FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH));
    return addslashes(trim($result));
}

$json_response = array();
$msg = "";
$values = "";

$url = "https://salam.uinsgd.ac.id/generator/pddikti/get_mahasiswa.php";
//param here
$param = array();


if ($_POST['mulai_smt']!='all') {
  //param here
  $param = array(
    "kode_dikti" => $_POST["kode_dikti"],
		"mulai_smt" => $_POST["mulai_smt"]
  );

  $and_kode_dikti = "and kode_dikti=?";
	$and_mulai_smt = "and mulai_smt=?";

}


if (isset($_GET["ask"])=="jumlah") {
    $url_jumlah = array(
        "ask" => "jumlah"
    );
    $jumlah_mahasiswa = post_data($url,$param,$url_jumlah);
    $results = json_decode($jumlah_mahasiswa);
    if ($results->jumlah>0) {
       $json_response["jumlah"] = $results->jumlah;
    } else {
      $json_response["jumlah"] = 0;
    }
    echo json_encode($json_response);
} else {
    if ($_POST["total_data"]<1) {
        $msg =  "<div class=\"alert alert-warning \" role=\"alert\">
            <font color=\"#3c763d\">Tidak ada data berhasil di Upload</font><br />
            </div>";

        $jumlah["last_notif"] = $msg;
        array_push($json_response, $jumlah);

        echo json_encode($json_response);
        exit();
    }

    $offset = $_POST["offset"];

    $jumlah["offset"] = $offset;

    $par_get = array(
        "offset" => $offset
    );


    $data_rec = array();
            $data = post_data($url,$param,$par_get);
            $datas = json_decode($data);

            foreach ($datas as $key) {

                    $check = $db->checkExist("mhs",array("nim" => $key->nim));
                 if ($check==true) {
                        $update_mahasiswa[] = array(
                            "nm_pd" => $key->nm_pd,
							"jk" => $key->jk,
							"nisn" => $key->nisn,
							"npwp" => $key->npwp,
							"nik" => $key->nik,
							"tmpt_lahir" => $key->tmpt_lahir,
							"tgl_lahir" => $key->tgl_lahir,
							"id_agama" => $key->id_agama,
							"jln" => $key->jln,
							"rt" => $key->rt,
							"rw" => $key->rw,
							"nm_dsn" => $key->nm_dsn,
							"ds_kel" => $key->ds_kel,
							"id_wil" => $key->id_wil,
							"kode_pos" => $key->kode_pos,
							"id_jns_tinggal" => $key->id_jns_tinggal,
							"id_alat_transport" => $key->id_alat_transport,
							"no_tel_rmh" => $key->no_tel_rmh,
							"no_hp" => $key->no_hp,
							"email" => $key->email,
							"a_terima_kps" => $key->a_terima_kps,
							"no_kps" => $key->no_kps,
							"stat_pd" => $key->stat_pd,
							"nik_ayah" => $key->nik_ayah,
							"nm_ayah" => $key->nm_ayah,
							"tgl_lahir_ayah" => $key->tgl_lahir_ayah,
							"id_jenjang_pendidikan_ayah" => $key->id_jenjang_pendidikan_ayah,
							"id_pekerjaan_ayah" => $key->id_pekerjaan_ayah,
							"id_penghasilan_ayah" => $key->id_penghasilan_ayah,
							"nik_ibu" => $key->nik_ibu_kandung,
							"nm_ibu_kandung" => $key->nm_ibu_kandung,
							"tgl_lahir_ibu" => $key->tgl_lahir_ibu,
							"id_jenjang_pendidikan_ibu" => $key->id_jenjang_pendidikan_ibu,
							"id_penghasilan_ibu" => $key->id_penghasilan_ibu,
							"id_pekerjaan_ibu" => $key->id_pekerjaan_ibu,
							"nm_wali" => $key->nm_wali,
							"tgl_lahir_wali" => $key->tgl_lahir_wali,
							"id_jenjang_pendidikan_wali" => $key->id_jenjang_pendidikan_wali,
							"id_pekerjaan_wali" => $key->id_pekerjaan_wali,
							"id_penghasilan_wali" => $key->id_penghasilan_wali,
							"kewarganegaraan" => $key->kewarganegaraan,
							"kode_jurusan" => $key->kode_jurusan,
							"id_jns_daftar" => $key->id_jns_daftar,
							"nipd" => $key->nipd,
							"tgl_masuk_sp" => $key->tgl_masuk_sp,
							"mulai_smt" => $key->mulai_smt,
							"id_pembiayaan" => $key->id_pembiayaan,
							"id_jalur_masuk" => $key->id_jalur_masuk
                        );
                        $nipd[] = $key->nim;
                  } else {
                       $insert_mahasiswa[] = array(
                            "nm_pd" => $key->nm_pd,
							"jk" => $key->jk,
							"nisn" => $key->nisn,
							"npwp" => $key->npwp,
							"nik" => $key->nik,
							"tmpt_lahir" => $key->tmpt_lahir,
							"tgl_lahir" => $key->tgl_lahir,
							"id_agama" => $key->id_agama,
							"jln" => $key->jln,
							"rt" => $key->rt,
							"rw" => $key->rw,
							"nm_dsn" => $key->nm_dsn,
							"ds_kel" => $key->ds_kel,
							"id_wil" => $key->id_wil,
							"kode_pos" => $key->kode_pos,
							"id_jns_tinggal" => $key->id_jns_tinggal,
							"id_alat_transport" => $key->id_alat_transport,
							"no_tel_rmh" => $key->no_tel_rmh,
							"no_hp" => $key->no_hp,
							"email" => $key->email,
							"a_terima_kps" => $key->a_terima_kps,
							"no_kps" => $key->no_kps,
							"stat_pd" => $key->stat_pd,
							"nik_ayah" => $key->nik_ayah,
							"nm_ayah" => $key->nm_ayah,
							"tgl_lahir_ayah" => $key->tgl_lahir_ayah,
							"id_jenjang_pendidikan_ayah" => $key->id_jenjang_pendidikan_ayah,
							"id_pekerjaan_ayah" => $key->id_pekerjaan_ayah,
							"id_penghasilan_ayah" => $key->id_penghasilan_ayah,
							"nik_ibu" => $key->nik_ibu_kandung,
							"nm_ibu_kandung" => $key->nm_ibu_kandung,
							"tgl_lahir_ibu" => $key->tgl_lahir_ibu,
							"id_jenjang_pendidikan_ibu" => $key->id_jenjang_pendidikan_ibu,
							"id_penghasilan_ibu" => $key->id_penghasilan_ibu,
							"id_pekerjaan_ibu" => $key->id_pekerjaan_ibu,
							"nm_wali" => $key->nm_wali,
							"tgl_lahir_wali" => $key->tgl_lahir_wali,
							"id_jenjang_pendidikan_wali" => $key->id_jenjang_pendidikan_wali,
							"id_pekerjaan_wali" => $key->id_pekerjaan_wali,
							"id_penghasilan_wali" => $key->id_penghasilan_wali,
							"kewarganegaraan" => $key->kewarganegaraan,
							"kode_jurusan" => $key->kode_jurusan,
							"id_jns_daftar" => $key->id_jns_daftar,
							"nipd" => $key->nipd,
							"tgl_masuk_sp" => $key->tgl_masuk_sp,
							"mulai_smt" => $key->mulai_smt,
							"id_pembiayaan" => $key->id_pembiayaan,
							"id_jalur_masuk" => $key->id_jalur_masuk
                        );
                  }      
              }

            if (!empty($update_mahasiswa)) {
                updateMulti("mahasiswa",$update_mahasiswa,"nim",$nipd);
            }

            if (!empty($insert_mahasiswa)) {
              $db->insertMulti("mhs",$insert_mahasiswa);
            }

    if ($_POST["last"]=="yes") {
        //echo "<pre>";

    $msg =  "<div class=\"alert alert-warning \" role=\"alert\">
            <font color=\"#3c763d\">".$_POST["total_data"]." Data mahasiswa berhasil di Unduh</font><br />";
            $msg .= "</div>
            </div>";

            $jumlah["last_notif"] = $msg;
    }



    array_push($json_response, $jumlah);

    echo json_encode($json_response);
    exit();
}
?>
