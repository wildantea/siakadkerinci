<?php
session_start();
include "../../../../inc/config.php";
session_check();
?>
                     <div class="row">
        <div class="col-xs-12">
        	<fieldset class="fieldset-wrapper">
                  <legend  style="width: 79px;">Ruangan</legend>

<?php
foreach ($db->query("select * from view_ruang_prodi
where is_aktif='Y' and gedung_id=? and kode_jur=?",array('gedung_id' => $_POST['gedung_id'],'kode_jur' => $_POST['kode_jur'])) as $isi) {
	?>
  <div class="btn-group" data-toggle="buttons">
                     <label class="btn btn-default ruang-button">
                    <input type="radio" class="ruang_id" name="ruang_id" data-msg-required="Silakan Pilih Ruang" value="<?=$isi->ruang_id;?>" required> <?=$isi->nm_ruang;?>
                </label>
              </div>
<?php
} 
?>
</fieldset>
 </div>
        </div>