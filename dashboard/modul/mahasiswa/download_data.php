<?php
require_once '../../inc/lib/Writer.php';


$writer = new XLSXWriter();
$style =
        array (
            array(
              'border' => array(
                'style' => 'thin',
                'color' => '000000'
                ),
            'allfilleddata' => true
            ),
            array(
                'fill' => array(
                    'color' => 'ff0000'
                    ),
                'cells' => array(
                   'A1','B1','L1','M1','N1','AA1','AJ1'
                    ),
                'border' => array(
                    'style' => 'thin',
                    'color' => '000000'
                    ),
                'verticalAlign' => 'center',
                'horizontalAlign' => 'center',
            ),
            
            array(
                'fill' => array(
                    'color' => '00ff00'
                    ),
                'cells' => array(
					'C1',
					'D1',
					'E1',
					'F1',
					'G1',
					'H1',
					'I1',
					'J1',
					'K1',
					'O1',
					'P1',
					'Q1',
					'R1',
					'S1',
					'T1',
					'U1',
					'V1',
					'W1',
					'X1',
					'Y1',
					'Z1',
					'AB1',
					'AI1',
					'AK1',
					'AL1',
					'AM1',
					'AN1',
					'AO1',
					'AP1',
					'AQ1',
					'AR1',
					'AS1',
					'AT1',
                'AC1',
				'AD1',
				'AE1',
				'AF1',
				'AG1',
				'AH1',
				'AU1',
				'AV1',
        'AW1',
        'AX1',
        'AY1',
        'AZ1',
        'BA1',
        'BB1'
                    ),
                'border' => array(
                    'style' => 'thin',
                    'color' => '000000'
                    ),
                'verticalAlign' => 'center',
                'horizontalAlign' => 'center',
            ),
        );

//column width
$col_width = array(
  1 => 27,
  2 => 27,
  3 => 27,
  4 => 27,
  5 => 27,
  6 => 27,
  7 => 19,
  8 => 19,
  9 => 19,
  10 => 19,
  11 => 19,
  12 => 19,
  13 => 19,
  14 => 19,
 15 => 19,
  16 => 19,
  17 => 19,
  18 => 19,
  19 => 19,
  20 => 19,
  21 => 19,
  22 => 19,
 23 => 19,
  24 => 19,
  25 => 19,
  26 => 19,
  27 => 19,
  28 => 19,
  29 => 19,
  30 => 19,
 31=> 19,
  32 => 19,
  33 => 19,
  34 => 19,
  35 => 19,
  36 => 19,
  37 => 19,
  38 => 19,
  
  39 => 19,
  40 => 19,
  41 => 19,
  42 => 19,
  43 => 19,
  44 => 19,
  45 => 19,
  46 => 19,
  47 => 19,
  48 => 30,
  49 => 40,
  50 => 40, 
  51 => 40,
  52 => 20,
  53 => 30,
  54 => 30
  );

$writer->setColWidth($col_width);

$header = array(
            'NIM' =>'string',
             'NAMA' =>'string',
             'Tempat Lahir' =>'string',
             'Tanggal Lahir' =>'string',
             'Jenis Kelamin' =>'string',
             'NIK' =>'string',
             'Agama' =>'string',
             'NISN' =>'string',
             'Jalur Pendaftaran' =>'string',
             'NPWP' =>'string',
             'Kewarganegaraan' =>'string',
             'Jenis Pendaftaran' =>'string',
             'Tgl Masuk Kuliah' =>'string',
             'Mulai Semester' =>'string',
             'Jalan' =>'string',
             'RT' =>'string',
             'RW' =>'string',
             'Nama Dusun' =>'string',
             'Kelurahan' =>'string',
             'Kecamatan' =>'string',
             'Kode Pos' =>'string',
             'Jenis Tinggal' =>'string',
             'Alat Transportasi' =>'string',
             'Telp Rumah' =>'string',
             'No HP' =>'string',
             'Email' =>'string',
             'Terima KPS' =>'string',
            'No KPS' =>'string',
             'NIK Ayah' =>'string',
             'Nama Ayah' =>'string',
             'Tgl Lahir Ayah' =>'string',
             'Pendidikan Ayah' =>'string',
             'Pekerjaan Ayah' =>'string',
             'Penghasilan Ayah' =>'string',
             'NIK Ibu' =>'string',
             'Nama Ibu' =>'string',
             'Tanggal Lahir Ibu' =>'string',
             'Pendidikan Ibu' =>'string',
             'Pekerjaan Ibu' =>'string',
             'Penghasilan Ibu' =>'string',
             'Nama Wali' =>'string',
             'Tanggal Lahir wali' =>'string',
             'Pendidikan Wali' =>'string',
             'Pekerjaan Wali' =>'string',
             'Penghasilan Wali' =>'string',
             'Kode Prodi' => 'string',
             'Jenis Pembiayaan' => 'string',
             'NIDN Dosen PA' => 'string',
             'Asal Sekolah' => 'string',
             'Nama Sekolah' => 'string',
             'STATUS MAHASISWA' => 'string',
             'Lengkap Data' => 'string',
             'Asal Perguruan Tinggi' => 'string',
             'Asal Program Studi' => 'string'
);

$data_rec = array();

function getJenisDaftar() {
  global $db2;
  $jenis_daftars = $db2->query("select * from jenis_daftar");
  foreach ($jenis_daftars as $jenis_daftar) {
    $data_jenis[$jenis_daftar->id_jenis_daftar] = $jenis_daftar->nm_jns_daftar;
  }
  return $data_jenis;
}
function getJenisKeluar() {
  global $db2;
  $jns_keluars = $db2->query("select * from jenis_keluar");
  foreach ($jns_keluars as $jns_keluar) {
    $data_jenis_keluar[$jns_keluar->id_jns_keluar] = $jns_keluar->ket_keluar;
  }
  return $data_jenis_keluar;
}

$semester_aktif = $db->fetch_single_row("semester_ref","aktif",1);

$periode_cuti = $semester_aktif->semester;


$jur_kode = "";
//get default akses prodi 
$akses_prodi = get_akses_prodi();
//echo "$akses_prodi";
$akses_jur = $db->fetch_custom_single("select group_concat(kode_jur) as kode_jur from view_prodi_jenjang $akses_prodi");
if ($akses_jur) {
  $jur_kode = "and jur_kode in(".$akses_jur->kode_jur.")";
} else {
  //jika tidak group tidak punya akses prodi, set in 0
  $jur_kode = "and jur_kode in(0)";
}
 
  $prodi_jenjang = getProdiJenjang(); 
 // print_r($prodi_jenjang); 
  //print_r($prodi_jenjang);
  $jenis_daftar = getJenisDaftar();
  $jenis_keluar_array = getJenisKeluar();
  $mulai_smt = "";
  $jk = "";
  $jenis_keluar = "";
  $mulai_smt_end = "";
  $id_jenis_daftar = "";
  $jenjang = "";
  $is_cuti = 0;
  $where_is_cuti = "";
  $is_submit_biodata = '';
  $fakultas = "";
 $on_kelulusan = " tb_data_kelulusan.nim=mahasiswa.nim";
  if (isset($_POST['jur_kode'])) {
    if ($_POST['periode_cuti']!='all') {
  $periode_cuti = $_POST['periode_cuti'];
}
      if ($_POST['jur_kode']!='all') {
        $jur_kode = ' and jur_kode="'.$_POST['jur_kode'].'"';
      }
  
      if ($_POST['mulai_smt_end']!='all') {
        $mulai_smt_end = $_POST['mulai_smt_end'];
      }
      if ($_POST['mulai_smt']!='all') {
        if ($mulai_smt_end!="") {
            $mulai_smt = ' and left(mulai_smt,4) between '.$_POST['mulai_smt'].' and '.$mulai_smt_end;
        } else {
            $mulai_smt = ' and left(mulai_smt,4)="'.$_POST['mulai_smt'].'"';
        }

      }

      if ($_POST['jenjang']!='all') {
        $jenjang = ' and id_jenjang="'.$_POST['jenjang'].'"';
      }

      if ($_POST['id_jenis_daftar']!='all') {
        $id_jenis_daftar = ' and id_jns_daftar="'.$_POST['id_jenis_daftar'].'"';
      }
  
      if ($_POST['jk']!='all') {
        $jk = ' and jk="'.$_POST['jk'].'"';
      }

    // if ($_POST['fakultas']!='all') {
    //   $fakultas = getProdiFakultas('mahasiswa.jur_kode',$_POST['fakultas']);
    // }
  
      if ($_POST['jenis_keluar']!='all') {

        if ($_POST['jenis_keluar']=='aktif') {
          $id_jenis_daftar = 'and id_jenis_keluar is null and mahasiswa.status="M"';
        } elseif ($_POST['jenis_keluar']=='calon') {
          $id_jenis_daftar = 'and mahasiswa.status="CM"';
        } elseif ($_POST['jenis_keluar']=='cuti') {
            $is_cuti = 1;
            $where_is_cuti = "and (select tb_data_cuti_mahasiswa.id_cuti from tb_data_cuti_mahasiswa inner join tb_data_cuti_mahasiswa_periode
using (id_cuti) where tb_data_cuti_mahasiswa_periode.periode='$periode_cuti' and tb_data_cuti_mahasiswa.nim=mahasiswa.nim) is not null";
        } else {
          $jenis_keluar = "and tb_data_kelulusan.id_jenis_keluar='".$_POST['jenis_keluar']."'";
          
        }
      }

       if ($_POST['is_submit_biodata']!='all') {
        $is_submit_biodata = ' and is_submit_biodata="'.$_POST['is_submit_biodata'].'"';
      }
  
}



/*  if ($is_cuti==0) {
    $qp = "select  mahasiswa.*,rs.nama_jenis_sekolah,(select tb_data_cuti_mahasiswa.id_cuti from tb_data_cuti_mahasiswa inner join tb_data_cuti_mahasiswa_periode using (id_cuti) where tb_data_cuti_mahasiswa_periode.periode='$periode_cuti' and tb_data_cuti_mahasiswa.nim=mahasiswa.nim) as is_cuti,tb_data_kelulusan.id_jenis_keluar as jns_keluar  from mahasiswa inner join jurusan on mahasiswa.jur_kode=jurusan.kode_jur  left join tb_data_kelulusan on ($on_kelulusan) left join tb_ref_jenis_asal_sekolah rs on rs.id_jenis_sekolah=mahasiswa.id_jenis_sekolah  where mhs_id is not null $jur_kode $mulai_smt $jk $id_jenis_daftar $jenis_keluar $jenjang $where_is_cuti"; 
      $temp_rec = $db->query($qp);

  } else {
    $qp = "select  DISTINCT mahasiswa.nim,mahasiswa.*,rs.nama_jenis_sekolah,(select tb_data_cuti_mahasiswa.id_cuti from tb_data_cuti_mahasiswa inner join tb_data_cuti_mahasiswa_periode using (id_cuti) where tb_data_cuti_mahasiswa_periode.periode='$periode_cuti' and tb_data_cuti_mahasiswa.nim=mahasiswa.nim) as is_cuti,tb_data_kelulusan.id_jenis_keluar as jns_keluar  from mahasiswa inner join jurusan on  mahasiswa.jur_kode=jurusan.kode_jur  left join tb_data_kelulusan on ($on_kelulusan)  left join tb_ref_jenis_asal_sekolah rs on rs.id_jenis_sekolah=mahasiswa.id_jenis_sekolah  where mhs_id is not null $jur_kode $mulai_smt $jk $id_jenis_daftar $jenis_keluar $jenjang $where_is_cuti ";
      $temp_rec = $db->query($qp);
  }

  if ($_POST['jenis_keluar']=='aktif') {
    $qp = "select mahasiswa.*,'' as lulus,'' as is_cuti from mahasiswa inner join jurusan on mahasiswa.jur_kode=jurusan.kode_jur  join akm a on (a.mhs_nim=mahasiswa.nim and a.sem_id='$sem_id' and a.id_stat_mhs='A' )   where mhs_id is not null $jur_kode $mulai_smt $jk $id_jenis_daftar $jenjang group by mahasiswa.nim";
      $temp_rec = $db->query($qp); 
  }else{
      $qp = "select mahasiswa.*,kl.id_jenis_keluar as lulus,c.nim as is_cuti from mahasiswa inner join jurusan on mahasiswa.jur_kode=jurusan.kode_jur  left join akm a on (a.mhs_nim=mahasiswa.nim and a.sem_id='$sem_id' and a.id_stat_mhs='A' ) left join tb_data_kelulusan kl on kl.nim=mahasiswa.nim left join tb_data_cuti_mahasiswa c on (c.nim=mahasiswa.nim) left join tb_data_cuti_mahasiswa_periode p on (p.id_cuti=c.id_cuti and p.periode='".$_POST['periode_cuti']."') where mhs_id is not null $jur_kode $mulai_smt $jk $id_jenis_daftar $jenjang group by mahasiswa.nim";
      $temp_rec = $db->query($qp);
  }*/

  $is_mahasiswa = '';
if ($_SESSION['group_level']!='admin') {
  $is_mahasiswa = 'and mahasiswa.status="M"';
}



  $temp_rec = $db->query("select mahasiswa.*, 

    (select dosen
 from view_dosen where view_dosen.nip=mahasiswa.dosen_pemb)  as nama_dosen, s.foto_user,mhs_id,mahasiswa.nim,mahasiswa.nama,mulai_smt,id_jns_daftar,jur_kode,tb_data_kelulusan.id_jenis_keluar as jns_keluar,(select tb_data_cuti_mahasiswa.id_cuti from tb_data_cuti_mahasiswa inner join tb_data_cuti_mahasiswa_periode
using (id_cuti) where tb_data_cuti_mahasiswa_periode.periode='$periode_cuti' and tb_data_cuti_mahasiswa.nim=mahasiswa.nim) as is_cuti
 from mahasiswa inner join jurusan on mahasiswa.jur_kode=jurusan.kode_jur
 left join tb_data_kelulusan  on ($on_kelulusan)
 left join sys_users s on s.username=mahasiswa.nim where mhs_id is not null $jur_kode $fakultas $mulai_smt $jk $id_jenis_daftar $jenis_keluar $jenjang $is_mahasiswa $is_submit_biodata

 ");

  // echo "<pre>";
  // print_r($_POST); 
  // echo "$qp "; 
  // die();  

  foreach ($temp_rec as $key) {
    
/*    if ($_POST['jenis_keluar']=='aktif') {
       $status_mhs = 'Aktif';
    }else{
      if ($key->is_cuti!='' && $key->lulus=='') {
       // if ($key->is_cuti!='') {
       //   $status_mhs = 'Cuti';
       // } else {
       $status_mhs = 'Cuti';
     //  }

      }else if ($key->is_cuti=='' && $key->lulus=='') {
       // if ($key->is_cuti!='') {
       //   $status_mhs = 'Cuti';
       // } else {
       $status_mhs = 'Aktif';
     //  }

      }
      //  elseif ($key->lulus=='' or $key->jns_keluar=='2') {
      //    $status_mhs = $jenis_keluar_array[$key->jns_keluar];
      // }
       else { 
        $status_mhs = $jenis_keluar_array[$key->lulus];
      }
    }*/

    if ($key->jns_keluar=='') {
       if ($key->is_cuti!='') {
         $status_mhs = 'Cuti';
       } else {
        if ($key->status=='CM') {
         $status_mhs = 'Calon Mahasiswa';
        }else{
          $status_mhs = 'Aktif';
        }
       
       }

    } elseif ($key->jns_keluar=='1' or $key->jns_keluar=='2') {
       $status_mhs = $jenis_keluar_array[$key->jns_keluar];
    } else {
      $status_mhs = $jenis_keluar_array[$key->jns_keluar];
    }

    $lengkap = 'Belum';
    if ($key->is_submit_biodata=='Y') {
      $lengkap = 'Lengkap';
    }



                      $data_rec[] = array(
                  $key->nim,
									$key->nama,
									$key->tmpt_lahir,
									$key->tgl_lahir,
									$key->jk,
									$key->nik,
									$key->id_agama,
									$key->nisn,
									$key->id_jalur_masuk,
									$key->npwp,
									$key->kewarganegaraan,
									$key->id_jns_daftar,
									$key->tgl_masuk_sp,
									$key->mulai_smt,
									$key->jln,
									$key->rt,
									$key->rw,
									$key->nm_dsn,
									$key->ds_kel,
									$key->id_wil,
									$key->kode_pos,
									$key->id_jns_tinggal,
									$key->id_alat_transport,
									$key->no_tel_rmh,
									$key->no_hp,
									$key->email,
									$key->a_terima_kps,
									$key->no_kps,
									$key->nik_ayah,
									$key->nm_ayah,
									$key->tgl_lahir_ayah,
									$key->id_jenjang_pendidikan_ayah,
									$key->id_pekerjaan_ayah,
									$key->id_penghasilan_ayah,
									$key->nik_ibu_kandung,
									$key->nm_ibu_kandung,
									$key->tgl_lahir_ibu,
									$key->id_jenjang_pendidikan_ibu,
									$key->id_pekerjaan_ibu,
									$key->id_penghasilan_ibu,
									$key->nm_wali,
									$key->tgl_lahir_wali,
									$key->id_jenjang_pendidikan_wali,
									$key->id_pekerjaan_wali,
									$key->id_penghasilan_wali,
									$key->jur_kode,
									$key->id_pembiayaan,
									$key->dosen_pemb,
                  $key->id_jenis_sekolah,
                  $key->nama_asal_sekolah,
                  $status_mhs,
                  $lengkap,
                  $key->kode_pt_asal,
                  $key->kode_prodi_asal
                        );

            }


ob_clean(); 
flush();
$filename = 'Mahasiswa.xlsx';
header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');
$writer->writeSheet($data_rec,'Data Mahasiswa', $header, $style);
$writer->writeToStdOut();
exit(0);
?>