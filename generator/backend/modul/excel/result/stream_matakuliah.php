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

$url = "https://salam.uinsgd.ac.id/generator/pddikti/get_matakuliah.php";
//param here
$param = array();


if ($_POST['sem_id']!='all') {
  //param here
  $param = array(
    "kode_dikti" => $_POST["kode_dikti"],
		"sem_id" => $_POST["sem_id"]
  );

  $and_kode_dikti = "and kode_dikti=?";
	$and_sem_id = "and sem_id=?";

}


if (isset($_GET["ask"])=="jumlah") {
    $url_jumlah = array(
        "ask" => "jumlah"
    );
    $jumlah_matakuliah = post_data($url,$param,$url_jumlah);
    $results = json_decode($jumlah_matakuliah);
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

                    $check = $db->checkExist("mat_kurikulum",array("nim" => $key->nim));
                 if ($check==true) {
                        $update_matakuliah[] = array(
                            "jns_mk" => $key->jns_mk,
							"kode_mk" => $key->kode_mk,
							"semester" => $key->semester,
							"nama_mk" => $key->nama_mk,
							"sks_tm" => $key->sks_tm,
							"sks_prak" => $key->sks_prak,
							"sks_prak_lap" => $key->sks_prak_lap,
							"sks_sim" => $key->sks_sim,
							"tgl_mulai_efektif" => $key->tgl_mulai_efektif,
							"tgl_akhir_efektif" => $key->tgl_akhir_efektif,
							"wajib" => $key->wajib,
							"metode_pelaksanaan_kuliah" => $key->metode_pelaksanaan_kuliah,
							"kode_jurusan" => $key->kode_jurusan,
							"tahun" => $key->tahun
                        );
                        $nipd[] = $key->nim;
                  } else {
                       $insert_matakuliah[] = array(
                            "jns_mk" => $key->jns_mk,
							"kode_mk" => $key->kode_mk,
							"semester" => $key->semester,
							"nama_mk" => $key->nama_mk,
							"sks_tm" => $key->sks_tm,
							"sks_prak" => $key->sks_prak,
							"sks_prak_lap" => $key->sks_prak_lap,
							"sks_sim" => $key->sks_sim,
							"tgl_mulai_efektif" => $key->tgl_mulai_efektif,
							"tgl_akhir_efektif" => $key->tgl_akhir_efektif,
							"wajib" => $key->wajib,
							"metode_pelaksanaan_kuliah" => $key->metode_pelaksanaan_kuliah,
							"kode_jurusan" => $key->kode_jurusan,
							"tahun" => $key->tahun
                        );
                  }      
              }

            if (!empty($update_matakuliah)) {
                updateMulti("matakuliah",$update_matakuliah,"nim",$nipd);
            }

            if (!empty($insert_matakuliah)) {
              $db->insertMulti("mat_kurikulum",$insert_matakuliah);
            }

    if ($_POST["last"]=="yes") {
        //echo "<pre>";

    $msg =  "<div class=\"alert alert-warning \" role=\"alert\">
            <font color=\"#3c763d\">".$_POST["total_data"]." Data matakuliah berhasil di Unduh</font><br />";
            $msg .= "</div>
            </div>";

            $jumlah["last_notif"] = $msg;
    }



    array_push($json_response, $jumlah);

    echo json_encode($json_response);
    exit();
}
?>
