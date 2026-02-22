<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>Mahasiswa Pindah</h1>
                   <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>mahasiswa-pindah">Mahasiswa Pindah</a></li>
                        <li class="active">Detail Mahasiswa Pindah</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box box-solid box-primary">
                            <div class="box-header">
                            <h3 class="box-title">Detail Mahasiswa Pindah</h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>

                    <div class="box-body">
                      <form class="form-horizontal">
                        
              <div class="form-group">
                <label for="Nim Lama" class="control-label col-lg-2">Nim Lama </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->nim_lama;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Nama Mahasiswa" class="control-label col-lg-2">Nama Mahasiswa </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->nama_mhs;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="NIM Baru" class="control-label col-lg-2">NIM Baru </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->nim_baru;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Kampus Lama" class="control-label col-lg-2">Kampus Lama </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->kampus_lama;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Kampus Baru" class="control-label col-lg-2">Kampus Baru </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->kampus_baru;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Jurusan Lama" class="control-label col-lg-2">Jurusan Lama </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->jurusan_lama;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              <div class="form-group">
                        <label for="Jurusan Baru" class="control-label col-lg-2">Jurusan Baru <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("jurusan") as $isi) {
                  if ($data_edit->jurusan_baru==$isi->kode_jur) {

                    echo "<input disabled class='form-control' type='text' value='$isi->nama_jur'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->

          <div class="form-group">
              <label for="Tanggal Pindah" class="control-label col-lg-2">Tanggal Pindah </label>
              <div class="col-lg-10">
                <input type="text" disabled="" value="<?=tgl_indo($data_edit->tgl_pindah);?>" class="form-control">
              </div>
          </div><!-- /.form-group -->
          
              <div class="form-group">
                <label for="NO SK" class="control-label col-lg-2">NO SK </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->no_sk;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
                        
                      </form>
                      <a href="<?=base_index();?>mahasiswa-pindah" class="btn btn-success "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>

                        </div>
                      </div>
                    </div>
                </div>

                </section><!-- /.content -->
