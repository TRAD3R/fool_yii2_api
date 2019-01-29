<?php
namespace app\commands;

use app\models\User;
use app\models\Table;
use app\models\Game;
use yii\web\Response;

class TableController extends CommonController
{
  public function actionAdd($request, $clientId){
    $user = User::findByAuthKey($request->authKey);

    if($user){
      $table = new Table();
      if($table->save()){
        $game = new Game();
        $game->table_id = $table->id;
        $game->user_id = $user->id;
        $game->status = 0;
        if($game->save()) {
          $this->status = true;
          $this->data = [
            'table' => $table->id
          ];
        }else{
          $this->data = $game->getErrors();
        } // if $game->save()
      }else{
        $this->error = $table->getErrors();
      } // if $table->save()
    }else{
      $this->data = "User did not find";
    } // if $user


    $response = new Response();
    $response->format = Response::FORMAT_JSON;
    $response->content = [
      'current' => [
        'clientId' => $clientId,
        'status' => $this->status,
        'data' => $this->data,
        'error' => $this->error
      ],
      'other' => [
        'otherClients' => [],
        'status' => $this->status,
        'data' => [
          'operation' => 'table/add'
        ],
      ]
    ];

    return $response;
  } // actionCAdd

  public function actionList($request, $clientId){
    $query = "SELECT table_id AS 'table', COUNT(user_id) AS 'gamers'
              FROM games
              WHERE status = 0
              GROUP BY table_id";
    $tables = Game::findBySql($query)->asArray()->all();

    if($tables){
      $this->status = true;
      $this->data = $tables;
    }

    $response = new Response();
    $response->format = Response::FORMAT_JSON;
    $response->content = [
      'current' => [
        'clientId' => $clientId,
        'status' => $this->status,
        'data' => $this->data,
        'error' => $this->error
      ],
      'other' => [
        'otherClients' => [-1],
        'status' => $this->status,
        'data' => '',
      ]
    ];

    return $response;
  } // actionList
} // TableController