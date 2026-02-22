<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>Setting Menu</h1>
                   <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>setting-menu">Setting Menu</a></li>
                        <li class="active">Detail Setting Menu</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box box-solid box-primary">
                            <div class="box-header">
                            <h3 class="box-title">Detail Setting Menu</h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>

                    <div class="box-body">
                      <form class="form-horizontal">
                        
              <div class="form-group">
                <label for="Nama Menu" class="control-label col-lg-2">Nama Menu <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->page_name;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Icon" class="control-label col-lg-2">Icon </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->icon;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
            <div class="form-group">
                <label for="Jenis Menu" class="control-label col-lg-2">Jenis Menu </label>
                <div class="col-lg-10">
                <?php
                  $option = array(
'main' => 'Menu Parent',

'page' => 'Menu Halaman',
);
                  foreach ($option as $isi => $val) {
                  if ($data_edit->type_menu==$isi) {

                    echo "<input disabled class='form-control' type='text' value='$val'>";
                  }
               } ?>
                  </div>
            </div><!-- /.form-group -->
            <div class="form-group">
                        <label for="Menu Parent" class="control-label col-lg-2">Menu Parent <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("sys_menu") as $isi) {
                  if ($data_edit->parent==$isi->page_name) {

                    echo "<input disabled class='form-control' type='text' value='$isi->id'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->

          <div class="form-group">
              <label for="Urutan Menu" class="control-label col-lg-2">Urutan Menu <span style="color:#FF0000">*</span></label>
              <div class="col-lg-10">
                <input type="text" disabled="" value="<?=$data_edit->urutan_menu;?>" class="form-control">
              </div>
          </div><!-- /.form-group -->
          
                        
                      </form>
                      <a href="<?=base_index();?>setting-menu" class="btn btn-success "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>

                        </div>
                      </div>
                    </div>
                </div>

                </section><!-- /.content -->
