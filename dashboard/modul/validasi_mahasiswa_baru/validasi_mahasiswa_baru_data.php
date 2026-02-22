<?php
include "../../inc/config.php";

$columns = array(
    'mahasiswa.nim',
    'mahasiswa.nama',
    'mahasiswa.mulai_smt',
    'jurusan.nama_jur',
    'mahasiswa.mhs_id',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('pesan','mahasiswa.mhs_id');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("mahasiswa.mhs_id");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by mahasiswa.mhs_id";
  $smt ="";
  if ($_POST['jurusan']!='all' && $_POST['jurusan']!='') {
    $smt = "and mahasiswa.jur_kode='".$_POST['jurusan']."'";
  }
  $query = $datatable->get_custom("select mahasiswa.status, mahasiswa.mhs_id, mahasiswa.no_pendaftaran, mahasiswa.nim,mahasiswa.nama,mahasiswa.mulai_smt,jurusan.nama_jur,mahasiswa.mhs_id from mahasiswa inner join jurusan on mahasiswa.jur_kode=jurusan.kode_jur where   mahasiswa.mulai_smt='".$_POST['tahun_akademik']."' $smt ",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
    $ResultData[] = $value->no_pendaftaran;
    $ResultData[] = $value->nim;
    $ResultData[] = $value->nama;
    $ResultData[] = $value->mulai_smt;
    $ResultData[] = $value->nama_jur;
    if ($value->status=='CM') {  
      $ResultData[] = "<button class='btn btn-success' onclick='show_form_validasi(\"$value->mhs_id\")'><i class='fa fa-credit-card' ></i> Validasi</button>";
      $ResultData[] = ""; 
    }else{

      $ResultData[] = "<button class='btn btn-danger' onclick='batal_validasi(\"$value->nim\")'><i class='fa fa-close' ></i> Batalkan Validasi</button>";
      
      $qq = $db->query("select m.nama,v.*,b.`nama_bank`  from validasi_mhs_baru v join mahasiswa m
							on m.`no_pendaftaran`=v.`no_pendaftaran`
							left join keu_bank b on b.`kode_bank`=v.`id_bank` 
							where v.`no_pendaftaran`='$value->no_pendaftaran' order by v.id desc limit 1");
      if ($qq->rowCount()>0) {
      	 foreach ($qq as $kk) {
	      	$ResultData[] = "<table>
	      	                 <tr><td>Tgl Validasi</td><td> : </td><td> $kk->tgl_validasi</td></tr>

	      	                 <tr><td>Jenis Bayar</td><td> : </td><td> $kk->ket_validasi</td></tr>
	      	                 </table>";
	      }
      }else{
      	 $ResultData[] = "";
      }
     
    }
    // <tr><td>Validator</td><td> : </td><td> $kk->validator</td></tr> <tr><td>Bank</td><td> : </td><td> $kk->nama_bank</td></tr>

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>