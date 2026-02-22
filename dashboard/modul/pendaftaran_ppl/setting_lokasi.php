<!-- Main content -->
<section class="content-header">
    <h1>
        Manage Lokasi PPL
    </h1>
          <ol class="breadcrumb">
          <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
          <li><a href="<?=base_index();?>pendaftaran-ppl">Pendaftaran PPL</a></li>
          <li class="active">Setting Lokasi PPL</li>
    </ol>
</section>
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
           <a href="<?=base_index();?>pendaftaran-ppl" class="btn btn-primary"><span class="fa fa-step-backward"></span> Kembali </a>
              <form class="form-horizontal" method="post">
            <div class="col-lg-4 col-lg-offset-1">

            <div class="form-group">
              <label for="Semester" class="control-label">Priode</label>
              <select id="priode_filter" name="priode_filter" onchange="pilih_lokasi(this.value)" data-placeholder="Pilih Priode PPL ..." class="form-control chzn-select" tabindex="2">
               <?php
               foreach ($db->query("select * from priode_ppl jm join semester_ref sr on jm.priode=sr.id_semester join jenis_semester j on sr.id_jns_semester=j.id_jns_semester order by sr.id_semester desc") as $isi) {
                echo "<option value='$isi->id_priode'>$isi->jns_semester $isi->tahun/".($isi->tahun+1)."</option>";
              } ?>
            </select>
          </div>
 
          <div class="form-group">
            <span id="filter" class="btn btn-primary" style="margin-top: 20px;">
              <i class="fa fa-refresh"></i> Filter 

            </span>
              
            </span> 
              <span  id="add_lokasi" class="btn btn-success" style="margin-top: 20px;" >
              <i class="fa fa-plus"></i> <?php echo $lang["add_button"];?> Lokasi
              
            </span>
          </div>
        </div><!-- /.form-group -->
      </form>
           <!--  <div class="box-body">
          
              <a href="<?=base_index();?>pendaftaran-ppl" class="btn btn-default"><span class="fa fa-step-backward"></span> Kembali </a>
              <a id="add_lokasi" class="btn btn-primary "><i class="fa fa-plus"></i> <?php echo $lang["add_button"];?> Lokasi</a>
                        
            </div> -->
          </div><!-- /.box-header -->
          <div class="box-body table-responsive">
            <table id="setting_lokasi" class="table table-bordered table-striped">
              <thead>
                  <tr>
                    <th>No</th>
                    <th>Lokasi</th>
                   
                   
                    <th>DPL 1</th> 
                     <th>DPL 2</th> 
                    <th>Kuota</th>
                    <th>Kuota Laki-laki</th>
                    <th>Kuota Perempuan</th> 
                    <th style="width: 400px">Kuota Jurusan</th>
                    <th>Jml Pendaftar</th>
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
            if ('pendaftaran-ppl'==$isi->url) {
              if (roleUser('pendaftaran-ppl')['up_act']=="Y") {
                $edit = "<a data-id='+aData[indek]+'  class=\"btn btn-primary edit_data \"><i class=\"fa fa-pencil\"></i></a>";
              } else {
                  $edit ="";
              }
            if ($role_act['del_act']=='Y') {
                $del = "<button data-id='+aData[indek]+' data-uri=".base_admin()."modul/pendaftaran_ppl/pendaftaran_ppl_action.php?act=delete_lokasi".' class="btn btn-danger hapus_data "><i class="fa fa-trash"></i></button>';
            } else {
                $del="";
            }
                             }
            }

        ?>

    <div class="modal" id="modal_lokasi" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> <h4 class="modal-title"><?php echo $lang["add_button"];?> Lokasi</h4> </div> <div class="modal-body" id="isi_lokasi"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>

    <div class="modal" id="modal_lokasi_edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> <h4 class="modal-title">Edit Lokasi</h4> </div> <div class="modal-body" id="isi_lokasi_edit"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>    

</section><!-- /.content -->

        <script type="text/javascript">

      $("#add_lokasi").click(function() {

          $.ajax({
              url : "<?=base_admin();?>modul/pendaftaran_ppl/lokasi_add.php",
              type : "GET",
              success: function(data) {
                  $("#isi_lokasi").html(data);
              }
          });

      $('#modal_lokasi').modal({ keyboard: false,backdrop:'static',show:true });

    });


    $(".table").on('click','.edit_data',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);

        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/pendaftaran_ppl/lokasi_edit.php",
            type : "post",
            data : {id_data:id},
            success: function(data) {
                $("#isi_lokasi_edit").html(data);
                $("#loadnya").hide();
          }
        });

    $('#modal_lokasi_edit').modal({ keyboard: false,backdrop:'static' });

    });

    $(".table").on('click','.hapus_data',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);

        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/pendaftaran_ppl/pendaftaran_ppl_action.php?act=delete_lokasi",
            type : "post",
            data : {id_data:id},
            success: function(data) {
              $("#loadnya").hide();
              dataTable.draw();
          }
        });
    });


setting_lokasi = $("#setting_lokasi").DataTable({
       "fnCreatedRow": function( nRow, aData, iDataIndex ) {
            var indek = aData.length-1;
            $('td:eq('+indek+')', nRow).html('<?=$edit;?> <?=$del;?>');
              $(nRow).attr('id', 'line_'+aData[indek]);
              },
              destroy : true,
              "lengthMenu": [[10,100, 200,500,1000, 5000], [10,100, 200,500,1000, 5000]],
           'bProcessing': true,
            'bServerSide': true,
            'order': [],
           'columnDefs': [ {
            'targets': [-1,0],
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
              url :'<?=base_admin();?>modul/pendaftaran_ppl/lokasi_data.php',
            type: 'post',  // method  , by default get
            data: function ( d ) {
              d.priode = $("#priode_filter").val();
            },
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          }
      });
//filter
$('#filter').on('click', function() {
  setting_lokasi.ajax.reload();
});


  $('#dtb_lokasi_kkn').on('draw.dt', function() {
          init_selected()
      });

      $('#select_all').on('click', function() {
          select_deselect('select')
      });
      $('#deselect_all').on('click', function() {
          select_deselect('unselect')
  });



  $(document).on('click', '#dtb_lokasi_kkn tbody tr td', function(event) {
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
      var table_select = $('#dtb_lokasi_kkn tbody tr.selected');
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
          $('#dtb_lokasi_kkn tbody tr').addClass('DTTT_selected selected')
      } else {
          $('#dtb_lokasi_kkn tbody tr').removeClass('DTTT_selected selected')
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
            url: '<?=base_admin();?>modul/pendaftaran_ppl/pendaftaran_ppl_action.php?act=del_massal_lokasi',
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
/*
  $('#filter').on('click', function() {
    dataTable = $("#dtb_lokasi_kkn").DataTable({
   "fnCreatedRow": function( nRow, aData, iDataIndex ) {
    var indek = aData.length-1;
    $('td:eq('+indek+')', nRow).html('<?=$edit;?> <?=$del;?>');
      $(nRow).attr('id', 'line_'+aData[indek]);
      },
      "dom": "<'row'<'col-sm-12'B>>" + "<'row'<'col-sm-6'l><'col-sm-6'f>>" +"<'row'<'col-sm-12'tr>>" +"<'row'<'col-sm-5'i><'col-sm-7'p>>",

      buttons: [
      {
         extend: 'collection',
         text: 'Export Data',
         buttons: [ 'pdfHtml5', 'csvHtml5', 'copyHtml5', 'excelHtml5' ],

      }
      ],
      destroy : true,
     'bProcessing': true,
      'bServerSide': true,
           "columnDefs": [ {
         "targets": [0,7],
        "orderable": false,
        "searchable": false

      } ],
      "ajax":{
       url :"<?=base_admin();?>modul/pendaftaran_ppl/pendaftaran_ppl_data.php",
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

});*/

  $("#fakultas_filter").change(function(){

        $.ajax({
        type : "post",
        url : "<?=base_admin();?>modul/pendaftaran_ppl/get_jurusan_filter.php",
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
