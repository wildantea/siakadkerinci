<?php
include "../../inc/config.php";
?>
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
        </style>
 <div class="alert alert-danger error_data" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning"></span>
        </div>
      <form id="input_setting_tagihan_mahasiswa" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/setting_tagihan_mahasiswa/setting_tagihan_mahasiswa_action.php?act=in">
                      
                      
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
                        <label for="Periode Pembayaran" class="control-label col-lg-2">Periode Tagihan <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-10">
            <select  id="periode" name="periode" data-placeholder="Pilih Periode Pembayaran ..." class="form-control chzn-select" tabindex="2" required>
               <option value=""></option>
               <?php 
                        looping_semester();
                        ?>
              </select>
            </div>
                      </div><!-- /.form-group -->
              <div class="form-group">
                        <label for="Kategori Biaya" class="control-label col-lg-2">Jenis Tagihan <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-10">
            <select  id="kode_tagihan" name="kode_tagihan" data-placeholder="Pilih Kategori Biaya ..." class="form-control chzn-select" tabindex="2" required>
               <option value=""></option>
               <?php foreach ($db->fetch_all("keu_jenis_tagihan") as $isi) {
                  echo "<option value='$isi->kode_tagihan'>$isi->nama_tagihan</option>";
               } ?>
              </select>
            </div>
                      </div><!-- /.form-group -->

                      <div class="form-group">
                        <label for="Nominal Tagihan" class="control-label col-lg-2">Potongan</label>
                        <div class="col-lg-4">
                          <div class="input-group">
                            <div class="input-group-addon">Rp.</div>
                            <input id="auto" type="text" name="potongan" class="form-control" data-a-sep="." data-a-dec="," required="" value="0">
                          </div>
                        </div>
                      </div><!-- /.form-group -->

<!--                 <div class="form-group">
              <label for="Tanggal Mulai" class="control-label col-lg-3">Tanggal Mulai Pembayaran</label>
              <div class="col-lg-3">
                <div class='input-group date tgl_picker'>
                    <input type='text' class="form-control tgl_picker_input" name="tanggal_awal" required="" autocomplete="off"/>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
              </div>
               <div class="col-lg-1" style="font-weight:bold;padding-left: 0;padding-right: 0;width: 20px;padding-top: 5px;">
                        S/d</div>
              <div class="col-lg-3">
                <div class='input-group date tgl_picker'>
                    <input type='text' class="form-control tgl_picker_input" name="tanggal_akhir" required="" autocomplete="off"/>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
              </div>
          </div> -->
                      

              <div class="form-group">
                <div class="col-lg-12">
                  <div class="modal-footer"> <button type="submit" class="btn btn-primary"><?php echo $lang["submit_button"];?></button>
                  <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang["cancel_button"];?></button>
                  </div>
                </div>
              </div><!-- /.form-group -->

      </form>
<script type="text/javascript">
    $(document).ready(function() {

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
          $("#nim").autocomplete({
            source: "<?=base_admin();?>modul/setting_tagihan_mahasiswa/search_nim.php",
            select: function( event, ui ) {
              $('.att-tambahan').fadeIn();
              $("#nama").val(ui.item.nama);
              $("#jurusan").val(ui.item.jurusan);
            },
            minLength: 2
        });   
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
      //trigger validation onchange
      $('select').on('change', function() {
          $(this).valid();
      });
      //hidden validate because we use chosen select
      $.validator.setDefaults({ ignore: ":hidden:not(select)" });
      
    $("#input_setting_tagihan_mahasiswa").validate({
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
        
        rules: {
            
          nim: {
          required: true,
          remote: {
              url: "<?=base_admin();?>modul/setting_tagihan_mahasiswa/check_exist_nim.php",
              type: "post",
              data:
                    {
                        nim: function()
                        {
                           return $('#input_setting_tagihan_mahasiswa :input[name="nim"]').val();
                        }
                    }
          },
          minlength: 2
          },
        
          kode_tagihan: {
          required: true,
          //minlength: 2
          },
        
          periode: {
          required: true,
          //minlength: 2
          },
        
        },
         messages: {
            
          nim: {
          required: "Isi Nim atau Cari",
          remote: jQuery.validator.format("Masukan Nim dengan Benar")
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          kode_tagihan: {
          required: "Pilih Kelompok Biaya",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          periode: {
          required: "Pilih Periode Pembayaran",
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
                            $('#modal_setting_tagihan_mahasiswa').modal('hide');
                            $(".error_data").hide();
                            $(".notif_top").fadeIn(1000);
                            $(".notif_top").fadeOut(1000, function() {
                                dtb_setting_tagihan_mahasiswa.draw();
                            });
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
</script>
