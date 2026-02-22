<?php
include "../../inc/config.php";

function loop_periode($current_periode,$jml_loop_year,$minimal_kuliah) {
  //get current periode year
  $current_year = substr($current_periode, 0,4)+$minimal_kuliah;
  $max_year = $current_year+$jml_loop_year;
  $array_loop = array();
  for ($i=$current_year; $i <$max_year; $i++) { 
    for ($j=1; $j <=2; $j++) { 
      if ($i.$j>=$current_periode) {
       $array_loop[$i.$j] = ganjil_genap($i.$j);
      }
    }
  }

  return $array_loop;
}
$nim = $_POST['nim'];
//data mahasiswa
$mhs = $db->fetch_single_row("view_simple_mhs_data",'nim',$nim);
$id_jenjang = $db->fetch_single_row("jurusan",'kode_jur',$mhs->jur_kode);

//get max cuti sekali pengajuan
$pengaturan_cuti = $db->query("select * from setting_cuti where id_jenjang=?",array('id_jenjang' => $id_jenjang->id_jenjang));
foreach ($pengaturan_cuti as $aturan) {
  $aturan_cuti[$aturan->nama_pengaturan] = $aturan->isi_pengaturan;
}

  if ($id_jenjang->id_jenjang==35) {
    $loop = 3;
  } else {
    $loop = 7;
  }
  foreach (loop_periode($mhs->mulai_smt,$loop,ceil($aturan_cuti['minimal_kuliah']/2)) as $key => $value) {
      ?>
     <label>
      <input type="checkbox" name="periode[]" class="minimal" value="<?=$key;?>">  <?=$value;?>
    </label>
    <br>
      <?php
  }