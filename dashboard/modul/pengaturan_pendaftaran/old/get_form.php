<?php

    $type = $_POST['tipe'];
    $itterate = $_POST['itterate']+1;
    /*$type = 'number';
    $itterate = 6;*/

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
          "attr_name"=>"bentuk_bumi",
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

    echo '<div class="group-json" style="border: 1px solid #3c8cbc;border-radius: 10px;margin-bottom: 10px;"><div class="form-group move-field"><div class="col-lg-10 text-center" style="padding:5px;cursor: move;"><i class="fa fa-align-justify"></i></div></div>';
    foreach ($data_json[$type] as $key => $value) {
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
                      <div class="row row-clone">
                          <!-- <div class="col-lg-2" style="padding-top:2px">
                              <input type="text" name="field[<?=$itterate;?>][<?=$key;?>][value][]" placeholder="Value" class="form-control value-data"> </div> -->
                          <div class="col-lg-10" style="padding-top:2px">
                              <input type="text" name="field[<?=$itterate;?>][<?=$key;?>][value][]" placeholder="Option" class="form-control label-data" required> </div>
                         <div class="col-lg-2" style="padding-left:0;padding-top:2px"><span class="btn btn-success add-clone"><i class="fa fa-plus"></i></span> <span class="btn btn-danger remove-clone" style="display: none"><i class="fa fa-trash"></i></span></div>
                      </div>
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
    echo '<div class="form-group"><div class="control-label col-lg-3"><span class="btn btn-danger hapus-group"><i class="fa fa-trash"></i></span></div></div></div>';

