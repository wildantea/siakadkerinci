<style type="text/css">
    .modal-dialog {
  width: 60%;
  min-height: 40%;
  margin: auto auto;
  padding: 0;
}

.modal-content {
  height: auto;
  min-height: 90%;
  border-radius: 0;
}
</style>
<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Laporan Pembayaran Mahasiswa
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>laporan-pembayaran">Laporan Pembayaran</a></li>
                        <li class="active">Laporan Pembayaran Mahasiswa List</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
                            <div class="box-body table-responsive">
<div class="box-header with-border">
                <h3 class="box-title">Filter</h3>
              </div>
            <div class="box-body">
              <div class="row">
                <form class="form-horizontal" method="post" action="<?=base_admin();?>modul/laporan_pembayaran/download_data.php">
 <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Periode Pembayaran</label>
                        <div class="col-lg-5">
                        <select id="periode" name="periode" data-placeholder="Pilih Periode Pembayaran ..." class="form-control chzn-select" tabindex="2">
                        <option value="all">Semua</option>
<?php 
 $angkatan_exist = $db->query("select periode_bayar,is_active from periode_pembayaran where 
periode_bayar in (select distinct(periode) from keu_tagihan_mahasiswa) order by periode_bayar  desc");

foreach ($angkatan_exist as $ak) {
  if ($ak->is_active=='Y') {
    echo "<option value='$ak->periode_bayar' selected>".ganjil_genap($ak->periode_bayar)."</option>";
  } else {
    echo "<option value='$ak->periode_bayar'>".ganjil_genap($ak->periode_bayar)."</option>";
  }
  
} ?>
              </select>

 </div>
                      </div><!-- /.form-group -->
          <div class="form-group">
                                <label for="Semester" class="control-label col-lg-2">Jenjang</label>
                                <div class="col-lg-5">
                                <select id="jenjang_filter" name="jenjang" data-placeholder="Pilih Fakultas ..." class="form-control chzn-select2" tabindex="2" required="">
                                <?php
                                loopingJenjang('filter_data_laporan_pembayaran');
                                ?>
                      </select>
                    </div>
                              </div>
                              <!-- /.form-group -->

            <?php
            if (hasFakultas()) {
              ?>
          <div class="form-group">
                                <label for="Semester" class="control-label col-lg-2">Fakultas</label>
                                <div class="col-lg-5">
                                <select id="fakultas_filter" name="fakultas" data-placeholder="Pilih Fakultas ..." class="form-control chzn-select2" tabindex="2" required="">
                                <?php
                                loopingFakultas('filter_data_laporan_pembayaran');
                                ?>
                      </select>
                    </div>
                              </div><!-- /.form-group -->
            <?php
            }
            ?>
  <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Program Studi</label>
                        <div class="col-lg-5">
                        <select id="kode_prodi" name="kode_prodi" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2">
                                <?php
                                loopingProdi('filter_data_laporan_pembayaran',getFilter(array('filter_data_laporan_pembayaran' => 'fakultas')));
                                ?>
              </select>

 </div>
                      </div><!-- /.form-group -->
              <div class="form-group">
                    <label for="Angkatan" class="control-label col-lg-2">Angkatan</label>
                    <div class="col-lg-2">
                    <select id="mulai_smt" name="mulai_smt" data-placeholder="Pilih Angkatan ..." class="form-control chzn-select" tabindex="2" required="">
                      <option value="all">Semua</option>
                    <?php
                   $angkatan = $db->query("select left(id_semester,4) as angkatan,id_semester from semester_ref where id_semester in(select distinct(berlaku_angkatan) from keu_tagihan) group by left(id_semester,4) order by id_semester desc");
                   foreach ($angkatan as $ak) {
                     echo "<option value='$ak->angkatan'>$ak->angkatan</option>";
                   }
                    ?>
                    </select>
                    </div>
                    <label for="Angkatan" class="control-label col-lg-1" style="width: 3%;padding-left: 0;text-align: left;">S/d</label>
                    <div class="col-lg-2">
                    <select id="mulai_smt_end" name="mulai_smt_end" data-placeholder="Pilih Angkatan ..." class="form-control chzn-select" tabindex="2" required="">
                      <option value="all">Semua</option>
                    <?php
                   $angkatan = $db->query("select left(id_semester,4) as angkatan,id_semester from semester_ref where id_semester in(select distinct(berlaku_angkatan) from keu_tagihan) group by left(id_semester,4) order by id_semester desc");
                   foreach ($angkatan as $ak) {
                     echo "<option value='$ak->angkatan'>$ak->angkatan</option>";
                   }
                    ?>
                    </select>
                    </div>  
              </div><!-- /.form-group -->

  <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Jenis Tagihan</label>
                        <div class="col-lg-5">
                        <select id="kode_tagihan" name="kode_tagihan" data-placeholder="Pilih Jenjang ..." class="form-control chzn-select2" tabindex="2">
                        <option value="all">Semua</option>
                                                      <?php 
 $angkatan_exist = $db->query("select kt.kode_tagihan,kj.nama_tagihan from keu_jenis_tagihan kj 
inner join keu_tagihan kt on kj.kode_tagihan=kt.kode_tagihan
inner join keu_tagihan_mahasiswa ktm on kt.id=ktm.id_tagihan_prodi
inner join keu_bayar_mahasiswa kbm on ktm.id=kbm.id_keu_tagihan_mhs
group by kj.kode_tagihan");

foreach ($angkatan_exist as $ak) {
  echo "<option value='$ak->kode_tagihan'>$ak->nama_tagihan</option>";
} ?>
              </select>

 </div>
                      </div><!-- /.form-group -->
  <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">BANK</label>
                        <div class="col-lg-5">
                        <select id="id_bank" name="id_bank" data-placeholder="Pilih BANK ..." class="form-control chzn-select2" tabindex="2">
                        <option value="all">Semua</option>
                                                      <?php 
 $angkatan_exist = $db->query("select keu_bank.kode_bank,keu_bank.nama_bank from keu_bank");

foreach ($angkatan_exist as $ak) {
  echo "<option value='$ak->kode_bank'>$ak->nama_bank</option>";
} ?>
 <option value="cash">CASH</option>
              </select>
            </div>
                      </div><!-- /.form-group -->
 <div class="form-group">
  <label for="Semester" class="control-label col-lg-2">Range Tanggal Pembayaran</label>
   <div class="col-lg-4">
        <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-clock-o"></i>
                  </div>
                  <input type="text" class="form-control pull-right" id="range_bayar" name="tgl_bayar" autocomplete="off">
                </div>
 </div>
 </div>
                      <div class="form-group">
                        <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                        <div class="col-lg-10">
                          <span id="filter" class="btn btn-primary"><i class="fa fa-refresh"></i> Filter</span>
                        <button type="submit" class="btn btn-success"><i class="fa fa-cloud-download"></i> Download</button>
                      </div><!-- /.form-group -->
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
                                  <th style="padding-right: 0;width: 5%">No</th>
                                  <th>No Pembayaran</th>
                                  <th>NIM</th>
                                  <th>Nama</th>
                                  <th>Angkatan</th>
                                   <th>Prodi</th>
                                   <th>Tagihan</th>
                                  <th>Periode Bayar</th>
                                  <th>Tanggal Bayar</th>
                                 <th>Validasi</th>
                                  <th>Nominal</th>
                                  <th>Bank</th>
                                  <th>Act</th>
                                </tr>

                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <th colspan="10" style="text-align:right">TOTAL BAYAR Rp. </th>
                                <th colspan="3" class="total_bayar" style="font-weight: bold;"></th>
                            </tfoot>
                        </table>
                    </div><!-- /.box-body -->
                  </div><!-- /.box -->
                </div>
              </div>
 </section><!-- /.content -->
  <div class="modal fade" id="modalDetilCicilan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                 <button type="button" class="close" style="opacity: 5" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><span class="btn btn-danger btn-xs"><i class="fa fa-close"></i></span></span></button>
                 <h4 class="modal-title" id="myModalLabel">Detail Pembayaran</h4>
               </div>
               <div class="modal-body" id="detil_cicilan">
                ...
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
        <script type="text/javascript">
$("#fakultas_filter").change(function(){
    $.ajax({
    type : "post",
    url : "<?=base_admin();?>modul/get_data_umum/get_prodi.php",
    data : {id_fakultas:this.value},
    success : function(data) {
        $("#kode_prodi").html(data);
        $("#kode_prodi").trigger("chosen:updated");

        }
    });
    //periode load
/*    $.ajax({
    type : "post",
    url : "<?=base_admin();?>modul/setting_tagihan_mahasiswa/get_periode_tagihan.php",
    data : {id_fakultas:this.value},
    success : function(data) {
        $("#berlaku_angkatan").html(data);
        $("#berlaku_angkatan").trigger("chosen:updated");

        }
    });*/
});
dtb_setting_tagihan_mahasiswa = $("#dtb_setting_tagihan_mahasiswa").DataTable({
              destroy : true,
           'bProcessing': true,
            'bServerSide': true,
            'order': [[1, 'desc']],
             "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
           'columnDefs': [
                {
            'width': '5%',
            'targets': 0,
            'orderable': false,
            'searchable': false,
            'className': 'dt-center'
          }
             ],

    
            'ajax':{
               url :'<?=base_admin();?>modul/laporan_pembayaran/laporan_pembayaran_data.php',
            type: 'post',  // method  , by default get
            data: function ( d ) {
              d.kode_prodi = $("#kode_prodi").val();
              d.fakultas = $("#fakultas_filter").val();
              d.mulai_smt = $("#mulai_smt").val();
              d.mulai_smt_end = $("#mulai_smt_end").val();
              d.periode = $("#periode").val();
              d.kode_pembayaran = $("#kode_pembayaran").val();
              d.kode_tagihan = $("#kode_tagihan").val();              
              d.id_bank = $("#id_bank").val();
              d.tgl_bayar = $("#range_bayar").val();
            },
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
          "fnDrawCallback": function() {
        var api = this.api()
        var json = api.ajax.json();
        console.log(json);
         $('.total_bayar').html(json.total_bayar);
    }

      });
//filter
$('#filter').on('click', function() {
  dtb_setting_tagihan_mahasiswa.ajax.reload();
});
$(document).ready(function(){
$('#range_bayar').daterangepicker({timePicker: false, timePickerIncrement: 30, format: 'YYYY-MM-DD'});
});
  function showDetilCicilan(id){
       //$("#btn-history-"+id).html("Loading...");
       $.ajax({
              url : "<?=base_admin();?>modul/history_bayar/detail_bayar.php",
              type : "POST",
              data : "id_kwitansi="+id,
              success: function(data) {
                 //$("#btn-history-"+id).html(" History Cicilan ");
                $("#detil_cicilan").html(data);
                $('#modalDetilCicilan').modal('toggle');
                $('#modalDetilCicilan').modal('show');
              }
          });
    
  }


</script>
            