<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>Sinkron Moodle</h1>
                   <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>sinkron-moodle">Sinkron Moodle</a></li>
                        <li class="active">Detail Sinkron Moodle</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box box-solid box-primary">
                            <div class="box-header">
                            <h3 class="box-title">Detail Sinkron Moodle</h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>

                    <div class="box-body">
                      <form class="form-horizontal">
                        
              <div class="form-group">
                <label for="shortname" class="control-label col-lg-2">shortname </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->shortname;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="fullname" class="control-label col-lg-2">fullname </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->nama_mk;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="category" class="control-label col-lg-2">category </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->category;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="sumary" class="control-label col-lg-2">sumary </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->sumary;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
                        
                      </form>
                      <a href="<?=base_index();?>sinkron-moodle" class="btn btn-success "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>

                        </div>
                      </div>
                    </div>
                </div>

                </section><!-- /.content -->
