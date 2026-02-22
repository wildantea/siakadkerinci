<!-- Content Header (Page header) -->
 <style type="text/css"> .datepicker {z-index: 1200 !important; }
.ui-autocomplete {
  position: absolute;
  top: 100%;
  left: 0;
  z-index: 1000;
  float: left;
  display: none;
  min-width: 160px;
  _width: 160px;
  padding: 4px 0;
  margin: 2px 0 0 0;
  list-style: none;
  background-color: #ffffff;
  border-color: #ccc;
  border-color: rgba(0, 0, 0, 0.2);
  border-style: solid;
  border-width: 1px;
  -webkit-border-radius: 5px;
  -moz-border-radius: 5px;
  border-radius: 5px;
  -webkit-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
  -moz-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
  box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
  -webkit-background-clip: padding-box;
  -moz-background-clip: padding;
  background-clip: padding-box;
  *border-right-width: 2px;
  *border-bottom-width: 2px;

  .ui-menu-item > a.ui-corner-all {
    display: block;
    padding: 3px 15px;
    clear: both;
    font-weight: normal;
    line-height: 18px;
    color: #555555;
    white-space: nowrap;

    &.ui-state-hover, &.ui-state-active {
      color: #ffffff;
      text-decoration: none;
      background-color: #0088cc;
      border-radius: 0px;
      -webkit-border-radius: 0px;
      -moz-border-radius: 0px;
      background-image: none;
    }
  }
}
.ui-autocomplete {
  z-index:2147483647;
}
.modal-dialog {
  width: 90%;
  min-height: 40%;
  margin: auto auto;
  padding: 0;
}

.modal-content {
  height: auto;
  min-height: 90%;
  border-radius: 0;
}
        </style>
    <section class="content-header">
        <h1>Input Pembayaran</h1>
        <ol class="breadcrumb">
            <li>
              <a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a>
            </li>
            <li>
              <a href="<?=base_index();?>input-pembayaran">Input Pembayaran Mahasiswa</a>
            </li>
            <li class="active">Input Pembayaran Mahasiswa</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
    <div class="row">
      <div class="col-lg-12">
        <div class="box box-solid box-primary">
          <div class="box-header">
            <h3 class="box-title">Input Pembayaran Mahasiswa</h3>
            <div class="box-tools pull-right">
              <button class="btn btn-info btn-sm"><i class="fa fa-plus"></i></button>
            </div>
          </div>
          <div class="box-body clear-end">
<?php
 if ($db2->userCan("import")) {
  ?>
     <a class="btn btn-primary" id="import_mat"><i class="fa fa-pencil"></i> Input Massal</a>
    <?php
  }
?>
 <div class="alert alert-danger error_data" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning"></span>
        </div>
            <form id="input_mahasiswa" autocomplete="off" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/input_pembayaran/input_pembayaran_action.php?act=in">
                      
              <div class="form-group">
                <label for="Nim" class="control-label col-lg-2">Nim <span style="color:#FF0000">*</span></label>
                <div class="col-lg-8">
                  <input type="text" id="nim" name="nim" placeholder="Ketik NIM atau Nama Mahasiswa" class="form-control" required>
                   
                </div>
              </div><!-- /.form-group -->
    <div class="form-group att-tambahan" style="display: none">
          <label for="nama" class="control-label col-lg-2">&nbsp;</label>
          <div class="col-lg-4">
            <input type="text" id="nama" name="nama" class="form-control"  readonly>
          </div>
          <div class="col-lg-4">
            <input type="text" id="jurusan" name="jurusan" class="form-control" readonly>
          </div>
        </div>
    <div class="form-group">
          <label for="nama" class="control-label col-lg-2">&nbsp;</label>
          <div class="col-lg-8">
            <select id="metode_bayar" name="metode_bayar" data-placeholder="Metode Pembayaran..." class="form-control chzn-select" tabindex="2" required="">
              <option value="">Pilih Metode Pembayaran</option>
              <option value="1">CASH</option>
              <option value="2">TRANSFER BANK</option>
            </select>
          </div>
        </div>


              

                  <div class="form-group" id="transfer" style="display: none">
                    <label class="control-label col-lg-2">Tanggal Bayar Ke Bank</label>
                    <div class="col-lg-3">
                      <div class='input-group date tgl_picker'>
                        <input type='text' id="tgl_bayar" autocomplete="false" class="form-control tgl_picker_input" type="text" name="tgl_bayar" />
                        <span class="input-group-addon">
                          <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                      </div>
                    </div>
                    <label class="control-label col-lg-1">Bank</label>
                    <div class="col-lg-4">
                     <select id="bank" name="bank" data-placeholder="Pilih Bank ..." class="form-control chzn-select" tabindex="2">
                      <option value="">Pilih Bank</option>
                      <?php 
                      $bank = $db2->query("select * from keu_bank");
                      foreach ($bank as $bk) {
                        echo "<option value='$bk->kode_bank'>$bk->nama_bank - $bk->nomor_rekening</option>";
                      } ?>
                    </select>
                    <i style="color:red;display: none" id="bank_kosong" >Silahkan Pilih bank</i>
                  </div>
                  </div>
                    <div class="form-group">
                        <label for="Nominal Tagihan" class="control-label col-lg-2">Jumlah yang dibayarkan <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-4">
                          <div class="input-group">
                            <div class="input-group-addon">Rp.</div>
                            <input id="auto" type="text"  name="jumlah_bayar" class="form-control input-group-custom" data-a-sep="." data-a-dec="," required="">

                          </div>
                          
                        </div>
                        <!-- <div class="col-md-4">
                           <input id="auto2" disabled type="text" name="nominal_tagihan2" class="form-control" data-a-sep="." data-a-dec="," required="">
                        </div> -->
                      </div><!-- /.form-group -->
                  <div class="form-group">
                       
                        <div class="col-lg-12" id="detail_tagihan">
<!-- <table class="table table-striped table-bordered">
  <thead>
  <tr>
    <th width="3%">No</th>
    <th>Jenis Tagihan</th>
    <th>Jumlah</th>
  </tr>
  </thead>
  <tbody id="isi_body"></tbody>
</table> -->
                        </div>
              </div>
                      
              <div class="form-group">
              
                <div class="col-lg-12" style="display: none" id="btn_cetak">
                  <button type="submit" class="btn btn-primary"><i class="fa fa-money"> Proses Pembayaran</i></button>
                  <a style="cursor: pointer" onclick="cetak_tagihan()" class="btn btn-success"><i class="fa fa-print"></i> Cetak Tagihan</a>
                </div>
              </div><!-- /.form-group -->

            </form>

          </div>

          <div class="modal fade" id="modalDetilCicilan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                 <button type="button" class="close" style="opacity: 5" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><span class="btn btn-danger btn-xs"><i class="fa fa-close"></i></span></span></button>
                 <h4 class="modal-title" id="myModalLabel">Detail Cicilan</h4>
               </div>
               <div class="modal-body" id="detil_cicilan">
                ...
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
      </div>
      </div>

    </div>

    <div class="modal" id="modal_import_mat" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" style="opacity: 5" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><span class="btn btn-danger btn-xs"><i class="fa fa-close"></i></span></span></button> <h4 class="modal-title">Lunas Massal Pembayaran</h4> </div> <div class="modal-body" id="isi_import_mat"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div> 
    <!-- Large modal -->
<!-- Button trigger modal -->

    </section><!-- /.content -->
<script type="text/javascript">
 $(document).ready(function() {

    $("#metode_bayar").change(function(){
        if (this.value==2) {
          $('#tgl_bayar').prop('required', true);   
          $('#bank').prop('required', true);
          $("#transfer").show();
        } else {
          $('#tgl_bayar').prop('required', false);   
          $('#bank').prop('required', false);
          $("#transfer").hide();
        }
    });
    $('#auto').autoNumeric("init",{vMin: '0', vMax: '999999999' });
});
         

function convertToRupiah(angka)
{
  var rupiah = '';    
  var angkarev = angka.toString().split('').reverse().join('');
  for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+'.';
  return 'Rp. '+rupiah.split('',rupiah.length-1).reverse().join('');
}


  function refresh_tagihan(nim){
             $.ajax({
                url : "<?=base_admin();?>modul/input_pembayaran/get_detail_tagihan.php",
                type : "POST",
                data : "nim="+nim,
                success: function(data) {
                  // alert(data);
                  $("#detail_tagihan").html(data);
                  $.each($('.make-switch'), function () {
                    $(this).bootstrapSwitch({
                      onText: $(this).data('onText'),
                      offText: $(this).data('offText'),
                      onColor: $(this).data('onColor'),
                      offColor: $(this).data('offColor'),
                      size: $(this).data('size'),
                      labelText: $(this).data('labelText')
                    });
                  });
                }
              });
  }

  function afirmasi_krs(id){
    if($("#krs_boleh-"+id).prop('checked') == true){
      var aff=1;
     }else{
      var aff=0;
     }
      $.ajax({
              url : "<?=base_admin();?>modul/input_pembayaran/input_pembayaran_action.php?act=afirmasi_krs",
              type : "POST",
              data : "id_tagihan="+id+"&aff="+aff,
              success: function(data) { 
                  
              }
          });
  }

  function lunaskan_tagihan(id,nim) {
        if($("#lunaskan_tagihan-"+id).prop('checked') == true){
          var lunas=1;
         // alert("true");
          if ($("#bank").val()=='') {
           var bank='';
           $("#bank_kosong").show();
           $("#bank").focus();
           $("#lunaskan_tagihan-"+id).prop('checked', false);
           $(".bootstrap-switch-id-lunaskan_tagihan-"+id).removeClass("bootstrap-switch-on");
           $(".bootstrap-switch-id-lunaskan_tagihan-"+id).removeClass("bootstrap-switch-focused");
           $(".bootstrap-switch-id-lunaskan_tagihan-"+id).addClass("bootstrap-switch-off");
           $(".bootstrap-switch-id-lunaskan_tagihan-"+id).addClass("bootstrap-switch-focused");

         }else{
           $("#bank_kosong").hide();
           var bank=$("#bank").val();
            $(".bootstrap-switch-id-lunaskan_tagihan-"+id).removeClass("bootstrap-switch-off");
           $(".bootstrap-switch-id-lunaskan_tagihan-"+id).removeClass("bootstrap-switch-focused");
            $(".bootstrap-switch-id-lunaskan_tagihan-"+id).addClass("bootstrap-switch-on");
           $(".bootstrap-switch-id-lunaskan_tagihan-"+id).addClass("bootstrap-switch-focused");
               $.ajax({
              url : "<?=base_admin();?>modul/input_pembayaran/input_pembayaran_action.php?act=lunaskan_tagihan",
              type : "POST",
              data : "id_tagihan="+id+"&lunas="+lunas+"&bank="+bank,
              success: function(data) {   
                refresh_tagihan(nim);   
              }
          });
        }
      }else{
        //alert("false");
        var lunas=0;
            $.ajax({
              url : "<?=base_admin();?>modul/input_pembayaran/input_pembayaran_action.php?act=lunaskan_tagihan",
              type : "POST",
              data : "id_tagihan="+id+"&lunas="+lunas+"&bank="+bank,
              success: function(data) {   
                refresh_tagihan(nim);   
              }
          });
      }
    

  }

  function showDetilCicilan(id){
       $("#btn-history-"+id).html("Loading...");
       $.ajax({
              url : "<?=base_admin();?>modul/input_pembayaran/detil_cicilan.php",
              type : "POST",
              data : "id_tagihan="+id,
              success: function(data) {
                 $("#btn-history-"+id).html(" History Cicilan ");
                $("#detil_cicilan").html(data);
                $('#modalDetilCicilan').modal('toggle');
                $('#modalDetilCicilan').modal('show');
              }
          });
    
  }

  function updateJumlah(jml,id){
 /*    $.ajax({
              url : "<?=base_admin();?>modul/input_pembayaran/input_pembayaran_action.php?act=get_nominal",
              type : "POST",
              data : "id_tagihan="+id,
              success: function(data) {*/
                var nominal = parseInt($("#data2-"+id).val());
                if (nominal>jml) {
                   //alert("test");
                   var jml_bayar = $("#auto").val();

                   var jumlah = jml_bayar.toString().replace('.','');

                  var jml_auto = parseInt(jumlah);

                  $("#auto").val(jml_auto+(nominal-jml));
                  $('#auto').autoNumeric("update", { vMin: '0', vMax: '999999999'  });
                  $("#data2-"+id).val(jml);
                  $("#data-"+id).val(jml);
                }else{
                  //alert("xxx");
                   var jml_bayar = $("#auto").val();

                   var jumlah = jml_bayar.toString().replace('.','');

                  var jml_auto = parseInt(jumlah);

                  var jml_tambah = parseInt(jml-nominal);
                  var jml_all = jml_auto-jml_tambah;
                //  alert(jml_tambah);
                  if (jml_all<0) {
                    alert("Nominal yang diinput melebihi uang yang dibayarkan");
                    $("#data-"+id).val(nominal);
                   // $("#data-"+id).val(0);
                  }else{
                    $("#auto").val(jml_all);
                    $('#auto').autoNumeric("update", { vMin: '0', vMax: '999999999'  });
                    $("#data2-"+id).val(jml);
                  }
                  
                }
    }
/*              }
          });
  }*/

  function cetak_tagihan() {
    var nim = $("#nim").val();
    window.open("<?= base_admin() ?>modul/input_pembayaran/cetak_tagihan.php?nim="+nim);
  }

  function set_bayar(id,jml_tagihan){
    //  alert(id);
    if($("#cek-"+id).prop('checked') == true){
      if ($("#auto").val()=='') {
         alert("isi jumlah yang akan dibayarkan");
         $("#auto").focus();
         $("#cek-"+id).prop('checked', false);
      }else{
         $("#form-"+id).html("<input type='hidden' name='ket_id-"+id+"' id='ket_id-"+id+"' value='"+id+"'> <input type='text' class='form-control' id='data-"+id+"' name='data-"+id+"' value='"+jml_tagihan+"' onchange='updateJumlah(this.value,"+id+")' > <input type='hidden' class='form-control' id='data2-"+id+"' name='jml_bayar["+id+"]'>  ");

        

       var jml_bayar = $("#auto").val();

       console.log(jml_bayar);

       var jumlah = jml_bayar.toString().replace('.','');

       console.log(jumlah);

      var jml_bayar = parseInt(jumlah);

      console.log(jml_bayar);

      //var input_jml = $("#data-"+id).val();
      //var jml_input = input_jml.toString().replace('.','');

       var jml_input = parseInt($("#data-"+id).val());


       if (jml_bayar<=jml_input) {
          $("#data-"+id).val(jml_bayar);
          $("#data2-"+id).val(jml_bayar);
          $("#auto").val(0);
       }else{
         $("#auto").val(jml_bayar-jml_input);
         $('#auto').autoNumeric("update", { vMin: '0', vMax: '999999999'  });
         $("#data2-"+id).val(jml_input);
       }
      }
     
       
    }else{
       var jml_bayar = $("#auto").val();

       var jumlah = jml_bayar.toString().replace('.','');

      var jml_bayar = parseInt(jumlah);

       var jml_input = parseInt($("#data-"+id).val());
       $("#auto").val(jml_bayar+jml_input);
       $('#auto').autoNumeric("update", { vMin: '0', vMax: '999999999'  });
       $("#form-"+id).html('');
       //$("#form-"+id).html(convertToRupiah(jml_tagihan));

    }
    
  }
    $(document).ready(function() {
         // $('#auto').autoNumeric("init",{vMin: '0', vMax: '999999999' });

            $("#import_mat").click(function() {
          $.ajax({
              url : "<?=base_admin();?>modul/input_pembayaran/import.php",
              type : "GET",
              success: function(data) {
                  $("#isi_import_mat").html(data);
              }
          });

      $('#modal_import_mat').modal({ keyboard: false,backdrop:'static',show:true });

    });
          //chosen select
          $(".chzn-select").chosen();
          $(".chzn-select-deselect").chosen({
              allow_single_deselect: true
          });
        
    
    
      //trigger validation onchange
      $('select').on('change', function() {
          $(this).valid();
      });
      //hidden validate because we use chosen select
      $.validator.setDefaults({ ignore: ":hidden:not(select)" });

          $("#nim").autocomplete({
            source: "<?=base_admin();?>modul/input_pembayaran/search_nim.php",
            select: function( event, ui ) {
              $('.att-tambahan').fadeIn();
              $("#nama").val(ui.item.nama);
              $("#jurusan").val(ui.item.jurusan);
              $("#isi_body").html('');
              $("#jenis_pembayaran").val('').trigger("chosen:updated");
              $.ajax({
                url : "<?=base_admin();?>modul/input_pembayaran/get_detail_tagihan.php",
                type : "POST",
                data : "nim="+ui.item.value,
                success: function(data) {
                  // alert(data);
                  $("#detail_tagihan").html(data);
                  $("#btn_cetak").show();
                  $.each($('.make-switch'), function () {
                    $(this).bootstrapSwitch({
                      onText: $(this).data('onText'),
                      offText: $(this).data('offText'),
                      onColor: $(this).data('onColor'),
                      offColor: $(this).data('offColor'),
                      size: $(this).data('size'),
                      labelText: $(this).data('labelText')
                    });
                  });
                }
              });
            },
            minLength: 2
        });       
    
   var input_bayar =  $("#input_mahasiswa").validate({
        errorClass: "help-block",
        errorElement: "span",
        highlight: function(element, errorClass, validClass) {
            $(element).parents(".form-group").removeClass(
                "has-success").addClass("has-error");
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).parents(".form-group").removeClass(
                "has-error").addClass("has-success");
        },
        errorPlacement: function(error, element) {
            if (element.hasClass("chzn-select")) {
                var id = element.attr("id");
                error.insertAfter("#" + id + "_chosen");
            } else if (element.hasClass("tgl_picker_input")) {
               element.parent().parent().append(error);
            } else if (element.hasClass("input-group-custom")) {
               element.parent().parent().append(error);
            } else if (element.attr("accept") == "image/*") {
                element.parent().parent().parent().append(error);
            }
            else if (element.hasClass("file-upload-data")) {
               element.parent().parent().parent().append(error);
            }
             else if (element.attr("type") == "checkbox") {
                element.parent().parent().append(error);
            } else if (element.attr("type") == "radio") {
                element.parent().parent().append(error);
            } else {
                error.insertAfter(element);
            }
        },
          rules: {
            
          nim: {
          required: true,
          //minlength: 2
          },
        
          jenis_pembayaran: {
          required: true,
          //minlength: 2
          },
        
        },
         messages: {
            
          nim: {
          required: "Silakan Masukan NIM",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          jenis_pembayaran: {
          required: "Pilih Jenis Pembayaran",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
        },
      submitHandler: function(form) {
            $("#loadnya").show();
            $(form).ajaxSubmit({
                url : $(this).attr("action"),
                dataType: "json",
                type : "post",
                error: function(data ) { 
                  $("#loadnya").hide();
                  console.log(data); 
                },
                success: function(responseText) {
                  $("#loadnya").hide();
                  console.log(responseText);
                      $.each(responseText, function(index) {
                          console.log(responseText[index].status);
                          if (responseText[index].status=="die") {
                            $("#informasi").modal("show");
                          } else if(responseText[index].status=="error") {
                             $(".isi_warning").text(responseText[index].error_message);
                             $(".error_data").focus()
                             $(".error_data").fadeIn();
                          } else if(responseText[index].status=="good") {
  $(".clear-end").html('<div class="alert alert-success alert-dismissible"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> <h4><i class="icon fa fa-check"></i> Pembayaran Berhasil di input!</h4> <a href="<?=base_index();?>input-pembayaran">Untuk Kembali klik Disini</a></div>');
                          } else {
                             console.log(responseText);
                             $(".isi_warning").text(responseText[index].error_message);
                             $(".error_data").focus()
                             $(".error_data").fadeIn();
                          }
                    });
                }

            });
        }
    });
});

                   $("#periode").change(function(){
                        $("#isi_body").html('');
                        $("#jenis_pembayaran").val('').trigger("chosen:updated");
                   });

                  $("#jenis_pembayaran").change(function(){
                    if($("#nim").valid()) {
                      $.ajax({
                        type : "post",
                        url : "<?=base_admin();?>modul/input_pembayaran/get_content_pembayaran.php",
                        data : {nim:$("#nim").val(),periode:$("#periode").val(),jenis_pembayaran:this.value},
                        success : function(data) {
                            $("#isi_body").html(data);
                        }
                      });
                    }


                  });
                  </script>