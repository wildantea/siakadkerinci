<?php
include 'inc/config.php';
$res = get_report_briva(date("Ymd"),date("Ymd"));  
// echo "<pre>"; 
// print_r($res);
$db->query("delete from transaksi_briva where tgl_bayar like '%".date("Y-m-d")."%' "); 
if ($res['status']=='1') {
	$id_keu_tagihan_mhs = 0;
 	foreach ($res['data'] as $key => $v) {
 		$va_check = $db->fetch_custom_single("select keu_keranjang_va.*,keu_keranjang_va_detail.id_keu_tagihan_mhs,keu_keranjang_va_detail.nominal as nominal_bayar from keu_keranjang_va inner join keu_keranjang_va_detail using(no_kwitansi)
 		  where keu_keranjang_va.no_va='".$v['brivaNo'].$v['custCode']."' order by keu_keranjang_va.id_keranjang desc limit 1 "); 
 		if ($va_check) { 
						if ($va_check->is_lunas!='Y') {  
							//echo "string";
						
							//data bayar mhs
							$dtb_bayar = $db->query("select * from keu_keranjang_va_detail where no_kwitansi='".$va_check->no_kwitansi."'");

							foreach ($dtb_bayar as $byr) {
								$id_keu_tagihan_mhs = $byr->id_keu_tagihan_mhs;
								$data_bayar[] = array(
								'id_keu_tagihan_mhs' => $byr->id_keu_tagihan_mhs,
								'tgl_bayar'          => $v['paymentDate'],
								'tgl_validasi'       => $v['paymentDate'],
								'created_by'         => 'API BRIVA',
								'nominal_bayar'      => $byr->nominal
								);
							} 


							$data_quitansi = array(
				              'nominal_bayar' => $va_check->nominal,
				              'total_tagihan' => $va_check->nominal,
				              'validator'     => 'API BRIVA',
				              'metode_bayar'  => 3,
				              'id_bank'       => '01',
				              'no_kwitansi'   => $va_check->no_kwitansi,
				              'nim_mahasiswa' => $va_check->nim_mhs,
				              'tgl_bayar'     => $v['paymentDate'],
				              'date_created'  => date('Y-m-d H:i:s')
				            );
					
						$db->begin_transaction();
						//$db->query("update keu_keranjang_va set is_active='N' where nim_mhs='".$va_check->nim_mhs."'");
						$db->update(
							'keu_keranjang_va',
							array('is_lunas' => 'Y'),
							'id_keranjang',$va_check->id_keranjang
						);
						$insert_kwitansi = $db->insert('keu_kwitansi',$data_quitansi);
						if ($insert_kwitansi) {
								$id_kwitansi = $db->last_insert_id();
								foreach ($data_bayar as $bayar) {
									$bayar['id_kwitansi'] = $id_kwitansi;
									$data_bayar_akhir[]   = $bayar;
							}
							$insert_bayar = $db->insertMulti('keu_bayar_mahasiswa',$data_bayar_akhir);
							if ($insert_bayar==false) {
							   $db->rollback();
							}
						}

					    $db->commit();
					    $data_bayar_akhir = array();
					    $data_bayar = array();
						
					}

				} 
		$data = array('no_briva'           => $v['brivaNo'].$v['custCode'] ,
 		              'nama'               => $v['nama'],
 		              'jumlah'             => $v['amount'],
 		              'tgl_bayar'          => $v['paymentDate'],
 		              'teller_id'          => $v['tellerid'],
 		              'date_created'       => date("Y-m-d H:i:s"),
 		              'id_keu_tagihan_mhs' => $id_keu_tagihan_mhs,
 		              'norek'              => $v['no_rek'] );
 		$db->insert("transaksi_briva",$data);
 	} 
 }     
?> 