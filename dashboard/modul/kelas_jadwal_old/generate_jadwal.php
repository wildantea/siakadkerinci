<!-- Content Header (Page header) -->
 <link rel="stylesheet" href="<?= base_url() ?>dashboard/assets/plugins/iCheck/all.css">
    <section class="content-header">
        <h1>Jadwal Kuliah</h1>
        <ol class="breadcrumb">
            <li>
              <a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a>
            </li>
            <li>
              <a href="<?=base_index();?>jadwal-kuliah">Jadwal Kuliah</a>
            </li>
            <li class="active">Generate Jadwal</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
    <div class="row">
      <div class="col-lg-12">
        <div class="box box-solid box-primary">
          <div class="box-header">
            <h3 class="box-title">Generate Jadwal</h3>
            <div class="box-tools pull-right">
              <button class="btn btn-info btn-sm"><i class="fa fa-gear"></i></button>
            </div>
          </div>
          <div class="box-body">
<table class="table table-bordered table-striped">
      <tbody><tr>
          <td width="20%">Program Studi</td>
<td colspan="5">: <?=$db->fetch_single_row("view_prodi_jenjang","kode_jur",$dec->dec($_GET['jur']))->jurusan;?></td>
        </tr>
        <tr>
            <td>Periode Semester <font color="#FF0000">*</font></td>
            <td>: <?=$db->fetch_single_row("view_semester","id_semester",$dec->dec($_GET['sem']))->tahun_akademik;?></td>
        </tr>
    </tbody></table>
    <hr>
            <form id="input_jadwal_kuliah" method="POST" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/kelas_jadwal/kelas_jadwal_action.php?act=gen_jadwal">
              <label class="control-label">Ruangan Yang Akan di Generate</label><hr>               
              <div class="form-group">
               <input type="hidden" name="jur" value="<?=$dec->dec($_GET['jur']);?>">
               <input type="hidden" name="sem" value="<?=$dec->dec($_GET['sem']);?>">
                <div class="col-lg-12">
                   <a class="btn btn-success " style="cursor:pointer;float:right" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i> Pilih Ruangan</a>
                
                  <table class="table">
                    <thead>
                       <tr>                        
                          <th>Nama Ruangan</th>                       
                          <th>Kapasitas</th>
                       </tr>                       
                    </thead>
                    <tbody id="list_ruangan">

                    </tbody>
                  </table>
                 </div>
              </div><!-- /.form-group -->
              
               <label class="control-label">Sessi Waktu Yang akan di Generate</label><hr>               
              <div class="form-group">
               
                <div class="col-lg-12">
                   <a class="btn btn-success " style="cursor:pointer;float:right" data-toggle="modal" data-target="#modalSessi"><i class="fa fa-plus"></i> Pilih Sesi Kuliah</a>
               
                  <table class="table">
                    <thead>
                       <tr>                        
                          <th>Sesi</th>                       
                          <th>Jam Mulai</th>  
                          <th>Jam Selesai</th>
                       </tr>                       
                    </thead>
                    <tbody id="list_sesi">

                    </tbody>
                  </table>
                   </div>
              </div><!-- /.form-group -->
               
                 <label class="control-label">Hari Waktu Yang akan di Generate</label><hr>               
              <div class="form-group">
               
                <div class="col-lg-12">
                   <a class="btn btn-success " style="cursor:pointer;float:right" data-toggle="modal" data-target="#modalHari"><i class="fa fa-plus"></i> Pilih Hari</a>
               
                  <table class="table">
                    <thead>
                       <tr>                        
                          <th>Hari</th>                      
                         
                       </tr>                       
                    </thead>
                    <tbody id="list_hari">

                    </tbody>
                  </table>
                   </div>
              </div><!-- /.form-group -->
                      
              <div class="form-group">
              
                <div class="col-lg-12">
                  <button type="submit" class="btn btn-primary " ><i class="fa fa-gears"></i> Generate Jadwal</button>
                </div>
              </div><!-- /.form-group -->

            </form>

          
          </div>
        </div>
      </div>
    </div>

    </section><!-- /.content -->
      <div id="myModal" class="modal fade" role="dialog" >
                             <div class="modal-dialog" style="width:70%">

                                <!-- Modal content-->
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title text-center">Pilih Ruangan</h4>
                                  </div>
                                  <div class="modal-body">
                                  
                                     <table id="dtb_ruang_ref" class="table table-bordered table-striped">
                                      <thead>
                                          <tr>
                                            <th></th>                                            
                                            <th>Nama Ruang</th>
                                            <th>Kapasitas</th>                                      
                                          </tr>
                                      </thead>
                                      <tbody>
                                        <?php
                                          $q= $db->query("select vp.jurusan, r.kapasitas, r.ruang_id, r.nm_ruang from ruang_ref r join gedung_ref g 
on r.gedung_id=g.gedung_id
inner join view_prodi_jenjang vp on r.kode_jur=vp.kode_jur
                                             where r.kode_jur='".$dec->dec($_GET['jur'])."'");
              foreach ($q as $k) {
                                         echo "
                                            <tr>
                                            <th><input type='checkbox' name='ruangan[]' value='$k->ruang_id===$k->nm_ruang===$k->kapasitas' class='minimal'></th>                                            
                                            <th>$k->nm_ruang</th>
                                            <th>$k->kapasitas</th>                        
                  </tr>
                                             ";
     }
                                        ?>
                                     </tbody>
                                  </table>                                  
                                 
                                  </div>
                                  <div class="modal-footer">
                                    <button type="submit" id="btn-kelas" class="btn btn-success" data-dismiss="modal">Pilih Ruang</button>
                                  </div>
                                </div>

                              </div>
                            
                            </div>
          <div id="modalSessi" class="modal fade" role="dialog" >
        
                              <div class="modal-dialog" style="width:70%">

                                <!-- Modal content-->
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title text-center">Pilih Sesi Waktu</h4>
                                  </div>
                                  <div class="modal-body">
                                  
                                     <table id="dtb_manual" class="table table-bordered table-striped">
                                      <thead>
                                          <tr>
                                            <th></th>                                            
                                            <th>Sesi Waktu</th>
                                            <th>Jam Mulai</th>   
                                            <th>Jam Selesai</th>                                        
                                          </tr>
                                      </thead>
                                      <tbody>
                                        <?php
                                        $qq= $db->query("select * from sesi_waktu ");
                                        foreach ($qq as $kk) {
                                         echo "
                                            <tr>
                                            <th><input type='checkbox' name='sessi[]' value='$kk->id_sesi===$kk->sesi===$kk->jam_mulai===$kk->jam_selesai' class='minimal check-kelas'></th>                                            
                                            <th>$kk->sesi</th>
                                            <th>$kk->jam_mulai</th>    
                                            <th>$kk->jam_selesai</th>                                       
                                          </tr>
                                             ";
                                        }
                                        ?>
                                      </tbody>
                                  </table>                                  
                                 
                                  </div>
                                  <div class="modal-footer">
                                    <button type="submit" id="btn-sessi" class="btn btn-success" data-dismiss="modal">Pilih Sessi</button>
                                  </div>
                                </div>

                              </div>
                             
                            </div>

                              <div id="modalHari" class="modal fade" role="dialog" >
        
                              <div class="modal-dialog" style="width:70%">

                                <!-- Modal content-->
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title text-center">Pilih hari</h4>
                                  </div>
                                  <div class="modal-body">
                                  
                                     <table id="dtb_manual" class="table table-bordered table-striped">
                                      <thead>
                                          <tr>
                                            <th></th>                                            
                                            <th>Hari</th>                                                                                
                                          </tr>
                                      </thead>
                                      <tbody>
                                        <?php
                                        $qh= $db->query("select * from hari_ref ");
                                        foreach ($qh as $kk) {
                                         echo "
                                            <tr>
                                            <th style='width:40px'><input type='checkbox' name='hari[]' value='$kk->hari' class='minimal check-hari'></th>                                            
                                            <th>$kk->hari</th>
                                                                                 
                                          </tr>
                                             ";
                                        }
                                        ?>
                                      </tbody>
                                  </table>                                  
                                 
                                  </div>
                                  <div class="modal-footer">
                                    <button type="submit" id="btn-hari" class="btn btn-success" data-dismiss="modal">Pilih Hari</button>
                                  </div>
                                </div>

                              </div>
                             
                            </div>

                       <div id="modalSukses" class="modal fade" role="dialog" data-backdrop="static" and data-keyboard="false" >
        
                              <div class="modal-dialog" style="width:70%">

                                <!-- Modal content-->
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title text-center">Hasil Generate Jadwal</h4>
                                  </div>
                                  <div class="modal-body hasil-generate">

                                  </div>
                                  <div class="modal-footer">
                                    <button id="btn-hari" class="btn btn-success" data-dismiss="modal">Tutup</button>
                                  </div>
                                </div>

                              </div>
                             
                            </div>
<script src="<?= base_url() ?>dashboard/assets/plugins/iCheck/icheck.min.js"></script>
<script type="text/javascript">

   var dataTable = $("#dtb_ruang_ref").DataTable();



 function hapus_ruangan(id){

      $("#"+id).remove();
      
    }
 $(function () {
   $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass: 'iradio_minimal-blue'
    });
  });
    $(document).ready(function() {
     
    $('#btn-kelas').on('click', function () {
       //var arr = [];
       $('.minimal:checked').each(function () {
          var ru = $(this).val().split("===");
          if(($(this).prop("checked") == true ) && ($("#"+ru[0]).length==0)){
             
               $("#list_ruangan").append("<tr id='"+ru[0]+"'><td><input type='hidden' name='ruangan[]' value='"+ru[0]+"==="+ru[1]+"'>"+ru[1]+"</td><td>"+ru[2]+" <button onclick='hapus_ruangan("+ru[0]+")'  class='btn btn-primary ' style='float:right' ><i class='fa fa-minus'></i></button></td></tr>");  
            }
          
       });

     });

    $('#btn-hari').on('click', function () {
       //var arr = [];
       $('.check-hari:checked').each(function () {
          var ru = $(this).val();
          if(($(this).prop("checked") == true ) && ($("#"+ru[0]).length==0)){
             
               $("#list_hari").append("<tr id='"+ru+"'><td><input type='hidden' name='hari[]' value='"+ru+"'>"+ru+" <button onclick='hapus_ruangan(\""+ru+"\")'  class='btn btn-primary ' style='float:right' ><i class='fa fa-minus'></i></button></td></tr>");  
            }
          
       });

     });

     $('#btn-sessi').on('click', function () {
       //var arr = [];
       $('.check-kelas:checked').each(function () {
          var ru = $(this).val().split("===");
          if(($(this).prop("checked") == true ) && ($("#"+ru[0]).length==0)){
              // alert(ru[0]);
               $("#list_sesi").append("<tr id='"+ru[0]+"'><td><input type='hidden' name='sesi[]' value='"+ru[2]+"==="+ru[3]+"'>"+ru[1]+"</td><td>"+ru[2]+"</td><td>"+ru[3]+" <button onclick='hapus_ruangan("+ru[0]+")'  class='btn btn-primary ' style='float:right' ><i class='fa fa-minus'></i></button></td></tr>");  
            }       
       });
     });

     $('#modalSukses').on('hidden.bs.modal', function () {
         document.location="<?= base_index() ?>kelas-jadwal";
      })
    
    
    $("#input_jadwal_kuliah").validate({
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
                type: "post",
                url: $(this).attr("action"),
                data: $("#input_jadwal_kuliah").serialize(),
                success: function(data) {
                    console.log(data);
                    $("#loadnya").hide();
                    $(".hasil-generate").html(data);
                    $('#modalSukses').modal('toggle');
                    $('#modalSukses').modal('show');
                }
            });
        }
    });
});
</script>
