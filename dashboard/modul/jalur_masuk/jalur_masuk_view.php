<?php
$ang = "";


if (isset($_GET['ang'])) {
  $ang = $dec->dec($_GET['ang']);
 // $_SESSION['jadwal_jur'] =$_POST['jur'];
}

?><!-- Content Header (Page header) -->
<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Manage Jalur Masuk
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>jalur-masuk">Jalur Masuk</a></li>
                        <li class="active">Jalur Masuk List</li>
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
                <label for="Nama Kelas" class="control-label col-lg-2">Angkatan</label>
                <div class="col-lg-4">
                  <select name="ang" id="ang" data-placeholder="Pilih Angkatan ..." class="form-control chzn-select" tabindex="2" method="post" >
                   <option value=""></option>
                   <?php foreach ($db->query("SELECT mulai_smt FROM mahasiswa GROUP BY mulai_smt DESC") as $isi3) {
                    if ($ang==$isi3->mulai_smt) {
                     echo "<option value='".$enc->enc($isi3->mulai_smt)."' selected>$isi3->mulai_smt</option>";
                   }else{
                     echo "<option value='".$enc->enc($isi3->mulai_smt)."'>$isi3->mulai_smt</option>";
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
             
                  if (isset($_GET['jur']) || isset($_GET['ang'])) {
                   include 'grafik_jalur.php';
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