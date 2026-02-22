<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Generate Nilai
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>generate-nilai">Generate Nilai</a></li>
                        <li class="active">Generate Nilai List</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
                                <div class="box-header">
                                <form id="input_generate_nilai" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/generate_nilai/generate_nilai_action.php?act=in">
                      <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Semester <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-6">
            <select  id="sem_id" name="sem_id" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2" required>
               <option value=""></option>
               <?php foreach ($db->query("select id_semester,semester from semester_ref order by id_semester desc") as $isi) {
                  echo "<option value='$isi->id_semester'>$isi->semester</option>";
               } ?>
              </select> 
            </div>
                      </div><!-- /.form-group -->
                    <div class="form-group" style="display: none"> 
                        <label for="kode_jur" class="control-label col-lg-2">Jurusan <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-6">
                          <select  id="kode_jur" name="kode_jur" data-placeholder="Pilih kode_jur ..." class="form-control chzn-select" tabindex="2" required>
                             <option value="all">Semua</option> 
                             <?php foreach ($db->fetch_all("jurusan") as $isi) {
                                echo "<option value='$isi->kode_jur'>$isi->nama_jur</option>";
                             } ?>
                            </select> 
                          </div>
                      </div><!-- /.form-group -->

              <div class="form-group" style="display: none">
                <label for="total" class="control-label col-lg-2">total </label>
                <div class="col-lg-6">
                  <input type="text" name="total" placeholder="total" class="form-control" >
                </div>
              </div><!-- /.form-group -->
              
                      

              <div class="form-group">
                <div class="col-lg-8">
                  <div class="modal-footer"> 
                    <a style="cursor: pointer;"  onclick="generate_nilai()" class="btn btn-primary"><i class="fa fa-save"></i> Generate</a>
                  
                  </div>
                </div>
              </div><!-- /.form-group -->

      </form>
                            </div><!-- /.box-header -->
                            <div class="box-body table-responsive">
                              <div id="hasil">
                                
                              </div>
                               <!--  <div class="row">
                                    <div class="col-sm-12" style="text-align: right;margin-bottom: 10px">
                                    <button id="select_all" class="btn btn-primary btn-xs"><i class="fa fa-check-square-o"></i> <?php echo $lang["select_all"];?></button>
                                    <button id="deselect_all" class="btn btn-primary btn-xs"><i class="fa fa-remove"></i> <?php echo $lang["deselect_all"];?></button>
                                    <button id="bulk_delete" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> <?php echo $lang["delete_selected"];?></button> <span class="selected-data"></span>
                            </div>
                            </div> -->
 <div class="alert alert-warning fade in error_data_delete" style="display:none">
          <button type="button" class="close hide_alert_notif">&times;</button>
          <i class="icon fa fa-warning"></i> <span class="isi_warning_delete"></span>
        </div>
                        <table id="dtb_generate_nilai3" class="table table-bordered table-striped" style="display: none">
                            <thead>
                                <tr>
                                  <th>No</th>
                                  <th>Semester</th>
                                  <th>Jurusan</th>
                                  <th>total</th>
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
                $del = "<button data-id='+aData[indek]+' data-uri=".base_admin()."modul/generate_nilai/generate_nilai_action.php".' class="btn btn-danger hapus_dtb_notif btn-sm" data-toggle="tooltip" title="Hapus" data-variable="dtb_generate_nilai"><i class="fa fa-trash"></i></button>';
            } else {
                $del="";
            }
                             }
            }

        ?>

    <div class="modal" id="modal_generate_nilai" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> <h4 class="modal-title"><?php echo $lang["add_button"];?> Generate Nilai</h4> </div> <div class="modal-body" id="isi_generate_nilai"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
    
    </section><!-- /.content -->

        <script type="text/javascript">

      
      $("#add_generate_nilai").click(function() {
          $.ajax({
              url : "<?=base_admin();?>modul/generate_nilai/generate_nilai_add.php",
              type : "GET",
              success: function(data) {
                  $("#isi_generate_nilai").html(data);
              }
          });

      $('#modal_generate_nilai').modal({ keyboard: false,backdrop:'static',show:true });

    });
    
      
    $(".table").on('click','.edit_data',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);

        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/generate_nilai/generate_nilai_edit.php",
            type : "post",
            data : {id_data:id},
            success: function(data) {
                $("#isi_generate_nilai").html(data);
                $("#loadnya").hide();
          }
        });

    $('#modal_generate_nilai').modal({ keyboard: false,backdrop:'static' });

    });
    
      var dtb_generate_nilai = $("#dtb_generate_nilai").DataTable({
           "fnCreatedRow": function( nRow, aData, iDataIndex ) {
            var indek = aData.length-1;
            $('td:eq('+indek+')', nRow).html('<a href="<?=base_index();?>generate-nilai/detail/'+aData[indek]+'"  class="btn btn-success btn-sm" data-toggle="tooltip" title="Detail"><i class="fa fa-eye"></i></a> <?=$edit;?> <?=$del;?>');
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
              url :'<?=base_admin();?>modul/generate_nilai/generate_nilai_data.php',
            type: 'post',  // method  , by default get
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });

    function generate_nilai() {
      var sem_id = $("#sem_id").val();
      var kode_jur = $("#kode_jur").val();
       $.ajax({
              url : "<?=base_admin();?>modul/generate_nilai/generate_nilai_action.php?act=generate_nilai",
              type : "POST",
              data : {
                 sem_id : sem_id,
                 kode_jur : kode_jur
              },
              success: function(data) {
                $("#hasil").html(data);
              }
          });
    }

  $('#dtb_generate_nilai').on('draw.dt', function() {
          init_selected()
      });

      $('#select_all').on('click', function() {
          select_deselect('select')
      });
      $('#deselect_all').on('click', function() {
          select_deselect('unselect')
  });



  $(document).on('click', '#dtb_generate_nilai tbody tr td', function(event) {
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
      var table_select = $('#dtb_generate_nilai tbody tr.selected');
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
          $('#dtb_generate_nilai tbody tr').addClass('DTTT_selected selected')
      } else {
          $('#dtb_generate_nilai tbody tr').removeClass('DTTT_selected selected')
      }
      init_selected()
  }




/* Add a click handler for the delete row */
  $('#bulk_delete').click( function() {
    var anSelected = fnGetSelected( dtb_generate_nilai );
    var data_array_id = check_selected();
    var all_ids = data_array_id.toString();
    $('#ucing').modal({ keyboard: false }).one('click', '#delete', function (e) {
        $('#loadnya').show();
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '<?=base_admin();?>modul/generate_nilai/generate_nilai_action.php?act=del_massal',
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
                               dtb_generate_nilai.draw();
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
            