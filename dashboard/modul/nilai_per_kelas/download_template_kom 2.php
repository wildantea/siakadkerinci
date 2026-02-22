<?php
include "../../inc/config.php";
// Load plugin PHPExcel nya
require_once '../../inc/lib/vendor/autoload.php';

$kelas_data = $db2->fetchCustomSingle("SELECT tdk.kelas_id,tdk.kls_nama,kuota,kode_jur,ada_komponen,komponen,vmk.kode_mk,tdk.sem_id,vmk.nama_mk,vmk.total_sks,vmk.kode_jur,vmk.kode_jur, vmk.nama_jurusan,(select group_concat(nama_gelar separator '#') from view_dosen_kelas where kelas_id=tdk.kelas_id) as nama_dosen from tb_data_kelas tdk
INNER JOIN view_matakuliah_kurikulum vmk using(id_matkul) where kelas_id=?",array('kelas_id' => $_GET['kelas_id']));

// Include librari PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
// Buat sebuah variabel untuk menampung pengaturan style dari header tabel
$style_col = [
    'font' => ['bold' => true], // Set font nya jadi bold
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
    ],
    'borders' => [
        'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
        'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
        'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
        'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
    ]
];
// Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
$style_row = [
    'alignment' => [
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
    ],
    'borders' => [
        'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
        'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
        'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
        'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
    ]
];
$style_col_head = [
    'font' => ['bold' => true], // Set font nya jadi bold
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT, // Set text jadi ditengah secara horizontal (center)
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
    ]
];
$sheet->setCellValue('A1', "Program Studi"); // Set kolom A1 dengan tulisan "DATA SISWA"
$sheet->mergeCells('A1:B1'); // Set Merge Cell pada kolom A1 sampai F1
$sheet->getStyle('A1')->getFont()->setBold(true); // Set bold kolom A1
$sheet->getStyle('A1')->getFont()->setSize(15); // Set font size 15 untuk kolom A1

$sheet->setCellValue('C1',$kelas_data->nama_jurusan); // Set kolom A1 dengan tulisan "DATA SISWA"
$sheet->mergeCells('C1:E1'); // Set Merge Cell pada kolom A1 sampai F1
$sheet->getStyle('C1')->getFont()->setSize(15); // Set font size 15 untuk kolom A1

$sheet->getStyle('A1:B1')->applyFromArray($style_col_head);
$sheet->getStyle('A2:B2')->applyFromArray($style_col_head);
$sheet->getStyle('A3:B3')->applyFromArray($style_col_head);
$sheet->getStyle('A4:B4')->applyFromArray($style_col_head);


$sheet->setCellValue('A2', "Matakuliah	"); // Set kolom A1 dengan tulisan "DATA SISWA"
$sheet->mergeCells('A2:B2'); // Set Merge Cell pada kolom A1 sampai F1
$sheet->getStyle('A2')->getFont()->setBold(true); // Set bold kolom A1
$sheet->getStyle('A2')->getFont()->setSize(15); // Set font size 15 untuk kolom A1


$sheet->setCellValue('C2', $kelas_data->kode_mk.' - '.$kelas_data->nama_mk.' ('.$kelas_data->total_sks.' sks)'); // Set kolom A1 dengan tulisan "DATA SISWA"
$sheet->mergeCells('C2:E2'); // Set Merge Cell pada kolom A1 sampai F1
$sheet->getStyle('C2')->getFont()->setSize(15); // Set font size 15 untuk kolom A1


$sheet->setCellValue('A3', "Periode"); // Set kolom A3 dengan tulisan "DATA SISWA"
$sheet->mergeCells('A3:B3'); // Set Merge Cell pada kolom A3 sampai F1
$sheet->getStyle('A3')->getFont()->setBold(true); // Set bold kolom A3
$sheet->getStyle('A3')->getFont()->setSize(15); // Set font size 15 untuk kolom A3

$sheet->setCellValue('C3', getPeriode($kelas_data->sem_id)); // Set kolom A1 dengan tulisan "DATA SISWA"
$sheet->mergeCells('C3:E3'); // Set Merge Cell pada kolom A1 sampai F1
$sheet->getStyle('C3')->getFont()->setSize(15); // Set font size 15 untuk kolom A1

$sheet->setCellValue('A4', "Kelas"); // Set kolom A4 dengan tulisan "DATA SISWA"
$sheet->mergeCells('A4:B4'); // Set Merge Cell pada kolom A4 sampai F1
$sheet->getStyle('A4')->getFont()->setBold(true); // Set bold kolom A4
$sheet->getStyle('A4')->getFont()->setSize(15); // Set font size 15 untuk kolom A4

$sheet->setCellValue('C4', $kelas_data->kls_nama); // Set kolom A1 dengan tulisan "DATA SISWA"
$sheet->mergeCells('C4:E4'); // Set Merge Cell pada kolom A1 sampai F1
$sheet->getStyle('C4')->getFont()->setSize(15); // Set font size 15 untuk kolom A1


$sheet->setCellValue('F4', "Silakan Isi dengan Nilai Angka skala 100"); // Set kolom A1 dengan tulisan "DATA SISWA"
$sheet->mergeCells('F4:I4'); // Set Merge Cell pada kolom A1 sampai F1
$sheet->getStyle('F4')->getFont()->setBold(true); // Set bold kolom A1
$sheet->getStyle('F4')->getFont()->setSize(15); // Set font size 15 untuk kolom A1

// Buat header tabel nya pada baris ke 6
$sheet->setCellValue('A6', "NO"); // Set kolom A6 dengan tulisan "NO"
$sheet->setCellValue('B6', "NIM"); // Set kolom B6 dengan tulisan "NIS"
$sheet->setCellValue('C6', "NAMA"); // Set kolom C6 dengan tulisan "NAMA"
$sheet->setCellValue('D6', "ANGKATAN"); // Set kolom D6 dengan tulisan "JENIS KELAMIN"

$alphas = range('E', 'Z');
$huruf_dipakai = array();
if ($kelas_data->ada_komponen=='Y') {
    $komponen = json_decode($kelas_data->komponen);
       foreach ($komponen as $key) {
        if (is_array($key)) {
            $i=0;
            foreach ($key as $val) {
                $huruf_dipakai[] = $alphas[$i];
                $leng = strlen($val->nama_komponen.' ('.$val->value_komponen.')');
                $sheet->setCellValue($alphas[$i].'6', $val->nama_komponen.' ('.$val->value_komponen.')');
                $sheet->getStyle($alphas[$i].'6')->applyFromArray($style_col);
                $sheet->getColumnDimension($alphas[$i])->setWidth($leng+2); // Set width kolom B
                $last_huruf = $alphas[$i];
                $i++;
            }
        }
       }
} 

$last_index_plus = $i;

// Apply style header yang telah kita buat tadi ke masing-masing kolom header
$sheet->getStyle('A6')->applyFromArray($style_col);
$sheet->getStyle('B6')->applyFromArray($style_col);
$sheet->getStyle('C6')->applyFromArray($style_col);
$sheet->getStyle('D6')->applyFromArray($style_col);
$sheet->getStyle('E6')->applyFromArray($style_col);
$sheet->getStyle('F6')->applyFromArray($style_col);

$sheet->setCellValue($alphas[$last_index_plus].'6', "NILAI AKHIR");
$sheet->getStyle($alphas[$last_index_plus].'6')->applyFromArray($style_col);
$sheet->getColumnDimension($alphas[$last_index_plus])->setWidth(12);


$spreadsheet->getActiveSheet()->getStyle('A6:'.$alphas[$last_index_plus].'6')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('92D050');

// Set height baris ke 1, 2 dan 3
$sheet->getRowDimension('1')->setRowHeight(25);
$sheet->getRowDimension('2')->setRowHeight(25);
$sheet->getRowDimension('3')->setRowHeight(25);
$sheet->getRowDimension('4')->setRowHeight(25);
// Buat query untuk menampilkan semua data siswa
$sheet->getRowDimension('6')->setRowHeight(28);

$sql = $db2->query("select tb_data_kelas_krs_detail.krs_detail_id,nilai_angka,nilai_huruf,nilai_indeks,tb_data_kelas_krs.nim,nama,left(mulai_smt,4) as angkatan from tb_data_kelas_krs_detail inner join tb_data_kelas_krs using(krs_id) inner join tb_master_mahasiswa using(nim) where kelas_id=? and tb_data_kelas_krs_detail.disetujui='1' order by tb_data_kelas_krs.nim asc",array('kelas_id' => $_GET['kelas_id']));

$no = 1; // Untuk penomoran tabel, di awal set dengan 1
$row = 7; // Set baris pertama untuk isi tabel adalah baris ke 4
foreach($sql as $data) { // Ambil semua data dari hasil eksekusi $sql
    $sheet->setCellValue('A' . $row, $no);
    $sheet->setCellValue('B' . $row, $data->nim);
    $sheet->setCellValue('C' . $row, $data->nama);
    $sheet->setCellValue('D' . $row, $data->angkatan);
    // Khusus untuk no telepon. kita set type kolom nya jadi STRING
    //$sheet->setCellValueExplicit('E' . $row, '', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
    //$sheet->setCellValue('F' . $row, $data->nilai_angka);
    // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
    $sheet->getStyle('A' . $row)->applyFromArray($style_row);
    $sheet->getStyle('B' . $row)->applyFromArray($style_row);
    $sheet->getStyle('C' . $row)->applyFromArray($style_row);
    $sheet->getStyle('D' . $row)->applyFromArray($style_row);
    if (!empty($huruf_dipakai)) {
        foreach ($huruf_dipakai as $huruf ) {
            $sheet->getStyle($huruf . $row)->applyFromArray($style_row);
        }
        $sheet->getStyle($alphas[$last_index_plus] . $row)->applyFromArray($style_row);
    }
    $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom No
    $sheet->getStyle('B' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT); // Set text left untuk kolom NIS
    $sheet->getStyle('D' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT); // Set text left untuk kolom NIS
    $sheet->getRowDimension($row)->setRowHeight(20); // Set height tiap row
    $no++; // Tambah 1 setiap kali looping
    $row++; // Tambah 1 setiap kali looping
}

// Set width kolom
$sheet->getColumnDimension('A')->setWidth(5); // Set width kolom A
$sheet->getColumnDimension('B')->setWidth(15); // Set width kolom B
$sheet->getColumnDimension('C')->setWidth(25); // Set width kolom C
$sheet->getColumnDimension('D')->setWidth(10); // Set width kolom D

// Set orientasi kertas jadi LANDSCAPE
$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);


// Set judul file excel nya
$sheet->setTitle("Template Nilai");
// Proses file excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="template_nilai_kelas.xlsx"'); // Set nama file excel nya
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
?>