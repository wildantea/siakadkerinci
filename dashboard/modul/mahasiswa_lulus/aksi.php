<?php
switch ($_POST['download']) {
	case 'download_feeder':
		include "download_data.php";
		break;
	case 'download_pin':
		include "download_pin.php";
		break;
}
?>
