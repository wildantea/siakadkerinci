<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>Semester Berlaku</h1>
                   <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>semester">Semester</a></li>
                        <li class="active">Edit Semester Berlaku</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box box-solid box-primary">
                            <div class="box-header">
                            <h3 class="box-title">Edit Semester Berlaku</h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>

                    <div class="box-body">
 <div class="alert alert-danger error_data" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning"></span>
        </div>
            <form id="update_semester" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/semester/semester_action.php?act=up">
<div class="form-group">
                <label for="Tahun" class="control-label col-lg-3">Periode Tahun Akademik</label>
                <div class="col-lg-3">
                  <input type="text"  value="<?=$data_edit->tahun_akademik;?>" class="form-control" readonly>
                </div>
              </div><!-- /.form-group -->
              <input type="hidden" name="id_semester"  value="<?=$data_edit->id_semester;?>" class="form-control">

          <div class="form-group">
              <label for="Aktif" class="control-label col-lg-3">Aktif</label>
              <div class="col-lg-5">

                <?php 
                if ($data_edit->aktif=="1") {
                ?>
                  <input name="is_aktif" class="make-switch" type="checkbox" checked data-on-text="Aktif" data-off-text="Tidak" readonly="">
                <?php
              } else {
                ?>
                  <input name="is_aktif" class="make-switch" type="checkbox" data-on-text="Aktif" data-off-text="Tidak" readonly="">
                <?php
              }?>
              </div>
          </div><!-- /.form-group -->


                    <div class="form-group">
              <label for="Tanggal Mulai" class="control-label col-lg-3">Tanggal Mulai</label>
              <div class="col-lg-3">
                <div class='input-group date' id="tgl1">
                    <input type='text' class="form-control tgl_picker_input" name="tgl_mulai"  value="<?=$data_edit->tgl_mulai;?>" required />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
              </div>
               <div class="col-lg-1" style="font-weight:bold;padding-left: 0;padding-right: 0;width: 20px;padding-top: 5px;">
                        S/d</div>
              <div class="col-lg-3">
              <div class='input-group date' id='tgl2'>
                    <input type='text' class="form-control tgl_picker_input" name="tgl_selesai" value="<?=$data_edit->tgl_selesai;?>" required />
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
                    <input type='text' class="form-control tgl_picker_input" name="tgl_mulai_krs" value="<?=$data_edit->tgl_mulai_krs;?>" required />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
              </div>
               <div class="col-lg-1" style="font-weight:bold;padding-left: 0;padding-right: 0;width: 20px;padding-top: 5px;">
                        S/d</div>
              <div class="col-lg-3">
              <div class='input-group date' id='tgl4'>
                    <input type='text' class="form-control tgl_picker_input" name="tgl_selesai_krs" value="<?=$data_edit->tgl_selesai_krs;?>" required/>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
              </div>
          </div><!-- /.form-group -->

     <div class="form-group">
              <label for="Tanggal Mulai" class="control-label col-lg-3">Tanggal Mulai Perbaikan KRS</label>
              <div class="col-lg-3">
                <div class='input-group date' id="tgl5">
                    <input type='text' class="form-control tgl_picker_input" name="tgl_mulai_pkrs" value="<?=$data_edit->tgl_mulai_pkrs;?>" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
              </div>
               <div class="col-lg-1" style="font-weight:bold;padding-left: 0;padding-right: 0;width: 20px;padding-top: 5px;">
                        S/d</div>
              <div class="col-lg-3">
              <div class='input-group date' id='tgl6'>
                    <input type='text' class="form-control tgl_picker_input" name="tgl_selesai_pkrs" value="<?=$data_edit->tgl_selesai_pkrs;?>" />
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
                    <input type='text' class="form-control tgl_picker_input" name="tgl_mulai_input_nilai" value="<?=$data_edit->tgl_mulai_input_nilai;?>" required />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
              </div>
               <div class="col-lg-1" style="font-weight:bold;padding-left: 0;padding-right: 0;width: 20px;padding-top: 5px;">
                        S/d</div>
              <div class="col-lg-3">
              <div class='input-group date' id='tgl8'>
                    <input type='text' class="form-control tgl_picker_input" name="tgl_selesai_input_nilai" value="<?=$data_edit->tgl_selesai_input_nilai;?>" required/>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
              </div>
          </div><!-- /.form-group -->

          <div class="form-group">
              <label for="Tanggal Mulai" class="control-label col-lg-3">Tanggal Input Kelas/Roster</label>
              <div class="col-lg-3">
                <div class='input-group date' id='tgl9'>
                    <input type='text' class="form-control tgl_picker_input" name="tgl_mulai_input_kelas" required="" value="<?=$data_edit->tgl_mulai_input_kelas;?>" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
              </div>
               <div class="col-lg-1" style="font-weight:bold;padding-left: 0;padding-right: 0;width: 20px;padding-top: 5px;">
                        S/d</div>
              <div class="col-lg-3">
              <div class='input-group date' id='tgl10'>
                    <input type='text' class="form-control tgl_picker_input" name="tgl_selesai_input_kelas" value="<?=$data_edit->tgl_selesai_input_kelas;?>" required=""/>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
              </div>
          </div>

          <div class="form-group">
              <label for="Tanggal Mulai" class="control-label col-lg-3">Tanggal Input Jadwal</label>
              <div class="col-lg-3">
                <div class='input-group date' id='tgl11'>
                    <input type='text' class="form-control tgl_picker_input" name="tgl_mulai_input_jadwal" required="" value="<?=$data_edit->tgl_mulai_input_jadwal;?>" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
              </div>
               <div class="col-lg-1" style="font-weight:bold;padding-left: 0;padding-right: 0;width: 20px;padding-top: 5px;">
                        S/d</div>
              <div class="col-lg-3">
              <div class='input-group date' id='tgl12'>
                    <input type='text' class="form-control tgl_picker_input" name="tgl_selesai_input_jadwal" value="<?=$data_edit->tgl_selesai_input_jadwal;?>" required=""/>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
              </div>
          </div>


           <div class="form-group">
              <label for="Tanggal Mulai" class="control-label col-lg-3">Tanggal Mulai Perkuliahan</label>
              <div class="col-lg-3">
                <div class='input-group date' id='tgl11'>
                    <input type='text' class="form-control tgl_picker_input" name="tgl_mulai_perkuliahan" value="<?=$data_edit->tgl_mulai_perkuliahan;?>" required="" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
              </div>
          </div>
  <div class="form-group">
    <label class="control-label col-lg-3">Konsultasi Awal Semester</label>

    <div class="col-lg-3">
        <div class='input-group date' id='tgl13'>
            <input type='text' class="form-control tgl_picker_input" 
                   name="konsul_awal_mulai" 
                   value="<?=$data_edit->konsul_awal_mulai;?>" required />
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
        </div>
    </div>

    <div class="col-lg-1" 
         style="font-weight:bold;padding-left:0;padding-right:0;width:20px;padding-top:5px;">
        S/d
    </div>

    <div class="col-lg-3">
        <div class='input-group date' id='tgl14'>
            <input type='text' class="form-control tgl_picker_input" 
                   name="konsul_awal_selesai" 
                   value="<?=$data_edit->konsul_awal_selesai;?>" required />
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
        </div>
    </div>
</div>
<div class="form-group">
    <label class="control-label col-lg-3">Konsultasi Tengah Semester</label>

    <div class="col-lg-3">
        <div class='input-group date' id='tgl15'>
            <input type='text' class="form-control tgl_picker_input" 
                   name="konsul_tengah_mulai" 
                   value="<?=$data_edit->konsul_tengah_mulai;?>" required />
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
        </div>
    </div>

    <div class="col-lg-1" 
         style="font-weight:bold;padding-left:0;padding-right:0;width:20px;padding-top:5px;">
        S/d
    </div>

    <div class="col-lg-3">
        <div class='input-group date' id='tgl16'>
            <input type='text' class="form-control tgl_picker_input" 
                   name="konsul_tengah_selesai" 
                   value="<?=$data_edit->konsul_tengah_selesai;?>" required />
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
        </div>
    </div>
</div>
<div class="form-group">
    <label class="control-label col-lg-3">Konsultasi Akhir Semester</label>

    <div class="col-lg-3">
        <div class='input-group date' id='tgl17'>
            <input type='text' class="form-control tgl_picker_input" 
                   name="konsul_akhir_mulai" 
                   value="<?=$data_edit->konsul_akhir_mulai;?>" required />
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
        </div>
    </div>

    <div class="col-lg-1" 
         style="font-weight:bold;padding-left:0;padding-right:0;width:20px;padding-top:5px;">
        S/d
    </div>

    <div class="col-lg-3">
        <div class='input-group date' id='tgl18'>
            <input type='text' class="form-control tgl_picker_input" 
                   name="konsul_akhir_selesai" 
                   value="<?=$data_edit->konsul_akhir_selesai;?>" required />
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
        </div>
    </div>
</div>

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
     $("#tgl13, #tgl14, #tgl15, #tgl16, #tgl17, #tgl18").datepicker({
    format: "yyyy-mm-dd",
    autoclose: true,
    todayHighlight: true
}).on('change', function () {
    $(this).find("input").valid();
});

          //chosen select
          $(".chzn-select").chosen();
          $(".chzn-select-deselect").chosen({
              allow_single_deselect: true
          });
        
    $("#tgl11").datepicker({ 
  format: "yyyy-mm-dd",
  autoclose: true, 
  todayHighlight: true
}).on('change',function(){
  $("#tgl11 :input").valid();
});
$("#tgl12").datepicker({ 
  format: "yyyy-mm-dd",
  autoclose: true, 
  todayHighlight: true
}).on('change',function(){
  $("#tgl12 :input").valid();
});
    
      //trigger validation onchange
      $('select').on('change', function() {
          $(this).valid();
      });
      //hidden validate because we use chosen select
      $.validator.setDefaults({ ignore: ":hidden:not(select)" });
      
    $("#update_semester").validate({
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
            } else if (element.hasClass("waktu")) {
               element.parent().parent().append(error);
            }
            else if (element.hasClass("tgl_picker_input")) {
               element.parent().parent().append(error);
            }
            else if (element.hasClass("file-upload-data")) {
               element.parent().parent().parent().append(error);
            }  else if (element.hasClass("dosen-ke")) {
                  error.appendTo('.error-dosen');
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
                            $(".notif_top_up").fadeIn(1000);
                            $(".notif_top_up").fadeOut(1000, function() {
                                  //  window.history.back();
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