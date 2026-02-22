<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>Generate Nilai</h1>
                   <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>generate-nilai">Generate Nilai</a></li>
                        <li class="active">Detail Generate Nilai</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box box-solid box-primary">
                            <div class="box-header">
                            <h3 class="box-title">Detail Generate Nilai</h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>

                    <div class="box-body">
                      <form class="form-horizontal">
                        <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Semester <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("semester_ref") as $isi) {
                  if ($data_edit->sem_id==$isi->id_semester) {

                    echo "<input disabled class='form-control' type='text' value='$isi->semester'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="kode_jur" class="control-label col-lg-2">kode_jur <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("jurusan") as $isi) {
                  if ($data_edit->kode_jur==$isi->kode_jur) {

                    echo "<input disabled class='form-control' type='text' value='$isi->nama_jur'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->

              <div class="form-group">
                <label for="total" class="control-label col-lg-2">total </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->total;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
                        
                      </form>
                      <a href="<?=base_index();?>generate-nilai" class="btn btn-success "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>

                        </div>
                      </div>
                    </div>
                </div>

                </section><!-- /.content -->
