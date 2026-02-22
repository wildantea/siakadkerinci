<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Skala Nilai
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>skala-nilai">Skala Nilai</a></li>
                        <li class="active">Skala Nilai List</li>
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
                                      <a href="<?=base_index();?>skala-nilai/tambah" class="btn btn-primary "><i class="fa fa-plus"></i> <?php echo $lang["add_button"];?></a>
                                      <?php
                                          }
                                          if ($role_act["import_act"]=="Y") {
                                            ?>
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
           <form class="form-horizontal" method="post" action="<?=base_admin();?>modul/skala_nilai/download_data.php">
                      <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Fakultas</label>
                        <div class="col-lg-5">
                        <select id="fakultas_filter" name="fakultas" data-placeholder="Pilih Angkatan ..." class="form-control chzn-select" tabindex="2">
                        <option value="all">Semua</option>
               <?php 
               foreach ($db->fetch_all("fakultas") as $isi) {
                  echo "<option value='$isi->kode_fak'>$isi->nama_resmi</option>";
               } ?>
              </select>

 </div>
                      </div><!-- /.form-group -->
  <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Program Studi</label>
                        <div class="col-lg-5">
                        <select id="jurusan_filter" name="jurusan" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2">
                        <option value="all">Semua</option>
  
              </select>

 </div>
                      </div><!-- /.form-group -->
  <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Jenjang</label>
                        <div class="col-lg-5">
                        <select id="jenjang" name="jenjang" data-placeholder="Pilih Jenjang ..." class="form-control chzn-select" tabindex="2">
                        <option value="all">Semua</option>
                                                      <?php 
               foreach ($db->query("select jenjang_pendidikan.id_jenjang, jenjang_pendidikan.jenjang from jurusan inner join jenjang_pendidikan
on jurusan.id_jenjang=jenjang_pendidikan.id_jenjang
group by jenjang") as $isi) {
                  echo "<option value='$isi->id_jenjang'>$isi->jenjang</option>";
               } ?>
              </select>

 </div>
                      </div><!-- /.form-group -->

              <div class="form-group">
                    <label for="Angkatan" class="control-label col-lg-2">Berlaku Angkatan</label>
                    <div class="col-lg-5">
                    <select id="angkatan" name="angkatan" data-placeholder="Pilih Angkatan ..." class="form-control chzn-select" tabindex="2" required="">
                      <option value="all">Semua</option>
                    <?php
                   $angkatan = $db->query("select distinct(mulai_smt) as mulai_smt from mahasiswa order by mulai_smt desc");
                   foreach ($angkatan as $ak) {
                     echo "<option value='$ak->mulai_smt'>$ak->mulai_smt</option>";
                   }
                    ?>
                    </select>
                    </div>
                 
              </div><!-- /.form-group -->

                      <div class="form-group">
                        <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                        <div class="col-lg-10">
                          <span id="filter" class="btn btn-primary"><i class="fa fa-refresh"></i> Filter</span>
                         <button type="submit" class="btn btn-success"><i class="fa fa-cloud-download"></i> Download Data</button>
                       </div>
                      </div><!-- /.form-group -->
                    </form>
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
                        <table id="dtb_skala_nilai" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                  <th rowspan="2" class="dt-center">No</th>
                                   <th colspan="2" class="dt-center">Nilai</th>
                                  <th colspan="2" class="dt-center">Bobot Nilai</th>
                                  <th colspan="2" class="dt-center">Tanggal Efektif </th>
                                  <th rowspan="2" class="dt-center">Angkatan</th>
                                  <th rowspan="2" class="dt-center">Prodi</th>
                                  <th rowspan="2" class="dt-center">Action</th>
                                </tr>
                                <tr>
                                   <th>Huruf</th>
                                    <th>Indeks</th>
                                  <th>Minimum</th>
                                  <th>Maksimum</th>
                                  <th>Awal</th>
                                  <th>Akhir</th>
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
                $edit = "<a data-id='+aData[indek]+' href=".base_index()."skala-nilai/edit/'+aData[indek]+' class=\"btn btn-primary btn-sm edit_data \" data-toggle=\"tooltip\" title=\"Edit\"><i class=\"fa fa-pencil\"></i></a>";
              } else {
                  $edit ="";
              }
            if ($role_act['del_act']=='Y') {
                $del = "<button data-id='+aData[indek]+' data-uri=".base_admin()."modul/skala_nilai/skala_nilai_action.php".' class="btn btn-danger hapus_dtb_notif btn-sm" data-toggle="tooltip" title="Hapus" data-variable="dtb_skala_nilai"><i class="fa fa-trash"></i></button>';
            } else {
                $del="";
            }
                             }
            }

        ?>
      <div class="modal" id="modal_import_mat" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&nbsp;</span></button> <h4 class="modal-title">Import Excel </h4> </div> <div class="modal-body" id="isi_import_mat"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>  
    </section><!-- /.content -->

        <script type="text/javascript">
      
                 $("#import_mat").click(function() {
          $.ajax({
              url : "<?=base_admin();?>modul/skala_nilai/import.php",
              type : "GET",
              success: function(data) {
                  $("#isi_import_mat").html(data);
              }
          });

      $('#modal_import_mat').modal({ keyboard: false,backdrop:'static',show:true });

    }); 

    
      var dtb_skala_nilai = $("#dtb_skala_nilai").DataTable({
           "fnCreatedRow": function( nRow, aData, iDataIndex ) {
            var indek = aData.length-1;
            $('td:eq('+indek+')', nRow).html('<?=$edit;?> <?=$del;?>');
              $(nRow).attr('id', 'line_'+aData[indek]);
              },
           'bProcessing': true,
            'bServerSide': true,
            
           'columnDefs': [ {
            'targets': [-1],
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
              url :'<?=base_admin();?>modul/skala_nilai/skala_nilai_data.php',
            type: 'post',  // method  , by default get
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });

$('#filter').on('click', function() {
  dtb_skala_nilai = $("#dtb_skala_nilai").DataTable({
           "fnCreatedRow": function( nRow, aData, iDataIndex ) {
            var indek = aData.length-1;
            $('td:eq('+indek+')', nRow).html('<?=$edit;?> <?=$del;?>');
              $(nRow).attr('id', 'line_'+aData[indek]);
              },
            destroy : true,
           'bProcessing': true,
            'bServerSide': true,
                 "columnDefs": [ {
               "targets": [0,-1],
              "orderable": false,
              "searchable": false

            } ],
            "ajax":{
              url :'<?=base_admin();?>modul/skala_nilai/skala_nilai_data.php',
            type: "post",  // method  , by default get
            data: function ( d ) {
                    d.fakultas = $("#fakultas_filter").val();
                    d.jurusan = $("#jurusan_filter").val();
                    d.jenjang = $("#jenjang").val();
                    d.angkatan = $("#angkatan").val();
                  },
          error: function (xhr, error, thrown) {
            console.log(xhr);
            }
          },
        });

      });

  $('#dtb_skala_nilai').on('draw.dt', function() {
          init_selected()
      });

      $('#select_all').on('click', function() {
          select_deselect('select')
      });
      $('#deselect_all').on('click', function() {
          select_deselect('unselect')
  });



  $(document).on('click', '#dtb_skala_nilai tbody tr td', function(event) {
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
      var table_select = $('#dtb_skala_nilai tbody tr.selected');
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
          $('#dtb_skala_nilai tbody tr').addClass('DTTT_selected selected')
      } else {
          $('#dtb_skala_nilai tbody tr').removeClass('DTTT_selected selected')
      }
      init_selected()
  }




/* Add a click handler for the delete row */
  $('#bulk_delete').click( function() {
    var anSelected = fnGetSelected( dtb_skala_nilai );
    var data_array_id = check_selected();
    var all_ids = data_array_id.toString();
    $('#ucing').modal({ keyboard: false }).one('click', '#delete', function (e) {
        $('#loadnya').show();
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '<?=base_admin();?>modul/skala_nilai/skala_nilai_action.php?act=del_massal',
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
                               dtb_skala_nilai.draw();
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

    $("#fakultas_filter").change(function(){

          $.ajax({
          type : "post",
          url : "<?=base_admin();?>modul/skala_nilai/get_jurusan_filter.php",
          data : {fakultas:this.value},
          success : function(data) {
              $("#jurusan_filter").html(data);
              $("#jurusan_filter").trigger("chosen:updated");

          }
      });

    });
</script>
            