<?php
session_start();
include "dashboard/inc/config.php";
$nip = $_SESSION['username'];
 $data_bimbingan = $db->query("select *,
 ((left(".$_GET['sem'].",4)-left(mulai_smt,4))*2)+right(".$_GET['sem'].",1)-(floor(right(mulai_smt,1)/2)) as smt,
 (select foto_user from sys_users where view_simple_mhs_data.nim=sys_users.username) as foto_mhs,
 (select nama_gelar from view_nama_gelar_dosen where nip=bimbingan_dosen_pa.nip) as nama_gelar
  from view_simple_mhs_data
  inner join bimbingan_dosen_pa using(nim) where bimbingan_dosen_pa.nim=? and id_semester=?
  group by bimbingan_dosen_pa.nim
  ",array('nim' => $_GET['nim'],'id_semester' => $_GET['sem']));

 echo $db->getErrorMessage();
$dosen = $db->fetch_single_row("view_nama_gelar_dosen","nip",$nip);

foreach ($data_bimbingan as $bimbingan) {
    $data[] = array(
        'nama' => $bimbingan->nama,
        'nim'  => $bimbingan->nim,
        'prodi' => $bimbingan->jurusan,
        'jurusan' => $bimbingan->nama_fakultas,
        'semester' => $bimbingan->smt.' ('.tampil_periode($_GET['sem']).')',
        'dosen' => $bimbingan->nama_gelar,
        'nip_dosen' => $bimbingan->nip,
        'foto' => $bimbingan->foto_mhs
    );
}
 //       

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Cetak Bimbingan Akademik</title>

<style>
    body {
        font-family: "Times New Roman", serif;
        font-size: 14px;
        padding: 20px;
    }

    /* ---------- HEADER + LOGO ---------- */
    .kop-container {
        display: flex;
        align-items: center;
        border-bottom: 3px double #000;
        padding-bottom: 10px;
        margin-bottom: 15px;
         gap: 15px; /* biar lebih rapi */
    }

   .kop-logo {
    width: 90px;
    height: 90px;
    object-fit: contain;
    margin-right: 15px;
}

    .kop-text {
        flex-grow: 1;
        text-align: center;
    }

    /* ---------- FOTO OVERLAY ---------- */
    .foto-overlay {
        position: absolute;
        right: 20px;    /* posisinya tepat di atas kolom TTD PA */
        top: -119px;
        width: 80px;
        height: 100px;
        object-fit: cover;
        border: 1px solid #000;
        background: #fff;
        padding: 2px;
    }

    .table-wrapper {
        position: relative; /* parent untuk foto overlay */
        margin-top: 15px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .tbl-bimbingan th, 
    .tbl-bimbingan td {
        border: 1px solid #000;
        padding: 5px;
    }

    .no-border td {
        border: none !important;
    }

    /* Print Mode */
    @media print {
        .page-break { page-break-before: always; }
        body { margin: 0; padding: 10mm; }
        .foto-overlay { right: 10mm; top: -119px;}
    }
</style>
</head>
<body>

<?php foreach ($data as $i => $m): ?>

<div class="page">

    <!-- ---------- HEADER ---------- -->
<div class="kop-container">
    <img src="<?=base_url().'upload/logo/'.getPengaturan('logo');?>" class="kop-logo">
    
    <div class="kop-text">
        <h3 style="margin:0;">INSTITUT AGAMA ISLAM NEGERI KERINCI</h3>
        <div>FAKULTAS TARBIYAH DAN ILMU KEGURUAN</div>
        <div>JURUSAN/PROGRAM STUDI TADRIS MATEMATIKA</div>
        <small>Kampus Utama : JL. Kapten Muradi, Sungai Penuh</small>
    </div>
</div>



    <h4 style="text-align:center; margin:0 0 10px 0;">
        BUKTI KONSULTASI DAN BIMBINGAN AKADEMIK
    </h4>

    <table class="no-border">
        <tr><td width="20%">Nama</td><td>: <?= $m['nama'] ?></td></tr>
        <tr><td>NIM</td><td>: <?= $m['nim'] ?></td></tr>
        <tr><td>Jurusan/Prodi</td><td>: <?= $m['jurusan'] ?> / <?= $m['prodi'] ?></td></tr>
        <tr><td>Semester</td><td>: <?= $m['semester'] ?></td></tr>
         <tr><td>Dosenn PA</td><td>: <?= $m['dosen'] ?></td></tr>
    </table>

    <!-- ---------- TABEL + FOTO OVERLAY ---------- -->
    <div class="table-wrapper">

        <img src="<?= $m['foto'] ?>" class="foto-overlay">

         <table class="tbl-bimbingan">
            <tr>
                <th width="5%">No</th>
                <th width="15%">Hari/Tgl</th>
                <th width="35%">Materi Konsultasi</th>
                <th width="35%">Arahan PA</th>
            </tr>


        <?php
        $awal_semester = $db->query("select * from bimbingan_dosen_pa where nim='".$m['nim']."' and kategori_konsultasi='1'");
        if ($awal_semester->rowCount()>0) {
            ?>
              <tr><td colspan="4"><b>Awal Semester</b></td></tr>
            <?php
            $no=1;
                    foreach ($awal_semester as $bimbing) {
                        $tanggal = tgl_indo($bimbing->tanggal_tanya);
                        if ($bimbing->jawaban!='') {
                           $tanggal = tgl_indo($bimbing->tanggal_jawab);
                        }
            ?>
            <tr>
                <td><?=$no?></td>
                <td><?=$tanggal?></td>
                <td><?=$bimbing->pertanyaan?></td>
               <td><?=$bimbing->jawaban?></td>
            </tr>

            <?php
            $no++;
            }
        }

        $tengah_semester = $db->query("select * from bimbingan_dosen_pa where nim='".$m['nim']."' and kategori_konsultasi='2'");
        if ($tengah_semester->rowCount()>0) {
            ?>
              <tr><td colspan="4"><b>Tengah Semester</b></td></tr>
            <?php
            $no=1;
                    foreach ($tengah_semester as $bimbing) {
                        $tanggal = getHariFromDate($bimbing->tanggal_tanya).', '.tgl_indo($bimbing->tanggal_tanya);
                        if ($bimbing->jawaban!='') {
                           $tanggal = getHariFromDate($bimbing->tanggal_jawab).', '.tgl_indo($bimbing->tanggal_jawab);
                        }
            ?>
            <tr>
                <td><?=$no?></td>
                <td><?=$tanggal?></td>
                <td><?=$bimbing->pertanyaan?></td>
               <td><?=$bimbing->jawaban?></td>
            </tr>

            <?php
            $no++;
            }
        }

        $akhir_semester = $db->query("select * from bimbingan_dosen_pa where nim='".$m['nim']."' and kategori_konsultasi='3'");
        if ($akhir_semester->rowCount()>0) {
            ?>
              <tr><td colspan="4"><b>Akhir Semester </b></td></tr>
            <?php
            $no=1;
                    foreach ($akhir_semester as $bimbing) {
                        $tanggal = tgl_indo($bimbing->tanggal_tanya);
                        if ($bimbing->jawaban!='') {
                           $tanggal = tgl_indo($bimbing->tanggal_jawab);
                        }
            ?>
            <tr>
                <td><?=$no?></td>
                <td><?=$tanggal?></td>
                <td><?=$bimbing->pertanyaan?></td>
               <td><?=$bimbing->jawaban?></td>
            </tr>

            <?php
            $no++;
            }
        }



        ?>
        </table>
    </div>

</div>

<?php if ($i < count($data)-1): ?>
    <div class="page-break"></div>
<?php endif; ?>

<?php endforeach; ?>

</body>
</html>
