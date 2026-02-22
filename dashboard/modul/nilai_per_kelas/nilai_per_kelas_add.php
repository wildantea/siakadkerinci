<section class="content-header">
  <h1>Input Nilai Perkelas</h1>
  <ol class="breadcrumb">
    <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="<?=base_index();?>nilai">Nilai</a></li>
    <li class="active">Input Nilai Perkelas</li>
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
<a href="<?=base_index();?>nilai-per-kelas" class="btn btn-warning"><i class="fa fa-reply"></i> Kembali</a>
<p>
              <form method="POST" action="<?= base_url() ?>dashboard/modul/nilai_per_kelas/nilai_per_kelas_action.php?act=input_nilai" id="form_input_nilai" >

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
<?php
$nama_dosen = "";
    if ($kelas_data->nama_dosen!='') {
        $nama_dosen = array_map('trim', explode('#', $kelas_data->nama_dosen));
        $nama_dosen = trim(implode("<br>- ", $nama_dosen));
        $nama_dosen = '- '.$nama_dosen;
    }
?>
 <tr>
          <td class="info2"><strong>Dosen Pengajar</strong></td>
          <td><?=$nama_dosen;?></td>
        </tr>
       <tr>
        <th style="text-align: right;vertical-align: middle;">Komponen Penilaian</th>
         <th>
        <span type="submit" class="btn btn-success tambah-komponen" data-toggle="tooltip" data-id="<?=$kelas_data->kelas_id;?>" data-title="Tambah Komponen Penilaian"><i class="fa fa-plus"></i> Tambah</span>
         </th>
       </tr>
      </tbody></table>
       <div class="alert alert-info fade in">
          <button type="button" class="close hide_alert_notif">&times;</button>
          <i class="icon fa fa-warning"></i>Jika anda ingin menggunakan Komponen Penilaian Klik Tombol diatas
        </div>
            <span class="lead"><button type="submit" class="btn btn-success simpan-nilai" disabled=""><i class="fa fa-check"></i> Simpan</button> Silakan Checklist Nilai Yang akan di Ubah</span>
            <p>

        <table  class="table table-bordered table-striped">
         
         <thead>
           <tr class="bg-light-blue color-palette">
             <th rowspan="2" style="padding-right:0;width: 7%;text-align: center;vertical-align: middle;background: #ffffff;color:#000"><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline "> <input type="checkbox" class="group-checkable bulk-check"> <span></span></label>No</th>
             <th rowspan="2" style="text-align: center;vertical-align: middle">NIM</th>
             <th rowspan="2" style="text-align: center;vertical-align: middle">Nama</th>
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
              $array_skala = array();
              $skala_nilai = $db2->query("select * from tb_data_skala_nilai where kode_jurusan=?",array('kode_jurusan' => $kelas_data->kode_jur));
              foreach ($skala_nilai as $skala) {
                $array_skala[$skala->nilai_huruf."#".$skala->nilai_indeks] = $skala->nilai_huruf." (".$skala->nilai_indeks.")";
              }
              $nilai_data = $db2->query("select tb_data_kelas_krs_detail.krs_detail_id,nilai_angka,nilai_huruf,nilai_indeks,tb_data_kelas_krs.nim,nama,left(mulai_smt,4) as angkatan from tb_data_kelas_krs_detail inner join tb_data_kelas_krs using(krs_id) inner join tb_master_mahasiswa using(nim) where kelas_id=? and tb_data_kelas_krs_detail.disetujui='1' order by tb_data_kelas_krs.nim asc",array('kelas_id' => $id));
              echo $db2->getErrorMessage();
              foreach ($nilai_data as $nilai) {
                $bobot = $nilai->nilai_huruf."#".$nilai->nilai_indeks;
                ?>
                <tr class="row-data" style="background: #eee">
                  <td style="background: #fff;text-align: center;vertical-align: middle"><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"> <input type="checkbox" class="group-checkable check-selected"> <span></span></label><?=$no;?></td>
                  <td style="vertical-align: middle"><?=$nilai->nim;?></td>
                  <td style="vertical-align: middle"><?=$nilai->nama;?></td>
                  <td style="vertical-align: middle"><?=$nilai->angkatan;?></td>
                  <td><input type="text" name="nilai_angka[<?=$nilai->krs_detail_id;?>]" disabled="" value="<?=$nilai->nilai_angka;?>" class="form-control nilai_angka desimal" maxlength="5" size="5" data-jurusan="<?=$kelas_data->kode_jur;?>"></td>
                  <td>
                    <select name="nilai_huruf[<?=$nilai->krs_detail_id;?>]" class="form-control nilai_huruf" disabled="">
<option value=""> </option>
<?php
foreach ($array_skala as $id_skala => $skala_bobot) {
  if ($bobot==$id_skala) {
    echo "<option value='$id_skala' selected>$skala_bobot</option>";
  } else {
    echo "<option value='$id_skala'>$skala_bobot</option>";
  }
  
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
     </div>

   </div>
  </div>
</div>
</section>
<div class="modal fade" id="modal_komponen_penilaian" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="glyphicon glyphicon-remove"></i></span></button> <h4 class="modal-title">Pengaturan Komponen Penilaian</h4> </div> <div class="modal-body" id="isi_komponen_penilaian"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
<script type="text/javascript">
$(document).ready(function() {
          $('input.desimal').numberField(
    {
      ints: 3, // digits count to the left from separator
      floats: 2, // digits count to the right from separator
      separator: "."
  }
  );
});
$(".tambah-komponen").click(function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);

        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/nilai_per_kelas/komponen/komponen_view.php",
            type : "post",
            data : {kelas_id:id},
            success: function(data) {
                $("#isi_komponen_penilaian").html(data);
                $("#loadnya").hide();
          }
        });

    $('#modal_komponen_penilaian').modal({ keyboard: false,backdrop:'static' });

    });
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


 $(".nilai_angka").on("keyup", function() {
        var btn = $(this);
        jurusan = btn.attr('data-jurusan');
        nilai_huruf = $(this).closest('td').siblings().find('.nilai_huruf');
          $.ajax({
            type : "post",
            url : "<?=base_admin();?>modul/nilai_per_kelas/get_nilai_huruf.php",
            data : {nilai:this.value,kode_jurusan:jurusan},
            success : function(data) {
                nilai_huruf.html(data);
              }
          });

});

 $(document).ready(function() {
    
    
    
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