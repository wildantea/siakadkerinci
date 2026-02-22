
 <section class="content-header">
                    <h1>
                        Dosen Belum Lengkap Input Nilai List
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>rekap_input_nilai">Rekap Input Nilai</a></li>
                        <li class="active">Rekap Input Nilai List</li>
                    </ol>
                </section>

<section class="content">
  <div class="row">

    <div class="col-xs-12">
      <div class="box">
         <div class="box-body">
                           
                    <div class="box-body table-responsive">
                    <form action="<?= base_admin().'modul/rekap_input_nilai/' ?>belum_input_cetak.php" method="post">
                      <button class="btn btn-success" name="laporan" type="submit"><i class="fa fa-print"></i> Cetak</button>
                     <br>
                     <br>
                                
                        <table id="dtb_manual" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                  <th>No</th>
                                  
                                  <th>Dosen Pengampu</th>
                                  <th >Mata Kuliah</th>
                                  <th >Sudah Dinilai</th>
                                  <th >Belum Dinilai</th>
                                                                                                   
                             
                                </tr>
                            </thead>
                            <tbody>
                              <?php
                              $no=1;
                              $jur=$_SESSION['id_jur'];
                                  $qq = $db->query("SELECT matkul.kode_mk, d.`gelar_depan`, d.`nama_dosen`, d.`gelar_belakang`, j.nama_jur, kelas.`sem_id`, kelas.kls_nama,kelas.kode_paralel, kelas.peserta_max,kelas.peserta_min,matkul.nama_mk, kelas.kelas_id,  
(SELECT COUNT(id_krs_detail) FROM krs_detail k WHERE k.id_kelas=kelas.kelas_id AND (nilai_huruf='')
                                                AND k.batal=0 GROUP BY k.id_kelas) AS belum,
(SELECT COUNT(id_krs_detail) FROM krs_detail k 
WHERE k.id_kelas=kelas.kelas_id AND (nilai_huruf!='') AND k.batal=0 GROUP BY k.id_kelas) AS sdh 
FROM kelas INNER JOIN matkul ON kelas.id_matkul=matkul.id_matkul 
JOIN kurikulum k ON k.kur_id=matkul.kur_id 
JOIN jurusan j ON j.kode_jur=k.kode_jur 
JOIN dosen_kelas dk ON dk.`id_kelas` = kelas.`kelas_id`
JOIN dosen d ON d.`nip` = dk.`id_dosen`
where j.kode_jur='$jur' and kelas.sem_id='".$dec->dec(uri_segment(4))."' and (SELECT COUNT(id_krs_detail) FROM krs_detail k WHERE k.id_kelas=kelas.kelas_id AND (nilai_huruf='') AND k.batal=0 GROUP BY k.id_kelas) IS NOT NULL AND (SELECT COUNT(id_krs_detail) FROM krs_detail k WHERE k.id_kelas=kelas.kelas_id AND (nilai_huruf!='') AND k.batal=0 GROUP BY k.id_kelas) IS NOT NULL");

                              
                            
                              foreach ($qq as $k) {
                              
                               ?>
                                  <tr id="line_<?=$k->kelas_id;?>">
                                  <td><?= $no ?></td>
                                  
                                                                  
                                  <td>
                                  <?= $k->gelar_depan." ".$k->nama_dosen." ".$k->gelar_belakang ?>
                                  </td> 
                                  <td><?= $k->kode_mk." - ".$k->nama_mk ?></td> 
                                  <td><?= $k->sdh ?></td>
                                  <td><?= $k->belum ?></td>

                                                                   
                                  
                                </tr>
                               <?php
                               $no++;
                              }
                              ?>
                            </tbody>
                            <input type='hidden' name='k' value='<?= $k->kelas_id ?>' method="post">
                            <input type='hidden' name='jur' value='<?= $jur ?>' method="post">
                              <input type='hidden' name='sem' value='<?= de(uri_segment(4)) ?>' method="post">
                        </table>
                    </div><!-- /.box-body -->
</form>
</div>
</div>
</div>
</div>
</div>
</section>