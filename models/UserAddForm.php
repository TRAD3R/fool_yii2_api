<?php
/**
 * Created by PhpStorm.
 * User: trad3r
 * Date: 28.01.19
 * Time: 19:38
 */

namespace app\models;


use yii\base\Model;

class UserAddForm extends Model
{
    public $email;
    public $password;
    public $authKey;

    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            ['email', 'email'],
        ];
    }
}