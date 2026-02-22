<?php
session_start();
include "../../inc/config.php";
session_check();
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

function uniqueName($file_name)
    {
        $filename = $file_name;
        $filename = preg_replace("#[^a-z.0-9]#i", "", $filename);
        $ex       = explode(".", $filename); // split filename
        $fileExt  = end($ex); // ekstensi akhir
        $filename = time() . rand() . "." . $fileExt; //rename nama file';
        return $filename;
    }

switch ($_GET["act"]) {
	case "delete":
		$db->delete("sys_users","id",$_GET["id"]);
		break;
	case "up":
	 $user=$db->fetch_single_row('sys_users','id',$_POST['id']);

	 $data = array(
	 );
   
   
                         if(isset($_FILES["foto_user"]["name"])) {
                        if (!preg_match("/.(png|jpg|jpeg|gif|bmp)$/i", $_FILES["foto_user"]["name"]) ) {

							echo "pastikan file yang anda pilih gambar";
							exit();

						} else {

$filename = $_FILES["foto_user"]["name"];
$filename = preg_replace("#[^a-z.0-9]#i", "", $filename); 
$ex = explode(".", $filename); // split filename
$fileExt = end($ex); // ekstensi akhir
 $filename = time().rand().".".$fileExt;//rename nama file';
$db->compressImage($_FILES["foto_user"]["type"],$_FILES["foto_user"]["tmp_name"],"../../../upload/back_profil_foto/",$filename,200);
 if ($user->foto_user!='default_user.png') {
 	unlink("../../../../upload/back_profil_foto/".$user->foto_user);
}
$foto_user = array("foto_user"=>$filename);
$data = array_merge($data,$foto_user);
}

                         }
   

    
		$up = $db->update("sys_users",$data,"id",$_POST["id"]);
		if ($up=true) {
			echo "good";
		} else {
			return false; 
		}
		break;
	case 'up_new':
 	
 	$data = array(
	 );

	


		if($_POST["isi_gambar"]!="")
		{

		//users
		 $user=$db->fetch_single_row('sys_users','id',$_SESSION['id_user']);

		 $gambar = $_POST["isi_gambar"];

		 $image_array_1 = explode(";", $gambar);

		 $image_array_2 = explode(",", $image_array_1[1]);

		 $gambar = base64_decode($image_array_2[1]);

		 //$imageName = time() . '.png';
		 $imageName = uniqueName('gambar.png');

		 file_put_contents("../../../upload/back_profil_foto/".$imageName, $gambar);

			// Get student information (assuming you have this from your session)
            $mhs = $db->fetch_single_row("mahasiswa", "nim", $_SESSION['username']);
            $nama_mhs = str_replace(" ", "-", $mhs->nim . "-" . $mhs->nama);
            $nama_mhs = str_replace("'", "", $nama_mhs);
              $nama_mhs = str_replace("`", "", $nama_mhs);

            if ($email_data->login=='Y') {
            	$refreshToken = $email_data->refresh_token;
	            $client->refreshToken($refreshToken);
	            $client->setAccessToken($client->getAccessToken());
            try {
                // 1. Find or create the "data-mahasiswa" folder (root folder)
                //$dataMahasiswaFolderId = findOrCreateFolder($service, 'data-mahasiswa');

                $dataMahasiswaFolderId = '1ghJJMPi4GcFzRMk-vVsUofkSzQAW3reF';



                // 2. Find or create the student's folder within "data-mahasiswa"
               $studentFolderId = findOrCreateFolder($service, $nama_mhs, $dataMahasiswaFolderId);

               $nama_file = 'foto-profil-'.$nama_mhs.'.'.$extension;


                // --- NEW LOGIC FOR DELETE AND RE-UPLOAD ---
               //get file current 
               $current_file_name = $file_current->file_name;
              
               //check if user first time
               if ($user->is_photo_changed=='Y' && $user->is_photo_drived=='Y') {
               		$existingFileId = findExistingFileByName($service, $current_file_name, $studentFolderId);
               		if ($existingFileId) {
                    	// File exists, delete it first
                    	$service->files->delete($existingFileId);
                	}
               }

                // 3. Create file metadata with the parent folder ID
                $fileMetadata = new Google_Service_Drive_DriveFile([
                    'name' => $nama_file,
                    'parents' => [$studentFolderId] // Set the parent folder to the student's folder
                ]);

                $content = file_get_contents("../../../upload/back_profil_foto/".$imageName);

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

                if ($user->foto_user!='default_user.png' && $user->is_photo_drived=='N') {
                	unlink("../../../upload/back_profil_foto/".$user->foto_user);
                }

                    $data = array(
                        "is_photo_changed" => 'Y',
                        "is_photo_drived" => 'Y',
                        "email" => $_POST["email"],
                        "foto_user" => $link_file,
                        "date_updated_foto" => date('Y-m-d H:i:s')
                    );
                       
                    $in = $db->update("sys_users",$data,'id',$_POST['id']);
                    action_response($db->getErrorMessage());


            } catch (Google_Service_Exception $e) {
                 echo "Google Service Error: " . $e->getMessage();
              /*  echo "Error Code: " . $e->getCode() . "<br>";
                echo "Errors: " . print_r($e->getErrors(), true) . "<br>";*/
            } catch (Exception $e) {
                echo"An error occurred: " . $e->getMessage();
            }

        }

		}
		 action_response('');
		break;
	default:
		# code...
		break;
}

?>