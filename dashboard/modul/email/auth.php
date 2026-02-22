<?php
require_once '/var/www/html/dashboard/lib/mail2/mail/vendor/autoload.php';

$email_dt = $_GET['email'];

$secret_data = $db->fetch_single_row('tb_token','email',$email_dt);

$client_id = $secret_data->client_id;

$client_secret = $secret_data->client_secret;

$redirect_url = $secret_data->redirect_url;

$login = $secret_data->login;

$id_token = $secret_data->id;



// Get API Credentials
$authException = false;
$mime = new Mail_mime();
// Setup Google API Client
$client = new Google_Client();
$client->setClientId($client_id);

$client->setClientSecret($client_secret);
$client->setRedirectUri($redirect_url);
//$client->addScope('https://mail.google.com/');
$client->addScope(Google_Service_Drive::DRIVE_FILE); // Scope for uploading files
$client->setAccessType('offline');
$client->setApprovalPrompt('force');
// Create GMail Service
$service = new Google_Service_Gmail($client);

// Check if we have an authorization code
if(isset($_GET['code'])){
    $code = $_GET['code'];
    $client->authenticate($code);


    $_SESSION['access_token'] = $client->getAccessToken();

    $access_token = $client->getAccessToken();

        $db->update('tb_token',array('refresh_token'=>$client->getRefreshToken(),'access_token' => json_encode($access_token),'login'=>'Y'),'id',$id_token);
    ?>
    <script type="text/javascript">window.location="<?=base_index();?>email"</script>
    <?php
}



$loginUrl = $client->createAuthUrl();


?>
    <div class="row">
        <div class="col-lg-8">
            <a class="btn btn-primary" href="<?php echo $loginUrl; ?>"><i class="glyphicon glyphicon-log-in"></i> Login to <?=$email_dt;?></a>
        </div>
    </div>