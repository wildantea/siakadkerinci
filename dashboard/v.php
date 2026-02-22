<a href="https://survei.iainkerinci.ac.id/inc/login_siakad.php?username=1810204012&password=ucing&login_as=mhs">LInk survei</a>
<?php
include "inc/config.php";

exit();
/*$startDate = new DateTime('2025-01-02');
$endDate = new DateTime('2025-02-04');
$endDate->modify('+1 day'); // Include the last date

$interval = new DateInterval('P1D'); // 1-day interval
$period = new DatePeriod($startDate, $interval, $endDate);

foreach ($period as $date) {

dump($res);
}*/


$res = get_report_briva(date('Ymd'),date('Ymd'));

dump($res);
$res = get_report_briva('20250117','20250117');
dump($res);

foreach ($res['data'][151] as $dt) {
		dump($dt);
}

?>