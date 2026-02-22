<?php
session_start();
include "../../../inc/config.php";

$kode_dokumen = "";
$doc_name = $db->fetchSingleRow("tb_data_dokumen","short_name","peserta_kelas");
$has_pengesah = $db->fetchCustomSingle("select * from tb_data_jabatan_pengesah where kode_jur=? and id_dokumen=?",array('kode_jur' => $_POST['jur_filter'],'id_dokumen' => $doc_name->id_dokumen));

if ($has_pengesah) {

  $pejabat_pengesah = $has_pengesah->pejabat_pengesah;

  $kode_dokumen = $has_pengesah->kode_dokumen;

  $pejabat = json_decode($pejabat_pengesah);


  //mulai dari kiri
  $status_pengesah_0 = status_pengesah($pejabat,0);
  $status_pengesah_1 = status_pengesah($pejabat,1);
  $status_pengesah_2 = status_pengesah($pejabat,2);

  //mulai dari kiri
  $kota_1 = kota($pejabat,0);
  $kota_2 = kota($pejabat,1);
  $kota_3 = kota($pejabat,2);

  //ada tgl
  $tgl_1 = ada_tgl($pejabat,0);
  $tgl_2 = ada_tgl($pejabat,1);
  $tgl_3 = ada_tgl($pejabat,2);

  //tipe pengesah
  $tipe_pengesah_1 = tipe_pengesah($pejabat,0);
  $tipe_pengesah_2 = tipe_pengesah($pejabat,1);
  $tipe_pengesah_3 = tipe_pengesah($pejabat,2);

  //kategori pejabat
  $kategori_pejabat_1 = kategori_pejabat($pejabat,0);
  $kategori_pejabat_2 = kategori_pejabat($pejabat,1);
  $kategori_pejabat_3 = kategori_pejabat($pejabat,2);

//end has pengesah condition
}

?>
<html>
<head>
  <title>Cetak Presensi Kelas</title>
  <link rel="stylesheet" type="text/css" href="<?=base_admin();?>assets/dist/css/cetak/table.css">
  <link rel="stylesheet" type="text/css" href="<?=base_admin();?>assets/dist/css/cetak/paper.css">
  <link rel="stylesheet" type="text/css" href="<?=base_admin();?>assets/dist/css/cetak/table.css">
  <style type="text/css">
@page { size: A4 portrait }
  </style>
</head>
<?php

$periode = "";
$kur_id = "";
$sistem_kuliah = "";
$hari = "";
$matakuliah = "";



    if ($_POST['jur_filter']!='all') {
      $jur_kode = ' and view_matakuliah_kurikulum.kode_jur="'.$_POST['jur_filter'].'"';
    }
    if ($_POST['periode']!='all') {
      $periode = ' and vk.sem_id="'.$_POST['periode'].'"';
    }
    if ($_POST['kurikulum']!='all') {
      $kur_id = ' and view_matakuliah_kurikulum.kur_id="'.$_POST['kurikulum'].'"';
    }
    if ($_POST['matakuliah']!='all') {
      $matakuliah = ' and vk.id_matkul="'.$_POST['matakuliah'].'"';
    }
    if ($_POST['sistem_kuliah']!='all') {
      $sistem_kuliah = ' and id_jenis_kelas="'.$_POST['sistem_kuliah'].'"';
    }
    if ($_POST['hari']!='all') {
      $hari = ' and id_hari="'.$_POST['hari'].'"';
    }

$header_attributes = $db->query("SELECT vk.kode_jur,vk.kls_nama,vk.total_sks as sks,vk.kode_mk,vk.nama_mk,vjk.nama_hari,vjk.jam_mulai,vjk.jam_selesai,nm_ruang,vk.nama_jurusan,view_semester.tahun_akademik,vk.kelas_id,jml_pertemuan,
(select nip_dosen from tb_data_kelas_dosen where (penanggung_jawab='Y' or dosen_ke='1') and tb_data_kelas_dosen.kelas_id=vjk.kelas_id and id_jadwal=vjk.jadwal_id limit 1) as nip_pengajar,
(select group_concat(nama_gelar separator '#')
 from view_nama_gelar_dosen inner join tb_data_kelas_dosen on view_nama_gelar_dosen.nip=tb_data_kelas_dosen.nip_dosen where tb_data_kelas_dosen.kelas_id=vjk.kelas_id and id_jadwal=vjk.jadwal_id order by dosen_ke asc) as nama_dosen
 from view_kelas vk
INNER JOIN view_semester on vk.sem_id=view_semester.id_semester
inner join view_matakuliah_kurikulum using(id_matkul)
LEFT JOIN view_jadwal_kelas vjk using(kelas_id)
where jadwal_id is not null and (select nip_dosen from tb_data_kelas_dosen where (penanggung_jawab='Y' or dosen_ke='1') and tb_data_kelas_dosen.kelas_id=vjk.kelas_id and id_jadwal=vjk.jadwal_id limit 1) is not null
$jur_kode $periode $kur_id $matakuliah $sistem_kuliah $hari
");

?>
<body class="A4 portrait" >
   <?php
   //onload="window.print()"
foreach ($header_attributes as $header_attribute) {


    $count_peserta = $db->fetchCustomSingle("SELECT COUNT(tdk.krs_detail_id) as jml  from tb_data_kelas_krs_detail tdk WHERE disetujui=1 AND kelas_id=?",array('kelas_id' => $header_attribute->kelas_id));

              $ada_header=true;
              $last_page = false;
              $additional_page = false;

              $row_count = $count_peserta->jml;

              $row_perpage = 25;
              $row_perpage_include_signature = 19;

              $total_page = ceil($row_count/$row_perpage);

              $sisa_row = $row_count%$row_perpage;

              if ($sisa_row==0) {
                $sisa_row = $row_perpage;
              }

              if ($total_page==1 && $sisa_row>$row_perpage_include_signature && $has_pengesah && $doc_name->enable_signature=='Y') {
                $row_perpage = $row_perpage-1;
                $total_page++;
              }

              $last_page_count = $total_page-1;



              $no=1;


              for ($i=0; $i < $total_page ; $i++) {

                  $offset = $i*$row_perpage;
                  $limit = $row_perpage;

                  if ($i==$last_page_count) {
                    $last_page = true;

                  }

              $row_mahasiswa=$db->query("SELECT tb_data_kelas_krs.nim,view_simple_mhs.nama  from tb_data_kelas_krs_detail tdk
INNER JOIN tb_data_kelas_krs using(krs_id)
INNER JOIN view_simple_mhs USING(nim)
WHERE tdk.disetujui=1 AND kelas_id=? order by nim asc limit $offset,$limit",array('kelas_id' => $header_attribute->kelas_id));

                
?>
       <div class="page-break">
        <div class="sheet padding-10mm">

      <?php
       if ($ada_header) {
       ?>
                    <div style="text-align: right;margin-top: -10px;"><?=$kode_dokumen;?></div>
                   <table>
                    <tbody><tr>
                     <td style="vertical-align: top;" >
                      <img src="<?=getPengaturan('logo');?>" width="100" height="100">
                    </td>
                    <td >
                     <h1><?= getPengaturan('header') ?></h1>

                     <?= getPengaturan('alamat') ?>
                    </tr>
                  </tbody></table>
                  <hr>

<h3 align="center" style="margin-bottom: 7px;">DAFTAR PRESENSI KULIAH<br><?= $header_attribute->tahun_akademik ?></h3>

        <table class="tabel-info" width="100%">
        <tr>
               <td nowrap ="nowrap" height="17">Mata Kuliah</td>
               <td nowrap ="nowrap" >:</td>
               <td ><?= $header_attribute->kode_mk."/".$header_attribute->nama_mk ?></td>
                <td >Program Studi</td>
               <td nowrap ="nowrap" >:</td>
               <td nowrap ="nowrap" ><?= $header_attribute->nama_jurusan ?></td>

            </tr>
            <tr>
               <td nowrap ="nowrap" height="17">Kelas</td>
               <td nowrap ="nowrap" >:</td>
               <td nowrap ="nowrap" ><?= $header_attribute->kls_nama ?></td>
               
<td nowrap ="nowrap"  valign="top">Hari / Waktu</td>
               <td nowrap ="nowrap"  valign="top">:</td>
               <td nowrap ="nowrap"  valign="top"><?= $header_attribute->nama_hari ?> / <?= $header_attribute->jam_mulai  ?>-<?= $header_attribute->jam_selesai ?></td>

              
            </tr>
            <tr>

               <td nowrap ="nowrap" height="17">Bobot SKS</td>
               <td nowrap ="nowrap" >:</td>
               <td nowrap ="nowrap" ><?= $header_attribute->sks ?>&nbsp;sks</td>
                <td nowrap ="nowrap" >Ruang</td>
               <td nowrap ="nowrap" >:</td>
               <td nowrap ="nowrap" ><?= $header_attribute->nm_ruang ?></td>
            </tr>
            <tr>
               <td nowrap ="nowrap"  valign="top">Dosen Pengampu</td>
               <td nowrap ="nowrap"  valign="top">:</td>
               <?php
               $dosen = "";
               if ($header_attribute->nama_dosen!="") {
                 $dosen_pengampu = explode("#", $header_attribute->nama_dosen);
                 if (count($dosen_pengampu)>0) {
                   for ($j=0; $j <count($dosen_pengampu); $j++) { 
                     $dosen.= '<li>'.$dosen_pengampu[$j]."</li>";
                   }
                 }
               }
               ?>
               <td nowrap ="nowrap"  valign="top"><ol style="padding:0 0 0 15;"><?=$dosen;?></ol></td>
               <td nowrap ="nowrap" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
               <td nowrap ="nowrap" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
               <td nowrap ="nowrap" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            </tr>
         </table>
 <br />
         <table width="100%" class="tabel-common">
           <?php
           //$jumlah_pertemuan = $header_attribute->jml_pertemuan;
           $jumlah_pertemuan = $db->fetchCustomSingle("SELECT count(tb_data_kelas_pertemuan.id_pertemuan) AS jml from tb_data_kelas_pertemuan WHERE kelas_id='".$header_attribute->kelas_id."'");
           // if ($row_mahasiswa->rowCount()>0) {
              ?>
            <tr>
               <th rowspan="2" >No.</th>
               <th rowspan="2" width="15%">NIM</th>
               <th rowspan="2">Nama</th>
               <th colspan="6">Jumlah</th>
            </tr>
            <tr>
              <?php
               $array_select = array(
                'Hadir','Ijin','Sakit','Alpa'
                );

               echo '<th >Pertemuan</th>';
               foreach ($array_select as $stat) {
                 echo '<th>'.$stat.'</th>';
               }
               echo '<th>Prosentase</th>';

              ?>
            </tr>
       <?php
      }
      ?>
     
            <?php

foreach ($row_mahasiswa as $mhs) {
    // check jumlah pertemuan
    $meets = $db->query("SELECT tb_data_kelas_pertemuan.*, isi_absensi FROM tb_data_kelas_pertemuan LEFT JOIN tb_data_kelas_absensi USING (id_pertemuan) WHERE kelas_id='".$header_attribute->kelas_id."'");

    echo "<tr></tr>
          <tr>
            <td style='text-align:center; vertical-align:middle;' height='30'>$no</td>
            <td style='vertical-align:middle;' width='150'>$mhs->nim</td>
            <td style='vertical-align:middle;' width='250'>$mhs->nama</td>";

    $pert = 0;
    $jml_hadir = 0;
    $jml_ijin = 0;
    $jml_sakit = 0;
    $jml_alfa = 0;

    foreach ($meets as $meet) {
        $is_hadir = 0;

        if ($meet->isi_absensi != "") {
            $data_absen = json_decode($meet->isi_absensi);

            foreach ($data_absen as $nim) {
                if ($mhs->nim == $nim->nim) {
                    if ($nim->status_absen == 'Hadir') {
                        $is_hadir = 1;
                        $jml_hadir++;
                    } elseif ($nim->status_absen == 'Ijin') {
                        $jml_ijin++;
                    } elseif ($nim->status_absen == 'Sakit') {
                        $jml_sakit++;
                    } elseif ($nim->status_absen == 'Alpa') {
                        $jml_alfa++;
                    }
                }
            }
        }

        $pert++; // Increment the pertemuan counter
    }
    echo "<td align='center' style='vertical-align:middle'>".$meets->rowCount()."</td>";
    echo '<td  align="center" style="vertical-align:middle">'.$jml_hadir.'</td>';
    echo '<td  align="center" style="vertical-align:middle">'.$jml_ijin.'</td>';
    echo '<td  align="center" style="vertical-align:middle">'.$jml_sakit.'</td>';
    echo '<td  align="center" style="vertical-align:middle">'.$jml_alfa.'</td>';

    
    if ($pert==0) {
      $persen = 0;
    } else {
      $persen = round(($jml_hadir/$pert)*100, 0);
    }

    echo "<td align='center' style='vertical-align:middle'><b>$persen%</b></td></tr>";

    $no++;
}



       
        if ($last_page) {
              if ($has_pengesah) {
                  $nip_pengesah_1 = nip_pengesah($pejabat,0,$header_attribute->nip_pengajar);
                  $nip_pengesah_2 = nip_pengesah($pejabat,1,$header_attribute->nip_pengajar);
                  $nip_pengesah_3 = nip_pengesah($pejabat,2,$header_attribute->nip_pengajar);

                  $nama_pengesah_1 = nama_pengesah($pejabat,0,$header_attribute->nip_pengajar);
                  $nama_pengesah_2 = nama_pengesah($pejabat,1,$header_attribute->nip_pengajar);
                  $nama_pengesah_3 = nama_pengesah($pejabat,2,$header_attribute->nip_pengajar);
                ?>
          <table class="table table-bordered table-striped display nowrap" width="100%" id="dtb_pengesahan_dokumen">
              <tr>
                <td width="32%" style="text-align:center"><span class="preview_kota_1"><?=$kota_1;?></span><span class="preview_tgl_1"><?=$tgl_1;?></span><span class="preview_tipe_pengesah_1"><?=$tipe_pengesah_1;?></span>
                <span class="preview_kat_jabatan_1"><?=$kategori_pejabat_1;?></span></td>
                <td width="31%" style="text-align:center;"><span class="preview_kota_2"><?=$kota_2;?></span><span class="preview_tgl_2"><?=$tgl_2;?></span><span class="preview_tipe_pengesah_2"><?=$tipe_pengesah_2;?></span>
                <span class="preview_kat_jabatan_2"><?=$kategori_pejabat_2;?></span>
                </td>
                <td width="37%" style="text-align:center"><span class="preview_kota_3"><?=$kota_3;?></span><span class="preview_tgl_3"><?=$tgl_3;?></span><span class="preview_tipe_pengesah_3"><?=$tipe_pengesah_3;?></span><span class="preview_kat_jabatan_3"><?=$kategori_pejabat_3;?></span>
                </td>
              </tr>
              <tr>  
                <td align="center" height="50">&nbsp;</td>
                <td align="center" height="50"></td>
                <td align="center" height="50"></td>
             </tr>
             <tr>  <td align="justify" style="text-align:center">
                        <span style="text-decoration: underline;" class="preview_pengesah_1"><?=$nama_pengesah_1;?></span><?=($nama_pengesah_1!='')?'<br />------------------------------<br />':'';?>
                        <span class="preview_nip_1"><?=$nip_pengesah_1;?></span></td>
                          <td nowrap="nowrap" style="text-align:center">
                              <span class="preview_pengesah_2"><?=$nama_pengesah_2;?></span><?=($nama_pengesah_2!='')?'<br />------------------------------<br />':'';?>
                              <span class="preview_nip_2"><?=$nip_pengesah_2;?></span></td>
                          <td style="text-align:center">
              <span class="preview_pengesah_3"><?=$nama_pengesah_3;?></span><?=($nama_pengesah_3!='')?'<br />------------------------------<br />':'';?>
                                  <span class="preview_nip_3"><?=$nip_pengesah_3;?></span></td>
                       </tr>
            </table>

         <?php
         //end has pengesah
         }
         //end if last page
        }
        //end page-break and sheet
        ?>
        </table>
        <p></p><div style="text-align: right">Halaman <?=$i+1;?></div>
        </div></div>
    <?php

    //end loop
    }


//end loop foreach
}


?>

</body>
</html>
