<?php
//filter session
/**
 * set filter 
 * @param [string] $filter_modul      filter modul name
 * @param [array] $array_filter_name two dimensional array of filter names
 */
function setFilter($filter_modul,$array_filter_name) {
  $_SESSION[$filter_modul] = $array_filter_name;
}

/**
 * get filter session
 * @param  [type] $array_filter_name array filter modulname and filtername
 * @return string/boolean       value of filtername
 */
function getFilter($array_filter_name) {
    foreach ($array_filter_name as $key => $value) {
      $filter_modul = $key;
      $filter_name = $value;
    }
    if (isset($_SESSION[$filter_modul][$filter_name])) {
        return $_SESSION[$filter_modul][$filter_name];
    } else {
      return '';
    }
}

/**
 * check if filter session is exist
 * @param  [type]  $filter_modul_name filter modul name
 * @return boolean                    boolean
 */
function hasFilter($filter_modul_name) {
  if (isset($_SESSION[$filter_modul_name])) {
    return true;
  } else {
    return false;
  }
}

/**
 * remove filter session
 * @param  [string] $filter_modul filter modul names
 * @return [type]               [description]
 */
function resetFilter($filter_modul) {
  unset($_SESSION[$filter_modul]);
}

function resetFilterButton($filter_modul) {
  echo '<span data-name="'.$filter_modul.'" id="reset_filter" class="btn btn-danger" data-toggle="tooltip" data-title="Reset Filter"><i class="fa fa-times">  </i> Reset </span>';
}
function aksesProdi($column) {
  //get default akses prodi 
  $akses_prodi = getAksesProdi();
  if ($akses_prodi) {
    $jur_kode = "and $column in(".$akses_prodi.")";
  } else {
    //jika tidak group tidak punya akses prodi, set in 0
    $jur_kode = "and $column in(0)";
  }
  return $jur_kode;
}
/**
 * get periode akademik
 * @param  [type] $sem_id semester id ex : 20181
 * @return [type]         get periode akademik name
 */
function getPeriode($sem_id)
{
    global $db2;
    $periode = $db2->fetchSingleRow('view_semester', "id_semester", $sem_id);
    echo $sem_id;
    return $periode->tahun_akademik;
}

function ganjil_genap($id_semester) {
  $year_next = substr($id_semester, 0,4) + 1;
  if (substr($id_semester, -1)==1) {
    $periode = substr($id_semester, 0,4)."/".$year_next." Ganjil";
  } elseif (substr($id_semester, -1)==2) {
    $periode = substr($id_semester, 0,4)."/".$year_next." Genap";
  } else {
    $periode = $id_semester;
  }
  return $periode;
}
/**
 * get semeste aktif
 * @return int semester
 */
function getSemesterAktif() {
      global $db2;
    $periode = $db2->fetchSingleRow('view_semester', "aktif", 1);
    return $periode->id_semester;
}

function getAngkatan($id_semester) {
  if (substr($id_semester, -1)==1) {
    $periode = substr($id_semester, 0,4)." Ganjil";
  } elseif (substr($id_semester, -1)==2) {
    $periode = substr($id_semester, 0,4)." Genap";
  } else {
    $periode = $id_semester;
  }
  return $periode;
}

/**
 * check jika kampus punya fakultas
 *
 * @return boolean
 */
function hasFakultas()
{
    global $db2;
    $has_fakultas = getPengaturan('has_fakultas');
    if ($has_fakultas=='Y') {
        return true;
    } else {
        return false;
    }
}

function getFakultasName($kode_jur) {
    global $db2;
    $has_fakultas = getPengaturan('has_fakultas');
    if ($has_fakultas=='Y') {
        $jurusan = $db2->fetchSingleRow("jurusan","kode_jur",$kode_jur);
        $fakultas = $db2->fetchSingleRow("fakultas","kode_fak",$jurusan->fak_kode);
        $nama_fakultas = $fakultas->nama_resmi;
    } else {
        $nama_fakultas = "";
    }
    return $nama_fakultas;
}
/**
 * return $pengaturan
 *
 * @param  [type] $short_name short name column call
 * @return [type]             isi pengaturan
 */
function getPengaturan($short_name)
{
    global $db2;
    $pengaturan = $db2->fetchSingleRow('tb_master_pengaturan_umum', "short_name", $short_name);
    return $pengaturan->isi_pengaturan;
}

/**
 * if session level is dosen
 * @return boolean boolean
 */
function isDosen() {
  if ($_SESSION['group_level']=='dosen') {
    return true;
  } else {
    return false;
  }
}
/**
 * if session level is mahasiswa
 * @return boolean boolean
 */
function isMahasiswa() {
  if ($_SESSION['group_level']=='mahasiswa') {
    return true;
  } else {
    return false;
  }
}
/**
 * check if biodata dosen is not complete
 * @param  [type] $id_user user_id
 * @return [type]           boolean 
 */
function checkBiodataDosen($id_user) 
{
  global $db2;
  $array_required = array(
    'gelar_belakang',
    'id_agama',
    'tgl_lahir',
    'nik',
    'email',
    'bidang_ilmu',
    'jenjang_tertinggi',
    'tmpt_lahir',
    'jk',
    'no_hp',
    'jenis_dosen'
  );
  $where = checkEmpty($array_required);
  $id_dosen = $db2->fetchSingleRow("view_dosen","id_user",$id_user);
  $result_query = $db2->query("select * from tb_master_dosen where id_dosen=? and ($where)",array('id_dosen' => $id_dosen->id_dosen));
  if ($result_query->rowCount()>0) {
    return true;
  } else {
    return false;
  }
}
/**
 * check if biodata mahasiswa is not complete
 * @param  [type] $id_user user_id
 * @return [type]           boolean 
 */
function checkBiodataMahasiswa($id_user) 
{
  global $db2;
  $array_required = array(
    'nim',
    'jk',
    'nik',
    'email',
    'kewarganegaraan',
    'id_jalur_masuk',
    'tmpt_lahir',
    'tgl_lahir',
    'id_agama',
    'id_wil',
    'jln',
    'kode_pos',
    'telepon_seluler',
    'id_jns_daftar',
    'id_pembiayaan',
    'nm_ibu_kandung',
    'dosen_pemb'
  );
  $where = checkEmpty($array_required);
  $column = implode(",", $array_required);
  $id_mhs = $db2->fetchSingleRow("view_simple_mhs","id_user",$id_user);
  $result_query = $db2->query("select $column from tb_master_mahasiswa where mhs_id=? and ($where)",array('mhs_id' => $id_mhs->mhs_id));
  if ($result_query->rowCount()>0) {
    return true;
  } else {
    return false;
  }
}
/**
 * check empty column
 * @param  [type] $array_data array column to be checked
 * @return [type]             query checking column
 */
function checkEmpty($array_data) {
  foreach ($array_data as $dt) {
    $result[] = "coalesce($dt, '') = ''";
  }
  $data = implode(' or ', $result);
  return $data;
}

/**
 * return implode kode jur base on hak akses prodi
 * @return [type] list comma separated kode prodi
 */
function getAksesProdi() {
  global $db2;
  $data_prodi = array();
  $kode_prodi = "";
  $get_akses_prodi = $db2->fetchSingleRow("sys_group_users","level",$_SESSION['group_level']);
  if ($get_akses_prodi->akses_prodi!="") {
    $decode_prodi = json_decode($get_akses_prodi->akses_prodi);
    $kode_prodi = $decode_prodi->akses;
  }
  if ($kode_prodi!="") {
    return $kode_prodi;
  } else {
    return false;
  }
}


function getProdiFakultas($column,$id_fakultas) {
  global $db2;
  //get default akses prodi
  $data_prodi_fakultas = $db2->fetchCustomSingle("select group_concat(kode_jur) as jur_kode from jurusan where fak_kode=?",array('fak_kode' => $id_fakultas));
  if ($data_prodi_fakultas) {
    $jur_kode = "and $column in(".$data_prodi_fakultas->jur_kode.")";
  } else {
    //jika tidak group tidak punya akses prodi, set in 0
    $jur_kode = "and $column in(0)";
  }
  return $jur_kode;
}
/**
 * menampilkan looping select list fakultas sesuai akses prodi yang diberikan
 * @return [type] select option list fakultas
 */
function loopingFakultas($filter_modul="") {
  if ($filter_modul!="") {
    $selected_filter_value = getFilter(array($filter_modul => 'fakultas'));

  }
  global $db2;
  $akses_prodi = getAksesProdi();
  if ($akses_prodi) {
    $fakultas = $db2->query("select * from view_prodi_jenjang where kode_jur in ($akses_prodi) group by id_fakultas");
        //jika jurusan hanya punya 1 akses prodi, misal admin prodi
   if ($fakultas->rowCount()==1) {
      foreach ($fakultas as $dt) {
        echo "<option value='$dt->id_fakultas' selected>$dt->nama_fakultas</option>";
      }
    } else {
      //jika group user punya akses ke semua prodi
      echo "<option value='all'>Semua</option>";
      foreach ($fakultas as $dt) {
        if ($filter_modul!="" && $selected_filter_value==$dt->id_fakultas) {
          echo "<option value='$dt->id_fakultas' selected>".ucwords(strtolower($dt->nama_fakultas))."</option>";
        } else {
          echo "<option value='$dt->id_fakultas'>".ucwords(strtolower($dt->nama_fakultas))."</option>";
        }
        
      }
    }

  } else {
    echo "<option value='' selected>Akun ini belum punya akses prodi</option>";
  }

}


/**
 * menampilkan looping select list prodi sesuai akses prodi yang diberikan
 * @return [type] select option list prodi
 */
function loopingProdi($filter_modul="",$id_fakultas="") {
  if ($filter_modul!="") {
    $selected_filter_value = getFilter(array($filter_modul => 'prodi'));
  }
  $and_id_fakultas = "";
  if ($id_fakultas!="" && $id_fakultas!="all") {
    $and_id_fakultas = "and id_fakultas='".$id_fakultas."'";
  }
  global $db2;
  $akses_prodi = getAksesProdi();
  if ($akses_prodi) {
    $jurusan = $db2->query("select *,jurusan as nama_jurusan from view_prodi_jenjang where kode_jur in ($akses_prodi) $and_id_fakultas");
        //jika jurusan hanya punya 1 akses prodi, misal admin prodi
   if ($jurusan->rowCount()==1) {
      foreach ($jurusan as $dt) {
        echo "<option value='$dt->kode_jur' selected>".string_rapih($dt->nama_jurusan)."</option>";
      }
    } else {
      //jika group user punya akses ke semua prodi
      echo "<option value='all'>Semua</option>";
      foreach ($jurusan as $dt) {
        if ($filter_modul!="" && $selected_filter_value==$dt->kode_jur) {
          echo "<option value='$dt->kode_jur' selected>".string_rapih($dt->nama_jurusan)."</option>";
        } else {
          echo "<option value='$dt->kode_jur'>".string_rapih($dt->nama_jurusan)."</option>";
        }
        
      }
    }

  } else {
    echo "<option value='' selected>Akun ini belum punya akses prodi</option>";
  }

}


/**
 * return session value
 * @param  [type] $session_name session name
 * @return [type]               session value
 */
function getSession($session_name) {
  return $_SESSION[$session_name];
}

function isPhotoChange() {
  global $db2;
  $photo = $db2->fetchSingleRow("sys_users","id",getSession('id_user'));
  if ($photo->photo_changed=='Y') {
    return true;
  } else {
    return false;
  }
}
/**
 * looping semester, selected if semester is aktif
 * @return [type] [description]
 */
function loopingSemester($filter_modul="") {
      if ($filter_modul!="") {
        $selected_filter_value = getFilter(array($filter_modul => 'semester'));

      }
      global $db2;
     foreach ($db2->query("select * from view_semester order by id_semester desc") as $isi) {
      if ($filter_modul!="" && $selected_filter_value==$isi->id_semester) {
        echo "<option value='$isi->id_semester' selected>$isi->tahun_akademik</option>";
      } else {
        if ($isi->aktif==1) {
            echo "<option value='$isi->id_semester' selected>$isi->tahun_akademik</option>";
        } else {
            echo "<option value='$isi->id_semester'>$isi->tahun_akademik</option>";
        }
      }

   } 
}
function loopingSemesterForm() {
      global $db2;
     foreach ($db2->query("select * from view_semester order by id_semester desc") as $isi) {
        if ($isi->aktif==1) {
            echo "<option value='$isi->id_semester' selected>$isi->tahun_akademik</option>";
        } else {
            echo "<option value='$isi->id_semester'>$isi->tahun_akademik</option>";
        }
   } 
}

function getRuang($id_ruang) {
  global $db2;
  $ruangan = $db2->fetchSingleRow('tb_data_ruang','ruang_id',$id_ruang);
  if ($ruangan) {
    return $ruangan->nm_ruang;
  }
}


//siganture goes here
function status_pengesah($pejabat,$posisi) {
  //id mahasiswa n pembimbing akademik
  $mhs_pem = array(1,2);
  if ($pejabat[$posisi]->status==0) {
  $status_pengesah = "hide";
  } else {
    if (in_array($pejabat[$posisi]->kategori_jabatan, $mhs_pem) ) {
      $status_pengesah = "hide";
    } else {
      $status_pengesah = "show";
    }
  }
  return $status_pengesah;
}

function kota($pejabat,$posisi) {
  global $db2;
  $kota = '';

  if ($pejabat[$posisi]->status!=0) {
      if (is_numeric($pejabat[$posisi]->ada_kota)) {
      $record_kota = $db2->fetchSingleRow("tb_data_kota_signature","id_kota",$pejabat[$posisi]->ada_kota);
      $kota = '<br>'.$record_kota->nama_kota.', ';
      }
  }
 
  return $kota;
}

function ada_tgl($pejabat,$posisi) {
  $tgl = '';
  if ($pejabat[$posisi]->status!=0) {
    if ($pejabat[$posisi]->ada_tgl=='yes') {
      $tgl = tgl_indo(date('Y-m-d'));
    } elseif ($pejabat[$posisi]->ada_tgl=='titik') {
      $tgl = ".................................";
    }
  }
  return $tgl;
}

function tipe_pengesah($pejabat,$posisi) {
  global $db2;
  $tipe_pengesah = '';
  if ($pejabat[$posisi]->status!=0) {
    if ($pejabat[$posisi]->tipe_pengesah=='no') {
      $tipe_pengesah = '';
    } else {
      $record_tipe_pengesah = $db2->fetchSingleRow("tb_data_jabatan_tipe_pengesah","id_tipe_pengesah",$pejabat[$posisi]->tipe_pengesah);
      $tipe_pengesah = '<br>'.$record_tipe_pengesah->nama_tipe_pengesah;
    }
  }

  return $tipe_pengesah;
}

function kategori_pejabat($pejabat,$posisi) {
  global $db2;
  $kat_pejabat = '';
    if ($pejabat[$posisi]->status!=0) {
      $record_kat_pejabat = $db2->fetchSingleRow("tb_data_jabatan_kategori","id_jabatan_kat",$pejabat[$posisi]->kategori_jabatan);
      $kat_pejabat = '<br>'.$record_kat_pejabat->nama_kategori;
    }

  return $kat_pejabat;
}

function nip_pengesah($pejabat,$posisi,$nim=NULL) {
  global $db2;
  $nip = '';
  if ($pejabat[$posisi]->status!=0) {
    if ($pejabat[$posisi]->kategori_jabatan==1) {
      if ($nim!=NULL) {
        $nip = 'NIM : '.getInfoMhs($nim)->nim;;
      } else {
        $nip = 'NIM : ';
      }
      
    } elseif ($pejabat[$posisi]->kategori_jabatan==2) {
      if ($nim!=NULL) {
        $nip = 'NIP : '.getInfoMhs($nim)->nip_pembimbing;;
      } else {
        $nip = 'NIP : ';
      }
    } else {
        $record_nip = $db2->fetchCustomSingle("select fungsi_get_pengesah_nama(is_dosen,id_dosen,id_pejabat) as nama_pejabat,fungsi_get_pengesah_nip(is_dosen,id_dosen,id_pejabat) as nip,id_pejabat from tb_data_jabatan_pejabat inner join tb_data_jabatan_kategori using(id_jabatan_kat) where id_pejabat=?",array("id_pejabat" => $pejabat[$posisi]->pengesah));

      $nip = 'NIP : '.$record_nip->nip;
    }
  }
  return $nip;
}

function nama_pengesah($pejabat,$posisi,$nim=NULL) {
  global $db2;
  $nama = '';
  if ($pejabat[$posisi]->status!=0) {
    if ($pejabat[$posisi]->kategori_jabatan==1) {
      if ($nim!=NULL) {
        $nama = getInfoMhs($nim)->nama;;
      } else {
        $nama = 'Nama Mahasiswa';
      }
    } elseif ($pejabat[$posisi]->kategori_jabatan==2) {
      if ($nim!=NULL) {
        $nama = getInfoMhs($nim)->nama_pembimbing;;
      } else {
        $nama = 'Nama Pembimbing';
      }
    } else {
        $record_nama = $db2->fetchCustomSingle("select fungsi_get_pengesah_nama(is_dosen,id_dosen,id_pejabat) as nama_pejabat,fungsi_get_pengesah_nip(is_dosen,id_dosen,id_pejabat) as nip,id_pejabat from tb_data_jabatan_pejabat inner join tb_data_jabatan_kategori using(id_jabatan_kat) where id_pejabat=?",array("id_pejabat" => $pejabat[$posisi]->pengesah));
      $nama = $record_nama->nama_pejabat;
    }
  }
  return $nama;
}

function getInfoMhs($nim) {
  global $db2;
  return $db2->fetchSingleRow('view_simple_mhs','nim',$nim);
}


function string_rapih($word) {
  return preg_replace( '/[^[:print:]]/', '',str_replace("Dan", "dan", ucwords(strtolower($word))));
}

//check if current date is periode krs or input nilai
/**
 * check current aktif per prodi
 * @param  string $type     pilihanya check krs atau lainya check periode input nilai
 * @param  int $periode  periode misal 20171
 * @param  int $kode_jur contoh 705
 * @return boolean           
 */
function checkPeriodeInput($type,$periode,$kode_jur) {
  global $db2;
  if ($type=='krs') {
      $check = $db2->query("select * from tb_data_semester_prodi s where ((curdate() between s.tgl_mulai_krs and s.tgl_selesai_krs) 
or (curdate() between s.tgl_mulai_pkrs and s.tgl_selesai_pkrs))
and id_semester='$periode' and kode_jur='$kode_jur'");
  } else {
    $check = $db2->query("select * from tb_data_semester_prodi s where curdate() between s.tgl_mulai_input_nilai and s.tgl_selesai_input_nilai and id_semester='$periode' and kode_jur='$kode_jur'");
  }
  if ($check->rowCount()>0) {
    return true;
  } else {
    return false;
  }

}
/**
 * check if mahasiswa has status bayar and boleh krs
 * @param  [type]  $nim         nim mahasiswa
 * @param  [type]  $id_semester periode semester, ex : 20192
 * @return boolean              true or false
 */
function isBayar($nim,$id_semester) {
  global $db2;
  $is_bayar = $db2->fetchCustomSingle("select * from tb_data_status_bayar where nim=? and id_semester=? and boleh_krs=?",array('nim' => $nim,'id_semester' => $id_semester,'boleh_krs' => 'Y'));
  if ($is_bayar) {
    return true;
  } else {
    return false;
  }
}
/**
 * check if mahasiswa has status bayar and boleh krs
 * @param  [type]  $nim         nim mahasiswa
 * @param  [type]  $id_semester periode semester, ex : 20192
 * @return boolean              true or false
 */
function isDisetujuiKrs($nim,$id_semester) {
  global $db2;
  $is_approved_krs = $db2->fetchCustomSingle("select * from tb_data_kelas_krs where nim=? and id_semester=? and disetujui=?",array('nim' => $nim,'id_semester' => $id_semester,'disetujui' => 1));
  if ($is_approved_krs) {
    return true;
  } else {
    return false;
  }
}
/**
 * Jatah SKS Mahasiswa
 * @param  [type]  $nim         nim mahasiswa
 * @param  [type]  $id_semester periode semester, ex : 20192
 * @return jatah sks or false
 */
function jatahSks($nim,$id_semester) {
  global $db2;
  $jatah_sks = $db2->fetchCustomSingle("select (select sks_mak from tb_data_jatah_sks j where  IFNULL((select tb_data_akm.ip  from tb_data_akm where tb_data_akm.nim='".$nim."'
and tb_data_akm.id_semester<".$id_semester."
and tb_data_akm.id_stat_mhs='A' ORDER BY id_semester DESC LIMIT 1),4) BETWEEN j.ip_min and j.ip_mak) as jatah_sks");
  if ($jatah_sks) {
    return $jatah_sks->jatah_sks;
  } else {
    return false;
  }
}

function sksDiambil($nim,$id_semester) {
  global $db2;
  $sks_diambil = $db2->fetchCustomSingle("select sum(total_sks) as sks_diambil from tb_data_kelas_krs_detail inner join tb_data_matakuliah
on matkul_id=id_matkul
inner join tb_data_kelas_krs using (krs_id) where nim=? and id_semester=? and batal=0",array("nim" => $nim,"id_semester" => $id_semester));
  if ($sks_diambil) {
    return $sks_diambil->sks_diambil;
  } else {
    return 0;
  }
}

function getTotalSksDiambil($nim,$id_semester=NULL) {
  global $db2;
  $and_semester = "";
  if ($id_semester!=NULL) {
    $and_semester = "and sem_id<=".$id_semester;
  }
  //minimal SKS diambil
  $data_sks_diambil = $db2->query("select id_matkul,max(nilai_indeks),sks from view_krs_mhs_kelas where nim=? and nilai_indeks is not null $and_semester  group by id_matkul",array('nim' => $nim));
  $sks = 0;
  if ($data_sks_diambil->rowCount()>0) {
    foreach ($data_sks_diambil as $diambil) {
      $sks+=$diambil->sks;
    }
  }
  return $sks;
}

function getTotalSksWajibDiambil($nim,$id_semester=NULL) {
  global $db2;
  $and_semester = "";
  if ($id_semester!=NULL) {
    $and_semester = "and sem_id<=".$id_semester;
  }
  //minimal SKS diambil
  $data_sks_diambil = $db2->query("select id_matkul,max(nilai_indeks),sks from view_krs_mhs_kelas where nim=? and a_wajib=1 and nilai_indeks is not null $and_semester group by id_matkul",array('nim' => $nim));
  $sks = 0;
  if ($data_sks_diambil->rowCount()>0) {
    foreach ($data_sks_diambil as $diambil) {
      $sks+=$diambil->sks;
    }
  }
  return $sks;
}

function getTotalSksPilihanDiambil($nim,$id_semester=NULL) {
  global $db2;
  $and_semester = "";
  if ($id_semester!=NULL) {
    $and_semester = "and id_semester<=".$id_semester;
  }
  //minimal SKS diambil
  $data_sks_diambil = $db2->query("select max(nilai_indeks),total_sks from tb_data_matakuliah
inner join tb_data_kelas_krs_detail on matkul_id=id_matkul
inner join tb_data_kelas_krs using(krs_id)
where nim=? and is_wajib=0  and nilai_indeks is not null $and_semester
group by id_matkul;",array('nim' => $nim));
  $sks = 0;
  if ($data_sks_diambil->rowCount()>0) {
    foreach ($data_sks_diambil as $diambil) {
      $sks+=$diambil->total_sks;
    }
  }
  return $sks;
}


/**
 * check apakah matakuliah sudah diambil atau beblum
 * @param  int $id_matkul it matakuliah
 * @param  [type] $nim       nim mahasiswa
 * @return [type]            return nilai huruf jika sudah diambil otherwise return false
 */
function checkMatDiambil($id_matkul,$nim) {
  global $db2;
  $check_mk = $db2->fetchCustomSingle("select max(nilai_huruf) as nilai_huruf from tb_data_kelas_krs_detail inner join tb_data_kelas_krs using(krs_id) where matkul_id=? and nim=? group by matkul_id",array("matkul_id" => $id_matkul,"nim" => $nim));

  if ($check_mk) {
    if ($check_mk->nilai_huruf=="") {
      return "Kosong";
    } else {
      return $check_mk->nilai_huruf;
    }
  } else {
      //grab if ada matkul setara
      $check_setara = $db2->query("select id_matkul_setara from tb_data_matakuliah_setara where id_matkul=?",array("id_matkul" => $id_matkul));
      if ($check_setara->rowCount()>0) {
        foreach ($check_setara as $setara) {
          $id_matkul_setara[] = $setara->id_matkul_setara;
        }
        $implode_id_mk_setara = implode(",", $id_matkul_setara);

        $check_mk_again = $db2->query("select max(nilai_huruf) as nilai_huruf from tb_data_kelas_krs_detail inner join tb_data_kelas_krs using(krs_id) where matkul_id in($implode_id_mk_setara) and nim=? group by matkul_id",array("nim" => $nim));
          if ($check_mk_again->rowCount()>0) {
            foreach ($check_mk_again as $mk_setara_ambil) {
              $nilai_huruf_setara[] = $mk_setara_ambil->nilai_huruf;
            }
            sort($nilai_huruf_setara);
            if ($nilai_huruf_setara[0]=="") {
              return "Kosong";
            } else {
              return $nilai_huruf_setara[0];
            }
          }

      } else {
        return 0;
      }

  }
}

function getNamaMkKelas($kelas_id) {
  global $db2;
  $data = $db2->fetchCustomSingle("select nama_mk,kls_nama from tb_data_kelas
inner join tb_data_matakuliah using(id_matkul) where kelas_id=?",array('kelas_id' => $kelas_id));
  return $data;
}

function checkDiambilCurrent($id_semester,$nim) {
  global $db2;
  $id_mk = array();
  $check_mk = $db2->query("select krs_detail_id,matkul_id from tb_data_kelas_krs_detail inner join tb_data_kelas_krs using (krs_id) where id_semester=? and nim=? group by matkul_id",array("id_semester" => $id_semester,"nim" => $nim));
  if ($check_mk->rowCount()>0) {
    foreach ($check_mk as $mk) {
      $id_mk[] = $mk->matkul_id;
    }
  }
  return $id_mk;
}

function isSemuaKelasPenuh($id_matkul,$id_semester) {
  global $db2;
  $check_penuh = $db2->fetchCustomSingle("select sum(kuota) as kuota,sum((select count(krs_detail_id) from tb_data_kelas_krs_detail 
where kelas_id=tb_data_kelas.kelas_id and batal=0)) as peserta_kelas from tb_data_kelas where id_matkul=? and sem_id=?",array('id_matkul' => $id_matkul,'sem_id' => $id_semester));
  if ($check_penuh->peserta_kelas >= $check_penuh->kuota) {
    return true;
  } else {
    return false;
  }
}

function isBentrok($kelas_id,$id_semester,$nim) {
  global $db2;
  $bentrok = array();
  //kelas yang akan di bandingkan
  $data_kelas_check = $db2->fetchSingleRow("tb_data_kelas_jadwal","kelas_id",$kelas_id);
  if ($data_kelas_check) {
     //loop jadwal kelas sudah diambil
      $loop_jadwal = $db2->query("select kelas_id from tb_data_kelas_krs_detail inner join tb_data_kelas_krs using(krs_id)
        inner join tb_data_kelas_jadwal using(kelas_id)
    where id_semester=? and nim=?",array('id_semester' => $id_semester,'nim' => $nim));
      foreach ($loop_jadwal as $jd) {
      $check_bentrok_kelas = $db2->query("select * from tb_data_kelas_jadwal where
        ('".$data_kelas_check->jam_mulai."'>jam_mulai and '".$data_kelas_check->jam_mulai."'<jam_selesai or '".$data_kelas_check->jam_selesai."'> jam_mulai and '".$data_kelas_check->jam_selesai."'<jam_selesai or jam_mulai 
        > '".$data_kelas_check->jam_mulai."' and jam_mulai <'".$data_kelas_check->jam_selesai."' or jam_selesai>'".$data_kelas_check->jam_mulai."' and jam_selesai<'".$data_kelas_check->jam_selesai."' or jam_mulai='".$data_kelas_check->jam_mulai."' and jam_selesai='".$data_kelas_check->jam_selesai."' ) and id_hari='".$data_kelas_check->id_hari."' and kelas_id='".$jd->kelas_id."'");
          if ($check_bentrok_kelas->rowCount()>0) {
            $bentrok[] = "Matakuliah ".getNamaMkKelas($data_kelas_check->kelas_id)->nama_mk." kelas ".getNamaMkKelas($jd->kelas_id)->kls_nama." Bentrok dengan ".getNamaMkKelas($jd->kelas_id)->nama_mk.' Kelas '.getNamaMkKelas($jd->kelas_id)->kls_nama;
          } 
      }
  }
 
  return $bentrok;
}

function isKelasPenuh($id_kelas) {
  global $db2;
  $check_penuh = $db2->fetchCustomSingle("select sum(kuota) as kuota,sum((select count(krs_detail_id) from tb_data_kelas_krs_detail 
where kelas_id=tb_data_kelas.kelas_id and batal=0)) as peserta_kelas from tb_data_kelas where kelas_id=?",array('kelas_id' => $id_kelas));
  if ($check_penuh->peserta_kelas >= $check_penuh->kuota) {
    return true;
  } else {
    return false;
  }
}

function getProfilUser($id_user) {
  global $db2;
  return $db2->fetchSingleRow("sys_users","id",$id_user);
}

function tanggalWaktu() {
  return date('Y-m-d H:i:s');
}
/**
 * check apakah matakuliah sudah diambil syarat
 * @param  int $id_matkul it matakuliah
 * @param  [type] $nim       nim mahasiswa
 * @param  [type] string     syart L : harus lulus, A: ambil saja, S : sejajar
 * @return [type]            return nilai huruf jika sudah diambil otherwise return false
 */
function checkMatDiambilSyarat($id_matkul,$nim,$syarat) {
  global $db2;
  $and_syarat = "";
  $join_matkul = "";
  if ($syarat=='L') {
    $and_syarat = "and nilai_indeks>=bobot_minimal_lulus";
    $join_matkul = "inner join tb_data_matakuliah on matkul_id=id_matkul";
  }

  $check_mk = $db2->fetchCustomSingle("select max(nilai_huruf) as nilai_huruf from tb_data_kelas_krs_detail
  $join_matkul
  inner join tb_data_kelas_krs using(krs_id) where matkul_id=? and nim=? $and_syarat group by matkul_id",array("matkul_id" => $id_matkul,"nim" => $nim));

  if ($check_mk) {
    return 1;
  } else {
      //grab if ada matkul setara
      $check_setara = $db2->query("select id_matkul_setara from tb_data_matakuliah_setara where id_matkul=?",array("id_matkul" => $id_matkul));
      if ($check_setara->rowCount()>0) {
        foreach ($check_setara as $setara) {
          $id_matkul_setara[] = $setara->id_matkul_setara;
        }
        $implode_id_mk_setara = implode(",", $id_matkul_setara);

        $check_mk_again = $db2->query("select max(nilai_huruf) as nilai_huruf from tb_data_kelas_krs_detail
         $join_matkul
         inner join tb_data_kelas_krs using(krs_id) where matkul_id in($implode_id_mk_setara) and nim=? $and_syarat group by matkul_id",array("nim" => $nim));
          if ($check_mk_again->rowCount()>0) {
            return 1;
          } else {
            return 0;
          }

      } else {
        return 0;
      }
  }
}

function checkPrasyaratMk($id_matkul,$nim) {
  global $db2;
  $error_syarat = array();
  //first check if matkul has prasyarat
  $has_prasyarat = $db2->fetchCustomSingle("select id_matkul from tb_data_matakuliah_prasyarat where id_matkul=?",array('id_matkul' => $id_matkul));
  if ($has_prasyarat) {
  $is_lulus_setara = array();
    //$mk_syarat = $db2->query("select id_matkul,nama_mk,bobot_minimal_lulus from tb_data_matakuliah where id_matkul in($id_matkul_syarat)");
    $mk_syarat = $db2->query("select tb_data_matakuliah.id_matkul,tb_data_matakuliah_prasyarat.syarat,tb_data_matakuliah.kode_mk,tb_data_matakuliah.nama_mk,id_matkul_prasyarat,prs.kode_mk as mk_prasyarat_kode_mk,prs.nama_mk as nama_mk_prasyarat,
prs.bobot_minimal_lulus as mk_prasyarat_bobot_minimal_lulus from tb_data_matakuliah_prasyarat
inner join tb_data_matakuliah on tb_data_matakuliah_prasyarat.id_matkul=tb_data_matakuliah.id_matkul
inner join tb_data_matakuliah prs on id_matkul_prasyarat=prs.id_matkul
 where tb_data_matakuliah_prasyarat.id_matkul=?",array('id_matkul' => $id_matkul));
    foreach ($mk_syarat as $dt_mk_syarat) {
      if (!checkMatDiambilSyarat($dt_mk_syarat->id_matkul_prasyarat,$nim,$dt_mk_syarat->syarat)) {
        if ($dt_mk_syarat->syarat=='L') {
          $status = "harus sudah diambil & Lulus";
        } else {
          $status = "harus sudah diambil";
        }
        $error_syarat[] = "Matakuliah ".$dt_mk_syarat->nama_mk_prasyarat." $status";
      }
    }
    return $error_syarat;
  } else {
      return $error_syarat;
    }
  }

function getStatusMahasiswa() {
  global $db2;
  $status_mahasiswa_data = $db2->query("select * from tb_ref_status_mahasiswa");
  foreach ($status_mahasiswa_data as $status) {
    $status_mahasiswa[$status->id_stat_mhs] = $status->nm_stat_mhs;
  }
  return $status_mahasiswa;
}
function getProdiJenjang() {
  global $db2;
  $prodi_jenjang = $db2->query("select * from view_prodi_jenjang where kode_jur in(".getAksesProdi().")");
  foreach ($prodi_jenjang as $prodi) {
    $data_prodi[$prodi->kode_jur] = string_rapih($prodi->jurusan);
  }
  return $data_prodi;
}
/**
 * get IP mahasiswa pada periode semester 
 * @param  [type] $nim         [description]
 * @param  [type] $id_semester periode semester
 * @return [type]              ip 
 */
function getDataIpSks($nim,$id_semester) {
  global $db2;
  $data_ip_sks = array();
  $krs_data = $db2->fetchCustomSingle("select sum(total_sks) as jml_sks,sum(nilai_indeks * total_sks) as jml_bobot from tb_data_kelas_krs_detail inner join tb_data_matakuliah 
on tb_data_kelas_krs_detail.matkul_id=tb_data_matakuliah.id_matkul
inner join tb_data_kelas_krs using(krs_id) where nim=? and id_semester=?  and batal=0 and nilai_indeks is not null",array('nim' => $nim,'id_semester' => $id_semester));
  if ($krs_data->jml_sks>0) {
    //ip adalah jumlah bobot nilai (bobot * sks) / jumlah sks
    $ip = $krs_data->jml_bobot/$krs_data->jml_sks;
    $ip = number_format($ip,2);
    $data_ip_sks['sks'] = $krs_data->jml_sks;
    $data_ip_sks['ip'] = $ip;
  }
  return $data_ip_sks;
}
/**
 * get IPK mahasiswa pada periode semester 
 * @param  [type] $nim         [description]
 * @param  [type] $id_semester periode semester
 * @return [type]              ip 
 */
function getIpk($nim,$id_semester=NULL) {
  global $db2;
  $and_semester = "";
  if ($id_semester!=NULL) {
    $and_semester = "and id_semester<=".$id_semester;
  }
  $krs_data = $db2->fetchCustomSingle("select sum(total_sks) as jml_sks,sum(nilai_indeks * total_sks) as jml_bobot from tb_data_kelas_krs_detail inner join tb_data_matakuliah 
on tb_data_kelas_krs_detail.matkul_id=tb_data_matakuliah.id_matkul
inner join tb_data_kelas_krs using(krs_id) where nim=? $and_semester  and batal=0 and nilai_indeks is not null",array('nim' => $nim));
  if ($krs_data->jml_sks>0) {
    //ip adalah jumlah bobot nilai (bobot * sks) / jumlah sks
    $ipk = $krs_data->jml_bobot/$krs_data->jml_sks;
    $ipk = number_format($ipk,2);
  } else {
    $ipk = 0;
  }
  return $ipk;
}
function updateAkmSemesterNim($id_semester,$nim,$status=NULL){
  global $db2;
  //check if akm exist
  $check_akm = $db2->checkExist('tb_data_akm',array('id_semester' => $id_semester,'nim' => $nim));
  if ($check_akm==false) {
    $data_insert_akm = array(
      'nim' => $nim,
      'id_semester' => $id_semester,
      'id_stat_mhs' => $status,
      'ip' => 0,
      'ipk' => 0,
      'jatah_sks' => 0,
      'sks_semester' => 0,
      'sks_wajib_kumulatif' => 0,
      'sks_pilihan_kumulatif' => 0,
      'sks_total' => 0,
      'date_created' => date('Y-m-d')
    );
    $db2->insert('tb_data_akm',$data_insert_akm);
  }
  $data_akm = $db2->fetchCustomSingle("select akm_id,id_stat_mhs,tb_data_akm.nim,(select sum(total_sks) from tb_data_kelas_krs_detail inner join tb_data_matakuliah 
  on tb_data_kelas_krs_detail.matkul_id=tb_data_matakuliah.id_matkul
  inner join tb_data_kelas_krs using(krs_id) where nim=tb_data_akm.nim and id_semester=tb_data_akm.id_semester  and batal=0) as sks,
  (select format(sum(nilai_indeks * total_sks)/sum(total_sks),2) from tb_data_kelas_krs_detail inner join tb_data_matakuliah 
  on tb_data_kelas_krs_detail.matkul_id=tb_data_matakuliah.id_matkul
  inner join tb_data_kelas_krs using(krs_id) where nim=tb_data_akm.nim and id_semester=tb_data_akm.id_semester  and batal=0 and nilai_indeks  is not null) as ip,
  (select format(sum(nilai_indeks * total_sks)/sum(total_sks),2) from tb_data_kelas_krs_detail inner join tb_data_matakuliah 
  on tb_data_kelas_krs_detail.matkul_id=tb_data_matakuliah.id_matkul
  inner join tb_data_kelas_krs using(krs_id) where nim=tb_data_akm.nim and id_semester<=tb_data_akm.id_semester  and batal=0 and nilai_indeks is not null) as ipk,
  (select sks_mak from tb_data_jatah_sks j where  IFNULL((select akm.ip  from tb_data_akm akm where akm.nim=tb_data_akm.nim
   and akm.id_semester<tb_data_akm.id_semester
  and akm.id_stat_mhs='A' ORDER BY id_semester DESC LIMIT 1),4) BETWEEN j.ip_min and j.ip_mak) as jatah_sks,
  (select SUM(IF(is_wajib = '1', total_sks,0)) from tb_data_kelas_krs_detail inner join tb_data_kelas_krs using (krs_id)
  inner join tb_data_matakuliah on matkul_id=id_matkul where nim=tb_data_akm.nim and id_semester<=tb_data_akm.id_semester  and batal=0) as sks_wajib_diambil_kumulatif,
  (select SUM(IF(is_wajib = '0', total_sks,0)) from tb_data_kelas_krs_detail inner join tb_data_kelas_krs using (krs_id)
  inner join tb_data_matakuliah on matkul_id=id_matkul where nim=tb_data_akm.nim and id_semester<=tb_data_akm.id_semester  and batal=0) as sks_pilihan_diambil_kumulatif,
  (select SUM(total_sks) from tb_data_kelas_krs_detail inner join tb_data_kelas_krs using (krs_id)
  inner join tb_data_matakuliah on matkul_id=id_matkul where nim=tb_data_akm.nim and id_semester<=tb_data_akm.id_semester  and batal=0) as sks_kumulatif
   from tb_data_akm 
  inner join tb_master_mahasiswa using(nim) where tb_data_akm.id_semester=? and tb_data_akm.nim=?",array('id_semester' => $id_semester,'nim' => $nim));
  if ($data_akm->sks=="") {
        $sks_semester=0;
      } else {
        $sks_semester = $data_akm->sks;
      }
      if ($data_akm->ip=="") {
        $ip = 0;
      } else {
        $ip = $data_akm->ip;
      }
      if ($data_akm->ipk=="") {
        $ipk = 0;
      } else {
        $ipk = $data_akm->ipk;
      }

      if ($data_akm->sks_wajib_diambil_kumulatif=="") {
        $sks_wajib_kumulatif = 0;
      } else {
        $sks_wajib_kumulatif = $data_akm->sks_wajib_diambil_kumulatif;
      }
      if ($data_akm->sks_pilihan_diambil_kumulatif=="") {
        $sks_pilihan_kumulatif = 0;
      } else {
        $sks_pilihan_kumulatif = $data_akm->sks_pilihan_diambil_kumulatif;
      }

      if ($data_akm->jatah_sks=="") {
        $jatah_sks = 0;
      } else {
        $jatah_sks = $data_akm->jatah_sks;
      }
      if ($data_akm->sks_kumulatif=="") {
        $sks_total = 0;
      } else {
        $sks_total = $data_akm->sks_kumulatif;
      }

      if ($status!=NULL) {
        $id_stat_mhs = $status;
      } else {
        if ($sks_semester==0) {
          $id_stat_mhs = 'N';
        } else {
          $id_stat_mhs = 'A';
        }
      }
      
      

      $data_update_akm = array(
        'ip' => $ip,
        'ipk' => $ipk,
        'id_stat_mhs' => $id_stat_mhs,
        'jatah_sks' => $jatah_sks,
        'sks_semester' => $sks_semester,
        'sks_wajib_kumulatif' => $sks_wajib_kumulatif,
        'sks_pilihan_kumulatif' => $sks_pilihan_kumulatif,
        'sks_total' => $sks_total,
        'date_updated' => date('Y-m-d')
      );
      $db2->update('tb_data_akm',$data_update_akm,'akm_id',$data_akm->akm_id);
}

function getSemesterMahasiswa($nim,$periode) {
  global $db2;
  $smt = $db2->fetchCustomSingle("select ((left($periode,4)-left(mulai_smt,4))*2)+right($periode,1)-(floor(right(mulai_smt,1)/2)) as smt from mahasiswa where nim=?",array('nim' => $nim));
  return $smt->smt;
}
