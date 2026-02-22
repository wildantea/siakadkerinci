<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Manage Tugas Akhir
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>tugas-akhir">Tugas Akhir</a></li>
                        <li class="active">Tugas Akhir List</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
                              <div class="box-header">
                                <h3 class="box-title">Filter</h3>
                                  <div class="box-body">
                                    <div class="row">
                                      <?php
                                        foreach ($db->fetch_all("sys_menu") as $isi) {
                                            if (uri_segment(1)==$isi->url) {
                                                if ($role_act["insert_act"]=="Y") {
                                            ?>
                                            <a id="add_tugas_akhir" class="btn btn-primary "><i class="fa fa-plus"></i> <?php echo $lang["add_button"];?></a>
                                            <?php
                                                }
                                            }
                                        }
                                      ?>
                                      <form class="form-horizontal" method="post" action="<?=base_admin();?>modul/tugas-akhir/download_data.php">
                                        <div class="col-lg-4 col-lg-offset-1">
                                          <div class="form-group">
                                            <label for="Semester" class="control-label">Jurusan</label>
                                            <select id="jurusan_filter" name="jurusan_filter" data-placeholder="Pilih Angkatan ..." class="form-control chzn-select" tabindex="2">
                                               <option value="all">Semua</option>
                                               <?php
                                               $fakultas=$_SESSION['id_fak'];
                                               foreach ($db->query("select * from jurusan where fak_kode='$fakultas'") as $isi) {
                                                  echo "<option value='$isi->kode_jur'>$isi->nama_jur</option>";
                                               } 
                                               ?>
                                            </select>
                                          </div>

                                          <div class="form-group">
                                            <label for="Semester" class="control-label">Priode</label>
                                              <select id="priode_filter" name="priode_filter" data-placeholder="Pilih Priode Kompre ..." class="form-control chzn-select" tabindex="2">
                                                 <option value="all">Semua</option>
                                                 <?php
                                                 foreach ($db->query("select * from jadwal_muna jm join semester_ref sr on jm.priode_muna=sr.id_semester join jenis_semester j on sr.id_jns_semester=j.id_jns_semester order by sr.id_semester desc") as $isi) {
                                                    echo "<option value='$isi->id_muna'>$isi->jns_semester $isi->tahun/".($isi->tahun+1)."</option>";
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
                                    </div>
                                </div>
                              </div><!-- /.box-header -->
                            <div class="box-body table-responsive">
                              <a id="cetak_tugas_akhir" class="btn btn-success"><i class="fa fa-print"></i> Cetak Tugas Akhir</a>
                              <div class="row">
                                  <div class="col-sm-12" style="text-align: right;margin-bottom: 10px">
                                    <button id="select_all" class="btn btn-primary btn-xs"><i class="fa fa-check-square-o"></i> <?php echo $lang["select_all"];?></button>
                                    <button id="deselect_all" class="btn btn-primary btn-xs"><i class="fa fa-remove"></i> <?php echo $lang["deselect_all"];?></button>
                                    <button id="bulk_delete" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> <?php echo $lang["delete_selected"];?></button> <span class="selected-data"></span>
                                  </div>
                              </div>
                          <table id="dtb_tugas_akhir" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                  <th>No</th>
                                  <th>Nim</th>
                                  <th>Nama</th>
                                  <th>Jurusan</th>
                                  <th>Pembimbing 1</th>
                                  <th>Pembimbing 2</th>
                                  <th width=15%>Status</th>
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
                $setting = "<a data-id='+aData[indek]+' class=\"btn btn-warning set_data\"><i class=\"fa fa-gear\"></i></a>";
              } else {
                  $edit ="";
                  $setting="";
              }
            if ($role_act['del_act']=='Y') {
                $del = "<button data-id='+aData[indek]+' data-uri=".base_admin()."modul/tugas_akhir/tugas_akhir_action.php".' class="btn btn-danger hapus_dtb "><i class="fa fa-trash"></i></button>';
            } else {
                $del="";
            }
                             }
            }

        ?>
    <!-- Modal Cetak -->
    <div class="modal" id="modal_cetak_tugas_akhir" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> <h4 class="modal-title">Cetak Tugas Akhir</h4> </div> <div class="modal-body" id="isi_cetak_tugas_akhir"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
    <!-- Modal Tugas Akhir-->
    <div class="modal" id="modal_tugas_akhir" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> <h4 class="modal-title"><?php echo $lang["add_button"];?> Tugas Akhir</h4> </div> <div class="modal-body" id="isi_tugas_akhir"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
    <!-- Modal Setting Tugas Akhir-->
    <div class="modal" id="modal_tugas_akhir_set" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> <h4 class="modal-title">Setting Tugas Akhir</h4> </div> <div class="modal-body" id="isi_tugas_akhir_set"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>

    </section><!-- /.content -->

        <script type="text/javascript">

      $("#add_tugas_akhir").click(function() {

          $.ajax({
              url : "<?=base_admin();?>modul/tugas_akhir/tugas_akhir_add.php",
              type : "GET",
              success: function(data) {
                  $("#isi_tugas_akhir").html(data);
              }
          });

      $('#modal_tugas_akhir').modal({ keyboard: false,backdrop:'static',show:true });

    });

    $("#cetak_tugas_akhir").click(function() {

          $.ajax({
              url : "<?=base_admin();?>modul/tugas_akhir/cetak_tugas_akhir.php",
              type : "GET",
              success: function(data) {
                  $("#isi_cetak_tugas_akhir").html(data);
              }
          });

      $('#modal_cetak_tugas_akhir').modal({ keyboard: false,backdrop:'static',show:true });

    });

    $(".table").on('click','.edit_data',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);

        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/tugas_akhir/tugas_akhir_edit.php",
            type : "post",
            data : {id_data:id},
            success: function(data) {
                $("#isi_tugas_akhir").html(data);
                $("#loadnya").hide();
          }
        });

    $('#modal_tugas_akhir').modal({ keyboard: false,backdrop:'static' });

    });

    $(".table").on('click','.set_data',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);

        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/tugas_akhir/tugas_akhir_set.php",
            type : "post",
            data : {id_data:id},
            success: function(data) {
                $("#isi_tugas_akhir_set").html(data);
                $("#loadnya").hide();
          }
        });

    $('#modal_tugas_akhir_set').modal({ keyboard: false,backdrop:'static' });

    });

      var dataTable = $("#dtb_tugas_akhir").DataTable({
           "fnCreatedRow": function( nRow, aData, iDataIndex ) {
            var indek = aData.length-1;
            $('td:eq('+indek+')', nRow).html('<?=$edit;?> <?=$setting;?> <?=$del;?>');
              $(nRow).attr('id', 'line_'+aData[indek]);
              },
           'bProcessing': true,
            'bServerSide': true,

           'columnDefs': [ {
            'targets': [6],
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
              url :'<?=base_admin();?>modul/tugas_akhir/tugas_akhir_data.php',
            type: 'post',  // method  , by default get
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });

  $('#dtb_tugas_akhir').on('draw.dt', function() {
          init_selected()
      });

      $('#select_all').on('click', function() {
          select_deselect('select')
      });
      $('#deselect_all').on('click', function() {
          select_deselect('unselect')
  });



  $(document).on('click', '#dtb_tugas_akhir tbody tr td', function(event) {
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
      var table_select = $('#dtb_tugas_akhir tbody tr.selected');
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
          $('#dtb_tugas_akhir tbody tr').addClass('DTTT_selected selected')
      } else {
          $('#dtb_tugas_akhir tbody tr').removeClass('DTTT_selected selected')
      }
      init_selected()
  }




/* Add a click handler for the delete row */
  $('#bulk_delete').click( function() {
    var anSelected = fnGetSelected( dataTable );
    var data_array_id = check_selected();
    var all_ids = data_array_id.toString();
    $('#ucing').modal({ keyboard: false }).one('click', '#delete', function (e) {
        $('#loadnya').show();
        $.ajax({
            type: 'POST',
            url: '<?=base_admin();?>modul/tugas_akhir/tugas_akhir_action.php?act=del_massal',
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

  $('#filter').on('click', function() {
    dataTable = $("#dtb_tugas_akhir").DataTable({
   "fnCreatedRow": function( nRow, aData, iDataIndex ) {
    var indek = aData.length-1;
    $('td:eq('+indek+')', nRow).html('<?=$edit;?> <?=$setting;?> <?=$del;?>');
      $(nRow).attr('id', 'line_'+aData[indek]);
      },
      destroy : true,
     'bProcessing': true,
      'bServerSide': true,
           "columnDefs": [ {
         "targets": [0,6],
        "orderable": false,
        "searchable": false

      } ],
      "ajax":{
       url :"<?=base_admin();?>modul/tugas_akhir/tugas_akhir_data.php",
      type: "post",  // method  , by default get
      data: function ( d ) {
              d.jurusan = $("#jurusan_filter").val();
              d.priode = $("#priode_filter").val();
            },
    error: function (xhr, error, thrown) {
      console.log(xhr);
      }
    },
  });

});

  $("#fakultas_filter").change(function(){

        $.ajax({
        type : "post",
        url : "<?=base_admin();?>modul/tugas_akhir/get_jurusan_filter.php",
        data : {fakultas:this.value},
        success : function(data) {
            $("#jurusan_filter").html(data);
            $("#jurusan_filter").trigger("chosen:updated");

        }
    });

  });

  function change_status(id,stat){
      $.ajax({
        type: "POST",
        url: "<?=base_admin();?>modul/tugas_akhir/tugas_akhir_action.php?act=change_status",
        data: "stat="+stat+"&id=" +id,
        success: function(msg){
        if(msg=='good'){
          if (stat=='1') {
              $("#stat_"+id).html('<button type="button" class="btn btn-success btn-xs">Tugas Akhir</button> <button type="button" class="btn btn-success dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false"> <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span> </button> <ul class="dropdown-menu" role="menu"> <li><a onclick="change_status(\''+id+'\',2)">Yudisium</a></li> <li><a onclick="change_status(\''+id+'\',3)">Lulus</a></li> </ul>');
          }else if(stat=='2') {
              $("#stat_"+id).html('<button type="button" class="btn btn-warning btn-xs">Yudisium</button> <button type="button" class="btn btn-warning dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false"> <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span> </button> <ul class="dropdown-menu" role="menu"> <li><a onclick="change_status(\''+id+'\',1)">Tugas Akhir</a></li><li><a onclick="change_status(\''+id+'\',3)">Lulus</a></li> </ul>');
          } else{
            $("#stat_"+id).html('<button type="button" class="btn btn-danger btn-xs">Lulus</button> <button type="button" class="btn btn-danger dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false"> <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span> </button> <ul class="dropdown-menu" role="menu"> <li><a onclick="change_status(\''+id+'\',1)">Tugas Akhir</a></li><li><a onclick="change_status(\''+id+'\',2)">Yudisium</a></li> </ul>');
          }

        } else {
          //$("#input_"+prodix).val(asli);
          alert('Maaf eksekusi Anda gagal');
          }
        }
      });
  }

  /* Get the rows which are currently selected */
  function fnGetSelected( oTableLocal )
  {
      return oTableLocal.$('tr.selected');
  }
</script>
