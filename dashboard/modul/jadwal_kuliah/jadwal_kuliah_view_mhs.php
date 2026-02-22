<!-- Content Header (Page header) -->
<?php
$nim = $_SESSION['username'];
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
?>

<section class="content-header">
  <h1>Manage Jadwal Kuliah</h1>
  <ol class="breadcrumb">
    <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="<?=base_index();?>jadwal-kuliah">Jadwal Kuliah</a></li>
    <li class="active">Jadwal Kuliah List</li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
          <form method="GET" action="" id="form_filter">
                              
                                <div class="form-group">
                                  <label for="Nama Kelas" class="control-label col-lg-2">Semester</label>
                                  <div class="col-lg-10">
                                      <select name="sem" id="sem" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2" method="post" required>
                                         <option value=""></option>
                                         <?php 
                                           $sem = $db->query("select k.mhs_id,k.krs_id, js.jns_semester,js.nm_singkat, sf.tahun, s.id_semester,a.*,
                                            (SELECT SUM(sks) FROM krs_detail WHERE krs_detail.id_krs=k.krs_id
                                             AND krs_detail.batal='0' GROUP BY krs_detail.id_krs) AS sks_diambil FROM krs k
                                            JOIN semester s ON s.sem_id=k.sem_id
                                            JOIN akm a ON (a.sem_id=s.id_semester AND a.mhs_nim=k.mhs_id)
                                            JOIN semester_ref sf ON sf.id_semester=s.id_semester
                                            JOIN jenis_semester js ON js.id_jns_semester=sf.id_jns_semester WHERE k.mhs_id='$nim'
                                            ORDER BY s.id_semester DESC");
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
                                <input name='nim' type='hidden' value='<?= $nim ?>'>

                                <div class="form-group">
                                  <label for="Nama Kelas" class="control-label col-lg-2"></label>
                                  <div class="col-lg-10">
                                      <br><button class="btn btn-primary " type="submit">Tampilkan Jadwal</button><br>
                                  </div>
                                  <br>
                                </div><!-- /.form-group -->
                                <br><br>
                                </form>
                <?php
          if (isset($_GET['sem'])) {
           include 'tabel_jadwal_mhs.php';
          }
          ?>
            </div><!-- /.box -->
          </div>
        </div>
        </section><!-- /.content -->

    </section><!-- /.content -->

    <script type="text/javascript">
      function reset_jadwal(){
         $("#loadnya").show();
        $.ajax({
                type: "post",
                url: "<?=base_admin();?>modul/jadwal_kuliah/jadwal_kuliah_action.php?act=reset_jadwal",
                data: "jur=<?= $_GET['jur'] ?>&sem=<?= $_GET['sem'] ?>",
                success: function(data) {
                    console.log(data);
                    $("#loadnya").hide();
                    location.reload();
                }
            });
      }
    </script>
                    
