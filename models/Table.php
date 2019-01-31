<?php
namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "tables".
 *
 * @property int $id
 * @property int $type - state of table (0 - closed, 1- open)
 * @property int $limit_players - max users at the table
 * @property int $created - time of created table (in UNIX)
 */
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

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        $this->created = time();
        return true;
    }
} // Table