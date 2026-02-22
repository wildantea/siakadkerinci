<?php
session_start();
include "../../../inc/config.php";

$columns = array(
    'jenis_bukti',
    'type_dokumen',
    'id_bukti',
  );

  //if you want to exclude column for searching, put columns name in quote and separate with comma if multi
  //$datatable2->setDisableSearchColumn("tb_data_pendaftaran.nim","tb_data_pendaftaran.id_pendaftaran");
  

  $id_jenis_bukti = $_POST['id_jenis_bukti'];
  $nama_directory = $_POST['nama_directory'];
  $nim = $_SESSION['username'];
  $id_jenis_pendaftaran_setting = $_POST['id_jenis_pendaftaran_setting'];
  $id_pendaftaran = $_POST['id_pendaftaran'];
        //set numbering is true
  $datatable2->setNumberingStatus(0);
  //set order by column
  //$datatable2->setOrderBy("tb_data_pendaftaran.id_pendaftaran desc");

//$datatable2->setDebug(1);

$where_jenis_bukti = "";

if ($id_jenis_bukti!="") {
  $where_jenis_bukti = "and id_jenis_bukti in($id_jenis_bukti)";
}
  //set group by column
  //$datatable2->setGroupBy("tb_data_pendaftaran.id_pendaftaran");

  $query = $datatable2->execQuery("SELECT tb_data_pendaftaran_jenis_bukti.id_jenis_bukti,tb_data_pendaftaran_jenis_bukti.jenis_bukti,type_dokumen,file_name,tb_data_pendaftaran_bukti_dokumen.id_bukti,ext_type,link_dokumen,status from tb_data_pendaftaran_jenis_bukti
LEFT JOIN tb_data_pendaftaran_bukti_dokumen USING(id_jenis_bukti) inner join tb_data_pendaftaran using(id_pendaftaran) where nim='$nim' $where_jenis_bukti and id_jenis_pendaftaran_setting='$id_jenis_pendaftaran_setting' and tb_data_pendaftaran.id_pendaftaran='$id_pendaftaran'",$columns);

  //buat inisialisasi array data
  $data = array();

  
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $value->jenis_bukti;
    if ($value->id_bukti!="") {
      if ($value->type_dokumen=='1') {
          if ($value->ext_type=='pdf') {
          $ResultData[] = '<a class="bukti-dokumen fancybox.iframe" title="'.$value->jenis_bukti.'" rel="show-dokumen" href="'.base_url().'upload/pendaftaran/'.$nama_directory.'/'.$nim.'/'.$value->file_name.'"><img style="cursor:pointer" src="'.base_admin().'assets/pdf_document.png" width="30"></a>';
          } else {  
          $ResultData[] = '<a class="bukti-dokumen fancybox.image" title="'.$value->jenis_bukti.'" rel="show-dokumen" href="'.base_url().'upload/pendaftaran/'.$nama_directory.'/'.$nim.'/'.$value->file_name.'"><img style="cursor:pointer" src="'.base_admin().'assets/gambar.png" width="30"></a>';
          }
      } else {
        if (strpos($value->link_dokumen, 'drive.google.com')) {
          $link_dokumen = str_replace('view?', 'preview?', $value->link_dokumen);
        } else {
          $link_dokumen = $value->link_dokumen;
        }
        $ResultData[] = '<a class="bukti-dokumen fancybox.iframe" rel="show-dokumen" title="'.$value->jenis_bukti.'" href="'.$link_dokumen.'">'.$value->link_dokumen.'</a>';
      }
    } else {
      $ResultData[] = "";
    }

    if ($value->status!='1') {
      $ResultData[] = '<button type="button" data-toggle="tooltip" title="Ganti Dokumen" class="btn btn-xs btn-primary edit-bukti" data-id="'.$value->id_bukti.'"><i class="glyphicon glyphicon-edit"></i></button>';
    } else {
      $ResultData[] = '';
    }

    

    $data[] = $ResultData;
    
  }

//set data
$datatable2->setData($data);
//create our json
$datatable2->createData();

?>