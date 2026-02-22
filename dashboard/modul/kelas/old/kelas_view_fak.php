<!-- Content Header (Page header) -->
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
$fak = $_SESSION['id_fak'];
?>
                <section class="content-header">
                    <h1>
                        Manage Kelas
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>kelas">Kelas</a></li>
                        <li class="active">Kelas List</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
                             <form method="GET" action="" id="form_filter" onSubmit="validasi()">
                               <div class="form-group">
                <label for="jur" class="control-label col-lg-2">Jurusan</label>
                <div class="col-lg-10">
                  <select name="jur" id="jur" data-placeholder="Pilih Jurusan ..." class="form-control chzn-select" tabindex="2" required>
                   <option value=""></option>
                   <?php foreach ($db->query("select j.kode_jur,j.nama_jur from jurusan j where j.fak_kode='$fak' order by nama_jur asc") as $isi) {
                    if ($jur==$isi->kode_jur) {
                     echo "<option value='".$enc->enc($isi->kode_jur)."' selected>$isi->nama_jur</option>";
                   }else{
                     echo "<option value='".$enc->enc($isi->kode_jur)."'>$isi->nama_jur</option>";
                   }

                 } ?>
               </select>
                 </div>
              </div><!-- /.form-group --><br><br>
                                <div class="form-group">
                                  <label for="Nama Kelas" class="control-label col-lg-2">Tahun Ajaran</label>
                                  <div class="col-lg-10">
                                      <select name="sem" id="sem" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2" required>
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
                                </div><!-- /.form-group --><br><br>
                                <div class="form-group">
                                  <label for="Nama Kelas" class="control-label col-lg-2"></label>
                                  <div class="col-lg-10">
                                      <button class="btn btn-primary " type="submit">Tampilkan Jadwal</button>
                                  </div>
                                </div><!-- /.form-group -->
                                <br><br>
                                </form>
                                <div id="hasil">
                                </div>
                                
          <?php
          if (isset($_GET['jur'])) {
           include 'tabel_kelas.php';
          }
          ?>



         </div>
        </div>
      </div>
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
              $("#hasil").html(result);
            },
            //async:false
        });
    return false;
  });





</script>
            