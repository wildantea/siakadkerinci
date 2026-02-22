<?php
session_start();
include "../../inc/config.php";
?>
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
        </style>
 <div class="alert alert-danger error_data" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning"></span>
        </div>
      <form id="input_pendaftaran" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/pendaftaran/pendaftaran_action.php?act=in">
                      
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
            <input type="text" id="jurusan_field" name="jurusan_field" class="form-control" readonly>
            <input type="hidden" name="kode_jur" id="kode_jur">
          </div>
        </div>

                      <div class="form-group">
                        <label for="Nama Pendaftaran" class="control-label col-lg-2">Nama Pendaftaran </label>
                        <div class="col-lg-10">
            <select id="id_jenis_pendaftaran_setting" name="id_jenis_pendaftaran_setting" data-placeholder="Pilih Nama Pendaftaran ..." class="form-control chzn-select" tabindex="2" required="">
               <option value=""></option>
               <?php foreach ($db2->query("select * from view_jenis_pendaftaran where kode_jur=?",array('kode_jur' => $data_mhs->kode_jur)) as $isi) {
                  echo "<option value='$isi->id_jenis_pendaftaran_setting'>$isi->nama_jenis_pendaftaran</option>";
               } ?>
              </select>
            </div>
                      </div><!-- /.form-group -->

              <div id="isi_attribute"></div>
 
      </form>
<script type="text/javascript">
    
    $(document).ready(function() {
    
     
             $("#nim").autocomplete({
            source: "<?=base_admin();?>modul/pendaftaran/find_nim.php",
            select: function( event, ui ) {
              $('.att-tambahan').fadeIn();
              $("#nama").val(ui.item.nama);
              $("#jurusan_field").val(ui.item.jurusan);
              $("#kode_jur").val(ui.item.kode_jur);
              $("#isi_body").html('');
              $("#jenis_pembayaran").val('').trigger("chosen:updated");
              $.ajax({
                url : "<?=base_admin();?>modul/pendaftaran/get_nama_pendaftaran.php",
                type : "POST",
                data : "kode_jur="+ui.item.kode_jur,
                success: function(data) {
                  // alert(data);
                  $("#id_jenis_pendaftaran_setting").html(data);
                  $("#id_jenis_pendaftaran_setting").trigger("chosen:updated");
                }
              });
            },
            minLength: 2
        });   

          //trigger validation onchange
      $('select').on('change', function() {
          $(this).valid();
      });
      //hidden validate because we use chosen select
      $.validator.setDefaults({ ignore: ":hidden:not(select)" });
      //chosen select
      $(".chzn-select").chosen();
      $(".chzn-select-deselect").chosen({
          allow_single_deselect: true
      });
    
    $("#input_pendaftaran").validate({
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
            } else if (element.attr("accept") == "image/*") {
                element.parent().parent().parent().append(error);
            }
            else if (element.hasClass("tgl_picker_input")) {
               element.parent().parent().append(error);
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
                  $(".isi_warning").html(data.responseText);
                  $(".error_data").focus()
                  $(".error_data").fadeIn();
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
                            $(".save-data").attr("disabled", "disabled");
                            $('#modal_pendaftaran').modal('hide');
                            $(".error_data").hide();
                            $(".notif_top").fadeIn(1000);
                            $(".notif_top").fadeOut(1000, function() {
                                dtb_pendaftaran.draw();
                            });
                          }
                    });
                }

            });
        }
    });

                        $("#id_jenis_pendaftaran_setting").change(function(){
                          if (this.value!='') {
                              $.ajax({
                              type : "post",
                              url : "<?=base_admin();?>modul/pendaftaran/get_attribute.php",
                              data : {id_jenis_pendaftaran_setting:this.value},
                              success : function(data) {
                                  $("#isi_attribute").html(data);
                                  }
                              });
                          } else {
                            $("#isi_attribute").html('');
                          }

                    });
});
</script>
