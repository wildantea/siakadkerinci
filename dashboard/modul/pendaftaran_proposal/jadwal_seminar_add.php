<?php
session_start();
include "../../inc/config.php";
?>
<style type="text/css"> .datepicker {z-index: 1200 !important; } </style>
 <div class="alert alert-danger error_data" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning"></span>
        </div>
      <form id="input_jadwal_ruang" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/pendaftaran_proposal/jadwal_seminar_action.php?act=in">
     <div class="form-group">
                                <label for="Semester" class="control-label col-lg-3">Program Studi</label>
                                <div class="col-lg-5">
                                <select id="jurusan_jadwal" name="jurusan_jadwal" data-placeholder="Pilih Program Studi ..." class="form-control chzn-select" tabindex="2" required="">
                                <?php
                                looping_prodi();
                                ?>
                      </select>
                    </div>
                                <div class="col-lg-3">
                                  <?php
                                  $id_semester_aktif = get_sem_aktif();
                                  $semester = get_tahun_akademik($id_semester_aktif);
                                  ?>
                                  <input type="text" class="form-control" value="<?=$semester;?>" readonly>
 <input type="hidden" name="semester_jadwal" id="semester_jadwal" value="<?=$id_semester_aktif;?>">

         </div>
                              </div><!-- /.form-group -->
                      <div class="form-group">
                        <label for="Periode" class="control-label col-lg-3">Periode</label>
                        <div class="col-lg-9">
            <select name="periode_bulan_jadwal" data-placeholder="Pilih Periode Pedaftaran..." class="form-control chzn-select" tabindex="2" required id="periode_bulan_jadwal">
                  <?php
                        //03 is kode proposal in tb_jenis_pendaftaran
                         looping_periode_pendaftaran('03')
                         ?>
              </select>
            </div>
                <input type="hidden" id="kode_jenis_pendaftaran" value="03">
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Ruangan" class="control-label col-lg-3">Ruangan</label>
                        <div class="col-lg-9">
            <select name="id_ruang_jadwal" data-placeholder="Pilih Ruangan ..." class="form-control chzn-select" tabindex="2" required id="id_ruang_jadwal">
               <?php
  $akses_prodi = get_akses_prodi();
  $jurusan = $db->query("select * from view_prodi_jenjang $akses_prodi");
  print_r($jurusan);
    if ($jurusan->rowCount()==1) {
      $data = $db->query("select ruang_id,nm_ruang from ruang_ref
      inner join ruang_ref_prodi rr using(ruang_id)
      $akses_prodi and jenis_ruang=?",array('jenis_ruang' => '2'));
             echo "<option value=''>Pilih Ruangan</option>";
            foreach ($data as $dt) {
              echo "<option value='$dt->ruang_id'>$dt->nm_ruang</option>";
            } 
    }
?>
              </select>
            </div>
                      </div><!-- /.form-group -->

 <div class="form-group">
              <label for="Tanggal Mulai" class="control-label col-lg-3">Tanggal Seminar</label>
              <div class="col-lg-3">
                <div class='input-group date' id='tgl'>
                    <input type='text' class="form-control tanggal" name="tanggal_jadwal" autocomplete="off" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
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
$("#tgl").datepicker({ 
  format: "yyyy-mm-dd",
  autoclose: true, 
  todayHighlight: true
}).on('change',function(){
  $("#tgl :input").valid();
});

    $("#jurusan_jadwal").change(function(){
          $.ajax({
          type : "post",
          url : "<?=base_admin();?>modul/pendaftaran_proposal/get_periode.php",
          data : {id_pendaftaran:$('#kode_jenis_pendaftaran').val(),prodi:this.value,semester:$('#semester_jadwal').val()},
          success : function(data) {
              $("#periode_bulan_jadwal").html(data);
              $("#periode_bulan_jadwal").trigger("chosen:updated");

          }
      });

      $.ajax({
          type : "post",
          url : "<?=base_admin();?>modul/pendaftaran_proposal/get_ruangan_seminar.php",
          data : {prodi:this.value},
          success : function(data) {
              $("#id_ruang_jadwal").html(data);
              $("#id_ruang_jadwal").trigger("chosen:updated");

          }
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
       $.validator.addMethod("myFunc", function(val,element) {
        //console.log(this.currentElements);
        if(val=='all'){
          return false;
        } else {
          return true;
        }
      }, function(params, element) {
          return $(element).attr('data-placeholder');
        });

   $("#input_jadwal_ruang").validate({
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
            } else if (element.hasClass("tanggal")) {
                 element.parent().parent().append(error);
            } else if (element.attr("type") == "checkbox") {
                element.parent().parent().append(error);
            } else if (element.attr("type") == "radio") {
                element.parent().parent().append(error);
            } else {
                error.insertAfter(element);
            }
        },
                rules: {
            
          jurusan_jadwal: {
           myFunc:true
          //minlength: 2
          },
        
          periode_bulan_jadwal: {
          myFunc:true
          //minlength: 2
          },
        
          tanggal_jadwal: {
          required: true,
          //minlength: 2
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
                            $('#modal_jadwal_ruang').modal('hide');
                            $(".error_data").hide();
                            $(".notif_top").fadeIn(1000);
                            $(".notif_top").fadeOut(1000, function() {
                                dataTable_jadwal.draw();
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
