<?php
$jur = "";
$sem2 = "";
if (isset($_GET['jur'])) {
  $jur = $dec->dec($_GET['jur']);
 // $_SESSION['jadwal_jur'] =$_POST['jur'];
}
if (isset($_GET['sem'])) {
  $sem2 = $dec->dec($_GET['sem']);
 // $_SESSION['jadwal_sem'] = $_POST['sem'];
}
?><!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Manage Rekap Distribusi Nilai
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>rekap-distribusi-nilai">Rekap Distribusi Nilai</a></li>
                        <li class="active">Rekap Distribusi Nilai List</li>
                    </ol>
                </section>

                <!-- Main content -->
                 <section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">

         <form method="GET" action="" class="form-horizontal" id="form_filter" style="position: relative;top: 10px" onSubmit= "validasi()">
         <div class="row">
           <div class="col-md-6">
              <h3 class="text-center">Form Filter Distribusi Nilai</h3>
           </div>
         </div>
        
         <?php
         if ($_SESSION['level']=='1') {
           ?>
             <div class="form-group">
                <label for="Nama Kelas" class="control-label col-lg-2">Jurusan</label>
                <div class="col-lg-4">
                  <select name="jur" id="jur" data-placeholder="Pilih Jurusan ..." class="form-control chzn-select" tabindex="2" >
                   <option value=""></option>
                   <?php foreach ($db->query("select j.kode_jur,j.nama_jur from jurusan j order by nama_jur asc") as $isi) {
                    if ($jur==$isi->kode_jur) {
                     echo "<option value='".$enc->enc($isi->kode_jur)."' selected>$isi->nama_jur</option>";
                   }else{
                     echo "<option value='".$enc->enc($isi->kode_jur)."'>$isi->nama_jur</option>";
                   }

                 } ?>
               </select>
                 </div>
              </div><!-- /.form-group -->
              <div class="form-group">
                <label for="Nama Kelas" class="control-label col-lg-2">Semester</label>
                  <div class="col-lg-4">
                    <select name="sem" id="sem" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2" >
                     <option value=""></option>
                     <?php 
                     $sem = $db->query("select * from semester_ref s join jenis_semester j 
                      on s.id_jns_semester=j.id_jns_semester order by s.id_semester desc");
                     foreach ($sem as $isi2) {
                      if ($isi2->id_semester==$sem2) {
                       echo "<option value='".$enc->enc($isi2->id_semester)."' selected>$isi2->jns_semester $isi2->tahun/".($isi2->tahun+1)."</option>";
                     }else{
                      echo "<option value='".$enc->enc($isi2->id_semester)."'>$isi2->jns_semester $isi2->tahun/".($isi2->tahun+1)."</option>";
                    }

                  } ?>
                </select>
              </div>
             </div><!-- /.form-group -->
           <?php
         }else{
           ?>
             <div class="form-group">
                <label for="Nama Kelas" class="control-label col-lg-2">Semester</label>
                  <div class="col-lg-4">
                    <select name="sem" id="sem" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2" >
                     <option value=""></option>
                     <?php 
                     $sem = $db->query("select  k.kelas_id, s.semester as id_semester,s.tahun,m.nama_mk,k.kls_nama,js.jns_semester 
                                        from dosen_kelas dk join kelas k on dk.id_kelas=k.kelas_id
                                        join semester_ref s on s.id_semester=k.sem_id
                                        join matkul m on m.id_matkul=k.id_matkul
                                        join jenis_semester js on js.id_jns_semester=s.id_jns_semester
                                        where dk.id_dosen='".$_SESSION['username']."' group by s.semester 
                                        order by s.tahun asc");
                     foreach ($sem as $isi2) {
                      if ($isi2->id_semester==$sem2) {
                       echo "<option value='".$enc->enc($isi2->id_semester)."' selected>$isi2->jns_semester $isi2->tahun/".($isi2->tahun+1)." $isi2->jns_semester</option>";
                     }else{
                      echo "<option value='".$enc->enc($isi2->id_semester)."'>$isi2->jns_semester $isi2->tahun/".($isi2->tahun+1)." $isi2->jns_semester</option>";
                    }

                  } ?>
                </select>
              </div>
             </div><!-- /.form-group -->
           <?php
         }
         ?>
         <div class="form-group">
          <label for="Nama Kelas" class="control-label col-lg-2"></label>
          <div class="col-lg-10">
            <button class="btn btn-primary " type="submit">Tampilkan Kelas Matakuliah</button>
          </div>
        </div><!-- /.form-group -->
        <br><br>
    </form>
         <?php
          if (isset($_GET['jur']) || isset($_GET['sem'])) {
           include 'tabel_rekap_dn.php';
          }
          ?>

      </div><!-- /.box -->
    </div>
  </div>
</section><!-- /.content -->

</section><!-- /.content -->
<script type="text/javascript">
function validasi() {
    var jur = document.getElementById("jur").value;
    var sem = document.getElementById("sem").value;
    if (jur != "" && sem!="") {
      return true;
    }else{
      alert('Agar Data Keluar dan Bisa Dicetak, Anda harus Memilih Semua Kategori !');
      
    }
  }
    $("#form_filter").submit(function(){
      $('#loadnya').show();
        $.ajax({
            type: 'POST',
            url: '<?=base_admin();?>modul/kelas/tabel_kelas.php',
            data: {data_ids:all_ids},
            success: function(result) {
               $('#loadnya').hide();
             // $("#hasil").html(result);
            },
            //async:false
        });
    return false;
  });
</script>