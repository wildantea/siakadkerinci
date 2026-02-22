<?php

   $fakultas="";
  $jurusan="";
  $priode="";
  $lokasi ="";
  $jk = ""; 

  $jur_filter = "";
//get default akses prodi 


    if($_POST['fakultas_filter']!='all') {
      $fakultas = ' and fakultas.kode_fak="'.$_POST['fakultas_filter'].'"';
    }

    if($_POST['jurusan_filter']!='all') {
      $jur_filter = ' and jurusan.kode_jur="'.$_POST['jurusan_filter'].'"';
    }

    if($_POST['priode_filter']!='all') {
      $priode = ' and priode_ppl.id_priode="'.$_POST['priode_filter'].'"';
    }

    if($_POST['id_lokasi']!='all') {
      $lokasi = ' and lk.id_lokasi="'.$_POST['id_lokasi'].'"';
    }

if($_POST['jk']!='all') {
      $jk = ' and mahasiswa.jk="'.$_POST['jk'].'"';
  }  



  // if($_SESSION['level']=='6'){
  //   if($_SESSION['id_fak'] != NULL){
  //     $fakultas = ' and fakultas.kode_fak="'.$_SESSION['id_fak'].'"';
  //   }
  // }
  ?>
    <table border="1">
        <thead>
          <tr>
            <th>No</th>
            <th>Nim</th>
            <th>Nama</th>
            <th>Kelamin</th>
            <th>Fakultas</th>
            <th>Jurusan</th>
            <th>Lokasi Kukerta</th>
             <th>Kode MK</th>
            <th>Nama MK</th>
            <th>Nilai Angka</th>
            <th>Nilai Huruf</th>
           
          </tr>
        </thead>
        <tbody>
       
  <?php
  $no=1;

 // die();
  $q  = $db->query("select lk.nama_lokasi,mahasiswa.jk, ppl.nim,mahasiswa.nama,fakultas.nama_resmi,jurusan.nama_jur,ppl.id_kkn, 
    (select ifnull(matkul.kode_mk,'-') from krs_detail join matkul on matkul.id_matkul=krs_detail.kode_mk
 where krs_detail.kode_mk in (select id_matkul from v_matkul_ppl) and krs_detail.nim=ppl.nim and nilai_huruf is not null order by nilai_huruf asc limit 1) as kode_mk,  
(select ifnull(matkul.nama_mk,'-') from krs_detail join matkul on matkul.id_matkul=krs_detail.kode_mk
 where krs_detail.kode_mk in (select id_matkul from v_matkul_ppl) and krs_detail.nim=ppl.nim and nilai_huruf is not null order by nilai_huruf asc limit 1) as nama_mk, 
(select ifnull(nilai_angka,'-') from krs_detail where kode_mk in (select id_matkul from v_matkul_ppl) and krs_detail.nim=ppl.nim and nilai_huruf is not null order by nilai_huruf asc limit 1) as nilai_angka, 
(select ifnull(nilai_huruf,'-') from krs_detail where kode_mk in (select id_matkul from v_matkul_ppl) and krs_detail.nim=ppl.nim and nilai_huruf is not null order by nilai_huruf asc limit 1) as nilai_huruf from ppl 
inner join mahasiswa on ppl.nim=mahasiswa.nim inner join fakultas on ppl.kode_fak=fakultas.kode_fak inner join jurusan on ppl.kode_jur=jurusan.kode_jur left join priode_ppl on priode_ppl.id_priode=ppl.id_priode 
left join lokasi_ppl lk on lk.id_lokasi=ppl.id_lokasi where  id_kkn is not null $fakultas $jur_filter $priode $lokasi $jk"); 



header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=peserta_ppl.xls");  //File name extension was wrong
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);

  foreach ($q as $k) {
      echo "<tr>
       <td>$no</td>
       <td>$k->nim</td>
       <td>$k->nama</td>
       <td>$k->jk</td>
       <td>$k->nama_resmi</td>
       <td>$k->nama_jur</td>
       <td>$k->nama_lokasi</td>
       <td>$k->kode_mk</td>
       <td>$k->nama_mk</td>
       <td>$k->nilai_angka</td>
       <td>$k->nilai_huruf</td>
  
      </tr>";
      $no++;
  }
  ?>
</tbody>
</table>