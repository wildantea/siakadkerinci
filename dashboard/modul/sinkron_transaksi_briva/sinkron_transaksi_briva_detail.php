<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>Sinkron Transaksi Briva</h1>
                   <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>sinkron-transaksi-briva">Sinkron Transaksi Briva</a></li>
                        <li class="active">Detail Sinkron Transaksi Briva</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box box-solid box-primary">
                            <div class="box-header">
                            <h3 class="box-title">Detail Sinkron Transaksi Briva</h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>

                    <div class="box-body">
                      <form class="form-horizontal">
                        
              <div class="form-group">
                <label for="no_briva" class="control-label col-lg-2">no_briva </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->no_briva;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="nama" class="control-label col-lg-2">nama </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->nama;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="jumlah" class="control-label col-lg-2">jumlah </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->jumlah;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="tgl_bayar" class="control-label col-lg-2">tgl_bayar </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->tgl_bayar;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="teller_id" class="control-label col-lg-2">teller_id </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->teller_id;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="id_keu_tagihan_mhs" class="control-label col-lg-2">id_keu_tagihan_mhs </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->id_keu_tagihan_mhs;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="norek" class="control-label col-lg-2">norek </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->norek;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
                        
                      </form>
                      <a href="<?=base_index();?>sinkron-transaksi-briva" class="btn btn-success "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>

                        </div>
                      </div>
                    </div>
                </div>

                </section><!-- /.content -->
