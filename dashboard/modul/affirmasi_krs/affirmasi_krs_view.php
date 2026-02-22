<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Affirmasi Krs
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>affirmasi-krs">Affirmasi Krs</a></li>
                        <li class="active">Affirmasi Krs List</li>
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
                                      <a id="add_affirmasi_krs" class="btn btn-primary "><i class="fa fa-plus"></i> <?php echo $lang["add_button"];?></a>
                                      <?php
                                          }
                                      }
                                  }
                                ?>
                            </div><!-- /.box-header -->
                            <div class="box-body table-responsive">
<div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-info"></i> Keterangan !</h4>
                Halaman ini digunakan untuk menambahkan mahasiswa yang dibolehkan mengambil Rencana Studi /IRS dengan kondisi belum melunasi Kewajiban Pembayaran SPP sebagai syarat IRS. Dengan begitu Mahasiswa tetap bisa mengisi IRS tanpa melunasi SPP pada Periode yang dipilih. 
              </div>
 <div class="alert alert-warning fade in error_data_delete" style="display:none">
          <button type="button" class="close hide_alert_notif">&times;</button>
          <i class="icon fa fa-warning"></i> <span class="isi_warning_delete"></span>
        </div>
                        <table id="dtb_affirmasi_krs" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                  <th>No</th>
                                  <th>NIM</th>
                                  <th>Nama</th>
                                  <th>Periode Affirmasi</th>
                                  <th>Keterangan</th>
                                  <th>Program Studi</th>
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
                $del = "<button data-id='+aData[indek]+' data-uri=".base_admin()."modul/affirmasi_krs/affirmasi_krs_action.php".' class="btn btn-danger hapus_dtb_notif btn-sm" data-toggle="tooltip" title="Hapus" data-variable="dtb_affirmasi_krs"><i class="fa fa-trash"></i></button>';
            } else {
                $del="";
            }
                             }
            }

        ?>

    <div class="modal" id="modal_affirmasi_krs" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> <h4 class="modal-title"><?php echo $lang["add_button"];?> Affirmasi Krs</h4> </div> <div class="modal-body" id="isi_affirmasi_krs"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
    
    </section><!-- /.content -->

        <script type="text/javascript">
      
      $("#add_affirmasi_krs").click(function() {
          $.ajax({
              url : "<?=base_admin();?>modul/affirmasi_krs/affirmasi_krs_add.php",
              type : "GET",
              success: function(data) {
                  $("#isi_affirmasi_krs").html(data);
              }
          });

      $('#modal_affirmasi_krs').modal({ keyboard: false,backdrop:'static',show:true });

    });
    
      
    $(".table").on('click','.edit_data',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);

        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/affirmasi_krs/affirmasi_krs_edit.php",
            type : "post",
            data : {id_data:id},
            success: function(data) {
                $("#isi_affirmasi_krs").html(data);
                $("#loadnya").hide();
          }
        });

    $('#modal_affirmasi_krs').modal({ keyboard: false,backdrop:'static' });

    });
    
      var dtb_affirmasi_krs = $("#dtb_affirmasi_krs").DataTable({
           "fnCreatedRow": function( nRow, aData, iDataIndex ) {
            var indek = aData.length-1;
            $('td:eq('+indek+')', nRow).html('<?=$edit;?> <?=$del;?>');
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
              url :'<?=base_admin();?>modul/affirmasi_krs/affirmasi_krs_data.php',
            type: 'post',  // method  , by default get
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });


</script>
            