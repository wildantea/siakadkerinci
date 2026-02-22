<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>Pengaturan Pendaftaran</h1>
                   <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>pengaturan-pendaftaran">Pengaturan Pendaftaran</a></li>
                        <li class="active">Detail Pengaturan Pendaftaran</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box box-solid box-primary">
                            <div class="box-header">
                            <h3 class="box-title">Detail Pengaturan Pendaftaran</h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>

                    <div class="box-body">
                      <form class="form-horizontal">
                        <div class="form-group">
                        <label for="Nama Pendaftaran" class="control-label col-lg-2">Nama Pendaftaran </label>
                        <div class="col-lg-10">
              <?php foreach ($db2->fetchAll("tb_data_pendaftaran_jenis") as $isi) {
                  if ($data_edit->id_jenis_pendaftaran==$isi->id_jenis_pendaftaran) {

                    echo "<input disabled class='form-control' type='text' value='$isi->nama_jenis_pendaftaran'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Untuk Program Studi" class="control-label col-lg-2">Untuk Program Studi </label>
                        <div class="col-lg-10">
              <?php foreach ($db2->fetchAll("view_prodi_jenjang") as $isi) {
                  if ($data_edit->for_jurusan==$isi->kode_jur) {

                    echo "<input disabled class='form-control' type='text' value='$isi->nama_jurusan'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->

            <div class="form-group">
                <label for="Ada Jadwal" class="control-label col-lg-2">Ada Jadwal </label>
                <div class="col-lg-10">
                <?php if ($data_edit->ada_jadwal=="Y") {
                  ?>
                  <input name="ada_jadwal" data-on-text="On" data-off-text="Off" class="make-switch" disabled type="checkbox" checked>
                  <?php
                } else {
                  ?>
                  <input name="ada_jadwal" data-on-text="On" data-off-text="Off" class="make-switch" disabled type="checkbox">
                  <?php
                }?>
                </div>
            </div><!-- /.form-group -->
            
            <div class="form-group">
                <label for="Ada Bukti" class="control-label col-lg-2">Ada Bukti </label>
                <div class="col-lg-10">
                <?php if ($data_edit->ada_bukti=="Y") {
                  ?>
                  <input name="ada_bukti" data-on-text="On" data-off-text="Off" class="make-switch" disabled type="checkbox" checked>
                  <?php
                } else {
                  ?>
                  <input name="ada_bukti" data-on-text="On" data-off-text="Off" class="make-switch" disabled type="checkbox">
                  <?php
                }?>
                </div>
            </div><!-- /.form-group -->
            
            <div class="form-group">
                <label for="Ada Judul" class="control-label col-lg-2">Ada Judul </label>
                <div class="col-lg-10">
                <?php if ($data_edit->ada_judul=="Y") {
                  ?>
                  <input name="ada_judul" data-on-text="On" data-off-text="Off" class="make-switch" disabled type="checkbox" checked>
                  <?php
                } else {
                  ?>
                  <input name="ada_judul" data-on-text="On" data-off-text="Off" class="make-switch" disabled type="checkbox">
                  <?php
                }?>
                </div>
            </div><!-- /.form-group -->
            
            <div class="form-group">
                <label for="Ada Pembimbing" class="control-label col-lg-2">Ada Pembimbing </label>
                <div class="col-lg-10">
                <?php if ($data_edit->ada_pembimbing=="Y") {
                  ?>
                  <input name="ada_pembimbing" data-on-text="On" data-off-text="Off" class="make-switch" disabled type="checkbox" checked>
                  <?php
                } else {
                  ?>
                  <input name="ada_pembimbing" data-on-text="On" data-off-text="Off" class="make-switch" disabled type="checkbox">
                  <?php
                }?>
                </div>
            </div><!-- /.form-group -->
            
          <div class="form-group">
              <label for="Jumlah Pembimbing" class="control-label col-lg-2">Jumlah Pembimbing </label>
              <div class="col-lg-10">
                <input type="text" disabled="" value="<?=$data_edit->jumlah_pembimbing;?>" class="form-control">
              </div>
          </div><!-- /.form-group -->
          
            <div class="form-group">
                <label for="Ada Penguji" class="control-label col-lg-2">Ada Penguji </label>
                <div class="col-lg-10">
                <?php if ($data_edit->ada_penguji=="Y") {
                  ?>
                  <input name="ada_penguji" data-on-text="On" data-off-text="Off" class="make-switch" disabled type="checkbox" checked>
                  <?php
                } else {
                  ?>
                  <input name="ada_penguji" data-on-text="On" data-off-text="Off" class="make-switch" disabled type="checkbox">
                  <?php
                }?>
                </div>
            </div><!-- /.form-group -->
            
          <div class="form-group">
              <label for="Jumlah Penguji" class="control-label col-lg-2">Jumlah Penguji </label>
              <div class="col-lg-10">
                <input type="text" disabled="" value="<?=$data_edit->jumlah_penguji;?>" class="form-control">
              </div>
          </div><!-- /.form-group -->
          <div class="form-group">
                        <label for="Bukti" class="control-label col-lg-2">Bukti </label>
                        <div class="col-lg-10">
              <?php foreach ($db2->fetchAll("tb_data_pendaftaran_jenis_bukti") as $isi) {
                  if ($data_edit->bukti==$isi->id_jenis_bukti) {

                    echo "<input disabled class='form-control' type='text' value='$isi->jenis_bukti'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->

                        
                      </form>
                      <a href="<?=base_index();?>pengaturan-pendaftaran" class="btn btn-success "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>

                        </div>
                      </div>
                    </div>
                </div>

                </section><!-- /.content -->
