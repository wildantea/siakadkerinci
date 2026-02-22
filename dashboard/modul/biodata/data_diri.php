
                                     
                                        <?php

                                        if(!empty(checkBiodataMahasiswaDataDiri($_SESSION['username']))) {
                                        ?>
                                         <div id="errorAlert" class="alert alert-danger alert-dismissible fade in alert-container" >
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <h4><i class="icon fa fa-ban"></i> Mohon lengkapi kolom-kolom berikut!</h4>
                                        <ul id="errorList">
                                            <?php
                                            foreach (checkBiodataMahasiswaDataDiri($_SESSION['username']) as $error) {
                                               echo "<li>".$error."</li>";
                                            }
                                            ?>
                                            </ul>
                                               </div>
                                        <?php
                                    }
                                    ?>
                                 
                                        <div class="form-group">
                                            <label for="NIM" class="control-label col-lg-2">NIM</label>
                                            <div class="col-lg-10">
                                                <input type="text" name="nim" readonly="" value="<?=$data_edit->nim;?>" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Nama Lengkap" class="control-label col-lg-2">Nama Lengkap <span style="color:#FF0000">*</span></label>
                                            <div class="col-lg-10">
                                                <input type="text" name="nama" value="<?=$data_edit->nama;?>" class="form-control" required>
                                                <p class="help-block" style="color:#1dc0ef">Nama Lengkap Wajib Sesuai Ijazah</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Jenis Kelamin" class="control-label col-lg-2">Jenis Kelamin <span style="color:#FF0000">*</span></label>
                                            <div class="col-lg-10">
                                                <select name="jk" data-placeholder="Pilih Jenis Kelamin..." class="form-control chzn-select" tabindex="2">
                                                    <option value=""></option>
                                                    <?php
                                                    $option = array(
                                                        'L' => 'Laki - Laki',
                                                        'P' => 'Perempuan',
                                                    );
                                                    foreach ($option as $isi => $val) {
                                                        echo "<option value='$isi'" . ($data_edit->jk == $isi ? ' selected' : '') . ">$val</option>";
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Status Pernikahan" class="control-label col-lg-2">Status Pernikahan <span style="color:#FF0000">*</span></label>
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
                                                        echo "<option value='$isi'" . ($data_edit->status_pernikahan == $isi ? ' selected' : '') . ">$val</option>";
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Nomor KTP" class="control-label col-lg-2">NIK KTP <span style="color:#FF0000">*</span></label>
                                            <div class="col-lg-10">
                                                <input type="text" data-rule-number="true" name="nik" value="<?=$data_edit->nik;?>" class="form-control" required minlength="16" maxlength="16" onkeypress="return isNumberKey(event)">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="NISN" class="control-label col-lg-2">NISN <span style="color:#FF0000">*</span></label>
                                            <div class="col-lg-10">
                                                <input type="text" name="nisn" data-rule-number="true" placeholder="No Induk Siswa Nasional" value="<?=$data_edit->nisn;?>" class="form-control" onkeypress="return isNumberKey(event)" maxlength="10" required>
                                                <p class="help-block" style="color:#1dc0ef">Wajib isi jika ada, jika tidak isi dengan 0000000000</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="NPWP" class="control-label col-lg-2">NPWP</label>
                                            <div class="col-lg-10">
                                                <input type="text" name="npwp" placeholder="NPWP" value="<?=$data_edit->npwp;?>" class="form-control" onkeypress="return isNumberKey(event)">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Kewarganegaraan" class="control-label col-lg-2">Kewarganegaraan <span style="color:#FF0000">*</span></label>
                                            <div class="col-lg-10">
                                                <select name="kewarganegaraan" id="kewarganegaraan" data-placeholder="Pilih Kewarganegaraan..." class="form-control chzn-select" tabindex="2" required>
                                                    <option value=""></option>
                                                    <?php foreach ($db->fetch_all("kewarganegaraan") as $isi) {
                                                        echo "<option value='$isi->kewarganegaraan'" . ($data_edit->kewarganegaraan == $isi->kewarganegaraan || ($data_edit->kewarganegaraan == "" && $isi->kewarganegaraan == 'ID') ? ' selected' : '') . ">$isi->nm_wil</option>";
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Tempat Lahir" class="control-label col-lg-2">Tempat Lahir <span style="color:#FF0000">*</span></label>
                                            <div class="col-lg-10">
                                                <input type="text" name="tmpt_lahir" value="<?=$data_edit->tmpt_lahir;?>" class="form-control" required maxlength="32" required>
                                                <p class="help-block" style="color:#1dc0ef">Tempat Lahir Wajib Sesuai Ijazah</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Tanggal Lahir" class="control-label col-lg-2">Tanggal Lahir <span style="color:#FF0000">*</span></label>
                                            <div class="col-lg-10">
                                                <div class="row">
                                                    <div class="col-xs-4">
                                                        <select id="tgl_lahir_tanggal" name="tgl_lahir_tanggal" class="form-control lahir tgl_lahir_tanggal chzn-select" required placeholder="Tanggal">
                                                            <option value="">Tanggal</option>
                                                            <?php
                                                            $tgl_lahir = substr($data_edit->tgl_lahir, 8, 2);
                                                            for ($i = 1; $i <= 31; $i++) {
                                                                $val = $i < 10 ? "0$i" : $i;
                                                                echo "<option value='$val'" . ($val == $tgl_lahir ? ' selected' : '') . ">$val</option>";
                                                            } ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-xs-4">
                                                        <select id="tgl_lahir_bulan" name="tgl_lahir_bulan" class="form-control lahir tgl_lahir_bulan chzn-select" required>
                                                            <option value="">Bulan</option>
                                                            <?php
                                                            $bulan_lahir = substr($data_edit->tgl_lahir, 5, 2);
                                                            for ($i = 1; $i <= 12; $i++) {
                                                                $val = $i < 10 ? "0$i" : $i;
                                                                echo "<option value='$val'" . ($val == $bulan_lahir ? ' selected' : '') . ">" . getBulan($val) . "</option>";
                                                            } ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-xs-4">
                                                        <select id="tgl_lahir_tahun" name="tgl_lahir_tahun" class="form-control lahir tgl_lahir_tahun chzn-select" required>
                                                            <option value="">Tahun</option>
                                                            <?php
                                                            $minimum_age = date("Y", strtotime("-100 year", time()));
                                                            $maximum_age = date("Y", strtotime("-15 year", time()));
                                                            $tahun_lahir = substr($data_edit->tgl_lahir, 0, 4);
                                                            for ($i = $maximum_age; $i >= $minimum_age; $i--) {
                                                                echo "<option value='$i'" . ($i == $tahun_lahir ? ' selected' : '') . ">$i</option>";
                                                            } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <p class="help-block" style="color:#1dc0ef">Tanggal Lahir Wajib Sesuai Ijazah Asal</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Agama" class="control-label col-lg-2">Agama <span style="color:#FF0000">*</span></label>
                                            <div class="col-lg-10">
                                                <select name="id_agama" id="id_agama" data-placeholder="Pilih Agama..." class="form-control chzn-select" tabindex="2" required>
                                                    <option value=""></option>
                                                    <?php foreach ($db->fetch_all("agama") as $isi) {
                                                        echo "<option value='$isi->id_agama'" . ($data_edit->id_agama == $isi->id_agama || ($data_edit->id_agama == "" && $isi->id_agama == '1') ? ' selected' : '') . ">$isi->nm_agama</option>";
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Penerima KPS" class="control-label col-lg-2">Penerima KPS?</label>
                                            <div class="col-lg-10">
                                                <select name="a_terima_kps" id="a_terima_kps" data-placeholder="Pilih Penerima KPS..." class="form-control chzn-select" tabindex="2">
                                                    <option value=""></option>
                                                    <?php
                                                    $option = array('0' => 'Tidak', '1' => 'Iya');
                                                    foreach ($option as $isi => $val) {
                                                        echo "<option value='$isi'" . ($data_edit->a_terima_kps == $isi ? ' selected' : '') . ">$val</option>";
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="No KPS" class="control-label col-lg-2">No KPS</label>
                                            <div class="col-lg-10">
                                                <input type="text" name="no_kps" id="no_kps" readonly="" value="<?=$data_edit->no_kps;?>" class="form-control">
                                            </div>
                                        </div>
                                          <div class="form-group">
                                            <label for="Jalur Masuk" class="control-label col-lg-2">Jalur Masuk <span style="color:#FF0000">*</span></label>
                                            <div class="col-lg-10">
                                                <select name="id_jalur_masuk" id="id_jalur_masuk" data-placeholder="Pilih Jalur Masuk..." class="form-control chzn-select" tabindex="2">
                                                    <option value=""></option>
                                                    <?php foreach ($db->fetch_all("jalur_masuk") as $isi) {
                                                        echo "<option value='$isi->id_jalur_masuk'" . ($data_edit->id_jalur_masuk == $isi->id_jalur_masuk ? ' selected' : '') . ">$isi->nm_jalur_masuk</option>";
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Jenis Pendaftaran" class="control-label col-lg-2">Jenis Pendaftaran <span style="color:#FF0000">*</span></label>
                                            <div class="col-lg-10">
                                                <select name="id_jns_daftar" id="id_jns_daftar" data-placeholder="Pilih Jenis Pendaftaran..." class="form-control chzn-select" tabindex="2">
                                                    <option value=""></option>
                                                    <?php foreach ($db->fetch_all("jenis_daftar") as $isi) {
                                                        echo "<option value='$isi->id_jenis_daftar'" . ($data_edit->id_jns_daftar == $isi->id_jenis_daftar ? ' selected' : '') . ">$isi->nm_jns_daftar</option>";
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
<?php
if ($data_edit->id_jns_daftar!=1) {
  $show = "";
  
  $required = 'required=""';
  } else {
  $show = "style='display:none'";
  $required = "";
  
}
?>
<div class="form-group" id="show_asal_pt" <?=$show?>>
                        <label for="Jenis Pendaftaran" class="control-label col-lg-2">Asal Perguruan Tinggi</label>
                        <div class="col-lg-10">
                          <select name="kode_pt_asal" id="kode_pt_asal" data-placeholder="Pilih Asal Perguruan Tinggi ..." class="form-control" tabindex="2" <?=$required;?>>
                          <?php
                          if ($data_edit->id_jns_daftar!=1 && $data_edit->id_pt_asal!="") {
                            $kampus = $db->fetch_single_row("satuan_pendidikan","id_sp",$data_edit->id_pt_asal);
                             echo "<option value='$kampus->id_sp'>$kampus->nm_lemb</option>";
                          }
                          ?>

              </select>
                        </div>
                      </div><!-- /.form-group -->
<div class="form-group" id="show_asal_prodi" <?=$show?>>
                        <label for="Jenis Pendaftaran" class="control-label col-lg-2">Asal Program Studi</label>
                        <div class="col-lg-10">
                          <select name="kode_prodi_asal" id="kode_prodi_asal" data-placeholder="Pilih Asal Program Studi ..." class="form-control chzn-select" tabindex="2" style="width:100%;">
                              <option value=""></option>
                          <?php
                          if ($data_edit->id_jns_daftar!=1 ) {
                            $prodis = $db->query("SELECT concat(jenjang,' ',nm_lemb) as nama_jurusan,kode_prodi,id_sms from jenjang_pendidikan INNER join sms on id_jenjang=id_jenj_didik where id_sp=?",array('id_sp' => $data_edit->id_pt_asal));
                            foreach ($prodis as $prodi) {
                              if ($data_edit->id_prodi_asal==$prodi->id_sms) {
                                 echo "<option value='$prodi->id_sms' selected>$prodi->nama_jurusan</option>";
                              } else {
                                echo "<option value='$prodi->id_sms'>$prodi->nama_jurusan</option>";
                              }
                          }
                        }
                          ?>
              </select>
                        </div>
                      </div><!-- /.form-group -->
                                        <div class="form-group">
                                                    <label for="Penghasilan" class="control-label col-lg-2">Jenis Pembiayaan Kuliah <span style="color:#FF0000">*</span></label>
                                                    <div class="col-lg-10">
                                                      <select name="id_pembiayaan" id="id_pembiayaan" data-placeholder="Jenis Pembiayaan..." class="form-control chzn-select" tabindex="2" required="">
                                           <option value=""></option>
                                           <?php foreach ($db->fetch_all("pembiayaan") as $isi) {

                                              if ($data_edit->id_pembiayaan==$isi->id_pembiayaan) {
                                                echo "<option value='$isi->id_pembiayaan' selected>$isi->nm_pembiayaan</option>";
                                              } else {
                                              echo "<option value='$isi->id_pembiayaan'>$isi->nm_pembiayaan</option>";
                                                }
                                           } ?>
                                          </select>
                                                    </div>
                                                  </div><!-- /.form-group -->
                                        <div class="form-group">
                                            <label for="Asal Sekolah" class="control-label col-lg-2">Asal Sekolah <span style="color:#FF0000">*</span></label>
                                            <div class="col-lg-10">
                                                <select id="id_jenis_sekolah" name="id_jenis_sekolah" data-placeholder="Jenis Asal Sekolah..." class="form-control chzn-select" tabindex="2" required>
                                                    <option value=""></option>
                                                    <?php foreach ($db->fetch_all("tb_ref_jenis_asal_sekolah") as $isi) {
                                                        echo "<option value='$isi->id_jenis_sekolah'" . ($data_edit->id_jenis_sekolah == $isi->id_jenis_sekolah ? ' selected' : '') . ">$isi->nama_jenis_sekolah</option>";
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Nama Asal Sekolah" class="control-label col-lg-2">Nama Asal Sekolah/Lembaga <span style="color:#FF0000">*</span></label>
                                            <div class="col-lg-10">
                                                <input type="text" name="nama_asal_sekolah" value="<?=$data_edit->nama_asal_sekolah;?>" class="form-control" required>
                                            </div>
                                        </div>
                                         <input type="hidden" name="act" value="datadiri">
                                         <div class="form-group">
                                    <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                                    <div class="col-lg-10">
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                                       </a>
                                    </div>
                                </div>

<script>
    $(document).ready(function () {



      $("#id_jns_daftar").change(function(){
    if ($(this).val()!=1) {
        //$("#show_sks_diakui").show();
        $("#show_asal_pt").show();
        $("#show_asal_prodi").show();
        //$("#show_sks_diakui").prop('required',true);
        $("#kode_pt_asal").prop('required',true);
        //$("#show_asal_prodi").prop('required',true);
    } else {
       // $("#show_sks_diakui").hide();
        $("#show_asal_pt").hide();
        $("#show_asal_prodi").hide();
        //$("#show_sks_diakui").prop('required',false);
        $("#kode_pt_asal").prop('required',false);
        //$("#show_asal_prodi").prop('required',false);
    }

});



});
</script>