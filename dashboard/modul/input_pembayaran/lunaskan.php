<?php
session_start();
include "../../inc/config.php";
session_check_json();

$ids = $_POST['id'];

$detail_tagihan_mhs = $db2->query("SELECT vsm.mhs_id,vsm.jur_kode as kode_jur, ktm.periode, ktm.id as id_keu_tagihan_mhs,ktt.nama_tagihan,kt.nominal_tagihan,vsm.nim,vsm.nama,ktt.syarat_krs,potongan
from keu_tagihan_mahasiswa ktm
INNER JOIN mahasiswa vsm USING(nim)
INNER JOIN keu_tagihan kt on ktm.id_tagihan_prodi=kt.id
INNER JOIN keu_jenis_tagihan ktt USING(kode_tagihan)
		WHERE ktm.id in($ids)");
	foreach ($detail_tagihan_mhs as $detail_tagihan) {

		//if ($detail_tagihan->syarat_krs==1) {

        //nominal tagihan per id tagihan mahasiswa
		$nominal_tagihan = $detail_tagihan->nominal_tagihan;

        //potongan
        $potongan = $detail_tagihan->potongan;

        //nominal setelah potongan
        $nominal_tagihan_akhir = $nominal_tagihan-$potongan;

        //jumlah sudah dibayar
        //get jumlah cicilan pembayaran
        $jml_dibayarkan=0; 
        $jumlah_cicilan=$db2->fetchCustomSingle("SELECT SUM(nominal_bayar) AS jml FROM keu_bayar_mahasiswa WHERE id_keu_tagihan_mhs='$detail_tagihan->id_keu_tagihan_mhs' and is_removed='0'");
        // echo "SELECT SUM(nominal_bayar) AS jml FROM keu_bayar_mahasiswa WHERE id_keu_tagihan_mhs='$detail_tagihan->id_keu_tagihan_mhs' and is_removed='0' <br>";
        if ($jumlah_cicilan) {
            $jml_dibayarkan=$jumlah_cicilan->jml;
        }

      

        //tunggakan/sisa yang belum dibayar
        $tunggakan = $nominal_tagihan_akhir-$jml_dibayarkan;

        $no_kwitansi = strtotime(date('Y-m-d')).rand(1000000000, 9999999999);
        $tanggal_bayar = $_POST['tgl_bayar']." ".date("H:i:s");
        $data_quitansi = array(
              'nominal_bayar' => $nominal_tagihan_akhir-$jml_dibayarkan,
              'total_tagihan' => $nominal_tagihan_akhir-$jml_dibayarkan,
              'validator' => $_SESSION['username'],
              'kode_jur' => $detail_tagihan->kode_jur,
              'no_kwitansi' => $no_kwitansi,
              'tgl_bayar' => $tanggal_bayar,
              'keterangan' => $_POST['keterangan'],
              'nim_mahasiswa' => $detail_tagihan->nim,
              'date_created' => date('Y-m-d H:i:s')
            );
        if ($_POST['metode_bayar']==1) {
		  $data_quitansi['metode_bayar'] = 1;
		} elseif ($_POST['metode_bayar']==2) {
		  $data_quitansi['metode_bayar'] = 2;
		  $data_quitansi['id_bank'] = $_POST['bank'];
		}

		
		$db2->insert('keu_kwitansi',$data_quitansi);

		//echo $db2->getErrorMessage();

		$last_insert_id = $db2->getLastInsertId();


		//insert pembayaran
        $data = array(
            'id_keu_tagihan_mhs' => $detail_tagihan->id_keu_tagihan_mhs,
            'tgl_bayar' => $tanggal_bayar,
            'tgl_validasi' => date('Y-m-d H:i:s'),
            'created_by' => $_SESSION['username'],
            'id_kwitansi' => $last_insert_id,
            'nominal_bayar' => $nominal_tagihan_akhir-$jml_dibayarkan
        );
        $db2->insert("keu_bayar_mahasiswa",$data);


        $jml_sudah = 0;
        $nominal_tagihan = 0;

}

action_response($db2->getErrorMessage());

