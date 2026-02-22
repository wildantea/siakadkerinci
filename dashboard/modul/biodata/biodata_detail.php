<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>Biodata</h1>
                   <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>biodata">Biodata</a></li>
                        <li class="active">Detail Biodata</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box box-solid box-primary">
                            <div class="box-header">
                            <h3 class="box-title">Detail Biodata</h3>
                            </div>

                    <div class="box-body">
                      <form class="form-horizontal">
                     <div class="form-group">
                        <label for="Dosen Pembimbing" class="control-label col-lg-2">&nbsp;</label>
                        <div class="col-lg-10">
               <a href="<?=base_index();?>biodata/edit" class="btn btn-success "><i class="fa fa-pencil"></i> Ubah Biodata</a>
              </div>
                      </div><!-- /.form-group -->        
              <div class="form-group">
                <label for="NIM" class="control-label col-lg-2">NIM </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->nim;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Nama Lengkap" class="control-label col-lg-2">Nama Lengkap <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->nama;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
            <div class="form-group">
                <label for="Jenis Kelamin" class="control-label col-lg-2">Jenis Kelamin </label>
                <div class="col-lg-10">
                <?php
                  $option = array(
'L' => 'Laki - Laki',

'P' => 'Perempuan',
);
                  foreach ($option as $isi => $val) {
                  if ($data_edit->jk==$isi) {

                    echo "<input disabled class='form-control' type='text' value='$val'>";
                  }
               } ?>
                  </div>
            </div><!-- /.form-group -->
            
              <div class="form-group">
                <label for="NIK KTP" class="control-label col-lg-2">NIK KTP <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->nik;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="NISN" class="control-label col-lg-2">NISN </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->nisn;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="NPWP" class="control-label col-lg-2">NPWP </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->npwp;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              <div class="form-group">
                        <label for="Kewarganegaraan" class="control-label col-lg-2">Kewarganegaraan <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("kewarganegaraan") as $isi) {
                  if ($data_edit->kewarganegaraan==$isi->kewarganegaraan) {

                    echo "<input disabled class='form-control' type='text' value='$isi->nm_wil'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Jalur Masuk" class="control-label col-lg-2">Jalur Masuk <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("jalur_masuk") as $isi) {
                  if ($data_edit->id_jalur_masuk==$isi->id_jalur_masuk) {

                    echo "<input disabled class='form-control' type='text' value='$isi->jalur'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->

              <div class="form-group">
                <label for="Kota/Kab Tempat Lahir " class="control-label col-lg-2">Kota/Kab Tempat Lahir  <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->tmpt_lahir;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
          <div class="form-group">
              <label for="Tanggal Lahir" class="control-label col-lg-2">Tanggal Lahir <span style="color:#FF0000">*</span></label>
              <div class="col-lg-10">
                <input type="text" disabled="" value="<?=tgl_indo($data_edit->tgl_lahir);?>" class="form-control">
              </div>
          </div><!-- /.form-group -->
          <div class="form-group">
                        <label for="Agama" class="control-label col-lg-2">Agama <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("agama") as $isi) {
                  if ($data_edit->id_agama==$isi->id_agama) {

                    echo "<input disabled class='form-control' type='text' value='$isi->nm_agama'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Kecamatan" class="control-label col-lg-2">Kecamatan </label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("data_wilayah") as $isi) {
                  if ($data_edit->id_wil==$isi->id) {

                    echo "<input disabled class='form-control' type='text' value='$isi->nm_wil'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->

              <div class="form-group">
                <label for="Alamat Jalan" class="control-label col-lg-2">Alamat Jalan <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->jln;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="RT" class="control-label col-lg-2">RT </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->rt;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="RW" class="control-label col-lg-2">RW </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->rw;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Dusun" class="control-label col-lg-2">Dusun </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->nm_dsn;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Kelurahan" class="control-label col-lg-2">Kelurahan </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->ds_kel;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Kode POS" class="control-label col-lg-2">Kode POS <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->kode_pos;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              <div class="form-group">
                        <label for="Jenis Tinggal" class="control-label col-lg-2">Jenis Tinggal </label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("jenis_tinggal") as $isi) {
                  if ($data_edit->id_jns_tinggal==$isi->id_jns_tinggal) {

                    echo "<input disabled class='form-control' type='text' value='$isi->jenis_tinggal'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->

              <div class="form-group">
                <label for="No Telepon Rumah" class="control-label col-lg-2">No Telepon Rumah </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->telepon_rumah;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="No Handphone" class="control-label col-lg-2">No Handphone <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->telepon_seluler;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Email" class="control-label col-lg-2">Email <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->email;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
            <div class="form-group">
                <label for="Penerima KPS (Kartu Perlindungan Sosial) ?" class="control-label col-lg-2">Penerima KPS (Kartu Perlindungan Sosial) ? </label>
                <div class="col-lg-10">
                <?php
                  $option = array(
'0' => 'Tidak',

'1' => 'Iya',
);
                  foreach ($option as $isi => $val) {
                  if ($data_edit->a_terima_kps==$isi) {

                    echo "<input disabled class='form-control' type='text' value='$val'>";
                  }
               } ?>
                  </div>
            </div><!-- /.form-group -->
            
              <div class="form-group">
                <label for="No KPS" class="control-label col-lg-2">No KPS </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->no_kps;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              <div class="form-group">
                        <label for="Jenis Pendaftaran" class="control-label col-lg-2">Jenis Pendaftaran </label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("jenis_daftar") as $isi) {
                  if ($data_edit->id_jns_daftar==$isi->id_jenis_daftar) {

                    echo "<input disabled class='form-control' type='text' value='$isi->nm_jns_daftar'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->

              <div class="form-group">
                <label for="NIK Ayah" class="control-label col-lg-2">NIK Ayah </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->nik_ayah;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Nama Ayah" class="control-label col-lg-2">Nama Ayah </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->nm_ayah;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
          <div class="form-group">
              <label for="Tanggal Lahir Ayah" class="control-label col-lg-2">Tanggal Lahir Ayah </label>
              <div class="col-lg-10">
                <input type="text" disabled="" value="<?=tgl_indo($data_edit->tgl_lahir_ayah);?>" class="form-control">
              </div>
          </div><!-- /.form-group -->
          <div class="form-group">
                        <label for="Pendidikan Ayah" class="control-label col-lg-2">Pendidikan Ayah </label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("jenjang_pendidikan") as $isi) {
                  if ($data_edit->id_jenjang_pendidikan_ayah==$isi->id_jenjang) {

                    echo "<input disabled class='form-control' type='text' value='$isi->jenjang'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Pekerjaan Ayah" class="control-label col-lg-2">Pekerjaan Ayah </label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("pekerjaan") as $isi) {
                  if ($data_edit->id_pekerjaan_ayah==$isi->id_pekerjaan) {

                    echo "<input disabled class='form-control' type='text' value='$isi->pekerjaan'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Penghasilan Ayah" class="control-label col-lg-2">Penghasilan Ayah </label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("penghasilan") as $isi) {
                  if ($data_edit->id_penghasilan_ayah==$isi->id_penghasilan) {

                    echo "<input disabled class='form-control' type='text' value='$isi->penghasilan'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->

              <div class="form-group">
                <label for="NIK Ibu" class="control-label col-lg-2">NIK Ibu </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->nik_ibu_kandung;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Nama Ibu " class="control-label col-lg-2">Nama Ibu  <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->nm_ibu_kandung;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
          <div class="form-group">
              <label for="Tanggal Lahir Ibu" class="control-label col-lg-2">Tanggal Lahir Ibu </label>
              <div class="col-lg-10">
                <input type="text" disabled="" value="<?=tgl_indo($data_edit->tgl_lahir_ibu);?>" class="form-control">
              </div>
          </div><!-- /.form-group -->
          <div class="form-group">
                        <label for="Pendidikan Ibu" class="control-label col-lg-2">Pendidikan Ibu </label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("jenjang_pendidikan") as $isi) {
                  if ($data_edit->id_jenjang_pendidikan_ibu==$isi->id_jenjang) {

                    echo "<input disabled class='form-control' type='text' value='$isi->jenjang'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Pekerjaan Ibu" class="control-label col-lg-2">Pekerjaan Ibu </label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("pekerjaan") as $isi) {
                  if ($data_edit->id_pekerjaan_ibu==$isi->id_pekerjaan) {

                    echo "<input disabled class='form-control' type='text' value='$isi->pekerjaan'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Penghasilan Ibu" class="control-label col-lg-2">Penghasilan Ibu </label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("penghasilan") as $isi) {
                  if ($data_edit->id_penghasilan_ibu==$isi->id_penghasilan) {

                    echo "<input disabled class='form-control' type='text' value='$isi->penghasilan'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->

              <div class="form-group">
                <label for="Nama Wali" class="control-label col-lg-2">Nama Wali </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->nm_wali;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
          <div class="form-group">
              <label for="Tanggal Lahir Wali" class="control-label col-lg-2">Tanggal Lahir Wali </label>
              <div class="col-lg-10">
                <input type="text" disabled="" value="<?=tgl_indo($data_edit->tgl_lahir_wali);?>" class="form-control">
              </div>
          </div><!-- /.form-group -->
          <div class="form-group">
                        <label for="Jenjang Pendidikan Wali" class="control-label col-lg-2">Jenjang Pendidikan Wali </label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("jenjang_pendidikan") as $isi) {
                  if ($data_edit->id_jenjang_pendidikan_wali==$isi->id_jenjang) {

                    echo "<input disabled class='form-control' type='text' value='$isi->jenjang'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Pekerjaan Wali" class="control-label col-lg-2">Pekerjaan Wali </label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("pekerjaan") as $isi) {
                  if ($data_edit->id_pekerjaan_wali==$isi->id_pekerjaan) {

                    echo "<input disabled class='form-control' type='text' value='$isi->pekerjaan'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Penghasilan Wali" class="control-label col-lg-2">Penghasilan Wali </label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("penghasilan") as $isi) {
                  if ($data_edit->id_penghasilan_wali==$isi->id_penghasilan) {

                    echo "<input disabled class='form-control' type='text' value='$isi->penghasilan'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Dosen Pembimbing" class="control-label col-lg-2">Dosen Pembimbing </label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("dosen") as $isi) {
                  if ($data_edit->dosen_pemb==$isi->id_dosen) {

                    echo "<input disabled class='form-control' type='text' value='$isi->gelar_depan $isi->nama_dosen $isi->gelar_belakang'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->

                        <div class="form-group">
                        <label for="Dosen Pembimbing" class="control-label col-lg-2">&nbsp;</label>
                        <div class="col-lg-10">
               <a href="<?=base_index();?>biodata/edit" class="btn btn-success "><i class="fa fa-pencil"></i> Ubah Biodata</a>
              </div>
                      </div><!-- /.form-group -->
                      </form>
                        </div>
                      </div>
                    </div>
                </div>

                </section><!-- /.content -->
