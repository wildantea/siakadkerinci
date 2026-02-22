<!-- Content Header (Page header) -->
<?php
$sem2 = "";
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
                                </div><!-- /.form-group --><br><br>
                                <div class="form-group">
                                  <label for="Nama Kelas" class="control-label col-lg-2"></label>
                                  <div class="col-lg-10">
                                      <button class="btn btn-primary " type="submit">Tampilkan Jadwal</button>
                                  </div>
                                </div><!-- /.form-group -->
                                <br><br>
                                </form>
                <?php
          if (isset($_GET['sem'])) {
           include 'tabel_jadwal_jur.php';
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
                    
