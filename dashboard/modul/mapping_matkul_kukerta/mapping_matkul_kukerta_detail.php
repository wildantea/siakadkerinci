<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>Mapping Matkul Kukerta</h1>
                   <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>mapping-matkul-kukerta">Mapping Matkul Kukerta</a></li>
                        <li class="active">Detail Mapping Matkul Kukerta</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box box-solid box-primary">
                            <div class="box-header">
                            <h3 class="box-title">Detail Mapping Matkul Kukerta</h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>

                    <div class="box-body">
                      <form class="form-horizontal">
                        <div class="form-group">
                        <label for="Kode MK" class="control-label col-lg-2">Kode MK <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("matkul") as $isi) {
                  if ($data_edit->kode_mk==$isi->kode_mk) {

                    echo "<input disabled class='form-control' type='text' value='$isi->nama_mk'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->

              <div class="form-group">
                <label for="Nama Matkul" class="control-label col-lg-2">Nama Matkul </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->mk;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Nama Matkul" class="control-label col-lg-2">Nama Matkul </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->mk;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
                        
                      </form>
                      <a href="<?=base_index();?>mapping-matkul-kukerta" class="btn btn-success "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>

                        </div>
                      </div>
                    </div>
                </div>

                </section><!-- /.content -->
