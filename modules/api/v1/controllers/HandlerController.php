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
            case "tableCreate":
                return $this->run("table/create", ["authKey" => $post["authKey"], "playersLimit" => max(0, (int)$post["playersLimit"])]);
                break;

            default:
                return [
                    $this->status = false,
                    $this->error = "Wrong request",
                ];
        }
    } // actionRequest
} // HandlerController