<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Manage Pendaftaran Komprehensif
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>pendaftaran-komprehensif">Pendaftaran Komprehensif</a></li>
                        <li class="active">Pendaftaran Komprehensif List</li>
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
                                      <a id="add_pendaftaran_komprehensif" class="btn btn-primary "><i class="fa fa-plus"></i> <?php echo $lang["add_button"];?></a>
                                      <?php
                                          }
                                      }
                                  }
                                ?>
                                <form class="form-horizontal" method="post">
                                  <div class="col-lg-4 col-lg-offset-1">
                                    <div class="form-group">
                                      <label for="Semester" class="control-label">Jurusan</label>
                                        <select id="jurusan_filter" name="jurusan_filter" data-placeholder="Pilih Jurusan ..." class="form-control chzn-select" tabindex="2">
                                           <option value="all">Semua</option>
                                           <?php
                                           $fakultas=$_SESSION['id_fak'];
                                           foreach ($db->query("SELECT * FROM jurusan WHERE fak_kode='$fakultas'") as $isi) {
                                              echo "<option value='$isi->kode_jur'>$isi->nama_jur</option>";
                                           } ?>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                      <span id="filter" class="btn btn-primary" style="margin-top: 20px;">
                                        <i class="fa fa-refresh"></i> Filter Civitas
                                      </span>
                                    </div>
                                  </div><!-- /.form-group -->
                                </form>
                            </div><!-- /.box-header -->
                            <div class="box-body table-responsive">
                            <a id="cetak_pendaftaran_komprehensif" class="btn btn-success"><i class="fa fa-print"></i> Cetak Pendaftaran Komprehensif</a>                              
                                <div class="row">
                                    <div class="col-sm-12" style="text-align: right;margin-bottom: 10px">
                                    <button id="select_all" class="btn btn-primary btn-xs"><i class="fa fa-check-square-o"></i> <?php echo $lang["select_all"];?></button>
                                    <button id="deselect_all" class="btn btn-primary btn-xs"><i class="fa fa-remove"></i> <?php echo $lang["deselect_all"];?></button>
                                    <button id="bulk_delete" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> <?php echo $lang["delete_selected"];?></button> <span class="selected-data"></span>
                            </div>
                            </div>
                        <table id="dtb_pendaftaran_komprehensif" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                  <th>No</th>
                                  <th>Nim</th>
                                  <th>Nama</th>
                                  <th>Fakultas</th>
                                  <th>Jurusan</th>
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
                $edit = "<a data-id='+aData[indek]+'  class=\"btn btn-primary edit_data \"><i class=\"fa fa-pencil\"></i></a>";
                $setting = "<a data-id='+aData[indek]+'  class=\"btn btn-warning set_data \"><i class=\"fa fa-gear\"></i></a>";
                $nilai = "<a data-id='+aData[indek]+'  class=\"btn btn-success nilai_data \"><i class=\"fa fa-book\"></i></a>";
              } else {
                  $edit ="";
              }
            if ($role_act['del_act']=='Y') {
                $del = "<button data-id='+aData[indek]+' data-uri=".base_admin()."modul/pendaftaran_komprehensif/pendaftaran_komprehensif_action.php".' class="btn btn-danger hapus_dtb "><i class="fa fa-trash"></i></button>';
            } else {
                $del="";
            }
                             }
            }

        ?>

    <div class="modal" id="modal_pendaftaran_komprehensif" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> <h4 class="modal-title"><?php echo $lang["add_button"];?> Pendaftaran Komprehensif</h4> </div> <div class="modal-body" id="isi_pendaftaran_komprehensif"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>

    <!-- Modal Setting Penguji-->
    <div class="modal" id="modal_pendaftaran_komprehensif_set" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> <h4 class="modal-title">Setting Penguji Komprehensif</h4> </div> <div class="modal-body" id="isi_pendaftaran_komprehensif_set"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>    

    <!-- Modal Setting Nilai-->
    <div class="modal" id="modal_pendaftaran_komprehensif_nilai" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> <h4 class="modal-title">Setting Nilai Komprehensif</h4> </div> <div class="modal-body" id="isi_pendaftaran_komprehensif_nilai"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>        
 
    <!-- Modal Cetak-->
    <div class="modal" id="modal_cetak_pendaftaran_komprehensif" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> <h4 class="modal-title">Cetak Pendaftaran Komprehensif</h4> </div> <div class="modal-body" id="isi_cetak_pendaftaran_komprehensif"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>       
    </section><!-- /.content -->

        <script type="text/javascript">
      
      $("#add_pendaftaran_komprehensif").click(function() {
          $.ajax({
              url : "<?=base_admin();?>modul/pendaftaran_komprehensif/pendaftaran_komprehensif_add.php",
              type : "GET",
              success: function(data) {
                  $("#isi_pendaftaran_komprehensif").html(data);
              }
          });

      $('#modal_pendaftaran_komprehensif').modal({ keyboard: false,backdrop:'static',show:true });

    });
    
    $("#cetak_pendaftaran_komprehensif").click(function() {
          $.ajax({
              url : "<?=base_admin();?>modul/pendaftaran_komprehensif/cetak_pendaftaran_komprehensif.php",
              type : "GET",
              success: function(data) {
                  $("#isi_cetak_pendaftaran_komprehensif").html(data);
              }
          });

      $('#modal_cetak_pendaftaran_komprehensif').modal({ keyboard: false,backdrop:'static',show:true });

    });    
      
    $(".table").on('click','.edit_data',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);

        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/pendaftaran_komprehensif/pendaftaran_komprehensif_edit.php",
            type : "post",
            data : {id_data:id},
            success: function(data) {
                $("#isi_pendaftaran_komprehensif").html(data);
                $("#loadnya").hide();
          }
        });

    $('#modal_pendaftaran_komprehensif').modal({ keyboard: false,backdrop:'static' });

    });

    $(".table").on('click','.set_data',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);

        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/pendaftaran_komprehensif/pendaftaran_komprehensif_setting.php",
            type : "post",
            data : {id_data:id},
            success: function(data) {
                $("#isi_pendaftaran_komprehensif_set").html(data);
                $("#loadnya").hide();
          }
        });

    $('#modal_pendaftaran_komprehensif_set').modal({ keyboard: false,backdrop:'static' });

    });

    $(".table").on('click','.nilai_data',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);

        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/pendaftaran_komprehensif/pendaftaran_komprehensif_nilai.php",
            type : "post",
            data : {id_data:id},
            success: function(data) {
                $("#isi_pendaftaran_komprehensif_nilai").html(data);
                $("#loadnya").hide();
          }
        });

    $('#modal_pendaftaran_komprehensif_nilai').modal({ keyboard: false,backdrop:'static' });

    });    
    
      var dataTable = $("#dtb_pendaftaran_komprehensif").DataTable({
           "fnCreatedRow": function( nRow, aData, iDataIndex ) {
            var indek = aData.length-1;
            $('td:eq('+indek+')', nRow).html('<?=$nilai;?> <?=$setting;?> <?=$edit;?> <?=$del;?>');
              $(nRow).attr('id', 'line_'+aData[indek]);
              },

           'bProcessing': true,
            'bServerSide': true,
            
           'columnDefs': [ {
            'targets': [5],
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
              url :'<?=base_admin();?>modul/pendaftaran_komprehensif/pendaftaran_komprehensif_data.php',
            type: 'post',  // method  , by default get
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });

  $('#dtb_pendaftaran_komprehensif').on('draw.dt', function() {
          init_selected()
      });

      $('#select_all').on('click', function() {
          select_deselect('select')
      });
      $('#deselect_all').on('click', function() {
          select_deselect('unselect')
  });



  $(document).on('click', '#dtb_pendaftaran_komprehensif tbody tr td', function(event) {
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
      var table_select = $('#dtb_pendaftaran_komprehensif tbody tr.selected');
      var array_data_delete = [];
      table_select.each(function() {
          var check_data = $(this).find('.hapus_dtb').attr('data-id');
          if (typeof check_data != 'undefined') {
              array_data_delete.push(check_data)
          }
      });
      $('.selected-data').text(array_data_delete.length + ' <?=$lang["selected_data"];?>');
      return array_data_delete
  }


  function select_deselect(type) {
      if (type == 'select') {
          $('#dtb_pendaftaran_komprehensif tbody tr').addClass('DTTT_selected selected')
      } else {
          $('#dtb_pendaftaran_komprehensif tbody tr').removeClass('DTTT_selected selected')
      }
      init_selected()
  }

  $('#filter').on('click', function() {
    dataTable = $("#dtb_pendaftaran_komprehensif").DataTable({
   "fnCreatedRow": function( nRow, aData, iDataIndex ) {
    var indek = aData.length-1;
    $('td:eq('+indek+')', nRow).html('<?=$nilai;?> <?=$setting;?> <?=$edit;?> <?=$del;?>');
      $(nRow).attr('id', 'line_'+aData[indek]);
      },

      destroy : true,
     'bProcessing': true,
      'bServerSide': true,
           "columnDefs": [ {
         "targets": [0,5],
        "orderable": false,
        "searchable": false

      } ],
      "ajax":{
       url :"<?=base_admin();?>modul/pendaftaran_komprehensif/pendaftaran_komprehensif_data.php",
      type: "post",  // method  , by default get
      data: function ( d ) {
              d.fakultas = $("#fakultas_filter").val();
              d.jurusan = $("#jurusan_filter").val();
            },
    error: function (xhr, error, thrown) {
      console.log(xhr);
      }
    },
  });

});
/* Add a click handler for the delete row */
  $('#bulk_delete').click( function() {
    var anSelected = fnGetSelected( dataTable );
    var data_array_id = check_selected();
    var all_ids = data_array_id.toString();
    $('#ucing').modal({ keyboard: false }).one('click', '#delete', function (e) {
        $('#loadnya').show();
        $.ajax({
            type: 'POST',
            url: '<?=base_admin();?>modul/pendaftaran_komprehensif/pendaftaran_komprehensif_action.php?act=del_massal',
            data: {data_ids:all_ids},
            success: function(result) {
               $('#loadnya').hide();
                 $(anSelected).remove();
                  dataTable.draw();
            },
            //async:false
        });

        $('#ucing').modal('hide');

    });

  });

  $("#fakultas_filter").change(function(){

        $.ajax({
        type : "post",
        url : "<?=base_admin();?>modul/pendaftaran_komprehensif/get_jurusan_filter.php",
        data : {fakultas:this.value},
        success : function(data) {
            $("#jurusan_filter").html(data);
            $("#jurusan_filter").trigger("chosen:updated");

        }
    });

  });

  /* Get the rows which are currently selected */
  function fnGetSelected( oTableLocal )
  {
      return oTableLocal.$('tr.selected');
  }
</script>
            