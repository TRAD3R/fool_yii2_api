<?php
namespace app\models;

use yii\db\ActiveRecord;

class Game extends ActiveRecord
{
  /**
   * @return string
   */
  public static function tableName()
  {
    return 'games';
  }

  /**
   * @param $id
   * @return mixed
   */
  public static function findById($id){
    return static::findOne($id);
  }
}