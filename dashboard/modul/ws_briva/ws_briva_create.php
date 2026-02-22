<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Tagihan Mahasiswa
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>ws-briva">Tagihan Mahasiswa</a></li>
                        <li class="active">Tagihan Mahasiswa List</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
                                <div class="box-header">
                             
                                
                            </div><!-- /.box-header -->
                            <div class="box-body table-responsive">
                           <?php  
/* Generate Token */ 
if (isset($_POST['simpan'])) { 
  // echo "<pre>";
  // print_r($_POST);
  
  $res = createBriva($_POST); 
 // print_r($res); 
  $status = $res['responseCode'];    
 // die();

  if ($status=='00') {    
     $respon = $res['responseDescription'];
    $nobriva = $res['data']['custCode']; 
    $nama =  $res['data']['nama'];  
     $amount =  $_POST['nominal'];
    $keterangan = $res['data']['keterangan']; 
    ?>
    <div class="col-md-6">
      <div class="alert alert-success">
        <h4 class="text-center"> <?= $respon  ?></h4>
      </div>
      <table class="table">  
        <tr>
          <td style="width:100px">Nama</td><td style="width: 2px">:</td><td><?= $nama ?></td>
        </tr>
         <tr>
          <td>Jumlah</td><td>:</td><td>Rp. <?= $amount ?></td>  
        </tr>
         <tr>
          <td>Jenis Tagihan</td><td>:</td><td><?= $keterangan ?></td>
        </tr>
         <tr>
          <td>Expire Tagihan</td><td>:</td><td><?= $res['data']['expiredDate'] ?></td>
        </tr>
      </table> 
    </div> 
    <?php
  }else{
    $error = $res['errDesc'];
    $nobriva =  $_POST['no_briva']; 
    $nama =  $_POST['nama'];
    $amount =  $_POST['nominal']; 
    $keterangan =  $_POST['ket']; 
   ?> 
   <div class="col-md-6"> 
    <div class="alert alert-danger">
      <h4 class="text-center"> <?= $error ?></h4>
    </div>
    <table class="table">  
      <tr>
        <td style="width:100px">Nama</td><td style="width: 2px">:</td><td><?= $nama ?></td>
      </tr>
       <tr>
        <td>Jumlah</td><td>:</td><td>Rp. <?= $amount ?></td>
      </tr>
       <tr>
        <td>Jenis Tagihan</td><td>:</td><td><?= $keterangan ?></td>
      </tr>
 
    </table> 
  </div>
   <?php
  }
  // echo "<pre>"; 
  // print_r(BrivaUpdate());
  // echo "</pre>"; 
  $institutionCode = $_POST['institutionCode'];
  $custCode = $_POST['custCode']; 

}else{
  $nobriva = "";
  $amount = "";
  $nama = "";
  $keterangan = ""; 
  $institutionCode = "";
  $custCode = ""; 
}

?>
                      <div class="col-md-12">
                        <form style="margin-top: 10px" id="input_fakultas" method="post" class="form-horizontal foto_banyak" action="" novalidate="novalidate">
                          <!--  <div class="form-group">
                            <label for="Kode Fakultas" class="control-label col-lg-2">Institution Code <span style="color:#FF0000">*</span></label>
                            <div class="col-lg-10">
                              <input type="text" name="institutionCode" placeholder="No Briva" value="<?= $institutionCode ?>" class="form-control" >
                            </div>
                          </div> 
                           <div class="form-group">
                            <label for="Kode Fakultas" class="control-label col-lg-2">Cust Code <span style="color:#FF0000">*</span></label>
                            <div class="col-lg-10">
                              <input type="text" name="custCode" placeholder="No Briva" value="<?= $custCode ?>" class="form-control" >
                            </div> 
                          </div>  -->
                          <div class="form-group">
                            <label for="Kode Fakultas" class="control-label col-lg-2">No Briva <span style="color:#FF0000">*</span></label>
                            <div class="col-lg-10">
                              <input type="text" name="no_briva" placeholder="No Briva" value="<?= $nobriva ?>" class="form-control" >
                            </div>
                          </div> 
                          <div class="form-group">
                            <label for="Kode Fakultas" class="control-label col-lg-2">Nominal <span style="color:#FF0000">*</span></label>
                            <div class="col-lg-10">
                              <input type="text" name="nominal" placeholder="Nominal" value="<?= $amount ?>" class="form-control" >
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="Kode Fakultas" class="control-label col-lg-2">Nama <span style="color:#FF0000">*</span></label>
                            <div class="col-lg-10">
                              <input type="text" name="nama" placeholder="Nama" value="<?= $nama ?>" class="form-control" >
                            </div>
                          </div>
                           <div class="form-group">
                            <label for="Kode Fakultas" class="control-label col-lg-2">Ket <span style="color:#FF0000">*</span></label>
                            <div class="col-lg-10">
                              <input type="text" name="ket" placeholder="Keterangan" value="<?= $keterangan ?>" class="form-control" >
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="Kode Fakultas" class="control-label col-lg-2"></label>
                            <div class="col-lg-10">
                               <input type="submit" name="simpan" value="simpan" class="btn btn-success">
                            </div>
                          </div>
                        </form>
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
                $edit = "<a data-id='+aData[indek]+' href=".base_index()."ws-briva/edit/'+aData[indek]+' class=\"btn btn-primary btn-sm edit_data \" data-toggle=\"tooltip\" title=\"Edit\"><i class=\"fa fa-pencil\"></i></a>";
              } else {
                  $edit ="";
              }
            if ($role_act['del_act']=='Y') {
                $del = "<button data-id='+aData[indek]+' data-uri=".base_admin()."modul/ws_briva/ws_briva_action.php".' class="btn btn-danger hapus_dtb_notif btn-sm" data-toggle="tooltip" title="Hapus" data-variable="dtb_ws_briva"><i class="fa fa-trash"></i></button>';
            } else {
                $del="";
            }
                             }
            }

        ?>

    </section><!-- /.content -->

        <script type="text/javascript">

        //  $("#date").datepicker();
      
      
      var dtb_ws_briva = $("#dtb_ws_briva").DataTable({
           "fnCreatedRow": function( nRow, aData, iDataIndex ) {
            var indek = aData.length-1;
            $('td:eq('+indek+')', nRow).html('<a href="<?=base_index();?>ws-briva/detail/'+aData[indek]+'"  class="btn btn-success btn-sm" data-toggle="tooltip" title="Detail"><i class="fa fa-eye"></i></a> <?=$edit;?> <?=$del;?>');
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
            'targets': [2],
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
              url :'<?=base_admin();?>modul/ws_briva/ws_briva_data.php',
            type: 'post',  // method  , by default get
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });

  $('#dtb_ws_briva').on('draw.dt', function() {
          init_selected()
      });

      $('#select_all').on('click', function() {
          select_deselect('select')
      });
      $('#deselect_all').on('click', function() {
          select_deselect('unselect')
  });



  $(document).on('click', '#dtb_ws_briva tbody tr td', function(event) {
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
      var table_select = $('#dtb_ws_briva tbody tr.selected');
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
          $('#dtb_ws_briva tbody tr').addClass('DTTT_selected selected')
      } else {
          $('#dtb_ws_briva tbody tr').removeClass('DTTT_selected selected')
      }
      init_selected()
  }




/* Add a click handler for the delete row */
  $('#bulk_delete').click( function() {
    var anSelected = fnGetSelected( dtb_ws_briva );
    var data_array_id = check_selected();
    var all_ids = data_array_id.toString();
    $('#ucing').modal({ keyboard: false }).one('click', '#delete', function (e) {
        $('#loadnya').show();
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '<?=base_admin();?>modul/ws_briva/ws_briva_action.php?act=del_massal',
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
                               dtb_ws_briva.draw();
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
            