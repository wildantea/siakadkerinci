<?php
$start_time = microtime(true);
function convertTime($time) {
  $year = substr($time,0,4);
  $month = substr($time,4,2);
  $day = substr($time,6,2);
  $hour = substr($time,8,2);
  $minute = substr($time,10,2);
  $second = substr($time,-2);
  $date_time = $year . "-" .$month.'-'.$day.' '.$hour.':'.$minute.':'.$second;
  if (validateDateTime($date_time)) {
    $date = $date_time;
  } else {
    $date = date('Y-m-d H:i:s');
  }
  return $date;
}
function convertTimeDate($time) {
  $year = substr($time,0,4);
  $month = substr($time,4,2);
  $day = substr($time,6,2);
  $hour = substr($time,8,2);
  $minute = substr($time,10,2);
  $second = substr($time,-2);
  $date_time = $year . "-" .$month.'-'.$day.' '.$hour.':'.$minute.':'.$second;
  if (validateDateTime($date_time)) {
    $date = $year.$month.$day;
  } else {
    $date = date('Ymd');
  }
  return $date;
}
function convertTimeDateOnly($time) {
  $year = substr($time,0,4);
  $month = substr($time,4,2);
  $day = substr($time,6,2);
  $hour = substr($time,8,2);
  $minute = substr($time,10,2);
  $second = substr($time,-2);
  $date_time = $year . "-" .$month.'-'.$day.' '.$hour.':'.$minute.':'.$second;
  if (validateDateTime($date_time)) {
    $date = $year . "-" .$month.'-'.$day;
  } else {
    $date = date('Y-m-d');
  }
  return $date;
}
    //doc route
    $app->get('/bjbs/doc',function() use ($db) {
        include "doc.php";
    });

//prod
$client_id = "38851";
$secret_key = "5968d057bb1200356aaf1b91da094e12";
/*
//dev
$client_id = "28890";
$secret_key = "9445051426bb163df98acebf18e86180";
*/


$app->post('/bni/payment', function() use ($app,$db,$apiClass,$status_token) {
        $app = \Slim\Slim::getInstance();
        $request = $app->request();
        
         $json = $app->request->getBody();
         $data_decode = json_decode($json, true); 

});

    // Define the route for processing the incoming data
$app->post('/bni/notif', function() use ($app,$db,$apiClass,$client_id, $secret_key) {

    $app = \Slim\Slim::getInstance();
    $request = $app->request();
    
     $json = $app->request->getBody();
     $data_json = json_decode($json, true); 

    if (!$data_json) {
        // Handling for invalid or missing JSON data
        $app->response->setStatus(400);
        $app->response->headers->set('Content-Type', 'application/json');
        echo json_encode(['status' => '999', 'message' => 'jangan iseng :D']);
    } else {
        if ($data_json['client_id'] === $client_id) {
            $data_asli = BniEnc::decrypt($data_json['data'], $client_id, $secret_key);

            if (!$data_asli) {
                // Handling if server time is incorrect or secret key is wrong
                $app->response->setStatus(400);
                $app->response->headers->set('Content-Type', 'application/json');
                    $insert_mega_history = array(
                        'raw_data' => json_encode($data_asli),
                        'is_success' => 0,
                        'date_created' => date('Y-m-d H:i:s')
                    );
                    if (isset($data_asli['trx_id'])) {
                        $insert_mega_history['trx_id'] = $data_asli['trx_id'];
                    }
                    $db->insert('bni_log',$insert_mega_history);

                echo json_encode(['status' => '999', 'message' => 'waktu server tidak sesuai NTP atau secret key salah.']);
            } else {
                //check if trx_id is exist on formulir
                $trx_check = $db->fetch_custom_single("select trx_id from tb_data_formulir where trx_id='".$data_asli['trx_id']."'");
                if ($trx_check) {
                    //update va
                    $array_formulir = array(
                        'is_lunas' => 'Y',
                        'updated' => $data_asli['datetime_payment'],
                        'bni_attr' => json_encode($data_asli)
                    );
                    $db->update('tb_data_formulir',$array_formulir,'trx_id',$data_asli['trx_id']);
                    $insert_mega_history = array(
                        'trx_id' => $data_asli['trx_id'],
                        'raw_data' => json_encode($data_asli),
                         'is_success' => 1,
                        'date_created' => date('Y-m-d H:i:s')
                    );
                    $db->insert('bni_log',$insert_mega_history);

                    $app->response->setStatus(200);
                     $app->response->headers->set('Content-Type', 'application/json');
                     echo json_encode(['status' => '000', 'message' => 'Success']);

                } else {
                    $va_check = $db->fetch_custom_single("select keu_keranjang_va.*,keu_keranjang_va_detail.id_keu_tagihan_mhs,keu_keranjang_va_detail.nominal as nominal_bayar from keu_keranjang_va inner join keu_keranjang_va_detail using(no_kwitansi) where no_va='".$data_asli["virtual_account"]."' and no_kwitansi='".$data_asli["trx_id"]."'");
                if ($va_check) {
                //check if tagihan already in keu bayar
                        $check_bayar = $db->fetch_custom_single("select id_keu_tagihan_mhs from keu_bayar_mahasiswa where id_keu_tagihan_mhs='$va_check->id_keu_tagihan_mhs'");
                        if ($check_bayar) {
                    $db->query("update keu_keranjang_va set is_active='N' where no_va='".$data_asli["virtual_account"]."' and no_kwitansi='".$data_asli["trx_id"]."'");
                    $update_keranjang = $db->query("update keu_keranjang_va set is_lunas='Y',updated='".date('Y-m-d H:i:s')."' where no_va='".$data_asli["virtual_account"]."' and no_kwitansi='".$data_asli["trx_id"]."'");

                     $insert_mega_history = array(
                        'trx_id' => $data_asli['trx_id'],
                         'is_success' => 1,
                        'raw_data' => json_encode($data_asli),
                        'date_created' => date('Y-m-d H:i:s')
                    );
                    $db->insert('bni_log',$insert_mega_history);

                            $app->response->setStatus(200);
                            $app->response->headers->set('Content-Type', 'application/json');
                            echo json_encode(['status' => '000']);
                        } else {
                            //data bayar mhs
                            $dtb_bayar = $db->query("select * from keu_keranjang_va_detail where no_kwitansi='".$va_check->no_kwitansi."'");
                            foreach ($dtb_bayar as $byr) {
                                $data_bayar[] = array(
                                'id_keu_tagihan_mhs' => $byr->id_keu_tagihan_mhs,
                                'tgl_bayar' => $data_asli["datetime_payment"],
                                'tgl_validasi' => $data_asli["datetime_payment"],
                                'created_by' => 'H2H BNI',
                                'nominal_bayar' => $byr->nominal
                                );
                            }


                            $data_quitansi = array(
                              'nominal_bayar' => $va_check->nominal,
                              'total_tagihan' => $va_check->nominal,
                              'validator' => 'H2H BNI',
                              'metode_bayar' => 3,
                              'id_bank' => '04',
                              'no_kwitansi' => $va_check->no_kwitansi,
                              'nim_mahasiswa' => $va_check->nim_mhs,
                              'tgl_bayar' => $data_asli["datetime_payment"],
                              'date_created' => date('Y-m-d H:i:s')
                            );
                    
                    $db->begin_transaction();
                    $db->query("update keu_keranjang_va set is_active='N' where no_va='".$data_asli["virtual_account"]."' and no_kwitansi='".$data_asli["trx_id"]."'");
                    $update_keranjang = $db->query("update keu_keranjang_va set is_lunas='Y',updated='".date('Y-m-d H:i:s')."' where no_va='".$data_asli["virtual_account"]."' and no_kwitansi='".$data_asli["trx_id"]."'");
                    $insert_kwitansi = $db->insert('keu_kwitansi',$data_quitansi);
                    if ($insert_kwitansi) {
                            $id_kwitansi = $db->last_insert_id();
                            foreach ($data_bayar as $bayar) {
                                $bayar['id_kwitansi'] = $id_kwitansi;
                                $data_bayar_akhir[] = $bayar;
                        }
                        $insert_bayar = $db->insertMulti('keu_bayar_mahasiswa',$data_bayar_akhir);
                        if ($insert_bayar==false) {
                           $db->rollback();
                        }
                    }


                    $db->commit();

                    $insert_mega_history = array(
                        'trx_id' => $data_asli['trx_id'],
                        'raw_data' => json_encode($data_asli),
                        'date_created' => date('Y-m-d H:i:s')
                    );
                    $db->insert('bni_log',$insert_mega_history);

                     $app->response->setStatus(200);
                     $app->response->headers->set('Content-Type', 'application/json');
                     echo json_encode(['status' => '000', 'message' => 'Success']);

                        }

                } else {
                    echo json_encode(['status' => '999', 'message' => 'Data is Not available']);

                }
                }
                
            }
        } else {
            $app->response->setStatus(400);
            $app->response->headers->set('Content-Type', 'application/json');
            echo json_encode(['status' => '999', 'message' => 'Client ID mismatch.']);
        }
    }

});
    


    