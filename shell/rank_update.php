<?php 
$time_start = microtime(true);
set_time_limit( 3600 );
// $now = new DateTime();
$host = '192.168.100.233';
$dbname = 'aviannea_new';
$user = 'aviannea_ijMVXma';
$pass = 'T4rAflZetaB3U';

// function date_diff($date1, $date2) {
// 	$current = $date1;
// 	$datetime2 = date_create($date2);
// 	$count = 0;
// 	while(date_create($current) < $datetime2){
// 		$current = gmdate("Y-m-d", strtotime("+1 day", strtotime($current)));
// 		$count++;
// 	}
// 	return $count;
// }

$dbh = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
// $stmt = $dbh->query( 'SELECT t1.entity_id, t1.sku, t1.created_at, COUNT(t2.item_id) as sales
// FROM catalog_product_entity as t1 LEFT JOIN sales_flat_order_item as t2 on t1.entity_id = t2.product_id AND t2.created_at > "2013-10-01 00:00:00"
// GROUP BY t1.entity_id' );
// $rows = $stmt->fetchAll(PDO::FETCH_OBJ);

// foreach ($rows as $row) {
// 	#New item points
// // 	$created = new DateTime($row->created_at);
// // 	$interval = date_diff($created->format('Y-m-d'), $now->format('Y-m-d'));
// // 	if ($interval !== false && $interval <= 30) {
// // 		$new_item_points = (9000-(100*$interval));
// // 	} else {
// // 		$new_item_points = 0;
// // 	}
// 	$new_item_points = 0;
// 	#Sale points
// 	$sales_points = ($row->sales * 5000);
// 	#Check if product exists
// 	$st = $dbh->prepare( 'SELECT * FROM catalog_product_rank WHERE entity_id = ?' );
// 	$st->execute(array( $row->entity_id ));
// 	if($st->fetchColumn(0)) {
// 		$sql = 'UPDATE catalog_product_rank SET sale_points = ?, new_item_points = ? WHERE entity_id = ?';
// 		$data = array($sales_points, $new_item_points, $row->entity_id);
// 	} else {
// 		$sql = 'INSERT INTO catalog_product_rank (entity_id, sale_points, new_item_points, manual_points) VALUES (?, ?, ?, ?)';
// 		$data = array($row->entity_id, $sales_points, $new_item_points, 0);
// 	}
// 	$write = $dbh->prepare( $sql );
// 	$write->execute( $data );
// }

#Update magento attributes
$sql = 'DELETE FROM catalog_product_entity_varchar WHERE `attribute_id` = ?';
$delete = $dbh->prepare( $sql );
$delete->execute( array(1015) );

$stmt = $dbh->query( 'SELECT * FROM catalog_product_rank' );
$rows = $stmt->fetchAll(PDO::FETCH_OBJ);
foreach ($rows as $row) {
	$rank = ($row->sale_points + $row->new_item_points + $row->manual_points);
	$sql = 'INSERT INTO catalog_product_entity_varchar (entity_type_id, attribute_id, store_id, entity_id, value) VALUES (?, ?, ?, ?, ?)';
	$write = $dbh->prepare( $sql );
	$write->execute( array(10, 1015, 0, $row->entity_id, $rank) );
}
system('php /var/www/html/store/shell/indexer.php reindexall');

// $time_end = microtime(true);
// $execution_time = ($time_end - $time_start)/60;
//echo '<b>Total Execution Time:</b> '.$execution_time.' Mins';
