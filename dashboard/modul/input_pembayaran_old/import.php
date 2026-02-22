<?php
session_start();
//import page
include "../../inc/config.php";
?>
        <div class="alert isi_informasi" style="display:none;margin-bottom: 0px;">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          
        </div>
             <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
                                <div class="box-header">
                            <div class="box-body table-responsive">
<div class="box-header with-border">
                <h3 class="box-title">Filter</h3>
              </div>
            <div class="box-body">
              <div class="row">
                <form class="form-horizontal" id="form_lunaskan" method="post" action="<?=base_admin();?>modul/setting_tagihan_mahasiswa/download_data.php">
                <div class="form-group">
                  <label for="Semester" class="control-label col-lg-2">Program Studi</label>
                  <div class="col-lg-5">
                    <select id="kode_prodi" name="kode_prodi" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2">
                     <?php
                     looping_prodi();
                     ?>
                   </select>

                 </div>
               </div><!-- /.form-group -->
               <div class="form-group">
                <label for="Semester" class="control-label col-lg-2">Periode Tagihan</label>
                <div class="col-lg-5">
                  <select id="berlaku_angkatan" name="periode" data-placeholder="Pilih Jenjang ..." class="form-control chzn-select" tabindex="2">
                    <option value="all">Semua</option>
                    <?php 
                    $angkatan_exist = $db->query("select tahun_akademik,periode from keu_tagihan_mahasiswa inner join view_semester 
                      on keu_tagihan_mahasiswa.periode=view_semester.id_semester
                      group by keu_tagihan_mahasiswa.periode
                      order by periode desc");

                    foreach ($angkatan_exist as $ak) {
                      if (get_sem_aktif()==$ak->periode) {
                        echo "<option value='$ak->periode' selected>$ak->tahun_akademik</option>";
                      } else {
                        echo "<option value='$ak->periode'>$ak->tahun_akademik</option>";
                      }

                    } ?>
                  </select>

                </div>
              </div><!-- /.form-group -->
              <div class="form-group">
                <label for="Semester" class="control-label col-lg-2">Jenis Pembayaran</label>
                <div class="col-lg-5">
                  <select id="kode_pembayaran" name="kode_pembayaran" data-placeholder="Pilih Jenjang ..." class="form-control chzn-select" tabindex="2">
                    <option value="all">Semua</option>
                    <?php 
                    $angkatan_exist = $db->query("select kjp.kode_pembayaran,kjp.nama_pembayaran from keu_jenis_pembayaran kjp
                      inner join keu_jenis_tagihan kjt on kjp.kode_pembayaran=kjt.kode_pembayaran
                      inner join keu_tagihan kt on kjt.kode_tagihan=kt.kode_tagihan
                      inner join keu_tagihan_mahasiswa ktm on kt.id=ktm.id_tagihan_prodi
                      group by kjp.kode_pembayaran");

                    foreach ($angkatan_exist as $ak) {
                      echo "<option value='$ak->kode_pembayaran'>$ak->nama_pembayaran</option>";
                    } ?>
                  </select>

                </div>
              </div><!-- /.form-group -->

              <div class="form-group">
                <label for="Semester" class="control-label col-lg-2">Jenis Tagihan</label>
                <div class="col-lg-5">
                  <select id="kode_tagihan" name="kode_tagihan" data-placeholder="Pilih Jenjang ..." class="form-control chzn-select" tabindex="2">
                    <option value="all">Semua</option>
                    <?php 
                    $angkatan_exist = $db->query("select kt.kode_tagihan,kj.nama_tagihan from keu_jenis_tagihan kj 
                      inner join keu_tagihan kt on kj.kode_tagihan=kt.kode_tagihan
                      inner join keu_tagihan_mahasiswa ktm on kt.id=ktm.id_tagihan_prodi
                      group by kj.kode_tagihan");

                    foreach ($angkatan_exist as $ak) {
                      echo "<option value='$ak->kode_tagihan'>$ak->nama_tagihan</option>";
                    } ?>
                  </select>

                </div>
              </div><!-- /.form-group -->
                 <div class="form-group">
                <label for="Semester" class="control-label col-lg-2">Bank</label>
                <div class="col-lg-5">
                  <select id="kode_bank" name="kode_bank" data-placeholder="Pilih Jenjang ..." class="form-control chzn-select" tabindex="2">
                    <option value="all">Semua</option>
                    <?php 
                    $angkatan_exist = $db->query("select * from keu_bank");

                    foreach ($angkatan_exist as $ak) {
                      echo "<option value='$ak->kode_bank'>$ak->nama_bank</option>";
                    } ?>
                  </select>

                </div>
              </div><!-- /.form-group -->

              <div class="form-group">
                <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                <div class="col-lg-10">
                  <span id="filter" class="btn btn-success"><i class="fa fa-money"></i> Lunaskan</span>
                </div><!-- /.form-group -->
              </div>
            </form>
              </div>
          </div>
 <div class="alert alert-warning fade in error_data_delete" style="display:none">
          <button type="button" class="close hide_alert_notif">&times;</button>
          <i class="icon fa fa-warning"></i> <span class="isi_warning_delete"></span>
        </div>
                        <table id="dtb_setting_tagihan_mahasiswa" class="table table-bordered table-striped" width="100%">
                            <thead>
                                <tr>
                                  <th>No</th>
                                  <th>NIM</th>
                                  <th>Nama</th>
                                  <th>Jenis Tagihan</th>
                                  <th>Jumlah Tagihan</th>
                                  <th>Periode Tagihan</th>
                                  <th>Prodi</th>
                                  <th>Status Lunas</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->
                  </div><!-- /.box -->
                </div>
              </div>

    <div class="modal" id="modal_setting_tagihan_mahasiswa" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> <h4 class="modal-title"><?php echo $lang["add_button"];?> Setting Tagihan Mahasiswa</h4> </div> <div class="modal-body" id="isi_setting_tagihan_mahasiswa"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
    <div class="modal" id="modal_import_mat" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&nbsp;</span></button> <h4 class="modal-title">Import Excel Tagihan Mahasiswa</h4> </div> <div class="modal-body" id="isi_import_mat"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>  
    </section><!-- /.content -->

        <script type="text/javascript">
             //chosen select
          $(".chzn-select").chosen();
          $(".chzn-select-deselect").chosen({
              allow_single_deselect: true
          });
        
    
    
      //trigger validation onchange
      $('select').on('change', function() {
          $(this).valid();
      });
      //hidden validate because we use chosen select
      $.validator.setDefaults({ ignore: ":hidden:not(select)" });   
    $(".table").on('click','.edit_data',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);

        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/setting_tagihan_mahasiswa/setting_tagihan_mahasiswa_edit.php",
            type : "post",
            data : {id_data:id},
            success: function(data) {
                $("#isi_setting_tagihan_mahasiswa").html(data);
                $("#loadnya").hide();
          }
        });

    $('#modal_setting_tagihan_mahasiswa').modal({ keyboard: false,backdrop:'static' });

    });
      var dtb_setting_tagihan_mahasiswa = $("#dtb_setting_tagihan_mahasiswa").DataTable({
           'bProcessing': true,
            'bServerSide': true,
            'order': [[1, 'desc']],
           'columnDefs': [ {
            'targets': [7],
              'orderable': false,
              'searchable': false
            },
                {
            'width': '5%',
            'targets': 0,
            'orderable': false,
            'searchable': false,
            'className': 'dt-center'
          }
             ],

    
            'ajax':{
              url :'<?=base_admin();?>modul/input_pembayaran/input_pembayaran_data.php',
            type: 'post',  // method  , by default get
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });
$('#filter').on('click', function() {
dtb_setting_tagihan_mahasiswa = $("#dtb_setting_tagihan_mahasiswa").DataTable({
              destroy : true,
           'bProcessing': true,
            'bServerSide': true,
            'order': [[1, 'desc']],
           'columnDefs': [ {
            'targets': [7],
              'orderable': false,
              'searchable': false
            },
                {
            'width': '5%',
            'targets': 0,
            'orderable': false,
            'searchable': false,
            'className': 'dt-center'
          }
             ],

    
            'ajax':{
               url :'<?=base_admin();?>modul/input_pembayaran/input_pembayaran_data.php',
            type: 'post',  // method  , by default get
            data: function ( d ) {
              d.kode_prodi = $("#kode_prodi").val();
              d.periode = $("#berlaku_angkatan").val();
              d.kode_tagihan = $("#kode_tagihan").val();
             d.kode_pembayaran = $("#kode_pembayaran").val();
             d.kode_bank = $("#kode_bank").val();
            },
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          }
      });
});
/*$(document).ready(function(){
  $("#form_lunaskan").submit(function(){
    alert("test");
    return false;
  });
});*/
</script>
            