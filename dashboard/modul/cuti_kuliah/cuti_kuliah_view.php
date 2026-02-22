<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Cuti Kuliah
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>cuti-kuliah">Cuti Kuliah</a></li>
                        <li class="active">Cuti Kuliah List</li>
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
                                      <a id="add_cuti_kuliah" class="btn btn-primary "><i class="fa fa-plus"></i> <?php echo $lang["add_button"];?></a>
                                       <a id="btn_generate_cuti" onclick="generate_cuti()" class="btn btn-primary "><i class="fa fa-gear"></i> Generate Cuti</a>
                                      <?php
                                          }
                                      }
                                  }
                                ?>
                            </div><!-- /.box-header -->
                            <div class="box-body">
<div class="box box-primary">

            <div class="box-header with-border">
              <h3 class="box-title">Filter</h3>
            </div>
            <div class="box-body">
           <form class="form-horizontal" id="filter_kelas_form" method="post" action="<?=base_admin();?>modul/cuti_kuliah/download_data.php" target="_blank">
          <div class="form-group">
                                <label for="Semester" class="control-label col-lg-2">Program Studi</label>
                                <div class="col-lg-5">
                                <select id="jur_filter" name="jur_filter" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2" required="">
                                <?php
                                looping_prodi();
                                ?>
                      </select>

         </div>
                              </div><!-- /.form-group -->

                           <div class="form-group">
                        <label for="Angkatan" class="control-label col-lg-2">Angkatan Mahasiswa</label>
                        <div class="col-lg-5">
                        <select id="angkatan_filter" name="angkatan_filter" data-placeholder="Pilih Angkatan ..." class="form-control chzn-select" tabindex="2">
                          <option value="all" selected>Semua</option>
                          <?php
                          $angkatan = $db->query("select mulai_smt from mahasiswa inner join tb_data_cuti_mahasiswa using(nim) group by mulai_smt order by mulai_smt desc");
                          foreach ($angkatan as $ak) {
                            echo "<option value='$ak->mulai_smt'>".getAngkatan($ak->mulai_smt)."</option>";
                          }
                          ?>
                        </select>
                      </div>
                      </div><!-- /.form-group -->

                      <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Periode Pengajuan</label>
                        <div class="col-lg-5">
                        <select id="sem_filter" name="sem_filter" data-placeholder="Pilih Periode ..." class="form-control chzn-select" tabindex="2" required="">
                          <option value="all">Semua</option>
                        <?php 
                        $semester_data = $db->query("select periode from tb_data_cuti_mahasiswa_periode group by periode order by periode desc");
                        foreach ($semester_data as $data) {
                          echo "<option value='$data->periode'>".getTahunakademik($data->periode)."</option>";
                        }
                        ?>
                        </select>
                        </div>
                      </div><!-- /.form-group -->

                           <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Status Disetujui</label>
                        <div class="col-lg-3">
                        <select id="disetujui" name="disetujui" data-placeholder="Pilih Status ..." class="form-control" tabindex="2">
                          <option value="all">Semua</option>
                          <option value="waiting">Menunggu Persetujuan</option>
                          <option value="approved">Sudah Disetujui</option>
                          <option value="approved">Ditolak</option>
                        </select>
                      </div>
                    </div><!-- /.form-group -->
                      <div class="form-group">
                        <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                        <div class="col-lg-10">
                          <span id="filter" class="btn btn-primary"><i class="fa fa-refresh"></i> Filter</span>
                          
                          <?php
                            if ($role_act["import_act"]=="Y") {
                            ?>
                             <button type="submit" class="btn btn-success"><i class="fa fa-cloud-download"></i> Download</button>
                              
                              <?php
                            }
                         ?>

                      </div><!-- /.form-group -->
                    </form>
            </div>
            <!-- /.box-body -->
          </div>
 <div class="alert alert-warning fade in error_data_delete" style="display:none">
          <button type="button" class="close hide_alert_notif">&times;</button>
          <i class="icon fa fa-warning"></i> <span class="isi_warning_delete"></span>
        </div>
<div class="row" id="aksi_top_krs" style="display: none">
    <div class="col-sm-4" style="margin-bottom: 10px;">
      <div class="input-group input-group-sm">
          <span class="input-group-btn">
            <button type="button" class="btn btn-danger btn-flat selected-data">Terpilih</button>
          </span>
       <select class="form-control col-lg-3" name="aksi_krs" id="aksi_krs">
      <option value="delete" data-aksi="del_massal">Hapus Cuti</option>

    </select>
          <span class="input-group-btn">
            <button type="button" class="btn btn-success btn-flat submit-proses">Submit</button>
          </span>
    </div>
    </div>
</div>
                        <table id="dtb_cuti_kuliah" class="table table-bordered table-striped responsive" width="100%">
                            <thead>
                                <tr>
                                   <th style="padding-right:7px;width: 3%"><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline "> <input type="checkbox" class="group-checkable bulk-check"> <span></span></label></th>
                                  <th>Nim/Nama</th>
                                  <th>Tgl Ajuan</th>
                                  <th>Periode Ajuan</th>
                                  <th>Status</th>
                                  <th>Tgl Acc/ditolak</th>
                                  <th>No Surat</th>
                                  <th>Prodi</th>
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
        <?php

            foreach ($db->fetch_all("sys_menu") as $isi) {

            //jika url = url dari table menu
            if (uri_segment(1)==$isi->url) {
              //check edit permission
              if ($role_act["up_act"]=="Y") {
                $edit = "<a data-id='+aData[indek]+'  class=\"btn btn-primary btn-sm edit_data \" data-toggle=\"tooltip\" title=\"Edit\"><i class=\"fa fa-pencil\"></i></a>";
              } else {
                  $edit ="";
              }
            if ($role_act['del_act']=='Y') {
                $del = "<button data-id='+aData[indek]+' data-uri=".base_admin()."modul/cuti_kuliah/cuti_kuliah_action.php".' class="btn btn-danger hapus_dtb_notif btn-sm " data-toggle="tooltip" title="Hapus" data-variable="dtb_cuti_kuliah"><i class="fa fa-trash"></i></button>';
            } else {
                $del="";
            }
                             }
            }

        ?>
<div class="modal" id="modal-confirm-action" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="glyphicon glyphicon-remove"></i></span></button> <h4 class="modal-title"><?php echo $lang['confirm'];?></h4> </div> <div class="modal-body"> <p> <i class="fa fa-info-circle fa-2x" style=" vertical-align: middle;margin-right:5px"></i> <span> Apakah Anda Yakin</span></p> </div> <div class="modal-footer"><button type="button" id="confirm-action" class="btn btn-primary">Ya Yakin</button> <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button></div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>


    <div class="modal" id="modal_cuti_kuliah" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> <h4 class="modal-title"><?php echo $lang["add_button"];?> Cuti Kuliah</h4> </div> <div class="modal-body" id="isi_cuti_kuliah"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>

        <div class="modal" id="modal_generate_cuti" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> 
          <div class="modal-dialog modal-lg"> 
            <div class="modal-content">
              <div class="modal-header"> 
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button> 
                <h4 class="modal-title">Generate Cuti Kuliah</h4> 
              </div> 
              <div class="modal-body" id="isi_generate_cuti"> 
               
                <select class="form-control" id="periode_cuti">
                  <option value="">Pilih Periode</option>
                  <?php
                  $q = $db->query("select id_semester from semester_ref order by id_semester desc");
                  foreach ($q as $k) {
                    echo "<option value='$k->id_semester'>$k->id_semester</option>";
                  }
                  ?>
                </select>
              </div> 
              <div class="modal-footer"> 
                <button class="btn btn-primary" onclick="generate_cuti_act()">Generate</button>
              </div>
            </div> 
          </div>
        </div>
    
    </section><!-- /.content -->

        <script type="text/javascript">

      function generate_cuti(){
        $("#modal_generate_cuti").modal('show');
      }


      
      $("#add_cuti_kuliah").click(function() {
          $.ajax({
              url : "<?=base_admin();?>modul/cuti_kuliah/cuti_kuliah_add.php",
              type : "GET",
              success: function(data) {
                  $("#isi_cuti_kuliah").html(data);
              }
          });

      $('#modal_cuti_kuliah').modal({ keyboard: false,backdrop:'static',show:true });

    });
    
      
    $(".table").on('click','.edit_data',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);

        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/cuti_kuliah/cuti_kuliah_edit.php",
            type : "post",
            data : {id_data:id},
            success: function(data) {
                $("#isi_cuti_kuliah").html(data);
                $("#loadnya").hide();
          }
        });

    $('#modal_cuti_kuliah').modal({ keyboard: false,backdrop:'static' });

    });
    
    var dtb_cuti_kuliah = $("#dtb_cuti_kuliah").DataTable({
           'bProcessing': true,
            'bServerSide': true,
                 "lengthMenu": [[10,100, 200,500,1000, 5000], [10,100, 200,500,1000, 5000]],
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
              url :'<?=base_admin();?>modul/cuti_kuliah/cuti_kuliah_data.php',
            type: 'post',  // method  , by default get
            data: function ( d ) {
                    d.jur_filter = $("#jur_filter").val();
                    d.sem_filter = $("#sem_filter").val();
                    d.angkatan_filter = $("#angkatan_filter").val();
                    d.disetujui = $("#disetujui").val();
                  },
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });
//filter
$('#filter').on('click', function() {
  dtb_cuti_kuliah.ajax.reload();
});

 function generate_cuti_act(){ 
        $.ajax({ 
              url : "<?=base_admin();?>modul/cuti_kuliah/cuti_kuliah_action.php?act=generate_cuti",
              type : "POST",
              data : {
                  periode_cuti : $("#periode_cuti").val()
              },
              success: function(data) { 
                  //$("#isi_cuti_kuliah").html(data);
                  alert("Generate Cuti Sukses");
                  $("#modal_generate_cuti").modal('hide'); 
                  dtb_cuti_kuliah.ajax.reload();
              }
          });
      }

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
      var table_select = $('#dtb_cuti_kuliah tbody tr.selected');
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
          $('#dtb_cuti_kuliah tbody tr').addClass('DTTT_selected selected')
      } else {
          $('#dtb_cuti_kuliah tbody tr').removeClass('DTTT_selected selected')
      }
    init_selected()
  }
  $(document).on('click', '#dtb_cuti_kuliah tbody tr .check-selected', function(event) {
      var btn = $(this).find('button');
      if (btn.length == 0) {
          $(this).parents('tr').toggleClass('DTTT_selected selected');
          var selected = check_selected();
          console.log(selected);
          init_selected();

      }
  });

/* Add a click handler for the delete row */
  $('.submit-proses').click( function(event) {
    $("#loadnya").show();
    event.stopPropagation();
    event.preventDefault();
    event.stopImmediatePropagation();
    //var anSelected = fnGetSelected( dtb_cuti_kuliah );
    var data_array_id = check_selected();
    var all_ids = data_array_id.toString();
    //var aksi = $(':selected', $(this)).data('aksi');
    var aksi = $("#aksi_krs option:selected").attr('data-aksi');
          $("#loadnya").hide();
          $('#modal-confirm-action').modal({ keyboard: false }).one('click', '#confirm-action', function (e) {
            e.stopImmediatePropagation()
            $.ajax({
                type: 'POST',
                url: '<?=base_admin();?>modul/cuti_kuliah/cuti_kuliah_action.php?act='+aksi,
                data: {aksi:$("#aksi_krs").val(), data_ids:all_ids},
                success: function(result) {
                   $('#loadnya').hide();
                      $(".bulk-check").prop("checked",0);
                      select_deselect('unselect');
                      dtb_cuti_kuliah.draw(false);
                },
                //async:false
            });
              $('#modal-confirm-action').modal('hide').data( 'bs.modal', null );;
          });
  });
</script>
            