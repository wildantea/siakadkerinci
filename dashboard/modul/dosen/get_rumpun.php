<?php
session_start();
include "../../inc/config.php";
session_check();
echo '<option value="all">Semua</option>';
if ($_POST['prodi']=='all') {
$prodi = $db->query("select drd.kode,drd.nama_rumpun from data_rumpun_dosen drd 
where id_level='1' and drd.kode in(select dw.id_induk
from  data_rumpun_dosen dw 
inner join data_rumpun_dosen dwc on dw.kode=dwc.id_induk
where dw.id_level='2' and dwc.kode in(select kode_rumpun from dosen where kode_rumpun is not null group by kode_rumpun))");
                    foreach ($prodi as $pr) {
                      echo "<option value='$pr->kode'>$pr->nama_rumpun</option>";
                    }
} else {
	foreach ($db->query("select drd.kode,drd.nama_rumpun from data_rumpun_dosen drd 
where id_level='1' and drd.kode in(select dw.id_induk
from  data_rumpun_dosen dw 
inner join data_rumpun_dosen dwc on dw.kode=dwc.id_induk
where dw.id_level='2' and dwc.kode in(select kode_rumpun from dosen where kode_rumpun is not null and dosen.kode_jur=? group by kode_rumpun))",array('kode_jur' => $_POST['prodi'])) as $isi) {
echo "<option value='$isi->kode'>$isi->nama_rumpun</option>";
}

}

?>
