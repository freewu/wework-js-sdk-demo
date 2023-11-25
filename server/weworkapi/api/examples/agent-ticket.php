<?php
 
include_once("../src/CorpAPI.class.php");
include_once("../src/ServiceCorpAPI.class.php");
include_once("../src/ServiceProviderAPI.class.php");
// 
$config = require('./config.php');
// 
$api = new CorpAPI($config['CORP_ID'], $config['APP_SECRET']);
 
try {
    $agent = $api->AgentConfigTicketGet();
    echo "<br/>Agent config: \n<br/>";
    var_dump($agent);
} catch (Exception $e) { 
    echo $e->getMessage() . "\n";
}