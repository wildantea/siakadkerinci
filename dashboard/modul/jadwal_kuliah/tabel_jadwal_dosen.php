  <?php
  $nim = $_SESSION['username'];
 // $sem = $sem_aktif->id_semester;

  ?>
  <section class="content-header">
    <h1>
     Jadwal Kuliah
   </h1>
   <ol class="breadcrumb">
    <li><a href="http://siakad.iainkerinci.ac.id/dashboard/index.php/"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="http://siakad.iainkerinci.ac.id/dashboard/index.php/jadwal-kuliah">Jadwal Kuliah</a></li>
    <li class="active">Jadwal Kuliah List</li>
  </ol>
</section>
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">         
        <div class="box-header">
          <form class="form-horizontal" id="filter_kelas_form" method="post" action="http://siakad.iainkerinci.ac.id/dashboard/modul/kelas_jadwal/cetak.php" target="_blank">

            <div class="form-group">
              <label for="Semester" class="control-label col-lg-2">Semester</label>
              <div class="col-lg-5">
               <select class="form-control" name="semester" onchange="show_jadwal(this.value,'<?= $nim ?>')">
                <option value="">-Pilih Semester-</option>
                <?php
                $qs = $db->query("select k.`sem_id`,js.`nm_singkat`,js.`jns_semester`,sf.tahun  from jadwal_kuliah ku 
                  left join kelas k on ku.kelas_id=k.`kelas_id`
                  left join dosen_kelas dk on dk.id_kelas=k.kelas_id
                  left join dosen ds on ds.nip=dk.id_dosen
                  left join ruang_ref rf on rf.`ruang_id`=ku.`ruang_id`
                  left JOIN semester_ref sf ON sf.id_semester=k.sem_id
                  left JOIN jenis_semester js ON js.id_jns_semester=sf.id_jns_semester 
                  where dk.id_dosen='".$_SESSION['username']."' group by k.sem_id

                  order by k.sem_id desc");
                foreach ($qs as $vs) {
                 echo "<option value='$vs->sem_id'>$vs->jns_semester ($vs->tahun/".($vs->tahun+1).")</option>";
               }
               ?>

             </select>
           </div>
         </div><!-- /.form-group -->

       </form>
       <div id="panel_jadwal">
       <!--   <div class="form-group">
          <form name="form" action="<?= base_admin().'modul/jadwal_kuliah/' ?>cetak_jadwal.php" method="post" target="_blank">

            <button class="btn btn-success" name="laporan" type="submit"><i class="fa fa-print" ></i> Cetak Jadwal</button>

            <input name='nim' type='hidden' value='<?= $nim ?>'>
            <input name='k' type='hidden' value='<?= $kk->krs_id ?>'>
            <input name='sem' type='hidden' value='<?= $sem ?>'>

          </form>


        </div> -->


        <div class="box-body table-responsive">

          <table  class="table table-bordered table-striped">
            <thead>
              <tr>
                <th style="width:25px" class='center' valign="center" rowspan='2'>No</th>
                <th class='center' valign="center" rowspan='2'>Mata Kuliah</th>
                <th class='center' valign="center" rowspan='2'>Jurusan</th>
                <th class='center' valign="center" rowspan='2'>SKS</th>
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
              // if ($_SESSION['group_level']=='mahasiswa') {
              //   $where = " and kr.id_semester='$sem' ";
              // }
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
        </div>
      </div>
    </div>
  </div>
</div>
</div>
</section>
<script type="text/javascript">
  function show_jadwal(id_sem,nip) {
    $.ajax({
      url : "<?= base_url() ?>dashboard/modul/jadwal_kuliah/jadwal_kuliah_action.php?act=show_jadwal",
      type : "POST",
      data : {
        id_sem : id_sem,
        nip : nip
      },
      success : function(data){
        $("#panel_jadwal").html(data);
      }
    });
  }
</script>


