<div class="box-header">
  <div class="box-group" id="accordion">
<!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
<?php
// echo "select k.mhs_id,k.krs_id, js.jns_semester,js.nm_singkat, sf.tahun, s.id_semester,a.*,
//           (select sum(sks) from krs_detail where krs_detail.id_krs=k.krs_id
//            and krs_detail.batal='0' group by krs_detail.id_krs) as sks_diambil from krs k
//           join semester s on s.sem_id=k.sem_id
//           join akm a on (a.sem_id=s.id_semester and a.mhs_nim=k.mhs_id)
//           join semester_ref sf on sf.id_semester=s.id_semester
//           join jenis_semester js on js.id_jns_semester=sf.id_jns_semester where k.mhs_id='".de(uri_segment(3))."'
//           order by s.id_semester asc";
      $qq = $db->query("select a.mhs_nim as mhs_id, js.jns_semester,js.nm_singkat, sf.tahun,a.*,
 (select sum(sks) from krs_detail where krs_detail.nim=a.mhs_nim
and krs_detail.batal='0'
 group by krs_detail.id_krs) as sks_diambil from  akm a  
join semester_ref sf on sf.id_semester=a.sem_id 
join jenis_semester js on js.id_jns_semester=sf.id_jns_semester
 where a.mhs_nim='".de(uri_segment(3))."' 
order by a.sem_id asc");
      $i=1;
?>
<form id="transkrip_nilai" class="form" target="_Blank" action="<?= base_admin().'modul/transkrip_nilai_akhir/' ?>cetak_transkrip_nilai.php" method="post">
  <div class="form-group">
    <button id="cetak_transkrip"  class="btn btn-success" type="submit" ><i class="fa fa-print"></i> Cetak Transkrip PDF</button>
    <button id="cetak_transkrip_excel"  class="btn btn-success" type="submit" ><i class="fa fa-print"></i> Cetak Transkrip Excel</button>
  </div>
<?php
      foreach ($qq as $k) {  
?>
    <input name='nim' type='hidden' value='<?= $k->mhs_id?>'>
    <input name='k' type='hidden' value='<?= $k->krs_id?>'> 
      <div class="panel box box-primary">
        <a style="font-size: 17px" data-toggle="collapse" data-parent="#accordion" href="#<?= $i ?>" class="collapsed" aria-expanded="false">
        <div class="box-header with-border">
          <h4 class="box-title" style="width: 100%;float:left;display:inline-block;">

              <?= "Semester :  ".$k->tahun."/".($k->tahun+1)." $k->jns_semester" ?>


          </h4>
          <i class="fa fa-get-pocket" style="float: right;position: relative;top: -25px"></i>
        </div>
         </a>
         <div class="box-body">
           <div class="callout">
             <table >
             <tr>
               <td style="width: 100px ;font-size: 15px">Total SKS</td><td>: <b style="font-size: 15px"><?= $k->sks_diambil ?></b></td>
             </tr>
             <tr>
                <td style="font-size: 15px">IP</td><td>: <b style="font-size: 15px"><?= $k->ip ?></b></td>
             </tr>
           </table>
           </div>


            <table class="table table-bordered table-striped" >
                 <thead>
                     <tr>
                       <th style='text-align:center'>No</th>
                       <th style='text-align:center'>Kode MK</th>
                       <th style='text-align:center'>Nama MK</th>
                       <th style='text-align:center'>SKS</th>
                       <th style='text-align:center'>Bobot</th>
                       <th style='text-align:center'>Nilai Huruf</th>
                       <th style='text-align:center'>
                         Print <input id="bulkDelete" class="pilih" type="checkbox" required>
                       </th>
                     </tr>
                 </thead>
           <?php
           $noo=1;
           $qq=$db->query("select k.id_krs_detail, k.sks, k.id_krs_detail,m.nama_mk,m.kode_mk,k.bobot,k.nilai_huruf from krs_detail k
                          join matkul m on m.id_matkul=k.kode_mk where k.nim='$k->mhs_nim' and k.id_semester='$k->sem_id' ");  
           foreach ($qq as $kr) { 
            echo " <tr>
                     <td>$noo</td>
                     <td>$kr->kode_mk</td>
                     <td>$kr->nama_mk</td>
                     <td style='text-align:center'>$kr->sks</td>
                     <td style='text-align:center' id='bobot-$kr->id_krs_detail'>$kr->bobot</td>
                     <td style='text-align:center' id='nilai-$kr->id_krs_detail'>$kr->nilai_huruf</td>
                     <td style='text-align:center'>
                        <input class='pilih' type='checkbox' name='id_transkrip[]' value='$kr->id_krs_detail'>
                     </td>
                 </tr>";
                 $noo++;
           }
           ?>
                 <tbody>
                 </tbody>
             </table>
             
         </div>
      </div>
<?php
    $i++;
  }
?>
</form>
                </div>
</div><!-- /.box-header -->

<script type="text/javascript">

$("#bulkDelete").on('click',function() { // bulk checked
        var status = this.checked;
        $(".pilih").each( function() {
          $(this).prop("checked",status);
        });
      });


  $('#cetak_transkrip').on("click", function(event){

      $('#transkrip_nilai').attr('action', '<?=base_admin()?>modul/transkrip_nilai_akhir/cetak_transkrip_nilai.php');

      if( $('pilih:checked').length > 0 ){


        var ids = [];
        $('.pilih').each(function(){
          if($(this).is(':checked')) {
            ids.push($(this).val());
          }
        });
        var ids_string = ids.toString();
    }
  }); 

  $('#cetak_transkrip_excel').on("click", function(event){

      $('#transkrip_nilai').attr('action', '<?=base_admin()?>modul/transkrip_nilai_akhir/cetak_transkrip_nilai_excel.php');

          if( $('pilih:checked').length > 0 ){


            var ids = [];
            $('.pilih').each(function(){
              if($(this).is(':checked')) {
                ids.push($(this).val());
              }
            });
            var ids_string = ids.toString();
    }
  });

  function set_rule(checkbox) {
    if(checkbox.checked == true){
       $(".komponen_nilai").prop('required',true);
    }else{
       $(".komponen_nilai").prop('required',false);
    }
  }

  function update_nilai(krs_id) {
    $("#"+krs_id).html("<i class='fa fa-edit'></i> Loading...");
    $.ajax({
            type: 'POST',
            url : '<?=base_admin();?>modul/transkrip_nilai_akhir/transkrip_nilai_akhir_action.php?act=show_update_nilai',
            data: {
              krs_id : krs_id
            },
            success: function(result) {
              $("#"+krs_id).html("<i class='fa fa-edit'></i> Update Nilai");
              $(".modal-body").html(result);
              $("#modal-nilai").modal("show");

            },
            //async:false
        });

  }
  $(document).ready(function(){
    $("#form_nilai").submit(function(){
         $.ajax({
            type: 'POST',
            url : '<?=base_admin();?>modul/transkrip_nilai_akhir/transkrip_nilai_akhir_action.php?act=update_nilai',
            data:  $("#form_nilai").serialize(),
            success: function(result) {
             // alert(result);
              var n = result.split("-");
              $("#bobot-"+n[2]).html(n[0]);
              $("#nilai-"+n[2]).html(n[1]);
             // $(".modal-body").html(result);
              $("#modal-nilai").modal("toggle");

            },
            //async:false
        });
      return false;
    })
  });
</script>
