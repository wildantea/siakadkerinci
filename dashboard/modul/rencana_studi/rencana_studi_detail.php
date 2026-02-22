<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>Rencana Studi</h1>
                   <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>rencana-studi">Rencana Studi</a></li>
                        <li class="active">Detail Rencana Studi</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box box-solid box-primary">
                            <div class="box-header">
                            <h3 class="box-title">Detail Rencana Studi</h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>

                    <div class="box-body">
                      <form class="form-horizontal">
                        <div class="form-group">
                        <label for="Mahasiswa" class="control-label col-lg-2">Mahasiswa</label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("mahasiswa") as $isi) {
                  if ($data_edit->mhs_id==$isi->mhs_id) {

                    echo "<input disabled class='form-control' type='text' value='$isi->nama'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Semester</label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("semester") as $isi) {
                  if ($data_edit->sem_id==$isi->sem_id) {

                    echo "<input disabled class='form-control' type='text' value='$isi->sem_id'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Mata Kuliah" class="control-label col-lg-2">Mata Kuliah</label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("matkul") as $isi) {
                  if ($data_edit->id_matkul==$isi->id_matkul) {

                    echo "<input disabled class='form-control' type='text' value='$isi->nama_mk'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Nilai" class="control-label col-lg-2">Nilai</label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("nilai_ref") as $isi) {
                  if ($data_edit->nilai_id==$isi->nilai_id) {

                    echo "<input disabled class='form-control' type='text' value='$isi->nilai_huruf'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->

            <div class="form-group">
                <label for="Disetujui" class="control-label col-lg-2">Disetujui</label>
                <div class="col-lg-10">
                <?php if ($data_edit->di_setujui=="1") {
                  ?>
                  <input name="di_setujui" class="make-switch" disabled type="checkbox" checked>
                  <?php
                } else {
                  ?>
                  <input name="di_setujui" class="make-switch" disabled type="checkbox">
                  <?php
                }?>
                </div>
            </div><!-- /.form-group -->
            
              <div class="form-group">
                <label for="Pengubah" class="control-label col-lg-2">Pengubah</label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->pengubah;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
          <div class="form-group">
              <label for="Tanggal Perubahan" class="control-label col-lg-2">Tanggal Perubahan</label>
              <div class="col-lg-10">
                <input type="text" disabled="" value="<?=tgl_indo($data_edit->tgl_perubahan);?>" class="form-control">
              </div>
          </div><!-- /.form-group -->
          
                        
                      </form>
                      <a href="<?=base_index();?>rencana-studi" class="btn btn-success "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>

                        </div>
                      </div>
                    </div>
                </div>

                </section><!-- /.content -->
