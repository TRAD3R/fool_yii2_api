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
    private static $_clients = [];

    /**
     * @param null $port
     */
    public function actionStart($port = null){
//        $_clients = [];
        $server = new EchoServer();
        $server->port = 8585;
        if ($port) {
            $server->port = $port;
        }
        // Connected new client
        $server->on(WebSocketServer::EVENT_CLIENT_CONNECTED, function($c){
            echo "New client ({$c->client->resourceId}) connected".PHP_EOL;
            self::$_clients[$c->client->resourceId] = $c->client;
        });

        // Disconnected client
        $server->on(WebSocketServer::EVENT_CLIENT_DISCONNECTED, function($c){
            echo "Client ({$c->client->resourceId}) disconnected".PHP_EOL;
            $this->runAction("request", [
                "closeConnection",
                $c->client->resourceId
            ]);
            unset(self::$_clients[$c->client->resourceId]);
        });

        // get new message
        $server->on(WebSocketServer::EVENT_CLIENT_MESSAGE, function(WSClientMessageEvent $e){
            $request = json_decode($e->message);
            $data = $request->data ?? "2";

            $result = json_decode($this->runAction("request", [
                $request->request,
                $e->client->resourceId,
                $data
            ]));

            // send all clients new query
            if($result->clients){
                foreach (self::$_clients as $id => $client){
                    if ($result->clients == "all" || in_array($id, $result->clients))
                        $client->send($result->query);
                }
            }
        });

        $server->start();
    } // actionStart

    public function actionRequest($request = null, $resourceId = null, $options = null){

        switch ($request){
            // save user resource_id
            case "newConnection":
                $user = User::findByAuthKey($options->authKey);

                if($user){
                    $user->resource_id = $resourceId;
                    $user->save();
                    echo "{$user->resource_id} => NewConnection";
                }

                break;
            // remove user resource_id
            case "closeConnection":
                $user = User::findByResource($resourceId);

                if($user){
                    $user->resource_id = null;
                    $user->save();
                }

                break;
            // update tableActivity
            case "table":
                echo $resourceId . "=> table";
                $this->forClients = "all";
                $this->query = "table";
                break;

            // game has started
            case "gameStart":
                echo "{$resourceId}=>gameStart";
                break;
        }

        return json_encode(
            $result = [
                "query" => $this->query,
                "clients" => $this->forClients
            ]
        );

    } // actionRequest
} // ServerController