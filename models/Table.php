<?php
namespace app\models;

use yii\db\ActiveRecord;

class Table extends ActiveRecord
{
  /**
   * @return string
   */
  public static function tableName()
  {
    return "tables";
  } // tableName

  /**
   * @param $id
   * @return null|object
   */
  public static function findById($id){
    return static::findOne($id);
  } // findById
} // Table