<?php
include "inc/config.php"; // Your database configuration
require_once 'inc/lib/vendor/autoload.php'; // Google API Client Library

// Function to initialize Google Client and Gmail Service
function initializeGmailClient() {
    global $db;

    // Fetch token data from database
    $email_data = $db->fetch_single_row('tb_token', 'id', 1);

    // Setup Google API Client
    $client = new Google_Client();
    $client->setClientId($email_data->client_id);
    $client->setClientSecret($email_data->client_secret);
    $client->setRedirectUri($email_data->redirect_url);
    $client->addScope(Google_Service_Gmail::GMAIL_READONLY); // Read-only scope for Gmail
    $client->setAccessType('offline');

    // Load access token from database
    $access_token = json_decode($email_data->access_token, true);
    $refreshToken = $email_data->refresh_token; 
    $client->refreshToken($refreshToken);
    $client->setAccessToken($client->getAccessToken());


    // Initialize Gmail Service
    return new Google_Service_Gmail($client);
}

// Function to read sent emails
function readSentEmails($service, $userId = 'me', $maxResults = 50) {
    try {
        // List sent messages
        $optParams = [
            'maxResults' => $maxResults,
            'q' => 'label:sent' // Filter for sent emails
        ];
        $messages = $service->users_messages->listUsersMessages($userId, $optParams)->getMessages();

        $emailData = [];
        foreach ($messages as $message) {
            // Get full message details
            $msg = $service->users_messages->get($userId, $message->getId(), ['format' => 'full']);
            $headers = $msg->getPayload()->getHeaders();
            $parts = $msg->getPayload()->getParts();

            // Extract relevant headers
            $emailDetails = [
                'id' => $msg->getId(),
                'snippet' => $msg->getSnippet(),
                'to' => '',
                'subject' => '',
                'date' => '',
                'body' => ''
            ];

            // Get To, Subject, and Date from headers
            foreach ($headers as $header) {
                if ($header->getName() == 'To') {
                    $emailDetails['to'] = $header->getValue();
                }
                if ($header->getName() == 'Subject') {
                    $emailDetails['subject'] = $header->getValue();
                }
                if ($header->getName() == 'Date') {
                    $emailDetails['date'] = $header->getValue();
                }
            }

            // Get email body (plain text or HTML)
            if ($parts) {
                foreach ($parts as $part) {
                    if ($part->getMimeType() == 'text/plain') {
                        $emailDetails['body'] = base64_decode(str_replace(['-', '_'], ['+', '/'], $part->getBody()->getData()));
                    } elseif ($part->getMimeType() == 'text/html') {
                        $emailDetails['body'] = base64_decode(str_replace(['-', '_'], ['+', '/'], $part->getBody()->getData()));
                    }
                }
            } else {
                // If no parts, get body directly from payload
                $emailDetails['body'] = base64_decode(str_replace(['-', '_'], ['+', '/'], $msg->getPayload()->getBody()->getData()));
            }

            $emailData[] = $emailDetails;
        }

        return $emailData;
    } catch (Exception $e) {
        echo "Error reading sent emails: " . $e->getMessage();
        return [];
    }
}

// Main execution
try {
    $service = initializeGmailClient();
    $emails = readSentEmails($service, 'me', 50); // Read up to 10 sent emails

    // Output sent emails
    foreach ($emails as $email) {
        echo "ID: {$email['id']}\n";
        echo "To: {$email['to']}\n";
        echo "Subject: {$email['subject']}\n";
        echo "Date: {$email['date']}\n";
        echo "Snippet: {$email['snippet']}\n";
        echo "Body: {$email['body']}\n";
        echo "------------------------\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>