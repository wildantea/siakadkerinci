
                                         <div class="box-body table-responsive">
                                         
                                         <form action="<?= base_admin().'modul/akm/' ?>cetak_akm.php" method="post">
                      <button class="btn btn-success" name="laporan" type="submit"><i class="fa fa-print"></i> Cetak</button>
                     <br>
                     <br>
                                
                        <table id="dtb_manual" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                  <th>No</th>
                                  <th>NIM</th>
                                  <th>Nama</th>
                                  <th>Status</th>
                                  <th>Semester</th>  
                                  <th>SKS Diambil</th>                              
                                  <th>IP</th>
                                  <th>IPK</th>
                                </tr>
                            </thead>
                            <tbody>
                              <?php
                              $jur = $_SESSION['id_jur'];
                              $no=1;
                              $qqq = $db->query("select m.nim,m.nama,m.stat_pd, m.`mulai_smt`,a.sem_id,a.jatah_sks,a.ip, a.ipk, m.mhs_id, j.kode_jur, a.sem_id FROM mahasiswa m
                                INNER JOIN akm a ON m.nim=a.mhs_nim
                                INNER JOIN jurusan j on m.jur_kode=j.kode_jur
                                where j.kode_jur='$jur' AND m.`mulai_smt`='".$dec->dec($_GET['ang'])."' and a.sem_id='".$dec->dec($_GET['sem'])."' ORDER BY a.mhs_nim asc");
                              
                              foreach ($qqq as $kk) {
                              
                               ?>
                                  <tr id="line_<?=$kk->mhs_id;?>">
                                  <td><?= $no ?></td>
                                  <td><?= $kk->nim ?></td>
                                  <td><?= $kk->nama ?></td>
                                  <td><?= $kk->stat_pd ?></td>
                                  <td><?= $kk->sem_id ?> </td> 
                                  <td><?= $kk->jatah_sks ?>  </td>                                
                                  <td><?= $kk->ip ?></td>
                                  <td><?= $kk->ipk ?></td>
                                </tr>
                               <?php
                               $no++;
                              }
                              ?>
                             
                            </tbody>
                             <input name="jur" type="hidden"  value='<?= $kk->kode_jur ?>' method="post">
                              <input name="ang" type="hidden"  value='<?= $kk->mulai_smt ?>' method="post">
                              <input name="sem" type="hidden"  value='<?= $kk->sem_id ?>' method="post">
                        </table>
                        </form>
                    </div><!-- /.box-body -->

                    <script type="text/javascript">
$(document).ready(function() {
    $('#dtb_manual').DataTable( {
        dom: 'Bfrtip',
        text: 'AKM',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    } );
} );
      


    
</script>
                    
                 
