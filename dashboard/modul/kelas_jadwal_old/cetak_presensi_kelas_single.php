<?php
session_start();
include "../../inc/config.php";

session_check();

?>
<html>
<head>
  <title>Cetak Presensi Kelas</title>
    <link rel="stylesheet" type="text/css" href="<?=base_admin();?>assets/dist/css/cetak/table.css">
  <link rel="stylesheet" type="text/css" href="<?=base_admin();?>assets/dist/css/cetak/paper.css">
  <link rel="stylesheet" type="text/css" href="<?=base_admin();?>assets/dist/css/cetak/table.css">
  <style type="text/css">
@page { size: A4 landscape }


  </style>
</head>
<?php


$kel = $db->fetch_custom_single("SELECT ds.`nip`,ds.`gelar_depan`,ds.`nama_dosen`,ds.`gelar_belakang`,rf.`nm_ruang`,jk.`hari`,jk.`jam_mulai`,jk.`jam_selesai`, matkul.kode_mk, f.nama_resmi, j.nama_jur,
jj.`jenjang`,kelas.kls_nama,kelas.sem_id, matkul.`sks_tm`, kelas.peserta_max,kelas.peserta_min,matkul.nama_mk, kelas.kelas_id FROM kelas 
INNER JOIN matkul ON kelas.id_matkul=matkul.id_matkul 
JOIN kurikulum k ON k.kur_id=matkul.kur_id 
left JOIN jadwal_kuliah jk ON jk.`kelas_id`= kelas.`kelas_id` 
JOIN jurusan j ON j.kode_jur=k.kode_jur 
JOIN fakultas f ON f.`kode_fak`=j.`fak_kode` 
JOIN jenjang_pendidikan jj ON jj.`id_jenjang`=j.`id_jenjang` 
left JOIN ruang_ref rf ON rf.`ruang_id`=jk.`ruang_id` 
left JOIN dosen_kelas dk ON dk.`id_kelas`=kelas.`kelas_id` 
left JOIN dosen ds ON ds.`nip`=dk.`id_dosen`
WHERE kelas.kelas_id=".$_POST['kelas_id']."  ORDER BY nama_dosen ASC");

        $matkul = $kel->nama_mk;
  $kls = $kel->kls_nama;
  $jur = $kel->nama_jur;
  $sem = $kel->sem_id;


$i=1;

$get_sem_first = $db->fetch_single_row("view_nama_kelas","kelas_id",$_POST['kelas_id']);
$periode = $db->fetch_custom_single("SELECT * from view_semester
WHERE id_semester='".$get_sem_first->sem_id."'");


$header_identity = $db->fetch_single_row("identitas","id_identitas",1);
$alamat_identity = $db->fetch_single_row("identitas","id_identitas",2);
$identity_kota = $db->fetch_single_row("identitas","id_identitas",5);


?>
<body class="A4 landscape" onload="window.print()">
   <?php



    $count_peserta = $db->fetch_custom_single("SELECT COUNT(tdk.id_krs_detail) as jml  from krs_detail tdk WHERE disetujui=1 AND id_kelas=?",array('kelas_id' => $_POST['kelas_id']));

              $jml_peserta = $count_peserta->jml;


                $limit=15;
                if ($jml_peserta>=10 && $jml_peserta<=15) {
                  $jml_page = 2;
                } else {
                  $jml_page = ceil($jml_peserta/15);
                }
                 //$jml_page = ceil($jml_peserta/15);
                

               $no=1;
               $offset=0;

               $ada_header=false;
               $last_page = false;

              for ($i=1; $i <=$jml_page ; $i++) {

              //if first page
              if ($i==1) {
                $limit=15;
                $ada_header=true;
              }//if next page
              elseif ($i>1 && $i<$jml_page) {
                $limit=15;
                $ada_header=true;
              } else {
                $last_page = true;
                $ada_header=true;
              }

              $row_mahasiswa=$db->query("SELECT m.nim, m.nama FROM krs_detail k
INNER JOIN mahasiswa m USING(nim)
WHERE disetujui=1 AND id_kelas=? order by nama asc limit $offset,$limit",array('kelas_id' => $_POST['kelas_id']));

              $offset=$offset+$limit;

              if ($ada_header==true && $row_mahasiswa->rowCount()<10) {
                $last_page = true;
              }

              if ($last_page==true && $row_mahasiswa->rowCount()>10) {
                $jml_page++;
                $last_page = false;
              }

?>    

 <div class="page-break">
        <div class="sheet padding-10mm">
          

      <?php
       if ($ada_header) {
       ?>
        <table class="tabel-info" width="100%">
          <tr>
           <td rowspan = "6" width ="35%" valign="top">
             <table style="width:300px">
              <tr>
               <td>
                <img src="<?=base_url();?>upload/logo/<?=$db->fetch_single_row('identitas_logo','id_logo',1)->logo;?>" width="100" height="100">
              </td>
              <td>
                <h4><?= $header_identity->isi ?></h4>
                 <hr />
                <h4><?= $kel->nama_resmi ?></h4>
               
              </td>
            </tr>
          </table>


        </td>
        <td nowrap ="nowrap" colspan="6"><u><b>DAFTAR PRESENSI KULIAH</b>&nbsp;</u></td>
      </tr>
        <tr>
               <td nowrap ="nowrap" >Mata Kuliah</td>
               <td nowrap ="nowrap" >:</td>
               <td nowrap ="nowrap" ><?= $matkul ?></td>
               <td nowrap ="nowrap" >Periode</td>
               <td nowrap ="nowrap" >:</td>
               <td nowrap ="nowrap" ><?= $periode->tahun_akademik ?></td>
            </tr>
            <tr>
               <td nowrap ="nowrap" >Kelas</td>
               <td nowrap ="nowrap" >:</td>
               <td nowrap ="nowrap" ><?= $kls ?></td>
               <td nowrap ="nowrap" >Program Studi</td>
               <td nowrap ="nowrap" >:</td>
               <td nowrap ="nowrap" ><?= $jur ?> - <?= $kel->jenjang ?> Reguler</td>
            </tr>
            <tr>
               
               <td nowrap ="nowrap" >Bobot SKS</td>
               <td nowrap ="nowrap" >:</td>
               <td nowrap ="nowrap" ><?= $kel->sks_tm ?>&nbsp;sks</td>
               <td nowrap ="nowrap" >Ruang</td>
               <td nowrap ="nowrap" >:</td>
               <td nowrap ="nowrap" ><?= $kel->nm_ruang ?></td>
            </tr>
            <tr>
               <td nowrap ="nowrap"  valign="top">Hari / Waktu</td>
               <td nowrap ="nowrap"  valign="top">:</td>
               <td nowrap ="nowrap"  valign="top"><?= ucwords($kel->hari) ?> / <?= $kel->jam_mulai ?>-<?= $kel->jam_selesai ?></td>
               <td nowrap ="nowrap" ></td>
               <td nowrap ="nowrap" ></td>
               <td nowrap ="nowrap" ></td>
            </tr>
            <tr>
               <td nowrap ="nowrap"  valign="top">Dosen Pengampu</td>
               <td nowrap ="nowrap"  valign="top">:</td>
               <td nowrap ="nowrap"  valign="top"><ol style="padding:0 0 0 15;"><li><?= $kel->gelar_depan ?> <?= $kel->nama_dosen ?> <?= $kel->gelar_belakang ?></li></ol></td>
               <td nowrap ="nowrap" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
               <td nowrap ="nowrap" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
               <td nowrap ="nowrap" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            </tr>
         </table>
       <?php
       }
      ?>
      <br />
         <table width="100%" class="tabel-common">
           <?php
            if ($row_mahasiswa->rowCount()>0) {
              ?>
            <tr>
               <th rowspan="3">No.</th>
               <th rowspan="3">NIM</th>
               <th rowspan="3" width="25%">Nama</th>
               <th colspan="16">Perkuliahan Ke / Tanggal</th>
            </tr>
            <tr>
               <th width="4%">1</th>
               <th width="4%">2</th>
               <th width="4%">3</th>
               <th width="4%">4</th>
               <th width="4%">5</th>
               <th width="4%">6</th>
               <th width="4%">7</th>
               <th width="4%">8</th>
               <th width="4%">9</th>
               <th width="4%">10</th>
               <th width="4%">11</th>
               <th width="4%">12</th>
               <th width="4%">13</th>
               <th width="4%">14</th>
               <th width="4%">15</th>
               <th width="4%">16</th>
            </tr>
            <tr>
               <th width="4%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
               <th width="4%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
               <th width="4%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
               <th width="4%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
               <th width="4%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
               <th width="4%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
               <th width="4%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
               <th width="4%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
               <th width="4%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
               <th width="4%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
               <th width="4%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
               <th width="4%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
               <th width="4%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
               <th width="4%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
               <th width="4%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
               <th width="4%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>


            </tr>
            <?php
                      foreach ($row_mahasiswa as $mhs) {
                     echo "
                          <tr>
                          </tr>
                          <tr>
                              <td style='text-align:center; vertical-align:middle;' height='34' >$no</td>
                              <td style='text-align:center; vertical-align:middle;' width='150'>$mhs->nim</td>
                              <td style='vertical-align:middle;' width='250'>$mhs->nama</td>
                              <td style='text-align:center'></td>
                              <td style='text-align:center'></td>
                              <td style='text-align:center'></td>
                              <td style='text-align:center'></td>
                              <td style='text-align:center'></td>
                              <td style='text-align:center'></td>
                              <td style='text-align:center'></td>
                              <td style='text-align:center'></td>
                              <td style='text-align:center'></td>
                              <td style='text-align:center'></td>
                              <td style='text-align:center'></td>
                              <td style='text-align:center'></td>
                              <td style='text-align:center'></td>
                              <td style='text-align:center'></td>
                              <td style='text-align:center'></td>
                              <td style='text-align:center'></td>

                          </tr>";
                      $no++;

                  //  echo "</div>";
              }

          }


       
        if ($last_page) {
         ?>
         <tr>
                        <th colspan="23">DOSEN PENGAMPU</th>
                     </tr>
                  <tr>
                                          <td nowrap ="nowrap" height="34" style="text-align:center; vertical-align:middle;">1</td>
                     <td style="vertical-align:middle;" width='88' colspan="2"><div class="nama">&nbsp;<?= $kel->gelar_depan ?> <?= $kel->nama_dosen ?> <?= $kel->gelar_belakang ?></div></td>
                     <td nowrap ="nowrap" >&nbsp;</td>
                     <td nowrap ="nowrap" >&nbsp;</td>
                     <td nowrap ="nowrap" >&nbsp;</td>
                     <td nowrap ="nowrap" >&nbsp;</td>
                     <td nowrap ="nowrap" >&nbsp;</td>
                     <td nowrap ="nowrap" >&nbsp;</td>
                     <td nowrap ="nowrap" >&nbsp;</td>
                     <td nowrap ="nowrap" >&nbsp;</td>
                     <td nowrap ="nowrap" >&nbsp;</td>
                     <td nowrap ="nowrap" >&nbsp;</td>
                     <td nowrap ="nowrap" >&nbsp;</td>
                     <td nowrap ="nowrap" >&nbsp;</td>
                     <td nowrap ="nowrap" >&nbsp;</td>
                     <td nowrap ="nowrap" >&nbsp;</td>
                     <td nowrap ="nowrap" >&nbsp;</td>
                     <td nowrap ="nowrap" >&nbsp;</td>

                  </tr>


            <tr valign="center">
                  <td colspan="3" height="34" style="text-align:center; vertical-align:middle;" width="100">Jumlah Mahasiswa Hadir</td>
                  <td nowrap ="nowrap" >&nbsp;</td>
                  <td nowrap ="nowrap" >&nbsp;</td>
                  <td nowrap ="nowrap" >&nbsp;</td>
                  <td nowrap ="nowrap" >&nbsp;</td>
                  <td nowrap ="nowrap" >&nbsp;</td>
                  <td nowrap ="nowrap" >&nbsp;</td>
                  <td nowrap ="nowrap" >&nbsp;</td>
                  <td nowrap ="nowrap" >&nbsp;</td>
                  <td nowrap ="nowrap" >&nbsp;</td>
                  <td nowrap ="nowrap" >&nbsp;</td>
                  <td nowrap ="nowrap" >&nbsp;</td>
                  <td nowrap ="nowrap" >&nbsp;</td>
                  <td nowrap ="nowrap" >&nbsp;</td>
                  <td nowrap ="nowrap" >&nbsp;</td>
                  <td nowrap ="nowrap" >&nbsp;</td>
                  <td nowrap ="nowrap" >&nbsp;</td>

               </tr>
             </table>
                     <table align="right" width="100%">
            <tr>
               <td width="70%" rowspan="3">&nbsp;</td>
               <br><td align="center"><?= ucfirst($identity_kota->isi) ?>, ................<br /><span id="tipe_pengesahan0">...............</span><br /><span id="jabatan0"></span></td>
            </tr>
            <tr>
               <td align="center" height="50"></td>
            </tr>
            <tr>
               <td align="center" nowrap="nowrap"><span id="nama0">........................................</span><br />------------------------------
               <br />NIP: <span id="nip0">..............................</span></td>
            </tr>
         </table> 

         <?php
         //end if last page
        }
        //end page-break and sheet
         ?>
        </table>
        <p></p><div style="text-align: right">Halaman <?=$i;?></div>
        </div></div>
    <?php
    //end loop
    }




?>

</body>
</html>
