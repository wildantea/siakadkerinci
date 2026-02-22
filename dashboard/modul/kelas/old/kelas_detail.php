<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>Kelas</h1>
                   <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>kelas">Kelas</a></li>
                        <li class="active">Detail Kelas</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box box-solid box-primary">
                            <div class="box-header">
                            <h3 class="box-title">Detail Kelas</h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>

                    <div class="box-body">
                      <form class="form-horizontal">
                        
              <div class="form-group">
                <label for="Nama Kelas" class="control-label col-lg-2">Nama Kelas</label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->kls_nama;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
            <div class="form-group">
                <label for="Kode Paralel" class="control-label col-lg-2">Kode Paralel</label>
                <div class="col-lg-10">
                <?php
                  $option = array(
'01' => 'A',

'02' => 'B',

'03' => 'C',

'04' => 'D',

'05' => 'E',

'06' => 'F',

'07' => 'G',

'08' => 'H',

'09' => 'I',

'10' => 'J',

'11' => 'K',

'12' => 'L',

'13' => 'M',
);
                  foreach ($option as $isi => $val) {
                  if ($data_edit->kode_paralel==$isi) {

                    echo "<input disabled class='form-control' type='text' value='$val'>";
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
                        <label for="Mata Kuliah Setara" class="control-label col-lg-2">Mata Kuliah Setara</label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("matkul") as $isi) {
                  if ($data_edit->id_matkul_setara==$isi->id_matkul) {

                    echo "<input disabled class='form-control' type='text' value='$isi->nama_mk'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Semester</label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("semester_ref") as $isi) {
                  if ($data_edit->sem_id==$isi->id_semester) {

                    echo "<input disabled class='form-control' type='text' value='$isi->semester'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->

          <div class="form-group">
              <label for="Peserta Maximal" class="control-label col-lg-2">Peserta Maximal</label>
              <div class="col-lg-10">
                <input type="text" disabled="" value="<?=$data_edit->peserta_max;?>" class="form-control">
              </div>
          </div><!-- /.form-group -->
          
          <div class="form-group">
              <label for="Peserta Minimal" class="control-label col-lg-2">Peserta Minimal</label>
              <div class="col-lg-10">
                <input type="text" disabled="" value="<?=$data_edit->peserta_min;?>" class="form-control">
              </div>
          </div><!-- /.form-group -->
          
            <div class="form-group">
                <label for="is_open" class="control-label col-lg-2">is_open</label>
                <div class="col-lg-10">
                <?php if ($data_edit->is_open=="Y") {
                  ?>
                  <input name="is_open" class="make-switch" disabled type="checkbox" checked>
                  <?php
                } else {
                  ?>
                  <input name="is_open" class="make-switch" disabled type="checkbox">
                  <?php
                }?>
                </div>
            </div><!-- /.form-group -->
            
          <div class="form-group">
              <label for="Catatan" class="control-label col-lg-2">Catatan</label>
              <div class="col-lg-10">
                <textarea class="form-control col-xs-12" rows="5" name="catatan" disabled="" ><?=$data_edit->catatan;?> </textarea>
              </div>
          </div><!-- /.form-group -->
          
                        
                      </form>
                      <a href="<?=base_index();?>kelas" class="btn btn-success "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>

                        </div>
                      </div>
                    </div>
                </div>

                </section><!-- /.content -->
