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
                 <form class="form-horizontal" method="post" action="<?=base_admin();?>modul/setting_tagihan_mahasiswa/download_data.php">
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
                        <label for="Semester" class="control-label col-lg-2">Angkatan Mahasiswa</label>
                        <div class="col-lg-5">
                        <select id="mulai_smt" name="mulai_smt" data-placeholder="Pilih Angkatan ..." class="form-control chzn-select" tabindex="2">
                        <option value="all">Semua</option>
 <?php
                               foreach ($db->query("select vs.mulai_smt, vs.angkatan from view_simple_mhs_data vs
inner join keu_tagihan_mahasiswa ktm on vs.nim=ktm.nim
inner join keu_bayar_mahasiswa kbm on ktm.id=kbm.id_keu_tagihan_mhs
group by vs.angkatan") as $dt) {
                                 echo "<option value='$dt->mulai_smt'>$dt->angkatan</option>";
                               }
                                ?>
              </select>

 </div>
                      </div><!-- /.form-group -->
  <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Periode Pembayaran</label>
                        <div class="col-lg-5">
                        <select id="periode" name="periode" data-placeholder="Pilih Periode Pembayaran ..." class="form-control chzn-select" tabindex="2">
                        <option value="all">Semua</option>
                                                      <?php 
 $angkatan_exist = $db->query("select view_semester.aktif,tahun_akademik,periode from keu_tagihan_mahasiswa inner join view_semester 
on keu_tagihan_mahasiswa.periode=view_semester.id_semester
group by keu_tagihan_mahasiswa.periode
order by periode desc");

foreach ($angkatan_exist as $ak) {
  if ($ak->aktif==1) {
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
inner join keu_bayar_mahasiswa kbm on ktm.id=kbm.id_keu_tagihan_mhs
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
                        <select id="id_bank" name="id_bank" data-placeholder="Pilih BANK ..." class="form-control chzn-select" tabindex="2">
                        <option value="all">Semua</option>
                                                      <?php 
 $angkatan_exist = $db->query("select keu_bank.kode_bank,keu_bank.nama_bank from keu_bank
inner join keu_bayar_mahasiswa kbm on keu_bank.kode_bank=kbm.id_bank
group by kode_bank");

foreach ($angkatan_exist as $ak) {
  echo "<option value='$ak->kode_bank'>$ak->nama_bank</option>";
} ?>
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
                  <input type="text" class="form-control pull-right" id="reservationtime" name="tgl_bayar">
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
                        <table id="dtb_setting_tagihan_mahasiswa" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                  <th style="padding-right: 0;width: 5%">No</th>
                                  <th>No Pembayaran</th>
                                  <th>NIM</th>
                                  <th>Nama</th>
                                  <th>Angkatan</th>
                                   <th>Prodi</th>
                                   <th>Jenis Pembayaran</th>
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
                        </table>
                    </div><!-- /.box-body -->
                  </div><!-- /.box -->
                </div>
              </div>
 </section><!-- /.content -->

        <script type="text/javascript">
      var dtb_setting_tagihan_mahasiswa = $("#dtb_setting_tagihan_mahasiswa").DataTable({
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
          }
             ],

    
            'ajax':{
              url :'<?=base_admin();?>modul/laporan_pembayaran/laporan_pembayaran_data.php',
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
              d.mulai_smt = $("#mulai_smt").val();
              d.periode = $("#periode").val();
              d.kode_pembayaran = $("#kode_pembayaran").val();
              d.kode_tagihan = $("#kode_tagihan").val();              
              d.id_bank = $("#id_bank").val();
              d.tgl_bayar = $("#reservationtime").val();
            },
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          }
      });
});
</script>
            