<?php
error_reporting(E_ALL);

require_once "lib/ip_pool.php";
require_once "lib/ip_runner.php";
require_once "config.php";

$ip_pool = new IPPool();
foreach($pool AS $plugin => $params) {
  require_once "lib/runners/{$plugin}.php";
  $ip_pool->add(new $plugin($params[0], $params[1], @$params[2]));
}

$ip_pool->update();
?>
