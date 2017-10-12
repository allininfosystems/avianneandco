<?php
phpinfo();
die();

// LIVE DB creds
$res = mysql_pconnect('192.168.100.233', 'aviannea_ijMVXma', 'T4rAflZetaB3U');
mysql_select_db('aviannea_new');

mysql_query("SET NAMES 'utf8';", $res);
mysql_query("SET CHARACTER SET 'utf8';", $res);
$query = ' SELECT t1.entity_id, IFNULL(t2.special_price,t2.price) AS price, SUBSTRING(TRIM(t3.value),1,2) AS length
FROM catalog_product_entity t1
JOIN catalog_product_flat_1 t2 ON t1.entity_id = t2.entity_id
JOIN catalog_product_entity_varchar t3 ON t1.entity_id = t3.entity_id
WHERE t1.type_id =  "simple"
AND t3.attribute_id = 897
AND t2.name LIKE  "%chain%"
AND ( t2.name LIKE  "%gold%" OR t2.name LIKE  "%platinum%" ) AND t1.entity_id != 2578 AND t1.entity_id != 4024
ORDER BY length';
$res = mysql_query($query);
$products=array();
while ($ret = mysql_fetch_array($res)) {
	$products[$ret['entity_id']]=$ret['length'];
    $query = "UPDATE catalog_product_entity set has_options=1, required_options=1 where entity_id=".$ret['entity_id']." ";
    $res1 = mysql_query($query);
}

foreach ($products as $product=>$length){
    $query = "insert into catalog_product_option (product_id,type,is_require,file_extension,image_size_x,image_size_y,sort_order) values (".$product.",'drop_down',1,'tmp',0,0,0)";
    $res1 = mysql_query($query);
}

$res = mysql_query('select * from catalog_product_option WHERE file_extension = "tmp"');
$sort_orders = array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14);
$sort_orders = array(0,1,2,3,4,5,6,7,8,9);
while ($ret = mysql_fetch_array($res)) {
	
	$_cur_length = (int)$products[$ret["product_id"]];
	$_cur_length = (ceil($_cur_length/2))*2;
	$_iterations_up = (40-$_cur_length)/2;
	$_iterations_down = ((10-$_iterations_up-1)>3)?3:(10-$_iterations_up-1);
	$_total_iterations = (1+$_iterations_up+$_iterations_down);
	$_sort_orders = array_slice($sort_orders, 0, $_total_iterations);
	
	//echo $ret["product_id"]."::".$_cur_length."::".$_iterations_up."::".$_total_iterations."::";
	
	$query = "insert into catalog_product_option_title (option_id,store_id,title) values ($ret[0],0,'Chain length')";
	//echo $query."<br />";
	$res1 = mysql_query($query);
    foreach($_sort_orders as $order){
        $query = "insert into catalog_product_option_type_value (option_id,sort_order) values ($ret[0],$order)";
        //echo $query."<br />";
        $res1 = mysql_query($query);
    }
}      
$length_values = array(16,18,20,22,24,26,28,30,32,34,36,38,40,42,44);
$length_values = array(22,24,26,28,30,32,34,36,38,40);
$res = mysql_query("SELECT option_id, product_id FROM catalog_product_option WHERE product_id IN (".implode(",", array_keys($products)).") AND file_extension = 'tmp'");
while ($ret = mysql_fetch_array($res)) {
	$_res = mysql_query('SELECT t1.entity_id, IFNULL(t1.special_price,t1.price) AS price, SUBSTRING(TRIM(t2.value),1,2) AS length
						FROM catalog_product_flat_1 t1
						JOIN catalog_product_entity_varchar t2 ON t2.entity_id = t1.entity_id
						WHERE t1.type_id =  "simple"
						AND t2.attribute_id = 897
						AND t1.entity_id = '.$ret['product_id'].' LIMIT 1');
	$_ret = mysql_fetch_assoc($_res);
	$price_values = array();
	$cur_price = (double)$_ret["price"];
	$cur_length = (int)$_ret["length"];
	$cur_length = (ceil($cur_length/2))*2;
	$iterations_up = (40-$cur_length)/2;
	$iterations_down = ((10-$iterations_up-1)>3)?3:(10-$iterations_up-1);
	$total_iterations = (10-(1+$iterations_up+$iterations_down));
	$_length_values = array_slice($length_values, $total_iterations);
	
	/*echo $ret['product_id']."::".$cur_price."::".$cur_length."::".$iterations_up."::".$total_iterations;
	var_dump($_length_values);*/
	
	for ($i = 0; $i < $iterations_down; $i++) {
		$price_values[] = number_format(round(($cur_price / $cur_length) * ($cur_length-(($i+1)*2))-$cur_price), 4, '.', '');
	}
	for ($i = 0; $i < $iterations_up; $i++) {
		$price_values[] = number_format(round(($cur_price / $cur_length) * ($cur_length+(($i+1)*2))-$cur_price), 4, '.', '');
	}
	$price_values[] = number_format(0, 4, '.', '');
	sort($price_values);
	//var_dump($price_values);
	
	$ic = 0;
	$__res = mysql_query("SELECT * FROM catalog_product_option_type_value WHERE option_id = '".$ret["option_id"]."'");
	while ($__ret = mysql_fetch_array($__res)) {
		$query = "insert into catalog_product_option_type_price (option_type_id,store_id,price,price_type) values ($__ret[0],0,'$price_values[$ic]','fixed')";
		$res1 = mysql_query($query);
		$query = "insert into catalog_product_option_type_title (option_type_id,store_id,title) values ($__ret[0],0,'$_length_values[$ic]')";
		$res1 = mysql_query($query);
		$ic++;
	}
	unset($price_values);
}

// mysql_query("UPDATE catalog_product_option SET file_extension = NULL WHERE file_extension IS NOT NULL");

echo '<hr>Done<hr>';
?>