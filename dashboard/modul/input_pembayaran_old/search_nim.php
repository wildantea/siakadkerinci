<?php
session_start();
include "../../inc/config.php";
session_check_json();
$term = $_GET['term'];
$data = array();
// buat database dan table provinsi
$query = $db->query("select mahasiswa.mhs_id,nim,nama,view_prodi_jenjang.jurusan from mahasiswa inner join view_prodi_jenjang
on mahasiswa.jur_kode=view_prodi_jenjang.kode_jur WHERE nim LIKE '%$term%' or nama LIKE '%$term%' LIMIT 5");
foreach ($query as $dt) {
	      $data[] = array(
	 	                 'jurusan' => $dt->jurusan,
	 	                 'nama'    => $dt->nama,
	 	                 'label'   => $dt->nim.' '.$dt->nama.' ('.$dt->jurusan.')',
	 	                 'value'   => $dt->nim
	 	                 );
}
/*$det_tagihan = '<table class="table table-striped table-bordered">
					  <thead>
					  <tr>
					    <th width="3%">No</th>
					    <th>Jenis Tagihan</th>
					    <th>Jumlah</th>
					  </tr>
					  </thead>
					  <tbody id="isi_body"></tbody>
					</table>';
		$data[]['detail_tagihan']=$det_tagihan;*/
echo json_encode($data);
?>