<?php
include "../../inc/config.php";
    $id_kelas = de($_GET['id_kelas']);
      $jur      = de($_GET['jur']);
      $sem      = de($_GET['sem']);
      $jml_komponen =0;
     // echo "$id_kelas";
     // print_r($_SESSION);
      $pengampu  = "";
      $jur = "";
      // echo "select k.kls_nama,m.nama_mk,j.kode_jur from kelas k 
      //                     join matkul m on k.id_matkul=m.id_matkul
      //                     join kurikulum ku on ku.kur_id=m.kur_id
      //                     join jurusan j on j.kode_jur=ku.kode_jur
      //                     where k.kelas_id='$id_kelas'";
      foreach ($db->query("select m.kode_mk, k.kls_nama,m.nama_mk,j.kode_jur from kelas k 
                          join matkul m on k.id_matkul=m.id_matkul
                          join kurikulum ku on ku.kur_id=m.kur_id
                          join jurusan j on j.kode_jur=ku.kode_jur
                          where k.kelas_id='$id_kelas'") as $data_kelas) {
        $jur = $data_kelas->kode_jur;
        foreach ($db->query("select ds.nip,ds.nama_dosen,ds.gelar_depan,ds.gelar_belakang from dosen_kelas d join dosen ds on d.id_dosen=ds.nip where d.id_kelas='$id_kelas'") as $data_dosen) {
           $pengampu.="-&nbsp;&nbsp;$data_dosen->gelar_depan $data_dosen->nama_dosen, $data_dosen->gelar_belakang<br>";
        }
            foreach ($db->query("select k.*,kp.id as id_kls_komponen,kp.id_kelas,kp.id_komponen,kp.nilai
                     from komponen_nilai k join kelas_penilaian kp on k.id=kp.id_komponen
                     where kp.id_kelas='$id_kelas' ") as $k) {
                     
                       $jml_komponen++;
                       // $nilai_komponen= $nilai_komponen + $k->nilai;
                    }
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
       <td style="width: 150px">Nama Kelas</td>
       <td>: <?= $data_kelas->kls_nama ?></td>
     </tr>
      <tr>
       <td>Kode Matkul</td>
       <td>: <?= $data_kelas->kode_mk ?></td>
     </tr>
     <tr>
       <td>Mata Kuliah</td>
       <td>: <?= $data_kelas->nama_mk ?></td>
     </tr>
     <tr>
       <td>Dosen Pengampu</td>
       <td>: <?= $pengampu ?></td>
     </tr>
     </table>
    </div>
  </div> 
  <div style="display: block;margin-top: 40px">
        
      



        <form method="POST" action="<?= base_url() ?>dashboard/modul/nilai/nilai_action.php?act=input_nilai" id="form_input_nilai" >
        <input type="hidden" name="jur" value="<?= en($jur) ?>">
        <table  class="table table-bordered table-striped" border="1" style="width: 85%">
         
         <thead>
           <tr>
             <th rowspan="2" style="text-align: center;vertical-align: middle;width: 13px">No</th>
             <th rowspan="2" style="text-align: center;vertical-align: middle;width: 50px">NIM</th>
             <th rowspan="2" style="text-align: center;vertical-align: middle">Nama</th>
             <th colspan="<?= $jml_komponen ?>" style="text-align: center;vertical-align: middle">Komponen Penilaian</th>
             <th rowspan="2" style="text-align: center;vertical-align: middle">Nilai Akhir</th>
             <th rowspan="2" style="text-align: center;vertical-align: middle">Nilai Huruf</th>
             <th style="text-align: center;vertical-align: middle;width: 100px;display: none" rowspan="2" >Rule Komponen
             <input type='checkbox' id="cek_semua"  style='width:20px;height:20px' ></th>
           </tr>
           <tr>
             <?php
                      foreach ($db->query("select *,n.id as id_komponen from kelas_penilaian kp join komponen_nilai n 
                                           on n.id=kp.id_komponen where kp.id_kelas='$id_kelas'") 
                              as $k) {
                        echo " <th data-value='$k->nilai' id='komponen_$k->id_komponen' style='text-align: center;vertical-align: middle'>$k->nama_komponen ($k->nilai %)</th>";
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
              foreach ($db->query("select k.use_rule, k.nilai_angka, k.nilai_huruf, k.id_krs_detail, m.nim,m.nama,k.mandiri,
                                    k.terstruktur,k.lain_lain,k.uts,k.uas,k.presensi from krs_detail k 
                                    join mahasiswa m on m.nim=k.nim
                                    where k.id_kelas='$id_kelas' and k.batal='0' and disetujui='1' order by m.nama asc") as $k) {
              $checked="";
              if ($k->use_rule=='1') { 
                $checked="checked";
              }
              $data_akm['mhs_nim'] = $k->nim;
              $data_akm['sem_id']  = get_sem_aktif();
              buat_akm($data_akm); 
              echo "<tr>
                      <td>$no</td>
                      <td>$k->nim</td>
                      <td>".ucwords(strtolower($k->nama))."</td> ";
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
                          echo "<td style='text-align:center'>$nilai</td>";
                        } 
                      }else{
                        //for ($i=0; $i < $jml_komponen; $i++) { 
                         echo "<td></td>";
                        //}
                      }
                                          
                      echo "<td style='text-align:center'>$k->nilai_angka</td>
                      <td style='text-align:center'>$k->nilai_huruf</td>
                     <td style='text-align:center;display:none'><input type='checkbox' class='det_rule' name='rule_komponen-$k->id_krs_detail' value='1' style='width:20px;height:20px' $checked></td>
                    </tr>";
             $no++;
          }
         ?>
         </tbody>

        </table>
       
       
           
       </form>
     </div>


</section>
         <?php
      }
?>

<!-- Main content -->

          
