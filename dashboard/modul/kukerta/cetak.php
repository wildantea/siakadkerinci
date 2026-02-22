<?php
include "../../inc/config.php";
    $id_lokasi = de($_GET['id']);
     $data_edit = $db->fetch_single_row("v_dpl","id_lokasi",$id_lokasi); 
         ?>
<style type="text/css">
  table {
  border-collapse: collapse;
  font-size: 9px;
  padding: 5px;
}
</style>  
<script type="text/javascript">
  window.print();
</script>
<section class="content">
  <div class="row" style="display: block;width: 100%">
    <div style="width: 100px;float: left;">
      <img src="<?=base_url();?>upload/logo/<?=$db->fetch_single_row('identitas_logo','id_logo',1)->logo;?>" width="80" >
    </div>
    <div>
       
      <table style="font-size: 14px">
        <tr>
       <td colspan="2" style="font-size: 20px;text-align: center;border-bottom: 1px solid black">Rekap Nilai</td>
       
     </tr>
     <tr style="margin-top: 10px">
       <td style="width: 150px">Periode Kukerta</td>
       <td>: <?= $data_edit->nama_periode ?></td>
     </tr>
     <tr style="margin-top: 10px">
       <td style="width: 150px">Lokasi Kukerta</td>
       <td>: <?= $data_edit->nama_lokasi ?></td>
     </tr>
      <tr>
       <td>DPL 1</td>
       <td>: <?= $data_edit->nm_dpl1 ?></td>
     </tr>
     <tr>
       <td>DPL 2</td>
       <td>: <?= $data_edit->nm_dpl2 ?></td>
     </tr>
   
     </table>
    </div>
  </div> 
  <div style="display: block;margin-top: 40px">
        
      



        <input type="hidden" name="jur" value="<?= en($jur) ?>">
        <table  class="table table-bordered table-striped" border="1" style="width: 85%">
         
         <thead>
           <tr>
             <th style="text-align: center;vertical-align: middle;width: 13px">No</th>
             <th style="text-align: center;vertical-align: middle;width: 50px">NIM</th>
             <th style="text-align: center;vertical-align: middle">Nama</th>
             <th style="text-align: center;vertical-align: middle">Program Studi</th>
             <th style="text-align: center;vertical-align: middle">Nilai Angka</th>
             <th style="text-align: center;vertical-align: middle">Nilai Huruf</th>
           </tr>
         </thead>
         <tbody>
         <?php
              $no=1;
              foreach ($db->query("select  k.*,dk.nilai_huruf,dk.bobot,dk.nilai_angka,m.nama,m.mulai_smt,m.jk,m.mulai_smt,m.mulai_smt as angkatan,m.jur_kode,j.nama_jur,dk.id_krs_detail,dk.nilai_angka,dk.nilai_huruf from kkn k join mahasiswa m on m.nim=k.nim
                      join jurusan j on j.kode_jur=m.jur_kode
                      left join krs_detail dk on (dk.nim=k.nim and dk.id_kelas='1' ) where  id_lokasi='$data_edit->id_lokasi' and id_priode='$data_edit->id_priode' order by m.nama asc") as $k) {
              echo "<tr>
                      <td>$no</td>
                      <td>$k->nim</td>
                      <td>".ucwords(strtolower($k->nama))."</td> ";
                                          
                      echo "<td style='text-align:center'>$k->nama_jur</td>
                      <td style='text-align:center'>$k->nilai_angka</td>
                      <td style='text-align:center'>$k->nilai_huruf</td>
                    </tr>";
             $no++;
          }
         ?>
         </tbody>

        </table>
       
      
     </div>


</section>

          
