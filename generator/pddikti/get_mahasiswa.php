<?php
header("Access-Control-Allow-Origin: *");
include "config.php";

$json_response = array();
$param = array();

$and_kode_dikti= "";
$and_mulai_smt= "";

if ($_POST['mulai_smt']!='all') {
  //param here
  $param = array(
    "kode_dikti" => $_POST["kode_dikti"],
		"mulai_smt" => $_POST["mulai_smt"]
  );

  $and_kode_dikti = "and kode_dikti=?";
	$and_mulai_smt = "and mulai_smt=?";

}

if (isset($_GET["ask"])=="jumlah") {
    $total_mahasiswa = $db->fetchCustomSingle("SELECT count(*) as jumlah FROM mahasiswa 
 inner JOIN jurusan ON mahasiswa.jur_kode=jurusan.kode_jur where 1=1 $and_kode_dikti $and_mulai_smt",$param);
    if ($total_mahasiswa->jumlah>0) {
       $json_response['jumlah'] = $total_mahasiswa->jumlah;
    } else {
      $json_response['jumlah'] = 0;
    }
} else {
  $limit = 20;
  $offset = $_GET["offset"];
  $data_mahasiswa = $db->query("SELECT mahasiswa.nama AS nm_pd,mahasiswa.jk,mahasiswa.nisn,mahasiswa.npwp,mahasiswa.nik,mahasiswa.tmpt_lahir,mahasiswa.tgl_lahir,mahasiswa.id_agama,mahasiswa.jln,mahasiswa.rt,mahasiswa.rw,mahasiswa.nm_dsn,mahasiswa.ds_kel,mahasiswa.id_wil,mahasiswa.kode_pos,mahasiswa.id_jns_tinggal,mahasiswa.id_alat_transport,mahasiswa.telepon_rumah AS no_tel_rmh,mahasiswa.telepon_seluler AS no_hp,mahasiswa.email,mahasiswa.a_terima_kps,mahasiswa.no_kps,mahasiswa.stat_pd,mahasiswa.nik_ayah,mahasiswa.nm_ayah,mahasiswa.tgl_lahir_ayah,mahasiswa.id_jenjang_pendidikan_ayah,mahasiswa.id_pekerjaan_ayah,mahasiswa.id_penghasilan_ayah,mahasiswa.nik_ibu_kandung,mahasiswa.nm_ibu_kandung,mahasiswa.tgl_lahir_ibu,mahasiswa.id_jenjang_pendidikan_ibu,mahasiswa.id_penghasilan_ibu,mahasiswa.id_pekerjaan_ibu,mahasiswa.nm_wali,mahasiswa.tgl_lahir_wali,mahasiswa.id_jenjang_pendidikan_wali,mahasiswa.id_pekerjaan_wali,mahasiswa.id_penghasilan_wali,mahasiswa.kewarganegaraan,jurusan.kode_dikti AS kode_jurusan,mahasiswa.id_jns_daftar,mahasiswa.nim AS nipd,mahasiswa.tgl_masuk_sp,mahasiswa.mulai_smt,mahasiswa.id_pembiayaan,mahasiswa.id_jalur_masuk,(select sum(nominal_tagihan-potongan) from keu_tagihan_mahasiswa inner join keu_tagihan on id_tagihan_prodi=keu_tagihan.id where keu_tagihan_mahasiswa.nim=mahasiswa.nim and keu_tagihan_mahasiswa.periode=mahasiswa.mulai_smt) as biaya FROM mahasiswa 
 inner JOIN jurusan ON mahasiswa.jur_kode=jurusan.kode_jur where 1=1 $and_kode_dikti $and_mulai_smt limit $offset,$limit",$param);
  foreach ($data_mahasiswa as $key) {
      $data_rec = array(
          	"nm_pd" => $key->nm_pd,
			"jk" => $key->jk,
			"nisn" => $key->nisn,
			"npwp" => $key->npwp,
			"nik" => $key->nik,
			"tmpt_lahir" => $key->tmpt_lahir,
			"tgl_lahir" => $key->tgl_lahir,
			"id_agama" => $key->id_agama,
			"jln" => $key->jln,
			"rt" => $key->rt,
			"rw" => $key->rw,
			"nm_dsn" => $key->nm_dsn,
			"ds_kel" => $key->ds_kel,
			"id_wil" => $key->id_wil,
			"kode_pos" => $key->kode_pos,
			"id_jns_tinggal" => $key->id_jns_tinggal,
			"id_alat_transport" => $key->id_alat_transport,
			"no_tel_rmh" => $key->no_tel_rmh,
			"no_hp" => $key->no_hp,
			"email" => $key->email,
			"a_terima_kps" => $key->a_terima_kps,
			"no_kps" => $key->no_kps,
			"stat_pd" => $key->stat_pd,
			"nik_ayah" => $key->nik_ayah,
			"nm_ayah" => $key->nm_ayah,
			"tgl_lahir_ayah" => $key->tgl_lahir_ayah,
			"id_jenjang_pendidikan_ayah" => $key->id_jenjang_pendidikan_ayah,
			"id_pekerjaan_ayah" => $key->id_pekerjaan_ayah,
			"id_penghasilan_ayah" => $key->id_penghasilan_ayah,
			"nik_ibu_kandung" => $key->nik_ibu_kandung,
			"nm_ibu_kandung" => $key->nm_ibu_kandung,
			"tgl_lahir_ibu" => $key->tgl_lahir_ibu,
			"id_jenjang_pendidikan_ibu" => $key->id_jenjang_pendidikan_ibu,
			"id_penghasilan_ibu" => $key->id_penghasilan_ibu,
			"id_pekerjaan_ibu" => $key->id_pekerjaan_ibu,
			"nm_wali" => $key->nm_wali,
			"tgl_lahir_wali" => $key->tgl_lahir_wali,
			"id_jenjang_pendidikan_wali" => $key->id_jenjang_pendidikan_wali,
			"id_pekerjaan_wali" => $key->id_pekerjaan_wali,
			"id_penghasilan_wali" => $key->id_penghasilan_wali,
			"kewarganegaraan" => $key->kewarganegaraan,
			"kode_jurusan" => $key->kode_jurusan,
			"id_jns_daftar" => $key->id_jns_daftar,
			"nipd" => $key->nipd,
			"tgl_masuk_sp" => $key->tgl_masuk_sp,
			"mulai_smt" => $key->mulai_smt,
			"id_pembiayaan" => $key->id_pembiayaan,
			"id_jalur_masuk" => $key->id_jalur_masuk,
			"biaya_masuk_kuliah" => $key->biaya
      );
      array_push($json_response, $data_rec);
  }
}

echo json_encode($json_response);