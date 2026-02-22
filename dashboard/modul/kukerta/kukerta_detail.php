<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>Kukerta</h1>
                   <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>kukerta">Kukerta</a></li>
                        <li class="active">Detail Kukerta</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box box-solid box-primary">
                            <div class="box-header">
                            <h3 class="box-title">Detail Kukerta</h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>

                    <div class="box-body">
                      <form class="form-horizontal">
                        
              <div class="form-group">
                <label for="priode" class="control-label col-lg-2">priode </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->priode;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="nama_periode" class="control-label col-lg-2">nama_periode </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->nama_periode;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="nip" class="control-label col-lg-2">nip </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->nip;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="nip2" class="control-label col-lg-2">nip2 </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->nip2;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
                        
                      </form>
                      <a href="<?=base_index();?>kukerta" class="btn btn-success "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>

                        </div>
                      </div>
                    </div>
                </div>

                </section><!-- /.content -->
