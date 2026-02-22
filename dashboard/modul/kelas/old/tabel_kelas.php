<div class="box-header">

                                <?php
                                  foreach ($db->fetch_all("sys_menu") as $k) {
                                      if (uri_segment(1)==$k->url) {
                                          if ($role_act["insert_act"]=="Y") {
                                      ?>
                                   
                                      <a href="<?=base_index();?>kelas/tambah/<?= $_GET['jur'] ?>/<?= $_GET['sem'] ?>" class="btn btn-primary " style="float:right" ><i class="fa fa-plus"></i>&nbsp;Tambah Kelas</a>
                                  
                                  <form name="form1" action="<?= base_admin().'modul/kelas/' ?>cetak_all_uas.php" method="post">
                                          <button class="btn btn-success" name="laporan" type="submit" style="float:right"><i class="fa fa-print" ></i> Semua Presensi UAS</button>
                                          <input name='jur' type='hidden' value='<?= de($_GET['jur']) ?>'>
                                          <input name='sem' type='hidden' value='<?= de($_GET['sem']) ?>'>

                                  </form>
                                  <form name="form1" action="<?= base_admin().'modul/kelas/' ?>cetak_all_uts.php" method="post">
                                          <button class="btn btn-warning" name="laporan" type="submit" style="float:right"><i class="fa fa-print" ></i> Semua Presensi UTS</button>
                                          <input name='jur2' type='hidden' value='<?= de($_GET['jur']) ?>'>
                                          <input name='sem2' type='hidden' value='<?= de($_GET['sem']) ?>'>

                                  </form>
                                  <form name="form3" action="<?= base_admin().'modul/kelas/' ?>cetak_all_kelas.php" method="post">
                                          <button class="btn btn-primary" name="laporan" type="submit" style="float:right"><i class="fa fa-print" ></i> Semua Presensi Kelas</button>
                                          <input name='jur3' type='hidden' value='<?= de($_GET['jur']) ?>'>
                                          <input name='sem3' type='hidden' value='<?= de($_GET['sem']) ?>'>

                                  </form>
                                      <?php
                                          }
                                      }
                                  }
                                ?>
                                <div class="form-group">
    <button class="btn btn-primary" data-toggle="modal" data-target="#myModal"><i class="fa fa-print"></i> Presensi Kelas</button>
    <button class="btn btn-warning" data-toggle="modal" data-target="#myModal2"><i class="fa fa-print"></i> Presensi UTS</button>
    <button class="btn btn-success" data-toggle="modal" data-target="#myModal3"><i class="fa fa-print"></i> Presensi UAS</button>
   


  </div>
  <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- konten modal-->
            <div class="modal-content">
            <form class="form" id="kode" action="<?= base_admin().'modul/kelas/' ?>cetak_presensi.php" method="post">
                <!-- heading modal -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Silakan Pilih Kelas untuk Dicetak</h4>
                </div>
                <!-- body modal -->
                <div class="modal-body">

                    <div class="form-group">
                                  <label for="Nama Kelas" class="control-label col-lg-2">Kelas</label>
                                  <div class="col-lg-10">
                                      <select name="kelas" id="kelas" data-placeholder="Pilih Kelas ..." class="form-control chzn-select" tabindex="2" method="post">
                                         <option value=""></option>
                                         <?php 
                                           $kel = $db->query("select matkul.kode_mk, j.nama_jur, kelas.kls_nama,kelas.kode_paralel,
                                                              kelas.peserta_max,kelas.peserta_min,matkul.nama_mk,
                                                              kelas.kelas_id from kelas inner join matkul on kelas.id_matkul=matkul.id_matkul
                                                              join kurikulum k on k.kur_id=matkul.kur_id
                                                              join jurusan j on j.kode_jur=k.kode_jur 
                                                              where j.kode_jur='".$dec->dec($_GET['jur'])."' and kelas.sem_id='".$dec->dec($_GET['sem'])."'");
                                            foreach ($kel as $isi2) {
                                              if ($isi2->kelas_id==$kel2) {
                                               echo "<option value='".$enc->enc($isi2->kelas_id)."' selected>$isi2->nama_mk $isi2->kls_nama</option>";
                                              }else{
                                                echo "<option value='".$enc->enc($isi2->kelas_id)."'>$isi2->nama_mk $isi2->kls_nama</option>";
                                              }
                                            
                                         } ?>
                                        </select>
                                        
                                  </div>
                                </div><!-- /.form-group --><br><br>
                </div>
                <!-- footer modal -->
                 
                 
                 
                <div class="modal-footer">
                
                    <button  class="btn btn-primary" type="submit">Cetak</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                    
                </div>
              </form>
            </div>
        </div>
    </div>       


    
  <div id="myModal2" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- konten modal-->
            <div class="modal-content">
            <form class="form" id="kode" action="<?= base_admin().'modul/kelas/' ?>cetak_presensi_uts.php" method="post">
                <!-- heading modal -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Silakan Pilih Kelas untuk Dicetak</h4>
                </div>
                <!-- body modal -->
                <div class="modal-body">

                    <div class="form-group">
                                  <label for="Nama Kelas" class="control-label col-lg-2">Kelas</label>
                                  <div class="col-lg-10">
                                      <select name="kelas2" id="kelas" data-placeholder="Pilih Kelas ..." class="form-control chzn-select" tabindex="2" method="post">
                                         <option value=""></option>
                                         <?php 
                                           $kel = $db->query("select matkul.kode_mk, j.nama_jur, kelas.kls_nama,kelas.kode_paralel,
                                                              kelas.peserta_max,kelas.peserta_min,matkul.nama_mk,
                                                              kelas.kelas_id from kelas inner join matkul on kelas.id_matkul=matkul.id_matkul
                                                              join kurikulum k on k.kur_id=matkul.kur_id
                                                              join jurusan j on j.kode_jur=k.kode_jur 
                                                              where j.kode_jur='".$dec->dec($_GET['jur'])."' and kelas.sem_id='".$dec->dec($_GET['sem'])."'");
                                            foreach ($kel as $isi2) {
                                              if ($isi2->kelas_id==$kel2) {
                                               echo "<option value='".$enc->enc($isi2->kelas_id)."' selected>$isi2->nama_mk $isi2->kls_nama</option>";
                                              }else{
                                                echo "<option value='".$enc->enc($isi2->kelas_id)."'>$isi2->nama_mk $isi2->kls_nama</option>";
                                              }
                                            
                                         } ?>
                                        </select>
                                        
                                  </div>
                                </div><!-- /.form-group --><br><br>
                </div>
                <!-- footer modal -->
                 
                 
                 
                <div class="modal-footer">
                
                    <button  class="btn btn-primary" type="submit">Cetak</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                    
                </div>
              </form>
            </div>
        </div>
    </div>       



    
  <div id="myModal3" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- konten modal-->
            <div class="modal-content">
            <form class="form" id="kode" action="<?= base_admin().'modul/kelas/' ?>cetak_presensi_uas.php" method="post">
                <!-- heading modal -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Silakan Pilih Kelas untuk Dicetak</h4>
                </div>
                <!-- body modal -->
                <div class="modal-body">

                    <div class="form-group">
                                  <label for="Nama Kelas" class="control-label col-lg-2">Kelas</label>
                                  <div class="col-lg-10">
                                      <select name="kelas3" id="kelas" data-placeholder="Pilih Kelas ..." class="form-control chzn-select" tabindex="2" method="post">
                                         <option value=""></option>
                                         <?php 
                                           $kel = $db->query("select matkul.kode_mk, j.nama_jur, kelas.kls_nama,kelas.kode_paralel,
                                                              kelas.peserta_max,kelas.peserta_min,matkul.nama_mk,
                                                              kelas.kelas_id from kelas inner join matkul on kelas.id_matkul=matkul.id_matkul
                                                              join kurikulum k on k.kur_id=matkul.kur_id
                                                              join jurusan j on j.kode_jur=k.kode_jur 
                                                              where j.kode_jur='".$dec->dec($_GET['jur'])."' and kelas.sem_id='".$dec->dec($_GET['sem'])."'");
                                            foreach ($kel as $isi2) {
                                              if ($isi2->kelas_id==$kel2) {
                                               echo "<option value='".$enc->enc($isi2->kelas_id)."' selected>$isi2->nama_mk $isi2->kls_nama</option>";
                                              }else{
                                                echo "<option value='".$enc->enc($isi2->kelas_id)."'>$isi2->nama_mk $isi2->kls_nama</option>";
                                              }
                                            
                                         } ?>
                                        </select>
                                        
                                  </div>
                                </div><!-- /.form-group --><br><br>
                </div>
                <!-- footer modal -->
                 
                 
                 
                <div class="modal-footer">
                
                    <button  class="btn btn-primary" type="submit">Cetak</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                    
                </div>
              </form>
            </div>
        </div>
    </div>       
                            </div><!-- /.box-header -->
                            
                            <div class="box-body table-responsive">
                                
                        <table id="dtb_manual" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                  <th>No</th>
                                  <th>Nama Kelas</th>
                                  <th style='width:200px;'>Mata Kuliah</th>
                                  <th>Kuota</th>
                                  <th>Peserta</th>  
                                  <th>Dosen Pengampu</th>                              
                                  <th>Jurusan</th>
                                  <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                              <?php
                              $no=1;
                              $qq = $db->query("select matkul.kode_mk, j.nama_jur, kelas.kls_nama,kelas.kode_paralel,
    kelas.peserta_max,kelas.peserta_min,matkul.nama_mk,
    kelas.kelas_id from kelas inner join matkul on kelas.id_matkul=matkul.id_matkul
    join kurikulum k on k.kur_id=matkul.kur_id
    join jurusan j on j.kode_jur=k.kode_jur 
    where j.kode_jur='".$dec->dec($_GET['jur'])."' and kelas.sem_id='".$dec->dec($_GET['sem'])."'");
                              foreach ($qq as $k) {
                              
                               ?>
                                  <tr id="line_<?=$k->kelas_id;?>">
                                  <td><?= $no ?></td>
                                  <td><?= $k->kls_nama ?></td>
                                  <td><?= $k->kode_mk." - ".$k->nama_mk ?></td>
                                  <td><?= $k->peserta_max ?></td>
                                  <td>  <?php
                                      $qp= $db->query("select count(k.id_krs_detail) as jml from krs_detail k join kelas kl on k.id_kelas=kl.kelas_id
                                                 where k.disetujui='1' and kl.kelas_id='".$k->kelas_id."' group by kl.kelas_id  ");
                                    foreach ($qp as $kp) {
                                       echo "$kp->jml <br>";
                                    }

                                    ?></td> 
                                  <td>
                                  <?php
                                      $qd= $db->query("select d.gelar_depan,d.gelar_belakang, d.nama_dosen, d.nama_dosen,ds.id_kelas from dosen_kelas ds join 
                                                       dosen d on d.nip=ds.id_dosen 
                                                      where ds.id_kelas='".$k->kelas_id."' group by d.nip ");
                                    foreach ($qd as $kd) {
                                       echo "<table><tr><td style='vertical-align: text-top;'>-&nbsp;</td><td>$kd->nama_dosen $kd->gelar_depan,$kd->gelar_belakang </td></tr></table>";
                                    }

                                    ?>
                                  </td>                                
                                  <td><?= $k->nama_jur ?></td>
                                  <td> <?php
            if($role_act["up_act"]=="Y") {
              echo '<a href="'.base_index().'kelas/edit/'.en($k->kelas_id)."/".$_GET['jur'].'/'.$_GET['sem'].'" data-id="'.$k->kelas_id.'" class="btn edit_data btn-primary "><i class="fa fa-pencil"></i></a> ';
            }
            if($role_act["del_act"]=="Y") {
              echo '<button class="btn btn-danger hapus " data-uri="'.base_admin().'modul/kelas/kelas_action.php" data-id="'.$k->kelas_id.'"><i class="fa fa-trash-o"></i></button>';
            }
          ?></td>
                                </tr>
                               <?php
                               $no++;
                              }
                              ?>
                            </tbody>
                        </table>
                        
                    </div><!-- /.box-body -->

                    <script type="text/javascript">

      function submit_form(){
document.form1.submit();
document.form2.submit();
document.form3.submit();
}


    
</script>
                    
                 
               