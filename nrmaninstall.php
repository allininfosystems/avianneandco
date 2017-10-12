<?php
//New Relic Manual install script 
//Walden Bodtker
//9_11_2014

if (extension_loaded('newrelic')){
  echo "<h1>Congratulations!!</h1><br />\n<h3>You have successfully installed New Relic to your system</h3><br />\n<h3>Please visit <a href='https://newrelic.com'>newrelic.com</a> and you should see your application reporting</h3>";
  echo "If you are still not seeing application data, please open a ticket at <a href='https://support.newrelic.com'>https://support.newrelic.com</a> and attach a copy of your logs from <code>/var/log/newrelic</code>";
} else {
  $PHPZTS = null;
  $ARCH = null;
  $URL = null;
  $environment = php_uname();
  $OS = explode(' ',trim($environment));
  $tar = 'linux';
  if ($OS[0] === 'Darwin') {
    $tar = 'osx';
  } elseif ($OS[0] === 'SunOS') {
    $tar = 'solaris';
  } elseif ($OS[0] === 'FreeBSD') {
    $tar = 'freebsd';
  } elseif ($OS[0] !== 'Linux') {
    echo "Sorry this environment may not be supported. Please read our <a href='https://docs.newrelic.com/docs/agents/php-agent/getting-started/new-relic-php#requirements'>compatibility and requirements</a>."; 
  };

  ob_start();
  phpinfo();
  $phpinfo = array('phpinfo' => array());
  if(preg_match_all('#(?:<h2>(?:<a name=".*?">)?(.*?)(?:</a>)?</h2>)|(?:<tr(?: class=".*?")?><t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>)?)?</tr>)#s', ob_get_clean(), $matches, PREG_SET_ORDER)) {
    foreach($matches as $match) 
      if(strlen($match[1])) {
        $phpinfo[$match[1]] = array();
      } elseif(isset($match[3])) {
        $phpinfo[end(array_keys($phpinfo))][$match[2]] = isset($match[4]) ? array($match[3], $match[4]) : $match[3];
      } else {
        $phpinfo[end(array_keys($phpinfo))][] = $match[2];
      };
  };
  
  $tmp = $phpinfo['phpinfo']['Thread Safety'];
  $inidir = $phpinfo['phpinfo']['Scan this dir for additional .ini files'];
  $MODULEDIR = rtrim(ini_get('extension_dir'), "/");
  $architecture = (PHP_INT_SIZE * 8);
  $PHPAPI = $phpinfo['phpinfo']['PHP Extension'];
  $phpini = $phpinfo['phpinfo']['Loaded Configuration File'];
  $template = './scripts/newrelic.ini.template';

  if ($tmp !== 'disabled'){
    $PHPZTS = '"-zts"';
  };

  if ($architecture !== 64){
    $ARCH = 86;
  } else {
    if ($tar === 'osx'){
      $ARCH = '86_64';
    } else {
      $ARCH = $architecture;
    };
  };
         
  if ($PHPAPI === 20050922 || $tar === 'freebsd'){
    $URL = 'https://download.newrelic.com/php_agent/archive/4.5.5.38/';
  } else {
    $URL = 'https://download.newrelic.com/php_agent/release/';
  };
         
  echo "<h3>Enter the following commands into your terminal as root to manually install the New Relic PHP Agent</h3><br />\nTo become root:   <code>su</code>   or   <code>sudo su</code><br />\n<br />\n";
  echo "<code>wget -r -l1 -nd -A\"$tar.tar.gz\" $URL</code><br />\n";
  echo "<code>gzip -dc newrelic*.tar.gz | tar xf -</code><br />\n";
  echo "<code>cd newrelic-php5*</code><br />\n";
  echo "<code>rm -f $MODULEDIR/newrelic.so</code><br />\n";
  echo "<code>cp ./agent/x$ARCH/newrelic-$PHPAPI$PHPZTS.so $MODULEDIR/newrelic.so</code><br />\n";
  echo "<code>cp ./daemon/newrelic-daemon.x$ARCH /usr/bin/newrelic-daemon</code><br />\n<br />\n";
  echo "<h3>Set your license key and application name. Be sure to replace yourLicenseKey and yourApplicationName with your real license key and the app name you desire.</h3><br />\n";
  echo "<code>sed -i -e 's/\"REPLACE_WITH_REAL_KEY\"/</code><strong style=\"color:red;\">yourLicenseKey</strong><code>/g' $template</code><br />\n";
  echo "<code>sed -i -e 's/PHP Application/</code><strong style=\"color:red;\">yourApplicationName</strong><code>/g' $template</code><br />\n";
      
  if ($inidir !== '(none)'){
    echo "<code>cp $template $inidir/newrelic.ini</code><br />\n<br />\n";
  } else {
    echo "<code>cat $template >> $phpini</code><br />\n<br />\n";
  };

  echo "<code>/usr/bin/newrelic-daemon start</code><br />\n";
  echo "<h3>Restart your webserver and reload this page. If New Relic is loaded you should see a congratulatory message.</h3>";
};