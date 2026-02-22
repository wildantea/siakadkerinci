<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Ruang Kuliah
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>ruang-kuliah">Ruang Kuliah</a></li>
                        <li class="active">Ruang Kuliah List</li>
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
                                      <a id="add_ruang_kuliah" class="btn btn-primary "><i class="fa fa-plus"></i> <?php echo $lang["add_button"];?></a>
                                      <?php
                                          }
                                          if ($role_act["import_act"]=="Y") {
                                      ?>
                                      <a class="btn btn-primary" id="import_data"><i class="fa fa-cloud-upload"></i> Import Excel</a>
                                      <?php
                                          }
                                      }
                                  }
                                ?>
                            </div><!-- /.box-header -->
                            <div class="box-body table-responsive">
<div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Filter</h3>
            </div>
            <div class="box-body">
           <form class="form-horizontal" id="filter_form" method="post" action="<?=base_admin();?>modul/ruang_kuliah/download_data.php" target="_blank">

              <div class="form-group">
                    <label for="Gedung" class="control-label col-lg-2">Gedung</label>
                    <div class="col-lg-5">
                    <select id="gedung_id_filter" name="gedung_id" data-placeholder="Pilih Gedung ..." class="form-control chzn-select" tabindex="2" required="">

                      <option value="all">Semua</option>
                      <?php
                      foreach ($db->query("select * from gedung_ref") as $dt) {
                        echo "<option value='$dt->gedung_id'>$dt->nm_gedung</option>";
                      }
                      ?>
                    </select>
                    </div>
              </div><!-- /.form-group -->
              <div class="form-group">
                    <label for="Program Studi" class="control-label col-lg-2">Penggunaan Untuk Prodi</label>
                    <div class="col-lg-5">
                    <select id="kode_jur_filter" name="kode_jur" data-placeholder="Pilih Program Studi ..." class="form-control chzn-select" tabindex="2" required="">
                      <option value="all">Semua</option>
                    <?php
                    foreach ($db->query("select * from ruang_ref_prodi group by kode_jur") as $key) {
                      echo "<option value='$key->kode_jur'>$key->nama_jurusan</option>";
                    }
                    ?>
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
            <!-- /.box-body -->
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
                        <table id="dtb_ruang_kuliah" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                  <th>No</th>
                                  <th>Nama Gedung</th>
                                  <th>Kode Ruang</th>
                                  <th>Nama Ruang</th>
                                  <th>Penggunaan</th>
                                  <th>Ket</th>
                                  <th>Aktif</th>
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

                $edit = "<button data-id='+aData[indek]+'".' class="btn btn-primary edit_data btn-sm" data-toggle="tooltip" title="Edit"><i class="fa fa-pencil"></i></button>';
              } else {
                  $edit ="";
              }
            if ($role_act['del_act']=='Y') {
                $del = "<button data-id='+aData[indek]+' data-uri=".base_admin()."modul/ruang_kuliah/ruang_kuliah_action.php".' class="btn btn-danger hapus_dtb_notif btn-sm" data-toggle="tooltip" title="Hapus" data-variable="dtb_ruang_kuliah"><i class="fa fa-trash"></i></button>';
            } else {
                $del="";
            }
                             }
            }

        ?>

    <div class="modal" id="modal_ruang_kuliah" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> <h4 class="modal-title ruang_title"></h4> </div> <div class="modal-body" id="isi_ruang_kuliah"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
       <div class="modal" id="modal_import_data" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&nbsp;</span></button> <h4 class="modal-title">Import Excel </h4> </div> <div class="modal-body" id="isi_import_data"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div> 
    </section><!-- /.content -->

        <script type="text/javascript">
              $("#import_data").click(function() {
          $.ajax({
              url : "<?=base_admin();?>modul/ruang_kuliah/import.php",
              type : "GET",
              success: function(data) {
                  $("#isi_import_data").html(data);
              }
          });

      $('#modal_import_data').modal({ keyboard: false,backdrop:'static',show:true });

    });

  $("#gedung_id_filter").change(function(){
            $.ajax({
            type : "post",
            url : "<?=base_admin();?>modul/ruang_kuliah/get_penggunaan_ruang.php",
            data : {gedung_id:this.value},
            success : function(data) {
                $("#kode_jur_filter").html(data);
                $("#kode_jur_filter").trigger("chosen:updated");

            }
        });
            });


      $("#add_ruang_kuliah").click(function() {
        $(".ruang_title").html('Tambah Baru Ruang Kuliah');
          $.ajax({
              url : "<?=base_admin();?>modul/ruang_kuliah/ruang_kuliah_add.php",
              type : "GET",
              success: function(data) {
                  $("#isi_ruang_kuliah").html(data);
              }
          });

      $('#modal_ruang_kuliah').modal({ keyboard: false,backdrop:'static',show:true });

    });
    
      
    $(".table").on('click','.edit_data',function(event) {
       $(".ruang_title").html('Edit Ruang Kuliah');
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);

        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/ruang_kuliah/ruang_kuliah_edit.php",
            type : "post",
            data : {id_data:id},
            success: function(data) {
                $("#isi_ruang_kuliah").html(data);
                $("#loadnya").hide();
          }
        });

    $('#modal_ruang_kuliah').modal({ keyboard: false,backdrop:'static' });

    });
    
      var dtb_ruang_kuliah = $("#dtb_ruang_kuliah").DataTable({
           "fnCreatedRow": function( nRow, aData, iDataIndex ) {
            var indek = aData.length-1;
            $('td:eq('+indek+')', nRow).html('<?=$edit;?> <?=$del;?>');
              $(nRow).attr('id', 'line_'+aData[indek]);
              },
           'bProcessing': true,
            'bServerSide': true,
            
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
              url :'<?=base_admin();?>modul/ruang_kuliah/ruang_kuliah_data.php',
            type: 'post',  // method  , by default get
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });



$('#filter').on('click', function() {
  dtb_ruang_kuliah = $("#dtb_ruang_kuliah").DataTable({
   "fnCreatedRow": function( nRow, aData, iDataIndex ) {
            var indek = aData.length-1;
            $('td:eq('+indek+')', nRow).html('<?=$edit;?> <?=$del;?>');
              $(nRow).attr('id', 'line_'+aData[indek]);
              },
            "order": [[1,'asc']],  
            destroy : true,
           'bProcessing': true,
            'bServerSide': true,
            
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
              url :'<?=base_admin();?>modul/ruang_kuliah/ruang_kuliah_data.php',
            type: 'post',  // method  , by default get
            data: function ( d ) {
                //filter variable datatable
                d.kode_jur = $("#kode_jur_filter").val();
                d.gedung_id = $("#gedung_id_filter").val();
  
                  },
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });
});

  $('#dtb_ruang_kuliah').on('draw.dt', function() {
          init_selected()
      });

      $('#select_all').on('click', function() {
          select_deselect('select')
      });
      $('#deselect_all').on('click', function() {
          select_deselect('unselect')
  });



  $(document).on('click', '#dtb_ruang_kuliah tbody tr td', function(event) {
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
      var table_select = $('#dtb_ruang_kuliah tbody tr.selected');
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
          $('#dtb_ruang_kuliah tbody tr').addClass('DTTT_selected selected')
      } else {
          $('#dtb_ruang_kuliah tbody tr').removeClass('DTTT_selected selected')
      }
      init_selected()
  }




/* Add a click handler for the delete row */
  $('#bulk_delete').click( function() {
    var anSelected = fnGetSelected( dtb_ruang_kuliah );
    var data_array_id = check_selected();
    var all_ids = data_array_id.toString();
    $('#ucing').modal({ keyboard: false }).one('click', '#delete', function (e) {
        $('#loadnya').show();
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '<?=base_admin();?>modul/ruang_kuliah/ruang_kuliah_action.php?act=del_massal',
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
                               dtb_ruang_kuliah.draw();
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
            