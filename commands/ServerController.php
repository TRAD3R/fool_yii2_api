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
      $_clients = [];
      $server = new EchoServer();
      $server->port = 8585;
      if ($port) {
          $server->port = $port;
      }

      $server->on(WebSocketServer::EVENT_CLIENT_CONNECTED, function($c) use ($_clients){

          echo "New client ({$c->client->resourceId}) connected".PHP_EOL;
          $_clients[$c->client->resourceId] = $c->client;
      });

      $server->on(WebSocketServer::EVENT_CLIENT_DISCONNECTED, function($c) use ($_clients){
          echo "Client ({$c->client->resourceId}) disconnected".PHP_EOL;
          unset($_clients[$c->client->resourceId]);
      });

      $server->on(WebSocketServer::EVENT_CLIENT_MESSAGE, function(WSClientMessageEvent $e) use ($_clients){
          $request = json_decode($e->message);
          var_dump($request);
          $user = User::find()->all();
          $user->id = "12345";
          $result = Yii::$app->runAction($request->request, $request);

          $e->client->send(json_encode(count($_clients)));
      });
      $server->start();
  }
}