<?php
/**
 * Created by PhpStorm.
 * User: trad3r
 * Date: 30.01.19
 * Time: 8:01
 */

namespace app\modules\api\v1\controllers;


use app\models\Table;
use app\modules\api\controllers\CommonApiController;
use Yii;
use yii\web\Response;

class TableController extends CommonApiController
{
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
}