<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>Mahasiswa</h1>
                   <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>mahasiswa">Mahasiswa</a></li>
                        <li class="active">Detail Mahasiswa</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box box-solid box-primary">
                            <div class="box-header">
                            <h3 class="box-title">Detail Mahasiswa</h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>

                    <div class="box-body">
                      <form class="form-horizontal">
                        
              <div class="form-group">
                <label for="NIM" class="control-label col-lg-2">NIM</label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->nim;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Nama Lengkap" class="control-label col-lg-2">Nama Lengkap</label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->nama;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              <div class="form-group">
                        <label for="Jurusan" class="control-label col-lg-2">Jurusan</label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("jurusan") as $isi) {
                  if ($data_edit->jur_kode==$isi->kode_jur) {

                    echo "<input disabled class='form-control' type='text' value='$isi->nama_jur'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Jenis Daftar" class="control-label col-lg-2">Jenis Daftar</label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("jenis_daftar") as $isi) {
                  if ($data_edit->id_jns_daftar==$isi->id_jenis_daftar) {

                    echo "<input disabled class='form-control' type='text' value='$isi->nm_jns_daftar'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Jalur Masuk" class="control-label col-lg-2">Jalur Masuk</label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("jalur_masuk") as $isi) {
                  if ($data_edit->id_jalur_masuk==$isi->id_jalur_masuk) {

                    echo "<input disabled class='form-control' type='text' value='$isi->jalur'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Agama" class="control-label col-lg-2">Agama</label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("agama") as $isi) {
                  if ($data_edit->id_agama==$isi->id_agama) {

                    echo "<input disabled class='form-control' type='text' value='$isi->nm_agama'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Semester Masuk" class="control-label col-lg-2">Semester Masuk</label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("semester_ref") as $isi) {
                  if ($data_edit->mulai_smt==$isi->id_semester) {

                    echo "<input disabled class='form-control' type='text' value='$isi->id_semester'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->

          <div class="form-group">
              <label for="Tanggal Masuk " class="control-label col-lg-2">Tanggal Masuk </label>
              <div class="col-lg-10">
                <input type="text" disabled="" value="<?=tgl_indo($data_edit->tgl_masuk_sp);?>" class="form-control">
              </div>
          </div><!-- /.form-group -->
          
                <div class="form-group">
                  <label for="Jenis Kelamin" class="control-label col-lg-2">Jenis Kelamin</label>
                  <div class="col-lg-10">
                    <input type="text" disabled="" value="<?=$data_edit->jk;?>" class="form-control">
                  </div>
                </div><!-- /.form-group -->
                
              <div class="form-group">
                <label for="NISN" class="control-label col-lg-2">NISN</label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->nisn;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="NIK" class="control-label col-lg-2">NIK</label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->nik;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Tempat Lahir" class="control-label col-lg-2">Tempat Lahir</label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->tmpt_lahir;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
          <div class="form-group">
              <label for="Tanggal Lahir" class="control-label col-lg-2">Tanggal Lahir</label>
              <div class="col-lg-10">
                <input type="text" disabled="" value="<?=tgl_indo($data_edit->tgl_lahir);?>" class="form-control">
              </div>
          </div><!-- /.form-group -->
          
              <div class="form-group">
                <label for="Alamat" class="control-label col-lg-2">Alamat</label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->jln;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="RT" class="control-label col-lg-2">RT</label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->rt;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="RW" class="control-label col-lg-2">RW</label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->rw;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Nama Dusun" class="control-label col-lg-2">Nama Dusun</label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->nm_dsn;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Kelurahan" class="control-label col-lg-2">Kelurahan</label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->ds_kel;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Kode Pos" class="control-label col-lg-2">Kode Pos</label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->kode_pos;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Telepon Rumah" class="control-label col-lg-2">Telepon Rumah</label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->telepon_rumah;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Telepon Seluler" class="control-label col-lg-2">Telepon Seluler</label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->telepon_seluler;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Email" class="control-label col-lg-2">Email</label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->email;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
            <div class="form-group">
                <label for="Terima KPS" class="control-label col-lg-2">Terima KPS</label>
                <div class="col-lg-10">
                <?php if ($data_edit->a_terima_kps=="1") {
                  ?>
                  <input name="a_terima_kps" class="make-switch" disabled type="checkbox" checked>
                  <?php
                } else {
                  ?>
                  <input name="a_terima_kps" class="make-switch" disabled type="checkbox">
                  <?php
                }?>
                </div>
            </div><!-- /.form-group -->
            
              <div class="form-group">
                <label for="No KPS" class="control-label col-lg-2">No KPS</label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->no_kps;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              <div class="form-group">
                        <label for="Status Mahasiswa" class="control-label col-lg-2">Status Mahasiswa</label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("status_mhs") as $isi) {
                  if ($data_edit->stat_pd==$isi->kode) {

                    echo "<input disabled class='form-control' type='text' value='$isi->ket'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->

              <div class="form-group">
                <label for="Nama Ayah" class="control-label col-lg-2">Nama Ayah</label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->nm_ayah;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="NIK Ayah" class="control-label col-lg-2">NIK Ayah</label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->nik_ayah;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
          <div class="form-group">
              <label for="Tanggal Lahir ayah" class="control-label col-lg-2">Tanggal Lahir ayah</label>
              <div class="col-lg-10">
                <input type="text" disabled="" value="<?=tgl_indo($data_edit->tgl_lahir_ayah);?>" class="form-control">
              </div>
          </div><!-- /.form-group -->
          <div class="form-group">
                        <label for="Pendidikan Ayah" class="control-label col-lg-2">Pendidikan Ayah</label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("jenjang_pendidikan") as $isi) {
                  if ($data_edit->id_jenjang_pendidikan_ayah==$isi->id_jenjang) {

                    echo "<input disabled class='form-control' type='text' value='$isi->id_jenjang'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Pekerjaan Ayah" class="control-label col-lg-2">Pekerjaan Ayah</label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("pekerjaan") as $isi) {
                  if ($data_edit->id_pekerjaan_ayah==$isi->id_pekerjaan) {

                    echo "<input disabled class='form-control' type='text' value='$isi->pekerjaan'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="penghasilan wali" class="control-label col-lg-2">penghasilan wali</label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("penghasilan") as $isi) {
                  if ($data_edit->id_penghasilan_ayah==$isi->id_penghasilan) {

                    echo "<input disabled class='form-control' type='text' value='$isi->penghasilan'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->

              <div class="form-group">
                <label for="Ibu Kandung" class="control-label col-lg-2">Ibu Kandung</label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->nm_ibu_kandung;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="NIK Ibu Kandung" class="control-label col-lg-2">NIK Ibu Kandung</label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->nik_ibu_kandung;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
          <div class="form-group">
              <label for="Tanggal Lahir Ibu" class="control-label col-lg-2">Tanggal Lahir Ibu</label>
              <div class="col-lg-10">
                <input type="text" disabled="" value="<?=tgl_indo($data_edit->tgl_lahir_ibu);?>" class="form-control">
              </div>
          </div><!-- /.form-group -->
          <div class="form-group">
                        <label for="Pendidikan Ibu" class="control-label col-lg-2">Pendidikan Ibu</label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("jenjang_pendidikan") as $isi) {
                  if ($data_edit->id_jenjang_pendidikan_ibu==$isi->id_jenjang) {

                    echo "<input disabled class='form-control' type='text' value='$isi->jenjang'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Pekerjaan Ibu" class="control-label col-lg-2">Pekerjaan Ibu</label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("pekerjaan") as $isi) {
                  if ($data_edit->id_pekerjaan_ibu==$isi->id_pekerjaan) {

                    echo "<input disabled class='form-control' type='text' value='$isi->pekerjaan'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Penghasilan Ibu" class="control-label col-lg-2">Penghasilan Ibu</label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("penghasilan") as $isi) {
                  if ($data_edit->id_penghasilan_ibu==$isi->id_penghasilan) {

                    echo "<input disabled class='form-control' type='text' value='$isi->penghasilan'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->

              <div class="form-group">
                <label for="Nama Wali" class="control-label col-lg-2">Nama Wali</label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->nm_wali;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
          <div class="form-group">
              <label for="Tanggal Lahir Wali" class="control-label col-lg-2">Tanggal Lahir Wali</label>
              <div class="col-lg-10">
                <input type="text" disabled="" value="<?=tgl_indo($data_edit->tgl_lahir_wali);?>" class="form-control">
              </div>
          </div><!-- /.form-group -->
          
              <div class="form-group">
                <label for="nik_wali" class="control-label col-lg-2">nik_wali</label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->nik_wali;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
          <div class="form-group">
              <label for="Pendidikan Wali" class="control-label col-lg-2">Pendidikan Wali</label>
              <div class="col-lg-10">
                <input type="text" disabled="" value="<?=tgl_indo($data_edit->id_jenjang_pendidikan_wali);?>" class="form-control">
              </div>
          </div><!-- /.form-group -->
          <div class="form-group">
                        <label for="Pekerjaan Wali" class="control-label col-lg-2">Pekerjaan Wali</label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("pekerjaan") as $isi) {
                  if ($data_edit->id_pekerjaan_wali==$isi->id_pekerjaan) {

                    echo "<input disabled class='form-control' type='text' value='$isi->pekerjaan'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Penghasilan Wali" class="control-label col-lg-2">Penghasilan Wali</label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("penghasilan") as $isi) {
                  if ($data_edit->id_penghasilan_wali==$isi->id_penghasilan) {

                    echo "<input disabled class='form-control' type='text' value='$isi->penghasilan'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Jenis Tinggal" class="control-label col-lg-2">Jenis Tinggal</label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("jenis_tinggal") as $isi) {
                  if ($data_edit->id_jns_tinggal==$isi->id_jns_tinggal) {

                    echo "<input disabled class='form-control' type='text' value='$isi->jenis_tinggal'>";
                  }
               } ?>
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
                        <label for="dosen_pemb" class="control-label col-lg-2">dosen_pemb</label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("dosen") as $isi) {
                  if ($data_edit->dosen_pemb==$isi->id_dosen) {

                    echo "<input disabled class='form-control' type='text' value='$isi->nama_dosen'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->

                <div class="form-group">
                  <label for="Kewarganegaraan" class="control-label col-lg-2">Kewarganegaraan</label>
                  <div class="col-lg-10">
                    <input type="text" disabled="" value="<?=$data_edit->kewarganegaraan;?>" class="form-control">
                  </div>
                </div><!-- /.form-group -->
                
              <div class="form-group">
                <label for="NPWP" class="control-label col-lg-2">NPWP</label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->npwp;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
                        
                      </form>
                      <a href="<?=base_index();?>mahasiswa" class="btn btn-success "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>

                        </div>
                      </div>
                    </div>
                </div>

                </section><!-- /.content -->
