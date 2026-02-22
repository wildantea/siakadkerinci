
<div class="box-body table-responsive">
  <button id="cetak_krs" onclick="cetak_krs(<?= $ku->krs_id.",".$ku->mhs_nim ?>)" class="btn btn-primary" style="float:right"><i class="fa fa-print"></i>&nbsp;Cetak KRS</button>
                  <form method="POST" id="input_rencana_studi" action="<?= base_admin() ?>modul/rencana_studi/rencana_studi_action.php?act=add_krs">     
                                        <input type="submit" id="btn-simpan" class="btn btn-primary " value="<?= $btn_simpan ?>">
                                      <table  class="table table-bordered table-striped" style="position:relative;top:8px">
                      
                                        <thead>
                                          <tr>
                                            <th style="width:20px" align="center"></th>
                                            <th>Kode MK</th>
                                            <th style="text-align:center">Mata Kuliah</th>
                                            
                                            <th style="text-align:center">Semester</th>
                                            <th style="text-align:center">SKS</th>   
                                            <th style="text-align:center">Kelas</th>
                                            <th style="text-align:center">Keterangan</th>  
                                          </tr>
                                        </thead>
                                        <tbody>
                                          
                                <?php
                             
                              
                                $dtb=$db->query("select kr.kur_id, m.semester from kelas k join matkul m on k.id_matkul=m.id_matkul
                          join kurikulum kr on kr.kur_id=m.kur_id
                          where m.jenis_semester='".substr($ku->sem_id, 4,5)."' and kr.kode_jur='".$ku->kode_jur."' 
                          and k.sem_id='".$ku->sem_id."'
                          group by m.semester order by m.semester asc");
                                $i=1;
                                foreach ($dtb as $isi) {
                                  ?>
                                <tr id="line_<?=$isi->krs_id;?>">
                               
                                    
                                   
                                    <td colspan="8" style="background:#00a65a;color:#fff">SEMESTER <?=$isi->semester;?></td>
                                  
                                   

                                </tr>
                                  <?php
                                    $dtb2=$db->query("select m.*,k.kls_nama from kelas k join matkul m on k.id_matkul=m.id_matkul
                                                    join kurikulum ku on ku.kur_id=m.kur_id
                                                     where k.sem_id='".$ku->sem_id."' and ku.kode_jur='".$ku->kode_jur."' 
                                                     and m.semester='".$isi->semester."'  group by k.id_matkul order by m.semester asc, m.nama_mk asc ");
                         
                                    foreach ($dtb2 as $k) {
                                      $cek_prasyarat = cek_prasyarat($k->id_matkul,$ku->mhs_nim);
                                     ?>
                                     
                                          <?php
                                          //echo "$ku->mhs_nim, $ku->sem, $k->id_matkul";
                                          if (cek_sudah_ambil($ku->mhs_nim,$ku->sem,$k->id_matkul)['result']==1
                                            && cek_sudah_ambil($ku->mhs_nim,$ku->sem,$k->id_matkul)['nilai']!='' ) {
                                           ?>
                                           <tr style="background:#ff8f00">
                                            <td align="center"><input type="checkbox" id="krs-<?= $k->id_matkul ?>" name="krs-<?= $k->id_matkul ?>" value="<?= $k->id_matkul ?>" disabled></td>
                                           <?php

                                           $ket= "Sudah diambil dan sudah ada nilai untuk matkul ini";
                                           $disabled_select = "disabled";
                                           $show_kelas_full=true;
                                          }
                                          elseif (cek_sudah_ambil($ku->mhs_nim,$ku->sem,$k->id_matkul)['result']==1 && cek_sudah_ambil($ku->mhs_nim,$ku->sem,$k->id_matkul)['nilai']=='') {
                                             ?>
                                           <tr>
                                            <td align="center"><input type="checkbox"  id="krs-<?= $k->id_matkul ?>" name="krs-<?= $k->id_matkul ?>" onchange="ambil_krs(this,<?= $ku->sem.",".$k->id_matkul.",".$k->sks_tm.",".$ku->krs_id ?>)"   value="<?= $k->id_matkul ?>" checked></td>
                                           <?php
                                           $ket= "Sudah diambil tapi belum ada nilai";
                                            $disabled_select = "";
                                            $show_kelas_full=true;
                                          }
                                          elseif ($cek_prasyarat!="0") {
                                              ?>
                                           <tr style="background:#a91f1f;color:#fff">
                                            <td align="center"><input type="checkbox" id="krs-<?= $k->id_matkul ?>" name="krs-<?= $k->id_matkul ?>" value="<?= $k->id_matkul ?>" disabled></td>
                                             <?php
                                             $ket= $cek_prasyarat;
                                             $disabled_select = "disabled";
                                             $show_kelas_full=true;
                                          }

                                          else{
                                            ?>
                                           <tr>
                                             <td align="center"><input type="checkbox" name="krs-<?= $k->id_matkul ?>" id="krs-<?= $k->id_matkul ?>" onchange="ambil_krs(this,<?= $ku->sem.",".$k->id_matkul.",".$k->sks_tm.",".$ku->krs_id ?>)" value="<?= $k->id_matkul ?>"></td>
                                            <?php
                                            $ket = "Available";
                                            $disabled_select = "";
                                            $show_kelas_full=false;
                                          }
                                          ?>
                                              <td><?=$k->kode_mk." ".$k->id_matkul;?></td>
                                              <td><?=$k->nama_mk;?></td>
                                              <input type="hidden" name="matkul-<?= $k->id_matkul ?>" value="<?= $k->nama_mk ?>">
                                             
                                              <td><?=$k->semester;?></td>
                                              <td><?=$k->sks_tm;?></td>
                                              <th>
                                              <?php
                                                  if ($cek_prasyarat=="0") {
                                                    ?>
                                                    <select style="width:100px" <?= $disabled_select ?> name="kelas-<?= $k->id_matkul ?>" id="kelas-<?= $k->id_matkul ?>">
                                                      <option value="">-Pilih Kelas-</option>
                                                        <?php
                                                              $qq = $db->query("select k.kelas_id,k.kls_nama from kelas k where 
                                                              k.id_matkul='$k->id_matkul' and k.sem_id='$ku->sem_id'");
                                                             foreach ($qq as $kk) {
                                                              if ($show_kelas_full==false) {
                                                                if (cek_kelas_penuh($kk->kelas_id)==false || cek_sudah_ambil2($ku->mhs_nim,$ku->sem,$k->id_matkul,$kk->kelas_id)['kelas']==$kk->kelas_id ) {
                                                                  if (cek_sudah_ambil2($ku->mhs_nim,$ku->sem,$k->id_matkul,$kk->kelas_id)['result']==1 ) {
                                                                    echo "<option value='$kk->kelas_id===$kk->kls_nama' selected>$kk->kls_nama</option>";
                                                                  }else{
                                                                     echo "<option value='$kk->kelas_id===$kk->kls_nama'>$kk->kls_nama</option>";
                                                                  }
                                                                }
                                                              }else{
                                                                 if (cek_sudah_ambil2($ku->mhs_nim,$ku->sem,$k->id_matkul,$kk->kelas_id)['result']==1 ) {
                                                                    echo "<option value='$kk->kelas_id===$kk->kls_nama' selected>$kk->kls_nama</option>";
                                                                  }else{
                                                                     echo "<option value='$kk->kelas_id===$kk->kls_nama'>$kk->kls_nama</option>";
                                                                  }
                                                              }
                                                         } 
                                                         
                                                        ?>
                                                      </select>
                                                <?php 
                                                  } 
                                                ?>
                                              </th>
                                              <td><?= $ket; ?></td>
                                             

                                          </tr>
                                         
                                     <?php
                                    }
                                 $i++;
                                }
                                ?>
                                   <tr>
                                     <td colspan="2">   <input type="submit" id="btn-simpan" class="btn btn-primary " value="<?= $btn_simpan ?>"></td>
                                   </tr>
                              
                                        </tbody>
                                        <input type="hidden" name="krs_id" value="<?= $ku->krs_id; ?>"> 
                                         <input type="hidden" name="kode_jur" value="<?= $ku->kode_jur; ?>"> 
                                         <input type="hidden" name="semester" value="<?= $ku->sem_id; ?>"> 
                                         <input type="hidden" name="jns_semester" value="<?= substr($ku->sem_id, 4,5); ?>"> 
                                         </form>
                                      </table>
                                      </div><!-- /.box-body -->
                                        <div id="info" class="alert alert-warning alert-info" style="position:fixed;bottom:5px;right:5px;font-size:20px" role="alert">
                                          Jatah SKS : <?= $ku->jatah_sks; ?> <br>
                                          Sisa Jatah SKS   : <span id="diambil_info" style="font-size:20px">  <?= $ku->jatah_sks - $ku->tot_sks ?> </span>
                                        </div>
                                      <div class="alert alert-danger" style="position:fixed;bottom:5px;right:5px;display:none" role="alert" id="warning">
                                        <strong>Warning</strong> Jumlah SKS yang diambil melebihi jatah, silahkan uncheck beberapa mata kuliah
                                      </div>

<script type="text/javascript">
  function cetak_krs (krs_id,nim) {
    document.location="<?= base_admin() ?>modul/rencana_studi/cetak_krs.php?k="+krs_id+"&nim="+nim;
  /*   $("#loadnya").show();
             $.ajax({
                type: "POST",
                url: "<?= base_admin() ?>modul/rencana_studi/cetak_krs.php",
                data: "krs_id="+krs_id,
                success: function(data) {
                    $("#loadnya").hide();
                    $(".modal-body").html(data);
                   $('#myModal').modal('toggle');
                    $('#myModal').modal('show');
                }
            });*/
  }
</script>