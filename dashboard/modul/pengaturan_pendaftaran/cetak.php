<?php
session_start();
include "../../inc/config.php";

$kode_jur = $db2->fetchCustomSingle("select kode_jur from view_kelas where kelas_id=?",array('kelas_id' => 38));

  $kode_dokumen = "";
              $doc_name = $db2->fetchSingleRow("tb_data_dokumen","short_name","cetak_uas");
              $has_pengesah = $db2->fetchCustomSingle("select * from tb_data_jabatan_pengesah where kode_jur=? and id_dokumen=?",array('kode_jur' => $kode_jur->kode_jur,'id_dokumen' => $doc_name->id_dokumen));

              if ($has_pengesah) {

              if ($doc_name->enable_signature=='Y') {
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

                $nip_pengesah_1 = nip_pengesah($pejabat,0);
                $nip_pengesah_2 = nip_pengesah($pejabat,1);
                $nip_pengesah_3 = nip_pengesah($pejabat,2);

                $nama_pengesah_1 = nama_pengesah($pejabat,0);
                $nama_pengesah_2 = nama_pengesah($pejabat,1);
                $nama_pengesah_3 = nama_pengesah($pejabat,2);
              } else {
                $kode_dokumen = $has_pengesah->kode_dokumen;
              }

              

            }
?>
<html>
<head>
  <title>Cetak Presensi UAS</title>
  <link href="https://fonts.googleapis.com/css2?family=EB+Garamond&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Times+New+Roman&display=swap" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="<?=base_admin();?>assets/dist/css/cetak/paper.css">
  <link rel="stylesheet" type="text/css" href="table.css">
  <style type="text/css">
body {
      color: #333;
}
@page { size: A4 portrait }

.tabel-info tr td, th {
 font-family: 'Garamond', serif;
 font-size: 11px;
 padding: 2px;
 font-weight: bold;
}
ol {
   font-family: 'Garamond', serif;
      margin-bottom: 0;
      margin-top: 0;
      padding-left: 15px;
     
}
li::before {
     font-size: 11pt;
    font-style: inherit;
    font-weight: inherit;
    /* Add other font-related styles here */
}
ul {
      margin-bottom: 0;
}
ol li {
      font-size: inherit;
      padding-left:10px;
}

  </style>
</head>
<?php


$header_attributes = $db2->fetchCustomSingle("SELECT vk.sem_id,vk.kode_jur,vk.kls_nama,vk.total_sks as sks,vk.kode_mk,vk.nama_mk,vjk.nama_hari,vjk.jam_mulai,vjk.jam_selesai,nm_ruang,vk.nama_jurusan,view_semester.tahun_akademik,vk.sem_id,
(select group_concat(nama_gelar separator '#' ) from view_dosen_kelas where kelas_id=vk.kelas_id order by dosen_ke asc) as nama_dosen
from view_kelas vk
INNER JOIN view_semester on vk.sem_id=view_semester.id_semester
LEFT JOIN view_jadwal_kelas vjk using(kelas_id)
WHERE vk.kelas_id=?",array('kelas_id' => 38));


//onload="window.print()"
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<!-- jsPDF library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<button onclick="Convert_HTML_To_PDF();">Convert HTML to PDF</button>
<body class="A4 portrait" >

       <div class="page-break">
        <div class="sheet padding-10mm">
          

       <div style="text-align: right;position: absolute;right: 20px;top:20px"><?=$kode_dokumen;?></div>
                   <table>
                    <tbody><tr>
                     <td style="vertical-align: middle;">
                      <img src="<?=getPengaturan('logo');?>" width="100" height="100">
                    </td><td style="text-align:center;">
                     <h1><?= getPengaturan('header') ?></h1>

                     <?= getPengaturan('alamat') ?>
                    </tr>
                  </tbody></table>
                  <hr style="border: 2px solid #000;margin: 2px 0 10px 0;">
       <?php
       
      $data_edit = $db2->fetchSingleRow("tb_data_pendaftaran_jenis_pengaturan","id_jenis_pendaftaran_setting",146);

      echo $data_edit->isi_template_surat;
             if ($has_pengesah && $doc_name->enable_signature=='Y') {
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
            //end has pengesah condition
            }
            ?>

         <?php
         //end if last page
        
        //end page-break and sheet
         ?>
        </table>

        </div></div>
    <?php
    //end loop
    




?>

</body>
<script type="text/javascript">
  window.jsPDF = window.jspdf.jsPDF;

// Convert HTML content to PDF
function Convert_HTML_To_PDF() {
    var doc = new jsPDF();
  
    // Source HTMLElement or a string containing HTML.
    var elementHTML = document.querySelector(".sheet");

    doc.html(elementHTML, {
        callback: function(doc) {
            // Save the PDF
            doc.save('surat_pembimbing.pdf');
        },
        margin: [10, 10, 10, 10],
        autoPaging: 'text',
        x: 0,
        y: 0,
        width: 190, //target width in the PDF document
        windowWidth: 675 //window width in CSS pixels
    });
}
</script>
</html>
