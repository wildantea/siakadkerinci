<?php

    $type = $_POST['tipe'];
    $itterate = $_POST['itterate']+1;
    /*$type = 'number';
    $itterate = 6;*/

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
          "attr_name"=>"bentuk_bumi",
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
        "attr_label"=>"Siapa Nama Anda",
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
        "attr_label"=>"Dimana Alamat Anda",
        "isi_admin" => "",
        "required"=>"checked"

      ),
      'textareamce' =>
    		    array(
        "attr_type"=>"textareamce",
        "attr_name"=>"sks_mk",
        "attr_label"=>"SKS Matakuliah",
        "isi_admin" => "",
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
        "isi_admin" => "",
        "required"=>"true",
        "data-msg-required"=>"Nama Wajib diisi",
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

    echo '<div class="group-json" style="border: 1px solid #3c8cbc;border-radius: 10px;margin-bottom: 10px;"><div class="form-group move-field"><div class="col-lg-10 text-center" style="padding:5px;cursor: move;"><i class="fa fa-align-justify"></i></div></div>';
    foreach ($data_json[$type] as $key => $value) {
    			if ($key=='attr_type') {
    			?>
              <div class="form-group">
                  <label for="Jumlah Pembimbing" class="control-label col-lg-3"><?=$data_array_label[$key];?></label>
                  <div class="col-lg-5">
                    <input type="text" name="field[<?=$itterate;?>][<?=$key;?>]" value="<?=$value;?>" class="form-control" readonly>
                  </div>
              </div><!-- /.form-group -->
    			<?php
    		} elseif ($key=='attr_label') {
          ?>
              <div class="form-group">
                  <label for="Jumlah Pembimbing" class="control-label col-lg-3"><?=$data_array_label[$key];?></label>
                  <div class="col-lg-5">
                    <input type="text" name="field[<?=$itterate;?>][<?=$key;?>]" value="<?=$value;?>" class="form-control">
                  </div>
              </div><!-- /.form-group -->
          <?php
        } elseif ($key=='required') {
    			?>
              <div class="form-group">
                  <label for="Jumlah Pembimbing" class="control-label col-lg-3"><?=$data_array_label[$key];?></label>
                  <div class="col-lg-5">
                    <input name="field[<?=$itterate;?>][<?=$key;?>]" data-on-text="Ya" data-off-text="Tidak" class="make-switch" type="checkbox" <?=$value;?>>
                  </div>
              </div><!-- /.form-group -->
    			<?php
    		} elseif ($key=='isi_admin') {
          ?>
              <div class="form-group">
                  <label for="Jumlah Pembimbing" class="control-label col-lg-3"><?=$data_array_label[$key];?></label>
                  <div class="col-lg-5">
                    <input name="field[<?=$itterate;?>][<?=$key;?>]" data-on-text="Ya" data-off-text="Tidak" class="make-switch" type="checkbox">
                  </div>
              </div><!-- /.form-group -->
          <?php
        } elseif ($key=='dropdown_data' || $key=='multiple_choice_data') {
            ?>
              <div class="form-group">
                  <label for="Jumlah Pembimbing" class="control-label col-lg-3"><?=$data_array_label[$key];?></label>
                  <div class="col-lg-9">
                      <div class="row row-clone">
                          <!-- <div class="col-lg-2" style="padding-top:2px">
                              <input type="text" name="field[<?=$itterate;?>][<?=$key;?>][value][]" placeholder="Value" class="form-control value-data"> </div> -->
                          <div class="col-lg-10" style="padding-top:2px">
                              <input type="text" name="field[<?=$itterate;?>][<?=$key;?>][value][]" placeholder="Opsi" class="form-control label-data" required> </div>
                         <div class="col-lg-2" style="padding-left:0;padding-top:2px"><span class="btn btn-success add-clone"><i class="fa fa-plus"></i></span> <span class="btn btn-danger remove-clone" style="display: none"><i class="fa fa-trash"></i></span></div>
                      </div>
                  </div>
              </div><!-- /.form-group -->
            <?php
        } elseif($key=='attr_name') {
          ?>
             <input type="hidden" name="field[<?=$itterate;?>][<?=$key;?>]" value="<?=$value;?>" class="form-control">
          <?php
        } elseif($key=='data-rule-number') {
          ?>
              <div class="form-group" style="display:none" style="display:none">
                  <label for="Jumlah Pembimbing" class="control-label col-lg-3"><?=$key;?></label>
                  <div class="col-lg-5">
                    <input type="text" name="field[<?=$itterate;?>][<?=$key;?>]" value="<?=$value;?>" class="form-control">
                  </div>
              </div><!-- /.form-group -->
          <?php
        } else {
          if (in_array($key, array_keys($data_array_label))) {
                            $key_label = $data_array_label[$key];
                          } else {
                            $key_label = $key;
                          }
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
    echo '<div class="form-group"><div class="control-label col-lg-3"><span class="btn btn-danger hapus-group"><i class="fa fa-trash"></i></span></div></div></div>';

