<?php
/**
 * Created by PhpStorm.
 * User: trad3r
 * Date: 28.01.19
 * Time: 19:42
 */
namespace app\modules\api\v1\controllers;

use app\models\Game;
use app\models\User;
use app\models\UserAddForm;
use app\modules\api\controllers\CommonApiController;
use Yii;
use yii\web\Response;

class UserController extends CommonApiController
{
    /**
     * @param $email string
     * @param $password string
     * @return array
     * @throws \yii\base\Exception
     */
    public function actionSignup($email, $password){
        Yii::$app->response->format = Response::FORMAT_JSON;

        $userForm = new UserAddForm();
        $userForm->email = $email;
        $userForm->password = $password;

        if($userForm->validate()){
            $user = User::findByEmail($userForm->email);
            if($user){
                if($user->validatePassword($userForm->password)){
                    $this->status = true;
                    $inGame = Game::findBySql("SELECT t.id FROM games g JOIN `tables` t ON t.id = g.table_id WHERE t.type > 0 AND user_id = $user->id")->one();
                    $table_id = $inGame ? (int)$inGame->id : 0;
                    $this->data = ['auth_key' => $user->auth_key, 'table_id' => $table_id];
                }else{
                    $this->error = "No correct email or password, or such e-mail has been isset!";
                } // if-else $user->validatePassword
            }else {
                $user = new User();
                $user->email = $userForm->email;
                $user->generatePassHash($userForm->password);
                $user->generateAuthKey();

                if ($user->save()) {
                    $this->status = true;
                    $this->data = $user->auth_key;
                } else {
                    $this->error = $user->getErrors();
                } // if-else $user->save
            }// if-else $user
        }else{
            $this->error = $userForm->getFirstError("email");
        } // if-else $userForm->validate

        return [
            "status" => $this->status,
            "data"   => $this->data,
            "error"  => $this->error
        ];
    } // actionSignup

    public function actionSignin($authKey){

        $user = User::findByAuthKey($authKey);

        if($user){
            $this->status = true;
            $inGame = Game::findBySql("SELECT t.id FROM games g JOIN tables t ON t.id = g.table_id WHERE t.type > 0 AND user_id = $user->id")->one();
            $this->data = (int)$inGame->id;
        }else{
            $this->error = "Not found auth key";
        } // if-else $user

        return [
            "status" => $this->status,
            "data"   => $this->data,
            "error"  => $this->error
        ];
    } // actionSignin
} // UserController