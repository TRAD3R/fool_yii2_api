<?php
/**
 * Created by PhpStorm.
 * User: TRAD3R
 * Date: 31/10/2018
 * Time: 19:50
 */

namespace app\commands;

use app\daemons\EchoServer;
use consik\yii2websocket\events\WSClientErrorEvent;
use consik\yii2websocket\events\WSClientMessageEvent;
use consik\yii2websocket\WebSocketServer;
use yii\console\Controller;
use yii\web\Response;

class SocketController extends Controller
{
  public function actionBegin(){
    $server = new EchoServer();
    $server->port = 8585;

    // when get request from client
    $server->on(WebSocketServer::EVENT_CLIENT_MESSAGE, function(WSClientMessageEvent $e) use($server){
      $request = json_decode($e->message);

//      if(empty($e->client->name) && !empty($request->authKey)){
//        $e->client->name = $request->authKey;
//      }
      echo "{$e->client->resourceId}: $request->request";
      // result of request
      $result = \Yii::$app->runAction($request->request, [$request, $e->client->resourceId]);

      // send response to clients
      foreach ($server->clients as $client){
        // if client is request client
        if($client->resourceId == $result->content['current']['clientId']){
          $client->send(json_encode($result->content['current']));
        }else{
          // if has corresponding clients
          if(!empty($result->content['other']['otherClients'])){
//            if(in_array($client->name, $result->content['other']['otherClients']))
              $client->send(json_encode($result->content['other']));
          }else{
            $client->send(json_encode($result->content['other']));
          } // if !empty($result->content['other']['otherClients'])
        } // if $client->resourceId == $result->content['clientId']
      } // foreach $server->clients
    }); // on

    $server->on(WebSocketServer::EVENT_CLIENT_ERROR, function (WSClientErrorEvent $error){
      echo $error->client->resourceId ." had error: ". $error->exception->getMessage().PHP_EOL;
    });

    $server->start();
  } // actionBegin
}