<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>Mahasiswa Lulus</h1>
                   <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>mahasiswa-lulus">Mahasiswa Lulus</a></li>
                        <li class="active">Detail Mahasiswa Lulus</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box box-solid box-primary">
                            <div class="box-header">
                            <h3 class="box-title">Detail Mahasiswa Lulus</h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>

                    <div class="box-body">
                      <form class="form-horizontal">
                        
              <div class="form-group">
                <label for="nim" class="control-label col-lg-2">nim </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->nim;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              <div class="form-group">
                        <label for="Jenis Keluar" class="control-label col-lg-2">Jenis Keluar <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-10">
              <?php foreach ($db2->fetchAll("jenis_keluar") as $isi) {
                  if ($data_edit->id_jenis_keluar==$isi->id_jns_keluar) {

                    echo "<input disabled class='form-control' type='text' value='$isi->ket_keluar'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->

          <div class="form-group">
              <label for="Tanggal Keluar" class="control-label col-lg-2">Tanggal Keluar <span style="color:#FF0000">*</span></label>
              <div class="col-lg-10">
                <input type="text" disabled="" value="<?=tgl_indo($data_edit->tanggal_keluar);?>" class="form-control">
              </div>
          </div><!-- /.form-group -->
          <div class="form-group">
                        <label for="Periode Keluar" class="control-label col-lg-2">Periode Keluar <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-10">
              <?php foreach ($db2->fetchAll("view_semester") as $isi) {
                  if ($data_edit->semester==$isi->id_semester) {

                    echo "<input disabled class='form-control' type='text' value='$isi->tahun_akademik'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->

          <div class="form-group">
              <label for="Keterangan" class="control-label col-lg-2">Keterangan </label>
              <div class="col-lg-10">
                <textarea class="form-control col-xs-12" rows="5" name="keterangan_kelulusan" disabled="" ><?=$data_edit->keterangan_kelulusan;?> </textarea>
              </div>
          </div><!-- /.form-group -->
          
              <div class="form-group">
                <label for="Nomor SK" class="control-label col-lg-2">Nomor SK </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->nomor_sk;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
          <div class="form-group">
              <label for="Tanggal SK" class="control-label col-lg-2">Tanggal SK </label>
              <div class="col-lg-10">
                <input type="text" disabled="" value="<?=tgl_indo($data_edit->tanggal_sk);?>" class="form-control">
              </div>
          </div><!-- /.form-group -->
          
              <div class="form-group">
                <label for="IPK" class="control-label col-lg-2">IPK </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->ipk;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="No Ijazah / No sertifikat profesi" class="control-label col-lg-2">No Ijazah / No sertifikat profesi </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->no_seri_ijasah;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
                        
                      </form>
                      <a href="<?=base_index();?>mahasiswa-lulus" class="btn btn-success "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>

                        </div>
                      </div>
                    </div>
                </div>

                </section><!-- /.content -->
