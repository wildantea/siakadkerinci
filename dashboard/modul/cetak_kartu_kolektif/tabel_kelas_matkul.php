                            <div class="box-body table-responsive">
                            

                            <form name="form1" target="_Blank" method="post">
                              <button name"cetak_kartu" id="cetak_kartu" class="btn btn-success"  name="laporan" type="submit" formaction="<?= base_admin().'modul/cetak_kartu_kolektif/' ?>cetak_kartu_kolektif_uts.php"><i class="fa fa-print"></i> Cetak Kartu UTS</button>

                            <button name"cetak_kartu2" id="cetak_kartu2" class="btn btn-primary"  name="laporan" type="submit" formaction="<?= base_admin().'modul/cetak_kartu_kolektif/' ?>cetak_kartu_kolektif_uas.php"><i class="fa fa-print"></i> Cetak Kartu UAS</button>
                            <br>
                            <br>

                               



                        <table id="dtb_manual" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                  <th>No</th>
                                  <th>NIM</th>
                                  <th style='width:200px;'>Nama Mahasiswa</th>
                                  <th>Jurusan</th>
                                  <th>Angkatan</th> 
                                  <th style='text-align:center'> Cetak <input id="bulkDelete" class="pilih" type="checkbox" required></th> 
                                </tr>
                            </thead>
                            
                              <?php
                              $no=1;
                             
                              $qq = $db->query("SELECT m.`nim`, m.`nama`, m.`mulai_smt`, m.`jur_kode`, s.`id_semester`, k.mhs_id, k.`krs_id` FROM krs k
                                                JOIN mahasiswa m ON m.`nim`=k.`mhs_id`
                                                JOIN semester s ON s.`sem_id`=k.`sem_id`
                                                WHERE m.`jur_kode`='".$dec->dec($_GET['jur'])."'  AND m.`mulai_smt`='".$dec->dec($_GET['ang'])."'  AND s.`id_semester`='".$dec->dec($_GET['sem'])."'
                                                and k.krs_id in(SELECT id_krs FROM krs_detail WHERE id_krs=k.krs_id)");
                              foreach ($qq as $k) {

                              
                             
                               
                                  echo " <tr>
                                  <td>$no</td>
                                  <td>$k->nim</td>
                                  <td>$k->nama</td>
                                  <td>$k->jur_kode</td>
                                  <td>$k->mulai_smt </td>
                                  <td style='text-align:center'>
                                            <input class='pilih' name='selector[]'' id='selector' type='checkbox' value='$k->krs_id' method='post'>

                                  </td>                                  
                                </tr>";
                              
                               $no++;
                              }
                              ?>
                              

                                <tbody>
                            </tbody>
                            <input name='nim' type='hidden' value='<?= $k->mhs_id?>'>
                              <input name='k' type='hidden' value='<?= $k->krs_id?>'>
                               <input name='jur' type='hidden' value='<?= $k->jur_kode?>'>
                              <input name='sem' type='hidden' value='<?= $k->id_semester?>'>
                        </table>
                        
 
 
</form>
                        
                    </div><!-- /.box-body -->
                            
                            
<script type="text/javascript">
 function submit_form(){
document.form1.submit();
document.form2.submit();
}

$("#bulkDelete").on('click',function() { // bulk checked
        var status = this.checked;
        $(".pilih").each( function() {
          $(this).prop("checked",status);
        });
      });
$(document).ready(function () {
    $('#cetak_kartu').click(function() {
      checked = $("input[type=checkbox]:checked").length;

      if(!checked) {
        alert("Anda harus Memilih Daftar Nama Mahasiswa yang Akan Dicetak.");
        return false;
      }

    });
});
$(document).ready(function () {
    $('#cetak_kartu2').click(function() {
      checked = $("input[type=checkbox]:checked").length;

      if(!checked) {
        alert("Anda harus Memilih Daftar Nama Mahasiswa yang Akan Dicetak.");
        return false;
      }

    });
});



$('#cetak_kartu').on("click", function(event){
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
$('#cetak_kartu2').on("click", function(event){
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

                 
