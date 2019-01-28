<?php
/**
 * Created by PhpStorm.
 * User: TRAD3R
 * Date: 31/10/2018
 * Time: 14:05
 */

namespace app\daemons;

use consik\yii2websocket\events\WSClientMessageEvent;
use consik\yii2websocket\WebSocketServer;
use ReflectionClass;

class EchoServer extends WebSocketServer
{
  public function init()
  {
    parent::init();
  }
// return clients
  public function getClients(){
//    $array = (array)$this;
//    $prefix = chr(0).'*'.chr(0);
//    return $array[$prefix.'clients'];
    try {
      $reflection = new ReflectionClass($this);
    } catch (\ReflectionException $e) {
    }
    $property = $reflection->getProperty('clients');
    $property->setAccessible(true);
    return $property->getValue($this);
  }
}