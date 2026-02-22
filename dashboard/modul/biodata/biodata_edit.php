<style type="text/css">
  .select2-container .select2-selection--single {
    height: 34px;
}
</style>
<!-- Content Header (Page header) -->
              <section class="content-header">
                  <h1>Biodata</h1>
                    <ol class="breadcrumb">
                        <li>
                        <a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a>
                        </li>
                        <li>
                        <a href="<?=base_index();?>biodata">Biodata</a>
                        </li>
                        <li class="active">Edit Biodata</li>
                    </ol>
              </section>

              <!-- Main content -->
              <section class="content">
              <div class="row">
                  <div class="col-lg-12">
                      <div class="box box-solid box-primary">
                          <div class="box-header">
                              <h3 class="box-title">Edit Biodata</h3>
                          </div>


                      <div class="box-body">
                          <form id="edit_biodata" method="post" class="form-horizontal" action="<?=base_admin();?>modul/biodata/biodata_action.php?act=up">
              <div class="form-group">

                     
                        <div class="col-lg-12">

<div class="callout callout-info">
                <h4>Data Diri</h4>

                <p> Silakan isi data diri Anda sesuai dengan <strong>KTP</strong> atau <strong>Kartu Keluarga (KK)</strong>. <br>
    Pastikan seluruh kolom yang diberi tanda bintang merah <span style="color:#FF0000">*</span> diisi dengan benar.</p>
              </div>





                        </div>
                      </div><!-- /.form-group -->         
              <div class="form-group">
                <label for="NIM" class="control-label col-lg-2">NIM </label>
                <div class="col-lg-10">
                  <input type="text" name="nim" readonly="" value="<?=$data_edit->nim;?>" class="form-control" >
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Nama Lengkap" class="control-label col-lg-2">Nama Lengkap <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="nama" value="<?=$data_edit->nama;?>" class="form-control" required>
                  <p class="help-block" style="color:#f00">Nama Lengkap Wajib Sesuai Ijazah</p>
                </div>
              </div><!-- /.form-group -->
              
            <div class="form-group">
                <label for="Jenis Kelamin" class="control-label col-lg-2">Jenis Kelamin </label>
                <div class="col-lg-10">
                    <select name="jk" data-placeholder="Pilih Jenis Kelamin..." class="form-control chzn-select" tabindex="2" >
                      <option value=""></option>
                     <?php
                     $option = array(
'L' => 'Laki - Laki',

'P' => 'Perempuan',
);
                     foreach ($option as $isi => $val) {

                        if ($data_edit->jk==$isi) {
                          echo "<option value='$data_edit->jk' selected>$val</option>";
                        } else {
                       echo "<option value='$isi'>$val</option>";
                          }
                     } ?>
                    </select>
                  </div>
            </div><!-- /.form-group -->
            
            <div class="form-group">
                <label for="Jenis Kelamin" class="control-label col-lg-2">Status Pernikahan <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                    <select name="status_pernikahan" id="status_pernikahan" data-placeholder="Pilih Status..." class="form-control chzn-select" tabindex="2" required>
                      <option value=""></option>
                     <?php
                     $option = array(
'B' => 'Belum Menikah',

'M' => 'Menikah',
'D' => 'Duda/Janda'
);
                     foreach ($option as $isi => $val) {
                        if ($data_edit->status_pernikahan==$isi) {
                          echo "<option value='$data_edit->status_pernikahan' selected>$val</option>";
                        } else {
                       echo "<option value='$isi'>$val</option>";
                          }
                     } ?>
                    </select>
                  </div>
            </div><!-- /.form-group -->

<div class="form-group">
                        <label for="Nomor KTP" class="control-label col-lg-2">NIK KTP  <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-10">
                          <input type="text" data-rule-number="true" name="nik" value="<?=$data_edit->nik;?>" class="form-control" required minlength="16" maxlength="16" onkeypress="return isNumberKey(event)"> 
                        </div>
                      </div><!-- /.form-group -->
              
<div class="form-group">
                        <label for="Nomor KTP" class="control-label col-lg-2">NISN</label>
                        <div class="col-lg-10">
                          <input type="text" name="nisn" data-rule-number="true" placeholder="No Induk Siswa Nasional" value="<?=$data_edit->nisn;?>" class="form-control" onkeypress="return isNumberKey(event)" maxlength="10"> 
                          <p class="help-block" style="color:#f00">Wajib isi jika ada, jika tidak isi dengan 0000000000</p>
                        </div>
                      </div><!-- /.form-group -->
              
<div class="form-group">
                        <label for="Nomor KTP" class="control-label col-lg-2">NPWP</label>
                        <div class="col-lg-10">
                          <input type="text" name="npwp" placeholder="NPWP" value="<?=$data_edit->npwp;?>" class="form-control" onkeypress="return isNumberKey(event)"> 
                        </div>
                      </div><!-- /.form-group -->
              <div class="form-group">
                        <label for="Kewarganegaraan" class="control-label col-lg-2">Kewarganegaraan <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-10">
              <select name="kewarganegaraan" id="kewarganegaraan" data-placeholder="Pilih Kewarganegaraan..." class="form-control chzn-select" tabindex="2" required>
               <option value=""></option>
               <?php foreach ($db->fetch_all("kewarganegaraan") as $isi) {

                  if ($data_edit->kewarganegaraan==$isi->kewarganegaraan) {
                    echo "<option value='$isi->kewarganegaraan' selected>$isi->nm_wil</option>";
                  } else {
                      if ($data_edit->kewarganegaraan=="" && $isi->kewarganegaraan=='ID') {
                        echo "<option value='$isi->kewarganegaraan' selected>$isi->nm_wil</option>";
                      } else {
                        echo "<option value='$isi->kewarganegaraan'>$isi->nm_wil</option>";
                      }
                    }
               } ?>
              </select>
          </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Jalur Masuk" class="control-label col-lg-2">Jalur Masuk <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-10">
              <select name="id_jalur_masuk" id="id_jalur_masuk" data-placeholder="Pilih Jalur Masuk..." class="form-control chzn-select" tabindex="2"> 
               <option value=""></option>
               <?php foreach ($db->fetch_all("jalur_masuk") as $isi) {

                  if ($data_edit->id_jalur_masuk==$isi->id_jalur_masuk) {
                    echo "<option value='$isi->id_jalur_masuk' selected>$isi->nm_jalur_masuk</option>";
                  } else {
                  echo "<option value='$isi->id_jalur_masuk'>$isi->nm_jalur_masuk</option>";
                    }
               } ?>
              </select>
          </div>
                      </div><!-- /.form-group -->


<div class="form-group">
                        <label for="Tempat Lahir" class="control-label col-lg-2">Tempat Lahir <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-10">
                          <input type="text" name="tmpt_lahir" value="<?=$data_edit->tmpt_lahir;?>" class="form-control" required maxlength="32"> 
                          <p class="help-block" style="color:#f00">Tempat Lahir Wajib Sesuai Ijazah</p>
                        </div>
                      </div><!-- /.form-group -->

                 <div class="form-group">
                        <label for="Tanggal Lahir" class="control-label col-lg-2">Tanggal Lahir <span style="color:#FF0000">*</span></label>
<div class="col-lg-10">
  <div class="row">
<div class="col-xs-2">
<select id="tgl_lahir_tanggal" name="tgl_lahir_tanggal" class="form-control lahir tgl_lahir_tanggal select2" required="">
  <option value="">Tanggal</option>
  <?php
  $tgl_lahir = substr($data_edit->tgl_lahir, 8,2);
  $bulan_lahir = substr($data_edit->tgl_lahir, 5,2);
  $tahun_lahir = substr($data_edit->tgl_lahir, 0,4);
                for ($i=1; $i<=31 ; $i++) {
                  if ($i<10) {
                    $i = "0".$i;
                  }
                  if ($i==$tgl_lahir) {
                    echo "<option value='$i' selected>$i</option>";
                  } else {
                    echo "<option value='$i'>$i</option>";
                  }
                }
            ?>
</select>
</div>
<div class="col-xs-2">
<select id="tgl_lahir_bulan" name="tgl_lahir_bulan" class="form-control lahir tgl_lahir_bulan select2" required="">
  <option value="">Bulan</option>
   <?php
            for ($i=1; $i<=12 ; $i++) {
                                if ($i<10) {
                    $i = "0".$i;
                  }
              if ($i==$bulan_lahir) {
                    echo "<option value='$i' selected>".getBulan($i)."</option>";
                  } else {
                    echo "<option value='$i'>".getBulan($i)."</option>";
                  }
            }
        ?>
</select>
</div>
<div class="col-xs-2">
<select id="tgl_lahir_tahun" name="tgl_lahir_tahun" class="form-control lahir tgl_lahir_tahun select2" required="">
  <option value="">Tahun</option>
  <?php
  $minimum_age = strtotime("-100 year", time());
  $minim_age = date("Y", $minimum_age);


  $maximum_age = strtotime("-15 year", time());
  $max_age = date("Y", $maximum_age);

        for ($i=$max_age; $i>=$minim_age ; $i--) {
              if ($i==$tahun_lahir) {
                echo "<option value='$i' selected>$i</option>";
              } else {
                echo "<option value='$i'>$i</option>";
              }
        }
    ?>
</select>
</div>

</div>
 <p class="help-block" style="color:#f00">Tanggal Lahir Wajib Sesuai Ijazah Asal</p>
              </div>
                      </div><!-- /.form-group -->

          <div class="form-group">
                        <label for="Agama" class="control-label col-lg-2">Agama <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-10">
              <select name="id_agama" id="id_agama" data-placeholder="Pilih Agama..." class="form-control chzn-select" tabindex="2" required>
               <option value=""></option>
               <?php foreach ($db->fetch_all("agama") as $isi) {

                  if ($data_edit->id_agama==$isi->id_agama) {
                    echo "<option value='$isi->id_agama' selected>$isi->nm_agama</option>";
                  } else {
                    if ($data_edit->id_agama=="" && $isi->id_agama=='1') {
                        echo "<option value='$isi->id_agama' selected>$isi->nm_agama</option>";
                      } else {
                        echo "<option value='$isi->id_agama'>$isi->nm_agama</option>";
                      }
                    }
               } ?>
              </select>
          </div>
                      </div><!-- /.form-group -->


<div class="form-group">
                        <label for="Jenis Tinggal" class="control-label col-lg-2">Kecamatan <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-10">
                          <select id="kecamatan" name="id_wil" data-placeholder="Pilih Kecamatan..." class="form-control data_wil" tabindex="2" required="">
               <option value=""></option>
              <?php

                   if ($data_edit->id_wil=='999999') {
                     echo "<option value='$data_edit->id_wil' selected>Tidak ada</option>";
                   } else {
                    foreach ($db->query("
                      SELECT 
    dwc.id_wil,CONCAT(dwc.nm_wil, ' - ', dw.nm_wil, ' - ', data_wilayah.nm_wil) AS wil 
FROM 
    data_wilayah
LEFT JOIN 
    data_wilayah dw ON data_wilayah.id_wil = dw.id_induk_wilayah
LEFT JOIN 
    data_wilayah dwc ON dw.id_wil = dwc.id_induk_wilayah
WHERE 
    data_wilayah.id_level_wil = '1'and dwc.id_wil='$data_edit->id_wil'

union all
SELECT 
    dw.id_wil,CONCAT( dw.nm_wil, ' - ', data_wilayah.nm_wil) AS wil 
FROM 
    data_wilayah
LEFT JOIN 
    data_wilayah dw ON data_wilayah.id_wil = dw.id_induk_wilayah
WHERE 
    data_wilayah.id_level_wil = '1' and dw.id_wil='$data_edit->id_wil'
") as $isi) {
                    if ($data_edit->id_wil==$isi->id_wil) {
                        echo "<option value='$isi->id_wil' selected>$isi->wil</option>";
                      }
                   }
                   }
                    ?>
              </select>
                        </div>
                      </div><!-- /.form-group -->

<div class="form-group">
                        <label for="Jalan" class="control-label col-lg-2">Alamat Jalan <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-10">
                          <input type="text" name="jln" value="<?=$data_edit->jln;?>" class="form-control" required maxlength="80"> 
                        </div>
                      </div><!-- /.form-group -->
              
           <div class="form-group">
                        <label for="RT" class="control-label col-lg-2">RT</label>
                        <div class="col-lg-10">
                          <input type="text" name="rt" value="<?=$data_edit->rt;?>" class="form-control" required data-rule-number="true" maxlength="2" onkeypress="return isNumberKey(event)"> 
                        </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="RW" class="control-label col-lg-2">RW</label>
                        <div class="col-lg-10">
                          <input type="text" name="rw" value="<?=$data_edit->rw;?>" class="form-control" required maxlength="2" onkeypress="return isNumberKey(event)"> 
                        </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Dusun" class="control-label col-lg-2">Dusun</label>
                        <div class="col-lg-10">
                          <input type="text" name="nm_dsn" value="<?=$data_edit->nm_dsn;?>" class="form-control" maxlength="60"> 
                        </div>
                      </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Kelurahan" class="control-label col-lg-2">Desa/Kelurahan </label>
                <div class="col-lg-10">
                  <input type="text" name="ds_kel" value="<?=$data_edit->ds_kel;?>" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
<div class="form-group">
                        <label for="Kodepos" class="control-label col-lg-2">Kode Pos <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-10">
                          <input type="text" name="kode_pos" value="<?=$data_edit->kode_pos;?>" class="form-control" required maxlength="5" onkeypress="return isNumberKey(event)"> 
                        </div>
                      </div><!-- /.form-group -->
              <div class="form-group">
                        <label for="Jenis Tinggal" class="control-label col-lg-2">Jenis Tinggal <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-10">
              <select name="id_jns_tinggal" data-placeholder="Pilih Jenis Tinggal..." class="form-control chzn-select" tabindex="2" required>
               <option value=""></option>
               <?php foreach ($db->fetch_all("jenis_tinggal") as $isi) {

                  if ($data_edit->id_jns_tinggal==$isi->id_jns_tinggal) {
                    echo "<option value='$isi->id_jns_tinggal' selected>$isi->jenis_tinggal</option>";
                  } else {
                  echo "<option value='$isi->id_jns_tinggal'>$isi->jenis_tinggal</option>";
                    }
               } ?>
              </select>
          </div>
                      </div><!-- /.form-group -->

              <div class="form-group">
                <label for="No Telepon Rumah" class="control-label col-lg-2">No Telepon Rumah </label>
                <div class="col-lg-10">
                  <input type="text" name="no_tel_rmh" value="<?=$data_edit->no_tel_rmh;?>" class="form-control"  onkeypress="return isNumberKey(event)" minlength="9">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="No Handphone" class="control-label col-lg-2">No Handphone <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="no_hp" value="<?=$data_edit->no_hp;?>" class="form-control" required onkeypress="return isNumberKey(event)">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Email" class="control-label col-lg-2">Email <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text"  data-rule-email="true" name="email" value="<?=$data_edit->email;?>" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
            <div class="form-group">
                <label for="Penerima KPS (Kartu Perlindungan Sosial) ?" class="control-label col-lg-2">Penerima KPS (Kartu Perlindungan Sosial) ? </label>
                <div class="col-lg-10">
                    <select name="a_terima_kps" id="a_terima_kps" data-placeholder="Pilih Penerima KPS (Kartu Perlindungan Sosial) ?..." class="form-control chzn-select" tabindex="2" >
                      <option value=""></option>
                     <?php
                     $option = array(
'0' => 'Tidak',

'1' => 'Iya',
);
                     foreach ($option as $isi => $val) {

                        if ($data_edit->a_terima_kps==$isi) {
                          echo "<option value='$data_edit->a_terima_kps' selected>$val</option>";
                        } else {
                       echo "<option value='$isi'>$val</option>";
                          }
                     } ?>
                    </select>
                  </div>
            </div><!-- /.form-group -->
            
              <div class="form-group">
                <label for="No KPS" class="control-label col-lg-2">No KPS </label>
                <div class="col-lg-10">
                  <input type="text" name="no_kps" id="no_kps" readonly="" value="<?=$data_edit->no_kps;?>" class="form-control" >
                </div>
              </div><!-- /.form-group -->
              <div class="form-group">
                        <label for="Jenis Pendaftaran" class="control-label col-lg-2">Jenis Pendaftaran </label>
                        <div class="col-lg-10">
              <select name="id_jns_daftar" data-placeholder="Pilih Jenis Pendaftaran..." class="form-control chzn-select" tabindex="2" >
               <option value=""></option>
               <?php foreach ($db->fetch_all("jenis_daftar") as $isi) {

                  if ($data_edit->id_jns_daftar==$isi->id_jenis_daftar) {
                    echo "<option value='$isi->id_jenis_daftar' selected>$isi->nm_jns_daftar</option>";
                  } else {
                  echo "<option value='$isi->id_jenis_daftar'>$isi->nm_jns_daftar</option>";
                    }
               } ?>
              </select>
          </div>
                      </div><!-- /.form-group -->
   <div class="form-group">

                        <label for="NIM" class="control-label col-lg-2">&nbsp;</label>
                        <div class="col-lg-10">
                        <div class="callout callout-info" style="font-size: 20px;margin: 0;padding: 7px;">Data Orang Tua</div>
                         
                        </div>
                      </div><!-- /.form-group -->   
              <div class="form-group">
                <label for="NIK Ayah" class="control-label col-lg-2">NIK Ayah </label>
                <div class="col-lg-10">
                  <input type="text" name="nik_ayah" value="<?=$data_edit->nik_ayah;?>" class="form-control" minlength="16" maxlength="16" onkeypress="return isNumberKey(event)">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Nama Ayah" class="control-label col-lg-2">Nama Ayah </label>
                <div class="col-lg-10">
                  <input type="text" name="nm_ayah" value="<?=$data_edit->nm_ayah;?>" class="form-control" >
                </div>
              </div><!-- /.form-group -->
              
                    <div class="form-group">
              <label for="Tanggal Mulai" class="control-label col-lg-2">Tanggal Lahir Ayah</label>
              <div class="col-lg-3">
                <div class='input-group date tgl_picker'>
                    <input type='text' class="form-control " name="tgl_lahir_ayah" value="<?=$data_edit->tgl_lahir_ayah;?>"  />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
              </div>
          </div><!-- /.form-group -->
          <div class="form-group">
                        <label for="Pendidikan Ayah" class="control-label col-lg-2">Pendidikan Ayah </label>
                        <div class="col-lg-10">
              <select name="id_jenjang_pendidikan_ayah" data-placeholder="Pilih Pendidikan Ayah..." class="form-control chzn-select" tabindex="2" >
               <option value=""></option>
               <?php foreach ($db->fetch_all("jenjang_pendidikan") as $isi) {

                  if ($data_edit->id_jenjang_pendidikan_ayah==$isi->id_jenjang) {
                    echo "<option value='$isi->id_jenjang' selected>$isi->jenjang</option>";
                  } else {
                  echo "<option value='$isi->id_jenjang'>$isi->jenjang</option>";
                    }
               } ?>
              </select>
          </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Pekerjaan Ayah" class="control-label col-lg-2">Pekerjaan Ayah </label>
                        <div class="col-lg-10">
              <select name="id_pekerjaan_ayah" data-placeholder="Pilih Pekerjaan Ayah..." class="form-control chzn-select" tabindex="2" >
               <option value=""></option>
               <?php foreach ($db->fetch_all("pekerjaan") as $isi) {

                  if ($data_edit->id_pekerjaan_ayah==$isi->id_pekerjaan) {
                    echo "<option value='$isi->id_pekerjaan' selected>$isi->pekerjaan</option>";
                  } else {
                  echo "<option value='$isi->id_pekerjaan'>$isi->pekerjaan</option>";
                    }
               } ?>
              </select>
          </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Penghasilan Ayah" class="control-label col-lg-2">Penghasilan Ayah </label>
                        <div class="col-lg-10">
              <select name="id_penghasilan_ayah" data-placeholder="Pilih Penghasilan Ayah..." class="form-control chzn-select" tabindex="2" >
               <option value=""></option>
               <?php foreach ($db->fetch_all("penghasilan") as $isi) {

                  if ($data_edit->id_penghasilan_ayah==$isi->id_penghasilan) {
                    echo "<option value='$isi->id_penghasilan' selected>$isi->penghasilan</option>";
                  } else {
                  echo "<option value='$isi->id_penghasilan'>$isi->penghasilan</option>";
                    }
               } ?>
              </select>
          </div>
                      </div><!-- /.form-group -->

              <div class="form-group">
                <label for="NIK Ibu" class="control-label col-lg-2">NIK Ibu </label>
                <div class="col-lg-10">
                  <input type="text" name="nik_ibu_kandung" value="<?=$data_edit->nik_ibu_kandung;?>" class="form-control" minlength="16" maxlength="16" onkeypress="return isNumberKey(event)">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Nama Ibu " class="control-label col-lg-2">Nama Ibu  <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="nm_ibu_kandung" value="<?=$data_edit->nm_ibu_kandung;?>" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
                    <div class="form-group">
              <label for="Tanggal Mulai" class="control-label col-lg-2">Tanggal Lahir Ibu</label>
              <div class="col-lg-3">
                <div class='input-group date' id='tgl3'>
                    <input type='text' class="form-control" name="tgl_lahir_ibu" value="<?=$data_edit->tgl_lahir_ibu;?>" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
              </div>
          </div><!-- /.form-group -->
          <div class="form-group">
                        <label for="Pendidikan Ibu" class="control-label col-lg-2">Pendidikan Ibu </label>
                        <div class="col-lg-10">
              <select name="id_jenjang_pendidikan_ibu" data-placeholder="Pilih Pendidikan Ibu..." class="form-control chzn-select" tabindex="2" >
               <option value=""></option>
               <?php foreach ($db->fetch_all("jenjang_pendidikan") as $isi) {

                  if ($data_edit->id_jenjang_pendidikan_ibu==$isi->id_jenjang) {
                    echo "<option value='$isi->id_jenjang' selected>$isi->jenjang</option>";
                  } else {
                  echo "<option value='$isi->id_jenjang'>$isi->jenjang</option>";
                    }
               } ?>
              </select>
          </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Pekerjaan Ibu" class="control-label col-lg-2">Pekerjaan Ibu </label>
                        <div class="col-lg-10">
              <select name="id_pekerjaan_ibu" data-placeholder="Pilih Pekerjaan Ibu..." class="form-control chzn-select" tabindex="2" >
               <option value=""></option>
               <?php foreach ($db->fetch_all("pekerjaan") as $isi) {

                  if ($data_edit->id_pekerjaan_ibu==$isi->id_pekerjaan) {
                    echo "<option value='$isi->id_pekerjaan' selected>$isi->pekerjaan</option>";
                  } else {
                  echo "<option value='$isi->id_pekerjaan'>$isi->pekerjaan</option>";
                    }
               } ?>
              </select>
          </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Penghasilan Ibu" class="control-label col-lg-2">Penghasilan Ibu </label>
                        <div class="col-lg-10">
              <select name="id_penghasilan_ibu" data-placeholder="Pilih Penghasilan Ibu..." class="form-control chzn-select" tabindex="2" >
               <option value=""></option>
               <?php foreach ($db->fetch_all("penghasilan") as $isi) {

                  if ($data_edit->id_penghasilan_ibu==$isi->id_penghasilan) {
                    echo "<option value='$isi->id_penghasilan' selected>$isi->penghasilan</option>";
                  } else {
                  echo "<option value='$isi->id_penghasilan'>$isi->penghasilan</option>";
                    }
               } ?>
              </select>
          </div>
                      </div><!-- /.form-group -->
   <div class="form-group">

                        <label for="NIM" class="control-label col-lg-2">&nbsp;</label>
                        <div class="col-lg-10">
                        <div class="callout callout-info" style="font-size: 20px;margin: 0;padding: 7px;">Data Orang Tua Wali</div>
                         
                        </div>
                      </div><!-- /.form-group -->   
              <div class="form-group">
                <label for="Nama Wali" class="control-label col-lg-2">Nama Wali </label>
                <div class="col-lg-10">
                  <input type="text" name="nm_wali" value="<?=$data_edit->nm_wali;?>" class="form-control" >
                </div>
              </div><!-- /.form-group -->
                    <div class="form-group">
              <label for="Tanggal Mulai" class="control-label col-lg-2">Tanggal Lahir Wali</label>
              <div class="col-lg-3">
                <div class='input-group date' id='tgl4'>
                    <input type='text' class="form-control" name="tgl_lahir_wali" value="<?=$data_edit->tgl_lahir_wali;?>"  />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
              </div>
          </div><!-- /.form-group -->
          <div class="form-group">
                        <label for="Jenjang Pendidikan Wali" class="control-label col-lg-2">Jenjang Pendidikan Wali </label>
                        <div class="col-lg-10">
              <select name="id_jenjang_pendidikan_wali" data-placeholder="Pilih Jenjang Pendidikan Wali..." class="form-control chzn-select" tabindex="2" >
               <option value=""></option>
               <?php foreach ($db->fetch_all("jenjang_pendidikan") as $isi) {

                  if ($data_edit->id_jenjang_pendidikan_wali==$isi->id_jenjang) {
                    echo "<option value='$isi->id_jenjang' selected>$isi->jenjang</option>";
                  } else {
                  echo "<option value='$isi->id_jenjang'>$isi->jenjang</option>";
                    }
               } ?>
              </select>
          </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Pekerjaan Wali" class="control-label col-lg-2">Pekerjaan Wali </label>
                        <div class="col-lg-10">
              <select name="id_pekerjaan_wali" data-placeholder="Pilih Pekerjaan Wali..." class="form-control chzn-select" tabindex="2" >
               <option value=""></option>
               <?php foreach ($db->fetch_all("pekerjaan") as $isi) {

                  if ($data_edit->id_pekerjaan_wali==$isi->id_pekerjaan) {
                    echo "<option value='$isi->id_pekerjaan' selected>$isi->pekerjaan</option>";
                  } else {
                  echo "<option value='$isi->id_pekerjaan'>$isi->pekerjaan</option>";
                    }
               } ?>
              </select>
          </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Penghasilan Wali" class="control-label col-lg-2">Penghasilan Wali </label>
                        <div class="col-lg-10">
              <select name="id_penghasilan_wali" data-placeholder="Pilih Penghasilan Wali..." class="form-control chzn-select" tabindex="2" >
               <option value=""></option>
               <?php foreach ($db->fetch_all("penghasilan") as $isi) {

                  if ($data_edit->id_penghasilan_wali==$isi->id_penghasilan) {
                    echo "<option value='$isi->id_penghasilan' selected>$isi->penghasilan</option>";
                  } else {
                  echo "<option value='$isi->id_penghasilan'>$isi->penghasilan</option>";
                    }
               } ?>
              </select>
          </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Dosen Pembimbing" class="control-label col-lg-2">Dosen Pembimbing </label>
                        <div class="col-lg-10">
              <select name="dosen_pemb" data-placeholder="Pilih Dosen Pembimbing..." class="form-control chzn-select" tabindex="2" readonly>
               <option value=""></option>
               <?php foreach ($db->fetch_all("dosen") as $isi) {

                  if ($data_edit->dosen_pemb==$isi->nip) {
                    echo "<option value='$isi->nip' selected>$isi->gelar_depan $isi->nama_dosen $isi->gelar_belakang</option>";
                  }
               } ?>
              </select>
          </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Dosen Pembimbing" class="control-label col-lg-2">Asal Sekolah </label>
                        <div class="col-lg-10">
              <select id="id_jenis_sekolah" name="id_jenis_sekolah" data-placeholder="Jenis Asal Sekolah..." class="form-control chzn-select" tabindex="2" required="">
               <option value=""></option>
               <?php foreach ($db->fetch_all("tb_ref_jenis_asal_sekolah") as $isi) {

                  if ($data_edit->id_jenis_sekolah==$isi->id_jenis_sekolah) {
                    echo "<option value='$isi->id_jenis_sekolah' selected>$isi->nama_jenis_sekolah</option>";
                  } else {
                  echo "<option value='$isi->id_jenis_sekolah'>$isi->nama_jenis_sekolah</option>";
                    }
               } ?>
              </select>
          </div>
                      </div><!-- /.form-group -->
              <div class="form-group">
                <label for="Nama Wali" class="control-label col-lg-2">Nama Asal Sekolah/Lembaga </label>
                <div class="col-lg-10">
                  <input type="text" name="nama_asal_sekolah" value="<?=$data_edit->nama_asal_sekolah;?>" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
                            <input type="hidden" name="id" value="<?=$data_edit->mhs_id;?>">
                                                  <div class="form-group">
                        <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                        <div class="col-lg-10">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                         <a href="<?=base_index();?>biodata" class="btn btn-success btn-flat"><i class="fa fa-step-backward"></i> Kembali</a>
                         </div>
                      </div><!-- /.form-group -->
                          </form>
                      </div>
                  </div>
              </div>
              </section><!-- /.content -->
<script type="text/javascript">
    $(document).ready(function() {

        $(".tgl_picker").datepicker({
        format: "yyyy-mm-dd",
        viewMode: 'years',
      changeMonth: true,
      changeYear: true,
        }).on("change",function(){
          $(":input",this).valid();
        });

$(".select2").select2();
  $( "#kecamatan" ).select2({
    ajax: {
      url: '<?=base_admin();?>modul/mahasiswa/get_kecamatan.php',
      dataType: 'json'
      // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
    },
    formatInputTooShort: "Cari Kecamatan/Kabupaten",
    width: "100%",
  });

            $("#provinsi_provinsi").change(function(){

                        $.ajax({
                        type : "post",
                        url : "<?=base_admin();?>modul/biodata/get_kabupaten.php",
                        data : {provinsi:this.value},
                        success : function(data) {
                            $("#kabupaten_kabupaten").html(data);
                            $("#kabupaten_kabupaten").trigger("chosen:updated");

                        }
                    });

                  });

                    $("#kabupaten_kabupaten").change(function(){

                        $.ajax({
                        type : "post",
                        url : "<?=base_admin();?>modul/biodata/get_kec.php",
                        data : {id_kab:this.value},
                        success : function(data) {
                            $("#id_kec_tea").html(data);
                            $("#id_kec_tea").trigger("chosen:updated");

                        }
                    });

                  });

              $("#a_terima_kps").change(function(){
                  
                  if (this.value=='1') {
                    $('#no_kps').prop('readonly', false);
                  } else {
                    $('#no_kps').prop('readonly', true);
                  }
              });      
    
      //trigger validation onchange
      $('select').on('change', function() {
          $(this).valid();
      });
      //hidden validate because we use chosen select
      $.validator.setDefaults({ ignore: ":hidden:not(select)" });
      
    $("#edit_biodata").validate({
        errorClass: "help-block",
        errorElement: "span",
        highlight: function(element, errorClass, validClass) {
            $(element).parents(".form-group").removeClass(
                "has-success").addClass("has-error");
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).parents(".form-group").removeClass(
                "has-error").addClass("has-success");
        },
        errorPlacement: function(error, element) {
            if (element.hasClass("chzn-select")) {
                var id = element.attr("id");
                error.insertAfter("#" + id + "_chosen");
            } else if (element.attr("type") == "checkbox") {
                element.parent().parent().append(error);
            } else if (element.attr("type") == "radio") {
                element.parent().parent().append(error);
            } else {
                error.insertAfter(element);
            }
        },
        
        rules: {
            
          nama: {
          required: true,
          //minlength: 2
          },
        
          nik: {
          required: true,
          //minlength: 2
          },
        
          kewarganegaraan: {
          required: true,
          //minlength: 2
          },
        
          id_jalur_masuk: {
          required: true,
          //minlength: 2
          },
        
          tmpt_lahir: {
          required: true,
          //minlength: 2
          },
        
          tgl_lahir: {
          required: true,
          //minlength: 2
          },
        
          id_agama: {
          required: true,
          //minlength: 2
          },
        
          jln: {
          required: true,
          //minlength: 2
          },
        
          kode_pos: {
          required: true,
          //minlength: 2
          },
        
          telepon_seluler: {
          required: true,
          //minlength: 2
          },
        
          email: {
          required: true,
          //minlength: 2
          },
        
          nm_ibu_kandung: {
          required: true,
          //minlength: 2
          },
        
        },
         messages: {
            
          nama: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          nik: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          kewarganegaraan: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          id_jalur_masuk: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          tmpt_lahir: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          tgl_lahir: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          id_agama: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          jln: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          kode_pos: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          telepon_seluler: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          email: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          nm_ibu_kandung: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
        },
    
        submitHandler: function(form) {
            $("#loadnya").show();
            $(form).ajaxSubmit({
                type: "post",
                url: $(this).attr("action"),
                data: $("#edit_biodata").serialize(),
                success: function(data) {
                    console.log(data);
                    $("#loadnya").hide();
                    if (data == "good") {
                        $(".notif_top_up").fadeIn(1000);
                        $(".notif_top_up").fadeOut(1000, function() {
                                window.history.back();
                            });
                    } else if (data == "die") {
                        $("#informasi").modal("show");
                    } else {
                        $(".errorna").fadeIn();
                    }
                }
            });
        }
    });
});
</script>
