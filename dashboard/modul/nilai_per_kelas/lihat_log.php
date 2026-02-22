<?php
session_start();
include "../../inc/config.php";
    $kelas_data = $db2->fetchCustomSingle("SELECT tdk.kelas_id,tdk.kls_nama,kuota,ada_komponen,komponen,vmk.kode_mk,tdk.sem_id,vmk.nama_mk,vmk.total_sks,vmk.kode_jur,vmk.kode_jur, vmk.nama_jurusan from tb_data_kelas tdk
INNER JOIN view_matakuliah_kurikulum vmk using(id_matkul) where kelas_id=?",array('kelas_id' => $_POST['kelas_id']));

$data_mhs = $db2->fetchCustomSingle("select view_simple_mhs.nim,nama from view_simple_mhs inner join tb_data_kelas_krs using(nim) inner join tb_data_kelas_krs_detail using (krs_id) where krs_detail_id=?",array('krs_detail_id' => $_POST['krs_id']));

?>
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
        <tr>
          <td class="info2"><strong>NIM</strong></td>
          <td colspan="3"><?=$data_mhs->nim;?></td>
        </tr>
        <tr>
          <td class="info2"><strong>Nama</strong></td>
          <td colspan="3"><?=$data_mhs->nama;?></td>
        </tr>
      </tbody></table>
 <div class="alert alert-danger error_data_nilai_komponen" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning_nilai_komponen"></span>
        </div>
               <table  class="table table-bordered table-striped">
         
         <thead>
           <tr class="bg-light-blue color-palette">
             <th style="text-align: center;vertical-align: middle;width:3%">No</th>
             <th style="text-align: center;vertical-align: middle">Nama Pengubah</th>
             <th style="text-align: center;vertical-align: middle">Tanggal Pengubahan</th>
             <th style="text-align: center;vertical-align: middle">Nilai Angka</th>
             <th style="text-align: center;vertical-align: middle">Nilai Huruf</th>
             <th style="text-align: center;vertical-align: middle">Nilai Bobot</th>
           </tr>
         </thead>
         <tbody>
         <?php
         $nilai_id = $_REQUEST['krs_id'];
         $key_nol = "";
         $label_nol = "";
              $no=1;
              $nilai_data = $db2->fetchCustomSingle("select krs_detail_id,history_nilai from tb_data_kelas_krs_detail where krs_detail_id=?",array('krs_detail_id' => $_POST['krs_id']));
              if ($nilai_data->history_nilai!="") {
                $decode_history = json_decode($nilai_data->history_nilai);
                foreach ($decode_history as $history) {
                  ?>
                  <tr class="row-data">
                    <td style="vertical-align: middle;text-align: center;"><?=$no;?></td>
                    <td style="vertical-align: middle"><?=$history->pengubah;?></td>
                    <td style="vertical-align: middle"><?=tgl_time($history->date_updated);?></td>
                    <td style="vertical-align: middle"><?=$history->nilai_angka;?></td>
                    <td style="vertical-align: middle"><?=$history->nilai_huruf;?></td>
                    <td style="vertical-align: middle"><?=$history->nilai_indeks;?></td>
                  </tr>
                  <?php
                  $no++;
                }
              }
         ?>
         </tbody>

        </table>
<script type="text/javascript">

$(document).ready(function() {
          $('input.desimal').numberField(
    {
      ints: 3, // digits count to the left from separator
      floats: 2, // digits count to the right from separator
      separator: "."
  }
  );
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


$(".nilai_angka").blur(function(){
 // console.log(this.value);
        var btn = $(this);
        id = btn.attr('data-id');
        //nilai_akhir = $(this).closest('td').siblings().find('.nilai_akhir');
        //nilai_akhir.val(this.value);
        findTotal(id);
        nilaiHuruf(id);
});

function nilaiHuruf(clsname) {
      nilai_akhir = $('.nilai_akhir_'+clsname).val();
      jurusan = $('.nilai_akhir_'+clsname).attr('data-jurusan');
      nilai_huruf = $('.nilai_huruf_'+clsname);
      $.ajax({
      type : "post",
      url : "<?=base_admin();?>modul/nilai_per_kelas/get_nilai_huruf.php",
      data : {nilai:this.nilai_akhir,kode_jurusan:jurusan},
      success : function(data) {
          nilai_huruf.html(data);
        }
    });
}

function findTotal(clsname){
    var arr = $('.nilai_angka_'+clsname);
    var tot=0;
    arr.each(function(){ 
      //console.log($(this).attr('data-prosentase'));
      prosen = $(this).attr('data-prosentase');
      val = $(this).val();
      tot += parseInt(val)*(parseInt(prosen)/100);
    });
    
    $('.nilai_akhir_'+clsname).val(tot);
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
                  $(".simpan-nilai").prop("disabled", false);
                },
                success: function(responseText) {
                  $("#loadnya").hide();
                  console.log(responseText);
                      $.each(responseText, function(index) {
                          console.log(responseText[index].status);
                          if (responseText[index].status=="die") {
                            $("#informasi").modal("show");
                          } else {
                            $(".simpan-nilai").attr("disabled", "disabled");
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