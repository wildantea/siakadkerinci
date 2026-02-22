<?php
$jur = "";

if (!isset($_GET['sem'])) {
  $sem2 = get_sem_aktif();
}else{
  $sem2 = $dec->dec($_GET['sem']);
}

?>
<style type="text/css">
  .modal { overflow: auto !important; }
</style>
<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Nilai Perkelas
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>nilai">Nilai Perkelas</a></li>
                        <li class="active">Nilai Perkelas List</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
                            <div class="box-body table-responsive">
                              <form method="GET" action="" class="form-horizontal" id="form_filter" style="position: relative;top: 10px">
         <div class="row">
           <div class="col-md-6">
              <h3 class="text-center">Form Filter Nilai</h3>
           </div>
         </div>
          
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
                                        order by s.tahun desc");
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
           
         <div class="form-group">
          <label for="Nama Kelas" class="control-label col-lg-2"></label>
          <div class="col-lg-10">
            <button class="btn btn-primary " type="submit">Tampilkan Kelas Matakuliah</button>
          </div>
        </div><!-- /.form-group -->
        <br><br>
    </form>
                        <table id="dtb_kelas_jadwal" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                  <th rowspan="2">No</th>
                                  <th rowspan="2">Matakuliah</th>
                                  <th rowspan="2">Kelas</th>
                                  <th rowspan="2">Dosen</th>
                                  <th rowspan="2">Peserta</th>
                                  <th colspan="2" style="text-align: center">Status Dinilai</th>
                                  <th rowspan="2">Prodi</th>
                                  <th rowspan="2">Action</th>
                                </tr>
                                <tr>
                                  <th>Sudah</th>
                                  <th>Belum</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->
                  </div><!-- /.box -->
                </div>
              </div>
        <script type="text/javascript">
    
      var dataTable_jadwal = $("#dtb_kelas_jadwal").DataTable({
           'bProcessing': true,
            'bServerSide': true,
            
           'columnDefs': [ {
            'targets': [0],
              'orderable': false,
              'searchable': false
            },
                {
            'width': '14%',
            'targets': 8,
            'orderable': false,
            'searchable': false,
            'className': 'dt-center'
          }
             ],
            'ajax':{
              url :'<?=base_admin();?>modul/nilai/nilai_dosen_data.php',
            type: 'post',  // method  , by default get
             data: function ( d ) {
              d.sem = $("#sem").val();
             
            },
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });

</script>
            