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

$url = "https://salam.uinsgd.ac.id/generator/pddikti/get_nilai_perkuliahan.php";
//param here
$param = array();


if ($_POST['kode_dikti']!='all') {
  //param here
  $param = array(
    "sem_id" => $_POST["sem_id"],
		"kode_dikti" => $_POST["kode_dikti"]
  );

  $and_sem_id = "and sem_id=?";
	$and_kode_dikti = "and kode_dikti=?";

}


if (isset($_GET["ask"])=="jumlah") {
    $url_jumlah = array(
        "ask" => "jumlah"
    );
    $jumlah_nilai_perkuliahan = post_data($url,$param,$url_jumlah);
    $results = json_decode($jumlah_nilai_perkuliahan);
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

                    $check = $db->checkExist("nilai",array("nim" => $key->nim));
                 if ($check==true) {
                        $update_nilai_perkuliahan[] = array(
                            "nim" => $key->nim,
							"nilai_indek" => $key->nilai_indek,
							"nilai_huruf" => $key->nilai_huruf,
							"nilai_angka" => $key->nilai_angka,
							"nama" => $key->nama,
							"kode_mk" => $key->kode_mk,
							"nama_mk" => $key->nama_mk,
							"semester" => $key->semester,
							"nama_kelas" => $key->nama_kelas,
							"kode_jurusan" => $key->kode_jurusan
                        );
                        $nipd[] = $key->nim;
                  } else {
                       $insert_nilai_perkuliahan[] = array(
                            "nim" => $key->nim,
							"nilai_indek" => $key->nilai_indek,
							"nilai_huruf" => $key->nilai_huruf,
							"nilai_angka" => $key->nilai_angka,
							"nama" => $key->nama,
							"kode_mk" => $key->kode_mk,
							"nama_mk" => $key->nama_mk,
							"semester" => $key->semester,
							"nama_kelas" => $key->nama_kelas,
							"kode_jurusan" => $key->kode_jurusan
                        );
                  }      
              }

            if (!empty($update_nilai_perkuliahan)) {
                updateMulti("nilai_perkuliahan",$update_nilai_perkuliahan,"nim",$nipd);
            }

            if (!empty($insert_nilai_perkuliahan)) {
              $db->insertMulti("nilai",$insert_nilai_perkuliahan);
            }

    if ($_POST["last"]=="yes") {
        //echo "<pre>";

    $msg =  "<div class=\"alert alert-warning \" role=\"alert\">
            <font color=\"#3c763d\">".$_POST["total_data"]." Data nilai_perkuliahan berhasil di Unduh</font><br />";
            $msg .= "</div>
            </div>";

            $jumlah["last_notif"] = $msg;
    }



    array_push($json_response, $jumlah);

    echo json_encode($json_response);
    exit();
}
?>
