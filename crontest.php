<?php
exit;
require_once './app/Mage.php';
ini_set('display_errors', 1);
umask(0);
Mage::app();// aa

$observer = Mage::getModel('wsm_orderstatus/observer');
$observer->process();
exit;

// exit;
require_once './app/Mage.php';
// Varien_Profiler::enable();
// Mage::setIsDeveloperMode(true);
ini_set('display_errors', 1);
umask(0);
Mage::app();

$obj = Mage::getModel('wsm_orderstatus/observer');
$obj->process();
// $obj = Mage::getModel('post/observer');
// $order = Mage::getModel('sales/order')->load(17474);
// $obj->_updateFraudStatus(30080123, 17474, $order);

// $obj->process();


// function _checkFraudStatus($xRefNum) {
// 	$postdata = array(
// 			"xKey" => "1897864691f644e48face201afadcdf6",
//     			"xVersion" => "4.5.2",
//     			"xSoftwareName" => "Magento",
//     			"xSoftwareVersion" => "1.9.2.1",
//     			"xCommand" => "report:transaction",
// 			"xRefNum" => $xRefNum,
// 	);
// 	$data = buildQuery($postdata);
// 	$orderData = _execRequest("https://x1.cardknox.com/report", $data);
// 	die(var_dump( $orderData ));
// 	$reportData = json_decode($orderData["xReportData"]);

// 	return $reportData;
// }

// function buildQuery($data) {
// 	if(function_exists('http_build_query') && ini_get('arg_separator.output')=='&') return
// 	http_build_query($data);
// 	$tmp = array();
// 	foreach($data as $key=>$val) $tmp[] = rawurlencode($key) . '=' . rawurlencode($val);
// 	return implode('&', $tmp);
// }

// function _parseResponse( $result, $header_size ) {
// 	$res_string = mb_substr( $result, $header_size );
// 	$data = array();
// 	parse_str( $res_string, $data );
	 
// 	return $data;
// }

// function _execRequest($url, $data) {
// 	$ch = curl_init($url);
// 	if(!is_resource($ch))
// 	{
// 		return;
// 	}
// 	curl_setopt($ch,CURLOPT_HEADER, 1);
// 	curl_setopt($ch,CURLOPT_POST, 1);
// 	curl_setopt($ch,CURLOPT_TIMEOUT, 45);
// 	curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
// 	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, 0);
// 	curl_setopt($ch,CURLOPT_POSTFIELDS, $data);
// 	$result = curl_exec($ch);
// 	$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
// 	curl_close($ch);
	 
// 	return _parseResponse($result, $header_size);
// }

// $data = _checkFraudStatus(33845579);
// die(var_dump( $data ));