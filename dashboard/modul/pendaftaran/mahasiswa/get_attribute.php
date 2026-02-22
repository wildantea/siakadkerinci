<?php
session_start();
include "../../../inc/config.php";
session_check_json();
$data_mhs = $db2->fetchSingleRow('view_simple_mhs','nim',$_SESSION['username']);
$pendaftaran_setting = $db2->fetchSingleRow('tb_data_pendaftaran_jenis_pengaturan','id_jenis_pendaftaran_setting',$_POST['id_jenis_pendaftaran_setting']);

/*print_r($pendaftaran_setting);
exit();*/
function fetch_curl($url) 
{ 
   $ch = curl_init(); 
   $timeout = 5; 
   curl_setopt ($ch, CURLOPT_URL, $url); 
   curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
   curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
   curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout); 
   $result = curl_exec($ch); 

   //$http_respond = trim( strip_tags( $result ) );
   $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );

   $result = json_decode($result);
   curl_close($ch); 
   return $result;
}


//check ada matkul syarat
if ($pendaftaran_setting->ada_matkul_syarat=='Y') {
    $get_id_matkul_syarat = $db2->fetchCustomSingle("select matkul_syarat from tb_data_pendaftaran_jenis_pengaturan_prodi where id_jenis_pendaftaran_setting=? and kode_jur=?",array('id_jenis_pendaftaran_setting' => $pendaftaran_setting->id_jenis_pendaftaran_setting,'kode_jur' => $data_mhs->kode_jur));
    $dta = $db2->fetchCustomSingle('select id_krs_detail from krs_detail where nim=? and kode_mk=? and id_semester=?',array('nim' => $_SESSION['username'],'kode_mk' => $get_id_matkul_syarat->matkul_syarat,'id_semester' => getSemesterAktif()));
   
//echo $db2->getErrorMessage();
    //get detail matkul
    $matkul_name = $db2->fetchSingleRow('matkul','id_matkul',$get_id_matkul_syarat->matkul_syarat);
    if ($dta==false) {
      ?>
    <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-ban"></i> Error!</h4>
               Maaf anda diharuskan ambil Krs di Semester ini untuk Matakuliah <b style="color: #000;"><?=$matkul_name->nama_mk;?></b>
              </div>
  <?php
      exit();
    }
}
 


/*if ($pendaftaran_setting->harus_lulus_ict=='Y') {
  $array_post = array(
    'nim' => $_SESSION['username']
  );
  $status_ict = post_data('https://ict.uinsgd.ac.id/Api/Kelulusan/token/9025aad1019e08f18910c20fa6161259',$array_post,$array_get = array(),'json');

  if( $status_ict->status=='error' ) {
  ?>
    <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-ban"></i> Error!</h4>
               Maaf anda belum bisa daftar karena Belum LULUS/Selesai ICT, Silakan Hubungi Admin PTIPD
              </div>
    <?php
    exit();
  } else {
    if ($status_ict->data->status_lulus!='Lulus' ) {
      ?>
          <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-ban"></i> Error!</h4>
               Maaf anda belum bisa daftar karena Belum LULUS/Selesai ICT, Silakan Hubungi Admin PTIPD
              </div>
              <?php
              exit();
    }
  }
} */

/*
if ($pendaftaran_setting->harus_lulus_toefa=='Y') {
    $status_toefa = fetch_curl('http://128.199.184.54/curl_lc.php?nim='.$_SESSION['username'].'&jenis=1');
  if( $status_toefa->status==false) {
  ?>
    <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-ban"></i> Error!</h4>
               Anda tidak terdaftar di Pusat Bahasa atau belum Mengikuti Ujian TOEFA, Silakan hubungi Admin Pusat Bahasa
              </div>
    <?php
    exit();
  } else {
    if ($status_toefa->score<$pendaftaran_setting->min_nilai_toefa) {
    ?>
      <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h4><i class="icon fa fa-ban"></i> Error!</h4>
                 Nilai Ujian TOEFA (Test Of English For Academics) Anda <?=$status_toefa->score;?>, sehingga belum memenuhi skor minimal yakni <?=$pendaftaran_setting->min_nilai_toefa;?>, Silakan hubungi Admin Pusat Bahasa
                </div>
      <?php
      exit();
    }
  }
} */
//check ara
/*if ($pendaftaran_setting->harus_lulus_toafl=='Y') {
    $status_toafl = fetch_curl('http://128.199.184.54/curl_lc.php?nim='.$_SESSION['username'].'&jenis=2');
  if( $status_toafl->status==false) {
  ?>
    <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-ban"></i> Error!</h4>
               Anda tidak terdaftar di Pusat Bahasa atau belum Mengikuti Ujian TOEFA, Silakan hubungi Admin Pusat Bahasa
              </div>
    <?php
    exit();
  } else {
    if ($status_toafl->score<$pendaftaran_setting->min_nilai_toafl) {
    ?>
      <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h4><i class="icon fa fa-ban"></i> Error!</h4>
                 Nilai Ujian TOAFL (Test of Arabic as Foreign Language) Anda <?=$status_toafl->score;?>, sehingga belum memenuhi skor minimal yakni <?=$pendaftaran_setting->min_nilai_toafl;?>, Silakan hubungi Admin Pusat Bahasa
                </div>
      <?php
      exit();
    }
  }
}*/ 

//if syarat daftar not empty
if ($pendaftaran_setting->syarat_daftar!='') {
  $get_id_pendaftaran_setting = $db2->query("select id_jenis_pendaftaran_setting,nama_jenis_pendaftaran from view_jenis_pendaftaran where id_jenis_pendaftaran in($pendaftaran_setting->syarat_daftar) and kode_jur=?",array('kode_jur' => $data_mhs->kode_jur));
  foreach ($get_id_pendaftaran_setting as $dts) {
    $check_pendaftaran = $db2->checkExist('tb_data_pendaftaran',array('nim' => $data_mhs->nim,'id_jenis_pendaftaran_setting' => $dts->id_jenis_pendaftaran_setting,'status' => 4));
    if ($check_pendaftaran==false) {
      ?>
    <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-ban"></i> Error!</h4>
               Maaf anda tidak bisa daftar karena belum Lulus/Selesai Pendaftaran <?=$dts->nama_jenis_pendaftaran;?>
              </div>
      <?php
      exit();
    }
  }

}

/* if ($pendaftaran_setting->harus_lunas_spp=='Y') {
    //$dta = fetch_curl('https://keuangan.uinsgd.ac.id/b2b/SPP/status/nim/'.$_SESSION['username'].'?wskey=Y9SdptijlddozZo_3D');

     $dta = fetch_curl('http://128.199.184.54/curl_keuangan.php?nim='.$_SESSION['username']);

    if ($dta->status==false) {
    ?>
    <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-ban"></i> Error!</h4>
                Sedang Maintenance Silakan Coba beberapa saat lagi
               <!--  NIM anda tidak terdaptar di Bagian keuangan, Silakan Hubungi Bagian Keuangan -->
              </div>
    <?php
    exit();
    } else {

/*      $semester = $dta->data->semester;
      $smt = "smt$semester";
      if ($dta->data->{$smt}) {
        if($dta->ket_lunas!='LUNAS') {
        ?>
    <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-ban"></i> Error!</h4>
                Anda masih punya tunggakan SPP, Silakan Hubungi Bagian Keuangan
              </div>
        <?php
        exit();
      }
      
    }
}
 */

//check sks if pengaturan exist
if ($pendaftaran_setting->ada_sks_ditempuh=='Y') {
  $check_sks = $db->fetch_custom_single("select sum(sks) as sks from krs_detail where krs_detail.nim='".$_SESSION['username']."'");
    if ($check_sks->sks <$pendaftaran_setting->jumlah_sks_ditempuh) {
      ?>
    <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-ban"></i> Error!</h4>
               Maaf SKS yang Anda tempuh belum memenuhi syarat untuk melakukan pendaftaran ini
              </div>
  <?php
      exit();
    }
}




if ($pendaftaran_setting->ada_judul=='Y') {
  ?>
            <div class="form-group">
              <label for="Judul " class="control-label col-lg-2">Judul</label>
              <div class="col-lg-10">
                <textarea class="form-control col-xs-12 editbox" id="editbox" rows="5" name="judul" required=""></textarea>
              </div>
          </div><!-- /.form-group -->
<?php
}

if ($pendaftaran_setting->ada_pembimbing=='Y') {
  for ($i=1; $i <= $pendaftaran_setting->jumlah_pembimbing; $i++) { 
?>
          <div class="form-group">
          <label for="Nama Pendaftaran" class="control-label col-lg-3">Pembimbing <?=$i;?> </label>
            <div class="col-lg-9">
              <select id="pembimbing_<?=$i;?>" name="pembimbing[]" data-placeholder="Pilih Dosen Pembimbing <?=$i;?>..." class="form-control pembimbing" tabindex="2" required="">
               <option value=""></option>
              </select>
            </div>
          </div><!-- /.form-group -->
<?php
  }
}
if ($pendaftaran_setting->bukti!='') {
  ?>
  <hr style="margin: 10px;">
  <div class="col-lg-3">&nbsp;</div>
  <h4 class="col-lg-9">Bukti Persayaratan</h4>
<?php
  $syarat_pendaftaran = $db2->query("select * from tb_data_pendaftaran_jenis_bukti where id_jenis_bukti in($pendaftaran_setting->bukti)");
  foreach ($syarat_pendaftaran as $syarat) {
    ?>
    <input type="hidden" name="id_jenis_bukti[]" value="<?=$syarat->id_jenis_bukti;?>">
<div class="form-group" style="margin-bottom: 0">
  <div class="col-lg-3" style="padding-top: 26px;">
    <label class="radio-inline">
      <input type="radio" name="type_dokumen[<?=$syarat->id_jenis_bukti;?>]" checked="" value='1' data-id="<?=$syarat->id_jenis_bukti;?>" class="radio-change">Upload
    </label>
    <label class="radio-inline">
      <input type="radio" value="0" data-id="<?=$syarat->id_jenis_bukti;?>" name="type_dokumen[<?=$syarat->id_jenis_bukti;?>]"  class="radio-change">Link
    </label>
  </div>
                         <div class="col-lg-9">
                        <label for="File" class="control-label"><?=$syarat->jenis_bukti;?><span style="color:#FF0000">*</span></label>
                      <div id="show-link-<?=$syarat->id_jenis_bukti;?>" style="display: none">
                        <input type="text" id="link_upload_<?=$syarat->id_jenis_bukti;?>" name="link_dokumen[<?=$syarat->id_jenis_bukti;?>]" class="form-control" placeholder="https://linkbukti.com">
                      </div>
                      <div id="show-upload-<?=$syarat->id_jenis_bukti;?>">
                      <div class="fileinput fileinput-new" data-provides="fileinput" style="margin-bottom: 0">
                            <div class="input-group">
                              <div class="form-control uneditable-input span3" data-trigger="fileinput">
                                <i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span>
                              </div>
                              <span class="input-group-addon btn btn-default btn-file"><span class="fileinput-new">Select file</span> <span class="fileinput-exists">Change</span>
                                <input type="file" id="file_upload_<?=$syarat->id_jenis_bukti;?>" name="file_name[<?=$syarat->id_jenis_bukti;?>]" required class="file-upload-data" accept="image/*,application/pdf">
                              </span>
                              <a href="#" class="input-group-addon btn btn-default fileinput-exists remove-<?=$syarat->id_jenis_bukti;?>" data-dismiss="fileinput">Remove</a>
                            </div>
                          </div>
                      </div>
                        </div>
                      </div><!-- /.form-group -->
    <?php
  }
?>


<?php
}

if ($pendaftaran_setting->has_attr=='Y') {
          $attr_value = json_decode($pendaftaran_setting->attr_value);
          $form_attribute = "";
          foreach ($attr_value as $attr) {
            if ($attr->attr_type=="dropdown") {
                     // print_r($attr);
                $form_attribute.='
              <div class="form-group">
                    <label for="single" class="control-label col-lg-3">'.$attr->attr_label.'</label>
                    <div class="col-lg-9">
              <select name="'.$attr->attr_name.'" id="'.$attr->attr_name.'" class="form-control chzn-select">';
/*              foreach ($attr->dropdown_data->value as $key_lb => $lb) {
                $form_attribute.="<option value='$lb'>".$attr->dropdown_data->label[$key_lb]."</option>";
              }*/
              foreach ($attr->dropdown_data->value as $key_lb => $lb) {
                $form_attribute.="<option value='$lb'>".$lb."</option>";
              }
            $form_attribute.='</select>
            </div>
              </div>';
            } elseif ($attr->attr_type=="multiple_choice") {
                     // print_r($attr);
                $form_attribute.='
              <div class="form-group">
                    <label for="single" class="control-label col-lg-3">'.$attr->attr_label.'</label>
                    <div class="col-lg-9">';
                ?>
                <?php
              foreach ($attr->multiple_choice_data->value as $key_radio => $lb_radio) {
                $form_attribute.='<div class="radio radio-success ">
                  <input type="radio" name="'.$attr->attr_name.'"  id="radio'.$key_radio.'" value="'.$lb_radio.'"><label for="radio'.$key_radio.'" style="padding-left: 5px;">
                      '.$lb_radio.'
                    </label>
                </div>';
              }
            $form_attribute.='
            </div>
              </div>';
            } elseif ($attr->attr_type=="paragraph") {
                  if(array_key_exists('required', $attr)) {
                      $required = "required";
                  } else {
                    $required = "";
                  }
                     // print_r($attr);
                $form_attribute.='
              <div class="form-group">
                  <label for="single" class="control-label col-lg-3">'.$attr->attr_label.'</label>
                   <div class="col-lg-9">
                  <textarea name="'.$attr->attr_name.'" id="'.$attr->attr_name.'" '.$required.' class="form-control" rows="5"></textarea>
                  </div>
              </div>';
            } elseif ($attr->attr_type=='number') {
                  if(array_key_exists('required', $attr)) {
                      $required = "required";
                  } else {
                    $required = "";
                  }
                     // print_r($attr);
                $form_attribute.='
              <div class="form-group">
                  <label for="single" class="control-label col-lg-3">'.$attr->attr_label.'</label>
                   <div class="col-lg-9">
                  <input type="text" name="'.$attr->attr_name.'" data-rule-number="true" id="'.$attr->attr_name.'" '.$required.' class="form-control">
                  </div>
              </div>';
            } elseif ($attr->attr_type=='date') {
                  if(array_key_exists('required', $attr)) {
                      $required = "required";
                  } else {
                    $required = "";
                  }
                     // print_r($attr);
                $form_attribute.='
              <div class="form-group">
                  <label for="single" class="control-label col-lg-3">'.$attr->attr_label.'</label>
                   <div class="col-lg-3">
                   <div class="input-group date tgl_picker">
                      <span class="input-group-addon">
                          <span class="glyphicon glyphicon-calendar"></span>
                      </span>
                      <input type="text" autocomplete="off" class="form-control tgl_picker_input" name="'.$attr->attr_name.'" id="'.$attr->attr_name.'" '.$required.'/>
                   </div>
                  </div>
              </div>';
            }  else {
                  if(array_key_exists('required', $attr)) {
                      $required = "required";
                  } else {
                    $required = "";
                  }
                     // print_r($attr);
                $form_attribute.='
              <div class="form-group">
                  <label for="single" class="control-label col-lg-3">'.$attr->attr_label.'</label>
                   <div class="col-lg-9">
                  <input type="text" name="'.$attr->attr_name.'" id="'.$attr->attr_name.'" '.$required.' class="form-control">
                  </div>
              </div>';
            }

          }

          echo $form_attribute;

  ?>


  <?php
}

if ($pendaftaran_setting->id_jenis_pendaftaran=='1') {
  ?>
              <div class="form-group">
                <label for="Nim" class="control-label col-lg-4">Nomor SK Judul / Pembimbing Skripsi <span style="color:#FF0000">*</span></label>
                <div class="col-lg-8">
                  <input type="text" id="nomor_sk_tugas" name="nomor_sk_tugas" placeholder="Nomor SK Judul / Pembimbing Skripsi, Lihat dibawah header" class="form-control" required>
                   
                </div>
              </div><!-- /.form-group -->

              <div class="form-group">
                 <label for="Semester" class="control-label col-lg-4">Tanggal SK Judul / Pembimbing Skripsi <span style="color:#FF0000">*</span></label>
                <div class="col-lg-8">
                   <div class="input-group date tgl_picker">
                      <span class="input-group-addon">
                          <span class="glyphicon glyphicon-calendar"></span>
                      </span>
                      <input type="text" autocomplete="off" class="form-control tgl_picker_input" name="tanggal_sk_tugas" placeholder="Lihat di tanda tangan Dekan" required="" />
                   </div>
                </div>
            </div>

              <div class="form-group">
                <label for="Nim" class="control-label col-lg-2">Lokasi Penelitian</label>
                <div class="col-lg-8">
                  <input type="text" id="lokasi" name="lokasi" placeholder="Isi Lokasi Penelitian Jika ada" class="form-control">
                </div>
              </div><!-- /.form-group -->

  <?php
}

if ($pendaftaran_setting->keterangan!='') {
  ?>
<div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-info"></i> Keterangan Lain!</h4>
                <?=$pendaftaran_setting->keterangan;?>
              </div>
  <?php
}
?>

  <input type="hidden" value="<?=$_POST['id_jenis_pendaftaran_setting'];?>" name="id_jenis_pendaftaran_setting">
              <div class="form-group">
                <div class="col-lg-12">
                  <div class="modal-footer"> 
                  <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> <?php echo $lang["cancel_button"];?></button>
                  <button type="submit" class="btn btn-primary save-data"><i class="fa fa-save"></i> <?php echo $lang["submit_button"];?></button>
                  </div>
                </div>
              </div><!-- /.form-group -->
<script type="text/javascript">
$(document).ready(function(){
  $( ".pembimbing" ).select2({
      allowClear: true,
    width: "100%",
    ajax: {
      url: '<?=base_admin();?>modul/pendaftaran/mahasiswa/data_dosen.php',
      dataType: 'json'
      // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
    },
    formatInputTooShort: "Cari & Pilih Nama Dosen"
  });
});
            $(".tgl_picker").datepicker({
        format: "yyyy-mm-dd",
        autoclose: true,
        todayHighlight: true
        }).on("change",function(){
          $(":input",this).valid();
        });

    $("textarea.editbox" ).ckeditor();

    $('.radio-change').change(function() {
        var id = $(this).data('id');
        //if type upload
        if (this.value=='1') {
          $("#show-upload-"+id).show();
          $("#show-link-"+id).hide();
          $("#link_upload_"+id).prop('required',false);
          $("#file_upload_"+id).prop('required',true);
        } else {
          $("#show-upload-"+id).hide();
          $("#show-link-"+id).show();
          $("#link_upload_"+id).prop('required',true);
          $("#file_upload_"+id).prop('required',false);
          $( ".remove-"+id).trigger( "click" );
        }
    });

    $(document).ready(function() {
      //trigger validation onchange
      $('select').on('change', function() {
          $(this).valid();
      });
      //hidden validate because we use chosen select
      $.validator.setDefaults({ ignore: ":hidden:not(select)" });
      //chosen select
      $(".chzn-select").chosen();
      $(".chzn-select-deselect").chosen({
          allow_single_deselect: true
      });

});
</script>