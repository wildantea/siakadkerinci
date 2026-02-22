<div class="box-header">

                            </div><!-- /.box-header -->

                            
                    <div class="box-body table-responsive">
                                
                        <table id="dtb_manual" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                  <th>No</th>
                                  <th>NIM</th>
                                  <th>Nama</th>
                                  <th>Jurusan</th>
                                  <th>Status</th>
                                  <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                              <?php
                              $no=1;  
                              
                              $where_nim  ="m.nim like '%".clean($_GET['nim'])."%'";    
                              
                              $qq = $db->query("select m.mhs_id,m.nim,m.nama,j.nama_jur,m.stat_pd from mahasiswa m
                                                join jurusan j on m.jur_kode=j.kode_jur
                                                join fakultas f on f.kode_fak=j.fak_kode where
                                                ($where_nim)");
                      /*        echo "select m.mhs_id,m.nim,m.nama,j.nama_jur,m.stat_pd from mahasiswa m
                                                join jurusan j on m.jur_kode=j.kode_jur
                                                join fakultas f on f.kode_fak=j.fak_kode where
                                                ($where_nama $or_kondisi $where_nim) $where_jur $where_fak";*/
                              if ($qq->rowCount()>0) {
                               foreach ($qq as $k) {                              
                               ?>
                                  <tr id="line_<?=$k->mhs_id;?>">
                                  <td><?= $no ?></td>
                                  <td><?= $k->nim ?></td>
                                  <td><?= $k->nama ?></td>                                               
                                  <td><?= $k->nama_jur ?></td>
                                  <td><?= $k->stat_pd ?></td>
                                  <td>
                                    <?php
                                       echo '<a href="'.base_index().'cetak-kartu-permahasiswa/lihat-data/'.en($k->nim).'" data-id="'.$k->nim.'" class="btn edit_data btn-primary "><i class="fa fa-eye"></i> Lihat Data</a> ';
                                       ?>
                                  </td>
                                </tr>
                               <?php
                               $no++;
                              }
                              }
                              
                              ?>
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->

                    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- konten modal-->
            <div class="modal-content">
            <form class="form" id="sem" action="<?= base_admin().'modul/cetak_kartu_permahasiswa/' ?>cetak_kartu_uts.php" method="post">
                <!-- heading modal -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Silakan Pilih Semester untuk Dicetak</h4>
                </div>
                <!-- body modal -->
                <div class="modal-body">

                    <div class="form-group">
                                  <label for="Nama Kelas" class="control-label col-lg-2">Semester</label>
                                  <div class="col-lg-10">
                                      <select name="sem" id="sem" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2" method="post" >
                                         <option value=""></option>
                                         <?php 
                                           $sem = $db->query("SELECT k.mhs_id,k.krs_id, sf.tahun, js.jns_semester,js.nm_singkat, s.id_semester FROM krs k
JOIN mahasiswa m ON m.nim = k.mhs_id
          
          JOIN semester s ON s.sem_id=k.sem_id
          JOIN akm a ON (a.sem_id=s.id_semester AND a.mhs_nim=k.mhs_id)
          JOIN semester_ref sf ON sf.id_semester=s.id_semester
          JOIN jenis_semester js ON js.id_jns_semester=sf.id_jns_semester WHERE k.mhs_id='".de(uri_segment(3))."'
          ORDER BY s.id_semester ASC");
                                           
                                            foreach ($sem as $isi2) {
                                              if ($isi2->id_semester==$sem2) {
                                               echo "<option value='".$enc->enc($isi2->id_semester)."' selected>$isi2->tahun/".($isi2->tahun+1)." $isi2->jns_semester</option>";
                                              }else{
                                                echo "<option value='".$enc->enc($isi2->id_semester)."'>$isi2->tahun/".($isi2->tahun+1)." $isi2->jns_semester </option>";
                                              }
                                            
                                         } ?>
                                        </select>
                                  </div>
                                </div><!-- /.form-group --><br><br>
                </div>
                <!-- footer modal -->
                 <input name='nim' type='hidden' value='<?= de(uri_segment(3)) ?>'>
                 <?php
                  $qqq = $db->query("select k.mhs_id,k.krs_id, js.jns_semester,js.nm_singkat, sf.tahun, s.id_semester,a.*,
          (select sum(sks) from krs_detail where krs_detail.id_krs=k.krs_id
           and krs_detail.batal='0' group by krs_detail.id_krs) as sks_diambil from krs k
          join semester s on s.sem_id=k.sem_id
          join akm a on (a.sem_id=s.id_semester and a.mhs_nim=k.mhs_id)
          join semester_ref sf on sf.id_semester=s.id_semester
          join jenis_semester js on js.id_jns_semester=sf.id_jns_semester where k.mhs_id='".de(uri_segment(3))."'
          order by s.id_semester asc limit 1");
                  foreach ($qqq as $kq) {
                    ?>
                    <input name='k' type='hidden' value='<?= $kq->krs_id?>'>
                    <?php
                  }
                 ?>
                 
                <div class="modal-footer">
                
                    <button  class="btn btn-primary" type="submit">Cetak</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                    
                </div>
              </form>
            </div>
        </div>
    </div>       
