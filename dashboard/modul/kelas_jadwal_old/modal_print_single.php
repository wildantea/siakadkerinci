<?php
include "../../inc/config.php";
?>
      <form method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/kelas_jadwal/cetak_single.php" target="_blank">
<input type="hidden" name="kelas_id" value="<?=$_POST['kelas_id'];?>">
                      <div class="form-group">
                        <label for="Kabupaten" class="control-label col-lg-2">Jenis Cetak</label>
                        <div class="col-lg-10">
                                      <select name="jenis_print" id="kelas_cetak" data-placeholder="Pilih Jenis Cetak ..." class="form-control chzn-select" tabindex="2" method="post">
                                         <option value="kelas">Cetak Presensi Kelas</option>
                                         <option value="uts">Cetak Presensi UTS</option>
                                         <option value="uas">Cetak Presensi UAS</option>
                                        </select>
            </div>
                      </div><!-- /.form-group -->

              <div class="form-group">
                <div class="col-lg-12">
                  <div class="modal-footer"> 
                  <button type="submit" class="btn btn-primary"><i class="fa fa-print"></i> Cetak</button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Tutup</button>

                  </div>
                </div>
              </div><!-- /.form-group -->

      </form>