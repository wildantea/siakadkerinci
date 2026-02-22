<?php
session_start();
include "../../inc/config.php";

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

$json_response = [];

if ($_POST["total_data"] < 1) {
    $msg = "<div class=\"alert alert-warning \" role=\"alert\">
        <font color=\"#3c763d\">Tidak ada data berhasil di Upload</font><br />
        </div>";

    $jumlah["last_notif"] = $msg;
    array_push($json_response, $jumlah);

    echo json_encode($json_response);
    exit();
}

$offset = $_POST["offset"];

$jumlah["offset"] = $offset;

$no = $offset;


$type_user = $_POST['type_user'];


if ($_POST["total_data"] <= 1) {
    $limit = $_POST["total_data"];
} else {
    $limit = 1;
}

$data = $db->query("SELECT *
FROM sys_users
WHERE foto_user != 'default_user.png' AND group_level = '".$type_user."' and is_photo_drived='N'  and username in(select nim from mahasiswa where mulai_smt > 20181 and mulai_smt!='20241' and status='M') and photo_not_found='N'
order by id desc
limit $limit
");




if ($email_data->login=='Y') {
      

foreach ($data as $dt) {

      $refreshToken = $email_data->refresh_token;
        $client->refreshToken($refreshToken);
        $client->setAccessToken($client->getAccessToken());

    if (file_exists("../../../upload/back_profil_foto/".$dt->foto_user)) {
        $extension = strtolower(pathinfo("../../../upload/back_profil_foto/".$dt->foto_user, PATHINFO_EXTENSION));
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, "../../../upload/back_profil_foto/".$dt->foto_user);
        finfo_close($finfo);
        $base_filename  = pathinfo("../../../upload/back_profil_foto/".$dt->foto_user, PATHINFO_FILENAME);

        try {
                // 1. Find or create the "data-mahasiswa" folder (root folder)
                //$dataMahasiswaFolderId = findOrCreateFolder($service, 'data-mahasiswa');

                $dataMahasiswaFolderId = '1ghJJMPi4GcFzRMk-vVsUofkSzQAW3reF';
                $mhs = $db->fetch_single_row("mahasiswa", "nim", $dt->username);
                 $nama_mhs = str_replace(" ", "-", $mhs->nim . "-" . $mhs->nama);

                 $nama_mhs = str_replace("'", "", $nama_mhs);
                  $nama_mhs = str_replace("`", "", $nama_mhs);


                // 2. Find or create the student's folder within "data-mahasiswa"
               $studentFolderId = findOrCreateFolder($service, $nama_mhs, $dataMahasiswaFolderId);

               $nama_file = 'foto-profil-'.$nama_mhs.'.'.$extension;

             


                // --- NEW LOGIC FOR DELETE AND RE-UPLOAD ---
               //get file current 
               $current_file_name = $file_current->file_name;
              
               //check if user first time
               /*if ($dt->is_photo_changed=='Y' && $dt->is_photo_drived=='Y') {
                    $existingFileId = findExistingFileByName($service, $current_file_name, $studentFolderId);
                    if ($existingFileId) {
                        // File exists, delete it first
                        $service->files->delete($existingFileId);
                    }
               }*/

                // 3. Create file metadata with the parent folder ID
                $fileMetadata = new Google_Service_Drive_DriveFile([
                    'name' => $nama_file,
                    'parents' => [$studentFolderId] // Set the parent folder to the student's folder
                ]);

                $content = file_get_contents("../../../upload/back_profil_foto/".$dt->foto_user);




                // 4. Upload the file
                $uploadedFile = $service->files->create($fileMetadata, [
                    'data' => $content,
                    'mimeType' => $fileMimeType,
                    'uploadType' => 'multipart',
                    'fields' => 'id, name, webViewLink'
                ]);

                // 5. Make the uploaded file publicly accessible (optional, adjust as needed)
                $permission = new Google_Service_Drive_Permission();
                $permission->setType('anyone');
                $permission->setRole('reader'); // 'reader' role makes it publicly viewable
                $service->permissions->create($uploadedFile->getId(), $permission, ['fields' => 'id']);
               /* echo "File permissions set to public.<br>";

                echo "File uploaded successfully!<br>";
                echo "File ID: " . $uploadedFile->getId() . "<br>";
                echo "File Name: " . $uploadedFile->getName() . "<br>";
                echo "Public View Link: <a href='" . $uploadedFile->getWebViewLink() . "' target='_blank'>" . $uploadedFile->getWebViewLink() . "</a><br>";*/

                //$link_file = $uploadedFile->getWebViewLink();

                $link_file = 'https://lh3.googleusercontent.com/d/'.$uploadedFile->getId();

                if ($dt->foto_user!='default_user.png') {
                    unlink("../../../upload/back_profil_foto/".$dt->foto_user);
                }

                    $data = array(
                        "is_photo_changed" => 'Y',
                        "is_photo_drived" => 'Y',
                        "foto_user" => $link_file,
                        "date_updated_foto" => date('Y-m-d H:i:s')
                    );
                       
                    $in = $db->update("sys_users",$data,'id',$dt->id);
                    action_response($db->getErrorMessage());


            } catch (Google_Service_Exception $e) {
                 action_response("Google Service Error: " . $e->getMessage());
              /*  echo "Error Code: " . $e->getCode() . "<br>";
                echo "Errors: " . print_r($e->getErrors(), true) . "<br>";*/
            } catch (Exception $e) {
                action_response("An error occurred: " . $e->getMessage());
            }


    } else {
        $db->update("sys_users",array('photo_not_found' => 'Y'),'id',$dt->id);

    }
    
}

}


if ($_POST['last']=='yes') {

    $msg ="<div class=\"alert alert-warning \" role=\"alert\"><font color=\"#3c763d\">" .$_POST['total_data'] ." data Kelas berhasil di generate</font><br />";
      $msg .= "<a class='btn btn-block btn-primary btn-xs'><i class='fa  fa-cloud-download'></i> Proses Selesai</a></div>
        </div>";

        $jumlah['data_uri'] = '';

        $jumlah['json_file'] = ''; 

    $jumlah["last_notif"] = $msg;
}

array_push($json_response, $jumlah);

echo json_encode($json_response);
exit();
?>
