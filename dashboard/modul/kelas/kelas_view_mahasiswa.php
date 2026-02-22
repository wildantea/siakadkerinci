<style type="text/css">
    .modal { overflow: auto !important; }
</style>
<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Jadwal Kuliah
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>jadwal-kuliah">Jadwal Kuliah</a></li>
                        <li class="active">Jadwal Kuliah List</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
                                <div class="box-header">
<div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-info"></i> Keterangan</h4>
                Halaman ini berisi daftar dari kelas dan jadwal yang anda ambil, Klik pada nama Kelas untuk melihat detail dari Kelas. 
              </div>
                               
                            </div><!-- /.box-header -->
                            <div class="box-body">
<div class="box box-primary">
   <div class="box-header with-border">
              <h3 class="box-title show-filter" data-toggle="tooltip" data-title="Tampilkan/Sembunyikan Filter"><i class='fa fa-minus'></i> Filter</h3>
            </div>
            <div class="box-body filter-body">
           <form class="form-horizontal" id="filter_kelas_form" method="post" action="<?=base_admin();?>modul/kelas/mahasiswa/cetak.php" target="_blank">
                      <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Periode</label>
                        <div class="col-lg-5">
                        <select id="sem_filter" name="periode" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2" required="">
                          <?php 
                          $periodes = $db->query("select id_semester from krs_detail where nim=? group by id_semester order by id_semester desc",array('nim' => getUser()->username));
                          foreach ($periodes as $periode) {
                            if ($periode->id_semester==getSemesterAktif()) {
                               echo "<option value='".$periode->id_semester."' selected>".getPeriode($periode->id_semester)."</option>";
                            } else {
                               echo "<option value='".$periode->id_semester."'>".getPeriode($periode->id_semester)."</option>";
                            }
                           
                          }
                          
                         // loopingSemester('filter_bimbingan_akademis');
                          ?>
                            </select>
                          </div>
                      </div><!-- /.form-group -->
                      <input type="hidden" name="nim" value="<?=$_SESSION['username'];?>">
                      <!-- /.form-group -->
                      <div class="form-group">
                        <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                        <div class="col-lg-10">
                          <span id="filter" class="btn btn-primary"><i class="fa fa-refresh"></i> Filter</span>
                  <button type="submit" class="btn btn-info"><i class="fa fa-print"></i> Cetak Data</button>
                      </div><!-- /.form-group -->
                    </form>
            </div>
            <!-- /.box-body -->
          </div>

 <div class="alert alert-warning fade in error_data_delete" style="display:none">
          <button type="button" class="close hide_alert_notif">&times;</button>
          <i class="icon fa fa-warning"></i> <span class="isi_warning_delete"></span>
        </div>
                        <table id="dtb_kelas_jadwal" class="table table-bordered table-striped display responsive nowrap" width="100%">
                            <thead>
                                <tr>
                                <th>No</th>
                                  <th>Matakuliah</th>
                                  <th>Kelas</th>
                                  <th>Ruang</th>
                                  <th>Hari</th>
                                  <th>Jam</th>
                                  <th>Dosen Pengajar</th>
                                  <th>Prodi Kelas</th>
                                  <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->
                  </div><!-- /.box -->
                </div>
              </div>
<!-- modal jadwal -->
<div class="modal fade" id="modal_kelas_jadwal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="btn btn-default" style="float:right" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="glyphicon glyphicon-remove"></i></span></button> <h4 class="modal-title">Pengaturan Dosen Pengajar & Jadwal Perkuliahan</h4> </div> <div class="modal-body" id="isi_kelas_jadwal"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
<!-- modal peserta -->
<div class="modal fade" id="modal_peserta_kelas" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg modal-abs"> <div class="modal-content"><div class="modal-header"> <button type="button" class="btn btn-danger" data-toggle="tooltip" data-title="Tutup" data-placement="left" style="float:right" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true"><i class="glyphicon glyphicon-remove"></i></span></button> <h4 class="modal-title">Peserta Kelas</h4> </div> <div class="modal-body" id="isi_peserta_kelas"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>

<!-- modal cetak single -->
  <div class="modal" id="modal_cetak_single" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="glyphicon glyphicon-remove"></i></span></button> <h4 class="modal-title">Silakan Pilih Jenis Yang Akan Dicetak</h4> </div> <div class="modal-body" id="isi_modal_cetak"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>

  <div class="modal" id="modal_input_absen" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog"> <div class="modal-content" style="height: auto;"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="glyphicon glyphicon-remove"></i></span></button> <h4 class="modal-title-absen">Isi Presensi Mahasiswa</h4> </div> <div class="modal-body" id="input_mahasiswa_absen" style="overflow-y: auto;"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
  <div class="modal" id="modal_photo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="glyphicon glyphicon-remove"></i></span></button> <h4 class="modal-title">Thumbnail</h4> </div> <div class="modal-body" id="isi_photo" style="text-align: center;"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
   
 <?php
  $edit ="";
  $del="";
 if ($db->userCan("update")) {
    $edit = "<a data-id='+data+' href=".base_index()."kelas-jadwal/edit/'+data+' data-toggle=\"tooltip\" title=\"Edit Kelas\"><i class=\"fa fa-pencil\"></i> Edit Kelas</a>";
    $jadwal = "<a data-id='+data+' data-toggle=\"tooltip\" title=\"Pengaturan Jadwal\" class=\"edit-jadwal\"><i class=\"fa fa-calendar\"></i> Pengaturan Jadwal</a>";
    $print = "<a data-id='+data+' data-toggle=\"tooltip\" title=\"Cetak\" class=\"cetak\"><i class=\"fa fa-print\"></i> Cetak Data</a>";
 }
        ?>

    </section><!-- /.content -->

        <script type="text/javascript">

$(document).ready(function(){
  $('.show-filter').click(function(){
      if ($(".filter-body").is(':visible')) {
        $(this).find('.fa').toggleClass('fa-minus fa-plus');
        $(".filter-body").slideUp();
      } else {
        $(this).find('.fa').toggleClass('fa-plus fa-minus');
        $(".filter-body").slideDown();
      }
  });
});
      
        var dtb_kelas_jadwal = $("#dtb_kelas_jadwal").DataTable({
          <?php
          if (getFilter(array('filter_kelas_dosen' => 'input_search'))!="") {
            ?>
                "search": {
                  "search": "<?=getFilter(array('filter_kelas' => 'input_search'));?>"
                },
            <?php
          }
          ?>              

           'bProcessing': true,
            'bServerSide': true,
             "order": [],
            
         //disable order dan searching pada tombol aksi use "className":"none" for always responsive hide column
                 "columnDefs": [
                            {
             "targets": [0],
             "width" : "5%",
              "orderable": false,
              "searchable": false,
              "class" : "dt-center"
            }
              
             ],
      
            'ajax':{
              url :'<?=base_admin();?>modul/kelas/mahasiswa/jadwal_kuliah_data.php',
            type: 'post',  // method  , by default get
            data: function ( d ) {
                    d.periode = $("#sem_filter").val();
                    d.input_search = $('.dataTables_filter input').val();
                  },
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });
//filter
$('#filter').on('click', function() {
  dtb_kelas_jadwal.ajax.reload();
});

$("#dtb_kelas_jadwal_filter").on('click','.reset-button-datatable',function(){
    dtb_kelas_jadwal
    .search( '' )
    .draw();
  });

  $("#sem_filter").change(function(){
                        $.ajax({
                        type : "post",
                        url : "<?=base_admin();?>modul/jadwal_kuliah/get_kurikulum_filter.php",
                        data : {periode:this.value,program_studi:$("#jur_filter").val()},
                        success : function(data) {
                            $("#kurikulum_filter").html(data);
                            $("#kurikulum_filter").trigger("chosen:updated");
                            $("#matkul_filter").html('<option value="all">Semua</option>');
                            $("#matkul_filter").trigger("chosen:updated");
                        }
      });

    });

//modal peserta kelas
$(".table").on('click','.peserta-kelas',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);

        id = currentBtn.attr('data-id');
        id_jadwal = currentBtn.attr('data-jadwal');

        $.ajax({
            url : "<?=base_admin();?>modul/kelas/mahasiswa/modal_view.php",
            type : "post",
            data : {kelas_id:id,id_jadwal:id_jadwal},
            success: function(data) {
                $("#isi_peserta_kelas").html(data);
                $("#loadnya").hide();
          }
        });

    $('#modal_peserta_kelas').modal({ keyboard: false,backdrop:'static' });

    });
//modal cetak single
    $(".table").on('click','.cetak',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);

        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/kelas_jadwal/cetak/cetak_modal_single.php",
            type : "post",
            data : {kelas_id:id},
            success: function(data) {
                $("#isi_modal_cetak").html(data);
                $("#loadnya").hide();
          }
        });
    $('#modal_cetak_single').modal({ keyboard: false,backdrop:'static' });
    });

$(".bulk-check").on('click',function() { // bulk checked
          var status = this.checked;
          if (status) {
            select_deselect('select');
          } else {
            select_deselect('unselect');
          }
          
          $(".check-selected").each( function() {
            $(this).prop("checked",status);
          });
        });

  function init_selected() {
      var selected = check_selected();
      var btn_hide = $('#aksi_top_krs');
      if (selected.length > 0) {
          btn_hide.show()
      } else {
          btn_hide.hide()
      }
  }

    function check_selected() {
      var table_select = $('#dtb_krs tbody tr.selected');
      var array_data_delete = [];
      table_select.each(function() {
          var check_data = $(this).find('.data_selected_id').attr('data-id');
          if (typeof check_data != 'undefined') {
              array_data_delete.push(check_data)
          }
      });
      $('.selected-data').text(array_data_delete.length + ' Data Terpilih');
      return array_data_delete
  }

  function select_deselect(type) {
      if (type == 'select') {
          $('#dtb_krs tbody tr').addClass('DTTT_selected selected')
      } else {
          $('#dtb_krs tbody tr').removeClass('DTTT_selected selected')
      }
    init_selected()
  }
  $(document).on('click', '#dtb_krs tbody tr .check-selected', function(event) {
      var btn = $(this).find('button');
      if (btn.length == 0) {
          $(this).parents('tr').toggleClass('DTTT_selected selected');
          var selected = check_selected();
          console.log(selected);
          init_selected();

      }
  });

/* Add a click handler for the delete row */
  $('.submit-proses').click( function() {
    $("#loadnya").show();
    //var anSelected = fnGetSelected( dataTable_jadwal );
    var data_array_id = check_selected();
    var all_ids = data_array_id.toString();
    console.log(all_ids);
        $.ajax({
            type: 'POST',
            url: '<?=base_admin();?>modul/rencana_studi/rencana_studi_action.php?act=proses_krs',
            data: {aksi:$("#aksi_krs").val(), data_ids:all_ids},
            success: function(result) {
               $('#loadnya').hide();
                  $(".bulk-check").prop("checked",0);
                  select_deselect('unselect');
                  dataTable_jadwal.draw(false);
            },
            //async:false
        });

  });

</script>
            