<?php
if (isset($_GET['file'])) {
    $tempdir = 'temp/'; // Make sure this matches your actual path
    $file = $tempdir . basename($_GET['file']);
    
    if (file_exists($file)) {
        unlink($file);
        echo "QR code deleted.";
    } else {
        echo "File not found.";
    }
}
?>