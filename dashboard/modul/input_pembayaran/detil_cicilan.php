<?php
session_start();
include "../../inc/config.php";
session_check_json();
$id_tagihan=$_POST['id_tagihan'];
$detail_cicilan_pembayaran=$db2->query("SELECT
   m.jur_kode,m.mulai_smt,
 t.periode,t.nim, ta.kode_tagihan, ta.nominal_tagihan,potongan,
  t.nim, jn.nama_tagihan,c.nominal_bayar as jml_bayar,c.tgl_bayar,c.created_by as validator,
(select keterangan from keu_kwitansi where c.id_kwitansi=keu_kwitansi.id_kwitansi) as ket_kwitansi
   FROM keu_bayar_mahasiswa c
right JOIN keu_tagihan_mahasiswa t ON c.id_keu_tagihan_mhs=t.id
right JOIN keu_tagihan ta on t.id_tagihan_prodi=ta.id
right JOIN keu_jenis_tagihan jn ON jn.kode_tagihan=ta.kode_tagihan
right join mahasiswa m on m.nim=t.nim
WHERE t.id='$id_tagihan' and is_removed='0' order by c.tgl_bayar asc ");
echo '<table class="table table-striped table-bordered">
  <thead>
  <tr>
    <th style="width:50px">No</th>
    <th style="text-align:center">Nama Tagihan</th>
    <th style="text-align:center">Nominal Pembayaran</th>
    <th style="text-align:center">Tanggal Bayar</th>
    <th style="text-align:center">Keterangan</th>
    <th style="text-align:center">Validator</th>
  </tr>
  </thead>
  <tbody id="isi_body_cicilan">';
  $total=0;
  $no=1;
  $nominal_tagihan = 0;
foreach ($detail_cicilan_pembayaran as $detail_cicilan) {

    $nominal_tagihan = $detail_cicilan->nominal_tagihan-$detail_cicilan->potongan;
    //if jenis tagihan adalah biaya sks, kalikan dengan sks
/*    if ($detail_cicilan->is_biaya_sks==1) {
      $jml_sks = 0;
      $jumlah_sks = $db2->fetchCustomSingle("select sum(sks) as jml_sks from view_krs_mhs_kelas k where
        k.nim='$detail_cicilan->nim' and k.sem_id='$detail_cicilan->periode' and k.disetujui='1' ");
      if ($jumlah_sks) {
        $jml_sks = $jumlah_sks->jml_sks;
      }
      $nominal_tagihan = $jml_sks * $nominal_tagihan;
    }*/

	  echo "<tr>
	         <td >$no</td>
	         <td>$detail_cicilan->nama_tagihan</td>
	         <td><span style='float:left'>Rp.</span> <span style='float:right'>".rupiah($detail_cicilan->jml_bayar)."</span></td>
	         <td>".tgl_indo($detail_cicilan->tgl_bayar)."</td>
           <td>$detail_cicilan->ket_kwitansi</td>
	         <td>$detail_cicilan->validator</td>
	        </tr>";

	  	$total=$total+$detail_cicilan->jml_bayar;

	$no++;
}
$warna="";
if ($nominal_tagihan-$total!=0) {
  $warna="background:#dd4b39;color:white";
}
echo "<tr>
        <td colspan='2'>Total yang sudah dibayar</td>
        <td style='text-align:right;background: #00a65a;color:white;'><span style='float:left'>Rp.</span> <span style='float:right'>".rupiah($total)."</span></td>
        <td></td>
        <td></td>
       </tr>
       <tr>
        <td colspan='2'>Total Tagihan</td>
        <td style='text-align:right'><span style='float:left'>Rp.</span> <span style='float:right'>".rupiah($nominal_tagihan)."</span></td>
        <td></td>
        <td></td>
       </tr>
       <tr>
        <td colspan='2'>Tunggakan</td>
        <td style='text-align:right;$warna'><span style='float:left;'>Rp.</span> <span style='float:right'>".rupiah($nominal_tagihan-$total)."</span></td>
        <td></td>
        <td></td>
       </tr>
      </tbody>
</table>";

?>
