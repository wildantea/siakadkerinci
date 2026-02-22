<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Sinkron Moodle
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>sinkron-moodle">Sinkron Moodle</a></li>
                        <li class="active">Sinkron Moodle List</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
                                <div class="box-header">
                                  <form class="form-horizontal" id="filter_kelas_form" method="POST" action="<?= base_url() ?>modul/jadwal_dosen/jadwal_dosen_act.php" >
                                  <div class="form-group">
                                    <label for="Semester" class="control-label col-lg-2">Fakultas</label>
                                    <div class="col-lg-5">
                                     <select class="form-control chzn-select" id="kode_fak" onchange="filter_fak(this.value)" >
                                      <option value="">-Pilih Fakultas-</option>                                       
                                      <?php
                                       $q = $db->query("select kode_fak,nama_resmi from fakultas");
                                       foreach ($q as $k) {
                                         echo "<option value='$k->kode_fak'>$k->nama_resmi</option>";
                                       }
                                      ?>
                                     </select>
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <label for="Semester" class="control-label col-lg-2">Jurusan</label>
                                    <div class="col-lg-5">
                                     <select class="form-control" name="kode_prodi" id="kode_prodi" >
                                      <option value="">-Pilih Jurusan-</option>
                                     
                                     </select>
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label for="Semester" class="control-label col-lg-2">Semester</label>
                                    <div class="col-lg-5">
                                     <select class="form-control chzn-select" name="semester" id="semester" >
                                      <option value="">-Pilih Semester-</option>
                                      <?php
                                      $qs = $db->query("select id_semester as sem_id,semester  from semester_ref order by id_semester desc");
                                      foreach ($qs as $vs) {
                                       echo "<option value='$vs->sem_id'>$vs->semester</option>";
                                     }
                                     ?>

                                   </select>
                                 </div>
                               </div>

                                <div class="form-group">
                                    <label for="Semester" class="control-label col-lg-2"></label>
                                    <div class="col-lg-5">
                                     <a class="btn btn-success" style="cursor: pointer;" id="btn_filter" onclick="filter_data()"  ><i class="fa fa-eye" ></i> Tampilkan</a>
                                     <a class="btn btn-success" style="cursor: pointer;" id="btn_filter" onclick="down_matkul()"  ><i class="fa fa-download" ></i> Down Mata Kuliah</a>
                                     <a class="btn btn-success" style="cursor: pointer;" id="btn_filter" onclick="down_dosen_kelas()"  ><i class="fa fa-download" ></i> Down Dosen Kelas</a>
                                     <a class="btn btn-success" style="cursor: pointer;" id="btn_filter" onclick="down_peserta_kelas()"  ><i class="fa fa-download" ></i> Down Peserta Kelas</a>
                                 </div> 
                               </div>

                             </form>
                            
                            </div><!-- /.box-header -->
                            <div class="box-body table-responsive">
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
        <ul class="nav nav-tabs">
          <li class="active"><a data-toggle="tab" href="#home">Mata Kuliah</a></li>
          <li><a data-toggle="tab" href="#menu1">Dosen Kelas</a></li>
          <li><a data-toggle="tab" href="#menu2">Peserta Kelas</a></li>
        </ul>

        <div class="tab-content">
          <div id="home" class="tab-pane fade in active">
            <br>
            <table id="dtb_sinkron_moodle" class="table table-bordered table-striped" style="padding-top: 10px">
                            <thead>
                                <tr>
                               <!--    <th>No</th> -->
                                  <th>shortname</th>
                                  <th>fullname</th>
                                  <th>category</th>
                                  <th>summary</th>
                                  <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
          </div>
          <div id="menu1" class="tab-pane fade">
            <br>
            <table id="dtb_dosen_kelas" class="table table-bordered table-striped" style="padding-top: 10px">
                            <thead>
                                <tr>
                               <!--    <th>No</th> -->
                                  <th>username</th>
                                  <th>password</th>
                                  <th>firstname</th>
                                  <th>lastname</th>
                                  <th>email</th>
                                  <th>course1</th>
                                  <th>role1</th>
                                  <th>cohort1</th>
                                  <th>group1</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
          </div>
          <div id="menu2" class="tab-pane fade">
            <br>
            <table id="dtb_peserta_kelas" class="table table-bordered table-striped" style="padding-top: 10px">
                            <thead>
                                <tr>
                               <!--    <th>No</th> -->
                                  <th>username</th>
                                  <th>password</th>
                                  <th>firstname</th>
                                  <th>lastname</th>
                                  <th>email</th>
                                  <th>course1</th>
                                  <th>role1</th>
                                  <th>cohort1</th>
                                  <th>group1</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
          </div>
        </div>
                        
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
                $edit = "<a data-id='+aData[indek]+' href=".base_index()."sinkron-moodle/edit/'+aData[indek]+' class=\"btn btn-primary btn-sm edit_data \" data-toggle=\"tooltip\" title=\"Edit\"><i class=\"fa fa-pencil\"></i></a>";
              } else {
                  $edit ="";
              }
            if ($role_act['del_act']=='Y') {
                $del = "<button data-id='+aData[indek]+' data-uri=".base_admin()."modul/sinkron_moodle/sinkron_moodle_action.php".' class="btn btn-danger hapus_dtb_notif btn-sm" data-toggle="tooltip" title="Hapus" data-variable="dtb_sinkron_moodle"><i class="fa fa-trash"></i></button>';
            } else {
                $del="";
            }
                             }
            }

        ?>

    </section><!-- /.content -->

        <script type="text/javascript">

          function filter_fak(val){
            $.ajax({
              type : "POST",
              data :{
                 kode_fak : val
              },
              url : '<?=base_admin();?>modul/sinkron_moodle/sinkron_moodle_action.php?act=get_jur',
              success : function(data){
                $("#kode_prodi").html(data); 
              }
            })
          }

          function down_matkul(){
              var kode_fak = $("#kode_fak").val();
              var kode_jur = $("#kode_prodi").val();
              var semester = $("#semester").val();
              document.location="<?= base_url() ?>dashboard/modul/sinkron_moodle/down_matkul.php?kode_fak="+kode_fak+"&kode_jur="+kode_jur+"&semester="+semester;
          }

           function down_dosen_kelas(){
              var kode_fak = $("#kode_fak").val();
              var kode_jur = $("#kode_prodi").val();
              var semester = $("#semester").val(); 
              document.location="<?= base_url() ?>dashboard/modul/sinkron_moodle/down_dosen_kelas.php?kode_fak="+kode_fak+"&kode_jur="+kode_jur+"&semester="+semester;
          }

          function down_peserta_kelas(){
              var kode_fak = $("#kode_fak").val();
              var kode_jur = $("#kode_prodi").val();
              var semester = $("#semester").val(); 
              document.location="<?= base_url() ?>dashboard/modul/sinkron_moodle/down_peserta_kelas.php?kode_fak="+kode_fak+"&kode_jur="+kode_jur+"&semester="+semester;
          }
      
      
      
      var dtb_sinkron_moodle = $("#dtb_sinkron_moodle").DataTable({
           "fnCreatedRow": function( nRow, aData, iDataIndex ) {
            var indek = aData.length-1;
            $('td:eq('+indek+')', nRow).html('<a href="<?=base_index();?>sinkron-moodle/detail/'+aData[indek]+'"  class="btn btn-success btn-sm" data-toggle="tooltip" title="Detail"><i class="fa fa-eye"></i></a> <?=$edit;?> <?=$del;?>');
              $(nRow).attr('id', 'line_'+aData[indek]);
              },
              "dom": "<'row'<'col-sm-12'B>>" + "<'row'<'col-sm-6'l><'col-sm-6'f>>" +"<'row'<'col-sm-12'tr>>" +"<'row'<'col-sm-5'i><'col-sm-7'p>>",

              buttons: [
              {
                 extend: 'collection',
                 text: 'Export Data',
                 buttons: [ 'pdfHtml5', 
                  {
                   
                      extend: 'csvHtml5', 
                      title: 'Data Mata Kuliah',
                      fieldSeparator: ';'
                  },
                 
                 'copyHtml5', 'excelHtml5' ],

              }
              ],
              aLengthMenu: [
                [25, 50, 100, 500, -1],
                [25, 50, 100, 500, "All"]
            ],
           'bProcessing': true,
            'bServerSide': true,
            
           'columnDefs': [ {
            'targets': [4],
              'orderable': false,
              'searchable': false
            },
                // {
                //   'width': '15%',
                //   'targets': 0,
                //   'orderable': false,
                //   'searchable': false,
                //   'className': 'dt-center'
                // },
                 {
                  'visible': false,
                  'targets': [4],
                  'orderable': false,
                  'searchable': false,
                  'className': 'dt-center'
                }
             ],

    
            'ajax':{
              url :'<?=base_admin();?>modul/sinkron_moodle/sinkron_moodle_data.php',
              type: 'post',  // method  , by default get
              data : function(d){
                 d.kode_fak = $("#kode_fak").val();
                 d.kode_jur = $("#kode_prodi").val();
                 d.semester = $("#semester").val();
              },
              error: function (xhr, error, thrown) {
              console.log(xhr);

            }
          },
        });

      var dtb_dosen_kelas = $("#dtb_dosen_kelas").DataTable({
           // "fnCreatedRow": function( nRow, aData, iDataIndex ) {
           //  var indek = aData.length-1;
           //  $('td:eq('+indek+')', nRow).html('<a href="<?=base_index();?>sinkron-moodle/detail/'+aData[indek]+'"  class="btn btn-success btn-sm" data-toggle="tooltip" title="Detail"><i class="fa fa-eye"></i></a> <?=$edit;?> <?=$del;?>');
           //    $(nRow).attr('id', 'line_'+aData[indek]);
           //    },
              "dom": "<'row'<'col-sm-12'B>>" + "<'row'<'col-sm-6'l><'col-sm-6'f>>" +"<'row'<'col-sm-12'tr>>" +"<'row'<'col-sm-5'i><'col-sm-7'p>>",

              buttons: [
              {
                 extend: 'collection',
                 text: 'Export Data',
                 buttons: [ 'pdfHtml5', 
                  {
                   
                      extend: 'csvHtml5', 
                      title: 'Data Dosen Kelas',
                      fieldSeparator: ';'
                  },
                 
                 'copyHtml5', 'excelHtml5' ],

              }
              ],
              aLengthMenu: [
                [25, 50, 100, 500, -1],
                [25, 50, 100, 500, "All"]
            ],
           'bProcessing': true,
            'bServerSide': true,
            
           // 'columnDefs': [ 
           // { 
           //  'targets': [4],
           //    'orderable': false,
           //    'searchable': false
           //  },
           //      // {
           //      //   'width': '5%',
           //      //   'targets': 0,
           //      //   'orderable': false,
           //      //   'searchable': false,
           //      //   'className': 'dt-center'
           //      // },
           //       {
           //        'visible': false,
           //        'targets': [4],
           //        'orderable': false,
           //        'searchable': false,
           //        'className': 'dt-center'
           //      }
           //   ],

    
            'ajax':{
              url :'<?=base_admin();?>modul/sinkron_moodle/dosen_kelas_data.php',
              type: 'post',  // method  , by default get
              data : function(d){
                 d.kode_fak = $("#kode_fak").val();
                 d.kode_jur = $("#kode_prodi").val();
                 d.semester = $("#semester").val();
              },
              error: function (xhr, error, thrown) {
              console.log(xhr);

            }
          },
        });

       var dtb_peserta_kelas = $("#dtb_peserta_kelas").DataTable({ 
           // "fnCreatedRow": function( nRow, aData, iDataIndex ) {
           //  var indek = aData.length-1;
           //  $('td:eq('+indek+')', nRow).html('<a href="<?=base_index();?>sinkron-moodle/detail/'+aData[indek]+'"  class="btn btn-success btn-sm" data-toggle="tooltip" title="Detail"><i class="fa fa-eye"></i></a> <?=$edit;?> <?=$del;?>');
           //    $(nRow).attr('id', 'line_'+aData[indek]);
           //    },
              "dom": "<'row'<'col-sm-12'B>>" + "<'row'<'col-sm-6'l><'col-sm-6'f>>" +"<'row'<'col-sm-12'tr>>" +"<'row'<'col-sm-5'i><'col-sm-7'p>>",

              buttons: [
              {
                 extend: 'collection',
                 text: 'Export Data',
                 buttons: [ 'pdfHtml5', 
                  {
                   
                    extend: 'csvHtml5', 
                    title: 'Data Peserta Kelas',
                    fieldSeparator: ';'
                  },
                 
                 'copyHtml5', 'excelHtml5' ],

              }
              ],
              aLengthMenu: [
                [25, 50, 100, 500, -1],
                [25, 50, 100, 500, "All"]
            ],
           'bProcessing': true,
            'bServerSide': true,
            
           // 'columnDefs': [ {
           //  'targets': [4],
           //    'orderable': false,
           //    'searchable': false
           //  },
           //      // {
           //      //   'width': '5%',
           //      //   'targets': 0,
           //      //   'orderable': false,
           //      //   'searchable': false,
           //      //   'className': 'dt-center'
           //      // },
           //       {
           //        'visible': false,
           //        'targets': [4],
           //        'orderable': false,
           //        'searchable': false,
           //        'className': 'dt-center'
           //      }
           //   ],
 
    
            'ajax':{
              url :'<?=base_admin();?>modul/sinkron_moodle/peserta_kelas_data.php',
              type: 'post',  // method  , by default get
              data : function(d){
                 d.kode_fak = $("#kode_fak").val();
                 d.kode_jur = $("#kode_prodi").val();
                 d.semester = $("#semester").val();
              },
              error: function (xhr, error, thrown) {
              console.log(xhr);

            }
          },
        });

      function filter_data(){
          dtb_sinkron_moodle.ajax.reload();
          dtb_dosen_kelas.ajax.reload();
          dtb_peserta_kelas.ajax.reload();
      }

  $('#dtb_sinkron_moodle').on('draw.dt', function() {
          init_selected()
      });

      $('#select_all').on('click', function() {
          select_deselect('select')
      });
      $('#deselect_all').on('click', function() {
          select_deselect('unselect')
  });



  $(document).on('click', '#dtb_sinkron_moodle tbody tr td', function(event) {
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
      var table_select = $('#dtb_sinkron_moodle tbody tr.selected');
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
          $('#dtb_sinkron_moodle tbody tr').addClass('DTTT_selected selected')
      } else {
          $('#dtb_sinkron_moodle tbody tr').removeClass('DTTT_selected selected')
      }
      init_selected()
  }




/* Add a click handler for the delete row */
  $('#bulk_delete').click( function() {
    var anSelected = fnGetSelected( dtb_sinkron_moodle );
    var data_array_id = check_selected();
    var all_ids = data_array_id.toString();
    $('#ucing').modal({ keyboard: false }).one('click', '#delete', function (e) {
        $('#loadnya').show();
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '<?=base_admin();?>modul/sinkron_moodle/sinkron_moodle_action.php?act=del_massal',
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
                               dtb_sinkron_moodle.draw();
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
            