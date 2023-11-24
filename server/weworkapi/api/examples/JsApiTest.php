<?php /*
 * Copyright (C) 2017 All rights reserved.
 *   
 * @File JsApiTest.php
 * @Brief 
 * @Author abelzhu, abelzhu@tencent.com
 * @Version 1.0
 * @Date 2017-12-26
 *
 */
 
include_once("../src/CorpAPI.class.php");
include_once("../src/ServiceCorpAPI.class.php");
include_once("../src/ServiceProviderAPI.class.php");
// 
$config = require('./config.php');
// 
$api = new CorpAPI($config['CORP_ID'], $config['APP_SECRET']);
 
try {
    //
    $ticket = $api->TicketGet();
    echo  "<br/>TicketGet: ".$ticket . "\n<br/>";

    //
    $ticket = $api->JsApiTicketGet();
    echo "<br/>JsApiTicket: " . $ticket . "\n<br/>";
    
    // //
    // $agent = $api->AgentConfigTicketGet();
    // echo "<br/>Agent config: \n<br/>";
    //var_dump($agent);
} catch (Exception $e) { 
    echo $e->getMessage() . "\n";
}

