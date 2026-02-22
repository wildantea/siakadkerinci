<style type="text/css">
  /* The switch - the box around the slider */
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

/* Hide default HTML checkbox */
.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

/* The slider */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>
<!-- Main content -->
<section class="content-header">
    <h1>
        Manage Priode Kukerta/PPL
    </h1>
        <ol class="breadcrumb">
          <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
          <li><a href="<?=base_index();?>pendaftaran-kukerta">Pendaftaran Kukerta/PPL</a></li>
          <li class="active">Setting Priode Kukerta/PPL</li>
    </ol>
</section>
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
            <div class="box-body">
              <?php
                foreach ($db->fetch_all("sys_menu") as $isi) {
                    if (uri_segment(1)==$isi->url) {
                        if ($role_act["insert_act"]=="Y") {
              ?>
              <a href="<?=base_index();?>pendaftaran-kukerta" class="btn btn-default"><span class="fa fa-step-backward"></span> Kembali </a>
              <a id="add_priode" class="btn btn-primary "><i class="fa fa-plus"></i> <?php echo $lang["add_button"];?> Priode</a>
              <?php
                        }
                    }
                }
              ?>

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
            <table id="dtb_priode_kkn" class="table table-bordered table-striped">
              <thead>
                  <tr>
                    <th>No</th>
                    <th>Priode</th>
                    <th>Nama Periode</th>
                    <th>Periode</th>
                 
                    <th>Waktu Pendaftaran</th>
                    <th>Input Nilai</th>
                   
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
                $edit = "<a data-id='+aData[indek]+'  class=\"btn btn-primary edit_data \"><i class=\"fa fa-pencil\"></i></a>";
              } else {
                  $edit ="";
              }
            if ($role_act['del_act']=='Y') {
                $del = "<button data-id='+aData[indek]+' data-uri=".base_admin()."modul/pendaftaran_kukerta/pendaftaran_kukerta_action.php?act=delete_priode".' class="btn btn-danger hapus_data "><i class="fa fa-trash"></i></button>';
            } else {
                $del="";
            }
                             }
            }

            $set_active = "<label class=\"switch\" style=\"position:relative;top:-4px\"><input type=\"checkbox\" value='+aData[indek]+' onchange=\"set_aktif(this.value)\"><span class=\"slider round\"></span></label>"; 
             $set_active_selected = "<label class=\"switch\" style=\"position:relative;top:-4px\"><input type=\"checkbox\" value='+aData[indek]+' onchange=\"set_aktif(this.value)\" checked><span class=\"slider round\"></span></label>"; 

        ?>

    <div class="modal" id="modal_priode" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> <h4 class="modal-title"><?php echo $lang["add_button"];?> Priode</h4> </div> <div class="modal-body" id="isi_priode"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>

    <div class="modal" id="modal_priode_edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> <h4 class="modal-title">Edit Priode</h4> </div> <div class="modal-body" id="isi_priode_edit"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
</section><!-- /.content -->

        <script type="text/javascript">

      $("#add_priode").click(function() {

          $.ajax({
              url : "<?=base_admin();?>modul/pendaftaran_kukerta/priode_add.php",
              type : "GET",
              success: function(data) {
                  $("#isi_priode").html(data);
              }
          });

      $('#modal_priode').modal({ keyboard: false,backdrop:'static',show:true });

    });

    function set_aktif(id) {
       $.ajax({
              url : "<?=base_admin();?>modul/pendaftaran_kukerta/pendaftaran_kukerta_action.php?act=set_aktif_periode",
              type : "POST",
              data : {
                 id : id
              },
              success: function(data) {
                  location.reload();
              }
          });
    }


    $(".table").on('click','.edit_data',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);

        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/pendaftaran_kukerta/priode_edit.php",
            type : "post",
            data : {id_data:id},
            success: function(data) {
                $("#isi_priode_edit").html(data);
                $("#loadnya").hide();
          }
        });

    $('#modal_priode_edit').modal({ keyboard: false,backdrop:'static' });

    });

    $(".table").on('click','.hapus_data',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);

        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/pendaftaran_kukerta/pendaftaran_kukerta_action.php?act=delete_priode",
            type : "post",
            data : {id_data:id},
            success: function(data) {
              $("#loadnya").hide();
              dataTable.draw();
          }
        });
    });

    var dataTable = $("#dtb_priode_kkn").DataTable({
               "fnCreatedRow": function( nRow, aData, iDataIndex ) {
                var indek = aData.length-2;
                var status = aData[6];
                //alert(status);
                if (status==0) {
                   $('td:eq('+indek+')', nRow).html('<?= $set_active;?> <?= $edit;?> <?=$del;?>');
                  $(nRow).attr('id', 'line_'+aData[indek]);
                }else{
                   $('td:eq('+indek+')', nRow).html('<?= $set_active_selected;?> <?= $edit;?> <?=$del;?>');
                  $(nRow).attr('id', 'line_'+aData[indek]);
                }
               
                  },
               'bProcessing': true,
                'bServerSide': true,
                
               'columnDefs': [ {
                'targets': [4],
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
                  url :'<?=base_admin();?>modul/pendaftaran_kukerta/priode_data.php',
                type: 'post',  // method  , by default get
                error: function (xhr, error, thrown) {
                console.log(xhr);

                }
              },
            });

  $('#dtb_priode_kkn').on('draw.dt', function() {
          init_selected()
      });

      $('#select_all').on('click', function() {
          select_deselect('select')
      });
      $('#deselect_all').on('click', function() {
          select_deselect('unselect')
  });



  $(document).on('click', '#dtb_priode_kkn tbody tr td', function(event) {
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
      var table_select = $('#dtb_priode_kkn tbody tr.selected');
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
          $('#dtb_priode_kkn tbody tr').addClass('DTTT_selected selected')
      } else {
          $('#dtb_priode_kkn tbody tr').removeClass('DTTT_selected selected')
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
            url: '<?=base_admin();?>modul/pendaftaran_kukerta/pendaftaran_kukerta_action.php?act=del_massal_priode',
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
