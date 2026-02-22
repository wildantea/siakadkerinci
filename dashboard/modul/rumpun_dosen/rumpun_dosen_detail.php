<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>Rumpun Dosen</h1>
                   <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>rumpun-dosen">Rumpun Dosen</a></li>
                        <li class="active">Detail Rumpun Dosen</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box box-solid box-primary">
                            <div class="box-header">
                            <h3 class="box-title">Detail Rumpun Dosen</h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>

                    <div class="box-body">
                      <form class="form-horizontal">
                        
          <div class="form-group">
              <label for="Kode Rumpun" class="control-label col-lg-2">Kode Rumpun <span style="color:#FF0000">*</span></label>
              <div class="col-lg-10">
                <input type="text" disabled="" value="<?=$data_edit->kode;?>" class="form-control">
              </div>
          </div><!-- /.form-group -->
          <div class="form-group">
                        <label for="Nama Rumpun" class="control-label col-lg-2">Nama Rumpun </label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("ruang_ref") as $isi) {
                  if ($data_edit->nama_rumpun==$isi->ruang_id) {

                    echo "<input disabled class='form-control' type='text' value='$isi->ruang_id'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->

                        
                      </form>
                      <a href="<?=base_index();?>rumpun-dosen" class="btn btn-success "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>

                        </div>
                      </div>
                    </div>
                </div>

                </section><!-- /.content -->
