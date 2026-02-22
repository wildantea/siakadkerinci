<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>Setting Tagihan Prodi</h1>
                   <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>setting-tagihan-prodi">Setting Tagihan Prodi</a></li>
                        <li class="active">Detail Setting Tagihan Prodi</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box box-solid box-primary">
                            <div class="box-header">
                            <h3 class="box-title">Detail Setting Tagihan Prodi</h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>

                    <div class="box-body">
                      <form class="form-horizontal">
                        <div class="form-group">
                        <label for="Program Studi" class="control-label col-lg-2">Program Studi <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("view_prodi_jenjang") as $isi) {
                  if ($data_edit->kode_prodi==$isi->kode_jur) {

                    echo "<input disabled class='form-control' type='text' value='$isi->jurusan'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Jenis Tagihan" class="control-label col-lg-2">Jenis Tagihan <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("keu_jenis_tagihan") as $isi) {
                  if ($data_edit->kode_tagihan==$isi->kode_tagihan) {

                    echo "<input disabled class='form-control' type='text' value='$isi->nama_tagihan'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->

              <div class="form-group">
                <label for="Nominal Tagihan" class="control-label col-lg-2">Nominal Tagihan <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->nominal_tagihan;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Tagihan Untuk Angkatan" class="control-label col-lg-2">Tagihan Untuk Angkatan <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->berlaku_angkatan;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
                        
                      </form>
                      <a href="<?=base_index();?>setting-tagihan-prodi" class="btn btn-success "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>

                        </div>
                      </div>
                    </div>
                </div>

                </section><!-- /.content -->
