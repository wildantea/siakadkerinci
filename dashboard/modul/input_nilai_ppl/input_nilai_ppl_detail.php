<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>Input Nilai PPL</h1>
                   <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>input-nilai-ppl">Input Nilai PPL</a></li>
                        <li class="active">Detail Input Nilai PPL</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box box-solid box-primary">
                            <div class="box-header">
                            <h3 class="box-title">Detail Input Nilai PPL</h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>

                    <div class="box-body">
                      <form class="form-horizontal">
                        
              <div class="form-group">
                <label for="tgl_awal_input_nilai" class="control-label col-lg-2">tgl_awal_input_nilai </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->tgl_awal_input_nilai;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="tgl_akhir_input_nilai" class="control-label col-lg-2">tgl_akhir_input_nilai </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->tgl_akhir_input_nilai;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="id_priode" class="control-label col-lg-2">id_priode </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->id_priode;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
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
              
                        
                      </form>
                      <a href="<?=base_index();?>input-nilai-ppl" class="btn btn-success "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>

                        </div>
                      </div>
                    </div>
                </div>

                </section><!-- /.content -->
