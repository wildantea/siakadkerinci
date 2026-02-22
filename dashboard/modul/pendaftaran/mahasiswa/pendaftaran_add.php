<?php
session_start();
include "../../../inc/config.php";
session_check_end();
$data_mhs = $db2->fetchSingleRow('view_simple_mhs_data','nim',$_SESSION['username']);


?>
 <div class="alert alert-danger error_data" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning"></span>
        </div>
      <form id="input_cboaa" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/pendaftaran/mahasiswa/pendaftaran_action.php?act=in">
                      <div class="form-group">
                        <label for="Nama Pendaftaran" class="control-label col-lg-2">Nama Pendaftaran </label>
                        <div class="col-lg-10">
            <select id="id_jenis_pendaftaran_setting" name="id_jenis_pendaftaran_setting" data-placeholder="Pilih Nama Pendaftaran ..." class="form-control chzna-select" tabindex="2" required="">
               <option value="">Pilih Jenis pendaftaran</option>
               <?php foreach ($db2->query("select * from view_jenis_pendaftaran where kode_jur=? and status_aktif='Y'",array('kode_jur' => $data_mhs->jur_kode)) as $isi) {
                  echo "<option value='$isi->id_jenis_pendaftaran_setting'>$isi->nama_jenis_pendaftaran</option>";
               } ?>
              </select>
            </div>
                      </div><!-- /.form-group -->
          <div id="form-pembimbing"></div>
 
      </form>
<script type="text/javascript">
    
    $(document).ready(function() {

      //trigger validation onchange
      $('select').on('change', function() {
          $(this).valid();
      });

        
      //chosen select
      $(".chzn-select").chosen();
      $(".chzn-select-deselect").chosen({
          allow_single_deselect: true
      });

      $("#id_jenis_pendaftaran_setting").change(function(){
        $('#loadnya').show();
          $.ajax({
          type : "post",
          url : "<?=base_admin();?>modul/pendaftaran/mahasiswa/get_attribute.php",
          data : {id_jenis_pendaftaran_setting:this.value},
          success : function(data) {
              $("#form-pembimbing").html(data);
              $('#loadnya').hide();
                $(".tgl_picker").datepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
                todayHighlight: true
                }).on("change",function(){
                  $(":input",this).valid();
                });
              }
          });
      });
        
       //hidden validate because we use chosen select
$.validator.setDefaults({ ignore: ":hidden:not(select)" });

$.validator.addMethod('filesize', function(value, element, param) {
    // param = size (en bytes) 
    // element = element to validate (<input>)
    // value = value of the element (file name)
    return this.optional(element) || (element.files[0].size <= param) 
});   

/*var rules = new Object();
var messages = new Object();
$("body").find('input.file-upload-data:file').each(function() {
    rules[this.name] = { 
             accept: "image/*,application/pdf",
             filesize: 1048576,
           };
    messages[this.name] = { 
            required : 'Pilih File yang akan anda Upload',
            accept : 'File hanya boleh Gambar atau Dokumen PDF',
            filesize : 'Besar file tidak boleh lebih dari 1 MB'
           };
});*/

$.validator.messages.accept = 'File hanya boleh Gambar atau Dokumen PDF';
$.validator.messages.filesize = 'Besar file tidak boleh lebih dari 3 MB';

$.validator.addClassRules({
          'file-upload-data' : {
            accept: "image/*,application/pdf",
            filesize: 3048576,
          }
});

    $("#input_cboaa").validate({
        //debug: true,
        ignore : [],
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
        
    submitHandler: function(form,event) {
            $("#loadnya").show();
            event.preventDefault();
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
});
</script>
