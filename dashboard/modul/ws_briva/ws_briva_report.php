<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                       Report Pembayaran
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>ws-briva">Ws Briva</a></li>
                        <li class="active">Ws Briva List</li>
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
// function BRIVAgenerateToken($client_id, $secret_id) {
//     $url ="https://partner.api.bri.co.id/oauth/client_credential/accesstoken?grant_type=client_credentials";
//     $data = "client_id=".$client_id."&client_secret=".$secret_id;
//     $ch = curl_init();
//     curl_setopt($ch,CURLOPT_URL, $url);
//     curl_setopt($ch, CURLOPT_POST, true);
//     curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");  //for updating we have to use PUT method.
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//     curl_setopt($ch,CURLOPT_POSTFIELDS, $data);
//     $result = curl_exec($ch);
//     $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//     curl_close($ch);

//     $json = json_decode($result, true);
//     $accesstoken = $json['access_token'];
//     return $accesstoken;
// }

// /* Generate signature */
// function BRIVAgenerateSignature($path, $verb, $token, $timestamp, $payload, $secret) {
//     $payloads = "path=$path&verb=$verb&token=Bearer $token&timestamp=$timestamp&body=";
//     $signPayload = hash_hmac('sha256', $payloads, $secret, true);
//     return base64_encode($signPayload);
// }

function BrivaUpdate() {
    $client_id = client_id;
    $secret_id = client_secret;
    $timestamp = gmdate("Y-m-d\TH:i:s.000\Z");
    $secret = $secret_id;
    $token = BRIVAgenerateToken($client_id, $secret_id);

    $institutionCode = "29B31740030";
    $brivaNo = "77897";
    $startDate = "20220101";
    $endDate = "20220105";

    $payload = null; 
    $path ="/v1/briva/report/29B31740030/77897/20220106/20220107";             
    $verb = "GET"; 
    $base64sign = BRIVAgenerateSignature($path, $verb, $token, $timestamp, $payload, $secret);

    $request_headers = array(
        "Authorization:Bearer " . $token,
        "BRI-Timestamp:" . $timestamp,
        "BRI-Signature:" . $base64sign, 
    ); 

    $urlPost ="https://partner.api.bri.co.id/v1/briva/report/29B31740030/77897/20220106/20220107";                  
    $chPost = curl_init();
    curl_setopt($chPost, CURLOPT_URL, $urlPost); 
    curl_setopt($chPost, CURLOPT_HTTPHEADER, $request_headers);
    curl_setopt($chPost, CURLOPT_CUSTOMREQUEST, "GET"); 
    curl_setopt($chPost, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($chPost, CURLINFO_HEADER_OUT, true);
    curl_setopt($chPost, CURLOPT_RETURNTRANSFER, true);

    $resultPost = curl_exec($chPost);
    $httpCodePost = curl_getinfo($chPost, CURLINFO_HTTP_CODE);
    curl_close($chPost);
 
   // echo "<br/> <br/>";
    //echo "Response Post : ".$resultPost;
    return json_decode($resultPost, true);
} 

// die(); 
$res = BrivaUpdate();
//error_reporting(0);  
//print_r($res);
?>
<div class='col-md-6'>
  <?php
  if ($res['responseCode']=='00') {
   ?>
   <div class="alert alert-info">
        <h4 class="text-center"> <?= $res['responseDescription']  ?></h4> 
      </div>
   <?php
  }else{
    ?>
    <div class="alert alert-danger">
        <h4 class="text-center"> <?= $res['responseDescription']  ?></h4> 
      </div>
    <?php
  }
  ?>

<table class="table">
   <thead>
     <tr>
       <th>No</th>
       <th>Nama</th>
       <th>No Briva</th>
       <th>Nominal</th>
       <th>Waktu Bayar</th>
     </tr>
   </thead>
   <tbody>
     <?php
     $no=1;
     foreach ($res['data'] as $key => $value) {
      echo "<tr>
      <td>$no</td>
      <td>".$value['nama']."</td>
      <td>".$value['brivaNo'].$value['custCode']."</td>
      <td>".$value['amount']."</td> 
      <td>".$value['paymentDate']."</td> 
      </tr>";
       $no++;
     }
     ?>
   </tbody>
</table>
<?php
echo "<pre>";
print_r(BrivaUpdate());
echo "</pre>";
?>
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
            