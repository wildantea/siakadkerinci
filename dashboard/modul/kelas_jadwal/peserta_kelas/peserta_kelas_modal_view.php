<?php
session_start();
include "../../../inc/config.php";
$kelas_data = $db2->fetchCustomSingle("SELECT peserta_max as kuota,kelas_id,kls_nama,kode_mk,sem_id,nama_mk,sks as total_sks,kode_jur,jurusan as nama_jurusan,
(select count(id_krs_detail) from krs_detail where krs_detail.id_kelas=view_nama_kelas.kelas_id and disetujui='1') as approved_krs,
(select count(id_krs_detail) from krs_detail where krs_detail.id_kelas=view_nama_kelas.kelas_id and disetujui='0') as pending_krs from view_nama_kelas where kelas_id=?",array('kelas_id' => $_POST['kelas_id']));

?>
<link rel="stylesheet" type="text/css" href="<?=base_admin();?>assets/plugins/clockpicker/bootstrap-clockpicker.min.css">
<style type="text/css">
    .clockpicker-span-hours,.clockpicker-span-minutes {
    font-size: 24px;
  }

.modal.left .modal-dialog,
  .modal.right .modal-dialog {
    top: 0;
    bottom:0;
    position:fixed;
    overflow-y:scroll;
    overflow-x:hidden;
    margin: auto;
    -webkit-transform: translate3d(0%, 0, 0);
        -ms-transform: translate3d(0%, 0, 0);
         -o-transform: translate3d(0%, 0, 0);
            transform: translate3d(0%, 0, 0);
  }
/*Right*/
  .modal.right.fade .modal-dialog {
    right: -320px;
    -webkit-transition: opacity 0.3s linear, right 0.3s ease-out;
       -moz-transition: opacity 0.3s linear, right 0.3s ease-out;
         -o-transition: opacity 0.3s linear, right 0.3s ease-out;
            transition: opacity 0.3s linear, right 0.3s ease-out;
  }
  
  .modal.right.fade.in .modal-dialog {
    right: 0;
  }
</style>
<table id="tabelku" class="table table-bordered table-striped" cellspacing="0" width="100%">
        <tbody><tr>
          <td class="info2" width="20%"><strong>Program Studi</strong></td>
          <td width="30%"><?=$kelas_data->nama_jurusan;?></td>
          <td class="info2" width="20%"><strong>Periode</strong></td>
          <td><?=getPeriode($kelas_data->sem_id);?></td>
        </tr>
        <tr>
          <td class="info2"><strong>Matakuliah</strong></td>
          <td><?=$kelas_data->kode_mk;?> - <?=$kelas_data->nama_mk;?> (<?=$kelas_data->total_sks;?> sks) </td>
          <td class="info2"><strong>Kelas</strong></td>
          <td><?=$kelas_data->kls_nama ;?></td>
        </tr>
      </tbody></table>

 <div class="alert alert-danger error_data" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning"></span>
        </div>
          <!-- Custom Tabs -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">

               <li <?=($_POST['action']=='yes')?'class="active"':'';?>><a href="#tab_2" data-toggle="tab"><i class="fa fa-check"></i> KRS Disetujui <span class="approved-krs-count btn btn-xs btn-success"><?=$kelas_data->approved_krs;?></span></a></li>
              <li <?=($_POST['action']=='no')?'class="active"':'';?>><a href="#tab_3" data-toggle="tab"><i class="fa fa-clock-o"></i> KRS Belum Disetujui <span class="pending-krs-count btn btn-xs btn-warning"><?=$kelas_data->pending_krs;?></span></a></li>
              <li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-building"></i> Kuota <span class="kuota-krs-count btn btn-xs btn-success"><?=$kelas_data->kuota;?></span></a></li>
            </ul>
            <div class="tab-content">
                            <!-- /.tab-pane -->
              <div class="tab-pane <?=($_POST['action']=='yes')?'active':'';?> " id="tab_2">
                    <?php
                    include "tab_approved_krs.php";
                    ?>
              </div>
              <div class="tab-pane <?=($_POST['action']=='no')?'active':'';?> " id="tab_3">
            <?php
                    include "tab_pending_krs.php";
                    ?>
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- nav-tabs-custom -->

