<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>Penghasilan Ref</h1>
                   <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>penghasilan-ref">Penghasilan Ref</a></li>
                        <li class="active">Detail Penghasilan Ref</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box box-solid box-primary">
                            <div class="box-header">
                            <h3 class="box-title">Detail Penghasilan Ref</h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>

                    <div class="box-body">
                      <form class="form-horizontal">
                        
              <div class="form-group">
                <label for="Penghasilan" class="control-label col-lg-2">Penghasilan</label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->penghasilan;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Batas Bawah" class="control-label col-lg-2">Batas Bawah</label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->batas_bawah;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Batas Atas" class="control-label col-lg-2">Batas Atas</label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->batas_atas;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
                        
                      </form>
                      <a href="<?=base_index();?>penghasilan-ref" class="btn btn-success "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>

                        </div>
                      </div>
                    </div>
                </div>

                </section><!-- /.content -->
