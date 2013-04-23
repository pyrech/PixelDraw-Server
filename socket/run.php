<?php

use Ratchet\Wamp\WampServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

require __DIR__ . '/vendor/autoload.php';

$pd_server = new PixelDrawer\Server();

$server = IoServer::factory(
    new WsServer(
        new WampServer(
            $pd_server
        )
    ),
    8080
);
$server->run();