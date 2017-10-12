<?php 
exit;
require '../app/Mage.php';
Mage::app();
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$products = Mage::getResourceModel('catalog/product_collection')->addAttributeToSelect('*')->addAttributeToFilter('entity_id', array('gt' => 30736));
$i = 0;
foreach ($products as $_product) {
	$i++;
	echo exec("/usr/local/php55/bin/php /var/www/html/store/shell/uuupd.php {$_product->getId()}") . "\r\n";
// 	if ($i == 2) {
// 		exit;
// 	}
	
}

?>