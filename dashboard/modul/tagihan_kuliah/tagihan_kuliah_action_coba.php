<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  case 'batal_va': 
    $nim = $_SESSION['username']; 
  //$nim = '1210705091';
    $va = $db->fetch_custom_single("select id_bank,no_kwitansi,no_va,billing_id,nominal from keu_keranjang_va where nim_mhs=? order by id_keranjang desc limit 1",array('nim' => $nim));
    $mhs = $db->fetch_single_row('mahasiswa','nim
    ',$nim); 
    if ($va->id_bank=='01') {
      deleteBriva($nim); 
    } elseif ($va->id_bank=='04') {
        include "bni.php";
        $trx_id = $va->no_kwitansi;  

        $amount = $va->nominal;
        $va = $prefix . $client_id . substr($nim,-8);

        $data_asli_update = array(
            'client_id' => $client_id,
            'trx_amount' => $amount,
            'customer_name' => $mhs->nama,
            'customer_email' => $mhs->email,
            'customer_phone' => $mhs->no_hp,
            'virtual_account' => $va,
            'trx_id' => $trx_id,
            'datetime_expired' => date('c', time() - 24 * 3600),
            'billing_type' => 'i',
            'type' => 'updatebilling'
        );

        $hashed_string = BniEnc::encrypt(
          $data_asli_update,
          $client_id,
          $secret_key
        );



        $data = array(
          'client_id' => $client_id,
          'prefix' => $prefix,
          'data' => $hashed_string,
        );

        $response = get_content($url, json_encode($data));
        $response_json = json_decode($response, true);

/*        echo "<BR><H1>RESPONSE ARRAY</H1>";
        if ($response_json['status'] !== '000') {
          // handling jika gagal
          var_dump($response_json);
        }*/
      
    }

        $exp_date = new DateTime(date('Y-m-d'));
        $exp_date->modify('-1 day');
        $exp = $exp_date->format('Y-m-d 23:59:00');
        $check_va = $db->query("update keu_keranjang_va set exp_date='$exp',is_active='N' where nim_mhs='".$nim."'");
        action_response($db->getErrorMessage());
    break;
  case "in":
  //id tagihan mahasiswa selected
  //$semester = $db->fetch_single_row('periode_pembayaran', "is_active", "Y");
  $qs = $db2->query("select periode_bayar,tgl_mulai,tgl_selesai from periode_pembayaran where now() between concat(tgl_mulai,' 00:00:00') and concat(tgl_selesai,' 23:59:59')  ");
  foreach ($qs as $periode) {
      //$semester = $periode;
      $tgl_selesai_spp = $periode->tgl_selesai." 23:59:59";  
  }

 // dump($tgl_selesai_spp); 
 // $tgl_selesai_spp = $semester->tgl_selesai." 23:59:59";  
  $id_tagihan = array();

  $nim = $_SESSION['username']; 
  //$nim = '1210705092'; 

  foreach ($_POST['nominal_tagihan'] as $id => $nominal) {
        if ($nominal!="") {
      $id_tagihan[] = $id;
    }
    //$id_tagihan[] = $id;
  }
  $implode_id = implode(",", $id_tagihan); 
  //sum item dipilih
  $tagihan = $db->query("SELECT ktm.id,kt.nominal_tagihan,nama_tagihan,potongan,periode,tanggal_akhir
from keu_tagihan_mahasiswa ktm
INNER JOIN keu_tagihan kt on ktm.id_tagihan_prodi=kt.id
inner join keu_jenis_tagihan kjt on kt.kode_tagihan=kjt.kode_tagihan
where ktm.id in($implode_id) order by tanggal_akhir asc");

  //urutan 
  /*$urutan = $db->fetch_custom_single("select count(*) as jml from keu_keranjang_va where date(created)=CURDATE()");
  $no_kwitansi = date('YmdHis').($urutan->jml+1);*/
  $no_kwitansi = strtotime(date('Y-m-d')).rand();
/*  $exp_date = new DateTime(date('Y-m-d'));
  $exp_date->modify('+1 day');
  $exp = $exp_date->format('2022-01-14 23:59:59');*/
  //exp 
  //$exp = '2022-01-14 23:59:59'; 
  $Date      = date("Y-m-d H:i:s");   
  //$exp= $tgl_selesai_spp;
  //date_add($exp,date_interval_create_from_date_string("7 days")); 
  //$exp = $date;
  $exp = "";
  $total_tagihan = 0;
  foreach ($tagihan as $tagihan_item) {
    $total_tagihan += $tagihan_item->nominal_tagihan-$tagihan_item->potongan;
    $insert_va_detail[] = array(
      'id_keu_tagihan_mhs' => $tagihan_item->id,
      'no_kwitansi' => $no_kwitansi,
      'nominal' => $tagihan_item->nominal_tagihan-$tagihan_item->potongan,
      'tgl_dibuat' => date('Y-m-d H:i:s')
    );
    $nama_tagihan[] = $tagihan_item->nama_tagihan;
    $tgl_selesai_spp = $tagihan_item->tanggal_akhir;

  //  dump($tagihan_item);
  }



  //va
  $bank = $db->fetch_single_row('keu_bank','kode_bank
    ',$_POST['bank']);

  // print_r($bank);
  // die();

  //get nama
  $mhs = $db->fetch_single_row('mahasiswa','nim',$nim);
  $is_success_api = 0;
 /* if ($_POST['bank']=='02' || $_POST['bank']=='04') {
    if ($_POST['bank']=='02') {
      $product_id = '1287';
    }else{
       $product_id = '1827';
    }
    $array_post = array(
      'nim' => $_SESSION['username'],
      'product_id' => $product_id,
      'amount' => $total_tagihan,
      'thn_akademik' => substr($tagihan_item->periode, 0,4),
      'jenis_semester' => substr($tagihan_item->periode, -1)
    );

    //dump($array_post);


    $post_api = post_data($bank->uri_api,$array_post,$array_get = array(),'json');
    // var_dump($array_post);
    // var_dump($post_api);

    $decode_api = json_decode($post_api);
    if ($decode_api->status=='success') {
      $is_success_api = 1;
      $va = $decode_api->va_acc_no;
      $billing_id = $decode_api->billing_id;
    } else {
      $is_success_api = 0;
      action_response('Terjadi Kesalahan, '.$decode_api->message);
    }
  } elseif ($_POST['bank']=='03') {
    $array_post = array(
      'nim' => $_SESSION['username'],
      'nama' => $mhs->nama,
      'tagihan' => $total_tagihan,
      'expired' => $exp,
      'description' => ''
    );
    $post_api = post_data($bank->uri_api,$array_post,$array_get = array(),'json');
    $decode_api = json_decode($post_api);
    if ($decode_api->status=='success') {
      $is_success_api = 1;
      $va = $decode_api->virtual_account;
    } else {
      $is_success_api = 0;
      action_response('Terjadi Kesalahan, '.$decode_api->message);
    }
  } else*/
  $is_success_api   = false;
  if($_POST['bank']=='01') { 
    $sem_aktif= getSemesterAktifPembayaran(); 
    $sem_aktif = substr($sem_aktif, (strlen($sem_aktif)-3),strlen($sem_aktif)); 
     $va = str_replace(".", "", ($nim.$sem_aktif));  
     deleteBriva($va);  
     $qm = $db->query("select nama,nim from mahasiswa where mhs_id='".$_POST['mhs_id']."' ");
     foreach ($qm as $km) { 
       $nama = $km->nama;
     }
    
      $data['no_briva'] = $va;
      $data['nama']     = $nama;
      $data['exp_date'] = "$tgl_selesai_spp"; 
      //$data['exp_date'] = "$exp"; 
      //$data['nominal']  = str_replace(".", "", $_POST['total_tagihan']);
      $data['nominal']  = $total_tagihan;
      //$data['ket']      = implode(",", $_POST['nama_tagihan']);
      $data['ket']      = substr(implode(",", $nama_tagihan),0,40);
      $res              = createBriva($data);
      $status           = $res['responseCode']; 
    //  print_r($res);
      if ($status=='00') {
        $is_success_api   = 1;
      }else{
        action_response($res['errDesc']);
      }
      $va = custCode.$va; 
      
  } elseif ($_POST['bank']=='03') {
      $va = "88262".$_SESSION['username'];
      $is_success_api = 1;
     // $tgl_selesai_spp  = $exp;
  } elseif ($_POST['bank']=='04') {
      include "bni.php";
      $trx_id = microtime(true) * 10000 + mt_rand(0, 9999);  
      $va = $prefix . $client_id . substr($mhs->nim,-8);
      $data_asli = array(
          'client_id' => $client_id,
          'trx_amount' => $total_tagihan,
          'customer_name' => $mhs->nama,
          'customer_email' => $mhs->email,
          'customer_phone' => $mhs->no_hp,
          'virtual_account' => $va,
          'trx_id' => $no_kwitansi, // fill with Billing ID
          'datetime_expired' => $tgl_selesai_spp, // billing will expire in 2 hours
          //'datetime_expired' => date('c', time() + 3 * 60), // billing will expire in 3 minutes
          'description' => substr(implode(",", $nama_tagihan),0,100),
          'billing_type' => 'c',
          'type' => 'createBilling'
      );


      $data_callback = array(  
          'virtual_account' => $prefix . $client_id . substr($mhs->nim, -8),  
          'customer_name' => $mhs->nama,  
          'trx_id' => $no_kwitansi,  
          "trx_amount" => $no_kwitansi,  
          "payment_amount" => $no_kwitansi,  
          "cumulative_payment_amount" => $no_kwitansi,  
          "payment_ntb" => "233171",  
          "datetime_payment" => date("Y-m-d H:i:s"), // Current date and time  
          "datetime_payment_iso8601" => (new DateTime())->format(DateTime::ISO8601) // Current date and time in ISO 8601 format  
      );  

      $hashed_string_callback = BniEnc::encrypt(
        $data_callback,
        $client_id,
        $secret_key
      );

    //  dump($hashed_string_callback);


      $hashed_string = BniEnc::encrypt(
        $data_asli,
        $client_id,
        $secret_key
      );

      $data = array(
        'client_id' => $client_id,
        'prefix' => $prefix,
        'data' => $hashed_string,
      );

      //https://portalbeta.bni-ecollection.com/partner/simulator/payment-simulator/index

  
      $response = get_content($url, json_encode($data));
      $response_json = json_decode($response, true);


      $is_success_api = 1;
     // $tgl_selesai_spp  = $exp;
  }  
  //var_dump($is_success_api);
  
  if ($is_success_api) {
       //data insert keu_keranjang_va
        $array_insert_keranjang_va = array(
          'nominal'     => $total_tagihan,
          'no_kwitansi' => $no_kwitansi,
          'no_va'       => $va,
          'exp_date'    => $tgl_selesai_spp,
          'nim_mhs'     => $nim,
          'created'     => date('Y-m-d H:i:s'),
          'id_bank'     => $_POST['bank']
        );


/*        dump($_POST);

dump($array_insert_keranjang_va);
exit();*/

       // print_r($array_insert_keranjang_va);

        $db2->begin_transaction();
        $insert_keranjang = $db2->insert('keu_keranjang_va',$array_insert_keranjang_va);
        //action_response($db2->getErrorMessage());
        if ($insert_keranjang) {
          $insert_keranjang_detail = $db2->insertMulti('keu_keranjang_va_detail',$insert_va_detail);
          if ($insert_keranjang_detail==false) {
            $db2->rollback();
            //call api va delete here
            //$post_api = delete_api_data($bank->delete_api,array('va' => $va),$array_get = array(),'json');
            action_response($db2->getErrorMessage());

          } 
        }/* else {

          if (in_array($_POST['bank'], $bjbs_array_bank)) {
              //call api va delete here
              $post_api = post_data($bank->uri_api,array('billing_id' => $billing_id),$array_get = array(),'json');
              action_response($db2->getErrorMessage());
          } else {
              //call api va delete here
              $post_api = post_data($bank->uri_api,array('va' => $va),$array_get = array(),'json');
              action_response($db2->getErrorMessage());
          }
        }*/
        $db2->commit();
  }





        //posting data to 
        //post_data()




    action_response($db2->getErrorMessage());
    break;
  case "delete":
    
    
    
    $db->delete("keu_tagihan_mahasiswa","id",$_GET["id"]);
    action_response($db->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("keu_tagihan_mahasiswa","id",$id);
         }
    }
    action_response($db->getErrorMessage());
    break;
  case "up":
    
   $data = array(
      "nim" => $_POST["nim"],
   );
   
   
   

    
    
    $up = $db->update("keu_tagihan_mahasiswa",$data,"id",$_POST["id"]);
    
    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>