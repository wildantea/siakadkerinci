  <div class="row">
        <div class="col-xs-12">
<div class="box-header">
<a id="add_jadwal_ruang" class="btn btn-primary "><i class="fa fa-plus"></i> Tambah Jadwal Seminar</a>
                            </div><!-- /.box-header -->
<div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">Filter</h3>
            </div>
            <div class="box-body">
           <form class="form-horizontal" id="filter_kelas_form" method="post">
          <div class="form-group">
                                <label for="Semester" class="control-label col-lg-2">Program Studi</label>
                                <div class="col-lg-4">
                                <select id="jur_filter_jadwal" name="jur_filter_jadwal" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2" required="">
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
                     <input type="hidden" name="sem_filter_jadwal" id="sem_filter_jadwal" value="<?=$id_semester_aktif;?>">

                             </div>
                              </div><!-- /.form-group -->
                           <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Periode Bulan</label>
                        <div class="col-lg-5">
                        <select id="periode_filter_jadwal" name="periode_filter_jadwal" data-placeholder="Pilih Periode ..." class="form-control chzn-select" tabindex="2">
                          <?php
                        //03 is kode proposal in tb_jenis_pendaftaran
                         looping_periode_pendaftaran('03')
                         ?>
                        </select>
                      </div>
                      </div><!-- /.form-group -->
    <input type="hidden" name="id_pendaftaran_jadwal" id="id_pendaftaran_jadwal" value="03">
                      <div class="form-group">
                        <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                        <div class="col-lg-10">
                          <span id="filter_jadwal" class="btn btn-primary"><i class="fa fa-refresh"></i> Filter</span>
                        
                </div>
                      </div><!-- /.form-group -->
                    </form>
            </div>
            <!-- /.box-body -->
          </div>

            <table id="dtb_jadwal_ruang" class="table table-bordered table-striped">
        <thead>
            <tr>
              <th>Periode</th>
              <th>Ruangan</th>
              <th>Tanggal Seminar</th>
              <th>Program Studi</th>
              <th>#</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
        </div>
      </div>
    <div class="modal" id="modal_jadwal_ruang" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> <h4 class="modal-title"><?php echo $lang["add_button"];?> Jadwal Seminar</h4> </div> <div class="modal-body" id="isi_jadwal_ruang"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
  <?php

            foreach ($db->fetch_all("sys_menu") as $isi) {

            //jika url = url dari table menu
            if (uri_segment(1)==$isi->url) {
              //check edit permission
              if ($role_act["up_act"]=="Y") {
                $edit_jadwal = "<a class=\"btn btn-primary btn-sm edit_ruang\" data-id='+aData+' data-toggle=\"tooltip\" title=\"Edit Jadwal\"><i class=\"fa fa-pencil\"></i></a>";

              } else {
                $edit_jadwal = "";
              }
            if ($role_act['del_act']=='Y') {
                $del_jadwal = "<a data-id='+aData+'  data-uri=".base_admin()."modul/pendaftaran_proposal/jadwal_seminar_action.php".' data-variable="dataTable_jadwal" class="btn btn-danger btn-sm hapus_dtb_notif" data-toggle="tooltip" title="Hapus"><i class="fa fa-trash"></i></a>';
            } else {
                $del_jadwal="";
            }
                             }
            }

        ?>
<script type="text/javascript">
        var dataTable_jadwal = $("#dtb_jadwal_ruang").DataTable({
           'bProcessing': true,
            'bServerSide': true,
            
           'columnDefs': [ {
            'targets': [4],
              'orderable': false,
              'searchable': false
            },
           {
            'targets': [4],
              'orderable': false,
              'searchable': false,
              'className': 'all dt-center',
              "render": function(aData, type, full, meta){
                return '<?=$edit_jadwal;?> <?=$del_jadwal;?>';
               }
            },
             ],

    
            'ajax':{
              url :'<?=base_admin();?>modul/pendaftaran_proposal/jadwal_seminar_data.php',
            type: 'post',  // method  , by default get
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });

$('#filter_jadwal').on('click', function() {
 var dataTable_jadwal = $("#dtb_jadwal_ruang").DataTable({
           destroy : true,
           'bProcessing': true,
            'bServerSide': true,
            
           'columnDefs': [ {
            'targets': [4],
              'orderable': false,
              'searchable': false
            },
           {
            'targets': [4],
              'orderable': false,
              'searchable': false,
              'className': 'all dt-center',
              "render": function(aData, type, full, meta){
                return '<?=$edit_jadwal;?> <?=$del_jadwal;?>';
               }
            },
             ],

    
            'ajax':{
              url :'<?=base_admin();?>modul/pendaftaran_proposal/jadwal_seminar_data.php',
            type: 'post',  // method  , by default get
            data: function ( d ) {
                    d.jur_filter = $("#jur_filter_jadwal").val();
                    d.sem_filter = $("#sem_filter_jadwal").val();
                    d.periode_bulan = $("#periode_filter_jadwal").val();
                  },
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });
});
      $("#add_jadwal_ruang").click(function() {
        
          $.ajax({
              url : "<?=base_admin();?>modul/pendaftaran_proposal/jadwal_seminar_add.php",
              type : "GET",
              success: function(data) {
                  $("#isi_jadwal_ruang").html(data);
                 
              }
          });

      $('#modal_jadwal_ruang').modal({ keyboard: false,backdrop:'static',show:true });

    });

    $(".table").on('click','.edit_ruang',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);

        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/pendaftaran_proposal/jadwal_seminar_edit.php",
            type : "post",
            data : {id_data:id},
            success: function(data) {
                $("#isi_jadwal_ruang").html(data);
                $("#loadnya").hide();
          }
        });

    $('#modal_jadwal_ruang').modal({ keyboard: false,backdrop:'static' });

    });
    $("#jur_filter_jadwal").change(function(){
          $.ajax({
          type : "post",
          url : "<?=base_admin();?>modul/pendaftaran_proposal/get_periode.php",
          data : {id_pendaftaran:$('#id_pendaftaran_jadwal').val(),prodi:this.value,semester:$('#sem_filter_jadwal').val()},
          success : function(data) {
              $("#periode_filter_jadwal").html(data);
              $("#periode_filter_jadwal").trigger("chosen:updated");

          }
      });

    });
</script>