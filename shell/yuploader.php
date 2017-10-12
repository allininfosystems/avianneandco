<?php 
session_start();
set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__) . "/library");
ini_set("display_errors","On");
error_reporting(E_ALL);
$dir = dirname(dirname(__FILE__)) . "/media/video/";
require_once 'Zend/Loader.php'; // the Zend dir must be in your include_path
Zend_Loader::loadClass('Zend_Gdata_YouTube');
Zend_Loader::loadClass('Zend_Gdata_ClientLogin');

//yt account info
$yt_user = 'avianneandco@gmail.com'; //youtube username or gmail account
$yt_pw = '832803280'; //account password
$yt_source = 'aviannea'; //name of application (can be anything)

if( is_dir($dir) ) {
	if ( $handle = opendir( $dir ) ) {
		$host = 'aviannea-db01.cdgdjpajbtw8.us-east-1.rds.amazonaws.com';
		$dbname = 'aviannea_new';
		$user = 'aviannea_mage';
		$pass = 'T4rAflZetaB3U';
		$dbh = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
		while ( false !== ($entry = readdir($handle)) ) {
			$file = $dir . $entry;
			if ( is_file( $file ) && ( pathinfo( $file, PATHINFO_EXTENSION )=="mov" ) ) {
				$sku = pathinfo($file, PATHINFO_FILENAME);
				$query = "SELECT t1.entity_id, t2.value as name, t3.value as short_description, t4.value as url_path
						FROM `catalog_product_entity` AS t1
						LEFT JOIN `catalog_product_entity_varchar` AS t2 ON t1.entity_id = t2.entity_id AND t2.attribute_id = 96 AND t2.store_id = 0
						LEFT JOIN `catalog_product_entity_text` AS t3 ON t1.entity_id = t3.entity_id AND t3.attribute_id = 506 AND t3.store_id = 0
						LEFT JOIN `catalog_product_entity_varchar` AS t4 ON t1.entity_id = t4.entity_id AND t4.attribute_id = 570 AND t4.store_id = 0
						WHERE t1.sku = ? LIMIT 1";
				$stst = $dbh->prepare($query);
				$stst->execute(array($sku));
				$stst->setFetchMode(PDO::FETCH_OBJ);
				$row = $stst->fetch();
				if( !empty($row->name) && !empty($row->url_path) ) {
					$name = $row->name;
					$url = "http://www.avianneandco.com/".$row->url_path;
					$desc = $row->short_description . "\r\n{$url}";
					// Upload video into Youtube code
					//video path
					$video_url = $file;
					
					//yt dev key
					$yt_api_key = 'AI39si6rBkOEZ49v1iRS9cG4IGzfjemcYz1h6yAmgyBUJU7Kov7beIxhY0Zu2OeBSJ63yVj-tUb8UpHrCJqXiLV30RpADWEghg'; //your youtube developer key
					
					//login in to YT
					$authenticationURL= 'https://www.google.com/youtube/accounts/ClientLogin';
					$httpClient = Zend_Gdata_ClientLogin::getHttpClient(
							$username = $yt_user,
							$password = $yt_pw,
							$service = 'youtube',
							$client = null,
							$source = $yt_source, // a short string identifying your application
							$loginToken = null,
							$loginCaptcha = null,
							$authenticationURL);
					
					$yt = new Zend_Gdata_YouTube($httpClient, $yt_source, NULL, $yt_api_key);
					
					$myVideoEntry = new Zend_Gdata_YouTube_VideoEntry();
					 
					$filesource = $yt->newMediaFileSource($video_url);
					$filesource->setContentType('video/quicktime'); //make sure to set the proper content type.
					$filesource->setSlug($sku);
					 
					$myVideoEntry->setMediaSource($filesource);
					 
					$myVideoEntry->setVideoTitle($name);
					$myVideoEntry->setVideoDescription($desc);
					// Note that category must be a valid YouTube category !
					$myVideoEntry->setVideoCategory('Entertainment');
					 
					// Set keywords, note that this must be a comma separated string
					// and that each keyword cannot contain whitespace
					$myVideoEntry->SetVideoTags('jewelry');
					 
					// Upload URI for the currently authenticated user
					
					$uploadUrl = "http://uploads.gdata.youtube.com/feeds/users/default/uploads";
					 
					// Try to upload the video, catching a Zend_Gdata_App_HttpException
					// if availableor just a regular Zend_Gdata_App_Exception
					 
					try {
						$newEntry = $yt->insertEntry($myVideoEntry,
								$uploadUrl,
								'Zend_Gdata_YouTube_VideoEntry');
					} catch (Zend_Gdata_App_HttpException $httpException) {
						echo $httpException->getRawResponseBody();
					} catch (Zend_Gdata_App_Exception $e) {
						echo $e->getMessage();
					}
					$video_id = $newEntry->getVideoId();
					if($video_id) {
						$youcode = '<iframe width="277" height="225" src="http://www.youtube.com/embed/' . $video_id . '" frameborder="0" allowfullscreen></iframe>';
						$stct = $dbh->prepare("SELECT `value_id` FROM `catalog_product_entity_text` WHERE `attribute_id` = 97 AND `entity_id` = ?");
						$stct->execute(array($row->entity_id));
						$stct->setFetchMode(PDO::FETCH_OBJ);
						$entity = $stct->fetch();
						if( isset($entity->value_id) ) {
							//echo "UPDATE `catalog_product_entity_text` SET `value` = {$youcode} WHERE `attribute_id` = 97 AND `entity_id` = {$row->entity_id}";
							$stmt = $dbh->prepare("UPDATE `catalog_product_entity_text` SET `value` = ? WHERE `attribute_id` = 97 AND `entity_id` = ?");
							if($stmt->execute(array($youcode,$row->entity_id))) {
								unlink($file);
							}
						}
					}
				}
			}
		}
		closedir($handle);
	}
}
