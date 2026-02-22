<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Nilai Per Kelas
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>nilai-per-kelas">Nilai Per Kelas</a></li>
                        <li class="active">Nilai Per Kelas List</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
                                <div class="box-header">
                               
                            </div><!-- /.box-header -->
                            <div class="box-body">
<div class="box box-primary">
   <div class="box-header with-border">
              <h3 class="box-title show-filter" data-toggle="tooltip" data-title="Tampilkan/Sembunyikan Filter"><i class='fa fa-minus'></i> Filter</h3>
            </div>
            <div class="box-body filter-body">
           <form class="form-horizontal" id="filter_kelas_form" method="post" action="<?=base_admin();?>modul/nilai_per_kelas/download_data.php" target="_blank">                         
                      <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Periode</label>
                        <div class="col-lg-5">
                        <select id="sem_filter" name="periode" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2" required="">
                        <?php
                                $filter_session_periode= getFilter(array('filter_nilai' => 'periode'));
                                $data = $db2->query("select sem_id from view_dosen_kelas inner join view_prodi_jenjang on kode_jur_kelas=kode_jur
                                where nip='".getUser()->username."' group by sem_id order by sem_id desc");
                                foreach ($data as $dt) {
                                  if ($dt->sem_id==$filter_session_periode) {
                                    echo "<option value='$dt->sem_id' selected>".getPeriode($dt->sem_id)."</option>";
                                  } else {
                                    echo "<option value='$dt->sem_id'>".getPeriode($dt->sem_id)."</option>";
                                  }
                                  
                                }
                          ?>
                            </select>
                          </div>
                      </div><!-- /.form-group -->
                      <!-- /.form-group -->
                      <div class="form-group">
                        <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                        <div class="col-lg-10">
                          <span id="filter" class="btn btn-primary"><i class="fa fa-refresh"></i> Filter</span>
                        </div>
                      </div>
                  
                    </form>
            </div>
            <!-- /.box-body -->
          </div>
 <div class="alert alert-warning fade in error_data_delete" style="display:none">
          <button type="button" class="close hide_alert_notif">&times;</button>
          <i class="icon fa fa-warning"></i> <span class="isi_warning_delete"></span>
        </div>
        <div class="table-responsive">
                        <table id="dtb_nilai_per_kelas" class="table table-bordered table-striped display nowrap" width="100%">
                            <thead>
                                <tr>
                                  <th rowspan="2">Matakuliah</th>
                                  <th rowspan="2">Kelas</th>
                                  <th rowspan="2">Dosen</th>
                                  <th rowspan="2">Peserta</th>
                                  <th colspan="5" class="dt-center">Status Nilai</th>
                                  <th rowspan="2">Program Studi</th>
                                  <th rowspan="2">Action</th>
                                </tr>
                                <tr>
                                  <th>Sudah</th>
                                  <th>Belum</th>
                                  <th>Diumumkan</th>
                                  <th>Dikunci</th>
                                  <th>Komponen</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        </div>
                    </div><!-- /.box-body -->
                  </div><!-- /.box -->
                </div>
              </div>
    </section><!-- /.content -->

        <script type="text/javascript">
      
 

      
      var dtb_nilai_per_kelas = $("#dtb_nilai_per_kelas").DataTable({
        <?php
          if (getFilter(array('filter_nilai' => 'input_search'))!="") {
            ?>
                "search": {
                  "search": "<?=getFilter(array('filter_nilai' => 'input_search'));?>"
                },
            <?php
          }
          ?>   

           'bProcessing': true,
            'bServerSide': true,
            "order": [],
      
            'ajax':{
              url :'<?=base_admin();?>modul/nilai_per_kelas/nilai_per_kelas_data_dosen.php',
            type: 'post',  // method  , by default get
            data: function ( d ) {
                    d.jurusan = $("#jur_filter").val();
                    d.periode = $("#sem_filter").val();
                    d.matakuliah = $("#matkul_filter").val();
                    d.input_search = $('.dataTables_filter input').val();
                  },
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });

     //modal jadwal
$(".table").on('click','.umumkan',function(event) {
    $("#loadnya").show();
     var currentBtn = $(this);
      id = currentBtn.attr('data-id');
      status = currentBtn.attr('data-status');
      var notif = 'Apakah Yakin akan meng-umumkan nilai kelas ini ?';
    $.confirm({
        title: 'Konfirmasi',
        content: notif,
         theme: 'modern',
        buttons: {
            confirm: function () {
            $.ajax({
              url: "<?=base_admin();?>modul/nilai_per_kelas/nilai_per_kelas_action.php?act=publish",
              dataType: "json",
              type : "post",
              data : {id:id,status:status},
              error: function(data ) { 
                  $("#loadnya").hide();
                  console.log(data); 
                  $(".isi_warning").html(data.responseText);
                  $(".error_data").focus()
                  $(".error_data").fadeIn();
              },
              success: function(responseText) {
                $("#loadnya").hide();
                console.log(responseText);
                $.each(responseText, function(index) {
                  console.log(responseText[index].status);
                  if (responseText[index].status=="die") {
                    $("#informasi").modal("show");
                  } else if(responseText[index].status=="good") {
                    dtb_nilai_per_kelas.draw(false);
                  }
                });
              }

              });
            },
            cancel: function () {
              $("#loadnya").hide();
            }
        }
    });

    });

$(".table").on('click','.kunci',function(event) {
    $("#loadnya").show();
     var currentBtn = $(this);
      id = currentBtn.attr('data-id');
      status = currentBtn.attr('data-status');
      var notif = 'Apakah Yakin akan mengunci nilai kelas ini ?';
    $.confirm({
        title: 'Konfirmasi',
        content: notif,
         theme: 'modern',
        buttons: {
            confirm: function () {
            $.ajax({
              url: "<?=base_admin();?>modul/nilai_per_kelas/nilai_per_kelas_action.php?act=kunci",
              dataType: "json",
              type : "post",
              data : {id:id,status:status},
              error: function(data ) { 
                  $("#loadnya").hide();
                  console.log(data); 
                  $(".isi_warning").html(data.responseText);
                  $(".error_data").focus()
                  $(".error_data").fadeIn();
              },
              success: function(responseText) {
                $("#loadnya").hide();
                console.log(responseText);
                $.each(responseText, function(index) {
                  console.log(responseText[index].status);
                  if (responseText[index].status=="die") {
                    $("#informasi").modal("show");
                  } else if(responseText[index].status=="good") {
                    dtb_nilai_per_kelas.draw(false);
                  }
                });
              }

              });
            },
            cancel: function () {
              $("#loadnya").hide();
            }
        }
    });

    });

$("#dtb_nilai_per_kelas_filter").on("click",".reset-button-datatable",function(){
    dtb_nilai_per_kelas
    .search( "" )
    .draw();
  });

//filter
$('#filter').on('click', function() {
  dtb_nilai_per_kelas.ajax.reload();
});

  $("#jur_filter").change(function(){
                        $.ajax({
                        type : "post",
                        url : "<?=base_admin();?>modul/nilai_per_kelas/get_matkul.php",
                        data : {program_studi:this.value,periode:$("#sem_filter").val()},
                        success : function(data) {
                            $("#matkul_filter").html(data);
                            $("#matkul_filter").trigger("chosen:updated");
                        }
      });
    });
$("#fakultas_filter").change(function(){
    $.ajax({
    type : "post",
    url : "<?=base_admin();?>modul/get_data_umum/get_prodi.php",
    data : {id_fakultas:this.value},
    success : function(data) {
        $("#jur_filter").html(data);
        $("#jur_filter").trigger("chosen:updated");

        }
    });
});
</script>
            