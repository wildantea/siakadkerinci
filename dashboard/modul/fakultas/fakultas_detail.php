<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>Fakultas</h1>
                   <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>fakultas">Fakultas</a></li>
                        <li class="active">Detail Fakultas</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box box-solid box-primary">
                            <div class="box-header">
                            <h3 class="box-title">Detail Fakultas</h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>

                    <div class="box-body">
                      <form class="form-horizontal">
                        
              <div class="form-group">
                <label for="Kode Fakultas" class="control-label col-lg-2">Kode Fakultas <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->kode_fak;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Nama Fakultas" class="control-label col-lg-2">Nama Fakultas <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->nama_resmi;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Nama Singkat" class="control-label col-lg-2">Nama Singkat <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->nama_singkat;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Nama Inggris" class="control-label col-lg-2">Nama Inggris </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->nama_asing;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Email" class="control-label col-lg-2">Email </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->email;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Website" class="control-label col-lg-2">Website </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->web;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              <div class="form-group">
                        <label for="Dekan" class="control-label col-lg-2">Dekan </label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("dosen") as $isi) {
                  if ($data_edit->dekan==$isi->id_dosen) {

                    echo "<input disabled class='form-control' type='text' value='$isi->nama_dosen'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->

                        
                      </form>
                      <a href="<?=base_index();?>fakultas" class="btn btn-success "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>

                        </div>
                      </div>
                    </div>
                </div>

                </section><!-- /.content -->
