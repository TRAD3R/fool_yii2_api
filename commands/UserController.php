<?php
/**
 * Created by PhpStorm.
 * User: TRAD3R
 * Date: 31/10/2018
 * Time: 17:32
 */
namespace app\commands;

use app\models\User;
use yii\validators\EmailValidator;
use yii\web\Response;

class UserController extends CommonController
{
  /**
   * @param null $request
   * @return Response
   * @throws \yii\base\Exception
   */
  public function actionRegistration($request, $clientId){
    if(!empty($request->email) && !empty($request->password)){
      $validator = new EmailValidator();
      if($validator->validate($request->email)) {
        $user = User::findByEmail($request->email);
        if (empty($user)) {
          $user = new User();
          $user->email = $request->email;
          $user->password = \Yii::$app->getSecurity()->generatePasswordHash($request->password);
          $user->generateAuthKey();
          if($user->save()){
            $this->status = true;
            $this->data = $user->auth_key;
          }else{
            $this->error = $user->getErrors();
          } // if $user->save
        } else {
          $this->data = 'Such email exists in app';
        } //
      }else{
        $this->data = 'Check your email for correctness';
      }// $validator
    }else{
      $this->data = "No email or password is specified";
    } // if $request->email && $request->password

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
  } // actionRegistration

  /**
   * @param null $request
   * @return Response
   */
  public function actionAuth($request, $clientId){
    if(!empty($request->authKey)){
      $user = User::findByAuthKey($request->authKey);
      if(!empty($user)){
        $this->status = true;
      }else{
        $this->data = 'User no found';
      }
    }else{
      $validator = new EmailValidator();
      if($validator->validate($request->email)){
        $user = User::findByEmail($request->email);
        if(!empty($user) && $user->validatePassword($request->password)){
          $this->status = true;
          $this->data = $user->auth_key;
        }else{
          $this->data = 'Wrong email or password';
        } // if !empty($user)
      }else{
        $this->data = 'Check your email for correctness';
      } // if $validator
    } // if $request->auth

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
  } // actionAuth

}