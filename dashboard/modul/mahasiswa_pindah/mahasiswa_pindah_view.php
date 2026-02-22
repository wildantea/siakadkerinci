<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Mahasiswa Pindah
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>mahasiswa-pindah">Mahasiswa Pindah</a></li>
                        <li class="active">Mahasiswa Pindah List</li>
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
                                      <a id="add_mahasiswa_pindah" class="btn btn-primary "><i class="fa fa-plus"></i> <?php echo $lang["add_button"];?></a>
                                      <?php
                                          }
                                      }
                                  }
                                ?>
                            </div><!-- /.box-header -->
                            <div class="box-body table-responsive">

<div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Filter</h3>
            </div>
            <div class="box-body">
           <form class="form-horizontal" id="filter_form" method="post" action="<?=base_admin();?>modul/mahasiswa_pindah/aksi.php" target="_blank">
                   
              <div class="form-group">
                    <label for="Program studi" class="control-label col-lg-2">Program studi Baru</label>
                    <div class="col-lg-5">
                    <select id="jur_kode" name="jur_kode" data-placeholder="Pilih Program studi ..." class="form-control chzn-select" tabindex="2" required="">
                          <option value="all">Semua</option>
                    <?php
                    $angkatan = $db->query("select * from view_prodi_jenjang where kode_dikti in (select jurusan_baru from mhs_pindah)");
                   foreach ($angkatan as $ak) {
                     echo "<option value='$ak->kode_dikti'>$ak->jurusan</option>";
                   }
                    ?>
                    </select>
                    </div>
              </div><!-- /.form-group -->

              <div class="form-group">
                    <label for="Angkatan" class="control-label col-lg-2">Angkatan Baru</label>
                    <div class="col-lg-2">
                    <select id="mulai_smt" name="mulai_smt" data-placeholder="Pilih Angkatan ..." class="form-control chzn-select" tabindex="2" required="">
                      <option value="all">Semua</option>
                    <?php
                      $angkatan = $db->query("select * from semester_ref where id_semester in (select angkatan_baru from mhs_pindah) order by id_semester desc");
                   foreach ($angkatan as $ak) {
                     echo "<option value='$ak->id_semester'>$ak->id_semester</option>";
                   }
                    ?>
                    </select>
                    </div>
              </div><!-- /.form-group -->

              <div class="form-group">
                    <label for="Jenis Kelamin" class="control-label col-lg-2">Jenis Pindah</label>
                    <div class="col-lg-5">
                    <select id="jenis_pindah" name="jenis_pindah" data-placeholder="Pilih Jenis jenis_pindah ..." class="form-control" tabindex="2">
                        <option value="">Semua</option>
                   <option value="internal">Internal</option>
                   <option value="eksternal">Eksternal</option>
                    </select>
                    </div>
              </div><!-- /.form-group -->

                      <div class="form-group">
                        <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                        <div class="col-lg-10">
                          <span id="filter" class="btn btn-primary"><i class="fa fa-refresh"></i> Filter</span>
                  <div class="btn-group">
                  <button type="submit" class="btn btn-success"><i class="fa fa-cloud-download"></i> Download Data</button>
                  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    <li class="cetak-data"><button type="submit" name="jenis" value="download" class="btn cetak-data"><i class="fa fa-cloud-download"></i> Download Nilai Transfer</li>
                   <!--  <li class="cetak-data"><button type="submit" name="jenis" value="cetak" class="btn cetak-data"><i class="fa fa-print"></i> Cetak Akun Mahasiswa</li> -->
                  </ul>
                </div>
                      </div><!-- /.form-group -->
                    </div>
                    </form>
            </div>
            <!-- /.box-body -->
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
                        <table id="dtb_mahasiswa_pindah" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                  <th>No</th>
                                  <th>NIM Lama</th>
                                  <th>NIM Baru</th>

                                  <th>Nama</th>
                                  <th>Angkatan Lama</th>
                                  <th>Angkatan Baru</th>
                                  <th>Jenis Pindah</th>
                                  <th>Kampus Lama</th>
                                  <th>Kampus Baru</th>
                                  <th>Jurusan Lama</th>
                                  <th>Jurusan Baru</th>
                                  <th>Tanggal Pindah</th>
                                  <th>No SK</th>
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
                $edit = "<a data-id='+aData[indek]+'  class=\"edit_data \" data-toggle=\"tooltip\" title=\"Edit\"><i class=\"fa fa-pencil\"></i> Edit Data</a>";
                $matkul = "<a data-id='+aData[indek]+'  class=\"konversi_matkul \" data-toggle=\"tooltip\" title=\"Konversi Matkul\"><i class=\"fa fa-book\"></i> Konversi Matkul</a>"; 
              } else { 
                  $edit ="";
              }
            if ($role_act['del_act']=='Y') {
                $del = "<a data-id='+aData[indek]+' data-uri=".base_admin()."modul/mahasiswa_pindah/mahasiswa_pindah_action.php".' class="hapus_dtb_notif" data-toggle="tooltip" title="Hapus" data-variable="dtb_mahasiswa_pindah"><i class="fa fa-trash"></i> Hapus</a>'; 
            } else {
                $del="";
            }
                             }
            }

        ?>

    <div class="modal" id="modal_mahasiswa_pindah" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> <h4 class="modal-title"><?php echo $lang["add_button"];?> Mahasiswa Pindah</h4> </div> <div class="modal-body" id="isi_mahasiswa_pindah"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
    
    </section><!-- /.content -->

        <script type="text/javascript">
     

    $(document).ready(function() {

      $.fn.modal.Constructor.prototype.enforceFocus = function() {};
    });

 


      $("#add_mahasiswa_pindah").click(function() {
          $.ajax({
              url : "<?=base_admin();?>modul/mahasiswa_pindah/mahasiswa_pindah_add.php",
              type : "GET",
              success: function(data) {
                  $("#isi_mahasiswa_pindah").html(data);
              }
          });

      $('#modal_mahasiswa_pindah').modal({ keyboard: false,backdrop:'static',show:true });

    });
    
      
    $(".table").on('click','.edit_data',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);

        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/mahasiswa_pindah/mahasiswa_pindah_edit.php",
            type : "post",
            data : {id_data:id},
            success: function(data) {
                $("#isi_mahasiswa_pindah").html(data);
                $("#loadnya").hide();
          }
        });

    $('#modal_mahasiswa_pindah').modal({ keyboard: false,backdrop:'static' });

    });

     $(".table").on('click','.konversi_matkul',function(event) {
      
        var currentBtn = $(this);

        id = currentBtn.attr('data-id');

        document.location="<?= base_url() ?>dashboard/index.php/mahasiswa-pindah/konversi_matkul/"+id;

    });
    


   var dtb_mahasiswa_pindah = $("#dtb_mahasiswa_pindah").DataTable({
           "fnCreatedRow": function( nRow, aData, iDataIndex ) {
            var indek = aData.length-1;
            $('td:eq('+indek+')', nRow).html('<div class="btn-group"> <button class="btn btn-xs btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true"> Actions <i class="fa fa-angle-down"></i> </button> <ul class="dropdown-menu aksi-table" role="menu"><li><?=$edit;?></li><li><?= $del; ?></li><li><?= $matkul ?></li></ul></div>');
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
            
           'columnDefs': [ {
            'targets': [-1],
              'orderable': false,
              'searchable': false
            },
                {
            'width': '5%',
            'targets': 0,
            'searchable': false,
            'className': 'dt-center'
          }
             ],

    
            'ajax':{
              url :'<?=base_admin();?>modul/mahasiswa_pindah/mahasiswa_pindah_data.php',
            type: 'post',  // method  , by default get
            data: function ( d ) {
                  //filter variable datatable
                  d.jur_kode = $("#jur_kode").val();
                  d.mulai_smt = $("#mulai_smt").val();
                  d.jenis_pindah = $("#jenis_pindah").val();
                  },
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });



//filter
$('#filter').on('click', function() {
  dtb_mahasiswa_pindah.ajax.reload();
});

    

  $('#dtb_mahasiswa_pindah').on('draw.dt', function() {
          init_selected()
      });

      $('#select_all').on('click', function() {
          select_deselect('select')
      });
      $('#deselect_all').on('click', function() {
          select_deselect('unselect')
  });



  $(document).on('click', '#dtb_mahasiswa_pindah tbody tr td', function(event) {
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
      var table_select = $('#dtb_mahasiswa_pindah tbody tr.selected');
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
          $('#dtb_mahasiswa_pindah tbody tr').addClass('DTTT_selected selected')
      } else {
          $('#dtb_mahasiswa_pindah tbody tr').removeClass('DTTT_selected selected')
      }
      init_selected()
  }




/* Add a click handler for the delete row */
  $('#bulk_delete').click( function() {
    var anSelected = fnGetSelected( dtb_mahasiswa_pindah );
    var data_array_id = check_selected();
    var all_ids = data_array_id.toString();
    $('#ucing').modal({ keyboard: false }).one('click', '#delete', function (e) {
        $('#loadnya').show();
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '<?=base_admin();?>modul/mahasiswa_pindah/mahasiswa_pindah_action.php?act=del_massal',
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
                               dtb_mahasiswa_pindah.draw();
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
            