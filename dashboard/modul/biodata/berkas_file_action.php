<?php
session_start();
include "../../inc/config.php";
session_check_end();
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



switch ($_GET["act"]) {
  case "in":

  $label = $db2->fetchSingleRow("tb_data_file_label","id_file_label",$_POST['id_file_label']);

  $extensions = explode(",", $label->extension); // splits into array
  // Quote each extension for SQL
  $quoted_extensions = array_map(function($ext) {
      return "'" . addslashes(trim($ext)) . "'";
  }, $extensions);
  // Create a string like "'pdf','png'"
  $in_clause = implode(",", $quoted_extensions);
  $mime = $db->query("select type,mime from tb_data_file_extention where type in($in_clause)");
  foreach ($mime as $ext) {
    $allowed_file_types[$ext->type] = $ext->mime;
  }


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file_name'])) {
    $file = $_FILES['file_name'];
    $maxSize = $label->size_file;

    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    $base_filename  = pathinfo($file['name'], PATHINFO_FILENAME);  

    if ($file['size'] > $maxSize) {
        action_response('Error Size '.$label->helper); 
    } elseif (!isset($allowed_file_types[$extension])) {
        action_response('Error Extensi '.$label->helper);
    } elseif ($allowed_file_types[$extension] !== $mime) {
        action_response("Error: MIME type $mime does not match expected type for .$extension.");
    } else {
        //upload drive
      // Create GMail Service
      
          if ($email_data->login=='Y') {
            $refreshToken = $email_data->refresh_token;
            $client->refreshToken($refreshToken);
            $client->setAccessToken($client->getAccessToken());

        if (!isset($_FILES['file_name']) || $_FILES['file_name']['error'] !== UPLOAD_ERR_OK) {
            $errorCode = $_FILES['file_name']['error'] ?? 'No file uploaded';
            action_response("Error saat file upload: " . $errorCode);
        } else {

            $fileName = basename($_FILES['file_name']['name']);
            $filePath = $_FILES['file_name']['tmp_name'];
            $fileMimeType = $_FILES['file_name']['type'];

            // Get student information (assuming you have this from your session)
            $mhs = $db->fetch_single_row("mahasiswa", "nim", $_SESSION['username']);
            $nama_mhs = str_replace(" ", "-", $mhs->nim . "-" . $mhs->nama);
            $nama_mhs = str_replace("'", "", $nama_mhs);
              $nama_mhs = str_replace("`", "", $nama_mhs);

            try {
                // 1. Find or create the "data-mahasiswa" folder (root folder)
                //$dataMahasiswaFolderId = findOrCreateFolder($service, 'data-mahasiswa');

                $dataMahasiswaFolderId = '1ghJJMPi4GcFzRMk-vVsUofkSzQAW3reF';



                // 2. Find or create the student's folder within "data-mahasiswa"
                $studentFolderId = findOrCreateFolder($service, $nama_mhs, $dataMahasiswaFolderId);

                $nama_file = $label->file_rename.'-'.$nama_mhs.'.'.$extension;

                // 3. Create file metadata with the parent folder ID
                $fileMetadata = new Google_Service_Drive_DriveFile([
                    'name' => $nama_file,
                    'parents' => [$studentFolderId] // Set the parent folder to the student's folder
                ]);

                $content = file_get_contents($filePath);

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

                $link_file = $uploadedFile->getWebViewLink();


                    $data = array(
                        "nim" => $mhs->nim,
                        "id_file_label" => $_POST["id_file_label"],
                        "file_name" => $nama_file,
                        "link_file" => $link_file,
                        "date_created" => date('Y-m-d H:i:s'),
                        "type_file" => $extension
                    );
                       
                    $in = $db->insert("tb_data_file",$data);

                    if (checkBiodataAllStatus($mhs->nim)==false) {
                        $db->update('mahasiswa',array('is_lengkap_data' => 'Y','tgl_lengkap' => date('Y-m-d H:i:s')),'nim',$mhs->nim);
                    }

                    /*$last_id = $db->last_insert_id();
                    //check extension type
                    $check_ext = $db->fetch_single_row("tb_data_file_extention","type",$extension);

                    $icon = "pdf_document.png";
                    if ($check_ext->file_type=='image') {
                        $icon = "gambar.png";
                    }

                      $res = array(
                        'res' => '<a target="_blank" data-toggle="tooltip" data-title="Lihat File" target="_blank" href="'.$link_file.'"><img style="cursor:pointer" src="'.$icon.'" width="30"></a> <a class="btn btn-primary ubah-file" data-toggle="tooltip" data-title="Klik untuk merubah File" data-id="'.$last_id.'"><i class="fa fa-pencil"></i> Edit</a>');*/
                      action_response($db->getErrorMessage());


            } catch (Google_Service_Exception $e) {
                 action_response("Google Service Error: " . $e->getMessage());
              /*  echo "Error Code: " . $e->getCode() . "<br>";
                echo "Errors: " . print_r($e->getErrors(), true) . "<br>";*/
            } catch (Exception $e) {
                action_response("An error occurred: " . $e->getMessage());
            }
            }
    }
}

}
    break;
  case "up":

  $file_current = $db->fetch_single_row("tb_data_file","id_file",$_POST['id']);
  $label = $db2->fetchSingleRow("tb_data_file_label","id_file_label",$file_current->id_file_label);

  $extensions = explode(",", $label->extension); // splits into array
  // Quote each extension for SQL
  $quoted_extensions = array_map(function($ext) {
      return "'" . addslashes(trim($ext)) . "'";
  }, $extensions);
  // Create a string like "'pdf','png'"
  $in_clause = implode(",", $quoted_extensions);
  $mime = $db->query("select type,mime from tb_data_file_extention where type in($in_clause)");
  foreach ($mime as $ext) {
    $allowed_file_types[$ext->type] = $ext->mime;
  }


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file_name'])) {
    $file = $_FILES['file_name'];
    $maxSize = $label->size_file;

    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    $base_filename  = pathinfo($file['name'], PATHINFO_FILENAME);  

    if ($file['size'] > $maxSize) {
        action_response('Error Size '.$label->helper); 
    } elseif (!isset($allowed_file_types[$extension])) {
        action_response('Error Extensi '.$label->helper);
    } elseif ($allowed_file_types[$extension] !== $mime) {
        action_response("Error: MIME type $mime does not match expected type for .$extension.");
    } else {
        //upload drive
      // Create GMail Service
      
          if ($email_data->login=='Y') {
            $refreshToken = $email_data->refresh_token;
            $client->refreshToken($refreshToken);
            $client->setAccessToken($client->getAccessToken());

        if (!isset($_FILES['file_name']) || $_FILES['file_name']['error'] !== UPLOAD_ERR_OK) {
            $errorCode = $_FILES['file_name']['error'] ?? 'No file uploaded';
            action_response("Error saat file upload: " . $errorCode);
        } else {

            $fileName = basename($_FILES['file_name']['name']);
            $filePath = $_FILES['file_name']['tmp_name'];
            $fileMimeType = $_FILES['file_name']['type'];

            // Get student information (assuming you have this from your session)
            $mhs = $db->fetch_single_row("mahasiswa", "nim", $_SESSION['username']);
            $nama_mhs = str_replace(" ", "-", $mhs->nim . "-" . $mhs->nama);
            $nama_mhs = str_replace("'", "", $nama_mhs);
              $nama_mhs = str_replace("`", "", $nama_mhs);

            try {
                // 1. Find or create the "data-mahasiswa" folder (root folder)
                //$dataMahasiswaFolderId = findOrCreateFolder($service, 'data-mahasiswa');

                $dataMahasiswaFolderId = '1ghJJMPi4GcFzRMk-vVsUofkSzQAW3reF';



                // 2. Find or create the student's folder within "data-mahasiswa"
                $studentFolderId = findOrCreateFolder($service, $nama_mhs, $dataMahasiswaFolderId);

               $nama_file = $label->file_rename.'-'.$nama_mhs.'.'.$extension;


                // --- NEW LOGIC FOR DELETE AND RE-UPLOAD ---
               //get file current 
               $current_file_name = $file_current->file_name;
              

                $existingFileId = findExistingFileByName($service, $current_file_name, $studentFolderId);

                if ($existingFileId) {
                    // File exists, delete it first
                    $service->files->delete($existingFileId);
                }


           

                // 3. Create file metadata with the parent folder ID
                $fileMetadata = new Google_Service_Drive_DriveFile([
                    'name' => $nama_file,
                    'parents' => [$studentFolderId] // Set the parent folder to the student's folder
                ]);

                $content = file_get_contents($filePath);

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

                $link_file = $uploadedFile->getWebViewLink();


                    $data = array(
                        "file_name" => $nama_file,
                        "link_file" => $link_file,
                        "date_updated" => date('Y-m-d H:i:s'),
                        "type_file" => $extension
                    );
                       
                    $in = $db->update("tb_data_file",$data,'id_file',$_POST['id']);

                    /*$last_id = $db->last_insert_id();
                    //check extension type
                    $check_ext = $db->fetch_single_row("tb_data_file_extention","type",$extension);

                    $icon = "pdf_document.png";
                    if ($check_ext->file_type=='image') {
                        $icon = "gambar.png";
                    }

                      $res = array(
                        'res' => '<a target="_blank" data-toggle="tooltip" data-title="Lihat File" target="_blank" href="'.$link_file.'"><img style="cursor:pointer" src="'.$icon.'" width="30"></a> <a class="btn btn-primary ubah-file" data-toggle="tooltip" data-title="Klik untuk merubah File" data-id="'.$last_id.'"><i class="fa fa-pencil"></i> Edit</a>');*/
                      action_response($db->getErrorMessage());


            } catch (Google_Service_Exception $e) {
                 action_response("Google Service Error: " . $e->getMessage());
              /*  echo "Error Code: " . $e->getCode() . "<br>";
                echo "Errors: " . print_r($e->getErrors(), true) . "<br>";*/
            } catch (Exception $e) {
                action_response("An error occurred: " . $e->getMessage());
            }
            }
    }
}

}

    break;
  default:
    # code...
    break;
}

?>