<!-- Main content -->
<section class="content-header">
    <h1>
        Manage Lokasi Kukerta/PPL
    </h1>
          <ol class="breadcrumb">
          <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
          <li><a href="<?=base_index();?>pendaftaran-kukerta">Pendaftaran Kukerta</a></li>
          <li class="active">Setting Lokasi Kukerta</li>
    </ol>
</section>
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
            <div class="box-body">
               <form class="form-horizontal" method="post">
            <div class="col-lg-4 col-lg-offset-1">
             <!--  <div class="form-group">
                <label for="Semester" class="control-label">Fakultas</label>
                <select id="fakultas_filter" name="fakultas_filter" data-placeholder="Pilih Angkatan ..." class="form-control chzn-select" tabindex="2">
                 <option value="all">Semua</option>
                 <?php
                 foreach ($db->fetch_all("fakultas") as $isi) {
                  echo "<option value='$isi->kode_fak'>$isi->nama_resmi</option>";
                } ?>
              </select>
            </div>

            <div class="form-group">
              <label for="Semester" class="control-label">Jurusan</label>
              <select id="jurusan_filter" name="jurusan_filter" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2">
                <option value="all">Semua</option>
              </select>
            </div> -->

            <div class="form-group">
              <label for="Semester" class="control-label">Priode</label>
              <select id="priode_filter" name="priode_filter" onchange="pilih_lokasi(this.value)" data-placeholder="Pilih Priode Kukerta ..." class="form-control chzn-select" tabindex="2">
               <option value="all">Semua</option>
               <?php
               foreach ($db->query("select * from priode_kkn jm join semester_ref sr on jm.priode=sr.id_semester join jenis_semester j on sr.id_jns_semester=j.id_jns_semester order by sr.id_semester desc") as $isi) {
                echo "<option value='$isi->id_priode'>$isi->jns_semester $isi->tahun/".($isi->tahun+1)."</option>";
              } ?>
            </select>
          </div>

           <!-- <div class="form-group">
              <label for="Semester" class="control-label">Lokasi</label>
              <select id="id_lokasi" name="id_lokasi" data-placeholder="Pilih Priode Kukerta ..." class="form-control chzn-select" tabindex="2">
               <option value="all">Semua</option>
               //<?php
             //  foreach ($db->query("select id_lokasi,nama_lokasi from lokasi_kkn order by nama_lokasi asc") as $isi) {
               // echo "<option value='$isi->id_lokasi'>$isi->nama_lokasi</option>";
             // } ?>
            </select>
          </div> -->

         <!--   <div class="form-group">
              <label for="Semester" class="control-label">Jenis Kelamin</label>
              <input type="radio" name="jk" value="L"> Laki-laki 
              <input type="radio" name="jk" value="P"> Perempuan 
          </div> -->
 
          <div class="form-group">
            <span id="filter" class="btn btn-primary" style="margin-top: 20px;">
              <i class="fa fa-refresh"></i> Filter 

            </span>
            <!--  <span id="filter" class="btn btn-success" style="margin-top: 20px;" onclick="download_data()">
              <i class="fa fa-download"></i> Download
              
            </span> -->
              <span  id="add_lokasi" class="btn btn-success" style="margin-top: 20px;" >
              <i class="fa fa-plus"></i> <?php echo $lang["add_button"];?> Lokasi
              
            </span>
          </div>
        </div><!-- /.form-group -->
      </form>
                   
            </div>
          </div><!-- /.box-header -->
          <div class="box-body table-responsive">
            <div class="row">
                <div class="col-sm-12" style="text-align: right;margin-bottom: 10px">
                  <button id="select_all" class="btn btn-primary btn-xs"><i class="fa fa-check-square-o"></i> <?php echo $lang["select_all"];?></button>
                  <button id="deselect_all" class="btn btn-primary btn-xs"><i class="fa fa-remove"></i> <?php echo $lang["deselect_all"];?></button>
                  <button id="bulk_delete" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> <?php echo $lang["delete_selected"];?></button> <span class="selected-data"></span>
                </div>
            </div>
            <table id="dtb_lokasi_kkn" class="table table-bordered table-striped">
              <thead>
                  <tr>
                    <th>No</th>
                    <th>Lokasi</th>
                   
                    <th>Periode</th>
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
            if (uri_segment(1)==$isi->url) {
              //check edit permission
              if ($role_act["up_act"]=="Y") {
                $edit = "<a data-id='+aData[indek]+'  class=\"btn btn-primary edit_data \"><i class=\"fa fa-pencil\"></i></a>";
              } else {
                  $edit ="";
              }
            if ($role_act['del_act']=='Y') {
                $del = "<button data-id='+aData[indek]+' data-uri=".base_admin()."modul/pendaftaran_kukerta/pendaftaran_kukerta_action.php?act=delete_lokasi".' class="btn btn-danger hapus_data "><i class="fa fa-trash"></i></button>';
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
              url : "<?=base_admin();?>modul/pendaftaran_kukerta/lokasi_add.php",
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
            url : "<?=base_admin();?>modul/pendaftaran_kukerta/lokasi_edit.php",
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
            url : "<?=base_admin();?>modul/pendaftaran_kukerta/pendaftaran_kukerta_action.php?act=delete_lokasi",
            type : "post",
            data : {id_data:id},
            success: function(data) {
              $("#loadnya").hide();
              dataTable.draw();
          }
        });
    });

      var dataTable = $("#dtb_lokasi_kkn").DataTable({
        "fnCreatedRow": function( nRow, aData, iDataIndex ) {
            var indek = aData.length-1;
            $('td:eq('+indek+')', nRow).html('<?=$edit;?> <?=$del;?>');
              $(nRow).attr('id', 'line_'+aData[indek]);
        },
         'bProcessing': true,
          'bServerSide': true,

         'columnDefs': [ {
          'targets': [3],
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
          url :'<?=base_admin();?>modul/pendaftaran_kukerta/lokasi_data.php',
          type: 'post',  // method  , by default get
          data: function ( d ) {
          //  d.jurusan = $("#jurusan").val();
            d.priode = $("#priode_filter").val();
          },
          error: function (xhr, error, thrown) {
          console.log(xhr);

          }
        },
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
            url: '<?=base_admin();?>modul/pendaftaran_kukerta/pendaftaran_kukerta_action.php?act=del_massal_lokasi',
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
        url :'<?=base_admin();?>modul/pendaftaran_kukerta/lokasi_data.php',
      type: "post",  // method  , by default get
      data: function ( d ) {
             // d.fakultas = $("#fakultas_filter").val();
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
        url : "<?=base_admin();?>modul/pendaftaran_kukerta/get_jurusan_filter.php",
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
