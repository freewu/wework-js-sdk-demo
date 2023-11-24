<?php
include_once("../src/CorpAPI.class.php");
include_once("../src/ServiceCorpAPI.class.php");
include_once("../src/ServiceProviderAPI.class.php");
// 
$config = require('./config.php');
// 
$api = new CorpAPI($config['CORP_ID'], $config['APP_SECRET']);
 
try {
    $groupList = $api->GroupChatList();
    echo "<br/>groupList\n<br/>";
    var_dump($groupList);
    
} catch (Exception $e) { 
    echo "error: ".$e->getMessage() . "\n";
}
