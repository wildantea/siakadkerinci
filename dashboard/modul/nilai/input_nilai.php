<section class="content-header">
  <h1>Input Nilai Perkelas</h1>
  <ol class="breadcrumb">
    <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="<?=base_index();?>nilai">Nilai</a></li>
    <li class="active">Input Nilai Perkelas</li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
            <div class="box-body table-responsive">
         <form method="GET" class="form-horizontal">
          <div class="form-group">
            <label for="Nama Kelas" class="control-label col-lg-2" style="text-align: left;padding-left: 40px">Nama Kelas</label>
            <div class="col-lg-10" style="top:6px">
              <b> <?= $data_kelas->kls_nama ?></b>
           </div>
           </div><!-- /.form-group -->
           <div class="form-group">
                <label for="Nama Kelas" class="control-label col-lg-2" style="text-align: left;padding-left: 40px">Mata Kuliah</label>
                <div class="col-lg-10" style="top:6px">
               <b> <?= $data_kelas->nama_mk ?></b>
               </div>
           </div><!-- /.form-group -->
           <div class="form-group">
                <label for="Nama Kelas" class="control-label col-lg-2" style="text-align: left;padding-left: 40px">Dosen Pengampu</label>
                <div class="col-lg-10" style="top:6px">
                <b><?= $pengampu ?></b>
               </div>
           </div><!-- /.form-group -->
           <div class="form-group">
                <label for="Nama Kelas" class="control-label col-lg-2" style="text-align: left;padding-left: 40px">Komponen Penilaian</label>
                <div class="col-lg-10" style="top:6px">
                  <table class="table" style="width:400px">
                    <thead>
                      <tr>
                      <?php
                      foreach ($db->query("select * from kelas_penilaian kp join komponen_nilai n 
                                           on n.id=kp.id_komponen where kp.id_kelas='$id_kelas'") 
                              as $k) {
                        echo "<th style='width:100px;text-align:center'>$k->nama_komponen</th>";
                      }
                      ?>
                        
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                         <?php
                         $jml_komponen=0;
                      foreach ($db->query("select * from kelas_penilaian kp join komponen_nilai n 
                                           on n.id=kp.id_komponen where kp.id_kelas='$id_kelas'") 
                              as $k) {
                        echo "<td style='width:100px;text-align:center'>$k->nilai %</td>";
                        $jml_komponen++;
                      }
                      ?>
                    </tbody>
                  </table>
               </div>
           </div><!-- /.form-group -->
        </form>
        <form method="POST" action="<?= base_url() ?>dashboard/modul/nilai/nilai_action.php?act=input_nilai" id="form_input_nilai" >
        <input type="hidden" name="jur" value="<?= en($jur) ?>">
        <table  class="table table-bordered table-striped">
         
         <thead>
           <tr>
             <th rowspan="2" style="text-align: center;vertical-align: middle">No</th>
             <th rowspan="2" style="text-align: center;vertical-align: middle">NIM</th>
             <th rowspan="2" style="text-align: center;vertical-align: middle">Nama</th>
             <th colspan="<?= $jml_komponen ?>" style="text-align: center;vertical-align: middle">Komponen Penilaian</th>
             <th rowspan="2" style="text-align: center;vertical-align: middle">Nilai Akhir</th>
             <th style="text-align: center;vertical-align: middle;width: 100px" rowspan="2">Gunakan Rule Komponen</th>
           </tr>
           <tr>
             <?php
                      foreach ($db->query("select * from kelas_penilaian kp join komponen_nilai n 
                                           on n.id=kp.id_komponen where kp.id_kelas='$id_kelas'") 
                              as $k) {
                        echo " <th style='text-align: center;vertical-align: middle'>$k->nama_komponen</th>";
                      }
                      ?>
<!--              <th style="text-align: center;vertical-align: middle">Presensi</th>
             <th style="text-align: center;vertical-align: middle">Mandiri</th>
             <th style="text-align: center;vertical-align: middle">Terstruktur</th>
             <th style="text-align: center;vertical-align: middle">Lain-lain</th>
             <th style="text-align: center;vertical-align: middle">UTS</th>
             <th style="text-align: center;vertical-align: middle">UAS</th> -->
           </tr>
         </thead>
         <tbody>
         <?php
              $no=1;
              foreach ($db->query("select k.use_rule, k.nilai_huruf, k.id_krs_detail, m.nim,m.nama,k.mandiri,
                                    k.terstruktur,k.lain_lain,k.uts,k.uas,k.presensi from krs_detail k 
                                    join mahasiswa m on m.nim=k.nim
                                    where k.id_kelas='$id_kelas' and k.batal='0'") as $k) {
              $checked="";
              if ($k->use_rule=='1') {
                $checked="checked";
              }
              echo "<tr>
                      <td>$no</td>
                      <td>$k->nim</td>
                      <td>$k->nama <input type='hidden' name='id_krs_detail-$k->id_krs_detail' value='$k->id_krs_detail'></td> ";
                      $qqs=$db->query("select * from kelas_penilaian kp join komponen_nilai n 
                                           on n.id=kp.id_komponen where kp.id_kelas='$id_kelas'");
                      if ($qqs->rowCount()>0) {
                            foreach ($qqs as $kk) {
                              $nilai="";
                              $ada_komponen = $db->query("select * from krs_penilaian where id_krs_detail='$k->id_krs_detail' 
                               and id_komponen='$kk->id_komponen' ")->rowCount();
                              if ($ada_komponen==0) {
                               $db->insert("krs_penilaian" ,array('id_krs_detail' => $k->id_krs_detail ,
                                'id_komponen'   => $kk->id_komponen,
                                'date_created'  => date("Y-m-d H:i:s")));
                             }else{
                              foreach ($db->query("select * from krs_penilaian where id_krs_detail='$k->id_krs_detail' 
                               and id_komponen='$kk->id_komponen' ") as $kn) {
                                $nilai = $kn->nilai_angka;
                            }
                          }
                          echo "<td style='text-align:center'><input type='number' style='width:70px;text-align:center' name='komponen-$k->id_krs_detail-$kk->id_komponen' id=komponen-$k->id_krs_detail-$kk->id_komponen' value='$nilai'></td>";
                        } 
                      }else{
                        //for ($i=0; $i < $jml_komponen; $i++) { 
                         echo "<td></td>";
                        //}
                      }
                                          
                      echo "<td style='text-align:center'>
                      <select name='nilai_huruf-$k->id_krs_detail'>
                      <option value=''>-Nilai Huruf-</option>";
                      foreach ($db->query("select * from nilai_ref") as $kn) {
                        if ($kn->nilai_huruf==$k->nilai_huruf) {
                          echo "<option value='$kn->bobot-$kn->nilai_huruf' selected>$kn->nilai_huruf</option>";
                        }else{
                          echo "<option value='$kn->bobot-$kn->nilai_huruf'>$kn->nilai_huruf</option>";
                        }
                        
                      }
                echo"</select></td>
                     <td style='text-align:center'><input type='checkbox' name='rule_komponen-$k->id_krs_detail' value='1' style='width:20px;height:20px' $checked></td>
                    </tr>";
             $no++;
          }
         ?>
         </tbody>

        </table>
        <div class="alert alert-info"><strong>Informasi</strong><br>
      Jika Opsi Gunakan Rule Komponen di Centang, maka yang akan di hitung adalah berdasarkan dari setiap komponen nilai,<br>
      Jika TIDAK di centang maka komponen tidak akan dihitung, yang dihitung nilai hasil akhir</div><br>
        <input type="hidden" name="id_kelas" value="<?= $id_kelas ?>">
        <a onclick="window.history.go(-1); return false;" style="cursor: pointer;" class="btn btn-default "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>
        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan Nilai</button>
           
       </form>
     </div>

   </div>
  </div>
</div>
</section>
<script type="text/javascript">
    $("#form_input_nilai").submit(function(){
      $('#loadnya').show();
        $.ajax({
            type: 'POST',
            url : '<?= base_url() ?>dashboard/modul/nilai/nilai_action.php?act=input_nilai',
            data: $("#form_input_nilai").serialize(),
            success: function(result) {
               $('#loadnya').hide();
               $('.notif_top_up').fadeIn(1000);
                $(".notif_top_up").fadeOut(1000,function(){
                          // window.history.back();
                        });
            },
            //async:false
        });
    return false;
  });
</script>