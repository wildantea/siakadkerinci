<?php
include "../../inc/config.php";
?>
  <style type="text/css"> .datepicker {z-index: 1200 !important; } </style>
   <div class="alert alert-danger error_data_tanggal" style="display:none;margin-top:30px">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning_tanggal"></span>
        </div>
            <form id="edit_setting_tagihan_mahasiswa" method="post" class="form-horizontal" action="<?=base_admin();?>modul/input_pembayaran/lunaskan.php">

    <div class="form-group">
        <label for="nama" class="control-label col-lg-3">Metode Pembayaran</label>
        <div class="col-lg-3">
            <select id="metode_bayar" name="metode_bayar" data-placeholder="Metode Pembayaran..." class="form-control chzn-select" tabindex="2" required="">
                <option value="">Pilih Metode Pembayaran</option>
                <option value="1">CASH</option>
                <option value="2">TRANSFER BANK</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-lg-3">Tanggal Bayar</label>
        <div class="col-lg-3">
            <div class='input-group date tgl_picker' data-date-end-date="0d">
                <input type='text' id="tgl_bayar" autocomplete="false" class="form-control tgl_picker_input" type="date" name="tgl_bayar"  pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))" required readonly/>
                <span class="input-group-addon">
                          <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>
    </div>
    <div class="form-group transfer" style="display: none">
        <label class="control-label col-lg-3">Bank</label>
        <div class="col-lg-3">
            <select id="bank" name="bank" data-placeholder="Pilih Bank ..." class="form-control chzn-select" tabindex="2">
                <option value="">Pilih Bank</option>
                <?php 
                      $bank = $db2->query("select * from keu_bank where aktif='Y'");
                      foreach ($bank as $bk) {
                        echo "<option value='$bk->kode_bank'>$bk->nama_bank - $bk->nomor_rekening</option>";
                      } ?>
            </select>
            <i style="color:red;display: none" id="bank_kosong">Silahkan Pilih bank</i>
        </div>
    </div>

           <div class="form-group">
              <label for="keterangan" class="control-label col-lg-3">Keterangan Pembayaran</label>
              <div class="col-lg-9">
                <textarea class="form-control col-xs-12" rows="5" name="keterangan" required></textarea>
              </div>
          </div><!-- /.form-group -->

              <input type="hidden" name="id" value="<?=$_POST['all_id'];?>">

              <div class="form-group">
                <label for="Tanggal Mulai" class="control-label col-lg-3">&nbsp;</label>
                <div class="col-lg-3">
                  <button type="submit" class="btn btn-primary">Lunaskan Sekarang</button>
                  <button type="button" class="btn btn-default batal-lunaskan">Batal</button>
                </div>
              </div><!-- /.form-group -->

            </form>

<script type="text/javascript">

            $("#metode_bayar").change(function() {
            if (this.value == 2) {
                $('#bank').prop('required', true);
                $(".transfer").show();
            } else {
                $('#bank').prop('required', false);
                $(".transfer").hide();
            }
        });

          //chosen select
          $(".chzn-select").chosen();
          $(".chzn-select-deselect").chosen({
              allow_single_deselect: true
          });
        
                $(".tgl_picker").datepicker({
        format: "yyyy-mm-dd",
        autoclose: true,
        todayHighlight: true
        }).on("change",function(){
          $(":input",this).valid();
        });
    
      //trigger validation onchange
      $('select').on('change', function() {
          $(this).valid();
      });
    
      //trigger validation onchange
      $('select').on('change', function() {
          $(this).valid();
      });
$('.batal-lunaskan').click(function(){
    $('#modal_setting_tagihan_mahasiswa').modal('hide');
});
 

      //hidden validate because we use chosen select
      $.validator.setDefaults({ ignore: ":hidden:not(select)" });
    $(document).ready(function() {
    
          //chosen select
          $(".chzn-select").chosen();
          $(".chzn-select-deselect").chosen({
              allow_single_deselect: true
          });
        
            $('#auto').autoNumeric("init",{vMin: '0', vMax: '999999999' });
    
    
      //trigger validation onchange
      $('select').on('change', function() {
          $(this).valid();
      });
      //hidden validate because we use chosen select
      $.validator.setDefaults({ ignore: ":hidden:not(select)" });
      
    $("#edit_setting_tagihan_mahasiswa").validate({
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
    
         submitHandler: function(form) {
            $("#loadnya").show();
            $(form).ajaxSubmit({
                url : $(this).attr("action"),
                dataType: "json",
                type : "post",
                error: function(data ) { 
                  $("#loadnya").hide();
                  console.log(data); 
                  $(".isi_warning_tanggal").html(data.responseText);
                  $(".error_data_tanggal").focus()
                  $(".error_data_tanggal").fadeIn();
                },
                success: function(responseText) {
                  $("#loadnya").hide();
                  console.log(responseText);
                      $.each(responseText, function(index) {
                          console.log(responseText[index].status);
                          if (responseText[index].status=="die") {
                            $("#informasi").modal("show");
                          } else if(responseText[index].status=="error") {
                             $(".isi_warning_tanggal").text(responseText[index].error_message);
                             $(".error_data_tanggal").focus()
                             $(".error_data_tanggal").fadeIn();
                          } else if(responseText[index].status=="good") {
                            $('#modal_setting_tagihan_mahasiswa').modal('hide');
                            $('#aksi_krs option:first').prop('selected',true);
                            $(".bulk-check").prop("checked",0);
                            select_deselect('unselect');
                            $(".error_data_tanggal").hide();
                            $(".notif_top_up").fadeIn(1000);
                            $(".notif_top_up").fadeOut(1000, function() {
                                 dtb_setting_tagihan_mahasiswa.draw();
                            });
                          } else {
                             console.log(responseText);
                             $(".isi_warning_tanggal").text(responseText[index].error_message);
                             $(".error_data_tanggal").focus()
                             $(".error_data_tanggal").fadeIn();
                          }
                    });
                }

            });
        }
    });
});
</script>
