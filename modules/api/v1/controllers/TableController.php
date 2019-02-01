<?php
/**
 * Created by PhpStorm.
 * User: trad3r
 * Date: 30.01.19
 * Time: 8:01
 */

namespace app\modules\api\v1\controllers;


use app\models\Game;
use app\models\Table;
use app\models\User;
use app\modules\api\controllers\CommonApiController;
use Yii;
use yii\web\Response;

class TableController extends CommonApiController
{
    const MAX_PLAYERS = 6;

    /**
     * Return table list
     * @return array
     */
    public function actionList(){
        Yii::$app->response->format = Response::FORMAT_JSON;

        $tables = Table::findBySql(
            "SELECT tables.id as tableNumber
                        , COUNT(user_id) as playersCount
                        , limit_players as playersLimit
                    FROM tables
                      JOIN games g on tables.id = g.table_id
                    WHERE type = 1
                    GROUP BY table_id"
        )
        ->asArray()
        ->all();

        $this->status = true;
        $this->data = $tables;

        return [
            "status" => $this->status,
            "data"   => $this->data,
            "error"  => $this->error
        ];
    } // actionList

    /**
     * Adding new table
     * @param $authKey string
     * @param $pLayersLimit integer
     * @return array
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionAdd($authKey, $playersLimit){
        Yii::$app->response->format = Response::FORMAT_JSON;

        $user = User::findByAuthKey($authKey);
        if($user){
            $table = new Table();
            $table->limit_players = min($playersLimit, self::MAX_PLAYERS);

            if($table->save()){
                $game = new Game();
                $game->table_id = $table->id;
                $game->user_id = $user->id;

                if($game->save()){
                    $this->status = true;
                }else{
                    $table->delete();
                    $this->error = "Error addition user at the table";
                }

            }else{
                $this->error = 'Error creating the table';
            }

        }else{
            $this->error = "User not found";
        }

        return [
            "status" => $this->status,
            "data"   => $this->data,
            "error"  => $this->error
        ];
    } // actionAdd

    public function actionSitDown($authKey, $tableId){
        Yii::$app->response->format = Response::FORMAT_JSON;

        $user = User::findByAuthKey($authKey);
        if($user){
            $table = Table::findById($tableId);

            if($table){
                $playersCount =Game::getPlayersInGame($table->id);
                if($playersCount < $table->limit_players) {
                    $game = new Game();
                    $game->table_id = $table->id;
                    $game->user_id = $user->id;

                    if($game->save()){
                        $this->status = true;
                    }else{
                        $this->error = "Error addition user at the table";
                    }
                }else{
                    $this->error = "Exceeded player limit at the table";
                }
            }else{
                $this->error = 'Table not found';
            }

        }else{
            $this->error = "User not found";
        }

        return [
            "status" => $this->status,
            "data"   => $this->data,
            "error"  => $this->error
        ];
    } // actionSitDown
} // TableController