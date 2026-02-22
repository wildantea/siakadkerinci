<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>Tugas Akhir</h1>
                   <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>tugas-akhir">Tugas Akhir</a></li>
                        <li class="active">Detail Tugas Akhir</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box box-solid box-primary">
                            <div class="box-header">
                            <h3 class="box-title">Detail Tugas Akhir</h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>

                    <div class="box-body">
                      <form class="form-horizontal">
                        <div class="form-group">
                        <label for="kode_fak" class="control-label col-lg-2">Fakultas <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("fakultas") as $isi) {
                  if ($data_edit->kode_fak==$isi->kode_fak) {

                    echo "<input disabled class='form-control' type='text' value='$isi->nama_resmi'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="kode_jurusan" class="control-label col-lg-2">Jurusan <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("jurusan") as $isi) {
                  if ($data_edit->kode_jurusan==$isi->kode_jur) {

                    echo "<input disabled class='form-control' type='text' value='$isi->nama_jur'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->

              <div class="form-group">
                <label for="nim" class="control-label col-lg-2">nim <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->nim;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->

          <div class="form-group">
              <label for="judul_ta" class="control-label col-lg-2">judul_ta </label>
              <div class="col-lg-10">
                <textarea class="form-control col-xs-12" rows="5" name="judul_ta" disabled="" ><?=$data_edit->judul_ta;?> </textarea>
              </div>
          </div><!-- /.form-group -->
          <div class="form-group">
                        <label for="pembimbing_1" class="control-label col-lg-2">Pembimbing 1 <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("dosen") as $isi) {
                  if ($data_edit->pembimbing_1==$isi->id_dosen) {

                    echo "<input disabled class='form-control' type='text' value='$isi->nama_dosen'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="pembimbing_2" class="control-label col-lg-2">Pembimbing 2 <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("dosen") as $isi) {
                  if ($data_edit->pembimbing_2==$isi->id_dosen) {

                    echo "<input disabled class='form-control' type='text' value='$isi->nama_dosen'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->

                      </form>
                      <a href="<?=base_index();?>tugas-akhir" class="btn btn-success "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>

                        </div>
                      </div>
                    </div>
                </div>

                </section><!-- /.content -->
