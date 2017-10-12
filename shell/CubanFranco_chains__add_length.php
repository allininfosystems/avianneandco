<?php
if (!@$_GET['go']) exit('no go');

$res = mysql_pconnect('192.168.100.233', 'aviannea_ijMVXma', 'T4rAflZetaB3U');
if (strpos(__FILE__, 'dev.avianneandco.com')) {
    mysql_select_db('aviannea_design');
} else {
    mysql_select_db('aviannea_new');
}
mysql_query("SET NAMES 'utf8';", $res);
mysql_query("SET CHARACTER SET 'utf8';", $res);

$query = 'SELECT `entity_id` FROM `catalog_product_entity_varchar` WHERE attribute_id = 96 AND (value LIKE "%Franco%" OR value LIKE "%Cuban%") AND value LIKE "%chain%"';// AND entity_id=12144';
$res = mysql_query($query);
$products=array();
while ($ret = mysql_fetch_array($res)) {
    $products[]=$ret['entity_id'];
    $query = "UPDATE catalog_product_entity set has_options=1 where entity_id=".$ret['entity_id']." ";
    $res1 = mysql_query($query);
}

foreach ($products as $product){
    /* Remove previous attempts */
    $ss = mysql_query("	SELECT t1.option_id 
			    FROM catalog_product_option AS t1 
			INNER JOIN catalog_product_option_title AS t2 USING ( option_id ) 
			WHERE t2.title = 'Chain length' AND t1.product_id =".$product);
    
    while ($rr = mysql_fetch_array($ss)) {
	mysql_query("DELETE FROM catalog_product_option_title WHERE option_id = ".$rr['option_id']);
	mysql_query("DELETE FROM catalog_product_option_price WHERE option_id = ".$rr['option_id']);
	mysql_query("DELETE FROM catalog_product_option WHERE option_id = ".$rr['option_id']);
    }
    $query = "insert into catalog_product_option (product_id,type,is_require,file_extension,image_size_x,image_size_y,sort_order) values (".$product.",'drop_down',1,'tmp',0,0,0)";
    $res1 = mysql_query($query);
}

$res = mysql_query('select * from catalog_product_option WHERE file_extension = "tmp"');
while ($ret = mysql_fetch_array($res)) {
    $query = "insert into catalog_product_option_title (option_id,store_id,title) values ($ret[0], 0, 'Chain length')";
    $res1 = mysql_query($query);
    $query = "insert into catalog_product_option_price (option_id,store_id,price,price_type) values ($ret[0], 0, 0.0000, 'fixed')";
    $res1 = mysql_query($query);
}

$sizes = array("22", "24", "26", "28", "30", "32", "34", "36", "38", "40", "42", "44",);
$res = mysql_query("SELECT option_id, product_id FROM catalog_product_option WHERE product_id IN (".implode(",", $products).") AND file_extension = 'tmp'");
while ($ret = mysql_fetch_array($res)) {
    $ic = 0;
//    $__res = mysql_query("SELECT * FROM catalog_product_option WHERE option_id = '".$ret["option_id"]."'");
    $__res = mysql_query("SELECT o.*, d.value AS price, v.value AS len 
			  FROM catalog_product_option o 
			  LEFT JOIN catalog_product_entity_decimal d ON (o.product_id = d.entity_id AND d.attribute_id=567) 
			  LEFT JOIN catalog_product_entity_varchar v ON (o.product_id = v.entity_id AND v.attribute_id=897) 
			  WHERE o.option_id = " . $ret["option_id"]);
    while ($__ret = mysql_fetch_array($__res)) {
	foreach ($sizes AS $k=>$v) {
    	    $query = "insert into catalog_product_option_type_value (option_id,sku,sort_order) values (".$__ret['option_id'].", '', $k)";
    	    $res1 = mysql_query($query);
	    $otid = mysql_insert_id();

	    if (!$__ret['len']) {
	        $r = mysql_query("SELECT o.*, d.value AS price, v.value AS len 
				    FROM catalog_product_option o 
				    LEFT JOIN catalog_product_entity_decimal d ON (o.product_id = d.entity_id AND d.attribute_id=567) 
				    LEFT JOIN catalog_product_entity_varchar v ON (o.product_id = v.entity_id AND v.attribute_id=888) 
				    WHERE o.option_id = " . $ret["option_id"]);
		$l = mysql_fetch_assoc($r);
		$__ret['len'] = $l['len'];
	    }
	    if (!$__ret['len']) {
		$__ret['len'] = 28;
	    }
//echo $__ret['len'].' =&gt; '.(int)$__ret['len'].' =&gt; ';
	    $__ret['len'] = (int)$__ret['len'];
	    if (!in_array($__ret['len'], $sizes)) {
		$t = $sizes[0];
		foreach ($sizes as $s) {
		    if ($s <= $__ret['len']) {
			$t = $s;
		    }
//echo'<i>'.$__ret['len'].'vs'.$s.'('.$t.')</i> ';
		}
		$__ret['len'] = $t;
	    }
//echo $__ret['len'].'<br>';

	    $s_price = round(($__ret['price'] / $__ret['len']) * $v);//, 2);
	    $s_diff = $s_price - $__ret['price'];

	    $query = "insert into catalog_product_option_type_price (option_type_id,store_id,price,price_type) values ($otid, 0, $s_diff, 'fixed')";
	    $res1 = mysql_query($query);

	    $query = "insert into catalog_product_option_type_title (option_type_id,store_id,title) values ($otid, 0, '$v')";
	    $res1 = mysql_query($query);
	    $ic++;
	}
    }
    mysql_query("UPDATE catalog_product_option SET file_extension = NULL WHERE option_id=".$ret['option_id']);
}

mysql_close();
?>