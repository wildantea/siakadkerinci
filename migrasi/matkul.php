<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<?php
include '../dashboard/inc/config.php';
$q = $dbo->query("select semester, id_mk,id_simak_baru as id,kode_mk,mk,'A' as 'jenis_mk',sks from mk limit 10 ");
?>
<table border="1">
	<thead>
		<tr>
			<th>Id</th>
			<th>Kode MK</th>
			<th>Nama MK</th>
			<th>Jenis MK</th>
			<th>SKS</th>
			<th>semester</th>
			<th>Jurusan</th>
		</tr>
	</thead>
	<tbody>
<?php
foreach ($q as $k) {
	$qq = $dbo->query("select m.id_jurusan,j.jurusan
from khs k 
left join kontrakx kk on kk.id_kontrakx=k.id_kontrakx
left join mk_dis md on md.id_mk_dis=kk.kntrk_mkdis 
left join mhs m on m.id_mhs=k.id_mhs
left join jurusan j on j.id_jurusan=m.id_jurusan
where kk.id_mk='$k->id_mk' or md.id_mk='$k->id_mk' 
group by m.id_jurusan");
	$jur = array();
	foreach ($qq as $kk) {
		$jur[]=$kk->jurusan;
	}
	$jurusan = implode(", ", $jur);
	echo "<tr>
	<td>$k->id $k->id_mk</td>
	<td>$k->kode_mk</td>
	<td>$k->mk</td>
	<td>$k->jenis_mk</td>
	<td>$k->sks</td>
	<td>$k->semester</td>
	<td>$jurusan</td>
	</tr>";
}
?>
</tbody>
</table>
</body>
</html>
