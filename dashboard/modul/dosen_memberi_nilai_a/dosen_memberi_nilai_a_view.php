<?php
define('BASE_URL', 'http://localhost/history_mhs/admina/index.php/');
error_reporting(0);
$koneksi = mysqli_connect("localhost", "root", "", "uinsgd_gtakademik");
?>


<?php
$sem2 = "";


if (isset($_GET['sem'])) {
  $sem2 = $dec->dec($_GET['sem']);
 // $_SESSION['jadwal_sem'] = $_POST['sem'];
}

?>

<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Grafik Dosen Memberi Nilai A
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>dosen-memberi-nilai-a">Dosen Memberi Nilai A</a></li>
                        <li class="active">Dosen Memberi Nilai A List</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
  <div class="row"> 
    <div class="col-xs-12">
      <div class="box">
         <div class="box-body">
                 <div class="row">
           <div class="col-md-6">
              <h3 class="text-center">Form Filter</h3>
           </div>
         </div>
                <form id="form_cari_mahasiswa" method="GET" class="form-horizontal" action="">
              
                <?php
                if ($_SESSION['level']=='1') {
                  $where = "";
                  $pilih_no_jur = "<option value=''>-All Program Studi-</option>";
                }elseif ($_SESSION['level']=='5') {
                  $where = "where j.kode_jur='".$_SESSION['id_jur']."' ";
                  $pilih_no_jur = "<option value=''>-All Program Studi-</option>";
                }elseif ($_SESSION['level']=='6') {
                  $where = "where j.fak_kode='".$_SESSION['id_fak']."' ";
                 $pilih_no_jur = "<option value=''>-All Program Studi-</option>";
                }
            
                $kode_jur = isset($_GET['jur']) ? de($_GET['jur']) : "";
              
                ?>
                     <div class="form-group">
                    <label for="nm_agama" class="control-label col-lg-2">Program Studi </label>
                    <div class="col-lg-4">
                      <select name="jur" id="jur" class="form-control chzn-select" tabindex="2" method="post">
                        <?= $pilih_no_jur ?>
                        <?php
                        $qj=$db->query("select * from jurusan j $where order by j.nama_jur asc ");
                        foreach ($qj as $kj) {
                          if (isset($_GET['jur'])) {
                            if ($kode_jur==$kj->kode_jur) {
                              echo "<option value='".$enc->enc($kj->kode_jur)."' selected>$kj->nama_jur</option>";
                            }else{
                               echo "<option value='".$enc->enc($kj->kode_jur)."'>$kj->nama_jur</option>"; 
                            }
                          }else{
                            echo "<option value='".$enc->enc($kj->kode_jur)."'>$kj->nama_jur</option>"; 
                          }
                        }
                        ?>
                      </select>
                    </div>
                  </div><!-- /.form-group --> 

                  <div class="form-group">
                <label for="Nama Kelas" class="control-label col-lg-2">Semester</label>
                  <div class="col-lg-4">
                    <select name="sem" id="sem" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2" method="post" >
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

                                            
                  <div class="form-group">
                    <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                    <div class="col-lg-4">
                     <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Lihat Grafik</button>
                 
               
                    </div>
                  </div><!-- /.form-group -->

                </form>

                <?php
             
                  if (isset($_GET['jur']) || isset($_GET['sem'])) {
                   include 'grafik_nilai_a.php';
                  }
                  ?>
              </div>
   
            </div><!-- /.box -->
          </div>
        </div>
        </section><!-- /.content -->
<script type="text/javascript">
    $("#form_cari_mahasiswa").submit(function(){
    $('#loadnya').show();
        $.ajax({
            type: 'POST',
            url: '<?=base_admin();?>modul/nilai_permahasiswa/nilai_permahasiswa_action.php?act=cari_mhs',
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