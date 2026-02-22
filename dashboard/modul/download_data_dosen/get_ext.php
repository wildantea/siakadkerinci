<?php
session_start();
include "../../inc/config.php";
session_check();
require_once '/var/www/html/dashboard/lib/mail2/mail/vendor/autoload.php';

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
    // Database fetch (as per your provided code)
    $email_data = $db->fetch_single_row('tb_token', 'id', 2);

    // Setup Google API Client
    $client = new Google_Client();
    $client->setClientId($email_data->client_id);
    $client->setClientSecret($email_data->client_secret);
    $client->setRedirectUri($email_data->redirect_url);
    $client->addScope(Google_Service_Drive::DRIVE_METADATA_READONLY); // Use read-only scope for metadata
    $client->setAccessType('offline');

    $refreshToken = $email_data->refresh_token; 
    $client->refreshToken($refreshToken);
    $client->setAccessToken($client->getAccessToken());




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


?>