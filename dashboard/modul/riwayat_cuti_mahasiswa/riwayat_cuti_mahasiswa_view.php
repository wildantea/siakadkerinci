<?php
$jur = "";
$fak = "";
if (isset($_GET['fakultas_filter'])) {
  $fak = $dec->dec($_GET['fakultas_filter']);
 // $_SESSION['jadwal_jur'] =$_POST['jur'];
}
if (isset($_GET['jurusan_filter'])) {
  $jur = $dec->dec($_GET['jurusan_filter']);
 // $_SESSION['jadwal_sem'] = $_POST['sem'];
}
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Manage Riwayat Cuti Mahasiswa
    </h1>
        <ol class="breadcrumb">
        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?=base_index();?>riwayat-cuti-mahasiswa">Riwayat Cuti Mahasiswa</a></li>
        <li class="active">List Riwayat Cuti Mahasiswa</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
        </div><!-- /.box-header -->
          <form id="form_filter" class="form-horizontal" method="GET" action="">
            <div class="form-group">
              <label for="Fakultas" class="control-label col-lg-2">Fakultas</label>
              <div class="col-lg-4">
                <select id="fakultas_filter" name="fakultas_filter" data-placeholder="Pilih Angkatan ..." class="form-control chzn-select" tabindex="2">
                   <option value="all">Semua</option>
                   <?php
                   foreach ($db->fetch_all("fakultas") as $isi) {
                      if ($isi->kode_fak == $fak) {
                        echo "<option value='".$enc->enc($isi->kode_fak)."' selected>$isi->nama_resmi</option>";
                      } else{
                        echo "<option value='".$enc->enc($isi->kode_fak)."'>$isi->nama_resmi</option>"; 
                      }
                   } 
                   ?>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label for="Jurusan" class="control-label col-lg-2">Jurusan</label>
              <div class="col-lg-4">
                <select id="jurusan_filter" name="jurusan_filter" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2">
                  <option value="all">Semua</option>
                  <?php
                   foreach ($db->fetch_all("jurusan") as $isi) {
                      if ($isi->kode_jur == $jur) {
                        echo "<option value='".$enc->enc($isi->kode_jur)."' selected>$isi->nama_jur</option>";
                      }
                   } 
                  ?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <dir class="col-lg-4 col-lg-offset-2">
                <button id="filter" type="submit" class="btn btn-primary">
                  <i class="fa fa-refresh"></i> Tampilkan Mahasiswa
                </button>
              </dir>
            </div>
          </form>

          <div id="hasil"></div>
          <?php
            if (isset($_GET['jurusan_filter'])) {
              $jurusan=$dec->dec($_GET['jurusan_filter']);
              $fakultas=$dec->dec($_GET['fakultas_filter']);
              include 'table_riwayat.php';
            }
          ?>
      </div><!-- /.box -->
    </div>
  </div>

  <div class="modal" id="modal_priode" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> <h4 class="modal-title"><?php echo $lang["add_button"];?> Priode Wisuda</h4> </div> <div class="modal-body" id="isi_priode"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
    
</section><!-- /.content -->

<script type="text/javascript">
      
  $("#add_periode_wisuda").click(function() {
        $.ajax({
            url : "<?=base_admin();?>modul/pengelolahan_sesi_wisuda/add_priode_wisuda.php",
            type : "GET",
            success: function(data) {
                $("#isi_priode").html(data);
            }
        });

    $('#modal_priode').modal({ keyboard: false,backdrop:'static',show:true });

  });
  
    
  $(".table").on('click','.edit_data',function(event) {
      $("#loadnya").show();
      event.preventDefault();
      var currentBtn = $(this);

      id = currentBtn.attr('data-id');

      $.ajax({
          url : "<?=base_admin();?>modul/pengelolahan_sesi_wisuda/pengelolahan_sesi_wisuda_edit.php",
          type : "post",
          data : {id_data:id},
          success: function(data) {
              $("#isi_wisuda").html(data);
              $("#loadnya").hide();
        }
      });

    $('#modal_wisuda').modal({ keyboard: false,backdrop:'static' });

  });

  $("#fakultas_filter").change(function(){

        $.ajax({
        type : "post",
        url : "<?=base_admin();?>modul/riwayat_cuti_mahasiswa/get_jurusan_filter.php",
        data : {fakultas:this.value},
        success : function(data) {
            $("#jurusan_filter").html(data);
            $("#jurusan_filter").trigger("chosen:updated");

        }
    });

  });

  $("#form_filter").submit(function(){
      $('#loadnya').show();
        $.ajax({
            type: 'POST',
            url: '<?=base_admin();?>modul/riwayat_cuti_mahasiswa/table_riwayat.php',
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
            