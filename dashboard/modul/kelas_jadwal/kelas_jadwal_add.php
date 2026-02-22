 <link rel="stylesheet" href="<?= base_url() ?>dashboard/assets/plugins/iCheck/all.css">
<style type="text/css">
  .red{
    color : red;
  }
</style>
<!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Kelas</h1>
        <ol class="breadcrumb">
            <li>
              <a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a>
            </li>
            <li>
              <a href="<?=base_index();?>kelas-jadwal">Kelas</a>
            </li>
            <li class="active">Tambah Kelas</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
    <div class="row">
      <div class="col-lg-12">
        <div class="box box-solid box-primary">
          <div class="box-header">
            <h3 class="box-title">Tambah Kelas</h3>
            <div class="box-tools pull-right">
              <button class="btn btn-info btn-sm"><i class="fa fa-plus"></i></button>
            </div>
          </div>
          <div class="box-body">
 <div class="alert alert-danger error_data" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning"></span>
        </div>
            <form id="input_kelas" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/kelas_jadwal/kelas_jadwal_action.php?act=in">   

            <div class="form-group">
                <label for="Kode Paralel" class="control-label col-lg-2">Program Studi <font color="#FF0000">*</font></label>
                <div class="col-lg-6">
                  <select name="kode_jur" id="kode_jur" data-placeholder="Program Studi ..." class="form-control chzn-select" tabindex="2" required="">
                   <?php
                                looping_prodi();
                                ?>

                  </select>
                </div>
            </div>
            <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Semester</label>
                        <div class="col-lg-6">
                        <select id="sem_id" name="sem_id" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2" required="">
                        <?php 
                        looping_semester();
                        ?>
              </select>
          </div>
                      </div><!-- /.form-group -->
                      
            <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Kurikulum</label>
                        <div class="col-lg-6">
                        <select id="kur_id" name="kur_id" data-placeholder="Pilih Kurikulum ..." class="form-control chzn-select" tabindex="2" required="">
                        <?php 
                        looping_kurikulum_kelas();
                        ?>
              </select>
          </div>
                      </div><!-- /.form-group -->
            <div class="form-group">
              <label for="Mata Kuliah" class="control-label col-lg-2">Mata Kuliah <font color="#FF0000">*</font></label>
              <div class="col-lg-6">
                <select name="id_matkul" id="id_matkul" required data-placeholder="Pilih Mata Kuliah ..." class="form-control chzn-select" tabindex="2" >
                   <option value="">-Pilih Matakuliah-</option>
                </select>
              </div>
            </div>


            <div class="form-group">
              <label for="Kode Paralel" class="control-label col-lg-2">Jenis Kelas <font color="#FF0000">*</font></label>
              <div class="col-lg-3">
                <select id="id_jenis_kelas" name="id_jenis_kelas" required data-placeholder="Pilih Jenis Kelas ..." class="form-control chzn-select" tabindex="2" >
                 <option value="">-Pilih Jenis Kelas-</option>
                 <?php
                 $kl = $db->fetch_all("jenis_kelas");
                 foreach ($kl as $kls) {
                   echo "<option value='$kls->id'>$kls->nama_jenis_kelas</option>";
                 }
                 ?>


               </select>
             </div>
           </div> 

          <div class="form-group">
              <label for="Peserta Maximal" class="control-label col-lg-2">Nama Kelas <font color="#FF0000">*</font></label>
              <div class="col-lg-2">
                <input type="text" id="kls_nama" maxlength="5"  name="kls_nama" placeholder="Maximal 5 Karakter" class="form-control" required >
              </div>
          </div>


          <div class="form-group">
              <label for="Peserta Maximal" class="control-label col-lg-2">Peserta Maximal <font color="#FF0000">*</font></label>
              <div class="col-lg-2">
                <input type="text" onkeypress="return isNumberKey(event)" required name="peserta_max" placeholder="Jumlah Max" class="form-control" >
              </div>
          </div>
          
          <div class="form-group">
              <label for="Peserta Minimal" class="control-label col-lg-2">Peserta Minimal <font color="#FF0000">*</font></label>
              <div class="col-lg-2">
                <input type="text" onkeypress="return isNumberKey(event)" required name="peserta_min" placeholder="Jumlah Min" class="form-control" >
              </div>
          </div>

          <div class="form-group">
              <label for="is_open" class="control-label col-lg-2">Aktif</label>
              <div class="col-lg-10">
                <input name="is_open" class="make-switch" type="checkbox" checked>
              </div>
          </div>
<!--           <hr>
           <div class="form-group">
              <label  class="control-label col-lg-2">Komponen Penilaian</label>
              <div class="col-lg-10">
                <div class="input-group" style="width:100px">
                   <a class="btn btn-success" data-toggle="modal" data-target="#modalKomponen"><i class="fa fa-plus"></i> Tambah Komponen</a>
                </div>               
              </div>
            </div>
           <div id="list_komponen">
             
           </div> -->
           <hr>  
          <div class="form-group">
              <label for="Catatan" class="control-label col-lg-2">Bahasan</label>
              <div class="col-lg-10">
                <textarea class="form-control col-xs-12" rows="5" name="catatan" ></textarea>
              </div>
          </div>
          
                      
              <div class="form-group">
                <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                <div class="col-lg-10">
                <a href="<?=base_index();?>kelas-jadwal" class="btn btn-default "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>
                 <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> <?php echo $lang["submit_button"];?></button>

                </div>
              </div>

            </form>

          </div>
        </div>
      </div>
    </div>

    </section><!-- /.content -->
           <div id="modalKomponen" class="modal fade" role="dialog" >
                              <div class="modal-dialog" style="width:70%">

                                <!-- Modal content-->
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title text-center">Pilih Komponen Penilaian</h4>
                                  </div>
                                  <div class="modal-body">
                                     <table id="dtb_manual" class="table table-bordered table-striped">
                                      <thead>
                                          <tr>
                                            <th style="width: 20px"></th>                                            
                                            <th>Nama Komponen</th>                                     
                                          </tr>
                                      </thead>
                                      <tbody>
                                        <?php
                                        $q= $db->query("select * from komponen_nilai where isShow='1' ");
                                        foreach ($q as $kpp) {
                                         echo "
                                            <tr>
                                            <th><input type='checkbox' name='komponen[]' value='$kpp->id==$kpp->nama_komponen' class='minimal data-komponen'></th>                                            
                                            <th>$kpp->nama_komponen</th>                                 
                                          </tr>
                                             ";
                                        }
                                        ?>
                                      </tbody>
                                  </table>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-success" id="btn-komponen" data-dismiss="modal">Pilih Komponen</button>
                                  </div>
                                </div>

                              </div>
                            </div>
<script src="<?= base_url() ?>dashboard/assets/plugins/iCheck/icheck.min.js"></script> 
<script type="text/javascript">
  $("#kode_jur").change(function(){
        $.ajax({
        type : "post",
        url : "<?=base_admin();?>modul/kelas_jadwal/get_kurikulum.php",
        data : {kode_jur:this.value},
        success : function(data) {
            $("#kur_id").html(data);
            $("#kur_id").trigger("chosen:updated");

        }
    });
  });

  $("#kode_paralel").change(function(){
      $("#kls_nama").val($('#kode_paralel option:selected').text());
  });

  $("#kur_id").change(function(){
        $.ajax({
        type : "post",
        url : "<?=base_admin();?>modul/kelas_jadwal/get_mat_kurikulum.php",
        data : {kur_id:this.value},
        success : function(data) {
            $("#id_matkul").html(data);
            $("#id_matkul").trigger("chosen:updated");

        }
    });
  });

    var tot = 0;
    var nilai =[];
    nilai["a-terstruktur"] = 0;
    nilai["a-lain-lain"] = 0;
    nilai["a-mandiri"] =0;
    nilai["a-uts"] =0;
    nilai["a-uas"] =0;
    nilai["a-presensi"] = 0;
    var jml_dosen =0;
    function hitung(jml,ket){
      
       nilai["a-"+ket]=parseInt(jml);
       tot = parseInt(nilai["a-terstruktur"]) + parseInt(nilai["a-lain-lain"]) + parseInt(nilai["a-mandiri"]) + parseInt(nilai["a-uts"]) + parseInt(nilai["a-uas"]) + parseInt(nilai["a-presensi"]);
       
       if (tot<=100) {
            $("#total").removeClass("red");
           $("#total").html(tot+" %");
       }else{
         $("#total").addClass("red");
         $("#total").html(tot+" % * MELEBIHI PRESENTASE");
         $("#"+ket).val('');
         $("#"+ket).focus();
         tot = tot - parseInt(jml);
       }
      
    }
    function pilih_dosen(nip){
        // $('#loadnya').show();
        jml_dosen++;
        $.ajax({
            type: 'POST',
            url: '<?=base_admin();?>modul/kelas/add_dosen.php',
            data: 'nip='+nip+'&jml_dosen='+jml_dosen,
            success: function(result) {
              // $('#loadnya').hide();
              $('#myModal').modal('hide');
              $("#dosen_ajar").append(result);
              $("#jml_dosen").val(jml_dosen);
            },
            //async:false
        });
    }


    function hapus_dosen(id){
      $("#dosen_"+id).remove();
    }

    function hapus_komponen(id) {
      $("#komponen-"+id).remove();
      //setInterval(function(){ $("#komponen-"+id).remove(); }, 3000);
      
    }

    $(function () {
     $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass: 'iradio_minimal-blue'
    });
   });

    $(document).ready(function() {
     
    $('#btn-komponen').on('click', function () {
       //var arr = [];
      // $("#list_komponen").html('');
       $('.data-komponen:checked').each(function () {
          var ru = $(this).val().split("==");
          if(($(this).prop("checked") == true ) && ($("#"+ru[0]).length==0)){
               $("#komponen-"+ru[0]).remove();
               $("#list_komponen").append(" <div class='form-group' id='komponen-"+ru[0]+"'><label  class='control-label col-lg-2'>"+ru[1]+"</label><div class='col-lg-10'><div class='input-group' style='width:100px'><input type='text' onkeypress='return isNumberKey(event)' class='form-control' style='width:100px' name='komponen_"+ru[0]+"' id='komponen_"+ru[0]+"' ><span class='input-group-addon'>%</span><button onclick='hapus_komponen(\""+ru[0]+"\")'  class='btn btn-primary ' style='float:right;position:relative;right:-3px' ><i class='fa fa-minus'></i></button></div></div></div>");  
            }          
       });
     });
    
          //trigger validation onchange
      $('select').on('change', function() {
          $(this).valid();
      });
      //hidden validate because we use chosen select
      $.validator.setDefaults({ ignore: ":hidden:not(select)" });

    $("#input_kelas").validate({
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
                             $('.error_data').fadeIn();
                             $('html, body').animate({
                                scrollTop: ($('.error_data').first().offset().top)
                            },500);
                          } else if(responseText[index].status=='good') {
                            $('.error_data').hide();
                            $(".notif_top").fadeIn(1000);
                            $(".notif_top").fadeOut(1000, function() {
                                    window.history.back();
                            });
                          } else {
                             console.log(responseText);
                             $('.isi_warning').text(responseText[index].error_message);
                               $('.error_data').fadeIn();
                             $('html, body').animate({
                                scrollTop: ($('.error_data').first().offset().top)
                            },500);
                          }
                    });
                }

            });
        }
    });
});
</script>
