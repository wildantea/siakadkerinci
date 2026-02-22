<section class="content-header">
  <h1>Kukerta</h1>
  <ol class="breadcrumb">
    <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="<?=base_index();?>nilai">Kukerta</a></li>
    <li class="active">Input Nilai Kukerta</li>
  </ol>
</section>
<style type="text/css">
  .error {
    color: #f00;
  }
</style>
<!-- Main content -->
<section class="content"> 
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
         <div class="box-body table-responsive">
           <?php
           if ($data_edit) {
           ?>
            <form method="POST" action="<?= base_url() ?>dashboard/modul/nilai/nilai_action.php?act=input_nilai_coba" id="form_input_nilai" >

                    <table class="table table-bordered table-striped">

                      <tbody>
                        <tr>
                            <td style="width: 200px">Lokasi Kukerta </td>
                            <td>  <?=$data_edit->nama_lokasi;?></td>
                        </tr>
                         <tr>
                            <td style="width: 200px">DPL 1 </td>
                            <td>  <?=$data_edit->nm_dpl1;?></td>
                        </tr> 
                         <tr>
                            <td style="width: 200px">DPL 2 </td>
                            <td>  <?=$data_edit->nm_dpl2;?></td>
                        </tr>
                         <tr>
                            <td style="width: 200px">Tanggal Input Nilai </td>
                            <td>  <?=$data_edit->tgl_awal_input_nilai." s/d ".$data_edit->tgl_akhir_input_nilai;?></td>
                        </tr>
                      
                    </tbody></table>
                    <span class="lead"><button type="submit" class="btn btn-success simpan-nilai" disabled=""><i class="fa fa-check"></i> Simpan</button> <a href="<?=base_index();?>kukerta" class="btn btn-warning"><i class="fa fa-reply"></i> Batal</a>  Silakan Checklist Nilai Yang akan di Ubah</span>
                    <p>

               <!--  <input type="hidden" name="jur" value="<?= en($jur) ?>"> -->
                <table  class="table table-bordered table-striped">
                 
                 <thead>
                   <tr class="bg-light-blue color-palette">
                     <th rowspan="2" style="padding-right:0;width: 7%;text-align: center;vertical-align: middle;background: #ffffff;color:#000"><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline "> <input type="checkbox" class="group-checkable bulk-check"> <span></span></label>No</th>
                     <th rowspan="2" style="text-align: center;vertical-align: middle">NIM</th>
                     <th rowspan="2" style="text-align: center;vertical-align: middle">Nama</th>
                      <th rowspan="2" style="text-align: center;vertical-align: middle">Kelamin</th>
                     <th rowspan="2" style="text-align: center;vertical-align: middle">Jurusan</th>
                     <th rowspan="2" style="text-align: center;vertical-align: middle">Angkatan</th>
                     <th colspan="2" style="text-align: center;vertical-align: middle">Nilai</th>
                   </tr>
                   <tr class="bg-light-blue color-palette">
                     <th rowspan="2" style="text-align: center;vertical-align: middle">Nilai Angka</th>
                     <th rowspan="2" style="text-align: center;vertical-align: middle">Nilai Huruf</th>
                   </tr>
                 </thead> 
                 <tbody> 
                 <?php
                      $no=1;
                     // echo "select k.*,m.nama,m.angkatan,m.jur_kode from kkn k join mahasiswa m on m.nim=k.nimwhere id_lokasi='$data_edit->id_lokasi' and id_priode='$data_edit->id_priode' ";
                      $nilai_data = $db->query("select  k.*,dk.nilai_huruf,dk.bobot,dk.nilai_angka,m.nama,m.mulai_smt,m.jk,m.mulai_smt,m.mulai_smt as angkatan,m.jur_kode,j.nama_jur,dk.id_krs_detail,dk.nilai_angka,dk.nilai_huruf from kkn k join mahasiswa m on m.nim=k.nim
                      join jurusan j on j.kode_jur=m.jur_kode
                      left join krs_detail dk on (dk.nim=k.nim and dk.id_kelas='1' ) where  id_lokasi='$data_edit->id_lokasi' and id_priode='$data_edit->id_priode' order by m.nama asc ");
                // echo "select  k.*,m.nama,m.mulai_smt,m.jk,m.mulai_smt,m.mulai_smt as angkatan,m.jur_kode,j.nama_jur,dk.id_krs_detail,dk.nilai_angka,dk.nilai_huruf from kkn k join mahasiswa m on m.nim=k.nim
                //       join jurusan j on j.kode_jur=m.jur_kode
                //       left join krs_detail dk on (dk.nim=k.nim and dk.id_kelas='1' ) where  id_lokasi='$data_edit->id_lokasi' and id_priode='$data_edit->id_priode' order by m.nama asc";
                      foreach ($nilai_data as $nilai) {  
                     
                         $array_skala = array();
                       // echo "select * from skala_nilai where kode_jurusan='$nilai->jur_kode' ";

                      $skala_nilai = $db->query("select * from skala_nilai where kode_jurusan=? and berlaku_angkatan=?",array('kode_jurusan' => $nilai->jur_kode,"berlaku_angkatan" => $nilai->mulai_smt));
                      if ($skala_nilai->rowCount()>0) {
                         foreach ($skala_nilai as $skala) {
                          $array_skala[$skala->nilai_huruf."#".$skala->nilai_indeks] = $skala->nilai_huruf." (".$skala->nilai_indeks.")";
                        }
                         $bobot = $nilai->nilai_huruf."#".$nilai->bobot; 
                      }else{
                          $skala_nilai = $db->query("select * from skala_nilai where kode_jurusan=? and berlaku_angkatan is null",array('kode_jurusan' => $nilai->jur_kode));
                           foreach ($skala_nilai as $skala) {
                          $array_skala[$skala->nilai_huruf."#".$skala->nilai_indeks] = $skala->nilai_huruf." (".$skala->nilai_indeks.")";
                        }
                         $bobot = $nilai->nilai_huruf."#".$nilai->bobot; 
                      }
                     
                        ?> 
                        <tr class="row-data" style="background: #eee">
                          <td style="background: #fff;text-align: center;vertical-align: middle"><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"> <input type="checkbox" class="group-checkable check-selected"> <span></span></label><?=$no;?></td>
                          <td style="vertical-align: middle"><?=$nilai->nim;?></td>
                          <td style="vertical-align: middle"><?=$nilai->nama;?></td>
                          <td style="vertical-align: middle"><?=$nilai->jk;?></td>
                          <td style="vertical-align: middle"><?=$nilai->nama_jur;?></td>
                          <td style="vertical-align: middle"><?=$nilai->angkatan;?></td>
                          <input type="hidden" name="nim[<?=$nilai->id_krs_detail;?>]" value="<?=$nilai->nim;?>">
                          <td><input type="text" name="nilai_angka[<?=$nilai->id_krs_detail;?>]" disabled="" value="<?=str_replace(".", ",", $nilai->nilai_angka);?>" onkeydown="return onlyNumber(event,this,true,false)" class="form-control nilai_angka" data-rule-decimalSeparator="100" 
        data-msg-decimalSeparator="Nilai tidak boleh lebih dari 100" 
        maxlength="5" size="5" data-angkatan="<?=$nilai->mulai_smt;?>" data-jurusan="<?=$nilai->kode_jur;?>"></td>
                          <td>
                            <select name="nilai_huruf[<?=$nilai->id_krs_detail;?>]" class="form-control nilai_huruf" disabled="">
                              <option value=""> </option>
                              <?php
                              foreach ($array_skala as $id_skala => $skala_bobot) {
                                if ($bobot==$id_skala) {
                                  echo "<option value='$id_skala' selected>$skala_bobot</option>";
                                } /*else {
                                  echo "<option value='$id_skala'>$skala_bobot</option>";
                                }  */                        
                              }
                              ?>
                              </select>
                          </td>
                        </tr>
                        <?php
                        $no++;
                      }
                 ?>
                 </tbody>

                </table>
                    <button type="submit" class="btn btn-success simpan-nilai" disabled=""><i class="fa fa-check"></i> Simpan</button>
                    <p>
               </form>
           <?php
           }else{
             ?>
             <div class="alert alert-warning">
               <h3>Invalid URL</h3>
             </div>
             <?php
           }
           ?>
         </div>

   </div>
  </div>
</div>
</section>
<script type="text/javascript">

function jml_checked() {
    var jumlah = $('.check-selected:checked').length;
    if (jumlah>0) {
      $(".simpan-nilai").attr("disabled",false);
    } else {
      $(".simpan-nilai").attr("disabled",true);
    }
}
$(document).ready(function() {

$('.bulk-check').click(function(){
          if (!$(this).is(':checked')) {
            $('.check-selected').prop('checked', false);
            $('.nilai_angka').prop("disabled", true);
            //$('.nilai_angka').val('');
            $('.nilai_angka').attr("required", false);
            $('.nilai_huruf').attr("disabled", true); 
            $('.nilai_huruf').val('').attr("required", false);
            $('.row-data').css("background-color", "#eee");
          } else {
            $('.nilai_angka').prop("disabled", false);
            $('.nilai_angka').attr("required", true);
            $('.nilai_huruf').attr("disabled", false); 
            $('.nilai_huruf') .attr("required", true);
            $('.check-selected').prop('checked', true);
            $('.row-data').css("background-color", "#ffffff");
          }
          jml_checked();

});

$('.check-selected').click(function(){
          if (!$(this).is(':checked')) {
            $(this).closest('td').siblings().find('.nilai_angka').prop("disabled", true);
            $(this).closest('td').siblings().find('.nilai_angka').attr("required", false);
            //$(this).closest('td').siblings().find('.nilai_angka').val('');
            $(this).closest('td').siblings().find('.nilai_huruf').attr("disabled", true); 
            //$(this).closest('td').siblings().find('.nilai_huruf').val('').attr("selected", true);
            $(this).closest('td').siblings().find('.nilai_huruf').attr("required", false);
            $(this).closest('tr').css("background-color", "#eee");
            jml_checked();
          } else {
            $(this).closest('td').siblings().find('.nilai_angka').prop("disabled", false);
            $(this).closest('td').siblings().find('.nilai_angka').attr("required", true);
            $(this).closest('td').siblings().find('.nilai_huruf').attr("disabled", false);
            $(this).closest('td').siblings().find('.nilai_huruf').attr("required", true);
            $(this).closest('tr').css("background-color", "#ffffff");
            jml_checked();
          }
      });

});

function onlyNumber(e, t, n, a) {
    var o = e.keyCode || e.which,
        i = t.value;
    return o > 57 && 96 > o || o > 105 || 32 == o ? 188 == o && n ? "" == i ? !1 : i.indexOf(",") > -1 ? !1 : !0 : a && (110 == o || 190 == o) || 116 == o ? !0 : !1 : void 0
}


/*$(".nilai_angka").blur(function(){
        var btn = $(this);
        jurusan = btn.attr('data-jurusan');
        angkatan = btn.attr('data-angkatan');
        nilai_huruf = $(this).closest('td').siblings().find('.nilai_huruf');
          $.ajax({
            type : "post",
            url : "<?=base_admin();?>modul/nilai/get_nilai_huruf.php",
            data : {nilai:this.value,kode_jurusan:jurusan,angkatan:angkatan},
            success : function(data) {
                nilai_huruf.html(data);
                nilai_huruf.valid();
              }
          });

});*/


$(".nilai_angka").on("keyup", function() {
    var btn = $(this);
    var jurusan = btn.attr('data-jurusan');
    var angkatan = btn.attr('data-angkatan');
    var nilai_huruf = $(this).closest('td').siblings().find('.nilai_huruf');
    var nilai = this.value;

    // Function to send AJAX request with retry logic
    function sendRequest(retryCount = 0, maxRetries = 3) {
        $.ajax({
            type: "post",
            url: "<?=base_admin();?>modul/nilai/get_nilai_huruf.php",
            data: { nilai: nilai, kode_jurusan: jurusan, angkatan: angkatan },
            success: function(data) {
                nilai_huruf.html(data);
                nilai_huruf.valid(); // Assuming this triggers validation
            },
            error: function(xhr, textStatus) {
                if (retryCount < maxRetries) {
                    console.log(`Retrying... (${retryCount + 1}/${maxRetries})`);
                    setTimeout(function() {
                        sendRequest(retryCount + 1, maxRetries);
                    }, 2000); // Retry after 2 seconds
                } else {
                    console.error("Failed to fetch data after multiple attempts.");
                }
            }
        });
    }

    // Call the function initially
    sendRequest();
});

// Retry automatically when the network reconnects
window.addEventListener("online", function() {
    console.log("Network reconnected. Retrying pending requests...");
    $(".nilai_angka").trigger("keyup"); // Simulate keyup to retry
});

$(document).ready(function() {
    
    
    $.validator.addMethod("decimalSeparator", function(value, element, param) {
    if (value.includes(",")) {
        value = value.replace(",", ".");
    }
    return this.optional(element) || parseFloat(value) <= param;
}, "Nilai tidak boleh lebih dari {0}.");

    $("#form_input_nilai").validate({      
        submitHandler: function(form) {
            $("#loadnya").show();
            $(form).ajaxSubmit({
                url : $(this).attr("action"),
                dataType: "json",
                type : "post",
                error: function(data ) { 
                  $("#loadnya").hide();
                  //console.log(data); 
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
                          } else {
                            $(".save-nilai").attr("disabled", "disabled");
                            $(".error_data").hide();
                            $(".notif_top_up").fadeIn(1000);
                            $(".notif_top_up").fadeOut(1000, function() {
                                   // location.reload();
                            });
                          }
                    });
                }

            });
        }
    });

});
</script>