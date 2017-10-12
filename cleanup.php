<?php
$o = getopt("c:x:");

switch (@$o['c']) {
    case 'l': 
	$clean = 'log'; break;
    case 'v': 
	$clean = 'var'; break;
    default:
	die('No option');
}

$xml = simplexml_load_file('/var/www/html/store/app/etc/local.xml', NULL, LIBXML_NOCDATA) or die('Unable to load connection settings');

$db['host'] = $xml->global->resources->default_setup->connection->host;
$db['name'] = $xml->global->resources->default_setup->connection->dbname;
$db['user'] = $xml->global->resources->default_setup->connection->username;
$db['pass'] = $xml->global->resources->default_setup->connection->password;
$db['pref'] = $xml->global->resources->db->table_prefix;

echo'Got command to clean: ' . $clean . "\n";
 
if($clean == 'log') clean_log_tables();
if($clean == 'var') clean_var_directory();
 
function clean_log_tables() {
    global $db;
   
    $tables = array(
        'dataflow_batch_export',
        'dataflow_batch_import',
        'log_customer',
        'log_quote',
        'log_summary',
        'log_summary_type',
        'log_url',
        'log_url_info',
        'log_visitor',
        'log_visitor_info',
        'log_visitor_online',
        'report_event'
    );
   
    mysql_connect($db['host'], $db['user'], $db['pass']) or die(mysql_error());
    mysql_select_db($db['name']) or die(mysql_error());
   
    foreach($tables as $v => $k) {
	$query = 'TRUNCATE `'.$db['pref'].$k.'`';
	echo $query . ' :: ';
        mysql_query($query) or die(mysql_error());
	echo mysql_error() ? mysql_error() : 'OK' . "\n";
    }
}
 
function clean_var_directory() {
    $dirs = array(
        'downloader/pearlib/cache/*',
        'downloader/pearlib/download/*',
//        'var/cache/*',
        'var/log/*',
        'var/report/*',
//        'var/session/*',
        'var/tmp/*'
    );
   
    foreach($dirs as $v => $k) {
        exec('rm -rf '.$k);
    }
}
?>