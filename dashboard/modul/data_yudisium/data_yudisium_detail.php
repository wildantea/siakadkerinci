<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>Data Yudisium</h1>
                   <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>data-yudisium">Data Yudisium</a></li>
                        <li class="active">Detail Data Yudisium</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box box-solid box-primary">
                            <div class="box-header">
                            <h3 class="box-title">Detail Data Yudisium</h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>

                    <div class="box-body">
                      <form class="form-horizontal">

              <div class="form-group">
                <label for="nim" class="control-label col-lg-2">nim <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->nim;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              <div class="form-group">
                        <label for="kode_fak" class="control-label col-lg-2">Fakultas <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("fakultas") as $isi) {
                  if ($data_edit->kode_fak==$isi->kode_fak) {

                    echo "<input disabled class='form-control' type='text' value='$isi->nama_resmi'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="kode_jurusan" class="control-label col-lg-2">Jurusan <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("jurusan") as $isi) {
                  if ($data_edit->kode_jurusan==$isi->kode_jur) {

                    echo "<input disabled class='form-control' type='text' value='$isi->nama_jur'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->

              <div class="form-group">
                <label for="nama" class="control-label col-lg-2">Nama </label>
                <div class="col-lg-10">
                  <?php foreach ($db->fetch_all("mahasiswa") as $isi) {
                      if ($data_edit->nim==$isi->nim) {

                        echo "<input disabled class='form-control' type='text' value='$isi->nama'>";
                      }
                   } ?>
                </div>
              </div><!-- /.form-group -->

            <div class="form-group">
                <label for="id_jenis_keluar" class="control-label col-lg-2">Jenis Keluar <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                <?php
                  if($data_edit->id_jenis_keluar != NULL){
                    foreach ($db->fetch_all('jenis_keluar') as $isi) {
                      if($data_edit->id_jenis_keluar == $isi->id_jns_keluar){
                        echo "<input class=\"form-control\" type=\"text\" disabled='' value='$isi->ket_keluar'></input>";
                      }
                    }
                  } else{
                      echo "<input class=\"form-control\" type=\"text\" disabled='' value='NULL'></input>";
                  }
                ?>
                  </div>
            </div><!-- /.form-group -->

              <div class="form-group">
                <label for="tanggal_keluar" class="control-label col-lg-2">Tanggal Keluar <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->tanggal_keluar;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->

              <div class="form-group">
                <label for="sk_yudisium" class="control-label col-lg-2">SK Yudisium <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->sk_yudisium;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->

              <div class="form-group">
                <label for="tgl_sk_yudisium" class="control-label col-lg-2">Tgl SK Yudisium <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->tgl_sk_yudisium;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->

              <div class="form-group">
                <label for="ipk" class="control-label col-lg-2">ipk </label>
                <div class="col-lg-10">
                  <?php
                    if($data_edit->nim != NULL){
                      $nim=$data_edit->nim;
                      foreach ($db->query("select * from akm where akm_id IN (select max(akm_id) from akm WHERE mhs_nim='$nim')") as $isi) {
                        if($data_edit->nim == $isi->mhs_nim){
                          echo "<input class=\"form-control\" type=\"text\" disabled='' value='$isi->ipk'></input>";
                        }
                      }
                    } else{
                      echo "<input class=\"form-control\" type=\"text\" disabled='' value='NULL'></input>";
                    }
                  ?>
                </div>
              </div><!-- /.form-group -->

              <div class="form-group">
                <label for="no_seri_ijasah" class="control-label col-lg-2">no_seri_ijasah </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->no_seri_ijasah;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->

              <div class="form-group">
                <label for="jalur_skripsi" class="control-label col-lg-2">jalur_skripsi <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <?php
                    if ($data_edit->jalur_skripsi != NULL) {
                      foreach ($db->fetch_all('jenis_skripsi') as $isi) {
                        if($data_edit->jalur_skripsi == $isi->id_jenis_skripsi){
                          echo "<input type='text' disabled='' value='<?=$isi->ket_jenis_skripsi;?>' class='form-control'>";
                        }
                      }
                    } else{
                      echo "<input type='text' disabled='' value='NULL' class='form-control'>";
                    }
                  ?>
                </div>
              </div><!-- /.form-group -->

              <div class="form-group">
                <label for="judul_skripsi" class="control-label col-lg-2">judul_skripsi </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->judul_ta;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->

              <div class="form-group">
                <label for="bulan_awal_bimbingan" class="control-label col-lg-2">bulan_awal_bimbingan </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->bulan_awal_bimbingan;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->

              <div class="form-group">
                <label for="bulan_akhir_bimbingan" class="control-label col-lg-2">bulan_akhir_bimbingan </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->bulan_akhir_bimbingan;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->

              <div class="form-group">
                <label for="pembimbing_1" class="control-label col-lg-2">pembimbing_1 <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <?php
                    foreach ($db->fetch_all('dosen') as $isi) {
                      if($data_edit->pembimbing_1 == $isi->nidn) {
                        echo "<input type='text' disabled='' value='<?=$isi->nama_dosem;?>' class='form-control'>";
                      }
                    }
                  ?>
                  <input type='text' disabled='' value='NULL' class='form-control'>
                </div>
              </div><!-- /.form-group -->

              <div class="form-group">
                <label for="pembimbing_2" class="control-label col-lg-2">pembimbing_2 <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <?php
                    foreach ($db->fetch_all('dosen') as $isi) {
                      if($data_edit->pembimbing_2 == $isi->nidn) {
                        echo "<input type='text' disabled='' value='<?=$isi->nama_dosem;?>' class='form-control'>";
                      }
                    }
                  ?>
                  <input type='text' disabled='' value='NULL' class='form-control'>
                </div>
              </div><!-- /.form-group -->

                      </form>
                      <a href="<?=base_index();?>data-yudisium" class="btn btn-success "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>

                        </div>
                      </div>
                    </div>
                </div>

                </section><!-- /.content -->
