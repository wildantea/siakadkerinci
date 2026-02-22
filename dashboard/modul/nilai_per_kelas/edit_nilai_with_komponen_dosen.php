<?php
session_start();
include "../../inc/config.php";
$kelas_data = $db2->fetchCustomSingle("SELECT tdk.kelas_id,tdk.kls_nama,ada_komponen,komponen,vmk.kode_mk,tdk.sem_id,vmk.nama_mk,vmk.total_sks,vmk.kode_jur,vmk.kode_jur, vmk.nama_jurusan from kelas tdk
INNER JOIN view_matakuliah_kurikulum vmk using(id_matkul) where kelas_id=?",array('kelas_id' => $_POST['kelas_id']));
  function get_nilai_absen($nim_user,$prosen_presensi) {
    global $db2;
    $kelas_id = $_POST['kelas_id'];
    $absen = $db2->query("select isi_absensi from tb_data_kelas_absensi inner join tb_data_kelas_pertemuan using(id_pertemuan)
    where kelas_id=$kelas_id");
    $jml_hadir = 0;
    $status_absen = array();
    if ($absen->rowCount()>0) {
      foreach ($absen as $ab ) {
        $data_absen = json_decode($ab->isi_absensi);
        foreach ($data_absen as $nim ) {
          if ($nim->status_absen=='Hadir') {
            $status_absen[$nim->nim][] = $nim->status_absen;
          }
        }
      }
    }
    if (isset($status_absen[$nim_user])) {
      $jml_hadir = count($status_absen[$nim_user]);
    } else {
      $jml_hadir = $jml_hadir;
    }

      $nilai_absen = 0;
      $jml_pertemuan = $db2->fetchCustomSingle("select count(id_pertemuan) as jml_pertemuan from tb_data_kelas_pertemuan where kelas_id='".$_POST['kelas_id']."'");
      if ($jml_pertemuan->jml_pertemuan>0) {
      $nilai_absen = (($jml_hadir/$jml_pertemuan->jml_pertemuan)*100);
      $nilai_absen = round($nilai_absen,2);
      }
    return $nilai_absen;
  }
?>
<style type="text/css">
  .error {
    color: #f00;
  }
</style>
 <div class="table-responsive">
<table id="tabelku" class="table table-bordered table-striped" cellspacing="0" width="100%">
        <tbody><tr>
          <td class="info2" width="20%"><strong>Program Studi</strong></td>
          <td width="30%"><?=$kelas_data->nama_jurusan;?></td>
          <td class="info2" width="20%"><strong>Periode</strong></td>
          <td colspan="2"><?=getPeriode($kelas_data->sem_id);?></td>
        </tr>
        <tr>
          <td class="info2"><strong>Matakuliah</strong></td>
          <td><?=$kelas_data->kode_mk;?> - <?=$kelas_data->nama_mk;?> (<?=$kelas_data->total_sks;?> sks) </td>
          <td class="info2"><strong>Kelas</strong></td>
          <td><?=$kelas_data->kls_nama ;?> </td>
        </tr>
      </tbody></table>
 <div class="alert alert-danger error_data_nilai_komponen" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning_nilai_komponen"></span>
        </div>
              <form method="POST" action="<?= base_url() ?>dashboard/modul/nilai_per_kelas/nilai_per_kelas_action.php?act=input_nilai_komponen" id="form_input_nilai_komponen" >

        <table  class="table table-bordered table-striped">
         
         <thead>
           <tr class="bg-light-blue color-palette">
             <th style="text-align: center;vertical-align: middle;width:3%">No</th>
             <th style="text-align: center;vertical-align: middle">NIM</th>
             <th style="text-align: center;vertical-align: middle">Nama</th>
             <th style="text-align: center;vertical-align: middle">Angkatan</th>
            <?php 
            if ($kelas_data->ada_komponen=='Y') {
            $komponen = json_decode($kelas_data->komponen);
               foreach ($komponen as $key) {
                if (is_array($key)) {
                    foreach ($key as $val) {
                        ?>
                          <th style="text-align: center;vertical-align: middle"><?=$val->nama_komponen;?> (<?=$val->value_komponen;?>%)</th>
                        <?php
                    }
                }
               }
             } 

             ?>
            <!--  <th style="padding-right:0"><span data-toggle="tooltip" data-title="Gunakan Komponen">Komp</span></th> -->
             <th style="text-align: center;vertical-align: middle">Nilai Akhir</th>
             <th style="text-align: center;vertical-align: middle;width: 9%;">Nilai Huruf</th>
           </tr>
         </thead>
         <tbody>
         <?php
         $nilai_id = $_REQUEST['nilai_id'];
         $key_nol = "";
         $label_nol = "";
              $no=1;
              $array_skala = array();
              $skala_nilai = $db2->query("select * from skala_nilai where kode_jurusan=?",array('kode_jurusan' => $kelas_data->kode_jur));

              foreach ($skala_nilai as $skala) {
                $array_skala[$skala->nilai_huruf."#".$skala->nilai_indeks] = $skala->nilai_huruf." (".$skala->nilai_indeks.")";
                if ($skala->nilai_indeks==0) {
                  $key_nol = $skala->nilai_huruf."#".$skala->nilai_indeks;
                  $label_nol = $skala->nilai_huruf." (".$skala->nilai_indeks.")";
                }
              }
              $nilai_data = $db2->query("

                select krs_detail.id_krs_detail as krs_detail_id,komponen_nilai,mahasiswa.mulai_smt,nilai_angka,nilai_huruf,bobot as nilai_indeks,krs_detail.nim,nama,left(mulai_smt,4) as angkatan,
use_rule from krs_detail inner join mahasiswa using(nim) where id_kelas=? and krs_detail.disetujui='1' and id_krs_detail in($nilai_id) order by krs_detail.nim asc",array('id_kelas' => $_POST['kelas_id']));
              foreach ($nilai_data as $nilai) {
                $id_krs_detail[] = $nilai->krs_detail_id;
                $read_componen_status = '';
                $read_grade_status = 'readonly';
                $read_huruf_status = 'readonly';
                $bobot = $nilai->nilai_huruf."#".$nilai->nilai_indeks;
                $isi_nilai_huruf = $nilai->nilai_huruf."(".$nilai->nilai_indeks.")";
                if ($nilai->use_rule=='0') {
                  $read_componen_status = 'readonly';
                  $read_grade_status = '';
                  $read_huruf_status = '';

                }
                ?>
                <tr class="row-data">
                  <td style="vertical-align: middle;text-align: center;"><?=$no;?></td>
                  <td style="vertical-align: middle"><?=$nilai->nim;?></td>
                  <td style="vertical-align: middle"><?=$nilai->nama;?></td>
                  <td style="vertical-align: middle"><?=$nilai->angkatan;?></td>
                  <?php
                  $komponen_nilai = "";
                  if ($nilai->komponen_nilai!="") {
                    $komponen_nilai = json_decode($nilai->komponen_nilai);
                  }
                  $komponen = json_decode($kelas_data->komponen);
                     foreach ($komponen as $key) {
                      if (is_array($key)) {
                        $indexs = 0;

                          foreach ($key as $val) {
                            $value_komponen = 0;
                            if (!empty($komponen_nilai)) {
                              foreach ($komponen_nilai as $key_nilai => $nilai_komponen) {
                                if ($val->id==$nilai_komponen->id) {
                                  if ($nilai_komponen->id==1 && $nilai_komponen->nilai<1) {
                                    $value_komponen = get_nilai_absen($nilai->nim,$val->value_komponen);
                                  } else {
                                    $value_komponen = $nilai_komponen->nilai;
                                  }
                                  
                                }
                                /*if ($nilai_komponen->id==1) {
                                  if ($val->value_komponen < get_nilai_absen($nilai->nim,$val->value_komponen)) {
                                    $value_komponen = get_nilai_absen($nilai->nim,$val->value_komponen);
                                    //jika nilai presensi diinput kurang dari absen yang diinput, maka gunakan nilai absen
                                    $is_less = 1;
                                  } else {
                                    $value_komponen = get_nilai_absen($nilai->nim,$val->value_komponen);
                                    $is_less = 0;
                                  }
                                }*/
                              }
                            }
                            if ($val->id==1 && empty($komponen_nilai)) {
                              $value_komponen = get_nilai_absen($nilai->nim,$val->value_komponen);
                            }
                            
                            /*if (!empty($komponen_nilai)) {
                              if (isset($komponen_nilai[$indexs]->id)==$val->id) {
                                  $value_komponen = $komponen_nilai[$indexs]->nilai;
                              }
                            }*/
                              ?>
               <td>
    <input type="hidden" name="id[<?=$nilai->krs_detail_id;?>][]" value="<?=$val->id;?>">
    <input type="hidden" name="prosentase[<?=$nilai->krs_detail_id;?>][]" value="<?=$val->value_komponen;?>">
    <input type="text"
           data-id="<?=$nilai->krs_detail_id;?>"
           data-prosentase="<?=$val->value_komponen;?>"
           <?=$read_componen_status;?>
           name="nilai[<?=$nilai->krs_detail_id;?>][]"
           required
           value="<?=$value_komponen;?>"
           class="form-control nilai_angka desimal nilai_angka_<?=$nilai->krs_detail_id;?>">
</td>

            <?php
            $indexs++;
        }
    }
   }
   $nilai_akhir = 0;
   if ($nilai->nilai_angka!="") {
     $nilai_akhir = $nilai->nilai_angka;
   }
?>
 <input type="hidden" name="use_rule[<?=$nilai->krs_detail_id;?>]" value="0">
<!-- <td style="background: #fff;vertical-align: middle"><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline" data-toggle="tooltip" data-title="Gunakan Komponen"> <input readonly type="checkbox" <?=($nilai->use_rule=='1'?'checked':'');?> class="group-checkable use-rule" data-id="<?=$nilai->krs_detail_id;?>" name="use_rule[<?=$nilai->krs_detail_id;?>]" value="1"> <span></span></label></td> -->

                 <td>
    <input type="text"
           name="nilai_akhir[<?=$nilai->krs_detail_id;?>]"
           <?=$read_grade_status;?>
           value="<?=$nilai_akhir;?>"
           data-id="<?=$nilai->krs_detail_id;?>"
           class="form-control final_grade final_grade_<?=$nilai->krs_detail_id;?>"
           maxlength="5" size="5"
           data-jurusan="<?=$kelas_data->kode_jur;?>"
           data-angkatan="<?=$nilai->mulai_smt;?>">
</td>
<td>
    <select name="nilai_huruf[<?=$nilai->krs_detail_id;?>]"
            class="form-control nilai_huruf nilai_huruf_<?=$nilai->krs_detail_id;?>"
            <?=$read_huruf_status;?>>
        <option><?=$isi_nilai_huruf?></option>
    </select>

    <!-- ⬇ Tambahan hidden untuk server-side -->
    <input type="hidden" name="kode_jurusan[<?=$nilai->krs_detail_id;?>]" value="<?=$kelas_data->kode_jur;?>">
    <input type="hidden" name="angkatan[<?=$nilai->krs_detail_id;?>]" value="<?=$nilai->mulai_smt;?>">
</td>

                </tr>
                <?php
                $no++;
              }
         ?>
         </tbody>

        </table>
     


            <button type="submit" class="btn btn-success simpan-nilai"><i class="fa fa-check"></i> Simpan</button>
            <p>
       </form>
        </div>
<script type="text/javascript">
$(document).on("keydown", ".nilai_angka", function (e) {

    // ============================
    // 1. FILTER INPUT: ANGKA + TITIK
    // ============================
    const allowedKeys = [
        8, 9,             // backspace, tab
        13,               // enter
        37, 38, 39, 40,   // arrows
        46                // delete
    ];

    if (allowedKeys.includes(e.keyCode)) {

        let td = $(this).closest("td");
        let colIndex = td.index();
        let tr = $(this).closest("tr");

        // ============================
        // ENTER → next row, same column
        // ============================
        if (e.keyCode === 13) {
            e.preventDefault();

            let nextTr = tr.next("tr");
            if (nextTr.length) {
                let nextInput = nextTr.children("td")
                                      .eq(colIndex)
                                      .find(".nilai_angka");
                if (nextInput.length) nextInput.focus().select();
            }
            return;
        }

        // ============================
        // TAB → next column
        // ============================
        if (e.keyCode === 9 && !e.shiftKey) {
            e.preventDefault();

            // cari kolom berikutnya yang berisi .nilai_angka
            let nextTd = td.nextAll("td")
                            .not(":has(.final_grade)")
                            .not(":has(.nilai_huruf)")
                            .has(".nilai_angka")
                            .first();

            if (nextTd.length) {
                nextTd.find(".nilai_angka").focus().select();
                return;
            }

            // ============================
            // JIKA KOLUM TERAKHIR → pindah BARIS berikutnya KOLUM PERTAMA
            // ============================
            let nextRow = tr.next("tr");
            if (nextRow.length) {
                let firstTd = nextRow.children("td")
                                     .not(":has(.final_grade)")
                                     .not(":has(.nilai_huruf)")
                                     .has(".nilai_angka")
                                     .first();

                if (firstTd.length) {
                    firstTd.find(".nilai_angka").focus().select();
                }
            }

            return;
        }

        // ============================
        // SHIFT + TAB → kolom sebelumnya
        // ============================
        if (e.keyCode === 9 && e.shiftKey) {
            e.preventDefault();

            let prevTd = td.prevAll("td")
                           .not(":has(.final_grade)")
                           .not(":has(.nilai_huruf)")
                           .has(".nilai_angka")
                           .first();

            if (prevTd.length) {
                prevTd.find(".nilai_angka").focus().select();
            }

            return;
        }

        return;
    }

    // ============================
    // Hanya angka dan titik
    // ============================
    if ((e.keyCode >= 48 && e.keyCode <= 57) || 
        (e.keyCode >= 96 && e.keyCode <= 105)) return;

    if (e.key === ".") return;

    e.preventDefault();
});





$(document).on("input", ".nilai_angka", function () {
    // Hanya boleh angka dan titik (.)
    this.value = this.value.replace(/[^0-9.]/g, '');

    // Cegah titik lebih dari satu
    if ((this.value.match(/\./g) || []).length > 1) {
        this.value = this.value.replace(/\.+$/, "");
    }
});

// Auto delete "0" on focus
$(document).on("focus", ".nilai_angka", function () {
    let v = $(this).val().trim();
    if (v === "0" || v === "0.0" || v === "0.00") {
        $(this).val("");
    }
});

// Optional: saat blur, kalau kosong → isi 0 lagi
$(document).on("blur", ".nilai_angka", function () {
    let v = $(this).val().trim();
    if (v === "") {
        $(this).val("0");
    }
});

$(document).ready(function() {

$('.use-rule').click(function(){
          if (!$(this).is(':checked')) {
            //nilai akhir
            $(this).closest('td').siblings().find('.final_grade').prop("readonly", false);
            $(this).closest('td').siblings().find('.final_grade').attr("required", true);

            $(this).closest('td').siblings().find('.nilai_huruf').attr("readonly", false); 
            $(this).closest('td').siblings().find('.nilai_huruf').attr("required", true);
            $(this).closest('tr').css("background-color", "#eee");
            //disable komponen grade
            $(this).closest('td').siblings().find('.nilai_angka').removeAttr("onkeypress"); 
            $(this).closest('td').siblings().find('.nilai_angka').attr("readonly", true); 
            $(this).closest('td').siblings().find('.nilai_angka').attr("required", false);
          } else {
            //nilai akhir
            $(this).closest('td').siblings().find('.final_grade').prop("readonly", true);
            $(this).closest('td').siblings().find('.final_grade').attr("required", true);

            $(this).closest('td').siblings().find('.nilai_huruf').attr("readonly", true); 
            $(this).closest('td').siblings().find('.nilai_huruf').attr("required", true);
            $(this).closest('tr').css("background-color", "#eee");
            //disable komponen grade
            $(this).closest('td').siblings().find('.nilai_angka').attr("onkeypress", "jumlah_nilai($(this).attr('data-id'))"); 
            $(this).closest('td').siblings().find('.nilai_angka').attr("readonly", false); 
            $(this).closest('td').siblings().find('.nilai_angka').attr("required", true);
            $(this).closest('tr').css("background-color", "#ffffff");
          }
      });

});

function onlyNumber(e, t, n, a) {
    var o = e.keyCode || e.which,
        i = t.value;
    return o > 57 && 96 > o || o > 105 || 32 == o ? 188 == o && n ? "" == i ? !1 : i.indexOf(",") > -1 ? !1 : !0 : a && (110 == o || 190 == o) || 116 == o ? !0 : !1 : void 0
}

/*
function jumlah_nilai(id) {
        //nilai_akhir = $(this).closest('td').siblings().find('.nilai_akhir');
        //nilai_akhir.val(this.value);
        //findTotal(id);
        nilaiHuruf(id);
}*/
 $(".final_grade").on("keyup", function() {
 // console.log(this.value);
        var btn = $(this);
        id = btn.attr('data-id');
        nilaiHuruf(id);
});
/* <?php 
foreach ($id_krs_detail as $id) {
  ?>
        var arr = $('.nilai_angka_'+<?=$id;?>);
        var tot=0;
        var total=0;
        arr.each(function(){ 
          //console.log($(this).attr('data-prosentase'));
          prosen = $(this).attr('data-prosentase');
          val = $(this).val();
          
          jml = parseFloat(val)*(parseFloat(prosen)/100);
           //tot += parseFloat(val)*(parseFloat(prosen)/100);
           //tot += val * prosen/100;

           jumlah = parseFloat(jml.round(2).toFixed(2))
           tot += jumlah;

            console.log(val+'='+jumlah);
        });
        $('.final_grade_'+<?=$id;?>).val(tot);
  <?php
}
  ?>*/
 /* $('.nilai_angka').blur(function() {
        var btn = $(this);
        id = btn.attr('data-id');
        var arr = $('.nilai_angka_'+id);
        var tot=0;
        var total=0;
        arr.each(function(){ 
          //console.log($(this).attr('data-prosentase'));
          prosen = $(this).attr('data-prosentase');
          val = $(this).val();
          
          jml = parseFloat(val)*(parseFloat(prosen)/100);
           //tot += parseFloat(val)*(parseFloat(prosen)/100);
           //tot += val * prosen/100;

           jumlah = parseFloat(jml.round(2).toFixed(2))
           tot += jumlah;
        });
        //tot = Math.round(tot);
        tot = parseFloat(tot.round(2).toFixed(2));
        $('.final_grade_'+id).val(tot);

      jurusan = $('.final_grade_'+id).attr('data-jurusan');
      nilai_huruf = $('.nilai_huruf_'+id);
      $.ajax({
      type : "post",
      url : "<?=base_admin();?>modul/nilai_per_kelas/get_nilai_huruf.php",
      data : {nilai:tot,kode_jurusan:jurusan},
      success : function(data) {
          nilai_huruf.html(data);
        }
      });

  });

$(".nilai_angka").on("keyup", function() {
    var btn = $(this);
    var id = btn.attr('data-id');
    var arr = $('.nilai_angka_' + id);
    var tot = 0;
    var total = 0;
    arr.each(function() {
        prosen = $(this).attr('data-prosentase');
        val = $(this).val();

        // Check if input value exceeds 100
        if (parseFloat(val) > 100) {
            // Set the input value to 100
            $(this).val(100);
            val = 100; // Update the value variable
        }

        jml = parseFloat(val) * (parseFloat(prosen) / 100);
        jumlah = parseFloat(jml.round(2).toFixed(2));
        tot += jumlah;
    });
    //tot = Math.round(tot);
    tot = parseFloat(tot.round(2).toFixed(2));
    $('.final_grade_' + id).val(tot);
      jurusan = $('.final_grade_'+id).attr('data-jurusan');
      nilai_huruf = $('.nilai_huruf_'+id);
      $.ajax({
      type : "post",
      url : "<?=base_admin();?>modul/nilai_per_kelas/get_nilai_huruf.php",
      data : {nilai:tot,kode_jurusan:jurusan},
      success : function(data) {
          nilai_huruf.html(data);
        }
      });
});*/

$(".nilai_angka").on("keyup", function() {
    var btn = $(this);
    var id = btn.attr('data-id');
    var arr = $('.nilai_angka_' + id);
    var tot = 0;
    var total = 0;
    
    arr.each(function() {
        prosen = $(this).attr('data-prosentase');
        val = $(this).val();

        // Check if input value exceeds 100
        if (parseFloat(val) > 100) {
            $(this).val(100);
            val = 100;
        }

        jml = parseFloat(val) * (parseFloat(prosen) / 100);
        jumlah = parseFloat(jml.round(2).toFixed(2));
        tot += jumlah;
    });
    
    tot = parseFloat(tot.round(2).toFixed(2));
    $('.final_grade_' + id).val(tot);
    
    jurusan = $('.final_grade_'+id).attr('data-jurusan');
    angkatan = $('.final_grade_'+id).attr('data-angkatan');
    nilai_huruf = $('.nilai_huruf_'+id);
    var nilai = tot;
    
    // Function to send AJAX request with retry logic
    function sendRequest(retryCount = 0, maxRetries = 3) {
        $.ajax({
            type: "post",
            url: "<?=base_admin();?>modul/nilai_per_kelas/get_nilai_huruf.php",
            data: {nilai: nilai, kode_jurusan: jurusan,angkatan:angkatan},
            success: function(data) {
                nilai_huruf.html(data);
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

function nilaiHuruf(clsname) {
      nilai_akhir = $('.final_grade_'+clsname).val();
      jurusan = $('.final_grade_'+clsname).attr('data-jurusan');
       angkatan = $('.final_grade_'+id).attr('data-angkatan');
      nilai_huruf = $('.nilai_huruf_'+clsname);
      $.ajax({
      type : "post",
      url : "<?=base_admin();?>modul/nilai_per_kelas/get_nilai_huruf.php",
      data : {nilai:this.nilai_akhir,kode_jurusan:jurusan,angkatan:angkatan},
      success : function(data) {
          nilai_huruf.html(data);
        }
    });
}

Number.prototype.round = function(decimals) {
    return Number((Math.round(this + "e" + decimals)  + "e-" + decimals));
}
function findTotal(clsname){
    var arr = $('.nilai_angka_'+clsname);
    var tot=0;
    var total=0;
    arr.each(function(){ 
      //console.log($(this).attr('data-prosentase'));
      prosen = $(this).attr('data-prosentase');
      val = $(this).val();
      jml = parseFloat(val)*(parseFloat(prosen)/100);
       //tot += parseFloat(val)*(parseFloat(prosen)/100);
       //tot += val * prosen/100;

       jumlah = parseFloat(jml.round(2).toFixed(2))
       tot += jumlah;

        console.log(val+'='+jumlah);
    });

    
    $('.final_grade_'+clsname).val(tot);
    //$('.final_grade_'+clsname).val(parseFloat(tot));
}

 $(document).ready(function() {
    
    $("#form_input_nilai_komponen").validate({      
        submitHandler: function(form) {
            $("#loadnya").show();
            $(form).ajaxSubmit({
                url : $(this).attr("action"),
                dataType: "json",
                type : "post",
                error: function(data ) { 
                  $("#loadnya").hide();
                  //console.log(data); 
                  $(".isi_warning_nilai_komponen").html(data.responseText);
                  $(".error_data_nilai_komponen").focus()
                  $(".error_data_nilai_komponen").fadeIn();
                  $(".simpan-nilai").prop("readonly", false);
                },
                success: function(responseText) {
                  $("#loadnya").hide();
                  console.log(responseText);
                      $.each(responseText, function(index) {
                          console.log(responseText[index].status);
                          if (responseText[index].status=="die") {
                            $("#informasi").modal("show");
                          } else {
                            $(".simpan-nilai").attr("readonly", "readonly");
                            $(".error_data_nilai_komponen").hide();
                            $(".notif_top_up").fadeIn(1000);
                            $(".notif_top_up").fadeOut(1000, function() {
                                    location.reload();
                            });
                          }
                    });
                }

            });
        }
    });

});
</script>