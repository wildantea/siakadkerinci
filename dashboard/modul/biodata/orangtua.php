                                      
                                           <?php
                                        if(!empty(checkBiodataMahasiswaOrtu($_SESSION['username']))) {
                                        ?>
                                         <div id="errorAlert" class="alert alert-danger alert-dismissible fade in alert-container" >
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <h4><i class="icon fa fa-ban"></i> Mohon lengkapi kolom-kolom berikut!</h4>
                                        <ul id="errorList">
                                            <?php
                                            foreach (checkBiodataMahasiswaOrtu($_SESSION['username']) as $error) {
                                               echo "<li>".$error."</li>";
                                            }
                                            ?>
                                            </ul>
                                              </div>
                                        <?php
                                    }
                                    ?>
                              
                                       <div class="callout callout-info" style="font-size: 20px; margin: 0; padding: 7px;">Data Ayah</div>
                                        <br>
                                        <div class="form-group">
                                            <label for="NIK Ayah" class="control-label col-lg-2">NIK Ayah</label>
                                            <div class="col-lg-10">
                                                <input type="text" name="nik_ayah" value="<?=$data_edit->nik_ayah;?>" class="form-control" minlength="16" maxlength="16" onkeypress="return isNumberKey(event)">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Nama Ayah" class="control-label col-lg-2">Nama Ayah</label>
                                            <div class="col-lg-10">
                                                <input type="text" name="nm_ayah" value="<?=$data_edit->nm_ayah;?>" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Tanggal Lahir" class="control-label col-lg-2">Tanggal Lahir Ayah</label>
                                            <div class="col-lg-10">
                                                <div class="row">
                                                    <div class="col-xs-4">
                                                        <select id="tgl_lahir_tanggal_ayah" name="tgl_lahir_tanggal_ayah" class="form-control lahir tgl_lahir_tanggal chzn-select" placeholder="Tanggal">
                                                            <option value="">Tanggal</option>
                                                            <?php
                                                            $tgl_lahir = substr($data_edit->tgl_lahir_ayah, 8, 2);
                                                            for ($i = 1; $i <= 31; $i++) {
                                                                $val = $i < 10 ? "0$i" : $i;
                                                                echo "<option value='$val'" . ($val == $tgl_lahir ? ' selected' : '') . ">$val</option>";
                                                            } ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-xs-4">
                                                        <select id="tgl_lahir_bulan_ayah" name="tgl_lahir_bulan_ayah" class="form-control lahir tgl_lahir_bulan chzn-select">
                                                            <option value="">Bulan</option>
                                                            <?php
                                                            $bulan_lahir = substr($data_edit->tgl_lahir_ayah, 5, 2);
                                                            for ($i = 1; $i <= 12; $i++) {
                                                                $val = $i < 10 ? "0$i" : $i;
                                                                echo "<option value='$val'" . ($val == $bulan_lahir ? ' selected' : '') . ">" . getBulan($val) . "</option>";
                                                            } ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-xs-4">
                                                        <select id="tgl_lahir_tahun_ayah" name="tgl_lahir_tahun_ayah" class="form-control lahir tgl_lahir_tahun chzn-select">
                                                            <option value="">Tahun</option>
                                                            <?php
                                                            $minimum_age = date("Y", strtotime("-100 year", time()));
                                                            $maximum_age = date("Y", strtotime("-15 year", time()));
                                                            $tahun_lahir = substr($data_edit->tgl_lahir_ayah, 0, 4);
                                                            for ($i = $maximum_age; $i >= $minimum_age; $i--) {
                                                                echo "<option value='$i'" . ($i == $tahun_lahir ? ' selected' : '') . ">$i</option>";
                                                            } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="Pendidikan Ayah" class="control-label col-lg-2">Pendidikan Ayah</label>
                                            <div class="col-lg-10">
                                                <select name="id_jenjang_pendidikan_ayah" data-placeholder="Pilih Pendidikan Ayah..." class="form-control chzn-select" tabindex="2">
                                                    <option value=""></option>
                                                    <?php foreach ($db->fetch_all("jenjang_pendidikan") as $isi) {
                                                        echo "<option value='$isi->id_jenjang'" . ($data_edit->id_jenjang_pendidikan_ayah == $isi->id_jenjang ? ' selected' : '') . ">$isi->jenjang</option>";
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Pekerjaan Ayah" class="control-label col-lg-2">Pekerjaan Ayah</label>
                                            <div class="col-lg-10">
                                                <select name="id_pekerjaan_ayah" data-placeholder="Pilih Pekerjaan Ayah..." class="form-control chzn-select" tabindex="2">
                                                    <option value=""></option>
                                                    <?php foreach ($db->fetch_all("pekerjaan") as $isi) {
                                                        echo "<option value='$isi->id_pekerjaan'" . ($data_edit->id_pekerjaan_ayah == $isi->id_pekerjaan ? ' selected' : '') . ">$isi->pekerjaan</option>";
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Penghasilan Ayah" class="control-label col-lg-2">Penghasilan Ayah</label>
                                            <div class="col-lg-10">
                                                <select name="id_penghasilan_ayah" data-placeholder="Pilih Penghasilan Ayah..." class="form-control chzn-select" tabindex="2">
                                                    <option value=""></option>
                                                    <?php foreach ($db->fetch_all("penghasilan") as $isi) {
                                                        echo "<option value='$isi->id_penghasilan'" . ($data_edit->id_penghasilan_ayah == $isi->id_penghasilan ? ' selected' : '') . ">$isi->penghasilan</option>";
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                         <div class="callout callout-info" style="font-size: 20px; margin: 0; padding: 7px;">Data Ibu</div>
                                        <br>
                                        <div class="form-group">
                                            <label for="NIK Ibu" class="control-label col-lg-2">NIK Ibu</label>
                                            <div class="col-lg-10">
                                                <input type="text" name="nik_ibu_kandung" value="<?=$data_edit->nik_ibu_kandung;?>" class="form-control" minlength="16" maxlength="16" onkeypress="return isNumberKey(event)">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Nama Ibu" class="control-label col-lg-2">Nama Ibu <span style="color:#FF0000">*</span></label>
                                            <div class="col-lg-10">
                                                <input type="text" name="nm_ibu_kandung" value="<?=$data_edit->nm_ibu_kandung;?>" class="form-control" required>
                                            </div>
                                        </div>
                                         <div class="form-group">
                                            <label for="Tanggal Lahir" class="control-label col-lg-2">Tanggal Lahir Ibu</label>
                                            <div class="col-lg-10">
                                                <div class="row">
                                                    <div class="col-xs-4">
                                                        <select id="tgl_lahir_tanggal_ibu" name="tgl_lahir_tanggal_ibu" class="form-control lahir tgl_lahir_tanggal chzn-select" placeholder="Tanggal">
                                                            <option value="">Tanggal</option>
                                                            <?php
                                                            $tgl_lahir = substr($data_edit->tgl_lahir_ibu, 8, 2);
                                                            for ($i = 1; $i <= 31; $i++) {
                                                                $val = $i < 10 ? "0$i" : $i;
                                                                echo "<option value='$val'" . ($val == $tgl_lahir ? ' selected' : '') . ">$val</option>";
                                                            } ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-xs-4">
                                                        <select id="tgl_lahir_bulan_ibu" name="tgl_lahir_bulan_ibu" class="form-control lahir tgl_lahir_bulan chzn-select">
                                                            <option value="">Bulan</option>
                                                            <?php
                                                            $bulan_lahir = substr($data_edit->tgl_lahir_ibu, 5, 2);
                                                            for ($i = 1; $i <= 12; $i++) {
                                                                $val = $i < 10 ? "0$i" : $i;
                                                                echo "<option value='$val'" . ($val == $bulan_lahir ? ' selected' : '') . ">" . getBulan($val) . "</option>";
                                                            } ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-xs-4">
                                                        <select id="tgl_lahir_tahun_ibu" name="tgl_lahir_tahun_ibu" class="form-control lahir tgl_lahir_tahun chzn-select">
                                                            <option value="">Tahun</option>
                                                            <?php
                                                            $minimum_age = date("Y", strtotime("-100 year", time()));
                                                            $maximum_age = date("Y", strtotime("-15 year", time()));
                                                            $tahun_lahir = substr($data_edit->tgl_lahir_ibu, 0, 4);
                                                            for ($i = $maximum_age; $i >= $minimum_age; $i--) {
                                                                echo "<option value='$i'" . ($i == $tahun_lahir ? ' selected' : '') . ">$i</option>";
                                                            } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="Pendidikan Ibu" class="control-label col-lg-2">Pendidikan Ibu</label>
                                            <div class="col-lg-10">
                                                <select name="id_jenjang_pendidikan_ibu" data-placeholder="Pilih Pendidikan Ibu..." class="form-control chzn-select" tabindex="2">
                                                    <option value=""></option>
                                                    <?php foreach ($db->fetch_all("jenjang_pendidikan") as $isi) {
                                                        echo "<option value='$isi->id_jenjang'" . ($data_edit->id_jenjang_pendidikan_ibu == $isi->id_jenjang ? ' selected' : '') . ">$isi->jenjang</option>";
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Pekerjaan Ibu" class="control-label col-lg-2">Pekerjaan Ibu</label>
                                            <div class="col-lg-10">
                                                <select name="id_pekerjaan_ibu" data-placeholder="Pilih Pekerjaan Ibu..." class="form-control chzn-select" tabindex="2">
                                                    <option value=""></option>
                                                    <?php foreach ($db->fetch_all("pekerjaan") as $isi) {
                                                        echo "<option value='$isi->id_pekerjaan'" . ($data_edit->id_pekerjaan_ibu == $isi->id_pekerjaan ? ' selected' : '') . ">$isi->pekerjaan</option>";
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Penghasilan Ibu" class="control-label col-lg-2">Penghasilan Ibu</label>
                                            <div class="col-lg-10">
                                                <select name="id_penghasilan_ibu" data-placeholder="Pilih Penghasilan Ibu..." class="form-control chzn-select" tabindex="2">
                                                    <option value=""></option>
                                                    <?php foreach ($db->fetch_all("penghasilan") as $isi) {
                                                        echo "<option value='$isi->id_penghasilan'" . ($data_edit->id_penghasilan_ibu == $isi->id_penghasilan ? ' selected' : '') . ">$isi->penghasilan</option>";
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <input type="hidden" name="act" value="orangtua">
                                         <div class="form-group">
                                    <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                                    <div class="col-lg-10">
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                                       </a>
                                    </div>
                                </div>
                                        <div class="callout callout-info">
                                        <h4>Orang Tua Wali</h4>
                                        <p>Jika anda punya orang tua wali, silakan diisi. Jika tidak ada, boleh di kosongkan</p>
                                    </div>
                                        <br>
                                        <div class="form-group">
                                            <label for="Nama Wali" class="control-label col-lg-2">Nama Wali</label>
                                            <div class="col-lg-10">
                                                <input type="text" name="nm_wali" value="<?=$data_edit->nm_wali;?>" class="form-control">
                                            </div>
                                        </div>
                                         <div class="form-group">
                                            <label for="Tanggal Lahir" class="control-label col-lg-2">Tanggal Lahir Wali</label>
                                            <div class="col-lg-10">
                                                <div class="row">
                                                    <div class="col-xs-4">
                                                        <select id="tgl_lahir_tanggal" name="tgl_lahir_tanggal" class="form-control lahir tgl_lahir_tanggal chzn-select" placeholder="Tanggal">
                                                            <option value="">Tanggal</option>
                                                            <?php
                                                            $tgl_lahir = substr($data_edit->tgl_lahir_wali, 8, 2);
                                                            for ($i = 1; $i <= 31; $i++) {
                                                                $val = $i < 10 ? "0$i" : $i;
                                                                echo "<option value='$val'" . ($val == $tgl_lahir ? ' selected' : '') . ">$val</option>";
                                                            } ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-xs-4">
                                                        <select id="tgl_lahir_bulan" name="tgl_lahir_bulan" class="form-control lahir tgl_lahir_bulan chzn-select">
                                                            <option value="">Bulan</option>
                                                            <?php
                                                            $bulan_lahir = substr($data_edit->tgl_lahir_wali, 5, 2);
                                                            for ($i = 1; $i <= 12; $i++) {
                                                                $val = $i < 10 ? "0$i" : $i;
                                                                echo "<option value='$val'" . ($val == $bulan_lahir ? ' selected' : '') . ">" . getBulan($val) . "</option>";
                                                            } ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-xs-4">
                                                        <select id="tgl_lahir_tahun" name="tgl_lahir_tahun" class="form-control lahir tgl_lahir_tahun chzn-select">
                                                            <option value="">Tahun</option>
                                                            <?php
                                                            $minimum_age = date("Y", strtotime("-100 year", time()));
                                                            $maximum_age = date("Y", strtotime("-15 year", time()));
                                                            $tahun_lahir = substr($data_edit->tgl_lahir_wali, 0, 4);
                                                            for ($i = $maximum_age; $i >= $minimum_age; $i--) {
                                                                echo "<option value='$i'" . ($i == $tahun_lahir ? ' selected' : '') . ">$i</option>";
                                                            } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Jenjang Pendidikan Wali" class="control-label col-lg-2">Jenjang Pendidikan Wali</label>
                                            <div class="col-lg-10">
                                                <select name="id_jenjang_pendidikan_wali" data-placeholder="Pilih Jenjang Pendidikan Wali..." class="form-control chzn-select" tabindex="2">
                                                    <option value=""></option>
                                                    <?php foreach ($db->fetch_all("jenjang_pendidikan") as $isi) {
                                                        echo "<option value='$isi->id_jenjang'" . ($data_edit->id_jenjang_pendidikan_wali == $isi->id_jenjang ? ' selected' : '') . ">$isi->jenjang</option>";
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Pekerjaan Wali" class="control-label col-lg-2">Pekerjaan Wali</label>
                                            <div class="col-lg-10">
                                                <select name="id_pekerjaan_wali" data-placeholder="Pilih Pekerjaan Wali..." class="form-control chzn-select" tabindex="2">
                                                    <option value=""></option>
                                                    <?php foreach ($db->fetch_all("pekerjaan") as $isi) {
                                                        echo "<option value='$isi->id_pekerjaan'" . ($data_edit->id_pekerjaan_wali == $isi->id_pekerjaan ? ' selected' : '') . ">$isi->pekerjaan</option>";
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Penghasilan Wali" class="control-label col-lg-2">Penghasilan Wali</label>
                                            <div class="col-lg-10">
                                                <select name="id_penghasilan_wali" data-placeholder="Pilih Penghasilan Wali..." class="form-control chzn-select" tabindex="2">
                                                    <option value=""></option>
                                                    <?php foreach ($db->fetch_all("penghasilan") as $isi) {
                                                        echo "<option value='$isi->id_penghasilan'" . ($data_edit->id_penghasilan_wali == $isi->id_penghasilan ? ' selected' : '') . ">$isi->penghasilan</option>";
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                         <input type="hidden" name="act" value="orangtua">
                                         <div class="form-group">
                                    <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                                    <div class="col-lg-10">
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                                       </a>
                                    </div>
                                </div>