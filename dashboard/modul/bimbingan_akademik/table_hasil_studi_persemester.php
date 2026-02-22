<div class="box-header">
  <div class="box-group" id="accordion">
<!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
<?php
      $qq = $db->query("select k.mhs_id,k.krs_id, js.jns_semester,js.nm_singkat, sf.tahun, s.id_semester,a.*,
          (select sum(sks) from krs_detail where krs_detail.id_krs=k.krs_id
           and krs_detail.batal='0' group by krs_detail.id_krs) as sks_diambil from krs k
          join semester s on s.sem_id=k.sem_id
          join akm a on (a.sem_id=s.id_semester and a.mhs_nim=k.mhs_id)
          join semester_ref sf on sf.id_semester=s.id_semester
          join jenis_semester js on js.id_jns_semester=sf.id_jns_semester where k.mhs_id='".de(uri_segment(3))."'
          order by s.id_semester asc");
      $i=1;
?>
<!--<form class="form" action="<?= base_admin().'modul/transkrip_nilai_akhir/' ?>cetak_transkrip_nilai.php" method="post">-->
  <div class="form-group">
  <form name="form1" action="<?= base_admin().'modul/hasil_studi_mahasiswa/' ?>cetak_all.php" method="post">
<button class="btn btn-success" name="laporan" type="submit" style="float:right"><i class="fa fa-print" ></i> Cetak Semua</button>
<input name='nim2' type='hidden' value='<?= de(uri_segment(3)) ?>'>

</form>
  

    <button class="btn btn-primary" data-toggle="modal" data-target="#myModal" type="submit" ><i class="fa fa-print"></i> Cetak KHS</button>
    
  </div>
  <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- konten modal-->
            <div class="modal-content">
            <form class="form" id="sem" action="<?= base_admin().'modul/hasil_studi_mahasiswa/' ?>cetak_hasil_persemester.php" method="post">
                <!-- heading modal -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Silakan Pilih Semester untuk Dicetak</h4>
                </div>
                <!-- body modal -->
                <div class="modal-body">

                    <div class="form-group">
                                  <label for="Nama Kelas" class="control-label col-lg-2">Semester</label>
                                  <div class="col-lg-10">
                                      <select name="sem" id="sem" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2" method="post" required>
                                         <option value=""></option>
                                         <?php 
                                           $sem = $db->query("select k.mhs_id,k.krs_id, js.jns_semester,js.nm_singkat, sf.tahun, s.id_semester,a.*,
                                            (select sum(sks) from krs_detail where krs_detail.id_krs=k.krs_id
                                             and krs_detail.batal='0' group by krs_detail.id_krs) as sks_diambil from krs k
                                            join semester s on s.sem_id=k.sem_id
                                            join akm a on (a.sem_id=s.id_semester and a.mhs_nim=k.mhs_id)
                                            join semester_ref sf on sf.id_semester=s.id_semester
                                            join jenis_semester js on js.id_jns_semester=sf.id_jns_semester where k.mhs_id='".de(uri_segment(3))."'
                                            order by s.id_semester asc");
                                            foreach ($sem as $isi2) {
                                              if ($isi2->id_semester==$sem2) {
                                               echo "<option value='".$enc->enc($isi2->id_semester)."' selected>$isi2->tahun/".($isi2->tahun+1)." $isi2->jns_semester</option>";
                                              }else{
                                                echo "<option value='".$enc->enc($isi2->id_semester)."'>$isi2->tahun/".($isi2->tahun+1)." $isi2->jns_semester </option>";
                                              }
                                            
                                         } ?>
                                        </select>
                                  </div>
                                </div><!-- /.form-group --><br><br>
                </div>
                <!-- footer modal -->
                 <input name='nim' type='hidden' value='<?= de(uri_segment(3)) ?>'>
                 <?php
                  $qqq = $db->query("select k.mhs_id,k.krs_id, js.jns_semester,js.nm_singkat, sf.tahun, s.id_semester,a.*,
          (select sum(sks) from krs_detail where krs_detail.id_krs=k.krs_id
           and krs_detail.batal='0' group by krs_detail.id_krs) as sks_diambil from krs k
          join semester s on s.sem_id=k.sem_id
          join akm a on (a.sem_id=s.id_semester and a.mhs_nim=k.mhs_id)
          join semester_ref sf on sf.id_semester=s.id_semester
          join jenis_semester js on js.id_jns_semester=sf.id_jns_semester where k.mhs_id='".de(uri_segment(3))."'
          order by s.id_semester asc limit 1");
                  foreach ($qqq as $kq) {
                    ?>
                    <input name='k' type='hidden' value='<?= $kq->krs_id?>'>
                    <?php
                  }
                 ?>
                 
                <div class="modal-footer">
                
                    <button  class="btn btn-primary" type="submit">Cetak</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                    
                </div>
              </form>
            </div>
        </div>
    </div>       
<?php
      foreach ($qq as $k) {
?>
   
      <div class="panel box box-primary">
                  <a style="font-size: 17px" data-toggle="collapse" data-parent="#accordion" href="#<?= $i ?>" class="collapsed" aria-expanded="false">
                  <div class="box-header with-border">
                    <h4 class="box-title" style="width: 100%;float:left;display:inline-block;">
                      
                        <?= "Semester :  ".$k->tahun."/".($k->tahun+1)." $k->jns_semester" ?>
                   
                     
                    </h4>
                    <i class="fa fa-get-pocket" style="float: right;position: relative;top: -25px"></i>
                  </div>
                   </a>
                  <div id="<?= $i ?>" class="panel-collapse collapse" aria-expanded="true" style="">
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
                                  
                                </tr>
                            </thead>
                      <?php
                      $noo=1;
                      $qq=$db->query("select k.id_krs_detail, k.sks, k.id_krs_detail,m.nama_mk,m.kode_mk,k.bobot,k.nilai_huruf from krs_detail k
                                     join matkul m on m.id_matkul=k.kode_mk where k.id_krs='$k->krs_id'");
                      foreach ($qq as $kr) {
                       echo " <tr>
                                <td>$noo</td>
                                <td>$kr->kode_mk</td>
                                <td>$kr->nama_mk</td>
                                <td style='text-align:center'>$kr->sks</td>
                                <td style='text-align:center' id='bobot-$kr->id_krs_detail'>$kr->bobot</td>
                                <td style='text-align:center' id='nilai-$kr->id_krs_detail'>$kr->nilai_huruf</td>
                            </tr>";
                            $noo++;
                      }
                      ?>
                        
                           
                            <tbody>
                            </tbody>
                        </table>
                     
                    </div>
                  </div>
                </div>
<?php
    $i++;
  }
?>

                </div>
</div><!-- /.box-header -->

<script type="text/javascript">
function submit_form(){
document.form1.submit();
}

$("#bulkDelete").on('click',function() { // bulk checked
        var status = this.checked;
        $(".pilih").each( function() {
          $(this).prop("checked",status);
        });
      });


$('#cetak_transkrip').on("click", function(event){
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
