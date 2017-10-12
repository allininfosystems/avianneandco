<?php
require_once './app/Mage.php';
Varien_Profiler::enable();
Mage::setIsDeveloperMode(true);
ini_set('display_errors', 1);
umask(0);
Mage::app();
$order = Mage::getModel('sales/order')->load(14496);
$obj = Mage::getModel('post/observer');
$obj->_updateFraudStatus(19282360, 14496, $order);