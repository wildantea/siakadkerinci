<?php
include "../../inc/config.php";
?>
<style type="text/css"> 
.datepicker {z-index: 1200 !important; }
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
      <form id="input_cuti_kuliah" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/cuti_kuliah/cuti_kuliah_action.php?act=in">
                      
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
                <label for="Status Persetujuan" class="control-label col-lg-2">Periode Cuti Kuliah <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10" id="isi_periode"> 
                  <br>
                  <span id="error_periode"></span>
                </div>
            </div><!-- /.form-group -->

  <div class="form-group">
              <label for="Alasan Cuti" class="control-label col-lg-2">Alasan Cuti <span style="color:#FF0000">*</span></label>
              <div class="col-lg-10">
              <textarea class="form-control col-xs-12" rows="5" name="alasan_cuti" required></textarea>
              </div>
          </div><!-- /.form-group -->

    <div class="form-group">
                <label for="Status Persetujuan" class="control-label col-lg-2">Status Persetujuan <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <select name="status_acc" id="status_acc" data-placeholder="Pilih Status Persetujuan ..." class="form-control chzn-select" tabindex="2" required>
                    
<option value='waiting'>Menunggu</option>

<option value='approved'>Disetujui</option>

<option value='rejected'>Ditolak</option>

                  </select>
                </div>
            </div><!-- /.form-group -->
            
          <div class="form-group">
              <label for="Tanggal Lahir" class="control-label col-lg-2">Tanggal Pengajuan</label>
              <div class="col-lg-3">
                <div class="input-group date tgl_picker">
                    <input type="text" autocomplete="off" class="form-control tgl_picker_input" required="" name="date_created" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
              </div>
               <!-- in case you need two column <div class="col-lg-1" style="font-weight:bold;padding-left: 0;padding-right: 0;width: 20px;padding-top: 5px;">S/d</div> -->
          </div><!-- /.form-group -->

 <div class="form-group" id="tgl_approve" style="display: none">
              <label for="Tanggal Lahir" class="control-label col-lg-2">Tanggal Disetujui/tolak</label>
              <div class="col-lg-3">
                <div class="input-group date tgl_picker">
                    <input type="text" autocomplete="off" class="form-control tgl_picker_input date-approved" name="date_approved" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
              </div>
               <!-- in case you need two column <div class="col-lg-1" style="font-weight:bold;padding-left: 0;padding-right: 0;width: 20px;padding-top: 5px;">S/d</div> -->
          </div><!-- /.form-group -->

              <div class="form-group" id="no_surat" style="display: none">
                <label for="Nim" class="control-label col-lg-2">Nomor Surat</label>
                <div class="col-lg-8">
                  <input type="text" name="no_surat" class="form-control">
                </div>
              </div><!-- /.form-group -->

        
          <div class="form-group" id="alasan_tolak" style="display: none">
              <label for="Alasan Cuti" class="control-label col-lg-2">Alasan ditolak</label>
              <div class="col-lg-10">
              <textarea class="form-control col-xs-12" rows="5" name="keterangan"> </textarea>
              </div>
          </div><!-- /.form-group -->
                  
              
                      

              <div class="form-group">
                <div class="col-lg-12">
                  <div class="modal-footer"> <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> <?php echo $lang["submit_button"];?></button>
                  <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> <?php echo $lang["cancel_button"];?></button>
                  </div>
                </div>
              </div><!-- /.form-group -->

      </form>


<script type="text/javascript">
    $(document).ready(function() {
        $("#status_acc").change(function(){
            if (this.value=='approved') {
              $("#no_surat").show();
              $("#alasan_tolak").hide();
              $("#tgl_approve").show();
              $('.date-approved').prop('required',true);
            } else if(this.value=='rejected') {
              $("#alasan_tolak").show();
              $("#no_surat").show();
              $("#tgl_approve").show();
              $('.date-approved').prop('required',true);
            } else {
              $("#no_surat").hide();
              $("#tgl_approve").hide();
              $('.date-approved').prop('required',false);
              $("#alasan_tolak").hide();
            }
        });

       // $('.tgl_picker').datepicker({ dateFormat: 'yyyy-mm-dd' });

        $(".tgl_picker").datepicker({
        format: "yyyy-mm-dd",
        autoclose: true,
        todayHighlight: true
        }).on("change",function(){
          $(":input",this).valid();
        });

          $("#nim").autocomplete({
            source: "<?=base_admin();?>modul/cuti_kuliah/search_nim.php",
            select: function( event, ui ) {
              $('.att-tambahan').fadeIn();
              $("#nama").val(ui.item.nama);
              $("#jurusan").val(ui.item.jurusan);
              var nim = ui.item.nim;
              $.ajax({
              type : "post",
              url : "<?=base_admin();?>modul/cuti_kuliah/get_periode_cuti.php",
              data : {nim:nim},
              success : function(data) {
                  $("#isi_periode").html(data);
                  }
              });
            },
            minLength: 2
        });  
         $.each($(".make-switch"), function () {
            $(this).bootstrapSwitch({
            onText: $(this).data("onText"),
            offText: $(this).data("offText"),
            onColor: $(this).data("onColor"),
            offColor: $(this).data("offColor"),
            size: $(this).data("size"),
            labelText: $(this).data("labelText")
            });
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
      
  $('.minimal').on('ifChanged', function(event) {
      $(this).valid();
    });
    
      //trigger validation onchange
      $('select').on('change', function() {
          $(this).valid();
      });
      //hidden validate because we use chosen select
      $.validator.setDefaults({ ignore: ":hidden:not(select)" });
      

    $("#input_cuti_kuliah").validate({
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
            } else if (element.hasClass("minimal")) {
               $("#error_periode").html(error);
            } else if (element.attr("type") == "checkbox") {
                element.parent().parent().append(error);
            } else if (element.attr("type") == "radio") {
                element.parent().parent().append(error);
            } else {
                error.insertAfter(element);
            }
        },
        
        rules: {
          "periode[]": { 
                required: true, 
                minlength: 1
            },
        
          alasan_cuti: {
          required: true,
          //minlength: 2
          },
        
        },
         messages: {
          "periode[]": {
            required : "Silakan Pilih Minimal Satu Semester!"
          },
          alasan_cuti: {
          required: "Silakan isi alasan anda Cuti Kuliah",
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
                            $('#modal_cuti_kuliah').modal('hide');
                            $(".error_data").hide();
                            $(".notif_top").fadeIn(1000);
                            $(".notif_top").fadeOut(1000, function() {
                                dtb_cuti_kuliah.draw();
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
