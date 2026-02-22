<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>Panduan Pembayaran</h1>
                   <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>panduan-pembayaran">Panduan Pembayaran</a></li>
                        <li class="active">Detail Panduan Pembayaran</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box box-solid box-primary">
                            <div class="box-header">
                            <h3 class="box-title">Detail Panduan Pembayaran</h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>

                    <div class="box-body">
                      <form class="form-horizontal">
                        <div class="form-group">
                        <label for="Bank" class="control-label col-lg-2">Bank <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("keu_bank") as $isi) {
                  if ($data_edit->id_bank==$isi->kode_bank) {

                    echo "<input disabled class='form-control' type='text' value='$isi->nama_singkat'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->

              <div class="form-group">
                <label for="Judul" class="control-label col-lg-2">Judul <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->judul;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
          <div class="form-group">
              <label for="Isi Panduan" class="control-label col-lg-2">Isi Panduan <span style="color:#FF0000">*</span></label>
              <div class="col-lg-10">
                <textarea id="editbox" name="isi_panduan" disabled="" class="editbox"required><?=$data_edit->isi_panduan;?> </textarea>
              </div>
          </div><!-- /.form-group -->
          
          <div class="form-group">
              <label for="Urutan" class="control-label col-lg-2">Urutan <span style="color:#FF0000">*</span></label>
              <div class="col-lg-10">
                <input type="text" disabled="" value="<?=$data_edit->urutan;?>" class="form-control">
              </div>
          </div><!-- /.form-group -->
          
              <div class="form-group">
                <label for="created" class="control-label col-lg-2">created </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->created;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="updated" class="control-label col-lg-2">updated </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->updated;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="creator" class="control-label col-lg-2">creator </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->creator;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="updator" class="control-label col-lg-2">updator </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->updator;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
                        
                      </form>
                      <a href="<?=base_index();?>panduan-pembayaran" class="btn btn-success "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>

                        </div>
                      </div>
                    </div>
                </div>

                </section><!-- /.content -->
