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
.table-bordered>tbody>tr>td {
      border: 1px solid #ddd;
}
        </style>
<!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Mahasiswa Lulus / DO</h1>
        <ol class="breadcrumb">
            <li>
              <a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a>
            </li>
            <li>
              <a href="<?=base_index();?>mahasiswa-lulus">Mahasiswa Lulus / DO</a>
            </li>
            <li class="active"><?php echo $lang["add_button"];?> Mahasiswa Lulus / DO</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
    <div class="row">
      <div class="col-lg-12">
        <div class="box box-solid box-primary">
          <div class="box-header">
            <h3 class="box-title"><?php echo $lang["add_button"];?> Mahasiswa Lulus / DO</h3>
            <div class="box-tools pull-right">
              <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-plus"></i></button>
            </div>
          </div>
          <div class="box-body">
           <div class="alert alert-danger error_data" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning"></span>
        </div>
            <form id="input_mahasiswa_lulus" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/mahasiswa_lulus/mahasiswa_lulus_action.php?act=in">
                      
              <div class="form-group">
                <label for="Nim" class="control-label col-lg-3">Nim <span style="color:#FF0000">*</span></label>
                <div class="col-lg-8">
                  <input type="text" id="nim" name="nim" placeholder="Ketik NIM atau Nama Mahasiswa" class="form-control" required>
                   
                </div>
              </div><!-- /.form-group -->
    <div class="form-group att-tambahan" style="display: none">
          <label for="nama" class="control-label col-lg-3">&nbsp;</label>
          <div class="col-lg-4">
            <input type="text" id="nama" name="nama" class="form-control"  readonly>
          </div>
          <div class="col-lg-4">
            <input type="text" id="jurusan" name="jurusan" class="form-control" readonly>
          </div>
        </div>
              <div class="form-group">
                        <label for="Jenis Keluar" class="control-label col-lg-3">Jenis Keluar <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-9">
            <select  id="id_jenis_keluar" name="id_jenis_keluar" data-placeholder="Pilih Jenis Keluar ..." class="form-control chzn-select" tabindex="2" required>
               <option value=""></option>
               <?php foreach ($db2->fetchAll("jenis_keluar") as $isi) {
                  echo "<option value='$isi->id_jns_keluar'>$isi->ket_keluar</option>";
               } ?>
              </select>
            </div>
                      </div><!-- /.form-group -->

          <div class="form-group">
              <label for="Tanggal Keluar" class="control-label col-lg-3">Tanggal Keluar <span style="color:#FF0000">*</span></label>
              <div class="col-lg-3">
                <div class="input-group date tgl_picker">
                    <input type="text" autocomplete="off" class="form-control tgl_picker_input" name="tanggal_keluar" required />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
              </div>
               <!-- in case you need two column <div class="col-lg-1" style="font-weight:bold;padding-left: 0;padding-right: 0;width: 20px;padding-top: 5px;">S/d</div> -->
          </div><!-- /.form-group -->
          <div class="form-group">
                        <label for="Periode Keluar" class="control-label col-lg-3">Periode Keluar <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-9">
            <select  id="semester" name="semester" data-placeholder="Pilih Periode Keluar ..." class="form-control chzn-select" tabindex="2" required>
               <option value=""></option>
               <?php
               loopingSemesterForm();
               ?>
              </select>
            </div>
                      </div><!-- /.form-group -->

          <div class="form-group">
              <label for="Keterangan" class="control-label col-lg-3">Keterangan </label>
              <div class="col-lg-9">
                <textarea class="form-control col-xs-12" rows="5" name="keterangan_kelulusan" ></textarea>
              </div>
          </div><!-- /.form-group -->
          
              <div class="form-group">
                <label for="Nomor SK" class="control-label col-lg-3">Nomor SK </label>
                <div class="col-lg-9">
                  <input type="text" name="nomor_sk" placeholder="Nomor SK" class="form-control" >
                </div>
              </div><!-- /.form-group -->
              
          <div class="form-group">
              <label for="Tanggal SK" class="control-label col-lg-3">Tanggal SK </label>
              <div class="col-lg-3">
                <div class="input-group date tgl_picker">
                    <input type="text" autocomplete="off" class="form-control tgl_picker_input" name="tanggal_sk"  />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
              </div>
               <!-- in case you need two column <div class="col-lg-1" style="font-weight:bold;padding-left: 0;padding-right: 0;width: 20px;padding-top: 5px;">S/d</div> -->
          </div><!-- /.form-group -->
          
              <div class="form-group">
                <label for="IPK" class="control-label col-lg-3">IPK </label>
                <div class="col-lg-2">
                  <input type="text" id="ipk" name="ipk" placeholder="IPK" class="form-control" >
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="No Ijazah / No sertifikat profesi" class="control-label col-lg-3">No Ijazah / No sertifikat profesi </label>
                <div class="col-lg-9">
                  <input type="text" name="no_seri_ijasah" placeholder="No Ijazah / No sertifikat profesi" class="form-control" >
                </div>
              </div><!-- /.form-group -->
              
                      
              <div class="form-group">
                <label for="tags" class="control-label col-lg-3">&nbsp;</label>
                <div class="col-lg-9">
             <a href="<?=base_index();?>mahasiswa-lulus" class="btn btn-default "><i class="fa fa-step-backward"></i> <?php echo $lang["cancel_button"];?></a>
            <button type="submit" class="btn btn-primary save-data"><i class="fa fa-save"></i> <?php echo $lang["submit_button"];?></button>
           
                </div>
              </div><!-- /.form-group -->

            </form>

          </div>
        </div>
      </div>
    </div>

    </section><!-- /.content -->

<script type="text/javascript">
  (function($)
{$.fn.numberField=function(options)
{if(!options)
{options={}}
var defaultOptions={ints:2,floats:6,separator:"."};options=$.extend(defaultOptions,options);var intNumAllow=options.ints;var floatNumAllow=options.floats;var separator=options.separator;$(this).on('keydown keypress keyup paste input',function()
{while((this.value.split(separator).length-1)>1)
{this.value=this.value.slice(0,-1);if((this.value.split(separator).length-1)<=1)
{return!1}}
var re=new RegExp('[^0-9'+options.separator+']','g');this.value=this.value.replace(re,'');var allowedLength;var iof=this.value.indexOf(separator);if((iof!=-1)&&(this.value.substring(0,iof).length>intNumAllow))
{allowedLength=0}
else if(iof!=-1)
{allowedLength=iof+floatNumAllow+1}
else{allowedLength=intNumAllow}
this.value=this.value.substring(0,allowedLength);return!0});return $(this)}})(jQuery)
    $(document).ready(function() {
            $('#ipk').numberField(
    {
      ints: 1, // digits count to the left from separator
      floats: 2, // digits count to the right from separator
      separator: "."
  }
  );  
    
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
        
    
        $("#modal_mahasiswa_lulus").scroll(function(){
          $(".tgl_picker").datepicker("hide");
          $(".tgl_picker").blur();
        });
        $(".tgl_picker").datepicker({
        format: "yyyy-mm-dd",
        autoclose: true,
        todayHighlight: true
        }).on("change",function(){
          $(":input",this).valid();
        });
    $("#input_mahasiswa_lulus").validate({
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
            
          id_jenis_keluar: {
          required: true,
          //minlength: 2
          },
        
          tanggal_keluar: {
          required: true,
          //minlength: 2
          },
        
          semester: {
          required: true,
          //minlength: 2
          },
        
        },
         messages: {
            
          id_jenis_keluar: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          tanggal_keluar: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          semester: {
          required: "This field is required",
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
                            $(".error_data").hide();
                            $(".notif_top").fadeIn(1000);
                            $(".notif_top").fadeOut(1000, function() {
                                    window.history.back();
                            });
                          }
                    });
                }

            });
        }
    });

              $("#nim").autocomplete({
            source: "<?=base_admin();?>modul/input_pembayaran/search_nim.php",
            select: function( event, ui ) {
              $('.att-tambahan').fadeIn();
              $("#nama").val(ui.item.nama);
              $("#jurusan").val(ui.item.jurusan);
              $.ajax({
                url : "<?=base_admin();?>modul/mahasiswa_lulus/get_ipk.php",
                type : "POST",
                data : "nim="+ui.item.value,
                success: function(data) {
                  $("#ipk").val(data);
                }
              });
            },
            minLength: 2
        });  
});
 
</script>
