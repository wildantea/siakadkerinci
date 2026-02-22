  <?php
  $nim = $_SESSION['username'];
  $sem = $sem_aktif->id_semester;
  $m = get_atribut_mhs($_SESSION['username']); 
  $qq = $db->query(" select k.`nim`, k.id_krs_detail, js.jns_semester,js.nm_singkat, sf.tahun, s.id_semester,
                  (SELECT SUM(sks) FROM krs_detail WHERE  krs_detail.batal='0' and krs_detail.`nim`=k.nim GROUP BY krs_detail.id_krs) AS sks_diambil 
                  FROM krs_detail k JOIN semester s ON (s.`id_semester`=k.`id_semester` and s.`kode_jur`='".$m->jur_kode."')
                  left JOIN semester_ref sf ON sf.id_semester=s.id_semester 
                  left JOIN jenis_semester js ON js.id_jns_semester=sf.id_jns_semester  
                  WHERE k.nim='$nim' and s.id_semester='$sem' group by s.id_semester ORDER BY s.id_semester DESC");
  // echo "select k.`nim`, k.id_krs_detail, js.jns_semester,js.nm_singkat, sf.tahun, s.id_semester,
  //                 (SELECT SUM(sks) FROM krs_detail WHERE  krs_detail.batal='0' and krs_detail.`nim`=k.nim GROUP BY krs_detail.id_krs) AS sks_diambil 
  //                 FROM krs_detail k JOIN semester s ON (s.`id_semester`=k.`id_semester` and s.`kode_jur`='".$m->jur_kode."')
  //                 left JOIN semester_ref sf ON sf.id_semester=s.id_semester 
  //                 left JOIN jenis_semester js ON js.id_jns_semester=sf.id_jns_semester  
  //                 WHERE k.nim='$nim' and s.id_semester='$sem' group by s.id_semester ORDER BY s.id_semester DESC";
 
  // print_r($_SESSION);

  // echo "select k.mhs_id,k.krs_id, js.jns_semester,js.nm_singkat, sf.tahun, s.id_semester,a.*,
  //   (SELECT SUM(sks) FROM krs_detail WHERE krs_detail.id_krs=k.krs_id
  //   AND krs_detail.batal='0' GROUP BY krs_detail.id_krs) AS sks_diambil FROM krs k
  //   JOIN semester s ON s.sem_id=k.sem_id
  //   JOIN akm a ON (a.sem_id=s.id_semester AND a.mhs_nim=k.mhs_id)
  //   JOIN semester_ref sf ON sf.id_semester=s.id_semester
  //   JOIN jenis_semester js ON js.id_jns_semester=sf.id_jns_semester WHERE k.mhs_id='$nim' and s.id_semester='$sem'
  //   ORDER BY s.id_semester DESC";

  foreach ($qq as $kk) {
    ?>
     <div class="box-header">
        <h3>
          <?= "Semester :  ".$kk->tahun."/".($kk->tahun+1)." $kk->jns_semester" ?>
        </h3>
        
      </div><!-- /.box-header -->

      <section class="content">
        <div class="row">
          <div class="col-xs-12">
            <div class="box">         
              <div class="box-header">
                <div class="form-group">
                  <form name="form" action="<?= base_admin().'modul/jadwal_kuliah/' ?>cetak_jadwal.php" method="post" target="_blank">

                    <button class="btn btn-success" name="laporan" type="submit"><i class="fa fa-print" ></i> Cetak Jadwal</button>

                    <input name='nim' type='hidden' value='<?= $nim ?>'>
                    <input name='k' type='hidden' value='<?= $kk->krs_id ?>'>
                    <input name='sem' type='hidden' value='<?= $sem ?>'>

                  </form>


                </div>
                

                <div class="box-body table-responsive">
                  
                  <table id="dtb_manual" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th style="width:25px" class='center' valign="center" rowspan='2'>No</th>
                        <th class='center' valign="center" rowspan='2'>Mata Kuliah</th>
                        <th class='center' valign="center" rowspan='2'>Ruang</th>
                        <th class='center' valign="center" colspan='7'>Hari</th>
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
                      $where = "";
                      if ($_SESSION['group_level']=='mahasiswa') {
                        $where = " and kr.id_semester='$sem' ";
                      }
                      $dtb=$db->query("select m.kode_mk,m.nama_mk,m.sks_tm,j.hari,j.jam_mulai,j.jam_selesai,m.semester, kl.`kls_nama`,r.nm_ruang FROM krs_detail kr 
                        JOIN matkul m ON m.id_matkul=kr.kode_mk
                        JOIN kelas kl ON kl.`id_matkul`=m.`id_matkul`
                        LEFT JOIN jadwal_kuliah j ON j.kelas_id=kr.id_kelas
                        LEFT JOIN ruang_ref r ON r.ruang_id=j.ruang_id 
                        JOIN semester s ON s.`id_semester`=kr.`id_semester` WHERE kr.`batal`=0 
                        and kr.nim='$nim' $where  GROUP BY kr.kode_mk order by jam_mulai");
                      // echo "select m.kode_mk,m.nama_mk,m.sks_tm,j.hari,j.jam_mulai,j.jam_selesai,m.semester, kl.`kls_nama`,r.nm_ruang FROM krs_detail kr 
                      //   JOIN matkul m ON m.id_matkul=kr.kode_mk
                      //   JOIN kelas kl ON kl.`id_matkul`=m.`id_matkul`
                      //   LEFT JOIN jadwal_kuliah j ON j.kelas_id=kr.id_kelas
                      //   LEFT JOIN ruang_ref r ON r.ruang_id=j.ruang_id 
                      //   JOIN semester s ON s.`id_semester`=kr.`id_semester` WHERE kr.`batal`=0 
                      //   and kr.nim='$nim' $where  GROUP BY kr.kode_mk";
                      $i=1;
                      foreach ($dtb as $isi) {
                        ?><tr id="line_<?=$isi->kode_mk;?>">
                          <td align="center"><?=$i;?></td>
                          
                          <td><?=$isi->kode_mk." - ".$isi->nama_mk;?></td>
                          
                          <td><?=$isi->nm_ruang;?></td> 
                          <td><?php if(strtolower($isi->hari)=='senin') echo substr($isi->jam_mulai, 0,5)." - ".substr($isi->jam_selesai,0,5); ?></td>
                          <td><?php if(strtolower($isi->hari)=='selasa') echo substr($isi->jam_mulai, 0,5)." - ".substr($isi->jam_selesai,0,5); ?></td>
                          <td><?php if(strtolower($isi->hari)=='rabu') echo substr($isi->jam_mulai, 0,5)." - ".substr($isi->jam_selesai,0,5); ?></td>
                          <td><?php if(strtolower($isi->hari)=='kamis') echo substr($isi->jam_mulai, 0,5)." - ".substr($isi->jam_selesai,0,5); ?></td>
                          <td><?php if(strtolower($isi->hari)=='jumat') echo substr($isi->jam_mulai, 0,5)." - ".substr($isi->jam_selesai,0,5); ?></td>
                          <td><?php if(strtolower($isi->hari)=='sabtu') echo substr($isi->jam_mulai, 0,5)." - ".substr($isi->jam_selesai,0,5); ?></td>
                          <td><?php if(strtolower($isi->hari)=='minggu') echo substr($isi->jam_mulai, 0,5)." - ".substr($isi->jam_selesai,0,5); ?></td>

                          
                          
                          
                        </tr>
                        <?php
                        $i++;
                      }
                      ?>
                    </tbody>
                    
                  </table>

                  
                </div><!-- /.box-body -->
              </div>
            </div>
          </div>
        </div>
      </section>
    <?php
  }
  ?>

