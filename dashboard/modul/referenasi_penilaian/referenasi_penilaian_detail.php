<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>Referenasi Penilaian</h1>
                   <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>referenasi-penilaian">Referenasi Penilaian</a></li>
                        <li class="active">Detail Referenasi Penilaian</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box box-solid box-primary">
                            <div class="box-header">
                            <h3 class="box-title">Detail Referenasi Penilaian</h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>

                    <div class="box-body">
                      <form class="form-horizontal">
                        
              <div class="form-group">
                <label for="Nama Komponen" class="control-label col-lg-2">Nama Komponen </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->nama_komponen;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
            <div class="form-group">
                <label for="Wajib" class="control-label col-lg-2">Wajib </label>
                <div class="col-lg-10">
                <?php if ($data_edit->wajib=="1") {
                  ?>
                  <input name="wajib" class="make-switch" disabled type="checkbox" checked>
                  <?php
                } else {
                  ?>
                  <input name="wajib" class="make-switch" disabled type="checkbox">
                  <?php
                }?>
                </div>
            </div><!-- /.form-group -->
            
            <div class="form-group">
                <label for="Tampil" class="control-label col-lg-2">Tampil </label>
                <div class="col-lg-10">
                <?php if ($data_edit->isShow=="1") {
                  ?>
                  <input name="isShow" class="make-switch" disabled type="checkbox" checked>
                  <?php
                } else {
                  ?>
                  <input name="isShow" class="make-switch" disabled type="checkbox">
                  <?php
                }?>
                </div>
            </div><!-- /.form-group -->
            
                        
                      </form>
                      <a href="<?=base_index();?>referenasi-penilaian" class="btn btn-success "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>

                        </div>
                      </div>
                    </div>
                </div>

                </section><!-- /.content -->
