<!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Matakuliah</h1>
        <ol class="breadcrumb">
            <li>
              <a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a>
            </li>
            <li>
              <a href="<?=base_index();?>matkul">Matkul</a>
            </li>
            <li class="active">Edit Matkul</li>
        </ol>
    </section>
<?php
$prodi = $db->fetch_custom_single("select id_matkul, kurikulum.kur_id,jenjang_pendidikan.id_jenjang,jml_sks_wajib,jml_sks_pilihan,nama_kurikulum,jurusan.kode_jur,jurusan.nama_jur,jenjang_pendidikan.jenjang from
jurusan inner join jenjang_pendidikan
on jurusan.id_jenjang=jenjang_pendidikan.id_jenjang
inner join kurikulum on jurusan.kode_jur=kurikulum.kode_jur
inner join matkul on kurikulum.kur_id=matkul.kur_id
where matkul.id_matkul=?",array('id_matkul' =>uri_segment(3)));

?>
    <!-- Main content -->
    <section class="content">
    <div class="row">
      <div class="col-lg-12">
        <div class="box box-solid box-primary">
          <div class="box-header">
            <h3 class="box-title">Edit Matkul</h3>
            <div class="box-tools pull-right">
              <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
              <button class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
          </div>
          <div class="box-body">
            <form id="input_matkul" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/kurikulum/matkul_action.php?act=up">

              <div class="form-group">
                        <label for="Kurikulum" class="control-label col-lg-2">Kurikulum</label>
                        <div class="col-lg-10">
            <input type="text" value="<?=$prodi->nama_kurikulum;?>" class="form-control" readonly>
            <input type="hidden" name="kur_id" value="<?=$prodi->kur_id;?>" class="form-control">
             <input type="hidden" name="id_jenjang" value="<?=$prodi->id_jenjang;?>" class="form-control">
             <input type="hidden" name="id" value="<?=$data_edit->id_matkul;?>" class="form-control">

            </div>
                      </div><!-- /.form-group -->

              <div class="form-group">
                        <label for="Kurikulum" class="control-label col-lg-2">Program Studi</label>
                        <div class="col-lg-10">
            <input type="text" value="<?=$prodi->jenjang.' '.$prodi->nama_jur;?>" class="form-control" readonly>
            </div>
                      </div><!-- /.form-group -->

              <div class="form-group">
                <label for="Kode Matakuliah" class="control-label col-lg-2">Kode Matakuliah</label>
                <div class="col-lg-10">
                  <input type="text" name="kode_mk" value="<?=$data_edit->kode_mk;?>" class="form-control" required>
                </div>
              </div><!-- /.form-group -->

              <div class="form-group">
                <label for="Nama Matakuliah" class="control-label col-lg-2">Nama Matakuliah</label>
                <div class="col-lg-10">
                  <input type="text" name="nama_mk" value="<?=$data_edit->nama_mk;?>" class="form-control" required>
                </div>
              </div><!-- /.form-group -->

            <div class="form-group">
                        <label for="Tipe Mata Kuliah" class="control-label col-lg-2">Jenis Mata Kuliah</label>
                        <div class="col-lg-10">
            <select name="id_tipe_matkul" data-placeholder="Jenis Mata Kuliah ..." class="form-control chzn-select" tabindex="2" >
               <option value=""></option>
               <?php foreach ($db->fetch_all("tipe_matkul") as $isi) {
                  if ($isi->id_tipe_matkul==$data_edit->id_tipe_matkul) {
                    echo "<option value='$isi->id_tipe_matkul' selected>$isi->tipe_matkul</option>";
                  } else {
                    echo "<option value='$isi->id_tipe_matkul'>$isi->tipe_matkul</option>";
                  }
                  
               } ?>
              </select>
            </div>
                      </div><!-- /.form-group -->

              <div class="form-group">
                <label for="Semester" class="control-label col-lg-2">Semester</label>
                <div class="col-lg-1">
                  <input type="text" name="semester" class="form-control" value="<?=$data_edit->semester;?>" maxlength="1" onkeypress="return isNumberKey(event)" required="">
                </div>
                <div class="col-lg-9" style="padding-left:0;padding-top: 5px;font-style: italic;color:#999999">(1-8) contoh : 5</div>
              </div><!-- /.form-group -->

          <div class="form-group">
              <label for="Aktif" class="control-label col-lg-2">Matkul Wajib ?</label>
              <div class="col-lg-5">
                <?php if ($data_edit->a_wajib=="1") {
                ?>
                  <input name="a_wajib" class="make-switch" type="checkbox" checked checked data-on-text="Wajib" data-off-text="Tidak">
                <?php
              } else {
                ?>
                  <input name="a_wajib" class="make-switch" type="checkbox" checked data-on-text="Wajib" data-off-text="Tidak">
                <?php
              }?>
              </div>
          </div><!-- /.form-group -->

             <div class="form-group">
                <label for="SKS Tatap Muka" class="control-label col-lg-2">SKS Teori/Tatap Muka</label>
                <div class="col-lg-1">
                  <input type="text" maxlength="2" value="<?=$data_edit->sks_tm;?>" onkeypress="return isNumberKey(event)" name="sks_tm"  class="form-control sks_tm" >
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="SKS Praktek" class="control-label col-lg-2">SKS Praktikum</label>
                <div class="col-lg-1">
                  <input type="text" name="sks_prak" value="<?=$data_edit->sks_prak;?>" maxlength="2" onkeypress="return isNumberKey(event)"  class="form-control sks_prak" >
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="SKS Praktek Lapangan" class="control-label col-lg-2">SKS Praktek Lapangan</label>
                <div class="col-lg-1">
                  <input type="text" name="sks_prak_lap" value="<?=$data_edit->sks_prak_lap;?>" maxlength="2" onkeypress="return isNumberKey(event)" class="form-control sks_prak_lap" >
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="SKS Simulasi" class="control-label col-lg-2">SKS Simulasi</label>
                <div class="col-lg-1">
                  <input type="text" name="sks_sim" value="<?=$data_edit->sks_sim;?>" maxlength="2" onkeypress="return isNumberKey(event)" class="form-control sks_sim" >
                </div>
              </div><!-- /.form-group -->



          <div class="form-group">
                <label for="SKS Tatap Muka" class="control-label col-lg-2">SKS Kurikulum</label>
                <div class="col-lg-6">
                <div class="input-group" >
                    <input type="text" name="total_sks" maxlength="2" onkeypress="return isNumberKey(event)" value="<?=$data_edit->total_sks;?>" class="form-control total_sks" readonly>
                    <span class="input-group-addon" style="border: none">
                        (SKS Teori + SKS Praktikum + SKS Prak. Lapangan + SKS Simulai)
                    </span>
                </div>
                </div>
              </div><!-- /.form-group -->
              <div class="form-group">
                <label for="Keterangan" class="control-label col-lg-2">Metode Pembelajaran</label>
                <div class="col-lg-10">
                  <input type="text" name="metode_pelaksanaan_kuliah" value="<?=$data_edit->metode_pelaksanaan_kuliah;?>" class="form-control" >
                </div>
              </div><!-- /.form-group -->



              <div class="form-group">
                <label for="Keterangan" class="control-label col-lg-2">Tanggal Mulai Efektif</label>
              <div class="col-lg-3">
                <div class="input-group date" id="tgl1">
                    <input type="text" class="form-control" name="tgl_mulai_efektif" value="<?=$data_edit->tgl_mulai_efektif;?>">
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
              </div>
              </div><!-- /.form-group -->
              <div class="form-group">
                <label for="Keterangan" class="control-label col-lg-2">Tanggal Akhir Efektif</label>
              <div class="col-lg-3">
                <div class="input-group date" id="tgl2">
                    <input type="text" class="form-control" name="tgl_akhir_efektif" value="<?=$data_edit->tgl_akhir_efektif;?>">
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
              </div>
              </div><!-- /.form-group -->

              <div class="form-group">
              <label for="Tanggal Akhir Efektif" class="control-label col-lg-3">&nbsp;</label>
              <div class="col-lg-9">
                <h4><b>Data Prasyarat Matakuliah</b></h4>
              </div>
          </div><!-- /.form-group -->

              <div class="form-group">
                <label for="SKS Simulasi" class="control-label col-lg-3">Bobot Nilai Minimal untuk Lulus</label>
                <div class="col-lg-5">
                <div class="input-group">
                    <input type="text" autocomplete="off" class="form-control desimal" value="<?=$data_edit->bobot_minimal_lulus;?>" name="bobot_minimal_lulus"  />
                    <span class="input-group-addon" style="border: none">
                        Diisi dengan angka boleh desimal, dengan nilai: 0.00 s/d 4.00
                    </span>
                </div>
                </div>
              </div><!-- /.form-group -->
              
                      
              <div class="form-group">
                <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                <div class="col-lg-10">
                <a onclick="window.history.back(-1)" class="btn btn-default "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>
                 <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> <?php echo $lang["submit_button"];?></button>

           
                </div>
              </div><!-- /.form-group -->

            </form>
          </div>
        </div>
      </div>
    </div>

    </section><!-- /.content -->

<script type="text/javascript">
  $(document).ready(function () {
    function updateTotalSKS() {
      // Get values from each field with respective class names and parse as integers (default to 0 if empty)
      var sks_tm = parseInt($(".sks_tm").val()) || 0;
      var sks_prak = parseInt($(".sks_prak").val()) || 0;
      var sks_prak_lap = parseInt($(".sks_prak_lap").val()) || 0;
      var sks_sim = parseInt($(".sks_sim").val()) || 0;
      
      // Calculate the total
      var total = sks_tm + sks_prak + sks_prak_lap + sks_sim;
      
      // Set the total_sks field with the calculated sum
      $(".total_sks").val(total);
    }

 // Function to set the field to 0 if it's empty on focusout
    function setDefaultToZero() {
      if ($(this).val() === '') {
        $(this).val(0);
      }
    }


    // Attach the updateTotalSKS function to the input event of each SKS field
     $(".sks_tm, .sks_prak, .sks_prak_lap, .sks_sim").on("focusout", setDefaultToZero);
    $(".sks_tm, .sks_prak, .sks_prak_lap, .sks_sim").on("input", updateTotalSKS);
  });

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
      
    $("#input_matkul").validate({
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
            
          kur_id: {
          required: true,
          //minlength: 2
          },
        
          kode_mk: {
          required: true,
          //minlength: 2
          },
          semester : {
            required:true,
            maxlength:1,
          },
        
          id_jenjang: {
          required: true,
          //minlength: 2
          },
        
        },
         messages: {
            
          kur_id: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          kode_mk: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          id_jenjang: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },

        
        },
    
        submitHandler: function(form) {
            $("#loadnya").show();
            $(form).ajaxSubmit({
                type: "post",
                url: $(this).attr("action"),
                data: $("#input_matkul").serialize(),
                success: function(data) {
                    console.log(data);
                    $("#loadnya").hide();
                    if (data == "good") {
                        $(".notif_top_up").fadeIn(1000);
                        $(".notif_top_up").fadeOut(1000, function() {
                            //    window.history.back();
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

      function isNumberKey(evt)
      {
         var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

         return true;
      }
</script>
