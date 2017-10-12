<?php
exit;

require_once './app/Mage.php';
// Varien_Profiler::enable();
// Mage::setIsDeveloperMode(true);
ini_set('display_errors', 1);
set_time_limit(0);
ini_set("memory_limit","2048M");
umask(0);
Mage::app();

header('X-Accel-Buffering: no');
ini_set('output_buffering', 'off');
ini_set('zlib.output_compression', false);
while (@ob_end_flush());
ini_set('implicit_flush', true);
ob_implicit_flush(true);
$start = microtime(true);

echo "Start...\n";
ob_end_flush();

$resource = Mage::getSingleton('core/resource');
$readConnection = $resource->getConnection('core_read');
// $writeConnection = $resource->getConnection('core_write');
// $query = '
// 		SELECT `entity_id` AS `product_id` 
// 		FROM `catalog_product_entity` AS `p` 
// 		INNER JOIN `catalog_product_entity_int` AS `a` USING(`entity_id`) 
// 		WHERE `a`.`attribute_id` = 273 AND `a`.`value` = 1 AND DATE(`p`.`updated_at`) <= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
// 		GROUP BY `p`.`entity_id` 
// 		ORDER BY `p`.`updated_at` 
// 		LIMIT 1000
// ';
// $query = '
// 		SELECT `entity_id` AS `product_id` 
// 		FROM `catalog_product_entity` AS `p` 
// 		WHERE `entity_id` NOT IN ( SELECT `entity_id` FROM `log_clean_options` )
// 		GROUP BY `p`.`entity_id` 
// 		ORDER BY `p`.`updated_at` 
// ';
$query = '
		SELECT *, COUNT(*) as `num` FROM `catalog_product_option_title` INNER JOIN `catalog_product_option` USING (`option_id`) GROUP BY `title`, `store_id`, `product_id` HAVING `num` > 1
';
$results = $readConnection->fetchAll($query);

foreach ($results as $_option) {
	$product_id = $_option['product_id'];
	echo "Entity ID: {$product_id}. " .ceil(microtime(true) - $start). "s \n";
	ob_end_flush();
	
	$product = Mage::getModel('catalog/product')->load($product_id);
// 	$price = $product->getFinalPrice();
	
	$oldOptions = $product->getOptions();
	
	foreach ($oldOptions as $option){
		$option->delete();
	}
	
	$product->setHasOptions(0);
	$product->setCanSaveCustomOptions(true);
	$product->save();
	
	unset($product);
	
	$_prod = Mage::getModel('catalog/product')->load($product_id);
	$_prod->setCanSaveCustomOptions(true);
	$_prod->save();
	
	unset($_prod);
	unset($oldOptions);
	
// 	$query = "REPLACE INTO `log_clean_options` (`entity_id`,`price`) VALUES ({$product_id}, '{$price}')";
// 	$writeConnection->query($query);
}
echo "Finish...\n";
ob_end_flush();
