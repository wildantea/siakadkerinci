<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Manage Pendaftaran Beasiswa
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>pendaftaran-beasiswa">Pendaftaran Beasiswa</a></li>
                        <li class="active">Pendaftaran Beasiswa List</li>
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
                                      <a id="add_pendaftaran_beasiswa" class="btn btn-primary "><i class="fa fa-plus"></i> Tambah Penerima Beasiswa</a>
                                      
                                      <a id="jenis_pendaftaran_beasiswa" class="btn btn-warning" href="<?=base_index();?>pendaftaran-beasiswa/jenis"><i class="fa fa-gear"></i> Setting Jenis Beasiswa </a>

                                      <a id="beasiswa_pendaftaran_beasiswa" class="btn btn-warning" href="<?=base_index();?>pendaftaran-beasiswa/beasiswa"><i class="fa fa-gear"></i> Setting Beasiswa </a>
                                      <?php
                                          }
                                      }
                                  }
                                ?>
                                <form class="form-horizontal" method="post" action="<?=base_admin();?>modul/tugas-akhir/download_data.php">
                                        <div class="col-lg-4 col-lg-offset-1">
                                          <div class="form-group">
                                            <label for="Semester" class="control-label">Jenis Beasiswa</label>
                                              <select id="jenis_filter" name="jenis_filter" data-placeholder="Pilih Angkatan ..." class="form-control chzn-select" tabindex="2">
                                                 <option value="all">Semua</option>
                                                 <?php
                                                 foreach ($db->fetch_all("beasiswa_jenis") as $isi) {
                                                    echo "<option value='$isi->id_beasiswajns'>$isi->jenis_beasiswajns</option>";
                                                 } ?>
                                              </select>
                                          </div>

                                          <div class="form-group">
                                            <label for="Semester" class="control-label">Beasiswa</label>
                                              <select id="beasiswa_filter" name="beasiswa_filter" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2">
                                                <option value="all">Semua</option>
                                              </select>
                                          </div>

                                          <div class="form-group">
                                            <label for="Priode Beasiswa" class="control-label">Priode Beasiswa</label>
                                            <select id="priode_beasiswa" name="priode_beasiswa" id="sem" data-placeholder="Pilih Priode Beasiswa ..." class="form-control chzn-select" tabindex="2" >
                                               <option value="all">Semua</option>
                                               <?php 
                                                 $sem = $db->query("select * from semester_ref s join jenis_semester j 
                                                  on s.id_jns_semester=j.id_jns_semester order by s.id_semester desc");
                                                  foreach ($sem as $isi2) {
                                                    if ($isi2->id_semester==$sem2) {
                                                     echo "<option value='".$enc->enc($isi2->id_semester)."' selected>$isi2->jns_semester $isi2->tahun/".($isi2->tahun+1)."</option>";
                                                    }else{
                                                      echo "<option value='".$enc->enc($isi2->id_semester)."'>$isi2->jns_semester $isi2->tahun/".($isi2->tahun+1)."</option>";
                                                    }
                                                  
                                               } ?>
                                              </select>
                                          </div><!-- /.form-group -->

                                          <div class="form-group">
                                            <span id="filter" class="btn btn-primary" style="margin-top: 20px;">
                                              <i class="fa fa-refresh"></i> Filter
                                            </span>
                                          </div>
                                        </div><!-- /.form-group -->
                                      </form>
                            </div><!-- /.box-header -->
                            <div class="box-body table-responsive">
                              <a id="cetak_pendaftaran_beasiswa" class="btn btn-success"><i class="fa fa-print"></i> Cetak Beasiswa</a>

                              <div class="row">
                                  <div class="col-sm-12" style="text-align: right;margin-bottom: 10px">
                                  <button id="select_all" class="btn btn-primary btn-xs"><i class="fa fa-check-square-o"></i> <?php echo $lang["select_all"];?></button>
                                  <button id="deselect_all" class="btn btn-primary btn-xs"><i class="fa fa-remove"></i> <?php echo $lang["deselect_all"];?></button>
                                  <button id="bulk_delete" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> <?php echo $lang["delete_selected"];?></button> <span class="selected-data"></span>
                              </div>
                            </div>
                       
                        <table id="dtb_pendaftaran_beasiswa" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                  <th>No</th>
                                  <th>NIM</th>
                                  <th>Nama</th>
                                  <th>Beasiswa</th>
                                  <th>Status</th>
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
                $edit = "<a data-id='+aData[indek]+'  class=\"btn btn-primary edit_data \"><i class=\"fa fa-pencil\" data-toggle=\"tooltip\" title=\"Edit\"></i></a>";
              } else {
                  $edit ="";
              }
            if ($role_act['del_act']=='Y') {
                $del = "<button data-id='+aData[indek]+' data-uri=".base_admin()."modul/pendaftaran_beasiswa/pendaftaran_beasiswa_action.php".' class="btn btn-danger hapus_dtb "><i class="fa fa-trash" data-toggle="tooltip" title="Hapus"></i></button>';
            } else {
                $del="";
            }
                             }
            }

        ?>

    <div class="modal" id="modal_pendaftaran_beasiswa" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> <h4 class="modal-title"><?php echo $lang["add_button"];?> Pendaftar Beasiswa</h4> </div> <div class="modal-body" id="isi_pendaftaran_beasiswa"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>

    <div class="modal" id="cetak_modal_pendaftaran_beasiswa" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> <h4 class="modal-title">Cetak Pendaftar Beasiswa</h4> </div> <div class="modal-body" id="cetak_isi_pendaftaran_beasiswa"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
    
    </section><!-- /.content -->

    <script type="text/javascript">
      
    $("#add_pendaftaran_beasiswa").click(function() {
          $.ajax({
              url : "<?=base_admin();?>modul/pendaftaran_beasiswa/pendaftaran_beasiswa_add.php",
              type : "GET",
              success: function(data) {
                  $("#isi_pendaftaran_beasiswa").html(data);
              }
          });

      $('#modal_pendaftaran_beasiswa').modal({ keyboard: false,backdrop:'static',show:true });

    });

    $("#cetak_pendaftaran_beasiswa").click(function() {
          $.ajax({
              url : "<?=base_admin();?>modul/pendaftaran_beasiswa/cetak_pendaftaran_beasiswa.php",
              type : "GET",
              success: function(data) {
                  $("#cetak_isi_pendaftaran_beasiswa").html(data);
              }
          });

      $('#cetak_modal_pendaftaran_beasiswa').modal({ keyboard: false,backdrop:'static',show:true });

    });
    
      
    $(".table").on('click','.edit_data',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);

        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/pendaftaran_beasiswa/pendaftaran_beasiswa_edit.php",
            type : "post",
            data : {id_data:id},
            success: function(data) {
                $("#isi_pendaftaran_beasiswa").html(data);
                $("#loadnya").hide();
          }
        });

    $('#modal_pendaftaran_beasiswa').modal({ keyboard: false,backdrop:'static' });

    });
    
      var dataTable = $("#dtb_pendaftaran_beasiswa").DataTable({
           "fnCreatedRow": function( nRow, aData, iDataIndex ) {
            var indek = aData.length-1;
            $('td:eq('+indek+')', nRow).html(' <a href="<?=base_index();?>pendaftaran-beasiswa/detail/'+aData[indek]+'" class="btn btn-success "><i class="fa fa-eye" data-toggle="tooltip" title="Detail"></i></a> <?=$edit;?> <?=$del;?>');
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
              url :'<?=base_admin();?>modul/pendaftaran_beasiswa/pendaftaran_beasiswa_data.php',
            type: 'post',  // method  , by default get
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });

  $('#filter').on('click', function() {
    dataTable = $("#dtb_pendaftaran_beasiswa").DataTable({
   "fnCreatedRow": function( nRow, aData, iDataIndex ) {
    var indek = aData.length-1;
    $('td:eq('+indek+')', nRow).html('<a href="<?=base_index();?>pendaftaran-beasiswa/detail/'+aData[indek]+'" class="btn btn-success "><i class="fa fa-eye"></i></a> <?=$edit;?> <?=$del;?>');
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
       url :"<?=base_admin();?>modul/pendaftaran_beasiswa/pendaftaran_beasiswa_data.php",
      type: "post",  // method  , by default get
      data: function ( d ) {
              d.jenis = $("#jenis_filter").val();
              d.beasiswa = $("#beasiswa_filter").val();
              d.priode = $("#priode_beasiswa").val();
            },
    error: function (xhr, error, thrown) {
      console.log(xhr);
      }
    },
  });

});

  $('#dtb_pendaftaran_beasiswa').on('draw.dt', function() {
          init_selected()
      });

      $('#select_all').on('click', function() {
          select_deselect('select')
      });
      $('#deselect_all').on('click', function() {
          select_deselect('unselect')
  });



  $(document).on('click', '#dtb_pendaftaran_beasiswa tbody tr td', function(event) {
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
      var table_select = $('#dtb_pendaftaran_beasiswa tbody tr.selected');
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
          $('#dtb_pendaftaran_beasiswa tbody tr').addClass('DTTT_selected selected')
      } else {
          $('#dtb_pendaftaran_beasiswa tbody tr').removeClass('DTTT_selected selected')
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
            url: '<?=base_admin();?>modul/pendaftaran_beasiswa/pendaftaran_beasiswa_action.php?act=del_massal',
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

  $("#jenis_filter").change(function(){

        $.ajax({
        type : "post",
        url : "<?=base_admin();?>modul/pendaftaran_beasiswa/get_beasiswa_filter.php",
        data : {jenisbeasiswa:this.value},
        success : function(data) {
            $("#beasiswa_filter").html(data);
            $("#beasiswa_filter").trigger("chosen:updated");

        }
    });

  });

  function change_status(id,stat){
      $.ajax({
        type: "POST",
        url: "<?=base_admin();?>modul/pendaftaran_beasiswa/pendaftaran_beasiswa_action.php?act=change_status",
        data: "stat="+stat+"&id=" +id,
        success: function(msg){
        if(msg=='good'){
          if(stat=='2') {
              $("#stat_"+id).html('<button type="button" class="btn btn-danger btn-xs">Belum Validasi</button> <button type="button" class="btn btn-danger dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false"> <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span> </button> <ul class="dropdown-menu" role="menu"> <li><a onclick="change_status(\''+id+'\',1)">Sudah Validasi</a></li></ul>');
          }else {
            $("#stat_"+id).html('<button type="button" class="btn btn-success btn-xs">Sudah Validasi</button> <button type="button" class="btn btn-success dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false"> <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span> </button> <ul class="dropdown-menu" role="menu"> <li><a onclick="change_status(\''+id+'\',2)">Belum Validasi</a></li></ul>');
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
            