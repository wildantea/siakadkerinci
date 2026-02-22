<?php
session_start();
include "../../inc/config.php";
session_check_json();
$kode_jurusan = implode(",", $_POST['program_studi']);

$array_reponse = array(
'kode_jur' => $_POST['program_studi']
);
foreach ($db2->query("select kode_jur,nama_jurusan from view_prodi_jenjang where kode_jur in($kode_jurusan)") as $jurusan) {

?>
          <div class="form-group" id="form_select_<?=$jurusan->kode_jur;?>">
              <label for="Ada Jadwal" class="control-label col-lg-1">&nbsp; </label>
              <label for="Ada Jadwal" class="control-label col-lg-2"><?=$jurusan->nama_jurusan;?></label>
              <div class="col-lg-8">
             <select  id="select_matkul_<?=$jurusan->kode_jur;?>" name="syarat_matkul[<?=$jurusan->kode_jur;?>][]" data-placeholder="Pilih Matakuliah ..." class="form-control select2" tabindex="2" required="" multiple>
              </select>
            </div>
          </div><!-- /.form-group -->
<script type="text/javascript">
$(document).ready(function () {
	$("#select_matkul_<?=$jurusan->kode_jur;?>").select2({
	  ajax: {
	    url: '<?=base_admin();?>modul/pengaturan_pendaftaran/get_matkul.php',
	    dataType: 'json',
	    type : 'post',
	    data: function (params) {
	      var query = {
	        q: params.term,
	        kode_jur: '<?=$jurusan->kode_jur;?>'
	      }

	      // Query parameters will be ?search=[term]&type=public
	      return query;
	    }
	    // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
	  },
	  formatInputTooShort: "Pilih Matakuliah",
	  //allowClear: true,
	  width: "100%",
	});
      //trigger validation onchange
      $('select').on('change', function() {
          $(this).valid();
      });
});
</script>
<?php
}
?>