<div class="box-header">


                            </div><!-- /.box-header -->
                    <div class="box-body table-responsive">
                     <form action="<?= base_admin().'modul/rekap_peserta_kelas/' ?>cetak_rpk.php" target="_Blank" method="post">
                     <button class="btn btn-success" name="laporan" type="submit"><i class="fa fa-print"></i> Cetak</button>
                     <br>
                     <br>
                                
                        <table id="dtb_manual" class="table table-bordered table-striped">
                        
                            <thead>
                                <tr>
                                  <th>No</th>
                                  <th>Nama Kelas</th>
                                  <th style='width:200px;'>Mata Kuliah</th>
            
                                  <th>Peserta</th>                                                                  
                                  <th>Maksimal</th>
                                  
                                </tr>
                            </thead>
                            <tbody>
                              <?php
                              $no=1;
                              $jur=$_SESSION['id_jur'];
                                  $qq = $db->query("select matkul.kode_mk, j.nama_jur, j.kode_jur, kelas.sem_id, kelas.kls_nama,kelas.kode_paralel,
                                                kelas.peserta_max,kelas.peserta_min,matkul.nama_mk,
                                                kelas.kelas_id,
                                                (select count(id_krs_detail) from krs_detail k where k.id_kelas=kelas.kelas_id and (nilai_huruf='')
                                                and k.batal=0 group by k.id_kelas) as belum,
                                                (select count(id_krs_detail) from krs_detail k where k.id_kelas=kelas.kelas_id and (nilai_huruf!='')
                                                and k.batal=0 group by k.id_kelas) as sdh
                                                from kelas inner join matkul on kelas.id_matkul=matkul.id_matkul
                                                join kurikulum k on k.kur_id=matkul.kur_id
                                                join jurusan j on j.kode_jur=k.kode_jur 
                                                  where j.kode_jur='$jur' and kelas.sem_id='".$dec->dec($_GET['sem'])."'");
                              
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
                                  
                                   <td>  <?php
                                      $qp= $db->query("select count(k.id_krs_detail) as jml from krs_detail k join kelas kl on k.id_kelas=kl.kelas_id
                                                 where k.disetujui='1' and kl.kelas_id='".$k->kelas_id."' and k.batal='0' group by kl.kelas_id  ");
                                    foreach ($qp as $kp) {
                                       echo "$kp->jml<br>";
                                    }

                                    ?></td>                                
                                  <td><?= $k->peserta_max ?></td>
                                  
                                </tr>
                               <?php
                               $no++;
                              }

                              ?>
                            

                            </tbody>
                            <input type='hidden' name='k' value='<?= $k->kelas_id ?>' method="post">
                            <input type='hidden' name='jur' value='<?= $jur ?>' method="post">
                              <input type='hidden' name='sem' value='<?= $k->sem_id ?>' method="post">
                        </table>
                        </form>
                    </div><!-- /.box-body -->

                    <script type="text/javascript">   
     $(document).ready(function() {
    $('#dtb_manual').DataTable( {
        dom: 'Bfrtip',
        text: 'Rekapitulasi Distribusi Nilai',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    } );
} );
  
</script>
            
