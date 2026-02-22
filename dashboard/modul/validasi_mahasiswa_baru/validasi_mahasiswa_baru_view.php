<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Validasi Mahasiswa Baru
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>validasi-mahasiswa-baru">Validasi Mahasiswa Baru</a></li>
                        <li class="active">Validasi Mahasiswa Baru List</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                  <div class="row">
                    <div class="col-xs-12"> 
                      <div class="box"> 
                      <!--   <div class="box-header">
                          <?php
                          foreach ($db->fetch_all("sys_menu") as $isi) {
                            if (uri_segment(1)==$isi->url) {
                              if ($role_act["insert_act"]=="Y") {
                                ?>
                                <a id="add_validasi_mahasiswa_baru" class="btn btn-primary "><i class="fa fa-plus"></i> Input Validasi </a>
                                <?php
                              }
                            }
                          }
                          ?>
                        </div> -->
                        <form id="form_filter" method="post" class="form-horizontal foto_banyak" action="<?= base_admin() ?>modul/validasi_mahasiswa_baru/validasi_mahasiswa_baru_action.php?act=validasi" novalidate="novalidate">

                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                              <h4 class="modal-title text-center">Validasi Mahasiswa Baru</h4>
                            </div>
                                <div class="form-group">
                                 <div class="col-lg-12" id="pesan"> 

                                 </div>
                               </div>
                      <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Periode Akademik</label>
                        <div class="col-lg-5">
                        <select id="tahun_akademik" name="tahun_akademik" data-placeholder="Pilih tahun_akademik ..." class="form-control chzn-select" tabindex="2" required="">
                          <?php 

                          $jur_kode = aksesProdi('mahasiswa.jur_kode');

                          $get_exist_periode = $db2->query("select id_semester,tahun_akademik from view_semester where id_semester in (select mulai_smt from mahasiswa where 1=1 $jur_kode)
                            order by id_semester desc");
                          $index = 0;
                          foreach ($get_exist_periode as $periode) {
                                echo "<option value='$periode->id_semester'>$periode->tahun_akademik</option>";
                          }
                          ?>
                            </select>
                          </div>
                      </div><!-- /.form-group -->

  <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Program Studi</label>
                        <div class="col-lg-5">
                        <select id="jurusan" name="jurusan" data-placeholder="Pilih Jurusan ..." class="form-control chzn-select" tabindex="2">
 <?php
                                looping_prodi();
                                ?>
              </select>

 </div>
                      </div><!-- /.form-group -->

                          
                                <div class="form-group">
                                  <label for="Nama Fakultas" class="control-label col-lg-2 col-md-2"></label>
                                  <div class="col-lg-5 col-md-2">
                                    <input type="submit" value="Filter" class="btn btn-primary">
                                  </div>
                                </div> 
                           
                         
                     
                          </form>
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
                          <table id="dtb_validasi_mahasiswa_baru" class="table table-bordered table-striped">
                            <thead>
                              <tr>
                                <th>No</th>
                                <th>No Pendaftaran</th>
                                <th>NIM</th>
                                <th>Nama</th>
                                <th>Angkatan</th>
                                <th>Jurusan</th>
                                <th>Action</th>
                                <th>Ket</th>
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
                $del = "<button data-id='+aData[indek]+' data-uri=".base_admin()."modul/validasi_mahasiswa_baru/validasi_mahasiswa_baru_action.php".' class="btn btn-danger hapus_dtb_notif btn-sm" data-toggle="tooltip" title="Hapus" data-variable="dtb_validasi_mahasiswa_baru"><i class="fa fa-trash"></i></button>';
            } else {
                $del="";
            }
                             }
            }

        ?>


     <div id="modal_validasi_error" class="modal fade" role="dialog"> 
        <div class="modal-dialog">
          
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title text-center error-validasi"></h4>
            </div>
            <div class="modal-body">
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">Close</button>
            </div>
          </div>
        
        </div>
      </div>
    </div>

      <!-- Modal -->
      <div id="modal_validasi" class="modal fade" role="dialog"> 
        <div class="modal-dialog">
          <form id="input_validasi" method="POST" class="form-horizontal foto_banyak" action="<?= base_admin() ?>modul/validasi_mahasiswa_baru/validasi_mahasiswa_baru_action.php?act=validasi" >
          
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title text-center">Validasi Mahasiswa Baru</h4>
            </div>
            <div class="modal-body">
              <div class="form-group">
               
               <div class="form-group" style="display: none">
                  <label for="Kode Fakultas" class="control-label col-lg-3">No Pendaftaran<span style="color:#FF0000">*</span></label>
                  <div class="col-lg-8">
                    <input type="text" name="no_pendaftaran" id="no_pendaftaran" placeholder="No Pendaftaran" class="form-control" required="">
                  </div>
                </div>
             <!--    <div class="form-group">
                  <label for="Kode Fakultas" class="control-label col-lg-3">No Resi<span style="color:#FF0000">*</span></label>
                  <div class="col-lg-8">
                    <input type="text" name="no_resi" id="no_resi" placeholder="No Resi Pembayaran" class="form-control" required="">
                  </div>
                </div> -->
                <input type="hidden" name="is_affirmasi" class="is_affirmasi">
<!--                 <div class="form-group">
                                  <label for="Nama Fakultas" class="control-label col-lg-3 col-md-3">Bank  <span style="color:#FF0000">*</span></label>
                                  <div class="col-lg-8 col-md-8">
                                    <select class="form-control" id="id_bank" name="id_bank" required="">
                                       <option>-Pilih Bank-</option> 
                                     <?php
                                        $q = $db->query("select * from keu_bank");
                                        foreach ($q as $k) {
                                          echo "<option value='$k->kode_bank'>$k->nama_singkat</option>";
                                        }
                                        ?>
                                      </select>
                                  </div>
                                </div> -->
                <div class="form-group">
                  <label for="Nama Fakultas" class="control-label col-lg-3">Email  <span style="color:#FF0000">*</span></label>
                  <div class="col-lg-8">
                    <input type="email" name="email" id="email" class="form-control" placeholder="Email" required="" >
                  </div>
                </div>  
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">Close</button>
               <button type="submit" class="btn btn-success" style="float: right" id="btn_validasi">Validasi</button>
            </div>
          </div>
          </form>
        </div>
      </div>
      </section><!-- /.content -->

        <script type="text/javascript">
         var dtb_validasi_mahasiswa_baru;
          $(document).ready(function(){
             $("#input_validasi").submit(function(){ 
              $("#btn_validasi").html("Loading...");
              $("#btn_validasi").prop('disabled', true);
              $.ajax({
                type : 'POST',
                data : $("#input_validasi").serialize(),
                //url  : "<?= base_admin() ?>/modul/validasi_mahasiswa_baru/validasi_mahasiswa_baru_action.php?act=validasi",
                url  : "<?= base_admin() ?>/modul/validasi_mahasiswa_baru/send_validasi.php",
                success: function(data) {
                  $("#modal_validasi").modal('hide');
                   $("#btn_validasi").html("Validasi");
                   $("#btn_validasi").prop('disabled', false);
                   $("#pesan").html(data);
                   dtb_validasi_mahasiswa_baru.ajax.reload();
                }
              });
              return false;
             });

             $("#form_filter").submit(function(){
                dtb_validasi_mahasiswa_baru.ajax.reload();
                return false;
             });
          });

      function show_form_validasi(id) {
        //if (id!='') {
           $.ajax({
                type : 'POST',
                data : {
                  id : id
                },
                dataType : 'JSON',
                url  : "<?= base_admin() ?>/modul/validasi_mahasiswa_baru/validasi_mahasiswa_baru_action.php?act=get_email",
                success: function(data) {
                  console.log(data);
                if (data.error==="") {
                   $("#email").val(data.email);
                  $("#no_pendaftaran").val(id);
                  $("#modal_validasi").modal('show');
                  $('.is_affirmasi').val(data.is_affirmasi);
                } else {
                  $(".error-validasi").html(data.error);
                  $("#modal_validasi_error").modal('show');
                }
                
                }
              }); 
          
       // }
        // else{
        //   alert("No Pendaftaran tidak boleh kosong");
        // }
         
      }

      function batal_validasi(id) {
        $.ajax({
                type : 'POST',
                data : 'nim='+id,
                url  : "<?= base_admin() ?>/modul/validasi_mahasiswa_baru/validasi_mahasiswa_baru_action.php?act=batal_validasi",
                success: function(data) {
                  dtb_validasi_mahasiswa_baru.ajax.reload();
                }
              });
      }
      
      $("#add_validasi_mahasiswa_baru").click(function() {
          $.ajax({
              url : "<?=base_admin();?>modul/validasi_mahasiswa_baru/validasi_mahasiswa_baru_add.php",
              type : "GET",
              success: function(data) {
                  $("#isi_validasi_mahasiswa_baru").html(data);
              }
          });

      $('#modal_validasi_mahasiswa_baru').modal({ keyboard: false,backdrop:'static',show:true });

    });
    


      
    $(".table").on('click','.edit_data',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);

        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/validasi_mahasiswa_baru/validasi_mahasiswa_baru_edit.php",
            type : "post",
            data : {id_data:id},
            success: function(data) {
                $("#isi_validasi_mahasiswa_baru").html(data);
                $("#loadnya").hide();
          }
        });

    $('#modal_validasi_mahasiswa_baru').modal({ keyboard: false,backdrop:'static' });

    });
    
       dtb_validasi_mahasiswa_baru = $("#dtb_validasi_mahasiswa_baru").DataTable({
           "fnCreatedRow": function( nRow, aData, iDataIndex ) {
            var indek = aData.length-1;
            // $('td:eq('+indek+')', nRow).html('<a href="<?=base_index();?>validasi-mahasiswa-baru/detail/'+aData[indek]+'"  class="btn btn-success btn-sm" data-toggle="tooltip" title="Detail"><i class="fa fa-eye"></i></a> <?=$edit;?> <?=$del;?>');
            $(nRow).attr('id', 'line_'+aData[indek]);
          },
          "dom": "<'row'<'col-sm-12'B>>" + "<'row'<'col-sm-6'l><'col-sm-6'f>>" +"<'row'<'col-sm-12'tr>>" +"<'row'<'col-sm-5'i><'col-sm-7'p>>",

          buttons: [
              // {
              //    extend: 'collection',
              //    text: 'Export Data',
              //    buttons: [ 'pdfHtml5', 'csvHtml5', 'copyHtml5', 'excelHtml5' ],

              // }
              ],
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
              },
              {
                'width': '100px',
                'targets': [1,2,3,4,5,6],
                'orderable': false,
                'searchable': false,
                'className': 'dt-center'
              }
              ],


              'ajax':{
                url :'<?=base_admin();?>modul/validasi_mahasiswa_baru/validasi_mahasiswa_baru_data.php',
            type: 'post',  // method  , by default get
            data:   function ( d ) {
              d.tahun_akademik = $("#tahun_akademik").val();
              d.jurusan = $("#jurusan").val();
                   // d.ket   = $("#ket").val();

                 },
                 error: function (xhr, error, thrown) {
                  console.log(xhr);

                }
          },
        });

  $('#dtb_validasi_mahasiswa_baru').on('draw.dt', function() {
          init_selected()
      });

      $('#select_all').on('click', function() {
          select_deselect('select')
      });
      $('#deselect_all').on('click', function() {
          select_deselect('unselect')
  });



  $(document).on('click', '#dtb_validasi_mahasiswa_baru tbody tr td', function(event) {
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
      var table_select = $('#dtb_validasi_mahasiswa_baru tbody tr.selected');
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
          $('#dtb_validasi_mahasiswa_baru tbody tr').addClass('DTTT_selected selected')
      } else {
          $('#dtb_validasi_mahasiswa_baru tbody tr').removeClass('DTTT_selected selected')
      }
      init_selected()
  }




/* Add a click handler for the delete row */
  $('#bulk_delete').click( function() {
    var anSelected = fnGetSelected( dtb_validasi_mahasiswa_baru );
    var data_array_id = check_selected();
    var all_ids = data_array_id.toString();
    $('#ucing').modal({ keyboard: false }).one('click', '#delete', function (e) {
        $('#loadnya').show();
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '<?=base_admin();?>modul/validasi_mahasiswa_baru/validasi_mahasiswa_baru_action.php?act=del_massal',
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
                               dtb_validasi_mahasiswa_baru.draw();
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
            