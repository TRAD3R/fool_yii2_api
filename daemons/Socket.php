<?php
namespace app\daemons;

require '../vendor/autoload.php';
require '../vendor/yiisoft/yii2/Yii.php';

use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use Yii;
/**
 * Created by PhpStorm.
 * User: TRAD3R
 * Date: 23/10/2018
 * Time: 14:06
 */

class Socket implements MessageComponentInterface {
  protected $clients;

  public function __construct() {
    $this->clients = new \SplObjectStorage;
  }

  public function onOpen(ConnectionInterface $conn) {
    // Store the new connection to send messages to later
    $this->clients->attach($conn);

    echo "New connection! ({$conn->resourceId})\n";
  }

  public function onMessage(ConnectionInterface $from, $requestJSON) {
    $numRecv = count($this->clients) - 1;
    $request = json_decode($requestJSON);

    echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
      , $from->resourceId, $request->request, $numRecv, $numRecv == 1 ? '' : 's');

    foreach ($this->clients as $client) {
      if ($from === $client) {
        // The sender is not the receiver, send to each client connected
        $client->send(json_encode(''));
      }
    }
  }

  public function onClose(ConnectionInterface $conn) {
    // The connection is closed, remove it, as we can no longer send it messages
    $this->clients->detach($conn);

    echo "Connection {$conn->resourceId} has disconnected\n";
  }

  public function onError(ConnectionInterface $conn, \Exception $e) {
    echo "An error has occurred: {$e->getMessage()}\n";

    $conn->close();
  }
}