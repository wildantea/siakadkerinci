<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>Mahasiswa</h1>
  <ol class="breadcrumb">
    <li>
      <a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a>
    </li>
    <li>
      <a href="<?=base_index();?>mahasiswa">Mahasiswa</a>
    </li>
    <li class="active">Edit Mahasiswa</li>
  </ol>
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-lg-12">
      <div class="box box-solid box-primary">
        <div class="box-header">
          <h3 class="box-title">Edit Mahasiswa</h3>
          <div class="box-tools pull-right">
            <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <form id="edit_mahasiswa" method="post" class="form-horizontal" action="<?=base_admin();?>modul/mahasiswa/mahasiswa_action.php?act=up">
            <div class="form-group">
              <label for="NIM" class="control-label col-lg-2">NIM</label>
              <div class="col-lg-10">
                <input type="text" name="nim" value="<?=$data_edit->nim;?>" class="form-control" >
              </div>
            </div><!-- /.form-group -->            
            <div class="form-group">
              <label for="Nama Lengkap" class="control-label col-lg-2">Nama Lengkap</label>
              <div class="col-lg-10">
                <input type="text" name="nama" value="<?=$data_edit->nama;?>" class="form-control" >
              </div>
            </div><!-- /.form-group -->
            <div class="form-group">
              <label for="Jurusan" class="control-label col-lg-2">Jurusan</label>
              <div class="col-lg-10">
                <select name="jur_kode" data-placeholder="Pilih Jurusan..." class="form-control chzn-select" tabindex="2" >
                 <option value=""></option>
                 <?php foreach ($db->fetch_all("jurusan") as $isi) {

                  if ($data_edit->jur_kode==$isi->kode_jur) {
                    echo "<option value='$isi->kode_jur' selected>$isi->nama_jur</option>";
                  } else {
                    echo "<option value='$isi->kode_jur'>$isi->nama_jur</option>";
                  }
                } ?>
              </select>
            </div>
          </div><!-- /.form-group -->
          <div class="form-group">
            <label for="Jenis Daftar" class="control-label col-lg-2">Jenis Daftar</label>
            <div class="col-lg-10">
              <select name="id_jns_daftar" data-placeholder="Pilih Jenis Daftar..." class="form-control chzn-select" tabindex="2" >
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
          <label for="Jalur Masuk" class="control-label col-lg-2">Jalur Masuk</label>
          <div class="col-lg-10">
            <select name="id_jalur_masuk" data-placeholder="Pilih Jalur Masuk..." class="form-control chzn-select" tabindex="2" >
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
        <label for="Agama" class="control-label col-lg-2">Agama</label>
        <div class="col-lg-10">
          <select name="id_agama" data-placeholder="Pilih Agama..." class="form-control chzn-select" tabindex="2" >
           <option value=""></option>
           <?php foreach ($db->fetch_all("agama") as $isi) {

            if ($data_edit->id_agama==$isi->id_agama) {
              echo "<option value='$isi->id_agama' selected>$isi->nm_agama</option>";
            } else {
              echo "<option value='$isi->id_agama'>$isi->nm_agama</option>";
            }
          } ?>
        </select>
      </div>
    </div><!-- /.form-group -->
    <div class="form-group">
      <label for="Semester Masuk" class="control-label col-lg-2">Semester Masuk</label>
      <div class="col-lg-10">
        <select name="mulai_smt" data-placeholder="Pilih Semester Masuk..." class="form-control chzn-select" tabindex="2" >
         <option value=""></option>
         <?php foreach ($db->query("select s.id_semester,j.nm_singkat,j.jns_semester,s.tahun from semester_ref s join jenis_semester j on s.id_jns_semester=j.id_jns_semester") as $isi) {

          if ($data_edit->mulai_smt==$isi->id_semester) {
            echo "<option value='$isi->id_semester' selected>$isi->jns_semester $isi->tahun</option>";
          } else {
            echo "<option value='$isi->id_semester'>$isi->jns_semester $isi->tahun</option>";
          }
        } ?>
      </select>
    </div>
  </div><!-- /.form-group -->

  <div class="form-group">
    <label for="Tanggal Masuk " class="control-label col-lg-2">Tanggal Masuk </label>
    <div class="col-lg-10">
      <input type="text" id="tgl1" data-rule-date="true" name="tgl_masuk_sp" value="<?=$data_edit->tgl_masuk_sp;?>" class="form-control" >
    </div>
  </div><!-- /.form-group -->

  <div class="form-group">
    <label for="Jenis Kelamin" class="control-label col-lg-2">Jenis Kelamin</label>
    <div class="col-lg-10">

      <div class="radio radio-success radio-inline">
        <input type="radio" name="jk"  id="radio1" value="L" <?=($data_edit->jk=="L")?"checked":"";?> >
        <label for="radio1" style="padding-left: 5px;">
          Laki-laki
        </label>
      </div>

      <div class="radio radio-success radio-inline">
        <input type="radio" name="jk"  id="radio2" value="P" <?=($data_edit->jk=="P")?"checked":"";?> >
        <label for="radio2" style="padding-left: 5px;">
          Perempuan
        </label>
      </div>

    </div>
  </div><!-- /.form-group -->
  <div class="form-group">
    <label class="control-label col-lg-2">Foto</label>
    <div class="col-lg-10">
      <div class="fileinput fileinput-new" data-provides="fileinput">
        <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
          <img data-src="holder.js/100%x100%" alt="...">
        </div>
        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"></div>
        <div>
          <span class="btn btn-default btn-file"><span class="fileinput-new">Select image</span> <span class="fileinput-exists">Change</span> 
          <input type="file" name="foto_user" accept="image/*">
        </span> 
        <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a> 
      </div>
    </div>
  </div>
</div>
<div class="form-group">
  <label for="Password" class="control-label col-lg-2">Password Portal</label>
  <div class="col-lg-10">
    <input type="password" id="password_baru" name="password_baru" placeholder="Password" class="form-control" > 
  </div>
</div><!-- /.form-group -->
<div class="form-group">
  <label for="Password" class="control-label col-lg-2">Ulangi Password</label>
  <div class="col-lg-10">
    <input type="password" id="password_confirm" name="password_confirm" placeholder="Password" class="form-control" > 
  </div>
</div><!-- /.form-group -->

<div class="form-group">
  <label for="NISN" class="control-label col-lg-2">NISN</label>
  <div class="col-lg-10">
    <input type="text" name="nisn" value="<?=$data_edit->nisn;?>" class="form-control" >
  </div>
</div><!-- /.form-group -->

<div class="form-group">
  <label for="NIK" class="control-label col-lg-2">NIK</label>
  <div class="col-lg-10">
    <input type="text" name="nik" value="<?=$data_edit->nik;?>" class="form-control" >
  </div>
</div><!-- /.form-group -->

<div class="form-group">
  <label for="Tempat Lahir" class="control-label col-lg-2">Tempat Lahir</label>
  <div class="col-lg-10">
    <input type="text" name="tmpt_lahir" value="<?=$data_edit->tmpt_lahir;?>" class="form-control" >
  </div>
</div><!-- /.form-group -->

<div class="form-group">
  <label for="Tanggal Lahir" class="control-label col-lg-2">Tanggal Lahir</label>
  <div class="col-lg-10">
    <input type="text" id="tgl2" data-rule-date="true" name="tgl_lahir" value="<?=$data_edit->tgl_lahir;?>" class="form-control" >
  </div>
</div><!-- /.form-group -->

<div class="form-group">
  <label for="Alamat" class="control-label col-lg-2">Alamat</label>
  <div class="col-lg-10">
    <input type="text" name="jln" value="<?=$data_edit->jln;?>" class="form-control" >
  </div>
</div><!-- /.form-group -->


<div class="form-group">
  <label for="RT" class="control-label col-lg-2">RT</label>
  <div class="col-lg-10">
    <input type="text" name="rt" value="<?=$data_edit->rt;?>" class="form-control" >
  </div>
</div><!-- /.form-group -->

<div class="form-group">
  <label for="RW" class="control-label col-lg-2">RW</label>
  <div class="col-lg-10">
    <input type="text" name="rw" value="<?=$data_edit->rw;?>" class="form-control" >
  </div>
</div><!-- /.form-group -->

<div class="form-group">
  <label for="Nama Dusun" class="control-label col-lg-2">Nama Dusun</label>
  <div class="col-lg-10">
    <input type="text" name="nm_dsn" value="<?=$data_edit->nm_dsn;?>" class="form-control" >
  </div>
</div><!-- /.form-group -->



<div class="form-group">
  <label for="Kelurahan" class="control-label col-lg-2">Kelurahan</label>
  <div class="col-lg-10">
    <input type="text" name="ds_kel" value="<?=$data_edit->ds_kel;?>" class="form-control" >
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
  <label for="Kode Pos" class="control-label col-lg-2">Kode Pos</label>
  <div class="col-lg-10">
    <input type="text" name="kode_pos" value="<?=$data_edit->kode_pos;?>" class="form-control" >
  </div>
</div><!-- /.form-group -->

<div class="form-group">
  <label for="Telepon Rumah" class="control-label col-lg-2">Telepon Rumah</label>
  <div class="col-lg-10">
    <input type="text" name="telepon_rumah" value="<?=$data_edit->no_tel_rmh;?>" class="form-control" >
  </div>
</div><!-- /.form-group -->

<div class="form-group">
  <label for="Telepon Seluler" class="control-label col-lg-2">Telepon Seluler</label>
  <div class="col-lg-10">
    <input type="text" name="telepon_seluler" value="<?=$data_edit->no_hp;?>" class="form-control" >
  </div>
</div><!-- /.form-group -->

<div class="form-group">
  <label for="Email" class="control-label col-lg-2">Email</label>
  <div class="col-lg-10">
    <input type="text" name="email" value="<?=$data_edit->email;?>" class="form-control" >
  </div>
</div><!-- /.form-group -->

<div class="form-group">
  <label for="Terima KPS" class="control-label col-lg-2">Terima KPS</label>
  <div class="col-lg-10">
    <?php if ($data_edit->a_terima_kps=="1") {
      ?>
      <input name="a_terima_kps" class="make-switch" type="checkbox" checked>
      <?php
    } else {
      ?>
      <input name="a_terima_kps" class="make-switch" type="checkbox">
      <?php
    }?>

  </div>
</div><!-- /.form-group -->

<div class="form-group">
  <label for="No KPS" class="control-label col-lg-2">No KPS</label>
  <div class="col-lg-10">
    <input type="text" name="no_kps" value="<?=$data_edit->no_kps;?>" class="form-control" >
  </div>
</div><!-- /.form-group -->
<div class="form-group">
  <label for="Status Mahasiswa" class="control-label col-lg-2">Status Mahasiswa</label>
  <div class="col-lg-10">
    <select name="stat_pd" data-placeholder="Pilih Status Mahasiswa..." class="form-control chzn-select" tabindex="2" >
     <option value=""></option>
     <?php foreach ($db->fetch_all("status_mhs") as $isi) {

      if ($data_edit->stat_pd==$isi->kode) {
        echo "<option value='$isi->kode' selected>$isi->ket</option>";
      } else {
        echo "<option value='$isi->kode'>$isi->ket</option>";
      }
    } ?>
  </select>
</div>
</div><!-- /.form-group -->

<div class="form-group">
  <label for="Nama Ayah" class="control-label col-lg-2">Nama Ayah</label>
  <div class="col-lg-10">
    <input type="text" name="nm_ayah" value="<?=$data_edit->nm_ayah;?>" class="form-control" >
  </div>
</div><!-- /.form-group -->

<div class="form-group">
  <label for="NIK Ayah" class="control-label col-lg-2">NIK Ayah</label>
  <div class="col-lg-10">
    <input type="text" name="nik_ayah" value="<?=$data_edit->nik_ayah;?>" class="form-control" >
  </div>
</div><!-- /.form-group -->

<div class="form-group">
  <label for="Tanggal Lahir ayah" class="control-label col-lg-2">Tanggal Lahir ayah</label>
  <div class="col-lg-10">
    <input type="text" id="tgl3" data-rule-date="true" name="tgl_lahir_ayah" value="<?=$data_edit->tgl_lahir_ayah;?>" class="form-control" >
  </div>
</div><!-- /.form-group -->
<div class="form-group">
  <label for="Pendidikan Ayah" class="control-label col-lg-2">Pendidikan Ayah</label>
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
  <label for="Pekerjaan Ayah" class="control-label col-lg-2">Pekerjaan Ayah</label>
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
  <label for="penghasilan wali" class="control-label col-lg-2">penghasilan wali</label>
  <div class="col-lg-10">
    <select name="id_penghasilan_ayah" data-placeholder="Pilih penghasilan wali..." class="form-control chzn-select" tabindex="2" >
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
  <label for="Ibu Kandung" class="control-label col-lg-2">Ibu Kandung</label>
  <div class="col-lg-10">
    <input type="text" name="nm_ibu_kandung" value="<?=$data_edit->nm_ibu_kandung;?>" class="form-control" >
  </div>
</div><!-- /.form-group -->

<div class="form-group">
  <label for="NIK Ibu Kandung" class="control-label col-lg-2">NIK Ibu Kandung</label>
  <div class="col-lg-10">
    <input type="text" name="nik_ibu_kandung" value="<?=$data_edit->nik_ibu_kandung;?>" class="form-control" >
  </div>
</div><!-- /.form-group -->

<div class="form-group">
  <label for="Tanggal Lahir Ibu" class="control-label col-lg-2">Tanggal Lahir Ibu</label>
  <div class="col-lg-10">
    <input type="text" id="tgl4" data-rule-date="true" name="tgl_lahir_ibu" value="<?=$data_edit->tgl_lahir_ibu;?>" class="form-control" >
  </div>
</div><!-- /.form-group -->
<div class="form-group">
  <label for="Pendidikan Ibu" class="control-label col-lg-2">Pendidikan Ibu</label>
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
  <label for="Pekerjaan Ibu" class="control-label col-lg-2">Pekerjaan Ibu</label>
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
  <label for="Penghasilan Ibu" class="control-label col-lg-2">Penghasilan Ibu</label>
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
  <label for="Nama Wali" class="control-label col-lg-2">Nama Wali</label>
  <div class="col-lg-10">
    <input type="text" name="nm_wali" value="<?=$data_edit->nm_wali;?>" class="form-control" >
  </div>
</div><!-- /.form-group -->

<div class="form-group">
  <label for="Tanggal Lahir Wali" class="control-label col-lg-2">Tanggal Lahir Wali</label>
  <div class="col-lg-10">
    <input type="text" id="tgl5" data-rule-date="true" name="tgl_lahir_wali" value="<?=$data_edit->tgl_lahir_wali;?>" class="form-control" >
  </div>
</div><!-- /.form-group -->

<div class="form-group">
  <label for="nik_wali" class="control-label col-lg-2">nik_wali</label>
  <div class="col-lg-10">
    <input type="text" name="nik_wali" value="<?= $data_edit->nik_wali; ?>" class="form-control" >
  </div>
</div><!-- /.form-group -->

<div class="form-group">
  <label for="Pendidikan Wali" class="control-label col-lg-2">Pendidikan Wali</label>
  <div class="col-lg-10">
    <input type="text" id="tgl6" data-rule-date="true" name="id_jenjang_pendidikan_wali" value="<?=$data_edit->id_jenjang_pendidikan_wali;?>" class="form-control" >
  </div>
</div><!-- /.form-group -->
<div class="form-group">
  <label for="Pekerjaan Wali" class="control-label col-lg-2">Pekerjaan Wali</label>
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
  <label for="Penghasilan Wali" class="control-label col-lg-2">Penghasilan Wali</label>
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
  <label for="Jenis Tinggal" class="control-label col-lg-2">Jenis Tinggal</label>
  <div class="col-lg-10">
    <select name="id_jns_tinggal" data-placeholder="Pilih Jenis Tinggal..." class="form-control chzn-select" tabindex="2" >
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
  <label for="Kurikulum" class="control-label col-lg-2">Kurikulum</label>
  <div class="col-lg-10">
    <select name="kur_id" data-placeholder="Pilih Kurikulum..." class="form-control chzn-select" tabindex="2" >
     <option value=""></option>
     <?php foreach ($db->query("select k.kur_id,j.nama_jur,k.nama_kurikulum from kurikulum k join jurusan j on k.kode_jur=j.kode_jur") as $isi) {

      if ($data_edit->kur_id==$isi->kur_id) {
        echo "<option value='$isi->kur_id' selected>$isi->nama_jur - $isi->nama_kurikulum</option>";
      } else {
        echo "<option value='$isi->kur_id'>$isi->nama_jur - $isi->nama_kurikulum</option>";
      }
    } ?>
  </select>
</div>
</div><!-- /.form-group -->
<div class="form-group">
  <label for="dosen_pemb" class="control-label col-lg-2">Dosen Pembimbing</label>
  <div class="col-lg-10">
    <select name="dosen_pemb" data-placeholder="Pilih dosen_pemb..." class="form-control chzn-select" tabindex="2" >
     <option value=""></option>
     <?php foreach ($db->fetch_all("dosen") as $isi) {

      if ($data_edit->dosen_pemb==$isi->nip) {
        echo "<option value='$isi->nip' selected>$isi->nama_dosen</option>";
      } else {
        echo "<option value='$isi->nip'>$isi->nama_dosen</option>";
      }
    } ?>
  </select>
</div>
</div><!-- /.form-group -->

<div class="form-group">
  <label for="Kewarganegaraan" class="control-label col-lg-2">Kewarganegaraan</label>
  <div class="col-lg-10">

    <div class="radio radio-success radio-inline">
      <input type="radio" name="kewarganegaraan"  id="radio1" value="WNI" <?=($data_edit->kewarganegaraan=="WNI")?"checked":"";?> required="" >
      <label for="radio1" style="padding-left: 5px;">
        WNI
      </label>
    </div>

    <div class="radio radio-success radio-inline">
      <input type="radio" name="kewarganegaraan"  id="radio2" value="WNA" <?=($data_edit->kewarganegaraan=="WNA")?"checked":"";?> required="" >
      <label for="radio2" style="padding-left: 5px;">
        WNA
      </label>
    </div>

  </div>
</div><!-- /.form-group -->

<div class="form-group">
  <label for="NPWP" class="control-label col-lg-2">NPWP</label>
  <div class="col-lg-10">
    <input type="text" name="npwp" value="<?=$data_edit->npwp;?>" class="form-control" >
  </div>
</div><!-- /.form-group -->


  <div class="form-group">
    <label for="Jenis Kelamin" class="control-label col-lg-2">Status Mahasiswa</label>
    <div class="col-lg-10">

      <div class="radio radio-success radio-inline">
        <input type="radio" name="status"  id="radio1status" value="CM" <?=($data_edit->status=="CM")?"checked":"";?> >
        <label for="radio1status" style="padding-left: 5px;">
          CALON MAHASISWA
        </label>
      </div>

      <div class="radio radio-success radio-inline">
        <input type="radio" name="status"  id="radio2status" value="M" <?=($data_edit->status=="M")?"checked":"";?> >
        <label for="radio2status" style="padding-left: 5px;">
          MAHASISWA
        </label>
      </div>

    </div>
  </div><!-- /.form-group -->


<input type="hidden" name="id" value="<?=$data_edit->mhs_id;?>">
<div class="form-group">
  <label for="tags" class="control-label col-lg-2">&nbsp;</label>
  <div class="col-lg-10">
    <input type="submit" class="btn btn-primary " value="submit">
  </div>
</div><!-- /.form-group -->
</form>
<a href="<?=base_index();?>mahasiswa" class="btn btn-success "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>
</div>
</div>
</div>
</section><!-- /.content -->

<script type="text/javascript">
  $(document).ready(function() {
  $( "#kecamatan" ).select2({
    ajax: {
      url: '<?=base_admin();?>modul/mahasiswa/get_kecamatan.php',
      dataType: 'json'
      // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
    },
    formatInputTooShort: "Cari Kecamatan/Kabupaten",
    width: "100%",
  });

    $("#tgl1").datepicker( {
      format: "yyyy-mm-dd",
    });
    $("#tgl1").datepicker( {
      format: "yyyy-mm-dd",
    });
    $("#tgl1").datepicker( {
      format: "yyyy-mm-dd",
    });
    $("#tgl1").datepicker( {
      format: "yyyy-mm-dd",
    });
    $("#tgl1").datepicker( {
      format: "yyyy-mm-dd",
    });
    $("#tgl2").datepicker( {
      format: "yyyy-mm-dd",
    });
    $("#tgl2").datepicker( {
      format: "yyyy-mm-dd",
    });
    $("#tgl2").datepicker( {
      format: "yyyy-mm-dd",
    });
    $("#tgl2").datepicker( {
      format: "yyyy-mm-dd",
    });
    $("#tgl2").datepicker( {
      format: "yyyy-mm-dd",
    });
    $("#tgl2").datepicker( {
      format: "yyyy-mm-dd",
    });
    $("#tgl2").datepicker( {
      format: "yyyy-mm-dd",
    });
    $("#tgl2").datepicker( {
      format: "yyyy-mm-dd",
    });
    $("#tgl2").datepicker( {
      format: "yyyy-mm-dd",
    });
    $("#tgl2").datepicker( {
      format: "yyyy-mm-dd",
    });
    $("#tgl2").datepicker( {
      format: "yyyy-mm-dd",
    });
    $("#tgl2").datepicker( {
      format: "yyyy-mm-dd",
    });
    $("#tgl2").datepicker( {
      format: "yyyy-mm-dd",
    });
    $("#tgl2").datepicker( {
      format: "yyyy-mm-dd",
    });
    $("#tgl2").datepicker( {
      format: "yyyy-mm-dd",
    });
    $("#tgl3").datepicker( {
      format: "yyyy-mm-dd",
    });
    $("#tgl3").datepicker( {
      format: "yyyy-mm-dd",
    });
    $("#tgl3").datepicker( {
      format: "yyyy-mm-dd",
    });
    $("#tgl3").datepicker( {
      format: "yyyy-mm-dd",
    });
    $("#tgl3").datepicker( {
      format: "yyyy-mm-dd",
    });
    $("#tgl3").datepicker( {
      format: "yyyy-mm-dd",
    });
    $("#tgl4").datepicker( {
      format: "yyyy-mm-dd",
    });
    $("#tgl4").datepicker( {
      format: "yyyy-mm-dd",
    });
    $("#tgl4").datepicker( {
      format: "yyyy-mm-dd",
    });
    $("#tgl4").datepicker( {
      format: "yyyy-mm-dd",
    });
    $("#tgl4").datepicker( {
      format: "yyyy-mm-dd",
    });
    $("#tgl5").datepicker( {
      format: "yyyy-mm-dd",
    });
    $("#tgl5").datepicker( {
      format: "yyyy-mm-dd",
    });
    $("#tgl6").datepicker( {
      format: "yyyy-mm-dd",
    });
    $("#tgl6").datepicker( {
      format: "yyyy-mm-dd",
    });
    $("#tgl6").datepicker( {
      format: "yyyy-mm-dd",
    });
    $("#tgl6").datepicker( {
      format: "yyyy-mm-dd",
    });
    $("#tgl6").datepicker( {
      format: "yyyy-mm-dd",
    });
    $("#tgl6").datepicker( {
      format: "yyyy-mm-dd",
    });
    $("#tgl6").datepicker( {
      format: "yyyy-mm-dd",
    });
    $("#tgl6").datepicker( {
      format: "yyyy-mm-dd",
    });
    
      //trigger validation onchange
      $('select').on('change', function() {
        $(this).valid();
      });
      //hidden validate because we use chosen select
      $.validator.setDefaults({ ignore: ":hidden:not(select)" });
      
      $("#edit_mahasiswa").validate({
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

          // nim: {
          // required: true,
          // //minlength: 2
          // },

          nama: {
            required: true,
          //minlength: 2
        },
        
        jur_kode: {
          required: true,
          //minlength: 2
        },
        
        id_jns_daftar: {
          required: true,
          //minlength: 2
        },
        
        id_jalur_masuk: {
          required: true,
          //minlength: 2
        },
        
        id_agama: {
          required: true,
          //minlength: 2
        },
        
        mulai_smt: {
          required: true,
          //minlength: 2
        },
        
        
        
      },
      messages: {

          // nim: {
          // required: "This field is required",
          // //minlength: "Your username must consist of at least 2 characters"
          // },

          nama: {
            required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
        },
        
        jur_kode: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
        },
        
        id_jns_daftar: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
        },
        
        id_jalur_masuk: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
        },
        
        id_agama: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
        },
        
        mulai_smt: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
        },
        
        
        
      },

      submitHandler: function(form) {
        $("#loadnya").show();
        $(form).ajaxSubmit({
          type: "post",
          url: $(this).attr("action"),
          data: $("#edit_mahasiswa").serialize(),
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

<?php 
if ($_SESSION['group_level']=='admin') {
  ?>
$('input, select').removeAttr('required');
  <?php
} ?>
  </script>
