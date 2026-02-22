<style type="text/css">
.modal.left .modal-dialog,
  .modal.right .modal-dialog {
    top: 0;
    bottom:0;
    position:fixed;
    overflow-y:scroll;
    overflow-x:hidden;
    margin: auto;
    -webkit-transform: translate3d(0%, 0, 0);
        -ms-transform: translate3d(0%, 0, 0);
         -o-transform: translate3d(0%, 0, 0);
            transform: translate3d(0%, 0, 0);
  }
/*Right*/
  .modal.right.fade .modal-dialog {
    right: -320px;
    -webkit-transition: opacity 0.3s linear, right 0.3s ease-out;
       -moz-transition: opacity 0.3s linear, right 0.3s ease-out;
         -o-transition: opacity 0.3s linear, right 0.3s ease-out;
            transition: opacity 0.3s linear, right 0.3s ease-out;
  }
  
  .modal.right.fade.in .modal-dialog {
    right: 0;
  }
</style>
<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Matakuliah
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>matakuliah">Matakuliah</a></li>
                        <li class="active">Matakuliah List</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
                                <div class="box-header">
                                <?php
                                  foreach ($db->fetch_all("sys_menu") as $isi) {
                                      if (uri_segment(1)==$isi->url) {
                                          if ($role_act["insert_act"]=="Y") {
                                      ?>
                                      <a href="<?=base_index();?>matakuliah/tambah" class="btn btn-primary "><i class="fa fa-plus"></i> <?php echo $lang["add_button"];?></a>
                                      <?php
                                          }
                                      }
                                  }
                                ?>
                            </div><!-- /.box-header -->
                            <div class="box-body table-responsive">
                            <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">Filter  </h3>
              </div>
              <div class="box-body">
             <form class="form-horizontal" id="filter_matakuliah" method="post" action=" <?=base_admin();?>/modul/matakuliah/matakuliah_data.php">

          <div class="form-group">
                                <label for="Semester" class="control-label col-lg-2">Fakultas</label>
                                <div class="col-lg-5">
                                <select id="fakultas_filter" name="fakultas" data-placeholder="Pilih Fakultas ..." class="form-control chzn-select" tabindex="2" required="">
                                <?php
                                 loopingFakultas();
                                ?>
                      </select>
                    </div>
                              </div><!-- /.form-group -->
  <div class="form-group">
                          <label for="Semester" class="control-label col-lg-2">Program Studi</label>
                          <div class="col-lg-5">
                          <select id="program_studi_filter" name="program_studi_filter" data-placeholder="Pilih Program Studi ..." class="form-control chzn-select" tabindex="2">
                          <?php
                         looping_prodi();
                          ?>
             </select>

          </div>
                        </div><!-- /.form-group -->
    <div class="form-group">
                          <label for="Semester" class="control-label col-lg-2">Kurikulum</label>
                          <div class="col-lg-5">
                          <select id="kurikulum_filter" name="kurikulum_filter" data-placeholder="Pilih Kurikulum ..." class="form-control chzn-select" tabindex="2">
                          <option value="all">Semua</option>
                          <?php
                          $kode_prodi = aksesProdi('kurikulum.kode_jur');
                          dump($kode_prodi);
                          $kurikulum = $db->query("select * from kurikulum where 1=1 $kode_prodi order by sem_id desc");

                          foreach ($kurikulum as $kur ) {
                            echo "<option value='$kur->kur_id'>$kur->nama_kurikulum</option><br>";
                          }
                          ?>
             </select>

          </div>
                        </div><!-- /.form-group -->
    <div class="form-group">
                          <label for="Semester" class="control-label col-lg-2">Sifat Matakuliah</label>
                          <div class="col-lg-4">
                          <select id="sifat_mk" name="sifat_mk" data-placeholder="Pilih Jenis Matakuliah ..." class="form-control chzn-select" tabindex="4">
                          <option value="all">Semua</option>
                        <?php 
                        $filter_session = getFilter(array('filter_matakuliah' => 'sifat_mk'));
                        $array_wajib = array('Wajib' => '1','Pilihan' => '0');
                        foreach ($array_wajib as $wajib => $val) {

                            if ($val==$filter_session) {
                               echo "<option value='$val' selected>$wajib</option><br>";
                            } else {
                                echo "<option value='$val'>$wajib</option><br>";
                            }
                        }
                             ?>
             </select>

          </div>
                        </div><!-- /.form-group -->
    <div class="form-group">
                          <label for="Semester" class="control-label col-lg-2">Semester</label>
                          <div class="col-lg-3">
                          <select id="semester_filter" name="semester_filter" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2">
                          <option value="all">Semua</option>
                         <?php
                         for ($i=1; $i < 9; $i++) { 
                            echo "<option value='$i'>$i</option><br>";
                         }
                          ?>
             </select>

          </div>
                        </div><!-- /.form-group -->
                       
                        <div class="form-group">
                          <label for="tags" class="control-label col-lg-2">   </label>
                          <div class="col-lg-10">
                            <span id="filter" class="btn btn-primary"><i class="fa fa-refresh">  </i> Filter  </span>
                          </div>
                        </div><!-- /.form-group -->
                      </form>
              </div>
            <!-- /.box-body -->
            </div>

 <div class="alert alert-warning fade in error_data_delete" style="display:none">
          <button type="button" class="close hide_alert_notif">&times;</button>
          <i class="icon fa fa-warning"></i> <span class="isi_warning_delete"></span>
        </div>
        <table id="dtb_matkul" class="table table-bordered table-striped display responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                  <th rowspan="2" style='padding-right:13px;' class='dt-center'>#</th>
                                  <th rowspan="2">Nama Kurikulum</th>
                                  <th rowspan="2">Tahun</th>
                                  <th colspan="4" class="dt-center">Matakuliah</th>
                                  <th colspan="3" class="dt-center">Jumlah</th>
                                  <th rowspan="2">Wajib ?</th>
                                  <th rowspan="2">Program Studi</th>
                                  <th rowspan="2">Action</th>
                                </tr>
                                <tr>
                                  <th>Kode MK</th>
                                  <th>Nama Matakuliah</th>
                                  <th>Jenis</th>
                                  <th><a data-toggle="tooltip" data-title="Semester">S</a></th>
                                  <th><a data-toggle="tooltip" data-title="SKS Total">SKS</a></th>
                                  <th><a data-toggle="tooltip" data-title="Matakuliah Prasyarat">P</a></th>
                                  <th><a data-toggle="tooltip" data-title="Matakuliah Setara">S</a></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->
                  </div><!-- /.box -->
                </div>
              </div>
              <div class="modal right fade" id="modal_input_syarat" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-body" id="isi_input_syarat"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
        <?php

            foreach ($db->fetch_all("sys_menu") as $isi) {

            //jika url = url dari table menu
            if (uri_segment(1)==$isi->url) {
              //check edit permission
              if ($role_act["up_act"]=="Y") {
                $edit = "<a data-id='+data+' href=".base_index()."matakuliah/edit/'+data+' class=\"btn btn-primary btn-sm edit_data \" data-toggle=\"tooltip\" title=\"Edit Matakuliah\"><i class=\"fa fa-pencil\"></i></a>";
              } else {
                  $edit ="";
              }
            if ($role_act['del_act']=='Y') {
                $del = "<button data-id='+data+' data-uri=".base_admin()."modul/matakuliah/matakuliah_action.php".' class="btn btn-danger hapus_dtb_notif btn-sm" data-toggle="tooltip" title="Hapus Matakuliah" data-variable="dtb_matkul"><i class="fa fa-trash"></i></button>';

            } else {
                $del="";
            }
                             }
            }

            $syarat = "<button data-id='+data+'".' class="btn btn-info lihat-matkul btn-sm" data-toggle="tooltip" title="Lihat Prayarat & Setara"><i class="fa fa-list-ol"></i></button>';
        ?>

    </section><!-- /.content -->

        <script type="text/javascript">
      var dtb_matkul = $("#dtb_matkul").DataTable({
           'order' : [],
           'bProcessing': true,
            'bServerSide': true,
            responsive: true,
            
         //disable order dan searching pada tombol aksi use "className":"none" for always responsive hide column
                 "columnDefs": [
              
            {
            "targets": [-1],
              "orderable": false,
              "searchable": false,
              "className": "all",
              "render": function(data, type, full, meta){
                return '<?=$syarat;?> <?=$edit;?> <?=$del;?>';
               }
            },

            {
            "targets": [1,2,3],
              "className": "all"
            },

      
              {
             "targets": [0],
             "width" : "5%",
              "orderable": false,
              "searchable": false,
              "class" : "dt-center"
            }
    
              ],
      
            'ajax':{
              url :'<?=base_admin();?>modul/matakuliah/matakuliah_data.php',
            type: 'post',  // method  , by default get
            data: function ( d ) {
                    <?php
                    if(hasFakultas()) {
                      ?>
                      d.fakultas = $("#fakultas_filter").val();
                      <?php
                    }
                    ?>
                    d.jurusan = $("#program_studi_filter").val();
                    d.kurikulum = $("#kurikulum_filter").val();
                    d.sifat_mk = $("#sifat_mk").val();
                    d.semester = $("#semester_filter").val();
                    d.input_search = $('.dataTables_filter input').val();
                  },
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
          "createdRow": function( row, data, dataIndex ) {
                $(row).attr('id', 'mat-'+data[10]);
          },
          "fnDrawCallback": function(data) {
              if ($("#table-kanan").is(':visible')) {
                console.log('yes');
                $('#dtb_matakuliah tbody #mat-'+$("#add-syarat").attr('data-id')).toggleClass('DTTT_selected selected');
              }
          },


        });

//filter
$('#filter').on('click', function() {
  dtb_matkul.ajax.reload();
});

$("#fakultas_filter").change(function(){
    $.ajax({
    type : "post",
    url : "<?=base_admin();?>modul/get_data_umum/get_prodi.php",
    data : {id_fakultas:this.value},
    success : function(data) {
        $("#program_studi_filter").html(data);
        $("#program_studi_filter").trigger("chosen:updated");

        }
    });
});
$("#program_studi_filter").change(function(){
        $.ajax({
        type : "post",
        url : "<?=base_admin();?>modul/matakuliah/get_kurikulum_filter.php",
        data : {program_studi:this.value},
        success : function(data) {
            $("#kurikulum_filter").html(data);
            $("#kurikulum_filter").trigger("chosen:updated");
        }
    });
});

$(".table").on('click','.lihat-matkul',function(event) {
           //$('#dtb_matkul tbody tr').removeClass('DTTT_selected selected');
            //$(this).parents('tr').toggleClass('DTTT_selected selected');
            var id_matkul = $(this).attr('data-id');
            $("#add-syarat").attr('data-id',id_matkul);
          $.ajax({
              url : "<?=base_admin();?>modul/matakuliah/view_syarat_setara.php",
              type : "POST",
              data : {id_matkul:id_matkul},
              success: function(data) {
                  $("#isi_input_syarat").html(data);
              }
          });
      $('#modal_input_syarat').modal({ keyboard: false,show:true });

    });
</script>
            