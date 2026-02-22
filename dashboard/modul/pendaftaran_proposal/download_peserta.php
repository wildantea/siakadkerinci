<?php
session_start();
include "../../inc/config.php";
/** Error reporting */
error_reporting(E_ALL);

/** PHPExcel */
require_once '../../inc/lib/PHPExcel.php';
/** PHPExcel_IOFactory */
require_once '../../inc/lib/PHPExcel/IOFactory.php';
/*if ($_POST['jurusan']!='all') {
   $id_jurusan =$_POST['jurusan'];
    $qq=$db->query("select * from jurusan where kode_jurusan=$id_jurusan");
    foreach ($qq as $k) {
        $jurusan = $k->jurusan;
    }
}
*/
// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

/*$objPHPExcel->getDefaultStyle()->getFont()
    ->setName('Times New Roman')
    ->setSize(12);*/
    //set font size
//page setup 

$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_LEGAL);

//set margin 
$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.5);
$objPHPExcel->getActiveSheet()->getPageMargins()->setHeader(0.31);
$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.31);
$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(1.6);
$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.19);

//set width column
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(4);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(16);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(8);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(26);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(6);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(7);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(24);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(24);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(24);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(24);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(24);
// Set properties
$objPHPExcel->getProperties()->setCreator("Mark Baker")
                             ->setLastModifiedBy("Mark Baker")
                             ->setTitle("Office 2007 XLSX Test Document")
                             ->setSubject("Office 2007 XLSX Test Document")
                             ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                             ->setKeywords("office 2007 openxml php")
                             ->setCategory("Test result file");

// Add some data

$h1 = 2;
$h2 = 3;
$h3 = 4;
$h4 = 5;

$hcontent=8;
$content =$hcontent+2;


$head=0;
$allhead=6;



$periode = "";
$status = "";
$jurusan = "";

print_r($_POST);
exit();

if (isset($_POST['status'])) {
    
    if ($_POST['jurusan']!='all') {
       $jurusan = "and id_jurusan='".$_POST['jurusan']."'";
    }
    
    if ($_POST['status']=='all') {
       $status = "";
    } else {
     $status=" and kl.status='".$_POST['status']."'";
    }

    if ($_POST['periode']!='all') {
        $periode = explode("-", $_POST['periode']);
        $month = $periode[0];
        $year = $periode[1];
        $periode = " and month(jk.tgl_mulai)='$month' and year(jk.tgl_mulai)='$year'";
    } 
}



$dosen = $db->fetch_all('dekan_1');
foreach ($dosen as $dos) {
    $n_dekan = $dos->nama.", ".$dos->gelar;
    $nip_dekan = $dos->nip;
}


    $main = $db->query("select jr.kode_jurusan as jrsn,tgl_seminar as 
    tgl_sidang,jr.jurusan as jurusan,jk.bulan_aktif_id as id_bulan from bulan_aktif jk
    right join jurusan jr on jk.id_jurusan=jr.kode_jurusan 
    inner join siswa sw on jr.kode_jurusan=sw.kode_jurusan
    inner join muna kl on sw.nim=kl.nim 
    left join jadwal_ruang on kl.id_jadwal_ruang=jadwal_ruang.id
where jk.nama_sidang='muna' $jurusan $periode $status 
  group by jk.id_jurusan");

/*echo "select jr.kode_jurusan as jrsn,jk.tgl_seminar as 
    tgl_sidang,jr.jurusan as jurusan from bulan_aktif jk
right join jurusan jr on jk.id_jurusan=jr.kode_jurusan 
inner join siswa sw on jr.kode_jurusan=sw.kode_jurusan and sw.kp='N'
inner join kolokium kl on sw.nim=kl.nim where
 jr.kode_jurusan='".$_POST['jurusan']."' and 
 YEAR(tgl) = YEAR(NOW()) and jk.nama_sidang='muna' and month(tgl_akhir)='".date("m")."' group by jk.id_jurusan";die();
*/
/* if ($_POST['jurusan']=='all') {
    $id_jadwal = $db->fetch_custom_single('select bulan_aktif_id, month(tgl_akhir) as bulan,
    year(tgl_akhir) as tahun from bulan_aktif where 
    status_aktif="Y"    and nama_sidang="muna"');
 }else{
    $id_jadwal = $db->fetch_custom_single('select bulan_aktif_id, month(tgl_akhir) as bulan,
    year(tgl_akhir) as tahun from bulan_aktif where 
    status_aktif="Y"
    and id_jurusan=? and nama_sidang="muna"',array('id_jurusan'=>$_POST['jurusan']));
 }*/

if ($_POST['jurusan']!='all') {
   $objPHPExcel->getActiveSheet()->mergeCells('B7:D7');
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B7', 'Prodi/Jurusan : '.$jurusan);
}else{
    $objPHPExcel->getActiveSheet()->mergeCells('B7:D7');
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B7', 'Prodi/Jurusan : Semua Jurusan');
}


//KET TANGGAL

$objPHPExcel = PHPExcel_IOFactory::load("template.xlsx");
$objPHPExcel->getActiveSheet()->mergeCells('H7:I7');
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('H7', 'Tanggal : '.tgl_indo(date("Y-m-d")));
//header content
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$hcontent, 'NO')
        ->setCellValue('B'.$hcontent, 'NAMA/NIM')
            ->setCellValue('C'.$hcontent, 'JURUSAN')
        ->setCellValue('D'.$hcontent, 'JUDUL')
        ->setCellValue('E'.$hcontent, 'TEMPAT')
        ->setCellValue('F'.$hcontent, 'TANGGAL')
        ->setCellValue('G'.$hcontent, 'WAKTU')
            ->setCellValue('H'.$hcontent, 'PEMBIMBING')
             ->setCellValue('H'.($hcontent+1), 'I')
             ->setCellValue('I'.($hcontent+1), 'II')
            ->setCellValue('J'.$hcontent, 'PENGUJI')
             ->setCellValue('J'.($hcontent+1), 'I')
             ->setCellValue('K'.($hcontent+1), 'II');
//merge header content
$objPHPExcel->getActiveSheet()->mergeCells('A'.$hcontent.":A".($hcontent+1));
$objPHPExcel->getActiveSheet()->mergeCells('B'.$hcontent.":B".($hcontent+1));
$objPHPExcel->getActiveSheet()->mergeCells('C'.$hcontent.":C".($hcontent+1));
$objPHPExcel->getActiveSheet()->mergeCells('D'.$hcontent.":D".($hcontent+1));
$objPHPExcel->getActiveSheet()->mergeCells('E'.$hcontent.":E".($hcontent+1));
$objPHPExcel->getActiveSheet()->mergeCells('F'.$hcontent.":F".($hcontent+1));
$objPHPExcel->getActiveSheet()->mergeCells('G'.$hcontent.":G".($hcontent+1));
$objPHPExcel->getActiveSheet()->mergeCells('H'.$hcontent.":I".$hcontent);
$objPHPExcel->getActiveSheet()->mergeCells('J'.$hcontent.":K".$hcontent);


//set font 
$objPHPExcel->getActiveSheet()->getStyle("A".$hcontent.":J".($hcontent+1))->getFont()->setSize(9);
//aligment header content
$objPHPExcel->getActiveSheet()->getStyle('A'.$hcontent.":A".($hcontent+1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('B'.$hcontent.":B".($hcontent+1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setWrapText(true);  
$objPHPExcel->getActiveSheet()->getStyle('C'.$hcontent.":C".($hcontent+1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
$objPHPExcel->getActiveSheet()->getStyle('D'.$hcontent.":D".($hcontent+1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
$objPHPExcel->getActiveSheet()->getStyle('E'.$hcontent.":E".($hcontent+1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 

$objPHPExcel->getActiveSheet()->getStyle('G'.$hcontent.":H".$hcontent)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
$objPHPExcel->getActiveSheet()->getStyle('G'.($hcontent+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('H'.($hcontent+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('I'.$hcontent.":J".$hcontent)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
$objPHPExcel->getActiveSheet()->getStyle('I'.($hcontent+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
$objPHPExcel->getActiveSheet()->getStyle('J'.($hcontent+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);        
 $n=1;
foreach ($main as $dat) {


    $data = $db->query("select waktu_sidang,tgl_seminar, id_ruang,kl.nim as nim,ds_p1.nama as pem_1,ds_p1.gelar as gelar_p1,ds_p2.nama as pem_2,ds_p2.gelar as gelar_p2,ds_1.nama as peng_1,ds_1.gelar as gelar_1,
ds_2.nama as peng_2,ds_2.gelar as gelar_2,
kl.judul_skripsi as judul,sw.nama nama,jr.jurusan as jur from muna kl inner join siswa sw
on kl.nim=sw.nim 
inner join jurusan jr on sw.kode_jurusan=jr.kode_jurusan
left join dosen ds_1 on kl.penguji_1=ds_1.id 
left join dosen ds_2 on kl.penguji_2=ds_2.id
left join dosen ds_p1 on kl.pembimbing_1=ds_p1.id
left join dosen ds_p2 on kl.pembimbing_2=ds_p2.id
left join jadwal_ruang on kl.id_jadwal_ruang=jadwal_ruang.id
where kl.stat_sub='Y'  and kl.id_jadwal= $dat->id_bulan $status
");

$jml=$data->rowCount();
$plus=$jml+$head;
$bor=$jml+$hcontent+1;

//$plus=$ct+$ts+$pl;
$con=$content+$jml+$hcontent;
//KET JURUSAN
$content=$hcontent+2;
$end = $jml+7;
$ns=10;
 
//set jurusan 
$objPHPExcel->setActiveSheetIndex(0)
             ->setCellValue('C'.$content, $dat->jurusan);

//merge jurusan
 $objPHPExcel->getActiveSheet()->mergeCells('C'.$content.':C'.$bor);

//set aligment jurusan
 $objPHPExcel->getActiveSheet()->getStyle('C'.$content.':C'.$bor)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setWrapText(true);
 $objPHPExcel->getActiveSheet()->getStyle('C'.$content)->getAlignment()->setTextRotation(-90);
//set ruang 
       /* $objPHPExcel->setActiveSheetIndex(0)
             ->setCellValue('E'.$content, $dat->ruang);*/

//merge ruang
        //Tempat
        $objPHPExcel->getActiveSheet()->getStyle('E'.$ns.':E'.$bor)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);   
        $objPHPExcel->getActiveSheet()->mergeCells('E'.$ns.':E'.$bor);
        $objPHPExcel->getActiveSheet()->getStyle('E'.$ns)->getAlignment()->setTextRotation(-90);

//content goes here 


 foreach ($data as $key) {
          $objPHPExcel->getActiveSheet()->getRowDimension($content)->setRowHeight(40);
          $objPHPExcel->getActiveSheet()->getRowDimension($bor+1)->setRowHeight(23.5);
$objPHPExcel->getActiveSheet()->getStyle("A".$content.":G".$bor)->getFont()->setSize(8);
$objPHPExcel->getActiveSheet()->getStyle('A'.$content.":A".($bor))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
$objPHPExcel->getActiveSheet()->getStyle('B'.$content.":B".($bor))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)->setWrapText(true);  
$objPHPExcel->getActiveSheet()->getStyle('D'.$content.":D".($bor))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)->setWrapText(true);  
$objPHPExcel->getActiveSheet()->getStyle('F'.$content.":F".($bor))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);  
$objPHPExcel->getActiveSheet()->getStyle('G'.$content.":G".($bor))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);  
$objPHPExcel->getActiveSheet()->getStyle('H'.$content.":H".($bor))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);  
$objPHPExcel->getActiveSheet()->getStyle('I'.$content.":I".($bor))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);  
$objPHPExcel->getActiveSheet()->getStyle('J'.$content.":J".($bor))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);  
$objPHPExcel->getActiveSheet()->getStyle('K'.$content.":K".($bor))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);  

    if ($key->id_ruang!=NULL) {
         $ruangan = $db->fetch_single_row("ruang_sidang",'id',$key->id_ruang);
         $ruangan = $ruangan->nama_ruang;
    } else {
        $ruangan = "";
    }
   
                $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$content, $n)
            ->setCellValue('B'.$content, ucwords(strtolower($key->nama))."\n ".$key->nim)
             ->setCellValue('D'.$content, $key->judul)
             ->setCellValue('H'.$content, $key->pem_1.", ".$key->gelar_p1)
             ->setCellValue('I'.$content, $key->pem_2.", ".$key->gelar_p2)
             ->setCellValue('E'.$content, $ruangan)
             ->setCellValue('F'.$content, $key->tgl_seminar)
             ->setCellValue('G'.$content, $key->waktu_sidang)
             ->setCellValue('J'.$content, $key->peng_1.", $key->gelar_1")
             ->setCellValue('K'.$content, $key->peng_2.", $key->gelar_2");
             $n++;
             $content++;
             // $ts++;
             // $end++;
            }
         
$objPHPExcel->getActiveSheet()->getStyle("J".($bor+2).":J".($bor+8))->getFont()->setSize(8);

//fill color

$objPHPExcel->getActiveSheet()->getStyle('A1:J'.($bor+20))->getFill()
->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
->getStartColor()->setARGB('FFFFFFFF');

 $styleArray = array(
      'borders' => array(
          'allborders' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
          )
      )
  );
$objPHPExcel->getActiveSheet()->getStyle('A'.$hcontent.":K".$bor)->applyFromArray($styleArray);


$h1+=$plus;
$h2+=$plus;
$h3+=$plus;
$h4+=$plus;
$hcontent+=$plus;
$content+=$plus;

//echo $content."<br>";


    }
     $objPHPExcel->setActiveSheetIndex(0)
             ->setCellValue('K'.($bor+2), "Bandung, ".tgl_indo(date('Y-m-d')))
             ->setCellValue('K'.($bor+3), "Pembantu Dekan I,")
             ->setCellValue('K'.($bor+7), $n_dekan)
             ->setCellValue('K'.($bor+8), "NIP. ".$nip_dekan);

// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('Daftar Hadir Peserta Munaqosah');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
//$sheet->getStyle($column.$style)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Daftar_Hadir_Peserta_munaqosah.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
return $objWriter->save('php://output');

/*$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));*/
exit;
?>

