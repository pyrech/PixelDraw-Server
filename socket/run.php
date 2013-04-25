<?php

use Ratchet\Wamp\WampServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

require __DIR__. '/conf/system.php';
require PD_SOCKET_ROOT . '/conf/log.php';
require PD_SOCKET_ROOT . '/conf/database.php';
require PD_SOCKET_ROOT . '/vendor/autoload.php';

$pd_server = new PixelDraw\Server();

$dsn = 'mysql:dbname='.PD_DB_NAME.';host='.PD_DB_SERVER;
$user = PD_DB_LOGIN;
$password = PD_DB_PASSWORD;
try {
  $pdo = new \PDO($dsn, $user, $password, array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
  $pd_server->setDatabase($pdo);
}
catch (\Exception $e) {
  $pd_server->log('Can\' connect to database');
}

$pd_server->populate();

$server = IoServer::factory(
    new WsServer(
        new WampServer(
            $pd_server
        )
    ),
    8080
);
$pd_server->log('Ready to run');
$server->run();