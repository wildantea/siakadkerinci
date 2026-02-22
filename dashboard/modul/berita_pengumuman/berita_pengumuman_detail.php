<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>Berita Pengumuman</h1>
                   <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>berita-pengumuman">Berita Pengumuman</a></li>
                        <li class="active">Detail Berita Pengumuman</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box box-solid box-primary">
                            <div class="box-header">
                            <h3 class="box-title">Detail Berita Pengumuman</h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>

                    <div class="box-body">
                      <form class="form-horizontal">
                        
              <div class="form-group">
                <label for="Judul Berita / Pengumuman" class="control-label col-lg-2">Judul Berita / Pengumuman <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->judul;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
          <div class="form-group">
              <label for="Isi Berita / Pengumuman" class="control-label col-lg-2">Isi Berita / Pengumuman <span style="color:#FF0000">*</span></label>
              <div class="col-lg-10">
                <textarea id="editbox" name="isi" disabled="" class="editbox"required><?=$data_edit->isi;?> </textarea>
              </div>
          </div><!-- /.form-group -->
          
            <div class="form-group">
                <label for="Tampil" class="control-label col-lg-2">Tampil </label>
                <div class="col-lg-10">
                <?php if ($data_edit->tampil=="Y") {
                  ?>
                  <input name="tampil" class="make-switch" disabled type="checkbox" checked>
                  <?php
                } else {
                  ?>
                  <input name="tampil" class="make-switch" disabled type="checkbox">
                  <?php
                }?>
                </div>
            </div><!-- /.form-group -->
            
                        
                      </form>
                      <a href="<?=base_index();?>berita-pengumuman" class="btn btn-success "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>

                        </div>
                      </div>
                    </div>
                </div>

                </section><!-- /.content -->
