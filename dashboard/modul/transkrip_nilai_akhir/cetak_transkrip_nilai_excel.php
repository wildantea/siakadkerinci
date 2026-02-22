<?php
session_start();
include "../../inc/config.php";
session_check();

/** Error reporting */
error_reporting(E_ALL);

/** PHPExcel */
require_once '../../inc/lib/PHPExcel.php';
/** PHPExcel_IOFactory */
require_once '../../inc/lib/PHPExcel/IOFactory.php';

// membuat obyek dari class PHPExcel
$objPHPExcel = new PHPExcel();


// PERSIAPAN DATA
$no=1;
$last_id_krs="";
$mhs_id=$_POST['nim'];
//$fakultas = $_SESSION['id_fak'];
$fakultas = 7;
$k = $_POST['k'];
$id_krs = array();

$last_l = 0;
$last_r = 0;

$m = get_atribut_mhs($mhs_id);

$header = $db->query("SELECT i.isi as header FROM identitas i WHERE i.id_identitas=1");
foreach ($header as $identitas_1) {
# code...
}

$alamat = $db->query("SELECT i.isi as alamat FROM identitas i WHERE i.id_identitas=2");
foreach ($alamat as $identitas_2) {
# code...
}

$kota = $db->query("SELECT i.isi as kota FROM identitas i WHERE i.id_identitas=4");
foreach ($kota as $identitas_3) {
# code...
}
$mahasiswa_query = $db->query("SELECT * FROM mahasiswa WHERE nim='$mhs_id'");
foreach ($mahasiswa_query as $mhs_q) {
# code...
}

$fakultas_query = $db->query("SELECT *,d.gelar_depan, d.nama_dosen, d.gelar_belakang FROM mahasiswa m
                          JOIN jurusan j ON m.jur_kode=j.kode_jur
                          JOIN fakultas f ON j.fak_kode=f.kode_fak
                          JOIN dosen d ON d.id_dosen=f.dekan
                          WHERE m.nim='$mhs_id'");
foreach ($fakultas_query as $fak_q) {
$dekan=$fak_q->gelar_depan." ".$fak_q->nama_dosen." ".$fak_q->gelar_belakang;
}

$semester_query = $db->query("SELECT * FROM semester_ref s JOIN jenis_semester j 
                            ON s.id_jns_semester=j.id_jns_semester ORDER BY s.id_semester DESC LIMIT 1");
foreach($semester_query as $sem_q){
  $semester=$sem_q->jns_semester." ".$sem_q->tahun." / ".($sem_q->tahun+1);
}

$ta_query = $db->query("SELECT * FROM tugas_akhir WHERE nim='$mhs_id'");
foreach ($ta_query as $ta_q) {
 # code...
}

$ipk_query = $db->query("SELECT * FROM akm WHERE mhs_nim='$mhs_id'");
foreach ($ipk_query as $ipk_q) {
} 



// Nama Sheet
$objPHPExcel->getSheet(0)->setTitle("Transkrip");
 
//set width column
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(3.5);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(11.6);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(34.3);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(4.5);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(4.5);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(4.5);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(2.5);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(3.5);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10.2);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(34.3);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(4.5);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(4.5);
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(4.5);

// Header ===================================================================
$objPHPExcel->getActiveSheet()->mergeCells('A1:M1')->setCellValue('A1', 'TRANSKRIP NILAI PROGRAM STRATA SATU(S-1)');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12)->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  

$objPHPExcel->getActiveSheet()->mergeCells('A2:M2')->setCellValue('A2', 'Nomor : B-3232/Un.05/III.7/PP.00.09/07/2020');
$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setSize(11);
$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  

$tglLahir = ($mhs_q->tgl_lahir = '0000-00-00' && empty($mhs_q->tgl_lahir)) ? '' : tgl_indo($mhs_q->tgl_lahir);
$objPHPExcel->getActiveSheet()->mergeCells('A4:B4')->setCellValue('A4', 'Nama')->setCellValue('C4', ': '.$m->nama)
								->mergeCells('A5:B5')->setCellValue('A5', 'NIM')->setCellValue('C5', ': 1147050155')
								->mergeCells('A6:B6')->setCellValue('A6', 'Tempat / Tgl. Lahir')->setCellValue('C6', ': '. $mhs_q->tmpt_lahir .', '.$tglLahir);

$objPHPExcel->getActiveSheet()->mergeCells('H4:I4')->setCellValue('H4', 'Fakultas')->setCellValue('J4', ': Sains dan Teknologi')
								->mergeCells('H5:I5')->setCellValue('H5', 'Jurusan')->setCellValue('J5', ': '.ucwords(strtolower($m->nama_jur)).' - S1 Reguler')
								->mergeCells('H6:I6')->setCellValue('H6', 'No. Ijazah')->setCellValue('J6', ': FST/S1/3232/2020')
								->mergeCells('H7:I7')->setCellValue('H7', 'SK BAN-PT')->setCellValue('J7', ': 1803/SK/BAN-PT/Akred/S/VII/2018');



// Mulai Loopin Nilai Transkrip ===================================================================
$styleArray1 = array(
      	'borders' => array(
          	'allborders' => array(
              	'style' => PHPExcel_Style_Border::BORDER_THIN
          	)
      	)
  	);
$styleArray2 = array(
      	'borders' => array(
          	'right' => array(
              	'style' => PHPExcel_Style_Border::BORDER_THIN
          	),
          	'left' => array(
              	'style' => PHPExcel_Style_Border::BORDER_THIN
          	)
      	)
  	);
$styleArray3 = array(
      	'borders' => array(
          	'right' => array(
              	'style' => PHPExcel_Style_Border::BORDER_THIN
          	),
          	'left' => array(
              	'style' => PHPExcel_Style_Border::BORDER_THIN
          	),
          	'bottom' => array(
              	'style' => PHPExcel_Style_Border::BORDER_THIN
          	)
      	)
  	);

// Tabel Kiri ===================================================================
$objPHPExcel->getActiveSheet()->setCellValue('A9', 'No')
								->setCellValue('B9', 'Kode')
								->setCellValue('C9', 'Nama Mata Kuliah')
								->setCellValue('D9', 'SKS')
								->setCellValue('E9', 'Nilai')
								->setCellValue('F9', 'AK');
$objPHPExcel->getActiveSheet()->getStyle('A9:F9')->applyFromArray($styleArray1);

foreach ($_POST['id_transkrip'] as $id) {
   $id_krs[] = $id;
 }
 $in_id_krs = implode(",", $id_krs);
 $q=$db->query("select k.id_krs_detail, k.sks, k.id_krs_detail,m.nama_mk,m.kode_mk,k.bobot,k.nilai_huruf from krs_detail k
                  join krs ks on k.id_krs=ks.krs_id join matkul m on m.id_matkul=k.kode_mk where ks.mhs_id='$mhs_id' and k.bobot IS NOT NULL and k.id_krs_detail in($in_id_krs)");

   $totalData = $q->rowCount();
   $kredit=0;
   $total_sks=0;
   $total_kredit=0;

   if ($totalData%2==0) {
   $batas = (ceil($totalData/2)+2);
   }else {
   $batas = (ceil($totalData/2)+1);
   }

   $dat2 = [];

   $rn = 10;
   foreach ($q as $kr) {

     if ($batas == $no) {
       $dat2[] = $kr;
       continue;
     }

     switch ($kr->nilai_huruf) {
       case 'A':
         $kredit = $kr->sks * 4;
         break;
       case 'B':
         $kredit = $kr->sks * 3;  
         break;
       case 'C':
         $kredit = $kr->sks * 2;
         break;
       case 'D':
         $kredit = $kr->sks * 1;
         break;
       default:
         $kredit = 0;
         break;
     }
     if ($batas == $no+1) {
     	$last_l = $rn;
     	$objPHPExcel->getActiveSheet()->setCellValue('A'.$rn, $no)
								->setCellValue('B'.$rn, $kr->kode_mk)
								->setCellValue('C'.$rn, $kr->nama_mk)
								->setCellValue('D'.$rn, $kr->sks)
								->setCellValue('E'.$rn, $kr->nilai_huruf)
								->setCellValue('F'.$rn, $kredit);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$rn)->applyFromArray($styleArray3)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('B'.$rn)->applyFromArray($styleArray3)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('C'.$rn)->applyFromArray($styleArray3);
		$objPHPExcel->getActiveSheet()->getStyle('D'.$rn)->applyFromArray($styleArray3)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('E'.$rn)->applyFromArray($styleArray3)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('F'.$rn)->applyFromArray($styleArray3)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);						

     }else {

     	$objPHPExcel->getActiveSheet()->setCellValue('A'.$rn, $no)
								->setCellValue('B'.$rn, $kr->kode_mk)
								->setCellValue('C'.$rn, $kr->nama_mk)
								->setCellValue('D'.$rn, $kr->sks)
								->setCellValue('E'.$rn, $kr->nilai_huruf)
								->setCellValue('F'.$rn, $kredit);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$rn)->applyFromArray($styleArray2)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('B'.$rn)->applyFromArray($styleArray2)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('C'.$rn)->applyFromArray($styleArray2);
		$objPHPExcel->getActiveSheet()->getStyle('D'.$rn)->applyFromArray($styleArray2)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('E'.$rn)->applyFromArray($styleArray2)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('F'.$rn)->applyFromArray($styleArray2)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

     }
    
     $no++;
     $rn++;
     $total_sks=$total_sks+$kr->sks;
     $total_kredit=$total_kredit+$kredit;
     $last_id_krs=$kr->id_krs_detail;
   }


// Tabel Kanan ===================================================================
$objPHPExcel->getActiveSheet()->setCellValue('H9', 'No')
								->setCellValue('I9', 'Kode')
								->setCellValue('J9', 'Nama Mata Kuliah')
								->setCellValue('K9', 'SKS')
								->setCellValue('L9', 'Nilai')
								->setCellValue('M9', 'AK');
$objPHPExcel->getActiveSheet()->getStyle('H9:M9')->applyFromArray($styleArray1);

	$rn = 10;
    $kredit=0;

   foreach ($dat2 as $kr) {
     switch ($kr->nilai_huruf) {
       case 'A':
         $kredit = $kr->sks * 4;
         break;
       case 'B':
         $kredit = $kr->sks * 3;  
         break;
       case 'C':
         $kredit = $kr->sks * 2;
         break;
       case 'D':
         $kredit = $kr->sks * 1;
         break;
       default:
         $kredit = 0;
         break;
     }

   if ($totalData == $no) {
   	$last_r = $rn;
     $objPHPExcel->getActiveSheet()->setCellValue('H'.$rn, $no)
								->setCellValue('I'.$rn, $kr->kode_mk)
								->setCellValue('J'.$rn, $kr->nama_mk)
								->setCellValue('K'.$rn, $kr->sks)
								->setCellValue('L'.$rn, $kr->nilai_huruf)
								->setCellValue('M'.$rn, $kredit);
		$objPHPExcel->getActiveSheet()->getStyle('H'.$rn)->applyFromArray($styleArray3)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('I'.$rn)->applyFromArray($styleArray3)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('J'.$rn)->applyFromArray($styleArray3);
		$objPHPExcel->getActiveSheet()->getStyle('K'.$rn)->applyFromArray($styleArray3)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('L'.$rn)->applyFromArray($styleArray3)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('M'.$rn)->applyFromArray($styleArray3)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
   }else {
     $objPHPExcel->getActiveSheet()->setCellValue('H'.$rn, $no)
								->setCellValue('I'.$rn, $kr->kode_mk)
								->setCellValue('J'.$rn, $kr->nama_mk)
								->setCellValue('K'.$rn, $kr->sks)
								->setCellValue('L'.$rn, $kr->nilai_huruf)
								->setCellValue('M'.$rn, $kredit);
		$objPHPExcel->getActiveSheet()->getStyle('H'.$rn)->applyFromArray($styleArray2)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('I'.$rn)->applyFromArray($styleArray2)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('J'.$rn)->applyFromArray($styleArray2);
		$objPHPExcel->getActiveSheet()->getStyle('K'.$rn)->applyFromArray($styleArray2)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('L'.$rn)->applyFromArray($styleArray2)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('M'.$rn)->applyFromArray($styleArray2)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
   }
    
     $no++;
     $rn++;
     $total_sks=$total_sks+$kr->sks;
     $total_kredit=$total_kredit+$kredit;
   }


// tabel footer ===================================================================

$objPHPExcel->getActiveSheet()->mergeCells('H'.$rn.':'.'J'.$rn)->setCellValue('H'.$rn, 'Total')
																->setCellValue('K'.$rn, $total_sks)
																->setCellValue('M'.$rn, $total_kredit);
$objPHPExcel->getActiveSheet()->getStyle('H'.$rn.':'.'M'.$rn)->applyFromArray($styleArray1);
$objPHPExcel->getActiveSheet()->getStyle('H'.$rn.':'.'M'.$rn)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('H'.$rn.':'.'M'.$rn)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 



// Footer Excel ===================================================================

$rn = ($last_l > $last_r) ? $last_l+2 : $last_r+2;
$objPHPExcel->getActiveSheet()->getStyle('A'.$rn.':'.'M'.($rn+5))->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->mergeCells('A'.$rn.':'.'B'.$rn)->setCellValue('A'.$rn, 'Judul Skripsi')->setCellValue('C'.$rn, ': Ini Adalah Judul Skripsi');
$rn += 2;
$objPHPExcel->getActiveSheet()->mergeCells('A'.$rn.':'.'B'.$rn)->setCellValue('A'.$rn, 'Tanggal Sidang')->setCellValue('C'.$rn++, ': 25 Juli 2020');
$objPHPExcel->getActiveSheet()->mergeCells('A'.$rn.':'.'B'.$rn)->setCellValue('A'.$rn, 'IPK')->setCellValue('C'.$rn++, ': '.$ipk_q->ipk .' ('. ipk_terbilang($ipk_q->ipk).')');
$objPHPExcel->getActiveSheet()->mergeCells('A'.$rn.':'.'B'.$rn)->setCellValue('A'.$rn, 'Predikat')->setCellValue('C'.$rn, ': Sangat Memuaskan');
$rn+=2;

$objPHPExcel->getActiveSheet()->setCellValue('J'.$rn++, "Bandung, ".tgl_indo(date('Y-m-d')));
$objPHPExcel->getActiveSheet()->setCellValue('C'.$rn, 'Dekan')->setCellValue('J'.$rn++, 'Ketua Jurusan');
$objPHPExcel->getActiveSheet()->setCellValue('C'.$rn, 'Fakultas Sains dan Teknologi,')->setCellValue('J'.$rn, ucwords(strtolower($m->nama_jur)));
$rn+=6;
$objPHPExcel->getActiveSheet()->setCellValue('C'.$rn, 'Dr. H. Opik Taupik Kurahman')->setCellValue('J'.$rn, 'Mohamad Irfan, ST., M.Kom');
$objPHPExcel->getActiveSheet()->getStyle('C'.$rn.':'.'J'.$rn++)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->setCellValue('C'.$rn, 'NIP. 196812141996031001')->setCellValue('J'.$rn, 'NIP. 198310232009121005');
$rn+=3;
$objPHPExcel->getActiveSheet()->setCellValue('A'.$rn, "Keterangan :");
$objPHPExcel->getActiveSheet()->getStyle('A'.$rn++)->getFont()->setSize(8)->setBold(true);
$objPHPExcel->getActiveSheet()->setCellValue('A'.$rn, "SKS : Satuan Kredit Semester, AK: Angka Kredit, IPK : Indeks Prestasi Kumulatif");
$objPHPExcel->getActiveSheet()->getStyle('A'.$rn++)->getFont()->setSize(8);


// Setup Akhir ===================================================================
$fontStyle = array(
 	'font' => array(
 		'name' => 'Times New Roman'
 	)
);

$objPHPExcel->getActiveSheet()->getStyle('A1:M'.$rn)->applyFromArray($fontStyle);
$objPHPExcel->getActiveSheet()->getStyle('A3:M'.($rn-3))->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('A9:M9')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A9:M9')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A'.$rn)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
// END SETUP AKHIR

// mengeset sheet 1 yang aktif
$objPHPExcel->setActiveSheetIndex(0);
 
// output file dengan nama file 'contoh.xls'
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Transkrip Akhir ('.$mhs_id.' '.$m->nama.').xls"');
header('Cache-Control: max-age=0');
 
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;


