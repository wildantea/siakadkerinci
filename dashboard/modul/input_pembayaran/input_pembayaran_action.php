<?php
session_start();
include "../../inc/config.php";
session_check_json();

function replace_dot($var) {
  return str_replace(".", "", $var);
}
switch ($_GET["act"]) {
  case "in":

//print_r($_POST);

foreach ($_POST['nominal_tagihan'] as $id_keu_tagihan_mhs => $jml_bayar) {

    $jml_bayar = replace_dot($jml_bayar);

    if ($jml_bayar>0) {
    $detail_tagihan_mhs = $db2->fetchCustomSingle("SELECT ktm.periode, ktm.id as id_keu_tagihan_mhs,ktt.nama_tagihan,kt.nominal_tagihan,vsm.nim,vsm.nama,ktt.syarat_krs,potongan 
from keu_tagihan_mahasiswa ktm
    INNER JOIN mahasiswa vsm USING(nim)
    INNER JOIN keu_tagihan kt on ktm.id_tagihan_prodi=kt.id
    INNER JOIN keu_jenis_tagihan ktt USING(kode_tagihan)
        WHERE ktm.id='$id_keu_tagihan_mhs'");



        //nominal tagihan per id tagihan mahasiswa
    $nominal_tagihan = $detail_tagihan_mhs->nominal_tagihan;

        //potongan
        $potongan = $detail_tagihan_mhs->potongan;

        //nominal setelah potongan
        $nominal_tagihan_akhir = $nominal_tagihan-$potongan;

        //jumlah sudah dibayar
        //get jumlah cicilan pembayaran
        $jml_dibayarkan=0;
        $jumlah_cicilan=$db2->fetchCustomSingle("SELECT SUM(nominal_bayar) AS jml FROM keu_bayar_mahasiswa WHERE id_keu_tagihan_mhs='$detail_tagihan_mhs->id_keu_tagihan_mhs' and is_removed='0'");
        if ($jumlah_cicilan) {
            $jml_dibayarkan=$jumlah_cicilan->jml;
        }

        //tunggakan/sisa yang belum dibayar
        $tunggakan = $nominal_tagihan_akhir-$jml_dibayarkan;

        $total_dibayar=$jml_bayar+$jml_dibayarkan;



    if($total_dibayar>$nominal_tagihan_akhir) {
      action_response('Ada kesalahan Pembayaran, Silakan Coba Lagi');
    } 

  }
}

/*$urutan_bayar_prodi = $db2->fetchCustomSingle("select urutan_bayar from keu_kwitansi where kode_jur=? and date(date_created)=?",
  array(
    'kode_jur' => $_POST['kode_jur'],
    'date_created' => date('Y-m-d')
  )
);
*/

/*if ($urutan_bayar_prodi) {
  $urutan = $urutan_bayar_prodi->urutan_bayar;
  $urutan = $urutan+1;
  if ($urutan<10) {
    $no_kwitansi = date('Ymd').$_POST['kode_jur'].'00'.($urutan);
  } else {
    $no_kwitansi = date('Ymd').$_POST['kode_jur'].($urutan);
  }
} else {
  $no_kwitansi = date('Ymd').$_POST['kode_jur'].'001';
  $urutan = 1;
}*/
$no_kwitansi = strtotime(date('Y-m-d')).rand();
$data_quitansi = array(
              'nominal_bayar' => replace_dot($_POST['total_tagihan']),
              'total_tagihan' => replace_dot($_POST['total_tagihan']),
              'validator' => $_SESSION['username'],
              'kode_jur' => $_POST['kode_jur'],
              'no_kwitansi' => $no_kwitansi,
              'keterangan' => $_POST['keterangan'],
              'nim_mahasiswa' => $_POST['nim'],
              'date_created' => date('Y-m-d H:i:s')
            );

if ($_POST['metode_bayar']==1) {
  $data_quitansi['metode_bayar'] = 1;
  $tanggal_bayar = date('Y-m-d H:i:s');
} elseif ($_POST['metode_bayar']==2) {
  $data_quitansi['metode_bayar'] = 2;
  $tanggal_bayar = $_POST['tgl_bayar']." ".date("H:i:s");
  $data_quitansi['id_bank'] = $_POST['bank'];
}


$data_quitansi['tgl_bayar'] = $tanggal_bayar;



//print_r($data_quitansi);

$db2->insert('keu_kwitansi',$data_quitansi);

//echo $db2->getErrorMessage();

$last_insert_id = $db2->getLastInsertId();


//check if ada saldo
/*if ($_POST['saldo_pembayaran']>0) {
  $insert_data_saldo = array(
    'nim' => $_POST['nim'],
    'pemasukan' => replace_dot($_POST['saldo_pembayaran']),
    'pengeluaran' => 0,
    'keterangan_saldo' => 'Kelebihan Pembayaran Tanggal '.substr($tanggal_bayar, 0,10),
    'date_created' => date('Y-m-d H:i:s')
  );
  $db2->insert('keu_saldo_potongan',$insert_data_saldo);
}*/
$total_dibayarkan = 0;
foreach ($_POST['nominal_tagihan'] as $id_keu_tagihan_mhs => $jml_bayar) {

    $jml_bayar = replace_dot($jml_bayar);

    if ($jml_bayar>0) {
    $detail_tagihan_mhs = $db2->fetchCustomSingle("SELECT ktm.periode, ktm.id as id_keu_tagihan_mhs,ktt.nama_tagihan,kt.nominal_tagihan,vsm.nim,vsm.nama,ktt.syarat_krs,potongan 
from keu_tagihan_mahasiswa ktm
    INNER JOIN mahasiswa vsm USING(nim)
    INNER JOIN keu_tagihan kt on ktm.id_tagihan_prodi=kt.id
    INNER JOIN keu_jenis_tagihan ktt USING(kode_tagihan)
        WHERE ktm.id='$id_keu_tagihan_mhs'");

        //nominal tagihan per id tagihan mahasiswa
    $nominal_tagihan = $detail_tagihan_mhs->nominal_tagihan;

        //potongan
        $potongan = $detail_tagihan_mhs->potongan;

        //nominal setelah potongan
        $nominal_tagihan_akhir = $nominal_tagihan-$potongan;

        //jumlah sudah dibayar
        //get jumlah cicilan pembayaran
        $jml_dibayarkan=0;

        $jumlah_cicilan=$db2->fetchCustomSingle("SELECT SUM(nominal_bayar) AS jml FROM keu_bayar_mahasiswa WHERE id_keu_tagihan_mhs='$detail_tagihan_mhs->id_keu_tagihan_mhs' and is_removed='0'");
        if ($jumlah_cicilan) {
            $jml_dibayarkan=$jumlah_cicilan->jml;
        }

        //tunggakan/sisa yang belum dibayar
        $tunggakan = $nominal_tagihan_akhir-$jml_dibayarkan;

        $total_dibayar=$jml_bayar+$jml_dibayarkan;


    //check if jumlah bayar=nominal tagihan
  //  if ($nominal_tagihan_akhir==$total_dibayar) {
      //insert bayar mahasiswa
        //get periode pembayaran and kode prodi
        /*$periode = $db2->fetchCustomSingle('select ktm.periode, kt.kode_prodi,kt.nominal_tagihan,ktm.id as id_keu_tagihan_mhs
        from keu_tagihan_mahasiswa ktm  inner join keu_tagihan kt on ktm.id_tagihan_prodi=kt.id
        where ktm.id=?',array('id_keu_tagihan_mhs' => $id_keu_tagihan_mhs));*/

        $data = array(
            'id_keu_tagihan_mhs' => $id_keu_tagihan_mhs,
            'tgl_bayar' => $tanggal_bayar,
            'tgl_validasi' => date('Y-m-d H:i:s'),
            'created_by' => $_SESSION['username'],
            'id_kwitansi' => $last_insert_id,
            'nominal_bayar' => $jml_bayar,
        );
           $db2->insert("keu_bayar_mahasiswa",$data);

   // } //else {
/*      $data_cicilan = array(
        'id_tagihan_mhs' => $id_keu_tagihan_mhs,
        'jml_bayar' => $jml_bayar ,
        'tgl_bayar' => $tanggal_bayar,
        'tgl_validasi' => date('Y-m-d H:m:s'),
        'id_kwitansi' => $last_insert_id,
        'validator' => $_SESSION['username']
      );

      $db2->insert("keu_cicilan",$data_cicilan);
*/
   // }

    }

}

action_response($db2->getErrorMessage());
      //print_r($_POST);
break;
  case "delete":
    
    
    
    $db2->delete("keu_bayar_mahasiswa","id",$_POST["id"]);
    action_response($db2->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db2->delete("keu_bayar_mahasiswa","id",$id);
         }
    }
    action_response($db2->getErrorMessage());
    break;
  case "up":
    
   $data = array(
      "id_keu_tagihan_mhs" => $_POST["id_keu_tagihan_mhs"],
      "tgl_bayar" => $_POST["tgl_bayar"],
   );
   
   
   

    
    
    $up = $db2->update("keu_bayar_mahasiswa",$data,"id",$_POST["id"]);
    
    action_response($db2->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>