<?php
session_start();
include "../../../inc/config.php";
$jur_filter = "";
$jur_filter = aksesProdi('vpj.kode_jur');
//default semester aktif
$semester_aktif = $db->fetch_single_row("semester_ref","aktif",1);
$sem_filter = "and view_krs_single2.id_semester='".$semester_aktif->id_semester."'";
$semester_krs = get_sem_aktif();
$is_bayar = "";
$disetuji = "";
$angkatan_filter = "";
$fakultas = "";
$mulai_smt = "";
$mulai_smt_end = "";

  if (isset($_POST['jur_filter'])) {

  if ($_POST['jur_filter']!='all') {
    $jur_filter = ' and vpj.kode_jur="'.$_POST['jur_filter'].'"';
  }

  if ($_POST['sem_filter']!='all') {
    $sem_filter = ' and view_krs_single2.id_semester="'.$_POST['sem_filter'].'"';
    $semester_krs = $_POST['sem_filter'];
  }

  if ($_POST['is_bayar']!='all') {
    if ($_POST['is_bayar']==1) {
    $is_bayar = "and (select keu_tagihan_mahasiswa.id from keu_tagihan_mahasiswa inner join keu_tagihan on id_tagihan_prodi=keu_tagihan.id
    inner join keu_jenis_tagihan using(kode_tagihan)
    where nim=view_krs_single2.nim and periode=view_krs_single2.id_semester and syarat_krs='Y'
    and keu_tagihan_mahasiswa.id in(select id_keu_tagihan_mhs from keu_bayar_mahasiswa where id_keu_tagihan_mhs=keu_tagihan_mahasiswa.id)) is not null";
    } elseif ($_POST['is_bayar']==2) {
        $is_bayar = "and (select keu_tagihan_mahasiswa.id from keu_tagihan_mahasiswa inner join keu_tagihan on id_tagihan_prodi=keu_tagihan.id
        inner join keu_jenis_tagihan using(kode_tagihan)
        where nim=view_krs_single2.nim and periode=view_krs_single2.id_semester and syarat_krs='Y' and nominal_tagihan-potongan=0) is not null";
    } else {
    $is_bayar = "and (select keu_tagihan_mahasiswa.id from keu_tagihan_mahasiswa inner join keu_tagihan on id_tagihan_prodi=keu_tagihan.id
    inner join keu_jenis_tagihan using(kode_tagihan)
    where nim=view_krs_single2.nim and periode=view_krs_single2.id_semester and syarat_krs='Y' and nominal_tagihan-potongan<>0
    and keu_tagihan_mahasiswa.id not in(select id_keu_tagihan_mhs from keu_bayar_mahasiswa where id_keu_tagihan_mhs=keu_tagihan_mahasiswa.id)) is not null";
    }

  }
  if ($_POST['disetujui']!='all') {
      if ($_POST['disetujui']!='1') {
        //$disetuji = "and view_krs_single2.disetujui='0'";
        $disetuji = "and (select disetujui from krs_detail where view_krs_single2.id_krs_detail=krs_detail.id_krs order by disetujui desc limit 1 ) = 0";
      } else {
        $disetuji = "and (select disetujui from krs_detail where view_krs_single2.id_krs_detail=krs_detail.id_krs order by disetujui desc limit 1) = 1 ";
      }
      
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
  if ($_POST['fakultas']!='all') {
    $fakultas = getProdiFakultas('mahasiswa.jur_kode',$_POST['fakultas']);
  }
}
$columns = array(
    'mahasiswa.nim',
    'mahasiswa.nama',
    'mahasiswa.nama',
   // 'dosen.nama_dosen',  
    'mahasiswa.mulai_smt',
    "(select ktm.nim  from keu_tagihan_mahasiswa ktm
    inner join keu_bayar_mahasiswa kbm on ktm.id=kbm.id_keu_tagihan_mhs
    inner join keu_tagihan on ktm.id_tagihan_prodi=keu_tagihan.id
    inner join keu_jenis_tagihan kjt on keu_tagihan.kode_tagihan=kjt.kode_tagihan
    where ktm.periode=view_krs_single2.id_semester and keu_tagihan.kode_prodi=jur_kode
    and kjt.syarat_krs='Y' and ktm.nim=view_krs_single2.nim
    )",
    "view_krs_single2.disetujui",
    "(select sks_mak from jatah_sks j where  IFNULL((select akm.ip  from akm where akm.mhs_nim=view_krs_single2.nim 
    and akm.sem_id!=view_krs_single2.id_semester and akm.sem_id<=view_krs_single2.id_semester
    and akm.id_stat_mhs='A' ORDER BY sem_id DESC LIMIT 1),4) BETWEEN j.ip_min and j.ip_mak)",
    "(select sum(krs_detail.sks) AS sks  from krs_detail where nim=view_krs_single2.nim and id_semester=view_krs_single2.id_semester and batal='0')",
    'mahasiswa.jur_kode',
    'id_krs_detail',
  );

  //if you want to exclude column for searching, put columns name in array
  $datatable2->setDisableSearchColumn(
    "(select ktm.nim  from keu_tagihan_mahasiswa ktm
    inner join keu_bayar_mahasiswa kbm on ktm.id=kbm.id_keu_tagihan_mhs
    inner join keu_tagihan on ktm.id_tagihan_prodi=keu_tagihan.id
    inner join keu_jenis_tagihan kjt on keu_tagihan.kode_tagihan=kjt.kode_tagihan
    where ktm.periode=view_krs_single2.id_semester and keu_tagihan.kode_prodi=jur_kode
    and kjt.syarat_krs='Y' and ktm.nim=view_krs_single2.nim
    )",
    "(select disetujui from view_krs_single2 where view_krs_single2.id_semester='$semester_krs' and nim=mahasiswa.nim and disetujui='1' limit 1)",
    "(select sks_mak from jatah_sks j where  IFNULL((select akm.ip  from akm where akm.mhs_nim=view_krs_single2.nim 
    and akm.sem_id!=view_krs_single2.id_semester and akm.sem_id<=view_krs_single2.id_semester
    and akm.id_stat_mhs='A' ORDER BY sem_id DESC LIMIT 1),4) BETWEEN j.ip_min and j.ip_mak)",
    "(select sum(krs_detail.sks) AS sks  from krs_detail where nim=view_krs_single2.nim and id_semester=view_krs_single2.id_semester and batal='0')",
    'mahasiswa.jur_kode',
    'mahasiswa.mulai_smt'
  );
  
  //set numbering is true
  $datatable2->setNumberingStatus(1);




$datatable2->setDebug(1);
$datatable2->setFromQuery("view_krs_single2
 right join mahasiswa on (view_krs_single2.nim=mahasiswa.nim and view_krs_single2.id_semester='$semester_krs')
 inner join view_prodi_jenjang vpj on mahasiswa.jur_kode=vpj.kode_jur
 inner join view_semester on mahasiswa.mulai_smt=view_semester.id_semester
where 1=1 and 
mahasiswa.nim not in (select nim from tb_data_kelulusan)   $jur_filter $is_bayar $disetuji $fakultas $mulai_smt");

  $query = $datatable2->execQuery("select concat(ifnull(dosen.gelar_depan,''),' ',dosen.nama_dosen,',',dosen.gelar_belakang)  as nama_dosen, view_krs_single2.id_semester,id_krs_detail,angkatan,mahasiswa.nim,
(select count(keu_tagihan_mahasiswa.id) from keu_tagihan_mahasiswa inner join keu_tagihan on id_tagihan_prodi=keu_tagihan.id
inner join keu_jenis_tagihan using(kode_tagihan)
where nim=mahasiswa.nim and periode='$semester_krs' and syarat_krs='Y'
and keu_tagihan_mahasiswa.id in(select id_keu_tagihan_mhs from keu_bayar_mahasiswa where id_keu_tagihan_mhs=keu_tagihan_mahasiswa.id)
  ) as is_lunas, 
(select keu_tagihan_mahasiswa.id from keu_tagihan_mahasiswa inner join keu_tagihan on id_tagihan_prodi=keu_tagihan.id
inner join keu_jenis_tagihan using(kode_tagihan)
where nim=mahasiswa.nim and periode='$semester_krs' and syarat_krs='Y'
and nim=mahasiswa.nim and nominal_tagihan=potongan
  ) as bebas_bayar,
view_krs_single2.disetujui,
 (select sks_mak from jatah_sks j where  IFNULL((select akm.ip  from akm where akm.mhs_nim=mahasiswa.nim 
and akm.sem_id!='$semester_krs'  and  akm.sem_id<='$semester_krs' and  substr(akm.sem_id,5,1)!='3'
and akm.id_stat_mhs='A' ORDER BY sem_id DESC LIMIT 1),4) BETWEEN j.ip_min and j.ip_mak) as jatah,
ifnull((select sum(krs_detail.sks) AS sks  from krs_detail where nim=mahasiswa.nim and id_semester=view_krs_single2.id_semester and batal='0'),'0') as sks_diambil,nama,jurusan from view_krs_single2
 right join mahasiswa on (view_krs_single2.nim=mahasiswa.nim and view_krs_single2.id_semester='$semester_krs')
 left join dosen  on (dosen.nip=mahasiswa.dosen_pemb or dosen.id_dosen=mahasiswa.dosen_pemb)
  inner join view_prodi_jenjang vpj on mahasiswa.jur_kode=vpj.kode_jur
  inner join view_semester on mahasiswa.mulai_smt=view_semester.id_semester
where 1=1 and 
 mahasiswa.nim not in (select nim from tb_data_kelulusan)  and mahasiswa.status!='CM' and mahasiswa.status is not null 
 $jur_filter $is_bayar $disetuji $fakultas $mulai_smt ",$columns);  
//   echo "select concat(ifnull(dosen.gelar_depan,''),' ',dosen.nama_dosen,',',dosen.gelar_belakang)  as nama_dosen, view_krs_single2.id_semester,id_krs_detail,angkatan,mahasiswa.nim,
// (select count(keu_tagihan_mahasiswa.id) from keu_tagihan_mahasiswa inner join keu_tagihan on id_tagihan_prodi=keu_tagihan.id
// inner join keu_jenis_tagihan using(kode_tagihan)
// where nim=mahasiswa.nim and periode='$semester_krs' and syarat_krs='Y'
// and keu_tagihan_mahasiswa.id in(select id_keu_tagihan_mhs from keu_bayar_mahasiswa where id_keu_tagihan_mhs=keu_tagihan_mahasiswa.id)
//   ) as is_lunas, 
// (select keu_tagihan_mahasiswa.id from keu_tagihan_mahasiswa inner join keu_tagihan on id_tagihan_prodi=keu_tagihan.id
// inner join keu_jenis_tagihan using(kode_tagihan)
// where nim=mahasiswa.nim and periode='$semester_krs' and syarat_krs='Y'
// and nim=mahasiswa.nim and nominal_tagihan=potongan
//   ) as bebas_bayar,
// (select disetujui from view_krs_single2 where view_krs_single2.id_semester='$semester_krs' and nim=mahasiswa.nim and disetujui='1' limit 1) as disetujui,
//  (select sks_mak from jatah_sks j where  IFNULL((select akm.ip  from akm where akm.mhs_nim=mahasiswa.nim 
// and akm.sem_id!='$semester_krs'  and  akm.sem_id<='$semester_krs' and  substr(akm.sem_id,5,1)!='3'
// and akm.id_stat_mhs='A' ORDER BY sem_id DESC LIMIT 1),4) BETWEEN j.ip_min and j.ip_mak) as jatah,
// ifnull((select sum(krs_detail.sks) AS sks  from krs_detail where nim=mahasiswa.nim and id_semester=view_krs_single2.id_semester and batal='0'),'0') as sks_diambil,nama,jurusan from view_krs_single2
//  right join mahasiswa on (view_krs_single2.nim=mahasiswa.nim and view_krs_single2.id_semester='$semester_krs')
//  left join dosen  on (dosen.nip=mahasiswa.dosen_pemb or dosen.id_dosen=mahasiswa.dosen_pemb)
//   inner join view_prodi_jenjang vpj on mahasiswa.jur_kode=vpj.kode_jur
//   inner join view_semester on mahasiswa.mulai_smt=view_semester.id_semester
// where 1=1 and 
//  mahasiswa.nim not in (select nim from tb_data_kelulusan)  
//  $jur_filter $is_bayar $disetuji $fakultas $mulai_smt
//  group by mahasiswa.nim ";

  $db->getErrorMessage();

  //buat inisialisasi array data
  $data = array(); 

  $datatable2->setDebug(1);

  $i=1;
  $dos = array();
  $login_as = "";
  foreach ($query as $value) {
    $is_lunas = '';
    //array data
    $ResultData = array();
    $ket = "";
    //$ResultData[] = '<div class="checkbox checkbox-primary"><input class="styled styled-primary check-selected" type="checkbox" id="bulk_check"> <label for="checkbox2">'.$datatable->number($i).'</label></div>';
    $ResultData[] = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"> <input type="checkbox" class="group-checkable check-selected"> <span></span></label>'.($i+1);
    $ResultData[] = $value->nim;
    $ResultData[] = $value->nama;
    $ResultData[] = $value->nama_dosen;
    $ResultData[] = $value->angkatan;
   if ($value->is_lunas=="0" && $value->bebas_bayar=="") {
          $is_lunas = '<span class="btn btn-danger btn-xs" data-toggle="tooltip" data-title="Belum Bayar"><i class="fa fa-close"></i> Belum</span>';
        }
   else if ($value->is_lunas!="0"  && $value->bebas_bayar=="") {
        $is_lunas = '<span class="btn btn-success btn-xs" data-toggle="tooltip" data-title="Sudah"><i class="fa fa-check"></i> Sudah</span>';
      } 
      else { 
        if ($value->bebas_bayar!="") {
          $is_lunas = '<span class="btn btn-info btn-xs" data-toggle="tooltip" data-title="Bebas Bayar"><i class="fa fa-check"></i> Bebas Bayar</span>';
          $qb = $db->query("select ket_tagihan keu_tagihan from keu_tagihan_mahasiswa where nim='$value->nim' and periode='$value->id_semester' ");
          foreach ($qb as $kb) {
           $ket = $kb->keu_tagihan;
          }
        } else {
          $is_lunas = '<span class="btn btn-danger btn-xs" data-toggle="tooltip" data-title="Belum Bayar"><i class="fa fa-close"></i> Belum</span>';
        }
    }
    $ResultData[] = $is_lunas;

/*     if ($value->is_bayar=='') { 
      $ResultData[] = '<span class="btn btn-danger btn-xs">Belum</span>';
    } else {
      $ResultData[] = '<span class="btn btn-success btn-xs">Sudah</span>';
    }*/
    if ($value->disetujui==0) {
      $ResultData[] = '<span class="btn btn-danger btn-xs">Belum</span>';
    } else {
       $ResultData[] = '<span class="btn btn-success btn-xs">Sudah</span>';
    } 
    $ResultData[] = $value->jatah;
    $ResultData[] = $value->sks_diambil;
    $ResultData[] = $value->jurusan;
    $ResultData[] = $ket; 

    if ($_SESSION['group_level']=='admin') {
      $login_as = '<a href="'.base_admin().'inc/login_as.php?id='.$value->nim.'&adm_id='.$_SESSION['id_user'].'&url=mahasiswa&back_uri=rencana-studi" class="btn btn-success btn-sm" data-toggle="tooltip" title="" data-original-title="Login As"><i class="fa fa-user"></i></a>';
    }

    $ResultData[] = '<a data-id="'.$value->id_krs_detail.'" href="'.base_index_new().'rencana-studi/detail/?n='.$enc->enc($value->nim).'&s='.$enc->enc($value->id_semester).'" data-toggle="tooltip" title="" class="btn btn-primary btn-xs data_selected_id" data-original-title="Lihat Detail KRS"><i class="fa fa-search"></i></a> '.$login_as; 
    $data[] = $ResultData;
     $dos = array(); 
    $i++;
  }

//set data
$datatable2->setData($data);
//create our json
$datatable2->createData();
?>