<?php
session_start();
include "../../../inc/config.php";
$kelas_id = $_POST['kelas_id'];
$kelas_data = $db2->fetchCustomSingle("SELECT kelas_id,kls_nama,id_matkul,kode_mk,sem_id,nama_mk,sks as total_sks,kode_jur,jurusan as nama_jurusan,
(select count(id_krs_detail) from krs_detail where disetujui='1' and id_kelas=view_nama_kelas.kelas_id) as approved_krs
#(select count(id_krs_detail) from krs_detail where disetujui='0' and id_kelas=view_nama_kelas.kelas_id) as pending_krs 
from view_nama_kelas where kelas_id=?",array('kelas_id' => $kelas_id));

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

  .nav-tabs-custom>.nav-tabs {
    border-bottom-color: #3c8dbc;
  }
.nav-tabs-custom>.nav-tabs>li.active>a {
    border-left-color: #3c8dbc;
    border-right-color: #3c8dbc;
  }
.label {
  font-size: 100%;
  margin-left: 5px;
}
.nav-tabs-custom>.nav-tabs>li {
  margin-bottom: -1px;
    margin-right: 0px;
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

        <div class="row">
          <div class="col-xs-12">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs" id="myTab">
            <li class="active">
              <a href="#tab_4"  data-toggle="tab"><i class="fa fa-book"></i> Detail Perkuliahan</a>
            </li>
            <li>
              <a href="#tab_3" data-toggle="tab"><i class="fa fa-users"></i> Teman Kelas <span class="approved-krs-count label label-primary pull-right"><?=$kelas_data->approved_krs;?></span></a>
            </li>
            </ul>
            <div class="tab-content">
                 <div class="tab-pane active" id="tab_4">
            <?php
              include "rps/tab_rps.php";
                    ?>
              </div>
              <div class="tab-pane" id="tab_3">
            <?php
                    include "peserta/tab_approved_krs.php";
                    ?>
              </div>
             
              <!-- /.tab-pane -->
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          </div>
        </div>             

          <div class="modal" id="modal_pertemuan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="glyphicon glyphicon-remove"></i></span></button> <h4 class="modal-title"><?php echo $lang["add_button"];?> Pertemuan</h4> </div> <div class="modal-body" id="isi_pertemuan"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>

        

    <script>
            $(document).ready(function() {
    // Do this before you initialize any of your modals
$.fn.modal.Constructor.prototype.enforceFocus = function() {};

$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        $($.fn.dataTable.tables(true)).DataTable()
           .columns.adjust()
           .responsive.recalc();
    });    
    
});
    </script>