<?php
session_start();
include "../../inc/config.php";
session_check_json();
$nim=$_POST['nim'];
$q=$db->query("SELECT js.jns_semester,js.nm_singkat,s.id_semester,s.tahun FROM keu_tagihan_mahasiswa m
JOIN semester_ref s ON s.id_semester=m.periode
JOIN jenis_semester js ON js.id_jns_semester=s.id_jns_semester
 WHERE m.nim='$nim' GROUP BY m.periode");
echo '<table class="table table-striped table-bordered">
  <thead>
  <tr>
    <th style="width:50px"></th>
    <th style="width:150px;text-align:center">Jenis Tagihan</th>
    <th style="width:100px;text-align:center">Jml Tagihan</th>
    <th style="width:100px;text-align:center">Sudah Dibayarkan</th>
    <th style="width:120px;text-align:center">Sisa</th>
    <th style="width:120px;text-align:center">Jumlah Bayar</th>';
    /*<th width=10% style="text-align:center">Boleh KRS</th>*/
    echo '<th width=10% style="text-align:center">Detil Cicilan</th>
  </tr>
  </thead>
  <tbody id="isi_body">';
  $jml_tagihan_all=0;
  $jml_sudah=0;
  $jml_sisa=0;
foreach ($q as $k) {
	  echo "<tr>
	         <td colspan='7'>$k->jns_semester $k->tahun</td>
	        </tr>";
	  $det_tag = $db->query("SELECT jt.syarat_krs, t.id, m.nama, m.nim, jt.nama_tagihan,ta.nominal_tagihan FROM keu_tagihan_mahasiswa t 
							JOIN mahasiswa m ON m.nim=t.nim
							JOIN keu_tagihan ta ON ta.id=t.id_tagihan_prodi
							JOIN keu_jenis_tagihan jt ON jt.kode_tagihan=ta.kode_tagihan
							WHERE t.nim='$nim' AND t.periode='$k->id_semester'");
	  foreach ($det_tag as $d) {
	  	if ($d->syarat_krs=='Y') {
	  		$qtag= $db->query("SELECT * FROM keu_bayar_mahasiswa m WHERE m.id_keu_tagihan_mhs='$d->id' ");
	  		$afirmasi = 0;
            foreach ($qtag as $ktag) {
            	$afirmasi=$ktag->afirmasi;
            }
		  	 if ($qtag->rowCount()>0 && $afirmasi=='1') {
		  	 	$btn_boleh_krs="<input name='krs_boleh-$d->id' id='krs_boleh-$d->id' onchange='afirmasi_krs($d->id)' class='make-switch' type='checkbox' checked>";
		  	 }else{
                  $btn_boleh_krs="<input name='krs_boleh-$d->id' id='krs_boleh-$d->id' onchange='afirmasi_krs($d->id)' class='make-switch' type='checkbox'>";
		  	 }
	  		
	  	}else{
	  		$btn_boleh_krs="";
	  	}
	  	$qj=$db->query("SELECT SUM(c.jml_bayar) AS jml FROM keu_cicilan c WHERE c.id_tagihan_mhs='$d->id'");
	  	$jml_dibayarkan=0;
	  	foreach ($qj as $kj) {
	  		$jml_dibayarkan=$kj->jml;
	  	}
	  	 $jml_tagihan_all=$jml_tagihan_all+$d->nominal_tagihan;
	          $jml_sudah=$jml_sudah+$jml_dibayarkan;
	          $jml_sisa=$jml_sisa+($d->nominal_tagihan-$jml_dibayarkan);
	    $style=" style='background:#bd2222;color:white'";
	    $disable_check="  ";
	    if (($d->nominal_tagihan-$jml_dibayarkan)<=0) {
	    	$style = " style='background:#b8ead3;'";
	    	$disable_check = " disabled";
	    }
	    echo "<tr $style>
	            <td><label class='mt-checkbox mt-checkbox-single mt-checkbox-outline '> 
	                 <input type='checkbox'  name='cek-$d->id' class='group-checkable bulk-check-sesi' id='cek-$d->id' onclick='set_bayar($d->id,\"".($d->nominal_tagihan-$jml_dibayarkan)."\")'> 
	                 <span></span></label>
	            </td>
	            <td>$d->nama_tagihan</td>
	            <td style='text-align:right'><span style='float:left'>Rp.</span> <span style='float:right'>".number_format($d->nominal_tagihan)."</span></td>
	            <td style='text-align:right'><span style='float:left'>Rp.</span> <span style='float:right'>".number_format($jml_dibayarkan)."</span></td>
	            <td style='text-align:right'><span style='float:left'>Rp.</span> <span style='float:right'>".number_format(($d->nominal_tagihan-$jml_dibayarkan))."</span></td>
	            <td style='text-align:right' id='form-$d->id'></td>";
	           /* <td>
	                $btn_boleh_krs
	            </td>*/
	            echo "<td>
	              <a style='cursor:pointer' class='btn btn-success' onclick='showDetilCicilan($d->id)'><i class='fa fa-eye'></i><span id='btn-history-$d->id'> History Cicilan </span></a>
	            </td>
	          </tr>";
	         
	  }
	 
}

echo "<tr>
        <td colspan='2'>Jumlah Tagihan</td>
        <td style='text-align:right'><span style='float:left'>Rp.</span> <span style='float:right'>".number_format($jml_tagihan_all)."</span></td>
        <td style='text-align:right'><span style='float:left'>Rp.</span> <span style='float:right'>".number_format($jml_sudah)."</span></td>
        <td style='text-align:right;background:#bd2222;color:white' ><b><span style='float:left'>Rp.</span><span style='float:right'>".number_format($jml_sisa)."</span></b></td>
        <td></td>
       </tr>
      </tbody>
</table>
<i style='color:blue'>* Silahkan centang item yang akan di bayarkan dan input nominal yang akan dibayarkan</i>";

?>