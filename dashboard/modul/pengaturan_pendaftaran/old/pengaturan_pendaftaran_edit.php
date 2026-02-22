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
                          <form id="edit_pengaturan_pendaftaran" method="post" class="form-horizontal" action="<?=base_admin();?>modul/pengaturan_pendaftaran/pengaturan_pendaftaran_action.php?act=up">
                            <div class="form-group">
                        <label for="Nama Pendaftaran" class="control-label col-lg-2">Nama Pendaftaran </label>
                        <div class="col-lg-10">
              <select  id="id_jenis_pendaftaran" name="id_jenis_pendaftaran" data-placeholder="Pilih Nama Pendaftaran..." class="form-control chzn-select" tabindex="2" >
               <option value=""></option>
               <?php foreach ($db2->fetchAll("tb_data_pendaftaran_jenis") as $isi) {

                  if ($data_edit->id_jenis_pendaftaran==$isi->id_jenis_pendaftaran) {
                    echo "<option value='$isi->id_jenis_pendaftaran' selected>$isi->nama_jenis_pendaftaran</option>";
                  } /*else {
                  echo "<option value='$isi->id_jenis_pendaftaran'>$isi->nama_jenis_pendaftaran</option>";
                    }*/
               } ?>
              </select>
          </div>
                      </div><!-- /.form-group -->
<!-- <div class="form-group">
                        <label for="Untuk Program Studi" class="control-label col-lg-2">Fakultas </label>
                        <div class="col-lg-10">
              <select  id="fakultas" class="form-control chzn-select" tabindex="2">
               <option value="">Fakultas</option>
               <?php
               $fak = $db2->query("select * from tb_master_fakultas");
                foreach ($fak as $isi) {
                    echo "<option value='$isi->id_fakultas'>$isi->nama_fakultas</option>";
               } ?>
              </select>
          </div>
                      </div>
                    -->
<div class="form-group">
                        <label for="Untuk Program Studi" class="control-label col-lg-2">Untuk Program Studi </label>
                        <div class="col-lg-10">
              <select  id="for_jurusan" name="for_jurusan[]" data-placeholder="Pilih Untuk Program Studi..." class="form-control chzn-select" tabindex="2" required="" multiple="">
               <?php
               //selected prody
               $kode_jur = $db2->fetchCustomSingle("select group_concat(kode_jur) as kode_jur from tb_data_pendaftaran_jenis_pengaturan_prodi where id_jenis_pendaftaran_setting=?",array('id_jenis_pendaftaran_setting' => $data_edit->id_jenis_pendaftaran_setting));
               loopingProdiForm($kode_jur->kode_jur);
                ?>
              </select>
          </div>
                      </div><!-- /.form-group -->

          <div class="form-group">
              <label for="Ada Jadwal" class="control-label col-lg-2">Ada Matakuliah harus/sedang di IRS </label>
              <div class="col-lg-10">
                <?php if ($data_edit->ada_matkul_syarat=="Y") {
                  $show_mat = "";
                ?>
                  <input name="ada_matkul_syarat" id="ada_matkul_syarat" data-on-text="Ya" data-off-text="Tidak" class="make-switch" type="checkbox" checked>
                <?php
              } else {
                 $show_mat = 'style="display:none"';
                ?>
                  <input name="ada_matkul_syarat" id="ada_matkul_syarat" data-on-text="Ya" data-off-text="Tidak" class="make-switch" type="checkbox">
                <?php
              }?>
              </div>
          </div><!-- /.form-group -->

          <div id="matkul_diambil" <?=$show_mat;?>>
          <hr>
                    <div class="form-group">
                      <div class="col-lg-2">&nbsp;</div>
                       <div class="col-lg-10">
          <div class="callout callout-info" style="margin-bottom: 5px;">

                          <p>Ketika mahasiswa mendaftar pendaftaran ini, maka harus dalam poisi sedang mengambil matakuliah / Krs di Aplikasi Salam.</p>
                        </div>
                      </div>
                    </div><!-- /.form-group -->
            <div id="isi_matkul_diambil">
<?php
if ($data_edit->ada_matkul_syarat=="Y") {

foreach ($db2->query("select kode_jur,jurusan as nama_jurusan,matkul_syarat from view_prodi_jenjang inner join tb_data_pendaftaran_jenis_pengaturan_prodi using(kode_jur) where id_jenis_pendaftaran_setting=?",array('id_jenis_pendaftaran_setting' => $data_edit->id_jenis_pendaftaran_setting)) as $jurusan) {

?>
          <div class="form-group" id="form_select_<?=$jurusan->kode_jur;?>">
              <label for="Ada Jadwal" class="control-label col-lg-3"><?=$jurusan->nama_jurusan;?></label>
              <div class="col-lg-9">
             <select  id="select_matkul_<?=$jurusan->kode_jur;?>" name="syarat_matkul[<?=$jurusan->kode_jur;?>][]" data-placeholder="Pilih Matakuliah ..." class="form-control" tabindex="2" required="" multiple>
<?php
/*$mat = $db2->fetchCustomSingle("select id_matkul,nama_mk,kode_mk from matkul where id_matkul=?",array('id_matkul' => $jurusan->matkul_syarat));
  echo "<option value='$mat->id_matkul'>$mat->kode_mk $mat->nama_mk</option>";
*/
$mat = $db2->query("select id_matkul,kode_mk,nama_mk,nama_kurikulum from matkul inner join kurikulum using(kur_id) where id_matkul in($jurusan->matkul_syarat)");
foreach ($mat as $mat_selected) {
    echo "<option value='$mat_selected->id_matkul' selected>kurikulum $mat_selected->nama_kurikulum - $mat_selected->kode_mk $mat_selected->nama_mk</option>";
}
?>
              </select>
            </div>
          </div><!-- /.form-group -->
<script type="text/javascript">
$(document).ready(function () {
  $("#select_matkul_<?=$jurusan->kode_jur;?>").select2({
    ajax: {
      url: '<?=base_admin();?>modul/pengaturan_pendaftaran/get_matkul.php',
      dataType: 'json',
      type : 'post',
      data: function (params) {
        var query = {
          q: params.term,
          kode_jur: '<?=$jurusan->kode_jur;?>'
        }

        // Query parameters will be ?search=[term]&type=public
        return query;
      }
      // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
    },
    formatInputTooShort: "Pilih Matakuliah",
    //allowClear: true,
    width: "100%",
  });

});
</script>
<?php
}

}
?>
            </div>
        </div>

            <div class="form-group">
                <label for="Ada Jadwal" class="control-label col-lg-2">Ada Jadwal Ujian</label>
                <div class="col-lg-10">
                <?php if ($data_edit->ada_jadwal=="Y") {
                ?>
                  <input name="ada_jadwal" data-on-text="Ya" data-off-text="Tidak" class="make-switch" type="checkbox" checked>
                <?php
              } else {
                ?>
                  <input name="ada_jadwal" data-on-text="Ya" data-off-text="Tidak" class="make-switch" type="checkbox">
                <?php
              }?>

                </div>
            </div><!-- /.form-group -->

            
            <div class="form-group">
                <label for="Ada Judul" class="control-label col-lg-2">Ada Judul </label>
                <div class="col-lg-10">
                <?php
                if ($data_edit->ada_judul=="Y") {
                ?>
                  <input name="ada_judul" data-on-text="Ya" data-off-text="Tidak" class="make-switch" type="checkbox" checked>
                <?php
              } else {
                ?>
                  <input name="ada_judul" data-on-text="Ya" data-off-text="Tidak" class="make-switch" type="checkbox">
                <?php
              }?>

                </div>
            </div><!-- /.form-group -->
            <hr>
            
            <div class="form-group">
                <label for="Ada Pembimbing" class="control-label col-lg-2">Ada Pembimbing </label>
                <div class="col-lg-10">
                <?php if ($data_edit->ada_pembimbing=="Y") {
                ?>
                  <input name="ada_pembimbing" data-on-text="Ya" data-off-text="Tidak" class="make-switch" type="checkbox" checked id="ada_pembimbing">
                <?php
              } else {
                ?>
                  <input name="ada_pembimbing" data-on-text="Ya" data-off-text="Tidak" class="make-switch" type="checkbox" id="ada_pembimbing">
                <?php
              }?>

                </div>
            </div><!-- /.form-group -->
            <?php
            $show_pembimbing = "";
            if ($data_edit->ada_pembimbing=='N') {
              $show_pembimbing = "style='display:none'";
            }
            ?>
          <div class="form-group show-pembimbing" <?=$show_pembimbing;?>>
              <label for="Jumlah Pembimbing" class="control-label col-lg-2">Jumlah Pembimbing </label>
              <div class="col-lg-1">
                <input type="text" data-rule-number="true" name="jumlah_pembimbing" value="<?=$data_edit->jumlah_pembimbing;?>" class="form-control" >
              </div>
          </div><!-- /.form-group -->
          
            <div class="form-group">
                <label for="Ada Penguji" class="control-label col-lg-2">Ada Penguji </label>
                <div class="col-lg-10">
                <?php if ($data_edit->ada_penguji=="Y") {
                ?>
                  <input name="ada_penguji" data-on-text="Ya" data-off-text="Tidak" class="make-switch" type="checkbox" checked id="ada_penguji">
                <?php
              } else {
                ?>
                  <input name="ada_penguji" data-on-text="Ya" data-off-text="Tidak" class="make-switch" type="checkbox" id="ada_penguji">
                <?php
              }?>

                </div>
            </div><!-- /.form-group -->

            <?php
            $show_penguji = "";
            if ($data_edit->ada_penguji=='N') {
              $show_penguji = "style='display:none'";
            }
            ?>

          <div class="form-group show-penguji" <?=$show_penguji;?>>
              <label for="Jumlah Penguji" class="control-label col-lg-2">Jumlah Penguji </label>
              <div class="col-lg-1">
                <input type="text" data-rule-number="true" name="jumlah_penguji" value="<?=$data_edit->jumlah_penguji;?>" class="form-control" >
              </div>
          </div><!-- /.form-group -->
           <hr>
            <div class="form-group">
                <label for="Ada Penguji" class="control-label col-lg-2">Ada Min SKS ditempuh </label>
                <div class="col-lg-1">
                <?php if ($data_edit->ada_sks_ditempuh=="Y") {
                ?>
                  <input name="ada_sks_ditempuh" data-on-text="Ya" data-off-text="Tidak" class="make-switch" type="checkbox" checked id="ada_sks_ditempuh">
                <?php
              } else {
                ?>
                  <input name="ada_sks_ditempuh" data-on-text="Ya" data-off-text="Tidak" class="make-switch" type="checkbox" id="ada_sks_ditempuh">
                <?php
              }?>

                </div>
            </div><!-- /.form-group -->

            <?php
            $show_sks = "";
            if ($data_edit->ada_sks_ditempuh=='N') {
              $show_sks = "style='display:none'";
            }
            ?>


          <div class="form-group show-sks" <?=$show_sks;?>>
              <label for="Jumlah Penguji" class="control-label col-lg-2">Min SKS ditempuh </label>
              <div class="col-lg-1">
                <input type="text" data-rule-number="true" name="jumlah_sks_ditempuh" value="<?=$data_edit->jumlah_sks_ditempuh;?>" class="form-control jumlah_sks_ditempuh" >
              </div>
          </div><!-- /.form-group -->

            <div class="form-group">
                <label for="Ada Penguji" class="control-label col-lg-2">Harus Lunas SPP</label>
                <div class="col-lg-10">
                <?php if ($data_edit->harus_lunas_spp=="Y") {
                ?>
                  <input name="harus_lunas_spp" data-on-text="Ya" data-off-text="Tidak" class="make-switch" type="checkbox" checked>
                <?php
              } else {
                ?>
                  <input name="harus_lunas_spp" data-on-text="Ya" data-off-text="Tidak" class="make-switch" type="checkbox">
                <?php
              }?>

                </div>
            </div><!-- /.form-group -->
                   <div class="form-group">
                        <label for="Bukti" class="control-label col-lg-2">Harus Lulus/Selesai Pendaftaran</label>
                        <div class="col-lg-10">
              <select  id="syarat_daftar" name="syarat_daftar[]" data-placeholder="Pilih Pendaftaran Syarat..." class="form-control chzn-select" tabindex="2" multiple="">
               <option value=""></option>
               <?php 
               $id_syarat_selected = explode(",", $data_edit->syarat_daftar);
               foreach ($db2->fetchAll("tb_data_pendaftaran_jenis") as $isi) {
                  if (in_array($isi->id_jenis_pendaftaran, $id_syarat_selected)) {
                    echo "<option value='$isi->id_jenis_pendaftaran' selected>$isi->nama_jenis_pendaftaran</option>";
                  } else {
                    if ($data_edit->id_jenis_pendaftaran!=$isi->id_jenis_pendaftaran) {
                      echo "<option value='$isi->id_jenis_pendaftaran'>$isi->nama_jenis_pendaftaran</option>";
                    }
                  }

               } ?>
              </select>
          </div>
                      </div><!-- /.form-group -->

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
                 <textarea id="editbox" name="keterangan" class="editbox"><?=$data_edit->keterangan;?> </textarea>
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
             <option value="multiple_choice">Multiple Choice</option>
            </select>
           </div>
         </div><!-- /.form-group -->

         <div class="isi_embed">
          <?php
$data_json = array(
      'dropdown' =>
        array(
          "attr_type"=>"dropdown",
          "attr_name"=>"peran_peneliti",
          "attr_label"=>"Peran Penelitian/Penulisan",
          "required"=>"checked",
          "dropdown_data" => ""
        ),
      'multiple_choice' =>
        array(
          "attr_type"=>"multiple_choice",
          "attr_name"=>"peran_peneliti",
          "attr_label"=>"Apakah Bumi Bulat atau Datar",
          "required"=>"checked",
          "multiple_choice_data" => ""
        ),
     'number' =>
        array(
          "attr_type"=>"number",
          "attr_name"=>"sks_mk",
          "attr_label"=>"SKS Matakuliah",
          "data-rule-number"=>"true",
          "required"=>"checked",
        ),
      'text' =>
      array(
        "attr_type"=>"text",
        "attr_name"=>"sks_mk",
        "attr_label"=>"SKS Matakuliah",
        "required"=>"checked"

      ),
      'date' =>
      array(
        "attr_type"=>"date",
        "attr_name"=>"tanggal_masuk",
        "attr_label"=>"Tanggal Masuk",
        "required"=>"checked"
      ),
      'paragraph' =>
      array(
        "attr_type"=>"paragraph",
        "attr_name"=>"sks_mk",
        "attr_label"=>"SKS Matakuliah",
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
        "allowed_type"=>"png|jpg|jpeg|gif|bmp"

      ),
      'file' =>
            array(
        "attr_type"=>"file",
        "attr_name"=>"sks_mk",
        "attr_label"=>"SKS Matakuliah",
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
                                <label for="Jumlah Pembimbing" class="control-label col-lg-3"><?=$key;?></label>
                                <div class="col-lg-5">
                                  <input type="text" name="field[<?=$itterate;?>][<?=$key;?>]" value="<?=$value;?>" class="form-control" readonly>
                                </div>
                            </div><!-- /.form-group -->
                        <?php
                      } elseif ($key=='required') {
                        if ($value=='on') {
                          $value = "checked";
                        } else {
                          $value = "";
                        }
                        ?>
                            <div class="form-group">
                                <label for="Jumlah Pembimbing" class="control-label col-lg-3"><?=$key;?></label>
                                <div class="col-lg-5">
                                  <input name="field[<?=$itterate;?>][<?=$key;?>]" data-on-text="Yes" data-off-text="No" class="make-switch" type="checkbox" <?=$value;?>>
                                </div>
                            </div><!-- /.form-group -->
                        <?php
                      } elseif ($key=='dropdown_data' || $key=='multiple_choice_data') {
                        ?>
                            <div class="form-group">
                                <label for="Jumlah Pembimbing" class="control-label col-lg-3"><?=$key;?></label>
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
                                <label for="Jumlah Pembimbing" class="control-label col-lg-3"><?=$key;?></label>
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
                                <a href="<?=base_index();?>pengaturan-pendaftaran" class="btn btn-default "><i class="fa fa-step-backward"></i> <?php echo $lang["cancel_button"];?></a>
                                <button type="submit" class="btn btn-primary save-data"><i class="fa fa-save"></i> <?php echo $lang["submit_button"];?></button>
                                </div>
                            </div><!-- /.form-group -->
                          </form>
                      </div>
                  </div>
              </div>
              </section><!-- /.content -->

<script type="text/javascript">
    $(document).ready(function() {


$("#for_jurusan").chosen().change(function(e, params){
  var param_remove = '';
    if(params.deselected){ 
      $("#form_select_"+params.deselected).remove();
      var param_remove = params.deselected;
    }/*else{ 
      alert("selected: " + params.selected);
    }*/

    if($('#ada_matkul_syarat').bootstrapSwitch('state') == true) {
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
      }
    }

/* else {
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
    $('.jumlah_pembimbing').prop('required',true);
   } else {
    $('.show-pembimbing').hide();
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
$('#ada_sks_ditempuh').on('switchChange.bootstrapSwitch', function (event, state) {
   if (state==true) {
    $('.show-sks').show();
    $('.jumlah_sks_ditempuh').prop('required',true);
   } else {
    $('.show-sks').hide();
    $('.jumlah_sks_ditempuh').prop('required',false);
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
                                    window.history.back();
                            });
                          }
                    });
                }

            });
        }
    });
});
</script>
