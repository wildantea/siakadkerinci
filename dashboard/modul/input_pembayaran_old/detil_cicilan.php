<?php
session_start();
include "../../inc/config.php";
session_check_json();
$id_tagihan=$_POST['id_tagihan'];
$q=$db->query("SELECT c.id_tagihan_mhs, t.nim, jn.nama_tagihan,c.jml_bayar,c.tgl_bayar,c.validator FROM keu_cicilan c 
JOIN keu_tagihan_mahasiswa t ON c.id_tagihan_mhs=t.id
JOIN keu_tagihan ta ON ta.id=t.id_tagihan_prodi
JOIN keu_jenis_tagihan jn ON jn.kode_tagihan=ta.kode_tagihan
WHERE t.id='$id_tagihan' order by c.tgl_bayar asc ");
echo '<table class="table table-striped table-bordered">
  <thead>
  <tr>
    <th style="width:50px">No</th>
    <th style="text-align:center">Nama Tagihan</th>
    <th style="text-align:center">Nominal Pembayaran</th>
    <th style="text-align:center">Tanggal Bayar</th>
    <th style="text-align:center">Validator</th>
  </tr>
  </thead>
  <tbody id="isi_body_cicilan">';
  $total=0;
  $no=1;
foreach ($q as $k) {
	  echo "<tr>
	         <td >$no</td>
	         <td>$k->nama_tagihan</td>
	         <td><span style='float:left'>Rp.</span> <span style='float:right'>".number_format($k->jml_bayar)."</span></td>
	         <td>".tgl_indo($k->tgl_bayar)."</td>
	         <td>$k->validator</td>
	        </tr>";
	
	  	$total=$total+$k->jml_bayar;

	$no++; 
}

echo "<tr>
        <td colspan='2'>Total yang sudah di cicil</td>
        <td style='text-align:right'><span style='float:left'>Rp.</span> <span style='float:right'>".number_format($total)."</span></td>
        <td></td>
        <td></td>
       </tr>
      </tbody>
</table>";

?>