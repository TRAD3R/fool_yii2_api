<?php
/**
 * Created by PhpStorm.
 * User: TRAD3R
 * Date: 31/10/2018
 * Time: 14:32
 */

namespace app\commands;

use app\daemons\EchoServer;
use app\models\User;
use consik\yii2websocket\events\WSClientMessageEvent;
use consik\yii2websocket\WebSocketServer;
use yii\console\Controller;
use Yii;

class ServerController extends Controller
{
  public function actionStart($port = null)
  {
    $server = new EchoServer();
    $server->port = 8585;
    if ($port) {
      $server->port = $port;
    }

    $server->on(WebSocketServer::EVENT_CLIENT_CONNECTED, function($c){
      echo "New client ({$c->client->resourceId}) connected".PHP_EOL;
    });

    $server->on(WebSocketServer::EVENT_CLIENT_DISCONNECTED, function($c){
      echo "Client ({$c->client->resourceId}) disconnected".PHP_EOL;
    });

    $server->on(WebSocketServer::EVENT_CLIENT_MESSAGE, function(WSClientMessageEvent $e){
      $request = json_decode($e->message);
      var_dump($request);
//      $user = User::find()->all();
//      $user->id = "12345";
      //$result = Yii::$app->runAction($request->request, $request);
      var_dump('');
      $e->client->send('1');
    });
    $server->start();
  }
}