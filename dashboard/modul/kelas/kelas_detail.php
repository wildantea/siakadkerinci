<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>&nbsp;</h1>
                   <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>kelas">Kelas</a></li>
                        <li class="active">Detail Kelas Kuliah</li>
                    </ol>
                </section>


    <!-- Main content -->
                <section class="content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box box-solid box-primary">
                            <div class="box-header">
                            <h3 class="box-title">Detail Kelas Kuliah</h3>
                            </div>

                    <div class="box-body">
                 <a href="<?=base_index_new();?>kelas" class="btn btn-primary" id="btn-simpan"><i class="fa fa-backward"></i> Kembali</a><p>      
<?php
$kelas_id = uri_segment(3);
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
.nav-tabs-custom {
    border: 1px solid #3c8cbc;
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
              <a href="#pengajar" data-toggle="tab"><i class="fa fa-eye"></i> Detail Kelas Perkuliahan</a>
            </li>
           
            <li>
              <a href="#presensi" data-toggle="tab"><i class="fa fa-check"></i> Presensi & Materi <span class="approved-krs-count label label-primary pull-right"></span></a>
            </li>
            <li>
              <a href="#peserta" data-toggle="tab"><i class="fa fa-users"></i> Peserta Kelas <span class="approved-krs-count label label-primary pull-right"><?=$kelas_data->approved_krs;?></span></a>
            </li>
            </ul>
            <div class="tab-content">
                            <!-- /.tab-pane -->
              <div class="tab-pane active" id="pengajar">
                    <?php
                    include "pengajar/tab_pengajar.php";
                    ?>
              </div>
                            <!-- /.tab-pane -->
              <div class="tab-pane" id="presensi">
                    <?php
                    include "presensi/tab_presensi.php";
                    ?>
              </div>
              <div class="tab-pane" id="peserta">
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

  <div class="modal" id="modal_input_absen" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog"> <div class="modal-content" style="height: auto;"><div class="modal-header"> <button type="button" class="close close-absensi" aria-label="Close"><span aria-hidden="true"><i class="glyphicon glyphicon-remove"></i></span></button> <h4 class="modal-title-absen">Isi Presensi Mahasiswa</h4> </div> <div class="modal-body" id="input_mahasiswa_absen" style="overflow-y: auto;"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
  <div class="modal" id="modal_photo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="glyphicon glyphicon-remove"></i></span></button> <h4 class="modal-title">Thumbnail</h4> </div> <div class="modal-body" id="isi_photo" style="text-align: center;"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
   

          <div class="modal" id="modal_pertemuan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="glyphicon glyphicon-remove"></i></span></button> <h4 class="modal-title"><?php echo $lang["add_button"];?> Pertemuan</h4> </div> <div class="modal-body" id="isi_pertemuan"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
<style type="text/css">
  .modal-abs,.modal-paper {
  width: 98%;
  padding: 0;
}

.modal-content-abs,.modal-content-paper {
  height: 99%;
}
</style>
        

    <script>

        $(document).ready(() => {
  let url = location.href.replace(/\/$/, "");
 
  if (location.hash) {
    const hash = url.split("#");
    $('#myTab a[href="#'+hash[1]+'"]').tab("show");
    url = location.href.replace(/\/#/, "#");
    history.replaceState(null, null, url);
    setTimeout(() => {
      $(window).scrollTop(0);
    }, 400);
  } 
   
  $('a[data-toggle="tab"]').on("click", function() {
    let newUrl;
    const hash = $(this).attr("href");
    if(hash == "#pengajar") {
      newUrl = url.split("#")[0];
    } else {
      newUrl = url.split("#")[0] + hash;
    }
    newUrl += "/";
    history.replaceState(null, null, newUrl);
  });
});  

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

                        </div>
                      </div>
                    </div>
                </div>
                
                </section><!-- /.content -->