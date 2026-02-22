<?php
session_start();
include "../../inc/config.php";
session_check();
echo "<pre>";
print_r($_POST);

?>