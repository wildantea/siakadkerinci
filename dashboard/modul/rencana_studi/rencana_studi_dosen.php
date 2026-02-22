<div class="box-header">

                            </div><!-- /.box-header -->
                    <div class="box-body table-responsive">
                                
                        <table id="dtb_manual" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                  <th>No</th>
                                  <th>Nama Kelas</th>
                                  <th style='width:200px;'>Mata Kuliah</th>
                                  <th>Dosen Pengampu</th>
                                  <th>Peserta</th>                                                                  
                                  
                                  <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                              <?php
                              $no=1;
                              if ($_SESSION['level']=='1') {
                                  $qq = $db->query("select matkul.kode_mk, j.nama_jur, kelas.kls_nama,kelas.kode_paralel,
                                                kelas.peserta_max,kelas.peserta_min,matkul.nama_mk,
                                                kelas.kelas_id,
                                                (select count(id_krs_detail) from krs_detail k where k.id_kelas=kelas.kelas_id and (nilai_huruf='')
                                                and k.batal=0 group by k.id_kelas) as belum,
                                                (select count(id_krs_detail) from krs_detail k where k.id_kelas=kelas.kelas_id and (nilai_huruf!='')
                                                and k.batal=0 group by k.id_kelas) as sdh
                                                from kelas inner join matkul on kelas.id_matkul=matkul.id_matkul
                                                join kurikulum k on k.kur_id=matkul.kur_id
                                                join jurusan j on j.kode_jur=k.kode_jur 
                                                  where j.kode_jur='".$dec->dec($_GET['jur'])."' and kelas.sem_id='".$dec->dec($_GET['sem'])."'");
                              }else if ($_SESSION['level']=='4') {
                                  $qq = $db->query("select m.kode_mk,  k.kelas_id, s.semester,s.tahun,m.nama_mk,k.kls_nama,js.jns_semester ,
                                                    (select count(id_krs_detail) from krs_detail kk where kk.id_kelas=k.kelas_id and (nilai_huruf='')
                                                    and kk.batal=0 group by kk.id_kelas) as belum,
                                                    (select count(id_krs_detail) from krs_detail kk where kk.id_kelas=k.kelas_id and (nilai_huruf!='')
                                                     and kk.batal=0 group by kk.id_kelas) as sdh
                                                    from dosen_kelas dk join kelas k on dk.id_kelas=k.kelas_id
                                                    join semester_ref s on s.id_semester=k.sem_id
                                                    join matkul m on m.id_matkul=k.id_matkul
                                                    join jenis_semester js on js.id_jns_semester=s.id_jns_semester
                                                  where dk.id_dosen='".$_SESSION['username']."' and s.semester='".$dec->dec($_GET['sem'])."'");

                              }
                                                              /*  echo "select m.kode_mk,  k.kelas_id, s.semester,s.tahun,m.nama_mk,k.kls_nama,js.jns_semester ,
                                                    (select count(id_krs_detail) from krs_detail kk where kk.id_kelas=k.kelas_id and (nilai_huruf='')
                                                    and kk.batal=0 group by kk.id_kelas) as belum,
                                                    (select count(id_krs_detail) from krs_detail kk where kk.id_kelas=k.kelas_id and (nilai_huruf!='')
                                                     and kk.batal=0 group by kk.id_kelas) as sdh
                                                    from dosen_kelas dk join kelas k on dk.id_kelas=k.kelas_id
                                                    join semester_ref s on s.id_semester=k.sem_id
                                                    join matkul m on m.id_matkul=k.id_matkul
                                                    join jenis_semester js on js.id_jns_semester=s.id_jns_semester
                                                  where dk.id_dosen='".$_SESSION['username']."' and s.semester='".$dec->dec($_GET['sem'])."'";*/
                            
                              foreach ($qq as $k) {
                              
                               ?>
                                  <tr id="line_<?=$k->kelas_id;?>">
                                  <td><?= $no ?></td>
                                  <td><?= $k->kls_nama ?></td>
                                  <td><?= $k->kode_mk." - ".$k->nama_mk ?></td>                                 
                                  <td>
                                  <?php
                                  if ($_SESSION['level']=='4') {
                                     $where_dosen="and d.nip='".$_SESSION['username']."'";
                                  }else {
                                     $where_dosen="";
                                  }
                                      $qd= $db->query("select d.gelar_depan,d.gelar_belakang, d.nama_dosen, d.nama_dosen,ds.id_kelas from dosen_kelas ds join 
                                                       dosen d on d.nip=ds.id_dosen 
                                                      where ds.id_kelas='".$k->kelas_id."' $where_dosen group by d.nip ");
                                    foreach ($qd as $kd) {
                                       echo "<table><tr><td style='vertical-align: text-top;'>-&nbsp;</td><td>$kd->nama_dosen $kd->gelar_depan,$kd->gelar_belakang </td></tr></table>";
                                    }

                                    ?>
                                  </td> 
                                   <td>  <?php
                                      $qp= $db->query("select count(k.id_krs_detail) as jml from krs_detail k join kelas kl on k.id_kelas=kl.kelas_id
                                                 where k.disetujui='1' and kl.kelas_id='".$k->kelas_id."' and k.batal='0' group by kl.kelas_id  ");
                                    foreach ($qp as $kp) {
                                       echo "$kp->jml<br>";
                                    }

                                    ?></td>                                
                                
                                  <td>
                                    <?php 
                                     
                                  
                                         echo '<a href="'.base_index().'rencana-studi/proses/'.en($k->kelas_id)."/".$_GET['sem'].'" data-id="'.$k->kelas_id.'" class="btn edit_data btn-primary "><i class="fa "></i> Proses</a> '; 
                                     
                                      
                                       ?>
                                  </td>
                                </tr>
                               <?php
                               $no++;
                              }
                              ?>
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->
