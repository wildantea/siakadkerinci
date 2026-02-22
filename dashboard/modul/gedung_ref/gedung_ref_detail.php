<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>Gedung Ref</h1>
                   <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>gedung-ref">Gedung Ref</a></li>
                        <li class="active">Detail Gedung Ref</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box box-solid box-primary">
                            <div class="box-header">
                            <h3 class="box-title">Detail Gedung Ref</h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>

                    <div class="box-body">
                      <form class="form-horizontal">
                        
              <div class="form-group">
                <label for="Kode Gedung" class="control-label col-lg-2">Kode Gedung</label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->kode_gedung;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Nama Gedung" class="control-label col-lg-2">Nama Gedung</label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->nm_gedung;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
            <div class="form-group">
                <label for="Aktif" class="control-label col-lg-2">Aktif</label>
                <div class="col-lg-10">
                <?php if ($data_edit->is_aktif=="Y") {
                  ?>
                  <input name="is_aktif" class="make-switch" disabled type="checkbox" checked>
                  <?php
                } else {
                  ?>
                  <input name="is_aktif" class="make-switch" disabled type="checkbox">
                  <?php
                }?>
                </div>
            </div><!-- /.form-group -->
            
                        
                      </form>
                      <a href="<?=base_index();?>gedung-ref" class="btn btn-success "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>

                        </div>
                      </div>
                    </div>
                </div>

                </section><!-- /.content -->
