<!-- Content Header (Page header) -->

    <section class="content-header">
        <h1>Rencana Studi</h1>
        <ol class="breadcrumb">
            <li>
              <a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a>
            </li>
            <li>
              <a href="<?=base_index();?>rencana-studi">Rencana Studi</a>
            </li>
            <li class="active">Add Rencana Studi</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
    <div class="row">
      <div class="col-lg-12">
      <?php
      if ($akm_id=='') {
          if ($_SESSION['group_level']=='mahasiswa') {
              $m = get_atribut_mhs($_SESSION['username']);  
          }else{
            $m=get_atribut_mhs($_GET['nim']);
          }      
           
      ?>
          <div class="box box-solid box-primary">
          <div class="box-header">
            <h3 class="box-title">Add Rencana Studi Periode <?= tampil_periode($sem_aktif->id_semester); ?></h3>
            <div class="box-tools pull-right">
              <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
              <button class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
          </div>
       
     

          <div class="box-body">


          <div class="form-group">
              <label for="Tanggal Masuk " class="control-label col-lg-2">Nama Mahasiswa</label>
              <div class="col-lg-10">
                 <b> <?= strtoupper($m->nama); ?> </b>
              </div>
          </div><!-- /.form-group --><br>
          <div class="form-group">
              <label for="Tanggal Masuk " class="control-label col-lg-2">NIM</label>
              <div class="col-lg-10">
                  <?= $m->nim; ?>
              </div>
          </div><!-- /.form-group --><br>
           <div class="form-group">
              <label for="Tanggal Masuk " class="control-label col-lg-2">Jurusan/Prodi</label>
              <div class="col-lg-10">
                  <?= $m->nama_jur; ?>
              </div>
          </div><!-- /.form-group --><br>
               <div class="alert alert-danger" style="text-align:center" role="alert" id="warning">
                  <strong>Warning</strong> Anda Melakukan Pembayaran Untuk Semester Ini
                </div>
            </div>
           </div>
         </div>
       </div>
     </div>
      <?php
      }else{
        echo $akm_id;
                           $q = $db->query("select js.id_jns_semester, k.krs_id, k.sem_id as sem, 
                s.kode_jur,sum(kr.sks) as tot_sks,s.id_semester,
                a.*,js.jns_semester,sf.tahun from krs k join semester s on k.sem_id=s.sem_id
                left join krs_detail kr on kr.id_krs=k.krs_id
                join akm a on a.mhs_nim=k.mhs_id and a.sem_id=s.id_semester
                join semester_ref sf on sf.id_semester=s.id_semester
                join jenis_semester js on js.id_jns_semester=sf.id_jns_semester
                       where a.akm_id='".$akm_id."' group by k.sem_id");
           
      foreach ($q as $ku) {
              $m = get_atribut_mhs($ku->mhs_nim);        
              $sem = substr($ku->id_semester, 0,4);
              $sem2 = $sem+1;
         ?>
   
        <div class="box box-solid box-primary">
          <div class="box-header">
            <h3 class="box-title">Add Rencana Studi Periode <?= $ku->jns_semester." $sem/$sem2"; ?></h3>
            <div class="box-tools pull-right">
              <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
              <button class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
          </div>
       
     

          <div class="box-body">


          <div class="form-group">
              <label for="Tanggal Masuk " class="control-label col-lg-2">Nama Mahasiswa</label>
              <div class="col-lg-10">
                 <b> <?= strtoupper($m->nama); ?> </b>
              </div>
          </div><!-- /.form-group --><br>
          <div class="form-group">
              <label for="Tanggal Masuk " class="control-label col-lg-2">NIM</label>
              <div class="col-lg-10">
                  <?= $m->nim; ?>
              </div>
          </div><!-- /.form-group --><br>
           <div class="form-group">
              <label for="Tanggal Masuk " class="control-label col-lg-2">Jurusan/Prodi</label>
              <div class="col-lg-10">
                  <?= $m->nama_jur; ?>
              </div>
          </div><!-- /.form-group --><br>
          <div class="form-group">
              <label for="Tanggal Masuk " class="control-label col-lg-2">Jatah SKS</label>
              <div class="col-lg-10">
                <span id="jatah"> <?= $ku->jatah_sks; ?> </span> 
                <input type="hidden" id="jatah_jml" value="<?= $ku->jatah_sks; ?>">
              </div>
          </div><!-- /.form-group --><br>
            <div class="form-group">
              <label for="Tanggal Masuk " class="control-label col-lg-2">Total SKS Diambil</label>
              <div class="col-lg-10">
                <span id="diambil">  <?php if ($ku->tot_sks=='') {
                 echo "0";
                } else{echo $ku->tot_sks;} ?> </span> 
                   <input type="hidden" id="diambil_jml" value="<?= $ku->tot_sks; ?>">
              </div>
          </div><!-- /.form-group --><br>
           <div class="form-group">
              <label for="Tanggal Masuk " class="control-label col-lg-2">Sisa Jatah SKS</label>
              <div class="col-lg-10">
                 <span id="sisa">
                  <?= $ku->jatah_sks - $ku->tot_sks; ?>
                   <input type="hidden" id="sisa_jml" value="<?= $ku->jatah_sks - $ku->tot_sks; ?>">
                </span>
              </div>
          </div><!-- /.form-group --><br>
          
          <?php
          if ($_SESSION['group_level']=='admin') {
             include 'tabel_krs.php';
          }else if($_SESSION['group_level']='mahasiswa'){
            if (cek_status_registrasi($m->nim,$sem_aktif->id_semester)==true) {
              if (get_avaliable_tanggal('krs',$sem_aktif->sem_id)==true) {
                  include 'tabel_krs.php';
               }else{
                ?>
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h4><i class="icon fa fa-ban"></i> Warning!</h4>
                  <p style="font-size:18px">Sekarang bukan periode KRS</p>
                </div>
                <?php
               }
            }else{
           //  var_dump(cek_status_registrasi($m->nim,$sem_aktif->id_semester));
              ?>
               <div class="alert alert-danger" style="text-align:center" role="alert" id="warning">
                  <strong>Warning</strong> Anda Melakukan Pembayaran Untuk Semester Ini
                </div>
              <?php
            }
             
          }
          ?>
          </div>
        </div>
        <?php
      } 
       ?>
      </div>
    </div>
  

    </section><!-- /.content -->
  <div class="modal modal-success fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content" >
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel" style="text-align:center">Informasi Pengambilan KRS<span id="semester"></span></h4>
        </div>
        <div class="modal-body">
          ...
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" data-dismiss="modal">Close</button>
        
        </div>
      </div>
    </div>  
  </div>
    <?php
           
      }
     ?>
<script type="text/javascript">
    $(document).ready(function() {
     
    
      $("#tgl1").datepicker( {
          format: "yyyy-mm-dd",
      });
    
      $("#input_rencana_studi").submit(function(){
        //alert("gdgd");
        $("#loadnya").show();
        $.ajax({
                type: "POST",
                url: $("#input_rencana_studi").attr("action"),
                data:$("#input_rencana_studi").serialize(),
                success: function(data) {
                  $("#loadnya").hide();
                   $(".modal-body").html(data);
                   $('#myModal').modal('toggle');
                    $('#myModal').modal('show');
                }
            });
      return false;
    });
});
function ambil_krs(stat,sem_id,id_matkul,sks,krs_id){
 // alert("kkjdkfd");
  //$("#loadnya").fadeIn(100);
  var jatah = parseInt($("#jatah_jml").val());
  if (($("#diambil_jml").val()=='')) {
    var diambil = 0;
  }else{
     var diambil = parseInt($("#diambil_jml").val());
  } 
  var sisa = jatah - diambil;
  if(stat.checked == true){
  
      diambil = diambil + sks;
      $("#diambil_jml").val(diambil);
      sisa = jatah - diambil;
      $("#sisa_jml").val(sisa);
      if (diambil>jatah) {
         $("#info").fadeOut(100);
         $("#warning").fadeIn(100);
          $("#btn-simpan").val("SKS yang diambil Melebihi jatah");
         $("#btn-simpan").attr("disabled", true);
        }
        else{
     /*       $.ajax({
                type: "POST",
                url: "<?= base_admin() ?>modul/rencana_studi/rencana_studi_action.php?act=add_krs",
                data: "id_matkul="+id_matkul+"&sem_id="+sem_id+"&sks="+sks+"&krs_id="+krs_id,
                success: function(data) {
                    console.log(data);
                    $("#loadnya").hide();
                    if (data == "good") {
                        $(".notif_top").fadeIn(1000);
                        $(".notif_top").fadeOut(1000, function() {
                                window.history.back();
                            });
                    } else if (data == "die") {
                        $("#informasi").modal("show");
                    } else {
                        $(".errorna").fadeIn();
                    }
                }
            });*/
                
           // $("#krs-"+id_matkul).attr("name","krs-"+id_matkul);
           // $("#kelas-"+id_matkul).attr("name","kelas-"+id_matkul);
           /* $("#kelas-"+id_matkul).removeAttr("required");
            $("#kelas-"+id_matkul).attr('required', 'required');*/
            $("#warning").fadeOut(100);
            $("#info").fadeIn(100);
            $("#btn-simpan").val("Ajukan KRS");
            $("#btn-simpan").attr("disabled", false);
        }     
    }else{
       diambil = diambil - sks;
        $("#diambil_jml").val(diambil);
       sisa = jatah - diambil;
       $("#sisa_jml").val(sisa);
          if (diambil>jatah) {
         $("#info").fadeOut(100);
         $("#warning").fadeIn(100);
         $("#btn-simpan").val("SKS yang diambil Melebihi jatah");
          $("#btn-simpan").attr("disabled", true);
        }
        else{
             $("#loadnya").show();
             $.ajax({
                type: "POST",
                url: "<?= base_admin() ?>modul/rencana_studi/rencana_studi_action.php?act=batal_krs",
                data: "id_matkul="+id_matkul+"&sem_id="+sem_id+"&sks="+sks+"&krs_id="+krs_id,
                success: function(data) {
                    $("#loadnya").hide();
                    $(".modal-body").html(data);
                   $('#myModal').modal('toggle');
                    $('#myModal').modal('show');
                }
            });
           // $("#krs-"+id_matkul).removeAttr("name");
          /*  $("#kelas-"+id_matkul).removeAttr("name");
            $("#kelas-"+id_matkul).removeAttr("required");*/
            $("#warning").fadeOut(100);
            $("#info").fadeIn(100);
            $("#btn-simpan").val("Ajukan KRS");
            $("#btn-simpan").attr("disabled", false);
        
        }
   }
    $("#diambil_info").html(sisa);

   
}

$("#pesan").on('textarea',function(){

      $.ajax({
      type : "post",
      url : "<?=base_admin();?>modul/rencana_studi/rencana_studi_action.php?act=batal_krs_pesan&nim=<?=$m->nim;?>",
      data : {pesan:this.value},
      error: function (xhr, error, thrown) {
      console.log(xhr);
      }
  });

});
/*$(document).ready(function() {
  $('tr')
    .filter(':has(:checkbox:checked)')
    .addClass('selected')
    .end()
  .click(function(event) {
    if (event.target.type !== 'checkbox') {
      $(':checkbox', this).trigger('click');
    }
  })
    .find(':checkbox')
    .click(function(event) {
      $(this).parents('tr:first').toggleClass('selected');
    });    
});*/
</script>
<?php
function cek_sudah_ambil2($nim,$sem,$id_mk,$id_kelas){
   global $db;
   $q = $db->query("select kr.kode_mk,k.sem_id,kr.nilai_huruf,kr.bobot  
    from krs_detail kr join krs k on kr.id_krs=k.krs_id 
                    where k.mhs_id='$nim' and k.sem_id='$sem' 
                    and kr.kode_mk='$id_mk' and kr.id_kelas='$id_kelas' and kr.batal='0' ");
   if ($q->rowCount()==0) {
     $hasil = array('result' => 0 , );
     
   }
   else{

    foreach ($q as $k) {
       $hasil['nilai'] = $k->nilai_huruf;
       $hasil['result'] = '1';
       $hasil['kelas'] = $k->id_kelas;
     }
   
   }
   return $hasil;
}

function cek_sudah_ambil($nim,$sem,$id_mk){
   global $db;
   $q = $db->query("select kr.id_kelas, kr.kode_mk,k.sem_id,kr.nilai_huruf,kr.bobot  from krs_detail kr join krs k on kr.id_krs=k.krs_id 
                    where k.mhs_id='$nim' and k.sem_id='$sem' and kr.kode_mk='$id_mk' and batal='0'");
   if ($q->rowCount()==0) {
     $hasil = array('result' => 0 , );
     
   }
   else{

    foreach ($q as $k) {
       $hasil['nilai'] = $k->nilai_huruf;
       $hasil['result'] = '1';
       $hasil['kelas'] = $k->id_kelas;
     }
   
   }
   return $hasil;
}





?>
