<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>Pendaftaran Beasiswa</h1>
                   <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>pendaftaran-beasiswa">Pendaftaran Beasiswa</a></li>
                        <li class="active">Detail Pendaftaran Beasiswa</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box box-solid box-primary">
                            <div class="box-header">
                            <h3 class="box-title">Detail Pendaftaran Beasiswa</h3>
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
                  <input type="text" disabled="" value="<?=$data_edit->nim_beasiswamhs;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              <div class="form-group">
                        <label for="Jenis Beasiswa" class="control-label col-lg-2">Jenis Beasiswa <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("beasiswa_jenis") as $isi) {
                  if ($data_edit->id_beasiswajns==$isi->id_beasiswajns) {

                    echo "<input disabled class='form-control' type='text' value='$isi->jenis_beasiswajns'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Beasiswa" class="control-label col-lg-2">Beasiswa </label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("beasiswa") as $isi) {
                  if ($data_edit->id_beasiswa==$isi->id_beasiswa) {

                    echo "<input disabled class='form-control' type='text' value='$isi->nama_beasiswa'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->

              <div class="form-group">
                <label for="IPK" class="control-label col-lg-2">IPK <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->ipk_beasiswamhs;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
                        
                      </form>
                      <a href="<?=base_index();?>pendaftaran-beasiswa" class="btn btn-success "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>

                        </div>
                      </div>
                    </div>
                </div>

                </section><!-- /.content -->
