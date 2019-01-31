<?php
namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "games".
 *
 * @property int $id
 * @property int $table_id
 * @property int $user_id
 */
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

    /**
     * @param $tableId
     * @return int
     */
  public static function getPlayersInGame($tableId){
      return (int)static::find()
          ->where(["table_id" => $tableId])
          ->count();
  }
} // Game