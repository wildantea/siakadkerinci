<?php
session_start();
include "../../../../inc/config.php";
//check if pertemuan exist
$kelas_id = $_POST['kelas_id'];

$data_materi = $db2->fetchSingleRow("rps_materi_kuliah","id_materi",$_POST['pertemuan']);

?>
<style type="text/css">
  #presensi > tbody > tr > td, .table > tfoot > tr > td {
    vertical-align: middle;
    padding:2px;
  }
 .help-block {
    color: #dd4b39;
}
.mt-checkbox {
  margin-bottom:0
}
#presensi.dataTable {
  border-color: #9e9595;
}
</style>

     <div class="alert alert-danger error_data" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning"></span>
        </div>
          
            <form id="input_kelas_jadwal" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/kelas/kelas_action.php?act=input_materi">
          <div class="form-group">
              
              <div class="col-lg-11">
              <label for="Catatan" class="control-label">Materi</label>
                <textarea class="form-control" rows="5" name="rencana_materi" readonly><?=$data_materi->materi;?></textarea>
              </div>
          </div>
        

          <div class="form-group">
              
              <div class="col-lg-11">
              <label for="Catatan" class="control-label">Link Materi </label>
                <a style="height:52px" href="<?=$data_materi->link_materi;?>" target="_blank" class="form-control" name="link_materi"><?=$data_materi->link_materi;?></a>
              </div>
          </div>

          <input type="hidden" name="id_pertemuan" value="<?=$_POST['pertemuan'];?>">
      
              <div class="form-group">
                <label for="tags" class="control-label col-lg-5">&nbsp;</label>
                <div class="col-lg-7">
             <a class="btn btn-default " data-dismiss="modal"><i class="fa fa-times"></i> Close</a>
           
                </div>
              </div><!-- /.form-group -->

            </form>
