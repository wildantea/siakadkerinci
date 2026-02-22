<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>Semester  Prodi</h1>
                   <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>semester--prodi">Semester  Prodi</a></li>
                        <li class="active">Detail Semester  Prodi</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box box-solid box-primary">
                            <div class="box-header">
                            <h3 class="box-title">Detail Semester  Prodi</h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>

                    <div class="box-body">
                      <form class="form-horizontal">
                        <div class="form-group">
                        <label for="Jurusan" class="control-label col-lg-2">Jurusan</label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("jurusan") as $isi) {
                  if ($data_edit->kode_jur==$isi->kode_jur) {

                    echo "<input disabled class='form-control' type='text' value='$isi->nama_jur'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Semester</label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("semester_ref") as $isi) {
                  if ($data_edit->id_semester==$isi->id_semester) {

                    echo "<input disabled class='form-control' type='text' value='$isi->semester'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->

            <div class="form-group">
                <label for="Aktif" class="control-label col-lg-2">Aktif</label>
                <div class="col-lg-10">
                <?php if ($data_edit->is_aktif=="1") {
                  ?>
                  <input name="is_aktif" class="make-switch" disabled type="checkbox" checked>
                  <?php
                } else {
                  ?>
                  <input name="is_aktif" class="make-switch" disabled type="checkbox">
                  <?php
                }?>
                </div>
            </div><!-- /.form-group -->
            
          <div class="form-group">
              <label for="Tanggal Mulai" class="control-label col-lg-2">Tanggal Mulai</label>
              <div class="col-lg-10">
                <input type="text" disabled="" value="<?=tgl_indo($data_edit->tgl_mulai);?>" class="form-control">
              </div>
          </div><!-- /.form-group -->
          
          <div class="form-group">
              <label for="Tanggal Selesai" class="control-label col-lg-2">Tanggal Selesai</label>
              <div class="col-lg-10">
                <input type="text" disabled="" value="<?=tgl_indo($data_edit->tgl_selesai);?>" class="form-control">
              </div>
          </div><!-- /.form-group -->
          
          <div class="form-group">
              <label for="Tanggal Mulai KRS" class="control-label col-lg-2">Tanggal Mulai KRS</label>
              <div class="col-lg-10">
                <input type="text" disabled="" value="<?=tgl_indo($data_edit->tgl_mulai_krs);?>" class="form-control">
              </div>
          </div><!-- /.form-group -->
          
          <div class="form-group">
              <label for="Tanggal Selesai KRS" class="control-label col-lg-2">Tanggal Selesai KRS</label>
              <div class="col-lg-10">
                <input type="text" disabled="" value="<?=tgl_indo($data_edit->tgl_selesai_krs);?>" class="form-control">
              </div>
          </div><!-- /.form-group -->
          
              <div class="form-group">
                <label for="Tanggal Mulai PKRS" class="control-label col-lg-2">Tanggal Mulai PKRS</label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->tgl_mulai_pkrs;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
          <div class="form-group">
              <label for="Tanggal Selesai PKRS" class="control-label col-lg-2">Tanggal Selesai PKRS</label>
              <div class="col-lg-10">
                <input type="text" disabled="" value="<?=tgl_indo($data_edit->tgl_selesai_pkrs);?>" class="form-control">
              </div>
          </div><!-- /.form-group -->
          
          <div class="form-group">
              <label for="Tanggal Mulai Input Nilai" class="control-label col-lg-2">Tanggal Mulai Input Nilai</label>
              <div class="col-lg-10">
                <input type="text" disabled="" value="<?=tgl_indo($data_edit->tgl_mulai_input_nilai);?>" class="form-control">
              </div>
          </div><!-- /.form-group -->
          
          <div class="form-group">
              <label for="Tanggal Selesai Input Nilai" class="control-label col-lg-2">Tanggal Selesai Input Nilai</label>
              <div class="col-lg-10">
                <input type="text" disabled="" value="<?=tgl_indo($data_edit->tgl_selesai_input_nilai);?>" class="form-control">
              </div>
          </div><!-- /.form-group -->
          
                        
                      </form>
                      <a href="<?=base_index();?>semester--prodi" class="btn btn-success "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>

                        </div>
                      </div>
                    </div>
                </div>

                </section><!-- /.content -->
