<?php
// set your timezone
date_default_timezone_set('America/New_York');

// pull in the packages managed by Composer
require_once("vendor/autoload.php");

$log = new \Monolog\Logger('PHRETSDemo');
$log->pushHandler(
	new \Monolog\Handler\StreamHandler('php://stdout', \Monolog\Logger::DEBUG)
);

// setup your configuration		
$config = new \PHRETS\Configuration;
$config->setLoginUrl('http://neren.rets.paragonrels.com/rets/fnisrets.aspx/NEREN/login?rets-version=rets/1.7.2');
$config->setUsername('619764idx');
$config->setPassword('l8Ml4YeAv3qFFamm');
$config->setRetsVersion('1.5');
//Value shown below are the defaults used when not overridden
$config->setOption('use_post_method', true);
$config->setOption('disable_follow_location', true);
//$config->setUserAgent('PHRETS/2.0');
//$config->setUserAgentPassword($rets_user_agent_password); // string password, if given
$config->setHttpAuthenticationMethod('digest'); // or 'basic' if required 

// get a session ready using the configuration
$rets = new \PHRETS\Session($config); //$session = new \PHRETS\Session($config);
//$rets->setLogger($log);

$rets_connect = $rets->Login();




?>
