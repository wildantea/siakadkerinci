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
                                <select id="jur_filter_penguji" name="jur_filter_penguji" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2" required="">
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
                     <input type="hidden" name="sem_filter_penguji" id="sem_filter_penguji" value="<?=$id_semester_aktif;?>">

                             </div>
                              </div><!-- /.form-group -->
    <input type="hidden" name="id_pendaftaran_penguji" id="id_pendaftaran_penguji" value="03">
                      <div class="form-group">
                        <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                        <div class="col-lg-10">
                          <span id="filter_penguji" class="btn btn-primary"><i class="fa fa-refresh"></i> Filter</span>
                        
                </div>
                      </div><!-- /.form-group -->
                    </form>
            </div>
            <!-- /.box-body -->
          </div>

            <table id="dtb_penguji" class="table table-bordered table-striped">
        <thead>
            <tr>
              <th>NIM</th>
              <th>Nama</th>
              <th>Hari/Tanggal Seminar</th>
              <th>Penguji</th>
              <th>Program Studi</th>
              <th>#</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
        </div>
      </div>
    <div class="modal" id="modal_penguji" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> <h4 class="modal-title"><?php echo $lang["add_button"];?> Jadwal Seminar</h4> </div> <div class="modal-body" id="isi_penguji"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
  
<script type="text/javascript">
        var dataTable_penguji = $("#dtb_penguji").DataTable({
           'bProcessing': true,
            'bServerSide': true,
            
           'columnDefs': [ {
            'targets': [-1],
              'orderable': false,
              'searchable': false
            },

             ],

    
            'ajax':{
              url :'<?=base_admin();?>modul/pendaftaran_proposal/penguji_data.php',
            type: 'post',  // method  , by default get
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });

$('#filter_penguji').on('click', function() {
 var dataTable_penguji = $("#dtb_penguji").DataTable({
           destroy : true,
           'bProcessing': true,
            'bServerSide': true,
            
           'columnDefs': [ {
            'targets': [-1],
              'orderable': false,
              'searchable': false
            },

             ],

    
            'ajax':{
              url :'<?=base_admin();?>modul/pendaftaran_proposal/penguji_data.php',
            type: 'post',  // method  , by default get
            data: function ( d ) {
                    d.jur_filter = $("#jur_filter_penguji").val();
                    d.sem_filter = $("#sem_filter_penguji").val();
                  },
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });
});
      $("#add_penguji").click(function() {
        
          $.ajax({
              url : "<?=base_admin();?>modul/pendaftaran_proposal/jadwal_seminar_add.php",
              type : "GET",
              success: function(data) {
                  $("#isi_penguji").html(data);
                 
              }
          });

      $('#modal_penguji').modal({ keyboard: false,backdrop:'static',show:true });

    });

    $(".table").on('click','.edit_penguji',function(event) {
        //$("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);

        id = currentBtn.attr('data-id');
        nim = currentBtn.attr('data-nim');

        $.ajax({
            url : "<?=base_admin();?>modul/pendaftaran_proposal/penguji_edit.php",
            type : "post",
            data : {nim:nim,id_pendaftar:id},
            success: function(data) {
                $("#isi_penguji").html(data);
                //$("#loadnya").hide();
          }
        });

    $('#modal_penguji').modal({ keyboard: false,backdrop:'static' });

    });
    $("#jur_filter_penguji").change(function(){
          $.ajax({
          type : "post",
          url : "<?=base_admin();?>modul/pendaftaran_proposal/get_periode.php",
          data : {id_pendaftaran:$('#id_pendaftaran_penguji').val(),prodi:this.value,semester:$('#sem_filter_penguji').val()},
          success : function(data) {
              $("#periode_filter_penguji").html(data);
              $("#periode_filter_penguji").trigger("chosen:updated");

          }
      });

    });
</script>