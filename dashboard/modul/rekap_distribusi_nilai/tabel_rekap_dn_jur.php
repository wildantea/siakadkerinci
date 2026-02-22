<div class="box-header">

                            </div><!-- /.box-header -->
                    <div class="box-body table-responsive">
                    <form action="<?= base_admin().'modul/rekap_distribusi_nilai/' ?>cetak_rdn.php" target="_Blank" method="post">
                     <button class="btn btn-success" name="laporan" type="submit"><i class="fa fa-print"></i> Cetak</button>
                     <br>
                     <br>
                                
                        <table id="dtb_manual" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                  <th>No</th>
                                  <th>Mata Kuliah</th>
                                  <th>A</th>
                                  <th>B</th>
                                  <th>C</th>                                                                  
                                  <th>D</th>
                                  <th>E</th>
                                  <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                              <?php
                              $no=1;
                              $jur=$_SESSION['id_jur'];
                             
                                  $qq = $db->query("SELECT j.kode_jur,kelas.sem_id,matkul.kode_mk, j.nama_jur, kelas.kls_nama,kelas.kode_paralel,kelas.peserta_max,kelas.peserta_min,matkul.nama_mk, kelas.kelas_id,
(SELECT COUNT(id_krs_detail) FROM krs_detail k WHERE k.id_kelas=kelas.kelas_id AND (nilai_huruf='')AND k.batal=0 GROUP BY k.id_kelas) AS belum,
(SELECT COUNT(id_krs_detail) FROM krs_detail k WHERE k.id_kelas=kelas.kelas_id AND (nilai_huruf!='') AND k.batal=0 GROUP BY k.id_kelas) AS sdh,
(SELECT COUNT(nilai_huruf) FROM krs_detail k WHERE k.id_kelas=kelas.kelas_id AND (nilai_huruf='A')) AS a,
(SELECT COUNT(nilai_huruf) FROM krs_detail k WHERE k.id_kelas=kelas.kelas_id AND (nilai_huruf='B')) AS b,
(SELECT COUNT(nilai_huruf) FROM krs_detail k WHERE k.id_kelas=kelas.kelas_id AND (nilai_huruf='C')) AS c,
(SELECT COUNT(nilai_huruf) FROM krs_detail k WHERE k.id_kelas=kelas.kelas_id AND (nilai_huruf='D')) AS d,
(SELECT COUNT(nilai_huruf) FROM krs_detail k WHERE k.id_kelas=kelas.kelas_id AND (nilai_huruf='E')) AS e,
(SELECT SUM(a + b + c + d + e)) AS jml
FROM kelas 
INNER JOIN matkul ON kelas.id_matkul=matkul.id_matkul 
JOIN kurikulum k ON k.kur_id=matkul.kur_id
JOIN jurusan j ON j.kode_jur=k.kode_jur 
WHERE j.kode_jur='$jur' AND kelas.sem_id='".$dec->dec($_GET['sem'])."'");
                             
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
                                  <td><?= $k->kls_nama." - ".$k->nama_mk  ?></td>
                                  <td><?= $k->a ?></td>                                 
                                  <td><?= $k->b ?></td>                                
                                  <td><?= $k->c ?></td>
                                  <td><?= $k->d ?></td>
                                  <td><?= $k->e ?></td>
                                  <td><?= $k->jml ?></td>
                                </tr>
                               <?php
                               $no++;
                              }
                              ?>
                              
                            </tbody>
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
            