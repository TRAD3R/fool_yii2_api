<?php
/**
 * Created by PhpStorm.
 * User: trad3r
 * Date: 06.02.19
 * Time: 7:48
 */

namespace app\modules\api\v1\controllers;


use app\models\Game;
use app\models\Table;
use app\models\User;
use app\modules\api\controllers\CommonApiController;
use Yii;
use yii\web\Response;

class GameController extends CommonApiController
{
    /**
     * Enter at the game
     * @param $authKey
     * @param $tableId
     * @return array
     */
    public function actionEnter($authKey, $tableId){
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
                        // If count users in game = player limit the game start
                        $usersInGame = Game::findBySql(
                            "SELECT g.*
                        FROM games g
                          JOIN tables t on g.table_id = t.id
                        WHERE t.id = $table->id"
                        )->count();

                        if($usersInGame >= $table->limit_players){
                            $table->type = 2;
                            if($table->save()){
                                $this->status = true;
                                $this->data = "gameStart";
                            }else{
                                $this->error = "Error start game";
                            }
                        }else{
                            $this->status = true;
                        }
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
    } // actionEnter

    /**
     * User exit from game
     * @param $authKey
     * @return array
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionExit($authKey){
        Yii::$app->response->format = Response::FORMAT_JSON;

        if($user = User::findByAuthKey($authKey)){
            if($game = Game::findBySql("
                        SELECT games.* 
                        FROM games 
                          JOIN tables t on games.table_id = t.id 
                        WHERE user_id = $user->id AND t.type > 0
                      ")
                ->one()){
                // If count users in game = player limit the game start
                $usersInGame = Game::find()
                                    ->where(["table_id" => $game->table_id])
                                    ->count();

                if($usersInGame == 1){
                    $table = Table::findById($game->table_id);

                    // start transaction for delete game and table
                    $transaction = Table::getDb()->beginTransaction();
                    try {
                        $game->delete();
                        $table->delete();

                        $transaction->commit();
                        $this->status = true;
                    } catch(\Exception $e) {
                        $transaction->rollBack();
                        $this->error = "Error remove game or table";
                        throw $e;
                    } catch(\Throwable $e) {
                        $transaction->rollBack();
                        $this->error = "Error remove game or table";
                    }
                }else{
                    if($game->delete()) {
                        $this->status = true;
                    }else{
                        $this->error = "You can't leave the game";
                    }
                }
            }else{
                $this->error = "User not in game";
            } // if-else $game
        }else{
            $this->error = "User not found";
        } // if-else $user

        return [
            "status" => $this->status,
            "data"   => $this->data,
            "error"  => $this->error
        ];
    } // actionExit
} // GameController