<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Setting Tagihan Mahasiswa
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>setting-tagihan-mahasiswa">Setting Tagihan Mahasiswa</a></li>
                        <li class="active">Setting Tagihan Mahasiswa List</li>
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
                                      <a id="add_setting_tagihan_mahasiswa" class="btn btn-primary "><i class="fa fa-plus"></i> <?php echo $lang["add_button"];?></a>
                                       <a class="btn btn-primary" id="import_mat"><i class="fa fa-cloud-upload"></i> Import Excel</a>
                                      <?php
                                          }
                                      }
                                  }
                                ?>
                            </div><!-- /.box-header -->
                            <div class="box-body table-responsive">
<div class="box-header with-border">
                <h3 class="box-title">Filter</h3>
              </div>
            <div class="box-body">
              <div class="row">
                 <form class="form-horizontal" method="post" action="<?=base_admin();?>modul/setting_tagihan_mahasiswa/download_data.php">
  <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Program Studi</label>
                        <div class="col-lg-5">
                        <select id="kode_prodi" name="kode_prodi" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2">
 <?php
                                looping_prodi();
                                ?>
              </select>

 </div>
                      </div><!-- /.form-group -->
  <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Periode Tagihan</label>
                        <div class="col-lg-5">
                        <select id="berlaku_angkatan" name="periode" data-placeholder="Pilih Jenjang ..." class="form-control chzn-select" tabindex="2">
                        <option value="all">Semua</option>
                                                      <?php 
 $angkatan_exist = $db->query("select tahun_akademik,periode from keu_tagihan_mahasiswa inner join view_semester 
on keu_tagihan_mahasiswa.periode=view_semester.id_semester
group by keu_tagihan_mahasiswa.periode
order by periode desc");

foreach ($angkatan_exist as $ak) {
  if (get_sem_aktif()==$ak->periode) {
    echo "<option value='$ak->periode' selected>$ak->tahun_akademik</option>";
  } else {
    echo "<option value='$ak->periode'>$ak->tahun_akademik</option>";
  }
  
} ?>
              </select>

 </div>
                      </div><!-- /.form-group -->
  <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Jenis Pembayaran</label>
                        <div class="col-lg-5">
                        <select id="kode_pembayaran" name="kode_pembayaran" data-placeholder="Pilih Jenjang ..." class="form-control chzn-select" tabindex="2">
                        <option value="all">Semua</option>
                                                      <?php 
 $angkatan_exist = $db->query("select kjp.kode_pembayaran,kjp.nama_pembayaran from keu_jenis_pembayaran kjp
inner join keu_jenis_tagihan kjt on kjp.kode_pembayaran=kjt.kode_pembayaran
inner join keu_tagihan kt on kjt.kode_tagihan=kt.kode_tagihan
inner join keu_tagihan_mahasiswa ktm on kt.id=ktm.id_tagihan_prodi
group by kjp.kode_pembayaran");

foreach ($angkatan_exist as $ak) {
  echo "<option value='$ak->kode_pembayaran'>$ak->nama_pembayaran</option>";
} ?>
              </select>

 </div>
                      </div><!-- /.form-group -->

  <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Jenis Tagihan</label>
                        <div class="col-lg-5">
                        <select id="kode_tagihan" name="kode_tagihan" data-placeholder="Pilih Jenjang ..." class="form-control chzn-select" tabindex="2">
                        <option value="all">Semua</option>
                                                      <?php 
 $angkatan_exist = $db->query("select kt.kode_tagihan,kj.nama_tagihan from keu_jenis_tagihan kj 
inner join keu_tagihan kt on kj.kode_tagihan=kt.kode_tagihan
inner join keu_tagihan_mahasiswa ktm on kt.id=ktm.id_tagihan_prodi
group by kj.kode_tagihan");

foreach ($angkatan_exist as $ak) {
  echo "<option value='$ak->kode_tagihan'>$ak->nama_tagihan</option>";
} ?>
              </select>

 </div>
                      </div><!-- /.form-group -->

                      <div class="form-group">
                        <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                        <div class="col-lg-10">
                          <span id="filter" class="btn btn-primary"><i class="fa fa-refresh"></i> Filter</span>
                        <button type="submit" class="btn btn-success"><i class="fa fa-cloud-download"></i> Download</button>
                      </div><!-- /.form-group -->
                    </div>
                </form>
              </div>
          </div>
                                <div class="row">
                                    <div class="col-sm-12" style="text-align: right;margin-bottom: 10px">
                                    <button id="select_all" class="btn btn-primary btn-xs"><i class="fa fa-check-square-o"></i> <?php echo $lang["select_all"];?></button>
                                    <button id="deselect_all" class="btn btn-primary btn-xs"><i class="fa fa-remove"></i> <?php echo $lang["deselect_all"];?></button>
                                    <button id="bulk_delete" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> <?php echo $lang["delete_selected"];?></button> <span class="selected-data"></span>
                            </div>
                            </div>
 <div class="alert alert-warning fade in error_data_delete" style="display:none">
          <button type="button" class="close hide_alert_notif">&times;</button>
          <i class="icon fa fa-warning"></i> <span class="isi_warning_delete"></span>
        </div>
                        <table id="dtb_setting_tagihan_mahasiswa" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                  <th>No</th>
                                  <th>NIM</th>
                                  <th>Nama</th>
                                  <th>No Briva</th>
                                  <th>Jenis Tagihan</th>
                                  <th>Jumlah Tagihan</th>
                                  <th>Periode Tagihan</th>
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
              $edit = "<a data-id='+aData[indek]+' data-toggle=\"tooltip\" title=\"Edit Data\" class=\"btn btn-primary btn-sm edit_data\"><i class=\"fa fa-pencil\"></i></a>";
              } else {
                  $edit ="";
              }
            if ($role_act['del_act']=='Y') {
                $del = "<button data-id='+aData[indek]+' data-uri=".base_admin()."modul/setting_tagihan_mahasiswa/setting_tagihan_mahasiswa_action.php".' class="btn btn-danger hapus_dtb_notif btn-sm" data-toggle="tooltip" title="Hapus" data-variable="dtb_setting_tagihan_mahasiswa"><i class="fa fa-trash"></i></button>';
            } else {
                $del="";
            }
                             }
            }

        ?>

    <div class="modal" id="modal_setting_tagihan_mahasiswa" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> <h4 class="modal-title"><?php echo $lang["add_button"];?> Setting Tagihan Mahasiswa</h4> </div> <div class="modal-body" id="isi_setting_tagihan_mahasiswa"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
    <div class="modal" id="modal_import_mat" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&nbsp;</span></button> <h4 class="modal-title">Import Excel Tagihan Mahasiswa</h4> </div> <div class="modal-body" id="isi_import_mat"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>  
    </section><!-- /.content -->

        <script type="text/javascript">
     $(document).ready(function() {     
            $("#import_mat").click(function() {
          $.ajax({
              url : "<?=base_admin();?>modul/setting_tagihan_mahasiswa/import.php",
              type : "GET",
              success: function(data) {
                  $("#isi_import_mat").html(data);
              }
          });

      $('#modal_import_mat').modal({ keyboard: false,backdrop:'static',show:true });

    });
      $("#add_setting_tagihan_mahasiswa").click(function() {
          $.ajax({
              url : "<?=base_admin();?>modul/setting_tagihan_mahasiswa/setting_tagihan_mahasiswa_add.php",
              type : "GET",
              success: function(data) {
                  $("#isi_setting_tagihan_mahasiswa").html(data);
              }
          });

      $('#modal_setting_tagihan_mahasiswa').modal({ keyboard: false,backdrop:'static',show:true });

    });
    
      
    $(".table").on('click','.edit_data',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);

        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/setting_tagihan_mahasiswa/setting_tagihan_mahasiswa_edit.php",
            type : "post",
            data : {id_data:id},
            success: function(data) {
                $("#isi_setting_tagihan_mahasiswa").html(data);
                $("#loadnya").hide();
          }
        });

    $('#modal_setting_tagihan_mahasiswa').modal({ keyboard: false,backdrop:'static' });

    });
    
});
      var dtb_setting_tagihan_mahasiswa = $("#dtb_setting_tagihan_mahasiswa").DataTable({
           "fnCreatedRow": function( nRow, aData, iDataIndex ) {
            var indek = aData.length-1;
            $('td:eq('+indek+')', nRow).html('<?=$edit;?> <?=$del;?>');
              $(nRow).attr('id', 'line_'+aData[indek]);
              },
           'bProcessing': true,
            'bServerSide': true,
            'order': [[1, 'desc']],
           'columnDefs': [ {
            'targets': [7],
              'orderable': false,
              'searchable': false
            },
                {
            'width': '5%',
            'targets': 0,
            'orderable': false,
            'searchable': false,
            'className': 'dt-center'
          }
             ],

    
            'ajax':{
              url :'<?=base_admin();?>modul/setting_tagihan_mahasiswa/setting_tagihan_mahasiswa_data.php',
            type: 'post',  // method  , by default get
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });
$('#filter').on('click', function() {
dtb_setting_tagihan_mahasiswa = $("#dtb_setting_tagihan_mahasiswa").DataTable({
       "fnCreatedRow": function( nRow, aData, iDataIndex ) {
            var indek = aData.length-1;
            $('td:eq('+indek+')', nRow).html('<?=$edit;?> <?=$del;?>');
              $(nRow).attr('id', 'line_'+aData[indek]);
              },
              destroy : true,
           'bProcessing': true,
            'bServerSide': true,
            'order': [[1, 'desc']],
           'columnDefs': [ {
            'targets': [7],
              'orderable': false,
              'searchable': false
            },
                {
            'width': '5%',
            'targets': 0,
            'orderable': false,
            'searchable': false,
            'className': 'dt-center'
          }
             ],

    
            'ajax':{
              url :'<?=base_admin();?>modul/setting_tagihan_mahasiswa/setting_tagihan_mahasiswa_data.php',
            type: 'post',  // method  , by default get
            data: function ( d ) {
              d.kode_prodi = $("#kode_prodi").val();
              d.periode = $("#berlaku_angkatan").val();
              d.kode_tagihan = $("#kode_tagihan").val();
             d.kode_pembayaran = $("#kode_pembayaran").val();
            },
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          }
      });
});
  $('#dtb_setting_tagihan_mahasiswa').on('draw.dt', function() {
          init_selected()
      });

      $('#select_all').on('click', function() {
          select_deselect('select')
      });
      $('#deselect_all').on('click', function() {
          select_deselect('unselect')
  });



  $(document).on('click', '#dtb_setting_tagihan_mahasiswa tbody tr td', function(event) {
      var btn = $(this).find('button');
      if (btn.length == 0) {
          $(this).parents('tr').toggleClass('DTTT_selected selected');
          var selected = check_selected();
          init_selected();

      }
  });



  function init_selected() {
      var selected = check_selected();
      var btn_hide = $('#select_all, #deselect_all, #bulk_delete, .selected-data');
      if (selected.length > 0) {
          btn_hide.show()
      } else {
          btn_hide.hide()
      }
  }


  function check_selected() {
      var table_select = $('#dtb_setting_tagihan_mahasiswa tbody tr.selected');
      var array_data_delete = [];
      table_select.each(function() {
          var check_data = $(this).find('.hapus_dtb_notif').attr('data-id');
          if (typeof check_data != 'undefined') {
              array_data_delete.push(check_data)
          }
      });
      $('.selected-data').text(array_data_delete.length + ' <?=$lang["selected_data"];?>');
      return array_data_delete
  }


  function select_deselect(type) {
      if (type == 'select') {
          $('#dtb_setting_tagihan_mahasiswa tbody tr').addClass('DTTT_selected selected')
      } else {
          $('#dtb_setting_tagihan_mahasiswa tbody tr').removeClass('DTTT_selected selected')
      }
      init_selected()
  }




/* Add a click handler for the delete row */
  $('#bulk_delete').click( function() {
    var anSelected = fnGetSelected( dtb_setting_tagihan_mahasiswa );
    var data_array_id = check_selected();
    var all_ids = data_array_id.toString();
    $('#ucing').modal({ keyboard: false }).one('click', '#delete', function (e) {
        $('#loadnya').show();
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '<?=base_admin();?>modul/setting_tagihan_mahasiswa/setting_tagihan_mahasiswa_action.php?act=del_massal',
            data: {data_ids:all_ids},
               success: function(responseText) {
                  $('#loadnya').hide();
                  console.log(responseText);
                      $.each(responseText, function(index) {
                          console.log(responseText[index].status);
                          if (responseText[index].status=='die') {
                            $('#informasi').modal('show');
                          } else if(responseText[index].status=='error') {
                             $('.isi_warning_delete').text(responseText[index].error_message);
                             $('.error_data_delete').fadeIn();
                             $('html, body').animate({
                                scrollTop: ($('.error_data_delete').first().offset().top)
                            },500);
                          } else if(responseText[index].status=='good') {
                            $('.error_data_delete').hide();
                               $('#loadnya').hide();
                               $(anSelected).remove();
                               dtb_setting_tagihan_mahasiswa.draw();
                          } else {
                             $('.isi_warning_delete').text(responseText[index].error_message);
                             $('.error_data_delete').fadeIn();
                             $('html, body').animate({
                                scrollTop: ($('.error_data_delete').first().offset().top)
                            },500);
                          }
                    });
                }
            //async:false
        });

        $('#ucing').modal('hide');

    });

  });

  /* Get the rows which are currently selected */
  function fnGetSelected( oTableLocal )
  {
      return oTableLocal.$('tr.selected');
  }
</script>
            