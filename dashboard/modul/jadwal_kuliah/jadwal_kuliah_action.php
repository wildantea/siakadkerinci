<?php
session_start();
include "../../inc/config.php";
session_check();
switch ($_GET["act"]) {
  case "show_jadwal":
  ?>
           <div class="form-group">
          <form name="form" action="<?= base_admin().'modul/jadwal_kuliah/' ?>cetak_jadwal_dosen.php" method="post" target="_blank">

            <button class="btn btn-success" name="laporan" type="submit"><i class="fa fa-print" ></i> Cetak Jadwal</button>

            <input name='nip' type='hidden' value='<?= $_POST['nip'] ?>'>
           <!--  <input name='k' type='hidden' value='<?= $kk->krs_id ?>'> -->
            <input name='sem' type='hidden' value='<?= $_POST['id_sem'] ?>'>

          </form>


        </div>


        <div class="box-body table-responsive">

          <table  class="table table-bordered table-striped">
            <thead>
              <tr>
                <th style="width:25px" class='center' valign="center" rowspan='2'>No</th>
                <th class='center' valign="center" rowspan='2'>Mata Kuliah</th>
                <th class='center' valign="center" rowspan='2'>Jurusan</th>
                <th class='center' valign="center" rowspan='2'>SKS</th>
                 <th class='center' valign="center" rowspan='2'>Semester</th>
                <th class='center' valign="center" rowspan='2'>Kelas/Ruang</th>
                <th class='center' valign="center" colspan='7'>Hari</th>
              </tr>
              <tr>
                <th>Senin</th>
                <th>Selasa</th>
                <th>Rabu</th>
                <th>Kamis</th>
                <th>Jumat</th>
                <th>Sabtu</th>
                <th>Minggu</th>
              </tr>
            </thead>
            <tbody>
            <?php            
             $dtb=$db->query("select k.`id_matkul`, k.kls_nama, dk.id_dosen,ku.*,ds.`nama_dosen`,rf.`nm_ruang`,
                              m.`kode_mk`,m.`nama_mk`,ju.`nama_jur`,m.`bobot_minimal_lulus`,m.id_matkul,m.semester  from  jadwal_kuliah ku 
                              left join kelas k on ku.kelas_id=k.`kelas_id`
                              left join dosen_kelas dk on dk.id_kelas=k.kelas_id
                              left join dosen ds on ds.nip=dk.id_dosen
                              left join ruang_ref rf on rf.`ruang_id`=ku.`ruang_id`
                              left join matkul m on m.`id_matkul`=k.id_matkul
                              left join kurikulum kur on kur.`kur_id`=m.`kur_id` 
                              left join jurusan ju on ju.`kode_jur`=kur.`kode_jur`
                              where k.sem_id='".$_POST['id_sem']."' and dk.id_dosen='".$_POST['nip']."'
                              group by k.kelas_id
                              ");                        
              $i=1;
              $total = 0;
              foreach ($dtb as $isi) {
                ?><tr id="line_<?=$isi->kode_mk;?>">
                  <td align="center"><?=$i;?></td>

                  <td><?=$isi->kode_mk." - ".$isi->nama_mk; ?></td>
                  <td><?= $isi->nama_jur ?></td>  
                  <td><?= get_jumlah_sks_matkul($isi->id_matkul)?></td>
                  <td><?= $isi->semester ?></td>
                  <td><?=$isi->kls_nama." / ".$isi->nm_ruang;?></td> 
                  <td><?php if(strtolower($isi->hari)=='senin') echo substr($isi->jam_mulai, 0,5)." - ".substr($isi->jam_selesai,0,5); ?></td>
                  <td><?php if(strtolower($isi->hari)=='selasa') echo substr($isi->jam_mulai, 0,5)." - ".substr($isi->jam_selesai,0,5); ?></td>
                  <td><?php if(strtolower($isi->hari)=='rabu') echo substr($isi->jam_mulai, 0,5)." - ".substr($isi->jam_selesai,0,5); ?></td>
                  <td><?php if(strtolower($isi->hari)=='kamis') echo substr($isi->jam_mulai, 0,5)." - ".substr($isi->jam_selesai,0,5); ?></td>
                  <td><?php if(strtolower($isi->hari)=='jumat') echo substr($isi->jam_mulai, 0,5)." - ".substr($isi->jam_selesai,0,5); ?></td>
                  <td><?php if(strtolower($isi->hari)=='sabtu') echo substr($isi->jam_mulai, 0,5)." - ".substr($isi->jam_selesai,0,5); ?></td>
                  <td><?php if(strtolower($isi->hari)=='minggu') echo substr($isi->jam_mulai, 0,5)." - ".substr($isi->jam_selesai,0,5); ?></td>

                </tr>
                <?php
                $total = $total + get_jumlah_sks_matkul($isi->id_matkul);
                $i++;
              }
              ?>
                <tr id="line_<?=$isi->kode_mk;?>">
                  <td align="center"></td>

                  <td></td>
                  <td></td>  
                  <td><?= $total ?></td>
                  <td colspan="7"></td>

                </tr>
            </tbody>

          </table>
        </div>
        <script type="text/javascript">
          $(document).ready(function() {
              $('#dtb_manual').DataTable();
          } );
        </script>
  <?php
  break;

  case "get_ruang":
      $q= $db->query("select r.kapasitas, r.ruang_id, r.nm_ruang,f.nama_resmi from ruang_ref r join gedung_ref g 
                                                        on r.gedung_id=g.gedung_id
                                                        join fakultas f on f.kode_fak=g.kode_fak
                                                        where g.kode_fak='".$_POST['kode_fak']."' ");
              foreach ($q as $k) {
                                         echo "
                                            <tr>
                                            <th><input type='checkbox' name='ruangan[]' value='$k->ruang_id===$k->nm_ruang===$k->nama_resmi' class='minimal'></th>                                            
                                            <th>$k->nm_ruang</th>
                                            <th>$k->kapasitas</th>    
                                            <th>$k->nama_resmi</th>                                       
                  </tr>
                                             ";
     }
 
    break;
  case "reset_jadwal":
    $db->query("update jadwal_kuliah ku,
          (
          select  kl.kelas_id from jadwal_kuliah k join kelas kl on k.kelas_id=kl.kelas_id
          join matkul m on m.id_matkul=kl.id_matkul
          join kurikulum kr on kr.kur_id=m.kur_id
          join jurusan j on j.kode_jur=kr.kode_jur
          where j.kode_jur='".de($_POST['jur'])."' and kl.sem_id='".de($_POST['sem'])."') jd
           set hari=NULL, ruang_id=NULL,jam_mulai=NULL, jam_selesai=NULL
          where ku.kelas_id=jd.kelas_id");
    break;
  case "gen_jadwal":
   /* echo "<pre>";
    echo "sesi";
    print_r($_POST['sesi']);
    echo "hari";
    print_r($_POST['hari']);
    echo "ruang";
    print_r($_POST['ruangan']); */
    $break = false;
    $jml=0;
     $kelasSukses = "<table class='table'>
                      <thead>
                        <tr>
                         <th>No</th>
                         <th>Kode MK</th>
                         <th>Mata Kuliah</th>
                         <th>Kelas</th>
                         <th>Dosen Pengampu</th>
                         <th>Hari</th>
                         <th>Jam</th>
                         <th>Ruang</th>
                        </tr>
                      </thead>
                      <tbody>";
    $no=1;
    foreach ($_POST['hari'] as $k => $v) { //pengulangan hari
      //echo "$v<br>";
      if ($break==false) {
       foreach ($_POST['ruangan'] as $kk => $vv) { //pengulangan ruangan
         if ($break==false) {
           foreach ($_POST['sesi'] as $s => $ss) { //pengulangan sesi
             if ($break==false) {
               // echo de($_GET['j'])."<br>".de($_GET['s']);
                 $q=$db->query("select jd.jadwal_id, d.nama_dosen, d.gelar_depan,d.gelar_belakang, 
                      r.nm_ruang, jd.jam_mulai,jd.jam_selesai,jd.hari, matkul.kode_mk, j.nama_jur, 
                      kelas.kls_nama,kelas.kode_paralel,
                      kelas.peserta_max,kelas.peserta_min,matkul.nama_mk,
                      kelas.kelas_id from kelas inner join matkul on kelas.id_matkul=matkul.id_matkul
                      join kurikulum k on k.kur_id=matkul.kur_id
                      join jurusan j on j.kode_jur=k.kode_jur
                     join jadwal_kuliah jd on jd.kelas_id=kelas.kelas_id  
                     left join ruang_ref r on r.ruang_id=jd.ruang_id
                     join dosen_kelas dk on dk.id_kelas=kelas.kelas_id
                     join dosen d on d.nip=dk.id_dosen 
                            where j.kode_jur='".de($_GET['j'])."' and kelas.sem_id='".de($_GET['s'])."'  
                            and jd.hari is null and jd.jam_mulai is null
                            and jd.jam_selesai is null and jd.ruang_id is null order by rand() limit 1  ");
                // echo $q->rowCount();
                 if ($q->rowCount()>0) {
                  // echo "string";
                  foreach ($q as $k) {
                    $jam = explode("===", $ss);
                    $ru = explode("===", $vv);
                    $data_jadwal = array('hari' => strtolower($v) , 
                                         'ruang_id'  => $ru[0],
                                         'jam_mulai' => $jam[0],
                                         'jam_selesai' => $jam[1] );
                  //  print_r($data_jadwal);
                   
                    $up = $db->update("jadwal_kuliah",$data_jadwal,"jadwal_id",$k->jadwal_id);
                     
                   if ($up==true) {
                      $jml++;
                        
                         $kelasSukses.="<tr>
                                        <td>$no</td>
                                        <td>$k->kode_mk</td>
                                        <td>$k->nama_mk</td>
                                        <td>$k->kls_nama</td>
                                        <td>$k->nama_dosen</td>
                                        <td>".strtolower($v)."</td>
                                        <td>".$jam[0]." - ".$jam[1]."</td>
                                        <td>".$ru[1]."</td>
                                       </tr>";
                                       $no++;
                     
                   }
                  }
                 }else{
                 // echo "string";
                   $break=true;
                   break;
                 }
             }else{
              break;
             }
         
           }
         }else{
           break;
         }
         
       }
      }else{
        break;
      }
       
    }
     $kelasSukses.="</tbody></table>";
      $q=$db->query("select jd.jadwal_id, d.nama_dosen, d.gelar_depan,d.gelar_belakang, 
                      r.nm_ruang, jd.jam_mulai,jd.jam_selesai,jd.hari, matkul.kode_mk, j.nama_jur, 
                      kelas.kls_nama,kelas.kode_paralel,
                      kelas.peserta_max,kelas.peserta_min,matkul.nama_mk,
                      kelas.kelas_id from kelas inner join matkul on kelas.id_matkul=matkul.id_matkul
                      join kurikulum k on k.kur_id=matkul.kur_id
                      join jurusan j on j.kode_jur=k.kode_jur
                     join jadwal_kuliah jd on jd.kelas_id=kelas.kelas_id  
                     left join ruang_ref r on r.ruang_id=jd.ruang_id
                     join dosen_kelas dk on dk.id_kelas=kelas.kelas_id
                     join dosen d on d.nip=dk.id_dosen 
                            where j.kode_jur='".de($_GET['j'])."' and kelas.sem_id='".de($_GET['s'])."'  
                            and jd.hari is null and jd.jam_mulai is null
                            and jd.jam_selesai is null and jd.ruang_id is null");
      $gagal = $q->rowCount();
   // echo "good";
    echo '<div  class="alert alert-success" role="alert" style="font-size:15px"> '.$jml.' Kelas ! Berhasil dibuatkan jadwal  <a  data-toggle="collapse" href="#kelasSukses" aria-expanded="false" aria-controls="collapseExample">
       Lihat Kelas Sukses
      </a><div class="collapse" id="kelasSukses">
        <div class="well" style="background:#00a65a">
          '.$kelasSukses.'
        </div>
      </div></div></div>';
    if ($gagal>0) {
      $kelasGagal = "<table class='table'>
                      <thead>
                        <tr>
                         <th>No</th>
                         <th>Kode MK</th>
                         <th>Mata Kuliah</th>
                         <th>Kelas</th>
                         <th>Dosen Pengampu</th>
                        </tr>
                      </thead>
                      <tbody>";
      $no=1;
     foreach ($q as $k) {
       $kelasGagal.="<tr>
                      <td>$no</td>
                      <td>$k->kode_mk</td>
                      <td>$k->nama_mk</td>
                      <td>$k->kls_nama</td>
                      <td>$k->nama_dosen</td>
                     </tr>";
                     $no++;
      }
      $kelasGagal.="</tbody></table>";
     echo ' <div class="alert alert-warning" role="alert" style="font-size:15px"> '.$gagal.' Kelas  Belum tergenerate. 
     <a  data-toggle="collapse" href="#kelasGagal" aria-expanded="false" aria-controls="collapseExample">
       Lihat Kelas yang belum terjadwalkan
      </a>
      <div class="collapse" id="kelasGagal">
        <div class="well" style="background:#88130d">
          '.$kelasGagal.'
        </div>
      </div></div>';





      
    }
    break;
  case "in":
    
  
  
  
  $data = array(
      "kelas_id" => $_POST["kelas_id"],
      "hari" => $_POST["hari"],
      "ruang_id" => $_POST["ruang_id"],
      "jam_mulai" => $_POST["jam_mulai"],
      "jam_selesai" => $_POST["jam_selesai"],
  );
  
  
  
   
    $in = $db->insert("jadwal_kuliah",$data);
    
    
    if ($in=true) {
      echo "good";
    } else {
      return false;
    }
    break;
  case "delete":
    
    
    
    $db->delete("jadwal_kuliah","jadwal_id",$_GET["id"]);
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("jadwal_kuliah","jadwal_id",$id);
         }
    }
    break;
  case "up":
    
   $data = array(
      "kelas_id" => $_POST["kelas_id"],
      "hari" => $_POST["hari"],
      "ruang_id" => $_POST["ruang_id"],
      "jam_mulai" => $_POST["jam_mulai"],
      "jam_selesai" => $_POST["jam_selesai"],
   );
   
   
   

    
    
    $up = $db->update("jadwal_kuliah",$data,"jadwal_id",$_POST["id"]);
    
    if ($up=true) {
      echo "good";
    } else {
      return false;
    }
    break;
  default:
    # code...
    break;
}

?>