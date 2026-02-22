<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>Matkul</h1>
                   <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>matkul">Matkul</a></li>
                        <li class="active">Detail Matkul</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box box-solid box-primary">
                            <div class="box-header">
                            <h3 class="box-title">Detail Matkul</h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>

                    <div class="box-body">
                      <form class="form-horizontal">
                        
              <div class="form-group">
                <label for="Kode MK" class="control-label col-lg-2">Kode MK</label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->id_matkul;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              <div class="form-group">
                        <label for="Kurikulum" class="control-label col-lg-2">Kurikulum</label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("kurikulum") as $isi) {
                  if ($data_edit->kur_id==$isi->kur_id) {

                    echo "<input disabled class='form-control' type='text' value='$isi->nama_kurikulum'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->

              <div class="form-group">
                <label for="Kode Matakuliah" class="control-label col-lg-2">Kode Matakuliah</label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->kode_mk;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              <div class="form-group">
                        <label for="Jenjang" class="control-label col-lg-2">Jenjang</label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("jenjang") as $isi) {
                  if ($data_edit->id_jenjang==$isi->idjenjang) {

                    echo "<input disabled class='form-control' type='text' value='$isi->jenjang'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Tipe Mata Kuliah" class="control-label col-lg-2">Tipe Mata Kuliah</label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("tipe_matkul") as $isi) {
                  if ($data_edit->id_tipe_matkul==$isi->id_tipe_matkul) {

                    echo "<input disabled class='form-control' type='text' value='$isi->tipe_matkul'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->

              <div class="form-group">
                <label for="Semester" class="control-label col-lg-2">Semester</label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->semester;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Tahun" class="control-label col-lg-2">Tahun</label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->tahun;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Nama Matakuliah" class="control-label col-lg-2">Nama Matakuliah</label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->nama_mk;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Nama Asing" class="control-label col-lg-2">Nama Asing</label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->nama_asing;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="SKS Tatap Muka" class="control-label col-lg-2">SKS Tatap Muka</label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->sks_tm;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="SKS Praktek" class="control-label col-lg-2">SKS Praktek</label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->sks_prak;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="SKS Praktek Lapangan" class="control-label col-lg-2">SKS Praktek Lapangan</label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->sks_prak_lap;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="SKS Simulasi" class="control-label col-lg-2">SKS Simulasi</label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->sks_sim;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
            <div class="form-group">
                <label for="Ada SAP" class="control-label col-lg-2">Ada SAP</label>
                <div class="col-lg-10">
                <?php if ($data_edit->a_sap=="1") {
                  ?>
                  <input name="a_sap" class="make-switch" disabled type="checkbox" checked>
                  <?php
                } else {
                  ?>
                  <input name="a_sap" class="make-switch" disabled type="checkbox">
                  <?php
                }?>
                </div>
            </div><!-- /.form-group -->
            
            <div class="form-group">
                <label for="Ada Silabus" class="control-label col-lg-2">Ada Silabus</label>
                <div class="col-lg-10">
                <?php if ($data_edit->a_silabus=="1") {
                  ?>
                  <input name="a_silabus" class="make-switch" disabled type="checkbox" checked>
                  <?php
                } else {
                  ?>
                  <input name="a_silabus" class="make-switch" disabled type="checkbox">
                  <?php
                }?>
                </div>
            </div><!-- /.form-group -->
            
            <div class="form-group">
                <label for="Ada Bahan Ajar" class="control-label col-lg-2">Ada Bahan Ajar</label>
                <div class="col-lg-10">
                <?php if ($data_edit->a_bahan_ajar=="1") {
                  ?>
                  <input name="a_bahan_ajar" class="make-switch" disabled type="checkbox" checked>
                  <?php
                } else {
                  ?>
                  <input name="a_bahan_ajar" class="make-switch" disabled type="checkbox">
                  <?php
                }?>
                </div>
            </div><!-- /.form-group -->
            
              <div class="form-group">
                <label for="Acara prakata dikdat" class="control-label col-lg-2">Acara prakata dikdat</label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->acara_prakata_dikdat;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Keterangan" class="control-label col-lg-2">Keterangan</label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->ket;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
                        
                      </form>
                      <a href="<?=base_index();?>matkul" class="btn btn-success "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>

                        </div>
                      </div>
                    </div>
                </div>

                </section><!-- /.content -->
