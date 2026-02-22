<?php
include "../../inc/config.php";
require("../../lib/fpdf/fpdf.php");
// require("../../lib/barcode.php");
include "../../lib/phpqrcode/qrlib.php"; 
$tempdir = "../../../upload/qr/";

function save_image($inPath,$outPath)
{ //Download images from remote server
    $in=    fopen($inPath, "rb");
    $out=   fopen($outPath, "wb");
    while ($chunk = fread($in,8192))
    {
        fwrite($out, $chunk, 8192);
    }
    fclose($in);
    fclose($out);
} 
 
// Function to find an existing file by name in a specific parent folder
function findExistingFileByName($service, $fileName, $parentFolderId) {
    $query = "name='$fileName' and '$parentFolderId' in parents and trashed=false";
    $files = $service->files->listFiles(['q' => $query, 'fields' => 'files(id, name)'])->getFiles();

    if (!empty($files)) {
        return $files[0]->getId(); // Return the ID of the first matching file
    }
    return null; // File not found
}

function getExt($url) {
        global $db;
    // Database fetch (as per your provided code)
    $email_data = $db->fetch_single_row('tb_token', 'id', 2);

    // Setup Google API Client
    $client = new Google_Client();
    $client->setClientId($email_data->client_id);
    $client->setClientSecret($email_data->client_secret);
    $client->setRedirectUri($email_data->redirect_url);
    $client->addScope(Google_Service_Drive::DRIVE_METADATA_READONLY); // Use read-only scope for metadata
    $client->setAccessType('offline');

    $access_token = $db->convert_obj_to_array(json_decode($email_data->access_token));


    $client->setAccessToken($access_token);

    if ($client->isAccessTokenExpired()) {
      //refresh token
      $client->refreshToken($data_token->refresh_token);
      $newtoken=$client->getAccessToken();

      //get access token
      //update token
     $db->update('tb_token',array('access_token' => json_encode($newtoken)),'id',$email_data->id);

      //set access token
      $client->setAccessToken($newtoken);

    }




    // Initialize Drive Service
    $service = new Google_Service_Drive($client);

    // Extract File ID from URL
    $fileId = basename(parse_url($url, PHP_URL_PATH)); // Extracts '1XUOlJnutcfUgVLC9JpZGzrF5NAzrMkeC'

    try {
        // Fetch file metadata
        $file = $service->files->get($fileId, ['fields' => 'mimeType,name']);
        // Get MIME type
        $mimeType = $file->getMimeType();
        
        // Map MIME type to extension
        $mimeToExtension = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/bmp' => 'bmp',
            'image/webp' => 'webp',
        ];
        
        $extension = isset($mimeToExtension[$mimeType]) ? $mimeToExtension[$mimeType] : '.png';
        return $extension;
    } catch (Exception $e) {
        global $notice, $authException;
        $notice = "Error: " . $e->getMessage();
        if (strpos($e->getMessage(), 'insufficientPermissions') !== false) {
            $authException = true;
        }
        echo $notice;
    }
}
  
 //simpan file kedalam folder temp dengan nama 001.png 
// echo base_url(); 
 //echo "<img src='../../lib/barcode.php?text=test' />";
 //file_put_contents('../../../upload/tes.png','../../lib/barcode.php?text=test');


$q = $db->query("select v.nim,v.nama,v.angkatan,v.jurusan as nama_jurusan,v.nama_fakultas,sys_users.foto_user,is_photo_drived,
ifnull((select tb_data_cuti_mahasiswa.id_cuti from tb_data_cuti_mahasiswa inner join tb_data_cuti_mahasiswa_periode
using (id_cuti) where 
tb_data_cuti_mahasiswa_periode.periode='".get_sem_aktif()."' 
and tb_data_cuti_mahasiswa.nim=v.nim),0) as cuti,
ifnull(k.id,1) as status_aktif,ifnull(jk.ket_keluar,'') as ket_keluar from view_simple_mhs_data v
inner join sys_users on v.nim=sys_users.username
left join tb_data_kelulusan k on k.nim=v.nim
left join jenis_keluar jk on jk.id_jns_keluar=k.id_jenis_keluar where 
v.mhs_id=? ",array($_GET['id']));
foreach ($q as $k) {

        if ($k->status_aktif=='1') {
            if ($k->cuti!='0') {
                $status = "Cuti";
            }else{
                $status = "Mahasiswa Aktif";
            }
        }else{
                $status = $k->ket_keluar;
        }
        $codeContents = "NIM : $k->nim \nNama: $k->nama\nAngkatan : $k->angkatan\nProdi : $k->nama_jurusan\nFakultas : $k->nama_fakultas\nStatus : $status"; 
        save_image(base_url().'dashboard/lib/barcode.php?text='.$k->nim,'../../../upload/barcode/'.$k->nim.'.png');
        QRcode::png($codeContents,$tempdir."001.png"); 
        $pdf = new FPDF('P','mm',array(130,150));
        $pdf->AddPage();
        if ($k->is_photo_drived=='Y') {
                $ext = getExt($k->foto_user);
                $foto = $k->foto_user.'=w300?ext=.'.$ext;
        } else {
                $foto = "../../../upload/back_profil_foto/".$k->foto_user;
        }



        $pdf->Image('../../assets/ktm/depan2.png',10,10,-300);
        $pdf->Image('../../assets/ktm/belakang2.png',10,80,-300);
        if ($k->foto_user=='default_user.png') {
                $pdf->Image($foto,92,36,20,20);
        }else{
                $pdf->Image($foto,92,36,-300);
        }
        $pdf->Image('../../../upload/barcode/'.$k->nim.'.png',73,82,45,15);

        //$pdf->SetXY(28, 33);
        $pdf->Image($tempdir."001.png",17,35, 20);
        $pdf->SetFont('Arial','B',8);
        $pdf->SetXY(38, 33);
        $pdf->Cell(30,10, $k->nama); 
        $pdf->SetXY(38, 36.5);
        $pdf->Cell(30,10, "NIM. ".$k->nim); 
        $pdf->SetXY(38, 43);
        $pdf->MultiCell(50,3, $k->nama_jurusan."\nFakultas ".ucwords(strtolower($k->nama_fakultas)),0); 
        // $pdf->SetXY(38, 49);
        // $pdf->MultiCell(50,2.8, "Fakultas ".ucwords(strtolower($k->nama_fakultas)),0); 
//      $pdf->Output('F','../../../upload/ktm/'.$k->nim.'.pdf');
                $pdf->Output();
}

?>