  <?php
session_start();
include "../../inc/config.php";
session_check();

if ($_POST['jenis_pembayaran']=='all') {
	$jns = "";
} else {
	$jns = "and kjt.kode_pembayaran='".$_POST['jenis_pembayaran']."'";
}
$where = array(
'periode' => $_POST['periode'],
'nim' => $_POST['nim']
);
$get_pembarayan = $db->query("select ktm.id, kjt.kode_tagihan,kjt.nama_tagihan,kt.nominal_tagihan from keu_jenis_tagihan kjt
inner join keu_tagihan kt on kjt.kode_tagihan=kt.kode_tagihan
inner join keu_tagihan_mahasiswa ktm on kt.id=ktm.id_tagihan_prodi
where periode=? and nim=? $jns
and ktm.id not in(select id_keu_tagihan_mhs from keu_bayar_mahasiswa)",$where);
if ($get_pembarayan->rowCount()>0) {
$no = 1;
$jumlah = 0;
foreach ($get_pembarayan as $gt) {
	echo "<tr><input type='hidden' name='id_tagihan[]' value='$gt->id'>
	<td>$no</td><td>$gt->nama_tagihan</td><td>Rp. ".rupiah($gt->nominal_tagihan)."</td>";
$jumlah+=$gt->nominal_tagihan;
$no++;
}

?>
    <tr>
      <td style="font-weight: bold;" colspan="2">TOTAL</td>
      <td style="font-weight: bold;">Rp. <?=rupiah($jumlah);?></td>
    </tr>
<?php
} else {
	echo "<td colspan='3' style='text-align:center;color:#f00'>Tidak Ada data Tagihan atau Tagihan Mahasiswa Belum Disetting</td>";
}
?>