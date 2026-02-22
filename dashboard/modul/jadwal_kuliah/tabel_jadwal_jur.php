  <?php
  $jur = $_SESSION['id_jur'];
  ?>
  <div class="box-header">
    
          </div><!-- /.box-header -->

          <div class="box-body table-responsive">
          <div class="form-group">
          <form name="form1" action="<?= base_admin().'modul/jadwal_kuliah/' ?>cetak_jadwal_ruang_all.php" method="post">
<button class="btn btn-danger" name="laporan" type="submit" style="float:right"><i class="fa fa-print" ></i> Cetak Semua Jadwal</button>
 <input name='sem2' type='hidden' value='<?= de($_GET['sem']) ?>'>
              <input name='jur2' type='hidden' value='<?= $jur ?>'>
             <?php
             
                  $qqq = $db->query("select r.ruang_id, r.nm_ruang FROM kelas INNER JOIN matkul ON kelas.id_matkul=matkul.id_matkul
                                                                JOIN kurikulum k ON k.kur_id=matkul.kur_id
                                                                JOIN jurusan j ON j.kode_jur=k.kode_jur
                                                               JOIN jadwal_kuliah jd ON jd.kelas_id=kelas.kelas_id  
                                                               LEFT JOIN ruang_ref r ON r.ruang_id=jd.ruang_id
                                                               JOIN dosen_kelas dk ON dk.id_kelas=kelas.kelas_id
                                                               JOIN dosen d ON d.nip=dk.id_dosen 
                                                               WHERE j.kode_jur='$jur' and kelas.sem_id='".$dec->dec($_GET['sem'])."'
                                                               GROUP BY r.`ruang_id` ORDER BY r.`ruang_id` ASC");
                  foreach ($qqq as $kq) {
                    ?>
                    <input name='ru' type='hidden' value='<?= $kq->ruang_id?>'>
                    <?php
                  }
                 ?>

</form>

  

    &nbsp;&nbsp;<button class="btn btn-primary" data-toggle="modal" data-target="#myModal" type="submit" style="float:right"><i class="fa fa-print"></i> Cetak Jadwal</button>&nbsp;&nbsp;

         
             
           
               <br><br><br>
             
          <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- konten modal-->
            <div class="modal-content">
            <form class="form" id="ruang" action="<?= base_admin().'modul/jadwal_kuliah/' ?>cetak_jadwal_ruang.php" method="post">
                <!-- heading modal -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Silakan Pilih Nama Ruang yang Akan Dicetak</h4>
                </div>
                <!-- body modal -->
                <div class="modal-body">
               
                    <div class="form-group">
                                  <label for="Nama Kelas" class="control-label col-lg-2">Ruang</label>
                                  <div class="col-lg-10">
                                      <select name="ruang" id="ruang" data-placeholder="Pilih Nama Ruang ..." class="form-control chzn-select" tabindex="2" method="post">
                                         <option value=""></option>
                                         <?php 
                                           $ruang = $db->query("select r.ruang_id, r.nm_ruang FROM kelas INNER JOIN matkul ON kelas.id_matkul=matkul.id_matkul
                                                                JOIN kurikulum k ON k.kur_id=matkul.kur_id
                                                                JOIN jurusan j ON j.kode_jur=k.kode_jur
                                                               JOIN jadwal_kuliah jd ON jd.kelas_id=kelas.kelas_id  
                                                               LEFT JOIN ruang_ref r ON r.ruang_id=jd.ruang_id
                                                               JOIN dosen_kelas dk ON dk.id_kelas=kelas.kelas_id
                                                               JOIN dosen d ON d.nip=dk.id_dosen 
                                                               WHERE j.kode_jur='$jur' and kelas.sem_id='".$dec->dec($_GET['sem'])."'
                                                               GROUP BY r.`ruang_id` ORDER BY r.`ruang_id` ASC");
                                            foreach ($ruang as $isi2) {
                                              if ($isi2->ruang_id) {
                                               echo "<option value='".$enc->enc($isi2->ruang_id)."' selected> $isi2->nm_ruang</option>";
                                              }else{
                                               echo "<option value='".$enc->enc($isi2->ruang_id)."'>$isi2->nm_ruang</option>";
                                              }
                                            
                                         } ?>
                                        </select>
                                  </div>
                                </div><!-- /.form-group --><br><br>
                </div>
                <!-- footer modal -->
                <input name='sem' type='hidden' value='<?= de($_GET['sem']) ?>'>
              <input name='jur' type='hidden' value='<?= $jur ?>'>
                 
                 
                <div class="modal-footer">
                
                    <button  class="btn btn-primary" type="submit">Cetak</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                    
                </div>
              </form>
            </div>
        </div>
    </div>
            <table id="dtb_manual" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th style="width:25px" class='center' valign="center" rowspan='2'>No</th>
                                 
                                  <th class='center' valign="center" rowspan='2'>Mata Kuliah</th>
                                  <th class='center' valign="center" rowspan='2'>Dosen Pengampu</th>
                                   <th class='center' valign="center" rowspan='2'>Ruang</th>
                                  <th class='center' valign="center" colspan='7'>Hari</th>
                                 
                                  
                  <th class='center' valign="center" rowspan='2'>Action</th>
                </tr>
                <tr>
                  <th>Senin</th>
                  <th>Selasa</th>
                  <th>Rabu</th>
                  <th>Kamis</th>
                  <th>Jumat</th>
                  <th>Sabtu</th>
                  <th>Minggu</th>
                </tr>
              </thead>
              <tbody>
                
      <?php
      $dtb=$db->query("select jd.jadwal_id, d.nama_dosen, d.gelar_depan,d.gelar_belakang, r.nm_ruang, jd.jam_mulai,jd.jam_selesai,jd.hari, matkul.kode_mk, j.nama_jur, kelas.kls_nama,kelas.kode_paralel,
                      kelas.peserta_max,kelas.peserta_min,matkul.nama_mk,
                      kelas.kelas_id from kelas inner join matkul on kelas.id_matkul=matkul.id_matkul
                      join kurikulum k on k.kur_id=matkul.kur_id
                      join jurusan j on j.kode_jur=k.kode_jur
                     join jadwal_kuliah jd on jd.kelas_id=kelas.kelas_id  
                     left join ruang_ref r on r.ruang_id=jd.ruang_id
                     join dosen_kelas dk on dk.id_kelas=kelas.kelas_id
                     join dosen d on d.nip=dk.id_dosen 
                     where j.kode_jur='$jur' and kelas.sem_id='".$dec->dec($_GET['sem'])."'
                     order by d.nama_dosen asc ");
                        $i=1;
      foreach ($dtb as $isi) {
        ?><tr id="line_<?=$isi->jadwal_id;?>">
          <td align="center"><?=$i;?></td>
         
          <td><?=$isi->nama_mk." - ".$isi->kls_nama;?></td>
          <td><?php echo "$isi->nama_dosen $isi->gelar_depan,$isi->gelar_belakang ";
                                   /*   $qd= $db->query("select d.gelar_depan,d.gelar_belakang, d.nama_dosen, d.nama_dosen,ds.id_kelas from dosen_kelas ds join 
                                                       dosen d on d.nip=ds.id_dosen 
                                                      where ds.id_kelas='".$isi->kelas_id."' group by d.nip ");
                                    foreach ($qd as $kd) {
                                       echo "<table><tr><td style='vertical-align: text-top;'>-&nbsp;</td><td>$kd->nama_dosen $kd->gelar_depan,$kd->gelar_belakang </td></tr></table>";
                                    }*/

                                    ?>
                                  </td>
               <td class='center'><?= $isi->nm_ruang ?></td> 
          <td><?php if(strtolower($isi->hari)=='senin') echo "$isi->hari <br>".substr($isi->jam_mulai, 0,5)." - ".substr($isi->jam_selesai,0,5); ?></td>
          <td><?php if(strtolower($isi->hari)=='selasa') echo "$isi->hari <br>".substr($isi->jam_mulai, 0,5)." - ".substr($isi->jam_selesai,0,5); ?></td>
          <td><?php if(strtolower($isi->hari)=='rabu') echo "$isi->hari <br>".substr($isi->jam_mulai, 0,5)." - ".substr($isi->jam_selesai,0,5); ?></td>
          <td><?php if(strtolower($isi->hari)=='kamis') echo "$isi->hari <br>".substr($isi->jam_mulai, 0,5)." - ".substr($isi->jam_selesai,0,5); ?></td>
          <td><?php if(strtolower($isi->hari)=='jumat') echo "$isi->hari <br>".substr($isi->jam_mulai, 0,5)." - ".substr($isi->jam_selesai,0,5); ?></td>
          <td><?php if(strtolower($isi->hari)=='sabtu') echo "$isi->hari <br>".substr($isi->jam_mulai, 0,5)." - ".substr($isi->jam_selesai,0,5); ?></td>
          <td><?php if(strtolower($isi->hari)=='minggu') echo "$isi->hari <br>".substr($isi->jam_mulai, 0,5)." - ".substr($isi->jam_selesai,0,5); ?></td>

         
          
        <td>
            <?php
            if($role_act["up_act"]=="Y") {
              echo '<a href="'.base_index().'jadwal-kuliah/edit/'.$isi->jadwal_id.'" data-id="'.$isi->jadwal_id.'" class="btn edit_data btn-primary "><i class="fa fa-pencil"></i></a> ';
            }
            if($role_act["del_act"]=="Y") {
              echo '<button class="btn btn-danger hapus " data-uri="'.base_admin().'modul/jadwal_kuliah/jadwal_kuliah_action.php" data-id="'.$isi->jadwal_id.'"><i class="fa fa-trash-o"></i></button>';
            }
          ?>
        </td>
        </tr>
        <?php
      $i++;
      }
      ?>
              </tbody>
              
            </table>
            </div><!-- /.box-body -->