<?php 
die();
$res = mysql_pconnect('localhost', 'aviannea_ijMVXma', 'T4rAflZetaB3U');
mysql_select_db('aviannea_update');
mysql_query("SET NAMES 'utf8';", $res);
mysql_query("SET CHARACTER SET 'utf8';", $res);
$query = " SELECT `option_id` FROM `catalog_product_option_title` WHERE `title` LIKE 'Chain length' GROUP BY `option_id` ";
$res = mysql_query($query);
while ($ret = mysql_fetch_array($res)) {
	$_query = " SELECT `option_type_id` FROM `catalog_product_option_type_value` WHERE `option_id` = ".$ret["option_id"];
	$_res = mysql_query($_query);
	while ($_ret = mysql_fetch_array($_res)) {
		if(!mysql_query(" DELETE FROM `catalog_product_option_type_title` WHERE `option_type_id` = ".$_ret["option_type_id"])) {
			echo "Failed to operate with `catalog_product_option_type_title`";
		}
		if(!mysql_query(" DELETE FROM `catalog_product_option_type_price` WHERE `option_type_id` = ".$_ret["option_type_id"])) {
			echo "Failed to operate with `catalog_product_option_type_price`";
		}
	}
	if(!mysql_query(" DELETE FROM `catalog_product_option_type_value` WHERE `option_id` = ".$ret["option_id"])) {
		echo "Failed to operate with `catalog_product_option_type_value`";
	}
	if(!mysql_query(" DELETE FROM `catalog_product_option_price` WHERE `option_id` = ".$ret["option_id"])) {
		echo "Failed to operate with `catalog_product_option_price`";
	}
	if(!mysql_query(" DELETE FROM `catalog_product_option_title` WHERE `option_id` = ".$ret["option_id"])) {
		echo "Failed to operate with `catalog_product_option_title`";
	}
	if(!mysql_query(" DELETE FROM `catalog_product_option` WHERE `option_id` = ".$ret["option_id"])) {
		echo "Failed to operate with `catalog_product_option`";
	}
}