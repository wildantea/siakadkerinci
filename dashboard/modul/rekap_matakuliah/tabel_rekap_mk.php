<div class="box-header">


                            </div><!-- /.box-header -->
                    <div class="box-body table-responsive">
                    <form action="<?= base_admin().'modul/rekap_matakuliah/' ?>cetak_rmk.php" target="_Blank" method="post">
                     <button class="btn btn-success" name="laporan" type="submit"><i class="fa fa-print"></i> Cetak</button>
                     <br>
                     <br>
                                
                        <table id="dtb_manual" class="table table-bordered table-striped">
                        
                            <thead>
                                <tr>
                                  <th>No</th>
                                  <th>NIM</th>
                                  <th style='width:200px;'>Nama</th>
                                  <th>Mata Kuliah</th>
                                  <th>Jumlah SKS</th>                                                                  
                                  <th>IP Semester</th>
                                </tr>
                            </thead>
                            <tbody>
                              <?php
                              $no=1;
                             
                                  $qq = $db->query("SELECT m.`nim`, m.`nama`,j.`nama_jur`, a.`jatah_sks`, a.ip, a.`sem_id`, m.`mulai_smt`,a.akm_id, j.kode_jur FROM akm a 
                                                    JOIN mahasiswa m ON m.`nim` = a.`mhs_nim`
                                                    JOIN jurusan j ON j.kode_jur=m.`jur_kode` 
                                                    WHERE j.`kode_jur`='".$dec->dec($_GET['jur'])."' AND m.`mulai_smt`='".$dec->dec($_GET['ang'])."' AND a.`sem_id`='".$dec->dec($_GET['sem'])."'");
            
                             
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
                                  <tr id="line_<?=$k->akm_id;?>">
                                  <td><?= $no ?></td>
                                  <td><?= $k->nim ?></td>
                                  <td><?= $k->nama ?></td>                                 
                                  <td><?php
                                      $qd= $db->query("SELECT k.`mhs_id`, mt.`kode_mk`, mt.`nama_mk` FROM krs_detail kr
                                                      JOIN matkul mt ON mt.`id_matkul`=kr.`kode_mk`
                                                      JOIN krs k ON k.`krs_id`=kr.`id_krs`
                                                      JOIN semester s ON s.`sem_id`=k.`sem_id`
                                                      JOIN mahasiswa m ON m.`nim` = k.`mhs_id`
                                                      where k.mhs_id='".$k->nim."' and s.`id_semester`='".$dec->dec($_GET['sem'])."' and kr.batal=0");
                                    foreach ($qd as $kd) {
                                       echo "<table><tr><td style='vertical-align: text-top;'>*)&nbsp;</td><td>$kd->kode_mk - $kd->nama_mk </td></tr></table>";
                                    }

                                    ?>
                                  </td> 
                                                            
                                  <td><?php
                                      $qp= $db->query("SELECT SUM(sks) AS jml FROM krs_detail kr 
                                                        JOIN krs k ON k.`krs_id`=kr.`id_krs`
                                                        JOIN semester s ON s.`sem_id`=k.`sem_id`
                                                 where k.mhs_id='".$k->nim."' and s.`id_semester`='".$dec->dec($_GET['sem'])."' and kr.batal=0  ");
                                    foreach ($qp as $kp) {
                                       echo "$kp->jml<br>";
                                    }

                                    ?></td>
                                  <td><?= $k->ip ?></td>
                                </tr>
                               <?php
                               $no++;
                              }
                              ?>
                               
                            </tbody>
                            <input type='hidden' name='jur' value='<?= $k->kode_jur ?>' method="post">
                                <input type='hidden' name='ang' value='<?= $k->mulai_smt ?>' method="post">
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