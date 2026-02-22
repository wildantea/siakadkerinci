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
                                                        <select id="tgl_lahir_tanggal_wali" name="tgl_lahir_tanggal" class="form-control lahir tgl_lahir_tanggal chzn-select" placeholder="Tanggal">
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
                                                        <select id="tgl_lahir_bulan_wali" name="tgl_lahir_bulan" class="form-control lahir tgl_lahir_bulan chzn-select">
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
                                                        <select id="tgl_lahir_tahun_wali" name="tgl_lahir_tahun" class="form-control lahir tgl_lahir_tahun chzn-select">
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
                                         <input type="hidden" name="act" value="wali">
                                         <div class="form-group">
                                    <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                                    <div class="col-lg-10">
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                                       </a>
                                    </div>
                                </div>