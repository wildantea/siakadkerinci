<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Manage Hasil Studi Mahasiswa
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>hasil-studi-mahasiswa">Hasil Studi Mahasiswa</a></li>
                        <li class="active">Hasil Studi Mahasiswa List</li>
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
              <h3 class="text-center">Form Cari Mahasiswa</h3>
           </div>
         </div>
                <form id="form_cari_mahasiswa" method="GET" class="form-horizontal" action="">

                <?php
                if ($_SESSION['level']=='6') {
                  $where = "where j.fak_kode='".$_SESSION['id_fak']."' ";
                 $pilih_no_jur = "<option value=''>-All Program Studi-</option>";
                }

                $kode_jur = isset($_GET['jur']) ? de($_GET['jur']) : "";
                $nim      = isset($_GET['nim']) ? clean($_GET['nim']) : "";
                $nama     = isset($_GET['nama']) ? clean($_GET['nama']) : "";
                ?>
                     <div class="form-group">
                    <label for="nm_agama" class="control-label col-lg-2">Program Studi </label>
                    <div class="col-lg-4">
                      <select name="jur" id="jur" class="form-control" tabindex="2" required="required">
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
                    <label for="nim" class="control-label col-lg-2">NIM </label>
                    <div class="col-lg-4">
                      <input type="text" name="nim" value="<?= isset($_GET['nim']) ? $nim: "" ?>" placeholder="NIM Mahasiswa" class="form-control">
                    </div>
                  </div><!-- /.form-group -->
                   <div class="form-group">
                    <label for="nama" class="control-label col-lg-2">Nama Mahsiswa</label>
                    <div class="col-lg-4">
                      <input type="text" value="<?= isset($_GET['nama']) ? $nama: "" ?>" name="nama" placeholder="Nama Mahasiswa" class="form-control" >
                    </div>
                  </div><!-- /.form-group -->


                  <div class="form-group">
                    <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                    <div class="col-lg-4">
                     <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Cari Mahasiswa</button>


                    </div>
                  </div><!-- /.form-group -->

                </form>

                <?php

                  if (isset($_GET['jur']) || isset($_GET['nama']) || isset($_GET['nim'])) {
                   include 'table_hasil_studi.php';
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
            url: '<?=base_admin();?>modul/hasil_studi_mahasiswa/hasil_studi_mahasiswa_action.php?act=cari_mhs',
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