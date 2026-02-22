<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>Upload Drive</h1>
                   <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>upload-drive">Upload Drive</a></li>
                        <li class="active">Detail Upload Drive</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box box-solid box-primary">
                            <div class="box-header">
                            <h3 class="box-title">Detail Upload Drive</h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>

                    <div class="box-body">
                      <form class="form-horizontal">
                        
              <div class="form-group">
                <label for="nama" class="control-label col-lg-2">nama <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->nama;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              <div class="form-group">
                        <label for="file" class="control-label col-lg-2">file <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->file;?>" class="form-control">
                  </div>
                      </div><!-- /.form-group -->

                        
                      </form>
                      <a href="<?=base_index();?>upload-drive" class="btn btn-success "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>

                        </div>
                      </div>
                    </div>
                </div>

                </section><!-- /.content -->
