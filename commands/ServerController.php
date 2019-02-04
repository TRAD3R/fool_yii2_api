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
    private $forClients = [];
    private $query = '';

    public function actionStart($port = null){
        $_clients = [];
        $server = new EchoServer();
        $server->port = 8585;
        if ($port) {
            $server->port = $port;
        }
        // Connected new client
        $server->on(WebSocketServer::EVENT_CLIENT_CONNECTED, function($c) use ($_clients){

            echo "New client ({$c->client->resourceId}) connected".PHP_EOL;
            $_clients[$c->client->resourceId] = $c->client;
        });

        // Disconnected client
        $server->on(WebSocketServer::EVENT_CLIENT_DISCONNECTED, function($c) use ($_clients){
            echo "Client ({$c->client->resourceId}) disconnected".PHP_EOL;
            unset($_clients[$c->client->resourceId]);
        });

        // get new message
        $server->on(WebSocketServer::EVENT_CLIENT_MESSAGE, function(WSClientMessageEvent $e) use ($_clients){
            $request = json_decode($e->message);

            $result = json_decode($this->runAction("request", [
                $request->request,
                $e->client->resourceId,
                $request->data
            ]));

            if($result->clients){
                foreach ($_clients as $id => $client){
                    if($result->clients == "all" || in_array($id, $result->clients))
                        $client->send($result->query);
                }
            }
        });

        $server->start();
    } // actionStart

    public function actionRequest($request = null, $resourceId = null, $options = null){

        switch ($request){
            case "newConnection":
                $user = User::findByAuthKey($options->authKey);

                if($user){
                    $user->resource_id = $resourceId;
                    $user->save();

                    $this->query = "table";
                }

                break;
        }

        return json_encode(
            $result = [
                "query" => $this->query,
                "clients" => $this->forClients
            ]
        );

    } // actionRequest
}