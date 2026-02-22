<?php 
session_start(); 
include "../../inc/config.php"; 
session_check_json(); 
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cetak Jadwal</title>
    <link rel="stylesheet" type="text/css" href="<?=base_admin();?>assets/dist/css/cetak/table.css">
    <link rel="stylesheet" type="text/css" href="<?=base_admin();?>assets/dist/css/cetak/paper.css">
    <link rel="icon" type="image/png" href="<?=base_url().'upload/logo/'.getPengaturan('logo');?>">
    <style type="text/css">
        @page { 
            size: A4 landscape; 
            margin: 8mm 10mm; 
        }
        body { 
            margin: 0; 
            padding: 0; 
            font-family: Tahoma, sans-serif; 
            font-size: 10px;
            line-height: 1.3;
        }
        .sheet { 
            width: 100%; 
            min-height: 180mm; 
            padding: 6mm 8mm; 
            box-sizing: border-box; 
            page-break-after: always;
            position: relative;
        }
        .sheet:last-of-type { 
            page-break-after: avoid !important; 
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 5px;
        }
        .tabel-common th, .tabel-common td { 
            border: 1px solid #000; 
            padding: 5px; 
            font-size: 10px; 
            vertical-align: top;
        }
        .tabel-common th { 
            background-color: #f0f0f0; 
            text-align: center; 
        }
        .tabel-common tr td {
    padding: 4px;
}
        .logo { width: 75px; height: 75px; }
        h1 { font-size: 15px; margin: 0; }
        h2 { font-size: 13px; margin: 4px 0; }
        h4 { font-size: 11px; margin: 2px 0; }
        hr { border: 0.5px solid #000; margin: 4px 0; }
        .table-head { font-size: 9.5px; margin-bottom: 5px; }
        .signature-table { 
            margin-top: 8px; 
            font-size: 9.5px; 
            width: 100%;
        }
        .signature-table td { 
            border: none; 
            text-align: center; 
            vertical-align: top; 
        }
        .underline { text-decoration: underline; }
    </style>
</head>
<body >

<?php
$nip = $_SESSION['username'];
$and_nip = "AND dosen_kelas.id_dosen='".$nip."'";
$periode = ' AND kelas.sem_id="'.$_POST['periode'].'"';

// Hitung total data
$count = $db->fetch_custom_single("
    SELECT COUNT(*) AS jml FROM kelas 
    INNER JOIN dosen_kelas ON dosen_kelas.id_kelas = kelas.kelas_id 
    WHERE kelas.kelas_id IS NOT NULL $periode $and_nip
");
$jml_peserta = $count->jml;
$limit = 9;
$jml_page = ceil($jml_peserta / $limit);
$offset = 0;

// Data dosen
$header = $db->fetch_single_row("view_nama_gelar_dosen", "nip", $_POST['id_dosen']);
$dosen_ttd = $db->fetch_single_row("view_dosen", "nip", $_SESSION['username']);
$nama_ttd = $dosen_ttd->dosen;
$nip_ttd = $dosen_ttd->nip;
$kota = getPengaturan('kota');
$tgl = tgl_indo(date('Y-m-d'));

// Total SKS
$total_sks = 0;
$sks_all = $db2->query("
    SELECT total_sks FROM kelas 
    INNER JOIN dosen_kelas ON dosen_kelas.id_kelas = kelas.kelas_id 
    INNER JOIN view_matakuliah_kurikulum USING(id_matkul) 
    WHERE kelas.kelas_id IS NOT NULL $periode $and_nip 
    GROUP BY kelas.kelas_id
");
foreach ($sks_all as $s) $total_sks += $s->total_sks;

// Loop halaman
for ($page = 1; $page <= $jml_page; $page++) {
    $is_last = ($page == $jml_page);
    $data = $db2->query("
        SELECT 
            semester, kode_mk, nama_mk, total_sks, kls_nama, 
            hari AS nama_hari, jam_mulai, jam_selesai, nm_ruang, nama_jurusan,
            (SELECT GROUP_CONCAT(DISTINCT nama_dosen SEPARATOR '#') 
             FROM view_dosen_kelas WHERE id_kelas = kelas.kelas_id) AS nama_dosen
        FROM kelas 
        INNER JOIN dosen_kelas ON dosen_kelas.id_kelas = kelas.kelas_id 
        INNER JOIN view_matakuliah_kurikulum USING(id_matkul)
        INNER JOIN view_jadwal_dosen_kelas ON kelas.kelas_id = view_jadwal_dosen_kelas.id_kelas
        INNER JOIN ruang_ref ON view_jadwal_dosen_kelas.id_ruang = ruang_ref.ruang_id
        WHERE kelas.kelas_id IS NOT NULL $periode $and_nip
        GROUP BY kelas.kelas_id
        ORDER BY id_hari ASC, jam_mulai ASC
        LIMIT $offset, $limit
    ");
    $offset += $limit;
    ?>

    <div class="sheet padding-10mm">

        <!-- HEADER -->
        <table>
            <tr>
                <td style="width: 5%;"><img src="<?=base_url().'upload/logo/'.getPengaturan('logo');?>" class="logo"></td>
                <td>
                    <h1><?=getPengaturan('header')?></h1>
                    <?=getPengaturan('alamat')?>
                </td>
            </tr>
        </table>
        <hr>
        <h2 align="center">JADWAL MENGAJAR</h2>
        <h4 align="center">Periode <?=ganjil_genap($_POST['periode']);?></h4>

        <table class="table-head">
            <tr><td width="5%">Nama</td><td width="2%">:</td><td><?=$header->nama_gelar?></td></tr>
            <tr><td>NIP</td><td>:</td><td><?=$nip?></td></tr>
        </table>

        <!-- TABEL DATA -->
        <table class="tabel-common">
            <thead>
                <tr>
                    <th width="3%">No</th>
                    <th>Dosen</th>
                    <th>MK</th>
                    <th>SKS</th>
                    <th>SMT</th>
                    <th>Kelas</th>
                    <th>Hari</th>
                    <th>Ruang</th>
                    <th>Waktu</th>
                    <th>Prodi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = ($page - 1) * $limit + 1;
                foreach ($data as $r): ?>
                <tr>
                    <td align="center"><?=$no++?></td>
                    <td style="font-size:9.5px;">
                        <?= $r->nama_dosen ? str_replace('#', '<br>', nl2br($r->nama_dosen)) : '-' ?>
                    </td>
                    <td><?=$r->kode_mk.' - '.$r->nama_mk?></td>
                    <td align="center"><?=$r->total_sks?></td>
                    <td align="center"><?=$r->semester?></td>
                    <td align="center"><?=$r->kls_nama?></td>
                    <td align="center"><?=ucwords($r->nama_hari)?></td>
                    <td align="center"><?=$r->nm_ruang?></td>
                    <td align="center"><?=substr($r->jam_mulai,0,5).'-'.substr($r->jam_selesai,0,5)?></td>
                    <td><?=$r->nama_jurusan?></td>
                </tr>
                <?php endforeach; ?>

                <!-- TOTAL & TANDA TANGAN HANYA DI HALAMAN TERAKHIR -->
                <?php if ($is_last): ?>
                <tr>
                    <td colspan="3" align="center"><strong>JUMLAH SKS MENGAJAR</strong></td>
                    <td align="center"><strong><?=$total_sks?></strong></td>
                    <td colspan="6"></td>
                </tr>
                </tbody>
            </table>

            <!-- TANDA TANGAN -->
            <table class="signature-table">
                <tr>
                    <td width="63%"></td>
                    <td width="37%" align="center">
                        <?=$kota?>, <?=$tgl?><br>
                        <strong>Dosen Yang Bersangkutan</strong>
                    </td>
                </tr>
                <tr><td colspan="2" height="45"></td></tr>
                <tr>
                    <td></td>
                    <td align="center">
                        <span class="underline"><?=$nama_ttd?></span><br>
                        <hr style="width:130px; margin:3px auto; border:1px solid #000;">
                        <span style="font-size:9px;"><?=$nip_ttd?></span>
                    </td>
                </tr>
            </table>

                <?php else: ?>
                </tbody>
            </table>
                <?php endif; ?>

        </div>

    <?php 
    // HANYA TAMBAH PAGE BREAK JIKA ADA HALAMAN BERIKUTNYA
    
    ?>

<?php } // end loop ?>

</body>
</html>