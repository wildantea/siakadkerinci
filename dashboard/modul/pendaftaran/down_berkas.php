<?php
session_start();
error_reporting(0);
include "../../inc/config.php";
require_once('../../inc/lib/pclzip.lib.php');

$id_scope = $_GET['id'];



$berkas = $db2->fetchCustomSingle("select nama_directory,nama,tb_data_pendaftaran.nim from tb_data_pendaftaran 
inner join view_simple_mhs on tb_data_pendaftaran.nim=view_simple_mhs.nim 
inner join tb_data_pendaftaran_jenis_pengaturan USING(id_jenis_pendaftaran_setting)
inner join tb_data_pendaftaran_jenis USING(id_jenis_pendaftaran) 
where tb_data_pendaftaran.id_pendaftaran=? ",array('id_pendaftaran' => $id_scope));

$i=1;



	$dir_to_download = $db2->get_dir_paper(getcwd()).$berkas->nama_directory.DIRECTORY_SEPARATOR.str_replace(" ", "_", $berkas->nim).DIRECTORY_SEPARATOR;

	//echo $i.$dir_to_download."<br>";
	$db2->downloadfolderpaper($dir_to_download,str_replace(" ", "_", $berkas->nim));

$db2->download_paper($berkas->nama_directory."_".str_replace(" ", "_", $berkas->nama));
