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
            <li class="active">Add Biodata</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
    <div class="row">
      <div class="col-lg-12">
        <div class="box box-solid box-primary">
          <div class="box-header">
            <h3 class="box-title">Add Biodata</h3>
            <div class="box-tools pull-right">
              <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
              <button class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
          </div>
          <div class="box-body">
            <form id="input_biodata" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/biodata/biodata_action.php?act=in">
                      
              <div class="form-group">
                <label for="NIM" class="control-label col-lg-2">NIM </label>
                <div class="col-lg-10">
                  <input type="text" name="nim" placeholder="NIM" class="form-control" >
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Nama Lengkap" class="control-label col-lg-2">Nama Lengkap <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="nama" placeholder="Nama Lengkap" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
            <div class="form-group">
                <label for="Jenis Kelamin" class="control-label col-lg-2">Jenis Kelamin </label>
                <div class="col-lg-10">
                  <select name="jk" data-placeholder="Pilih Jenis Kelamin ..." class="form-control chzn-select" tabindex="2" >
                    
<option value='L'>Laki - Laki</option>

<option value='P'>Perempuan</option>

                  </select>
                </div>
            </div><!-- /.form-group -->
            
              <div class="form-group">
                <label for="NIK KTP" class="control-label col-lg-2">NIK KTP <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="nik" placeholder="NIK KTP" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="NISN" class="control-label col-lg-2">NISN </label>
                <div class="col-lg-10">
                  <input type="text" name="nisn" placeholder="NISN" class="form-control" >
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="NPWP" class="control-label col-lg-2">NPWP </label>
                <div class="col-lg-10">
                  <input type="text" name="npwp" placeholder="NPWP" class="form-control" >
                </div>
              </div><!-- /.form-group -->
              <div class="form-group">
                        <label for="Kewarganegaraan" class="control-label col-lg-2">Kewarganegaraan <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-10">
            <select name="kewarganegaraan" data-placeholder="Pilih Kewarganegaraan ..." class="form-control chzn-select" tabindex="2" required>
               <option value=""></option>
               <?php foreach ($db->fetch_all("kewarganegaraan") as $isi) {
                  echo "<option value='$isi->kewarganegaraan'>$isi->nm_wil</option>";
               } ?>
              </select>
            </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Jalur Masuk" class="control-label col-lg-2">Jalur Masuk <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-10">
            <select name="id_jalur_masuk" data-placeholder="Pilih Jalur Masuk ..." class="form-control chzn-select" tabindex="2" required>
               <option value=""></option>
               <?php foreach ($db->fetch_all("jalur_masuk") as $isi) {
                  echo "<option value='$isi->id_jalur_masuk'>$isi->jalur</option>";
               } ?>
              </select>
            </div>
                      </div><!-- /.form-group -->

              <div class="form-group">
                <label for="Kota/Kab Tempat Lahir " class="control-label col-lg-2">Kota/Kab Tempat Lahir  <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="tmpt_lahir" placeholder="Kota/Kab Tempat Lahir " class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
          <div class="form-group">
              <label for="Tanggal Lahir" class="control-label col-lg-2">Tanggal Lahir <span style="color:#FF0000">*</span></label>
              <div class="col-lg-10">
                <input type="text" id="tgl1" data-rule-date="true" name="tgl_lahir" placeholder="Tanggal Lahir" class="form-control" required>
              </div>
          </div><!-- /.form-group -->
          <div class="form-group">
                        <label for="Agama" class="control-label col-lg-2">Agama <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-10">
            <select name="id_agama" data-placeholder="Pilih Agama ..." class="form-control chzn-select" tabindex="2" required>
               <option value=""></option>
               <?php foreach ($db->fetch_all("agama") as $isi) {
                  echo "<option value='$isi->id_agama'>$isi->nm_agama</option>";
               } ?>
              </select>
            </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Kecamatan" class="control-label col-lg-2">Kecamatan </label>
                        <div class="col-lg-10">
            <select name="id_wil" data-placeholder="Pilih Kecamatan ..." class="form-control chzn-select" tabindex="2" >
               <option value=""></option>
               <?php foreach ($db->fetch_all("data_wilayah") as $isi) {
                  echo "<option value='$isi->id'>$isi->nm_wil</option>";
               } ?>
              </select>
            </div>
                      </div><!-- /.form-group -->

              <div class="form-group">
                <label for="Alamat Jalan" class="control-label col-lg-2">Alamat Jalan <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="jln" placeholder="Alamat Jalan" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="RT" class="control-label col-lg-2">RT </label>
                <div class="col-lg-10">
                  <input type="text" name="rt" placeholder="RT" class="form-control" >
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="RW" class="control-label col-lg-2">RW </label>
                <div class="col-lg-10">
                  <input type="text" name="rw" placeholder="RW" class="form-control" >
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Dusun" class="control-label col-lg-2">Dusun </label>
                <div class="col-lg-10">
                  <input type="text" name="nm_dsn" placeholder="Dusun" class="form-control" >
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Kelurahan" class="control-label col-lg-2">Kelurahan </label>
                <div class="col-lg-10">
                  <input type="text" name="ds_kel" placeholder="Kelurahan" class="form-control" >
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Kode POS" class="control-label col-lg-2">Kode POS <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="kode_pos" placeholder="Kode POS" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              <div class="form-group">
                        <label for="Jenis Tinggal" class="control-label col-lg-2">Jenis Tinggal </label>
                        <div class="col-lg-10">
            <select name="id_jns_tinggal" data-placeholder="Pilih Jenis Tinggal ..." class="form-control chzn-select" tabindex="2" >
               <option value=""></option>
               <?php foreach ($db->fetch_all("jenis_tinggal") as $isi) {
                  echo "<option value='$isi->id_jns_tinggal'>$isi->jenis_tinggal</option>";
               } ?>
              </select>
            </div>
                      </div><!-- /.form-group -->

              <div class="form-group">
                <label for="No Telepon Rumah" class="control-label col-lg-2">No Telepon Rumah </label>
                <div class="col-lg-10">
                  <input type="text" name="telepon_rumah" placeholder="No Telepon Rumah" class="form-control" >
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="No Handphone" class="control-label col-lg-2">No Handphone <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="telepon_seluler" placeholder="No Handphone" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Email" class="control-label col-lg-2">Email <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                    <input type="text" data-rule-email="true" name="email" placeholder="Email" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
            
            <div class="form-group">
                <label for="Penerima KPS (Kartu Perlindungan Sosial) ?" class="control-label col-lg-2">Penerima KPS (Kartu Perlindungan Sosial) ? </label>
                <div class="col-lg-10">
                  <select name="a_terima_kps" data-placeholder="Pilih Penerima KPS (Kartu Perlindungan Sosial) ? ..." class="form-control chzn-select" tabindex="2" >
                    
<option value='0'>Tidak</option>

<option value='1'>Iya</option>

                  </select>
                </div>
            </div><!-- /.form-group -->
            
              <div class="form-group">
                <label for="No KPS" class="control-label col-lg-2">No KPS </label>
                <div class="col-lg-10">
                  <input type="text" name="no_kps" placeholder="No KPS" class="form-control" >
                </div>
              </div><!-- /.form-group -->
              <div class="form-group">
                        <label for="Jenis Pendaftaran" class="control-label col-lg-2">Jenis Pendaftaran </label>
                        <div class="col-lg-10">
            <select name="id_jns_daftar" data-placeholder="Pilih Jenis Pendaftaran ..." class="form-control chzn-select" tabindex="2" >
               <option value=""></option>
               <?php foreach ($db->fetch_all("jenis_daftar") as $isi) {
                  echo "<option value='$isi->id_jenis_daftar'>$isi->id_jenis_daftar</option>";
               } ?>
              </select>
            </div>
                      </div><!-- /.form-group -->

              <div class="form-group">
                <label for="NIK Ayah" class="control-label col-lg-2">NIK Ayah </label>
                <div class="col-lg-10">
                  <input type="text" name="nik_ayah" placeholder="NIK Ayah" class="form-control" >
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Nama Ayah" class="control-label col-lg-2">Nama Ayah </label>
                <div class="col-lg-10">
                  <input type="text" name="nm_ayah" placeholder="Nama Ayah" class="form-control" >
                </div>
              </div><!-- /.form-group -->
              
          <div class="form-group">
              <label for="Tanggal Lahir Ayah" class="control-label col-lg-2">Tanggal Lahir Ayah </label>
              <div class="col-lg-10">
                <input type="text" id="tgl2" data-rule-date="true" name="tgl_lahir_ayah" placeholder="Tanggal Lahir Ayah" class="form-control" >
              </div>
          </div><!-- /.form-group -->
          <div class="form-group">
                        <label for="Pendidikan Ayah" class="control-label col-lg-2">Pendidikan Ayah </label>
                        <div class="col-lg-10">
            <select name="id_jenjang_pendidikan_ayah" data-placeholder="Pilih Pendidikan Ayah ..." class="form-control chzn-select" tabindex="2" >
               <option value=""></option>
               <?php foreach ($db->fetch_all("jenjang_pendidikan") as $isi) {
                  echo "<option value='$isi->id_jenjang'>$isi->jenjang</option>";
               } ?>
              </select>
            </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Pekerjaan Ayah" class="control-label col-lg-2">Pekerjaan Ayah </label>
                        <div class="col-lg-10">
            <select name="id_pekerjaan_ayah" data-placeholder="Pilih Pekerjaan Ayah ..." class="form-control chzn-select" tabindex="2" >
               <option value=""></option>
               <?php foreach ($db->fetch_all("pekerjaan") as $isi) {
                  echo "<option value='$isi->id_pekerjaan'>$isi->pekerjaan</option>";
               } ?>
              </select>
            </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Penghasilan Ayah" class="control-label col-lg-2">Penghasilan Ayah </label>
                        <div class="col-lg-10">
            <select name="id_penghasilan_ayah" data-placeholder="Pilih Penghasilan Ayah ..." class="form-control chzn-select" tabindex="2" >
               <option value=""></option>
               <?php foreach ($db->fetch_all("penghasilan") as $isi) {
                  echo "<option value='$isi->id_penghasilan'>$isi->penghasilan</option>";
               } ?>
              </select>
            </div>
                      </div><!-- /.form-group -->

              <div class="form-group">
                <label for="NIK Ibu" class="control-label col-lg-2">NIK Ibu </label>
                <div class="col-lg-10">
                  <input type="text" name="nik_ibu_kandung" placeholder="NIK Ibu" class="form-control" >
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Nama Ibu " class="control-label col-lg-2">Nama Ibu  <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="nm_ibu_kandung" placeholder="Nama Ibu " class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
          <div class="form-group">
              <label for="Tanggal Lahir Ibu" class="control-label col-lg-2">Tanggal Lahir Ibu </label>
              <div class="col-lg-10">
                <input type="text" id="tgl3" data-rule-date="true" name="tgl_lahir_ibu" placeholder="Tanggal Lahir Ibu" class="form-control" >
              </div>
          </div><!-- /.form-group -->
          <div class="form-group">
                        <label for="Pendidikan Ibu" class="control-label col-lg-2">Pendidikan Ibu </label>
                        <div class="col-lg-10">
            <select name="id_jenjang_pendidikan_ibu" data-placeholder="Pilih Pendidikan Ibu ..." class="form-control chzn-select" tabindex="2" >
               <option value=""></option>
               <?php foreach ($db->fetch_all("jenjang_pendidikan") as $isi) {
                  echo "<option value='$isi->id_jenjang'>$isi->jenjang</option>";
               } ?>
              </select>
            </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Pekerjaan Ibu" class="control-label col-lg-2">Pekerjaan Ibu </label>
                        <div class="col-lg-10">
            <select name="id_pekerjaan_ibu" data-placeholder="Pilih Pekerjaan Ibu ..." class="form-control chzn-select" tabindex="2" >
               <option value=""></option>
               <?php foreach ($db->fetch_all("pekerjaan") as $isi) {
                  echo "<option value='$isi->id_pekerjaan'>$isi->pekerjaan</option>";
               } ?>
              </select>
            </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Penghasilan Ibu" class="control-label col-lg-2">Penghasilan Ibu </label>
                        <div class="col-lg-10">
            <select name="id_penghasilan_ibu" data-placeholder="Pilih Penghasilan Ibu ..." class="form-control chzn-select" tabindex="2" >
               <option value=""></option>
               <?php foreach ($db->fetch_all("penghasilan") as $isi) {
                  echo "<option value='$isi->id_penghasilan'>$isi->penghasilan</option>";
               } ?>
              </select>
            </div>
                      </div><!-- /.form-group -->

              <div class="form-group">
                <label for="Nama Wali" class="control-label col-lg-2">Nama Wali </label>
                <div class="col-lg-10">
                  <input type="text" name="nm_wali" placeholder="Nama Wali" class="form-control" >
                </div>
              </div><!-- /.form-group -->
              
          <div class="form-group">
              <label for="Tanggal Lahir Wali" class="control-label col-lg-2">Tanggal Lahir Wali </label>
              <div class="col-lg-10">
                <input type="text" id="tgl4" data-rule-date="true" name="tgl_lahir_wali" placeholder="Tanggal Lahir Wali" class="form-control" >
              </div>
          </div><!-- /.form-group -->
          <div class="form-group">
                        <label for="Jenjang Pendidikan Wali" class="control-label col-lg-2">Jenjang Pendidikan Wali </label>
                        <div class="col-lg-10">
            <select name="id_jenjang_pendidikan_wali" data-placeholder="Pilih Jenjang Pendidikan Wali ..." class="form-control chzn-select" tabindex="2" >
               <option value=""></option>
               <?php foreach ($db->fetch_all("jenjang_pendidikan") as $isi) {
                  echo "<option value='$isi->id_jenjang'>$isi->jenjang</option>";
               } ?>
              </select>
            </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Pekerjaan Wali" class="control-label col-lg-2">Pekerjaan Wali </label>
                        <div class="col-lg-10">
            <select name="id_pekerjaan_wali" data-placeholder="Pilih Pekerjaan Wali ..." class="form-control chzn-select" tabindex="2" >
               <option value=""></option>
               <?php foreach ($db->fetch_all("pekerjaan") as $isi) {
                  echo "<option value='$isi->id_pekerjaan'>$isi->pekerjaan</option>";
               } ?>
              </select>
            </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Penghasilan Wali" class="control-label col-lg-2">Penghasilan Wali </label>
                        <div class="col-lg-10">
            <select name="id_penghasilan_wali" data-placeholder="Pilih Penghasilan Wali ..." class="form-control chzn-select" tabindex="2" >
               <option value=""></option>
               <?php foreach ($db->fetch_all("penghasilan") as $isi) {
                  echo "<option value='$isi->id_penghasilan'>$isi->penghasilan</option>";
               } ?>
              </select>
            </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Dosen Pembimbing" class="control-label col-lg-2">Dosen Pembimbing </label>
                        <div class="col-lg-10">
            <select name="dosen_pemb" data-placeholder="Pilih Dosen Pembimbing ..." class="form-control chzn-select" tabindex="2" >
               <option value=""></option>
               <?php foreach ($db->fetch_all("dosen") as $isi) {
                  echo "<option value='$isi->id_dosen'>$isi->nama_dosen</option>";
               } ?>
              </select>
            </div>
                      </div><!-- /.form-group -->

                      
              <div class="form-group">
                <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                <div class="col-lg-10">
                  <input type="submit" class="btn btn-primary " value="submit">
                </div>
              </div><!-- /.form-group -->

            </form>

            <a href="<?=base_index();?>biodata" class="btn btn-success "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>

          </div>
        </div>
      </div>
    </div>

    </section><!-- /.content -->

<script type="text/javascript">
    $(document).ready(function() {
     
          //chosen select
          $(".chzn-select").chosen();
          $(".chzn-select-deselect").chosen({
              allow_single_deselect: true
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
    
      //trigger validation onchange
      $('select').on('change', function() {
          $(this).valid();
      });
      //hidden validate because we use chosen select
      $.validator.setDefaults({ ignore: ":hidden:not(select)" });
      
    $("#input_biodata").validate({
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
                data: $("#input_biodata").serialize(),
                success: function(data) {
                    console.log(data);
                    $("#loadnya").hide();
                    if (data == "good") {
                        $(".notif_top").fadeIn(1000);
                        $(".notif_top").fadeOut(1000, function() {
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
