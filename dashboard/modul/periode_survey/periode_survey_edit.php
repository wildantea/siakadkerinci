<!-- Content Header (Page header) -->
              <section class="content-header">
                  <h1>Periode Survey</h1>
                    <ol class="breadcrumb">
                        <li>
                        <a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a>
                        </li>
                        <li>
                        <a href="<?=base_index();?>periode-survey">Periode Survey</a>
                        </li>
                        <li class="active">Edit Periode Survey</li>
                    </ol>
              </section>

              <!-- Main content -->
              <section class="content">
              <div class="row">
                  <div class="col-lg-12">
                      <div class="box box-solid box-primary">
                          <div class="box-header">
                              <h3 class="box-title">Edit Periode Survey</h3>
                              <div class="box-tools pull-right">
                                  <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-pencil"></i></button>
                              </div>
                          </div>
                      <div class="box-body">
                       <div class="alert alert-danger error_data" style="display:none">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <span class="isi_warning"></span>
                      </div>
                          <form id="edit_periode_survey" method="post" class="form-horizontal" action="<?=base_admin();?>modul/periode_survey/periode_survey_action.php?act=up">
                            
<div class="form-group">
                <label for="Tahun" class="control-label col-lg-3">Periode Tahun Akademik</label>
                <div class="col-lg-3">
                  <input type="text"  value="<?=ganjil_genap($data_edit->id_semester);?>" class="form-control" readonly>
                </div>
              </div><!-- /.form-group -->

              <input type="hidden" name="id_semester" value="<?=$data_edit->id_semester;?>">
          

 <div class="form-group">
              <label for="Tanggal Mulai Daftar" class="control-label col-lg-3 col-xs-12">Tanggal Aktif Awal Semester <span style="color:#FF0000">*</span></label>

          <div class="col-lg-2 col-xs-7">
          <div class="input-group date tgl_picker" data-target="periode_awal_mulai">
              <input type="text" class="form-control tgl_picker_input" readonly  required value="<?=tgl_indo($data_edit->periode_awal_mulai);?>">
              <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
              
              </div>
              <input type="hidden" name="periode_awal_mulai" value="<?=$data_edit->periode_awal_mulai;?>">
          </div>


             <div class="col-lg-1 col-xs-1" style="font-weight:bold;padding-left: 0;padding-right: 0;width: 20px;padding-top: 5px;">S/d</div> 

  <div class="col-lg-2 col-xs-7">
          <div class="input-group date tgl_picker" data-target="periode_awal_selesai">
              <input type="text" class="form-control tgl_picker_input" readonly  required value="<?=tgl_indo($data_edit->periode_awal_selesai);?>">
              <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
              
              </div>
              <input type="hidden" name="periode_awal_selesai" value="<?=$data_edit->periode_awal_selesai;?>">
          </div>

          </div><!-- /.form-group -->


          <div class="form-group">
              <label for="Tanggal Mulai Daftar" class="control-label col-lg-3 col-xs-12">Tanggal Aktif Tengah Semester <span style="color:#FF0000">*</span></label>

          <div class="col-lg-2 col-xs-7">
          <div class="input-group date tgl_picker" data-target="periode_tengah_mulai">
              <input type="text" class="form-control tgl_picker_input" readonly  required value="<?=tgl_indo($data_edit->periode_tengah_mulai);?>">
              <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
              
              </div>
              <input type="hidden" name="periode_tengah_mulai" value="<?=$data_edit->periode_tengah_mulai;?>">
          </div>


             <div class="col-lg-1 col-xs-1" style="font-weight:bold;padding-left: 0;padding-right: 0;width: 20px;padding-top: 5px;">S/d</div> 

  <div class="col-lg-2 col-xs-7">
          <div class="input-group date tgl_picker" data-target="periode_tengah_selesai">
              <input type="text" class="form-control tgl_picker_input" readonly  required value="<?=tgl_indo($data_edit->periode_tengah_selesai);?>">
              <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
              
              </div>
              <input type="hidden" name="periode_tengah_selesai" value="<?=$data_edit->periode_tengah_selesai;?>">
          </div>

          </div><!-- /.form-group -->


          <div class="form-group">
              <label for="Tanggal Mulai Daftar" class="control-label col-lg-3 col-xs-12">Tanggal Aktif Akhir Semester <span style="color:#FF0000">*</span></label>

          <div class="col-lg-2 col-xs-7">
          <div class="input-group date tgl_picker" data-target="periode_akhir_mulai">
              <input type="text" class="form-control tgl_picker_input"  readonly  required value="<?=tgl_indo($data_edit->periode_akhir_mulai);?>">
              <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
              
              </div>
              <input type="hidden" name="periode_akhir_mulai" value="<?=$data_edit->periode_akhir_mulai;?>">
          </div>


             <div class="col-lg-1 col-xs-1" style="font-weight:bold;padding-left: 0;padding-right: 0;width: 20px;padding-top: 5px;">S/d</div> 

  <div class="col-lg-2 col-xs-7">
          <div class="input-group date tgl_picker" data-target="periode_akhir_selesai">
              <input type="text" class="form-control tgl_picker_input"  readonly  required value="<?=tgl_indo($data_edit->periode_akhir_selesai);?>">
              <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
              
              </div>
              <input type="hidden" name="periode_akhir_selesai" value="<?=$data_edit->periode_akhir_selesai;?>" >
          </div>

          </div><!-- /.form-group -->


  <div class="form-group">
              <label for="Tanggal Mulai Daftar" class="control-label col-lg-3 col-xs-12">Tanggal Aktif Survei Lainnya <span style="color:#FF0000">*</span></label>

          <div class="col-lg-2 col-xs-7">
          <div class="input-group date tgl_picker" data-target="periode_lainya_mulai">
              <input type="text" class="form-control tgl_picker_input"  readonly  required value="<?=tgl_indo($data_edit->periode_lainya_mulai);?>">
              <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
              
              </div>
              <input type="hidden" name="periode_lainya_mulai" value="<?=$data_edit->periode_lainya_mulai;?>">
          </div>


             <div class="col-lg-1 col-xs-1" style="font-weight:bold;padding-left: 0;padding-right: 0;width: 20px;padding-top: 5px;">S/d</div> 

  <div class="col-lg-2 col-xs-7">
          <div class="input-group date tgl_picker" data-target="periode_lainya_selesai">
              <input type="text" class="form-control tgl_picker_input"  readonly  required value="<?=tgl_indo($data_edit->periode_lainya_selesai);?>">
              <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
              
              </div>
              <input type="hidden" name="periode_lainya_selesai" value="<?=$data_edit->periode_lainya_selesai;?>" >
          </div>

          </div><!-- /.form-group -->

          
                            <input type="hidden" name="id" value="<?=$data_edit->id_sem_survey;?>">
                            <div class="form-group">
                                <label for="tags" class="control-label col-lg-3">&nbsp;</label>
                                <div class="col-lg-9">
                                <a href="<?=base_index();?>periode-survey" class="btn btn-default "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> <?php echo $lang["submit_button"];?></button>
                                </div>
                            </div><!-- /.form-group -->
                          </form>
                      </div>
                  </div>
              </div>
              </section><!-- /.content -->

<script type="text/javascript">
    $(document).ready(function() {
    
          $(".tgl_picker").datepicker({
        format: "dd MM yyyy",
        autoclose: true,
        language: "id",
        todayHighlight: true,
        forceParse : false
        }).on("change",function(){
          var val = $(this).datepicker('getDate');
          var formatted = moment(val).format('YYYY-MM-DD');
          var target = $(this).data('target');
          //$(`[name="${target}"]`).val(formatted);
          $("input[name='"+target+"']").val(formatted);
          console.log(target);
          $(":input",this).valid();
    });
    
      //trigger validation onchange
      $('select').on('change', function() {
          $(this).valid();
      });
      //hidden validate because we use chosen select
      $.validator.setDefaults({ ignore: ":hidden:not(select)" });
      
    $("#edit_periode_survey").validate({
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
                            $(".error_data").hide();
                            $(".notif_top_up").fadeIn(1000);
                            $(".notif_top_up").fadeOut(1000, function() {
                                    window.location="<?=base_index();?>periode-survey";
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
