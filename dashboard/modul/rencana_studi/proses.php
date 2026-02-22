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
          
        </form>
        <form method="POST" action="<?= base_url() ?>dashboard/modul/nilai/nilai_action.php?act=input_nilai" id="form_input_nilai" >
        <input type="hidden" name="jur" value="<?= en($jur) ?>">
        <table  class="table table-bordered table-striped">
         
         <thead>
           <tr>
             <th rowspan="2" style="text-align: center;vertical-align: middle">No</th>
             <th rowspan="2" style="text-align: center;vertical-align: middle">NIM</th>
             <th rowspan="2" style="text-align: center;vertical-align: middle">Nama</th>
             <th colspan="2" style="text-align: center;vertical-align: middle">Aksi</th>
          
           </tr>
           
         </thead>
         <tbody>
         <?php
              $no=1;
              foreach ($db->query("select k.use_rule, k.nilai_huruf, k.id_krs_detail,kr.krs_id, m.nim,m.nama,k.mandiri,
                                    k.terstruktur,k.lain_lain,k.uts,k.uas,k.presensi from krs_detail k 
                                    join krs kr on kr.krs_id=k.id_krs
                                    join mahasiswa m on m.nim=kr.mhs_id
                                    where k.id_kelas='$id_kelas' and k.batal='0'") as $k) {
             
              echo "<tr>
                      <td>$no</td>
                      <td>$k->nim</td>
                      <td>$k->nama <input type='hidden' name='id_krs_detail-$k->id_krs_detail' value='$k->id_krs_detail'></td> ";
                      
                      echo "<td style='text-align:center'>
                      <select name='nilai_huruf-$k->id_krs_detail'>
                      <option value=''>-Nilai Huruf-</option>";
                      foreach ($db->query("select * from nilai_ref") as $kn) {
                        if ($kn->nilai_huruf==$k->nilai_huruf) {
                          echo "<option value='$kn->bobot-$kn->nilai_huruf' selected>Setujui</option>";
                        }else{
                          echo "<option value='$kn->bobot-$kn->nilai_huruf'>$kn->Batal</option>";
                        }
                        
                      }
                echo"</select></td>
                     
                    </tr>";
             $no++;
          }
         ?>
         </tbody>

        </table>
       <br>
        <input type="hidden" name="id_kelas" value="<?= $id_kelas ?>">
        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan Nilai</button>
           <a onclick="window.history.go(-1); return false;" style="cursor: pointer;" class="btn btn-success "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>
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
                           window.history.back();
                        });
            },
            //async:false
        });
    return false;
  });
</script>