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



    function get_data($nim)
    {
        $data = array(
            "url" => "https://api.uinsgd.ac.id/pmb/sarjana/Daftar/Api/View_daftar_kelulusan", //Not Null
            "method" => "POST", // GET, POST, PUT, PATCH, DELETE
            "header" => array(
                "Content-Type: application/json"
            ),
            "request" => json_encode(array(
                "username" => 'admin@api',
                "password" => '!!!1Sampai9!!!',
                "action"=> "where",
                "select"=> null,
                "where" => array(
                   "nomor_peserta" => $nim
                ),
                "or_where"=> null,
                "order"=> null,
                "limit"=>  1,
                "pagging"=> null
            )),
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, $data['url']);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $data['method']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data['request']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $data['header']);
        $respond = curl_exec($ch); 
        //print_r($data);
        curl_close($ch);
        $respond = json_decode($respond);
        return $respond;
    }

$json_response = array();
$msg = "";
$values = "";

$url = "localhost/generator/pddikti/download/update_mhs.php";
//param here
$param = array();


if ($_POST['mulai_smt']!='all') {
  //param here
  $param = array(
    "mulai_smt" => $_POST["mulai_smt"]
  );

  $and_mulai_smt = "and mulai_smt=?";

}


if (isset($_GET["ask"])=="jumlah") {
    $url_jumlah = array(
        "ask" => "jumlah"
    );
    $jumlah_download = post_data($url,$param,$url_jumlah);
    $results = json_decode($jumlah_download);
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

              $res = get_data($key->nim);
              if (isset($res->data)) {
                  $update_download[] = array(
                      "date_updated_mhs" => date('Y-m-d H:i:s'),
                      "jalur_lokal_id" => $res->data[0]->id_jlr_msk
                  );
                  $mhs_id[] = $key->mhs_id;
                }    
              }

             // dump($update_download);

            if (!empty($update_download)) {
                $db2->updateMulti("mahasiswa",$update_download,"mhs_id",$mhs_id);
                echo $db2->getErrorMessage();
            }

/*            if (!empty($insert_download)) {
              $db->insertMulti("mhs",$insert_download);
            }*/

    if ($_POST["last"]=="yes") {
        //echo "<pre>";

    $msg =  "<div class=\"alert alert-warning \" role=\"alert\">
            <font color=\"#3c763d\">".$_POST["total_data"]." Data download berhasil di Unduh</font><br />";
            $msg .= "</div>
            </div>";

            $jumlah["last_notif"] = $msg;
    }



    array_push($json_response, $jumlah);

    echo json_encode($json_response);
    exit();
}
?>
