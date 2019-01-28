<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $email
 * @property string $password
 * @property string $auth_key
 */
class User extends ActiveRecord implements IdentityInterface
{
  /**
   * @inheritdoc
   * @return string
   */
  public static function tableName()
  {
    return "users";
  }

  /**
   * @inheritdoc
   * @return array
   */
  public function rules()
  {
    return [
      [['email', 'password'], 'required'],
      [['auth_key'], 'string', 'max' => 20],
    ];
  }

  /**
   * @inheritdoc
   */
  public function attributeLabels()
  {
    return [
      'id' => 'ID',
      'email' => 'Email',
      'password' => 'Password',
      'auth_key' => 'Authorization Key',
    ];
  }

  /**
   * Finds an identity by the given ID.
   *
   * @param string|int $id the ID to be looked for
   * @return IdentityInterface|null the identity object that matches the given ID.
   */
  public static function findIdentity($id)
  {
    return static::findOne($id);
  }

  /**
   * Finds user by username
   *
   * @param string $username
   * @return static|null
   */
  public static function findByUsername($username)
  {
    return static::findOne(["email" => $username]);
  }

  /**
   * Finds user by email
   *
   * @param string $email
   * @return static|null
   */
  public static function findByEmail($email)
  {
    return static::findOne(["email" => $email]);
  }

  /**
   * Finds user by authKey
   *
   * @param string $authKey
   * @return static|null
   */
  public static function findByAuthKey($authKey)
  {
    return static::findOne(["auth_key" => $authKey]);
  }


  /**
   * Finds an identity by the given token.
   *
   * @param string $token the token to be looked for
   * @return IdentityInterface|null the identity object that matches the given token.
   */
  public static function findIdentityByAccessToken($token, $type = null)
  {
//        return static::findOne(['access_token' => $token]);
  }

  /**
   * @return int|string current user ID
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @return string current user auth key
   */
  public function getAuthKey()
  {
    return $this->auth_key;
  }

  /**
   * Generate auth key for new user
   * @throws \yii\base\Exception
   */
  public function generateAuthKey()
  {
    return $this->auth_key = \Yii::$app->security->generateRandomString(20);
  }

  /**
   * @param string $authKey
   * @return bool if auth key is valid for current user
   */
  public function validateAuthKey($authKey)
  {
//        return $this->getAuthKey() === $authKey;
  }

  /**
   * Validates password
   *
   * @param string $password password to validate
   * @return bool if password provided is valid for current user
   */
  public function validatePassword($password)
  {
    return \Yii::$app->security->validatePassword($password,$this->password);
  }
}