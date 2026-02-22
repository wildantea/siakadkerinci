<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>Info Tagihan</h1>
                   <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>info-tagihan">Info Tagihan</a></li>
                        <li class="active">Detail Info Tagihan</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box box-solid box-primary">
                            <div class="box-header">
                            <h3 class="box-title">Detail Info Tagihan</h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>

                    <div class="box-body">
                      <form class="form-horizontal">
                        
              <div class="form-group">
                <label for="nim" class="control-label col-lg-2">nim </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->nim;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="jumlah" class="control-label col-lg-2">jumlah </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->jumlah;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="id_tagihan_prodi" class="control-label col-lg-2">id_tagihan_prodi </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->id_tagihan_prodi;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="periode" class="control-label col-lg-2">periode </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->periode;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
                        
                      </form>
                      <a href="<?=base_index();?>info-tagihan" class="btn btn-success "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>

                        </div>
                      </div>
                    </div>
                </div>

                </section><!-- /.content -->
