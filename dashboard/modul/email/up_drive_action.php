<?php
include "../../inc/config.php";
require_once '/var/www/html/dashboard/lib/mail2/mail/vendor/autoload.php';

$email_data = $db->fetch_single_row('tb_token','id',2);

/*$data_user = $db->fetch_custom_single("select * from sys_users where id=?",array('id' => $_POST["id"]));
$email = $data_user->email;*/

$notice = '';
$authException = false;

// Setup Google API Client
$client = new Google_Client();
$client->setClientId($email_data->client_id);

$client->setClientSecret($email_data->client_secret);
$client->setRedirectUri($email_data->redirect_url);
$client->addScope(Google_Service_Drive::DRIVE_FILE); // Scope for uploading files
$client->setAccessType('offline');


/**
 * Finds a folder by name or creates it if it doesn't exist.
 *
 * @param Google_Service_Drive $service The Google Drive service object.
 * @param string $folderName The name of the folder to find or create.
 * @return string The ID of the found or created folder.
 * @throws Exception If folder cannot be found or created.
 */
function findOrCreateFolder(Google_Service_Drive $service, $folderName) {
    // Search for the folder
    $parameters = [
        'q' => "mimeType='application/vnd.google-apps.folder' and name='{$folderName}' and trashed=false",
        'fields' => 'files(id, name)'
    ];
    $files = $service->files->listFiles($parameters);

    if (count($files->getFiles()) > 0) {
        // Folder found, return its ID
        return $files->getFiles()[0]->getId();
    } else {
        // Folder not found, create it
        $fileMetadata = new Google_Service_Drive_DriveFile([
            'name' => $folderName,
            'mimeType' => 'application/vnd.google-apps.folder'
        ]);
        try {
            $folder = $service->files->create($fileMetadata, ['fields' => 'id']);
            return $folder->id;
        } catch (Exception $e) {
            throw new Exception("Error creating folder: " . $e->getMessage());
        }
    }
}

// Create GMail Service
// --- 5. Initialize Drive Service ---
$service = new Google_Service_Drive($client);


  if ($email_data->login=='Y') {
   	$refreshToken = $email_data->refresh_token;
    $client->refreshToken($refreshToken);
    $client->setAccessToken($client->getAccessToken());

// --- 6. File Upload Logic ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['fileToUpload'])) {
    $file = $_FILES['fileToUpload'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        echo "Error during file upload: " . $file['error'];
    } else {
        $fileName = basename($file['name']);
        $filePath = $file['tmp_name'];
        $fileMimeType = $file['type'];

        try {
            // 1. Find or create the target folder
            $folderId = findOrCreateFolder($service, $_POST['nama']);
            echo "Target Folder ID: " . $folderId . "<br>";

            // 2. Create file metadata with the parent folder ID
            $fileMetadata = new Google_Service_Drive_DriveFile([
                'name' => $fileName,
                'parents' => [$folderId] // Set the parent folder
            ]);

            $content = file_get_contents($filePath);

            // 3. Upload the file
            $uploadedFile = $service->files->create($fileMetadata, [
                'data' => $content,
                'mimeType' => $fileMimeType,
                'uploadType' => 'multipart',
                'fields' => 'id, name, webViewLink' // Request webViewLink in response
            ]);

            // 4. Make the uploaded file publicly accessible
            $permission = new Google_Service_Drive_Permission();
            $permission->setType('anyone');
            $permission->setRole('reader'); // 'reader' role makes it publicly viewable

            // Create the permission
            $service->permissions->create($uploadedFile->getId(), $permission, ['fields' => 'id']);
            echo "File permissions set to public.<br>";

            echo "File uploaded successfully!<br>";
            echo "File ID: " . $uploadedFile->getId() . "<br>";
            echo "File Name: " . $uploadedFile->getName() . "<br>";
            echo "Public View Link: <a href='" . $uploadedFile->getWebViewLink() . "' target='_blank'>" . $uploadedFile->getWebViewLink() . "</a><br>";

        } catch (Google_Service_Exception $e) {
            echo "Google Service Error: " . $e->getMessage() . "<br>";
            echo "Error Code: " . $e->getCode() . "<br>";
            echo "Errors: " . print_r($e->getErrors(), true) . "<br>";
        } catch (Exception $e) {
            echo "An error occurred: " . $e->getMessage() . "<br>";
        }
    }
}


      } else {
        echo "login dulu brow";
      }
