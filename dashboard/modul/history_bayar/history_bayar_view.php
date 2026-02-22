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
                        History Bayar
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>history-bayar">History Bayar</a></li>
                        <li class="active">History Bayar List</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
<div class="box-header with-border">
                <h3 class="box-title">Filter</h3>
              </div>
            <div class="box-body">
              <div class="row">
                 <form class="form-horizontal" method="post" action="<?=base_admin();?>modul/history_bayar/download_data.php">
                    <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Fakultas</label>
                        <div class="col-lg-5">
                        <select id="fakultas_filter" name="fakultas" data-placeholder="Pilih Fakultas ..." class="form-control chzn-select" tabindex="2">
 <?php
                                loopingFakultas();
                                ?>
              </select>

 </div>
                      </div><!-- /.form-group -->
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
                        <label for="Semester" class="control-label col-lg-2">Angkatan Mahasiswa</label>
                        <div class="col-lg-5">
                        <select id="mulai_smt" name="mulai_smt" data-placeholder="Pilih Angkatan ..." class="form-control chzn-select" tabindex="2">
                        <option value="all">Semua</option>
 <?php
 $angkatan_exist = $db->query("select mulai_smt from mahasiswa where nim in(select nim_mahasiswa from keu_kwitansi)
group by mulai_smt
order by mulai_smt desc
");

foreach ($angkatan_exist as $ak) {
    echo "<option value='$ak->mulai_smt'>".getAngkatan($ak->mulai_smt)."</option>";
  
}

/*                               foreach ($db->query("select vs.mulai_smt, vs.angkatan from view_simple_mhs_data vs
inner join keu_tagihan_mahasiswa ktm on vs.nim=ktm.nim
inner join keu_bayar_mahasiswa kbm on ktm.id=kbm.id_keu_tagihan_mhs
group by vs.angkatan") as $dt) {
                                 echo "<option value='$dt->mulai_smt'>$dt->angkatan</option>";
                               }*/
                                ?>
              </select>

 </div>
                      </div><!-- /.form-group -->

  <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">BANK</label>
                        <div class="col-lg-5">
                        <select id="id_bank" name="id_bank" data-placeholder="Pilih BANK ..." class="form-control chzn-select" tabindex="2">
                        <option value="all">Semua</option>
                                                      <?php 
 $angkatan_exist = $db->query("select keu_bank.kode_bank,keu_bank.nama_bank from keu_bank");

foreach ($angkatan_exist as $ak) {
  echo "<option value='$ak->kode_bank'>$ak->nama_bank</option>";
} ?>
              </select>
            </div>
                      </div><!-- /.form-group -->
  <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">JALUR HOST TO HOST</label>
                        <div class="col-lg-5">
                        <select id="validator" name="validator" data-placeholder="Pilih BANK ..." class="form-control chzn-select" tabindex="2">
                        <option value="all">Semua</option>
                        <option value="API BRIVA">BRIVA</option>
                        <option value="H2H Bank Jambi">H2H BANK JAMBI</option>
              </select>
            </div>
                      </div><!-- /.form-group -->
 <div class="form-group">
  <label for="Semester" class="control-label col-lg-2">Range Tanggal Waktu</label>
   <div class="col-lg-4">
        <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-clock-o"></i>
                  </div>
                  <input type="text" class="form-control pull-right" id="reservationtime" name="tgl_bayar" autocomplete="off">
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
                        <table id="dtb_history_bayar" class="table table-bordered table-striped responsive" width="100%">
                            <thead>
                                <tr>
                                  <th>No</th>
                                  <th>No Pembayaran</th>
                                  <th>NIM</th>
                                  <th>Nama</th>
                                  <th>Jumlah Bayar</th>
                                  <th>Tgl Bayar</th>
                                  <th>Tgl Validasi</th>
                                  <th>Bank</th>
                                  <th>Keterangan</th>
                                  <th>Program Studi</th>
                                  <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <th colspan="4" style="text-align:right">TOTAL BAYAR Rp. </th>
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
});
dtb_history_bayar = $("#dtb_history_bayar").DataTable({
              destroy : true,
           'bProcessing': true,
            'bServerSide': true,
            'order': [[1, 'desc']],
           'columnDefs': [
                {
            'width': '5%',
            'targets': 0,
            'orderable': false,
            'searchable': false,
            'className': 'dt-center'
          },
                {
            'width': '9%',
            'targets': -1,
            'orderable': false,
            'searchable': false,
            'className': 'dt-center'
          }
             ],

    
            'ajax':{
               url :'<?=base_admin();?>modul/history_bayar/history_bayar_data.php',
            type: 'post',  // method  , by default get
            data: function ( d ) {
              d.kode_prodi = $("#kode_prodi").val();
              d.fakultas = $("#fakultas_filter").val();
              d.mulai_smt = $("#mulai_smt").val();           
              d.id_bank = $("#id_bank").val();
              d.validator = $("#validator").val();
              d.tgl_bayar = $("#reservationtime").val();
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
  dtb_history_bayar.ajax.reload();
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
            