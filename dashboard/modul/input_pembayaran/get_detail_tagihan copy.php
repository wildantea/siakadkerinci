<?php
session_start();
include "../../inc/config.php";
session_check_json();
$nim=$_POST['nim'];
//periode bayar semester
$periode_bayar_semester=$db2->query("select periode,angkatan as nama_periode_bayar from keu_tagihan_mahasiswa 
inner join view_semester on periode=id_semester
WHERE nim='$nim' group by periode order by periode desc");
?>
<table class="table table-striped table-bordered">
<thead>
<tr>
<th style="width:50px"></th>
<th style="width:150px;text-align:center">Jenis Tagihan</th>
<th style="width:100px;text-align:center">Jumlah Tagihan</th>
<th style="width:100px;text-align:center">Sudah Bayar</th>
<th style="width:120px;text-align:center">Sisa</th>
<th style="width:120px;text-align:center">Jumlah Bayar</th>
<th width=10% style="text-align:center">Boleh KRS</th>
<th width=10% style="text-align:center">Lunaskan</th>
<th width=10% style="text-align:center">Detil Cicilan</th>
</tr>
</thead>
<tbody id="isi_body">
<?php
$jml_tagihan_all=0;
$jml_sudah=0;
$jml_sisa=0;
foreach ($periode_bayar_semester as $periode_bayar) {
	?>

	<tr>
	<td colspan='7'><?=$periode_bayar->nama_periode_bayar;?></td>
	</tr>
	<?php
	//detail tagihan dan detail pembayaran mahasiswa
	$detail_tagihan_mhs = $db2->query("SELECT ktm.periode,kbm.id_bayar_mahasiswa as lunas,biaya_sks as is_biaya_sks, ktm.id_keu_tagihan_mhs,ktt.nama_tagihan,kt.nominal_tagihan,kbm.nominal_bayar,vsm.nim,vsm.nama,ktt.syarat_krs from keu_tagihan_mahasiswa ktm
INNER JOIN view_simple_mhs vsm USING(nim)
INNER JOIN keu_tagihan kt USING(id_tagihan_prodi)
INNER JOIN keu_jenis_tagihan ktt USING(kode_tagihan)
LEFT JOIN keu_bayar_mahasiswa kbm USING(id_keu_tagihan_mhs)
		WHERE ktm.nim='$nim' AND ktm.periode='$periode_bayar->periode'");
	foreach ($detail_tagihan_mhs as $detail_tagihan) {

		if ($detail_tagihan->syarat_krs==1) {
			
			$afirmasi= $db2->fetchCustomSingle("SELECT * FROM keu_bayar_mahasiswa m WHERE m.id_keu_tagihan_mhs='$detail_tagihan->id_keu_tagihan_mhs' ");
			if ($afirmasi) {
				$afirmasi=$afirmasi->afirmasi;
			}

			
			if ($detail_tagihan && $afirmasi=='1') {
				$btn_boleh_krs="<input name='krs_boleh-$detail_tagihan->id_keu_tagihan_mhs' id='krs_boleh-$detail_tagihan->id_keu_tagihan_mhs' onchange='afirmasi_krs($detail_tagihan->id_keu_tagihan_mhs)' class='make-switch' type='checkbox' checked>";
			}else{
				$btn_boleh_krs="<input name='krs_boleh-$detail_tagihan->id_keu_tagihan_mhs' id='krs_boleh-$detail_tagihan->id_keu_tagihan_mhs' onchange='afirmasi_krs($detail_tagihan->id_keu_tagihan_mhs)' class='make-switch' type='checkbox'>";
			}


		}else{
			$btn_boleh_krs="";
		}
		if ($detail_tagihan->lunas=='' || $detail_tagihan->lunas==NULL) {
			$btn_lunaskan="<input name='lunaskan_tagihan-$detail_tagihan->id_keu_tagihan_mhs' id='lunaskan_tagihan-$detail_tagihan->id_keu_tagihan_mhs' onchange='lunaskan_tagihan($detail_tagihan->id_keu_tagihan_mhs,\"$detail_tagihan->nim\")' class='make-switch' type='checkbox'>";
		}else{
			$btn_lunaskan="<input name='lunaskan_tagihan-$detail_tagihan->id_keu_tagihan_mhs' id='lunaskan_tagihan-$detail_tagihan->id_keu_tagihan_mhs' onchange='lunaskan_tagihan($detail_tagihan->id_keu_tagihan_mhs,\"$detail_tagihan->nim\")' class='make-switch' type='checkbox' checked>";
		}
		//get jumlah cicilan pembayaran
		$jml_dibayarkan=0;
		$jumlah_cicilan=$db2->fetchCustomSingle("SELECT SUM(c.jml_bayar) AS jml FROM keu_cicilan c WHERE c.id_tagihan_mhs='$detail_tagihan->id_keu_tagihan_mhs'");
		if ($jumlah_cicilan) {
			$jml_dibayarkan=$jumlah_cicilan->jml;
		}
		
		$nominal_tagihan = $detail_tagihan->nominal_tagihan;
		//if jenis tagihan adalah biaya sks, kalikan dengan sks
		if ($detail_tagihan->is_biaya_sks==1) {
			$jml_sks = 0;
			$jumlah_sks = $db2->fetchCustomSingle("select sum(sks) as jml_sks from view_krs_mhs k where
				k.nim='$nim' and k.sem_id='$detail_tagihan->periode' and k.disetujui='1' ");
			if ($jumlah_sks) {
				$jml_sks = $jumlah_sks->jml_sks;
			}
			$nominal_tagihan = $jml_sks * $nominal_tagihan;
		}


		//jumlah tagihan 
		$jml_tagihan_all=$jml_tagihan_all+$nominal_tagihan;
		//jumlah sudah dibayarkan
		$jml_sudah=$jml_sudah+$jml_dibayarkan;
		//jumlah sisa tagihan
		$jml_sisa=$jml_sisa+($nominal_tagihan-$jml_dibayarkan);
		$style=" class='bg-red color-palette'";
		$checked = 1;
		$disable_check="  ";
		$nominal_bayar_tagihan=(int)$detail_tagihan->nominal_bayar;
		if (($nominal_tagihan-$jml_dibayarkan)<=0 || $nominal_bayar_tagihan>=$nominal_tagihan) {
			$style = " style='background:#b8ead3;'";
			$disable_check = " disabled";
			$checked = 0;
		}
		?>

		<tr <?=$style;?>>
		<td><label class='mt-checkbox mt-checkbox-single mt-checkbox-outline '>
		<input type='checkbox'  name='cek-<?=$detail_tagihan->id_keu_tagihan_mhs;?>' class='group-checkable bulk-check-sesi' id='cek-<?=$detail_tagihan->id_keu_tagihan_mhs;?>' onclick='set_bayar(<?=$detail_tagihan->id_keu_tagihan_mhs;?>,<?=$nominal_tagihan-$jml_dibayarkan;?>)' <?=$disable_check;?>>
		<span></span></label>
		</td>
		<td><?=$detail_tagihan->nama_tagihan;?></td>
		<td style='text-align:right'><span style='float:left'>Rp.</span> <span style='float:right'><?=rupiah($nominal_tagihan);?></span></td>
		<td style='text-align:right'><span style='float:left'>Rp.</span> <span style='float:right'><?=rupiah($jml_dibayarkan);?></span></td>
		<td style='text-align:right'><span style='float:left'>Rp.</span> <span style='float:right'><?=rupiah(($nominal_tagihan-$jml_dibayarkan));?></span></td>
		<td style='text-align:right' id='form-<?=$detail_tagihan->id_keu_tagihan_mhs;?>'></td>
		<td>
		<?=$btn_boleh_krs;?>
		</td>
		<td>
		<?=$btn_lunaskan;?>
		</td>
		<td>
		<a style='cursor:pointer;' class='btn btn-success' onclick='showDetilCicilan(<?=$detail_tagihan->id_keu_tagihan_mhs;?>)'><i class='fa fa-eye'></i><span id='btn-history-<?=$detail_tagihan->id_keu_tagihan_mhs;?>'> History Cicilan </span></a>
		</td>
		</tr>
		<?php

	}

}
$ket_lunas="";
if ($jml_sisa>0) {
	$ket_lunas="background:#dd4b39;color:white";
}
?>
<tr>
<td colspan='2'>Jumlah Tagihan</td>
<td style='text-align:right' class="bg-aqua-active color-palette"><span style='float:left'>Rp.</span> <span style='float:right'><?=rupiah($jml_tagihan_all);?></span></td>
<td style='text-align:right' class="bg-green color-palette"><span style='float:left'>Rp.</span> <span style='float:right'><?=rupiah($jml_sudah);?></span></td>
<td style='text-align:right;' class="bg-red-active color-palette"><b><span style='float:left'>Rp.</span><span style='float:right'><?=rupiah($jml_sisa);?></span></b></td>
<td></td>
</tr>
</tbody>
</table>
<i style='color:blue'>* Silahkan centang item yang akan di bayarkan dan input nominal yang akan dibayarkan</i>
