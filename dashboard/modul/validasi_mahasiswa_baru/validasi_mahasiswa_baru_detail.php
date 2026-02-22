<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>Validasi Mahasiswa Baru</h1>
                   <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>validasi-mahasiswa-baru">Validasi Mahasiswa Baru</a></li>
                        <li class="active">Detail Validasi Mahasiswa Baru</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box box-solid box-primary">
                            <div class="box-header">
                            <h3 class="box-title">Detail Validasi Mahasiswa Baru</h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>

                    <div class="box-body">
                      <form class="form-horizontal">
                        
              <div class="form-group">
                <label for="NIM" class="control-label col-lg-2">NIM <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->nim;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Nama" class="control-label col-lg-2">Nama <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->nama;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              <div class="form-group">
                        <label for="Jurusan" class="control-label col-lg-2">Jurusan <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("jurusan") as $isi) {
                  if ($data_edit->jur_kode==$isi->kode_jur) {

                    echo "<input disabled class='form-control' type='text' value='$isi->nama_jur'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Jalur Masuk" class="control-label col-lg-2">Jalur Masuk </label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("jalur_masuk") as $isi) {
                  if ($data_edit->id_jalur_masuk==$isi->id_jalur_masuk) {

                    echo "<input disabled class='form-control' type='text' value='$isi->nm_jalur_masuk'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Foto" class="control-label col-lg-2">Foto </label>
                        <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->pesan;?>" class="form-control">
                  </div>
                      </div><!-- /.form-group -->

                        
                      </form>
                      <a href="<?=base_index();?>validasi-mahasiswa-baru" class="btn btn-success "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>

                        </div>
                      </div>
                    </div>
                </div>

                </section><!-- /.content -->
