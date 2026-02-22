<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'dosen.nip',
    'dosen.nidn',
    'dosen.nama_dosen',
    'dosen.email',
    'no_hp',
    'dosen.aktif',
    'id_jenis_dosen',
    'view_prodi_jenjang.jurusan',
    'dwc.nama_rumpun',
    'jml_bimbingan',
    'dosen.id_dosen',
  );
$datatable2->setDisableSearchColumn(
    'jml_bimbingan',
    'dwc.nama_rumpun'
  );
  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('aktif','dosen.id_dosen');
  
  //set numbering is true
  $datatable2->setNumberingStatus(0);

 $datatable2->setDebug(1);

$kode_jur = "";
$aktif = "";
$rumpun = "";
$sub_rumpun = "";
$bidang_ilmu = "";
$id_jenis_dosen = "";
  
function getJenisDosen() {
  global $db;
  $jns_dosen = $db->query("select * from jenis_dosen");
  foreach ($jns_dosen as $jenis) {
    $data_jenis_dosen[$jenis->id_jenis_dosen] = $jenis->nama_jenis;
  }
  return $data_jenis_dosen;
}

  $jenis_dosen_array = getJenisDosen();


  if (isset($_POST['kode_jur'])) {
    
      if ($_POST['kode_jur']!='all') {
        $kode_jur = ' and dosen.kode_jur="'.$_POST['kode_jur'].'"';
      }

      if ($_POST['id_jenis_dosen']!='all') {
        $id_jenis_dosen = ' and id_jenis_dosen="'.$_POST['id_jenis_dosen'].'"';
      }
  
      if ($_POST['aktif']!='all') {
        $aktif = ' and dosen.aktif="'.$_POST['aktif'].'"';
      }
      if ($_POST['rumpun']!='all' && $_POST['rumpun']!='') {
        $rumpun = ' and d.kode="'.$_POST['rumpun'].'"';
      }
      if ($_POST['sub_rumpun']!='all' && $_POST['sub_rumpun']!='') {
        $sub_rumpun = ' and dw.kode="'.$_POST['sub_rumpun'].'"';
      }
      if ($_POST['bidang_ilmu']!='all' && $_POST['bidang_ilmu']!='') {
        $bidang_ilmu = ' and dwc.kode="'.$_POST['bidang_ilmu'].'"';
      }

      
  
}

$datatable2->setFromQuery("dosen
inner join sys_users on dosen.nip=sys_users.username
left join view_prodi_jenjang on dosen.kode_jur=view_prodi_jenjang.kode_jur
left join data_rumpun_dosen dwc on dosen.kode_rumpun=dwc.kode
left join data_rumpun_dosen dw on dwc.id_induk=dw.kode
left join data_rumpun_dosen d on dw.id_induk=d.kode where dosen.id_dosen is not null $kode_jur $aktif $rumpun $sub_rumpun $bidang_ilmu $id_jenis_dosen");

  $query = $datatable2->execQuery("select dosen.email,no_hp,dosen.aktif,dosen.nip,dosen.nidn,dosen.nama_dosen,view_prodi_jenjang.jurusan,dosen.id_dosen,dwc.nama_rumpun,sys_users.id as id_user,id_jenis_dosen,
    (select count(nim) from view_simple_mhs_data where nip_dosen_pa=dosen.nip and nim not in(select nim from tb_data_kelulusan)) as jml_bimbingan
from dosen
inner join sys_users on dosen.nip=sys_users.username
left join view_prodi_jenjang on dosen.kode_jur=view_prodi_jenjang.kode_jur
left join data_rumpun_dosen dwc on dosen.kode_rumpun=dwc.kode
left join data_rumpun_dosen dw on dwc.id_induk=dw.kode
left join data_rumpun_dosen d on dw.id_induk=d.kode where dosen.id_dosen is not null $kode_jur $aktif $rumpun $sub_rumpun $bidang_ilmu $id_jenis_dosen ",$columns);

  //buat inisialisasi array data
  $data = array();

  $id_admin = $_SESSION['id_user'];

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"> <input type="checkbox"  class="group-checkable check-selected data_selected_id" data-id="'.$value->nip.'"> <span></span></label>';
    $ResultData[] = $value->nip; 
    $ResultData[] = $value->nidn;
    $ResultData[] = $value->nama_dosen;
    $ResultData[] = $value->email;
    $ResultData[] = $value->no_hp;
    if ($_SESSION['group_level']=='admin') {
      $login_as = '<a href="'.base_admin().'inc/login_as.php?id='.$value->nip.'&adm_id='.$_SESSION['id_user'].'&url=dosen" class="btn btn-success btn-sm" data-toggle="tooltip" title="" data-original-title="Login As"><i class="fa fa-user"></i></a>';
    }

    if ($value->aktif=='Y') {
      $ResultData[] = '<span class="btn btn-success btn-xs"><i class="fa fa-check"></i> Aktif</span> '.$login_as;
    } else {
      $ResultData[] = '<span class="btn btn-danger btn-xs"><i class="fa fa-close"></i> Non Aktif</span>';
    }

    $ResultData[] = (in_array($value->id_jenis_dosen,array_keys($jenis_dosen_array)))?$jenis_dosen_array[$value->id_jenis_dosen]:'';

    //$ResultData[] = $status;
    $ResultData[] = $value->jurusan;
    $ResultData[] = $value->nama_rumpun;
    $ResultData[] = $value->jml_bimbingan;
    $ResultData[] = $value->id_dosen;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable2->setData($data);
//create our json
$datatable2->createData();

?>