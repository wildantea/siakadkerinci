<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>Program Studi</h1>
                   <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>program-studi">Program Studi</a></li>
                        <li class="active">Detail Program Studi</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box box-solid box-primary">
                            <div class="box-header">
                            <h3 class="box-title">Detail Program Studi</h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>

                    <div class="box-body">
                      <form class="form-horizontal">
                        
          <div class="form-group">
              <label for="Kode Jurusan Lokal" class="control-label col-lg-2">Kode Jurusan Lokal <span style="color:#FF0000">*</span></label>
              <div class="col-lg-10">
                <input type="text" disabled="" value="<?=$data_edit->kode_jur;?>" class="form-control">
              </div>
          </div><!-- /.form-group -->
          
          <div class="form-group">
              <label for="Kode Jurusan Dikti" class="control-label col-lg-2">Kode Jurusan Dikti <span style="color:#FF0000">*</span></label>
              <div class="col-lg-10">
                <input type="text" disabled="" value="<?=$data_edit->kode_dikti;?>" class="form-control">
              </div>
          </div><!-- /.form-group -->
          <div class="form-group">
                        <label for="Jenjang" class="control-label col-lg-2">Jenjang </label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("jenjang_pendidikan") as $isi) {
                  if ($data_edit->id_jenjang==$isi->id_jenjang) {

                    echo "<input disabled class='form-control' type='text' value='$isi->jenjang'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Fakultas" class="control-label col-lg-2">Fakultas </label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("fakultas") as $isi) {
                  if ($data_edit->fak_kode==$isi->kode_fak) {

                    echo "<input disabled class='form-control' type='text' value='$isi->nama_resmi'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->

            <div class="form-group">
                <label for="Status" class="control-label col-lg-2">Status </label>
                <div class="col-lg-10">
                <?php
                  $option = array(
'A' => 'Aktif',

'N' => 'Tidak Aktif',
);
                  foreach ($option as $isi => $val) {
                  if ($data_edit->status==$isi) {

                    echo "<input disabled class='form-control' type='text' value='$val'>";
                  }
               } ?>
                  </div>
            </div><!-- /.form-group -->
            
              <div class="form-group">
                <label for="Nama Program Studi" class="control-label col-lg-2">Nama Program Studi <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->nama_jur;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Nama Inggris" class="control-label col-lg-2">Nama Inggris </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->nama_jur_asing;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
          <div class="form-group">
              <label for="Sks Lulus" class="control-label col-lg-2">Sks Lulus </label>
              <div class="col-lg-10">
                <input type="text" disabled="" value="<?=$data_edit->sks_lulus;?>" class="form-control">
              </div>
          </div><!-- /.form-group -->
          
              <div class="form-group">
                <label for="Website" class="control-label col-lg-2">Website </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->web;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Email" class="control-label col-lg-2">Email </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->email;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Telp" class="control-label col-lg-2">Telp </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->telp;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Tanggal Berdiri" class="control-label col-lg-2">Tanggal Berdiri </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->tgl_berdiri;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="SK Penyelenggaraan" class="control-label col-lg-2">SK Penyelenggaraan </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->no_sk_dikti;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
          <div class="form-group">
              <label for="Tanggal SK" class="control-label col-lg-2">Tanggal SK </label>
              <div class="col-lg-10">
                <input type="text" disabled="" value="<?=tgl_indo($data_edit->tgl_sk_dikti);?>" class="form-control">
              </div>
          </div><!-- /.form-group -->
          <div class="form-group">
                        <label for="Ka Prodi" class="control-label col-lg-2">Ka Prodi </label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("dosen") as $isi) {
                  if ($data_edit->ketua_jurusan==$isi->id_dosen) {

                    echo "<input disabled class='form-control' type='text' value='$isi->nama_dosen'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->

                        
                      </form>
                      <a href="<?=base_index();?>program-studi" class="btn btn-success "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>

                        </div>
                      </div>
                    </div>
                </div>

                </section><!-- /.content -->
