<?php
session_start();
include "../../../inc/config.php";


require_once '/var/www/html/dashboard/lib/mail2/mail/vendor/autoload.php';

$notice = '';
$authException = false;

$email_data = $db->fetch_single_row('tb_token','id',2);


// Setup Google API Client
$client = new Google_Client();
$client->setClientId($email_data->client_id);

$client->setClientSecret($email_data->client_secret);
$client->setRedirectUri($email_data->redirect_url);
$client->addScope(Google_Service_Drive::DRIVE_FILE); // Scope for uploading files
$client->setAccessType('offline');

// --- 5. Initialize Drive Service ---
$service = new Google_Service_Drive($client);
/**
 * Finds a folder by name or creates it if it doesn't exist.
 *
 * @param Google_Service_Drive $service The Google Drive service object.
 * @param string $folderName The name of the folder to find or create.
 * @return string The ID of the found or created folder.
 * @throws Exception If folder cannot be found or created.
 */
// Function to find or create a folder within a specified parent
function findOrCreateFolder($service, $folderName, $parentFolderId = null) {
    $query = "mimeType='application/vnd.google-apps.folder' and name='$folderName' and trashed=false";
    if ($parentFolderId) {
        $query .= " and '$parentFolderId' in parents";
    }

    $files = $service->files->listFiles(['q' => $query, 'fields' => 'files(id, name)'])->getFiles();

    if (empty($files)) {
        // Folder doesn't exist, create it
        $fileMetadata = new Google_Service_Drive_DriveFile([
            'name' => $folderName,
            'mimeType' => 'application/vnd.google-apps.folder',
        ]);
        if ($parentFolderId) {
            $fileMetadata->setParents([$parentFolderId]);
        }
        $folder = $service->files->create($fileMetadata, ['fields' => 'id']);
        return $folder->getId();
    } else {
        // Folder exists, return its ID
        return $files[0]->getId();
    }
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



if (!empty($_POST["selfie"])) {

    $gambar = $_POST["selfie"];

    // Decode base64
    $image_array_1 = explode(";", $gambar);
    $image_array_2 = explode(",", $image_array_1[1]);
    $gambar = base64_decode($image_array_2[1]);

    // Fungsi buat nama unik
    function uniqueName($prefix = 'gambar.png') {
        return uniqid() . '_' . $prefix;
    }

    $imageName = uniqueName('selfie.png');
    $localPath = "../../../../upload/" . $imageName;

    // Simpan ke folder upload
    file_put_contents($localPath, $gambar);

    if ($email_data->login == 'Y') {
        $refreshToken = $email_data->refresh_token;
        $client->refreshToken($refreshToken);
        $client->setAccessToken($client->getAccessToken());

        try {
            // ID folder "data-mahasiswa" di Google Drive
            $dataMahasiswaFolderId = '1TBRxEN32wQ2X_k96LxVWSNuWa1CBYQZJ';

            // Buat folder sesuai kelas_id jika belum ada
            $studentFolderId = findOrCreateFolder($service, $_POST['kelas_id'], $dataMahasiswaFolderId);

            // Metadata file
            $fileMetadata = new Google_Service_Drive_DriveFile([
                'name'    => $imageName,
                'parents' => [$studentFolderId]
            ]);

            // Deteksi MIME type dari file lokal
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $fileMimeType = finfo_file($finfo, $localPath);
            finfo_close($finfo);

            $content = file_get_contents($localPath);

            // Upload ke Google Drive
            $uploadedFile = $service->files->create($fileMetadata, [
                'data'       => $content,
                'mimeType'   => $fileMimeType,
                'uploadType' => 'multipart',
                'fields'     => 'id, name, webViewLink'
            ]);

            // Buat file bisa diakses publik
            $permission = new Google_Service_Drive_Permission();
            $permission->setType('anyone');
            $permission->setRole('reader');
            $service->permissions->create($uploadedFile->getId(), $permission, ['fields' => 'id']);

            // Link direct foto
            $foto_absen = 'https://lh3.googleusercontent.com/d/' . $uploadedFile->getId();

            // Hapus file lokal
            unlink($localPath);

            // Update ke database
            //$foto_absen =  $link_file;
           /* $data = [
                "foto_absen"        => $link_file,
                "date_updated_foto" => date('Y-m-d H:i:s')
            ];
            //dump($data);
            $db->update("tb_data_kelas_pertemuan", $data, 'id_pertemuan', $_POST['pert']);*/

        } catch (Google_Service_Exception $e) {
            echo "Google Service Error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "An error occurred: " . $e->getMessage();
        }
    }
}

/*if (!empty($_POST['selfie'])) {
    $data = $_POST['selfie'];
    $data = str_replace('data:image/jpeg;base64,','',$data);
    $data = str_replace(' ','+',$data);
    $img = base64_decode($data);
    $filename = "../../../../upload/selfie_".$nip."_".time().".jpg";
    file_put_contents($filename, $img);
    // simpan path $filename ke database absensi
}*/

$row = $db2->fetchCustomSingle(
    "SELECT tanggal_pertemuan,jam_mulai,jam_selesai,kehadiran_dosen 
     FROM tb_data_kelas_pertemuan 
     WHERE id_pertemuan=?",
    ['id_pertemuan' => $_POST['pert']]
);

$tanggal_pertemuan = $row->tanggal_pertemuan;
$jam_mulai         = $row->jam_mulai;
$jam_selesai       = $row->jam_selesai;

$start_datetime = strtotime($tanggal_pertemuan . ' ' . $jam_mulai);
$end_datetime   = strtotime($tanggal_pertemuan . ' ' . $jam_selesai);

$new_tanggal_absen = date('Y-m-d H:i:s');
$absen_datetime = strtotime($new_tanggal_absen);

$is_sesuai_jadwal = ($absen_datetime >= $start_datetime && $absen_datetime <= $end_datetime) ? 'Y' : 'N';

// ----------------------
// Kalau sudah ada data kehadiran_dosen
// ----------------------
if (!empty($row->kehadiran_dosen)) {

    $kehadiran = json_decode($row->kehadiran_dosen, true);
    $found = false;

    // cek apakah NIP sudah ada
    foreach ($kehadiran as &$dosen) {
        if ($dosen['nip'] === $_POST['nip']) {
            // update data lama
            $dosen['tanggal_absen'] = $new_tanggal_absen;
            $dosen['foto_absen']    = $foto_absen;
            $dosen['sesuai_jadwal'] = $is_sesuai_jadwal;
            $found = true;
            break;
        }
    }

    // kalau nip belum ada, tambahkan baru
    if (!$found) {
        $kehadiran[] = [
            'nip'           => $_POST['nip'],
            'tanggal_absen' => $new_tanggal_absen,
            'sesuai_jadwal' => $is_sesuai_jadwal,
            'foto_absen'    => $foto_absen
        ];
    }


    $updated_json = json_encode($kehadiran, JSON_UNESCAPED_UNICODE);
    $update = ['kehadiran_dosen' => $updated_json];

    $db2->update('tb_data_kelas_pertemuan', $update, 'id_pertemuan', $_POST['pert']);

    action_response('', [
        'status_absen'  => '<span class="btn btn-success btn-sm"><i class="fa fa-check"></i> Sudah</span>',
        'tanggal_absen' => '<span class="btn btn-info btn-sm"><i class="fa fa-clock-o"></i> ' . tgl_time($new_tanggal_absen) . '</span>'
    ]);

// ----------------------
// Kalau belum ada sama sekali
// ----------------------
} else {

    $array_absen[] = [
        'nip'           => $_POST['nip'],
        'tanggal_absen' => $new_tanggal_absen,
        'sesuai_jadwal' => $is_sesuai_jadwal,
        'foto_absen'    => $foto_absen
    ];

    $json = json_encode($array_absen, JSON_UNESCAPED_UNICODE);
    $data_update = ['kehadiran_dosen' => $json];

    $db2->update('tb_data_kelas_pertemuan', $data_update, 'id_pertemuan', $_POST['pert']);

    action_response('', [
        'status_absen'  => '<span class="btn btn-success btn-sm"><i class="fa fa-check"></i> Sudah</span>',
        'tanggal_absen' => '<span class="btn btn-info btn-sm"><i class="fa fa-clock-o"></i> ' . tgl_time($new_tanggal_absen) . '</span>'
    ]);
}
