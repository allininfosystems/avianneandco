<?php 
exit;
require '../app/Mage.php';
Mage::app();
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$product_id = $argv[1];
if (empty($product_id)) {
	exit;
}

$product = Mage::getModel('catalog/product')->load($product_id);
$product->setName($product->getName());
if($product->save()) {
	echo $product->getId() . " Ok!\r\n";
} else {
	echo $product->getId() . " Failed...\r\n";
}
?>