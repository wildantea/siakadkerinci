
                                      <?php

                                         if(!empty(checkBiodataMahasiswaAlamat($_SESSION['username']))) {
                                        ?>
                                         <div id="errorAlert" class="alert alert-danger alert-dismissible fade in alert-container" >
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <h4><i class="icon fa fa-ban"></i> Mohon lengkapi kolom-kolom berikut!</h4>
                                        <ul id="errorList">
                                            <?php
                                            foreach (checkBiodataMahasiswaAlamat($_SESSION['username']) as $error) {
                                               echo "<li>".$error."</li>";
                                            }
                                            ?>
                                            </ul>
                                               </div>
                                        <?php
                                    }
                                    ?>
                                  
                                     <div class="form-group">
                                            <label for="Jalan" class="control-label col-lg-2">Alamat Jalan <span style="color:#FF0000">*</span></label>
                                            <div class="col-lg-10">
                                                <input type="text" name="jln" value="<?=$data_edit->jln;?>" class="form-control" required maxlength="80">
                                            </div>
                                        </div>
                                            <div class="form-group">
                                            <label for="Dusun" class="control-label col-lg-2">Dusun/Kampung</label>
                                            <div class="col-lg-10">
                                                <input type="text" name="nm_dsn" value="<?=$data_edit->nm_dsn;?>" class="form-control" maxlength="60">
                                            </div>
                                        </div>
                                           <div class="form-group">
                                            <label for="RT" class="control-label col-lg-2">RT <span style="color:#FF0000">*</span></label>
                                            <div class="col-lg-10">
                                                <input type="text" name="rt" value="<?=$data_edit->rt;?>" class="form-control" required data-rule-number="true" maxlength="2" onkeypress="return isNumberKey(event)">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="RW" class="control-label col-lg-2">RW <span style="color:#FF0000">*</span></label>
                                            <div class="col-lg-10">
                                                <input type="text" name="rw" value="<?=$data_edit->rw;?>" class="form-control" required maxlength="2" onkeypress="return isNumberKey(event)">
                                            </div>
                                        </div>
                                    
                                        <div class="form-group">
                                            <label for="Kelurahan" class="control-label col-lg-2">Desa/Kelurahan <span style="color:#FF0000">*</span></label>
                                            <div class="col-lg-10">
                                                <input type="text" name="ds_kel" value="<?=$data_edit->ds_kel;?>" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Kecamatan" class="control-label col-lg-2">Kecamatan <span style="color:#FF0000">*</span></label>
                                            <div class="col-lg-10">
                                                <select id="kecamatan" name="id_wil" data-placeholder="Pilih Kecamatan..." class="form-control data_wil" tabindex="2" required>
                                                    <option value=""></option>
                                                    <?php
                                                    if ($data_edit->id_wil == '999999') {
                                                        echo "<option value='$data_edit->id_wil' selected>Tidak ada</option>";
                                                    } else {
                                                        foreach ($db->query("
                                                            SELECT dwc.id_wil, CONCAT(dwc.nm_wil, ' - ', dw.nm_wil, ' - ', data_wilayah.nm_wil) AS wil 
                                                            FROM data_wilayah
                                                            LEFT JOIN data_wilayah dw ON data_wilayah.id_wil = dw.id_induk_wilayah
                                                            LEFT JOIN data_wilayah dwc ON dw.id_wil = dwc.id_induk_wilayah
                                                            WHERE data_wilayah.id_level_wil = '1' AND dwc.id_wil='$data_edit->id_wil'
                                                            UNION ALL
                                                            SELECT dw.id_wil, CONCAT(dw.nm_wil, ' - ', data_wilayah.nm_wil) AS wil 
                                                            FROM data_wilayah
                                                            LEFT JOIN data_wilayah dw ON data_wilayah.id_wil = dw.id_induk_wilayah
                                                            WHERE data_wilayah.id_level_wil = '1' AND dw.id_wil='$data_edit->id_wil'
                                                        ") as $isi) {
                                                            echo "<option value='$isi->id_wil'" . ($data_edit->id_wil == $isi->id_wil ? ' selected' : '') . ">$isi->wil</option>";
                                                        }
                                                    } ?>
                                                </select>
                                                <p class="help-block" style="color:#1dc0ef">Jika Kecamatan tidak ditemukan, Cukup isi dengan Nama Kabupaten</p>
                                            </div>
                                        </div>
                                        
                                     

                                        <div class="form-group">
                                            <label for="Kodepos" class="control-label col-lg-2">Kode Pos <span style="color:#FF0000">*</span></label>
                                            <div class="col-lg-10">
                                                <input type="text" name="kode_pos" value="<?=$data_edit->kode_pos;?>" class="form-control" required maxlength="5" onkeypress="return isNumberKey(event)">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Jenis Tinggal" class="control-label col-lg-2">Jenis Tinggal <span style="color:#FF0000">*</span></label>
                                            <div class="col-lg-10">
                                                <select name="id_jns_tinggal" data-placeholder="Pilih Jenis Tinggal..." class="form-control chzn-select" tabindex="2" required>
                                                    <option value=""></option>
                                                    <?php foreach ($db->fetch_all("jenis_tinggal") as $isi) {
                                                        echo "<option value='$isi->id_jns_tinggal'" . ($data_edit->id_jns_tinggal == $isi->id_jns_tinggal ? ' selected' : '') . ">$isi->jenis_tinggal</option>";
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                           <div class="form-group">
                                            <label for="No Handphone" class="control-label col-lg-2">No Handphone <span style="color:#FF0000">*</span></label>
                                            <div class="col-lg-10">
                                                <input type="text" name="no_hp" value="<?=$data_edit->no_hp;?>" class="form-control" required onkeypress="return isNumberKey(event)">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="No Telepon Rumah" class="control-label col-lg-2">No Telepon Rumah</label>
                                            <div class="col-lg-10">
                                                <input type="text" name="no_tel_rmh" value="<?=$data_edit->no_tel_rmh;?>" class="form-control" onkeypress="return isNumberKey(event)" minlength="9">
                                            </div>
                                        </div>
                                     
                                        <div class="form-group">
                                            <label for="Email" class="control-label col-lg-2">Email <span style="color:#FF0000">*</span></label>
                                            <div class="col-lg-10">
                                                <input type="text" data-rule-email="true" name="email" value="<?=$data_edit->email;?>" class="form-control" required>
                                            </div>
                                        </div>
                                         <input type="hidden" name="act" value="alamat">
                                         <div class="form-group">
                                    <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                                    <div class="col-lg-10">
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                                       </a>
                                    </div>
                                </div>
                                    