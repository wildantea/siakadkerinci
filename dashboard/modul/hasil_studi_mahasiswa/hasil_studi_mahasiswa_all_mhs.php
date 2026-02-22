<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>Hasil Studi</h1>
  <ol class="breadcrumb">
    <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="<?=base_index();?>hasil-studi-mahasiswa">Hasil Studi</a></li>
    <li class="active">Hasil Studi Permahasiswa</li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
  <div class="row">

    <div class="col-xs-12">
      <div class="box">
         <div class="box-body">
          <div class="box box-primary">

                <form id="form_cari_mahasiswa" method="GET" class="form-horizontal" action="">
                    <div class="row">
                   <div class="col-md-12">
                      <h3 class="text-center">Hasil Studi Permahasiswa</h3>
                   </div>
                 </div>
                  <div class="form-group">
                    <label for="nim" class="control-label col-lg-2 col-md-2 col-xs-4" style="text-align: left">NIM </label>
                    <div class="col-lg-10 col-md-10 col-xs-8">
                      <b>: <?= $kk->nim ?></b>
                    </div>
                  </div><!-- /.form-group -->
                   <div class="form-group">
                    <label for="nama" class="control-label col-lg-2 col-md-2 col-xs-4" style="text-align: left">Nama Mahasiswa</label>
                     <div class="col-lg-10 col-md-10 col-xs-8">
                     <b>: <?= strtoupper($kk->nama) ?></b>
                    </div>
                  </div><!-- /.form-group -->
                  <div class="form-group">
                    <label for="nama" class="control-label col-lg-2 col-md-2 col-xs-4" style="text-align: left">Fakultas</label>
                     <div class="col-lg-10 col-md-10 col-xs-8">
                      <b>: <?= $kk->fakultas ?></b>
                    </div>
                  </div><!-- /.form-group -->
                   <div class="form-group">
                    <label for="nama" class="control-label col-lg-2 col-md-2 col-xs-4" style="text-align: left">Program Studi</label>
                     <div class="col-lg-10 col-md-10 col-xs-8">
                      <b>: <?= $kk->jurusan ?></b>
                    </div>
                  </div><!-- /.form-group -->
                </form>
               </div>
                <?php
                   include 'table_hasil_studi_persemester_mhs.php';
                  ?>
              </div>

            </div><!-- /.box -->
          </div>
        </div>
        </section><!-- /.content -->

    </section><!-- /.content -->
<script type="text/javascript">
    $("#form_cari_mahasiswa").submit(function(){
    $('#loadnya').show();
        $.ajax({
            type: 'POST',
            url: '<?=base_admin();?>modul/hasil_studi_mahasiswa/hasil_studi_mahasiswa_action.php?act=cari_mhs',
            data: {data_ids:all_ids},
            success: function(result) {
               $('#loadnya').hide();
             // $("#hasil").html(result);
            },
            //async:false
        });
    return false;
  });

</script>
