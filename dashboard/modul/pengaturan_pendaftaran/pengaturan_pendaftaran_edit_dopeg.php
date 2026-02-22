<style type="text/css">
.modal.fade:not(.in) .modal-dialog {
    -webkit-transform: translate3d(-25%, 0, 0);
    transform: translate3d(-25%, 0, 0);
}
.peserta-kelas {
  cursor: pointer;
}
.modal-abs {
  width: 98%;
  padding: 0;
}

.modal-content-abs {
  height: 99%;
}
  .nav-tabs-custom>.nav-tabs {
    border-bottom-color: #3c8dbc;
  }
.nav-tabs-custom>.nav-tabs>li.active>a {
    border-left-color: #3c8dbc;
    border-right-color: #3c8dbc;
  }
.nav-tabs-custom>.nav-tabs>li {
  border-top: 2px solid transparent;
    margin-bottom: -1px;
}
.nav-tabs-custom>.nav-tabs>li.active>a {
    border-top-color: #3c8dbc;
}
#modal_pendaftaran {
      z-index: 1500;
}
    .draggable-form-group {
      cursor: move;
    }

    .drag-handle {
      cursor: move;
    }
</style>

<!-- Content Header (Page header) -->
              <section class="content-header">
                  <h1>Pengaturan Pendaftaran</h1>
                    <ol class="breadcrumb">
                        <li>
                        <a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a>
                        </li>
                        <li>
                        <a href="<?=base_index();?>pengaturan-pendaftaran">Pengaturan Pendaftaran</a>
                        </li>
                        <li class="active"><?php echo $lang["edit"];?> Pengaturan Pendaftaran</li>
                    </ol>
              </section>

              <!-- Main content -->
              <section class="content">
              <div class="row">
                  <div class="col-lg-12">
                      <div class="box box-solid box-primary">
                          <div class="box-header">
                              <h3 class="box-title"><?php echo $lang["edit"];?> Pengaturan Pendaftaran</h3>
                              <div class="box-tools pull-right">
                                  <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-pencil"></i></button>
                              </div>
                          </div>
                      <div class="box-body">
                       <div class="alert alert-danger error_data" style="display:none">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <span class="isi_warning"></span>
                      </div>
                          <form id="edit_pengaturan_pendaftaran" method="post" class="form-horizontal" action="<?=base_admin();?>modul/pengaturan_pendaftaran/pengaturan_pendaftaran_action.php?act=up_dopeg">
                            <div class="form-group">
                        <label for="Nama Pendaftaran" class="control-label col-lg-2">Nama Pendaftaran </label>
                        <div class="col-lg-10">
              <select  id="id_jenis_pendaftaran" name="id_jenis_pendaftaran" data-placeholder="Pilih Nama Pendaftaran..." class="form-control chzn-select" tabindex="2" >
               <option value=""></option>
              <?php foreach ($db2->query("select * from tb_data_pendaftaran_jenis") as $isi) {

                  if ($data_edit->id_jenis_pendaftaran==$isi->id_jenis_pendaftaran) {
                    echo "<option value='$isi->id_jenis_pendaftaran' selected>$isi->nama_jenis_pendaftaran</option>";
                  } /*else {
                  echo "<option value='$isi->id_jenis_pendaftaran'>$isi->nama_jenis_pendaftaran</option>";
                    }*/
               } ?>
              </select>
          </div>
                      </div>
<div class="form-group">
              <label for="Untuk Program Studi" class="control-label col-lg-2">Ditujukan untuk</label>
                        <div class="col-lg-10">
              <select  id="diperuntukan" class="form-control chzn-select" name="diperuntukan" tabindex="2" required>
                <option value="">Pilih Tujuan</option>
               <option value="mahasiswa" <?=(uri_segment(2)=='mahasiswa')?'selected':'';?>>Mahasiswa</option>
               <option value="dopeg" <?=(uri_segment(2)=='dopeg')?'selected':'';?>>Dosen/Pegawai</option>
              </select>
          </div>

</div>
<div class="form-group">
<label for="Untuk Program Studi" class="control-label col-lg-2">Untuk Unit <span style="color:#FF0000">*</span></label>
<div class="col-lg-10">
<select  id="unit" name="unit[]" data-placeholder="Pilih UNIT ..." class="form-control chzn-select" tabindex="2" multiple="" required="">
 <option value="">Pilih UNIT Dosen/Pegawai</option>
               <?php 
$exp_unit = array();
$kode_jur = $db2->fetchCustomSingle("select group_concat(kode_jur) as kode_jur from tb_data_pendaftaran_jenis_pengaturan_prodi where id_jenis_pendaftaran_setting=?",array('id_jenis_pendaftaran_setting' => $data_edit->id_jenis_pendaftaran_setting));
$exp_unit = explode(",", $kode_jur->kode_jur);

               foreach ($db2->query("select kode_jur as kode_unit,nama_jurusan as nama_unit from view_prodi_jenjang union select kode_unit,nama_unit from tb_data_unit") as $isi) {
                  if (in_array($isi->kode_unit, $exp_unit)) {
                      echo "<option value='$isi->kode_unit' selected>$isi->nama_unit</option>";  
                  } else {
                    echo "<option value='$isi->kode_unit'>$isi->nama_unit</option>";
                  }
                  
                } 
                ?>
</select>
            </div>
                      </div><!-- /.form-group -->

          <?php
          $has_surat = 0;
          $pemaraf = "";
          $has_pemaraf = 0;
          $is_json = 0;
          $array_pemaraf = array();
          if ($data_edit->ada_template_surat=="Y") {
            $has_surat = 1;
            $pemaraf = json_decode($data_edit->penanda_tangan,true);
            if ($pemaraf) {
              $is_json = 1;
                $filteredArray = array_filter($pemaraf, function ($item) {
                    return isset($item['status']) && $item['status'] === 'pemaraf';
                });
              foreach ($pemaraf as $dt_pemaraf) {
                if ($dt_pemaraf['status']=='penanda_tangan') {
                  $penanda_tangan = $dt_pemaraf['id_jabatan_kat'];
                } else {
                  $array_pemaraf[$dt_pemaraf['pemaraf_ke']] = $dt_pemaraf['id_jabatan_kat'];
                }
              }
            }
            if (!empty($filteredArray)) {
              $has_pemaraf = 1;
            }
          }
          ?>
          <hr>
            <div class="form-group">
                <label for="Ada Judul" class="control-label col-lg-2">Ada Template Surat </label>
                <div class="col-lg-5">
                <?php
                if ($has_surat) {
                ?>
                  <input name="ada_template_surat" data-on-text="Ya" data-off-text="Tidak" class="make-switch" type="checkbox" checked id="ada_template_surat">
                  <span class="btn btn-primary btn-sm template-surat edit-template" data-jenis="surat" data-id="<?=$data_edit->id_jenis_pendaftaran_setting;?>"><i class="fa fa-envelope"></i> Edit Template Surat</span>
                <?php
              } else {
                ?>
                  <input name="ada_template_surat" data-on-text="Ya" data-off-text="Tidak" class="make-switch" type="checkbox" id="ada_template_surat">
                  <span class="btn btn-primary btn-sm template-surat edit-template" style="display:none" data-jenis="surat" data-id="<?=$data_edit->id_jenis_pendaftaran_setting;?>"><i class="fa fa-envelope"></i> Edit Template Surat</span>
                <?php
              }?>
                </div>
            </div><!-- /.form-group -->

             <?php
            $show_penanda_tangan = "";
            if ($data_edit->ada_template_surat=='N') {
              $show_penanda_tangan = "style='display:none'";
            }
            ?>
            <div class="form-group show-one-more" <?=$show_penanda_tangan;?>>
              <div class="control-label col-lg-2"></div>
              <div class="col-lg-5">
              <div class="radio radio-success radio-inline">
              <input type="radio" class="satu_penanda" name="satu_penanda" id="one" value="one" required="" <?=(!$has_pemaraf)?"checked":"";?>>
                <label for="one" style="padding-left: 5px;">
                  Satu Penanda Tangan
                </label>
            </div>
            <div class="radio radio-success radio-inline">
            <input type="radio" class="satu_penanda" name="satu_penanda" id="more" value="more" <?=($has_pemaraf)?"checked":"";?>>
              <label for="more" style="padding-left: 5px;">
                Lebih dari Satu Pemaraf/Penanda Tangan
              </label>
            </div>
              </div>
            </div>

  <div class="draggable-container has-pemaraf" <?=(!$has_pemaraf)?'style="display:none"':'';?>>
    <div id="sortable-container">

<div class="form-group">
<label for="Jumlah Pembimbing" class="control-label col-lg-2">Pilih Pemaraf</label>
<div class="col-lg-9 clone-div">
  <?php
  if ($has_pemaraf) {
    foreach ($array_pemaraf as $number => $value) {
      $show_hapus = 'display: none';
      if ($number>1) {
        $show_hapus = '';
      }
      ?>
    <div class="row row-clone-pemaraf">
        <div class="col-lg-5" style="padding-top:2px;">
        <div class="input-group">
        <span class="input-group-addon numbering drag-handle"><?=$number;?></span>
            <select name="pemaraf[]" data-placeholder="Pilih Validator/Penanda Tangan..." class="form-control pemaraf tgl_picker_input" tabindex="2">
               <?php 
               foreach ($db2->query("select * from tb_data_jabatan_kategori where id_jabatan_kat in(select id_jabatan_kat from tb_data_jabatan_pejabat) and is_editable='Y'") as $isi) {
                  if ($isi->id_jabatan_kat==$value) {
                    echo "<option value='$isi->id_jabatan_kat' selected>$isi->nama_kategori</option>";
                  } else {
                    echo "<option value='$isi->id_jabatan_kat'>$isi->nama_kategori</option>";   
                  } 
                }
                ?>
              </select>
        </div>
    </div>
    <div class="col-lg-1 show-hapus-pemaraf" style="padding-top:5px;<?=$show_hapus;?>" data-toggle="tooltip" data-title="Hapus Pemaraf">
      <span class="btn btn-danger btn-sm hapus-pemaraf"><i class="fa fa-trash"></i></span>
    </div>
    </div>
    <?php
    }
    ?>

    <?php
  } else {
    ?>
    <div class="row row-clone-pemaraf">
        <div class="col-lg-5" style="padding-top:2px;">
        <div class="input-group">
        <span class="input-group-addon numbering drag-handle">1</span>
            <select name="pemaraf[]" data-placeholder="Pilih Validator/Penanda Tangan..." class="form-control pemaraf" tabindex="2">
               <option value="">Pilih Validator/Penanda Tangan</option>
               <?php 
               foreach ($db2->query("select * from tb_data_jabatan_kategori where id_jabatan_kat in(select id_jabatan_kat from tb_data_jabatan_pejabat) and is_editable='Y'") as $isi) {
                    echo "<option value='$isi->id_jabatan_kat'>$isi->nama_kategori</option>";   
                  
                }
                ?>
              </select>
        </div>
    </div>
    <div class="col-lg-1 show-hapus-pemaraf" style="padding-top:5px;display: none;" data-toggle="tooltip" data-title="Hapus Pemaraf">
      <span class="btn btn-danger btn-sm hapus-pemaraf"><i class="fa fa-trash"></i></span>
    </div>
    </div>
    <?php
  }
  ?>

</div>
</div>
<div class="form-group">
<div class="control-label col-lg-2"></div>
<div class="col-lg-9">
<span class="btn btn-success btn-sm clone-btn"><i class="fa fa-plus"></i> Tambah Pemaraf</span>
</div>
</div>
</div>
</div>

          <div class="form-group show-penanda-tangan" <?=$show_penanda_tangan;?>>
                        <label for="Bukti" class="control-label col-lg-2">Pilih Penanda Tangan</label>
                        <div class="col-lg-3">
            <select id="penanda_tangan" name="penanda_tangan" data-placeholder="Pilih Validator/Penanda Tangan..." class="form-control" tabindex="2">
               <option value="">Pilih Validator/Penanda Tangan</option>
               <?php 
               foreach ($db2->query("select * from tb_data_jabatan_kategori where id_jabatan_kat in(select id_jabatan_kat from tb_data_jabatan_pejabat) and is_editable='Y'") as $isi) {
                  if ($isi->id_jabatan_kat==$penanda_tangan) {
                    echo "<option value='$isi->id_jabatan_kat' selected>$isi->nama_kategori</option>";
                  } else {
                    echo "<option value='$isi->id_jabatan_kat'>$isi->nama_kategori</option>";   
                  } 
                }
                ?>
                
              </select>
            </div>
                      </div><!-- /.form-group -->

            <div class="form-group" <?=($data_edit->ada_template_surat=='Y') ? '':'style="display:none"';?>>
                <label for="Ada Judul" class="control-label col-lg-2">Ada Kuesioner harus disi </label>
                <div class="col-lg-5">
                <?php
                $ada_kuesioner = 'N';
                if ($data_edit->ada_kuesioner=='Y') {
                  $ada_kuesioner = 'Y';
                ?>
                  <input name="ada_kuesioner" data-on-text="Ya" data-off-text="Tidak" class="make-switch" type="checkbox" checked id="ada_kuesioner">
                <?php
              } else {
                ?>
                  <input name="ada_kuesioner" data-on-text="Ya" data-off-text="Tidak" class="make-switch" type="checkbox" id="ada_kuesioner">
                <?php
              }?>
                </div>
            </div><!-- /.form-group -->
          <div class="form-group show-kuesioner" <?=($ada_kuesioner=='Y') ? '' : 'style="display:none"'?>>
                        <label for="Bukti" class="control-label col-lg-2">Pilih Jenis Kuesioner</label>
                        <div class="col-lg-6">
            <select  id="id_jenis_kuesioner" name="id_jenis_kuesioner[]" data-placeholder="Pilih Jenis Kuesioner ..." class="form-control chzn-select" tabindex="2" multiple="multiple">
              <option value=''></option>
              <?php
              $id_jenis_kuesioner = explode(",", $data_edit->id_jenis_kuesioner);
              foreach ($db2->query("select id,nama_survey from tb_survey_kat where id!='1'") as $isi) {
                if (in_array($isi->id, $id_jenis_kuesioner)) {
                    echo '<option value="'.$isi->id.'" selected>'.$isi->nama_survey.'</option>';
                } else {
                  echo '<option value="'.$isi->id.'">'.$isi->nama_survey.'</option>';
                }
              }
              
              ?>
              </select>
            </div>
                      </div><!-- /.form-group -->

 <hr>
          <div class="form-group">
                        <label for="Bukti" class="control-label col-lg-2">Bukti Upload</label>
                        <div class="col-lg-10">
              <select  id="bukti" name="bukti[]" data-placeholder="Pilih Bukti..." class="form-control chzn-select" tabindex="2" multiple="">
               <option value=""></option>
               <?php 
               $id_bukti_selected = explode(",", $data_edit->bukti);

               foreach ($db2->fetchAll("tb_data_pendaftaran_jenis_bukti") as $isi) {

                  if (in_array($isi->id_jenis_bukti, $id_bukti_selected)) {
                    echo "<option value='$isi->id_jenis_bukti' selected>$isi->jenis_bukti</option>";
                  } else {
                    echo "<option value='$isi->id_jenis_bukti'>$isi->jenis_bukti</option>";
                  }
               } ?>
              </select>
          </div>
                      </div><!-- /.form-group -->

          <div class="form-group">
              <label for="nama_bank" class="control-label col-lg-2">Keterangan Lain <span style="color:#FF0000">*</span></label>
              <div class="col-lg-10">
                 <textarea id="editbox" name="keterangan" class="editboxs"><?=$data_edit->keterangan;?> </textarea>
              </div>
          </div><!-- /.form-group -->
            <div class="form-group">
                <label for="Ada Penguji" class="control-label col-lg-2">Aktif</label>
                <div class="col-lg-10">
                <?php if ($data_edit->status_aktif=="Y") {
                ?>
                  <input name="status_aktif" data-on-text="Ya" data-off-text="Tidak" class="make-switch" type="checkbox" checked>
                <?php
              } else {
                ?>
                  <input name="status_aktif" data-on-text="Ya" data-off-text="Tidak" class="make-switch" type="checkbox">
                <?php
              }?>

                </div>
            </div><!-- /.form-group -->

<div class="form-group">
            <label for="Semester" class="control-label col-lg-2">Jenis Aktivitas (Dikti)</label>
              <div class="col-lg-9">
                <select id="id_jenis_aktivitas_dikti" name="id_jenis_aktivitas_dikti" data-placeholder="Pilih Jenis Atkvitias ..." class="form-control select2" >
                    <option value=""></option>
                 <?php
                 $jenis_akt = $db2->query("
                                        SELECT
                                            id_jns_akt_mhs, 
                                            nm_jns_akt_mhs,
                                            CASE 
                                                WHEN a_kegiatan_kampus_merdeka = 1 THEN '(Kampus Merdeka)'
                                                ELSE ''
                                            END AS a_kegiatan_kampus_merdeka 
                                        FROM tb_ref_dikti_jenis_akt_mhs
                                        ");
                 foreach ($jenis_akt as $jenis) {
                  if ($data_edit->id_jenis_akt_mhs==$jenis->id_jns_akt_mhs) { 
                    echo "<option value='$jenis->id_jns_akt_mhs' selected>$jenis->nm_jns_akt_mhs $jenis->a_kegiatan_kampus_merdeka</option>";
                  } else {
                    echo "<option value='$jenis->id_jns_akt_mhs'>$jenis->nm_jns_akt_mhs $jenis->a_kegiatan_kampus_merdeka</option>";
                  }
                 }
                 ?>
               </select>
             </div>
           </div> 

         <div class="form-group">
           <div class="col-lg-2" style="text-align: right">
             <span class="btn btn-success add-attr"><i class="fa fa-plus"></i> Add Form Field</span>
           </div>

           <div class="col-lg-8 show-select" style="display: none">
            <select class="form-control select-type">
             <option value="">Pilih Jenis Form</option>
             <option value="text">Simple Text</option>
             <option value="paragraph">Paragraph</option>
             <option value="number">Number</option>
             <option value="date">Date</option>
             <option value="dropdown">Dropdown</option>
             <option value="dropdown_dosen_tendik">Dropdown Dosen/Tendik</option>
             <option value="multiple_choice">Multiple Choice</option>
            </select>
           </div>
         </div><!-- /.form-group -->

         <div class="isi_embed">
          <?php
    $data_array_label = array(
      'attr_type' => 'Jenis Isian',
      'attr_name' => 'Nama Isian',
      'attr_label' => 'Label Isian',
      'required' => 'Wajb disi',
      'dropdown_data' => 'Data Pilihan',
      'multiple_choice_data' => 'Data Pilihan',
      'isi_admin' => 'Isi hanya oleh Admin'
    );

$data_json = array(
      'dropdown' =>
        array(
          "attr_type"=>"dropdown",
          "attr_name"=>"peran_peneliti",
          "attr_label"=>"Peran Penelitian/Penulisan",
          "required"=>"checked",
          "isi_admin" => "",
          "dropdown_data" => ""
        ),
      'dropdown_dosen_tendik' =>
        array(
          "attr_type"=>"dropdown_dosen_tendik",
          "attr_name"=>"dosen_tendik",
          "attr_label"=>"Panitian UTS",
          "required"=>"checked",
          "isi_admin" => ""
        ),
      'multiple_choice' =>
        array(
          "attr_type"=>"multiple_choice",
          "attr_name"=>"peran_peneliti",
          "attr_label"=>"Apakah Bumi Bulat atau Datar",
          "required"=>"checked",
          "isi_admin" => "",
          "multiple_choice_data" => ""
        ),
     'number' =>
        array(
          "attr_type"=>"number",
          "attr_name"=>"sks_mk",
          "attr_label"=>"SKS Matakuliah",
          "data-rule-number"=>"true",
          "isi_admin" => "",
          "required"=>"checked",
        ),
      'text' =>
      array(
        "attr_type"=>"text",
        "attr_name"=>"sks_mk",
        "attr_label"=>"SKS Matakuliah",
        "isi_admin" => "",
        "required"=>"checked"

      ),
      'date' =>
      array(
        "attr_type"=>"date",
        "attr_name"=>"tanggal_masuk",
        "attr_label"=>"Tanggal Masuk",
        "isi_admin" => "",
        "required"=>"checked"
      ),
      'paragraph' =>
      array(
        "attr_type"=>"paragraph",
        "attr_name"=>"sks_mk",
        "attr_label"=>"SKS Matakuliah",
        "isi_admin" => "",
        "required"=>"checked"

      ),
      'textareamce' =>
            array(
        "attr_type"=>"textareamce",
        "attr_name"=>"sks_mk",
        "attr_label"=>"SKS Matakuliah",
        "data-rule-minlength"=>"2",
        "data-msg-minlength"=>"At least two chars",
        "data-rule-maxlength"=>"4",
        "data-msg-maxlength"=>"At most fours chars",
        "isi_admin" => "",
        "required"=>"true",
        "data-msg-required"=>"Nama Wajib diisi"

      ),
      'image' =>
            array(
        "attr_type"=>"image",
        "attr_name"=>"sks_mk",
        "attr_label"=>"SKS Matakuliah",
        "required"=>"true",
        "data-msg-required"=>"Nama Wajib diisi",
        "isi_admin" => "",
        "allowed_type"=>"png|jpg|jpeg|gif|bmp"

      ),
      'file' =>
            array(
        "attr_type"=>"file",
        "attr_name"=>"sks_mk",
        "attr_label"=>"SKS Matakuliah",
        "isi_admin" => "",
        "required"=>"true",
        "data-msg-required"=>"Nama Wajib diisi",
        "allowed_type"=>"pdf|docx|xlsx"

      )
    );

  function get_type_name($arr) {
    $hasil = array();
    //$hasil[$arr['attr_type']] = $arr['attr_type'];
    $hasil[$arr['attr_name']] = $arr;
    return $hasil;
  }

  function get_edit_attr($arr) {
    $hasil = array();
    //$hasil[$arr['attr_type']] = $arr['attr_type'];
    $hasil[$arr['attr_name']] = $arr;
    return $hasil;
  }
          if ($data_edit->has_attr=='Y') {
            $attr = $data_edit->attr_value;
            $edit_attr = "";
            $readonly = "";
              $decode = json_decode($attr);
              $itterate = 1;



                $all_type = array_map('get_type_name', $db2->converObjToArray($decode));

                //echo "<pre>";


              foreach ($all_type as $key_parent => $type_attr) {
                 echo '<div class="group-json" style="border: 1px solid #3c8cbc;border-radius: 10px;margin-bottom: 10px;"><div class="form-group move-field"><div class="col-lg-10 text-center" style="padding:5px;cursor: move;"><i class="fa fa-align-justify"></i></div></div>';

                 foreach ($data_json[$type_attr[key($type_attr)]['attr_type']] as $key => $value) {

                        if (in_array($key, array_keys($type_attr[key($type_attr)]))) {
                          $value =  $type_attr[key($type_attr)][$key];
                        }
                        //$value =  $type_attr[key($type_attr)][$key];

                        if ($key=='attr_type') {
                        ?>
                            <div class="form-group">
                                <label for="Jumlah Pembimbing" class="control-label col-lg-3"><?=$data_array_label[$key];?></label>
                                <div class="col-lg-5">
                                  <input type="text" name="field[<?=$itterate;?>][<?=$key;?>]" value="<?=$value;?>" class="form-control" readonly>
                                </div>
                            </div><!-- /.form-group -->
                        <?php
                      } elseif($key=='attr_name') {
                        ?>
                           <input type="hidden" name="field[<?=$itterate;?>][<?=$key;?>]" value="<?=$value;?>" class="form-control">
                        <?php
                      } elseif ($key=='required') {
                        if ($value=='on') {
                          $value = "checked";
                        } else {
                          $value = "";
                        }
                        ?>
                            <div class="form-group">
                                <label for="Jumlah Pembimbing" class="control-label col-lg-3"><?=$data_array_label[$key];?></label>
                                <div class="col-lg-5">
                                  <input name="field[<?=$itterate;?>][<?=$key;?>]" data-on-text="Yes" data-off-text="No" class="make-switch" type="checkbox" <?=$value;?>>
                                </div>
                            </div><!-- /.form-group -->
                        <?php
                      } elseif ($key=='isi_admin') {
                        if ($value=='on') {
                          $value = "checked";
                        } else {
                          $value = "";
                        }
                        ?>
                            <div class="form-group">
                                <label for="Jumlah Pembimbing" class="control-label col-lg-3"><?=$data_array_label[$key];?></label>
                                <div class="col-lg-5">
                                  <input name="field[<?=$itterate;?>][<?=$key;?>]" data-on-text="Yes" data-off-text="No" class="make-switch" type="checkbox" <?=$value;?>>
                                </div>
                            </div><!-- /.form-group -->
                        <?php
                      } elseif ($key=='dropdown_data' || $key=='multiple_choice_data') {
                        ?>
                            <div class="form-group">
                                <label for="Jumlah Pembimbing" class="control-label col-lg-3"><?=$data_array_label[$key];?></label>
                                 <div class="col-lg-9">
                        <?php


                        if (count($value)>0) {
                          foreach ($value['value'] as $index_data => $val_data) {
                            if ($index_data==0) {
                              $show = "style='display: none'";
                            } else {
                              $show = "";
                            }
                          ?>
                                    <div class="row row-clone">
                                        <!-- <div class="col-lg-2" style="padding-top:2px">
                                            <input type="text" name="field[<?=$itterate;?>][<?=$key;?>][value][]" placeholder="Value" class="form-control value-data" value="<?=$val_data;?>"> </div> -->
                                        <div class="col-lg-10" style="padding-top:2px">
                                            <input type="text" name="field[<?=$itterate;?>][<?=$key;?>][value][]" placeholder="Option" class="form-control label-data" value="<?=$val_data;?>" required> </div>
                                       <div class="col-lg-2" style="padding-left:0;padding-top:2px"><span class="btn btn-success add-clone"><i class="fa fa-plus"></i></span> <span class="btn btn-danger remove-clone" <?=$show;?>><i class="fa fa-trash"></i></span></div>
                                    </div>
                          <?php
                          }
                        }
                        ?>
                         </div>
                            </div><!-- /.form-group -->
                          <?php
                      } else {
                        ?>
                            <div class="form-group">
                                <label for="Jumlah Pembimbing" class="control-label col-lg-3"><?=$data_array_label[$key];?></label>
                                <div class="col-lg-5">
                                  <input type="text" name="field[<?=$itterate;?>][<?=$key;?>]" value="<?=$value;?>" class="form-control">
                                </div>
                            </div><!-- /.form-group -->
                        <?php
                      }
                  }
                  echo '<div class="form-group"><div class="control-label col-lg-3"><span class="btn btn-danger hapus-group"><i class="fa fa-trash"></i></span></div></div><hr></div>';
                   $itterate++;
              }

          }
?>
           
         </div>
                            <input type="hidden" name="id" value="<?=$data_edit->id_jenis_pendaftaran_setting;?>">
                            <div class="form-group">
                                <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                                <div class="col-lg-10">
                                <a href="<?=base_index();?>pengaturan-pendaftaran" class="btn btn-default "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>
                                <button type="submit" class="btn btn-primary save-data"><i class="fa fa-save"></i> <?php echo $lang["submit_button"];?></button>
                                </div>
                            </div><!-- /.form-group -->
                          </form>
                      </div>
                  </div>
              </div>
              </section><!-- /.content -->
    <div class="modal" id="modal_pendaftaran" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg modal-abs"> <div class="modal-content"><div class="modal-header"> <h4 class="modal-title"><?php echo $lang["add_button"];?> Pendaftaran</h4> </div> <div class="modal-body" id="isi_pendaftaran"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
<script type="text/javascript">
$(document).ready(function () {
 $(".editboxs" ).ckeditor({
      toolbar: [{
          name: 'document',
          items: ['Undo', 'Redo']
        },
        {
          name: 'basicstyles',
          items: ['Bold', 'Italic', 'Strike', 'Underline','-', 'RemoveFormat']
        },
         {
          name: "paragraph",
          items: ["NumberedList", "BulletedList"]
        },
        {
          name: 'links',
          items: ['Link', 'Unlink', 'Anchor']
        }

      ],

 });

  $("#diperuntukan").change(function(){
    if (this.value=='mahasiswa') {
      document.location="<?=base_index();?>pengaturan-pendaftaran/edit/mahasiswa/<?=$data_edit->id_jenis_pendaftaran_setting;?>";
    } else {
      document.location="<?=base_index();?>pengaturan-pendaftaran/edit/dopeg/<?=$data_edit->id_jenis_pendaftaran_setting;?>";
    }

 $.ajax({
   url : url_content,
   type : "post",
   success : function(data) {
     $('.form-content').html(data);
   }
 });

  });

});
    $(document).ready(function () {
        // Initial setup based on the selected radio button
        toggleHasPemaraf();

        // Handle change event on radio buttons
        $('.satu_penanda').change(function () {
            toggleHasPemaraf();
        });

        // Function to toggle the visibility of .has-pemaraf based on the selected radio button
        function toggleHasPemaraf() {
            var selectedValue = $('input[name="satu_penanda"]:checked').val();
            if (selectedValue === 'one') {
                $('.has-pemaraf').hide();
                $('.pemaraf').prop('required',false);
            } else {
                $('.has-pemaraf').show();
                $('.pemaraf').prop('required',true);
            }
        }
    });

    $(document).ready(function () {
    $('.clone-btn').on('click', function () {
      var clonedRow = $('.row-clone-pemaraf:last').clone();
      // Increment the number beside input-group-addon
      var lastNumber = parseInt(clonedRow.find('.input-group-addon').text());
      clonedRow.find('.numbering').text(lastNumber + 1);
      clonedRow.find('.show-hapus-pemaraf').show();
      // Append the cloned row
      clonedRow.appendTo('.clone-div');
    });

    // Make rows sortable
    $('.clone-div').sortable({
      handle: '.drag-handle',
      update: function (event, ui) {
        // Rearrange the numbers beside input-group-addon
        $('.numbering').each(function (index) {
          $(this).text(index + 1);
        });
        $('.row-clone-pemaraf').find('.show-hapus-pemaraf').show();
        $('.row-clone-pemaraf:first').find('.show-hapus-pemaraf').hide();
      }
    });
    // Remove the row when hapus-pemaraf is clicked, but only if there is more than one row
    $(document).on('click', '.hapus-pemaraf', function () {
      if ($('.row-clone-pemaraf').length > 1) {
        $(this).closest('.row-clone-pemaraf').remove();
        // Rearrange the numbers beside input-group-addon
        $('.numbering').each(function (index) {
          $(this).text(index + 1);
        });
      }
    });
  });

    $(document).ready(function() {
    $(".edit-template").click(function(){
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);
        jenis = currentBtn.data("jenis");
        $(".modal-title").html("Edit Pendaftaran");
        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/pengaturan_pendaftaran/edit_template_surat_dopeg.php",
            type : "post",
            data : {id_data:id,jenis:jenis},
            success: function(data) {
                $("#isi_pendaftaran").html(data);
                $("#loadnya").hide();
          }
        });

    $('#modal_pendaftaran').modal({ keyboard: false,backdrop:'static' });

    });

$("#for_jurusan").chosen().change(function(e, params){
  var param_remove = '';
    if(params.deselected){ 
      $("#form_select_"+params.deselected).remove();
      var param_remove = params.deselected;
    }/*else{ 
      alert("selected: " + params.selected);
    }*/
    program = [];
    program[0] = params.selected;
    if ($(this).val()!== null && param_remove=='') {
      $.ajax({
      type : "post",
      url : "<?=base_admin();?>modul/pengaturan_pendaftaran/get_matkul_syarat.php",
      data : {program_studi:program},
      success : function(data) {
        //console.log(data);
          $("#isi_matkul_diambil").append(data);
          }
      });
    }/* else {
      $("#isi_matkul_diambil").html('');
    }*/
});

$('#ada_matkul_syarat').on('switchChange.bootstrapSwitch', function (event, state) {
   if (state==true) {
    if ($("#for_jurusan").val()!== null) {
      $.ajax({
      type : "post",
      url : "<?=base_admin();?>modul/pengaturan_pendaftaran/get_matkul_syarat.php",
      data : {program_studi:$("#for_jurusan").val()},
      success : function(data) {
            $("#matkul_diambil").show();
            $("#isi_matkul_diambil").html(data);
          }
      });
    } else {
      $("#matkul_diambil").show();
    }
   } else {
    $("#isi_matkul_diambil").html('');
    $("#matkul_diambil").hide();
   }
});

/*   $(".group-json").sortable({
           cancel: ".primary,select,input,checkbox",
           revert: 100,
           placeholder: "dashed-placeholder"
        });*/

    $( ".isi_embed" ).sortable({
      connectWith: ".isi_embed",
      handle: ".move-field",
         revert : 100,
      cancel: ".primary,select,input,checkbox",
      //placeholder: "portlet-placeholder ui-corner-all"
    });


$(document).on('click','.hapus-group',function() {
  $(this).parent().parent().parent().remove();
});


$("#for_jurusan").change(function(){
  if (this.value=='all') {
    $('#for_jurusan option').prop('selected', true);
    $("#for_jurusan option[value='all']").prop("selected", false);
    $("#for_jurusan").trigger("chosen:updated");
  }
});

$('.add-attr').click(function(){
    $('.show-select').toggle();
});

$('.select-type').change(function(){
tipe = this.value;
if (this.value!='') {
 $('.show-select').hide();
 $('.select-type option:first').prop('selected',true);
 itterate = $('.group-json').length;
 $.ajax({
 url : "<?=base_admin();?>modul/pengaturan_pendaftaran/get_form.php",
   type : "post",
   data : {tipe:tipe,itterate:itterate},
   success : function(data) {
     $('.isi_embed').append(data);
      $.each($(".make-switch"), function () {
            $(this).bootstrapSwitch({
            onText: $(this).data("onText"),
            offText: $(this).data("offText"),
            onColor: $(this).data("onColor"),
            offColor: $(this).data("offColor"),
            size: $(this).data("size"),
            labelText: $(this).data("labelText")
            });
          });

   }
 });
}
});

        $(document).on('click','.add-clone',function() {
            var cloned = $(this).parent().parent().last().clone().insertAfter( $(this).parent().parent());

            //var cloned = $(".row-clone:last").clone(true);
            cloned.find('.value-data').val('');
            cloned.find('.label-data').val('');
             //cloned.find('.btn-info').removeClass('btn-info').addClass('btn-danger');
             //cloned.find('.fa-plus').removeClass('fa-plus').addClass('fa-minus');
            cloned.find('.remove-clone').show();

/*           var $newdiv = $(".row-clone:last").clone(true);
            $newdiv.find('input').each(function() {
                var $this = $(this);
               $this.attr('id', $this.attr('id').replace(/_(\d+)_/, function($0, $1) {
                    return '_' + (+$1 + 1) + '_';
                }));
                $this.attr('name', $this.attr('name').replace(/\[(\d+)\]/, function($0, $1) {
                    return '[' + (+$1 + 1) + ']';
                }));
                $this.val('');
            });
            $newdiv.insertAfter('.row-clone:last');*/

        });
        $(document).on('click','.remove-clone',function() {
            var jml_element = $('.row-clone').length;
            if (jml_element>1) {
              $(this).parent().parent().remove();
            }
        });

$('#ada_pembimbing').on('switchChange.bootstrapSwitch', function (event, state) {
   if (state==true) {
    $('.show-pembimbing').show();
    $('.show-isi-pembimbing').show();
    $('.jumlah_pembimbing').prop('required',true);
   } else {
    $('.show-pembimbing').hide();
    $('.show-isi-pembimbing').hide();
    $('.jumlah_pembimbing').prop('required',false);
   }
}); 
$('#ada_penguji').on('switchChange.bootstrapSwitch', function (event, state) {
   if (state==true) {
    $('.show-penguji').show();
    $('.jumlah_penguji').prop('required',true);
   } else {
    $('.show-penguji').hide();
    $('.jumlah_penguji').prop('required',false);
   }
});
$('#ada_sks_ditempuh').on('switchChange.bootstrapSwitch', function (event, state) {
   if (state==true) {
    $('.show-sks').show();
    $('.jumlah_sks_ditempuh').prop('required',true);
   } else {
    $('.show-sks').hide();
    $('.jumlah_sks_ditempuh').prop('required',false);
   }
});
$('#ada_kuesioner').on('switchChange.bootstrapSwitch', function (event, state) {
   if (state==true) {
    $('.show-kuesioner').show();
    $('.id_jenis_kuesioner').prop('required',true);
   } else {
$('.show-kuesioner').hide();
    $('.id_jenis_kuesioner').prop('required',false);
   }
});
$('#ada_template_surat').on('switchChange.bootstrapSwitch', function (event, state) {
   if (state==true) {
    $('.template-surat').show();
    $('.show-pemaraf').show();
    $('.show-one-more').show();
    $('.show-penanda-tangan').show();
    $('#penanda_tangan').prop('required',true);
   } else {
    $('.template-surat').hide();
    $('.show-pemaraf').hide();
    $('.show-one-more').hide();
    $('.show-penanda-tangan').hide();
    $('#penanda_tangan').prop('required',false);
   }
});

$('#ada_sk_pembimbing').on('switchChange.bootstrapSwitch', function (event, state) {
   if (state==true) {
    $('.template-surat-sk-pembimbing').show();
   } else {
    $('.template-surat-sk-pembimbing').hide();
   }
});

$('#ada_sk_penguji').on('switchChange.bootstrapSwitch', function (event, state) {
   if (state==true) {
    $('.template-surat-sk-penguji').show();
   } else {
    $('.template-surat-sk-penguji').hide();
   }
});

$('#harus_lulus_toafl').on('switchChange.bootstrapSwitch', function (event, state) {
   if (state==true) {
    $('.show-nilai-toafl').show();
    $('.min_nilai_toafl').prop('required',true);
   } else {
    $('.show-nilai-toafl').hide();
    $('.min_nilai_toafl').prop('required',false);
   }
});
$('#harus_lulus_toefa').on('switchChange.bootstrapSwitch', function (event, state) {
   if (state==true) {
    $('.show-nilai-toefa').show();
    $('.min_nilai_toefa').prop('required',true);
   } else {
    $('.show-nilai-toefa').hide();
    $('.min_nilai_toefa').prop('required',false);
   }
});
    
                    $("#fakultas").change(function(){
                        $.ajax({
                        type : "post",
                        url : "<?=base_admin();?>modul/pengaturan_pendaftaran/get_prodi.php",
                        data : {id_fakultas:this.value},
                        success : function(data) {
                            $("#for_jurusan").html(data);
                            $("#for_jurusan").trigger("chosen:updated");

                            }
                        });
                    });

      $.each($(".make-switch"), function () {
            $(this).bootstrapSwitch({
            onText: $(this).data("onText"),
            offText: $(this).data("offText"),
            onColor: $(this).data("onColor"),
            offColor: $(this).data("offColor"),
            size: $(this).data("size"),
            labelText: $(this).data("labelText")
            });
          });
        
    
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
        
    
    $("#edit_pengaturan_pendaftaran").validate({
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
            } else if (element.attr("accept") == "image/*") {
                element.parent().parent().parent().append(error);
            } else if (element.hasClass("waktu")) {
               element.parent().parent().append(error);
            }
            else if (element.hasClass("tgl_picker_input")) {
               element.parent().parent().append(error);
            }
            else if (element.hasClass("select2")) {
               element.parent().append(error);
            }
            else if (element.hasClass("file-upload-data")) {
               element.parent().parent().parent().append(error);
            }  else if (element.hasClass("dosen-ke")) {
                  error.appendTo('.error-dosen');
            }
            else if (element.attr("type") == "checkbox") {
                element.parent().parent().append(error);
            } else if (element.attr("type") == "radio") {
                element.parent().parent().append(error);
            } else {
                error.insertAfter(element);
            }
        },

        

        submitHandler: function(form) {
            $("#loadnya").show();
            $(form).ajaxSubmit({
                url : $(this).attr("action"),
                dataType: "json",
                type : "post",
                error: function(data ) { 
                  $("#loadnya").hide();
                  console.log(data); 
                  $(".isi_warning").html(data.responseText);
                  $(".error_data").focus()
                  $(".error_data").fadeIn();
                },
                success: function(responseText) {
                  $("#loadnya").hide();
                  console.log(responseText);
                      $.each(responseText, function(index) {
                          console.log(responseText[index].status);
                          if (responseText[index].status=="die") {
                            $("#informasi").modal("show");
                          } else if(responseText[index].status=="error") {
                             $(".isi_warning").text(responseText[index].error_message);
                             $(".error_data").focus()
                             $(".error_data").fadeIn();
                          } else if(responseText[index].status=="good") {
                            $(".save-data").attr("disabled", "disabled");
                            $(".error_data").hide();
                            $(".notif_top_up").fadeIn(1000);
                            $(".notif_top_up").fadeOut(1000, function() {
                                    window.location="<?=base_index();?>pengaturan-pendaftaran"
                            });
                          }
                    });
                }

            });
        }
    });
});
</script>
