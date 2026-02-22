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
                        <li class="active"><?php echo $lang["edit"];?> Mahasiswa Lulus / DO</li>
                    </ol>
              </section>

              <!-- Main content -->
              <section class="content">
              <div class="row">
                  <div class="col-lg-12">
                      <div class="box box-solid box-primary">
                          <div class="box-header">
                              <h3 class="box-title"><?php echo $lang["edit"];?> Mahasiswa Lulus / DO</h3>
                              <div class="box-tools pull-right">
                                  <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-pencil"></i></button>
                              </div>
                          </div>
                      <div class="box-body">
                       <div class="alert alert-danger error_data" style="display:none">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <span class="isi_warning"></span>
                      </div>
                          <form id="edit_mahasiswa_lulus" method="post" class="form-horizontal" action="<?=base_admin();?>modul/mahasiswa_lulus/mahasiswa_lulus_action.php?act=up">              
<div class="form-group">
                <label for="Nim" class="control-label col-lg-3">Nim <span style="color:#FF0000">*</span></label>
                <div class="col-lg-8">
                  <input type="text" id="nim" name="nim" value="<?=$data_mhs->nim;?>" class="form-control" required readonly>
                   
                </div>
              </div><!-- /.form-group -->
    <div class="form-group att-tambahan" >
          <label for="nama" class="control-label col-lg-3">&nbsp;</label>
          <div class="col-lg-4">
            <input type="text" id="nama" name="nama" class="form-control" value="<?=$data_mhs->nama;?>"  readonly>
          </div>
          <div class="col-lg-4">
            <input type="text" value="<?=$data_mhs->nama_jurusan;?>" id="jurusan" name="jurusan" class="form-control" readonly>
          </div>
        </div>
              <div class="form-group">
                        <label for="Jenis Keluar" class="control-label col-lg-3">Jenis Keluar <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-9">
              <select  id="id_jenis_keluar" name="id_jenis_keluar" data-placeholder="Pilih Jenis Keluar..." class="form-control chzn-select" tabindex="2" required>
               <option value=""></option>
               <?php foreach ($db2->fetchAll("jenis_keluar") as $isi) {

                  if ($data_edit->id_jenis_keluar==$isi->id_jns_keluar) {
                    echo "<option value='$isi->id_jns_keluar' selected>$isi->ket_keluar</option>";
                  } else {
                  echo "<option value='$isi->id_jns_keluar'>$isi->ket_keluar</option>";
                    }
               } ?>
              </select>
          </div>
                      </div><!-- /.form-group -->

              <div class="form-group">
              <label for="Tanggal Keluar" class="control-label col-lg-3">Tanggal Keluar <span style="color:#FF0000">*</span></label>
              <div class="col-lg-3">
                <div class="input-group date tgl_picker">
                    <input type="text" autocomplete="off" class="form-control tgl_picker_input" value="<?=$data_edit->tanggal_keluar;?>" name="tanggal_keluar" required />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
              </div>
          </div><!-- /.form-group -->
          <div class="form-group">
                        <label for="Periode Keluar" class="control-label col-lg-3">Periode Keluar <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-9">
              <select  id="semester" name="semester" data-placeholder="Pilih Periode Keluar..." class="form-control chzn-select" tabindex="2" required>
               <option value=""></option>
               <?php foreach ($db2->query("select * from view_semester order by id_semester desc") as $isi) {

                  if ($data_edit->semester==$isi->id_semester) {
                    echo "<option value='$isi->id_semester' selected>$isi->tahun_akademik</option>";
                  } else {
                  echo "<option value='$isi->id_semester'>$isi->tahun_akademik</option>";
                    }
               } ?>
              </select>
          </div>
                      </div><!-- /.form-group -->

          <div class="form-group">
              <label for="Keterangan" class="control-label col-lg-3">Keterangan </label>
              <div class="col-lg-9">
              <textarea class="form-control col-xs-12" rows="5" name="keterangan_kelulusan"><?=$data_edit->keterangan_kelulusan;?> </textarea>
              </div>
          </div><!-- /.form-group -->
          
              <div class="form-group">
                <label for="Nomor SK" class="control-label col-lg-3">Nomor SK </label>
                <div class="col-lg-9">
                  <input type="text" name="nomor_sk" value="<?=$data_edit->nomor_sk;?>" class="form-control" >
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
              <label for="Tanggal SK" class="control-label col-lg-3">Tanggal SK </label>
              <div class="col-lg-3">
                <div class="input-group date tgl_picker">
                    <input type="text" autocomplete="off" class="form-control tgl_picker_input" value="<?=$data_edit->tanggal_sk;?>" name="tanggal_sk"  />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
              </div>
          </div><!-- /.form-group -->
          
              <div class="form-group">
                <label for="IPK" class="control-label col-lg-3">IPK </label>
                <div class="col-lg-2">
                  <input type="text" name="ipk" id="ipk" value="<?=$data_edit->ipk;?>" class="form-control" >
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="No Ijazah / No sertifikat profesi" class="control-label col-lg-3">No Ijazah / No sertifikat profesi </label>
                <div class="col-lg-9">
                  <input type="text" name="no_seri_ijasah" value="<?=$data_edit->no_seri_ijasah;?>" class="form-control" >
                </div>
              </div><!-- /.form-group -->
              
                            <input type="hidden" name="id" value="<?=$data_edit->id;?>">
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
              </section><!-- /.content -->

<script type="text/javascript">
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
    $("#edit_mahasiswa_lulus").validate({
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
            } else if (element.hasClass("tgl_picker_input")) {
               element.parent().parent().append(error);
            } else if (element.hasClass("file-upload-data")) {
               element.parent().parent().parent().append(error);
            } else if (element.attr("type") == "checkbox") {
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
                            $(".notif_top_up").fadeIn(1000);
                            $(".notif_top_up").fadeOut(1000, function() {
                                    window.history.back();
                            });
                          }
                    });
                }

            });
        }
    });
});
</script>
