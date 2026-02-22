<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>Kurikulum</h1>
                   <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>kurikulum">Kurikulum</a></li>
                        <li class="active">Detail Kurikulum</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box box-solid box-primary">
                            <div class="box-header">
                            <h3 class="box-title">Detail Kurikulum</h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>

                    <div class="box-body">
                      <form class="form-horizontal">
                        <div class="form-group">
                        <label for="Jurusan" class="control-label col-lg-2">Jurusan</label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("jurusan") as $isi) {
                  if ($data_edit->kode_jur==$isi->kode_jur) {

                    echo "<input disabled class='form-control' type='text' value='$isi->nama_jur'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Semester Berlaku" class="control-label col-lg-2">Semester Berlaku</label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("semester") as $isi) {
                  if ($data_edit->sem_id==$isi->sem_id) {

                    echo "<input disabled class='form-control' type='text' value='$isi->id_semester'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->

              <div class="form-group">
                <label for="Nama Kurikulum" class="control-label col-lg-2">Nama Kurikulum</label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->nama_kurikulum;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Tahun Berlaku" class="control-label col-lg-2">Tahun Berlaku</label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->tahun_mulai_berlaku;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="No SK Rektor" class="control-label col-lg-2">No SK Rektor</label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->no_sk_rektor;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
          <div class="form-group">
              <label for="Tanggal SK Rektor" class="control-label col-lg-2">Tanggal SK Rektor</label>
              <div class="col-lg-10">
                <input type="text" disabled="" value="<?=tgl_indo($data_edit->tgl_sk_rektor);?>" class="form-control">
              </div>
          </div><!-- /.form-group -->
          
              <div class="form-group">
                <label for="Tanggal Disetujui" class="control-label col-lg-2">Tanggal Disetujui</label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->tgl_disetujui;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Yang Menyetujui" class="control-label col-lg-2">Yang Menyetujui</label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->yang_menyetujui;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="No SK Dikti" class="control-label col-lg-2">No SK Dikti</label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->no_sk_dikti;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
          <div class="form-group">
              <label for="Tanggal SK DIKTI" class="control-label col-lg-2">Tanggal SK DIKTI</label>
              <div class="col-lg-10">
                <input type="text" disabled="" value="<?=tgl_indo($data_edit->tgl_sk_dikti);?>" class="form-control">
              </div>
          </div><!-- /.form-group -->
          
              <div class="form-group">
                <label for="Masa Studi Ideal" class="control-label col-lg-2">Masa Studi Ideal</label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->masa_studi_ideal;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Masa Studi Max" class="control-label col-lg-2">Masa Studi Max</label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->masa_studi_max;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Gelar Dipakai" class="control-label col-lg-2">Gelar Dipakai</label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->gelar_dipakai;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Ket" class="control-label col-lg-2">Ket</label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->ket;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
          <div class="form-group">
              <label for="Jml SKS Wajib" class="control-label col-lg-2">Jml SKS Wajib</label>
              <div class="col-lg-10">
                <input type="text" disabled="" value="<?=$data_edit->jml_sks_wajib;?>" class="form-control">
              </div>
          </div><!-- /.form-group -->
          
          <div class="form-group">
              <label for="Jml SKS Pilihan" class="control-label col-lg-2">Jml SKS Pilihan</label>
              <div class="col-lg-10">
                <input type="text" disabled="" value="<?=$data_edit->jml_sks_pilihan;?>" class="form-control">
              </div>
          </div><!-- /.form-group -->
          
          <div class="form-group">
              <label for="Total SKS" class="control-label col-lg-2">Total SKS</label>
              <div class="col-lg-10">
                <input type="text" disabled="" value="<?=$data_edit->total_sks;?>" class="form-control">
              </div>
          </div><!-- /.form-group -->
          
                        
                      </form>
                      <a href="<?=base_index();?>kurikulum" class="btn btn-success "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>

                        </div>
                      </div>
                    </div>
                </div>

                </section><!-- /.content -->
