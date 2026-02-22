<?php
$data_token = $db->fetch_custom_single("select * from tb_token where aktif='Y' order by rand() limit 1");

// Get API Credentials
$authException = false;
$mime = new Mail_mime();
// Setup Google API Client
$client = new Google_Client();
$client->setClientId($data_token->client_id);

$client->setClientSecret($data_token->client_secret);
$client->setRedirectUri($data_token->redirect_url);
$client->addScope('https://mail.google.com/');
$client->setAccessType('offline');
$client->setApprovalPrompt('force');
// Create GMail Service
$service = new Google_Service_Gmail($client);

$access_token = $db->convert_obj_to_array(json_decode($data_token->access_token));

$client->setAccessToken($access_token);

//if expired update token to record
if ($client->isAccessTokenExpired()) {
  echo "yes";
  //refresh token
  $client->refreshToken($data_token->refresh_token);
  $newtoken=$client->getAccessToken();
  //get access token
  //update token
 $db->update('tb_token',array('access_token' => json_encode($newtoken)),'id',$data_token->id);

  //set access token
  $client->setAccessToken($newtoken);

}


//for mail google 
function base64url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function base64url_decode($data) {
    return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
}
            $to = 'unyilte4@gmail.com';
            $body = 'Hello brow, hopelly this email is working after a feew hours trying';
            $subject = 'test email brow';

            //email tujuan
            $mime->addTo($to);
            $mime->setTXTBody($body);
            $mime->setHTMLBody($body);
            $mime->setSubject($subject);
            $message_body = $mime->getMessage();

            $encoded_message = base64url_encode($message_body);

            // Gmail Message Body
            $message = new Google_Service_Gmail_Message();
            $message->setRaw($encoded_message);

            // Send the Email
            $email = $service->users_messages->send('me',$message);

            print_r($email);
?>