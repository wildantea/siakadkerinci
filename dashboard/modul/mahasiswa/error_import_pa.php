<?php
session_start();
include "../../inc/config.php";
$error_import = $db->query("select * from temp_error_pa where id_import=?",array('id' => $_GET['id']));
?>
 <link href="<?=base_admin();?>assets/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />

<h4 style="float: left"><a  onclick="copyClipboard()" class="copyToClipboard" style="float: right;cursor: pointer;">Klik Untuk Copy Semua</a></h4>
<table  id="sumber" class="table table-bordered table-striped">
	<tr>
		<th colspan="3">Error Import Data</th>
	</th>
</tr>
<tr>
	<th>No</th>
	<th>NIM</th>
  <th>NIP</th>
	<th>Error Detail</th>
</tr>
<?php
$no = 1;
foreach ($error_import as $dt) {
	echo "<tr><td>".$no."</td><td>".$dt->nim."</td><td>$dt->nip</td><td>$dt->error_data</td></tr>";
$no++;
}
$db->query("delete from temp_error_pa where id_import=?",array('id' => $_GET['id']));
?>
</table>

<script type="text/javascript">

  
function copyClipboard() {
  var elm = document.getElementById("sumber");
  // for Internet Explorer

  if(document.body.createTextRange) {
    var range = document.body.createTextRange();
    range.moveToElementText(elm);
    range.select();
    document.execCommand("Copy");
alert("Data Berhasil Di copy, Anda bisa Paste di Excel untuk keperluan selanjutnya");
  }
  else if(window.getSelection) {
    // other browsers

    var selection = window.getSelection();
    var range = document.createRange();
    range.selectNodeContents(elm);
    selection.removeAllRanges();
    selection.addRange(range);
    document.execCommand("Copy");
    alert("Data Berhasil Di copy, Anda bisa Paste di Excel untuk keperluan selanjutnya");
  }
}
</script>