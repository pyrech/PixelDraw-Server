<?php

use Ratchet\Wamp\WampServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

require __DIR__ . '/vendor/autoload.php';

require __DIR__ . '/conf/database.php';

$pd_server = new PixelDraw\Server();

$dsn = 'mysql:dbname='.PD_DB_NAME.';host='.PD_DB_SERVER;
$user = PD_DB_LOGIN;
$password = PD_DB_PASSWORD;
try {
  $pdo = new \PDO($dsn, $user, $password, array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
  $pd_server->setDatabase($dbo);
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
$server->run();