<!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Semester Prodi</h1>
        <ol class="breadcrumb">
            <li>
              <a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a>
            </li>
            <li>
              <a href="<?=base_index();?>semester--prodi">Semester Prodi</a>
            </li>
            <li class="active">Tambah Semester Prodi</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
    <div class="row">
      <div class="col-lg-12">
        <div class="box box-solid box-primary">
          <div class="box-header">
            <h3 class="box-title">Tambah Semester Prodi</h3>
            <div class="box-tools pull-right">
              <button class="btn btn-info btn-sm" ><i class="fa fa-plus"></i></button>
            </div>
          </div>
          <div class="box-body">
 <div class="alert alert-danger error_data" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning"></span>
        </div>
            <form id="input_semester" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/semester__prodi/semester__prodi_action.php?act=in" autocomplete="off">
                        <div class="form-group">
                                <label for="Semester" class="control-label col-lg-3">Program Studi</label>
                                <div class="col-lg-5">
                                <select id="kode_jur" name="kode_jur" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2" required="">
                                <?php
                                looping_prodi();
                                ?>
                      </select>

         </div>
                              </div><!-- /.form-group -->
                      <div class="form-group">
                        <label for="Semester" class="control-label col-lg-3">Semester</label>
                        <div class="col-lg-5">
                        <select id="id_semester" name="id_semester" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2" required="">
                        <option value="">Pilih Semester</option>
                        <?php 
                        looping_semester();
                        ?>
              </select>

 </div>
                      </div><!-- /.form-group -->

                    <div class="form-group">
              <label for="Tanggal Mulai" class="control-label col-lg-3">Tanggal Mulai</label>
              <div class="col-lg-3">
                <div class='input-group date' id='tgl1'>
                    <input type='text' class="form-control" name="tgl_mulai" required="" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
              </div>
               <div class="col-lg-1" style="font-weight:bold;padding-left: 0;padding-right: 0;width: 20px;padding-top: 5px;">
                        S/d</div>
              <div class="col-lg-3">
              <div class='input-group date' id='tgl2'>
                    <input type='text' class="form-control" name="tgl_selesai" required="" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
              </div>
          </div><!-- /.form-group -->

                <div class="form-group">
              <label for="Tanggal Mulai" class="control-label col-lg-3">Tanggal Mulai KRS</label>
              <div class="col-lg-3">
                <div class='input-group date' id='tgl3'>
                    <input type='text' class="form-control" name="tgl_mulai_krs" required="" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
              </div>
               <div class="col-lg-1" style="font-weight:bold;padding-left: 0;padding-right: 0;width: 20px;padding-top: 5px;">
                        S/d</div>
              <div class="col-lg-3">
              <div class='input-group date' id='tgl4'>
                    <input type='text' class="form-control" name="tgl_selesai_krs" required="" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
              </div>
          </div><!-- /.form-group -->

     <div class="form-group">
              <label for="Tanggal Mulai" class="control-label col-lg-3">Tanggal Mulai Perbaikan KRS</label>
              <div class="col-lg-3">
                <div class='input-group date' id='tgl5'>
                    <input type='text' class="form-control" name="tgl_mulai_pkrs" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
              </div>
               <div class="col-lg-1" style="font-weight:bold;padding-left: 0;padding-right: 0;width: 20px;padding-top: 5px;">
                        S/d</div>
              <div class="col-lg-3">
              <div class='input-group date' id='tgl6'>
                    <input type='text' class="form-control" name="tgl_selesai_pkrs" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
              </div>
          </div><!-- /.form-group -->

     <div class="form-group">
              <label for="Tanggal Mulai" class="control-label col-lg-3">Tanggal Mulai Input Nilai</label>
              <div class="col-lg-3">
                <div class='input-group date' id='tgl7'>
                    <input type='text' class="form-control" name="tgl_mulai_input_nilai" required="" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
              </div>
               <div class="col-lg-1" style="font-weight:bold;padding-left: 0;padding-right: 0;width: 20px;padding-top: 5px;">
                        S/d</div>
              <div class="col-lg-3">
              <div class='input-group date' id='tgl8'>
                    <input type='text' class="form-control" name="tgl_selesai_input_nilai" required=""/>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
              </div>
          </div><!-- /.form-group -->



          
              <div class="form-group">
                <label for="tags" class="control-label col-lg-3">&nbsp;</label>
                <div class="col-lg-4">
                  <a onclick="window.history.back(-1)" class="btn btn-default btn-flat"><i class="fa fa-step-backward"></i> Batal</a>
           <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button> 
                </div>
              </div><!-- /.form-group -->

            </form>

          </div>
        </div>
      </div>
    </div>

    </section><!-- /.content -->

<script type="text/javascript">

    $(document).ready(function() {
     
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
      
    $("#input_semester").validate({
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
            } else if (element.attr("type") == "checkbox") {
                element.parent().parent().append(error);
            } else if (element.attr("type") == "radio") {
                element.parent().parent().append(error);
            } else {
                error.insertAfter(element);
            }
        },
        
        rules: {
            
          semester: {
          required: true,

          //minlength: 2
          },
        
          id_jns_semester: {
          required: true,
          //minlength: 2
          },
        
          tahun: {
          required: true,
          number : true,
          minlength: 4,
          maxlength:4
          },
        
        },
         messages: {
            
          semester: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          id_jns_semester: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          tahun: {
          required: "This field is required",
          minlength: "Your username must consist of at least 4 characters",
          maxlength: "Max 4 characters"
          },
        
        },

        submitHandler: function(form) {
            $("#loadnya").show();
            $(form).ajaxSubmit({
                url : $(this).attr("action"),
                dataType: 'json',
                type : 'post',
                error: function(data ) { 
                  $("#loadnya").hide();
                  console.log(data); 
                },
                success: function(responseText) {
                  $("#loadnya").hide();
                  console.log(responseText);
                      $.each(responseText, function(index) {
                          console.log(responseText[index].status);
                          if (responseText[index].status=='die') {
                            $("#informasi").modal("show");
                          } else if(responseText[index].status=='error') {
                             $('.isi_warning').text(responseText[index].error_message);
                             $('.error_data').focus()
                             $('.error_data').fadeIn();
                          } else if(responseText[index].status=='good') {
                            $('.error_data').hide();
                            $(".notif_top").fadeIn(1000);
                            $(".notif_top").fadeOut(1000, function() {
                                    window.history.back();
                            });
                          } else {
                             console.log(responseText);
                             $('.isi_warning').text(responseText[index].error_message);
                             $('.error_data').focus()
                             $('.error_data').fadeIn();
                          }
                    });
                }

            });
        }

    });


});
</script>
