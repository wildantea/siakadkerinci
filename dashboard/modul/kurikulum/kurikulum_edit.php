<!-- Content Header (Page header) -->
              <section class="content-header">
                  <h1>Kurikulum</h1>
                    <ol class="breadcrumb">
                        <li>
                        <a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a>
                        </li>
                        <li>
                        <a href="<?=base_index();?>kurikulum">Kurikulum</a>
                        </li>
                        <li class="active">Edit Kurikulum</li>
                    </ol>
              </section>

              <!-- Main content -->
              <section class="content">
              <div class="row">
                  <div class="col-lg-12">
                      <div class="box box-solid box-primary">
                          <div class="box-header">
                              <h3 class="box-title">Edit Kurikulum</h3>
                              <div class="box-tools pull-right">
                                  <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                  <button class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                              </div>
                          </div>


                      <div class="box-body">
                          <form id="edit_kurikulum" method="post" class="form-horizontal" action="<?=base_admin();?>modul/kurikulum/kurikulum_action.php?act=up">
                            <div class="form-group">
                        <label for="Jurusan" class="control-label col-lg-2">Jurusan</label>
                        <div class="col-lg-10">
              <select name="kode_jur" data-placeholder="Pilih Jurusan..." class="form-control chzn-select" tabindex="2" required>
               <option value=""></option>
               <?php 
               $data = $db->query("select jurusan.kode_jur,jurusan.nama_jur,jenjang_pendidikan.jenjang from jurusan inner join jenjang_pendidikan
on jurusan.id_jenjang=jenjang_pendidikan.id_jenjang");
       echo "<option value=''>Pilih Program Studi</option>";
      foreach ($data as $isi) {
                  if ($data_edit->kode_jur==$isi->kode_jur) {
            echo "<option value='$isi->kode_jur' selected>$dt->jenjang $isi->nama_jur</option>";
          } else {
          echo "<option value='$isi->kode_jur'>$dt->jenjang $isi->nama_jur</option>";
            }

        echo "<option value='$dt->kode_jur'>$dt->jenjang $dt->nama_jur</option>";
      }
 ?>
              </select>
          </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Semester Berlaku" class="control-label col-lg-2">Mulai Berlaku</label>
                        <div class="col-lg-10">
              <select name="sem_id" data-placeholder="Pilih Semester Berlaku..." class="form-control chzn-select" tabindex="2" required>
               <option value=""></option>
               <?php 
               foreach ($db->query("select semester_ref.id_semester,
concat(tahun,'/',tahun+1,' ',jns_semester) as tahun_akademik
 from semester_ref inner join jenis_semester on semester_ref.id_jns_semester=jenis_semester.id_jns_semester
order by tahun desc") as $isi) {
                  if ($data_edit->sem_id==$isi->id_semester) {
                    echo "<option value='$isi->id_semester' selected>$isi->tahun_akademik</option>";
                  } else {
                  echo "<option value='$isi->id_semester'>$isi->tahun_akademik</option>";
                    }

                  echo "<option value='$isi->id_semester'>$isi->tahun_akademik</option>";
               }
 ?>
              </select>
          </div>
                      </div><!-- /.form-group -->

              <div class="form-group">
                <label for="Nama Kurikulum" class="control-label col-lg-2">Nama Kurikulum</label>
                <div class="col-lg-10">
                  <input type="text" name="nama_kurikulum" value="<?=$data_edit->nama_kurikulum;?>" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
         
              <div class="form-group">
                <label for="No SK Rektor" class="control-label col-lg-2">No SK Rektor</label>
                <div class="col-lg-10">
                  <input type="text" name="no_sk_rektor" value="<?=$data_edit->no_sk_rektor;?>" class="form-control" >
                </div>
              </div><!-- /.form-group -->
              
          <div class="form-group">
              <label for="Tanggal SK Rektor" class="control-label col-lg-2">Tanggal SK Rektor</label>
              <div class="col-lg-10">
                <input type="text" id="tgl1" data-rule-date="true" name="tgl_sk_rektor" value="<?=$data_edit->tgl_sk_rektor;?>" class="form-control" >
              </div>
          </div><!-- /.form-group -->
       
              
          <div class="form-group">
              <label for="Jml SKS Wajib" class="control-label col-lg-2">Jml SKS Wajib</label>
              <div class="col-lg-3">
                <input type="text" onkeypress="return isNumberKey(event)" data-rule-number="true" name="jml_sks_wajib" value="<?=$data_edit->jml_sks_wajib;?>" class="form-control" >
              </div>
          </div><!-- /.form-group -->
          
          <div class="form-group">
              <label for="Jml SKS Pilihan" class="control-label col-lg-2">Jml SKS Pilihan</label>
              <div class="col-lg-3">
                <input type="text" onkeypress="return isNumberKey(event)" data-rule-number="true" name="jml_sks_pilihan" value="<?=$data_edit->jml_sks_pilihan;?>" class="form-control" >
              </div>
          </div><!-- /.form-group -->
          
          <div class="form-group">
              <label for="Total SKS" class="control-label col-lg-2">Total SKS</label>
              <div class="col-lg-3">
                <input type="text" name="total_sks" value="<?=$data_edit->total_sks;?>" class="form-control" readonly="" >
              </div>
          </div><!-- /.form-group -->

      
              <div class="form-group">
                <label for="Ket" class="control-label col-lg-2">Ket</label>
                <div class="col-lg-10">
                  <input type="text" name="ket" value="<?=$data_edit->ket;?>" class="form-control" >
                </div>
              </div><!-- /.form-group -->

          
                            <input type="hidden" name="id" value="<?=$data_edit->kur_id;?>">
              <div class="form-group">
                <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                <div class="col-lg-10">
                <a href="<?=base_index();?>kurikulum" class="btn btn-default "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>
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
    
      $("#tgl1").datepicker( {
          format: "yyyy-mm-dd",
      });
     
      //trigger validation onchange
      $('select').on('change', function() {
          $(this).valid();
      });
      //hidden validate because we use chosen select
      $.validator.setDefaults({ ignore: ":hidden:not(select)" });
      
    $("#edit_kurikulum").validate({
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
            
          kode_jur: {
          required: true,
          //minlength: 2
          },
        
          sem_id: {
          required: true,
          //minlength: 2
          },
        
          nama_kurikulum: {
          required: true,
          //minlength: 2
          },
        
        },
         messages: {
            
          kode_jur: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          sem_id: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          nama_kurikulum: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
        },
    
        submitHandler: function(form) {
            $("#loadnya").show();
            $(form).ajaxSubmit({
                type: "post",
                url: $(this).attr("action"),
                data: $("#edit_kurikulum").serialize(),
                success: function(data) {
                    console.log(data);
                    $("#loadnya").hide();
                    if (data == "good") {
                        $(".notif_top_up").fadeIn(1000);
                        $(".notif_top_up").fadeOut(1000, function() {
                                window.history.back();
                            });
                    } else if (data == "die") {
                        $("#informasi").modal("show");
                    } else {
                        $(".errorna").fadeIn();
                    }
                }
            });
        }
    });
});
</script>
