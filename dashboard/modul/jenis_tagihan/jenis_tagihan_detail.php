<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>Jenis Tagihan</h1>
                   <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>jenis-tagihan">Jenis Tagihan</a></li>
                        <li class="active">Detail Jenis Tagihan</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box box-solid box-primary">
                            <div class="box-header">
                            <h3 class="box-title">Detail Jenis Tagihan</h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>

                    <div class="box-body">
                      <form class="form-horizontal">
                        
              <div class="form-group">
                <label for="Kode Tagihan" class="control-label col-lg-2">Kode Tagihan <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->kode_tagihan;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Nama Tagihan" class="control-label col-lg-2">Nama Tagihan <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->nama_tagihan;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              <div class="form-group">
                        <label for="Jenis Pembayaran" class="control-label col-lg-2">Jenis Pembayaran <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("keu_jenis_pembayaran") as $isi) {
                  if ($data_edit->kode_pembayaran==$isi->kode_pembayaran) {

                    echo "<input disabled class='form-control' type='text' value='$isi->nama_pembayaran'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->

            <div class="form-group">
                <label for="Sebagai Syarat Berhak KRS" class="control-label col-lg-2">Sebagai Syarat Berhak KRS </label>
                <div class="col-lg-10">
                <?php if ($data_edit->syarat_krs=="Y") {
                  ?>
                  <input name="syarat_krs" class="make-switch" disabled type="checkbox" checked>
                  <?php
                } else {
                  ?>
                  <input name="syarat_krs" class="make-switch" disabled type="checkbox">
                  <?php
                }?>
                </div>
            </div><!-- /.form-group -->
            
                        
                      </form>
                      <a href="<?=base_index();?>jenis-tagihan" class="btn btn-success "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>

                        </div>
                      </div>
                    </div>
                </div>

                </section><!-- /.content -->
