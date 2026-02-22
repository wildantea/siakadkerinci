<?php
session_start();
include "../../inc/config.php";
session_check_json();
$id_kwitansi=$_POST['id_kwitansi'];
$detail_mhs = $db2->fetchCustomSingle("select * from keu_kwitansi inner join view_simple_mhs_data on nim_mahasiswa=nim where id_kwitansi=?",array('id_kwitansi' => $id_kwitansi));
?>
<table class="table" width="100%">
  <tbody>
    <tr>
      <td><strong>NIM</strong></td>
      <td><?=$detail_mhs->nim;?></td>
      <td><strong>Nama</strong></td>
      <td><?=$detail_mhs->nama;?></td>
    </tr>
    <tr>
      <td><strong>Angkatan</strong></td>
      <td><?=$detail_mhs->angkatan ;?></td>
      <td><strong>Program Studi</strong></td>
      <td><?=$detail_mhs->jurusan ;?></td>
    </tr>
    <tr>
      <td><strong>No. Pembayaran</strong></td>
      <td><?=$detail_mhs->no_kwitansi ;?></td>
      <td><strong>Validator</strong></td>
      <td><?=$detail_mhs->validator ;?></td>
    </tr>
    <tr>
      <td><strong>Tanggal Bayar</strong></td>
      <td><?=tgl_indo($detail_mhs->tgl_bayar) ;?></td>
      
    </tr>
  </tbody>
</table>
<?php
$detail_cicilan_pembayaran=$db2->query("SELECT
   m.jur_kode,m.mulai_smt,
 t.periode,t.nim, ta.kode_tagihan, ta.nominal_tagihan,potongan,
  t.nim, jn.nama_tagihan,c.nominal_bayar as jml_bayar,c.tgl_bayar,c.created_by as validator FROM keu_bayar_mahasiswa c
right JOIN keu_tagihan_mahasiswa t ON c.id_keu_tagihan_mhs=t.id
right JOIN keu_tagihan ta on t.id_tagihan_prodi=ta.id
right JOIN keu_jenis_tagihan jn ON jn.kode_tagihan=ta.kode_tagihan
right join mahasiswa m on m.nim=t.nim
WHERE c.id_kwitansi='$id_kwitansi' and is_removed='0' order by c.tgl_bayar asc ");
echo '<table class="table table-striped table-bordered">
  <thead>
  <tr>
    <th style="width:50px">No</th>
    <th style="text-align:center">Nama Tagihan</th>
    <th style="text-align:center">Jml Tagihan</th>
    <th style="text-align:center">Dibayar</th>
    <th style="text-align:center">Sisa</th>
  </tr>
  </thead>
  <tbody id="isi_body_cicilan">';
  $total=0;
  $no=1;
  $nominal_tagihan = 0;
  $total_nominal_tagihan = 0;
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
	         <td>$detail_cicilan->nama_tagihan $detail_cicilan->periode</td>
           <td><span style='float:left'>Rp.</span> <span style='float:right'>".rupiah($nominal_tagihan)."</span></td>
	         <td><span style='float:left'>Rp.</span> <span style='float:right'>".rupiah($detail_cicilan->jml_bayar)."</span></td>
           <td><span style='float:left'>Rp.</span> <span style='float:right'>".rupiah($nominal_tagihan-$detail_cicilan->jml_bayar)."</span></td>
	        </tr>";

	  	$total=$total+$detail_cicilan->jml_bayar;


	$no++;
}
echo "<tr>
        <td colspan='3'>Total</td>
        <td><span style='float:left'>Rp.</span> <span style='float:right;font-weight:bold'>".rupiah($total)."</span></td>
       </tr>
      </tbody>
</table>";

?>
