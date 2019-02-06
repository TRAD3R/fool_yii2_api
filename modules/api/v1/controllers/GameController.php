<?php
/**
 * Created by PhpStorm.
 * User: trad3r
 * Date: 06.02.19
 * Time: 7:48
 */

namespace app\modules\api\v1\controllers;


use app\models\Game;
use app\models\User;
use app\modules\api\controllers\CommonApiController;
use Yii;
use yii\web\Response;

class GameController extends CommonApiController
{
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

                if($game->delete()){
                    $this->status = true;
                }else{
                    $this->error = "Error remove user from game";
                } // if-else $game->delete
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