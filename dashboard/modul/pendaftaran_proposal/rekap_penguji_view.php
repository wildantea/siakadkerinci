  <div class="row">
        <div class="col-xs-12">
<div class="box" style="border-top:none">
            <div class="box-header with-border">
              <h3 class="box-title">Filter</h3>
            </div>
            <div class="box-body" >
           <form class="form-horizontal" id="filter_kelas_form" method="post">
          <div class="form-group">
                                <label for="Semester" class="control-label col-lg-2">Program Studi</label>
                                <div class="col-lg-4">
                                <select id="jur_filter_rekap" name="jur_filter_rekap" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2" required="">
                                <?php
                                looping_prodi();
                                ?>
                      </select>
                        </div>
                      <div class="col-lg-3">
                                  <?php
                                  $id_semester_aktif = get_sem_aktif();
                                  $semester = get_tahun_akademik($id_semester_aktif);
                                  ?>
                      <input type="text" class="form-control" value="<?=$semester;?>" readonly>
                     <input type="hidden" name="sem_filter_rekap" id="sem_filter_rekap" value="<?=$id_semester_aktif;?>">

                             </div>
                              </div><!-- /.form-group -->
                           <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Periode Bulan</label>
                        <div class="col-lg-5">
                        <select id="periode_filter_rekap" name="periode_filter_rekap" data-placeholder="Pilih Periode ..." class="form-control chzn-select" tabindex="2">
                          <?php
                        //03 is kode proposal in tb_jenis_pendaftaran
                         looping_periode_pendaftaran('03')
                         ?>
                        </select>
                      </div>
                      </div><!-- /.form-group -->
    <input type="hidden" name="id_pendaftaran_rekap" id="id_pendaftaran_rekap" value="03">
                      <div class="form-group">
                        <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                        <div class="col-lg-10">
                          <span id="filter_rekap" class="btn btn-primary"><i class="fa fa-refresh"></i> Filter</span>
                        
                </div>
                      </div><!-- /.form-group -->
                    </form>
            </div>
            <!-- /.box-body -->
          </div>

            <table id="dtb_rekap" class="table table-bordered table-striped">
        <thead>
            <tr>
              <th>Nama Dosen</th>
              <th>Jumlah Menguji</th>
              <th>Hari/Tanggal Menguji</th>
              <th>Program Studi</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
        </div>
      </div>
  
<script type="text/javascript">
        var dataTable_rekap = $("#dtb_rekap").DataTable({
           'bProcessing': true,
            'bServerSide': true,

    
            'ajax':{
              url :'<?=base_admin();?>modul/pendaftaran_proposal/rekap_penguji_data.php',
            type: 'post',  // method  , by default get
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });

$('#filter_rekap').on('click', function() {
 var dataTable_rekap = $("#dtb_rekap").DataTable({
           destroy : true,
           'bProcessing': true,
            'bServerSide': true,
    
            'ajax':{
              url :'<?=base_admin();?>modul/pendaftaran_proposal/rekap_penguji_data.php',
            type: 'post',  // method  , by default get
            data: function ( d ) {
                    d.jur_filter = $("#jur_filter_rekap").val();
                    d.sem_filter = $("#sem_filter_rekap").val();
                    d.periode_bulan = $("#periode_filter_rekap").val();
                  },
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });
});

    $("#jur_filter_rekap").change(function(){
          $.ajax({
          type : "post",
          url : "<?=base_admin();?>modul/pendaftaran_proposal/get_periode.php",
          data : {id_pendaftaran:$('#id_pendaftaran_rekap').val(),prodi:this.value,semester:$('#sem_filter_rekap').val()},
          success : function(data) {
              $("#periode_filter_rekap").html(data);
              $("#periode_filter_rekap").trigger("chosen:updated");

          }
      });

    });
</script>