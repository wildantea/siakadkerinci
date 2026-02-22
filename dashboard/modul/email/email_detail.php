<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>Email</h1>
                   <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>email">Email</a></li>
                        <li class="active">Detail Email</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box box-solid box-primary">
                            <div class="box-header">
                            <h3 class="box-title">Detail Email</h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>

                    <div class="box-body">
                      <form class="form-horizontal">
                        
              <div class="form-group">
                <label for="Email" class="control-label col-lg-2">Email <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->email;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
            <div class="form-group">
                <label for="Login" class="control-label col-lg-2">Login </label>
                <div class="col-lg-10">
                <?php if ($data_edit->login=="Y") {
                  ?>
                  <input name="login" data-on-text="On" data-off-text="Off" class="make-switch" disabled type="checkbox" checked>
                  <?php
                } else {
                  ?>
                  <input name="login" data-on-text="On" data-off-text="Off" class="make-switch" disabled type="checkbox">
                  <?php
                }?>
                </div>
            </div><!-- /.form-group -->
            
              <div class="form-group">
                <label for="client_id" class="control-label col-lg-2">client_id <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->client_id;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="client_secret" class="control-label col-lg-2">client_secret <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->client_secret;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="redirect_url" class="control-label col-lg-2">redirect_url <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->redirect_url;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
          <div class="form-group">
              <label for="refresh_token" class="control-label col-lg-2">refresh_token </label>
              <div class="col-lg-10">
                <textarea class="form-control col-xs-12" rows="5" name="refresh_token" disabled="" ><?=$data_edit->refresh_token;?> </textarea>
              </div>
          </div><!-- /.form-group -->
          
          <div class="form-group">
              <label for="access_token" class="control-label col-lg-2">access_token </label>
              <div class="col-lg-10">
                <textarea class="form-control col-xs-12" rows="5" name="access_token" disabled="" ><?=$data_edit->access_token;?> </textarea>
              </div>
          </div><!-- /.form-group -->
          
                        
                      </form>
                      <a href="<?=base_index();?>email" class="btn btn-success "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>

                        </div>
                      </div>
                    </div>
                </div>

                </section><!-- /.content -->
