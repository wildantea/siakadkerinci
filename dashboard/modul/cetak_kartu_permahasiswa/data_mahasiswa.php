<section class="content-header">
  <h1>Manage Rencana Studi</h1>
  <ol class="breadcrumb">
    <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="<?=base_index();?>rencana-studi">Rencana Studi</a></li>
    <li class="active">Rencana Studi List</li>
  </ol>
</section>

<section class="content">
  <div class="row">
  
    <div class="col-xs-12">
      <div class="box">
         <div class="box-body">
          <div class="box box-primary">                
             
                <form id="form_cari_mahasiswa" method="GET" class="form-horizontal" action="">
                    <div class="row">
                   <div class="col-md-12">
                      <h3 class="text-center">Data Mahasiswa</h3>
                   </div>
                 </div>
                  <div class="form-group">
                    <label for="nim" class="control-label col-lg-2 col-md-2 col-xs-4" style="text-align: left">NIM </label>
                    <div class="col-lg-10 col-md-10 col-xs-8">
                      <b>: <?= $k->nim ?></b> 
                    </div>
                  </div><!-- /.form-group -->
                   <div class="form-group">
                    <label for="nama" class="control-label col-lg-2 col-md-2 col-xs-4" style="text-align: left">Nama Mahsiswa</label>
                     <div class="col-lg-10 col-md-10 col-xs-8">
                     <b>: <?= strtoupper($k->nama) ?></b> 
                    </div>
                  </div><!-- /.form-group -->
                  <div class="form-group">
                    <label for="nama" class="control-label col-lg-2 col-md-2 col-xs-4" style="text-align: left">Fakultas</label>
                     <div class="col-lg-10 col-md-10 col-xs-8">
                      <b>: <?= $k->fakultas ?></b>
                    </div>
                  </div><!-- /.form-group -->
                   <div class="form-group">
                    <label for="nama" class="control-label col-lg-2 col-md-2 col-xs-4" style="text-align: left">Program Studi</label>
                     <div class="col-lg-10 col-md-10 col-xs-8">
                      <b>: <?= $k->jurusan ?></b>
                    </div>
                  </div><!-- /.form-group -->
                </form>
               </div>
              </div>
   
            </div><!-- /.box -->
          </div>
        </div>
        </section><!-- /.content -->
<div class="box-header">
  <div class="box-group" id="accordion">
<div class="form-group">
    <button class="btn btn-warning" data-toggle="modal" data-target="#myModal" type="submit" ><i class="fa fa-print"></i> Cetak Kartu UTS</button>
    <button class="btn btn-danger" data-toggle="modal" data-target="#myModal2" type="submit" ><i class="fa fa-print"></i> Cetak Kartu UAS</button>
  </div>

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
                                           $sem = $db->query("select k.mhs_id,k.krs_id, js.jns_semester,js.nm_singkat, sf.tahun, s.id_semester,a.*,
                                            (select sum(sks) from krs_detail where krs_detail.id_krs=k.krs_id
                                             and krs_detail.batal='0' group by krs_detail.id_krs) as sks_diambil from krs k
                                            join semester s on s.sem_id=k.sem_id
                                            join akm a on (a.sem_id=s.id_semester and a.mhs_nim=k.mhs_id)
                                            join semester_ref sf on sf.id_semester=s.id_semester
                                            join jenis_semester js on js.id_jns_semester=sf.id_jns_semester where k.mhs_id='".de(uri_segment(3))."'
                                            order by s.id_semester asc");
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

    <div id="myModal2" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- konten modal-->
            <div class="modal-content">
            <form class="form" id="sem" action="<?= base_admin().'modul/cetak_kartu_permahasiswa/' ?>cetak_kartu_uas.php" method="post">
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
                                      <select name="sem2" id="sem2" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2" method="post" >
                                         <option value=""></option>
                                         <?php 
                                           $sem = $db->query("select k.mhs_id,k.krs_id, js.jns_semester,js.nm_singkat, sf.tahun, s.id_semester,a.*,
                                            (select sum(sks) from krs_detail where krs_detail.id_krs=k.krs_id
                                             and krs_detail.batal='0' group by krs_detail.id_krs) as sks_diambil from krs k
                                            join semester s on s.sem_id=k.sem_id
                                            join akm a on (a.sem_id=s.id_semester and a.mhs_nim=k.mhs_id)
                                            join semester_ref sf on sf.id_semester=s.id_semester
                                            join jenis_semester js on js.id_jns_semester=sf.id_jns_semester where k.mhs_id='".de(uri_segment(3))."'
                                            order by s.id_semester asc");
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
    </div>
    </div>    
                    
<script type="text/javascript">

  
</script>