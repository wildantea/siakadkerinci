<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Riwayat Pembayaran
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>riwayat-pembayaran">Riwayat Pembayaran</a></li>
                        <li class="active">Riwayat Pembayaran List</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
                            <div class="box-body table-responsive">
 <div class="alert alert-warning fade in error_data_delete" style="display:none">
          <button type="button" class="close hide_alert_notif">&times;</button>
          <i class="icon fa fa-warning"></i> <span class="isi_warning_delete"></span>
        </div>
                        <table id="dtb_riwayat_pembayaran" class="table table-bordered table-striped" width="100%">
                            <thead>
                                <tr>
                                  <th>No</th>
                                  <th>Periode</th>
                                  <th>Semester</th>
                                  <th>Jenis Tagihan</th>
                                  <th>Tanggal Bayar</th>
                                  <th>Bank</th>
                                  <th>Tagihan</th>
                                  <th>Bayar</th>
                                  <th>Potongan</th>
                                  <th>Sisa</th>
                                  <th>Status</th>
                                  <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                              <?php
                              $nim = $_SESSION['username'];
                              //$nim = '220019039';
                              ?>
                                <th colspan="7" style="text-align:right;vertical-align: middle;">TOTAL Rp. </th>
                                <th colspan="3" class="total_bayar"></th>
                                <th><a target="_blank" href="<?=base_admin();?>modul/riwayat_pembayaran/cetak_all.php?nim=<?=$enc->enc($nim);?>"  class="btn btn-primary" data-toggle="tooltip" title="Cetak Semua Pembayaran"><i class="fa fa-print"></i> Cetak Semua</a>
</th>
                            </tfoot>
                        </table>

  

                    </div><!-- /.box-body -->
                  </div><!-- /.box -->
                </div>
              </div>
      
    </section><!-- /.content -->

        <script type="text/javascript">
      
      
      var dtb_riwayat_pembayaran = $("#dtb_riwayat_pembayaran").DataTable({
           'bProcessing': true,
            'bServerSide': true,
            'paging' : false,
            'responsive' : true,
            'searching' : false,
            
           'columnDefs': [ {
            'targets': [-1],
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
              url :'<?=base_admin();?>modul/riwayat_pembayaran/riwayat_pembayaran_data.php',
            type: 'post',  // method  , by default get
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
          "fnDrawCallback": function() {
              var api = this.api()
              var json = api.ajax.json();
              console.log(json);
              $('.total_bayar').html('<span class="btn btn-success">'+json.total_bayar+'</span>');
          }
        });
</script>

