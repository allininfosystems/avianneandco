<?php 
file_put_contents("/var/www/html/store/var/fraud.csv", fopen("http://fraudshields.com/avianne/fs/fraudshields.csv", 'r'));
$handle = fopen("/var/www/html/store/var/fraud.csv", "r");
$max_loop_iterations = 500000;
$i=0;
//$dbh = new PDO("mysql:host=192.168.100.233;dbname=aviannea_new", "aviannea_ijMVXma", "T4rAflZetaB3U");
$dbh = new PDO("mysql:host=aviannea-db01.cdgdjpajbtw8.us-east-1.rds.amazonaws.com;dbname=aviannea_new", "aviannea_mage", "T4rAflZetaB3U");
while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
	if ($i++ == $max_loop_iterations) {
		break;
	}
	if ($data[1]=="status") {
		continue;
	}
	$sth = $dbh->prepare("UPDATE `sales_flat_order_grid` SET `fraud_status` = ? WHERE increment_id = ?");
	$sth->execute(array($data[1],$data[0]));
}
fclose($handle);
?>
