<?php
/**
 * Created by PhpStorm.
 * User: TRAD3R
 * Date: 23/10/2018
 * Time: 14:26
 */

require $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
require 'Socket.php';

use app\daemons\Socket;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

$server = IoServer::factory(
  new HttpServer(
    new WsServer(
      new Socket()
    )
  ),
  8585
);

$server->run();