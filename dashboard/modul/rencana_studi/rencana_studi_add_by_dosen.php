    <section class="content-header">
        <h1>Rencana Studi</h1>
        <ol class="breadcrumb">
            <li>
              <a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a>
            </li>
            <li>
              <a href="<?=base_index();?>rencana-studi">Rencana Studi</a>
            </li>
            <li class="active">Pengajuan Rencana Studi</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
    <div class="row">
      <div class="col-lg-12">

          <div class="box box-solid box-primary">
          <div class="box-header">
            <h3 class="box-title">Pengajuan Rencana Studi Periode <?= get_tahun_akademik($sem) ?></h3>
            <div class="box-tools pull-right">
              <button class="btn btn-info btn-sm"><i class="fa fa-plus"></i></button>
            </div>
          </div>
          <div class="box-body">
  <div class="box-header">
           <?php 
             $data_mhs = $db->fetch_single_row("view_simple_mhs_data","nim",$nim);
             //check semester mahasiswa
             $sem2 = substr($sem, 0,4);
            // echo "$sem2";
             $semester_mhs = $db->fetch_custom_single("select (($sem2-left(mulai_smt,4))*2)+right($sem2,1)-(floor(right(mulai_smt,1)/2)) as semester from mahasiswa where nim=?",array('mhs_nim' => $data_mhs->nim));
              //print_r($semester_mhs);
           //echo "select (($sem-left(mulai_smt,4))*2)+right($sem,1)-(floor(right(mulai_smt,1)/2)) as semester from mahasiswa where nim=?";
            // print_r($semester_mhs);
             if ($semester_mhs->semester>16) {
               $qa = $db->query("select jatah_sks from akm where sem_id='$sem' and mhs_nim='$nim' ");
               if ($qa->rowCount()==0) {
                  $qcm = $db->query("select fungsi_get_jatah_sks('$data_mhs->nim',$sem) as jatah");
                  foreach ($qcm as $kcm) {
                    $jatah_sks = $kcm->jatah;
                  } 
               }else{
                   foreach ($qa as $ka) {
                     $jatah_sks = $ka->jatah_sks;
                   }
               }
              
               $dapat_paket = "";
               $ip_semester_lalu = "";
              //  echo "$jatah_sks";
             }else{ 
                $check_paket_semester = $db->fetch_single_row("data_paket_semester","id","1");
               // print_r($check_paket_semester);
                $data_jatah_sks = $db->fetch_custom_single("select fungsi_get_jatah_sks('".$data_mhs->nim."',".$sem.") as jatah_sks,fungsi_jml_sks_diambil('".$data_mhs->nim."',".$sem.") as diambil ");
                $jatah_sks = $data_jatah_sks->jatah_sks;
                $dapat_paket = "";
               if ($check_paket_semester) {
                 //echo "$jatah_sks"; 
                if ($semester_mhs) {
                  //semester paket
                  $xpl_semester = explode(",", $check_paket_semester->semester_mhs);
                  if (in_array($semester_mhs->semester,$xpl_semester)) {
                    $jatah_sks = $check_paket_semester->jml_sks;
                    $dapat_paket = "(Paket Semester)";
                  }
                } 
               }

                $ip_semester_lalu = "";
              
               $ip_sebelumnya = $db->fetch_custom_single("select fungsi_get_ip_semester_sebelumnya('".$data_mhs->nim."',".$sem.") as ip_sebelumnya");
               if ($ip_sebelumnya) {
                 $ip_semester_lalu = $ip_sebelumnya->ip_sebelumnya;
               }


             }


             $sks_diambil = sksDiambilMhs($data_mhs->nim,$sem);

           ?>
            <table class="table table-bordered table-striped">
      <tbody>
        <tr>
          <td width="10%">Nama</td>
          <td colspan="5"> : <?=$data_mhs->nama;?></td>
        </tr>
        <tr>
          <td width="10%">NIM</td>
          <td colspan="5"> : <?=$data_mhs->nim;?></td>
        </tr>
        <tr>
          <td width="10%">Program Studi</td>
          <td colspan="5"> : <?=$data_mhs->jurusan;?></td>
        </tr>
        <tr>
          <td width="10%">Periode Semester</td>
          <td colspan="5"> : <?= get_tahun_akademik($sem) ?> </td>
        </tr>
        <tr>
          <td width="10%">IP Semester Sebelumnya</td>
          <td colspan="5"> : <?= $ip_semester_lalu ?> </td>
        </tr>
        <tr>
          <td width="10%">Jatah SKS</td>
          <td colspan="5"> : <?= $jatah_sks." ".$dapat_paket?> </td>
        </tr>
            </tbody></table>
        </div><!-- /.box-header -->

     <?php
       //check if current date is periode krs or periode perbaikan krs
                $is_periode = check_current_periode('krs',$sem,$data_mhs->jur_kode);
                $check_if_bayar = $db->fetch_custom_single("select fungsi_cek_pembayaran_periode(".$sem.",".$data_mhs->jur_kode.",".$data_mhs->nim.") as is_bayar,fungsi_get_krs_disetujui_mhs($data_mhs->nim,".$sem.") as status_disetujui");
                if ($is_periode==false) {
                 ?>
               <div class="alert alert-danger" style="text-align:center" role="alert" id="warning">
                  <strong><i class="fa fa-warning"></i></strong> Bukan Periode KRS
                </div>
                 <?php
                } elseif ($check_if_bayar->is_bayar=='0') {
                  ?>
                <div class="alert alert-danger" style="text-align:center" role="alert" id="warning">
                  <strong><i class="fa fa-warning"></i></strong> Mahasiswa Ini Belum Melakukan Pembayaran Di Semester Ini
                </div>
                <?php
                } elseif ($check_if_bayar->status_disetujui>0) {
?>
                <div class="alert alert-danger" style="text-align:center" role="alert" id="warning">
                  <strong><i class="fa fa-warning"></i></strong> IRS Sudah disetujui Dosen Wali
                </div>
                <?php

                } 


                  ?>


<div class="box-body table-responsive">
<!--   <button id="cetak_krs" data-sem="<?=$enc->enc($sem);?>" data-nim="<?=$enc->enc($data_mhs->nim);?>" class="btn btn-primary" style="float:right"><i class="fa fa-print"></i>&nbsp;Cetak KRS</button> -->
                  <form method="POST" id="input_rencana_studi" action="<?= base_admin() ?>modul/rencana_studi/rencana_studi_action.php?act=add_krs_mhs">     
  <input type="hidden" id="jatah_jml" value="<?= $jatah_sks; ?>">
  <input type="hidden" id="diambil_jml" value="<?= $sks_diambil; ?>">
  <input type="hidden" id="sisa_jml" value="<?= $jatah_sks - $sks_diambil; ?>">
  <input type="hidden" name="nim" value="<?=$data_mhs->nim;?>">
  <input type="hidden" name="id_semester" value="<?=$sem;?>">
    
                                      <a href="<?=base_index();?>rencana-studi/detail/?n=<?=$enc->enc($nim);?>&s=<?=$enc->enc($sem);?>" class="btn btn-default" id="btn-simpan"><i class="fa fa-backward"></i> Kembali</a> 
                                        <button type="submit" class="btn btn-primary btn_simpan_krs"><i class="fa fa-save"></i> Simpan </button>
                                        <h4 >Ceklist Untuk Mengambil Matakuliah Lalu Simpan</h4>
                                      <table  class="table table-bordered table-striped" style="position:relative;top:8px;overflow: auto;">
                      
                                        <thead>
                                          <tr>
                                            <th style="width:20px" align="center"></th>
                                            <th style="text-align:center">Mata Kuliah</th>
                                            <th style="text-align:center">Kelas</th>
                                            <th style="text-align:center">Kuota/Jadwal</th>
                                            <th style="text-align:center">SKS</th>
                                            <th style="text-align:center">Sifat</th>    
                                            <th style="text-align:center">Keterangan</th>  
                                          </tr>
                                        </thead>
                                        <tbody>
                                          
                                <?php
                                $dtb=$db->query("select vnk.kur_id,vnk.sem_matkul as semester from view_nama_kelas vnk
                                  where vnk.kode_jur='$data_mhs->jur_kode' 
                          and sem_id='".$sem."'
                          group by sem_matkul order by sem_matkul asc");
                                $i=1;
                                foreach ($dtb as $isi) {
                                  ?>
                                <tr">
                                    <td colspan="8" style="background:#00a65a;color:#fff">SEMESTER <?=$isi->semester;?></td>
                                </tr>
                                  <?php
                                    $dtb2=$db->query("select m.*,k.kls_nama from kelas k join matkul m on k.id_matkul=m.id_matkul
                                                    join kurikulum ku on ku.kur_id=m.kur_id
                                                     where k.sem_id='".$sem."' and ku.kode_jur='".$data_mhs->jur_kode."' 
                                                     and m.semester='".$isi->semester."'  group by k.id_matkul order by m.semester asc, m.nama_mk asc ");
                         
                                    foreach ($dtb2 as $k) {

                                      $cek_prasyarat = cek_prasyarat_mhs($k->id_matkul,$data_mhs->nim);
                                     ?>
                                     
                                          <?php
                                          //echo "$ku->mhs_nim, $ku->sem, $k->id_matkul";
                                          if (cek_sudah_ambil($data_mhs->nim,$sem,$k->id_matkul)['result']==1
                                            && cek_sudah_ambil($data_mhs->nim,$sem,$k->id_matkul)['nilai']!='' ) {
                                           ?>
<!--                                            <tr style="background:#ff8f00"">
  <td align="center" >  <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline "> <input type="checkbox" class="group-checkable bulk-check"  name="krs-<?= $k->id_matkul ?>" id="krs-<?= $k->id_matkul ?>" onchange="ambil_krs(this,<?= $sem.",".$k->id_matkul.",".$k->sks_tm.",".$data_mhs->nim ?>)" value="<?= $k->id_matkul ?>" checked> <span></span></label></td>  -->
                                           <?php

                                           $ket= "Sudah diambil dan sudah ada nilai untuk matkul ini";
                                           $disabled_select = "current_taken";
                                           $show_kelas_full=true;
                                          } elseif (cek_sudah_history($data_mhs->nim,$k->id_matkul)['result']==1
                                            && cek_sudah_history($data_mhs->nim,$k->id_matkul)['nilai']!='' ) {
                                              ?>
                                           <tr style="background:#ff8f00"">
  <td align="center" >  <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline "> <input type="checkbox" class="group-checkable bulk-check"  name="krs-<?= $k->id_matkul ?>" id="krs-<?= $k->id_matkul ?>" onchange="ambil_krs(this,<?= $sem.",".$k->id_matkul.",".($k->sks_tm + $k->sks_prak+$k->sks_prak_lap+$k->sks_sim).",'".$data_mhs->nim ?>')" value="<?= $k->id_matkul ?>"> <span></span></label></td> 
                                           <?php
                                           $ket= "Sudah diambil dan sudah ada nilai untuk matkul ini";
                                           $disabled_select = "";
                                           $show_kelas_full=true;
                                          }
                                          elseif (cek_sudah_ambil($data_mhs->nim,$sem,$k->id_matkul)['result']==1 && cek_sudah_ambil($data_mhs->nim,$sem,$k->id_matkul)['nilai']=='') {
                                             ?>
<!--                                            <tr>
  <td align="center">  <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline "> <input type="checkbox" class="group-checkable bulk-check"  name="krs-<?= $k->id_matkul ?>" id="krs-<?= $k->id_matkul ?>" onchange="ambil_krs(this,<?= $sem.",".$k->id_matkul.",".$k->sks_tm.",".$data_mhs->nim ?>)" value="<?= $k->id_matkul ?>" checked> <span></span></label></td>  -->

                                           <?php
                                            $ket= "Sudah diambil tapi belum ada nilai";
                                            $disabled_select = "sudah";
                                            $show_kelas_full=true;
                                          }
                                          elseif ($cek_prasyarat!="0") {
                                              ?>
                                           <tr style="background:#ff0d0d">
  <td align="center" ></td> 
                                             <?php
                                             $ket= $cek_prasyarat;
                                             $disabled_select = "";
                                             $show_kelas_full=true;
                                          } elseif (cek_penuh_permatkul($k->id_matkul,$sem)) {
                                            $ket = "";
                                            $disabled_select = "disabled";
                                             $show_kelas_full=true;
                                          }

                                          else {
                                            ?>
                                           <tr>
                                    <td align="center"><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline "> <input type="checkbox" class="group-checkable bulk-check" name="krs-<?= $k->id_matkul ?>" id="krs-<?= $k->id_matkul ?>" onchange="ambil_krs(this,<?= $sem.",".$k->id_matkul.",".($k->sks_tm + $k->sks_prak+$k->sks_prak_lap+$k->sks_sim).",'".$data_mhs->nim ?>')" value="<?= $k->id_matkul ?>"> <span></span></label></td>
                                            <?php
                                            $ket = "Tersedia";
                                            $disabled_select = "";
                                            $show_kelas_full=false;
                                          }

                                       if ($disabled_select=='') {
                                         
                                          ?>
                                              <td><?=$k->kode_mk." ".$k->nama_mk;?></td>
                                              <input type="hidden" name="matkul-<?= $k->id_matkul ?>" value="<?= $k->nama_mk ?>">
                                               <td>
                                              <?php
                                                  if ($cek_prasyarat=="0") {
                                                    ?>
                                                    <select class="kelas_name" style="width:100px" <?= $disabled_select ?> data-name="kelas_<?= $k->id_matkul ?>" name="kelas-<?= $k->id_matkul ?>" id="kelas-<?= $k->id_matkul ?>" disabled>
                                                      <option value="">-Pilih Kelas-</option>
                                                        <?php
                                                              $qq = $db->query("select k.kelas_id,k.kls_nama from kelas k where 
                                                              k.id_matkul='$k->id_matkul' and k.sem_id='".$sem."'");
                                                             foreach ($qq as $kk) {
                                                              if ($show_kelas_full==false) {
                                                                if (cek_kelas_penuh($kk->kelas_id)==false || cek_sudah_ambil2($data_mhs->nim,$sem,$k->id_matkul,$kk->kelas_id)['result']==$kk->kelas_id ) {
                                                                  if (cek_sudah_ambil2($data_mhs->nim,$sem,$k->id_matkul,$kk->kelas_id)['result']==1 ) {
                                                                    echo "<option value='$kk->kelas_id===$kk->kls_nama' >$kk->kls_nama</option>";
                                                                  }else{
                                                                     echo "<option value='$kk->kelas_id===$kk->kls_nama'>$kk->kls_nama</option>";
                                                                  }
                                                                }
                                                              }else{
                                                                 if (cek_sudah_ambil2($data_mhs->nim,$sem,$k->id_matkul,$kk->kelas_id)['result']==1 ) {
                                                                    echo "<option value='$kk->kelas_id===$kk->kls_nama' >$kk->kls_nama</option>";
                                                                  }else{
                                                                     echo "<option value='$kk->kelas_id===$kk->kls_nama'>$kk->kls_nama</option>";
                                                                  }
                                                              }
                                                         } 
                                                         
                                                        ?>
                                                      </select>
                                                <?php 
                                                  } 
                                                ?>
                                              </td>
                                              <td><span class="all_kuota"></span><input type="hidden" class="kelas_<?= $k->id_matkul ?> reset-doang" name="kelas_<?= $k->id_matkul ?>" value="kelas_<?= $k->id_matkul ?>"></td>
                                              <td><?= ($k->sks_tm+$k->sks_prak+$k->sks_sim+$k->sks_prak_lap); ?></td>
                                              <td><?php
                                              if ($k->a_wajib==1) {
                                                echo "<span class='btn btn-danger btn-xs'>Wajib</span>";
                                              } else {
                                                echo "<span class='btn btn-info btn-xs'>Pilihan</span>";
                                              };?></td>
                                              <td><?= $ket; ?></td>
                                          </tr>
                                     <?php
                                     //kelas false 
                                      }
                                    }
                                 $i++;
                                }
                                ?>
                                   <tr>
                                     <td colspan="2"> <a href="<?=base_index();?>rencana-studi/detail/?n=<?=$enc->enc($nim);?>&s=<?=$enc->enc($sem);?>" class="btn btn-default" id="btn-simpan"><i class="fa fa-backward"></i> Kembali</a>   <button type="submit" class="btn btn-primary btn_simpan_krs"><i class="fa fa-save"></i> Simpan </button></td>
                                   </tr>
                              
                                        </tbody>
                                      
                                         <input type="hidden" name="kode_jur" value="<?= $data_mhs->jur_kode; ?>"> 
                                         <input type="hidden" name="semester" value="<?= $sem; ?>"> 
                                       
                                         </form>
                                      </table>
                                      </div><!-- /.box-body -->
                                        <div id="info" class="alert alert-warning alert-info" style="position:fixed;bottom:5px;right:5px;font-size:20px" role="alert">
                                          Jatah SKS : <?= $jatah_sks; ?> <br>
                                          Sisa Jatah SKS   : <span id="diambil_info" style="font-size:20px">  <?= $jatah_sks - $sks_diambil ?> </span>
                                        </div>
                                      <div class="alert alert-danger" style="position:fixed;bottom:5px;right:5px;display:none" role="alert" id="warning_krs">
                                        <strong>Warning</strong> Jumlah SKS yang diambil melebihi jatah, silahkan uncheck beberapa mata kuliah
                                      </div>

      </div>
    </div>
      </div>

    </section><!-- /.content -->
  <div class="modal modal-success fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content" >
        <div class="modal-header">
          <button type="button" class="close" ><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel" style="text-align:center">Informasi Pengambilan KRS<span id="semester"></span></h4>
        </div>
        <div class="modal-body">
          ...
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" onClick="window.history.back(-1)">Close</button>
        
        </div>
      </div>
    </div>  
  </div>

<script type="text/javascript">


    $(document).ready(function() {
     
    $("#cetak_krs").click(function(){
       var currentBtn = $(this);
       sem = currentBtn.data('sem');
       nim = currentBtn.data('nim');
       window.open("<?= base_admin() ?>modul/rencana_studi/cetak_krs_mhs.php?k="+sem+"&nim="+nim,'_blank');
      });

      $("#tgl1").datepicker( {
          format: "yyyy-mm-dd",
      });

  function check_selected() {
      var array_data_delete = [];
       $('select option:selected').each(function(){
              array_data_delete.push($(this).val())
          });
      return array_data_delete
  }

 
      $('.bulk-check').click(function(){
          if (!$(this).is(':checked')) {
            $(this).closest('td').siblings().find('.kelas_name').prop("disabled", true);
            $(this).closest('td').siblings().find('.kelas_name').val('');
            $(this).closest('td').siblings().find('.all_kuota').html('');
            $(this).closest('td').siblings().find('.kelas_name').valid(false);
          } else {
            $(this).closest('td').siblings().find('.kelas_name').prop("disabled", false);
            $(this).closest('td').siblings().find('.kelas_name').valid();

          }
      });


     $(".kelas_name").on('change',function(){

        var butt = $(this).closest('td').siblings().find('.all_kuota');
        arr = check_selected();
        current_element = $(this).data('name');





         temp = [];
         for(let i of arr)
              i && temp.push(i); // copy each non-empty value to the 'temp' array

          arr = temp;
          delete temp; // discard the variable
         // console.log(arr);

              $.ajax({
              type : "post",
              url : "<?=base_admin();?>modul/rencana_studi/cek_kuota_bentrok.php",
              data : {check_id : $(this).val(),id_kelas:arr},
/*              success : function(data) {
                 //$("."+var_name).text(data);
                 //butt.html(data);
                 //console.log(butt);

              }*/
                dataType: 'json',
                error: function(data ) { 
                  $("#loadnya").hide();
                  console.log(data); 
                },
                success: function(responseText) {
                  console.log(responseText);
                      $.each(responseText, function(index) {
                          //console.log(responseText[index]);
                          if (responseText[index].bentrok!='') {
                              butt.html(responseText[index].kuota);
                              $("."+current_element).removeAttr('value');
                              $(".reset-doang").valid();
                              $.validator.addMethod("bentrok", function(val,element) {
                                 //console.log($(element).closest('td').siblings().find('input[type=checkbox]'));
                                if (val=='') {
                                   return false;
                                  } else {
                                    return true;
                                  }
                              }, responseText[index].bentrok);
                                $.validator.addClassRules(current_element, {
                                    bentrok:true,
                              });
                          }  else {
                              $(".reset-doang").val('value');
                              $(".reset-doang").valid();
                              butt.html(responseText[index].kuota);
                          }
                    });
                }

          });
      });

    
      $.validator.addMethod("myFunc", function(val,element) {
         //console.log($(element).closest('td').siblings().find('input[type=checkbox]'));
        if ($(element).closest('td').siblings().find('input[type=checkbox]').is(':checked')&&val=='') {
           return false;
          } else {
            return true;
          }
      }, "Silakan Pilih Kelas");

      $.validator.addClassRules("kelas_name", {
             myFunc:true,
      });

      var validat = $("#input_rencana_studi").validate({
         ignore: [],
        errorClass: "help-block red-required",
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
            if (element.hasClass("kelas_name")) {
                error.insertAfter(element);
            } else {
               error.insertAfter(element);
            }
        },
        submitHandler: function(form) {
            $("#loadnya").show();
            $(form).ajaxSubmit({
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
        }
    });


/*
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
    });*/
});
function ambil_krs(stat,sem_id,id_matkul,sks,nim){
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
        // alert("xxx");
         $("#info").fadeOut(100);
         $("#warning_krs").fadeIn(100);
         $(".btn_simpan_krs").removeClass("btn-primary");
         $(".btn_simpan_krs").addClass("btn-danger");
          $(".btn_simpan_krs").html("<i class='fa fa-save'></i> SKS yang diambil melebihi batas ");
         $(".btn_simpan_krs").attr("disabled", true);
        }
        else{
            $("#warning_krs").fadeOut(100);
            $("#info").fadeIn(100);
            $(".btn_simpan_krs").removeClass("btn-danger");
            $(".btn_simpan_krs").addClass("btn-primary");
            $(".btn_simpan_krs").html("<i class='fa fa-save'></i> Simpan ");
            $(".btn_simpan_krs").attr("disabled", false);
        }     
    } else{
       //alert("yyy");
       diambil = diambil - sks;
        $("#diambil_jml").val(diambil);
       sisa = jatah - diambil;
       $("#sisa_jml").val(sisa);
          if (diambil>jatah) {
         $("#info").fadeOut(100);
         $("#warning_krs").fadeIn(100);
         $(".btn_simpan_krs").removeClass("btn-primary");
         $(".btn_simpan_krs").addClass("btn-danger");
         $(".btn_simpan_krs").html("<i class='fa fa-save'></i> SKS yang diambil melebihi batas ");
          $(".btn_simpan_krs").attr("disabled", true);
        }
        else{
             //$("#loadnya").show();
/*             $.ajax({
                type: "POST",
                url: "<?= base_admin() ?>modul/rencana_studi/rencana_studi_action.php?act=batal_krs_mhs",
                data: "id_matkul="+id_matkul+"&sem_id="+sem_id+"&sks="+sks+"&nim="+nim,
                success: function(data) {
                    $("#loadnya").hide();
                    $(".modal-body").html(data);
                   $('#myModal').modal('toggle');
                    $('#myModal').modal('show');
                }
            });*/
          
            $("#warning_krs").fadeOut(100);
            $("#info").fadeIn(100);
            $(".btn_simpan_krs").removeClass("btn-danger");
            $(".btn_simpan_krs").addClass("btn-primary");
            $(".btn_simpan_krs").html("<i class='fa fa-save'></i> Simpan ");
            $(".btn_simpan_krs").attr("disabled", false);
        
        }
   }
  
    $("#diambil_info").html(sisa);

   
}
$("#pesan").on('textarea',function(){

      $.ajax({
      type : "post",
      url : "<?=base_admin();?>modul/rencana_studi/rencana_studi_action.php?act=batal_krs_pesan&nim=<?=$data_mhs->nim;?>",
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

   $q = $db->query("select id_kelas,kr.kode_mk,id_semester,kr.nilai_huruf,kr.bobot  
    from krs_detail kr where nim='$nim' and id_semester='$sem' 
                    and kode_mk='$id_mk' and id_kelas='$id_kelas' ");
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


function cek_sudah_history($nim,$id_mk){
   global $db;
   $q = $db->query("select kr.id_kelas, kr.kode_mk,id_semester,kr.nilai_huruf,kr.bobot  from krs_detail kr
                    where nim='$nim' and kode_mk='$id_mk'");
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
   $q = $db->query("select kr.id_kelas, kr.kode_mk,id_semester,kr.nilai_huruf,kr.bobot  from krs_detail kr
                    where nim='$nim' and id_semester='$sem' and kode_mk='$id_mk'");
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
