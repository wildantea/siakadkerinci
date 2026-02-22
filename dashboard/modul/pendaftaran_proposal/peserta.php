 

                                <div class="box-header">
                                <?php
                                  foreach ($db->fetch_all("sys_menu") as $isi) {
                                      if (uri_segment(1)==$isi->url) {
                                          if ($role_act["insert_act"]=="Y") {
                                      ?>
                                      <a id="add_pendaftaran_proposal" class="btn btn-primary "><i class="fa fa-plus"></i> <?php echo $lang["add_button"];?></a>
                                      <?php
                                          }
                                      }
                                  }
                                ?>
                            </div><!-- /.box-header -->
                            <div class="box-body">
<div class="box box-danger">
  
            <div class="box-header with-border">
              <h3 class="box-title">Filter</h3>
            </div>
            <div class="box-body">
           <form class="form-horizontal" id="filter_kelas_form" method="post" action="<?=base_admin();?>modul/pendaftaran_proposal/download_peserta.php" target="_blank">
          <div class="form-group">
                                <label for="Semester" class="control-label col-lg-2">Program Studi</label>
                                <div class="col-lg-5">
                                <select id="jur_filter" name="jur_filter" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2" required="">
                                <?php
                                looping_prodi();
                                ?>
                      </select>

         </div>
                              </div><!-- /.form-group -->
          <div class="form-group">
                                <label for="Semester" class="control-label col-lg-2">Semester</label>
                                <div class="col-lg-5">
                                <select id="sem_filter" name="sem_filter" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2" required="">
                                <?php
                                looping_semester();
                                ?>
                      </select>

         </div>
                              </div><!-- /.form-group -->
    <input type="hidden" name="id_pendaftaran" id="id_pendaftaran" value="03">
    <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Status Pendaftaran</label>
                        <div class="col-lg-3">
                        <select id="status_accepted" name="status_accepted" data-placeholder="Pilih Pendaftaran ..." class="form-control  chzn-select" tabindex="2">
                          <option value="all">Semua</option>
                          <option value="menunggu">Menunggu</option>
                          <option value="diterima">Diterima</option>
                          <option value="ditolak">Ditoloak</option>
                        </select>


 </div>
                      </div><!-- /.form-group -->
 <div class="form-group">
  <label for="Semester" class="control-label col-lg-2">Range Tanggal Pendaftaran</label>
   <div class="col-lg-4">
        <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-clock-o"></i>
                  </div>
                  <input type="text" class="form-control pull-right" id="range_tanggal" name="range_tanggal" readonly="">
                </div>
 </div>
 </div>
                      <div class="form-group">
                        <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                        <div class="col-lg-10">
                          <span id="filter" class="btn btn-primary"><i class="fa fa-refresh"></i> Filter</span>
                          <button class="btn btn-primary" id="download_peserta"><i class="fa fa-refresh"></i> Download Peserta</button>
                        
                </div>
                      </div><!-- /.form-group -->
                    </form>
            </div>
            <!-- /.box-body -->
          </div>
 <div class="alert alert-warning fade in error_data_delete" style="display:none">
          <button type="button" class="close hide_alert_notif">&times;</button>
          <i class="icon fa fa-warning"></i> <span class="isi_warning_delete"></span>
        </div>
                        <table id="dtb_pendaftaran_proposal" class="table table-bordered table-striped responsive display nowrap" width="100%">
                            <thead>
                                <tr>
                                  <th>No</th>
                                  <th>Nim</th>
                                  <th>Nama</th>
                                  <th>No HP</th>
                                  <th>Tanggal Daftar</th>
                                  <th>Judul Proposal</th>
                                  <th>Status</th>
                                  <th>Program Studi</th>
                                  <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->

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
                $del = "<button data-id='+aData[indek]+' data-uri=".base_admin()."modul/pendaftaran_proposal/pendaftaran_proposal_action.php".' class="btn btn-danger hapus_dtb_notif btn-sm" data-toggle="tooltip" title="Hapus" data-variable="dtb_pendaftaran_proposal"><i class="fa fa-trash"></i></button>';
            } else {
                $del="";
            }
                             }
            }

        ?>

    <div class="modal" id="modal_pendaftaran_proposal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> <h4 class="modal-title"><?php echo $lang["add_button"];?> Pendaftaran Proposal</h4> </div> <div class="modal-body" id="isi_pendaftaran_proposal"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
    
<script type="text/javascript">


      $("#add_pendaftaran_proposal").click(function() {
          $.ajax({
              url : "<?=base_admin();?>modul/pendaftaran_proposal/pendaftaran_proposal_add.php",
              type : "GET",
              success: function(data) {
                  $("#isi_pendaftaran_proposal").html(data);
              }
          });

      $('#modal_pendaftaran_proposal').modal({ keyboard: false,backdrop:'static',show:true });

    });
    
      
    $(".table").on('click','.edit_data',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);

        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/pendaftaran_proposal/pendaftaran_proposal_edit.php",
            type : "post",
            data : {id_data:id},
            success: function(data) {
                $("#isi_pendaftaran_proposal").html(data);
                $("#loadnya").hide();
          }
        });

    $('#modal_pendaftaran_proposal').modal({ keyboard: false,backdrop:'static' });

    });

   
    
      var dtb_pendaftaran_proposal = $("#dtb_pendaftaran_proposal").DataTable({
           "fnCreatedRow": function( nRow, aData, iDataIndex ) {
            var indek = aData.length-1;
            $('td:eq('+indek+')', nRow).html('<?=$edit;?> <?=$del;?>');
              $(nRow).attr('id', 'line_'+aData[indek]);
              },
           'bProcessing': true,
            'bServerSide': true,
            'responsive' : true,
            
           'columnDefs': [ {
            'targets': [-1],
              'orderable': false,
              'className': 'all',
              'searchable': false
            },
            {
            'targets': [5],
              'className': 'none'
            },
            {
            'targets': [1,2,6],
              'className': 'all'
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
              url :'<?=base_admin();?>modul/pendaftaran_proposal/pendaftaran_proposal_data.php',
            type: 'post',  // method  , by default get
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });

$('#filter').on('click', function() {
 var dtb_pendaftaran_proposal = $("#dtb_pendaftaran_proposal").DataTable({
           "fnCreatedRow": function( nRow, aData, iDataIndex ) {
            var indek = aData.length-1;
            $('td:eq('+indek+')', nRow).html('<?=$edit;?> <?=$del;?>');
              $(nRow).attr('id', 'line_'+aData[indek]);
              },
           destroy : true,
           'bProcessing': true,
            'bServerSide': true,
            
           'columnDefs': [ {
            'targets': [-1],
              'orderable': false,
              'searchable': false,
              'className': 'all',
            },
            {
            'targets': [5],
              'className': 'none'
            },
            {
            'targets': [1,2,6],
              'className': 'all'
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
              url :'<?=base_admin();?>modul/pendaftaran_proposal/pendaftaran_proposal_data.php',
            type: 'post',  // method  , by default get
            data: function ( d ) {
                    d.jur_filter = $("#jur_filter").val();
                    d.sem_filter = $("#sem_filter").val();
                    d.status_accepted = $("#status_accepted").val();
                    d.tgl_daftar = $("#range_tanggal").val();
                  },
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });
});
    function change_status(id,stat){  
      $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '<?=base_admin();?>modul/pendaftaran_proposal/pendaftaran_proposal_action.php?act=change',
            data: {stat:stat,id:id},
               success: function(responseText) {
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
                               dtb_pendaftaran_proposal.draw();
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
      }


$(document).ready(function(){
$('#range_tanggal').daterangepicker({timePicker: false, timePickerIncrement: 30, format: 'YYYY-MM-DD'});
});
</script>