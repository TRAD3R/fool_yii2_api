<?php
/**
 * Created by PhpStorm.
 * User: trad3r
 * Date: 28.01.19
 * Time: 19:59
 */
namespace app\modules\api\v1\controllers;

use app\modules\api\controllers\CommonApiController;
use Yii;
use yii\web\Response;

class HandlerController extends CommonApiController
{
    public function actionRequest(){
        Yii::$app->response->format = Response::FORMAT_JSON;

        $post = Yii::$app->request->post();

        switch ($post['operation']){
            case "authentification":
                if($post['password']){
                    return $this->run("user/signup", ["email" => $post['email'], "password" => $post['password']]);
                }else{
                    return $this->run("user/signin", ["authKey" => $post['email']]);
                }
                break;
            case "tableList":
                return $this->run("table/list", ["authKey" => $post["authKey"]]);
                break;
            case "tableAdd":
                return $this->run("table/add", ["authKey" => $post["authKey"], "playerLimit" => max(2, (int)$post["playerLimit"])]);
                break;
            case "gameEnter":
                return $this->run("game/enter", ["authKey" => $post["authKey"], "tableId" => (int)$post["tableId"]]);
                break;
            case "gameExit":
                return $this->run("game/exit", ["authKey" => $post["authKey"]]);
                break;

            default:
                return [
                    $this->status = false,
                    $this->error = "Wrong request",
                ];
        }
    } // actionRequest
} // HandlerController