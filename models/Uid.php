<?php
/**
 * Created by PhpStorm.
 * User: smirnovrm
 * Date: 01.03.2018
 * Time: 12:30
 */

namespace fedornabilkin\binds\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Class Uid
 * @package fedornabilkin\models
 */
class Uid extends ActiveRecord
{
    CONST STATUS_PUBLISHED = 1;
    CONST STATUS_DRAFT = 2;
    CONST STATUS_DELETED = 3;

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'TimestampBehavior' => [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],

        ]);
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }
    public static function tableName()
    {
        return '{{%bind_uids}}';
    }

    public static function getStatusId($id = 0)
    {
        $arr = [

        ];
    }

}